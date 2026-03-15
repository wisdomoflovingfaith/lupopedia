<?php
/**
 * Integration test: registry ↔ DB synchronization (4.0.76).
 * When DB has default project (project_id 1), registry.json should list it; next_id_hint >= 2.
 * Run: php lupo-tests/integration/test_project_registry_db_sync.php
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

$registryPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . 'registry.json';
assert_true(is_file($registryPath), 'registry.json exists');
$reg = json_decode(file_get_contents($registryPath), true);
assert_true(is_array($reg) && isset($reg['projects']) && isset($reg['next_id_hint']), 'registry has projects and next_id_hint');

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : null;
if (!$svc && isset($GLOBALS['mydatabase'])) {
    $app = (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '') . (defined('LUPO_APP_DIR') ? LUPO_APP_DIR : 'lupo-database/lupopedia/content/lupo-app') . '/Services/ProjectService.php';
    if (is_file($app)) {
        require_once $app;
        $svc = new \App\Services\ProjectService($GLOBALS['mydatabase']);
    }
}

if ($svc) {
    $dbProject1 = $svc->getProjectById(1);
    $regHas1 = false;
    foreach ($reg['projects'] as $p) {
        if (isset($p['project_id']) && (int) $p['project_id'] === 1) {
            $regHas1 = true;
            break;
        }
    }
    if ($dbProject1 !== null) {
        assert_true($regHas1 === true, 'when DB has project_id 1, registry lists project_id 1');
        assert_true((int) $reg['next_id_hint'] >= 2, 'next_id_hint >= 2 when default project exists');
    } else {
        echo "SKIP: DB has no project_id 1; registry-DB sync check skipped.\n";
    }
} else {
    echo "SKIP: ProjectService not available.\n";
}

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
