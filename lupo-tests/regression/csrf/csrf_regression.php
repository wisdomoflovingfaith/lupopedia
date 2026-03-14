<?php
/**
 * Regression: CSRF — security.php, token and validation functions exist.
 * Run from repo root: php tests/regression/csrf/csrf_regression.php
 * Full token validation is in tests/unit/admin_csrf.php. PHP 5.3-compatible.
 */
$repo_root = dirname(dirname(dirname(__DIR__)));
$fail = 0;

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

$security = $repo_root . '/lupo-includes/functions/security.php';
if (!is_file($security)) {
    echo "FAIL missing: $security\n";
    exit(1);
}
$out = array();
exec('php -l ' . escapeshellarg($security) . ' 2>&1', $out, $ret);
if ($ret !== 0) {
    echo "FAIL syntax security.php\n";
    exit(1);
}
require_once $security;

if (!function_exists('lupo_get_csrf_token')) {
    echo "FAIL lupo_get_csrf_token not defined\n";
    $fail++;
}
if (!function_exists('lupo_require_valid_csrf_token')) {
    echo "FAIL lupo_require_valid_csrf_token not defined\n";
    $fail++;
}

// Stub used by unit tests
$stub = $repo_root . '/tests/unit/admin_csrf_stub.php';
if (!is_file($stub)) {
    echo "FAIL missing admin_csrf_stub.php\n";
    $fail++;
}

if ($fail > 0) {
    echo "REGRESSION CSRF: $fail fail\n";
    exit(1);
}
echo "PASS CSRF regression (security helpers and stub exist)\n";
exit(0);
