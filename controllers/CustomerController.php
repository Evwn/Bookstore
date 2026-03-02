<?php
require_once __DIR__ . '/../db.php';

// Fetch all customers
function getAllCustomers() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM customers ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single customer by ID
function getCustomerById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM customers WHERE customer_id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new customer
function addCustomer($name, $email, $phone, $address) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)');
    return $stmt->execute([$name, $email, $phone, $address]);
}

// Update customer
function updateCustomer($id, $name, $email, $phone, $address) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE customer_id = ?');
    return $stmt->execute([$name, $email, $phone, $address, $id]);
}

// Delete customer
function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM customers WHERE customer_id = ?');
    return $stmt->execute([$id]);
}
