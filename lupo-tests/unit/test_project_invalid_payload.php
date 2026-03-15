<?php
/**
 * Unit test: invalid payload and missing context (4.0.76).
 * createProject with missing required fields returns false; invalid id/slug/key return null.
 * Run: php lupo-tests/unit/test_project_invalid_payload.php
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
function assert_true($cond, $msg) {
    global $passed, $failed;
    if ($cond) { $passed++; echo "PASS: $msg\n"; } else { $failed++; echo "FAIL: $msg\n"; }
}

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    echo "SKIP: No database connection.\n";
    echo "Summary: $passed passed, $failed failed\n";
    exit(0);
}

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : new \App\Services\ProjectService($db);

// createProject missing required fields → false
$ok = $svc->createProject(array());
assert_true($ok === false, 'createProject empty array returns false');

$ok = $svc->createProject(array('project_id' => 999994, 'project_key' => 'x'));
assert_true($ok === false, 'createProject missing project_slug/name/federation_node_id/orchestrator_id returns false');

// getProjectById invalid id → null
assert_true($svc->getProjectById(0) === null, 'getProjectById(0) returns null');
assert_true($svc->getProjectById(-1) === null, 'getProjectById(-1) returns null');

// getProjectByKey empty string
assert_true($svc->getProjectByKey('', 1) === null, 'getProjectByKey empty string returns null');

// updateProject on non-existent id: runs but affects 0 rows (service still returns true; no exception)
$ok = $svc->updateProject(999999, array('description' => 'no such project'), null);
assert_true($ok === true, 'updateProject on non-existent id does not throw');
assert_true($svc->getProjectById(999999) === null, 'getProjectById(999999) still null after update');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
