<?php
/**
 * T8 Regression tests: legacy paths, module loader, content_show_by_content_id, admin paths.
 * Run from repo root: php tests/regression/legacy_paths.php
 * Verifies no breakage: syntax of key files, presence of routing/content helpers.
 * Does NOT start web server; full legacy index.php?slug= and admin require HTTP. PHP 5.3-compatible.
 */
$repo_root = dirname(dirname(__DIR__));
$fail = 0;

// Syntax check key files (routing + admin)
$files = array(
    $repo_root . '/index.php',
    $repo_root . '/lupo-includes/modules/module-loader.php',
    $repo_root . '/lupo-includes/modules/content/content-controller.php',
    $repo_root . '/lupo-includes/functions/url_resolver.php',
    $repo_root . '/lupo-includes/classes/UrlResolver.php',
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
foreach ($files as $f) {
    if (!is_file($f)) {
        echo "FAIL missing: $f\n";
        $fail++;
        continue;
    }
    $out = array();
    $ret = 0;
    exec('php -l ' . escapeshellarg($f) . ' 2>&1', $out, $ret);
    if ($ret !== 0) {
        echo "FAIL syntax: $f\n";
        $fail++;
    }
}

// Routing helpers exist when url_resolver is loaded (minimal env)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', $repo_root);
}
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}
if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}
$GLOBALS['mydatabase'] = null;
require_once $repo_root . '/lupo-includes/classes/UrlResolver.php';
require_once $repo_root . '/lupo-includes/functions/url_resolver.php';

if (!function_exists('lupo_resolve_web_path')) {
    echo "FAIL lupo_resolve_web_path missing\n";
    $fail++;
}
if (!function_exists('lupo_smart_404')) {
    echo "FAIL lupo_smart_404 missing\n";
    $fail++;
}
if (!function_exists('lupo_get_url_resolver')) {
    echo "FAIL lupo_get_url_resolver missing\n";
    $fail++;
}

// content_show_by_content_id: defined in content-controller (requires config + DB to load fully)
// We only verify the file that defines it exists and has no syntax error (already in $files above).

// Module loader defines lupo_route_slug; we cannot require it without full config. So we only did syntax check.

// AdminUsersHandler class exists when loaded (4.0.19)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
require_once $repo_root . '/lupo-includes/classes/AdminUsersHandler.php';
if (!class_exists('AdminUsersHandler')) {
    echo "FAIL AdminUsersHandler class missing after require\n";
    $fail++;
}

if ($fail > 0) {
    echo "FAIL ($fail checks)\n";
    exit(1);
}
echo "PASS (regression: syntax, routing helpers, admin files)\n";
exit(0);
