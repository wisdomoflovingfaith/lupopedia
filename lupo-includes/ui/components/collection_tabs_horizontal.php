<?php
/**
---
wolfie.headers.version: "4.0.11"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Created horizontal collection tabs navigation bar component. Renders Collection 0 system tabs as a visible horizontal navigation bar at the top of the page."
    mood: "00FF00"
tags:
  categories: ["component", "ui", "collections"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "Collection Tabs Horizontal Navigation"
  description: "Horizontal navigation bar for Collection 0 system tabs"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collection_tabs_horizontal.php cannot be called directly.");
}

// Get variables with defaults
$current_collection = isset($current_collection) ? $current_collection : 'System Collection';
$tabs_data = isset($tabs_data) ? $tabs_data : [];
$collection_id = isset($collection_id) && $collection_id !== null ? (int)$collection_id : null;

// Only render if we have tabs data and collection_id is set
if ($collection_id !== null && $collection_id > 0 && !empty($tabs_data) && is_array($tabs_data)) {
    echo '<div class="collection-tabs-nav" style="background: #4CAF50; padding: 10px 0; margin: 0; border-bottom: 2px solid #2E7D32; width: 100%; position: relative; z-index: 1000;">';
    echo '<div style="max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 5px; align-items: center; padding: 0 20px;">';
    
    foreach ($tabs_data as $main_tab => $sub_tabs) {
        // Get tab slug
        $tab_slug = null;
        if (is_array($sub_tabs) && isset($sub_tabs['_slug'])) {
            $tab_slug = $sub_tabs['_slug'];
        } else {
            $tab_slug = strtolower(str_replace(' ', '-', $main_tab));
        }
        
        $tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $tab_slug;
        $tab_name = htmlspecialchars($main_tab, ENT_QUOTES, 'UTF-8');
        $tab_url_escaped = htmlspecialchars($tab_url, ENT_QUOTES, 'UTF-8');
        
        echo '<a href="' . $tab_url_escaped . '" ';
        echo 'class="collection-tab" ';
        echo 'style="display: inline-block; padding: 8px 16px; background: #66BB6A; color: white; text-decoration: none; border-radius: 4px; font-weight: 500; transition: background 0.2s; white-space: nowrap;" ';
        echo 'onmouseover="this.style.background=\'#81C784\'" ';
        echo 'onmouseout="this.style.background=\'#66BB6A\'">';
        echo $tab_name;
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

?>
