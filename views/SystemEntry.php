<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';

$message = '';
$error   = '';

// Handle system reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_system'])) {
    if (resetSystem()) {
        $message = 'System has been reset successfully. All data cleared.';
    } else {
        $error = 'Failed to reset system. Please try again.';
    }
}

// Use the exact functions from AssignmentController.php
$stats        = getSystemStats();
$authors      = getAllAuthors();
$books        = searchBooks(''); // returns all books with AUTHORS column via GROUP_CONCAT

$totalAuthors   = $stats['authors'];
$totalBooks     = $stats['books'];
$totalSold      = $stats['total_sold']      ?? 0;
$totalRoyalties = $stats['total_royalties'] ?? 0;
$totalStock     = array_sum(array_column($books, 'QUANTITY_SOLD'));

// Build author map for the full books table
$authorMap = [];
foreach ($authors as $a) $authorMap[$a['AUTHOR_ID']] = $a['NAME'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Bookstore Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
        <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#0f4bc2",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>
<body>

<!-- ── Main ── -->
<div class="main">
    <div class="topbar">
        <h1>Dashboard</h1>
        <div class="tstatus"><span class="dot"></span>System Online</div>
    </div>

    <div class="content">

        <?php if ($message): ?>
            <div class="alert as"><span class="material-icons">check_circle</span><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert ae"><span class="material-icons">error</span><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Stat cards — data from getSystemStats() -->
        <div class="sg">
            <div class="sc">
                <div class="si"><span class="material-icons">people</span></div>
                <div class="sv"><?= $totalAuthors ?></div>
                <div class="slb">Authors</div>
            </div>
            <div class="sc">
                <div class="si"><span class="material-icons">menu_book</span></div>
                <div class="sv"><?= $totalBooks ?></div>
                <div class="slb">Books</div>
            </div>
            <div class="sc g">
                <div class="si"><span class="material-icons">shopping_cart</span></div>
                <div class="sv"><?= number_format($totalSold) ?></div>
                <div class="slb">Copies Sold</div>
            </div>
            <div class="sc g">
                <div class="si"><span class="material-icons">payments</span></div>
                <div class="sv">$<?= number_format($totalRoyalties, 2) ?></div>
                <div class="slb">Total Royalties</div>
            </div>
        </div>

        <!-- Authors list (getAllAuthors) + Stock table (searchBooks) -->
        <div class="tc">

            <!-- Authors — from getAllAuthors() -->
            <div class="card">
                <div class="ch">
                    <span class="ct">Authors</span>
                    <span class="cc"><?= $totalAuthors ?> total</span>
                </div>
                <?php if (!empty($authors)): ?>
                    <?php foreach ($authors as $a):
                        $words = explode(' ', trim($a['NAME']));
                        $ini   = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
                    ?>
                    <div class="ai">
                        <div class="av"><?= htmlspecialchars($ini) ?></div>
                        <div>
                            <div class="an"><?= htmlspecialchars($a['NAME']) ?></div>
                            <!-- AUTHORS table has NAME + ROYALTY columns -->
                            <div class="ab">Royalty: $<?= number_format($a['ROYALTY'] ?? 0, 2) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty">No authors yet.</div>
                <?php endif; ?>
            </div>

            <!-- Books summary — from searchBooks('') which returns QUANTITY_SOLD + AUTHORS -->
            <div class="card">
                <div class="ch">
                    <span class="ct">Books &amp; Sales</span>
                    <span class="cc"><?= $totalBooks ?> titles</span>
                </div>
                <?php if (!empty($books)): ?>
                <table class="bt">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>Sold</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $b): ?>
                        <tr>
                            <td style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text);font-weight:500;">
                                <?= htmlspecialchars($b['TITLE']) ?>
                            </td>
                            <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--muted);">
                                <?= htmlspecialchars($b['AUTHORS'] ?? '—') ?>
                            </td>
                            <td class="<?= ($b['QUANTITY_SOLD'] ?? 0) > 0 ? 'sok' : '' ?>">
                                <?= $b['QUANTITY_SOLD'] ?? 0 ?>
                            </td>
                            <td class="pr">$<?= number_format($b['PRICE'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="empty">No books yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Full books table — ISBN, title, authors (GROUP_CONCAT from searchBooks), price, qty sold -->
        <div class="fw">
            <div class="card">
                <div class="ch">
                    <span class="ct">All Books</span>
                    <span class="cc"><?= $totalBooks ?> entries</span>
                </div>
                <?php if (!empty($books)): ?>
                <div class="tw">
                <table class="bt">
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Authors</th>
                            <th>Percentages</th>
                            <th>Price</th>
                            <th>Qty Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $b): ?>
                        <tr>
                            <td style="font-family:monospace;font-size:.72rem;color:var(--muted);"><?= htmlspecialchars($b['ISBN']) ?></td>
                            <td style="font-weight:500;color:var(--text);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                <?= htmlspecialchars($b['TITLE']) ?>
                            </td>
                            <td style="color:var(--muted);"><?= htmlspecialchars($b['AUTHORS'] ?? '—') ?></td>
                            <td>
                                <?php
                                // PERCENTAGES is a GROUP_CONCAT of percentages matching AUTHORS order
                                $pcts = explode(', ', $b['PERCENTAGES'] ?? '');
                                foreach ($pcts as $pct):
                                    if (trim($pct) !== ''):
                                ?>
                                    <span class="gb"><?= htmlspecialchars(trim($pct)) ?>%</span>
                                <?php endif; endforeach; ?>
                            </td>
                            <td class="pr">$<?= number_format($b['PRICE'], 2) ?></td>
                            <td class="<?= ($b['QUANTITY_SOLD'] ?? 0) > 0 ? 'sok' : '' ?>">
                                <?= $b['QUANTITY_SOLD'] ?? 0 ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php else: ?>
                    <div class="empty">No books in the system.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danger Zone — calls resetSystem() -->
        <div class="dz">
            <div>
                <div class="dzt">Reset System</div>
                <div class="dzd">Permanently removes all authors, books, and royalty data (BOOK_AUTHORS, BOOKS, AUTHORS). Cannot be undone.</div>
            </div>
            <form method="POST" onsubmit="return confirm('Permanently delete all data? This cannot be undone.');">
                <button type="submit" name="reset_system" class="btn-d">
                    <span class="material-icons">delete_forever</span>Clear All Data
                </button>
            </form>
        </div>

    </div>
</div>
</body>
</html>