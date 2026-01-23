<?php
/**
 * wolfie.header.identity: collection-selector
 * wolfie.header.placement: /lupo-includes/ui/components/collection_selector.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: collection-selector
 *   message: "Created collection selector component: renders the saved collections navigation bar with dropdown menus for WHO, WHAT, WHERE, WHEN, WHY, HOW, DO tabs. Extracted from header.php mockup."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collection_selector.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Collection Selector Component
 * ---------------------------------------------------------
 * 
 * This component renders the saved collections navigation bar.
 * It's the same as saved-collections-nav.php but with a different name
 * for consistency with main_layout.php references.
 */

// Use the existing saved-collections-nav component
if (file_exists(__DIR__ . '/saved-collections-nav.php')) {
    include __DIR__ . '/saved-collections-nav.php';
} else {
    // Fallback: render empty nav if component doesn't exist
    echo '<nav class="saved-collections-nav"><div class="saved-collections-container"></div></nav>';
}

?>
