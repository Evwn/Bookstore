<?php
require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM BOOKS");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "BOOKS table:\n";
    foreach ($books as $book) {
        print_r($book);
        echo "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>