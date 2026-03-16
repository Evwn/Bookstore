<?php
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/AssignmentController.php';

$loading = false;
$error   = '';
$success = '';

// Search
$search_query = trim($_GET['search'] ?? $_POST['search_query'] ?? '');
$books = !empty($search_query) ? searchBooks($search_query) : getAllBooks();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
    $book_id = intval($_POST['delete_book']);
    if (deleteBook($book_id)) {
        $success = 'Book deleted successfully.';
    } else {
        $error = 'Failed to delete book. It may be referenced in orders.';
    }
    $books = !empty($search_query) ? searchBooks($search_query) : getAllBooks();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Book Inventory - BookStore Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
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
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-200 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="material-icons text-primary text-3xl mr-2">library_books</span>
                        <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">BookStore<span class="text-primary">Admin</span></span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=dashboard">Dashboard</a>
                        <a class="border-primary text-slate-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=inventrory">Inventory</a>
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
                    <li><span aria-current="page" class="text-sm font-medium text-primary">Inventory</span></li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                        Book Inventory
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Manage your book collection and stock levels.
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
                    <a href="index.php?page=addnewbook" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <span class="material-icons text-sm mr-2">add</span>
                        Add Book
                    </a>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Books Table -->
            <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between gap-4">
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white whitespace-nowrap">
                        All Books
                        <span class="ml-2 text-sm font-normal text-slate-500">(<?= count($books) ?>)</span>
                        <?php if (!empty($search_query)): ?>
                            <span class="ml-2 text-sm font-normal text-primary">— results for "<?= htmlspecialchars($search_query) ?>"</span>
                        <?php endif; ?>
                    </h3>

                    <!-- Search bar -->
                    <form method="GET" action="" class="flex items-center gap-2 flex-1 max-w-sm ml-auto">
                        <input type="hidden" name="page" value="inventrory"/>
                        <div class="relative flex-1">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none">search</span>
                            <input type="text" name="search"
                                   value="<?= htmlspecialchars($search_query) ?>"
                                   placeholder="Search by title…"
                                   class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"/>
                        </div>
                        <button type="submit" class="px-3 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-hover rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Search
                        </button>
                        <?php if (!empty($search_query)): ?>
                            <a href="index.php?page=inventrory" class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 border border-slate-300 rounded-lg">
                                Clear
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50">
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Book</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Author</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">ISBN</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Genre</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Price</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Stock</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr class="hover:bg-primary/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-12 w-8 flex-shrink-0 rounded bg-slate-200 dark:bg-slate-700 overflow-hidden shadow-sm mr-4 relative">
                                                <?php if (!empty($book['COVER_URL'])): ?>
                                                    <img src="<?= htmlspecialchars($book['COVER_URL']) ?>" alt="Book cover" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="absolute inset-0 bg-gradient-to-tr from-slate-300 to-slate-100 dark:from-slate-600 dark:to-slate-500"></div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <a class="text-base font-semibold text-primary hover:underline hover:text-primary-hover block" href="index.php?page=bookdetails&id=<?= $book['BOOK_ID'] ?>"><?= htmlspecialchars($book['TITLE'] ?? 'No Title') ?></a>
                                                <div class="text-sm text-slate-500 dark:text-slate-400">Published: <?= htmlspecialchars($book['PUBLISHED_YEAR'] ?? 'Unknown') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($book['AUTHOR_NAME'] ?? 'Unknown Author') ?></td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-sm"><?= htmlspecialchars($book['ISBN'] ?? 'N/A') ?></td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($book['GENRE'] ?? 'N/A') ?></td>
                                    <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">$<?= number_format($book['PRICE'] ?? 0, 2) ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $stock = $book['STOCK'] ?? 0;
                                        $stockColor = $stock > 10 ? 'text-green-600 dark:text-green-400' : ($stock > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400');
                                        ?>
                                        <span class="font-medium <?= $stockColor ?>"><?= $stock ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="index.php?page=bookdetails&id=<?= $book['BOOK_ID'] ?>" class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-primary/5" title="View Details">
                                                <span class="material-icons text-sm">visibility</span>
                                            </a>
                                            <a href="index.php?page=editbook&id=<?= $book['BOOK_ID'] ?>" class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-primary/5" title="Edit Book">
                                                <span class="material-icons text-sm">edit</span>
                                            </a>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="search_query" value="<?= htmlspecialchars($search_query) ?>">
                                                <button type="submit" name="delete_book" value="<?= $book['BOOK_ID'] ?>" class="p-2 text-slate-400 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete Book" onclick="return confirm('Are you sure you want to delete this book? This action cannot be undone.');">
                                                    <span class="material-icons text-sm">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (count($books) === 0): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons text-4xl mb-2">library_books</span>
                                            <?php if (!empty($search_query)): ?>
                                                <p class="text-lg font-medium mb-1">No books match "<?= htmlspecialchars($search_query) ?>"</p>
                                                <p class="text-sm">Try different keywords or <a href="index.php?page=inventrory" class="text-primary hover:underline">clear the search</a>.</p>
                                            <?php else: ?>
                                                <p class="text-lg font-medium mb-1">No books found</p>
                                                <p class="text-sm">Get started by adding your first book to the inventory.</p>
                                                <a href="index.php?page=addnewbook" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover">
                                                    <span class="material-icons text-sm mr-2">add</span>
                                                    Add First Book
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>