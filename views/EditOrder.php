<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Order - Bookstore Admin</title>
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
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700" href="index.php?page=order">Orders</a></li>
<li><span class="text-slate-300">/</span></li>
<li><a aria-current="page" class="text-sm font-medium text-primary" href="#">Edit Order</a></li>
</ol>
</nav>
<h2 class="mt-2 text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">Edit Order</h2>
<p class="mt-1 text-sm text-slate-500">Update order details and status.</p>
</div>
</div>
<!-- Form -->
<form action="#" class="space-y-6" method="POST">
<!-- Order Information Card -->
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
<div class="px-4 py-5 sm:p-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
<span class="material-icons">shopping_cart</span>
                        Order Information
                    </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
<!-- Order Number -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="ordernumber">Order Number</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" disabled id="ordernumber" name="ordernumber" type="text" value="ORD-2024-001234"/>
</div>
<!-- Order Date -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="orderdate">Order Date</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" disabled id="orderdate" name="orderdate" type="date" value="2024-02-15"/>
</div>
<!-- Customer Name -->
<div class="sm:col-span-2">
<label class="block text-sm font-medium text-slate-700 mb-1" for="customer">Customer Name</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="customer" name="customer" placeholder="Enter customer name" type="text" value="John Smith"/>
</div>
<!-- Status -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="status">Order Status</label>
<select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="status" name="status">
<option>Pending</option>
<option selected>Processing</option>
<option>Shipped</option>
<option>Delivered</option>
<option>Cancelled</option>
</select>
</div>
<!-- Payment Status -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="payment">Payment Status</label>
<select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="payment" name="payment">
<option>Unpaid</option>
<option selected>Paid</option>
<option>Refunded</option>
</select>
</div>
</div>
</div>
</div>
<!-- Order Items Card -->
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
<div class="px-4 py-5 sm:p-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
<span class="material-icons">inventory_2</span>
                        Order Items
                    </h3>
<div class="space-y-4">
<!-- Item 1 -->
<div class="border border-slate-200 rounded-lg p-4 space-y-3">
<div class="grid grid-cols-1 gap-y-3 gap-x-4 sm:grid-cols-3">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="item1">Book Title</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="item1" name="item1" placeholder="Enter book title" type="text" value="1984"/>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="qty1">Quantity</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="qty1" min="1" name="qty1" type="number" value="2"/>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="price1">Price</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="price1" name="price1" type="number" value="15.99"/>
</div>
</div>
</div>
<!-- Item 2 -->
<div class="border border-slate-200 rounded-lg p-4 space-y-3">
<div class="grid grid-cols-1 gap-y-3 gap-x-4 sm:grid-cols-3">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="item2">Book Title</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="item2" name="item2" placeholder="Enter book title" type="text" value="Animal Farm"/>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="qty2">Quantity</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="qty2" min="1" name="qty2" type="number" value="1"/>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="price2">Price</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="price2" name="price2" type="number" value="12.99"/>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Totals Card -->
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
<div class="px-4 py-5 sm:p-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
<span class="material-icons">receipt_long</span>
                        Order Totals
                    </h3>
<div class="space-y-3">
<div class="flex justify-between text-sm">
<span class="text-slate-600">Subtotal:</span>
<span class="text-slate-900 font-medium">$44.97</span>
</div>
<div class="flex justify-between text-sm">
<span class="text-slate-600">Shipping:</span>
<input class="w-20 px-2 py-1 border border-slate-300 rounded text-right" name="shipping" type="number" value="5.00"/>
</div>
<div class="flex justify-between text-sm">
<span class="text-slate-600">Tax:</span>
<input class="w-20 px-2 py-1 border border-slate-300 rounded text-right" name="tax" type="number" value="4.00"/>
</div>
<div class="border-t border-slate-200 pt-3 flex justify-between text-lg font-bold">
<span>Total:</span>
<span class="text-primary">$53.97</span>
</div>
</div>
</div>
</div>
<!-- Buttons -->
<div class="flex items-center justify-between pt-6">
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none transition-all" type="button">
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
</div>
</main>
</body>
</html>
