<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/ingest_channel66_production.php"
  last_modified_utc: "20260324175911"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/ingest_channel66_production.php"
  last_modified_utc: "20260324175617"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Channel 66 Production Migration Script
 * 
 * Production-grade ingestion for all Channel 66 threads with batch processing,
 * performance monitoring, and error handling beyond Thread 1001 fixture scope.
 * 
 * Usage: php lupo-scripts/ingest_channel66_production.php [options]
 * 
 * Options:
 *   --thread-id=<id>        (default: null = all threads)
 *   --batch-size=<num>       (default: 100)
 *   --memory-limit=<size>    (default: 256M)
 *   --config=<path>          (production config file)
 *   --dry-run                (validate only, no DB writes)
 *   --monitoring             (enable detailed performance monitoring)
 * 
 * @version 4.0.80
 * @author HEPHAESTUS (actor_id 3)
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', ABSPATH);
}

require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'lupo-includes/bootstrap.php';

// Parse command line arguments
$options = getopt('', array(
    'thread-id:',
    'batch-size:',
    'memory-limit:',
    'config:',
    'dry-run',
    'monitoring'
));

$threadId = isset($options['thread-id']) ? (int)$options['thread-id'] : null;
$batchSize = isset($options['batch-size']) ? (int)$options['batch-size'] : 100;
$memoryLimit = isset($options['memory-limit']) ? $options['memory-limit'] : '256M';
$configFile = isset($options['config']) ? $options['config'] : null;
$dryRun = isset($options['dry-run']);
$enableMonitoring = isset($options['monitoring']);

// Validate configuration
$config = validateProductionConfig($configFile, $threadId, $batchSize, $memoryLimit);

// Bootstrap database
global $mydatabase;
if (!$mydatabase) {
    die("Error: Database connection failed.\n");
}

try {
    // Initialize production components
    require_once ABSPATH . 'lupo-includes/classes/Channel66ProductionConfig.php';
    require_once ABSPATH . 'lupo-includes/classes/Channel66ProductionIngester.php';
    require_once ABSPATH . 'lupo-includes/classes/Channel66ProductionErrorHandler.php';
    require_once ABSPATH . 'lupo-includes/classes/Channel66PerformanceMonitor.php';
    require_once ABSPATH . 'lupo-includes/classes/Channel66ProductionLogger.php';
    require_once ABSPATH . 'lupo-includes/classes/Channel66BatchProcessor.php';
    
    $productionConfig = new Channel66ProductionConfig($config);
    $performanceMonitor = new Channel66PerformanceMonitor($enableMonitoring);
    $errorHandler = new Channel66ProductionErrorHandler();
    $logger = new Channel66ProductionLogger();
    $batchProcessor = new Channel66BatchProcessor($batchSize, $memoryLimit);
    
    // Create production ingester
    $ingester = new Channel66ProductionIngester(
        $mydatabase,
        $productionConfig,
        $performanceMonitor,
        $errorHandler,
        $logger,
        $batchProcessor
    );
    
    // Run production migration
    echo "Starting Channel 66 production migration...\n";
    echo "Thread ID: " . ($threadId ?: 'all') . "\n";
    echo "Batch size: $batchSize\n";
    echo "Memory limit: $memoryLimit\n";
    echo "Dry run: " . ($dryRun ? 'YES' : 'NO') . "\n";
    echo "Monitoring: " . ($enableMonitoring ? 'YES' : 'NO') . "\n\n";
    
    $result = $ingester->runProductionMigration($threadId, $dryRun);
    
    // Report results
    echo "\n=== Production Migration Results ===\n";
    echo "Files discovered: {$result['files_discovered']}\n";
    echo "Batches processed: {$result['batches_processed']}\n";
    echo "Files processed: {$result['files_processed']}\n";
    echo "Files ingested: {$result['files_ingested']}\n";
    echo "Files rejected: {$result['files_rejected']}\n";
    echo "Files conflict_flagged: {$result['files_conflict_flagged']}\n";
    echo "Batches failed: {$result['batches_failed']}\n";
    echo "Peak memory usage: {$result['peak_memory_mb']}MB\n";
    echo "Average throughput: {$result['avg_files_per_second']}/sec\n";
    echo "Total runtime: {$result['total_runtime_seconds']}s\n";
    
    if ($result['errors']) {
        echo "\nErrors encountered:\n";
        foreach ($result['errors'] as $error) {
            echo "  - $error\n";
        }
        exit(1);
    }
    
    echo "\nProduction migration completed successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

/**
 * Validate production configuration
 */
function validateProductionConfig($configFile, $threadId, $batchSize, $memoryLimit) {
    $config = array();
    
    // Load config file if provided
    if ($configFile && is_file($configFile)) {
        $configContent = parse_ini_file($configFile);
        if ($configContent !== false) {
            $config = array_merge($config, $configContent);
        }
    }
    
    // Override with command line options
    if ($threadId !== null) $config['thread_id'] = $threadId;
    $config['batch_size'] = $batchSize;
    $config['memory_limit'] = $memoryLimit;
    
    // Validate required directories
    $requiredDirs = array(
        ABSPATH . 'lupo-channels/66',
        ABSPATH . 'lupo-database/lupopedia/toon'
    );
    
    foreach ($requiredDirs as $dir) {
        if (!is_dir($dir)) {
            throw new Exception("Required directory not found: $dir");
        }
    }
    
    return $config;
}
