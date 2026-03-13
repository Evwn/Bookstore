
<?php

try {
    $databasePath = __DIR__ . '/bookstore.db';
    $pdo = new PDO('sqlite:bookstore.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>