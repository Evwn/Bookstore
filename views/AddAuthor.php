<?php

file_put_contents(__DIR__ . '/../author_debug.log', date('c') . " | AddAuthor.php loaded | METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
// Request handling logic (before any HTML)
require_once __DIR__ . '/../controllers/AuthorController.php';
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only handle if this is a direct POST (not included in another form)
    $name = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
    $biography = isset($_POST['biography']) ? trim($_POST['biography']) : '';
    if ($name === '') {
        $error = 'Author name is required.';
    } else {
        // Defensive: check function exists
        if (function_exists('addAuthor')) {
            if (addAuthor($name, $biography)) {
                $success = 'Author added successfully!';
            } else {
                $error = 'Failed to add author.';
            }
        } else {
            $error = 'addAuthor function not found.';
        }
    }
}
?>
<!-- Breadcrumbs & Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<nav aria-label="Breadcrumb" class="flex text-sm text-gray-500 mb-1">
<ol class="flex items-center space-x-2">
<li><a class="hover:text-primary transition-colors" href="index.php?page=dashboard">Dashboard</a></li>
<li><span class="material-icons text-[14px] text-gray-400">chevron_right</span></li>
<li><a class="hover:text-primary transition-colors" href="index.php?page=authordetails">Authors</a></li>
<li><span class="material-icons text-[14px] text-gray-400">chevron_right</span></li>
<li class="text-gray-800 dark:text-gray-200 font-medium">Add New</li>
</ol>
</nav>
<h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Add New Author</h1>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Left Column: Form -->
<div class="lg:col-span-2">
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
<div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700">
<div class="flex items-center space-x-3 mb-6">
<div class="p-2 bg-primary/10 rounded-lg">
<span class="material-icons text-primary">person</span>
</div>
<h2 class="text-lg font-semibold text-gray-900 dark:text-white">Author Details</h2>
</div>
<form class="space-y-6" method="POST" action="" id="add-author-form">
<div class="grid grid-cols-1 gap-6">
<!-- Author ID (Disabled) -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="author_id">
                                        Author ID <span class="text-gray-400 font-normal ml-1">(System Generated)</span>
</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-icons text-gray-400 text-lg">tag</span>
</div>
<input class="pl-10 block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 sm:text-sm cursor-not-allowed dark:bg-gray-900/50 dark:border-gray-700 dark:text-gray-400 shadow-sm" disabled="" id="author_id" name="author_id" type="text" value="Auto-generated"/>
</div>
<p class="mt-1 text-xs text-gray-400">This ID will be permanently assigned upon saving.</p>
</div>
<?php if ($success): ?>
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800"><?php echo $error; ?></div>
<?php endif; ?>
<!-- Author Name -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="author_name">
                                        Author Name <span class="text-red-500">*</span>
</label>
<div class="relative">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<span class="material-icons text-gray-400 text-lg">edit</span>
</div>
<input class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary sm:text-sm dark:bg-gray-900 dark:border-gray-600 dark:text-white dark:placeholder-gray-500 shadow-sm py-2.5" id="author_name" name="author_name" placeholder="e.g. J.K. Rowling" type="text" value="<?php echo htmlspecialchars($_POST['author_name'] ?? ''); ?>"/>
</div>
</div>
<!-- Biography (Optional) -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="biography">
                                        Biography <span class="text-gray-400 font-normal ml-1">(Optional)</span>
</label>
<div class="relative">
<textarea class="block w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary sm:text-sm dark:bg-gray-900 dark:border-gray-600 dark:text-white dark:placeholder-gray-500 shadow-sm p-3" id="biography" name="biography" placeholder="Enter a brief biography about the author..." rows="4"><?php echo htmlspecialchars($_POST['biography'] ?? ''); ?></textarea>
</div>
</div>
</div>
</div>
<div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-gray-700">
<button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors" type="button" onclick="window.location.href='index.php?page=author'">
                            Cancel
                        </button>
<button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="submit" id="save-btn">
<span class="material-icons text-sm mr-2">save</span>
                            Save Author
                        </button>
</div>
</form>

<!-- Loading Overlay for Form Submission -->
<div id="form-loading-overlay" style="display:none;position:fixed;z-index:99999;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);align-items:center;justify-content:center;">
        <div class="flex flex-col items-center">
                <span class="material-icons animate-spin text-5xl text-primary mb-4" style="animation:spin 1s linear infinite;">autorenew</span>
                <span class="text-lg font-semibold text-primary">Submitting...</span>
        </div>
</div>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>
<script>
// Show loading overlay on form submit
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('add-author-form');
    if (form) {
        form.addEventListener('submit', function() {
            var overlay = document.getElementById('form-loading-overlay');
            if (overlay) overlay.style.display = 'flex';
        });
    }
});
</script>
</div>
</div>
</div>
<!-- Right Column: Recent Activity -->
<div class="lg:col-span-1 space-y-6">
<!-- Recent Authors Card -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
<div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
<h3 class="text-base font-semibold text-gray-900 dark:text-white">Recently Added</h3>
<a class="text-xs font-medium text-primary hover:text-blue-700" href="index.php?page=author">View All</a>
</div>
<div class="p-4 text-center text-gray-500 dark:text-gray-400">
<span class="material-icons text-3xl mb-2 opacity-50">person_add</span>
<p class="text-sm">No recent authors</p>
<p class="text-xs">Authors you add will appear here</p>
</div>
</div>
<!-- Helper Card -->
<div class="bg-primary/5 border border-primary/20 rounded-xl p-5">
<div class="flex items-start">
<div class="flex-shrink-0">
<span class="material-icons text-primary text-xl mt-0.5">info</span>
</div>
<div class="ml-3">
<h3 class="text-sm font-medium text-primary">Naming Convention</h3>
<div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
<p class="mb-2">Please ensure author names follow the standard format: <br/><code>[First Name] [Last Name]</code>.</p>
<p>Avoid using nicknames or titles unless part of their pen name.</p>
</div>
</div>
</div>
</div>
</div>
</div>