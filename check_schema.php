<?php
require_once __DIR__ . '/lupopedia-config.php';

function check_table_schema($table)
{
    try {
        $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));

        echo "Schema for $table:\n";
        $stmt = $pdo->query("DESCRIBE `$table` ");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

check_table_schema('livehelp_autoinvite');
echo "\n";
check_table_schema('lupo_crafty_syntax_auto_invite');
