<?php
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';
$error = '';
$order = null;
$orderItems = [];

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Order Details - BookStore Admin</title>
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="flex mb-5">
                <ol class="flex items-center space-x-2">
                    <li><a class="text-slate-400 hover:text-slate-500" href="index.php?page=dashboard"><span class="material-icons text-sm">home</span></a></li>
                    <li><span class="text-slate-300">/</span></li>
                    <li><a class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200" href="index.php?page=order">Orders</a></li>
                    <li><span class="text-slate-300">/</span></li>
                    <li><span aria-current="page" class="text-sm font-medium text-primary">Order Details</span></li>
                </ol>
            </nav>

            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
                <div class="text-center">
                    <a href="index.php?page=order" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover">
                        <span class="material-icons text-sm mr-2">arrow_back</span>
                        Back to Orders
                    </a>
                </div>
            <?php else: ?>
                <!-- Page Header -->
                <div class="md:flex md:items-center md:justify-between mb-8">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                            Order Details - ORDER-<?= str_pad($order['ORDER_ID'], 5, '0', STR_PAD_LEFT) ?>
                        </h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Complete order information and items.
                        </p>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
                        <a href="index.php?page=editorder&id=<?= $order['ORDER_ID'] ?>" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700">
                            <span class="material-icons text-sm mr-2">edit</span>
                            Edit Order
                        </a>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-icons text-primary">info</span>
                            Order Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Order ID</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-white font-mono">ORDER-<?= str_pad($order['ORDER_ID'], 5, '0', STR_PAD_LEFT) ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Order Date</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-white"><?= date('M j, Y g:i A', strtotime($order['ORDER_DATE'])) ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Amount</dt>
                                <dd class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">$<?= number_format($order['TOTAL_AMOUNT'], 2) ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</dt>
                                <dd class="mt-1">
                                    <?php 
                                    $status = $order['STATUS'];
                                    $statusColors = [
                                        'Pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'Processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'Shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'Delivered' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'Cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    ];
                                    $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-icons text-primary">person</span>
                            Customer Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Customer Name</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-white"><?= htmlspecialchars($order['CUSTOMER_NAME'] ?? 'Unknown Customer') ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Email</dt>
                                <dd class="mt-1 text-sm text-slate-900 dark:text-white"><?= htmlspecialchars($order['CUSTOMER_EMAIL'] ?? 'N/A') ?></dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-icons text-primary">shopping_cart</span>
                            Order Items (<?= count($orderItems) ?> items)
                        </h3>
                    </div>
                    <?php if (empty($orderItems)): ?>
                        <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                            No items found for this order.
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Book</th>
                                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Author</th>
                                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">ISBN</th>
                                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Quantity</th>
                                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Unit Price</th>
                                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr class="hover:bg-primary/5 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900 dark:text-white"><?= htmlspecialchars($item['TITLE']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($item['AUTHOR_NAME'] ?? 'Unknown') ?></td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-sm"><?= htmlspecialchars($item['ISBN'] ?? 'N/A') ?></td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400"><?= $item['QUANTITY'] ?></td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">$<?= number_format($item['PRICE'], 2) ?></td>
                                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">$<?= number_format($item['QUANTITY'] * $item['PRICE'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                                        <td colspan="5" class="px-6 py-4 text-right font-semibold text-slate-900 dark:text-white">Total:</td>
                                        <td class="px-6 py-4 font-bold text-lg text-primary">$<?= number_format($order['TOTAL_AMOUNT'], 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6">
                    <a href="index.php?page=order" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none transition-all">
                        <span class="material-icons text-lg mr-2">arrow_back</span>
                        Back to Orders
                    </a>
                    <div class="flex gap-3">
                        <a href="index.php?page=editorder&id=<?= $order['ORDER_ID'] ?>" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg shadow-sm text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none transition-all">
                            <span class="material-icons text-lg mr-2">edit</span>
                            Edit Order
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>