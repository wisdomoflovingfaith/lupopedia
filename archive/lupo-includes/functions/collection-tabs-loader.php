<?php
/**
---
wolfie.headers.version: "3.0.0"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.18: Updated load_collection_tabs() to load child tabs from database. Now loads root-level tabs AND their child tabs using collection_tab_parent_id. Properly counts child tabs excluding _slug metadata."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.12: Version bump for hierarchical tab structure implementation. No logic changes to collection-tabs-loader.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.11: Updated collection tabs loader to include tab slug in tabs data structure. Stores slug as _slug key in sub-tabs array for URL generation in collection_tabs.php component."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.10: Created collection tabs loader function to load and format tabs for Collection 0 (System Collection). Formats tabs for collection_tabs.php component display."
    mood: "00FF00"
tags:
  categories: ["function", "collections", "tabs"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "Collection Tabs Loader"
  description: "Helper function to load collection tabs from database and format for UI components"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collection-tabs-loader.php cannot be called directly.");
}

/**
 * Load collection tabs for a given collection ID (thin wrapper — CollectionTabsService).
 *
 * @param int $collection_id Collection ID (0 for System Collection)
 * @return array Formatted tabs data for collection_tabs.php component
 */
function load_collection_tabs($collection_id) {
    $s = $GLOBALS['lupo_collection_tabs_service'] ?? null;
    return $s ? $s->loadCollectionTabs((int) $collection_id) : [];
}

/**
 * Optional GET override: switch chrome to a specific collection (full page reload).
 * Query key: lupo_collection_id (non-negative int).
 *
 * @return int|null null if absent/invalid
 */
function lupo_get_request_collection_context_id() {
    if (isset($GLOBALS['UNTRUSTED']) && is_array($GLOBALS['UNTRUSTED']) && isset($GLOBALS['UNTRUSTED']['get']) && is_array($GLOBALS['UNTRUSTED']['get'])) {
        $g = $GLOBALS['UNTRUSTED']['get'];
        if (isset($g['lupo_collection_id']) && $g['lupo_collection_id'] !== '' && $g['lupo_collection_id'] !== null) {
            $v = (int) $g['lupo_collection_id'];
            return $v >= 0 ? $v : null;
        }
    }
    if (isset($_GET['lupo_collection_id']) && $_GET['lupo_collection_id'] !== '' && $_GET['lupo_collection_id'] !== null) {
        $v = (int) $_GET['lupo_collection_id'];
        return $v >= 0 ? $v : null;
    }
    $sess = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if ($sess !== null && $db !== null && method_exists($sess, 'getSessionId')) {
        $sid = $sess->getSessionId();
        if ($sid !== null && $sid !== '' && class_exists('App\\Auth\\Session')) {
            $meta = \App\Auth\Session::getDecodedMetadata($db, $sid);
            if (isset($meta['ui_lupo_collection_id'])) {
                $mid = (int) $meta['ui_lupo_collection_id'];
                if ($mid >= 0) {
                    return $mid;
                }
            }
        }
    }
    return null;
}

/**
 * When lupo_collection_id is present in the query string, persist it to lupo_sessions.metadata (Model A UI preference).
 * Call once per request after Session::start() / validate (e.g. from bootstrap).
 *
 * @return void
 */
function lupo_collection_context_persist_from_request() {
    if (php_sapi_name() === 'cli') {
        return;
    }
    if (!isset($_GET['lupo_collection_id']) || $_GET['lupo_collection_id'] === '' || $_GET['lupo_collection_id'] === null) {
        return;
    }
    $v = (int) $_GET['lupo_collection_id'];
    if ($v < 0) {
        return;
    }
    $sess = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if ($sess === null || $db === null || !method_exists($sess, 'getSessionId')) {
        return;
    }
    $sid = $sess->getSessionId();
    if ($sid === null || $sid === '') {
        return;
    }
    if (class_exists('App\\Auth\\Session') && method_exists('App\\Auth\\Session', 'mergeSessionMetadata')) {
        \App\Auth\Session::mergeSessionMetadata($db, $sid, array('ui_lupo_collection_id' => $v));
    }
}

/**
 * Grouped nav rows for master Collections dropdown (see CollectionTabsService::getNavMenuCollectionsGrouped).
 *
 * @return array
 */
function lupo_nav_menu_collection_groups() {
    $s = isset($GLOBALS['lupo_collection_tabs_service']) ? $GLOBALS['lupo_collection_tabs_service'] : null;
    if ($s !== null && method_exists($s, 'getNavMenuCollectionsGrouped')) {
        $g = $s->getNavMenuCollectionsGrouped();
        return is_array($g) ? $g : array();
    }
    return array();
}

