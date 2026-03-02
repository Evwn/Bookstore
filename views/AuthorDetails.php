<?php
require_once __DIR__ . '/../controllers/AuthorController.php';
$success = $error = '';
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (deleteAuthor($id)) {
        $success = 'Author deleted.';
    } else {
        $error = 'Failed to delete author.';
    }
}
$authors = getAllAuthors();
?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Author Details and Royalties</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-light": "#d0deff", // derived from hue
                        "primary-dark": "#0a3aa6",  // derived from hue
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "neutral-50": "#f8fafc",
                        "neutral-100": "#f1f5f9",
                        "neutral-200": "#e2e8f0",
                        "neutral-300": "#cbd5e1",
                        "neutral-400": "#94a3b8",
                        "neutral-500": "#64748b",
                        "neutral-600": "#475569",
                        "neutral-700": "#334155",
                        "neutral-800": "#1e293b",
                        "neutral-900": "#0f172a",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-neutral-800 dark:text-neutral-100 min-h-screen flex flex-col">
<!-- Top Navigation / Breadcrumbs -->
<header class="bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-800 sticky top-0 z-30">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between h-16">
<div class="flex items-center">
<div class="flex-shrink-0 flex items-center gap-2">
<span class="material-icons text-primary text-2xl">menu_book</span>
<span class="font-bold text-xl tracking-tight text-neutral-900 dark:text-white">BookStore<span class="text-primary">Admin</span></span>
</div>
<nav aria-label="Breadcrumb" class="hidden md:flex ml-10 space-x-1">
<ol class="flex items-center space-x-2 text-sm text-neutral-500 dark:text-neutral-400">
<li><a class="hover:text-primary transition-colors" href="index.php?page=dashboard">Dashboard</a></li>
<li><span class="material-icons text-base">chevron_right</span></li>
<li><a class="hover:text-primary transition-colors" href="index.php?page=authordetails">Authors</a></li>
<li><span class="material-icons text-base">chevron_right</span></li>
<li><span aria-current="page" class="font-medium text-neutral-900 dark:text-white">Author Profile</span></li>
</ol>
</nav>
</div>
<div class="flex items-center gap-4">
<button class="p-2 rounded-full text-neutral-500 hover:text-primary hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
<span class="material-icons">notifications_none</span>
</button>
<div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm">
                        AD
                    </div>
</div>
</div>
</div>
</header>
<!-- Main Content Area -->
<main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
<!-- Header Section with Actions -->
<div class="md:flex md:items-center md:justify-between mb-8">
<div class="flex-1 min-w-0">
<h2 class="text-2xl font-bold leading-7 text-neutral-900 dark:text-white sm:text-3xl sm:truncate">
                    Author Details
                </h2>
<p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">Manage author profile and review royalty distribution.</p>
</div>
<div class="mt-4 flex md:mt-0 md:ml-4">
<button class="inline-flex items-center px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg shadow-sm text-sm font-medium text-neutral-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" onclick="window.location.href='index.php?page=editauthor'" type="button">
<span class="material-icons text-sm mr-2">edit</span>
                    Edit Profile
                </button>
<button class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-primary/30 shadow-lg" type="button">
<span class="material-icons text-sm mr-2">code</span>
                    Display Source
                </button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Left Column: Profile & Summary -->
