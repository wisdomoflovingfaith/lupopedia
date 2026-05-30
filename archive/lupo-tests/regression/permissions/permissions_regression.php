<?php
/**
 * Regression: Permissions — is_admin, role checks, admin access logic present.
 * Run from repo root: php tests/regression/permissions/permissions_regression.php
 * Does not test live permission denial; verifies auth/role helpers exist. PHP 5.3-compatible.
 */
$repo_root = dirname(dirname(dirname(__DIR__)));
$fail = 0;

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
$redirect = $repo_root . '/lupo-includes/functions/redirect-helpers.php';
if (is_file($redirect)) {
    require_once $redirect;
}
require_once $repo_root . '/lupo-includes/functions/auth-helpers.php';

if (!function_exists('lupo_is_admin')) {
    echo "FAIL lupo_is_admin not defined\n";
    $fail++;
}
if (!function_exists('lupo_has_admin_for_channel')) {
    echo "FAIL lupo_has_admin_for_channel not defined\n";
    $fail++;
}
if (!function_exists('require_admin')) {
    echo "FAIL require_admin not defined\n";
    $fail++;
}

// admin.php uses $isAdmin = !empty($user['is_admin']); AuthService/getCurrentUser provides is_admin
$auth_service = $repo_root . '/app/auth/AuthService.php';
if (!is_file($auth_service)) {
    echo "FAIL missing AuthService.php\n";
    $fail++;
}

if ($fail > 0) {
    echo "REGRESSION PERMISSIONS: $fail fail\n";
    exit(1);
}
echo "PASS permissions regression (role/admin helpers exist)\n";
exit(0);
