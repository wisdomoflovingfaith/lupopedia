<?php
echo "START PERFORMANCE AUDIT\n";
define('LUPOPEDIA_DEBUG', true);
require_once 'lupopedia-config.php';
try {
    $db = DatabaseFactory::getConnection();
    $pdo = $db->getPdo();
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "COUNT: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo $table . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
