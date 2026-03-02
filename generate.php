<?php
// Create an HTML file with Hello World
$html_content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World</title>
</head>
<body>
    <h1>Hello World</h1>
</body>
</html>
HTML;

// Write the HTML content to a file
file_put_contents('index.html', $html_content);

echo "HTML file generated successfully: index.html\n";
?>
