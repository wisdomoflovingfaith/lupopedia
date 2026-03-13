<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=lupopedia', 'root', 'ServBay.dev');
    $res = $db->query('SHOW TABLES');
    $tables = $res->fetchAll(PDO::FETCH_COLUMN);
    echo "Count: " . count($tables) . "\n";
    print_r($tables);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
