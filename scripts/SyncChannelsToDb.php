<?php
// SyncChannelsToDb.php: Script entry point for channel synchronization
// Usage: php scripts/SyncChannelsToDb.php [--commit]

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/classes/SyncChannelsToDb.php';

// Entrypoint
$dryRun = true;
if (in_array('--commit', $argv)) {
    $dryRun = false;
}
$sync = new SyncChannelsToDb(__DIR__ . '/../database/lupopedia/json', $dryRun);
$sync->run();
