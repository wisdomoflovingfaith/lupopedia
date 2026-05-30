<?php
/**
 * 4.0.19 Unit test: Admin CSRF — token generation and validation.
 * Run from repo root: php tests/unit/admin_csrf.php
 * PHP 5.3-compatible. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
if (session_id() === '') {
    session_start();
}
require_once $repo_root . '/lupo-includes/functions/security.php';

$fail = 0;

// 1. lupo_get_csrf_token() returns non-empty string
$t1 = lupo_get_csrf_token();
if (!is_string($t1) || strlen($t1) === 0) {
    echo "FAIL lupo_get_csrf_token() did not return non-empty string\n";
    $fail++;
}

// 2. Second call returns same token
$t2 = lupo_get_csrf_token();
if ($t1 !== $t2) {
    echo "FAIL lupo_get_csrf_token() returned different value on second call\n";
    $fail++;
}

// 3. Valid token → action allowed (no exit)
$_SESSION['csrf_token'] = 'allowed_token';
$_POST['csrf_token'] = 'allowed_token';
lupo_require_valid_csrf_token();
// If we reach here, validation passed
$_POST = array();
$_SESSION['csrf_token'] = $t1;

// 4. Missing token → blocked (run in subprocess; expect "Invalid or missing CSRF token.")
$stub = $repo_root . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'unit' . DIRECTORY_SEPARATOR . 'admin_csrf_stub.php';
$php = defined('PHP_BINARY') ? PHP_BINARY : 'php';
$cmd = $php . ' ' . escapeshellarg($stub) . ' missing 2>&1';
$out = array();
@exec($cmd, $out);
$out = implode("\n", $out);
if (strpos($out, 'Invalid or missing CSRF token.') === false) {
    echo "FAIL missing token should block (expected message in output)\n";
    $fail++;
}

// 5. Invalid token → blocked
$out2 = array();
@exec($php . ' ' . escapeshellarg($stub) . ' invalid 2>&1', $out2);
$out2 = implode("\n", $out2);
if (strpos($out2, 'Invalid or missing CSRF token.') === false) {
    echo "FAIL invalid token should block (expected message in output)\n";
    $fail++;
}

if ($fail > 0) {
    echo "FAIL ($fail assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
