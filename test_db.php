<?php
require_once 'lupopedia-config.php';
try {
    $db = DatabaseFactory::getConnection();
    echo "Connection Success\n";
    $tables = $db->fetchAll("SHOW TABLES");
    echo "Tables: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo "- " . array_values($table)[0] . "\n";
    }
} catch (Exception $e) {
    echo "Connection Failed: " . $e->getMessage() . "\n";
}
