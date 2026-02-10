<?php
/**
 * Collection 0 (System Documentation) Helpers
 *
 * Ensures Collection 0 exists and is populated with Lupopedia documentation.
 * Collection 0 is system-owned and serves as the documentation landing page
 * for new users after Crafty Syntax migration.
 *
 * @package Lupopedia
 * @since 2026.3.9
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collection-zero-helpers.php cannot be called directly.");
}

/**
 * Ensure Collection 0 exists (thin wrapper — logic in CollectionZeroService).
 *
 * @return array|false Collection data if successful, false on error
 */
function lupo_ensure_collection_zero() {
    $s = $GLOBALS['lupo_collection_zero_service'] ?? null;
    return $s ? $s->ensureCollectionZero() : false;
}

/**
 * Populate Collection 0 with documentation tabs (thin wrapper — CollectionZeroService).
 *
 * @return bool True if successful, false on error
 */
function lupo_populate_collection_zero_tabs() {
    $s = $GLOBALS['lupo_collection_zero_service'] ?? null;
    return $s ? $s->populateCollectionZeroTabs() : false;
}

/**
 * Get Collection 0 URL (thin wrapper — CollectionZeroService).
 *
 * @param string|null $tab_slug Optional tab slug
 * @return string Collection 0 URL
 */
function lupo_get_collection_zero_url($tab_slug = null) {
    $s = $GLOBALS['lupo_collection_zero_service'] ?? null;
    return $s ? $s->getCollectionZeroUrl($tab_slug) : ((defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/collection/0/lupopedia' . ($tab_slug ? '/' . $tab_slug : ''));
}

/**
 * Initialize Collection 0 (thin wrapper — CollectionZeroService).
 *
 * @return bool True if successful, false on error
 */
function lupo_initialize_collection_zero() {
    $s = $GLOBALS['lupo_collection_zero_service'] ?? null;
    return $s ? $s->initializeCollectionZero() : false;
}
