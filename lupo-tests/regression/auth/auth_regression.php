<?php
/**
 * Regression: Auth — require_login, current_user, is_admin exist; auth-helpers load.
 * Run from repo root: php tests/regression/auth/auth_regression.php
 * Does not test live login; requires config for full load. PHP 5.3-compatible.
 */
$repo_root = dirname(dirname(dirname(__DIR__)));
$fail = 0;

$auth_helpers = $repo_root . '/lupo-includes/functions/auth-helpers.php';
if (!is_file($auth_helpers)) {
    echo "FAIL missing: $auth_helpers\n";
    exit(1);
}
$out = array();
exec('php -l ' . escapeshellarg($auth_helpers) . ' 2>&1', $out, $ret);
if ($ret !== 0) {
    echo "FAIL syntax auth-helpers.php\n";
    exit(1);
}

// Load with minimal defines (auth-helpers requires LUPOPEDIA_CONFIG_LOADED)
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
$session_compat = $repo_root . '/lupo-includes/functions/session-compat-5.3.php';
if (is_file($session_compat)) {
    require_once $session_compat;
}
// auth-helpers may require redirect-helpers
$redirect = $repo_root . '/lupo-includes/functions/redirect-helpers.php';
if (is_file($redirect)) {
    require_once $redirect;
}
require_once $auth_helpers;

if (!function_exists('current_user')) {
    echo "FAIL current_user not defined\n";
    $fail++;
}
if (!function_exists('require_login')) {
    echo "FAIL require_login not defined\n";
    $fail++;
}
if (!function_exists('require_admin')) {
    echo "FAIL require_admin not defined\n";
    $fail++;
}
if (!function_exists('lupo_is_admin')) {
    echo "FAIL lupo_is_admin not defined\n";
    $fail++;
}

if ($fail > 0) {
    echo "REGRESSION AUTH: $fail fail\n";
    exit(1);
}
echo "PASS auth regression (helpers and functions exist)\n";
exit(0);
