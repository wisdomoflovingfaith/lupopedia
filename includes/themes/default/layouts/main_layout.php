<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: layout
  when_updated: "20260410021335"
  file_path_from_root: "includes/themes/default/layouts/main_layout.php"
  web_path: "http://www.lupopedia.com/lupopedia/includes/themes/default/layouts/main_layout.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "layout"
  artifact_kind: "main"
  purpose: "Main Lupopedia UI layout; collections chrome, modals, shortcut/contents dropdowns, 9-slice book shell; title-bar toggles footer semantic bar (components/footer.php)."
  tags: ["layout", "ui", "collections", "tabs", "locale"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. main_layout.php cannot be called directly.");
}

$root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '';
if ($root !== '' && !class_exists('LupoLocale', false)) {
    $lp = $root . '/includes/classes/LupoLocale.php';
    if (is_file($lp)) {
        require_once $lp;
    }
}
if ($root !== '' && class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
    LupoLocale::bootstrap($root);
}
if (!function_exists('lupo_t')) {
    $i18n = ($root !== '' ? $root . '/includes/i18n.php' : '');
    if ($i18n !== '' && is_file($i18n)) {
        require_once $i18n;
    }
}

if (!isset($UNTRUSTED) || !is_array($UNTRUSTED)) {
    $UNTRUSTED = array();
}
if (!isset($UNTRUSTED['server']) || !is_array($UNTRUSTED['server'])) {
    $UNTRUSTED['server'] = (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array();
}
if (!isset($UNTRUSTED['get']) || !is_array($UNTRUSTED['get'])) {
    $UNTRUSTED['get'] = (isset($_GET) && is_array($_GET)) ? $_GET : array();
}
$UNTRUSTED_SERVER = $UNTRUSTED['server'];

/**
 * ---------------------------------------------------------
 * Main Layout Template
 * ---------------------------------------------------------
 * 
 * This is the global Lupopedia UI - the "desktop environment"
 * that wraps all content. Updated to match template structure.
 */

// Theme path: use theme when loading from theme, else core UI (fallback)
if (!defined('LUPO_UI_PATH')) {
    $theme_layout = LUPOPEDIA_PATH . '/includes/themes/default/layouts/main_layout.php';
    define('LUPO_UI_PATH', (file_exists($theme_layout) ? LUPOPEDIA_PATH . '/includes/themes/default' : LUPOPEDIA_PATH . '/includes/ui'));
}

// Extract content fields with defaults
$page_title = isset($content['title']) ? $content['title'] : 'Lupopedia';
$page_description = isset($content['description']) ? $content['description'] : '';
$page_keywords = isset($content['seo_keywords']) ? $content['seo_keywords'] : '';

// Initialize user session variables
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}
if (!isset($currentUserId)) {
    $currentUserId = 0;
    if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
        $aid = $GLOBALS['lupo_session']->getActorId();
        $currentUserId = $aid !== null ? (int) $aid : 0;
    }
    $isUserLoggedIn = ($currentUserId > 0);
}

// Load collections data for saved collections nav (SavedCollectionsService expects auth_user_id, not actor_id).
if (!isset($collectionsData)) {
    $lupo_collections_auth_user_id = 0;
    $lupo_auth_svc = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($lupo_auth_svc !== null && method_exists($lupo_auth_svc, 'getCurrentUser')) {
        $lupo_cu = $lupo_auth_svc->getCurrentUser();
        if (is_array($lupo_cu) && isset($lupo_cu['auth_user_id']) && (int) $lupo_cu['auth_user_id'] > 0) {
            $lupo_collections_auth_user_id = (int) $lupo_cu['auth_user_id'];
        }
    }
    if (function_exists('render_saved_collections')) {
        $collectionsData = render_saved_collections($lupo_collections_auth_user_id);
    } else {
        $renderer_path = LUPOPEDIA_PATH . '/includes/functions/render-saved-collections.php';
        if (file_exists($renderer_path)) {
            require_once $renderer_path;
            $collectionsData = function_exists('render_saved_collections')
                ? render_saved_collections($lupo_collections_auth_user_id)
                : array();
        } else {
            $collectionsData = array();
        }
    }
}

