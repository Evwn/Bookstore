<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Hello World this is the home page</h1>
    <p>Message: <strong><?php echo htmlspecialchars($message); ?></strong></p>
    <p>Generated at: <?php echo $timestamp; ?></p>
</body>
</html>
