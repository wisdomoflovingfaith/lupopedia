<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: layout
  when_updated: "20260406044907"
  file_path_from_root: "lupo-includes/themes/default/layouts/main_layout.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/themes/default/layouts/main_layout.php"
  last_modified_utc: "20260406044907"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "layout"
  artifact_kind: "main"
  purpose: "Main Lupopedia UI layout; collections chrome, modals, shortcut/contents dropdowns, bottom bar; lupo_t and LUPO_HDR.strings for JS."
  tags: ["layout", "ui", "collections", "tabs", "locale"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. main_layout.php cannot be called directly.");
}

$root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '';
if ($root !== '' && !class_exists('LupoLocale', false)) {
    $lp = $root . '/lupo-includes/classes/LupoLocale.php';
    if (is_file($lp)) {
        require_once $lp;
    }
}
if ($root !== '' && class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
    LupoLocale::bootstrap($root);
}
if (!function_exists('lupo_t')) {
    $i18n = ($root !== '' ? $root . '/lupo-includes/lupo-i18n.php' : '');
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
    $theme_layout = LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/main_layout.php';
    define('LUPO_UI_PATH', (file_exists($theme_layout) ? LUPOPEDIA_PATH . '/lupo-includes/themes/default' : LUPOPEDIA_PATH . '/lupo-includes/ui'));
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

// Load collections data for saved collections nav
if (!isset($collectionsData)) {
    if (function_exists('render_saved_collections')) {
        $collectionsData = render_saved_collections($currentUserId);
    } else {
        // Load the function if available
        $renderer_path = LUPOPEDIA_PATH . '/lupo-includes/functions/render-saved-collections.php';
        if (file_exists($renderer_path)) {
            require_once $renderer_path;
            $collectionsData = render_saved_collections($currentUserId);
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
if (!isset($collection_id) || $collection_id === null) {
    $collection_id = (int) $GLOBALS['collection_id'];
}

// Initialize content sections for contents dropdown
if (!isset($contentSections)) {
    $contentSections = isset($content['content_sections']) ? $content['content_sections'] : array();
}

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
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main.css">
    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/lupopedia.js"></script>
    
    <?php
    // Load UI assets (CSS and JS) from ui-loader.php
    if (!function_exists('lupo_print_ui_css')) {
        if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/ui/ui-loader.php')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/ui/ui-loader.php';
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
    
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main-layout.css">
    <style>
    /* Decorative border tile backgrounds need LUPOPEDIA_PUBLIC_PATH (subdirectory installs). */
    /* Row 1: Top Border */
    .resources-top-left {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s1b.png');
    }

    .resources-top-center {
        width: calc(100vw - 118px);
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s2b.png');
        background-repeat: repeat;
        display: flex;
        align-items: flex-start;
    }

    .resources-top-right {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s3b.png');
    }

    /* Row 2: Middle Border and Content */
    .resources-middle-left {
        width: 54px;
        height: calc(100vh - 107px - 78px);
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s4b.png');
        background-repeat: repeat-y;
    }

    .resources-middle-center {
        width: calc(100vw - 118px);
        height: calc(100vh - 107px - 78px);
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s5.png');
        background-repeat: repeat;
        overflow-y: auto;
        padding: 20px;
    }

    .resources-middle-right {
        width: 54px;
        height: calc(100vh - 107px - 78px);
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s6b.png');
        background-repeat: repeat-y;
    }

    /* Row 3: Bottom Border */
    .resources-bottom-left {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s7b.png');
    }

    .resources-bottom-center {
        width: calc(100vw - 118px);
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s8b.png');
        background-repeat: repeat;
    }

    .resources-bottom-right {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/s9b.png');
    }

    </style>

    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/main-layout.js"></script>
</head>
<body>

<?php
// Top navigation bar
if (file_exists(LUPO_UI_PATH . '/components/topbar.php')) {
    include LUPO_UI_PATH . '/components/topbar.php';
}

// Determine if semantic nav bar should be hidden (channel staff interface)
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

<?php if (!$hide_semantic_nav): ?>
<input type="hidden" id="active-collection-id" name="active_collection_id" value="<?= $collection_id !== null ? (int) $collection_id : 0 ?>">
<!-- Saved Collections Navigation -->
<nav class="saved-collections-nav" data-collection-id="<?= $collection_id !== null ? (int) $collection_id : 0 ?>">
    <!-- Spacer div -->
    <div style="width: 50px; height: 40px;"></div>

    <div class="saved-collections-container">
        <!-- Tabs loaded by AJAX starts here -->
        <div id="collection-tabs-container">
            <?php
            // Render tabs if tabs_data is available (collection_id 0 = System Collection)
            if ($collection_id !== null && !empty($tabs_data) && is_array($tabs_data)) {
    foreach ($tabs_data as $main_tab => $sub_tabs) {
        $tab_slug = null;
        if (is_array($sub_tabs) && isset($sub_tabs['_slug'])) {
            $tab_slug = $sub_tabs['_slug'];
        } else {
            $tab_slug = strtolower(str_replace(' ', '-', $main_tab));
        }
                    $dropdownId = 'dropdown-' . strtolower(str_replace(' ', '-', $main_tab));
                    ?>
                    <div class="saved-collections-dropdown" data-qa-type="<?= htmlspecialchars(strtolower(str_replace(' ', '-', $main_tab))) ?>">
                        <button class="saved-collections-button" 
                                onclick="toggleSavedCollectionsDropdown(this)"
                                aria-expanded="false"
                                aria-haspopup="true"
                                aria-controls="<?= htmlspecialchars($dropdownId) ?>"
                                data-qa-type="<?= htmlspecialchars(strtolower(str_replace(' ', '-', $main_tab))) ?>">
                            <?= htmlspecialchars(strtoupper($main_tab)) ?> <span class="count"><?php 
                                $childCount = 0;
                                if (is_array($sub_tabs)) {
                                    foreach ($sub_tabs as $key => $value) {
                                        if ($key !== '_slug') {
                                            $childCount++;
                                        }
                                    }
                                }
                                echo $childCount;
                            ?></span>
                        </button>
                        <div class="saved-collections-dropdown-content" 
                             id="<?= htmlspecialchars($dropdownId) ?>"
                             role="menu">
                            <?php
                            // Render sub-tabs if available
                            if (is_array($sub_tabs)) {
                                foreach ($sub_tabs as $key => $value) {
                                    if ($key !== '_slug') {
                                        $sub_tab_slug = strtolower(str_replace(' ', '-', $value));
                                        $sub_tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $sub_tab_slug;
                                        ?>
                                        <a href="<?= htmlspecialchars($sub_tab_url) ?>" 
                                           class="saved-collections-item"
                                           role="menuitem"
                                           tabindex="0">
                                            <?= htmlspecialchars($value) ?>
                                        </a>
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
        <!-- Tabs loaded by AJAX ends here -->
    </div>
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
)); ?>;
</script>
<script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/main-layout-collections.js"></script>

<?php if (!$hide_semantic_nav): ?>
<!-- Content Container with Decorative Borders -->
<div class="content-list-container">
    <!-- Row 1: Top Border -->
    <div class="resources-top-left"></div>
    <div class="resources-top-center">
        <!-- Shortcut Dropdown -->
        <div class="dropdown">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/addshortcut.png" width="42" height="42" onclick="toggleMenu('shortcutDropdown')" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.shortcut_trigger_alt', 'Add shortcut') : 'Add shortcut', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.shortcut_trigger_alt', 'Add shortcut') : 'Add shortcut', ENT_QUOTES, 'UTF-8'); ?>"> 
            <div id="shortcutDropdown" class="dropdown-content">
                <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
                    <b><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.current_label', 'Current collection:') : 'Current collection:', ENT_QUOTES, 'UTF-8'); ?></b> <span id="current-collection-display"><?= htmlspecialchars($current_collection) ?></span><br>
                    <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.instructions', 'Click the tab or sub-tab name to add this shortcut; use the blue collections control to pick a different collection.') : 'Click the tab or sub-tab name to add this shortcut; use the blue collections control to pick a different collection.', ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div id="shortcut-tabs-list">
                    <?php if (!empty($tabs_data) && is_array($tabs_data)): ?>
                        <?php foreach ($tabs_data as $main_tab => $sub_tabs): ?>
                            <?php
                            $tab_slug = null;
                            if (is_array($sub_tabs) && isset($sub_tabs['_slug'])) {
                                $tab_slug = $sub_tabs['_slug'];
                            } else {
                                $tab_slug = strtolower(str_replace(' ', '-', $main_tab));
                            }
                            $tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $tab_slug;
                            ?>
                            <a href="<?= htmlspecialchars($tab_url) ?>" class="main-tab">| <?= htmlspecialchars($main_tab) ?></a>
                            <?php if (is_array($sub_tabs)): ?>
                                <?php foreach ($sub_tabs as $key => $value): ?>
                                    <?php if ($key !== '_slug'): ?>
                                        <?php
                                        $sub_tab_slug = strtolower(str_replace(' ', '-', $value));
                                        $sub_tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $sub_tab_slug;
                                        ?>
                                        <a href="<?= htmlspecialchars($sub_tab_url) ?>" class="sub-tab">|— <?= htmlspecialchars($value) ?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <a href="javascript:void(0)" class="add-action" onclick="addNewItem('sub', <?php echo json_encode($main_tab); ?>)"><?php echo htmlspecialchars(sprintf(function_exists('lupo_t') ? lupo_t('layout.main_layout.new_sub_tab', '+ New Sub-Tab for %s') : '+ New Sub-Tab for %s', $main_tab), ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <hr>
                    <a href="javascript:void(0)" class="add-action global" onclick="addNewItem('main')"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.create_main', '+ Create new main tab') : '+ Create new main tab', ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
            </div>
        </div>

        <!-- Contents Dropdown -->
        <div class="dropdown">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/contents.png" width="42" height="42" onclick="toggleMenu('contentsDropdown')" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.contents_trigger_alt', 'Page contents') : 'Page contents', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.contents_trigger_alt', 'Page contents') : 'Page contents', ENT_QUOTES, 'UTF-8'); ?>">
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
        <div style="display: flex; align-items: right; margin-left: auto;">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/edges.png" width="194" height="42" style="cursor:pointer; margin-left: auto;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.edges_alt', 'Edges') : 'Edges', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.edges_alt', 'Edges') : 'Edges', ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/help.png" width="44" height="42" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.help_alt', 'Help') : 'Help', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.help_alt', 'Help') : 'Help', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
    </div>
    <div class="resources-top-right"></div>
    
    <!-- Row 2: Middle Border and Content -->
    <div class="resources-middle-left"></div>
    <div class="resources-middle-center">
        <!-- Page Content -->
        <?= $page_body ?>
    </div>
    <div class="resources-middle-right"></div>
    
    <!-- Row 3: Bottom Border -->
    <div class="resources-bottom-left"></div>
    <div class="resources-bottom-center">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/prevpage.png" width="32" height="32" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_prev', 'Previous page') : 'Previous page', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_prev', 'Previous page') : 'Previous page', ENT_QUOTES, 'UTF-8'); ?>" <?php if ($prevContent): ?>onclick="window.location.href='<?= htmlspecialchars($prevContent['url'] ?? '#') ?>'" style="cursor:pointer;"<?php endif; ?>>
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/references.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_references', 'References') : 'References', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_references', 'References') : 'References', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/context.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_context', 'Context') : 'Context', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_context', 'Context') : 'Context', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/hashtag.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_tags', 'Tags') : 'Tags', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_tags', 'Tags') : 'Tags', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/share.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_share', 'Share') : 'Share', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_share', 'Share') : 'Share', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/like.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_like', 'Like') : 'Like', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_like', 'Like') : 'Like', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/comment.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_comment', 'Comment') : 'Comment', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_comment', 'Comment') : 'Comment', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/links.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_links', 'Links') : 'Links', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_links', 'Links') : 'Links', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/folder.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_folder', 'Folder') : 'Folder', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_folder', 'Folder') : 'Folder', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/atoms.png" width="32" height="32" style="cursor:pointer;" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_atoms', 'Atoms') : 'Atoms', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_atoms', 'Atoms') : 'Atoms', ENT_QUOTES, 'UTF-8'); ?>">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/nextpage.png" width="32" height="32" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_next', 'Next page') : 'Next page', ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('layout.main_layout.icon_next', 'Next page') : 'Next page', ENT_QUOTES, 'UTF-8'); ?>" <?php if ($nextContent): ?>onclick="window.location.href='<?= htmlspecialchars($nextContent['url'] ?? '#') ?>'" style="cursor:pointer;"<?php endif; ?>>
    </div>
    <div class="resources-bottom-right"></div>
