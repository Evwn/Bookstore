<?php
require_once __DIR__ . '/db.php';

try {
    // Read the schema file
    $schema = file_get_contents(__DIR__ . '/sqlite_schema.sql');

    // Remove SQL comments
    $schema = preg_replace('/--.*$/m', '', $schema); // Remove -- comments
    $schema = preg_replace('/\/\*.*?\*\//s', '', $schema); // Remove /* */ comments

    // Split the schema into individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    // Insert sample data if tables are empty
    $stmt = $pdo->query('SELECT COUNT(*) FROM AUTHORS');
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO AUTHORS (NAME, BIOGRAPHY) VALUES ('J.K. Rowling', 'British author best known for the Harry Potter series.')");
        $pdo->exec("INSERT INTO AUTHORS (NAME, BIOGRAPHY) VALUES ('George Orwell', 'English novelist and essayist.')");
    }
    $stmt = $pdo->query('SELECT COUNT(*) FROM BOOKS');
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO BOOKS (TITLE, AUTHOR_ID, ISBN, GENRE, PUBLISHED_YEAR, PRICE, STOCK, EDITION) VALUES ('Harry Potter and the Philosopher''s Stone', 1, '0747532699', 'Fantasy', 1997, 19.99, 100, '1st Edition')");
        $pdo->exec("INSERT INTO BOOKS (TITLE, AUTHOR_ID, ISBN, GENRE, PUBLISHED_YEAR, PRICE, STOCK, EDITION) VALUES ('1984', 2, '0451524934', 'Dystopian', 1949, 14.99, 50, '1st Edition')");
    }

    echo "Database schema loaded successfully!";

} catch (PDOException $e) {
    die("Error loading schema: " . $e->getMessage());
}
?>