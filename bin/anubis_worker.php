#!/usr/bin/env php
<?php
/**
 * ANUBIS Worker Script
 * 
 * Processes the ANUBIS queue for orphaned files
 * Can be run as a cron job or manually
 * 
 * Usage: php bin/anubis_worker.php [--batch-size=10] [--daemon] [--interval=60]
 *
 * @package Lupopedia\ANUBIS
 * @version 4.0.53
 */

if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.");
}

// Set up paths
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}

// Load bootstrap
$bootstrap = LUPOPEDIA_PATH . '/includes/bootstrap.php';
if (!file_exists($bootstrap)) {
    // Try to find config manually if bootstrap fails (standalone mode)
    require_once LUPOPEDIA_PATH . '/lupopedia-config.php';
    require_once LUPOPEDIA_PATH . '/includes/classes/pdo_db.php';
    require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
} else {
    require_once $bootstrap;
}

require_once LUPOPEDIA_PATH . '/includes/classes/ANUBIS/QueueProcessor.php';

// Parse arguments
$options = getopt('', array('batch-size::', 'daemon', 'interval::'));
$batch_size = isset($options['batch-size']) ? (int) $options['batch-size'] : 10;
$daemon_mode = isset($options['daemon']);
$interval = isset($options['interval']) ? (int) $options['interval'] : 60;

$db = DatabaseFactory::getConnection();
$queue = new ANUBIS_QueueProcessor($db);

echo "🤖 ANUBIS Worker starting (PID: " . getmypid() . ")\n";
echo "   Batch size: $batch_size\n";
echo "   Mode: " . ($daemon_mode ? "daemon (interval: {$interval}s)" : "one-shot") . "\n\n";

if ($daemon_mode) {
    while (true) {
        anubis_run_batch($queue, $batch_size);
        echo "   Sleeping for $interval seconds...\n";
        sleep($interval);
    }
} else {
    anubis_run_batch($queue, $batch_size);
}

function anubis_run_batch($queue, $batch_size)
{
    $start = microtime(true);

    // Get stats before
    $stats_before = $queue->getQueueStats();

    // Process queue
    $results = $queue->processQueue($batch_size);

    $duration = round(microtime(true) - $start, 2);

    echo "[" . gmdate('Y-m-d H:i:s') . "] Processed " . count($results) . " items in {$duration}s\n";

    // Log results
    foreach ($results as $queue_id => $status) {
        echo "   - Queue #$queue_id: $status\n";
    }

    // Show stats after
    $stats_after = $queue->getQueueStats();
    if ($stats_before && $stats_after) {
        $pending_before = 0;
        $pending_after = 0;
        foreach ($stats_before as $stat) {
            if ($stat['status'] === 'pending')
                $pending_before = $stat['count'];
        }
        foreach ($stats_after as $stat) {
            if ($stat['status'] === 'pending')
                $pending_after = $stat['count'];
        }
        if ($pending_before !== $pending_after) {
            echo "   📊 Queue: $pending_before pending → $pending_after pending\n";
        }
    }
}
?>
