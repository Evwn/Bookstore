<!-- Breadcrumbs & Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<nav aria-label="Breadcrumb" class="flex text-sm text-gray-500 mb-1">
<ol class="flex items-center space-x-2">
<li><a class="hover:text-primary transition-colors" href="index.php?page=dashboard">Dashboard</a></li>
<li><span class="material-icons text-[14px] text-gray-400">chevron_right</span></li>
<li class="text-gray-800 dark:text-gray-200 font-medium">Customers</li>
</ol>
</nav>
<h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Customer Directory</h1>
</div>
</div>
<div class="flex-1 overflow-y-auto">
<div class="max-w-7xl mx-auto space-y-6">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
<div class="relative flex-1 max-w-md">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 material-icons" style="font-size: 20px;">search</span>
<input class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-400 shadow-sm" placeholder="Search by name or Customer ID..." type="text"/>
</div>
<a href="index.php?page=addcustomer" class="bg-primary hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 whitespace-nowrap">
<span class="material-icons text-sm">person_add</span>
Add New Customer
</a>
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<?php
require_once __DIR__ . '/../controllers/CustomerController.php';
$customers = getAllCustomers();
?>
<table class="w-full text-left border-collapse">
	<thead>
		<tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
			<th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
			<th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
			<th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email Address</th>
			<th class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($customers as $customer): ?>
			<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
				<td class="px-6 py-4 font-mono text-sm text-gray-500 dark:text-gray-400">#CUST-<?= htmlspecialchars($customer['CUSTOMER_ID']) ?></td>
				<td class="px-6 py-4">
					<span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($customer['NAME']) ?></span>
				</td>
				<td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($customer['EMAIL']) ?></td>
				<td class="px-6 py-4 text-right">
					<a href="index.php?page=editcustomer&id=<?= $customer['CUSTOMER_ID'] ?>" class="text-gray-400 hover:text-primary dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-primary/5 px-2 py-1">Edit</a>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (count($customers) === 0): ?>
			<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No customers found. <a href="index.php?page=addcustomer" class="text-primary hover:underline">Add your first customer</a>.</td></tr>
		<?php endif; ?>
	</tbody>
</table>
</div>
</div>
</div>
</div>