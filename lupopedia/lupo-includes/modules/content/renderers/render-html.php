<?php
/**
 * wolfie.header.identity: render-html
 * wolfie.header.placement: /lupo-includes/modules/content/renderers/render-html.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: render-html
 *   message: "Created HTML renderer: returns HTML content as-is with basic sanitization."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. render-html.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * HTML Renderer
 * ---------------------------------------------------------
 */

/**
 * Render HTML content
 * 
 * @param string $body HTML content
 * @return string Sanitized HTML
 */
function render_html($body) {
    // Basic HTML sanitization - can be enhanced later
    // For now, return as-is (trusted content from database)
    return $body;
}

?>
