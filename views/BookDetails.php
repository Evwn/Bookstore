
<?php
require_once __DIR__ . '/../controllers/BookController.php';
$loading = false;
$error = '';
$success = '';
$book = null;

// Get book ID from query string
if (isset($_GET['id'])) {
    $bookId = intval($_GET['id']);
    $book = getBookById($bookId);
    if (!$book) {
        $error = 'Book not found.';
    }
} else {
    $error = 'No book selected.';
}

// Handle delete action
if (isset($_POST['delete_book']) && isset($book['book_id'])) {
    $loading = true;
    if (deleteBook($book['book_id'])) {
        $success = 'Book deleted successfully.';
        // Redirect to inventory after 1.5s
        echo '<script>setTimeout(function(){ window.location.href = "index.php?page=inventrory"; }, 1500);</script>';
    } else {
        $error = 'Failed to delete book.';
    }
    $loading = false;
}
?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Book Details View</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#0f4bc4",
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
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-100 antialiased min-h-screen">
<!-- Dashboard Layout Wrapper -->
<div class="flex h-screen overflow-hidden">
<!-- Sidebar Navigation (Simplified for Context) -->
<aside class="hidden md:flex flex-col w-64 bg-white dark:bg-[#1a202c] border-r border-slate-200 dark:border-slate-800 h-full">
<div class="p-6 flex items-center space-x-3">
<div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center text-white font-bold text-xl">B</div>
<span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">BookStore</span>
</div>
<nav class="flex-1 px-4 space-y-2 mt-4">
<a class="flex items-center px-4 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors group" href="index.php?page=dashboard">
<span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">dashboard</span>
                    Dashboard
                </a>
<a class="flex items-center px-4 py-3 bg-primary/10 text-primary dark:text-primary-400 rounded-lg transition-colors font-medium" href="index.php?page=bookdetails">
<span class="material-icons text-xl mr-3">library_books</span>
                    Books Inventory
                </a>
<a class="flex items-center px-4 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors group" href="index.php?page=authordetails">
<span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">people</span>
                    Authors
                </a>
<a class="flex items-center px-4 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-colors group" href="index.php?page=order">
<span class="material-icons text-xl mr-3 group-hover:text-primary transition-colors">shopping_cart</span>
                    Orders
                </a>
</nav>
<div class="p-4 border-t border-slate-200 dark:border-slate-800">
<div class="flex items-center space-x-3">
<div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 bg-cover bg-center" data-alt="Admin User Profile Picture" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAqZ_toHgo6SF_AyaNIQgwAhDX1CgeX0VUQlevqvBQOJWF7OJ2usxkTuLW-_n6mSn1MfSOnPiR0oKqI3yWijnFANHn_gbTfiQ-h3jE93CEFN7jfkhSwtalr1gjXd3tnMUm-xr8YnBjAFg6rv41FwmbXk54-rIShWjv-WgKQC7afU2Cj3-pA2dW1pZXL710riUsA9PMEBWtcd-LHOx7cL_gZbKHdmyPXAw3ZhKsGWs57yr-bw1xixKJ5BrT077ymYCKgjccW32y4dg')"></div>
<div>
<p class="text-sm font-medium text-slate-900 dark:text-white">Admin User</p>
<p class="text-xs text-slate-500 dark:text-slate-400">View Profile</p>
</div>
</div>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-1 overflow-y-auto">
<!-- Top Header -->
<header class="bg-white dark:bg-[#1a202c] border-b border-slate-200 dark:border-slate-800 sticky top-0 z-20 px-8 py-4 flex justify-between items-center">
<div class="flex items-center space-x-4">
<button class="md:hidden text-slate-500 hover:text-slate-700 dark:hover:text-slate-200">
<span class="material-icons">menu</span>
</button>
<nav class="text-sm font-medium text-slate-500 dark:text-slate-400">
<a class="hover:text-primary dark:hover:text-blue-400" href="index.php?page=inventrory">Inventory</a>
<span class="mx-2">/</span>
<span class="text-slate-900 dark:text-white">Book Details</span>
</nav>
</div>
<div class="flex items-center space-x-4">
<button class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
<span class="material-icons">notifications</span>
</button>
<button class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
<span class="material-icons">settings</span>
</button>
</div>
</header>
<!-- Page Content -->
<div class="max-w-6xl mx-auto px-4 sm:px-8 py-10">
    <?php if ($loading): ?>
        <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">Loading...</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
