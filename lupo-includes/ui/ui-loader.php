<?php
/**
 * wolfie.header.identity: ui-loader
 * wolfie.header.placement: /lupo-includes/ui/ui-loader.php
 * wolfie.header.version: lupopedia_current_version
 *
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: ui-loader
 *   message: "Created initial skeleton for the UI subsystem loader."
 *
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. ui-loader.php cannot be called directly.");
}




/**
 * ---------------------------------------------------------
 * UI Asset Arrays
 * ---------------------------------------------------------
 */
$lupo_ui_js = [];
$lupo_ui_css = [];

/**
 * ---------------------------------------------------------
 * Enqueue Functions
 * ---------------------------------------------------------
 */
function lupo_enqueue_js($file) {
    global $lupo_ui_js;
    $lupo_ui_js[] = $file;
}

function lupo_enqueue_css($file) {
    global $lupo_ui_css;
    $lupo_ui_css[] = $file;
}
/**
 * ---------------------------------------------------------
 * Enqueue Vendor JS
 * ---------------------------------------------------------
 */
lupo_enqueue_js(LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/js/dynlayer.js');

/**
 * ---------------------------------------------------------
 * Enqueue Crafty Syntax Eyes System
 * ---------------------------------------------------------
 */
lupo_enqueue_js(LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/js/crafty_syntax_eyes.js');

/**
 * ---------------------------------------------------------
 * Enqueue Navigation System
 * ---------------------------------------------------------
 */
lupo_enqueue_css(LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/css/navigation.css');
lupo_enqueue_js(LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/js/navigation.js');

/**
 * ---------------------------------------------------------
 * Enqueue TRUTH Module Styles
 * ---------------------------------------------------------
 */
lupo_enqueue_css(LUPOPEDIA_PUBLIC_PATH . '/lupo-includes/css/truth.css');


/**
 * ---------------------------------------------------------
 * Output Functions
 * ---------------------------------------------------------
 */
function lupo_print_ui_css() {
    global $lupo_ui_css;
    foreach ($lupo_ui_css as $css) {
        echo '<link rel="stylesheet" type="text/css" href="' . htmlspecialchars($css) . '">' . "\n";
    }
}

function lupo_print_ui_js() {
    global $lupo_ui_js;
    foreach ($lupo_ui_js as $js) {
        echo '<script src="' . htmlspecialchars($js) . '"></script>' . "\n";
    }
}

/**
 * ---------------------------------------------------------
 * UI Initialization Hook
 * ---------------------------------------------------------
 */
function lupo_init_content_ui() {
    // Placeholder: initialize book UI, eyes, DynLayer blocks, etc.
}


?>