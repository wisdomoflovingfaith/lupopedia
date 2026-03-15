<?php
/**
 * Unit test: project lifecycle — archive and freeze (4.0.76).
 * Validates archiveProject, freezeProject, status transitions, listProjects filtering.
 * Run: php lupo-tests/unit/test_project_lifecycle.php
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

$idArch = 999997;
$idFrz  = 999996;
$keyArch = 'test-lifecycle-arch-4-0-76';
$keyFrz  = 'test-lifecycle-frz-4-0-76';
$node = 1;

$svc->createProject(array('project_id' => $idArch, 'project_key' => $keyArch, 'project_slug' => $keyArch, 'project_name' => 'Lifecycle Archive', 'federation_node_id' => $node, 'orchestrator_id' => 1));
$svc->createProject(array('project_id' => $idFrz, 'project_key' => $keyFrz, 'project_slug' => $keyFrz, 'project_name' => 'Lifecycle Freeze', 'federation_node_id' => $node, 'orchestrator_id' => 1));

$ok = $svc->archiveProject($idArch, 102);
assert_true($ok === true, 'archiveProject succeeds');
$p = $svc->getProjectById($idArch);
assert_true($p !== null && isset($p['status']) && $p['status'] === 'archived', 'archived project status');
assert_true($p !== null && (int) $p['is_archived'] === 1, 'is_archived set');

$ok = $svc->freezeProject($idFrz, 102);
assert_true($ok === true, 'freezeProject succeeds');
$p2 = $svc->getProjectById($idFrz);
assert_true($p2 !== null && isset($p2['status']) && $p2['status'] === 'frozen', 'frozen project status');
assert_true($p2 !== null && (int) $p2['is_frozen'] === 1, 'is_frozen set');

$listActive = $svc->listProjects($node, 'active');
$listArchived = $svc->listProjects($node, 'archived');
$listFrozen = $svc->listProjects($node, 'frozen');

$hasArchInArchived = false;
$hasFrzInFrozen = false;
foreach ($listArchived as $r) {
    if ((int) $r['project_id'] === $idArch) $hasArchInArchived = true;
}
foreach ($listFrozen as $r) {
    if ((int) $r['project_id'] === $idFrz) $hasFrzInFrozen = true;
}
assert_true($hasArchInArchived, 'listProjects(status=archived) includes archived project');
assert_true($hasFrzInFrozen, 'listProjects(status=frozen) includes frozen project');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
