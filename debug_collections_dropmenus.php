<?php
/**
 * debug_collections_dropmenus.php — isolated collections / shortcut / contents dropdowns for JS/CSS debugging.
 *
 * Collections strip uses try2 pattern (debug_collections_try2.htm): .lupo-dropdown + .dropdown-panel + .floating-submenu.
 * Scripts: LUPO_MAIN_LAYOUT, main-layout.js, main-layout-collections.js, navigation.js, plus inline lupoDbgNav* handlers.
 * Does not load: main.css, ui-loader stacks, book tile backgrounds, modals (Save/Load), or full 9-slice shell.
 *
 * Usage: /lupopedia/debug_collections_dropmenus.php  optional: ?collection_id=0
 *
 * Disable: create empty file bin/debug_collections_dropmenus.disable
 */

if (is_file(__DIR__ . '/bin/debug_collections_dropmenus.disable')) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    echo "debug_collections_dropmenus.php is disabled (bin/debug_collections_dropmenus.disable exists).\n";
    exit;
}

$config_paths = array(
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/lupopedia-config.php',
);

$config_loaded = false;
foreach ($config_paths as $config_path) {
    if (file_exists($config_path)) {
        require_once $config_path;
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain; charset=UTF-8');
    echo "lupopedia-config.php not found.\n";
    exit;
}

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}

$root = LUPOPEDIA_PATH;
if (!class_exists('LupoLocale', false)) {
    $lp = $root . '/includes/classes/LupoLocale.php';
    if (is_file($lp)) {
        require_once $lp;
    }
}
if (class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
    LupoLocale::bootstrap($root);
}
if (!function_exists('lupo_t')) {
    $i18n = $root . '/includes/i18n.php';
    if (is_file($i18n)) {
        require_once $i18n;
    }
}

if (file_exists(LUPOPEDIA_PATH . '/includes/bootstrap.php')) {
    require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';
}

if (file_exists(LUPOPEDIA_PATH . '/includes/functions/collection-tabs-loader.php')) {
    require_once LUPOPEDIA_PATH . '/includes/functions/collection-tabs-loader.php';
}

if (file_exists(LUPOPEDIA_PATH . '/includes/functions/render-saved-collections.php')) {
    require_once LUPOPEDIA_PATH . '/includes/functions/render-saved-collections.php';
}

if (!defined('LUPO_UI_PATH')) {
    $theme_default = rtrim(LUPOPEDIA_PATH, "/\\") . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'default';
    if (is_dir($theme_default)) {
        define('LUPO_UI_PATH', $theme_default);
    }
}

function dbg_cd_h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

$collection_id = 0;
if (isset($_GET['collection_id']) && is_numeric($_GET['collection_id'])) {
    $collection_id = (int) $_GET['collection_id'];
}
$GLOBALS['collection_id'] = $collection_id;

$isUserLoggedIn = false;
$currentUserId = 0;
if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
    $aid = $GLOBALS['lupo_session']->getActorId();
    $currentUserId = ($aid !== null) ? (int) $aid : 0;
    $isUserLoggedIn = ($currentUserId > 0);
}

$lupo_collections_auth_user_id = 0;
$lupo_auth_svc = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($lupo_auth_svc !== null && method_exists($lupo_auth_svc, 'getCurrentUser')) {
    $lupo_cu = $lupo_auth_svc->getCurrentUser();
    if (is_array($lupo_cu) && isset($lupo_cu['auth_user_id']) && (int) $lupo_cu['auth_user_id'] > 0) {
        $lupo_collections_auth_user_id = (int) $lupo_cu['auth_user_id'];
    }
}

$collectionsData = array();
if (function_exists('render_saved_collections')) {
    $collectionsData = render_saved_collections($lupo_collections_auth_user_id);
}

$tabs_data = array();
if (function_exists('load_collection_tabs')) {
    try {
        $tabs_data = load_collection_tabs($collection_id);
    } catch (Exception $e) {
        $tabs_data = array();
    }
}

