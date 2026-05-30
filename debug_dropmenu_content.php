<?php
/**
 * debug_dropmenu_content.php — diagnose collections / shortcut / contents dropdowns on content pages.
 *
 * Usage: open in browser under your LUPOPEDIA_PUBLIC_PATH, e.g. /lupopedia/debug_dropmenu_content.php
 * Optional: ?collection_id=0
 *
 * Safety: create bin/debug_dropmenu.disable (empty file) to 403 this script. Delete this file when finished.
 */

if (is_file(__DIR__ . '/bin/debug_dropmenu.disable')) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    echo "debug_dropmenu_content.php is disabled (bin/debug_dropmenu.disable exists).\n";
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
    echo "lupopedia-config.php not found (tried parent dir and this dir).\n";
    exit;
}

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
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

if (!defined('LUPO_UI_PATH') && defined('LUPOPEDIA_PATH')) {
    $lupo_dbg_tr = rtrim(LUPOPEDIA_PATH, "/\\") . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'default';
    if (is_dir($lupo_dbg_tr)) {
        define('LUPO_UI_PATH', $lupo_dbg_tr);
    }
}

function dbg_h($s)
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

$collection_id = 0;
if (isset($_GET['collection_id']) && is_numeric($_GET['collection_id'])) {
    $collection_id = (int) $_GET['collection_id'];
}

$db = function_exists('lupo_get_db') ? lupo_get_db() : null;
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$actor_id = 0;
if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
    $aid = $GLOBALS['lupo_session']->getActorId();
    $actor_id = ($aid !== null) ? (int) $aid : 0;
}

$auth_user_id = 0;
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService && method_exists($authService, 'getCurrentUser')) {
    $cu = $authService->getCurrentUser();
    if (is_array($cu) && isset($cu['auth_user_id'])) {
        $auth_user_id = (int) $cu['auth_user_id'];
    }
}

$req_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
$slug_get = isset($_GET['slug']) ? (string) $_GET['slug'] : '';
$hide_semantic_nav = false;
if (strpos($req_uri, '/channels/') !== false || ($slug_get !== '' && strpos($slug_get, 'channels/') === 0)) {
    $hide_semantic_nav = true;
}

$tabs_service_ok = !empty($GLOBALS['lupo_collection_tabs_service']);
$saved_service_ok = !empty($GLOBALS['lupo_saved_collections_service']);

$tabs_data = array();
$tabs_error = '';
if (function_exists('load_collection_tabs')) {
    try {
        $tabs_data = load_collection_tabs($collection_id);
    } catch (Exception $e) {
        $tabs_error = $e->getMessage();
    }
} else {
    $tabs_error = 'load_collection_tabs() not defined';
}

$collection_name = null;
if (function_exists('get_collection_name')) {
    try {
        $collection_name = get_collection_name($collection_id);
    } catch (Exception $e) {
        $collection_name = '(error: ' . $e->getMessage() . ')';
    }
}

$chrome_preview = array();
if (function_exists('lupo_resolve_collection_tabs_for_chrome')) {
    $chrome_preview = lupo_resolve_collection_tabs_for_chrome($collection_id);
}

$collections_as_actor = array();
$collections_as_auth = array();
if (function_exists('render_saved_collections')) {
    $collections_as_actor = render_saved_collections($actor_id);
    $collections_as_auth = render_saved_collections($auth_user_id);
}

$row_counts = array();
if ($db) {
    try {
        $c = $db->fetchRow('SELECT COUNT(*) AS n FROM ' . $db->quoteIdentifier($prefix . 'collections') . ' WHERE (is_deleted = 0 OR is_deleted IS NULL)', array());
        $row_counts['collections'] = isset($c['n']) ? (int) $c['n'] : 0;
    } catch (Exception $e) {
        $row_counts['collections'] = -1;
        $row_counts['collections_err'] = $e->getMessage();
    }
    try {
        $t = $db->fetchRow(
            'SELECT COUNT(*) AS n FROM ' . $db->quoteIdentifier($prefix . 'collection_tabs')
            . ' WHERE collection_id = :cid AND (is_deleted = 0 OR is_deleted IS NULL)',
            array('cid' => $collection_id)
        );
        $row_counts['tabs_for_collection'] = isset($t['n']) ? (int) $t['n'] : 0;
    } catch (Exception $e) {
        $row_counts['tabs_for_collection'] = -1;
        $row_counts['tabs_err'] = $e->getMessage();
    }
    try {
        $p = $db->fetchRow(
            'SELECT COUNT(*) AS n FROM ' . $db->quoteIdentifier($prefix . 'permissions')
            . ' WHERE target_type = :tt AND (is_deleted = 0 OR is_deleted IS NULL)',
            array('tt' => 'collection')
        );
        $row_counts['permissions_collection'] = isset($p['n']) ? (int) $p['n'] : 0;
    } catch (Exception $e) {
        $row_counts['permissions_collection'] = -1;
    }
}

