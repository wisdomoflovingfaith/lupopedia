#!/usr/bin/env php
<?php
/**
 * Staging GC CLI — lupo-bin/cli/staging_gc.php
 *
 * Invoked by: php lupo-bin/lupo.php staging-gc [--days=90] [--batch=1000] [--dry-run]
 *
 * Purges soft-deleted staging-tier rows (embedded year 2000–2099) from
 * lupo_memory_nodes and lupo_memory_edges that have exceeded the retention window.
 *
 * See: lupo-docs/doctrine/RETENTION_POLICY.md, CHRONOLOGICAL_TRUST_LADDER.md §9.3.
 */

if (!defined('LUPOPEDIA_PATH')) {
    // When invoked via lupo.php, LUPOPEDIA_PATH is already defined.
    // When run standalone: php lupo-bin/cli/staging_gc.php
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}

// -----------------------------------------------------------------------
// Parse CLI arguments
// -----------------------------------------------------------------------
$args     = isset($argv) ? array_slice($argv, 1) : array();
$days     = 90;
$batch    = 1000;
$dryRun   = false;
$actorId  = 0;

foreach ($args as $arg) {
    if (preg_match('/^--days=(\d+)$/', $arg, $m)) {
        $days = max(1, (int) $m[1]);
    } elseif (preg_match('/^--batch=(\d+)$/', $arg, $m)) {
        $batch = max(1, (int) $m[1]);
    } elseif ($arg === '--dry-run') {
        $dryRun = true;
    } elseif (preg_match('/^--actor=(\d+)$/', $arg, $m)) {
        $actorId = (int) $m[1];
    } elseif (in_array($arg, array('--help', '-h'), true)) {
        echo "Usage: php lupo-bin/lupo.php staging-gc [options]\n\n";
        echo "Options:\n";
        echo "  --days=N     Retention window in days (default: 90)\n";
        echo "  --batch=N    Max rows per table per run (default: 1000)\n";
        echo "  --dry-run    Count eligible rows without deleting (read-only)\n";
        echo "  --actor=N    Actor ID for log attribution (default: 0 = system)\n";
        exit(0);
    }
}

// -----------------------------------------------------------------------
// Bootstrap: config + dependencies
// -----------------------------------------------------------------------
$configCandidates = array(
    LUPOPEDIA_PATH . '/lupopedia-config.php',
    LUPOPEDIA_PATH . '/config.php',
);
$configLoaded = false;
foreach ($configCandidates as $cp) {
    if (file_exists($cp)) {
        require_once $cp;
        $configLoaded = true;
        break;
    }
}
if (!$configLoaded) {
    fwrite(STDERR, "[ERROR] staging-gc: No config file found. Expected lupopedia-config.php.\n");
    exit(1);
}

if (!class_exists('DatabaseFactory', false)) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
}
if (!class_exists('IdGenerator', false)) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';
}
if (!class_exists('timestamp_ymdhis', false)) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
}

$gcServicePath = LUPOPEDIA_PATH . '/app/Services/Kairos/StagingGcService.php';
if (!file_exists($gcServicePath)) {
    fwrite(STDERR, "[ERROR] staging-gc: StagingGcService not found at $gcServicePath\n");
    exit(1);
}
require_once $gcServicePath;

// -----------------------------------------------------------------------
// Connect and run
// -----------------------------------------------------------------------
try {
    $db = DatabaseFactory::getConnection();
} catch (Exception $e) {
    fwrite(STDERR, "[ERROR] staging-gc: DB connection failed: " . $e->getMessage() . "\n");
    exit(1);
}

$tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$service     = new StagingGcService($db, $tablePrefix, $actorId);

$nowLabel = date('Y-m-d H:i:s') . ' UTC';

if ($dryRun) {
    echo "[staging-gc] DRY RUN — $nowLabel\n";
    echo "[staging-gc] Retention: $days days, batch: $batch\n";
    try {
        $result = $service->dryRun($days);
    } catch (Exception $e) {
        fwrite(STDERR, "[ERROR] staging-gc dry-run failed: " . $e->getMessage() . "\n");
        exit(1);
    }
    echo "[staging-gc] Eligible for purge:\n";
    echo "  memory_nodes : " . (int) $result['memory_nodes_eligible'] . " rows\n";
    echo "  memory_edges : " . (int) $result['memory_edges_eligible'] . " rows\n";
    echo "  cutoff       : " . $result['cutoff_ymdhis'] . "\n";
    exit(0);
}

echo "[staging-gc] START — $nowLabel\n";
echo "[staging-gc] Retention: $days days, batch: $batch\n";

try {
    $result = $service->purge($days, $batch);
} catch (Exception $e) {
    fwrite(STDERR, "[ERROR] staging-gc: Purge failed: " . $e->getMessage() . "\n");
    exit(1);
}

echo "[staging-gc] DONE:\n";
echo "  memory_nodes purged : " . (int) $result['memory_nodes_purged'] . "\n";
echo "  memory_edges purged : " . (int) $result['memory_edges_purged'] . "\n";

if (!empty($result['errors'])) {
    fwrite(STDERR, "[staging-gc] ERRORS:\n");
    foreach ($result['errors'] as $err) {
        fwrite(STDERR, "  - $err\n");
    }
    exit(1);
}

exit(0);
