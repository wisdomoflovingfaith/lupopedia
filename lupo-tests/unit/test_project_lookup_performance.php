<?php
/**
 * Lightweight performance validation: project lookup (4.0.76).
 * Measures getProjectById(1) and getProjectByKey('lupopedia-core') time; advisory only.
 * Target: resolution <100ms per Windsurf review; no formal benchmark framework.
 * Run: php lupo-tests/unit/test_project_lookup_performance.php
 */

$repoRoot = dirname(dirname(__DIR__));
$config = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    echo "SKIP: lupopedia-config.php not found.\n";
    exit(0);
}
require_once $config;

$passed = 0;
$failed = 0;
$maxMs = 100;

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    echo "SKIP: No database connection.\n";
    exit(0);
}

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : new \App\Services\ProjectService($db);

$t0 = microtime(true);
for ($i = 0; $i < 10; $i++) {
    $svc->getProjectById(1);
}
$ms = (microtime(true) - $t0) * 1000;
$avg = $ms / 10;
if ($avg <= $maxMs) {
    $passed++;
    echo "PASS: getProjectById(1) avg " . round($avg, 2) . " ms (<= {$maxMs} ms)\n";
} else {
    $failed++;
    echo "FAIL: getProjectById(1) avg " . round($avg, 2) . " ms exceeds {$maxMs} ms (advisory)\n";
}

$t0 = microtime(true);
for ($i = 0; $i < 10; $i++) {
    $svc->getProjectByKey('lupopedia-core', 1);
}
$ms = (microtime(true) - $t0) * 1000;
$avg = $ms / 10;
if ($avg <= $maxMs) {
    $passed++;
    echo "PASS: getProjectByKey(lupopedia-core) avg " . round($avg, 2) . " ms (<= {$maxMs} ms)\n";
} else {
    $failed++;
    echo "FAIL: getProjectByKey avg " . round($avg, 2) . " ms exceeds {$maxMs} ms (advisory)\n";
}

echo "Summary: $passed passed, $failed failed (performance advisory only)\n";
exit($failed > 0 ? 1 : 0);
