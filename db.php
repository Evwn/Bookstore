
<?php

try {
    $pdo = new PDO('sqlite:bookstore.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to SQLite successfully";

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>