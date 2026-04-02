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

// Load AuthSessionManager for actor management
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AuthSessionManager.php';

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

// Actor selector: list of actors user can act as, and current active actor (Trae IDE doc: Web Authentication and Actor Selection)
$admin_actor_list = array();
$admin_active_actor_id = 0;
if ($isUserLoggedIn) {
    // Use AuthSessionManager for actor management
    $sessionManager = new AuthSessionManager();
    $admin_actor_list = $sessionManager->getActorsUserCanActAs($user['auth_user_id'], $isAdmin);
    $admin_active_actor_id = $sessionManager->getActiveActorId();
    if ($admin_active_actor_id <= 0 && !empty($user['actor_id'])) {
        $admin_active_actor_id = (int) $user['actor_id'];
    }
}

// Display name for "Acting as" — human actors (e.g. captain, is_agent=0) are not in getActorsUserCanActAs() list
$admin_active_actor_display = '';
if ($isUserLoggedIn && $admin_active_actor_id > 0) {
    foreach ($admin_actor_list as $row) {
        if ((int) $row['actor_id'] === $admin_active_actor_id) {
            if (isset($row['name']) && $row['name'] !== '') {
                $admin_active_actor_display = $row['name'];
            } elseif (isset($row['actor_name'])) {
                $admin_active_actor_display = $row['actor_name'];
            }
            break;
        }
    }
    if ($admin_active_actor_display === '') {
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
        $db_ad = DatabaseFactory::getConnection();
        $tp = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $arow = $db_ad->fetchRow(
            "SELECT name, actor_name FROM {$tp}actors WHERE actor_id = :aid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('aid' => $admin_active_actor_id)
        );
        if ($arow) {
            if (isset($arow['name']) && $arow['name'] !== '') {
                $admin_active_actor_display = $arow['name'];
            } elseif (isset($arow['actor_name'])) {
                $admin_active_actor_display = $arow['actor_name'];
            }
        }
    }
}

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
            'Artifacts' => 'admin.php?section=artifacts',
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
            'Actors' => 'admin.php?section=actors',
            'Actor Status' => 'admin.php?section=actor_status',
            'Channels' => $base . '/channels',
            'Tasks' => 'admin.php?section=tasks',
            'Registry' => 'admin.php?section=registry',
        ),
    ),
    array(
        'title' => 'Live Help',
        'items' => array(
            'Live' => 'admin.php?section=live',
            'Channel Chat' => 'admin.php?section=channel-chat',
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
            'Channel 66 Q&A' => 'admin.php?section=channel66-qa',
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
    'artifacts' => array('description' => 'Manage system artifacts and chunks. Replaces the legacy document system for RAG and semantic mapping.', 'links' => array('Artifacts API' => 'lupo-api/v1/artifact.php')),
    'documentation' => array('description' => 'Links to Lupopedia documentation and doctrine. Use the Q/A and Content areas from the main nav for browsing.', 'links' => array('Doctrine' => 'doctrine/', 'Q/A' => 'qa/', 'Docs' => 'lupo-docs/')),
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
    'channel-chat' => array('description' => 'Channel chat interface with actor-first server-side resolution, optional actor override, and department-scoped fallback.', 'links' => array()),
    'quick-replies' => array('description' => 'Quick reply templates for operators (lupo_actor_reply_templates).', 'links' => array()),
    'quick-images' => array('description' => 'Quick image assets for chat.', 'links' => array()),
    'quick-urls' => array('description' => 'Quick URL shortcuts.', 'links' => array()),
    'auto-invite' => array('description' => 'Auto-invite rules (lupo_crafty_syntax_auto_invite).', 'links' => array()),
    'emotion-icons' => array('description' => 'Emotion icon set for chat.', 'links' => array()),
    'layer-images' => array('description' => 'Edit layer images for the chat UI.', 'links' => array()),
    'my-account' => array('description' => 'Edit your own operator account (profile, password). Use My Profile from the main nav for profile; admin-specific options can be added here.', 'links' => array('My Profile' => 'my-profile')),
    'operators' => array('description' => 'Create, edit, and delete operators. User management is in the Data → Users section.', 'links' => array('Users' => 'admin.php?section=users')),
    'departments-html' => array('description' => 'HTML code snippets for department-specific widgets or embed codes.', 'links' => array('Departments' => 'admin.php?section=departments')),
    'data-visits' => array('description' => 'Visit analytics (lupo_visits, lupo_analytics_visits). List and filter visits.', 'links' => array()),
    'data-messages' => array('description' => 'Message database (lupo_dialog_messages). Browse and search messages.', 'links' => array()),
    'data-referrers' => array('description' => 'Referrer analytics (lupo_referers).', 'links' => array()),
    'data-visits-period' => array('description' => 'Visits aggregated by period.', 'links' => array()),
    'data-paths' => array('description' => 'Path analytics (lupo_analytics_paths).', 'links' => array()),
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
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=users') . '" class="admin-link">Users</a> — Manage auth users and their primary actor pairing for admin actions</li>'
        . '<li><a href="' . htmlspecialchars($base . '/channels') . '" class="admin-link">Channels</a> — Open the MVP dialog channel list</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=agents') . '" class="admin-link">Agents</a> — List actor identities that carry agent behavior</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=departments') . '" class="admin-link">Departments</a> — List actor-scoped departments and defaults</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=leads') . '" class="admin-link">Leads</a> — CRM leads database</li>'
        . '<li><a href="' . htmlspecialchars($base . '/admin.php?section=settings') . '" class="admin-link">Master Settings</a> — Configuration</li>'
        . '</ul>';

    // Q4 (4.0.87): Read-only metadata staleness panel — no UI mutations, admin-only.
    // Shows lupo_metadata rows where last_verified is NULL or < 20260301000000 (2026-03-01).
    if (isset($GLOBALS['mydatabase'])) {
        $meta_db     = $GLOBALS['mydatabase'];
        $meta_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $stale_cutoff = 20260301000000;

        $stale_rows = $meta_db->fetchAll(
            "SELECT entity_type, entity_id, property_value AS last_verified, class_name
             FROM {$meta_prefix}metadata
             WHERE is_deleted = 0
               AND property_key = 'last_verified'
               AND (property_value IS NULL OR CAST(property_value AS UNSIGNED) < :cutoff)
             ORDER BY property_value ASC
             LIMIT 50",
            array('cutoff' => $stale_cutoff)
        );

        // Entities that have NO last_verified row at all
        $no_lv_row = $meta_db->fetchOne(
            "SELECT COUNT(DISTINCT CONCAT(entity_type,':', CAST(entity_id AS CHAR))) AS cnt
             FROM {$meta_prefix}metadata m
             WHERE m.is_deleted = 0
               AND NOT EXISTS (
                 SELECT 1 FROM {$meta_prefix}metadata m2
                 WHERE m2.entity_type = m.entity_type
                   AND m2.entity_id   = m.entity_id
                   AND m2.property_key = 'last_verified'
                   AND m2.is_deleted   = 0
               )"
        );

        $stale_count  = count($stale_rows);
        $no_lv_count  = ($no_lv_row && isset($no_lv_row['cnt'])) ? (int) $no_lv_row['cnt'] : 0;

        $panel = '<div class="admin-staleness-panel" style="margin-top:1.5em;padding:1em;border:1px solid #daa;background:#fff8f8;border-radius:4px;">';
        $panel .= '<h3 style="margin-top:0;">Metadata Staleness <small style="font-weight:normal;font-size:.85em;">(read-only &mdash; threshold: 2026-03-01)</small></h3>';

        if ($stale_count === 0 && $no_lv_count === 0) {
            $panel .= '<p style="color:#080;">&#10003; All metadata records have current <code>last_verified</code> timestamps.</p>';
        } else {
            $panel .= '<p style="color:#a00;">&#9888; Stale <code>last_verified</code>: <strong>' . $stale_count . '</strong> &nbsp;|&nbsp; '
                . 'Missing <code>last_verified</code> (entity): <strong>' . $no_lv_count . '</strong></p>';

            if ($stale_count > 0) {
                $panel .= '<table style="width:100%;border-collapse:collapse;font-size:.9em;">'
                    . '<thead><tr style="background:#f0e0e0;">'
                    . '<th style="padding:4px 8px;text-align:left;">entity_type</th>'
                    . '<th style="padding:4px 8px;text-align:left;">entity_id</th>'
                    . '<th style="padding:4px 8px;text-align:left;">last_verified</th>'
                    . '<th style="padding:4px 8px;text-align:left;">class_name</th>'
                    . '</tr></thead><tbody>';
                foreach ($stale_rows as $sr) {
                    $lv_val = isset($sr['last_verified']) ? (string) $sr['last_verified'] : '';
                    $lv_display = ($lv_val !== '') ? htmlspecialchars($lv_val) : '<em>NULL</em>';
                    $panel .= '<tr style="border-top:1px solid #ecc;">'
                        . '<td style="padding:3px 8px;">' . htmlspecialchars((string) $sr['entity_type']) . '</td>'
                        . '<td style="padding:3px 8px;">' . htmlspecialchars((string) $sr['entity_id']) . '</td>'
                        . '<td style="padding:3px 8px;color:#900;">' . $lv_display . '</td>'
                        . '<td style="padding:3px 8px;">' . htmlspecialchars((string) $sr['class_name']) . '</td>'
                        . '</tr>';
                }
                $panel .= '</tbody></table>';
            }
        }
        $panel .= '</div>';

        $admin_main_content .= $panel;
    }
}

