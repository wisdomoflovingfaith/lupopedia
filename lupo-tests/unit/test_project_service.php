<?php
/**
 * Unit tests for ProjectService (4.0.76).
 * Run: php lupo-tests/unit/test_project_service.php
 * Requires DB and seed (lupo_projects) for full pass; otherwise exercises class/method presence.
 */

$repoRoot = dirname(dirname(__DIR__));
$config = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    echo "SKIP: lupopedia-config.php not found; run from repo root or set path.\n";
    exit(0);
}

require_once $config;

$passed = 0;
$failed = 0;

function assert_true($cond, $msg) {
    global $passed, $failed;
    if ($cond) {
        $passed++;
        echo "PASS: $msg\n";
    } else {
        $failed++;
        echo "FAIL: $msg\n";
    }
}

function assert_false($cond, $msg) {
    assert_true(!$cond, $msg);
}

// Class and service
assert_true(class_exists('App\Services\ProjectService'), 'ProjectService class exists');
$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    echo "SKIP: No database connection; remaining tests skipped.\n";
    echo "Summary: $passed passed, $failed failed\n";
    exit($failed > 0 ? 1 : 0);
}

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : new \App\Services\ProjectService($db);
assert_true($svc instanceof \App\Services\ProjectService, 'ProjectService instance');

// Method existence
assert_true(method_exists($svc, 'getProjectById'), 'getProjectById exists');
assert_true(method_exists($svc, 'getProjectByKey'), 'getProjectByKey exists');
assert_true(method_exists($svc, 'getProjectBySlug'), 'getProjectBySlug exists');
assert_true(method_exists($svc, 'createProject'), 'createProject exists');
assert_true(method_exists($svc, 'updateProject'), 'updateProject exists');
assert_true(method_exists($svc, 'archiveProject'), 'archiveProject exists');
assert_true(method_exists($svc, 'freezeProject'), 'freezeProject exists');
assert_true(method_exists($svc, 'listProjects'), 'listProjects exists');

// Read-only tests if table exists (seed run)
$p1 = $svc->getProjectById(1);
if ($p1 !== null) {
    assert_true(is_array($p1), 'getProjectById(1) returns array');
    assert_true(isset($p1['project_id']) && (int) $p1['project_id'] === 1, 'project_id 1');
    assert_true(isset($p1['project_key']) && $p1['project_key'] === 'lupopedia-core', 'project_key lupopedia-core');
    $byKey = $svc->getProjectByKey('lupopedia-core', 1);
    assert_true($byKey !== null && (int) $byKey['project_id'] === 1, 'getProjectByKey lupopedia-core');
    $list = $svc->listProjects(1);
    assert_true(is_array($list) && count($list) >= 1, 'listProjects returns at least one');
} else {
    echo "SKIP: getProjectById(1) returned null (seed_projects may not be run).\n";
}

// Uniqueness: get by non-existent id returns null
$none = $svc->getProjectById(999999);
assert_true($none === null, 'getProjectById(999999) returns null');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
