<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=lupopedia', 'root', 'ServBay.dev');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $res = $db->query('SHOW TABLES');
    $tables = $res->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables to drop.\n";
        exit;
    }
    
    $db->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach ($tables as $table) {
        $db->exec("DROP TABLE `$table`");
        echo "Dropped $table\n";
    }
    $db->exec('SET FOREIGN_KEY_CHECKS = 1');
    
    echo "All tables dropped.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