if ($isAdmin && isset($_GET['section']) && is_string($_GET['section'])) {
    $section = trim($_GET['section']);

    if (($section === 'channels' || $section === 'channel_view') && !headers_sent()) {
        header('Location: ' . $base . '/channels', true, 302);
        exit;
    }

    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    // Section title and active menu key (must match menu item label)
    $section_titles = array(
        'artifacts' => array('Artifacts', 'Artifacts'),
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
        'actors' => array('Actors', 'Actors'),
        'channels' => array('Channels', 'Channels'),
        'registry' => array('Registry', 'Registry'),
        'live' => array('Live', 'Live'),
        'channel-chat' => array('Channel Chat', 'Channel Chat'),
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
        'channel66-qa' => array('Channel 66 Q&A', 'Channel 66 Q&A'),
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
    } elseif ($section === 'channel_view' && $db) {
        $admin_page_title = 'Channel View';
        $admin_active_key = 'Channels';
        require_once LUPOPEDIA_PATH . '/lupo-includes/modules/channels/ChannelsController.php';
        $controller = new ChannelsController($db);
        $controller->admin_view(isset($_GET['id']) ? (int) $_GET['id'] : 0);
    } elseif ($section === 'agents' && $db) {
        $admin_page_title = 'Agents';
        $admin_active_key = 'Agents';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminAgentsHandler.php';
        $admin_main_content = AdminAgentsHandler::render($db, $prefix, $base);
    } elseif ($section === 'actors' && $db) {
        $admin_page_title = 'Actors';
        $admin_active_key = 'Actors';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminActorsHandler.php';
        $admin_main_content = AdminActorsHandler::render($db, $prefix, $base);
    } elseif ($section === 'tasks' && $db) {
        $admin_page_title = 'Tasks';
        $admin_active_key = 'Tasks';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminTasksHandler.php';
        $admin_main_content = AdminTasksHandler::render($db, $prefix, $base);
    } elseif ($section === 'registry' && $db) {
        $admin_page_title = 'Registry';
        $admin_active_key = 'Registry';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminRegistryHandler.php';
        $admin_main_content = AdminRegistryHandler::render($db, $prefix, $base);
    } elseif ($section === 'actor_status' && $db) {
        $admin_page_title = 'Actor Status';
        $admin_active_key = 'Actor Status';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminActorStatusHandler.php';
        $handler = new AdminActorStatusHandler($db, $prefix);
        ob_start();
        $handler->render();
        $admin_main_content = ob_get_clean();
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
    } elseif ($section === 'settings' && $db) {
        $admin_page_title = 'Master Settings';
        $admin_active_key = 'Master Settings';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminSettingsHandler.php';
        $handler = new AdminSettingsHandler($db, $prefix, $base);
        $admin_main_content = $handler->render();
    } elseif ($section === 'channel66-qa' && $db) {
        $admin_page_title = 'Channel 66 Q&A';
        $admin_active_key = 'Channel 66 Q&A';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminChannel66QaHandler.php';
        $admin_main_content = AdminChannel66QaHandler::render($db, $prefix, $base);
    } elseif ($section === 'channel-chat' && $db) {
        $admin_page_title = 'Channel Chat';
        $admin_active_key = 'Channel Chat';
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/AdminChannelChatHandler.php';
        $admin_main_content = AdminChannelChatHandler::render($db, $prefix, $base);
    } elseif (isset($section_titles[$section])) {
        $admin_page_title = $section_titles[$section][0];
        $admin_active_key = $section_titles[$section][1];
        if ($section === 'channels' || $section === 'agents' || $section === 'actors' || $section === 'departments' || $section === 'leads' || $section === 'registry' || $section === 'channel-chat') {
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
