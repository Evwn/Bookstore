<?php
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../controllers/AuthorController.php';
$loading = false;
$error = '';
$success = '';
$authors = getAllAuthors();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $author_id = intval($_POST['author_id'] ?? 0);
    $genre = trim($_POST['genre'] ?? '');
    $published_year = $_POST['published_year'] ?? null;
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $edition = trim($_POST['edition'] ?? '');
    $cover_url = trim($_POST['cover_url'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!$title || !$author_id || !$price) {
        $error = 'Title, author and price required';
    } else {

        if (addBook(
            $title,
            $author_id,
            $genre,
            $published_year,
            $price,
            $stock,
            $edition,
            $cover_url,
            $description
        )) {
            $success = "Book saved successfully";
            $_POST = []; // clears form
        } else {
            $error = "Database insert failed";
        }
    }
}

?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Add New Book - Admin Dashboard</title>
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
<img alt="Admin Avatar" class="h-8 w-8 rounded-full bg-slate-300" data-alt="User profile avatar image" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAbWM4RTaj5YleCt-NPgaymDPt85XXLWyTnSei_0usGIRsfr519tqc1KMpEzEx-2a9hjQ-w584IqMjDuARckZc-JJYqGeCGIIQx2iYFIsys6b8SyD_pz2mffDW5_qvmxKkLSkNRv6f0yvkFFlGPTiX1APpBOpY9TCN8w_ccF6qJSvaoK8izDHGd6UDCZ3pQDrn45G_pJnaLeOLUZC1JlC7jsKMqleEQ6wWyP3_3r92TJ__acuws0NTP4rMsvK9osMVeSHErzAGR4A"/>
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
<li><span aria-current="page" class="text-sm font-medium text-primary">Add New Book</span></li>
</ol>
</nav>
<!-- Page Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                        Add New Book
                    </h2>
<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Fill in the details below to add a new title to the bookstore database.
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
<form class="space-y-6" method="POST">
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
<input class="focus:ring-primary focus:border-primary block w-full pl-7 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10" id="isbn" maxlength="10" name="isbn" placeholder="0123456789" type="text" value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>"/>
</div>
<p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Must be exactly 10 characters.</p>
</div>
<!-- Title Field -->
<div class="sm:col-span-6">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="title">
                                    Book Title
                                </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="title" name="title" placeholder="e.g. The Great Gatsby" type="text" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"/>
<input type="text" name="genre" placeholder="Genre"
value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>">

<input type="text" name="published_year" placeholder="Published Year"
value="<?= htmlspecialchars($_POST['published_year'] ?? '') ?>">

<input type="text" name="price" placeholder="Price"
value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">

<input type="text" name="stock" placeholder="Stock"
value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">

<input type="text" name="edition" placeholder="Edition"
value="<?= htmlspecialchars($_POST['edition'] ?? '') ?>">

<input type="text" name="cover_url" placeholder="Image URL"
value="<?= htmlspecialchars($_POST['cover_url'] ?? '') ?>">

<textarea name="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>


</div>
</div>
</div>
</div>
<!-- Section 2: Pricing & Stats -->
<div class="p-6 sm:p-8 space-y-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">attach_money</span>
                            Pricing &amp; Fees
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
<input class="focus:ring-primary focus:border-primary block w-full pl-7 pr-12 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10" id="price" name="price" placeholder="0.00" type="number"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-500 sm:text-sm">USD</span>
</div>
</div>
</div>
<!-- Percentage Field -->
<div class="sm:col-span-3">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="percentage">
                                    Percentage
                                </label>
<div class="mt-1 relative rounded-md shadow-sm">
<input class="focus:ring-primary focus:border-primary block w-full pr-10 sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="percentage" name="percentage" placeholder="0" type="number"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-500 sm:text-sm">%</span>
</div>
</div>
<p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Royalty or Discount share.</p>
</div>
</div>
</div>
<!-- Section 3: Authors -->
<div class="p-6 sm:p-8 space-y-6">
<div class="flex items-center justify-between">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">group</span>
                                Authors
                            </h3>
<a class="text-xs font-medium text-primary hover:text-primary-hover flex items-center" href="#">
<span class="material-icons text-sm mr-1">add</span>
                                Add New Author
                            </a>
</div>
<div class="sm:col-span-6">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                                Select Author(s)
                            </label>
