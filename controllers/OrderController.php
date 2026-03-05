<?php
require_once __DIR__ . '/../db.php';

// Fetch all orders
function getAllOrders() {
    global $pdo;
    $stmt = $pdo->query('SELECT O.*, C.NAME AS CUSTOMER_NAME FROM ORDERS O LEFT JOIN CUSTOMERS C ON O.CUSTOMER_ID = C.CUSTOMER_ID ORDER BY O.ORDER_DATE DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single order by ID
function getOrderById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM ORDERS WHERE ORDER_ID = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new order
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

// Delete order
function deleteOrder($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM ORDERS WHERE ORDER_ID = ?');
    return $stmt->execute([$id]);
}
