<?php
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/AuthorController.php';
$loading = false;
$error = '';
$success = '';
$authors = getAllAuthors();
$book = null;
if (!isset($_GET['id'])) {
    $error = 'No book selected.';
} else {
    $book = getBookById(intval($_GET['id']));
    if (!$book) {
        $error = 'Book not found.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $book) {
    $loading = true;
    $title = trim($_POST['title'] ?? $book['title']);
    $author_id = intval($_POST['author_id'] ?? $book['author_id']);
    $genre = trim($_POST['genre'] ?? $book['genre']);
    $published_year = trim($_POST['published_year'] ?? $book['published_year']);
    $price = floatval($_POST['price'] ?? $book['price']);
    $stock = intval($_POST['stock'] ?? $book['stock']);
    $edition = trim($_POST['edition'] ?? $book['edition']);
    $cover_url = trim($_POST['cover_url'] ?? $book['cover_url']);
    $description = trim($_POST['description'] ?? $book['description']);
    if ($title && $author_id && $price) {
        if (updateBook($book['book_id'], $title, $author_id, $genre, $published_year, $price, $stock, $edition, $cover_url, $description)) {
            $success = 'Book updated successfully!';
            $book = getBookById($book['book_id']);
        } else {
            $error = 'Failed to update book.';
        }
    } else {
        $error = 'Title, author, and price are required.';
    }
    $loading = false;
}
?>
<!DOCTYPE html>
<?php if ($loading): ?>
    <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">Loading...</div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form action="#" class="space-y-6" method="POST">
<input class="focus:ring-primary focus:border-primary block w-full pl-7 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10" id="isbn" maxlength="10" name="isbn" placeholder="0123456789" type="text" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>"/>
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="title" name="title" placeholder="e.g. The Great Gatsby" type="text" value="<?= htmlspecialchars($book['title'] ?? '') ?>"/>
<!-- Author Dropdown -->
<select name="author_id" class="block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white h-10 px-3" required>
    <option value="">Select Author</option>
    <?php foreach ($authors as $author): ?>
        <option value="<?= $author['author_id'] ?>" <?= ($book['author_id'] == $author['author_id']) ? 'selected' : '' ?>><?= htmlspecialchars($author['name']) ?></option>
    <?php endforeach; ?>
</select>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Book - Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<img alt="Admin Avatar" class="h-8 w-8 rounded-full bg-slate-300" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAbWM4RTaj5YleCt-NPgaymDPt85XXLWyTnSei_0usGIRsfr519tqc1KMpEzEx-2a9hjQ-w584IqMjDuARckZc-JJYqGeCGIIQx2iYFIsys6b8SyD_pz2mffDW5_qvmxKkLSkNRv6f0yvkFFlGPTiX1APpBOpY9TCN8w_ccF6qJSvaoK8izDHGd6UDCZ3pQDrn45G_pJnaLeOLUZC1JlC7jsKMqleEQ6wWyP3_3r92TJ__acuws0NTP4rMsvK9osMVeSHErzAGR4A"/>
<span class="hidden md:block text-sm font-medium text-slate-700 dark:text-slate-300">Admin User</span>
</div>
</div>
</div>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="flex-grow py-10">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
<!-- Breadcrumbs -->
<nav aria-label="Breadcrumb" class="flex mb-5">
<ol class="flex items-center space-x-2">
<li><a class="text-slate-400 hover:text-slate-500" href="index.php?page=dashboard"><span class="material-icons text-sm">home</span></a></li>
<li><span class="text-slate-300">/</span></li>
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" href="index.php?page=inventrory">Inventory</a></li>
<li><span class="text-slate-300">/</span></li>
<li><span aria-current="page" class="text-sm font-medium text-primary">Edit Book</span></li>
</ol>
</nav>
<!-- Page Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                        Edit Book
                    </h2>
<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Update the book details below.
                    </p>
</div>
</div>
<form action="#" class="space-y-6" method="POST">
<!-- Card Container -->
<div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
<!-- Section 1: Basic Information -->
<div class="p-6 sm:p-8 space-y-6 border-b border-slate-200 dark:border-slate-800">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">menu_book</span>
                            Book Details
                        </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
<!-- ISBN Field -->
<div class="sm:col-span-3">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="isbn">
                                    ISBN-10
                                </label>
<div class="mt-1 relative rounded-md shadow-sm">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="text-slate-400 text-sm font-mono">#</span>
</div>
<input class="focus:ring-primary focus:border-primary block w-full pl-7 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10" id="isbn" maxlength="10" name="isbn" placeholder="0123456789" type="text" value="0451524934"/>
</div>
</div>
<!-- Title Field -->
<div class="sm:col-span-6">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="title">
                                    Book Title
                                </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="title" name="title" placeholder="e.g. The Great Gatsby" type="text" value="1984"/>
</div>
</div>
</div>
</div>
<!-- Section 2: Pricing & Stats -->
<div class="p-6 sm:p-8 space-y-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">attach_money</span>
                            Pricing &amp; Stats
                        </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
<!-- Price Field -->
<div class="sm:col-span-3">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="price">
                                    Price
                                </label>
<div class="mt-1 relative rounded-md shadow-sm">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="text-slate-500 sm:text-sm">$</span>
</div>
<input class="focus:ring-primary focus:border-primary block w-full pl-7 pr-12 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10" id="price" name="price" placeholder="0.00" type="number" value="15.99"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-500 sm:text-sm">USD</span>
</div>
</div>
</div>
<!-- Stock Field -->
<div class="sm:col-span-3">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="stock">
                                    Stock Quantity
                                </label>
<div class="mt-1 relative rounded-md shadow-sm">
<input class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="stock" min="0" name="stock" placeholder="0" type="number" value="42"/>
</div>
</div>
</div>
</div>
<!-- Section 3: Additional Info -->
<div class="p-6 sm:p-8 space-y-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">description</span>
                            Additional Information
                        </h3>
<div class="space-y-6">
<!-- Description Field -->
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="description">
                                    Description
                                </label>
<div class="mt-1">
<textarea class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md px-3 py-2" id="description" name="description" placeholder="Enter book description..." rows="4">A dystopian social science fiction novel about totalitarianism and surveillance.</textarea>
</div>
</div>
<!-- Author Field -->
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="author">
                                        Author
                                    </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="author" name="author" placeholder="Author name" type="text" value="George Orwell"/>
</div>
</div>
<!-- Publisher Field -->
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="publisher">
                                        Publisher
                                    </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="publisher" name="publisher" placeholder="Publisher name" type="text" value="Penguin Books"/>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Buttons -->
<div class="flex items-center justify-between pt-6">
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all dark:bg-slate-700 dark:hover:bg-slate-600" type="button">
<span class="material-icons text-lg mr-2">arrow_back</span>
                        Back
                    </button>
<div class="flex gap-3">
<button class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg shadow-sm text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all dark:border-slate-600" type="reset">
<span class="material-icons text-lg mr-2">refresh</span>
                            Reset
                        </button>
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="submit">
<span class="material-icons text-lg mr-2">save</span>
                            Save Changes
                        </button>
</div>
</div>
</form>
</div>
</main>
</body>
</html>
