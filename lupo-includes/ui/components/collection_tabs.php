<?php
/**
---
wolfie.headers.version: "4.0.12"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.12: Version bump for hierarchical tab structure implementation. No logic changes to collection_tabs.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Fixed collection tabs component to filter out _slug metadata key when iterating sub-tabs. Component now correctly displays tabs without showing metadata keys. Uses collection_id from context for URL generation."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.10: Updated collection tabs component to render tabs as clickable links using LUPOPEDIA_PUBLIC_PATH. Tab URLs follow pattern /collection/0/tab/{slug} for Collection 0 system tabs."
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
  description: "Renders the tabs dropdown menu with main-tab and sub-tab structure. Version 4.0.10: Collection 0 tabs with clickable links."
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
         onclick="toggleMenu('shortcutDropdown')" 
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
            // Version 4.0.11: Use actual tab slug from database if available
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
            
            // Filter out metadata keys (_slug) from sub_tabs for display
            $display_sub_tabs = [];
            if (is_array($sub_tabs)) {
                foreach ($sub_tabs as $key => $value) {
                    if ($key !== '_slug') {
                        $display_sub_tabs[] = $value;
                    }
                }
            }
            ?>
            <a href="<?= htmlspecialchars($tab_url) ?>" class="main-tab">| <?= htmlspecialchars($main_tab) ?></a>
            <?php foreach ($display_sub_tabs as $sub_tab): ?>
                <?php
                $sub_tab_slug = strtolower(str_replace(' ', '-', $sub_tab));
                // Only generate URL if collection_id is set
                $sub_tab_url = ($collection_id !== null)
                    ? LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $sub_tab_slug
                    : '#';
                ?>
                <a href="<?= htmlspecialchars($sub_tab_url) ?>" class="sub-tab">|— <?= htmlspecialchars($sub_tab) ?></a>
            <?php endforeach; ?>
            <?php if (!empty($display_sub_tabs)): ?>
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
