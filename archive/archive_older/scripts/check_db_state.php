<?php
require_once __DIR__ . '/lupopedia-config.php';

function check_db()
{
    try {
        $dsn = DB_TYPE . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));

        echo "Database: " . DB_NAME . "\n";
        echo "Table Prefix: " . LUPO_TABLE_PREFIX . "\n\n";

        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $lupo_tables = array();
        $livehelp_tables = array();

        foreach ($tables as $table) {
            if (strpos($table, LUPO_TABLE_PREFIX) === 0) {
                $lupo_tables[] = $table;
            } elseif (strpos($table, 'livehelp_') === 0) {
                $livehelp_tables[] = $table;
            }
        }

        echo "Found " . count($lupo_tables) . " Lupopedia tables.\n";
        echo "Found " . count($livehelp_tables) . " Legacy Crafty Syntax tables.\n\n";

        echo "Sample Lupopedia Tables (first 20):\n";
        foreach (array_slice($lupo_tables, 0, 20) as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$table` balance");
            $count = $stmt->fetchColumn();
            echo "- $table: $count rows\n";
        }

        echo "\nCrafty Syntax Mapping Tables:\n";
        $crafty_mapping_tables = array(
            'lupo_crafty_syntax_auto_invite',
            'lupo_crafty_syntax_layer_invites',
            'lupo_crafty_syntax_leave_message',
            'lupo_crafty_syntax_chat_questions',
            'lupo_departments',
            'lupo_actor_departments',
            'lupo_dialog_threads',
            'lupo_dialog_messages',
            'lupo_analytics_paths',
            'lupo_visits'
        );
        foreach ($crafty_mapping_tables as $table) {
            if (in_array($table, $lupo_tables)) {
                $stmt = $pdo->query("SELECT COUNT(*) FROM `$table` balance");
                $count = $stmt->fetchColumn();
                echo "- $table: $count rows\n";
            }
        }

        echo "\nLegacy Source Tables (livehelp_):\n";
        foreach (array_slice($livehelp_tables, 0, 20) as $table) {
            $stmt = $pdo->query("SELECT COUNT(*) FROM `$table` balance");
            $count = $stmt->fetchColumn();
            echo "- $table: $count rows\n";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

check_db();
