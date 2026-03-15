<?php
/**
 * Unit test: project schema and TOON (4.0.76).
 * Validates lupo_projects in install SQL and TOON.
 * Run: php lupo-tests/unit/test_project_schema.php
 */

$repoRoot = dirname(dirname(__DIR__));
$installPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql';
$toonPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'toon' . DIRECTORY_SEPARATOR . 'lupo_projects.toon.json';

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

assert_true(is_file($installPath), 'install_new_lupopedia.sql exists');
$install = file_get_contents($installPath);
assert_true(strpos($install, 'CREATE TABLE lupo_projects') !== false, 'lupo_projects in install');
assert_true(strpos($install, 'project_id bigint') !== false, 'project_id column');
assert_true(strpos($install, 'default_channel_id') !== false, 'default_channel_id column');
assert_true(strpos($install, 'uk_project_slug_node') !== false, 'uk_project_slug_node unique key');

assert_true(is_file($toonPath), 'lupo_projects.toon.json exists');
$toon = json_decode(file_get_contents($toonPath), true);
assert_true(is_array($toon) && isset($toon['table_name']) && $toon['table_name'] === 'lupo_projects', 'TOON table_name');
assert_true(isset($toon['fields']) && is_array($toon['fields']), 'TOON fields');
assert_true(isset($toon['primary_key']['column_name']) && $toon['primary_key']['column_name'] === 'project_id', 'TOON primary_key project_id');

$hasDefaultChannel = false;
foreach ($toon['fields'] as $f) {
    if (strpos($f, 'default_channel_id') !== false) {
        $hasDefaultChannel = true;
        break;
    }
}
assert_true($hasDefaultChannel, 'TOON has default_channel_id');

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
