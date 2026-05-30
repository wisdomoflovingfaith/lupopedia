<?php
require_once __DIR__ . '/lupopedia-config.php';

try {
    $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

    $stmt = $pdo->query("SELECT user_id FROM livehelp_autoinvite");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Rows in livehelp_autoinvite: " . count($rows) . "\n";
    foreach ($rows as $row) {
        echo " - user_id: " . ($row['user_id'] === null ? 'NULL' : $row['user_id']) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
