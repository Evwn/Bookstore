<?php
require_once 'db.php';
echo 'Database connection: OK' . PHP_EOL;

// Check if tables exist
$tables = ['AUTHORS', 'BOOKS', 'CUSTOMERS', 'ORDERS', 'ORDER_ITEMS'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query('SELECT COUNT(*) FROM ' . $table);
        $count = $stmt->fetchColumn();
        echo $table . ': ' . $count . ' records' . PHP_EOL;
    } catch (Exception $e) {
        echo $table . ': ERROR - ' . $e->getMessage() . PHP_EOL;
    }
}

// Test order with items query
try {
    $stmt = $pdo->query('
        SELECT O.ORDER_ID, O.TOTAL_AMOUNT, C.NAME AS CUSTOMER_NAME, 
               COUNT(OI.ORDER_ITEM_ID) as ITEM_COUNT
        FROM ORDERS O 
        LEFT JOIN CUSTOMERS C ON O.CUSTOMER_ID = C.CUSTOMER_ID 
        LEFT JOIN ORDER_ITEMS OI ON O.ORDER_ID = OI.ORDER_ID
        GROUP BY O.ORDER_ID
        LIMIT 3
    ');
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo PHP_EOL . 'Sample orders with customer linking:' . PHP_EOL;
    foreach ($orders as $order) {
        echo 'Order ' . $order['ORDER_ID'] . ': Customer=' . ($order['CUSTOMER_NAME'] ?? 'None') . ', Items=' . $order['ITEM_COUNT'] . ', Total=$' . $order['TOTAL_AMOUNT'] . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Order query error: ' . $e->getMessage() . PHP_EOL;
}
?>