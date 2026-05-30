<?php
/**
 * Unit test for actor directory migration script (dry-run only; no filesystem changes).
 * Run from project root: php tests/unit/directory_migration_test.php
 * Verifies script loads registry, parses --dry-run, and produces expected log lines.
 */

$base = dirname(dirname(__DIR__));
$script = $base . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . '20260306_actor_directory_migration.php';

if (!is_file($script)) {
    echo "SKIP Migration script not found: " . $script . "\n";
    exit(0);
}

$registry_path = $base . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
if (!is_file($registry_path)) {
    echo "SKIP Registry not found\n";
    exit(0);
}

$ok = 0;
$fail = 0;

// Run script with --dry-run and capture output
$cmd = 'php ' . escapeshellarg($script) . ' --dry-run 2>&1';
$output = array();
@exec($cmd, $output, $code);
$out = implode("\n", $output);

if (strpos($out, 'DRY-RUN') !== false || strpos($out, 'Migration') !== false) {
    echo "PASS Script runs with --dry-run\n";
    $ok++;
} else {
    echo "FAIL Script output missing expected content\n";
    $fail++;
}

if (strpos($out, 'started') !== false || strpos($out, 'Summary') !== false || strpos($out, 'finished') !== false) {
    echo "PASS Log structure present\n";
    $ok++;
} else {
    echo "FAIL Log structure missing\n";
    $fail++;
}

// Verify registry has name-based dirs
$data = json_decode(file_get_contents($registry_path), true);
if (is_array($data) && isset($data['actors']['system']['dir']) && strpos($data['actors']['system']['dir'], 'system') !== false) {
    echo "PASS Registry has name-based dir for system\n";
    $ok++;
} else {
    echo "FAIL Registry dir for system not name-based\n";
    $fail++;
}

echo "\nTotal: $ok pass, $fail fail\n";
exit($fail > 0 ? 1 : 0);
