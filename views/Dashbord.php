<?php
ob_start();
require_once __DIR__ . '/../controllers/AssignmentController.php'; // resetSystem

// handle reset request BEFORE outputting anything
if (isset($_POST['reset_system'])) {
    resetSystem();
}

// now include your other controllers and start output
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/AuthorController.php';
require_once __DIR__ . '/../controllers/OrderController.php';

$books = getAllBooks();
$authors = getAllAuthors();
$orders = getAllOrders();
// compute top authors by royalties
$topAuthors = getTopAuthors(5);

?>
<div class="flex-1 overflow-y-auto p-6 md:p-8">
    <div class="max-w-6xl mx-auto space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Bookstore Admin Dashboard</h2>
            <p class="text-slate-500 dark:text-slate-400">All data below is live from your database.</p>

            <!-- RESET BUTTON -->
<!-- RESET BUTTON -->
<form method="POST" action="index.php?page=<?php echo htmlspecialchars($page); ?>" 
      onsubmit="return confirm('This will delete ALL data. Continue?');" 
      class="mt-4">
    <button 
        type="submit" 
        name="reset_system"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
        Reset System
    </button>
</form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Books</h3>
                <p class="text-3xl font-bold text-primary"><?php echo count($books); ?></p>
                <a href="index.php?page=inventrory" class="text-primary hover:underline text-sm">View Inventory</a>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Authors</h3>
                <p class="text-3xl font-bold text-primary"><?php echo count($authors); ?></p>
                <a href="index.php?page=author" class="text-primary hover:underline text-sm">View Authors</a>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Orders</h3>
                <p class="text-3xl font-bold text-primary"><?php echo count($orders); ?></p>
                <a href="index.php?page=order" class="text-primary hover:underline text-sm">View Orders</a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 mt-8">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Recent Books</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">ID</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Title</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Author</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($books, 0, 5) as $book): ?>
                        <tr class="hover:bg-primary/5 transition-colors group">
                            <td class="px-6 py-4 font-mono text-sm text-slate-600 dark:text-slate-400"><?php echo htmlspecialchars($book['BOOK_ID'] ?? ''); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($book['TITLE'] ?? ''); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($book['AUTHOR_NAME'] ?? ''); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($book['STOCK'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (count($books) === 0): ?>
                <div class="text-slate-500 mt-4">No books found in the database.</div>
            <?php endif; ?>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 mt-8">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Top Authors by Royalties</h3>
            <?php if (!empty($topAuthors)): ?>
                <ol class="list-decimal pl-5 text-slate-700 dark:text-slate-300 space-y-2">
                    <?php foreach ($topAuthors as $entry): $a = $entry['author']; $r = $entry['royalty']; ?>
                        <li>
                            <div class="flex justify-between items-center">
                                <div>
                                    <a href="index.php?page=authordetails&id=<?php echo $a['AUTHOR_ID']; ?>" class="text-primary hover:underline"><?php echo htmlspecialchars($a['NAME']); ?></a>
                                    <div class="text-xs text-slate-500">ID: <?php echo $a['AUTHOR_ID']; ?></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">$<?php echo number_format($r, 2); ?></div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <div class="text-slate-500">No royalty data available yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
ob_end_flush();