<?php
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';
$loading = false;
$error = '';
$success = '';
$orders = getAllOrders();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loading = true;
    if (isset($_POST['delete_order'])) {
        $id = intval($_POST['delete_order']);
        if (deleteOrder($id)) {
            $success = 'Order deleted successfully.';
        } else {
            $error = 'Failed to delete order.';
        }
    }
    $loading = false;
    $orders = getAllOrders();
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Order Management - BookStore Admin</title>
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
<a class="border-transparent text-slate-500 hover:border-primary hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=customer">Customers</a>
<a class="border-primary text-slate-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=order">Orders</a>
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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<!-- Breadcrumbs -->
<nav aria-label="Breadcrumb" class="flex mb-5">
<ol class="flex items-center space-x-2">
<li><a class="text-slate-400 hover:text-slate-500" href="index.php?page=dashboard"><span class="material-icons text-sm">home</span></a></li>
<li><span class="text-slate-300">/</span></li>
<li><span aria-current="page" class="text-sm font-medium text-primary">Orders</span></li>
</ol>
</nav>
<!-- Page Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                        Order Management
                    </h2>
<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Manage customer orders and book sales.
                    </p>
</div>
<div class="mt-4 flex md:mt-0 md:ml-4">
<a href="index.php?page=addorder" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
<span class="material-icons text-sm mr-2">add_shopping_cart</span>
                        Create New Order
                    </a>
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

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
<div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
<h2 class="text-xl font-bold text-slate-900">All Orders</h2>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-slate-50 dark:bg-slate-800/50">
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Order ID</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Customer</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Order Date</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Items</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Total Amount</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Status</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr class="hover:bg-primary/5 transition-colors group">
                <td class="px-6 py-4 font-mono text-sm text-slate-600 dark:text-slate-400">ORDER-<?php echo str_pad($order['ORDER_ID'], 5, '0', STR_PAD_LEFT); ?></td>
                <td class="px-6 py-4">
                    <div class="font-medium text-slate-900 dark:text-white"><?php echo htmlspecialchars($order['CUSTOMER_NAME'] ?? 'Unknown Customer'); ?></div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                    <?php echo date('M j, Y', strtotime($order['ORDER_DATE'])); ?>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                    <?php echo ($order['ITEM_COUNT'] ?? 0); ?> items (<?php echo ($order['TOTAL_BOOKS'] ?? 0); ?> books)
                </td>
                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">$<?php echo number_format($order['TOTAL_AMOUNT'] ?? 0, 2); ?></td>
                <td class="px-6 py-4">
                    <?php 
                    $status = $order['STATUS'] ?? 'Pending';
                    $statusColors = [
                        'Pending' => 'bg-yellow-100 text-yellow-800',
                        'Processing' => 'bg-blue-100 text-blue-800',
                        'Shipped' => 'bg-purple-100 text-purple-800',
                        'Delivered' => 'bg-green-100 text-green-800',
                        'Cancelled' => 'bg-red-100 text-red-800'
                    ];
                    $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $colorClass; ?>">
                        <?php echo htmlspecialchars($status); ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="index.php?page=orderdetails&id=<?php echo $order['ORDER_ID']; ?>" class="text-primary hover:text-primary-hover mr-3 text-sm">View</a>
                    <a href="index.php?page=editorder&id=<?php echo $order['ORDER_ID']; ?>" class="text-primary hover:text-primary-hover mr-3 text-sm">Edit</a>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="delete_order" value="<?php echo $order['ORDER_ID']; ?>" class="text-red-400 hover:text-red-600 transition-colors text-sm" onclick="return confirm('Are you sure you want to delete this order? This will restore book stock.');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($orders) === 0): ?>
            <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">No orders found. <a href="index.php?page=addorder" class="text-primary hover:underline">Create your first order</a>.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>
</main>
</body>
</html>