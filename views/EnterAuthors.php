<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';

$message = '';
$error = '';
$authors = getAllAuthors();

// Handle adding new author
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_author'])) {
    $name = trim($_POST['name'] ?? '');
    
    if (empty($name)) {
        $error = 'Author name is required.';
    } else {
        if (addAuthor($name)) {
            $message = "Author '$name' added successfully with royalty $0.00.";
            $authors = getAllAuthors(); // Refresh list
        } else {
            $error = 'Failed to add author. Name may already exist.';
        }
    }
}

// Handle display source
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_source'])) {
    $password = $_POST['password'] ?? '';
    $result = displaySource(2, $password);
    
    if ($result['success']) {
        header('Content-Type: text/plain');
        echo $result['source'];
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Enter Authors - Bookstore System</title>
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
<body class="bg-background-light font-display text-slate-800 min-h-screen">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900">Enter Authors (5%)</h1>
                <p class="mt-2 text-slate-600">Add authors one by one. Royalty is zero initially.</p>
            </div>

            <!-- Navigation -->
            <div class="mb-6">
                <nav class="flex space-x-4">
                    <a href="index.php" class="text-primary hover:text-primary-hover">← Back to Main</a>
                    <span class="text-slate-300">|</span>
                    <a href="index.php?page=enter_books" class="text-slate-600 hover:text-primary">Next: Enter Books →</a>
                </nav>
            </div>

            <!-- Messages -->
            <?php if ($message): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Add Author Form -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4 flex items-center">
                        <span class="material-icons text-primary mr-2">person_add</span>
                        Add New Author
                    </h2>
                    
                    <form method="POST" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                                Author Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                   placeholder="Enter author's full name">
                        </div>
                        
                        <button type="submit" name="add_author" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <span class="material-icons text-sm mr-2">add</span>
                            Add Author
                        </button>
                    </form>
                </div>

                <!-- Current Authors List -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-900 mb-4 flex items-center">
                        <span class="material-icons text-primary mr-2">people</span>
                        Current Authors (<?= count($authors) ?>)
                    </h2>
                    
                    <?php if (empty($authors)): ?>
                        <div class="text-center py-8 text-slate-500">
                            <span class="material-icons text-4xl mb-2">person_outline</span>
                            <p>No authors added yet.</p>
                            <p class="text-sm">Add your first author to get started.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <?php foreach ($authors as $author): ?>
                                <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg hover:bg-slate-50">
                                    <div>
                                        <div class="font-medium text-slate-900">
                                            <?= htmlspecialchars($author['NAME']) ?>
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            ID: <?= $author['AUTHOR_ID'] ?> | Royalty: $<?= number_format($author['ROYALTY'], 2) ?>
                                        </div>
                                    </div>
                                    <a href="index.php?page=author_details&id=<?= $author['AUTHOR_ID'] ?>" 
                                       class="text-primary hover:text-primary-hover">
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Display Source Section -->
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-medium text-slate-900 mb-4 flex items-center">
                    <span class="material-icons text-primary mr-2">code</span>
                    Display Source
                </h3>
                <form method="POST" class="flex gap-3">
                    <input type="password" name="password" placeholder="Enter password" required 
                           class="flex-1 px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    <button type="submit" name="display_source" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Display Source
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>