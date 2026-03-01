<?php
require_once 'lupopedia-config.php';
$db = DatabaseFactory::getConnection();
$pdo = $db->getPdo();
$stmt = $pdo->query("SHOW TABLES LIKE 'lupo_%'");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

$candidates = array();
foreach ($tables as $t) {
    if (strpos($t, 'log') !== false || strpos($t, 'event') !== false) {
        $candidates[] = $t;
    }
}

echo "LOGGING CANDIDATES:\n";
foreach ($candidates as $c) {
    echo $c . "\n";
}
