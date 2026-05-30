<?php
/**
---
wolfie.headers.version: "3.0.12"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.12: Version bump for hierarchical tab structure implementation. No logic changes to collection_tabs.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.11: Fixed collection tabs component to filter out _slug metadata key when iterating sub-tabs. Component now correctly displays tabs without showing metadata keys. Uses collection_id from context for URL generation."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.10: Updated collection tabs component to render tabs as clickable links using LUPOPEDIA_PUBLIC_PATH. Tab URLs follow pattern /collection/0/tab/{slug} for Collection 0 system tabs."
    mood: "00FF00"
  - speaker: Wolfie
    target: collection-tabs
    message: "Created collection tabs component: renders the tabs dropdown menu with main-tab and sub-tab structure. Extracted from header.php mockup (shortcutDropdown)."
    mood: "336699"
tags:
  categories: ["component", "ui", "collections"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "Collection Tabs Component"
  description: "Renders the tabs dropdown menu with main-tab and sub-tab structure. Version 3.0.10: Collection 0 tabs with clickable links."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collection_tabs.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Collection Tabs Component
 * ---------------------------------------------------------
 * 
 * Renders the tabs dropdown menu with main-tab and sub-tab structure.
 * This is the shortcut dropdown that shows the current collection's tabs.
 */

// Get current collection name (default to System Collection)
$current_collection = isset($current_collection) ? $current_collection : 'System Collection';

// Get tabs data (should be passed from controller)
$tabs_data = isset($tabs_data) ? $tabs_data : [];

// Debug: Log if tabs_data is empty (only in debug mode)
if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG && empty($tabs_data)) {
    error_log('Collection tabs component: tabs_data is empty. Collection ID: ' . (isset($collection_id) ? $collection_id : 'not set'));
}

?>
<!-- Collection Tabs Component (Dropdown Menu Only) -->
<!-- Note: Horizontal tab bar is rendered directly in main_layout.php -->

<!-- Original Dropdown Component (kept for backward compatibility) -->
<div class="dropdown">
    <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/addshortcut.png" 
         width="42" 
         height="42" 
         onclick="toggleMenu('shortcutDropdown', event)" 
         style="cursor:pointer;" 
         alt="Add Shortcut"> 
    <div id="shortcutDropdown" class="dropdown-content">
        <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
            <b>Current Collection:</b> <?= htmlspecialchars($current_collection) ?><br>
            Click on the name of the tab or subtab you would like to add this shortcut to. Use the blue collections tab to select a different collection.
        </div>

        <?php 
        // Get collection ID from context (may be null if not specified)
        $collection_id = isset($collection_id) && $collection_id !== null ? (int)$collection_id : null;
        ?>
        <?php if (empty($tabs_data)): ?>
            <!-- Debug: No tabs data available -->
            <div style="padding: 10px; color: #999; font-style: italic;">
                No tabs available for this collection.
            </div>
        <?php endif; ?>
        <?php foreach ($tabs_data as $main_tab => $sub_tabs): ?>
            <?php
            // Version 3.0.11: Use actual tab slug from database if available
            // Fallback to generated slug if tabs_data includes slug info
            $tab_slug = null;
            if (is_array($sub_tabs) && isset($sub_tabs['_slug'])) {
                $tab_slug = $sub_tabs['_slug'];
            } else {
                // Generate slug from tab name (fallback)
                $tab_slug = strtolower(str_replace(' ', '-', $main_tab));
            }
            // Only generate URL if collection_id is set
            $tab_url = ($collection_id !== null) 
                ? LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $tab_slug
                : '#';
            
            $root_tab_id = (is_array($sub_tabs) && isset($sub_tabs['_collection_tab_id'])) ? (int) $sub_tabs['_collection_tab_id'] : 0;
            ?>
            <a href="javascript:void(0)" class="main-tab shortcut-pin" role="button" data-collection-tab-id="<?= $root_tab_id ?>" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">| <?= htmlspecialchars($main_tab) ?></a>
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
            <?php elseif (is_array($sub_tabs)): ?>
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
            <?php
            $has_subs = false;
            if (!empty($sub_tabs['_children']) && is_array($sub_tabs['_children'])) {
                $has_subs = count($sub_tabs['_children']) > 0;
            } elseif (is_array($sub_tabs)) {
                foreach ($sub_tabs as $key => $value) {
                    if (is_string($key) && strlen($key) > 0 && $key[0] === '_') {
                        continue;
                    }
                    if (is_string($value)) {
                        $has_subs = true;
                        break;
                    }
                }
            }
            ?>
            <?php if ($has_subs): ?>
                <a href="javascript:void(0)" 
                   class="add-action" 
                   onclick="addNewItem('sub', '<?= htmlspecialchars($main_tab) ?>')">
                    + New Sub-Tab for <?= htmlspecialchars($main_tab) ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
        
        <hr>
        <a href="javascript:void(0)" 
           class="add-action global" 
           onclick="addNewItem('main')">
            + Create New Main Tab
        </a>
    </div>
</div>
