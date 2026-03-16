<?php
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';
$loading = false;
$error = '';
$success = '';
$order = null;
$orderItems = [];
$customers = getAllCustomers();

if (!isset($_GET['id'])) {
    $error = 'No order selected.';
} else {
    $order = getOrderById(intval($_GET['id']));
    if (!$order) {
        $error = 'Order not found.';
    } else {
        $orderItems = getOrderItems($order['ORDER_ID']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order) {
    $loading = true;
    $customer_id = intval($_POST['customer_id'] ?? $order['CUSTOMER_ID']);
    $total_amount = floatval($_POST['total_amount'] ?? $order['TOTAL_AMOUNT']);
    $status = trim($_POST['status'] ?? $order['STATUS']);
    
    if ($customer_id && $total_amount && $status) {
        if (updateOrder($order['ORDER_ID'], $customer_id, $total_amount, $status)) {
            $success = 'Order updated successfully!';
            $order = getOrderById($order['ORDER_ID']);
        } else {
            $error = 'Failed to update order.';
        }
    } else {
        $error = 'All fields are required.';
    }
    $loading = false;
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Order - Bookstore Admin</title>
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
                        "primary-hover": "#104bc7",
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
<a class="border-primary text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=order">Orders</a>
</div>
</div>
<div class="flex items-center">
<div class="flex-shrink-0">
<button class="relative p-1 rounded-full text-slate-400 hover:text-slate-500 focus:outline-none" type="button">
<span class="sr-only">View notifications</span>
<span class="material-icons">notifications</span>
</button>
</div>
<div class="ml-3 relative flex items-center gap-2">
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
<!-- Order Information Card -->
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
<div class="px-4 py-5 sm:p-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
<span class="material-icons">shopping_cart</span>
                        Order Information
                    </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
<!-- Order ID -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="order_id">Order ID</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm bg-gray-50" disabled id="order_id" name="order_id" type="text" value="ORDER-<?= str_pad($order['ORDER_ID'] ?? '', 5, '0', STR_PAD_LEFT) ?>"/>
</div>
<!-- Order Date -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="order_date">Order Date</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm bg-gray-50" disabled id="order_date" name="order_date" type="text" value="<?= htmlspecialchars($order['ORDER_DATE'] ?? '') ?>"/>
</div>
<!-- Customer -->
<div class="sm:col-span-2">
<label class="block text-sm font-medium text-slate-700 mb-1" for="customer_id">Customer</label>
<select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="customer_id" name="customer_id" required>
    <option value="">Select Customer</option>
    <?php foreach ($customers as $customer): ?>
        <option value="<?= $customer['CUSTOMER_ID'] ?>" <?= ($order && $order['CUSTOMER_ID'] == $customer['CUSTOMER_ID']) ? 'selected' : '' ?>><?= htmlspecialchars($customer['NAME']) ?> (<?= htmlspecialchars($customer['EMAIL']) ?>)</option>
    <?php endforeach; ?>
</select>
</div>
<!-- Total Amount -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="total_amount">Total Amount</label>
<input class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="total_amount" name="total_amount" type="number" step="0.01" min="0" value="<?= htmlspecialchars($order['TOTAL_AMOUNT'] ?? '') ?>" required/>
</div>
<!-- Status -->
<div>
<label class="block text-sm font-medium text-slate-700 mb-1" for="status">Order Status</label>
<select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" id="status" name="status" required>
<option value="Pending" <?= ($order && $order['STATUS'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
<option value="Processing" <?= ($order && $order['STATUS'] == 'Processing') ? 'selected' : '' ?>>Processing</option>
<option value="Shipped" <?= ($order && $order['STATUS'] == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
<option value="Delivered" <?= ($order && $order['STATUS'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
<option value="Cancelled" <?= ($order && $order['STATUS'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
</select>
</div>
</div>
</div>
</div>

<!-- Order Items Section -->
<?php if (!empty($orderItems)): ?>
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-slate-900 flex items-center gap-2 mb-6">
            <span class="material-icons">shopping_cart</span>
            Order Items
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">Book</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">Author</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">ISBN</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">Quantity</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">Price</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-900"><?= htmlspecialchars($item['TITLE']) ?></td>
                            <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($item['AUTHOR_NAME'] ?? 'Unknown') ?></td>
                            <td class="px-4 py-3 text-slate-600 font-mono text-sm"><?= htmlspecialchars($item['ISBN'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3 text-slate-600"><?= $item['QUANTITY'] ?></td>
                            <td class="px-4 py-3 text-slate-600">$<?= number_format($item['PRICE'], 2) ?></td>
                            <td class="px-4 py-3 font-semibold text-slate-900">$<?= number_format($item['QUANTITY'] * $item['PRICE'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

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
</div>
</main>
</body>
</html>