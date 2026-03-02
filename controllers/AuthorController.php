<?php
require_once __DIR__ . '/../db.php';

// Fetch all authors 
function getAllAuthors() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM authors ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single author by ID
function getAuthorById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM authors WHERE author_id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new author
function addAuthor($name, $biography) {
    // Log input to a file for debugging
    $logMsg = date('Y-m-d H:i:s') . " | addAuthor called | name: $name | biography: $biography\n";
    file_put_contents(__DIR__ . '/../author_debug.log', $logMsg, FILE_APPEND);
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO authors (name, biography) VALUES (?, ?)');
    return $stmt->execute([$name, $biography]);
}

// Update author
function updateAuthor($id, $name, $biography) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE authors SET name = ?, biography = ? WHERE author_id = ?');
    return $stmt->execute([$name, $biography, $id]);
}

// Delete author
function deleteAuthor($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM authors WHERE author_id = ?');
    return $stmt->execute([$id]);
}
