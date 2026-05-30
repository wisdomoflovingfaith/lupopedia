<?php
/**
 * T8/4.0.19 Unit test: AdminUsersHandler — class exists, render() with mock DB returns HTML.
 * Run from repo root: php tests/unit/admin_users_handler.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}

require_once $repo_root . '/lupo-includes/classes/AdminUsersHandler.php';

$fail = 0;

// Class exists
if (!class_exists('AdminUsersHandler')) {
    echo "FAIL AdminUsersHandler class not found\n";
    exit(1);
}

// render() exists and is callable
if (!method_exists('AdminUsersHandler', 'render')) {
    echo "FAIL AdminUsersHandler::render missing\n";
    $fail++;
}

// Mock PDO_DB for list path: quoteIdentifier, fetchAll (users list empty -> no role query)
class MockPDODB_AdminUsers {
    public function quoteIdentifier($name) {
        return '`' . str_replace('`', '``', $name) . '`';
    }
    public function fetchAll($sql, $params) {
        return array();
    }
}
$mockDb = new MockPDODB_AdminUsers();

$html = AdminUsersHandler::render($mockDb, 'lupo_', '/lupopedia');
if (!is_string($html)) {
    echo "FAIL render() did not return string\n";
    $fail++;
}
if (strlen($html) === 0) {
    echo "FAIL render() returned empty string\n";
    $fail++;
}
// View outputs admin-users-section and Users or user list
if (strpos($html, 'admin-users-section') === false && strpos($html, 'Users') === false && strpos($html, 'user') === false) {
    echo "FAIL render() output has no expected markup (admin-users-section, Users, or user)\n";
    $fail++;
}

if ($fail > 0) {
    echo "FAIL ($fail assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
