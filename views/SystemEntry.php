<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/AuthorController.php';
require_once __DIR__ . '/../controllers/OrderController.php';

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
    <title>Dashboard - BookStore Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-200 min-h-screen flex flex-col">

    <!-- Navbar — identical to inventory -->
    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="material-icons text-primary text-3xl mr-2">library_books</span>
                        <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">BookStore<span class="text-primary">Admin</span></span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a class="border-primary text-slate-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=dashboard">Dashboard</a>
                        <a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=inventrory">Inventory</a>
                        <a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=authordetails">Authors</a>
                        <a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=customer">Customers</a>
                        <a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=order">Orders</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <button class="bg-primary/10 p-1 rounded-full text-primary hover:text-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <span class="sr-only">View notifications</span>
                        <span class="material-icons">notifications</span>
                    </button>
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-3">
                            <span class="hidden md:block text-sm font-medium text-slate-700 dark:text-slate-300">Admin User</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="flex mb-5">
                <ol class="flex items-center space-x-2">
                    <li><a class="text-slate-400 hover:text-slate-500" href="index.php?page=dashboard"><span class="material-icons text-sm">home</span></a></li>
                    <li><span class="text-slate-300">/</span></li>
                    <li><span aria-current="page" class="text-sm font-medium text-primary">Dashboard</span></li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">Dashboard</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">System overview and statistics.</p>
                </div>
            </div>

            <!-- Alerts -->
            <?php if ($message): ?>
                <div class="mb-6 p-3 bg-green-100 text-green-800 rounded flex items-center gap-2">
                    <span class="material-icons text-sm">check_circle</span><?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="mb-6 p-3 bg-red-100 text-red-800 rounded flex items-center gap-2">
                    <span class="material-icons text-sm">error</span><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Stat cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Authors</span>
                        <span class="material-icons text-primary text-xl">people</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white"><?= $totalAuthors ?></div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Books</span>
                        <span class="material-icons text-primary text-xl">menu_book</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white"><?= $totalBooks ?></div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Copies Sold</span>
                        <span class="material-icons text-green-500 text-xl">shopping_cart</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white"><?= number_format($totalSold) ?></div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Royalties</span>
                        <span class="material-icons text-green-500 text-xl">payments</span>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 dark:text-white">$<?= number_format($totalRoyalties, 2) ?></div>
                </div>
            </div>

            <!-- Two-col: Authors + Books & Sales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <!-- Authors -->
                <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                        <h3 class="text-base font-medium text-slate-900 dark:text-white">Authors</h3>
                        <span class="text-xs text-slate-500 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded-full"><?= $totalAuthors ?> total</span>
                    </div>
                    <?php if (!empty($authors)): ?>
                        <?php foreach ($authors as $a):
                            $words = explode(' ', trim($a['NAME']));
                            $ini   = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
                        ?>
                        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-100 dark:border-slate-800 hover:bg-primary/5 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold text-sm flex-shrink-0">
                                <?= htmlspecialchars($ini) ?>
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-slate-900 dark:text-white"><?= htmlspecialchars($a['NAME']) ?></div>
                                <div class="text-xs text-slate-500">Royalty: $<?= number_format($a['ROYALTY'] ?? 0, 2) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="px-6 py-10 text-center text-slate-500 text-sm">No authors yet.</div>
                    <?php endif; ?>
                </div>

                <!-- Books & Sales -->
                <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                        <h3 class="text-base font-medium text-slate-900 dark:text-white">Books &amp; Sales</h3>
                        <span class="text-xs text-slate-500 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded-full"><?= $totalBooks ?> titles</span>
                    </div>
                    <?php if (!empty($books)): ?>
                    <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50">
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Title</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Author</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Sold</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($books as $b): ?>
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="px-6 py-3 text-sm font-medium text-slate-900 dark:text-white max-w-[140px] truncate"><?= htmlspecialchars($b['TITLE']) ?></td>
                                <td class="px-6 py-3 text-sm text-slate-500 max-w-[120px] truncate"><?= htmlspecialchars($b['AUTHORS'] ?? $b['AUTHOR_NAME'] ?? '—') ?></td>
                                <td class="px-6 py-3 text-sm <?= ($b['QUANTITY_SOLD'] ?? 0) > 0 ? 'text-green-600 font-medium' : 'text-slate-500' ?>"><?= $b['QUANTITY_SOLD'] ?? 0 ?></td>
                                <td class="px-6 py-3 text-sm font-semibold text-slate-900 dark:text-white">$<?= number_format($b['PRICE'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php else: ?>
                        <div class="px-6 py-10 text-center text-slate-500 text-sm">No books yet.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Full Books Table -->
            <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                    <h3 class="text-base font-medium text-slate-900 dark:text-white">All Books</h3>
                    <span class="text-xs text-slate-500 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 px-2 py-0.5 rounded-full"><?= $totalBooks ?> entries</span>
                </div>
                <?php if (!empty($books)): ?>
                <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">ISBN</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Title</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Authors</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Percentages</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Price</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Qty Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $b): ?>
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500"><?= htmlspecialchars($b['ISBN']) ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white max-w-[200px] truncate"><?= htmlspecialchars($b['TITLE']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($b['AUTHORS'] ?? $b['AUTHOR_NAME'] ?? '—') ?></td>
                            <td class="px-6 py-4">
                                <?php
                                $pcts = explode(', ', $b['PERCENTAGES'] ?? '');
                                foreach ($pcts as $pct):
                                    if (trim($pct) !== ''):
                                ?>
                                    <span class="inline-block bg-primary/10 text-primary text-xs px-2 py-0.5 rounded-full mr-1"><?= htmlspecialchars(trim($pct)) ?>%</span>
                                <?php endif; endforeach; ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">$<?= number_format($b['PRICE'], 2) ?></td>
                            <td class="px-6 py-4 text-sm <?= ($b['QUANTITY_SOLD'] ?? 0) > 0 ? 'text-green-600 font-medium' : 'text-slate-500' ?>"><?= $b['QUANTITY_SOLD'] ?? 0 ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php else: ?>
                    <div class="px-6 py-12 text-center text-slate-500 text-sm">No books in the system.</div>
                <?php endif; ?>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-red-200 dark:border-red-900/50 p-6 flex items-center justify-between gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-red-600 mb-1">Reset System</h3>
                    <p class="text-sm text-slate-500">Permanently removes all authors, books, and royalty data. Cannot be undone.</p>
                </div>
                <form method="POST" onsubmit="return confirm('Permanently delete all data? This cannot be undone.');">
                    <button type="submit" name="reset_system"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <span class="material-icons text-sm">delete_forever</span>
                        Clear All Data
                    </button>
                </form>
            </div>

        </div>
    </main>
</body>
</html>