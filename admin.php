<?php
/**
 * Admin interface — Lupopedia. Requires login and admin role.
 * Renders basic template with left navigation of admin options and main content area.
 * Linked from user dropdown (Database Admin) in topbar.
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

// Config path: lupopedia-config.php first; config.php only if lupopedia-config does not exist (legacy)
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php');
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
} elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/config.php');
} elseif (@file_exists(LUPOPEDIA_PATH . '/config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/config.php');
} else {
    header('Location: ' . (rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/') ?: '') . '/install.php');
    exit;
}

require_once LUPOPEDIA_CONFIG_PATH;

// Require login only; if not admin, show graceful error inside layout (nav still visible)
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService) {
    $authService->requireLogin();
} else {
    if (!function_exists('require_login')) {
        require_once LUPOPEDIA_PATH . '/lupo-includes/functions/auth-helpers.php';
    }
    require_login();
}

$user = $authService ? $authService->getCurrentUser() : (function_exists('current_user') ? current_user() : array());
$isUserLoggedIn = ($user !== false && !empty($user));
$isAdmin = $isUserLoggedIn && !empty($user['is_admin']);

$admin_page_title = 'Dashboard';
$admin_active_key = 'Dashboard';
$admin_menu_items = array(
    'Dashboard' => 'admin.php',
    'Database' => 'admin.php?section=database',
    'Users' => 'admin.php?section=users',
    'Channels' => 'admin.php?section=channels',
    'Settings' => 'admin.php?section=settings',
);

if (!$isAdmin) {
    $admin_main_content = '<div class="admin-error-box">'
        . '<h2>Access denied</h2>'
        . '<p>Your account does not have permission to access the admin area. If you believe this is an error, ask an administrator to grant you a channel role (e.g. captain or administrator on channel 1) or owner permission on the admin module.</p>'
        . '<p><a href="' . (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/index.php">Return to home</a></p>'
        . '</div>';
} else {
    $admin_main_content = '<p>Admin options will show here.</p>';
}

if ($isAdmin && isset($_GET['section']) && is_string($_GET['section'])) {
    $section = trim($_GET['section']);
    if ($section === 'database') {
        $admin_page_title = 'Database';
        $admin_active_key = 'Database';
        $admin_main_content = '<p>Database admin options will show here.</p>';
    } elseif ($section === 'users') {
        $admin_page_title = 'Users';
        $admin_active_key = 'Users';
        $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        if (!$db) {
            $admin_main_content = '<p class="admin-empty">Database not available.</p>';
        } else {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminUsersHandler.php';
            $admin_main_content = AdminUsersHandler::render($db, $prefix, $base);
        }
    } elseif ($section === 'channels') {
        $admin_page_title = 'Channels';
        $admin_active_key = 'Channels';
        $admin_main_content = '<p>Channel admin options will show here.</p>';
    } elseif ($section === 'settings') {
        $admin_page_title = 'Settings';
        $admin_active_key = 'Settings';
        $admin_main_content = '<p>Settings options will show here.</p>';
    }
}

require LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_layout.php';
