<?php
require_once __DIR__ . '/../controllers/AuthorController.php';
require_once __DIR__ . '/../controllers/BookController.php';
$success = $error = '';
$author = null;
$books = [];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    $author = getAuthorById($id);
    if ($author) {
        $allBooks = getAllBooks();
        foreach ($allBooks as $book) {
            if ($book['AUTHOR_ID'] == $id) {
                $books[] = $book;
            }
        }
    } else {
        $error = 'Author not found.';
    }
} else {
    $error = 'No author selected.';
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Author Details - BookStore Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-light": "#d0deff",
                        "primary-dark": "#0a3aa6",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-100 min-h-screen flex flex-col">
<!-- Top Navigation -->
<nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-3">
                <span class="material-icons text-primary text-2xl">menu_book</span>
                <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">BookStore<span class="text-primary">Admin</span></span>
            </div>
            <div class="flex gap-4">
                <a href="index.php?page=dashboard" class="text-slate-500 dark:text-slate-300 hover:text-primary px-3 py-2 rounded transition">Dashboard</a>
                <a href="index.php?page=author" class="text-slate-500 dark:text-slate-300 hover:text-primary px-3 py-2 rounded transition">Authors</a>
                <a href="index.php?page=inventrory" class="text-slate-500 dark:text-slate-300 hover:text-primary px-3 py-2 rounded transition">Inventory</a>
                <a href="index.php?page=order" class="text-slate-500 dark:text-slate-300 hover:text-primary px-3 py-2 rounded transition">Orders</a>
            </div>
        </div>
    </div>
</nav>
<!-- Main Content -->
<main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
<!-- Header Section -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
    Author Details
</h2>
<?php if ($error): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($author): ?>
    <div class="mt-4 mb-8">
        <h3 class="text-xl font-semibold text-primary mb-2"><?= htmlspecialchars($author['NAME']) ?></h3>
        <div class="mb-2 text-slate-700 dark:text-slate-200"><strong>Biography:</strong></div>
        <div class="mb-4 text-slate-600 dark:text-slate-300"><?= nl2br(htmlspecialchars($author['BIOGRAPHY'] ?? 'No biography available.')) ?></div>
        <a href="index.php?page=editauthor&id=<?= $author['AUTHOR_ID'] ?>" class="inline-block px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition">Edit Author</a>
    </div>
    <div class="mb-8">
        <h4 class="text-lg font-bold mb-4">Books by this Author</h4>
        <?php if (count($books) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($books as $book): ?>
                    <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-4 hover:shadow-md transition-shadow">
                        <h5 class="font-semibold text-slate-900 dark:text-white mb-2">
                            <a href="index.php?page=bookdetails&id=<?= $book['BOOK_ID'] ?>" class="text-primary hover:underline"><?= htmlspecialchars($book['TITLE']) ?></a>
                        </h5>
                        <div class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                            <div><strong>Genre:</strong> <?= htmlspecialchars($book['GENRE'] ?? 'N/A') ?></div>
                            <div><strong>Price:</strong> $<?= htmlspecialchars($book['PRICE'] ?? '0.00') ?></div>
                            <div><strong>Stock:</strong> <?= htmlspecialchars($book['STOCK'] ?? '0') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-8 text-center">
                <span class="material-icons text-4xl text-slate-400 mb-2">book</span>
                <p class="text-slate-500 dark:text-slate-400">No books found for this author.</p>
                <a href="index.php?page=addnewbook" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">Add First Book</a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>
<div class="mt-4 flex md:mt-0 md:ml-4">
<?php if ($author): ?>
<button class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm text-sm font-medium text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" onclick="window.location.href='index.php?page=editauthor&id=<?= $author['AUTHOR_ID'] ?>'" type="button">
<span class="material-icons text-sm mr-2">edit</span>
                    Edit Profile
                </button>
<?php endif; ?>
<button class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="button" onclick="window.history.back()">
<span class="material-icons text-sm mr-2">arrow_back</span>
                    Back
                </button>
</div>
</div>
<?php if ($author): ?>
<!-- Author Profile Card -->
<div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-800">
<div class="h-32 w-full relative">
<div class="absolute inset-0 bg-gradient-to-r from-primary to-primary-light opacity-90"></div>
<div class="absolute -bottom-12 left-6">
<div class="h-24 w-24 rounded-full border-4 border-white dark:border-slate-900 shadow-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
    <span class="material-icons text-3xl text-slate-500">person</span>
</div>
</div>
</div>
<div class="pt-16 pb-6 px-6">
<div class="flex justify-between items-start">
<div>
<h3 class="text-xl font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($author['NAME']) ?></h3>
<p class="text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center">
<span class="material-icons text-xs mr-1">fingerprint</span>
                                    ID: <span class="font-mono ml-1 text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-xs"><?= $author['AUTHOR_ID'] ?></span>
</p>
</div>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Active
                            </span>
</div>
<div class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-4">
<dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
<div>
<dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Books Published</dt>
<dd class="mt-1 text-sm text-slate-900 dark:text-white"><?= count($books) ?> Title<?= count($books) != 1 ? 's' : '' ?></dd>
</div>
<div>
<dt class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Stock</dt>
<dd class="mt-1 text-sm text-slate-900 dark:text-white">
    <?php 
    $totalStock = 0;
    foreach ($books as $book) {
        $totalStock += intval($book['STOCK'] ?? 0);
    }
    echo $totalStock;
    ?> Books
</dd>
</div>
</dl>
</div>
</div>
</div>
<?php endif; ?>
</main>
</body></html>