// Default UI collection context ($GLOBALS — not $_SESSION; §17.7 session authority is lupo_sessions only).
if (!isset($GLOBALS['collection_id']) || $GLOBALS['collection_id'] === '' || $GLOBALS['collection_id'] === null) {
    $GLOBALS['collection_id'] = 0;
}
// Ensure required variables exist for collection tabs; use $GLOBALS when context has no collection_id
if (!isset($current_collection) || $current_collection === null) {
    $current_collection = function_exists('lupo_t') ? lupo_t('layout.main_layout.system_collection', 'System Collection') : 'System Collection';
}
if (!isset($tabs_data) || !is_array($tabs_data)) {
    $tabs_data = array();
}
if (!isset($semantic_widget_context) || !is_array($semantic_widget_context)) {
    $semantic_widget_context = array();
}
if (!isset($collection_id) || $collection_id === null) {
    $collection_id = (int) $GLOBALS['collection_id'];
} else {
    $collection_id = (int) $collection_id;
}
$GLOBALS['collection_id'] = $collection_id;

// Initialize content sections for contents dropdown + left TOC (normalize string ids to anchor/title rows)
if (!isset($contentSections)) {
    $contentSections = (isset($content) && is_array($content) && isset($content['content_sections'])) ? $content['content_sections'] : array();
}
if (is_string($contentSections)) {
    $lupo_decoded_sections = json_decode($contentSections, true);
    $contentSections = is_array($lupo_decoded_sections) ? $lupo_decoded_sections : array();
}
if (!empty($contentSections) && is_array($contentSections)) {
    $lupo_norm_sections = array();
    foreach ($contentSections as $lupo_sec) {
        if (is_string($lupo_sec) && $lupo_sec !== '') {
            $lupo_norm_sections[] = array(
                'anchor' => $lupo_sec,
                'title' => ucwords(str_replace(array('-', '_'), ' ', $lupo_sec)),
            );
        } elseif (is_array($lupo_sec) && !empty($lupo_sec['anchor'])) {
            $lupo_norm_sections[] = array(
                'anchor' => $lupo_sec['anchor'],
                'title' => isset($lupo_sec['title']) ? $lupo_sec['title'] : $lupo_sec['anchor'],
            );
        }
    }
    $contentSections = $lupo_norm_sections;
}

$page_content_id = 0;
if (isset($content) && is_array($content) && isset($content['content_id'])) {
    $page_content_id = (int) $content['content_id'];
}

$loader_shell = ($root !== '') ? ($root . '/includes/functions/collection-tabs-loader.php') : '';
if (!function_exists('lupo_get_public_content_shell') && $loader_shell !== '' && is_file($loader_shell)) {
    require_once $loader_shell;
}
$lupo_public_shell = function_exists('lupo_get_public_content_shell') ? lupo_get_public_content_shell() : 'book';
$lupo_body_layout_class = ($lupo_public_shell === 'scroll') ? 'scroll-layout' : 'book-layout';

