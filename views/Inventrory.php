<?php
require_once 'db.php';

$stmt = $pdo->query("
    SELECT 
        b.book_id,
        b.title,
        b.isbn,
        b.stock,
        b.cover_url,
        a.name AS author_name
    FROM books b
    LEFT JOIN authors a ON a.author_id = b.author_id
    ORDER BY b.book_id DESC
");

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Book Search and Inventory Update</title>
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
                        "primary-light": "rgba(19, 91, 236, 0.1)",
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
<style>
        /* Custom scrollbar for table body if needed */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="bg-background-light text-slate-800 font-display antialiased min-h-screen flex flex-col">
<!-- Navigation Bar -->
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
<a class="border-primary text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=inventrory">Inventory</a>
<a class="border-transparent text-slate-500 hover:border-primary hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium" href="index.php?page=order">Orders</a>
</div>
</div>
<div class="flex items-center">
<div class="flex-shrink-0">
<button class="relative p-1 rounded-full text-slate-400 hover:text-slate-500 focus:outline-none" type="button">
<span class="sr-only">View notifications</span>
<span class="material-icons">notifications</span>
<span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
</button>
</div>
<div class="ml-3 relative flex items-center gap-2">
<img alt="Admin Profile Picture" class="h-8 w-8 rounded-full bg-slate-100" data-alt="Close up of a smiling man" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcqJ8KRwrEve0Cw0AXwwBTYJbFk9BOlQfP-inWjEG2GEDo9Ej0jUAreHPwB6uY7Eh70EQ7m21DHU1nUe9s_sUKfwIQSIVthASJSy41O8J2dKwxb7ibCKRdSugX8fuJdUuj5KKknmahRo55GTFjBCND_2-QWZyV0fnMHVvOKK0sG3YRzR6zhqLzhIa-E9TZog9sklL3CgptvHBe8APMKV24IglNf9HnSYXoJeMnjS9e0KPdh8skExmjlgWCiu7-TUaVKxloC_h5gg"/>
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
<li><a class="text-sm font-medium text-slate-500 hover:text-slate-700" href="#">Inventory</a></li>
<li><span class="text-slate-300">/</span></li>
<li><a aria-current="page" class="text-sm font-medium text-primary" href="#">Update Stock</a></li>
</ol>
</nav>
<h2 class="mt-2 text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                        Search &amp; Update Inventory
                    </h2>
<p class="mt-1 text-sm text-slate-500">Search for books by title, author or ISBN to update sales figures.</p>
</div>
<div class="mt-4 flex md:mt-0 md:ml-4">
<button class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" type="button">
<span class="material-icons text-base mr-2 text-slate-500">code</span>
                        Display Source
                    </button>
</div>
</div>
<button 
    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all"
    onclick="window.location.href='index.php?page=addnewbook'">
    
    <span class="material-icons text-sm mr-2">save</span>
    Add Book
</button>

<!-- Search Card -->
<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6 mb-8">
<div class="relative rounded-md shadow-sm">
<div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
<span class="material-icons text-slate-400 text-2xl">search</span>
</div>
<input class="block w-full rounded-lg border-slate-300 pl-12 pr-12 py-4 text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-primary sm:text-lg shadow-sm" id="search" name="search" placeholder="Start typing title, ISBN, or keywords (case-insensitive)..." type="text"/>
<div class="absolute inset-y-0 right-0 flex items-center pr-3">
<button class="p-1 rounded-md text-slate-400 hover:text-slate-500 focus:outline-none">
<span class="material-icons">tune</span>
</button>
</div>
</div>
<div class="mt-3 flex items-center justify-between">
<div class="text-sm text-slate-500">
                        Showing results for <span class="font-medium text-slate-900">"design patterns"</span>
</div>
<div class="flex gap-2">
<span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">In Stock</span>
<span class="inline-flex items-center rounded-full bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">All Categories</span>
</div>
</div>
</div>
<!-- Results Table -->
<div class="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
<div class="min-w-full overflow-x-auto">
<table class="min-w-full divide-y divide-slate-200">
<thead class="bg-slate-50">
<tr>
<th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-16" scope="col">
                                    Select
                                </th>
<th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider" scope="col">
                                    Book Details
                                </th>
<th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-32" scope="col">
                                    ISBN
                                </th>
<th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-32 text-center" scope="col">
                                    Current Stock
                                </th>
<th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-40" scope="col">
                                    Quantity Sold
                                </th><th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-20" scope="col">
                                    Actions
                                </th></tr>
</thead>
<tbody class="bg-white divide-y divide-slate-200">
<!-- Row 1 -->
<tr class="hover:bg-slate-50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-center">
<div class="flex items-center justify-center h-full">
<input class="focus:ring-primary h-4 w-4 text-primary border-slate-300" id="book-1" name="book-selection" type="radio"/>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center">
<div class="h-12 w-8 flex-shrink-0 rounded bg-slate-200 overflow-hidden shadow-sm mr-4 relative">
<!-- Placeholder for book cover -->
<div class="absolute inset-0 bg-gradient-to-tr from-slate-300 to-slate-100" data-alt="Abstract gradient representing book cover"></div>
</div>
<div>
<a class="text-base font-semibold text-primary hover:underline hover:text-primary-hover block" href="#">Refactoring UI</a>
<div class="text-sm text-slate-500">Adam Wathan &amp; Steve Schoger</div>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                    978-0132350884
                                </td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 text-center font-medium">
                                    42
                                </td>
<td class="px-6 py-4 whitespace-nowrap">
<div class="relative rounded-md shadow-sm">
<input class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md text-right pr-8" id="qty-sold-1" min="0" name="qty-sold-1" placeholder="0" type="number"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-400 text-xs">pcs</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right">
<button class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-primary/5" onclick="window.location.href='index.php?page=editbook'">
<span class="material-icons" style="font-size: 20px;">edit</span>
</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="bg-primary-light/10 ring-1 ring-inset ring-primary/20">
<td class="px-6 py-4 whitespace-nowrap text-center">
<div class="flex items-center justify-center h-full">
<input checked="" class="focus:ring-primary h-4 w-4 text-primary border-slate-300" id="book-2" name="book-selection" type="radio"/>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center">
<div class="h-12 w-8 flex-shrink-0 rounded bg-slate-200 overflow-hidden shadow-sm mr-4 relative">
<div class="absolute inset-0 bg-gradient-to-br from-blue-200 to-indigo-300" data-alt="Abstract blue gradient book cover"></div>
</div>
<div>
<a class="text-base font-semibold text-primary hover:underline hover:text-primary-hover block" href="#">Clean Code</a>
<div class="text-sm text-slate-500">Robert C. Martin</div>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                    978-0132350884
                                </td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 text-center font-medium">
                                    15
                                </td>
<td class="px-6 py-4 whitespace-nowrap">
<div class="relative rounded-md shadow-sm">
<input class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-primary rounded-md text-right pr-8 font-bold text-slate-900" id="qty-sold-2" min="0" name="qty-sold-2" type="number" value="5"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-400 text-xs">pcs</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right">
<button class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-primary/5" onclick="window.location.href='index.php?page=editbook'">
<span class="material-icons" style="font-size: 20px;">edit</span>
</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-slate-50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-center">
<div class="flex items-center justify-center h-full">
<input class="focus:ring-primary h-4 w-4 text-primary border-slate-300" id="book-3" name="book-selection" type="radio"/>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center">
<div class="h-12 w-8 flex-shrink-0 rounded bg-slate-200 overflow-hidden shadow-sm mr-4 relative">
<div class="absolute inset-0 bg-gradient-to-t from-gray-200 to-gray-400" data-alt="Abstract gray gradient book cover"></div>
</div>
<div>
<a class="text-base font-semibold text-primary hover:underline hover:text-primary-hover block" href="#">The Pragmatic Programmer</a>
<div class="text-sm text-slate-500">Andrew Hunt &amp; David Thomas</div>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                    978-0201616224
                                </td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 text-center font-medium">
                                    8
                                </td>
<td class="px-6 py-4 whitespace-nowrap">
<div class="relative rounded-md shadow-sm">
<input class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md text-right pr-8" id="qty-sold-3" min="0" name="qty-sold-3" placeholder="0" type="number"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-400 text-xs">pcs</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right">
<button class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-primary/5" onclick="window.location.href='index.php?page=editbook'">
<span class="material-icons" style="font-size: 20px;">edit</span>
</button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-slate-50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap text-center">
<div class="flex items-center justify-center h-full">
<input class="focus:ring-primary h-4 w-4 text-primary border-slate-300" id="book-4" name="book-selection" type="radio"/>
</div>
</td>
<td class="px-6 py-4">
<div class="flex items-center">
<div class="h-12 w-8 flex-shrink-0 rounded bg-slate-200 overflow-hidden shadow-sm mr-4 relative">
<div class="absolute inset-0 bg-gradient-to-r from-red-200 to-orange-100" data-alt="Abstract red gradient book cover"></div>
</div>
<div>
<a class="text-base font-semibold text-primary hover:underline hover:text-primary-hover block" href="#">Design Patterns</a>
<div class="text-sm text-slate-500">Erich Gamma et al.</div>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                    978-0201633610
                                </td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 text-center font-medium">
                                    112
                                </td>
<td class="px-6 py-4 whitespace-nowrap">
<div class="relative rounded-md shadow-sm">
<input class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md text-right pr-8" id="qty-sold-4" min="0" name="qty-sold-4" placeholder="0" type="number"/>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
<span class="text-slate-400 text-xs">pcs</span>
</div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination / Footer of table -->
<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-slate-200 sm:px-6">
<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
<div>
<p class="text-sm text-slate-700">
                                Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">24</span> results
                            </p>
</div>
<div>
<nav aria-label="Pagination" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
<a class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50" href="#">
<span class="sr-only">Previous</span>
<span class="material-icons text-base">chevron_left</span>
</a>
<a aria-current="page" class="z-10 bg-primary/10 border-primary text-primary relative inline-flex items-center px-4 py-2 border text-sm font-medium" href="#"> 1 </a>
<a class="bg-white border-slate-300 text-slate-500 hover:bg-slate-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium" href="#"> 2 </a>
<a class="bg-white border-slate-300 text-slate-500 hover:bg-slate-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium" href="#"> 3 </a>
<span class="relative inline-flex items-center px-4 py-2 border border-slate-300 bg-white text-sm font-medium text-slate-700"> ... </span>
<a class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-slate-300 bg-white text-sm font-medium text-slate-500 hover:bg-slate-50" href="#">
<span class="sr-only">Next</span>
<span class="material-icons text-base">chevron_right</span>
</a>
</nav>
</div>
</div>
</div>
</div>
<!-- Sticky Action Footer -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-lg lg:static lg:bg-transparent lg:border-none lg:shadow-none lg:mt-6 lg:p-0">
<div class="max-w-6xl mx-auto flex flex-col sm:flex-row justify-end items-center gap-4">
<div class="text-sm text-slate-500 hidden sm:block">
<span class="font-medium text-primary">1 item</span> selected for update.
                    </div>
<button class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="button">
<span class="material-icons mr-2">update</span>
                        Update Selected Inventory
                    </button>
</div>
</div>
</div>
</main>
</body></html>