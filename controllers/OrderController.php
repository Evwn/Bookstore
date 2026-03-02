<?php
require_once __DIR__ . '/../db.php';

// Fetch all orders
function getAllOrders() {
    global $pdo;
    $stmt = $pdo->query('SELECT o.*, c.name as customer_name FROM orders o LEFT JOIN customers c ON o.customer_id = c.customer_id ORDER BY o.order_date DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single order by ID
function getOrderById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE order_id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new order
function addOrder($customer_id, $total_amount, $status) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, ?)');
    $stmt->execute([$customer_id, $total_amount, $status]);
    return $pdo->lastInsertId();
}

// Update order
function updateOrder($id, $customer_id, $total_amount, $status) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE orders SET customer_id = ?, total_amount = ?, status = ? WHERE order_id = ?');
    return $stmt->execute([$customer_id, $total_amount, $status, $id]);
}

// Delete order
function deleteOrder($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM orders WHERE order_id = ?');
    return $stmt->execute([$id]);
}
