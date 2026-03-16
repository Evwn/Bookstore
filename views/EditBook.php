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
    $title = trim($_POST['title'] ?? '');
    $author_id = intval($_POST['author_id'] ?? 0);
    $isbn = trim($_POST['isbn'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $published_year = $_POST['published_year'] ? intval($_POST['published_year']) : null;
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $edition = trim($_POST['edition'] ?? '');
    $cover_url = trim($_POST['cover_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (!$title) {
        $error = 'Title is required.';
    } elseif (!$author_id) {
        $error = 'Please select an author.';
    } elseif ($price < 0) {
        $error = 'Price cannot be negative.';
    } elseif ($stock < 0) {
        $error = 'Stock cannot be negative.';
    } else {
        if (updateBook($book['BOOK_ID'], $title, $author_id, $isbn, $genre, $published_year, $price, $stock, $edition, $cover_url, $description)) {
            $success = 'Book updated successfully!';
            $book = getBookById($book['BOOK_ID']); // Refresh book data
        } else {
            $error = 'Failed to update book. Please try again.';
        }
    }
    $loading = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Edit Book - BookStore Admin</title>
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

            <?php if ($loading): ?>
                <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">Loading...</div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if ($book): ?>
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
                                    ISBN
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 text-sm font-mono">#</span>
                                    </div>
                                    <input class="focus:ring-primary focus:border-primary block w-full pl-7 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10" id="isbn" name="isbn" placeholder="Enter ISBN" type="text" value="<?= htmlspecialchars($book['ISBN'] ?? '') ?>"/>
                                </div>
                            </div>
                            <!-- Title Field -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="title">
                                    Book Title <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="title" name="title" placeholder="e.g. The Great Gatsby" type="text" value="<?= htmlspecialchars($book['TITLE'] ?? '') ?>" required/>
                                </div>
                            </div>
                            <!-- Author Field -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="author_id">
                                    Author <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="author_id" name="author_id" required>
                                        <option value="">Select an author</option>
                                        <?php foreach ($authors as $author): ?>
                                            <option value="<?= $author['AUTHOR_ID'] ?>" <?= ($book && $book['AUTHOR_ID'] == $author['AUTHOR_ID']) ? 'selected' : '' ?>><?= htmlspecialchars($author['NAME']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Genre Field -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="genre">
                                    Genre
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="genre" name="genre" placeholder="e.g. Fiction" type="text" value="<?= htmlspecialchars($book['GENRE'] ?? '') ?>"/>
                                </div>
                            </div>
                            <!-- Published Year Field -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="published_year">
                                    Published Year
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="published_year" name="published_year" placeholder="e.g. 2023" type="number" min="1000" max="<?= date('Y') ?>" value="<?= htmlspecialchars($book['PUBLISHED_YEAR'] ?? '') ?>"/>
                                </div>
                            </div>
                            <!-- Price Field -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="price">
                                    Price ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="price" name="price" placeholder="0.00" step="0.01" min="0" type="number" value="<?= htmlspecialchars($book['PRICE'] ?? '') ?>" required/>
                                </div>
                            </div>
                            <!-- Stock Field -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="stock">
                                    Stock
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="stock" name="stock" placeholder="0" min="0" type="number" value="<?= htmlspecialchars($book['STOCK'] ?? '') ?>"/>
                                </div>
                            </div>
                            <!-- Edition Field -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="edition">
                                    Edition
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="edition" name="edition" placeholder="e.g. 1st Edition" type="text" value="<?= htmlspecialchars($book['EDITION'] ?? '') ?>"/>
                                </div>
                            </div>
                            <!-- Cover URL Field -->
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="cover_url">
                                    Cover Image URL
                                </label>
                                <div class="mt-1">
                                    <input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="cover_url" name="cover_url" placeholder="https://example.com/cover.jpg" type="url" value="<?= htmlspecialchars($book['COVER_URL'] ?? '') ?>"/>
                                </div>
                            </div>
                            <!-- Description Field -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="description">
                                    Description
                                </label>
                                <div class="mt-1">
                                    <textarea class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md px-3 py-2" id="description" name="description" rows="4" placeholder="Book description..."><?= htmlspecialchars($book['DESCRIPTION'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between pt-6">
                    <button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all dark:bg-slate-700 dark:hover:bg-slate-600" type="button" onclick="window.history.back()">
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
            <?php else: ?>
                <div class="text-center py-12">
                    <span class="material-icons text-6xl text-slate-400 mb-4">error_outline</span>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Book Not Found</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-4">The requested book could not be found.</p>
                    <a href="index.php?page=inventrory" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover">
                        <span class="material-icons text-sm mr-2">arrow_back</span>
                        Back to Inventory
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>