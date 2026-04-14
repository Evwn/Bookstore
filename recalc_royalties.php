<?php
// CLI/web script to recompute royalties and populate AUTHOR_STATS.
// Usage (CLI): php recalc_royalties.php [--create-transactions]
// Safe for production: idempotent checks prevent duplicate transactions for same-day deltas.

require_once __DIR__ . '/db.php';

$createTransactions = false;
if (php_sapi_name() === 'cli') {
    $argvs = $_SERVER['argv'];
    foreach ($argvs as $a) {
        if ($a === '--create-transactions' || $a === '-p') $createTransactions = true;
    }
}

// Aggregation query: compute total royalties per author using BOOK_AUTHORS.CONTRIBUTION_PERCENTAGE when available,
// fall back to PERCENTAGE column.
$sql = "SELECT
  ba.AUTHOR_ID,
  SUM((oi.PRICE * oi.QUANTITY) * COALESCE(ba.CONTRIBUTION_PERCENTAGE, ba.PERCENTAGE, 0.10) / CASE WHEN bac.author_count IS NULL OR bac.author_count = 0 THEN 1 ELSE bac.author_count END) AS total_royalty
FROM ORDER_ITEMS oi
JOIN BOOK_AUTHORS ba ON oi.BOOK_ID = ba.BOOK_ID
LEFT JOIN (SELECT BOOK_ID, COUNT(*) AS author_count FROM BOOK_AUTHORS GROUP BY BOOK_ID) bac ON bac.BOOK_ID = ba.BOOK_ID
GROUP BY ba.AUTHOR_ID";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $now = date('c');
    $updated = 0;
    foreach ($rows as $r) {
        $author_id = intval($r['AUTHOR_ID']);
        $total = floatval($r['total_royalty'] ?? 0.0);

        // Fetch existing stat
        $s = $pdo->prepare('SELECT TOTAL_ROYALTY FROM AUTHOR_STATS WHERE AUTHOR_ID = ?');
        $s->execute([$author_id]);
        $existing = $s->fetchColumn();
        $existing = $existing === false ? null : floatval($existing);

        // Upsert AUTHOR_STATS
        if ($existing === null) {
            $ins = $pdo->prepare('INSERT INTO AUTHOR_STATS (AUTHOR_ID, TOTAL_ROYALTY, LAST_CALC_AT) VALUES (?, ?, ?)');
            $ins->execute([$author_id, $total, $now]);
        } else {
            $upd = $pdo->prepare('UPDATE AUTHOR_STATS SET TOTAL_ROYALTY = ?, LAST_CALC_AT = ? WHERE AUTHOR_ID = ?');
            $upd->execute([$total, $now, $author_id]);
        }
        $updated++;

        // Optionally create a royalty transaction for positive delta since last stored value
        if ($createTransactions && $existing !== null) {
            $delta = $total - $existing;
            // small tolerance to avoid noise
            if ($delta > 0.005) {
                // Idempotency: check if a transaction with same author and amount exists today
                $check = $pdo->prepare("SELECT COUNT(*) FROM ROYALTY_TRANSACTIONS WHERE AUTHOR_ID = ? AND ABS(AMOUNT - ?) < 0.0001 AND date(CREATED_AT) = date('now')");
                $check->execute([$author_id, $delta]);
                $cnt = intval($check->fetchColumn() ?? 0);
                if ($cnt === 0) {
                    $insTx = $pdo->prepare('INSERT INTO ROYALTY_TRANSACTIONS (AUTHOR_ID, AMOUNT, NOTE, STATUS) VALUES (?, ?, ?, ?)');
                    $insTx->execute([$author_id, $delta, 'Auto-created delta from recalc_royalties', 'Pending']);
                }
            }
        }
    }

    // Also ensure authors with no ORDER_ITEMS but present in authors table have AUTHOR_STATS row (0 total)
    $allAuthors = $pdo->query('SELECT AUTHOR_ID FROM AUTHORS')->fetchAll(PDO::FETCH_COLUMN);
    foreach ($allAuthors as $aid) {
        $aid = intval($aid);
        $s = $pdo->prepare('SELECT 1 FROM AUTHOR_STATS WHERE AUTHOR_ID = ?');
        $s->execute([$aid]);
        if (!$s->fetchColumn()) {
            $ins = $pdo->prepare('INSERT INTO AUTHOR_STATS (AUTHOR_ID, TOTAL_ROYALTY, LAST_CALC_AT) VALUES (?, 0.0, ?)');
            $ins->execute([$aid, $now]);
        }
    }

    $msg = "Recalculation complete. Authors updated: $updated";
    if (php_sapi_name() === 'cli') {
        echo $msg . PHP_EOL;
    } else {
        echo '<pre>' . htmlspecialchars($msg) . '</pre>';
    }

} catch (Exception $e) {
    error_log('recalc_royalties error: ' . $e->getMessage());
    if (php_sapi_name() === 'cli') echo 'Error: ' . $e->getMessage() . PHP_EOL;
    else echo '<pre>Error: ' . htmlspecialchars($e->getMessage()) . '</pre>';
}

?>