$lupoPublicBase = (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '');
$lupoHdrJs = array(
    'base' => $lupoPublicBase,
    'strings' => array(
        'collections_save_login' => function_exists('lupo_t') ? lupo_t('header.collections.alert_save_login', 'Please log in to save collections.') : 'Please log in to save collections.',
        'collections_load_login' => function_exists('lupo_t') ? lupo_t('header.collections.alert_load_login', 'Please log in to load collections.') : 'Please log in to load collections.',
        'collections_edit_login' => function_exists('lupo_t') ? lupo_t('header.collections.alert_edit_login', 'Please log in to edit collections.') : 'Please log in to edit collections.',
        'collections_edit_save_first' => function_exists('lupo_t') ? lupo_t('header.collections.edit_save_first', 'Please save this collection first, then you can edit it!') : 'Please save this collection first, then you can edit it!',
        'collections_edit_open_save' => function_exists('lupo_t') ? lupo_t('header.collections.edit_open_save', 'Click OK to open the Save dialog.') : 'Click OK to open the Save dialog.',
        'collections_name_required' => function_exists('lupo_t') ? lupo_t('header.collections.name_required', 'Please enter a name for this collection') : 'Please enter a name for this collection',
        'collections_saved_ok' => function_exists('lupo_t') ? lupo_t('header.collections.saved_ok', 'Collection saved successfully!') : 'Collection saved successfully!',
        'collections_error_prefix' => function_exists('lupo_t') ? lupo_t('header.collections.error_prefix', 'Error: ') : 'Error: ',
        'collections_save_failed' => function_exists('lupo_t') ? lupo_t('header.collections.save_failed', 'Failed to save collection') : 'Failed to save collection',
        'collections_save_try_again' => function_exists('lupo_t') ? lupo_t('header.collections.save_try_again', 'Error saving collection. Please try again.') : 'Error saving collection. Please try again.',
        'collections_loading_short' => function_exists('lupo_t') ? lupo_t('header.collections.loading_short', 'Loading...') : 'Loading...',
        'collections_loading_list' => function_exists('lupo_t') ? lupo_t('header.collections.loading_list', 'Loading your collections...') : 'Loading your collections...',
        'collections_no_description' => function_exists('lupo_t') ? lupo_t('header.collections.no_description', 'No description') : 'No description',
        'collections_saved_items' => function_exists('lupo_t') ? lupo_t('header.collections.saved_items', 'saved items') : 'saved items',
        'collections_created' => function_exists('lupo_t') ? lupo_t('header.collections.created', 'Created:') : 'Created:',
        'collections_load_btn' => function_exists('lupo_t') ? lupo_t('header.collections.load_btn', 'Load') : 'Load',
        'collections_delete_btn' => function_exists('lupo_t') ? lupo_t('header.collections.delete_btn', 'Delete') : 'Delete',
        'collections_active_prefix' => function_exists('lupo_t') ? lupo_t('header.collections.active_prefix', '[Active] ') : '[Active] ',
        'collections_empty' => function_exists('lupo_t') ? lupo_t('header.collections.empty', 'No saved collections yet.') : 'No saved collections yet.',
        'collections_empty_hint' => function_exists('lupo_t') ? lupo_t('header.collections.empty_hint', 'Click Save to save your first collection!') : 'Click Save to save your first collection!',
        'collections_list_error' => function_exists('lupo_t') ? lupo_t('header.collections.list_error', 'Error loading collections') : 'Error loading collections',
        'collections_confirm_load' => function_exists('lupo_t') ? lupo_t('header.collections.confirm_load', 'Load collection "%s"? This will replace your current recently viewed items.') : 'Load collection "%s"? This will replace your current recently viewed items.',
        'collections_loaded_ok' => function_exists('lupo_t') ? lupo_t('header.collections.loaded_ok', 'Collection loaded! Refreshing page...') : 'Collection loaded! Refreshing page...',
        'collections_load_failed' => function_exists('lupo_t') ? lupo_t('header.collections.load_failed', 'Failed to load collection') : 'Failed to load collection',
        'collections_load_try_again' => function_exists('lupo_t') ? lupo_t('header.collections.load_try_again', 'Error loading collection. Please try again.') : 'Error loading collection. Please try again.',
        'collections_delete_confirm' => function_exists('lupo_t') ? lupo_t('header.collections.delete_confirm', 'Delete this collection? This cannot be undone.') : 'Delete this collection? This cannot be undone.',
        'collections_delete_failed' => function_exists('lupo_t') ? lupo_t('header.collections.delete_failed', 'Failed to delete collection') : 'Failed to delete collection',
        'collections_delete_try_again' => function_exists('lupo_t') ? lupo_t('header.collections.delete_try_again', 'Error deleting collection. Please try again.') : 'Error deleting collection. Please try again.',
        'prompt_main_tab' => function_exists('lupo_t') ? lupo_t('header.shortcut.prompt_main_tab', 'Enter name for new Main Tab:') : 'Enter name for new Main Tab:',
        'prompt_sub_tab' => function_exists('lupo_t') ? lupo_t('header.shortcut.prompt_sub_tab', 'Enter new Sub-Tab name for "%s":') : 'Enter new Sub-Tab name for "%s":',
        'add_success' => function_exists('lupo_t') ? lupo_t('header.shortcut.add_success', 'Successfully added "%s" to your collection!') : 'Successfully added "%s" to your collection!',
        'shortcut_pin_no_tab' => function_exists('lupo_t') ? lupo_t('header.shortcut.pin_no_tab', 'This tab cannot be pinned. Reload the page or choose another tab.') : 'This tab cannot be pinned. Reload the page or choose another tab.',
        'shortcut_pin_no_content' => function_exists('lupo_t') ? lupo_t('header.shortcut.pin_no_content', 'This page has no content record to pin. Open a content page first.') : 'This page has no content record to pin. Open a content page first.',
        'shortcut_pin_ok' => function_exists('lupo_t') ? lupo_t('header.shortcut.pin_ok', 'Page pinned to this tab.') : 'Page pinned to this tab.',
    ),
);

