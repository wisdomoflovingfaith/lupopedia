<?php
/**
 * wolfie.header.identity: admin-layout
 * wolfie.header.placement: /lupo-includes/themes/default/layouts/admin_layout.php
 *
 * Admin page: scroll chrome + 7-square intro (vanilla JS), optional legacy header/nav (hidden after intro).
 * Expects: $admin_page_title, $admin_menu_items, $admin_main_content. Optional: $admin_disable_scroll_intro.
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
$admin_active_actor_display = isset($admin_active_actor_display) ? $admin_active_actor_display : '';
if (!defined('LUPO_UI_PATH')) {
    define('LUPO_UI_PATH', LUPOPEDIA_PATH . '/lupo-includes/themes/default');
}
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';

/* Top nav squares (fixed set; Documentation labeled Q/A; includes Channels + Data) */
$admin_intro_nav_links = array(
    array('label' => 'Dashboard', 'href' => $base . '/admin.php'),
    array('label' => 'Q/A', 'href' => $base . '/admin.php?section=documentation'),
    array('label' => 'Data', 'href' => $base . '/admin.php?section=database'),
    array('label' => 'Channels', 'href' => $base . '/channels'),
    array('label' => 'Artifacts', 'href' => $base . '/admin.php?section=artifacts'),
    array('label' => 'Settings', 'href' => $base . '/admin.php?section=settings'),
    array('label' => 'Help', 'href' => $base . '/admin.php?section=help'),
);

$admin_nav_actor_label = '';
if ($isUserLoggedIn) {
    if ($admin_active_actor_display !== '') {
        $admin_nav_actor_label = $admin_active_actor_display;
    } else {
        foreach ($admin_actor_list as $a) {
            if ((int) $a['actor_id'] === $admin_active_actor_id) {
                $admin_nav_actor_label = (isset($a['name']) && $a['name'] !== '')
                    ? $a['name']
                    : (isset($a['actor_name']) ? $a['actor_name'] : '');
                break;
            }
        }
    }
    if ($admin_nav_actor_label === '') {
        $admin_nav_actor_label = 'Unknown';
    }
}
$admin_nav_actor_display = $admin_nav_actor_label;
if ($admin_nav_actor_label !== '') {
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($admin_nav_actor_label, 'UTF-8') > 15) {
            $admin_nav_actor_display = mb_substr($admin_nav_actor_label, 0, 15, 'UTF-8') . '..';
        }
    } else {
        if (strlen($admin_nav_actor_label) > 15) {
            $admin_nav_actor_display = substr($admin_nav_actor_label, 0, 15) . '..';
        }
    }
}
$admin_select_actor_redirect = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ($base . '/admin.php');
$admin_select_actor_href = LUPOPEDIA_PUBLIC_PATH . '/select-actor.php?redirect=' . rawurlencode($admin_select_actor_redirect);