/**
 * Build same-URL reload with collection context (preserves slug, artifact_type, etc.).
 * Uses SCRIPT_NAME so subdirectory + content/index.php stay correct.
 *
 * @param int $target_collection_id
 * @return string path + query (absolute from web root, begins with /)
 */
function lupo_collection_context_switch_href($target_collection_id) {
    $target_collection_id = (int) $target_collection_id;
    $q = (isset($_GET) && is_array($_GET)) ? $_GET : array();
    unset($q['lupo_collection_id']);
    $q['lupo_collection_id'] = (string) $target_collection_id;
    $sn = (isset($_SERVER['SCRIPT_NAME']) && is_string($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '')
        ? $_SERVER['SCRIPT_NAME']
        : '/index.php';
    $qs = http_build_query($q, '', '&', PHP_QUERY_RFC3986);
    return $sn . ($qs !== '' ? '?' . $qs : '');
}

/**
 * Get collection name by ID (thin wrapper — CollectionTabsService).
 *
 * @param int $collection_id Collection ID
 * @return string|null Collection name or null if not found
 */
function get_collection_name($collection_id) {
    $s = $GLOBALS['lupo_collection_tabs_service'] ?? null;
    return $s ? $s->getCollectionName((int) $collection_id) : null;
}

/**
 * Resolve collection id + tabs for main_layout chrome when preferred id is null, 0, or has no tabs.
 * Tries: preferred id (if >= 0), then each is_nav_menu collection until tabs exist.
 *
 * @param int|null $preferred_id Collection id from URL/content or null
 * @return array Keys: collection_id (int), tabs_data (array), current_collection (string|null)
 */
function lupo_resolve_collection_tabs_for_chrome($preferred_id) {
    $tabs_data = array();
    $current_collection = null;
    $collection_id = 0;

    if (!function_exists('load_collection_tabs')) {
        return array(
            'collection_id' => 0,
            'tabs_data' => array(),
            'current_collection' => null,
        );
    }

    $reqCollection = function_exists('lupo_get_request_collection_context_id') ? lupo_get_request_collection_context_id() : null;
    if ($reqCollection !== null) {
        $td = load_collection_tabs($reqCollection);
        $cn = function_exists('get_collection_name') ? get_collection_name($reqCollection) : null;
        return array(
            'collection_id' => $reqCollection,
            'tabs_data' => is_array($td) ? $td : array(),
            'current_collection' => $cn,
        );
    }

    $try_ids = array();
    if ($preferred_id !== null) {
        $pid = (int) $preferred_id;
        if ($pid >= 0) {
            $try_ids[] = $pid;
        }
    }

    $svc = isset($GLOBALS['lupo_collection_tabs_service']) ? $GLOBALS['lupo_collection_tabs_service'] : null;
    if ($svc !== null && method_exists($svc, 'getCollectionsForNavMenu')) {
        $nav = $svc->getCollectionsForNavMenu();
        if (is_array($nav)) {
            foreach ($nav as $row) {
                if (!empty($row['collection_id'])) {
                    $try_ids[] = (int) $row['collection_id'];
                }
            }
        }
    }

    $seen = array();
    $ordered = array();
    foreach ($try_ids as $tid) {
        if ($tid < 0) {
            continue;
        }
        if (isset($seen[$tid])) {
            continue;
        }
        $seen[$tid] = true;
        $ordered[] = $tid;
    }

    foreach ($ordered as $id) {
        $td = load_collection_tabs($id);
        if (!empty($td) && is_array($td)) {
            $collection_id = $id;
            $tabs_data = $td;
            $current_collection = function_exists('get_collection_name') ? get_collection_name($id) : null;
            break;
        }
    }

    return array(
        'collection_id' => $collection_id,
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
    );
}

/**
 * Master Settings: public visitor shell (book 9-slice vs scroll tile set).
 *
 * @return string 'book' or 'scroll'
 */
function lupo_get_public_content_shell() {
    $default = 'book';
    if (!function_exists('lupo_get_db')) {
        return $default;
    }
    try {
        $db = lupo_get_db();
        $pfx = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $t = $db->quoteIdentifier($pfx . 'settings');
        $row = $db->fetchRow(
            "SELECT setting_value FROM {$t} WHERE setting_key = :k AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('k' => 'public_content_shell')
        );
        if ($row && isset($row['setting_value']) && strtolower(trim((string) $row['setting_value'])) === 'scroll') {
            return 'scroll';
        }
    } catch (Exception $e) {
        // settings table may be missing on fresh trees
    }
    return $default;
}

?>