<!-- Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
<div>
<h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-1">Book Details</h1>
<p class="text-slate-500 dark:text-slate-400">
    <?php if ($book): ?>
        View and manage metadata for ISBN: <span class="font-mono"> <?= htmlspecialchars($book['isbn'] ?? '') ?> </span>
    <?php endif; ?>
</p>
</div>
<div class="flex items-center space-x-3">
    <?php if ($book): ?>
    <button class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 font-medium transition-colors flex items-center" onclick="window.location.href='index.php?page=editbook&id=<?= $book['book_id'] ?>'">
        <span class="material-icons text-sm mr-2">edit</span>
        Edit
    </button>
    <form method="post" style="display:inline;">
        <button type="submit" name="delete_book" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center" onclick="return confirm('Are you sure you want to delete this book?');">
            <span class="material-icons text-sm mr-2">delete</span>
            Delete
        </button>
    </form>
    <?php endif; ?>
    <button class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-lg font-medium shadow-sm hover:shadow-md transition-all flex items-center group">
        <span class="material-icons text-sm mr-2 group-hover:rotate-180 transition-transform">code</span>
        Display Source
    </button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Left Column: Visuals & Quick Actions -->
<div class="lg:col-span-1 space-y-6">
<!-- Book Cover Card -->
<div class="bg-white dark:bg-[#1a202c] rounded-xl border border-slate-200 dark:border-slate-800 p-2 shadow-sm">
<div class="relative aspect-[2/3] w-full bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden group">
<img alt="Book cover" class="w-full h-full object-cover" src="<?= htmlspecialchars($book['cover_url'] ?? 'https://via.placeholder.com/200x300?text=No+Cover') ?>"/>
<div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
<button class="bg-white text-slate-900 px-4 py-2 rounded-lg font-medium text-sm shadow-lg hover:bg-slate-50">Change Cover</button>
</div>
</div>
<div class="p-4 text-center">
<div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 mb-2">
                                    In Stock
                                </div>
<h3 class="font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($book['title'] ?? '') ?></h3>
<p class="text-sm text-slate-500 dark:text-slate-400">Edition: <?= htmlspecialchars($book['edition'] ?? 'N/A') ?></p>
</div>
</div>
<!-- Quick Stats Card -->
<div class="bg-white dark:bg-[#1a202c] rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
<h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4">Performance</h4>
<div class="space-y-5">
<div>
<div class="flex justify-between items-end mb-1">
<span class="text-sm font-medium text-slate-700 dark:text-slate-300">Sales Goal</span>
<span class="text-sm font-bold text-primary">78%</span>
</div>
<div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
<div class="bg-primary h-2 rounded-full" style="width: 78%"></div>
</div>
</div>
<div class="grid grid-cols-2 gap-4 pt-2">
<div class="p-3 bg-background-light dark:bg-slate-800/50 rounded-lg">
<p class="text-xs text-slate-500 dark:text-slate-400">Rating</p>
<div class="flex items-center mt-1">
<span class="material-icons text-yellow-400 text-base mr-1">star</span>
<span class="font-bold text-slate-900 dark:text-white">4.8</span>
</div>
</div>
<div class="p-3 bg-background-light dark:bg-slate-800/50 rounded-lg">
<p class="text-xs text-slate-500 dark:text-slate-400">Views</p>
<div class="flex items-center mt-1">
<span class="material-icons text-blue-400 text-base mr-1">visibility</span>
<span class="font-bold text-slate-900 dark:text-white">2.4k</span>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Right Column: Detailed Info -->
<div class="lg:col-span-2 space-y-6">
<!-- Main Info Card -->
<div class="bg-white dark:bg-[#1a202c] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
<div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
<h3 class="text-lg font-semibold text-slate-900 dark:text-white">General Information</h3>
</div>
<div class="p-6">
<dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">
<div class="col-span-2">
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Book Title</dt>
<dd class="text-xl font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($book['title'] ?? '') ?></dd>
</div>
<div>
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">ISBN</dt>
<dd class="flex items-center">
<span class="font-mono text-base text-slate-900 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700"><?= htmlspecialchars($book['isbn'] ?? '') ?></span>
<button class="ml-2 text-slate-400 hover:text-primary transition-colors" title="Copy ISBN">
<span class="material-icons text-sm">content_copy</span>
</button>
</dd>
</div>
<div>
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Publication Date</dt>
<dd class="text-base text-slate-900 dark:text-white"><?= htmlspecialchars($book['published_year'] ?? '') ?></dd>
</div>
<div class="col-span-2 sm:col-span-1">
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">Authors</dt>
<dd class="flex flex-wrap gap-2">
<a class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all group" href="#">
<div class="w-5 h-5 rounded-full bg-primary/20 mr-2 flex items-center justify-center text-xs font-bold group-hover:bg-white/20">
    <?= isset($book['author_name']) ? strtoupper(substr($book['author_name'], 0, 2)) : '' ?>
