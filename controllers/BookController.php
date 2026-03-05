<?php
require_once __DIR__ . '/../db.php';

// Fetch all books
function getAllBooks() {
    global $pdo;
    $stmt = $pdo->query('SELECT B.*, A.NAME AS AUTHOR_NAME FROM BOOKS B LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID ORDER BY B.TITLE');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single book by ID
function getBookById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT B.*, A.NAME AS AUTHOR_NAME FROM BOOKS B LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID WHERE B.BOOK_ID = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new book
function addBook($title, $author_id, $genre, $published_year, $price, $stock, $edition = null, $cover_url = null, $description = null) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO BOOKS (TITLE, AUTHOR_ID, GENRE, PUBLISHED_YEAR, PRICE, STOCK, EDITION, COVER_URL, DESCRIPTION) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    return $stmt->execute([$title, $author_id, $genre, $published_year, $price, $stock, $edition, $cover_url, $description]);
}

// Update book
function updateBook($id, $title, $author_id, $genre, $published_year, $price, $stock, $edition = null, $cover_url = null, $description = null) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE BOOKS SET TITLE = ?, AUTHOR_ID = ?, GENRE = ?, PUBLISHED_YEAR = ?, PRICE = ?, STOCK = ?, EDITION = ?, COVER_URL = ?, DESCRIPTION = ? WHERE BOOK_ID = ?');
    return $stmt->execute([$title, $author_id, $genre, $published_year, $price, $stock, $edition, $cover_url, $description, $id]);
}

// Delete book
function deleteBook($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM BOOKS WHERE BOOK_ID = ?');
    return $stmt->execute([$id]);
}
