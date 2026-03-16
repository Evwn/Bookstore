<?php
require_once __DIR__ . '/../db.php';

// Fetch all orders with customer names and item counts
function getAllOrders() {
    global $pdo;
    $stmt = $pdo->query('
        SELECT O.*, C.NAME AS CUSTOMER_NAME, 
               COUNT(OI.ORDER_ITEM_ID) as ITEM_COUNT,
               SUM(OI.QUANTITY) as TOTAL_BOOKS
        FROM ORDERS O 
        LEFT JOIN CUSTOMERS C ON O.CUSTOMER_ID = C.CUSTOMER_ID 
        LEFT JOIN ORDER_ITEMS OI ON O.ORDER_ID = OI.ORDER_ID
        GROUP BY O.ORDER_ID
        ORDER BY O.ORDER_DATE DESC
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single order by ID with customer info
function getOrderById($id) {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT O.*, C.NAME AS CUSTOMER_NAME, C.EMAIL AS CUSTOMER_EMAIL 
        FROM ORDERS O 
        LEFT JOIN CUSTOMERS C ON O.CUSTOMER_ID = C.CUSTOMER_ID 
        WHERE O.ORDER_ID = ?
    ');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch order items for a specific order
function getOrderItems($order_id) {
    global $pdo;
    $stmt = $pdo->prepare('
        SELECT OI.*, B.TITLE, B.ISBN, A.NAME AS AUTHOR_NAME
        FROM ORDER_ITEMS OI
        JOIN BOOKS B ON OI.BOOK_ID = B.BOOK_ID
        LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID
        WHERE OI.ORDER_ID = ?
        ORDER BY OI.ORDER_ITEM_ID
    ');
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add new order with items
function addOrderWithItems($customer_id, $items, $status = 'Pending') {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Calculate total amount
        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        
        // Insert order
        $stmt = $pdo->prepare('INSERT INTO ORDERS (CUSTOMER_ID, TOTAL_AMOUNT, STATUS) VALUES (?, ?, ?)');
        $stmt->execute([$customer_id, $total_amount, $status]);
        $order_id = $pdo->lastInsertId();
        
        // Insert order items
        $stmt = $pdo->prepare('INSERT INTO ORDER_ITEMS (ORDER_ID, BOOK_ID, QUANTITY, PRICE) VALUES (?, ?, ?, ?)');
        foreach ($items as $item) {
            $stmt->execute([$order_id, $item['book_id'], $item['quantity'], $item['price']]);
            
            // Update book stock
            $updateStock = $pdo->prepare('UPDATE BOOKS SET STOCK = STOCK - ? WHERE BOOK_ID = ?');
            $updateStock->execute([$item['quantity'], $item['book_id']]);
        }
        
        $pdo->commit();
        return $order_id;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Add new simple order (for backward compatibility)
function addOrder($customer_id, $total_amount, $status) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO ORDERS (CUSTOMER_ID, TOTAL_AMOUNT, STATUS) VALUES (?, ?, ?)');
    $stmt->execute([$customer_id, $total_amount, $status]);
    return $pdo->lastInsertId();
}

// Update order
function updateOrder($id, $customer_id, $total_amount, $status) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE ORDERS SET CUSTOMER_ID = ?, TOTAL_AMOUNT = ?, STATUS = ? WHERE ORDER_ID = ?');
    return $stmt->execute([$customer_id, $total_amount, $status, $id]);
}

// Update order status only
function updateOrderStatus($id, $status) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE ORDERS SET STATUS = ? WHERE ORDER_ID = ?');
    return $stmt->execute([$status, $id]);
}

// Delete order and its items
function deleteOrder($id) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // First, restore stock for all items in this order
        $items = getOrderItems($id);
        foreach ($items as $item) {
            $updateStock = $pdo->prepare('UPDATE BOOKS SET STOCK = STOCK + ? WHERE BOOK_ID = ?');
            $updateStock->execute([$item['QUANTITY'], $item['BOOK_ID']]);
        }
        
        // Delete order items first (foreign key constraint)
        $stmt = $pdo->prepare('DELETE FROM ORDER_ITEMS WHERE ORDER_ID = ?');
        $stmt->execute([$id]);
        
        // Delete order
        $stmt = $pdo->prepare('DELETE FROM ORDERS WHERE ORDER_ID = ?');
        $result = $stmt->execute([$id]);
        
        $pdo->commit();
        return $result;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Add item to existing order
function addOrderItem($order_id, $book_id, $quantity, $price) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Insert order item
        $stmt = $pdo->prepare('INSERT INTO ORDER_ITEMS (ORDER_ID, BOOK_ID, QUANTITY, PRICE) VALUES (?, ?, ?, ?)');
        $stmt->execute([$order_id, $book_id, $quantity, $price]);
        
        // Update order total
        $updateTotal = $pdo->prepare('
            UPDATE ORDERS SET TOTAL_AMOUNT = (
                SELECT SUM(QUANTITY * PRICE) FROM ORDER_ITEMS WHERE ORDER_ID = ?
            ) WHERE ORDER_ID = ?
        ');
        $updateTotal->execute([$order_id, $order_id]);
        
        // Update book stock
        $updateStock = $pdo->prepare('UPDATE BOOKS SET STOCK = STOCK - ? WHERE BOOK_ID = ?');
        $updateStock->execute([$quantity, $book_id]);
        
        $pdo->commit();
        return true;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Remove item from order
function removeOrderItem($order_item_id) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Get item details first
        $stmt = $pdo->prepare('SELECT * FROM ORDER_ITEMS WHERE ORDER_ITEM_ID = ?');
        $stmt->execute([$order_item_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            // Restore stock
            $updateStock = $pdo->prepare('UPDATE BOOKS SET STOCK = STOCK + ? WHERE BOOK_ID = ?');
            $updateStock->execute([$item['QUANTITY'], $item['BOOK_ID']]);
            
            // Delete item
            $stmt = $pdo->prepare('DELETE FROM ORDER_ITEMS WHERE ORDER_ITEM_ID = ?');
            $stmt->execute([$order_item_id]);
            
            // Update order total
            $updateTotal = $pdo->prepare('
                UPDATE ORDERS SET TOTAL_AMOUNT = (
                    SELECT COALESCE(SUM(QUANTITY * PRICE), 0) FROM ORDER_ITEMS WHERE ORDER_ID = ?
                ) WHERE ORDER_ID = ?
            ');
            $updateTotal->execute([$item['ORDER_ID'], $item['ORDER_ID']]);
        }
        
        $pdo->commit();
        return true;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Get available books for order creation
function getAvailableBooks() {
    global $pdo;
    $stmt = $pdo->query('
        SELECT B.*, A.NAME AS AUTHOR_NAME 
        FROM BOOKS B 
        LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID 
        WHERE B.STOCK > 0 
        ORDER BY B.TITLE
    ');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
