<?php
/**
 * DOCTOR context handler — doctor-context and --repair via DOCTOR actor (actor_id 1009).
 * Included from lupo-bin/lupo.php when "doctor-context" command is run and this file exists.
 * Uses $db, $table_prefix, $state_file, ABSPATH, $argv from including scope.
 *
 * @package Lupopedia\Doctor
 * @version 4.0.62
 */

$argv_doc = isset($argv) ? $argv : (isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array());
$do_repair = in_array('--repair', $argv_doc);

require_once ABSPATH . 'lupo-includes/classes/ContextKernel.php';
require_once ABSPATH . 'lupo-includes/classes/DoctorService.php';

$kernel = ContextKernel::getInstance();
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
$kernel->bootstrap($db, $table_prefix, $state_file, ABSPATH, $authService);
$doctor = new DoctorService($kernel, $db, $table_prefix, $state_file, ABSPATH);

$ctx = $kernel->getContext();
$issues = $doctor->validateContext();
$src = isset($ctx['context_source']) ? $ctx['context_source'] : (isset($ctx['source']) ? $ctx['source'] : 'default');

$repaired = false;
if ($do_repair && (strpos($src, 'conflict') !== false || !empty($issues))) {
    if ($doctor->repairSessionFile()) {
        $repaired = true;
        $ctx = $kernel->getContext();
        $issues = $doctor->validateContext();
    }
}

echo "\n";
echo "=== DOCTOR Context Validation (actor 1009) ===\n";
echo "Time: " . gmdate('Y-m-d H:i:s') . " UTC\n";
echo "\n";

echo "Resolved context:\n";
echo "  actor_name: " . (isset($ctx['actor_name']) ? $ctx['actor_name'] : 'system') . "\n";
echo "  actor_id: " . (isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0) . "\n";
echo "  session_id: " . (isset($ctx['session_id']) ? $ctx['session_id'] : '') . "\n";
echo "  context_source: " . $src . "\n";
echo "\n";

if (empty($issues)) {
    echo "[OK] No context issues detected.\n";
} else {
    echo "[WARN] Context issues:\n";
    foreach ($issues as $issue) {
        echo "  - " . $issue . "\n";
    }
}

if ($repaired) {
    echo "[OK] Session file repaired from kernel context.\n";
}
if ($do_repair && empty($issues) && !$repaired) {
    echo "[INFO] No repair needed — context already consistent.\n";
}

echo "\n";
