<?php
/**
 * Stub for admin_csrf.php unit test: runs CSRF validation in isolation for valid/missing/invalid.
 * Usage: php admin_csrf_stub.php [valid|missing|invalid]
 * Exit 0; output "ALLOWED" on valid path, "Invalid or missing CSRF token." on failure path.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
session_start();
$case = isset($argv[1]) ? $argv[1] : '';

$_SESSION['csrf_token'] = 'test_token_123';
if ($case === 'valid') {
    $_POST['csrf_token'] = 'test_token_123';
} elseif ($case === 'missing') {
    unset($_POST['csrf_token']);
} elseif ($case === 'invalid') {
    $_POST['csrf_token'] = 'wrong_token';
} else {
    echo 'Usage: php admin_csrf_stub.php valid|missing|invalid';
    exit(1);
}

require_once $repo_root . '/lupo-includes/functions/security.php';
lupo_require_valid_csrf_token();
echo 'ALLOWED';