</div>
<?= htmlspecialchars($book['author_name'] ?? 'Unknown') ?>
</dd>
</div>
<div class="col-span-2 mt-2">
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Description</dt>
<dd class="text-base text-slate-700 dark:text-slate-300 leading-relaxed max-w-2xl">
    <?= nl2br(htmlspecialchars($book['description'] ?? 'No description.')) ?>
</dd>
</div>
</dl>
</div>
</div>
<!-- Sales & Inventory Card -->
<div class="bg-white dark:bg-[#1a202c] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
<div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
<h3 class="text-lg font-semibold text-slate-900 dark:text-white">Financial &amp; Inventory Data</h3>
</div>
<div class="p-6">
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
<!-- Price Box -->
<div class="relative overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700 p-4 group hover:border-primary/50 transition-colors bg-white dark:bg-transparent">
<div class="absolute right-0 top-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
<span class="material-icons text-5xl text-primary">attach_money</span>
</div>
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Unit Price</dt>
<dd class="mt-2 text-3xl font-bold text-slate-900 dark:text-white tracking-tight">$<?= htmlspecialchars($book['price'] ?? '0.00') ?></dd>
<div class="mt-1 text-xs text-green-600 dark:text-green-400 font-medium flex items-center">
<span class="material-icons text-sm mr-0.5">trending_up</span> +2.5% vs last month
                                        </div>
</div>
<!-- Percentage Box -->
<div class="relative overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700 p-4 group hover:border-purple-500/50 transition-colors bg-white dark:bg-transparent">
<div class="absolute right-0 top-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
<span class="material-icons text-5xl text-purple-500">pie_chart</span>
</div>
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Publisher Share</dt>
<dd class="mt-2 text-3xl font-bold text-slate-900 dark:text-white tracking-tight">15%</dd>
<div class="mt-1 text-xs text-slate-400">Standard rate applies</div>
</div>
<!-- Quantity Box -->
<div class="relative overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700 p-4 group hover:border-orange-500/50 transition-colors bg-white dark:bg-transparent">
<div class="absolute right-0 top-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
<span class="material-icons text-5xl text-orange-500">inventory_2</span>
</div>
<dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Quantity Sold</dt>
<dd class="mt-2 text-3xl font-bold text-slate-900 dark:text-white tracking-tight"><?= htmlspecialchars($book['stock'] ?? '0') ?></dd>
<div class="mt-1 text-xs text-orange-600 dark:text-orange-400 font-medium">Low stock alert (Regional)</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>