#!/usr/bin/env php
<?php
/**
 * lupo-bin/export.php
 * DB memory export CLI wrapper for MemoryExportService.
 *
 * Usage:
 *   php lupo-bin/export.php --node-id 12345
 *   php lupo-bin/export.php --full
 *   php lupo-bin/export.php --since 20260401000000
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This command must run in CLI mode.\n");
    exit(1);
}

$root = dirname(__DIR__);
$configCandidates = array(
    $root . '/lupopedia-config.php',
    dirname($root) . '/lupopedia-config.php',
);

$configLoaded = false;
foreach ($configCandidates as $cfg) {
    if (file_exists($cfg)) {
        require_once $cfg;
        $configLoaded = true;
        break;
    }
}

if (!$configLoaded) {
    fwrite(STDERR, "[ERROR] Could not load lupopedia-config.php\n");
    exit(1);
}

require_once $root . '/lupo-includes/classes/MemoryExportService.php';

$opts = getopt('', array('node-id:', 'full', 'since:'));
$service = new MemoryExportService();

if (isset($opts['node-id'])) {
    $nodeId = (int) $opts['node-id'];
    if ($nodeId <= 0) {
        fwrite(STDERR, "[ERROR] --node-id must be a positive integer\n");
        exit(1);
    }
    $service->exportNode($nodeId);
    fwrite(STDOUT, "[OK] Exported node {$nodeId}\n");
    exit(0);
}

if (isset($opts['full'])) {
    $count = $service->fullExport();
    fwrite(STDOUT, "[OK] Full export completed ({$count} nodes)\n");
    exit(0);
}

if (isset($opts['since'])) {
    $since = preg_replace('/[^0-9]/', '', (string) $opts['since']);
    if ($since === '') {
        fwrite(STDERR, "[ERROR] --since requires a numeric UTC timestamp\n");
        exit(1);
    }
    $count = $service->exportSince((int) $since);
    fwrite(STDOUT, "[OK] Export since {$since} completed ({$count} nodes)\n");
    exit(0);
}

fwrite(STDOUT, "Usage:\n");
fwrite(STDOUT, "  php lupo-bin/export.php --node-id 12345\n");
fwrite(STDOUT, "  php lupo-bin/export.php --full\n");
fwrite(STDOUT, "  php lupo-bin/export.php --since 20260401000000\n");
exit(1);

