<?php
require_once __DIR__ . '/../db.php';

// Fetch all books
function getAllBooks() {
    global $pdo;
    $stmt = $pdo->query('SELECT b.*, a.name as author_name FROM books b LEFT JOIN authors a ON b.author_id = a.author_id ORDER BY b.title');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single book by ID
function getBookById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT b.*, a.name as author_name FROM books b LEFT JOIN authors a ON b.author_id = a.author_id WHERE b.book_id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new book
function addBook($title, $author_id, $genre, $published_year, $price, $stock, $edition = null, $cover_url = null, $description = null) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO books (title, author_id, genre, published_year, price, stock, edition, cover_url, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    return $stmt->execute([$title, $author_id, $genre, $published_year, $price, $stock, $edition, $cover_url, $description]);
}

// Update book
function updateBook($id, $title, $author_id, $genre, $published_year, $price, $stock, $edition = null, $cover_url = null, $description = null) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE books SET title = ?, author_id = ?, genre = ?, published_year = ?, price = ?, stock = ?, edition = ?, cover_url = ?, description = ? WHERE book_id = ?');
    return $stmt->execute([$title, $author_id, $genre, $published_year, $price, $stock, $edition, $cover_url, $description, $id]);
}

// Delete book
function deleteBook($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM books WHERE book_id = ?');
    return $stmt->execute([$id]);
}