if (!isset($admin_disable_scroll_intro)) {
    $admin_disable_scroll_intro = false;
}
$admin_disable_scroll_intro = (bool) $admin_disable_scroll_intro;
if (!isset($admin_nav_logo_src) || $admin_nav_logo_src === '') {
    $admin_nav_logo_src = LUPOPEDIA_PUBLIC_PATH . '/lupo-images/logoface.png';
}
if (!isset($admin_nav_logo_href) || $admin_nav_logo_href === '') {
    $admin_nav_logo_href = LUPOPEDIA_PUBLIC_PATH . '/index.php';
}
$admin_nav_logo_alt = isset($admin_nav_logo_alt) ? $admin_nav_logo_alt : 'Lupopedia';
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
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/admin-intro-scroll.css">
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
<body>
<div id="lupo-admin-app" class="admin-wrap lupo-admin-app" data-admin-intro="<?= $admin_disable_scroll_intro ? '0' : '1' ?>">

    <div id="lupo-admin-scroll-shell">
        <nav class="lupo-admin-nav-row" id="lupo-admin-nav-row" role="navigation" aria-label="Admin quick nav">
            <div class="lupo-admin-nav-lead">
                <a class="lupo-admin-nav-logo" href="<?= htmlspecialchars($admin_nav_logo_href, ENT_QUOTES, 'UTF-8') ?>" title="<?= htmlspecialchars($admin_nav_logo_alt, ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($admin_nav_logo_src, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($admin_nav_logo_alt, ENT_QUOTES, 'UTF-8') ?>">
                </a>
            </div>
            <div class="lupo-admin-nav-squares">
            <?php foreach ($admin_intro_nav_links as $idx => $navItem): ?>
                <?php
                $ph = !empty($navItem['placeholder']);
                $nh = $ph ? '#' : htmlspecialchars($navItem['href']);
                $nl = $ph ? '·' : htmlspecialchars($navItem['label']);
                ?>
            <a class="lupo-admin-nav-sq<?= $ph ? ' is-placeholder' : '' ?>" href="<?= $nh ?>"<?= $ph ? ' onclick="return false;" aria-hidden="true"' : '' ?>><span><?= $nl ?></span></a>
            <?php endforeach; ?>
            </div>
            <div class="lupo-admin-nav-tail">
                <?php if ($isUserLoggedIn): ?>
                <div class="lupo-admin-nav-actor" id="lupo-admin-nav-actor">
                    <span class="lupo-admin-nav-actor-text" title="<?= htmlspecialchars($admin_nav_actor_label, ENT_QUOTES, 'UTF-8') ?>"><strong><?= htmlspecialchars($admin_nav_actor_display) ?></strong></span>
                    <?php if (count($admin_actor_list) > 1): ?>
                    <a class="lupo-admin-nav-actor-link" href="<?= htmlspecialchars($admin_select_actor_href, ENT_QUOTES, 'UTF-8') ?>">Change</a>
                    <?php endif; ?>
                    <a class="lupo-admin-nav-actor-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/logout.php">Log out</a>
                </div>
                <?php endif; ?>
            </div>
        </nav>
        <div class="lupo-admin-scroll-frame">
            <div class="lupo-admin-scroll-row lupo-admin-scroll-row--top" aria-hidden="true">
                <div class="lupo-admin-scroll-cap lupo-admin-scroll-cap--l"></div>
                <div class="lupo-admin-scroll-center"></div>
                <div class="lupo-admin-scroll-cap lupo-admin-scroll-cap--r"></div>
            </div>
            <div class="lupo-admin-scroll-row lupo-admin-scroll-row--mid">
                <div class="lupo-admin-scroll-cap lupo-admin-scroll-cap--l"></div>
                <div class="lupo-admin-scroll-center">
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
                </div>
                <div class="lupo-admin-scroll-cap lupo-admin-scroll-cap--r"></div>
            </div>
            <div class="lupo-admin-scroll-row lupo-admin-scroll-row--bottom" aria-hidden="true">
                <div class="lupo-admin-scroll-cap lupo-admin-scroll-cap--l"></div>
                <div class="lupo-admin-scroll-center"></div>
                <div class="lupo-admin-scroll-cap lupo-admin-scroll-cap--r"></div>
            </div>
        </div>
    </div>

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
                <?php
                $current_actor = null;
                foreach ($admin_actor_list as $a) {
                    if ((int) $a['actor_id'] === $admin_active_actor_id) {
                        $current_actor = $a;
                        break;
                    }
                }
                $acting_as_label = 'Unknown';
                if ($current_actor) {
                    $acting_as_label = (isset($current_actor['name']) && $current_actor['name'] !== '')
                        ? $current_actor['name']
                        : (isset($current_actor['actor_name']) ? $current_actor['actor_name'] : 'Unknown');
                } elseif ($admin_active_actor_display !== '') {
                    $acting_as_label = $admin_active_actor_display;
                }
                ?>
                <div style="display: inline-flex; align-items: center; margin: 0 0 0 1rem; color: #a0aec0; font-size: 0.875rem;">
                    <span>Acting as: <strong><?= htmlspecialchars($acting_as_label) ?></strong></span>
                    <?php if (count($admin_actor_list) > 1): ?>
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/select-actor.php?redirect=<?= urlencode(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $base . '/admin.php') ?>" style="margin-left: 8px; color: #4299e1; text-decoration: none; font-size: 0.8rem;">change</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($isUserLoggedIn): ?>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/my-profile" style="margin-left: auto;">My Profile</a>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/logout.php">Sign Out</a>
            <?php else: ?>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/login" style="margin-left: auto;">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

    <footer class="basic-footer">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php">Lupopedia</a>
        &middot; Admin
    </footer>

    <div id="lupo-admin-intro-overlay" aria-hidden="true">
        <?php foreach ($admin_intro_nav_links as $navItem): ?>
            <?php
            $ph = !empty($navItem['placeholder']);
            $oh = $ph ? '#' : htmlspecialchars($navItem['href']);
            $ol = $ph ? '·' : htmlspecialchars($navItem['label']);
            ?>
        <a class="lupo-admin-intro-sq<?= $ph ? ' is-placeholder' : '' ?>" href="<?= $oh ?>"<?= $ph ? ' onclick="return false;" tabindex="-1"' : '' ?>><span><?= $ol ?></span></a>
        <?php endforeach; ?>
    </div>

    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/admin-intro-scroll.js"></script>
    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/lupopedia.js"></script>
</div>
</body>
</html>
