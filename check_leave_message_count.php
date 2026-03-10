<?php
require_once __DIR__ . '/lupopedia-config.php';

try {
    $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));

    $stmt = $pdo->query("SELECT COUNT(*) FROM lupo_crafty_syntax_leave_message");
    echo "Count in lupo_crafty_syntax_leave_message: " . $stmt->fetchColumn() . "\n";

    $stmt = $pdo->query("SELECT COUNT(*) FROM livehelp_leavemessage");
    echo "Count in livehelp_leavemessage: " . $stmt->fetchColumn() . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
