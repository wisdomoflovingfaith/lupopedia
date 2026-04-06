<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: layout
  when_updated: "20260406003830"
  file_path_from_root: "lupo-includes/themes/default/layouts/admin_layout.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/themes/default/layouts/admin_layout.php"
  last_modified_utc: "20260406003830"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "layout"
  artifact_kind: "admin"
  purpose: "Admin page layout with scroll chrome, 7-square intro, user dropdown, actor switcher; UNTRUSTED GET boundary for section."
  tags: ["admin", "layout", "ui", "locale"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. admin_layout.php cannot be called directly.");
}

$UNTRUSTED = array(
    'get' => (isset($_GET) && is_array($_GET)) ? $_GET : array(),
);

if (!class_exists('LupoLocale', false)) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/LupoLocale.php';
}
if (!function_exists('lupo_t')) {
    LupoLocale::bootstrap(LUPOPEDIA_PATH);
    require_once LUPOPEDIA_PATH . '/lupo-includes/lupo-i18n.php';
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

/* Top nav chips (order: Dashboard, Content, Q/A, Data, Channels, Settings, Help) */
$admin_intro_nav_links = array(
    array('label' => lupo_t('admin.nav.dashboard', 'Dashboard'), 'href' => $base . '/admin.php'),
    array('label' => lupo_t('admin.nav.content', 'Content'), 'href' => $base . '/admin.php?section=artifacts'),
    array('label' => lupo_t('admin.nav.qa', 'Q/A'), 'href' => $base . '/admin.php?section=documentation'),
    array('label' => lupo_t('admin.nav.data', 'Data'), 'href' => $base . '/admin.php?section=database'),
    array('label' => lupo_t('admin.nav.channels', 'Channels'), 'href' => $base . '/channels'),
    array('label' => lupo_t('admin.nav.settings', 'Settings'), 'href' => $base . '/admin.php?section=settings'),
    array('label' => lupo_t('admin.nav.help', 'Help'), 'href' => $base . '/admin.php?section=help'),
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
        $admin_nav_actor_label = lupo_t('admin.layout.unknown_actor', 'Unknown');
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
$lupo_admin_page_heading = lupo_t('admin.page.' . LupoLocale::slug($admin_page_title), $admin_page_title);

/* Top nav user menu (same dropdown pattern as channels / main topbar) */
$admin_nav_auth_user_id = 0;
$admin_nav_user_display = '';
$admin_nav_user_email = '';
if (isset($user) && is_array($user)) {
    if (!empty($user['auth_user_id'])) {
        $admin_nav_auth_user_id = (int) $user['auth_user_id'];
    }
    if (isset($user['display_name']) && $user['display_name'] !== '') {
        $admin_nav_user_display = $user['display_name'];
    } elseif (isset($user['username']) && $user['username'] !== '') {
        $admin_nav_user_display = $user['username'];
    }
    if (isset($user['email']) && $user['email'] !== '') {
        $admin_nav_user_email = $user['email'];
    }
}
$admin_nav_user_avatar = LUPOPEDIA_PUBLIC_PATH . '/lupo-images/logoface.png';
$admin_nav_avatar_ts = '';
if ($admin_nav_auth_user_id > 0) {
    $admin_nav_avatar_disk = LUPOPEDIA_PATH . '/lupo-uploads/avatars/' . $admin_nav_auth_user_id . '_avatar.jpg';
    if (file_exists($admin_nav_avatar_disk)) {
        $admin_nav_user_avatar = LUPOPEDIA_PUBLIC_PATH . '/lupo-uploads/avatars/' . $admin_nav_auth_user_id . '_avatar.jpg';
        $admin_nav_avatar_ts = '?' . gmdate('YmdHis');
    }
}

/* Parchment scroll tiles: s1a–s9a default; s1b–s9b when Content (section=artifacts) is open */
$admin_scroll_skin = 'a';
$admin_untrusted_section = '';
if (isset($UNTRUSTED['get']['section'])) {
    $us = $UNTRUSTED['get']['section'];
    if (is_string($us)) {
        $admin_untrusted_section = trim($us);
    }
}
if ($admin_untrusted_section === 'artifacts') {
    $admin_scroll_skin = 'b';
}
?>
<!DOCTYPE html>
<html lang="<?= LupoLocale::htmlLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lupo_admin_page_heading) ?> - <?= htmlspecialchars(lupo_t('admin.html_title_suffix', 'Admin - LUPOPEDIA')) ?></title>
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
        /* Parchment + leather (scroll body) — palette aligned with admin chrome */
        .admin-sidebar {
            width: 240px;
            min-width: 240px;
            background-color: #4e342e;
            color: #ede7f6;
            padding: 1.25rem 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.25);
            border-right: 2px solid #3e2723;
            border-top-right-radius: 28px;
            border-bottom-right-radius: 28px;
        }
        .admin-sidebar h2 {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(237, 231, 246, 0.65);
            padding: 0 1rem 0.5rem;
            margin: 0 0 0.5rem 0;
            border-bottom: 1px solid rgba(78, 52, 46, 0.35);
        }
        .admin-sidebar nav { padding: 0; }
        .admin-sidebar a {
            display: block;
            padding: 10px 1rem;
            color: #f5f5f0;
            text-decoration: none;
            font-size: 0.95rem;
            transition: background 0.2s ease, color 0.2s ease;
            border-bottom: 1px solid rgba(62, 39, 35, 0.35);
        }
        .admin-sidebar a:hover {
            background-color: #6d4c41;
            color: #ffffff;
        }
        .admin-sidebar a.active {
            background-color: #6d4c41;
            color: #ffffff;
            font-weight: 600;
        }
        .admin-main {
            flex: 1;
            overflow: auto;
            padding: 20px 24px 28px;
            background: transparent;
        }
        .admin-main h1 {
            margin-top: 0;
            font-size: 1.65rem;
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-weight: 600;
            color: #2e1a16;
            letter-spacing: 0.02em;
        }
        .admin-main .admin-placeholder {
            background-color: #f5f5dc;
            border-radius: 15px;
            padding: 28px 32px;
            box-shadow: inset 0 0 14px rgba(0, 0, 0, 0.06);
            color: #2e1a16;
        }
        .admin-main .admin-placeholder code {
            background: rgba(62, 39, 35, 0.08);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
            color: #3e2723;
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
        .admin-main .admin-link {
            color: #5d4037;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px solid rgba(93, 64, 55, 0.35);
        }
        .admin-main .admin-link:hover {
            color: #3e2723;
            border-bottom-color: #3e2723;
        }
        .admin-link { color: #2b6cb0; text-decoration: none; }
        .admin-link:hover { text-decoration: underline; }
        .admin-muted { color: #6d4c41; font-size: 0.9rem; opacity: 0.9; }
        .admin-users-edit-profile, .admin-users-edit-permissions { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08); max-width: 480px; }
        .admin-users-edit-profile h2, .admin-users-edit-permissions h2 { margin-top: 0; font-size: 1.25rem; }
        .admin-users-meta { color: #718096; font-size: 0.9rem; margin-bottom: 1rem; }
        .admin-input { width: 100%; max-width: 320px; padding: 8px 10px; border: 1px solid #cbd5e0; border-radius: 4px; font-size: 1rem; }
        .admin-hint { color: #718096; font-size: 0.85rem; margin-top: 4px; }
        .admin-btn { display: inline-block; padding: 8px 16px; border-radius: 6px; font-size: 0.95rem; text-decoration: none; cursor: pointer; border: 1px solid #cbd5e0; background: #fff; color: #2d3748; margin-right: 8px; }
        .admin-btn-primary { background: #2b6cb0; color: #fff; border-color: #2b6cb0; }
        .admin-btn:hover { opacity: 0.9; }
        .admin-empty { color: #6d4c41; font-style: italic; }
        .admin-placeholder-text { color: #2e1a16; margin: 0; }
        .admin-section-description { color: #2e1a16; line-height: 1.55; }
        .admin-data-hub { max-width: 100%; }
        .admin-data-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin: 1rem 0 1.25rem;
            align-items: center;
        }
        .admin-data-tab {
            display: inline-block;
            padding: 0.45rem 0.95rem;
            border-radius: 6px;
            border: 1px solid rgba(78, 52, 46, 0.35);
            background: rgba(255, 248, 235, 0.9);
            color: #3e2723;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .admin-data-tab:hover { background: rgba(121, 85, 72, 0.18); color: #1b100c; }
        .admin-data-tab.is-active {
            background: #4e342e;
            color: #fffef7;
            border-color: #3e2723;
            font-weight: 600;
        }
        .admin-data-panel { margin-top: 0.25rem; }
        .admin-data-panel-title { font-size: 1.1rem; margin: 0 0 0.75rem; color: #3e2723; }
        .admin-data-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .admin-data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
            background: rgba(255, 252, 245, 0.96);
            border: 1px solid rgba(78, 52, 46, 0.22);
        }
        .admin-data-table th,
        .admin-data-table td {
            padding: 6px 9px;
            text-align: left;
            border-bottom: 1px solid rgba(78, 52, 46, 0.12);
            vertical-align: top;
        }
        .admin-data-table th {
            background: rgba(121, 85, 72, 0.12);
            font-weight: 600;
            color: #3e2723;
            white-space: nowrap;
        }
        .admin-data-paths-form {
            margin: 0.75rem 0 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem 1.25rem;
            align-items: center;
        }
        .admin-data-paths-label span { margin-right: 0.35rem; color: #3e2723; }
        .admin-data-paths-from { margin: 0.5rem 0 0.75rem; line-height: 1.45; color: #2e1a16; }
        .admin-data-paths-back { margin: 0 0 0.75rem; }
        .admin-data-paths-bar {
            display: inline-block;
            width: 72px;
            max-width: 100%;
            height: 10px;
            background: rgba(78, 52, 46, 0.12);
            border-radius: 4px;
            overflow: hidden;
            vertical-align: middle;
        }
        .admin-data-paths-bar-fill {
            display: block;
            height: 100%;
            background: #6d4c41;
            border-radius: 4px;
            min-width: 0;
        }
        .admin-data-paths-note { font-size: 0.88rem; margin-top: 1rem; }
        .admin-dashboard-links {
            list-style: disc;
            margin: 1rem 0;
            padding-left: 1.5rem;
            color: #2e1a16;
        }
        .admin-dashboard-links li {
            margin: 0;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(78, 52, 46, 0.2);
        }
        .admin-dashboard-links li:last-child { border-bottom: none; }
        .admin-metadata-staleness {
            margin-top: 1.5em;
            padding: 1em 1.25em;
            border: 1px solid #a1887f;
            border-radius: 10px;
            background-color: rgba(121, 85, 72, 0.12);
            color: #2e1a16;
        }
        .admin-metadata-staleness h3 {
            margin-top: 0;
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-size: 1.1rem;
            color: #3e2723;
            font-weight: 600;
        }
        .admin-metadata-staleness h3 small {
            font-weight: normal;
            font-size: 0.85em;
            color: #5d4037;
        }
        .admin-staleness-ok { color: #1b5e20; margin: 0.5rem 0 0; }
        .admin-staleness-warn { color: #8d2f0f; margin: 0.5rem 0 0; }
        .admin-staleness-table { width: 100%; border-collapse: collapse; font-size: 0.9em; margin-top: 0.75rem; }
        .admin-staleness-table thead tr { background: rgba(121, 85, 72, 0.15); }
        .admin-staleness-table th { padding: 6px 10px; text-align: left; color: #3e2723; border-bottom: 1px solid rgba(78, 52, 46, 0.25); }
        .admin-staleness-table td { padding: 6px 10px; border-top: 1px solid rgba(78, 52, 46, 0.15); }
        .admin-staleness-table .admin-staleness-bad { color: #8d2f0f; }
        .admin-section-info .admin-section-description { margin: 0 0 1rem 0; color: #2e1a16; }
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
        /* Admin top bar: compact user-dropdown (matches main topbar / channels) */
        .lupo-admin-nav-tail {
            min-width: 0;
        }
        .lupo-admin-nav-user.nav-user {
            margin: 0;
        }
        .lupo-admin-nav-user .user-profile-btn {
            padding: 2px 8px;
            gap: 6px;
            max-height: 56px;
            font-size: 0.75rem;
        }
        .lupo-admin-nav-user .user-avatar {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
        }
        .lupo-admin-nav-user .user-name.lupo-admin-nav-trigger-label {
            max-width: 10em;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 600;
        }
        .lupo-admin-nav-user .dropdown-arrow {
            flex-shrink: 0;
        }
        .lupo-admin-nav-user .user-dropdown-menu {
            z-index: 200010;
        }
        .lupo-admin-nav-user .lupo-admin-dropdown-actor-line {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 6px;
            line-height: 1.3;
        }
    </style>
</head>
<body>
<div id="lupo-admin-app" class="admin-wrap lupo-admin-app" data-admin-intro="<?= $admin_disable_scroll_intro ? '0' : '1' ?>" data-admin-scroll-skin="<?= htmlspecialchars($admin_scroll_skin, ENT_QUOTES, 'UTF-8') ?>">

    <div id="lupo-admin-scroll-shell">
        <nav class="lupo-admin-nav-row" id="lupo-admin-nav-row" role="navigation" aria-label="<?= htmlspecialchars(lupo_t('admin.layout.nav_aria', 'Admin quick nav')) ?>">
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
                <div class="nav-user lupo-admin-nav-user" id="lupoAdminNavUserDropdown">
                    <div class="user-dropdown">
                        <button type="button" class="user-profile-btn lupo-admin-user-profile-btn" onclick="toggleLupoAdminUserDropdown(event)" aria-haspopup="true" aria-expanded="false" aria-controls="lupoAdminUserDropdownMenu" id="lupoAdminUserDropdownBtn">
                            <div class="user-avatar">
                                <img src="<?= htmlspecialchars($admin_nav_user_avatar . $admin_nav_avatar_ts, ENT_QUOTES, 'UTF-8') ?>"
                                     alt=""
                                     style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            </div>
                            <span class="user-name lupo-admin-nav-trigger-label" title="<?= htmlspecialchars($admin_nav_actor_label, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($admin_nav_actor_display) ?></span>
                            <span class="dropdown-arrow" aria-hidden="true">▼</span>
                        </button>
                        <div class="user-dropdown-menu" id="lupoAdminUserDropdownMenu" role="menu" aria-labelledby="lupoAdminUserDropdownBtn">
                            <div class="dropdown-header">
                                <div class="user-info">
                                    <div class="user-avatar-large">
                                        <img src="<?= htmlspecialchars($admin_nav_user_avatar . $admin_nav_avatar_ts, ENT_QUOTES, 'UTF-8') ?>"
                                             alt=""
                                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name-large"><?= htmlspecialchars($admin_nav_user_display !== '' ? $admin_nav_user_display : $admin_nav_actor_display) ?></div>
                                        <?php if ($admin_nav_user_email !== ''): ?>
                                        <div class="user-email"><?= htmlspecialchars($admin_nav_user_email) ?></div>
                                        <?php endif; ?>
                                        <div class="lupo-admin-dropdown-actor-line"><?= htmlspecialchars(lupo_t('admin.layout.acting_as', 'Acting as:')) ?> <strong><?= htmlspecialchars($admin_nav_actor_label) ?></strong></div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <?php if (count($admin_actor_list) > 1): ?>
                            <a href="<?= htmlspecialchars($admin_select_actor_href, ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item" role="menuitem">
                                <span class="dropdown-icon" aria-hidden="true">👤</span>
                                <?= htmlspecialchars(lupo_t('admin.layout.menu_change_actor', 'Change actor')) ?>
                            </a>
                            <?php endif; ?>
                            <a href="<?= htmlspecialchars(LUPOPEDIA_PUBLIC_PATH . '/my-profile', ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item" role="menuitem">
                                <span class="dropdown-icon" aria-hidden="true">✎</span>
                                <?= htmlspecialchars(lupo_t('admin.layout.my_profile', 'My Profile')) ?>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= htmlspecialchars(LUPOPEDIA_PUBLIC_PATH . '/logout.php', ENT_QUOTES, 'UTF-8') ?>" class="dropdown-item logout-item" role="menuitem">
                                <span class="dropdown-icon" aria-hidden="true">🚪</span>
                                <?= htmlspecialchars(lupo_t('admin.layout.log_out', 'Log out')) ?>
                            </a>
                        </div>
                    </div>
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
        <aside class="admin-sidebar" role="navigation" aria-label="<?= htmlspecialchars(lupo_t('admin.layout.sidebar_aria', 'Admin options')) ?>">
            <?php
            if (!empty($admin_menu_sections) && is_array($admin_menu_sections)) {
                foreach ($admin_menu_sections as $group) {
                    $groupTitle = isset($group['title']) ? $group['title'] : '';
                    $items = isset($group['items']) && is_array($group['items']) ? $group['items'] : array();
                    if ($groupTitle !== '' || !empty($items)) {
                        $groupDisplay = ($groupTitle !== '')
                            ? lupo_t('admin.grp.' . LupoLocale::slug($groupTitle), $groupTitle)
                            : '';
                        ?>
            <h2><?= htmlspecialchars($groupDisplay) ?></h2>
            <nav>
                        <?php foreach ($items as $label => $url): ?>
                    <?php
                    $href = (strpos($url, 'http') === 0 || strpos($url, '/') === 0) ? $url : $base . '/' . ltrim($url, '/');
                    $active = ($label === $admin_active_key) ? ' active' : '';
                    $itemDisplay = lupo_t('admin.itm.' . LupoLocale::slug($label), $label);
                    ?>
                    <a href="<?= htmlspecialchars($href) ?>" class="<?= $active ?>"><?= htmlspecialchars($itemDisplay) ?></a>
                        <?php endforeach; ?>
            </nav>
                        <?php
                    }
                }
            } else {
                $fallback = isset($admin_menu_items) && is_array($admin_menu_items) ? $admin_menu_items : array();
                ?>
            <h2><?= htmlspecialchars(lupo_t('admin.layout.fallback_sidebar_heading', 'Admin')) ?></h2>
            <nav>
                <?php foreach ($fallback as $label => $url): ?>
                    <?php
                    $href = (strpos($url, 'http') === 0 || strpos($url, '/') === 0) ? $url : $base . '/' . ltrim($url, '/');
                    $active = ($label === $admin_active_key) ? ' active' : '';
                    $itemDisplay = lupo_t('admin.itm.' . LupoLocale::slug($label), $label);
                    ?>
                    <a href="<?= htmlspecialchars($href) ?>" class="<?= $active ?>"><?= htmlspecialchars($itemDisplay) ?></a>
                <?php endforeach; ?>
            </nav>
            <?php
            }
            ?>
        </aside>
        <main class="admin-main" id="admin-main">
            <h1><?= htmlspecialchars($lupo_admin_page_heading) ?></h1>
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
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php" title="<?= htmlspecialchars(lupo_t('admin.layout.site_home_title', 'Lupopedia Home')) ?>">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/logoface.png" alt="" width="64" height="64">
            <span class="site-name">LUPOPEDIA</span>
        </a>
    </header>

    <nav class="basic-nav" role="navigation" aria-label="<?= htmlspecialchars(lupo_t('admin.layout.main_nav_aria', 'Main')) ?>">
        <div class="nav-inner">
            <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php"><?= htmlspecialchars(lupo_t('admin.layout.nav_home', 'Home')) ?></a>
            <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/qa/"><?= htmlspecialchars(lupo_t('admin.layout.nav_qa', 'Q/A')) ?></a>
            <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/search.php"><?= htmlspecialchars(lupo_t('admin.layout.nav_content', 'Content')) ?></a>
            <a class="nav-link active" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/admin.php"><?= htmlspecialchars(lupo_t('admin.layout.nav_admin', 'Admin')) ?></a>
            <?php if ($isUserLoggedIn && !empty($admin_actor_list)): ?>
                <?php
                $current_actor = null;
                foreach ($admin_actor_list as $a) {
                    if ((int) $a['actor_id'] === $admin_active_actor_id) {
                        $current_actor = $a;
                        break;
                    }
                }
                $acting_as_label = lupo_t('admin.layout.unknown_actor', 'Unknown');
                if ($current_actor) {
                    $acting_as_label = (isset($current_actor['name']) && $current_actor['name'] !== '')
                        ? $current_actor['name']
                        : (isset($current_actor['actor_name']) ? $current_actor['actor_name'] : 'Unknown');
                } elseif ($admin_active_actor_display !== '') {
                    $acting_as_label = $admin_active_actor_display;
                }
                ?>
                <div style="display: inline-flex; align-items: center; margin: 0 0 0 1rem; color: #a0aec0; font-size: 0.875rem;">
                    <span><?= htmlspecialchars(lupo_t('admin.layout.acting_as', 'Acting as:')) ?> <strong><?= htmlspecialchars($acting_as_label) ?></strong></span>
                    <?php if (count($admin_actor_list) > 1): ?>
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/select-actor.php?redirect=<?= urlencode(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $base . '/admin.php') ?>" style="margin-left: 8px; color: #4299e1; text-decoration: none; font-size: 0.8rem;"><?= htmlspecialchars(lupo_t('admin.layout.change_lower', 'change')) ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($isUserLoggedIn): ?>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/my-profile" style="margin-left: auto;"><?= htmlspecialchars(lupo_t('admin.layout.my_profile', 'My Profile')) ?></a>
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/logout.php"><?= htmlspecialchars(lupo_t('admin.layout.sign_out', 'Sign Out')) ?></a>
            <?php else: ?>
                <a class="nav-link" href="<?= htmlspecialchars(function_exists('lupo_login_url') ? lupo_login_url() : (rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/login.php')) ?>" style="margin-left: auto;"><?= htmlspecialchars(lupo_t('admin.layout.sign_in', 'Sign In')) ?></a>
            <?php endif; ?>
        </div>
    </nav>

    <footer class="basic-footer">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php">Lupopedia</a>
        &middot; <?= htmlspecialchars(lupo_t('admin.layout.footer_admin', 'Admin')) ?>
        <form method="get" action="" class="lupo-admin-lang-form" style="display: inline-block; margin-left: 12px;">
            <label for="lupo_admin_footer_locale" style="color: #a0aec0; font-size: 0.85rem; margin-right: 6px;"><?= htmlspecialchars(lupo_t('login.language', 'Language')) ?></label>
            <select name="lupo_locale" id="lupo_admin_footer_locale" onchange="this.form.submit()" style="font-size: 0.85rem;">
                <?php foreach (LupoLocale::allowedLocales() as $lupoLocCode): ?>
                <option value="<?= htmlspecialchars($lupoLocCode) ?>"<?= (LupoLocale::getLocale() === $lupoLocCode) ? ' selected' : ''; ?>><?= htmlspecialchars($lupoLocCode === 'en' ? 'English' : $lupoLocCode) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
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
    <script>
    function toggleLupoAdminUserDropdown(evt) {
        if (evt && evt.stopPropagation) {
            evt.stopPropagation();
        }
        var menu = document.getElementById('lupoAdminUserDropdownMenu');
        var btn = document.getElementById('lupoAdminUserDropdownBtn');
        if (menu) {
            menu.classList.toggle('show');
        }
        if (btn) {
            btn.setAttribute('aria-expanded', menu && menu.classList.contains('show') ? 'true' : 'false');
        }
    }
    document.addEventListener('click', function (event) {
        var wrap = document.getElementById('lupoAdminNavUserDropdown');
        if (!wrap || wrap.contains(event.target)) {
            return;
        }
        var menu = document.getElementById('lupoAdminUserDropdownMenu');
        var btn = document.getElementById('lupoAdminUserDropdownBtn');
        if (menu) {
            menu.classList.remove('show');
        }
        if (btn) {
            btn.setAttribute('aria-expanded', 'false');
        }
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            var menu = document.getElementById('lupoAdminUserDropdownMenu');
            var btn = document.getElementById('lupoAdminUserDropdownBtn');
            if (menu) {
                menu.classList.remove('show');
            }
            if (btn) {
                btn.setAttribute('aria-expanded', 'false');
            }
        }
    });
    </script>
</div>
</body>
</html>
