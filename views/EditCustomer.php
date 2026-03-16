<?php
require_once __DIR__ . '/../controllers/CustomerController.php';
$loading = false;
$error = '';
$success = '';
$customer = null;

if (!isset($_GET['id'])) {
    $error = 'No customer selected.';
} else {
    $customer = getCustomerById(intval($_GET['id']));
    if (!$customer) {
        $error = 'Customer not found.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $customer) {
    $loading = true;
    $name = trim($_POST['name'] ?? $customer['NAME']);
    $email = trim($_POST['email'] ?? $customer['EMAIL']);
    $phone = trim($_POST['phone'] ?? $customer['PHONE']);
    $address = trim($_POST['address'] ?? $customer['ADDRESS']);
    if ($name && $email) {
        if (updateCustomer($customer['CUSTOMER_ID'], $name, $email, $phone, $address)) {
            $success = 'Customer updated successfully!';
            $customer = getCustomerById($customer['CUSTOMER_ID']);
        } else {
            $error = 'Failed to update customer.';
        }
    } else {
        $error = 'Name and email required.';
    }
    $loading = false;
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Customer - Bookstore Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#104bc7",
                        "primary-light": "rgba(19, 91, 236, 0.1)",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light text-slate-800 font-display antialiased min-h-screen flex flex-col">
<nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16">
<div class="flex">
<div class="flex-shrink-0 flex items-center gap-3">
<div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white">
<span class="material-icons text-xl">menu_book</span>
</div>
<span class="font-bold text-xl tracking-tight text-slate-900">BookStore<span class="text-primary">Admin</span></span>
</div>
<div class="hidden sm:ml-8 sm:flex sm:space-x-8">
<a class="border-transparent text-slate-500 hover:border-primary hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=dashboard">Dashboard</a>
<a class="border-transparent text-slate-500 hover:border-primary hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=inventrory">Inventory</a>
<a class="border-transparent text-slate-500 hover:border-primary hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=order">Orders</a>
</div>
</div>
<div class="flex items-center">
<div class="flex-shrink-0">
<button class="relative p-1 rounded-full text-slate-400 hover:text-slate-500 focus:outline-none" type="button">
<span class="sr-only">View notifications</span>
<span class="material-icons">notifications</span>
<span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
</button>
</div>
<div class="ml-3 relative flex items-center gap-2">
<img alt="Admin Profile Picture" class="h-8 w-8 rounded-full bg-slate-100" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcqJ8KRwrEve0Cw0AXwwBTYJbFk9BOlQfP-inWjEG2GEDo9Ej0jUAreHPwB6uY7Eh70EQ7m21DHU1nUe9s_sUKfwIQSIVthASJSy41O8J2dKwxb7ibCKRdSugX8fuJdUuj5KKknmahRo55GTFjBCND_2-QWZyV0fnMHVvOKK0sG3YRzR6zhqLzhIa-E9TZog9sklL3CgptvHBe8APMKV24IglNf9HnSYXoJeMnjS9e0KPdh8skExmjlgWCiu7-TUaVKxloC_h5gg"/>
<span class="text-sm font-medium text-slate-700 hidden md:block">Admin User</span>
</div>
</div>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="flex-1 py-10">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
<!-- Header Section -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<nav aria-label="Breadcrumb" class="flex">
<ol class="flex items-center space-x-2" role="list">
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700" href="index.php?page=dashboard">Dashboard</a></li>
<li><span class="text-slate-300">/</span></li>
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700" href="index.php?page=customer">Customers</a></li>
<li><span class="text-slate-300">/</span></li>
<li><a aria-current="page" class="text-sm font-medium text-primary" href="#">Edit Customer</a></li>
</ol>
</nav>
<h2 class="mt-2 text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">Edit Customer</h2>
<p class="mt-1 text-sm text-slate-500">Update customer information and details.</p>
</div>
</div>
<!-- Form -->
<?php if ($loading): ?>
    <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">Loading...</div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($customer): ?>
    <div class="mb-4 p-3 rounded bg-blue-100 text-blue-800">
        <strong>Editing:</strong> <?php echo htmlspecialchars($customer['NAME']); ?> (ID: <?php echo $customer['CUSTOMER_ID']; ?>)
    </div>
<?php endif; ?>
<?php if ($customer): ?>
<form action="#" class="space-y-6" method="POST">
<!-- Personal Information Card -->
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
<div class="px-4 py-5 sm:p-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
<span class="material-icons">person</span>
                        Personal Information
                    </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
<!-- Full Name -->
<div class="sm:col-span-2">
<label class="block text-sm font-medium text-slate-700 mb-1" for="name">Full Name <span class="text-red-500">*</span></label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="name" name="name" placeholder="Enter full name" type="text" value="<?= htmlspecialchars($customer['NAME'] ?? '') ?>" required/>
</div>
<!-- Email -->
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1" for="email">Email Address <span class="text-red-500">*</span></label>
    <input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="email" name="email" placeholder="Enter email" type="email" value="<?= htmlspecialchars($customer['EMAIL'] ?? '') ?>" required/>
</div>
<!-- Phone -->
<div>
    <label class="block text-sm font-medium text-slate-700 mb-1" for="phone">Phone Number</label>
    <input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="phone" name="phone" placeholder="Enter phone" type="tel" value="<?= htmlspecialchars($customer['PHONE'] ?? '') ?>"/>
</div>
</div>
</div>
</div>
<!-- Address Information Card -->
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
<div class="px-4 py-5 sm:p-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
<span class="material-icons">location_on</span>
                        Address Information
                    </h3>
<div class="space-y-6">
<!-- Address -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="address">Address</label>
<textarea class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="address" name="address" placeholder="Enter full address" rows="3"><?= htmlspecialchars($customer['ADDRESS'] ?? '') ?></textarea>
</div>
</div>
</div>
</div>
<!-- Buttons -->
<div class="flex items-center justify-between pt-6">
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none transition-all" type="button" onclick="window.history.back()">
<span class="material-icons text-lg mr-2">arrow_back</span>
                    Back
                </button>
<div class="flex gap-3">
<button class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg shadow-sm text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-all" type="reset">
<span class="material-icons text-lg mr-2">refresh</span>
                        Reset
                    </button>
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none transition-all" type="submit">
<span class="material-icons text-lg mr-2">save</span>
                        Save Changes
                    </button>
</div>
</div>
</form>
<?php else: ?>
<div class="bg-white shadow-sm rounded-lg border border-slate-200 p-8 text-center">
    <span class="material-icons text-4xl text-slate-400 mb-4">person_off</span>
    <h3 class="text-lg font-medium text-slate-900 mb-2">Customer Not Found</h3>
    <p class="text-slate-500 mb-4">The customer you're trying to edit could not be found.</p>
    <a href="index.php?page=customer" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition">
        <span class="material-icons text-sm mr-2">arrow_back</span>
        Back to Customers
    </a>
</div>
<?php endif; ?>
</div>
</main>
</body>
</html>
