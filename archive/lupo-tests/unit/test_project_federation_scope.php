<?php
/**
 * Unit test: project federation scope (4.0.76).
 * Validates listProjects respects federation_node_id; rows include federation_node_id.
 * Run: php lupo-tests/unit/test_project_federation_scope.php
 * Requires DB; skips if unavailable.
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

$list1 = $svc->listProjects(1);
assert_true(is_array($list1), 'listProjects(1) returns array');
foreach ($list1 as $r) {
    assert_true(isset($r['federation_node_id']) && (int) $r['federation_node_id'] === 1, 'each row has federation_node_id 1');
    break;
}

$list99999 = $svc->listProjects(99999);
assert_true(is_array($list99999), 'listProjects(99999) returns array');
assert_true(count($list99999) === 0, 'listProjects(99999) empty when no projects on that node');

$listAll = $svc->listProjects(1, null);
assert_true(is_array($listAll), 'listProjects(1, null) returns array');
assert_true(count($listAll) >= 1, 'listProjects(1, null) has at least one project');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
