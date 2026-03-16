<?php
require_once __DIR__ . '/../controllers/CustomerController.php';
$loading = false;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loading = true;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!$name || !$email) {
        $error = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        if (addCustomer($name, $email, $phone, $address)) {
            $success = 'Customer added successfully!';
            // Redirect to customer list after 2 seconds
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=customer"; }, 2000);</script>';
            $_POST = []; // Clear form
        } else {
            $error = 'Failed to add customer.';
        }
    }
    $loading = false;
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Add New Customer - BookStore Admin</title>
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
<a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=inventrory">Inventory</a>
<a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=authordetails">Authors</a>
<a class="border-primary text-slate-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=customer">Customers</a>
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
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" href="index.php?page=customer">Customers</a></li>
<li><span class="text-slate-300">/</span></li>
<li><span aria-current="page" class="text-sm font-medium text-primary">Add New Customer</span></li>
</ol>
</nav>
<!-- Page Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                        Add New Customer
                    </h2>
<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Fill in the customer details below to add them to the database.
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
<!-- Section 1: Personal Information -->
<div class="p-6 sm:p-8 space-y-6 border-b border-slate-200 dark:border-slate-800">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">person</span>
                            Personal Information
                        </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
<!-- Name Field -->
<div class="sm:col-span-2">
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="name">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="name" name="name" placeholder="e.g. John Doe" type="text" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required/>
</div>
</div>
<!-- Email Field -->
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="email">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="email" name="email" placeholder="e.g. john@example.com" type="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
</div>
</div>
<!-- Phone Field -->
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="phone">
                                    Phone Number
                                </label>
<div class="mt-1">
<input class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md h-10 px-3" id="phone" name="phone" placeholder="e.g. +1 (555) 123-4567" type="tel" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"/>
</div>
</div>
</div>
</div>
<!-- Section 2: Address Information -->
<div class="p-6 sm:p-8 space-y-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">location_on</span>
                            Address Information
                        </h3>
<div class="space-y-6">
<!-- Address Field -->
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="address">
                                    Address
                                </label>
<div class="mt-1">
<textarea class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md px-3 py-2" id="address" name="address" rows="3" placeholder="Enter full address..."><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
</div>
</div>
</div>
</div>
</div>
<!-- Form Actions -->
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
<span class="material-icons text-lg mr-2">person_add</span>
                            Add Customer
                        </button>
</div>
</div>
</form>
</div>
</main>
</body>
</html>