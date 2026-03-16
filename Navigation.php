<?php
// Navigation sidebar component - included in all pages
?>
<!-- Sidebar Navigation -->
<aside id="sidebar" class="w-64 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex-shrink-0 hidden md:flex flex-col h-full transition-colors duration-200 fixed md:relative left-0 top-0 z-40 md:z-0">
<div class="h-16 flex items-center px-6 border-b border-border-light dark:border-border-dark">
<div class="flex items-center gap-2 text-primary dark:text-primary-400">
<span class="material-icons">menu_book</span>
<span class="font-bold text-lg text-slate-900 dark:text-white">AdminPortal</span>
</div>
<button id="close-sidebar" class="md:hidden ml-auto p-2 rounded-lg text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
<span class="material-icons">close</span>
</button>
</div>
<nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
<a class="flex items-center gap-3 px-3 py-2.5 <?php echo ($page === 'dashboard') ? 'bg-primary/10 text-primary dark:text-blue-400 rounded-lg font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-blue-400 rounded-lg transition-colors group'; ?>" href="index.php?page=dashboard">
<span class="material-icons text-xl <?php echo ($page === 'dashboard') ? '' : 'text-slate-400 group-hover:text-primary dark:group-hover:text-blue-400'; ?>">dashboard</span>
Dashboard
</a>
<div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Inventory</div>
<a class="flex items-center gap-3 px-3 py-2.5 <?php echo ($page === 'inventrory') ? 'bg-primary/10 text-primary dark:text-blue-400 rounded-lg font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-blue-400 rounded-lg transition-colors group'; ?>" href="index.php?page=inventrory">
<span class="material-icons text-xl <?php echo ($page === 'inventrory') ? '' : 'text-slate-400 group-hover:text-primary dark:group-hover:text-blue-400'; ?>">library_books</span>
Books
</a>
<a class="flex items-center gap-3 px-3 py-2.5 <?php echo ($page === 'author' || $page === 'addauthor' || $page === 'editauthor' || $page === 'authordetails') ? 'bg-primary/10 text-primary dark:text-blue-400 rounded-lg font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-blue-400 rounded-lg transition-colors group'; ?>" href="index.php?page=author">
<span class="material-icons text-xl <?php echo ($page === 'author' || $page === 'addauthor' || $page === 'editauthor' || $page === 'authordetails') ? '' : 'text-slate-400 group-hover:text-primary dark:group-hover:text-blue-400'; ?>">person</span>
Authors
</a>
<div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Management</div>
<a class="flex items-center gap-3 px-3 py-2.5 <?php echo ($page === 'customer' || $page === 'editcustomer' || $page === 'addcustomer') ? 'bg-primary/10 text-primary dark:text-blue-400 rounded-lg font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-blue-400 rounded-lg transition-colors group'; ?>" href="index.php?page=customer">
<span class="material-icons text-xl <?php echo ($page === 'customer' || $page === 'editcustomer' || $page === 'addcustomer') ? '' : 'text-slate-400 group-hover:text-primary dark:group-hover:text-blue-400'; ?>">people</span>
Customers
</a>
<a class="flex items-center gap-3 px-3 py-2.5 <?php echo ($page === 'order') ? 'bg-primary/10 text-primary dark:text-blue-400 rounded-lg font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-primary dark:hover:text-blue-400 rounded-lg transition-colors group'; ?>" href="index.php?page=order">
<span class="material-icons text-xl <?php echo ($page === 'order') ? '' : 'text-slate-400 group-hover:text-primary dark:group-hover:text-blue-400'; ?>">shopping_cart</span>
Orders
</a>
</nav>
<div class="p-4 border-t border-border-light dark:border-border-dark">
<div class="flex items-center gap-3 p-2 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
<div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
AD
</div>
<div class="flex-1 min-w-0">
<p class="text-sm font-medium text-slate-900 dark:text-white truncate">Admin User</p>
<p class="text-xs text-slate-500 truncate">admin@bookstore.com</p>
</div>
</div>
</div>
</aside>
<!-- Sidebar Overlay for Mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 hidden md:hidden z-30"></div>

<script>
// Mobile sidebar toggle
const sidebarToggle = document.getElementById('mobile-menu-btn');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');
const closeSidebarBtn = document.getElementById('close-sidebar');

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('hidden');
        sidebarOverlay.classList.toggle('hidden');
    });
}

if (closeSidebarBtn) {
    closeSidebarBtn.addEventListener('click', function() {
        sidebar.classList.add('hidden');
        sidebarOverlay.classList.add('hidden');
    });
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.add('hidden');
        sidebarOverlay.classList.add('hidden');
    });
}
</script>
