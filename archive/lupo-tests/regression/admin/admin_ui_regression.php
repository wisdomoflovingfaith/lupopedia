<?php
/**
 * Regression: Admin UI — admin.php loads, sections/handlers/templates exist.
 * Run from repo root: php tests/regression/admin/admin_ui_regression.php
 * PHP 5.3-compatible.
 */
$repo_root = dirname(dirname(dirname(__DIR__)));
$fail = 0;
$skip = 0;

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}

// Required admin entry and layout
$required = array(
    $repo_root . '/admin.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_layout.php',
    $repo_root . '/lupo-includes/themes/default/layouts/admin_sections/info.php',
);
foreach ($required as $f) {
    if (!is_file($f)) {
        echo "FAIL missing: $f\n";
        $fail++;
    } else {
        $out = array();
        exec('php -l ' . escapeshellarg($f) . ' 2>&1', $out, $ret);
        if ($ret !== 0) {
            echo "FAIL syntax: $f\n";
            $fail++;
        }
    }
}

// Sections with list handlers: users, channels, agents, departments, leads
$handlers = array(
    'users' => 'AdminUsersHandler.php',
    'channels' => 'AdminChannelsHandler.php',
    'agents' => 'AdminAgentsHandler.php',
    'departments' => 'AdminDepartmentsHandler.php',
    'leads' => 'AdminLeadsHandler.php',
);
$handler_dir = $repo_root . '/lupo-includes/classes';
$view_dir = $repo_root . '/lupo-includes/themes/default/layouts/admin_sections';
foreach ($handlers as $section => $class_file) {
    $path = $handler_dir . '/' . $class_file;
    if (!is_file($path)) {
        echo "FAIL missing handler: $path\n";
        $fail++;
    } else {
        $out = array();
        exec('php -l ' . escapeshellarg($path) . ' 2>&1', $out, $ret);
        if ($ret !== 0) {
            echo "FAIL syntax: $path\n";
            $fail++;
        }
    }
    $view = $view_dir . '/' . $section . '.php';
    if (!is_file($view)) {
        echo "FAIL missing view: $view\n";
        $fail++;
    }
}

// Dashboard: admin.php sets quick links when $isAdmin (no section); we only check structure
if ($fail > 0) {
    echo "REGRESSION ADMIN UI: $fail fail, $skip skip\n";
    exit(1);
}
echo "PASS admin UI regression (entry, handlers, views)\n";
exit(0);
