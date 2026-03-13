<?php
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/CustomerController.php';
$loading = false;
$error = '';
$success = '';
$orders = getAllOrders();
$customers = getAllCustomers();

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
    $orders = getAllOrders();
}
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
                        <?php
                        require_once __DIR__ . '/../controllers/OrderController.php';
                        $orders = getAllOrders();
                        ?>
                        <h2 class="text-2xl font-bold mb-4">Create New Order</h2>
                        <?php if ($error): ?>
                            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>
                        <form method="post" class="mb-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-xl">
                            <div class="mb-4">
                                <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-1">Customer</label>
                                <select name="customer_id" id="customer_id" class="block w-full rounded-lg border-slate-300 focus:ring-primary focus:border-primary sm:text-sm">
                                    <option value="">Select customer</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?= $customer['CUSTOMER_ID'] ?>"><?= htmlspecialchars($customer['NAME']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="total_amount" class="block text-sm font-medium text-slate-700 mb-1">Total Amount</label>
                                <input type="number" step="0.01" min="0" name="total_amount" id="total_amount" class="block w-full rounded-lg border-slate-300 focus:ring-primary focus:border-primary sm:text-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                <select name="status" id="status" class="block w-full rounded-lg border-slate-300 focus:ring-primary focus:border-primary sm:text-sm">
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" name="add_order" class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">Create Order</button>
                        </form>

                        <h2 class="text-xl font-bold mb-2">All Orders</h2>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50">
                                    <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Order ID</th>
                                    <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Customer</th>
                                    <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Total Amount</th>
                                    <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">Status</th>
                                    <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr class="hover:bg-primary/5 transition-colors group">
                                        <td class="px-6 py-4 font-mono text-sm text-slate-600 dark:text-slate-400">ORDER-<?php echo str_pad($order['ORDER_ID'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($order['CUSTOMER_NAME'] ?? ''); ?></td>
                                        <td class="px-6 py-4">$<?php echo htmlspecialchars($order['TOTAL_AMOUNT'] ?? '0.00'); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($order['STATUS'] ?? ''); ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <form method="post" style="display:inline;">
                                                <button type="submit" name="delete_order" value="<?php echo $order['ORDER_ID']; ?>" class="text-red-400 hover:text-red-600 transition-colors">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (count($orders) === 0): ?>
                                    <tr><td colspan="5" class="px-6 py-4 text-slate-500">No orders found in the database.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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