<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';

$message = '';
$error = '';
$authors = getAllAuthors();

// Handle adding new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $isbn = trim($_POST['isbn'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    
    // Process authors and percentages
    $authors_data = [];
    $total_percentage = 0;
    
    if (isset($_POST['author_ids']) && isset($_POST['percentages'])) {
        foreach ($_POST['author_ids'] as $index => $author_id) {
            $percentage = floatval($_POST['percentages'][$index] ?? 0);
            if ($author_id && $percentage > 0) {
                $authors_data[] = [
                    'author_id' => intval($author_id),
                    'percentage' => $percentage
                ];
                $total_percentage += $percentage;
            }
        }
    }
    
    // Validation
    if (empty($isbn)) {
        $error = 'ISBN is required.';
    } elseif (strlen($isbn) !== 10) {
        $error = 'ISBN must be exactly 10 characters.';
    } elseif (empty($title)) {
        $error = 'Title is required.';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0.';
    } elseif (empty($authors_data)) {
        $error = 'At least one author with percentage is required.';
    } elseif (abs($total_percentage - 100) > 0.01) {
        $error = "Total percentage must equal 100%. Current total: {$total_percentage}%";
    } else {
        if (addBook($isbn, $title, $price, $authors_data)) {
            $message = "Book '$title' (ISBN: $isbn) added successfully.";
        } else {
            $error = 'Failed to add book. ISBN may already exist or other error occurred.';
        }
    }
}

// Handle display source
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_source'])) {
    $password = $_POST['password'] ?? '';
    $result = displaySource(3, $password);
    
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
    <title>Enter Books - Bookstore System</title>
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
                <h1 class="text-3xl font-bold text-slate-900">Enter Books (20%)</h1>
                <p class="mt-2 text-slate-600">Add books one by one where authors are selected, not typed.</p>
            </div>

            <!-- Navigation -->
            <div class="mb-6">
                <nav class="flex space-x-4">
                    <a href="index.php?page=enter_authors" class="text-slate-600 hover:text-primary">← Back: Enter Authors</a>
                    <span class="text-slate-300">|</span>
                    <a href="index.php?page=search_books" class="text-slate-600 hover:text-primary">Next: Search Books →</a>
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

            <?php if (empty($authors)): ?>
                <!-- No Authors Warning -->
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    <div class="flex items-center">
                        <span class="material-icons mr-2">warning</span>
                        <div>
                            <strong>No authors found!</strong> You need to add authors before you can add books.
                            <a href="index.php?page=enter_authors" class="underline ml-2">Add authors first →</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Add Book Form -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 mb-8">
                    <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center">
                        <span class="material-icons text-primary mr-2">library_add</span>
                        Add New Book
                    </h2>
                    
                    <form method="POST" id="bookForm" class="space-y-6">
                        <!-- Basic Book Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="isbn" class="block text-sm font-medium text-slate-700 mb-1">
                                    ISBN (10 characters) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="isbn" name="isbn" required maxlength="10" 
                                       class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                       placeholder="1234567890">
                            </div>
                            
                            <div>
                                <label for="price" class="block text-sm font-medium text-slate-700 mb-1">
                                    Price ($) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="price" name="price" required min="0.01" step="0.01" 
                                       class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                       placeholder="19.99">
                            </div>
                        </div>
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                   placeholder="Enter book title">
                        </div>

                        <!-- Authors and Percentages -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">
                                Authors and Percentages <span class="text-red-500">*</span>
                                <span class="text-xs text-slate-500">(Total must equal 100%)</span>
                            </label>
                            
                            <div id="authorsContainer" class="space-y-3">
                                <!-- Author entries will be added here -->
                            </div>
                            
                            <button type="button" onclick="addAuthorRow()" 
                                    class="mt-3 inline-flex items-center px-3 py-2 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <span class="material-icons text-sm mr-1">add</span>
                                Add Author
                            </button>
                            
                            <div id="totalPercentage" class="mt-2 text-sm font-medium text-slate-600">
                                Total: 0%
                            </div>
                        </div>
                        
                        <button type="submit" name="add_book" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <span class="material-icons text-sm mr-2">add</span>
                            Add Book
                        </button>
                    </form>
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

    <script>
        const authors = <?= json_encode($authors) ?>;
        let authorRowCount = 0;

        function addAuthorRow() {
            authorRowCount++;
            const container = document.getElementById('authorsContainer');
            const row = document.createElement('div');
            row.className = 'flex gap-3 items-center';
            row.id = `authorRow${authorRowCount}`;
            
            row.innerHTML = `
                <div class="flex-1">
                    <select name="author_ids[]" required onchange="calculateTotal()" 
                            class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <option value="">Select Author</option>
                        ${authors.map(author => `<option value="${author.AUTHOR_ID}">${author.NAME}</option>`).join('')}
                    </select>
                </div>
                <div class="w-24">
                    <input type="number" name="percentages[]" required min="0.01" max="100" step="0.01" 
                           placeholder="%" onchange="calculateTotal()"
                           class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                </div>
                <button type="button" onclick="removeAuthorRow(${authorRowCount})" 
                        class="p-2 text-red-500 hover:text-red-700">
                    <span class="material-icons text-sm">delete</span>
                </button>
            `;
            
            container.appendChild(row);
            calculateTotal();
        }

        function removeAuthorRow(id) {
            const row = document.getElementById(`authorRow${id}`);
            if (row) {
                row.remove();
                calculateTotal();
            }
        }

        function calculateTotal() {
            const percentageInputs = document.querySelectorAll('input[name="percentages[]"]');
            let total = 0;
            
            percentageInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            
            const totalElement = document.getElementById('totalPercentage');
            totalElement.textContent = `Total: ${total.toFixed(2)}%`;
            
            if (Math.abs(total - 100) < 0.01) {
                totalElement.className = 'mt-2 text-sm font-medium text-green-600';
            } else {
                totalElement.className = 'mt-2 text-sm font-medium text-red-600';
            }
        }

        // Add first author row by default
        document.addEventListener('DOMContentLoaded', function() {
            if (authors.length > 0) {
                addAuthorRow();
            }
        });
    </script>
</body>
</html>