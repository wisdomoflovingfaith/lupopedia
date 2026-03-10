<?php
require_once dirname(__FILE__) . '/../lupopedia-config.php';
$db = DatabaseFactory::getConnection();

$files = array(
    'database/migrations/dev_20260308_multi_agent_evolution.sql',
    'database/migrations/dev_20260308_base_agent_tables.sql'
);

foreach ($files as $file) {
    echo "Running $file...\n";
    $sql = file_get_contents(ABSPATH . $file);
    foreach (explode(';', $sql) as $q) {
        $q = trim($q);
        if (empty($q))
            continue;
        try {
            $db->query($q);
        } catch (Exception $e) {
            // Ignore duplicates
            if (strpos($e->getMessage(), 'already exists') === false && strpos($e->getMessage(), 'Duplicate') === false) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "All migrations finished.\n";
