<?php
/**
 * ANUBIS Database-First File Watcher
 * 
 * DESIGN: Database primary, filesystem secondary.
 * This watcher records file changes in the database even if files are later deleted,
 * and queues orphaned files for header generation.
 * 
 * @package Lupopedia\ANUBIS
 * @version 4.0.53
 */

define('LUPOPEDIA_PATH', dirname(__DIR__));
require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';
require_once LUPOPEDIA_PATH . '/includes/classes/ANUBIS/QueueProcessor.php';
require_once LUPOPEDIA_PATH . '/includes/functions/upload-handler.php';

$db = DatabaseFactory::getConnection();
$queue = new ANUBIS_QueueProcessor($db);
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Configuration
$watched_dirs = array('docs/', 'channels/', 'prompts/');
$interval = 60; // seconds

echo "🤖 ANUBIS File Watcher (Database Primary) started...\n";
echo "Watching: " . implode(', ', $watched_dirs) . "\n";

while (true) {
    echo "[" . gmdate('Y-m-d H:i:s') . "] Scanning directories...\n";

    foreach ($watched_dirs as $dir_relative) {
        $dir = LUPOPEDIA_PATH . '/' . $dir_relative;
        if (!is_dir($dir))
            continue;

        $directoryIterator = new RecursiveDirectoryIterator($dir);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'md') {
                continue;
            }

            $file_path = $file->getPathname();
            $file_content = file_get_contents($file_path);
            if ($file_content === false)
                continue;

            $file_hash = hash('sha256', $file_content);

            // 1. Database Primacy: Always track file in database
            // (Note: Here we just check for orphaned headers - actual file tracking table would be separate)

            // 2. Check for FLARE headers
            if (strpos($file_content, 'flare.headers:') === false) {
                // Orphan detected!
                echo "🚨 Orphan detected: " . basename($file_path) . "\n";

                // Add to queue with full content for database primacy
                $queue->addToQueue(
                    $file_path,
                    'missing_headers_watcher',
                    5, // priority
                    null, // no header snapshot
                    $file_content // store content in database!
                );
            }
        }
    }

    echo "Scan complete. Sleeping for {$interval}s...\n";
    sleep($interval);
}
