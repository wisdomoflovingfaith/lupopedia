<?php
/**
 * DOCTOR main handler — health check via DOCTOR actor (actor_id 1009).
 * Included from lupo-bin/lupo.php when "doctor" command is run and this file exists.
 * Uses $db, $table_prefix, $state_file, ABSPATH from including scope.
 *
 * @package Lupopedia\Doctor
 * @version 4.0.62
 */

require_once ABSPATH . 'lupo-includes/classes/ContextKernel.php';
require_once ABSPATH . 'lupo-includes/classes/DoctorService.php';
$kernel = ContextKernel::getInstance();
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
$kernel->bootstrap($db, $table_prefix, $state_file, ABSPATH, $authService);
$doctor = new DoctorService($kernel, $db, $table_prefix, $state_file, ABSPATH);
$results = $doctor->runHealthCheck();

echo "\n";
echo "=== DOCTOR Health Check (actor 1009) ===\n";
echo "Time: " . $results['timestamp'] . " UTC\n";
echo "\n";

echo "Database: ";
echo $results['database']['ok'] ? "[OK]" : "[FAIL]";
if (isset($results['database']['details']) && $results['database']['details'] !== '') {
    echo " " . $results['database']['details'];
}
echo "\n";

echo "Session: ";
echo $results['session']['ok'] ? "[OK]" : "[FAIL]";
if (isset($results['session']['details']) && $results['session']['details'] !== '') {
    echo " " . $results['session']['details'];
}
echo "\n";

echo "Registry: ";
echo $results['registry']['ok'] ? "[OK]" : "[FAIL]";
if (isset($results['registry']['details']) && $results['registry']['details'] !== '') {
    echo " " . $results['registry']['details'];
}
echo "\n";

echo "Context drift: ";
echo $results['context']['ok'] ? "[OK] None" : "[WARN] Detected";
echo "\n";
if (!empty($results['context']['issues'])) {
    foreach ($results['context']['issues'] as $issue) {
        echo "  - " . $issue . "\n";
    }
}

echo "Files: ";
echo $results['files']['ok'] ? "[OK]" : "[FAIL]";
if (isset($results['files']['details']) && $results['files']['details'] !== '') {
    echo " " . $results['files']['details'];
}
echo "\n";

echo "\n";
echo "Logs: " . $doctor->getLogsDir() . "\n";
echo "Reports: " . $doctor->getReportsDir() . "\n";
echo "\n";
