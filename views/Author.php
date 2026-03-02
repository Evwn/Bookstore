<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>All Authors Directory - Admin Dashboard</title>
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
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-200 min-h-screen">
<!-- Navigation Sidebar/Top Bar Mockup -->
<header class="bg-white dark:bg-slate-900 border-b border-primary/10 shadow-sm sticky top-0 z-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between items-center h-16">
<div class="flex items-center gap-4">
<div class="bg-primary p-2 rounded-lg">
<span class="material-icons text-white">menu_book</span>
</div>
<span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white uppercase">BookStore<span class="text-primary">Admin</span></span>
</div>
<div class="flex items-center gap-4">
<button class="p-2 text-slate-500 hover:text-primary transition-colors">
<span class="material-icons">notifications</span>
</button>
<div class="h-8 w-8 rounded-full overflow-hidden border border-primary/20">
<img alt="Profile" data-alt="User profile avatar of admin user" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC5R35A46MNYtJLvds-NC9oxfndhjXYa4cXUIApOGpTfrdqQbsaesc-n8JJ21ZygltNxQRuFjSIgJ1YzG3ARBuJrxEISA-v_4Y7y48ct4BvXfSz8XeaOVPAYWmtlIawJqmpeCxOfBEy63cOIm6hGKn_fCJCm_dGnfRLN4I1vWrPTMAI9nps8mP4ekTTD8Yx9jaovvV0tgJmMTG86DSKN9al_I4HOEh928zQgX3qFotwsYgpEYtKDGTGH-08rzvW2bzF-x0kBkYdKQ"/>
</div>
</div>
</div>
</div>
</header>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<!-- Breadcrumbs -->
<nav aria-label="Breadcrumb" class="flex mb-4 text-sm text-slate-500">
<ol class="flex items-center space-x-2">
<li><a class="hover:text-primary transition-colors" href="#">Dashboard</a></li>
<li class="flex items-center space-x-2">
<span class="material-icons text-sm">chevron_right</span>
<span class="text-slate-900 dark:text-white font-medium">Authors Directory</span>
</li>
</ol>
</nav>
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
<div>
<h1 class="text-2xl font-bold text-slate-900 dark:text-white">All Authors Directory</h1>
<p class="text-slate-500 mt-1">Manage and track author royalties and profiles in the system.</p>
</div>
<a href="index.php?page=addauthor" class="inline-flex items-center px-4 py-2 bg-primary text-white font-semibold rounded-lg shadow-sm hover:bg-blue-700 transition-all focus:ring-2 focus:ring-primary focus:ring-offset-2">
<span class="material-icons text-sm mr-2">add</span>
                Add New Author
            </a>
</div>
<!-- Table Controls -->
<div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
<div class="p-4 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
<div class="relative flex-1 max-w-md">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
<span class="material-icons text-lg">search</span>
</span>
<input class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800 text-sm focus:ring-primary focus:border-primary transition-all" placeholder="Search by name or Author ID..." type="text"/>
</div>
<div class="flex items-center gap-2">
<button class="inline-flex items-center px-3 py-2 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-lg text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 transition-colors">
<span class="material-icons text-sm mr-2">filter_list</span>
                        Filter
                    </button>
<button class="inline-flex items-center px-3 py-2 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-lg text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 transition-colors">
<span class="material-icons text-sm mr-2">download</span>
                        Export
                    </button>
</div>
</div>
<!-- Data Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-slate-50 dark:bg-slate-800/50">
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">
<div class="flex items-center gap-1 cursor-pointer hover:text-primary">
                                    Author ID
                                    <span class="material-icons text-xs">unfold_more</span>
</div>
</th>
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800">
<div class="flex items-center gap-1 cursor-pointer hover:text-primary">
                                    Author Name
                                    <span class="material-icons text-xs">unfold_more</span>
</div>
</th>
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800 text-right">
<div class="flex items-center justify-end gap-1 cursor-pointer hover:text-primary">
                                    Total Royalty
                                    <span class="material-icons text-xs">unfold_more</span>
</div>
</th>
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200 dark:border-slate-800 text-right">
                                Actions
                            </th>
</tr>
</thead>
<?php
require_once __DIR__ . '/../controllers/AuthorController.php';
$authors = getAllAuthors();
?>
<?php foreach ($authors as $author): ?>
<tr class="hover:bg-primary/5 transition-colors group">
    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-slate-600 dark:text-slate-400">AUTH-<?php echo str_pad($author['author_id'], 5, '0', STR_PAD_LEFT); ?></td>
    <td class="px-6 py-4 whitespace-nowrap">
        <a class="text-primary font-medium hover:underline decoration-2 underline-offset-4" href="index.php?page=authordetails&id=<?php echo $author['author_id']; ?>"><?php echo htmlspecialchars($author['name']); ?></a>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-slate-900 dark:text-white">-</td>
    <td class="px-6 py-4 whitespace-nowrap text-right">
        <button class="text-slate-400 hover:text-primary transition-colors">
            <span class="material-icons">more_vert</span>
        </button>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="p-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
<span class="text-sm text-slate-500">Showing 1 to 5 of 124 authors</span>
<div class="flex items-center gap-1">
<button class="p-2 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-400 hover:text-primary hover:border-primary disabled:opacity-50 disabled:cursor-not-allowed" disabled="">
<span class="material-icons text-sm">chevron_left</span>
</button>
<button class="px-3 py-1 bg-primary text-white rounded-lg text-sm font-semibold shadow-sm">1</button>
<button class="px-3 py-1 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50">2</button>
<button class="px-3 py-1 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50">3</button>
<span class="px-2 text-slate-400 text-sm">...</span>
<button class="px-3 py-1 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50">25</button>
<button class="p-2 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-400 hover:text-primary hover:border-primary">
<span class="material-icons text-sm">chevron_right</span>
</button>
</div>
</div>
</div>
<!-- Footer Section with mandatory button -->
<footer class="mt-12 py-6 border-t border-slate-200 dark:border-slate-800 flex flex-col items-center gap-4">
<button class="inline-flex items-center px-6 py-3 border-2 border-primary/20 bg-primary/5 text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-all">
<span class="material-icons mr-2">code</span>
                Display Source
            </button>
<p class="text-xs text-slate-400">© 2024 Bookstore Management System Admin. All rights reserved.</p>
</footer>
</main>
</body></html>