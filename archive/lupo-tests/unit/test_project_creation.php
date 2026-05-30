<?php
/**
 * Unit test: project creation (4.0.76).
 * Validates createProject, getProjectById/ByKey/BySlug, updateProject, archiveProject.
 * Run: php lupo-tests/unit/test_project_creation.php
 * Requires DB and lupo_projects table; skips if unavailable.
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
$testId = 999998;
$key = 'test-project-creation-4-0-76';
$slug = 'test-project-creation-4-0-76';
$node = 1;

// Create
$create = $svc->createProject(array(
    'project_id' => $testId,
    'project_key' => $key,
    'project_slug' => $slug,
    'project_name' => 'Test Project Creation',
    'federation_node_id' => $node,
    'orchestrator_id' => 1,
));
assert_true($create === true, 'createProject succeeds');

$p = $svc->getProjectById($testId);
assert_true($p !== null && (int) $p['project_id'] === $testId, 'getProjectById returns created project');
assert_true(isset($p['project_key']) && $p['project_key'] === $key, 'project_key correct');
assert_true(isset($p['created_ymdhis']) && (int) $p['created_ymdhis'] > 0, 'created_ymdhis set');

$byKey = $svc->getProjectByKey($key, $node);
assert_true($byKey !== null && (int) $byKey['project_id'] === $testId, 'getProjectByKey returns created project');

$bySlug = $svc->getProjectBySlug($slug, $node);
assert_true($bySlug !== null && (int) $bySlug['project_id'] === $testId, 'getProjectBySlug returns created project');

// Update
$ok = $svc->updateProject($testId, array('description' => 'Updated description'), 102);
assert_true($ok === true, 'updateProject succeeds');
$p2 = $svc->getProjectById($testId);
assert_true($p2 !== null && isset($p2['description']) && $p2['description'] === 'Updated description', 'description updated');
assert_true(isset($p2['updated_ymdhis']) && (int) $p2['updated_ymdhis'] >= (int) $p['created_ymdhis'], 'updated_ymdhis changed');

// Archive
$ok = $svc->archiveProject($testId, 102);
assert_true($ok === true, 'archiveProject succeeds');
$p3 = $svc->getProjectById($testId);
assert_true($p3 !== null && isset($p3['status']) && $p3['status'] === 'archived', 'status is archived');
assert_true($p3 !== null && isset($p3['is_archived']) && (int) $p3['is_archived'] === 1, 'is_archived set');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