<div class="lg:col-span-1 space-y-8">
<!-- Author Profile Card -->
<div class="bg-white dark:bg-neutral-900 overflow-hidden shadow-sm rounded-xl border border-neutral-200 dark:border-neutral-800">
<div class="h-32 w-full relative">
<!-- Abstract Banner Background -->
<div class="absolute inset-0 bg-gradient-to-r from-primary to-primary-light opacity-90" data-alt="Gradient banner background for author profile"></div>
<div class="absolute -bottom-12 left-6">
<img alt="Profile picture of the author" class="h-24 w-24 rounded-full border-4 border-white dark:border-neutral-900 shadow-lg object-cover bg-neutral-200" data-alt="Portrait of a smiling man with glasses" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBu7gD95xpbQiA5sJUzXU_rxPRCkNgVgFilTuR-F1gCXKJDpg-99DM_p0sgHwofvdXIhVKgSx09tiFzg4RXhC-zf0dCTbV2IjraRzXyx17_jJResLeG4hoydYu3i-M1eoeKTEdeQCBZ2V7_WJwiBxSBmXuu3rJWLz1TGnCXAA0iggWdVY3PEhDyJCCtfYCtWQXSOvaiv910dlxIUsoJ3i61a-a13H5JutoaPuP9C8mCw5OoiR22MQo3fYrORLoX52QSJiaY6QWGsQ"/>
</div>
</div>
<div class="pt-16 pb-6 px-6">
<div class="flex justify-between items-start">
<div>
<h3 class="text-xl font-bold text-neutral-900 dark:text-white">James R. Patterson</h3>
<p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1 flex items-center">
<span class="material-icons text-xs mr-1">fingerprint</span>
                                    ID: <span class="font-mono ml-1 text-neutral-700 dark:text-neutral-300 bg-neutral-100 dark:bg-neutral-800 px-1.5 py-0.5 rounded text-xs">AUTH-8832</span>
</p>
</div>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Active
                            </span>
</div>
<div class="mt-6 border-t border-neutral-100 dark:border-neutral-800 pt-4">
<dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
<div class="sm:col-span-2">
<dt class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Email</dt>
<dd class="mt-1 text-sm text-neutral-900 dark:text-white">james.patterson@example.com</dd>
</div>
<div>
<dt class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Join Date</dt>
<dd class="mt-1 text-sm text-neutral-900 dark:text-white">Oct 24, 2021</dd>
</div>
<div>
<dt class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Books Published</dt>
<dd class="mt-1 text-sm text-neutral-900 dark:text-white">12 Titles</dd>
</div>
</dl>
</div>
</div>
</div>
<!-- Total Royalty Card -->
<div class="bg-primary overflow-hidden shadow-lg shadow-primary/40 rounded-xl relative group">
<div class="absolute right-0 top-0 h-full w-1/2 bg-white/5 skew-x-12 transform origin-bottom-left transition-transform group-hover:scale-110"></div>
<div class="px-6 py-6 relative z-10">
<div class="flex items-center justify-between">
<h3 class="text-white/80 text-sm font-medium uppercase tracking-wider">Total Accumulated Royalty</h3>
<span class="p-1.5 rounded bg-white/20 text-white">
<span class="material-icons text-sm">paid</span>
</span>
</div>
<div class="mt-4 flex items-baseline">
<span class="text-4xl font-bold text-white tracking-tight">$145,200.50</span>
</div>
<p class="mt-2 text-sm text-white/70">
                            Based on calculated formula across all titles.
                        </p>
<!-- Mini Formula Legend -->
<div class="mt-6 p-3 bg-black/20 rounded-lg backdrop-blur-sm border border-white/10">
<p class="text-xs text-white/90 font-mono flex items-center gap-2">
<span class="material-icons text-sm opacity-70">functions</span>
                                Σ (Price × % × Qty ÷ Authors)
                            </p>
</div>
</div>
</div>
</div>
<!-- Right Column: Detailed Breakdown -->
<div class="lg:col-span-2">
<div class="bg-white dark:bg-neutral-900 shadow-sm rounded-xl border border-neutral-200 dark:border-neutral-800 h-full flex flex-col">
<div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-800 flex items-center justify-between bg-neutral-50/50 dark:bg-neutral-800/20 rounded-t-xl">
<div>
<h3 class="text-lg leading-6 font-semibold text-neutral-900 dark:text-white">Royalty Breakdown</h3>
<p class="mt-1 max-w-2xl text-xs text-neutral-500 dark:text-neutral-400">Detailed ledger of sales contributing to the total royalty.</p>
</div>
<div class="flex gap-2">
<button class="p-1.5 text-neutral-400 hover:text-primary transition-colors">
<span class="material-icons">filter_list</span>
</button>
<button class="p-1.5 text-neutral-400 hover:text-primary transition-colors">
<span class="material-icons">download</span>
</button>
</div>
</div>
<div class="flex-1 overflow-x-auto">
<table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-800">
<thead class="bg-neutral-50 dark:bg-neutral-900/50">
<tr>
<th class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider" scope="col">Book Title</th>
<th class="px-6 py-3 text-right text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider" scope="col">Unit Price</th>
<th class="px-6 py-3 text-center text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider" scope="col">Royalty %</th>
<th class="px-6 py-3 text-center text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider" scope="col">Qty Sold</th>
<th class="px-6 py-3 text-center text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider text-nowrap" scope="col">Co-Authors</th>
<th class="px-6 py-3 text-right text-xs font-bold text-primary dark:text-primary-light uppercase tracking-wider" scope="col">Sub-Total</th>
</tr>
</thead>
<tbody class="bg-white dark:bg-neutral-900 divide-y divide-neutral-200 dark:divide-neutral-800">
<?php if ($success): ?>
    <tr><td colspan="6" class="p-3 text-green-700 bg-green-100"><?php echo $success; ?></td></tr>
