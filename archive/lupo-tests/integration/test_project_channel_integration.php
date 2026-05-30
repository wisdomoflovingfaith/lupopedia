<?php
/**
 * Integration test: project-channel schema (4.0.76).
 * Validates lupo_channels has project_id column and index in install SQL; backward compatibility.
 * Run: php lupo-tests/integration/test_project_channel_integration.php
 */

$repoRoot = dirname(dirname(__DIR__));
$installPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql';

$passed = 0;
$failed = 0;
function assert_true($cond, $msg) {
    global $passed, $failed;
    if ($cond) { $passed++; echo "PASS: $msg\n"; } else { $failed++; echo "FAIL: $msg\n"; }
}

assert_true(is_file($installPath), 'install SQL exists');
$sql = file_get_contents($installPath);

assert_true(strpos($sql, 'CREATE TABLE lupo_channels') !== false, 'lupo_channels table in install');
assert_true(preg_match('/project_id\s+bigint\s+DEFAULT\s+NULL/i', $sql) === 1, 'lupo_channels.project_id BIGINT DEFAULT NULL');
assert_true(strpos($sql, 'lupo_channels_idx_project_id') !== false, 'lupo_channels_idx_project_id index');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