?>
<!DOCTYPE html>
<html lang="<?php echo class_exists('LupoLocale', false) ? LupoLocale::htmlLang() : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
        $titleSuffix = function_exists('lupo_t') ? lupo_t('layout.main_layout.title_suffix', 'LUPOPEDIA') : 'LUPOPEDIA';
        echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($titleSuffix, ENT_QUOTES, 'UTF-8');
    ?></title>
    <script>
    (function() {
        var incoming = <?php echo json_encode($lupoHdrJs); ?>;
        window.LUPO_HDR = window.LUPO_HDR || { base: '', strings: {} };
        if (incoming && incoming.base) {
            if (!window.LUPO_HDR.base) {
                window.LUPO_HDR.base = incoming.base;
            }
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
    <link rel="icon" type="image/x-icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="shortcut icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/includes/css/main.css">
    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/includes/js/lupopedia.js"></script>
    
    <?php
    // Load UI assets (CSS and JS) from ui-loader.php
    if (!function_exists('lupo_print_ui_css')) {
        if (file_exists(LUPOPEDIA_PATH . '/includes/ui/ui-loader.php')) {
            require_once LUPOPEDIA_PATH . '/includes/ui/ui-loader.php';
        }
    }
    if (function_exists('lupo_print_ui_css')) {
        lupo_print_ui_css();
    }
    ?>
    
    <?php if (!empty($page_description)): ?>
        <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <?php endif; ?>
    
    <?php if (!empty($page_keywords)): ?>
        <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/includes/css/main-layout.css">
    <style>
    /* Tile art: dual scope so backgrounds survive selector drift (body OR .content-list-container carries shell class). */
    body.book-layout .resources-top-left,
    .content-list-container.book-layout .resources-top-left {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s1b.png') center / cover no-repeat;
    }
    body.book-layout .resources-top-center,
    .content-list-container.book-layout .resources-top-center {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s2b.png');
        background-repeat: repeat-x;
        background-position: center top;
    }
    body.book-layout .resources-top-right,
    .content-list-container.book-layout .resources-top-right {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s3b.png') center / cover no-repeat;
    }
    body.book-layout .resources-middle-left,
    .content-list-container.book-layout .resources-middle-left {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s4b.png');
        background-repeat: repeat-y;
        background-position: left center;
    }
    body.book-layout .resources-middle-center,
    .content-list-container.book-layout .resources-middle-center {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s5.png');
        background-repeat: repeat;
        padding: 20px;
    }
    body.book-layout .resources-middle-right,
    .content-list-container.book-layout .resources-middle-right {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s6b.png');
        background-repeat: repeat-y;
        background-position: right center;
    }
    body.book-layout .resources-bottom-left,
    .content-list-container.book-layout .resources-bottom-left {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s7b.png') center / cover no-repeat;
    }
    body.book-layout .resources-bottom-center,
    .content-list-container.book-layout .resources-bottom-center {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s8b.png');
        background-repeat: repeat-x;
        background-position: center bottom;
    }
    body.book-layout .resources-bottom-right,
    .content-list-container.book-layout .resources-bottom-right {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s9b.png') center / cover no-repeat;
    }
    body.scroll-layout .resources-top-left,
    .content-list-container.scroll-layout .resources-top-left {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s1a.png') center / cover no-repeat;
    }
    body.scroll-layout .resources-top-center,
    .content-list-container.scroll-layout .resources-top-center {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s2a.png');
        background-repeat: repeat-x;
        background-position: center top;
    }
    body.scroll-layout .resources-top-right,
    .content-list-container.scroll-layout .resources-top-right {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s3a.png') center / cover no-repeat;
    }
    body.scroll-layout .resources-middle-left,
    .content-list-container.scroll-layout .resources-middle-left {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s4a.png');
        background-repeat: repeat-y;
        background-position: left center;
    }
    body.scroll-layout .resources-middle-center,
    .content-list-container.scroll-layout .resources-middle-center {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s5a.png');
        background-repeat: repeat;
        padding: 20px;
    }
    body.scroll-layout .resources-middle-right,
    .content-list-container.scroll-layout .resources-middle-right {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s6a.png');
        background-repeat: repeat-y;
        background-position: right center;
    }
    body.scroll-layout .resources-bottom-left,
    .content-list-container.scroll-layout .resources-bottom-left {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s7a.png') center / cover no-repeat;
    }
    body.scroll-layout .resources-bottom-center,
    .content-list-container.scroll-layout .resources-bottom-center {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s8a.png');
        background-repeat: repeat-x;
        background-position: center bottom;
    }
    body.scroll-layout .resources-bottom-right,
    .content-list-container.scroll-layout .resources-bottom-right {
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s9a.png') center / cover no-repeat;
    }
    </style>

    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/includes/js/main-layout.js"></script>
</head>
<?php
// Channel staff UI: hide 9-slice shell + saved-collections chrome (PRD routing).
$hide_semantic_nav = false;
$req_uri_ch = isset($UNTRUSTED_SERVER['REQUEST_URI']) ? $UNTRUSTED_SERVER['REQUEST_URI'] : '';
$slug_get = '';
if (isset($UNTRUSTED['get']['slug']) && is_string($UNTRUSTED['get']['slug'])) {
    $slug_get = $UNTRUSTED['get']['slug'];
} elseif (isset($_GET['slug']) && is_string($_GET['slug'])) {
    $slug_get = $_GET['slug'];
}
if (strpos($req_uri_ch, '/channels/') !== false || strpos($slug_get, 'channels/') === 0) {
    $hide_semantic_nav = true;
}
?>
<body<?php if (!$hide_semantic_nav): ?> class="<?= htmlspecialchars($lupo_body_layout_class, ENT_QUOTES, 'UTF-8') ?>"<?php endif; ?>>

<?php
if (file_exists(LUPO_UI_PATH . '/components/topbar.php')) {
    include LUPO_UI_PATH . '/components/topbar.php';
}
?>

<?php if (!$hide_semantic_nav): ?>
<script>
var isUserLoggedIn = <?php echo !empty($isUserLoggedIn) ? 'true' : 'false'; ?>;
</script>
<input type="hidden" id="active-collection-id" name="active_collection_id" value="<?= $collection_id !== null ? (int) $collection_id : 0 ?>">
<input type="hidden" id="current-content-id" name="lupo_current_content_id" value="<?= (int) $page_content_id ?>">
<!-- Saved Collections Navigation (try2: saved-collections-nav-try2.php + saved-collections-nav-try2.js) -->
<nav class="saved-collections-nav debug-try2-nav" data-collection-id="<?= $collection_id !== null ? (int) $collection_id : 0 ?>">
<?php
$lupo_sc_try2 = LUPOPEDIA_PATH . '/includes/themes/default/components/saved-collections-nav-try2.php';
if (is_file($lupo_sc_try2)) {
    include $lupo_sc_try2;
}
?>
</nav>
<?php endif; ?>

<!-- Save Collection Modal -->
<div id="saveCollectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-top: 0; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.save_modal_title', 'Save recently viewed collection') : 'Save recently viewed collection', ENT_QUOTES, 'UTF-8'); ?></h3>
        <p style="color: #6c757d; margin-bottom: 20px;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.save_modal_intro', 'Give this collection a name to save your current browsing session.') : 'Give this collection a name to save your current browsing session.', ENT_QUOTES, 'UTF-8'); ?></p>
        
        <div id="updateExistingNotice" style="display: none; background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin-bottom: 15px;">
            <strong><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.update_label', 'Update existing:') : 'Update existing:', ENT_QUOTES, 'UTF-8'); ?></strong> <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.update_body_before_name', 'You are currently viewing collection') : 'You are currently viewing collection', ENT_QUOTES, 'UTF-8'); ?> "<span id="currentCollectionName"></span>"<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.update_body_after_name', '. Save to update it, or enter a new name to create a copy.') : '. Save to update it, or enter a new name to create a copy.', ENT_QUOTES, 'UTF-8'); ?>
        </div>
        
        <label for="collectionName" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.name_label', 'Collection name:') : 'Collection name:', ENT_QUOTES, 'UTF-8'); ?></label>
        <input type="text" id="collectionName" placeholder="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.name_placeholder', 'e.g., Bible study session, research project') : 'e.g., Bible study session, research project', ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; padding: 12px; border: 2px solid #D4AF37; border-radius: 6px; font-size: 1rem; margin-bottom: 10px;">
        
        <label for="collectionDescription" style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.desc_label', 'Description (optional):') : 'Description (optional):', ENT_QUOTES, 'UTF-8'); ?></label>
        <textarea id="collectionDescription" placeholder="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.desc_placeholder', 'What is this collection for?') : 'What is this collection for?', ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; padding: 12px; border: 2px solid #D4AF37; border-radius: 6px; font-size: 1rem; margin-bottom: 20px; min-height: 80px;"></textarea>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeSaveCollectionModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.cancel', 'Cancel') : 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            <button onclick="saveCollection()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.save_submit', 'Save collection') : 'Save collection', ENT_QUOTES, 'UTF-8'); ?></button>
        </div>
    </div>
