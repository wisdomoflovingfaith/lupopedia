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

if (!function_exists('lupo_get_csrf_token')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/security.php';
}

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

// Admin diagnostics (4.0.20): session introspection, permission check — local-only, dev-only
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/functions/admin_diagnostics.php')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/admin_diagnostics.php';
    $diag_actor_id = ($isUserLoggedIn && isset($user['actor_id'])) ? (int) $user['actor_id'] : 0;
    $diag_session_age = 0;
    if ($diag_actor_id && isset($GLOBALS['mydatabase']) && function_exists('session_id')) {
        $sid = session_id();
        if ($sid !== '' && $sid !== false) {
            $db = $GLOBALS['mydatabase'];
            $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $t = $db->quoteIdentifier($prefix . 'sessions');
            $row = $db->fetchRow("SELECT created_ymdhis FROM {$t} WHERE session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array('sid' => $sid));
            if ($row && isset($row['created_ymdhis'])) {
                $s = (string) $row['created_ymdhis'];
                if (strlen($s) >= 14) {
                    $str = substr($s, 0, 4) . '-' . substr($s, 4, 2) . '-' . substr($s, 6, 2) . ' ' . substr($s, 8, 2) . ':' . substr($s, 10, 2) . ':' . substr($s, 12, 2) . ' UTC';
                    $ts = strtotime($str);
                    if ($ts !== false) {
                        $diag_session_age = max(0, time() - $ts);
                    }
                }
            }
        }
    }
    $diag_ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    if (function_exists('lupo_diag_session')) {
        lupo_diag_session($diag_actor_id, $diag_session_age, $diag_ip);
    }
    $diag_roles = $isAdmin ? array('admin') : array();
    if (function_exists('lupo_diag_permission_check')) {
        lupo_diag_permission_check($diag_actor_id, $diag_roles, 'admin', $isAdmin);
    }
}

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
            'CSV Data Export' => 'admin.php?section=csv-export',
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

// Section-specific info for sections that use the generic info panel (description + optional links).
$admin_section_info = array(
    'documentation' => array('description' => 'Links to Lupopedia documentation and doctrine. Use the Q/A and Content areas from the main nav for browsing.', 'links' => array('Doctrine' => 'doctrine/', 'Q/A' => 'qa/', 'Docs' => 'docs/')),
    'settings' => array('description' => 'Master settings for the installation (e.g. site name, timezone, feature flags). Configuration is stored in config and database; this panel will be expanded to edit key settings.', 'links' => array()),
    'help' => array('description' => 'In-app help and usage guides. Content can be added here or linked to doctrine/docs.', 'links' => array()),
    'support' => array('description' => 'Support contacts and resources for administrators.', 'links' => array()),
    'security-registration' => array('description' => 'Security and registration settings (e.g. allowed domains, invite-only).', 'links' => array()),
    'registration' => array('description' => 'Lupopedia product registration and license (if applicable).', 'links' => array()),
    'member-services' => array('description' => 'Member services and subscription management.', 'links' => array()),
    'general-qa' => array('description' => 'General questions and answers. See the Q/A section in the main nav for content.', 'links' => array('Q/A' => 'qa/')),
    'email-messages' => array('description' => 'Email message database (outbound/inbound). Table: lupo_crm_lead_messages and related. List/detail UI can be added here.', 'links' => array('Leads' => 'admin.php?section=leads')),
    'proactive-leads' => array('description' => 'Proactive lead outreach and automation. Configure triggers and templates.', 'links' => array()),
    'import-leads' => array('description' => 'Import leads from CSV or other sources into lupo_crm_leads.', 'links' => array('Leads' => 'admin.php?section=leads')),
    'live' => array('description' => 'Live help session monitor and controls.', 'links' => array()),
    'quick-replies' => array('description' => 'Quick reply templates for operators (lupo_actor_reply_templates).', 'links' => array()),
    'quick-images' => array('description' => 'Quick image assets for chat.', 'links' => array()),
    'quick-urls' => array('description' => 'Quick URL shortcuts.', 'links' => array()),
    'auto-invite' => array('description' => 'Auto-invite rules (lupo_crafty_syntax_auto_invite).', 'links' => array()),
    'emotion-icons' => array('description' => 'Emotion icon set for chat.', 'links' => array()),
    'layer-images' => array('description' => 'Edit layer images for the chat UI.', 'links' => array()),
    'my-account' => array('description' => 'Edit your own operator account (profile, password). Use My Profile from the main nav for profile; admin-specific options can be added here.', 'links' => array('My Profile' => 'my-profile')),
    'operators' => array('description' => 'Create, edit, and delete operators. User management is in the Data → Users section.', 'links' => array('Users' => 'admin.php?section=users')),
    'departments-html' => array('description' => 'HTML code snippets for department-specific widgets or embed codes.', 'links' => array('Departments' => 'admin.php?section=departments')),
    'data-visits' => array('description' => 'Visit analytics (lupo_unified_visits, lupo_analytics_visits). List and filter visits.', 'links' => array()),
    'data-messages' => array('description' => 'Message database (lupo_dialog_messages). Browse and search messages.', 'links' => array()),
    'data-referrers' => array('description' => 'Referrer analytics (lupo_unified_referers).', 'links' => array()),
    'data-visits-period' => array('description' => 'Visits aggregated by period.', 'links' => array()),
    'data-paths' => array('description' => 'Path analytics (lupo_unified_analytics_paths).', 'links' => array()),
    'data-keywords' => array('description' => 'Keyword analytics.', 'links' => array()),
    'module-qa' => array('description' => 'Questions & Answers module configuration.', 'links' => array('Q/A' => 'qa/')),
    'directory' => array('description' => 'View directory listing (e.g. file or content directory).', 'links' => array()),
    'donations' => array('description' => 'Donation and support information.', 'links' => array()),
    'updates' => array('description' => 'Check for Lupopedia updates and apply patches.', 'links' => array()),
    'changelog' => array('description' => 'Lupopedia changelog. See CHANGELOG.md in the repository or docs.', 'links' => array()),
);

