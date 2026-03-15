<?php
/**
 * Unit test: project registry structure and alignment (4.0.76).
 * Validates registry.json schema_version, projects array, next_id_hint, and default project.
 * Run: php lupo-tests/unit/test_project_registry.php
 */

$repoRoot = dirname(dirname(__DIR__));
$registryPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'projects' . DIRECTORY_SEPARATOR . 'registry.json';

$passed = 0;
$failed = 0;
function assert_true($cond, $msg) {
    global $passed, $failed;
    if ($cond) { $passed++; echo "PASS: $msg\n"; } else { $failed++; echo "FAIL: $msg\n"; }
}

assert_true(is_file($registryPath), 'registry.json exists');
$raw = @file_get_contents($registryPath);
assert_true($raw !== false && $raw !== '', 'registry.json readable');

$data = json_decode($raw, true);
assert_true(is_array($data), 'registry valid JSON');
assert_true(isset($data['schema_version']), 'schema_version present');
assert_true(isset($data['projects']) && is_array($data['projects']), 'projects array present');
assert_true(isset($data['next_id_hint']) && is_numeric($data['next_id_hint']), 'next_id_hint present');

$default = null;
foreach ($data['projects'] as $p) {
    if (isset($p['project_id']) && (int) $p['project_id'] === 1) {
        $default = $p;
        break;
    }
}
assert_true($default !== null, 'default project_id 1 in registry');
assert_true($default !== null && isset($default['project_key']) && $default['project_key'] === 'lupopedia-core', 'default project_key lupopedia-core');
assert_true($default !== null && isset($default['status']) && $default['status'] === 'active', 'default status active');

if (isset($data['federation_node_id'])) {
    assert_true(is_numeric($data['federation_node_id']), 'federation_node_id numeric');
}

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