$public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
$api_tabs = $public_path . '/api/load_collection_tabs.php?collection_id=' . (int) $collection_id;
$api_list = $public_path . '/api/list_user_collections.php';

$lupo_ui = '';
if (defined('LUPO_UI_PATH')) {
    $lupo_ui = LUPO_UI_PATH;
}
$dd_path = LUPOPEDIA_PATH . '/includes/themes/default/components/collections_dropdown.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>debug_dropmenu_content</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 16px; background: #f8f9fa; color: #212529; }
        h1 { font-size: 1.25rem; }
        h2 { font-size: 1.05rem; margin-top: 1.5rem; border-bottom: 1px solid #ccc; }
        pre { background: #fff; border: 1px solid #dee2e6; padding: 12px; overflow: auto; max-height: 420px; font-size: 12px; }
        .ok { color: #0a7a2f; }
        .warn { color: #b35900; background: #fff3cd; padding: 8px 12px; border: 1px solid #ffc107; }
        .bad { color: #b00020; }
        table { border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #dee2e6; padding: 6px 10px; text-align: left; }
        a { color: #0d6efd; }
    </style>
</head>
<body>
<h1>debug_dropmenu_content.php</h1>
<p><strong>collection_id</strong> = <?php echo (int) $collection_id; ?> (use <code>?collection_id=N</code>)</p>

<h2>1. Layout gates (why chrome might be missing)</h2>
<ul>
    <li><code>hide_semantic_nav</code> (main_layout): <strong class="<?php echo $hide_semantic_nav ? 'bad' : 'ok'; ?>"><?php echo $hide_semantic_nav ? 'TRUE — shortcut/contents blue bar hidden' : 'FALSE'; ?></strong>
        <?php if ($hide_semantic_nav): ?><br><small>Triggered when URL path contains <code>/channels/</code> or slug starts with <code>channels/</code>.</small><?php endif; ?></li>
    <li>Topbar no longer embeds <code>collections_dropdown.php</code> (removed 4.0.97+); Collections is <code>main_layout</code> saved-collections-nav (light blue master).</li>
</ul>
<table>
    <tr><th>LUPO_UI_PATH</th><td><?php echo dbg_h($lupo_ui !== '' ? $lupo_ui : '(not defined)'); ?></td></tr>
    <tr><th>collections_dropdown.php</th><td><?php echo is_file($dd_path) ? '<span class="ok">exists</span>' : '<span class="bad">MISSING</span>'; ?> <?php echo dbg_h($dd_path); ?></td></tr>
    <tr><th>LUPOPEDIA_PUBLIC_PATH</th><td><?php echo dbg_h($public_path); ?></td></tr>
</table>

<h2>2. Session identity (dropdowns + APIs)</h2>
<table>
    <tr><th>actor_id</th><td><?php echo (int) $actor_id; ?> <small>(from lupo_session)</small></td></tr>
    <tr><th>auth_user_id</th><td><?php echo (int) $auth_user_id; ?> <small>(from AuthService)</small></td></tr>
</table>
<div class="warn" style="margin-top:12px;">
    <strong>Note:</strong> <code>main_layout.php</code> calls <code>render_saved_collections(auth_user_id)</code> (0 = guest → all collections).
    Logged-in users: if <code>lupo_permissions</code> has no collection rows, the service now falls back to <code>lupo_actor_collections</code>, then to all collections (same spirit as <code>list_user_collections.php</code>).
</div>

<h2>3. Services + load_collection_tabs(<?php echo (int) $collection_id; ?>)</h2>
<table>
    <tr><th>lupo_collection_tabs_service</th><td><?php echo $tabs_service_ok ? '<span class="ok">set</span>' : '<span class="bad">MISSING</span>'; ?></td></tr>
    <tr><th>lupo_saved_collections_service</th><td><?php echo $saved_service_ok ? '<span class="ok">set</span>' : '<span class="bad">MISSING</span>'; ?></td></tr>
    <tr><th>get_collection_name</th><td><?php echo dbg_h($collection_name !== null ? (string) $collection_name : '(null)'); ?></td></tr>
    <tr><th>tabs_data keys count</th><td><?php echo is_array($tabs_data) ? count($tabs_data) : 0; ?></td></tr>
</table>
<?php if ($tabs_error !== ''): ?>
<p class="bad">Error: <?php echo dbg_h($tabs_error); ?></p>
<?php endif; ?>
<pre><?php echo dbg_h(print_r($tabs_data, true)); ?></pre>

<h2>3b. Content chrome resolver (same as content pages)</h2>
<p><code>lupo_resolve_collection_tabs_for_chrome(<?php echo (int) $collection_id; ?>)</code> — picks first collection that actually has root tabs (tries query id, then each <code>is_nav_menu</code> collection):</p>
<pre><?php echo dbg_h(print_r($chrome_preview, true)); ?></pre>

<h2>4. render_saved_collections() — layout uses auth_user_id</h2>
<p><strong>render_saved_collections(<?php echo (int) $actor_id; ?>)</strong> (wrong id if this is actor_id, not auth — shown for comparison only):</p>
<pre><?php echo dbg_h(print_r($collections_as_actor, true)); ?></pre>
<p><strong>render_saved_collections(<?php echo (int) $auth_user_id; ?>)</strong> (what <code>main_layout.php</code> uses; service falls back: <code>lupo_permissions</code> → <code>lupo_actor_collections</code> → all collections):</p>
<pre><?php echo dbg_h(print_r($collections_as_auth, true)); ?></pre>
<?php
$slug_keys_auth = is_array($collections_as_auth) ? implode(', ', array_keys($collections_as_auth)) : '';
$nav_menu_count = 0;
if ($db && $tabs_service_ok) {
    try {
        $svc = $GLOBALS['lupo_collection_tabs_service'];
        if (method_exists($svc, 'getCollectionsForNavMenu')) {
            $nav_menu_count = count($svc->getCollectionsForNavMenu());
        }
    } catch (Exception $e) {
        $nav_menu_count = -1;
    }
}
?>
<div class="<?php echo (!empty($collections_as_auth)) ? 'ok' : 'warn'; ?>" style="padding:10px;margin:10px 0;border:1px solid #ccc;">
    <strong>Blue bar:</strong> <code>main_layout</code> includes <code>saved-collections-dropdown-group.php</code> when <code>render_saved_collections</code> returns any slug-keyed groups.<br>
    <strong>Your slug keys (auth path):</strong> <code><?php echo dbg_h($slug_keys_auth !== '' ? $slug_keys_auth : '(empty)'); ?></code><br>
    <strong>is_nav_menu collections (for green tabs fallback):</strong> <?php echo (int) $nav_menu_count; ?> — set <code>is_nav_menu = 1</code> on collections that should drive the tab strip when content has no <code>default_collection_id</code>.<br>
    <strong>Tabs:</strong> root tabs need <code>collection_tab_parent_id IS NULL</code>, <code>is_active = 1</code> in <code>lupo_collection_tabs</code> (see <code>CollectionTabsService::loadCollectionTabs</code>).
</div>

<h2>5. Table row counts</h2>
<pre><?php echo dbg_h(print_r($row_counts, true)); ?></pre>

<h2>6. Live JSON endpoints (same origin)</h2>
<ul>
    <li><a href="<?php echo dbg_h($api_tabs); ?>" target="_blank" rel="noopener"><?php echo dbg_h($api_tabs); ?></a></li>
    <li><a href="<?php echo dbg_h($api_list); ?>" target="_blank" rel="noopener"><?php echo dbg_h($api_list); ?></a></li>
</ul>

<h2>7. JavaScript gotcha (contents / shortcut)</h2>
<p><code>main-layout.js</code> defines <code>toggleMenu</code> and <code>window.onclick</code> closes all <code>.dropdown-content</code> when the click target is <strong>not</strong> an <code>img</code>.
    Clicks on links inside an open panel may close menus; the shortcut trigger is an <code>img</code> (ok). If <code>toggleMenu</code> throws because an element id is missing, check the browser console.</p>

<hr>
<p style="font-size:12px;color:#6c757d;">Disable: create <code>bin/debug_dropmenu.disable</code>. Delete this script when done.</p>
</body>
</html>