$current_collection = function_exists('lupo_t') ? lupo_t('layout.main_layout.system_collection', 'System Collection') : 'System Collection';
if (function_exists('get_collection_name')) {
    try {
        $cn = get_collection_name($collection_id);
        if ($cn !== null && $cn !== '') {
            $current_collection = $cn;
        }
    } catch (Exception $e) {
        // keep default
    }
}

$page_title = 'debug_collections_dropmenus';
$page_content_id = 0;
$contentSections = array(
    array('anchor' => 'demo_section_one', 'title' => 'Demo section one'),
    array('anchor' => 'demo_section_two', 'title' => 'Demo section two'),
);

$public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';

$lupo_try2_funcs_dbg = LUPOPEDIA_PATH . '/includes/themes/default/components/saved-collections-nav-try2-functions.php';
if (is_file($lupo_try2_funcs_dbg)) {
    require_once $lupo_try2_funcs_dbg;
}

$lupoHdrJs = array(
    'base' => $public_path,
    'strings' => array(),
);

$all_user_collections = (!empty($collectionsData) && is_array($collectionsData)) ? $collectionsData : array();
$lupo_master_collections = array('count' => 0, 'tabs' => array());
if (isset($all_user_collections['collections']) && is_array($all_user_collections['collections'])) {
    $lupo_master_collections = $all_user_collections['collections'];
} elseif (!empty($all_user_collections)) {
    foreach ($all_user_collections as $lupo_cd) {
        if (!is_array($lupo_cd)) {
            continue;
        }
        $lupo_master_collections['count'] += isset($lupo_cd['count']) ? (int) $lupo_cd['count'] : 0;
        if (!empty($lupo_cd['tabs']) && is_array($lupo_cd['tabs'])) {
            $lupo_master_collections['tabs'] = array_merge($lupo_master_collections['tabs'], $lupo_cd['tabs']);
        }
    }
}
$lupo_master_count = isset($lupo_master_collections['count']) ? (int) $lupo_master_collections['count'] : 0;

/**
 * Try2-style nav: render nested collection tabs (same shape as render_tab_item children).
 *
 * @param array $children
 * @param string $public_path
 * @return void
 */
function dbg_try2_render_tab_children_links($children, $public_path)
{
    if (!is_array($children)) {
        return;
    }
    foreach ($children as $child) {
        if (!is_array($child)) {
            continue;
        }
        $itype = isset($child['item_type']) ? $child['item_type'] : '';
        if ($itype === 'tab') {
            $sub = isset($child['children']) ? $child['children'] : array();
            $label = isset($child['tab_name']) ? $child['tab_name'] : '';
            if ($label === '') {
                continue;
            }
            echo '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
            echo '<span class="menu-item-label">' . dbg_cd_h($label) . '</span>';
            echo '<div class="floating-submenu">';
            dbg_try2_render_tab_children_links($sub, $public_path);
            echo '</div></div>';
        } elseif ($itype === 'content') {
            $cid = isset($child['content_id']) ? (int) $child['content_id'] : (isset($child['item_id']) ? (int) $child['item_id'] : 0);
            $title = isset($child['title']) ? $child['title'] : 'Content';
            $cslug = isset($child['slug']) && is_string($child['slug']) ? trim($child['slug']) : '';
            if ($cslug !== '' && function_exists('lupo_try2_index_content_query_href')) {
                $at = (isset($child['content_artifact_type']) && is_string($child['content_artifact_type']) && $child['content_artifact_type'] !== '')
                    ? $child['content_artifact_type'] : null;
                $mk = (isset($child['content_memory_key']) && is_string($child['content_memory_key']) && $child['content_memory_key'] !== '')
                    ? $child['content_memory_key'] : null;
                $url = lupo_try2_index_content_query_href($public_path, $cslug, $at, $mk);
            } else {
                $url = $public_path . '/content.php?id=' . $cid;
            }
            echo '<a class="menu-item" href="' . dbg_cd_h($url) . '">' . dbg_cd_h($title) . '</a>';
        } elseif ($itype === 'link') {
            $url = isset($child['url']) ? $child['url'] : '#';
            $label = isset($child['label']) ? $child['label'] : 'Link';
            echo '<a class="menu-item" href="' . dbg_cd_h($url) . '">' . dbg_cd_h($label) . '</a>';
        }
    }
}

