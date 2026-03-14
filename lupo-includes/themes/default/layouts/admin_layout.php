<?php
/**
 * wolfie.header.identity: admin-layout
 * wolfie.header.placement: /lupo-includes/themes/default/layouts/admin_layout.php
 *
 * Admin page: basic template (top graphic + top nav) plus left sidebar of admin options
 * and main content area. Used by admin.php. Expects: $admin_page_title, $admin_menu_items, $admin_main_content.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. admin_layout.php cannot be called directly.");
}

$admin_page_title = isset($admin_page_title) ? $admin_page_title : 'Admin';
$admin_main_content = isset($admin_main_content) ? $admin_main_content : '<p>Admin options will show here.</p>';
$admin_active_key = isset($admin_active_key) ? $admin_active_key : 'Dashboard';
if (!isset($admin_menu_sections)) {
    $admin_menu_sections = array();
}
if (!isset($admin_menu_items) || !is_array($admin_menu_items)) {
    $admin_menu_items = array(
        'Dashboard' => 'admin.php',
        'Database' => 'admin.php?section=database',
        'Users' => 'admin.php?section=users',
        'Channels' => 'admin.php?section=channels',
        'Settings' => 'admin.php?section=settings',
    );
}
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}
$admin_actor_list = isset($admin_actor_list) && is_array($admin_actor_list) ? $admin_actor_list : array();
$admin_active_actor_id = isset($admin_active_actor_id) ? (int) $admin_active_actor_id : 0;
if (!defined('LUPO_UI_PATH')) {
    define('LUPO_UI_PATH', LUPOPEDIA_PATH . '/lupo-includes/themes/default');
}
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($admin_page_title) ?> - Admin - LUPOPEDIA</title>
    <link rel="icon" type="image/x-icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="shortcut icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main.css">
    <style>
        .admin-wrap { min-height: 100vh; display: flex; flex-direction: column; }
        .basic-header-graphic {
            width: 100%;
            min-height: 120px;
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 50%, #2b6cb0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .basic-header-graphic a { text-decoration: none; color: #fff; display: flex; align-items: center; gap: 12px; }
        .basic-header-graphic img { border-radius: 50%; }
        .basic-header-graphic .site-name { font-size: 1.75rem; font-weight: 700; letter-spacing: 0.02em; }
        .basic-nav {
            background: #2d3748;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .basic-nav .nav-inner { max-width: 1200px; margin: 0 auto; width: 100%; display: flex; align-items: center; flex-wrap: wrap; }
        .basic-nav .nav-item { position: relative; }
        .basic-nav .nav-link {
            display: block;
            padding: 12px 16px;
            color: #e2e8f0;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .basic-nav .nav-link:hover { background: #4a5568; color: #fff; }
        .basic-nav .nav-link.active { background: #4a5568; color: #fff; }
        .admin-body { display: flex; flex: 1; min-height: 0; }
        .admin-sidebar {
            width: 240px;
            min-width: 240px;
            background: #2d3748;
            color: #e2e8f0;
            padding: 1rem 0;
            box-shadow: 2px 0 6px rgba(0,0,0,0.1);
        }
        .admin-sidebar h2 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #a0aec0;
            padding: 0 1rem 0.5rem;
            margin: 0 0 0.5rem 0;
            border-bottom: 1px solid #4a5568;
        }
        .admin-sidebar nav { padding: 0; }
        .admin-sidebar a {
            display: block;
            padding: 10px 1rem;
            color: #e2e8f0;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .admin-sidebar a:hover { background: #4a5568; color: #fff; }
        .admin-sidebar a.active { background: #2b6cb0; color: #fff; font-weight: 600; }
        .admin-main {
            flex: 1;
            overflow: auto;
            padding: 24px;
            background: #f7fafc;
        }
        .admin-main h1 { margin-top: 0; font-size: 1.5rem; color: #2d3748; }
        .admin-main .admin-placeholder {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            color: #4a5568;
        }
        .admin-error-box {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 8px;
            padding: 1.5rem;
            color: #742a2a;
        }
        .admin-error-box h2 { margin-top: 0; font-size: 1.25rem; }
        .admin-error-box a { color: #2b6cb0; }
        /* Admin users section */
        .admin-users-section { max-width: 960px; }
        .admin-message { background: #c6f6d5; border: 1px solid #48bb78; border-radius: 6px; padding: 10px 14px; margin-bottom: 1rem; color: #22543d; }
        .admin-users-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .admin-users-table th, .admin-users-table td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .admin-users-table th { background: #edf2f7; font-weight: 600; color: #2d3748; }
        .admin-users-table tr:hover { background: #f7fafc; }
        .admin-users-actions { white-space: nowrap; }
        .admin-link { color: #2b6cb0; text-decoration: none; }
        .admin-link:hover { text-decoration: underline; }
        .admin-muted { color: #a0aec0; font-size: 0.9rem; }
        .admin-users-edit-profile, .admin-users-edit-permissions { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 480px; }
        .admin-users-edit-profile h2, .admin-users-edit-permissions h2 { margin-top: 0; font-size: 1.25rem; }
        .admin-users-meta { color: #718096; font-size: 0.9rem; margin-bottom: 1rem; }
        .admin-input { width: 100%; max-width: 320px; padding: 8px 10px; border: 1px solid #cbd5e0; border-radius: 4px; font-size: 1rem; }
        .admin-hint { color: #718096; font-size: 0.85rem; margin-top: 4px; }
        .admin-btn { display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 0.95rem; text-decoration: none; cursor: pointer; border: 1px solid #cbd5e0; background: #fff; color: #2d3748; margin-right: 8px; }
        .admin-btn-primary { background: #2b6cb0; color: #fff; border-color: #2b6cb0; }
        .admin-btn:hover { opacity: 0.9; }
        .admin-empty { color: #718096; font-style: italic; }
        .admin-placeholder-text { color: #4a5568; margin: 0; }
        .admin-dashboard-links { list-style: disc; margin: 1rem 0; padding-left: 1.5rem; }
        .admin-dashboard-links li { margin: 0.5rem 0; }
        .admin-section-info .admin-section-description { margin: 0 0 1rem 0; color: #4a5568; }
        .admin-section-links { list-style: disc; margin: 0.5rem 0; padding-left: 1.5rem; }
        .admin-list-table { margin-top: 1rem; }
        .basic-footer {
            background: #2d3748;
            color: #a0aec0;
            padding: 12px 1rem;
            text-align: center;
            font-size: 0.875rem;
        }
        .basic-footer a { color: #e2e8f0; text-decoration: none; }
    </style>
</head>
<body class="admin-wrap">
    <header class="basic-header-graphic" role="banner">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php" title="Lupopedia Home">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/logoface.png" alt="" width="64" height="64">
            <span class="site-name">LUPOPEDIA</span>
        </a>
    </header>

    <nav class="basic-nav" role="navigation" aria-label="Main">
        <div class="nav-inner">
            <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php">Home</a>
            <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/qa/">Q/A</a>
            <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/search.php">Content</a>
            <a class="nav-link active" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/admin.php">Admin</a>
            <?php if ($isUserLoggedIn && !empty($admin_actor_list)): ?>
                <form method="post" action="<?= LUPOPEDIA_PUBLIC_PATH ?>/switch-actor.php" class="admin-actor-selector-form" style="display: inline-flex; align-items: center; margin: 0 0 0 1rem;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(function_exists('lupo_get_csrf_token') ? lupo_get_csrf_token() : '') ?>">
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $base . '/admin.php') ?>">
                    <label for="admin-actor-select" style="color: #a0aec0; font-size: 0.875rem; margin-right: 6px;">Act as:</label>
                    <select name="actor_id" id="admin-actor-select" onchange="this.form.submit()" style="padding: 6px 8px; border-radius: 4px; border: 1px solid #4a5568; background: #2d3748; color: #e2e8f0; font-size: 0.9rem;">
                        <?php foreach ($admin_actor_list as $a): ?>
                            <option value="<?= (int) $a['actor_id'] ?>"<?= ((int) $a['actor_id'] === $admin_active_actor_id) ? ' selected="selected"' : '' ?>><?= htmlspecialchars(isset($a['name']) && $a['name'] !== '' ? $a['name'] : $a['actor_name']) ?> (<?= (int) $a['actor_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </form>
            <?php endif; ?>
            <?php if ($isUserLoggedIn): ?>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/my-profile" style="margin-left: auto;">My Profile</a>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/logout.php">Sign Out</a>
            <?php else: ?>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/login" style="margin-left: auto;">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="admin-body">
        <aside class="admin-sidebar" role="navigation" aria-label="Admin options">
            <?php
            if (!empty($admin_menu_sections) && is_array($admin_menu_sections)) {
                foreach ($admin_menu_sections as $group) {
                    $groupTitle = isset($group['title']) ? $group['title'] : '';
                    $items = isset($group['items']) && is_array($group['items']) ? $group['items'] : array();
                    if ($groupTitle !== '' || !empty($items)) {
                        ?>
            <h2><?= htmlspecialchars($groupTitle) ?></h2>
            <nav>
                        <?php foreach ($items as $label => $url): ?>
                    <?php
                    $href = (strpos($url, 'http') === 0 || strpos($url, '/') === 0) ? $url : $base . '/' . ltrim($url, '/');
                    $active = ($label === $admin_active_key) ? ' active' : '';
                    ?>
                    <a href="<?= htmlspecialchars($href) ?>" class="<?= $active ?>"><?= htmlspecialchars($label) ?></a>
                        <?php endforeach; ?>
            </nav>
                        <?php
                    }
                }
            } else {
                $fallback = isset($admin_menu_items) && is_array($admin_menu_items) ? $admin_menu_items : array();
                ?>
            <h2>Admin</h2>
            <nav>
                <?php foreach ($fallback as $label => $url): ?>
                    <?php
                    $href = (strpos($url, 'http') === 0 || strpos($url, '/') === 0) ? $url : $base . '/' . ltrim($url, '/');
                    $active = ($label === $admin_active_key) ? ' active' : '';
                    ?>
                    <a href="<?= htmlspecialchars($href) ?>" class="<?= $active ?>"><?= htmlspecialchars($label) ?></a>
                <?php endforeach; ?>
            </nav>
            <?php
            }
            ?>
        </aside>
        <main class="admin-main" id="admin-main">
            <h1><?= htmlspecialchars($admin_page_title) ?></h1>
            <div class="admin-placeholder">
                <?= $admin_main_content ?>
            </div>
        </main>
    </div>

    <footer class="basic-footer">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php">Lupopedia</a>
        &middot; Admin
    </footer>
    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/lupopedia.js"></script>
</body>
</html>
