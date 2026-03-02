<?php
require_once __DIR__ . '/../controllers/AuthorController.php';
$success = $error = '';
$author = null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id) {
    $author = getAuthorById($id);
    if (!$author) {
        $error = 'Author not found.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $name = trim($_POST['fullname'] ?? '');
    $biography = trim($_POST['biography'] ?? '');
    if ($name === '') {
        $error = 'Author name is required.';
    } else {
        if (updateAuthor($id, $name, $biography)) {
            $success = 'Author updated successfully!';
            $author = getAuthorById($id);
        } else {
            $error = 'Failed to update author.';
        }
    }
}
?>
<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Edit Author - Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display antialiased text-gray-800 dark:text-gray-100 min-h-screen flex flex-col md:flex-row">
<!-- Mobile Header -->
<header class="md:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between">
<div class="flex items-center space-x-2">
<span class="material-icons text-primary">menu_book</span>
<span class="font-bold text-lg text-gray-900 dark:text-white">BookStore Admin</span>
</div>
<button class="text-gray-500 hover:text-primary">
<span class="material-icons">menu</span>
</button>
</header>
<!-- Sidebar (Desktop) -->
<aside class="hidden md:flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-screen sticky top-0">
<div class="p-6 flex items-center space-x-3 border-b border-gray-100 dark:border-gray-700/50">
<div class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center text-white">
<span class="material-icons text-sm">menu_book</span>
</div>
<span class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">BookStore</span>
</div>
<nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
<a class="flex items-center px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors group" href="index.php?page=dashboard">
<span class="material-icons text-xl mr-3 group-hover:text-primary">dashboard</span>
<span class="font-medium">Dashboard</span>
</a>
<a class="flex items-center px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors group" href="index.php?page=inventrory">
<span class="material-icons text-xl mr-3 group-hover:text-primary">inventory_2</span>
<span class="font-medium">Inventory</span>
</a>
<div class="pt-4 pb-2 px-4">
<p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
</div>
<a class="flex items-center px-4 py-3 bg-primary text-white rounded-lg shadow-sm shadow-primary/30" href="index.php?page=addauthor">
<span class="material-icons text-xl mr-3">person_add</span>
<span class="font-medium">Authors</span>
</a>
<a class="flex items-center px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors group" href="index.php?page=addnewbook">
<span class="material-icons text-xl mr-3 group-hover:text-primary">category</span>
<span class="font-medium">Add Book</span>
</a>
<a class="flex items-center px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors group" href="index.php?page=order">
<span class="material-icons text-xl mr-3 group-hover:text-primary">shopping_cart</span>
<span class="font-medium">Orders</span>
</a>
</nav>
<div class="p-4 border-t border-gray-100 dark:border-gray-700/50">
<div class="flex items-center space-x-3 px-2">
<img class="h-9 w-9 rounded-full object-cover border border-gray-200 dark:border-gray-600" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyVJ30bYlHHhk2UUKLkrFk6p9faDsgW39hUMtTY-9z0Uek3TfNkHQZr2Ho-e32SKSgDyz9IDvxtoEtmkHonrP--kL7Fw7InGvE4jaCgjzVGKHirbkqkfM3HSgqnW-e1LobwmkuWuZAzaJl-Q44qmaCOar6qWm8INgNJv4bWhgeySZCnEdRLyCVdA_Es4UWUL4fVvnZLr0Jsh30Y9qXjD8sxCNP-yy-NpzpUwVlcgqvYBnQgzIIUWt-95OBrJJ4--iT66UNXNOTeA"/>
<div class="flex-1 min-w-0">
<p class="text-sm font-medium text-gray-900 dark:text-white truncate">Admin User</p>
<p class="text-xs text-gray-500 truncate">admin@bookstore.com</p>
</div>
</div>
</div>
</aside>
<!-- Main Content -->
<main class="flex-1 p-4 md:p-8 lg:p-10 max-w-7xl mx-auto w-full">
<!-- Breadcrumbs & Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<nav aria-label="Breadcrumb" class="flex text-sm text-gray-500 mb-1">
<ol class="flex items-center space-x-2">
<li><a class="hover:text-primary transition-colors" href="index.php?page=dashboard">Dashboard</a></li>
<li><span class="material-icons text-[14px] text-gray-400">chevron_right</span></li>
<li><a class="hover:text-primary transition-colors" href="index.php?page=authordetails">Authors</a></li>
<li><span class="material-icons text-[14px] text-gray-400">chevron_right</span></li>
<li class="text-gray-800 dark:text-gray-200 font-medium">Edit Author</li>
</ol>
</nav>
<h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Author</h1>
</div>
</div>
<!-- Form -->
<form action="#" class="space-y-6" method="POST">
<?php if ($success): ?>
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800"><?php echo $error; ?></div>
<?php endif; ?>
<!-- Basic Info Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
<h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-6">
<span class="material-icons text-primary">person</span>
                        Author Information
                    </h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- Full Name -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="fullname">
                                Full Name
                            </label>
<input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="fullname" name="fullname" placeholder="Enter full name" type="text" value="<?php echo htmlspecialchars($_POST['fullname'] ?? ($author['name'] ?? '')); ?>"/>
</div>
<!-- Email -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="email">
                                Email Address
                            </label>
<input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="email" name="email" placeholder="Enter email" type="email" value="george@example.com"/>
</div>
<!-- Phone -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="phone">
                                Phone Number
                            </label>
<input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="phone" name="phone" placeholder="Enter phone number" type="tel" value="+1234567890"/>
</div>
<!-- Country -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="country">
                                Country
                            </label>
<input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="country" name="country" placeholder="Enter country" type="text" value="United Kingdom"/>
</div>
</div>
</div>
<!-- Additional Info Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
<h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-6">
<span class="material-icons text-primary">description</span>
                        Biography &amp; Details
                    </h2>
<div class="space-y-6">
<!-- Biography -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="biography">
                                Biography
                            </label>
<textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="biography" name="biography" placeholder="Enter biography..." rows="4"><?php echo htmlspecialchars($_POST['biography'] ?? ($author['biography'] ?? '')); ?></textarea>
</div>
<!-- Notable Works -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="works">
                                Notable Works
                            </label>
<textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent" id="works" name="works" placeholder="List notable works" rows="3">1984, Animal Farm, Homage to Catalonia</textarea>
</div>
</div>
</div>
<!-- Buttons -->
<div class="flex items-center justify-between pt-6">
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none transition-all" type="button">
<span class="material-icons text-lg mr-2">arrow_back</span>
                    Back
                </button>
<div class="flex gap-3">
<button class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-all" type="reset">
<span class="material-icons text-lg mr-2">refresh</span>
                        Reset
                    </button>
<button class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none transition-all" type="submit">
<span class="material-icons text-lg mr-2">save</span>
                        Save Changes
                    </button>
</div>
</div>
</form>
</main>
</body>
</html>
