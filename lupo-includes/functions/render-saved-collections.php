<?php
/**
 * Render Saved Collections Navigation (thin wrapper — SavedCollectionsService).
 *
 * @param int $userId The current user ID (auth_user_id)
 * @return array Array structure for the component template
 */
function render_saved_collections($userId) {
    $s = $GLOBALS['lupo_saved_collections_service'] ?? null;
    return $s ? $s->renderSavedCollections((int) $userId) : [];
}

/**
 * Load children of a tab (thin wrapper — SavedCollectionsService).
 *
 * @param PDO|\PDO_DB $db Unused when service available; kept for backward compatibility
 * @param int $tabId collection_tab_id
 * @return array Array of child items
 */
function load_tab_children($db, $tabId) {
    $s = $GLOBALS['lupo_saved_collections_service'] ?? null;
    return $s ? $s->loadTabChildren((int) $tabId) : [];
}

/**
 * Count items in a tab recursively (thin wrapper — SavedCollectionsService).
 *
 * @param PDO|\PDO_DB $db Unused when service available; kept for backward compatibility
 * @param int $tabId collection_tab_id
 * @return int Count of items
 */
function count_tab_items($db, $tabId) {
    $s = $GLOBALS['lupo_saved_collections_service'] ?? null;
    return $s ? $s->countTabItems((int) $tabId) : 0;
}
