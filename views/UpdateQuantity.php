<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';

$message = '';
$error = '';
$updated = false;

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_selected'])) {
    $selected_isbn = $_POST['selected_isbn'] ?? '';
    
    if (empty($selected_isbn)) {
        $error = 'Please select a book to update.';
    } else {
        $quantity_field = "quantity_$selected_isbn";
        $new_quantity = intval($_POST[$quantity_field] ?? 0);
        
        if ($new_quantity < 0) {
            $error = 'Quantity cannot be negative.';
        } else {
            if (updateBookQuantity($selected_isbn, $new_quantity)) {
                $message = "Book quantity updated successfully to $new_quantity.";
                $updated = true;
            } else {
                $error = 'Failed to update book quantity.';
            }
        }
    }
}

// Handle display source
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_source'])) {
    $password = $_POST['password'] ?? '';
    $result = displaySource(5, $password);
    
    if ($result['success']) {
        header('Content-Type: text/plain');
        echo $result['source'];
        exit;
    } else {
        $error = $result['message'];
    }
}

// Get book details if ISBN provided
$book = null;
if (isset($_POST['selected_isbn']) && !empty($_POST['selected_isbn'])) {
    $book = getBookDetails($_POST['selected_isbn']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Update Book Quantity - Bookstore System</title>
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
                <h1 class="text-3xl font-bold text-slate-900">Update Book Quantity (20%)</h1>
                <p class="mt-2 text-slate-600">Update the selected book's quantity sold.</p>
            </div>

            <!-- Navigation -->
            <div class="mb-6">
                <nav class="flex space-x-4">
                    <a href="index.php?page=search_books" class="text-slate-600 hover:text-primary">← Back to Search</a>
                    <span class="text-slate-300">|</span>
                    <a href="index.php" class="text-primary hover:text-primary-hover">Main Menu</a>
                </nav>
            </div>

            <!-- Messages -->
            <?php if ($message): ?>
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($message) ?>
                    <?php if ($updated): ?>
                        <div class="mt-2">
                            <a href="index.php?page=search_books" class="underline">← Back to search results</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($book): ?>
                <!-- Book Details and Update Form -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
                    <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center">
                        <span class="material-icons text-primary mr-2">update</span>
                        Update Book Quantity
                    </h2>
                    
                    <!-- Book Information -->
                    <div class="bg-slate-50 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-slate-900 mb-2">Selected Book:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong>Title:</strong> <?= htmlspecialchars($book['TITLE']) ?></div>
                            <div><strong>ISBN:</strong> <?= htmlspecialchars($book['ISBN']) ?></div>
                            <div><strong>Price:</strong> $<?= number_format($book['PRICE'], 2) ?></div>
                            <div><strong>Current Quantity Sold:</strong> <?= $book['QUANTITY_SOLD'] ?></div>
                        </div>
                        <?php if (!empty($book['AUTHORS'])): ?>
                            <div class="mt-2">
                                <strong>Authors:</strong>
                                <?php foreach ($book['AUTHORS'] as $index => $author): ?>
                                    <?= htmlspecialchars($author['NAME']) ?> (<?= $author['PERCENTAGE'] ?>%)<?= $index < count($book['AUTHORS']) - 1 ? ', ' : '' ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Update Form -->
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="selected_isbn" value="<?= htmlspecialchars($book['ISBN']) ?>">
                        
                        <div>
                            <label for="new_quantity" class="block text-sm font-medium text-slate-700 mb-1">
                                New Quantity Sold <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="new_quantity" name="quantity_<?= htmlspecialchars($book['ISBN']) ?>" 
                                   value="<?= $book['QUANTITY_SOLD'] ?>" min="0" required 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                            <p class="mt-1 text-xs text-slate-500">Enter the total number of books sold (not additional sales).</p>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="submit" name="update_selected" 
                                    class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <span class="material-icons text-sm mr-2">update</span>
                                Update Quantity
                            </button>
                            <a href="index.php?page=search_books" 
                               class="flex-1 flex justify-center py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <!-- No Book Selected -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
                    <div class="text-center py-8 text-slate-500">
                        <span class="material-icons text-4xl mb-2">info</span>
                        <p class="text-lg font-medium mb-2">No Book Selected</p>
                        <p>Please go back to the search page and select a book to update.</p>
                        <a href="index.php?page=search_books" 
                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary-hover">
                            <span class="material-icons text-sm mr-2">search</span>
                            Go to Search Books
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