</div>
<?php endif; ?>

<?php if ($hide_semantic_nav): ?>
<!-- Channel Page: Simple Container for Content -->
<div style="width: 100%; height: calc(100vh - 60px); position: fixed; top: 60px; left: 0; overflow: hidden;">
    <?= $page_body ?>
</div>
<?php endif; ?>

<!-- Auto-load collection tabs on page load: read active collection_id (default 0), call loadCollectionTabs so tabs populate -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('collection-tabs-container');
    if (!container) return;
    var input = document.getElementById('active-collection-id');
    var rawId = input ? input.value : null;
    if (rawId === null || rawId === '') rawId = '0';
    var collectionId = parseInt(rawId, 10);
    if (isNaN(collectionId) || collectionId < 0) collectionId = 0;
    var tabsData = <?php echo !empty($tabs_data) ? json_encode($tabs_data) : 'null'; ?>;
    var tabsEmpty = !tabsData || (typeof tabsData === 'object' && Object.keys(tabsData).length === 0);

    if (tabsEmpty && typeof window.loadCollectionTabs === 'function') {
        window.loadCollectionTabs(collectionId, <?php echo json_encode($current_collection !== null && $current_collection !== '' ? $current_collection : (function_exists('lupo_t') ? lupo_t('layout.main_layout.system_collection', 'System Collection') : 'System Collection')); ?>);
    }
});
</script>

<?php 
// Footer
if (file_exists(LUPO_UI_PATH . '/components/footer.php')) {
   if (!$hide_semantic_nav){
     include LUPO_UI_PATH . '/components/footer.php';
     }
}

// Load UI JavaScript at end of body
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/ui/ui-loader.php')) {
    if (function_exists('lupo_print_ui_js')) {
        lupo_print_ui_js();
    }
}
?>

</body>
</html>