if (!$isAdmin) {
    $admin_main_content = '<div class="admin-error-box">'
        . '<h2>Access denied</h2>'
        . '<p>Your account does not have permission to access the admin area. If you believe this is an error, ask an administrator to grant you a channel role (e.g. captain or administrator on channel 1) or owner permission on the admin module.</p>'
        . '<p><a href="' . (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/index.php">Return to home</a></p>'
        . '</div>';
} else {
    $admin_main_content = '<p class="admin-section-description">Welcome to the admin area. Use the sidebar to open a section.</p>'
        . '<ul class="admin-dashboard-links">'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=users') . '" class="admin-link">Users</a> — Manage auth users and channel roles</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=channels') . '" class="admin-link">Channels</a> — List channels</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=agents') . '" class="admin-link">Agents</a> — List agents</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=departments') . '" class="admin-link">Departments</a> — List departments</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=leads') . '" class="admin-link">Leads</a> — CRM leads database</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=settings') . '" class="admin-link">Master Settings</a> — Configuration</li>'
        . '</ul>';
}

if ($isAdmin && isset($_GET['section']) && is_string($_GET['section'])) {
    $section = trim($_GET['section']);
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    // Section title and active menu key (must match menu item label)
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
        'csv-export' => array('CSV Data Export', 'CSV Data Export'),
        'module-qa' => array('Questions & Answers', 'Questions & Answers'),
        'directory' => array('View Directory', 'View Directory'),
        'donations' => array('Donations', 'Donations'),
        'updates' => array('Updates', 'Updates'),
        'changelog' => array('Changelog', 'Changelog'),
    );

    // Sections with dedicated handlers (list data from DB)
    if ($section === 'users') {
        $admin_page_title = 'Users';
        $admin_active_key = 'Users';
        if (!$db) {
            $admin_main_content = '<p class="admin-empty">Database not available.</p>';
        } else {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminUsersHandler.php';
            $admin_main_content = AdminUsersHandler::render($db, $prefix, $base);
        }
    } elseif ($section === 'csv-export') {
        $admin_page_title = 'CSV Data Export';
        $admin_active_key = 'CSV Data Export';
        if (!$db) {
            $admin_main_content = '<p class="admin-empty">Database not available.</p>';
        } else {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminCsvExportHandler.php';
            $admin_main_content = AdminCsvExportHandler::render($db, $prefix, $base);
        }
    } elseif ($section === 'channels' && $db) {
        $admin_page_title = 'Channels';
        $admin_active_key = 'Channels';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminChannelsHandler.php';
        $admin_main_content = AdminChannelsHandler::render($db, $prefix, $base);
    } elseif ($section === 'agents' && $db) {
        $admin_page_title = 'Agents';
        $admin_active_key = 'Agents';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminAgentsHandler.php';
        $admin_main_content = AdminAgentsHandler::render($db, $prefix, $base);
    } elseif ($section === 'departments' && $db) {
        $admin_page_title = 'Create / Edit / Delete departments';
        $admin_active_key = 'Create / Edit / Delete departments';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminDepartmentsHandler.php';
        $admin_main_content = AdminDepartmentsHandler::render($db, $prefix, $base);
    } elseif ($section === 'leads' && $db) {
        $admin_page_title = 'Leads Database';
        $admin_active_key = 'Leads Database';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminLeadsHandler.php';
        $admin_main_content = AdminLeadsHandler::render($db, $prefix, $base);
    } elseif (isset($section_titles[$section])) {
        $admin_page_title = $section_titles[$section][0];
        $admin_active_key = $section_titles[$section][1];
        if ($section === 'channels' || $section === 'agents' || $section === 'departments' || $section === 'leads') {
            $admin_main_content = '<p class="admin-empty">Database not available.</p>';
        } elseif (isset($admin_section_info[$section])) {
            $info = $admin_section_info[$section];
            $admin_section_title = $admin_page_title;
            $admin_section_description = isset($info['description']) ? $info['description'] : 'Content for this section will be implemented here.';
            $admin_section_links = isset($info['links']) ? $info['links'] : array();
            ob_start();
            include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/info.php';
            $admin_main_content = ob_get_clean();
        } else {
            $admin_section_title = $admin_page_title;
            $admin_section_description = 'Content for ' . $admin_page_title . ' will be implemented here.';
            $admin_section_links = array();
            ob_start();
            include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/info.php';
            $admin_main_content = ob_get_clean();
        }
    }
}

require LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_layout.php';