<?php elseif ($error): ?>
    <tr><td colspan="6" class="p-3 text-red-700 bg-red-100"><?php echo $error; ?></td></tr>
<?php endif; ?>
<?php foreach ($authors as $author): ?>
<tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="h-10 w-8 flex-shrink-0 bg-neutral-200 dark:bg-neutral-700 rounded shadow-sm overflow-hidden bg-cover bg-center"></div>
            <div class="ml-4">
                <div class="text-sm font-medium text-neutral-900 dark:text-white"><?php echo htmlspecialchars($author['name']); ?></div>
                <div class="text-xs text-neutral-500">ID: <?php echo $author['author_id']; ?></div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-600 dark:text-neutral-300 font-mono">-</td>
    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-600 dark:text-neutral-300">-</td>
    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-600 dark:text-neutral-300">-</td>
    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500 dark:text-neutral-400">-</td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-neutral-900 dark:text-white font-mono">
        <a href="index.php?page=editauthor&id=<?php echo $author['author_id']; ?>" class="text-blue-600 hover:underline">Edit</a>
        <a href="index.php?page=authordetails&delete=<?php echo $author['author_id']; ?>" class="ml-2 text-red-600 hover:underline" onclick="return confirm('Delete this author?');">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot class="bg-neutral-50 dark:bg-neutral-900/80 border-t border-neutral-200 dark:border-neutral-700">
<tr>
<td class="px-6 py-3 text-right text-sm font-medium text-neutral-500 dark:text-neutral-400" colspan="5">Total Calculation:</td>
<td class="px-6 py-3 text-right text-base font-bold text-primary dark:text-primary-light font-mono">$158,436.16</td>
</tr>
</tfoot>
</table>
</div>
<!-- Pagination -->
<div class="bg-white dark:bg-neutral-900 px-4 py-3 flex items-center justify-between border-t border-neutral-200 dark:border-neutral-800 sm:px-6 rounded-b-xl">
<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
<div>
<p class="text-sm text-neutral-700 dark:text-neutral-300">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">12</span> results
                                </p>
</div>
<div>
<nav aria-label="Pagination" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
<a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-sm font-medium text-neutral-500 hover:bg-neutral-50 dark:hover:bg-neutral-700" href="#">
<span class="sr-only">Previous</span>
<span class="material-icons text-sm">chevron_left</span>
</a>
<a aria-current="page" class="z-10 bg-primary/10 border-primary text-primary relative inline-flex items-center px-4 py-2 border text-sm font-medium" href="#">1</a>
<a class="bg-white dark:bg-neutral-800 border-neutral-300 dark:border-neutral-600 text-neutral-500 hover:bg-neutral-50 dark:hover:bg-neutral-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium" href="#">2</a>
<a class="bg-white dark:bg-neutral-800 border-neutral-300 dark:border-neutral-600 text-neutral-500 hover:bg-neutral-50 dark:hover:bg-neutral-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium" href="#">3</a>
<a class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 text-sm font-medium text-neutral-500 hover:bg-neutral-50 dark:hover:bg-neutral-700" href="#">
<span class="sr-only">Next</span>
<span class="material-icons text-sm">chevron_right</span>
</a>
</nav>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
<footer class="mt-auto border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 py-6">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center text-sm text-neutral-400">
            © 2023 Bookstore Management System. All rights reserved.
        </div>
</footer>
</body></html>