</div>

<!-- Load Collection Modal -->
<div id="loadCollectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <h3 style="margin-top: 0; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.load_modal_title', 'Load saved collection') : 'Load saved collection', ENT_QUOTES, 'UTF-8'); ?></h3>
        <p style="color: #6c757d; margin-bottom: 20px;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.load_modal_intro', 'Select a saved collection to restore your browsing session.') : 'Select a saved collection to restore your browsing session.', ENT_QUOTES, 'UTF-8'); ?></p>
        
        <div id="collectionsList" style="margin-bottom: 20px;">
            <div style="text-align: center; padding: 40px; color: #6c757d;">
                <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.loading_list', 'Loading your collections...') : 'Loading your collections...', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeLoadCollectionModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.close', 'Close') : 'Close', ENT_QUOTES, 'UTF-8'); ?></button>
        </div>
    </div>
</div>

<!-- JavaScript for Collection Management: PHP supplies paths; logic in main-layout-collections.js -->
<script>
window.LUPO_MAIN_LAYOUT = <?php echo json_encode(array(
    'publicPath' => LUPOPEDIA_PUBLIC_PATH,
    'collectionId' => $collection_id !== null ? (int) $collection_id : 0,
    'currentCollectionName' => $current_collection,
    'contentId' => (int) $page_content_id,
)); ?>;
</script>
<script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/includes/js/main-layout-collections.js"></script>
<script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/includes/js/saved-collections-nav-try2.js"></script>

