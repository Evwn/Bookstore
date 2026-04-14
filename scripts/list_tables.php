<?php
$db = new PDO('sqlite:' . __DIR__ . '/../bookstore.db');
$r = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
foreach ($r as $t) echo $t.PHP_EOL;
?>