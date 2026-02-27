<?php
/**
 * Dialog System Production Deployment Script
 * 
 * Applies dialog system schema to production database.
 * Non-destructive migration - only creates tables if they don't exist.
 * 
 * Usage: php deploy/apply_dialog_schema.php --production
 *        php deploy/apply_dialog_schema.php --staging
 */

require_once __DIR__ . '/../lupopedia-config.php';

$environment = 'staging';
if (isset($argv[1]) && $argv[1] === '--production') {
    $environment = 'production';
}

echo "🚀 Dialog System Schema Deployment\n";
echo "Environment: {$environment}\n\n";

$db = DatabaseFactory::getConnection();

// Schema name based on environment
$schemaName = $environment === 'production'
    ? 'lupopedia'
    : 'lupopedia_staging_dialog';

echo "📊 Using schema: {$schemaName}\n\n";

// Dialog system tables (from lupopedia_orchestration schema)
$tables = [
    'lupo_dialog_threads' => "
        CREATE TABLE IF NOT EXISTS `{$schemaName}`.`lupo_dialog_threads` (
            `dialog_thread_id` bigint NOT NULL,
            `channel_id` bigint DEFAULT NULL,
            `title` varchar(255) NOT NULL,
            `last_message_ymdhis` bigint DEFAULT NULL,
            `created_ymdhis` bigint NOT NULL,
            `updated_ymdhis` bigint NOT NULL,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `deleted_ymdhis` bigint DEFAULT NULL,
            PRIMARY KEY (`dialog_thread_id`),
            KEY `idx_channel_id` (`channel_id`),
            KEY `idx_last_message` (`last_message_ymdhis`),
            KEY `idx_created_ymdhis` (`created_ymdhis`),
            KEY `idx_is_deleted` (`is_deleted`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'lupo_dialog_messages' => "
        CREATE TABLE IF NOT EXISTS `{$schemaName}`.`lupo_dialog_messages` (
            `dialog_message_id` bigint NOT NULL,
            `dialog_thread_id` bigint DEFAULT NULL,
            `channel_id` bigint DEFAULT NULL,
            `from_actor_id` bigint DEFAULT NULL,
            `to_actor_id` bigint DEFAULT NULL,
            `message_text` varchar(1000) NOT NULL,
            `message_type` varchar(64) NOT NULL DEFAULT 'text',
            `metadata_json` json DEFAULT NULL,
            `mood_rgb` char(6) NOT NULL DEFAULT '666666',
            `weight` decimal(3,2) NOT NULL DEFAULT 0.00,
            `created_ymdhis` bigint NOT NULL,
            `updated_ymdhis` bigint NOT NULL,
            `is_deleted` tinyint NOT NULL DEFAULT 0,
            `deleted_ymdhis` bigint DEFAULT NULL,
            PRIMARY KEY (`dialog_message_id`),
            KEY `idx_thread_id` (`dialog_thread_id`),
            KEY `idx_channel_id` (`channel_id`),
            KEY `idx_from_actor` (`from_actor_id`),
            KEY `idx_to_actor` (`to_actor_id`),
            KEY `idx_created_ymdhis` (`created_ymdhis`),
            KEY `idx_is_deleted` (`is_deleted`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    "
];

// Apply migrations
$success = true;
foreach ($tables as $tableName => $sql) {
    echo "📋 Creating table: {$tableName}... ";

    try {
        $db->query($sql);
        echo "✅ SUCCESS\n";
    } catch (Exception $e) {
        echo "❌ FAILED: {$e->getMessage()}\n";
        $success = false;
    }
}

if ($success) {
    echo "\n✅ Schema deployment complete!\n";
    exit(0);
} else {
    echo "\n❌ Schema deployment failed. Review errors above.\n";
    exit(1);
}

?>