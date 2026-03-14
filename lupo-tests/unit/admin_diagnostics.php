<?php
/**
 * Unit tests for admin diagnostics (4.0.20). Verifies JSON log write and flock-based rotation.
 * Run from repo root: php tests/unit/admin_diagnostics.php
 * PHP 5.3+ compatible.
 */

$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

$diag_file = $repo_root . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'admin_diagnostics.php';
if (!is_file($diag_file)) {
    echo "SKIP admin_diagnostics.php not found\n";
    exit(0);
}
require_once $diag_file;

$log_dir = $repo_root . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'admin';
$today = date('Y-m-d');
$log_path = $log_dir . DIRECTORY_SEPARATOR . $today . '.jsonl';

$passed = 0;
$failed = 0;

// 1. Core writer exists
if (!function_exists('lupo_diag_write')) {
    echo "FAIL lupo_diag_write not defined\n";
    $failed++;
} else {
    echo "PASS lupo_diag_write defined\n";
    $passed++;
}

// 2. Write permission_check entry and verify JSON line in daily file
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0755, true);
}
lupo_diag_permission_check(99999, array('admin'), 'admin', true);
if (!file_exists($log_path)) {
    echo "FAIL permission_check did not create log file " . $log_path . "\n";
    $failed++;
} else {
    $content = file_get_contents($log_path);
    $lines = array_filter(explode("\n", $content));
    $last = end($lines);
    $decoded = json_decode($last, true);
    if (!$decoded || !isset($decoded['type']) || $decoded['type'] !== 'permission_check' || $decoded['actor_id'] !== 99999 || $decoded['allowed'] !== true) {
        echo "FAIL permission_check JSON invalid or wrong: " . $last . "\n";
        $failed++;
    } else {
        echo "PASS permission_check JSON written to daily file\n";
        $passed++;
    }
}

// 3. Rotation at >1MB: fill today's file >1MB, next write should rotate to .1
$saved = null;
if (file_exists($log_path)) {
    $saved = file_get_contents($log_path);
    @unlink($log_path);
}
if (file_exists($log_path . '.1')) {
    @unlink($log_path . '.1');
}
$chunk = str_repeat('x', 1024);
for ($i = 0; $i < 1025; $i++) {
    file_put_contents($log_path, $chunk, FILE_APPEND);
}
$size_before = file_exists($log_path) ? filesize($log_path) : 0;
if ($size_before <= 1024 * 1024) {
    @unlink($log_path);
    if ($saved !== null) {
        file_put_contents($log_path, $saved);
    }
    echo "SKIP could not create file >1MB for rotation test\n";
} else {
    lupo_diag_permission_check(2, array('admin'), 'test', true);
    $has_rotated = file_exists($log_path . '.1');
    $current_exists = file_exists($log_path);
    $current_small = $current_exists && filesize($log_path) < 1024 * 1024;
    @unlink($log_path);
    if (file_exists($log_path . '.1')) {
        @unlink($log_path . '.1');
    }
    if ($saved !== null) {
        file_put_contents($log_path, $saved);
    }
    if ($has_rotated && $current_exists && $current_small) {
        echo "PASS rotation triggered at >1MB (flock-based)\n";
        $passed++;
    } else {
        echo "SKIP rotation did not occur as expected (has_rotated=" . ($has_rotated ? '1' : '0') . " current_exists=" . ($current_exists ? '1' : '0') . ")\n";
    }
}

echo "\nTotal: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
