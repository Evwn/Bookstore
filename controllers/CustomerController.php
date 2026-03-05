<?php
require_once __DIR__ . '/../db.php';

// Fetch all customers
function getAllCustomers() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM CUSTOMERS ORDER BY NAME');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single customer by ID
function getCustomerById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM CUSTOMERS WHERE CUSTOMER_ID = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new customer
function addCustomer($name, $email, $phone, $address) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO CUSTOMERS (NAME, EMAIL, PHONE, ADDRESS) VALUES (?, ?, ?, ?)');
    return $stmt->execute([$name, $email, $phone, $address]);
}

// Update customer
function updateCustomer($id, $name, $email, $phone, $address) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE CUSTOMERS SET NAME = ?, EMAIL = ?, PHONE = ?, ADDRESS = ? WHERE CUSTOMER_ID = ?');
    return $stmt->execute([$name, $email, $phone, $address, $id]);
}

// Delete customer
function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM CUSTOMERS WHERE CUSTOMER_ID = ?');
    return $stmt->execute([$id]);
}
