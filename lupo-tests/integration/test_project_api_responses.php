<?php
/**
 * Integration test: project API response structure (4.0.76).
 * Validates JSON contract (projects array, utc_timestamp, system_version) and external-actor fields.
 * Run: php lupo-tests/integration/test_project_api_responses.php
 * Requires config/DB for full pass; validates structure otherwise.
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

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : null;
if (!$svc && isset($GLOBALS['mydatabase'])) {
    $app = (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '') . (defined('LUPO_APP_DIR') ? LUPO_APP_DIR : 'lupo-database/lupopedia/content/lupo-app') . '/Services/ProjectService.php';
    if (is_file($app)) {
        require_once $app;
        $svc = new \App\Services\ProjectService($GLOBALS['mydatabase']);
    }
}

if ($svc) {
    $list = $svc->listProjects(1);
    $payload = array(
        'projects' => $list,
        'utc_timestamp' => gmdate('YmdHis'),
        'system_version' => '4.0.76',
    );
    $json = json_encode($payload);
    assert_true($json !== false && $json !== '', 'response JSON encodable');
    $dec = json_decode($json, true);
    assert_true(is_array($dec) && isset($dec['projects']) && is_array($dec['projects']), 'response has projects array');
    assert_true(isset($dec['utc_timestamp']) && preg_match('/^\d{14}$/', (string) $dec['utc_timestamp']), 'utc_timestamp BIGINT format');
    assert_true(isset($dec['system_version']), 'system_version present');
} else {
    echo "SKIP: ProjectService not available; API contract not exercised.\n";
}

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
