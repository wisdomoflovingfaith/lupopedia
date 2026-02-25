<?php
/**
 * Standardize MD Files in channels/0/broadcasts/
 * 
 * Converts cw_* files to standard format: [YYYYMMDDHHIISS]_[FROM]_[TO]_[CHANNEL]_[TITLE].md
 * FROM: 10000 (root captain)
 * TO: 1000 (broadcast to all - will be interpreted as system broadcast)
 * CHANNEL: 0 (system channel)
 * 
 * @package Lupopedia
 * @version 4.0.45
 */

define('LUPOPEDIA_PATH', dirname(__DIR__));
$broadcasts_dir = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . '0' . DIRECTORY_SEPARATOR . 'broadcasts';

if (!is_dir($broadcasts_dir)) {
    die("Error: broadcasts directory not found: {$broadcasts_dir}\n");
}

// Mapping of cw_ files to their metadata
$file_mappings = array(
    'cw_0001_php_compatibility.md' => array(
        'timestamp' => '20260224153000',
        'title' => 'php_compatibility_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0002_timestamp_standard.md' => array(
        'timestamp' => '20260224153100',
        'title' => 'timestamp_standard_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0003_soft_delete.md' => array(
        'timestamp' => '20260224153200',
        'title' => 'soft_delete_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0004_pdo_factory.md' => array(
        'timestamp' => '20260224153300',
        'title' => 'pdo_database_factory_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0005_oop_enforcement.md' => array(
        'timestamp' => '20260224153400',
        'title' => 'oop_enforcement_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0006_cross_db_sql.md' => array(
        'timestamp' => '20260224153500',
        'title' => 'cross_database_sql_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0007_windows_wsl.md' => array(
        'timestamp' => '20260224153600',
        'title' => 'windows_wsl_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0008_db_feature_ban.md' => array(
        'timestamp' => '20260224153700',
        'title' => 'database_feature_ban_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0009_full_column_queries.md' => array(
        'timestamp' => '20260224153800',
        'title' => 'full_column_queries_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    ),
    'cw_0010_registry_id_policy.md' => array(
        'timestamp' => '20260224153900',
        'title' => 'registry_id_policy_doctrine',
        'from' => 10000,
        'to' => 1000,
        'channel' => 0
    )
);

$renamed_count = 0;
$errors = array();

echo "Lupopedia MD File Standardization Script\n";
echo "=========================================\n\n";

foreach ($file_mappings as $old_name => $meta) {
    $old_path = $broadcasts_dir . DIRECTORY_SEPARATOR . $old_name;
    $new_name = sprintf(
        '%s_%d_%d_%d_%s.md',
        $meta['timestamp'],
        $meta['from'],
        $meta['to'],
        $meta['channel'],
        $meta['title']
    );
    $new_path = $broadcasts_dir . DIRECTORY_SEPARATOR . $new_name;
    
    if (!file_exists($old_path)) {
        echo "[SKIP] {$old_name} - file not found\n";
        continue;
    }
    
    if (file_exists($new_path)) {
        echo "[SKIP] {$old_name} - target already exists: {$new_name}\n";
        continue;
    }
    
    // Read content
    $content = file_get_contents($old_path);
    if ($content === false) {
        $errors[] = "Failed to read: {$old_name}";
        echo "[ERROR] Failed to read: {$old_name}\n";
        continue;
    }
    
    // Update YAML front matter to include from/to
    $content = preg_replace(
        '/^---\n(.*?)^---\n/ms',
        "---\nfrom_actor_id: {$meta['from']}\nto_actor_id: {$meta['to']}\n$1---\n",
        $content
    );
    
    // Write to new location
    if (file_put_contents($new_path, $content) === false) {
        $errors[] = "Failed to write: {$new_name}";
        echo "[ERROR] Failed to write: {$new_name}\n";
        continue;
    }
    
    // Delete old file
    if (!unlink($old_path)) {
        $errors[] = "Failed to delete: {$old_name}";
        echo "[WARN] Failed to delete old file: {$old_name}\n";
    }
    
    echo "[OK] Renamed: {$old_name} -> {$new_name}\n";
    $renamed_count++;
}

echo "\n=========================================\n";
echo "Summary:\n";
echo "  Renamed: {$renamed_count} files\n";
echo "  Errors: " . count($errors) . "\n";

if (!empty($errors)) {
    echo "\nErrors encountered:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    exit(1);
}

echo "\nAll MD files standardized successfully!\n";
exit(0);