/**
 * @param array $tabs
 * @param string $public_path
 * @return void
 */
function dbg_try2_render_tabs_for_type($tabs, $public_path)
{
    if (empty($tabs) || !is_array($tabs)) {
        return;
    }
    foreach ($tabs as $tab) {
        if (!is_array($tab) || empty($tab['tab_name'])) {
            continue;
        }
        $name = $tab['tab_name'];
        $hasChildren = !empty($tab['children']);
        if ($hasChildren) {
            echo '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
            echo '<span class="menu-item-label">' . dbg_cd_h($name) . '</span>';
            echo '<div class="floating-submenu">';
            dbg_try2_render_tab_children_links($tab['children'], $public_path);
            echo '</div></div>';
        } else {
            echo '<span class="menu-item">' . dbg_cd_h($name) . '</span>';
        }
    }
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="<?php echo class_exists('LupoLocale', false) ? LupoLocale::htmlLang() : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo dbg_cd_h($page_title); ?></title>
    <script>
    (function () {
        var incoming = <?php echo json_encode($lupoHdrJs); ?>;
        window.LUPO_HDR = window.LUPO_HDR || { base: '', strings: {} };
        if (incoming && incoming.base && !window.LUPO_HDR.base) {
            window.LUPO_HDR.base = incoming.base;
        }
        if (incoming && incoming.strings) {
            var s = window.LUPO_HDR.strings;
            for (var k in incoming.strings) {
                if (incoming.strings.hasOwnProperty(k) && typeof s[k] === 'undefined') {
                    s[k] = incoming.strings[k];
                }
            }
        }
    })();
    </script>
    <link rel="stylesheet" href="<?php echo dbg_cd_h($public_path); ?>/includes/css/main-layout.css">
    <style>
        /* Detach fixed chrome from main_layout (no topbar); keep dropdown CSS from main-layout.css */
        body.debug-collections-dropmenus {
            margin: 0;
            font-family: system-ui, sans-serif;
            background: #fafafa;
            color: #212529;
        }
        body.debug-collections-dropmenus .saved-collections-nav {
            position: relative;
            top: auto;
            left: auto;
            right: auto;
            width: 100%;
            box-shadow: none;
            border-bottom: 2px solid #4CAF50;
        }
        .debug-cd-banner {
            background: #e7f3ff;
            border-bottom: 1px solid #b8daff;
            padding: 10px 16px;
            font-size: 13px;
        }
        .debug-cd-banner code { background: rgba(0,0,0,0.06); padding: 2px 6px; border-radius: 4px; }
        .debug-book-strip {
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 16px;
            background: #eee;
            border-bottom: 1px solid #ccc;
        }
        .debug-book-strip .dropdown { position: relative; display: inline-block; }
        .debug-outside {
            padding: 24px 16px;
            max-width: 720px;
        }
        .debug-outside h2 { font-size: 1rem; margin-top: 0; }

        /* Try2 menu system (aligned with debug_collections_try2.htm), scoped to .debug-try2-nav */
        .debug-try2-nav .debug-try2-inner {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
            min-height: 48px;
        }
        .debug-try2-nav .lupo-dropdown {
            position: relative;
            display: inline-block;
        }
        .debug-try2-nav .dropdown-button {
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        .debug-try2-nav .btn-blue { background: #2973e4; border: 1px solid #1f5bb8; }
        .debug-try2-nav .btn-green { background: #4CAF50; }
        .debug-try2-nav .dropdown-panel {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
            z-index: 2000;
            padding: 6px 0;
        }
        .debug-try2-nav .lupo-dropdown.active > .dropdown-panel {
            display: block;
        }
        .debug-try2-nav .menu-item {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
            position: relative;
        }
        .debug-try2-nav a.menu-item:hover {
            background: #f5f5f5;
            color: #2973e4;
        }
        .debug-try2-nav .has-submenu::after {
            content: '\25B6';
            float: right;
            font-size: 10px;
            color: #999;
            margin-top: 2px;
        }
        .debug-try2-nav .count-badge {
            background: rgba(255,255,255,0.3);
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 11px;
        }
        /*
         * Flyout panels are moved to document.body — they do NOT inherit .debug-try2-nav .menu-item.
         * Mirror .dropdown-panel chrome for secondary (and nested) submenus.
         */
        .floating-submenu {
            position: fixed;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 220px;
            max-width: 360px;
            padding: 6px 0;
            display: none;
            z-index: 10006;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            box-sizing: border-box;
            -webkit-overflow-scrolling: touch;
        }
        .floating-submenu.active {
            display: block;
        }
        .floating-submenu .menu-item {
            display: block;
            padding: 10px 15px;
            margin: 0;
            color: #333;
            text-decoration: none;
            font-size: 13px;
            font-weight: normal;
            line-height: 1.35;
            cursor: pointer;
            position: relative;
            box-sizing: border-box;
            border-bottom: 1px solid #eee;
        }
        .floating-submenu .menu-item:last-child {
            border-bottom: none;
        }
        .floating-submenu a.menu-item,
        .floating-submenu a.menu-item:visited {
            color: #333;
        }
        .floating-submenu .menu-item-label {
            display: block;
            pointer-events: none;
        }
        .floating-submenu .has-submenu::after {
            content: '\25B6';
            float: right;
            font-size: 10px;
            color: #999;
            margin-top: 2px;
            pointer-events: none;
        }
        .floating-submenu a.menu-item:hover,
        .floating-submenu .menu-item:hover {
            background: #f0f7ff;
            color: #2973e4;
        }
        .floating-submenu a.menu-item:hover:visited {
            color: #2973e4;
        }
        /* Nested flyout content (depth > 1) */
        .floating-submenu .floating-submenu {
            margin-top: 0;
        }
    </style>
</head>
<body class="debug-collections-dropmenus">

<div class="debug-cd-banner">
    <strong>debug_collections_dropmenus.php</strong> —
    collection_id=<code><?php echo (int) $collection_id; ?></code>
    (try <code>?collection_id=1</code>) —
    actor_id=<code><?php echo (int) $currentUserId; ?></code> —
    auth_user_id for collections=<code><?php echo (int) $lupo_collections_auth_user_id; ?></code> —
    <code>LUPOPEDIA_PUBLIC_PATH</code>=<code><?php echo dbg_cd_h($public_path); ?></code>
    <br><small>Collections strip: <strong>try2</strong> (<code>.lupo-dropdown</code> / <code>lupoDbgNavToggle</code>). Book bar: main-layout + collections JS. See <code>debug_collections_try2.htm</code>.</small>
</div>

<script>
var isUserLoggedIn = <?php echo $isUserLoggedIn ? 'true' : 'false'; ?>;
</script>
<input type="hidden" id="active-collection-id" name="active_collection_id" value="<?php echo (int) $collection_id; ?>">
<input type="hidden" id="lupo-current-content-id" name="lupo_current_content_id" value="<?php echo (int) $page_content_id; ?>">

<nav class="saved-collections-nav debug-try2-nav" data-collection-id="<?php echo (int) $collection_id; ?>">
    <div style="width: 16px; height: 40px; flex-shrink: 0;"></div>
    <div class="saved-collections-container debug-try2-inner">
        <div class="lupo-dropdown lupo-master-collections-wrap" id="lupo-dbg-master" data-qa-type="collections">
            <button type="button"
                    class="dropdown-button btn-blue"
                    onclick="lupoDbgNavToggle(this, event)"
                    aria-expanded="false"
                    aria-haspopup="true"
                    aria-controls="lupo-dbg-master-panel"
                    data-qa-type="collections">
                <?php echo dbg_cd_h(function_exists('lupo_t') ? lupo_t('header.collections.dropdown_label', 'Collections') : 'Collections'); ?>
                <span class="count-badge"><?php echo (int) $lupo_master_count; ?></span>
            </button>
            <div class="dropdown-panel" id="lupo-dbg-master-panel" role="menu">
                <?php
                if (!empty($lupo_master_collections['tabs']) && is_array($lupo_master_collections['tabs'])) {
                    dbg_try2_render_tabs_for_type($lupo_master_collections['tabs'], $public_path);
                }
                ?>
            </div>
        </div>
        <?php
        $dbg_exclude_types = array();
        if (isset($all_user_collections['collections'])) {
            $dbg_exclude_types[] = 'collections';
        }
        if (!empty($collectionsData) && is_array($collectionsData)) {
            foreach ($collectionsData as $dbg_type => $dbg_collection_type_data) {
                if (in_array($dbg_type, $dbg_exclude_types, true)) {
                    continue;
                }
                $dbg_count = isset($dbg_collection_type_data['count']) ? (int) $dbg_collection_type_data['count'] : 0;
                $dbg_label = strtoupper((string) $dbg_type);
                if ($dbg_type === 'collections' && function_exists('lupo_t')) {
                    $dbg_label = lupo_t('header.collections.dropdown_label', 'Collections');
                }
                ?>
                <div class="lupo-dropdown" data-qa-type="<?php echo dbg_cd_h($dbg_type); ?>">
                    <button type="button" class="dropdown-button btn-green"
                            onclick="lupoDbgNavToggle(this, event)"
                            aria-expanded="false"
                            aria-haspopup="true"
                            data-qa-type="<?php echo dbg_cd_h($dbg_type); ?>">
                        <?php echo dbg_cd_h($dbg_label); ?> <span class="count-badge"><?php echo (int) $dbg_count; ?></span>
                    </button>
                    <div class="dropdown-panel" role="menu">
                        <?php
                        if (!empty($dbg_collection_type_data['tabs']) && is_array($dbg_collection_type_data['tabs'])) {
                            dbg_try2_render_tabs_for_type($dbg_collection_type_data['tabs'], $public_path);
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <div id="collection-tabs-container">
            <?php
            if ($collection_id !== null && !empty($tabs_data) && is_array($tabs_data)) {
                foreach ($tabs_data as $main_tab => $sub_tabs) {
                    $main_tab_label = is_string($main_tab) ? $main_tab : '';
                    if ($main_tab_label === '') {
                        continue;
                    }
                    $qa_type = strtolower(str_replace(' ', '-', $main_tab_label));
                    ?>
                    <div class="lupo-dropdown" data-qa-type="<?php echo dbg_cd_h($qa_type); ?>">
                        <button type="button" class="dropdown-button btn-green"
                                onclick="lupoDbgNavToggle(this, event)"
                                aria-expanded="false"
                                aria-haspopup="true"
                                data-qa-type="<?php echo dbg_cd_h($qa_type); ?>">
                            <?php echo dbg_cd_h(strtoupper($main_tab_label)); ?> <span class="count-badge"><?php
                            $childCount = 0;
                            if (is_array($sub_tabs)) {
                                if (!empty($sub_tabs['_children']) && is_array($sub_tabs['_children'])) {
                                    foreach ($sub_tabs['_children'] as $ch) {
                                        if (is_array($ch) && isset($ch['name']) && is_string($ch['name']) && $ch['name'] !== '') {
                                            $childCount++;
                                        }
                                    }
                                } else {
                                    foreach ($sub_tabs as $key => $value) {
                                        if (is_string($key) && strlen($key) > 0 && $key[0] === '_') {
                                            continue;
                                        }
                                        if (is_string($value)) {
                                            $childCount++;
                                        }
                                    }
                                }
                            }
                            echo (int) $childCount;
                            ?></span>
                        </button>
                        <div class="dropdown-panel" role="menu">
                            <?php
                            if (is_array($sub_tabs)) {
                                if (!empty($sub_tabs['_children']) && is_array($sub_tabs['_children'])) {
                                    foreach ($sub_tabs['_children'] as $child) {
                                        if (!is_array($child)) {
                                            continue;
                                        }
                                        $cname = isset($child['name']) && is_string($child['name']) ? $child['name'] : '';
                                        if ($cname === '') {
                                            continue;
                                        }
                                        if (isset($child['slug']) && is_string($child['slug']) && $child['slug'] !== '') {
                                            $sub_tab_slug = $child['slug'];
                                        } else {
                                            $sub_tab_slug = strtolower(str_replace(' ', '-', $cname));
                                        }
                                        $sub_at = (isset($child['content_artifact_type']) && is_string($child['content_artifact_type']) && $child['content_artifact_type'] !== '')
                                            ? $child['content_artifact_type'] : null;
                                        $sub_mk = (isset($child['content_memory_key']) && is_string($child['content_memory_key']) && $child['content_memory_key'] !== '')
                                            ? $child['content_memory_key'] : null;
                                        $sub_tab_url = function_exists('lupo_try2_index_content_query_href')
                                            ? lupo_try2_index_content_query_href($public_path, $sub_tab_slug, $sub_at, $sub_mk)
                                            : ($public_path . '/index.php?slug=' . rawurlencode($sub_tab_slug) . '&artifact_type=' . rawurlencode('text/markdown') . '&memory_key=' . rawurlencode('content:' . $sub_tab_slug));
                                        ?>
                                        <a href="<?php echo dbg_cd_h($sub_tab_url); ?>" class="menu-item" role="menuitem" tabindex="0"><?php echo dbg_cd_h($cname); ?></a>
                                        <?php
                                    }
                                } else {
                                    foreach ($sub_tabs as $key => $value) {
                                        if (is_string($key) && strlen($key) > 0 && $key[0] === '_') {
                                            continue;
                                        }
                                        if (!is_string($value)) {
                                            continue;
                                        }
                                        $sub_tab_slug = strtolower(str_replace(' ', '-', $value));
                                        $sub_tab_url = function_exists('lupo_try2_index_content_query_href')
                                            ? lupo_try2_index_content_query_href($public_path, $sub_tab_slug)
                                            : ($public_path . '/index.php?slug=' . rawurlencode($sub_tab_slug) . '&artifact_type=' . rawurlencode('text/markdown') . '&memory_key=' . rawurlencode('content:' . $sub_tab_slug));
                                        ?>
                                        <a href="<?php echo dbg_cd_h($sub_tab_url); ?>" class="menu-item" role="menuitem" tabindex="0"><?php echo dbg_cd_h($value); ?></a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</nav>

<div class="debug-book-strip">
    <div class="dropdown">
        <img src="<?php echo dbg_cd_h($public_path); ?>/images/addshortcut.png" width="42" height="42"
             onclick="lupoOpenShortcutDropdown(event)" style="cursor:pointer;"
             alt="Add shortcut">
        <div id="shortcutDropdown" class="dropdown-content">
            <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
                <b>Current collection:</b> <span id="current-collection-display"><?php echo dbg_cd_h($current_collection); ?></span><br>
                Shortcut menu (AJAX loads tabs when opened).
            </div>
            <div id="shortcut-tabs-list">
                <div id="shortcut-tabs-dynamic">
                    <?php if (!empty($tabs_data) && is_array($tabs_data)): ?>
                        <?php foreach ($tabs_data as $main_tab => $sub_tabs): ?>
                            <?php
                            $root_tab_id = (is_array($sub_tabs) && isset($sub_tabs['_collection_tab_id'])) ? (int) $sub_tabs['_collection_tab_id'] : 0;
                            ?>
                            <a href="javascript:void(0)" class="main-tab lupo-shortcut-pin" role="button" data-collection-tab-id="<?php echo (int) $root_tab_id; ?>" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">| <?php echo dbg_cd_h($main_tab); ?></a>
                            <?php if (is_array($sub_tabs)): ?>
                                <?php if (!empty($sub_tabs['_children']) && is_array($sub_tabs['_children'])): ?>
                                    <?php foreach ($sub_tabs['_children'] as $child): ?>
                                        <?php
                                        $cname = isset($child['name']) ? $child['name'] : '';
                                        $ctid = isset($child['collection_tab_id']) ? (int) $child['collection_tab_id'] : 0;
                                        if ($cname === '') {
                                            continue;
                                        }
                                        ?>
                                        <a href="javascript:void(0)" class="sub-tab lupo-shortcut-pin" role="button" data-collection-tab-id="<?php echo (int) $ctid; ?>" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">|— <?php echo dbg_cd_h($cname); ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <hr>
                <a href="javascript:void(0)" class="add-action global" onclick="addNewItem('main')">+ Create new main tab</a>
            </div>
        </div>
    </div>
    <div class="dropdown">
        <img src="<?php echo dbg_cd_h($public_path); ?>/images/contents.png" width="42" height="42"
             onclick="toggleMenu('contentsDropdown', event)" style="cursor:pointer;"
             alt="Contents">
        <div id="contentsDropdown" class="dropdown-content">
            <?php foreach ($contentSections as $section): ?>
                <?php
                $section_anchor = isset($section['anchor']) ? $section['anchor'] : '';
                $section_title = isset($section['title']) ? $section['title'] : '';
                if ($section_anchor !== ''):
                ?>
                    <a href="#<?php echo dbg_cd_h($section_anchor); ?>"><?php echo dbg_cd_h($section_title); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <span style="align-self: center; color: #666; font-size: 13px;">Shortcut + Contents (book bar)</span>
</div>

<div class="debug-outside" id="debug-outside-click-target">
    <h2>Outside-click target</h2>
    <p>Click this area to verify document-level listeners close all dropdowns.</p>
    <p id="demo_section_one"><strong>demo_section_one</strong> — contents menu anchor target.</p>
    <p id="demo_section_two"><strong>demo_section_two</strong> — contents menu anchor target.</p>
</div>

<script>
window.DEBUG_DROPDOWNS = true;
window.LUPO_MAIN_LAYOUT = <?php echo json_encode(array(
    'publicPath' => $public_path,
    'collectionId' => (int) $collection_id,
    'currentCollectionName' => $current_collection,
    'contentId' => (int) $page_content_id,
)); ?>;
</script>
<script src="<?php echo dbg_cd_h($public_path); ?>/includes/js/main-layout.js"></script>
<script src="<?php echo dbg_cd_h($public_path); ?>/includes/js/main-layout-collections.js"></script>
<script>
(function () {
    window.lupoDbgNavActiveSubmenu = null;

    function lupoDbgHtmlEsc(s) {
        var d = document.createElement('div');
        d.textContent = s === null || typeof s === 'undefined' ? '' : String(s);
        return d.innerHTML;
    }

    window.lupoDbgNavCloseAll = function () {
        var nav = document.querySelector('.debug-try2-nav');
        if (nav) {
            nav.querySelectorAll('.lupo-dropdown').forEach(function (el) {
                el.classList.remove('active');
            });
        }
        if (window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
            window.lupoDbgNavActiveSubmenu = null;
        }
    };

    window.lupoDbgNavToggle = function (btn, ev) {
        if (ev) {
            if (ev.stopImmediatePropagation) {
                ev.stopImmediatePropagation();
            } else if (ev.stopPropagation) {
                ev.stopPropagation();
            }
        }
        var dropdown = btn.closest('.lupo-dropdown');
        if (!dropdown) {
            return;
        }
        var wasActive = dropdown.classList.contains('active');
        document.querySelectorAll('.debug-try2-nav .dropdown-button').forEach(function (b) {
            b.setAttribute('aria-expanded', 'false');
        });
        document.querySelectorAll('.debug-try2-nav .lupo-dropdown').forEach(function (d) {
            d.classList.remove('active');
        });
        if (window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
            window.lupoDbgNavActiveSubmenu = null;
        }
        if (!wasActive) {
            dropdown.classList.add('active');
            btn.setAttribute('aria-expanded', 'true');
            if (dropdown.id === 'lupo-dbg-master' || dropdown.classList.contains('lupo-master-collections-wrap')) {
                lupoDbgMasterHydrateIfNeeded();
            }
        }
    };

    window.lupoDbgNavOpenSubmenu = function (item) {
        if (window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
        }
        var submenu = item._lupoFloatingSubmenu || item.querySelector('.floating-submenu');
        if (!submenu) {
            return;
        }
        item._lupoFloatingSubmenu = submenu;
        var rect = item.getBoundingClientRect();
        submenu.style.top = rect.top + 'px';
        submenu.style.left = (rect.right + 5) + 'px';
        submenu.classList.add('active');
        window.lupoDbgNavActiveSubmenu = submenu;
        if (!submenu.parentElement || submenu.parentElement.tagName !== 'BODY') {
            document.body.appendChild(submenu);
        }
    };

    function lupoDbgPublicBase() {
        if (window.LUPO_MAIN_LAYOUT && window.LUPO_MAIN_LAYOUT.publicPath) {
            return String(window.LUPO_MAIN_LAYOUT.publicPath);
        }
        if (window.LUPO_HDR && window.LUPO_HDR.base) {
            return String(window.LUPO_HDR.base);
        }
        return '';
    }

    function lupoDbgMasterGroupedHtml(groups) {
        if (!groups || !groups.length) {
            return '';
        }
        var html = '';
        var gi;
        var ii;
        for (gi = 0; gi < groups.length; gi++) {
            var g = groups[gi];
            if (!g || !g.group_label || !g.items || !g.items.length) {
                continue;
            }
            html += '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
            html += '<span class="menu-item-label">' + lupoDbgHtmlEsc(String(g.group_label)) + '</span>';
            html += '<div class="floating-submenu">';
            for (ii = 0; ii < g.items.length; ii++) {
                var it = g.items[ii];
                if (!it || it.collection_id == null) {
                    continue;
                }
                var id = parseInt(String(it.collection_id), 10);
                var lab = it.label ? String(it.label) : ('Collection ' + id);
                var href = (typeof window.lupoCollectionContextSwitchHref === 'function')
                    ? window.lupoCollectionContextSwitchHref(id)
                    : (window.location.pathname + '?lupo_collection_id=' + encodeURIComponent(String(id)));
                html += '<a href="' + lupoDbgHtmlEsc(href) + '" class="menu-item" role="menuitem" tabindex="0">' + lupoDbgHtmlEsc(lab) + '</a>';
            }
            html += '</div></div>';
        }
        return html;
    }

    function lupoDbgMasterHydrateIfNeeded() {
        var panel = document.getElementById('lupo-dbg-master-panel');
        if (!panel || panel.getAttribute('data-lupo-hydrated') === '1') {
            return;
        }
        if (panel.querySelector('.menu-item, a.menu-item')) {
            panel.setAttribute('data-lupo-hydrated', '1');
            return;
        }
        var base = lupoDbgPublicBase();
        var url = (base === '' ? '' : base) + '/api/list_nav_collections_grouped.php';
        fetch(url)
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                var dd = document.getElementById('lupo-dbg-master');
                if (!dd || !dd.classList.contains('active')) {
                    return;
                }
                var grouped = data && data.success && data.groups && data.groups.length;
                var html = grouped ? lupoDbgMasterGroupedHtml(data.groups) : '';
                if (!html) {
                    panel.innerHTML = '<span class="menu-item" style="color:#6c757d;">No collections</span>';
                    panel.setAttribute('data-lupo-hydrated', '1');
                    return;
                }
                panel.innerHTML = html;
                panel.setAttribute('data-lupo-hydrated', '1');
            })
            .catch(function () {
                if (!document.getElementById('lupo-dbg-master') || !document.getElementById('lupo-dbg-master').classList.contains('active')) {
                    return;
                }
                panel.innerHTML = '<span class="menu-item" style="color:#dc3545;">Could not load collections</span>';
            });
    }
})();
</script>
<script src="<?php echo dbg_cd_h($public_path); ?>/includes/js/navigation.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.lupoSyncPageContextToMainLayout === 'function') {
        window.lupoSyncPageContextToMainLayout();
    }
});
</script>

<p style="padding:8px 16px;font-size:12px;color:#6c757d;">
    Disable this page: create <code>bin/debug_collections_dropmenus.disable</code>
</p>
</body>
</html>
