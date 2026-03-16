<?php
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';
$loading = false;
$error = '';
$success = '';
$customers = getAllCustomers();
$books = getAvailableBooks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loading = true;
    $customer_id = intval($_POST['customer_id'] ?? 0);
    $status = trim($_POST['status'] ?? 'Pending');
    
    // Process order items
    $items = [];
    if (isset($_POST['book_ids']) && is_array($_POST['book_ids'])) {
        foreach ($_POST['book_ids'] as $index => $book_id) {
            $quantity = intval($_POST['quantities'][$index] ?? 0);
            $price = floatval($_POST['prices'][$index] ?? 0);
            
            if ($book_id && $quantity > 0 && $price > 0) {
                $items[] = [
                    'book_id' => intval($book_id),
                    'quantity' => $quantity,
                    'price' => $price
                ];
            }
        }
    }
    
    if (!$customer_id) {
        $error = 'Please select a customer.';
    } elseif (empty($items)) {
        $error = 'Please add at least one book to the order.';
    } else {
        $order_id = addOrderWithItems($customer_id, $items, $status);
        if ($order_id) {
            $success = 'Order created successfully! Order ID: ' . $order_id;
            // Redirect to order list after 2 seconds
            echo '<script>setTimeout(function(){ window.location.href = "index.php?page=order"; }, 2000);</script>';
        } else {
            $error = 'Failed to create order. Please check book availability.';
        }
    }
    $loading = false;
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Create New Order - BookStore Admin</title>
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
<li><span aria-current="page" class="text-sm font-medium text-primary">Create New Order</span></li>
</ol>
</nav>
<!-- Page Header -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:text-3xl sm:truncate">
                        Create New Order
                    </h2>
<p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Create a new order by selecting customer and books.
                    </p>
</div>
</div>
<?php if ($loading): ?>
    <div class="mb-4 p-3 bg-blue-100 text-blue-800 rounded">Processing order...</div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form class="space-y-6" method="POST" id="orderForm">
<!-- Customer Selection -->
<div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
<div class="p-6 sm:p-8 space-y-6">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">person</span>
                            Customer Information
                        </h3>
<div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="customer_id">
                                    Customer <span class="text-red-500">*</span>
                                </label>
<select class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-primary focus:ring-primary sm:text-sm" id="customer_id" name="customer_id" required>
<option value="">Select a customer</option>
<?php foreach ($customers as $customer): ?>
<option value="<?= $customer['CUSTOMER_ID'] ?>"><?= htmlspecialchars($customer['NAME']) ?> (<?= htmlspecialchars($customer['EMAIL']) ?>)</option>
<?php endforeach; ?>
</select>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="status">
                                    Order Status
                                </label>
<select class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-primary focus:ring-primary sm:text-sm" id="status" name="status">
<option value="Pending">Pending</option>
<option value="Processing">Processing</option>
<option value="Shipped">Shipped</option>
<option value="Delivered">Delivered</option>
</select>
</div>
</div>
</div>
</div>
<!-- Order Items -->
<div class="bg-white dark:bg-slate-900 shadow-sm rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
<div class="p-6 sm:p-8 space-y-6">
<div class="flex items-center justify-between">
<h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white flex items-center gap-2">
<span class="material-icons text-primary text-xl">shopping_cart</span>
                                Order Items
                            </h3>
<button type="button" onclick="addOrderItem()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
<span class="material-icons text-sm mr-1">add</span>
                                Add Book
                            </button>
</div>
<div id="orderItems" class="space-y-4">
<!-- Order items will be added here dynamically -->
</div>
<div class="border-t border-slate-200 dark:border-slate-700 pt-4">
<div class="flex justify-between items-center text-lg font-semibold">
<span>Total Amount:</span>
<span id="totalAmount" class="text-primary">$0.00</span>
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
<button class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg shadow-sm text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all dark:border-slate-600" type="reset" onclick="resetForm()">
<span class="material-icons text-lg mr-2">refresh</span>
                            Reset
                        </button>
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="submit">
<span class="material-icons text-lg mr-2">shopping_cart_checkout</span>
                            Create Order
                        </button>
</div>
</div>
</form>
</div>
</main>

<script>
let itemCount = 0;
const books = <?= json_encode($books) ?>;

function addOrderItem() {
    itemCount++;
    const container = document.getElementById('orderItems');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'border border-slate-200 dark:border-slate-700 rounded-lg p-4 space-y-4';
    itemDiv.id = `item-${itemCount}`;
    
    itemDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-medium text-slate-900 dark:text-white">Book ${itemCount}</h4>
            <button type="button" onclick="removeOrderItem(${itemCount})" class="text-red-500 hover:text-red-700">
                <span class="material-icons text-sm">delete</span>
            </button>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Book</label>
                <select name="book_ids[]" onchange="updatePrice(${itemCount})" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
                    <option value="">Select a book</option>
                    ${books.map(book => `<option value="${book.BOOK_ID}" data-price="${book.PRICE}" data-stock="${book.STOCK}">${book.TITLE} by ${book.AUTHOR_NAME} - $${book.PRICE} (Stock: ${book.STOCK})</option>`).join('')}
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Quantity</label>
                <input type="number" name="quantities[]" min="1" onchange="calculateTotal()" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-primary focus:ring-primary sm:text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Price</label>
                <input type="number" name="prices[]" step="0.01" min="0" readonly class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm bg-slate-50 dark:bg-slate-700 sm:text-sm">
            </div>
        </div>
    `;
    
    container.appendChild(itemDiv);
}

function removeOrderItem(id) {
    const item = document.getElementById(`item-${id}`);
    if (item) {
        item.remove();
        calculateTotal();
    }
}

function updatePrice(itemId) {
    const item = document.getElementById(`item-${itemId}`);
    const bookSelect = item.querySelector('select[name="book_ids[]"]');
    const priceInput = item.querySelector('input[name="prices[]"]');
    const quantityInput = item.querySelector('input[name="quantities[]"]');
    
    const selectedOption = bookSelect.options[bookSelect.selectedIndex];
    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        const stock = selectedOption.getAttribute('data-stock');
        priceInput.value = price;
        quantityInput.max = stock;
        calculateTotal();
    } else {
        priceInput.value = '';
        quantityInput.max = '';
    }
}

function calculateTotal() {
    const quantities = document.querySelectorAll('input[name="quantities[]"]');
    const prices = document.querySelectorAll('input[name="prices[]"]');
    let total = 0;
    
    for (let i = 0; i < quantities.length; i++) {
        const qty = parseFloat(quantities[i].value) || 0;
        const price = parseFloat(prices[i].value) || 0;
        total += qty * price;
    }
    
    document.getElementById('totalAmount').textContent = `$${total.toFixed(2)}`;
}

function resetForm() {
    document.getElementById('orderItems').innerHTML = '';
    itemCount = 0;
    document.getElementById('totalAmount').textContent = '$0.00';
}

// Add first item by default
document.addEventListener('DOMContentLoaded', function() {
    addOrderItem();
});
</script>
</body>
</html>