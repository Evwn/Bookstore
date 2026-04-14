<?php
require_once __DIR__ . '/../db.php';

// Fetch all authors 
function getAllAuthors() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM AUTHORS ORDER BY NAME');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch single author by ID
function getAuthorById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM AUTHORS WHERE AUTHOR_ID = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Add new author
function addAuthor($name, $biography) {
    // Log input to a file for debugging
    $logMsg = date('Y-m-d H:i:s') . " | addAuthor called | name: $name | biography: $biography\n";
    file_put_contents(__DIR__ . '/../author_debug.log', $logMsg, FILE_APPEND);
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO AUTHORS (NAME, BIOGRAPHY) VALUES (?, ?)');
    return $stmt->execute([$name, $biography]);
}

// Update author
function updateAuthor($id, $name, $biography) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE AUTHORS SET NAME = ?, BIOGRAPHY = ? WHERE AUTHOR_ID = ?');
    return $stmt->execute([$name, $biography, $id]);
}

// Delete author
function deleteAuthor($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM AUTHORS WHERE AUTHOR_ID = ?');
    return $stmt->execute([$id]);
}

// Get books contributed by an author (supports BOOK_AUTHORS many-to-many and legacy BOOKS.AUTHOR_ID)
function getBooksByAuthor($author_id) {
    global $pdo;
    try {
        // First, get books via BOOK_AUTHORS mapping
        $stmt = $pdo->prepare('SELECT B.* FROM BOOKS B JOIN BOOK_AUTHORS BA ON B.BOOK_ID = BA.BOOK_ID WHERE BA.AUTHOR_ID = ?');
        $stmt->execute([$author_id]);
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Include legacy single-author books that may not have an entry in BOOK_AUTHORS
        $stmt2 = $pdo->prepare('SELECT * FROM BOOKS WHERE AUTHOR_ID = ? AND BOOK_ID NOT IN (SELECT BOOK_ID FROM BOOK_AUTHORS)');
        $stmt2->execute([$author_id]);
        $legacy = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        foreach ($legacy as $b) { $books[] = $b; }

        return $books;
    } catch (Exception $e) {
        error_log('Error fetching books by author: ' . $e->getMessage());
        return [];
    }
}

// Calculate total royalties for an author using formula:
// royalty = Σ(price × percentage × quantity_sold ÷ author_count) for all books contributed by the author
function getAuthorRoyalty($author_id) {
    global $pdo;
    $result = ['total' => 0.0, 'per_book' => []];
    try {
        // Get contributions from BOOK_AUTHORS
        $stmt = $pdo->prepare('SELECT BA.BOOK_ID, BA.PERCENTAGE, (SELECT COUNT(*) FROM BOOK_AUTHORS WHERE BOOK_ID = BA.BOOK_ID) AS author_count FROM BOOK_AUTHORS BA WHERE BA.AUTHOR_ID = ?');
        $stmt->execute([$author_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add legacy single-author books not present in BOOK_AUTHORS
        $stmt2 = $pdo->prepare('SELECT BOOK_ID FROM BOOKS WHERE AUTHOR_ID = ? AND BOOK_ID NOT IN (SELECT BOOK_ID FROM BOOK_AUTHORS)');
        $stmt2->execute([$author_id]);
        $legacy = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        foreach ($legacy as $r) {
            $rows[] = ['BOOK_ID' => $r['BOOK_ID'], 'PERCENTAGE' => 0.10, 'author_count' => 1];
        }

        foreach ($rows as $r) {
            $book_id = $r['BOOK_ID'];
            $percentage = floatval($r['PERCENTAGE']);
            $author_count = max(1, intval($r['author_count'] ?? 1));

            // Get total quantity sold and revenue from ORDER_ITEMS
            $stmt3 = $pdo->prepare('SELECT SUM(QUANTITY) AS qty, SUM(PRICE * QUANTITY) AS revenue FROM ORDER_ITEMS WHERE BOOK_ID = ?');
            $stmt3->execute([$book_id]);
            $s = $stmt3->fetch(PDO::FETCH_ASSOC);
            $qty = intval($s['qty'] ?? 0);
            if ($qty <= 0) {
                $royalty_amt = 0.0;
            } else {
                // Determine unit price: prefer recorded order revenue, else fallback to BOOKS.PRICE
                if (!empty($s['revenue'])) {
                    $unit_price = floatval($s['revenue']) / max(1, $qty);
                } else {
                    $stmt4 = $pdo->prepare('SELECT PRICE, TITLE FROM BOOKS WHERE BOOK_ID = ?');
                    $stmt4->execute([$book_id]);
                    $b = $stmt4->fetch(PDO::FETCH_ASSOC);
                    $unit_price = floatval($b['PRICE'] ?? 0.0);
                }
                $royalty_amt = ($unit_price * $percentage * $qty) / $author_count;
            }
            $result['total'] += $royalty_amt;
            $result['per_book'][] = ['book_id' => $book_id, 'royalty' => $royalty_amt];
        }
    } catch (Exception $e) {
        error_log('Error calculating royalty: ' . $e->getMessage());
    }
    return $result;
}

// Get top authors by royalty total. Returns array of ['author' => author_row, 'royalty' => total]
function getTopAuthors($limit = 5) {
    $authors = getAllAuthors();
    $list = [];
    foreach ($authors as $a) {
        $r = getAuthorRoyalty($a['AUTHOR_ID']);
        $list[] = ['author' => $a, 'royalty' => floatval($r['total'] ?? 0.0)];
    }
    usort($list, function($x, $y) { return $y['royalty'] <=> $x['royalty']; });
    return array_slice($list, 0, max(0, intval($limit)));
}
