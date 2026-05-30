<?php
/**
 * Try2 collections chrome: .dropdown / .dropdown-panel / .floating-submenu.
 * Expects: LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH, $collectionsData, $collection_id, $tabs_data, lupo_t optional.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. saved-collections-nav-try2.php cannot be called directly.');
}

$lupo_try2_funcs = __DIR__ . '/saved-collections-nav-try2-functions.php';
if (is_file($lupo_try2_funcs)) {
    require_once $lupo_try2_funcs;
}

$lupo_try2_ctl = defined('LUPOPEDIA_PATH') ? (LUPOPEDIA_PATH . '/includes/functions/collection-tabs-loader.php') : '';
if ($lupo_try2_ctl !== '' && is_file($lupo_try2_ctl) && !function_exists('lupo_nav_menu_collection_groups')) {
    require_once $lupo_try2_ctl;
}

$lupo_try2_pub = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';

$lupo_master_nav_groups = function_exists('lupo_nav_menu_collection_groups') ? lupo_nav_menu_collection_groups() : array();
$lupo_master_nav_leaf_count = 0;
if (!empty($lupo_master_nav_groups) && is_array($lupo_master_nav_groups)) {
    foreach ($lupo_master_nav_groups as $lupo_mng) {
        if (!empty($lupo_mng['items']) && is_array($lupo_mng['items'])) {
            $lupo_master_nav_leaf_count += count($lupo_mng['items']);
        }
    }
}

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
if ($lupo_master_nav_leaf_count > 0) {
    $lupo_master_count = $lupo_master_nav_leaf_count;
}
?>
<div style="width: 50px; height: 40px;"></div>

<div class="saved-collections-container debug-try2-inner">
    <div class="dropdown master-collections-wrap" id="try2-master" data-qa-type="collections">
        <button type="button"
                class="dropdown-button btn-blue"
                onclick="lupoDbgNavToggle(this, event)"
                aria-expanded="false"
                aria-haspopup="true"
                aria-controls="try2-master-panel"
                data-qa-type="collections">
            <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.dropdown_label', 'Collections') : 'Collections', ENT_QUOTES, 'UTF-8'); ?>
            <span class="count-badge"><?php echo (int) $lupo_master_count; ?></span>
        </button>
        <div class="dropdown-panel" id="try2-master-panel" role="menu"<?php if (!empty($lupo_master_nav_groups)) { ?> data-master-nav-server="1" data-hydrated="1"<?php } ?>>
            <?php
            if (!empty($lupo_master_nav_groups) && function_exists('lupo_try2_render_master_nav_groups')) {
                lupo_try2_render_master_nav_groups($lupo_master_nav_groups);
            } elseif (!empty($lupo_master_collections['tabs']) && is_array($lupo_master_collections['tabs'])) {
                lupo_try2_render_tabs_for_type($lupo_master_collections['tabs'], $lupo_try2_pub);
            }
            ?>
        </div>
    </div>
    <?php
    $lupo_try2_exclude = array();
    if (isset($all_user_collections['collections'])) {
        $lupo_try2_exclude[] = 'collections';
    }
    if (!empty($collectionsData) && is_array($collectionsData)) {
        foreach ($collectionsData as $dbg_type => $dbg_collection_type_data) {
            if (in_array($dbg_type, $lupo_try2_exclude, true)) {
                continue;
            }
            $dbg_count = isset($dbg_collection_type_data['count']) ? (int) $dbg_collection_type_data['count'] : 0;
            $dbg_label = strtoupper((string) $dbg_type);
            if ($dbg_type === 'collections' && function_exists('lupo_t')) {
                $dbg_label = lupo_t('header.collections.dropdown_label', 'Collections');
            }
            ?>
            <div class="dropdown" data-qa-type="<?php echo htmlspecialchars($dbg_type, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="button" class="dropdown-button btn-green"
                        onclick="lupoDbgNavToggle(this, event)"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-qa-type="<?php echo htmlspecialchars($dbg_type, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($dbg_label, ENT_QUOTES, 'UTF-8'); ?> <span class="count-badge"><?php echo (int) $dbg_count; ?></span>
                </button>
                <div class="dropdown-panel" role="menu">
                    <?php
                    if (!empty($dbg_collection_type_data['tabs']) && is_array($dbg_collection_type_data['tabs'])) {
                        lupo_try2_render_tabs_for_type($dbg_collection_type_data['tabs'], $lupo_try2_pub);
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
                <div class="dropdown" data-qa-type="<?php echo htmlspecialchars($qa_type, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="button" class="dropdown-button btn-green"
                            onclick="lupoDbgNavToggle(this, event)"
                            aria-expanded="false"
                            aria-haspopup="true"
                            data-qa-type="<?php echo htmlspecialchars($qa_type, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars(strtoupper($main_tab_label), ENT_QUOTES, 'UTF-8'); ?> <span class="count-badge"><?php
                        echo (int) (function_exists('lupo_try2_green_tab_badge_count') ? lupo_try2_green_tab_badge_count($sub_tabs) : 0);
                        ?></span>
                    </button>
                    <div class="dropdown-panel" role="menu">
                        <?php
                        if (is_array($sub_tabs) && function_exists('lupo_try2_render_green_collection_tab_panel')) {
                            lupo_try2_render_green_collection_tab_panel($sub_tabs, $lupo_try2_pub);
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div style="margin-left: auto; display: flex; gap: 8px; flex-shrink: 0;">
        <button type="button" class="recently-viewed-button" onclick="checkLoginAndSave()" style="background: #28a745; border: 1px solid #28a745; color: #fff; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold;">
            <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.btn_save', 'Save') : 'Save', ENT_QUOTES, 'UTF-8'); ?>
        </button>
        <button type="button" class="recently-viewed-button" onclick="checkLoginAndLoad()" style="background: #17a2b8; border: 1px solid #17a2b8; color: #fff; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold;">
            <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.btn_load', 'Load') : 'Load', ENT_QUOTES, 'UTF-8'); ?>
        </button>
        <button type="button" class="recently-viewed-button" id="editCollectionBtn" onclick="checkLoginAndEdit()" style="background: #ffc107; border: 1px solid #ffc107; color: #000; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold;">
            <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.btn_edit', 'Edit') : 'Edit', ENT_QUOTES, 'UTF-8'); ?>
        </button>
    </div>
    <script>
    function lupoMainLayoutHdrStr(key, fallback) {
        if (window.LUPO_HDR && window.LUPO_HDR.strings && window.LUPO_HDR.strings[key]) {
            return window.LUPO_HDR.strings[key];
        }
        return fallback;
    }
    function checkLoginAndSave() {
        if (!isUserLoggedIn) {
            alert(lupoMainLayoutHdrStr('collections_save_login', 'Please log in to save collections.'));
            return false;
        }
        if (typeof showSaveCollectionModal === 'function') {
            showSaveCollectionModal();
        }
    }
    function checkLoginAndLoad() {
        if (!isUserLoggedIn) {
            alert(lupoMainLayoutHdrStr('collections_load_login', 'Please log in to load collections.'));
            return false;
        }
        if (typeof showLoadCollectionModal === 'function') {
            showLoadCollectionModal();
        }
    }
    function checkLoginAndEdit() {
        if (!isUserLoggedIn) {
            alert(lupoMainLayoutHdrStr('collections_edit_login', 'Please log in to edit collections.'));
            return false;
        }
        if (typeof editCurrentCollection === 'function') {
            editCurrentCollection();
        }
    }
    </script>
</div>
