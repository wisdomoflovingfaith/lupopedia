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

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
$admin_page_title = 'Dashboard';
$admin_active_key = 'Dashboard';

// Admin menu: grouped sections matching legacy Crafty nav (legacy/craftysyntax/navigation.php), rewritten for Lupopedia.
// All sections and items are present as placeholders; content will be implemented per section.
$admin_menu_sections = array(
    array(
        'title' => 'Overview',
        'items' => array(
            'Dashboard' => 'admin.php',
        ),
    ),
    array(
        'title' => 'General',
        'items' => array(
            'Documentation' => 'admin.php?section=documentation',
            'Master Settings' => 'admin.php?section=settings',
            'Help' => 'admin.php?section=help',
            'Support' => 'admin.php?section=support',
            'Security Registration' => 'admin.php?section=security-registration',
            'Lupopedia Registration' => 'admin.php?section=registration',
            'Member Services' => 'admin.php?section=member-services',
            'Questions and Answers' => 'admin.php?section=general-qa',
        ),
    ),
    array(
        'title' => 'CRM tools',
        'items' => array(
            'Leads Database' => 'admin.php?section=leads',
            'Email message database' => 'admin.php?section=email-messages',
            'Proactive Leads' => 'admin.php?section=proactive-leads',
            'Import Leads' => 'admin.php?section=import-leads',
        ),
    ),
    array(
        'title' => 'Agents & Channels',
        'items' => array(
            'Agents' => 'admin.php?section=agents',
            'Channels' => 'admin.php?section=channels',
        ),
    ),
    array(
        'title' => 'Live Help',
        'items' => array(
            'Live' => 'admin.php?section=live',
            'Quick replies' => 'admin.php?section=quick-replies',
            'Quick images' => 'admin.php?section=quick-images',
            'Quick URLs' => 'admin.php?section=quick-urls',
            'Auto invite' => 'admin.php?section=auto-invite',
            'Emotion Icons' => 'admin.php?section=emotion-icons',
            'Edit Layer Images' => 'admin.php?section=layer-images',
        ),
    ),
    array(
        'title' => 'Operators',
        'items' => array(
            'Edit your account' => 'admin.php?section=my-account',
            'Create / Edit / Delete' => 'admin.php?section=operators',
        ),
    ),
    array(
        'title' => 'Departments',
        'items' => array(
            'HTML code for departments' => 'admin.php?section=departments-html',
            'Create / Edit / Delete departments' => 'admin.php?section=departments',
        ),
    ),
    array(
        'title' => 'Data',
        'items' => array(
            'Visits' => 'admin.php?section=data-visits',
            'Messages' => 'admin.php?section=data-messages',
            'Referrers' => 'admin.php?section=data-referrers',
            'Visits by period' => 'admin.php?section=data-visits-period',
            'Paths' => 'admin.php?section=data-paths',
            'Keywords' => 'admin.php?section=data-keywords',
            'Users' => 'admin.php?section=users',
        ),
    ),
    array(
        'title' => 'Modules',
        'items' => array(
            'Questions & Answers' => 'admin.php?section=module-qa',
        ),
    ),
    array(
        'title' => 'Extras',
        'items' => array(
            'View Directory' => 'admin.php?section=directory',
        ),
    ),
    array(
        'title' => 'Information',
        'items' => array(
            'Donations' => 'admin.php?section=donations',
            'Updates' => 'admin.php?section=updates',
            'Changelog' => 'admin.php?section=changelog',
        ),
    ),
);

// Build flat list for layout (backward compat); layout will use $admin_menu_sections when set.
$admin_menu_items = array();
foreach ($admin_menu_sections as $group) {
    foreach ($group['items'] as $label => $url) {
        $admin_menu_items[$label] = $url;
    }
}

// Placeholder content for sections not yet implemented.
$admin_placeholder = function ($title) {
    return '<p class="admin-placeholder-text">This section is a placeholder. Content for <strong>' . htmlspecialchars($title) . '</strong> will be implemented here.</p>';
};

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
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    // Sections that have real content
    if ($section === 'users') {
        $admin_page_title = 'Users';
        $admin_active_key = 'Users';
        if (!$db) {
            $admin_main_content = '<p class="admin-empty">Database not available.</p>';
        } else {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminUsersHandler.php';
            $admin_main_content = AdminUsersHandler::render($db, $prefix, $base);
        }
    } else {
        // Map section slug to page title and active menu key (must match menu item label)
        $section_titles = array(
            'documentation' => array('Documentation', 'Documentation'),
            'settings' => array('Master Settings', 'Master Settings'),
            'help' => array('Help', 'Help'),
            'support' => array('Support', 'Support'),
            'security-registration' => array('Security Registration', 'Security Registration'),
            'registration' => array('Lupopedia Registration', 'Lupopedia Registration'),
            'member-services' => array('Member Services', 'Member Services'),
            'general-qa' => array('Questions and Answers', 'Questions and Answers'),
            'leads' => array('Leads Database', 'Leads Database'),
            'email-messages' => array('Email message database', 'Email message database'),
            'proactive-leads' => array('Proactive Leads', 'Proactive Leads'),
            'import-leads' => array('Import Leads', 'Import Leads'),
            'agents' => array('Agents', 'Agents'),
            'channels' => array('Channels', 'Channels'),
            'live' => array('Live', 'Live'),
            'quick-replies' => array('Quick replies', 'Quick replies'),
            'quick-images' => array('Quick images', 'Quick images'),
            'quick-urls' => array('Quick URLs', 'Quick URLs'),
            'auto-invite' => array('Auto invite', 'Auto invite'),
            'emotion-icons' => array('Emotion Icons', 'Emotion Icons'),
            'layer-images' => array('Edit Layer Images', 'Edit Layer Images'),
            'my-account' => array('Edit your account', 'Edit your account'),
            'operators' => array('Create / Edit / Delete', 'Create / Edit / Delete'),
            'departments-html' => array('HTML code for departments', 'HTML code for departments'),
            'departments' => array('Create / Edit / Delete departments', 'Create / Edit / Delete departments'),
            'data-visits' => array('Visits', 'Visits'),
            'data-messages' => array('Messages', 'Messages'),
            'data-referrers' => array('Referrers', 'Referrers'),
            'data-visits-period' => array('Visits by period', 'Visits by period'),
            'data-paths' => array('Paths', 'Paths'),
            'data-keywords' => array('Keywords', 'Keywords'),
            'module-qa' => array('Questions & Answers', 'Questions & Answers'),
            'directory' => array('View Directory', 'View Directory'),
            'donations' => array('Donations', 'Donations'),
            'updates' => array('Updates', 'Updates'),
            'changelog' => array('Changelog', 'Changelog'),
        );
        if (isset($section_titles[$section])) {
            $admin_page_title = $section_titles[$section][0];
            $admin_active_key = $section_titles[$section][1];
            $admin_main_content = $admin_placeholder($admin_page_title);
        }
    }
}

require LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_layout.php';
