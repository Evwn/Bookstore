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

    // Ensure BOOK_AUTHORS mapping exists for existing BOOKS (migrate single-author books)
    try {
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='BOOK_AUTHORS'");
        if ($stmt && $stmt->fetchColumn()) {
            // Insert mappings for any books that have AUTHOR_ID set but no entry in BOOK_AUTHORS
            $pdo->exec("INSERT INTO BOOK_AUTHORS (BOOK_ID, AUTHOR_ID, PERCENTAGE)
                        SELECT BOOK_ID, AUTHOR_ID, 0.10 FROM BOOKS WHERE AUTHOR_ID IS NOT NULL
                        AND BOOK_ID NOT IN (SELECT BOOK_ID FROM BOOK_AUTHORS)");
        }
    } catch (Exception $e) {
        // Non-fatal, continue
        error_log('BOOK_AUTHORS migration error: ' . $e->getMessage());
    }

    // -- Additional guarded migrations for production safety --
    try {
        // Add new columns to BOOK_AUTHORS if they don't exist: ROLE, CONTRIBUTION_PERCENTAGE
        $cols = $pdo->query("PRAGMA table_info('BOOK_AUTHORS')")->fetchAll(PDO::FETCH_ASSOC);
        $colNames = array_column($cols, 'name');
        if (!in_array('ROLE', $colNames)) {
            $pdo->exec("ALTER TABLE BOOK_AUTHORS ADD COLUMN ROLE TEXT DEFAULT 'author'");
        }
        if (!in_array('CONTRIBUTION_PERCENTAGE', $colNames)) {
            // keep legacy PERCENTAGE but add CONTRIBUTION_PERCENTAGE for clarity
            $pdo->exec("ALTER TABLE BOOK_AUTHORS ADD COLUMN CONTRIBUTION_PERCENTAGE REAL DEFAULT 0.10");
            // Backfill from PERCENTAGE where present
            $pdo->exec("UPDATE BOOK_AUTHORS SET CONTRIBUTION_PERCENTAGE = PERCENTAGE WHERE PERCENTAGE IS NOT NULL");
        }

        // Ensure AUTHOR_STATS and ROYALTY_TRANSACTIONS exist (schema file already creates IF NOT EXISTS)
        $pdo->exec("CREATE TABLE IF NOT EXISTS AUTHOR_STATS (
            AUTHOR_ID INTEGER PRIMARY KEY,
            TOTAL_ROYALTY REAL NOT NULL DEFAULT 0.0,
            LAST_CALC_AT TEXT DEFAULT CURRENT_TIMESTAMP,
            PROJECTED_YEAR REAL DEFAULT 0.0,
            FOREIGN KEY (AUTHOR_ID) REFERENCES AUTHORS(AUTHOR_ID)
        )");

        $pdo->exec("CREATE TABLE IF NOT EXISTS ROYALTY_TRANSACTIONS (
            TRANSACTION_ID INTEGER PRIMARY KEY AUTOINCREMENT,
            AUTHOR_ID INTEGER NOT NULL,
            BOOK_ID INTEGER,
            ORDER_ID INTEGER,
            AMOUNT REAL NOT NULL,
            CREATED_AT TEXT DEFAULT CURRENT_TIMESTAMP,
            STATUS TEXT DEFAULT 'Pending',
            NOTE TEXT,
            FOREIGN KEY (AUTHOR_ID) REFERENCES AUTHORS(AUTHOR_ID),
            FOREIGN KEY (BOOK_ID) REFERENCES BOOKS(BOOK_ID),
            FOREIGN KEY (ORDER_ID) REFERENCES ORDERS(ORDER_ID)
        )");

    } catch (Exception $e) {
        error_log('Post-schema migration error: ' . $e->getMessage());
    }

    echo "Database schema loaded successfully!";

} catch (PDOException $e) {
    die("Error loading schema: " . $e->getMessage());
}
?>