<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/BookController.php'; // getAllBooks, addBook, updateBook, deleteBook, searchBooks, updateBookStock

// ── System Reset ──────────────────────────────
function resetSystem() {
    global $pdo;
    try {
        $pdo->beginTransaction();
        $pdo->exec('DELETE FROM ORDER_ITEMS');
        $pdo->exec('DELETE FROM ORDERS');
        $pdo->exec('DELETE FROM BOOKS');
        $pdo->exec('DELETE FROM AUTHORS');
        $pdo->exec('DELETE FROM CUSTOMERS');
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name IN ('AUTHORS','BOOKS','CUSTOMERS','ORDERS','ORDER_ITEMS')");
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("resetSystem error: " . $e->getMessage());
        return false;
    }
}

// // ── Author Functions ──────────────────────────
// function addAuthor($name, $biography = '') {
//     global $pdo;
//     try {
//         $stmt = $pdo->prepare('INSERT INTO AUTHORS (NAME, BIOGRAPHY) VALUES (?, ?)');
//         return $stmt->execute([trim($name), trim($biography)]);
//     } catch (Exception $e) {
//         error_log("addAuthor error: " . $e->getMessage());
//         return false;
//     }
// }

// function getAllAuthors() {
//     global $pdo;
//     try {
//         return $pdo->query('SELECT * FROM AUTHORS ORDER BY NAME')->fetchAll();
//     } catch (Exception $e) {
//         error_log("getAllAuthors error: " . $e->getMessage());
//         return [];
//     }
// }

// function getAuthorById($id) {
//     global $pdo;
//     try {
//         $stmt = $pdo->prepare('SELECT * FROM AUTHORS WHERE AUTHOR_ID = ?');
//         $stmt->execute([$id]);
//         return $stmt->fetch();
//     } catch (Exception $e) {
//         error_log("getAuthorById error: " . $e->getMessage());
//         return false;
//     }
// }

// ── Book Details (with order history) ────────
function getBookDetails($book_id) {
    global $pdo;
    try {
        $book = getBookById($book_id);
        if (!$book) return false;

        $stmt = $pdo->prepare('
            SELECT OI.*, O.ORDER_ID
            FROM ORDER_ITEMS OI
            JOIN ORDERS O ON OI.ORDER_ID = O.ORDER_ID
            WHERE OI.BOOK_ID = ?
        ');
        $stmt->execute([$book_id]);
        $book['ORDER_ITEMS'] = $stmt->fetchAll();
        return $book;
    } catch (Exception $e) {
        error_log("getBookDetails error: " . $e->getMessage());
        return false;
    }
}

// ── Author Details ────────────────────────────
function getAuthorDetails($author_id) {
    global $pdo;
    try {
        $author = getAuthorById($author_id);
        if (!$author) return false;

        $stmt = $pdo->prepare('SELECT * FROM BOOKS WHERE AUTHOR_ID = ? ORDER BY TITLE');
        $stmt->execute([$author_id]);
        $author['BOOKS'] = $stmt->fetchAll();
        return $author;
    } catch (Exception $e) {
        error_log("getAuthorDetails error: " . $e->getMessage());
        return false;
    }
}

// ── Display Source ────────────────────────────
function displaySource($interface, $password) {
    $correct_password = "bookstore2024";
    if ($password !== $correct_password) {
        return ['success' => false, 'message' => "Wrong password: $password"];
    }

    $base = __DIR__ . '/..';
    $map  = [
        1 => ['index.php', 'controllers/AssignmentController.php'],
        2 => ['views/EnterAuthors.php',   'controllers/AssignmentController.php'],
        3 => ['views/EnterBooks.php',     'controllers/AssignmentController.php'],
        4 => ['views/SearchBooks.php',    'controllers/AssignmentController.php'],
        5 => ['views/UpdateQuantity.php', 'controllers/AssignmentController.php'],
        6 => ['views/BookDetails.php',    'controllers/AssignmentController.php'],
        7 => ['views/AuthorDetails.php',  'controllers/AssignmentController.php'],
    ];

    if (!isset($map[$interface])) {
        return ['success' => false, 'message' => "No such interface: $interface"];
    }

    $source = '';
    foreach ($map[$interface] as $file) {
        $path = $base . '/' . $file;
        if (file_exists($path)) {
            $source .= "=== $file ===\n\n" . file_get_contents($path) . "\n\n\n\n\n";
        }
    }
    return ['success' => true, 'source' => $source];
}

// ── System Statistics ─────────────────────────
function getSystemStats() {
    global $pdo;
    try {
        return [
            'authors'         => $pdo->query('SELECT COUNT(*) FROM AUTHORS')->fetchColumn(),
            'books'           => $pdo->query('SELECT COUNT(*) FROM BOOKS')->fetchColumn(),
            'total_sold'      => $pdo->query('SELECT COALESCE(SUM(QUANTITY), 0) FROM ORDER_ITEMS')->fetchColumn(),
            'total_revenue'   => $pdo->query('SELECT COALESCE(SUM(PRICE * QUANTITY), 0) FROM ORDER_ITEMS')->fetchColumn(),
            'total_stock'     => $pdo->query('SELECT COALESCE(SUM(STOCK), 0) FROM BOOKS')->fetchColumn(),
            'total_royalties' => $pdo->query('SELECT COALESCE(SUM(PRICE * QUANTITY), 0) FROM ORDER_ITEMS')->fetchColumn(),
        ];
    } catch (Exception $e) {
        error_log("getSystemStats error: " . $e->getMessage());
        return ['authors' => 0, 'books' => 0, 'total_sold' => 0, 'total_revenue' => 0, 'total_stock' => 0, 'total_royalties' => 0];
    }
}
?>