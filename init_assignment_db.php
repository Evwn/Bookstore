<?php
// Initialize database for assignment requirements
try {
    // Create/connect to SQLite database
    $pdo = new PDO('sqlite:bookstore_assignment.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute schema
    $schema = file_get_contents('assignment_schema.sql');
    $pdo->exec($schema);
    
    echo "Assignment database initialized successfully!\n";
    echo "Database file: bookstore_assignment.db\n";
    
} catch (Exception $e) {
    echo "Error initializing database: " . $e->getMessage() . "\n";
}
?>