<?php
/**
---
wolfie.headers.version: "4.0.0"
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
 * Get collection name by ID (thin wrapper — CollectionTabsService).
 *
 * @param int $collection_id Collection ID
 * @return string|null Collection name or null if not found
 */
function get_collection_name($collection_id) {
    $s = $GLOBALS['lupo_collection_tabs_service'] ?? null;
    return $s ? $s->getCollectionName((int) $collection_id) : null;
}

?>
