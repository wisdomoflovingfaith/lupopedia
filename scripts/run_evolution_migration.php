<?php
/**
 * Migration Runner for Multi-Agent Evolution
 */
require_once dirname(__FILE__) . '/../lupopedia-config.php';

$db = DatabaseFactory::getConnection();
$sqlFile = ABSPATH . 'database/migrations/dev_20260308_multi_agent_evolution.sql';

if (!is_file($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

echo "Running evolution migration...\n";

$sql = file_get_contents($sqlFile);
// Split by semicolon (Doctrine: simple sequential execution for migrations)
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (empty($query))
        continue;

    try {
        $db->query($query);
    } catch (Exception $e) {
        // Soft error: ignore "table already exists" or "column already exists" for idempotency
        if (strpos($e->getMessage(), 'already exists') === false && strpos($e->getMessage(), 'Duplicate column name') === false) {
            echo "Error in query: " . substr($query, 0, 50) . "...\n";
            echo "Message: " . $e->getMessage() . "\n";
        }
    }
}

echo "Migration complete.\n";
?>