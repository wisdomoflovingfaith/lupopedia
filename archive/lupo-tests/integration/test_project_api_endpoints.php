<?php
/**
 * Integration test: project API endpoint files exist and are loadable (4.0.76).
 * Does not require HTTP; validates file presence and syntax.
 * Run: php lupo-tests/integration/test_project_api_endpoints.php
 */

$repoRoot = dirname(dirname(__DIR__));
$base = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-api' . DIRECTORY_SEPARATOR . 'v1' . DIRECTORY_SEPARATOR . 'projects';

$endpoints = array('list.php', 'get.php', 'create.php', 'update.php', 'archive.php', 'freeze.php');
$passed = 0;
$failed = 0;

foreach ($endpoints as $file) {
    $path = $base . DIRECTORY_SEPARATOR . $file;
    $exists = is_file($path);
    if ($exists) {
        $passed++;
        echo "PASS: $file exists\n";
    } else {
        $failed++;
        echo "FAIL: $file missing\n";
    }
}

echo "Summary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
