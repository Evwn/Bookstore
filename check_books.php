<?php
require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query("SELECT b.book_id, b.title, b.isbn, b.stock, b.cover_url, a.name AS author_name FROM books b LEFT JOIN authors a ON a.author_id = b.author_id ORDER BY b.book_id DESC");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Books found: " . count($books) . "\n";
    foreach ($books as $book) {
        echo "ID: " . ($book['book_id'] ?? 'null') . ", Title: " . ($book['title'] ?? 'null') . ", Author: " . ($book['author_name'] ?? 'null') . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>