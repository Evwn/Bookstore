<?php
require_once __DIR__ . '/../controllers/AssignmentController.php';

$message      = '';
$error        = '';
$books        = [];
$search_query = '';
$searched     = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_query = trim($_POST['query'] ?? '');
    $books        = searchBooks($search_query);
    $searched     = true;
    $message      = empty($search_query) ? 'Showing all books.' : 'Results for: "' . htmlspecialchars($search_query) . '"';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    $books        = searchBooks($search_query);
    $searched     = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_selected'])) {
    $book_id = (int)($_POST['selected_book_id'] ?? 0);
    $stock   = (int)($_POST['new_stock'] ?? 0);
    if ($book_id > 0) {
        updateBookStock($book_id, $stock) ? $message = 'Stock updated.' : $error = 'Failed to update stock.';
    } else {
        $error = 'Please select a book first.';
    }
    $search_query = trim($_POST['last_query'] ?? '');
    $books        = searchBooks($search_query);
    $searched     = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['display_source'])) {
    $result = displaySource(4, $_POST['password'] ?? '');
    if ($result['success']) { header('Content-Type: text/plain'); echo $result['source']; exit; }
    $error = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Search Books</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #fff; color: #000; font-family: sans-serif; font-size: 14px; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 200px; background: #f5f5f5; border-right: 1px solid #ddd; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; }
        .sidebar-logo { padding: 16px; border-bottom: 1px solid #ddd; font-weight: bold; font-size: 15px; }
        .sidebar-logo small { display: block; font-size: 11px; color: #666; font-weight: normal; margin-top: 2px; }
        .nav-group { padding: 12px 8px 4px; }
        .nav-label { font-size: 10px; text-transform: uppercase; color: #999; padding: 0 8px; margin-bottom: 4px; letter-spacing: .05em; }
        .nav-link { display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 5px; text-decoration: none; color: #333; font-size: 13px; margin-bottom: 1px; }
        .nav-link:hover { background: #e8e8e8; }
        .nav-link.active { background: #000; color: #fff; }
        .nav-link .material-icons { font-size: 15px; }
        .sidebar-footer { margin-top: auto; padding: 12px 16px; border-top: 1px solid #ddd; font-size: 11px; color: #999; }

        /* Main */
        .main { margin-left: 200px; flex: 1; display: flex; flex-direction: column; }
        .topbar { height: 48px; background: #fff; border-bottom: 1px solid #ddd; display: flex; align-items: center; padding: 0 24px; position: sticky; top: 0; z-index: 10; }
        .topbar h1 { font-size: 16px; font-weight: 600; }
        .content { padding: 24px; }

        /* Alerts */
        .alert { padding: 10px 14px; border-radius: 5px; margin-bottom: 16px; font-size: 13px; }
        .alert-info    { background: #f0f0f0; border: 1px solid #ccc; }
        .alert-success { background: #f0faf4; border: 1px solid #a3d9b1; }
        .alert-error   { background: #fff0f0; border: 1px solid #f5b7b7; }

        /* Card */
        .card { border: 1px solid #ddd; border-radius: 6px; margin-bottom: 20px; overflow: hidden; }
        .card-header { padding: 10px 16px; background: #f9f9f9; border-bottom: 1px solid #ddd; display: flex; align-items: center; justify-content: space-between; font-size: 13px; font-weight: 600; }
        .badge { font-size: 11px; background: #eee; border: 1px solid #ddd; padding: 1px 8px; border-radius: 20px; font-weight: normal; color: #555; }

        /* Search bar */
        .search-bar { padding: 14px 16px; display: flex; gap: 8px; }
        .search-bar input { flex: 1; padding: 7px 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 13px; outline: none; }
        .search-bar input:focus { border-color: #000; }

        /* Buttons */
        .btn { padding: 7px 14px; border-radius: 5px; font-size: 13px; cursor: pointer; border: 1px solid #ccc; background: #fff; color: #000; display: inline-flex; align-items: center; gap: 5px; }
        .btn:hover { background: #f0f0f0; }
        .btn-dark { background: #000; color: #fff; border-color: #000; }
        .btn-dark:hover { background: #222; }
        .btn .material-icons { font-size: 15px; }

        /* Book rows */
        .book-row { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-bottom: 1px solid #eee; cursor: pointer; }
        .book-row:last-child { border-bottom: none; }
        .book-row:hover { background: #fafafa; }
        .book-row.selected { background: #f5f5f5; }
        .book-row input[type=radio] { flex-shrink: 0; }
        .book-cover { width: 34px; height: 46px; border-radius: 3px; object-fit: cover; flex-shrink: 0; border: 1px solid #eee; }
        .cover-ph { width: 34px; height: 46px; border-radius: 3px; background: #f0f0f0; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .cover-ph .material-icons { font-size: 14px; color: #aaa; }
        .book-info { flex: 1; min-width: 0; }
        .book-title { font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .book-title a { color: #000; text-decoration: none; }
        .book-title a:hover { text-decoration: underline; }
        .book-meta { display: flex; flex-wrap: wrap; gap: 4px 14px; margin-top: 3px; font-size: 12px; color: #555; }
        .genre-tag { background: #eee; border-radius: 3px; padding: 0 6px; font-size: 11px; }

        /* Inline stock editor */
        .stock-edit { display: none; align-items: center; gap: 6px; flex-shrink: 0; }
        .stock-edit.show { display: flex; }
        .stock-edit label { font-size: 11px; color: #555; white-space: nowrap; }
        .stock-edit input { width: 64px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; text-align: center; outline: none; }
        .stock-edit input:focus { border-color: #000; }

        /* Update bar */
        .update-bar { display: none; padding: 10px 16px; background: #f9f9f9; border-top: 1px solid #ddd; justify-content: flex-end; gap: 10px; align-items: center; }
        .update-bar.show { display: flex; }
        .update-hint { font-size: 12px; color: #888; }

        /* Empty */
        .empty { padding: 40px; text-align: center; color: #999; }
        .empty .material-icons { font-size: 36px; display: block; margin-bottom: 8px; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">BookManager <small>Admin Portal</small></div>
    <div class="nav-group">
        <div class="nav-label">Overview</div>
        <a href="index.php" class="nav-link"><span class="material-icons">dashboard</span>Dashboard</a>
    </div>
    <div class="nav-group">
        <div class="nav-label">Catalogue</div>
        <a href="index.php?page=enter_authors"  class="nav-link"><span class="material-icons">person_add</span>Add Author</a>
        <a href="index.php?page=author_details" class="nav-link"><span class="material-icons">people</span>Authors</a>
        <a href="index.php?page=enter_books"    class="nav-link"><span class="material-icons">library_add</span>Add Book</a>
        <a href="index.php?page=book_details"   class="nav-link"><span class="material-icons">menu_book</span>Books</a>
    </div>
    <div class="nav-group">
        <div class="nav-label">Operations</div>
        <a href="index.php?page=search_books"    class="nav-link active"><span class="material-icons">search</span>Search Books</a>
        <a href="index.php?page=update_quantity" class="nav-link"><span class="material-icons">inventory_2</span>Update Stock</a>
    </div>
    <div class="nav-group">
        <div class="nav-label">System</div>
        <a href="index.php?page=view_source" class="nav-link"><span class="material-icons">code</span>View Source</a>
    </div>
    <div class="sidebar-footer">Bookstore v1.0</div>
</aside>

<div class="main">
    <div class="topbar"><h1>Search Books</h1></div>
    <div class="content">

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Search -->
        <div class="card">
            <div class="card-header">Search</div>
            <form method="POST">
                <div class="search-bar">
                    <input type="text" name="query"
                           value="<?= htmlspecialchars($search_query) ?>"
                           placeholder="Keywords separated by spaces — leave empty to show all"
                           autofocus/>
                    <button type="submit" name="search" class="btn btn-dark">
                        <span class="material-icons">search</span> Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <?php if ($searched): ?>
        <div class="card">
            <div class="card-header">
                Results
                <span class="badge"><?= count($books) ?> found</span>
            </div>

            <?php if (empty($books)): ?>
                <div class="empty">
                    <span class="material-icons">search_off</span>
                    No books found. Try different keywords.
                </div>
            <?php else: ?>
            <form method="POST" id="updateForm">
                <input type="hidden" name="last_query"      value="<?= htmlspecialchars($search_query) ?>">
                <input type="hidden" name="selected_book_id" id="selectedBookId" value="">
                <input type="hidden" name="new_stock"        id="newStock"       value="">

                <?php foreach ($books as $book): ?>
                <div class="book-row" onclick="selectRow(this, <?= $book['BOOK_ID'] ?>, <?= (int)$book['STOCK'] ?>)">
                    <input type="radio" name="_sel" value="<?= $book['BOOK_ID'] ?>" id="row_<?= $book['BOOK_ID'] ?>">

                    <?php if (!empty($book['COVER_URL'])): ?>
                        <img src="<?= htmlspecialchars($book['COVER_URL']) ?>" class="book-cover" alt="">
                    <?php else: ?>
                        <div class="cover-ph"><span class="material-icons">menu_book</span></div>
                    <?php endif; ?>

                    <div class="book-info">
                        <div class="book-title">
                            <a href="index.php?page=book_details&book_id=<?= $book['BOOK_ID'] ?>"
                               onclick="event.stopPropagation()">
                                <?= htmlspecialchars($book['TITLE']) ?>
                            </a>
                        </div>
                        <div class="book-meta">
                            <span><?= htmlspecialchars($book['AUTHOR_NAME'] ?? '—') ?></span>
                            <span>ISBN: <?= htmlspecialchars($book['ISBN']) ?></span>
                            <span class="genre-tag"><?= htmlspecialchars($book['GENRE'] ?? '—') ?></span>
                            <span>$<?= number_format($book['PRICE'], 2) ?></span>
                            <span>Stock: <?= $book['STOCK'] ?></span>
                            <span><?= $book['PUBLISHED_YEAR'] ?></span>
                        </div>
                    </div>

                    <div class="stock-edit" id="stockEdit_<?= $book['BOOK_ID'] ?>">
                        <label>New stock</label>
                        <input type="number" min="0"
                               id="stockInput_<?= $book['BOOK_ID'] ?>"
                               value="<?= (int)$book['STOCK'] ?>"
                               onclick="event.stopPropagation()"
                               oninput="document.getElementById('newStock').value = this.value">
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="update-bar" id="updateBar">
                    <span class="update-hint" id="updateHint">Select a book to update its stock</span>
                    <button type="submit" name="update_selected" class="btn btn-dark">
                        <span class="material-icons">save</span> Save Stock
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- View Source -->
        <div class="card">
            <div class="card-header">View Source</div>
            <form method="POST" style="padding:14px 16px; display:flex; gap:8px;">
                <input type="password" name="password" placeholder="Password"
                       style="flex:1; padding:7px 10px; border:1px solid #ccc; border-radius:5px; font-size:13px; outline:none;">
                <button type="submit" name="display_source" class="btn">
                    <span class="material-icons">code</span> View
                </button>
            </form>
        </div>

    </div>
</div>

<script>
let currentBookId = null;
function selectRow(row, bookId, stock) {
    if (currentBookId !== null) {
        document.getElementById('stockEdit_' + currentBookId).classList.remove('show');
        const pr = document.querySelector('.book-row.selected');
        if (pr) pr.classList.remove('selected');
    }
    if (currentBookId === bookId) {
        currentBookId = null;
        document.getElementById('selectedBookId').value = '';
        document.getElementById('updateBar').classList.remove('show');
        document.getElementById('row_' + bookId).checked = false;
        return;
    }
    currentBookId = bookId;
    row.classList.add('selected');
    document.getElementById('row_' + bookId).checked = true;
    document.getElementById('selectedBookId').value = bookId;
    document.getElementById('newStock').value = document.getElementById('stockInput_' + bookId).value;
    document.getElementById('stockEdit_' + bookId).classList.add('show');
    document.getElementById('updateHint').textContent = 'Updating stock for selected book';
    document.getElementById('updateBar').classList.add('show');
}
</script>
</body>
</html>