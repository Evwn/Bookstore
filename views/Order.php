<?php
require_once __DIR__ . '/../controllers/OrderController.php';
$loading = false;
$error = '';
$success = '';
$orders = [];

// Handle add, delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loading = true;
    if (isset($_POST['add_order'])) {
        $customer_id = intval($_POST['customer_id'] ?? 0);
        $total_amount = floatval($_POST['total_amount'] ?? 0);
        $status = trim($_POST['status'] ?? 'Pending');
        if ($customer_id && $total_amount) {
            if (addOrder($customer_id, $total_amount, $status)) {
                $success = 'Order added.';
            } else {
                $error = 'Failed to add order.';
            }
        } else {
            $error = 'Customer and amount required.';
        }
    } elseif (isset($_POST['delete_order'])) {
        $id = intval($_POST['delete_order']);
        if (deleteOrder($id)) {
            $success = 'Order deleted.';
        } else {
            $error = 'Failed to delete order.';
        }
    }
    $loading = false;
}

$orders = getAllOrders();
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Add New Order - Bookstore Admin</title>
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
<style type="text/tailwindcss">
        @layer base {
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
    </style>
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
<a class="border-primary text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=order">Orders</a>
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
<main class="flex-1 py-10">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<nav aria-label="Breadcrumb" class="flex">
<ol class="flex items-center space-x-2" role="list">
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700" href="#">Orders</a></li>
<li><span class="text-slate-300">/</span></li>
<li><a aria-current="page" class="text-sm font-medium text-primary" href="#">Add New Order</a></li>
</ol>
</nav>
<h2 class="mt-2 text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                        Create New Sales Order
                    </h2>
<p class="mt-1 text-sm text-slate-500">Record a manual sale and update inventory levels automatically.</p>
</div>
<div class="mt-4 flex md:mt-0 md:ml-4">
<button class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="button">
<span class="material-icons text-base mr-2 text-slate-500">code</span>
                        Display Source
                    </button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<div class="lg:col-span-2 space-y-6">
<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
<h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
<span class="material-symbols-outlined mr-2 text-primary">search</span>
                            Select Book
                        </h3>
<div class="relative">
<label class="sr-only" for="book-search">Search Book</label>
<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
<span class="material-icons text-slate-400">search</span>
</div>
<input class="block w-full rounded-lg border-slate-300 pl-10 pr-12 py-3 text-slate-900 focus:border-primary focus:ring-primary sm:text-sm" id="book-search" placeholder="Search by title, author, or ISBN..." type="text"/>
<div class="absolute inset-y-0 right-0 flex items-center pr-3">
<kbd class="hidden sm:inline-flex items-center rounded border border-slate-200 px-1 font-sans text-xs text-slate-400">⌘K</kbd>
</div>
</div>
<div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-4">
<div class="h-24 w-16 flex-shrink-0 rounded bg-slate-200 overflow-hidden shadow-sm relative">
<div class="absolute inset-0 bg-gradient-to-br from-blue-200 to-indigo-300"></div>
</div>
<div class="flex-1">
<h4 class="text-lg font-bold text-slate-900">Clean Code</h4>
<p class="text-sm text-slate-500">Robert C. Martin • ISBN: 978-0132350884</p>
<div class="mt-3 flex gap-6">
<div>
<span class="block text-xs font-medium uppercase tracking-wider text-slate-400">Current Price</span>
<span class="text-lg font-bold text-slate-900">$44.99</span>
</div>
<div>
<span class="block text-xs font-medium uppercase tracking-wider text-slate-400">In Stock</span>
<span class="text-lg font-bold text-emerald-600">15 units</span>
</div>
</div>
</div>
<button class="text-slate-400 hover:text-red-500 transition-colors">
<span class="material-icons">close</span>
</button>
</div>
</div>
<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
<h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
<span class="material-symbols-outlined mr-2 text-primary">list_alt</span>
                            Order Details
                        </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-medium text-slate-700" for="quantity">Quantity Ordered</label>
<div class="mt-1 relative rounded-md shadow-sm">
<input class="block w-full rounded-lg border-slate-300 focus:ring-primary focus:border-primary sm:text-sm py-2.5" id="quantity" min="1" name="quantity" placeholder="1" type="number" value="1"/>
</div>
</div>
<div>
<label class="block text-sm font-medium text-slate-700" for="sale-date">Date of Sale</label>
<div class="mt-1 relative rounded-md shadow-sm">
<input class="block w-full rounded-lg border-slate-300 focus:ring-primary focus:border-primary sm:text-sm py-2.5" id="sale-date" name="sale-date" type="date" value="2023-11-15"/>
</div>
</div>
<div class="md:col-span-2">
<label class="block text-sm font-medium text-slate-700" for="customer-name">Customer Name (Optional)</label>
<div class="mt-1">
<input class="block w-full rounded-lg border-slate-300 focus:ring-primary focus:border-primary sm:text-sm py-2.5" id="customer-name" name="customer-name" placeholder="e.g. John Doe" type="text"/>
</div>
</div>
</div>
</div>
</div>
<div class="lg:col-span-1">
<div class="bg-white shadow-sm rounded-xl border border-slate-200 sticky top-24 overflow-hidden">
<div class="p-6 border-b border-slate-100 bg-slate-50/50">
<h3 class="text-lg font-semibold text-slate-900">Order Summary</h3>
</div>
<div class="p-6 space-y-4">
<div class="flex justify-between text-sm">
<span class="text-slate-500">Unit Price</span>
<span class="text-slate-900 font-medium">$44.99</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-slate-500">Quantity</span>
<span class="text-slate-900 font-medium">x 1</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-slate-500">Subtotal</span>
<span class="text-slate-900 font-medium">$44.99</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-slate-500">Tax (0%)</span>
<span class="text-slate-900 font-medium">$0.00</span>
</div>
<div class="pt-4 border-t border-slate-100 flex justify-between items-end">
<span class="text-base font-semibold text-slate-900">Total Price</span>
<span class="text-2xl font-bold text-primary">$44.99</span>
</div>
<div class="pt-6">
<button class="w-full flex justify-center items-center px-6 py-3.5 border border-transparent text-base font-semibold rounded-xl shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="submit">
<span class="material-icons mr-2">shopping_cart_checkout</span>
                                    Place Order
                                </button>
<p class="mt-3 text-center text-xs text-slate-400">
                                    Clicking 'Place Order' will reduce stock and update sales analytics.
                                </p>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
<footer class="bg-white border-t border-slate-200 py-6 mt-10">
<div class="max-w-7xl mx-auto px-4 text-center">
<p class="text-sm text-slate-500">© 2023 BookStore Inventory Management System. All rights reserved.</p>
</div>
</footer>

</body></html>