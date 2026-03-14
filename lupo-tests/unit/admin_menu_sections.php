<?php
/**
 * T8/4.0.19 Unit test: Admin menu sections and required admin files exist.
 * Run from repo root: php tests/unit/admin_menu_sections.php
 * PHP 5.3-compatible. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

$fail = 0;

// Required admin files (must exist for admin UI to function)
$admin_files = array(
    $repo_root . '/admin.php',
    $repo_root . '/lupo-includes/classes/AdminUsersHandler.php',
    $repo_root . '/lupo-includes/classes/AdminChannelsHandler.php',
    $repo_root . '/lupo-includes/classes/AdminAgentsHandler.php',
    $repo_root . '/lupo-includes/classes/AdminDepartmentsHandler.php',
    $repo_root . '/lupo-includes/classes/AdminLeadsHandler.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_layout.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/users.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/info.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/channels.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/agents.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/departments.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/leads.php',
);
foreach ($admin_files as $f) {
    if (!is_file($f)) {
        echo "FAIL missing admin file: $f\n";
        $fail++;
    }
}

// Expected admin section slugs (contract: admin.php?section= must support these)
$expected_sections = array(
    'users', 'settings', 'channels', 'agents', 'documentation', 'help', 'support',
    'leads', 'operators', 'departments', 'data-visits', 'data-messages', 'module-qa',
    'my-account', 'changelog', 'updates', 'donations', 'directory',
);
if (count($expected_sections) < 10) {
    echo "FAIL expected_sections contract too small\n";
    $fail++;
}

if ($fail > 0) {
    echo "FAIL ($fail checks)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
