<?php
require_once __DIR__ . '/db.php';

try {
    $pdo->exec("INSERT INTO BOOKS (TITLE, AUTHOR_ID, ISBN, GENRE, PUBLISHED_YEAR, PRICE, STOCK, EDITION) VALUES ('Harry Potter and the Philosopher''s Stone', 1, '0747532699', 'Fantasy', 1997, 19.99, 100, '1st Edition')");
    $pdo->exec("INSERT INTO BOOKS (TITLE, AUTHOR_ID, ISBN, GENRE, PUBLISHED_YEAR, PRICE, STOCK, EDITION) VALUES ('1984', 2, '0451524934', 'Dystopian', 1949, 14.99, 50, '1st Edition')");
    echo "Books inserted successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>