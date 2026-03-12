<?php
require_once __DIR__ . '/db.php';

try {
    // Read the schema file
    $schema = file_get_contents(__DIR__ . '/sqlite_schema.sql');

    // Split the schema into individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));

    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) { // Skip comments and empty lines
            $pdo->exec($statement);
        }
    }

    echo "Database schema loaded successfully!";

} catch (PDOException $e) {
    die("Error loading schema: " . $e->getMessage());
}
?>