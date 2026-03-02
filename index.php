<?php
// index.php - Route requests to controller and view

// Check DB connection first
$dbError = '';
try {
    require_once __DIR__ . '/db.php';
    // Try a simple query to check connection
    $pdo->query('SELECT 1');
} catch (Exception $e) {
    $dbError = $e->getMessage();
}

// Get page parameter from URL, default to dashboard
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Map page to view file
$views = [
    'dashboard' => 'views/Dashbord.php',
    'inventrory' => 'views/Inventrory.php',
    'author' => 'views/Author.php',
    'authordetails' => 'views/AuthorDetails.php',
    'addauthor' => 'views/AddAuthor.php',
    'addnewbook' => 'views/AddNewBook.php',
    'bookdetails' => 'views/BookDetails.php',
    'customer' => 'views/Customer.php',
    'order' => 'views/Order.php',
    'editbook' => 'views/EditBook.php',
    'editauthor' => 'views/EditAuthor.php',
    'editcustomer' => 'views/EditCustomer.php',
    'editorder' => 'views/EditOrder.php',
];

// Check if page exists, default to dashboard
$viewFile = isset($views[$page]) ? $views[$page] : $views['dashboard'];

// Start with HTML structure and navigation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-content": "#ffffff",
                        "primary-dark": "#0e45b5",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1a2234",
                        "border-light": "#e5e7eb",
                        "border-dark": "#2d3748",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-100 antialiased h-screen flex overflow-hidden">

<!-- Loading Overlay -->
<div id="loading-overlay" style="position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.8);display:flex;align-items:center;justify-content:center;transition:opacity 0.3s;">
    <div class="flex flex-col items-center">
        <span class="material-icons animate-spin text-5xl text-primary mb-4" style="animation:spin 1s linear infinite;">autorenew</span>
        <span class="text-lg font-semibold text-primary">Loading...</span>
    </div>
</div>
<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; }
</style>
<script>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.getElementById('loading-overlay').style.display = 'none';
    }, 600); // Simulate loading
});
</script>

<?php
// Include navigation
include 'Navigation.php';
?>

<!-- Main Content Area -->
<main class="flex-1 flex flex-col h-full overflow-hidden relative">
    <!-- Mobile Header -->
    <header class="md:hidden h-16 bg-surface-light dark:bg-surface-dark border-b border-border-light dark:border-border-dark flex items-center justify-between px-4 z-10">
        <button id="mobile-menu-btn" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
            <span class="material-icons">menu</span>
        </button>
        <h1 class="text-lg font-bold text-slate-900 dark:text-white">Admin Portal</h1>
        <div class="w-10"></div>
    </header>

    <!-- Page Content -->
    <div class="flex-1 overflow-y-auto">
        <?php if ($dbError): ?>
            <div class="max-w-xl mx-auto mt-20 p-8 bg-red-100 text-red-800 rounded-lg shadow text-center">
                <span class="material-icons text-4xl mb-2">error_outline</span>
                <h2 class="text-2xl font-bold mb-2">Database Connection Failed</h2>
                <div class="mb-2">Sorry, the application could not connect to the database.</div>
                <div class="text-sm text-red-700 mb-4"><?php echo htmlspecialchars($dbError); ?></div>
                <a href="" class="inline-block px-4 py-2 bg-primary text-white rounded hover:bg-blue-700">Retry</a>
            </div>
        <?php else: ?>
            <?php
            // Include the view
            if (file_exists($viewFile)) {
                include $viewFile;
            } else {
                echo "Page not found";
            }
            ?>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

