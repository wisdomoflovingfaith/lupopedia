<?php
/**
 * Lupopedia CLI Hydrator & Sync Wrapper
 * Actor: HEPHAESTUS (102)
 * Purpose: Provide the bootstrap context for DB Sync and Context Elevation.
 */

// 1. Load the Kernel Configuration
$configPath = __DIR__ . '/../lupopedia-config.php';
if (!file_exists($configPath)) {
    die("ERROR: lupopedia-config.php not found. The Forge cannot start.\n");
}
require_once $configPath;

// 2. Verify Bootstrap Integrity
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("ERROR: Bootstrap failed. LUPOPEDIA_CONFIG_LOADED not defined.\n");
}

echo "--- Lupopedia Forge: Hydration Started ---\n";
echo "Actor: HEPHAESTUS (102)\n";
echo "Node ID: " . (defined('FEDERATION_NODE_ID') ? FEDERATION_NODE_ID : 'undefined') . "\n";

// 3. Pre-flight Constitutional Audit
$enforcePath = __DIR__ . '/../lupo-rules/enforce_doctrine.py';
echo "Running enforce_doctrine.py...\n";
$audit = shell_exec("python3 " . escapeshellarg($enforcePath));
if (strpos($audit, 'DOCTRINE VIOLATIONS DETECTED') !== false) {
    echo $audit;
    die("CONSTITUTIONAL VIOLATION DETECTED. Execution aborted.\n");
}
echo $audit;
echo "Audit Passed.\n";

// 4. Execute the Sync Service
require_once __DIR__ . '/../lupo-scripts/SyncChannelsToDb.php';
if (!class_exists('SyncChannelsToDb')) {
    die("ERROR: SyncChannelsToDb class not found.\n");
}
$sync = new SyncChannelsToDb();
$sync->execute(['commit' => true]);

echo "--- Elevation Complete ---\n";