<?php if (!$hide_semantic_nav): ?>
<!-- 9-slice book shell: grid lives on .content-list-container (main is landmark only; display:contents in CSS). -->
<main id="book-shell" class="book-shell">
<div class="content-list-container <?= htmlspecialchars($lupo_body_layout_class, ENT_QUOTES, 'UTF-8') ?>">
    <!-- Row 1: Top Border -->
    <div class="resources-top-left"></div>
    <div class="resources-top-center">
        <!-- Shortcut Dropdown -->
        <div class="dropdown">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/addshortcut.png" width="42" height="42" onclick="lupoOpenShortcutDropdown(event)" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.shortcut_trigger_alt', 'Add shortcut') : 'Add shortcut', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.shortcut_trigger_alt', 'Add shortcut') : 'Add shortcut', ENT_QUOTES, 'UTF-8'); ?>"> 
            <div id="shortcutDropdown" class="dropdown-content">
                <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
                    <b><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.current_label', 'Current collection:') : 'Current collection:', ENT_QUOTES, 'UTF-8'); ?></b> <span id="current-collection-display"><?= htmlspecialchars($current_collection) ?></span><br>
                    <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.instructions', 'Opens a live menu: tabs load from the collections DB for the active collection. Pin uses the page slug from the URL when content id is unknown. Log in to pin.') : 'Opens a live menu: tabs load from the collections DB for the active collection. Pin uses the page slug from the URL when content id is unknown. Log in to pin.', ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div id="shortcut-tabs-list">
                    <div id="shortcut-tabs-dynamic">
                    <?php if (!empty($tabs_data) && is_array($tabs_data)): ?>
                        <?php foreach ($tabs_data as $main_tab => $sub_tabs): ?>
                            <?php
                            $root_tab_id = (is_array($sub_tabs) && isset($sub_tabs['_collection_tab_id'])) ? (int) $sub_tabs['_collection_tab_id'] : 0;
                            ?>
                            <a href="javascript:void(0)" class="main-tab shortcut-pin" role="button" data-collection-tab-id="<?= $root_tab_id ?>" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">| <?= htmlspecialchars($main_tab) ?></a>
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
                                        <a href="javascript:void(0)" class="sub-tab shortcut-pin" role="button" data-collection-tab-id="<?= $ctid ?>" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">|— <?= htmlspecialchars($cname) ?></a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php foreach ($sub_tabs as $key => $value): ?>
                                        <?php
                                        if (is_string($key) && strlen($key) > 0 && $key[0] === '_') {
                                            continue;
                                        }
                                        if (!is_string($value)) {
                                            continue;
                                        }
                                        ?>
                                        <a href="javascript:void(0)" class="sub-tab shortcut-pin" role="button" data-collection-tab-id="0" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">|— <?= htmlspecialchars($value) ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <a href="javascript:void(0)" class="add-action" onclick="addNewItem('sub', <?php echo json_encode($main_tab); ?>)"><?php echo htmlspecialchars(sprintf(function_exists('lupo_t') ? lupo_t('layout.main_layout.new_sub_tab', '+ New Sub-Tab for %s') : '+ New Sub-Tab for %s', $main_tab), ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                    <hr>
                    <a href="javascript:void(0)" class="add-action global" onclick="addNewItem('main')"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.create_main', '+ Create new main tab') : '+ Create new main tab', ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
            </div>
        </div>

        <!-- Contents Dropdown -->
        <div class="dropdown">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/contents.png" width="42" height="42" onclick="toggleMenu('contentsDropdown', event)" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.contents_trigger_alt', 'Page contents') : 'Page contents', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.contents_trigger_alt', 'Page contents') : 'Page contents', ENT_QUOTES, 'UTF-8'); ?>">
            <div id="contentsDropdown" class="dropdown-content">
                <?php if (!empty($contentSections) && is_array($contentSections)): ?>
                    <?php foreach ($contentSections as $section): ?>
                        <?php
                        $section_anchor = isset($section['anchor']) ? $section['anchor'] : '';
                        $section_title = isset($section['title']) ? $section['title'] : '';
                        if ($section_anchor):
                        ?>
                            <a href="#<?= htmlspecialchars($section_anchor) ?>"><?= htmlspecialchars($section_title) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="#" style="color: #999; font-style: italic;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.no_sections', 'No sections available') : 'No sections available', ENT_QUOTES, 'UTF-8'); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Page Title -->
        <h1 id="firstHeading" class="firstHeading mw-first-heading">
            <span class="mw-page-title-main"><?= htmlspecialchars($page_title) ?></span>
        </h1>
        &nbsp;
        <div class="semantic-nav-title-triggers" style="display: flex; align-items: center; margin-left: auto; gap: 4px;">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/semantic_show_icon.png" width="194" height="42" class="semantic-show-nav-trigger edges-responsive" style="cursor:pointer;" role="button" tabindex="0" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.semantic_show_alt', 'Show footer semantic bar') : 'Show footer semantic bar', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.semantic_show_alt', 'Show footer semantic bar') : 'Show footer semantic bar', ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/semantic_hide_icon.png" width="44" height="42" class="semantic-hide-nav-trigger edges-responsive" role="button" tabindex="0" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.semantic_hide_alt', 'Hide footer semantic bar') : 'Hide footer semantic bar', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.semantic_hide_alt', 'Hide footer semantic bar') : 'Hide footer semantic bar', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
    </div>
    <div class="resources-top-right"></div>
    
    <!-- Row 2: Middle Border and Content -->
    <div class="resources-middle-left"></div>
    <div class="resources-middle-center"<?php
    $lupo_at = '';
    $lupo_mk = '';
    $lupo_edge_focus = '';
    if (!empty($semantic_widget_context) && is_array($semantic_widget_context)) {
        $lupo_at = isset($semantic_widget_context['artifact_type']) ? (string) $semantic_widget_context['artifact_type'] : '';
        $lupo_mk = isset($semantic_widget_context['memory_key']) ? (string) $semantic_widget_context['memory_key'] : '';
        if ($lupo_at === 'help_guide') {
            $lupo_edge_focus = 'tutorial';
        } elseif ($lupo_at === 'text/markdown') {
            $lupo_edge_focus = 'transcript';
        }
    }
    if ($lupo_at !== '' || $lupo_mk !== '') {
        echo ' data-artifact-type="' . htmlspecialchars($lupo_at, ENT_QUOTES, 'UTF-8') . '"';
        echo ' data-memory-key="' . htmlspecialchars($lupo_mk, ENT_QUOTES, 'UTF-8') . '"';
    }
    if ($lupo_edge_focus !== '') {
        echo ' data-edge-focus="' . htmlspecialchars($lupo_edge_focus, ENT_QUOTES, 'UTF-8') . '"';
    }
    ?>>
        <div class="book-page-inner">
            <?php
            $lupo_has_toc = !empty($contentSections) && is_array($contentSections);
            $lupo_toc_aria = function_exists('lupo_t') ? lupo_t('layout.main_layout.page_toc_aria', 'On this page') : 'On this page';
            ?>
            <?php if ($lupo_has_toc): ?>
            <aside class="book-page-sidebar" aria-label="<?php echo htmlspecialchars($lupo_toc_aria, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="book-page-sidebar-inner">
                    <div class="book-page-sidebar-title"><?php echo htmlspecialchars($lupo_toc_aria, ENT_QUOTES, 'UTF-8'); ?></div>
                    <nav class="book-page-toc">
                        <ul>
                            <?php foreach ($contentSections as $section): ?>
                                <?php
                                $section_anchor = isset($section['anchor']) ? $section['anchor'] : '';
                                $section_title = isset($section['title']) ? $section['title'] : '';
                                if ($section_anchor === '' || $section_title === '') {
                                    continue;
                                }
                                ?>
                                <li><a href="#<?php echo htmlspecialchars($section_anchor, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($section_title, ENT_QUOTES, 'UTF-8'); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </aside>
            <?php endif; ?>
            <div class="book-page-main">
                <?= $page_body ?>
            </div>
        </div>
    </div>
    <div class="resources-middle-right"></div>
    
    <!-- Row 3: 9-slice bottom border (book chrome only; interactive semantic bar is footer.php .semantic-nav-bar). -->
    <div class="resources-bottom-left"></div>
    <div class="resources-bottom-center">
  </div>
    <div class="resources-bottom-right"></div>
