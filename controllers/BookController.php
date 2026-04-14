<?php
require_once __DIR__ . '/../db.php';

// Fetch all books with proper error handling
function getAllBooks() {
    global $pdo;
    try {
        $stmt = $pdo->query('
            SELECT B.*, A.NAME AS AUTHOR_NAME 
            FROM BOOKS B 
            LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID 
            ORDER BY B.TITLE
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching books: " . $e->getMessage());
        return [];
    }
}

// Fetch single book by ID with proper error handling
function getBookById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare('
            SELECT B.*, A.NAME AS AUTHOR_NAME 
            FROM BOOKS B 
            LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID 
            WHERE B.BOOK_ID = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching book by ID: " . $e->getMessage());
        return false;
    }
}

// Add new book with validation
function addBook($title, $author_id, $isbn = null, $genre = null, $published_year = null, $price = 0, $stock = 0, $edition = null, $cover_url = null, $description = null) {
    global $pdo;
    
    // Validation
    if (empty($title)) {
        return false;
    }
    
    if ($author_id <= 0) {
        return false;
    }
    
    if ($price < 0) {
        return false;
    }
    
    if ($stock < 0) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare('
            INSERT INTO BOOKS (TITLE, AUTHOR_ID, ISBN, GENRE, PUBLISHED_YEAR, PRICE, STOCK, EDITION, COVER_URL, DESCRIPTION) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $ok = $stmt->execute([$title, $author_id, $isbn, $genre, $published_year, $price, $stock, $edition, $cover_url, $description]);
        if ($ok) {
            // create BOOK_AUTHORS mapping for compatibility with default percentage 0.10
            try {
                $bookId = $pdo->lastInsertId();
                $stmt2 = $pdo->prepare('INSERT INTO BOOK_AUTHORS (BOOK_ID, AUTHOR_ID, PERCENTAGE) VALUES (?, ?, ?)');
                $stmt2->execute([$bookId, $author_id, 0.10]);
            } catch (Exception $e) {
                // non-fatal, log and continue
                error_log('BOOK_AUTHORS insert error: ' . $e->getMessage());
            }
        }
        return $ok;
    } catch (Exception $e) {
        error_log("Error adding book: " . $e->getMessage());
        return false;
    }
}

// Update book with validation
function updateBook($id, $title, $author_id, $isbn = null, $genre = null, $published_year = null, $price = 0, $stock = 0, $edition = null, $cover_url = null, $description = null) {
    global $pdo;
    
    // Validation
    if (empty($title)) {
        return false;
    }
    
    if ($author_id <= 0) {
        return false;
    }
    
    if ($price < 0) {
        return false;
    }
    
    if ($stock < 0) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare('
            UPDATE BOOKS 
            SET TITLE = ?, AUTHOR_ID = ?, ISBN = ?, GENRE = ?, PUBLISHED_YEAR = ?, PRICE = ?, STOCK = ?, EDITION = ?, COVER_URL = ?, DESCRIPTION = ? 
            WHERE BOOK_ID = ?
        ');
        $ok = $stmt->execute([$title, $author_id, $isbn, $genre, $published_year, $price, $stock, $edition, $cover_url, $description, $id]);
        if ($ok) {
            // ensure BOOK_AUTHORS mapping exists for this book-author; if not, insert with default percentage
            try {
                $stmtCheck = $pdo->prepare('SELECT COUNT(*) FROM BOOK_AUTHORS WHERE BOOK_ID = ? AND AUTHOR_ID = ?');
                $stmtCheck->execute([$id, $author_id]);
                if ($stmtCheck->fetchColumn() == 0) {
                    $stmtIns = $pdo->prepare('INSERT INTO BOOK_AUTHORS (BOOK_ID, AUTHOR_ID, PERCENTAGE) VALUES (?, ?, ?)');
                    $stmtIns->execute([$id, $author_id, 0.10]);
                }
            } catch (Exception $e) {
                error_log('BOOK_AUTHORS update error: ' . $e->getMessage());
            }
        }
        return $ok;
    } catch (Exception $e) {
        error_log("Error updating book: " . $e->getMessage());
        return false;
    }
}

// Delete book with dependency check
function deleteBook($id) {
    global $pdo;
    
    try {
        // Check if book is referenced in orders
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM ORDER_ITEMS WHERE BOOK_ID = ?');
        $stmt->execute([$id]);
        $orderCount = $stmt->fetchColumn();
        
        if ($orderCount > 0) {
            // Don't delete if book has orders, just set stock to 0
            $stmt = $pdo->prepare('UPDATE BOOKS SET STOCK = 0 WHERE BOOK_ID = ?');
            return $stmt->execute([$id]);
        } else {
            // Safe to delete
            $stmt = $pdo->prepare('DELETE FROM BOOKS WHERE BOOK_ID = ?');
            return $stmt->execute([$id]);
        }
    } catch (Exception $e) {
        error_log("Error deleting book: " . $e->getMessage());
        return false;
    }
}

// Search books by title, author, or ISBN
function searchBooks($query) {
    global $pdo;
    try {
        $searchTerm = '%' . $query . '%';
        $stmt = $pdo->prepare('
            SELECT B.*, A.NAME AS AUTHOR_NAME 
            FROM BOOKS B 
            LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID 
            WHERE B.TITLE LIKE ? OR A.NAME LIKE ? OR B.ISBN LIKE ?
            ORDER BY B.TITLE
        ');
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error searching books: " . $e->getMessage());
        return [];
    }
}

// Get books with low stock
function getLowStockBooks($threshold = 5) {
    global $pdo;
    try {
        $stmt = $pdo->prepare('
            SELECT B.*, A.NAME AS AUTHOR_NAME 
            FROM BOOKS B 
            LEFT JOIN AUTHORS A ON B.AUTHOR_ID = A.AUTHOR_ID 
            WHERE B.STOCK <= ?
            ORDER BY B.STOCK ASC, B.TITLE
        ');
        $stmt->execute([$threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching low stock books: " . $e->getMessage());
        return [];
    }
}

// Update book stock
function updateBookStock($id, $stock) {
    global $pdo;
    
    if ($stock < 0) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare('UPDATE BOOKS SET STOCK = ? WHERE BOOK_ID = ?');
        return $stmt->execute([$stock, $id]);
    } catch (Exception $e) {
        error_log("Error updating book stock: " . $e->getMessage());
        return false;
    }
}
?>