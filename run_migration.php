<?php
require_once 'lupopedia-config.php';
$migrationFile = isset($argv[1]) ? $argv[1] : 'database/migrations/table_consolidation_phase1.sql';
if (!file_exists($migrationFile)) {
    die("Migration file not found: $migrationFile\n");
}

echo "RUNNING MIGRATION: $migrationFile\n";
$sql = file_get_contents($migrationFile);
$db = DatabaseFactory::getConnection();
$pdo = $db->getPdo();

$sql = preg_replace('/--.*$/m', '', $sql);
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (empty($query))
        continue;

    echo "Executing query (" . strlen($query) . ")...\n";
    try {
        $pdo->exec($query);
        echo "OK\n";
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}