</div>
</main>
<?php endif; ?>

<?php if ($hide_semantic_nav): ?>
<!-- Channel Page: Simple Container for Content -->
<div style="width: 100%; height: calc(100vh - 60px); position: fixed; top: 60px; left: 0; overflow: hidden;">
    <?= $page_body ?>
</div>
<?php endif; ?>

<?php 
// Footer
if (file_exists(LUPO_UI_PATH . '/components/footer.php')) {
   if (!$hide_semantic_nav){
     include LUPO_UI_PATH . '/components/footer.php';
     }
}
?>

<?php if (!$hide_semantic_nav): ?>
<script>
(function () {
    function lupoEyeAnchorFromBook() {
        var el = document.querySelector('.resources-middle-center');
        if (!el || typeof window === 'undefined') {
            return;
        }
        var r = el.getBoundingClientRect();
        var w = 520;
        var h = 320;
        window.wheretoX = Math.max(0, Math.round(r.left + r.width - w));
        window.wheretoY = Math.max(0, Math.round(r.top + r.height - h));
    }
    document.addEventListener('DOMContentLoaded', function () {
        lupoEyeAnchorFromBook();
        window.addEventListener('resize', lupoEyeAnchorFromBook);
        window.addEventListener('scroll', lupoEyeAnchorFromBook, true);
        if (typeof LupoLayerInit === 'function') {
            LupoLayerInit();
        } else if (typeof DynLayerInit === 'function') {
            DynLayerInit();
        }
    });
})();
</script>
<?php endif; ?>

<?php
// Load UI JavaScript at end of body
if (file_exists(LUPOPEDIA_PATH . '/includes/ui/ui-loader.php')) {
    if (function_exists('lupo_print_ui_js')) {
        lupo_print_ui_js();
    }
}
?>

</body>
</html>
