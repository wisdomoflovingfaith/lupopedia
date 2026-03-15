<?php
/**
 * Unit test: project uniqueness (4.0.76).
 * Validates getProjectByKey/getProjectBySlug return single match; duplicate key fails at DB level.
 * Run: php lupo-tests/unit/test_project_uniqueness.php
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

// Non-existent key returns null
$none = $svc->getProjectByKey('nonexistent-key-4-0-76', 1);
assert_true($none === null, 'getProjectByKey nonexistent returns null');

// By key: single result
$p = $svc->getProjectByKey('lupopedia-core', 1);
if ($p !== null) {
    assert_true(is_array($p) && isset($p['project_id']), 'getProjectByKey returns single project');
    assert_true((int) $p['project_id'] === 1, 'getProjectByKey lupopedia-core is project_id 1');
} else {
    echo "SKIP: lupopedia-core not seeded (getProjectByKey returned null).\n";
}

// By slug: single result
$p2 = $svc->getProjectBySlug('lupopedia-core', 1);
if ($p2 !== null) {
    assert_true(is_array($p2) && isset($p2['project_id']), 'getProjectBySlug returns single project');
    assert_true((int) $p2['project_id'] === 1, 'getProjectBySlug lupopedia-core is project_id 1');
} else {
    echo "SKIP: lupopedia-core not seeded (getProjectBySlug returned null).\n";
}

// Duplicate key same node: only when default project exists, create with same key should fail
if ($p !== null) {
    $didThrow = false;
    try {
        $svc->createProject(array(
            'project_id' => 999995,
            'project_key' => 'lupopedia-core',
            'project_slug' => 'dup-slug-uniqueness',
            'project_name' => 'Dup',
            'federation_node_id' => 1,
            'orchestrator_id' => 1,
        ));
    } catch (Exception $e) {
        $didThrow = true;
    }
    assert_true($didThrow === true, 'duplicate project_key same node rejected (DB exception)');
}

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
