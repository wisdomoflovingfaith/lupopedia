<?php
/**
 * Unit test: project allocation / registry (4.0.76).
 * Validates project registry file and allocation hint.
 * Run: php lupo-tests/unit/test_project_allocation.php
 */

$repoRoot = dirname(dirname(__DIR__));
$registryPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . 'registry.json';

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

assert_true(is_file($registryPath), 'projects/registry.json exists');
$raw = @file_get_contents($registryPath);
assert_true($raw !== false && $raw !== '', 'registry.json readable');

$data = json_decode($raw, true);
assert_true(is_array($data), 'registry.json valid JSON');
assert_true(isset($data['schema_version']), 'schema_version present');
assert_true(isset($data['projects']) && is_array($data['projects']), 'projects array present');
assert_true(isset($data['next_id_hint']) && is_numeric($data['next_id_hint']), 'next_id_hint present');

$hasOne = false;
foreach ($data['projects'] as $p) {
    if (isset($p['project_id']) && (int) $p['project_id'] === 1 && isset($p['project_key']) && $p['project_key'] === 'lupopedia-core') {
        $hasOne = true;
        break;
    }
}
assert_true($hasOne, 'default project_id 1 lupopedia-core in registry');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
