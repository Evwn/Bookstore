<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';

$error = '';
$book = null;

// Get book details
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    $book = getBookDetails($isbn);
    if (!$book) {
        $error = 'Book not found.';
    }
} else {
    $error = 'No book ISBN provided.';
}

// Handle display source
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_source'])) {
    $password = $_POST['password'] ?? '';
    $result = displaySource(6, $password);
    
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
    <title>Book Details - Bookstore System</title>
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
                <h1 class="text-3xl font-bold text-slate-900">Book Details (10%)</h1>
                <p class="mt-2 text-slate-600">Complete book information including ISBN, title, authors, percentages, price, and quantity sold.</p>
            </div>

            <!-- Navigation -->
            <div class="mb-6">
                <nav class="flex space-x-4">
                    <a href="index.php?page=search_books" class="text-slate-600 hover:text-primary">← Back to Search</a>
                    <span class="text-slate-300">|</span>
                    <a href="index.php" class="text-primary hover:text-primary-hover">Main Menu</a>
                </nav>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($book): ?>
                <!-- Book Details -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                        <span class="material-icons text-primary mr-3">menu_book</span>
                        <?= htmlspecialchars($book['TITLE']) ?>
                    </h2>
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">ISBN (10 characters)</label>
                                <div class="text-lg font-mono bg-slate-100 px-3 py-2 rounded border">
                                    <?= htmlspecialchars($book['ISBN']) ?>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Title</label>
                                <div class="text-lg font-semibold text-slate-900">
                                    <?= htmlspecialchars($book['TITLE']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Price</label>
                                <div class="text-2xl font-bold text-green-600">
                                    $<?= number_format($book['PRICE'], 2) ?>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Quantity Sold</label>
                                <div class="text-2xl font-bold text-blue-600">
                                    <?= $book['QUANTITY_SOLD'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Authors Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                            <span class="material-icons text-primary mr-2">people</span>
                            Authors and Percentages
                        </h3>
                        
                        <?php if (empty($book['AUTHORS'])): ?>
                            <div class="text-slate-500 italic">No authors assigned to this book.</div>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($book['AUTHORS'] as $author): ?>
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold mr-4">
                                                <?= strtoupper(substr($author['NAME'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <div class="font-medium">
                                                    <a href="index.php?page=author_details&id=<?= $author['AUTHOR_ID'] ?>" 
                                                       class="text-primary hover:text-primary-hover hover:underline">
                                                        <?= htmlspecialchars($author['NAME']) ?>
                                                    </a>
                                                </div>
                                                <div class="text-sm text-slate-500">
                                                    ID: <?= $author['AUTHOR_ID'] ?> | Royalty: $<?= number_format($author['ROYALTY'], 2) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-primary">
                                                <?= $author['PERCENTAGE'] ?>%
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                of book price
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Percentage Verification -->
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                                <div class="text-sm text-blue-800">
                                    <strong>Total Percentage:</strong> 
                                    <?php 
                                    $total_percentage = array_sum(array_column($book['AUTHORS'], 'PERCENTAGE'));
                                    echo $total_percentage;
                                    ?>% 
                                    <?php if (abs($total_percentage - 100) < 0.01): ?>
                                        <span class="text-green-600">✓ Correct</span>
                                    <?php else: ?>
                                        <span class="text-red-600">⚠ Should be 100%</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Actions</h3>
                    <div class="flex gap-3">
                        <a href="index.php?page=update_quantity" 
                           onclick="document.getElementById('updateForm').submit(); return false;"
                           class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <span class="material-icons text-sm mr-2">update</span>
                            Update Quantity
                        </a>
                        <a href="index.php?page=search_books" 
                           class="flex-1 flex justify-center py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <span class="material-icons text-sm mr-2">search</span>
                            Back to Search
                        </a>
                    </div>
                    
                    <!-- Hidden form for update quantity -->
                    <form id="updateForm" method="POST" action="index.php?page=update_quantity" style="display: none;">
                        <input type="hidden" name="selected_isbn" value="<?= htmlspecialchars($book['ISBN']) ?>">
                        <input type="hidden" name="quantity_<?= htmlspecialchars($book['ISBN']) ?>" value="<?= $book['QUANTITY_SOLD'] ?>">
                    </form>
                </div>
            <?php else: ?>
                <!-- Book Not Found -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
                    <div class="text-center py-8 text-slate-500">
                        <span class="material-icons text-4xl mb-2">search_off</span>
                        <p class="text-lg font-medium mb-2">Book Not Found</p>
                        <p>The requested book could not be found in the system.</p>
                        <a href="index.php?page=search_books" 
                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover">
                            <span class="material-icons text-sm mr-2">search</span>
                            Search Books
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Display Source Section -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
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