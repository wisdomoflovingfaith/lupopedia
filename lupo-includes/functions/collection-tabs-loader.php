<?php
/**
---
wolfie.headers.version: "4.0.18"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.18: Updated load_collection_tabs() to load child tabs from database. Now loads root-level tabs AND their child tabs using collection_tab_parent_id. Properly counts child tabs excluding _slug metadata."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.12: Version bump for hierarchical tab structure implementation. No logic changes to collection-tabs-loader.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Updated collection tabs loader to include tab slug in tabs data structure. Stores slug as _slug key in sub-tabs array for URL generation in collection_tabs.php component."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.10: Created collection tabs loader function to load and format tabs for Collection 0 (System Collection). Formats tabs for collection_tabs.php component display."
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
 * Load collection tabs for a given collection ID
 * 
 * Version 4.0.10: Loads tabs for Collection 0 (System Collection) and formats
 * them for the collection_tabs.php component.
 * 
 * @param int $collection_id Collection ID (0 for System Collection)
 * @return array Formatted tabs data for collection_tabs.php component
 */
function load_collection_tabs($collection_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    $tabs_data = [];
    
    try {
        // Load root-level tabs (no parent) for this collection
        $sql = "SELECT 
                    collection_tab_id,
                    name,
                    slug,
                    sort_order,
                    collection_tab_parent_id
                FROM {$table_prefix}collection_tabs
                WHERE collection_id = :collection_id
                  AND collection_tab_parent_id IS NULL
                  AND is_active = 1
                  AND is_deleted = 0
                ORDER BY sort_order ASC, name ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':collection_id' => $collection_id]);
        $tabs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format tabs for collection_tabs.php component
        // Component expects: array('Main Tab Name' => ['Sub Tab 1', 'Sub Tab 2', ..., '_slug' => 'tab-slug'])
        // Version 4.0.11: Include slug in tab data for URL generation
        foreach ($tabs as $tab) {
            $tab_name = $tab['name'];
            $tab_id = $tab['collection_tab_id'];
            
            // Load child tabs (sub-tabs) for this parent tab
            $childTabsSql = "SELECT 
                                collection_tab_id,
                                name,
                                slug,
                                sort_order
                            FROM {$table_prefix}collection_tabs
                            WHERE collection_id = :collection_id
                              AND collection_tab_parent_id = :parent_tab_id
                              AND is_active = 1
                              AND is_deleted = 0
                            ORDER BY sort_order ASC, name ASC";
            
            $childStmt = $db->prepare($childTabsSql);
            $childStmt->execute([
                ':collection_id' => $collection_id,
                ':parent_tab_id' => $tab_id
            ]);
            $childTabs = $childStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Build sub-tabs array
            $subTabs = ['_slug' => $tab['slug']];
            foreach ($childTabs as $childTab) {
                $subTabs[] = $childTab['name'];
            }
            
            $tabs_data[$tab_name] = $subTabs;
        }
        
        return $tabs_data;
        
    } catch (PDOException $e) {
        error_log('Collection tabs loader error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get collection name by ID
 * 
 * @param int $collection_id Collection ID
 * @return string|null Collection name or null if not found
 */
function get_collection_name($collection_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        $sql = "SELECT name
                FROM {$table_prefix}collections
                WHERE collection_id = :collection_id
                  AND is_deleted = 0
                LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':collection_id' => $collection_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['name'] : null;
        
    } catch (PDOException $e) {
        error_log('Get collection name error: ' . $e->getMessage());
        return null;
    }
}

?>
