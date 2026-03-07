<?php
require_once __DIR__ . '/lupopedia-config.php';

$db = DatabaseFactory::getConnection();
$sql_file = __DIR__ . '/lupo-database/lupopedia/mysql/migrations/20260306_actor_primary_key_migration.sql';

if (!file_exists($sql_file)) {
    die("Error: Migration file not found.\n");
}

$sql = file_get_contents($sql_file);

// Split SQL into separate statements. Simple regex split by semicolon followed by newline.
$queries = preg_split('/;\s*(\r\n|\n|\r)/', $sql);

echo "Starting database migration...\n";

foreach ($queries as $query) {
    if (trim($query) === '')
        continue;
    try {
        echo "Executing: " . substr(trim($query), 0, 100) . "...\n";
        $db->query($query);
    } catch (Exception $e) {
        // If it's a "Duplicate column name" error, we can ignore it (idempotency)
        if (
            strpos($e->getMessage(), 'Duplicate column name') !== false ||
            strpos($e->getMessage(), 'Duplicate key name') !== false ||
            strpos($e->getMessage(), 'Multiple primary key defined') !== false
        ) {
            echo "  [SKIP] Rule already applied.\n";
        } else {
            echo "  [ERROR] " . $e->getMessage() . "\n";
        }
    }
}

echo "Database migration finished.\n";
