
<?php

$host = '20.185.147.112';
$port = '1521';
$sid  = 'xe';
$user = 'C##yannick_mbolela';
$pass = 'yannick4592';

try {
    $dsn = "oci:dbname=(DESCRIPTION=
                (ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))
                (CONNECT_DATA=(SID=$sid))
            )";

    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to Oracle successfully";

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>