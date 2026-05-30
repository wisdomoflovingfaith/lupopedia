<?php
require_once __DIR__ . '/lupopedia-config.php';

try {
    $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

    echo "Schema for livehelp_layerinvites:\n";
    $stmt = $pdo->query("DESCRIBE livehelp_layerinvites");
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $r['Field'] . " (" . $r['Type'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