<!-- Custom Multi-select UI -->
<div class="border border-slate-300 dark:border-slate-700 rounded-lg p-4 bg-slate-50 dark:bg-slate-800/30">
<!-- Search bar for the list -->
<div class="relative mb-4">
<span class="absolute inset-y-0 left-0 flex items-center pl-3">
<span class="material-icons text-slate-400 text-lg">search</span>
</span>
<input class="w-full py-2 pl-10 pr-4 text-sm text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Search existing authors..." type="text"/>
</div>
<!-- Scrollable Checkbox List -->
<div class="max-h-48 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
<!-- Option 1 -->
<label class="flex items-center p-2 rounded-md hover:bg-white dark:hover:bg-slate-800 transition-colors cursor-pointer group">
<input class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" name="authors[]" type="checkbox" value="1"/>
<div class="ml-3 flex items-center">
<div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                JD
                                            </div>
<div class="ml-3">
<p class="text-sm font-medium text-slate-900 dark:text-slate-200 group-hover:text-primary">J.D. Salinger</p>
<p class="text-xs text-slate-500">New York, USA</p>
</div>
</div>
</label>
<!-- Option 2 (Selected) -->
<label class="flex items-center p-2 rounded-md bg-white dark:bg-slate-800 border border-primary/20 cursor-pointer group">
<input checked="" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" name="authors[]" type="checkbox" value="2"/>
<div class="ml-3 flex items-center">
<div class="flex-shrink-0 h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-bold text-xs">
                                                JK
                                            </div>
<div class="ml-3">
<p class="text-sm font-medium text-slate-900 dark:text-slate-200 group-hover:text-primary">J.K. Rowling</p>
<p class="text-xs text-slate-500">Yate, UK</p>
</div>
</div>
<span class="ml-auto text-xs font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-full">Selected</span>
</label>
<!-- Option 3 -->
<label class="flex items-center p-2 rounded-md hover:bg-white dark:hover:bg-slate-800 transition-colors cursor-pointer group">
<input class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" name="authors[]" type="checkbox" value="3"/>
<div class="ml-3 flex items-center">
<div class="flex-shrink-0 h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">
                                                SK
                                            </div>
<div class="ml-3">
<p class="text-sm font-medium text-slate-900 dark:text-slate-200 group-hover:text-primary">Stephen King</p>
<p class="text-xs text-slate-500">Maine, USA</p>
</div>
</div>
</label>
<!-- Option 4 -->
<label class="flex items-center p-2 rounded-md hover:bg-white dark:hover:bg-slate-800 transition-colors cursor-pointer group">
<!-- Author Dropdown -->
<select name="author_id" class="block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white h-10 px-3" required>
    <option value="">Select Author</option>
    <?php foreach ($authors as $author): ?>
        <option value="<?= $author['author_id'] ?>" <?= (isset($_POST['author_id']) && $_POST['author_id'] == $author['author_id']) ? 'selected' : '' ?>><?= htmlspecialchars($author['name']) ?></option>
    <?php endforeach; ?>
</select>
<div class="ml-3 flex items-center">
<div class="flex-shrink-0 h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs">
                                                GA
                                            </div>
<div class="ml-3">
<p class="text-sm font-medium text-slate-900 dark:text-slate-200 group-hover:text-primary">George R.R. Martin</p>
<p class="text-xs text-slate-500">New Jersey, USA</p>
</div>
</div>
</label>
</div>
<p class="mt-2 text-xs text-slate-500 dark:text-slate-400 flex items-center">
<span class="material-icons text-xs mr-1 text-slate-400">info</span>
                                    Scroll to see more authors
                                </p>
</div>
</div>
</div>
<!-- Form Actions -->
<div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between sm:px-8">
<button class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white" type="button">
                            Cancel
                        </button>
<button class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors" type="submit">
                            Save Book
                        </button>
</div>
</div>
</form>
<!-- Bottom Utility Action -->
<div class="mt-8 flex justify-center">
<button class="group inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-md text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="button">
<span class="material-icons text-slate-400 group-hover:text-primary mr-2 text-lg">code</span>
                    Display Source
                </button>
</div>
</div>
</main>
<style>
        /* Custom scrollbar for the authors list */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(19, 91, 236, 0.2);
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(19, 91, 236, 0.4);
        }
    </style>
</body></html>