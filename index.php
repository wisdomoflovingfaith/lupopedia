<?php
/**
 * Front to the Lupopedia application. This file is designed to be placed directly inside the Lupopedia directory (or whatever you rename it to) under your web server's public root.
 * It dynamically detects the folder name for the public path, so it works even if you rename the 'lupopedia' folder.
 * @package Lupopedia
 */

// Enable error reporting IMMEDIATELY (before any other code runs)
// This ensures we see errors even if config hasn't loaded yet
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '0'); // Display to screen, don't log
ini_set('html_errors', '1'); // Format errors as HTML

/**
 * The path to the Lupopedia directory (full filesystem path, since this file is inside it).
 *
 * @var string
 */
define( 'LUPOPEDIA_PATH', __DIR__ );

/**
 * The path to the Lupopedia directory relative to the public directory (dynamically detected).
 *
 * @var string
 */
define( 'LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__) );
 
/**
 * The full path to the config file, preferably in a private directory outside the public root.
 * 
 * Config file search order (MANDATORY - DO NOT SIMPLIFY):
 * 1. One directory ABOVE the server's DOCUMENT_ROOT (most secure, preferred)
 * 2. One directory above DOCUMENT_ROOT + the Lupopedia public path
 * 3. Inside the Lupopedia directory itself (fallback)
 * 
 * This multi-path search ensures:
 * - Maximum security (config outside web root)
 * - Flexibility (works in different hosting environments)
 * - Backward compatibility (Crafty Syntax subdirectory pattern)
 * 
 * @var string
 */

// Path 1: One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php');
}
// Path 2: One directory above DOCUMENT_ROOT + Lupopedia public path
elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
}
// Path 3: Inside the Lupopedia directory itself (fallback)
elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
}
// No config found - run installer
else {
    // Config file not found - run setup/upgrade
    require_once LUPOPEDIA_PATH . '/lupo-includes/lupopedia-setup.php';
    // Setup script will handle detection and creation
    exit;
}

require_once LUPOPEDIA_CONFIG_PATH;

/**
 * Extract slug from URL
 * Priority: $_GET['slug'] > PATH_INFO > REQUEST_URI
 */
$slug = '';

// Method 1: Check for slug parameter (from .htaccess rewrite)
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];
}
// Method 2: Check PATH_INFO
elseif (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    $slug = ltrim($_SERVER['PATH_INFO'], '/');
}
// Method 3: Extract from REQUEST_URI (fallback)
elseif (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = $_SERVER['REQUEST_URI'];
    // Remove query string
    $request_uri = strtok($request_uri, '?');
    // Remove base path (e.g., /lupopedia/)
    $base_path = LUPOPEDIA_PUBLIC_PATH;
    if (strpos($request_uri, $base_path) === 0) {
        $slug = substr($request_uri, strlen($base_path));
    } else {
        $slug = ltrim($request_uri, '/');
    }
    // Remove index.php if present
    $slug = preg_replace('#^index\.php/?$#', '', $slug);
    $slug = ltrim($slug, '/');
}

// Normalize slug (remove leading/trailing slashes)
$slug = trim($slug, '/');

// DEBUG: Show routing information if in debug mode
if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
    echo "<!-- DEBUG INFO -->\n";
    echo "<!-- Slug extracted: " . htmlspecialchars($slug) . " -->\n";
    echo "<!-- REQUEST_URI: " . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'not set') . " -->\n";
    echo "<!-- PATH_INFO: " . htmlspecialchars($_SERVER['PATH_INFO'] ?? 'not set') . " -->\n";
    echo "<!-- GET slug: " . htmlspecialchars($_GET['slug'] ?? 'not set') . " -->\n";
    echo "<!-- lupo_route_slug exists: " . (function_exists('lupo_route_slug') ? 'yes' : 'no') . " -->\n";
    echo "<!-- LUPOPEDIA_PUBLIC_PATH: " . htmlspecialchars(LUPOPEDIA_PUBLIC_PATH) . " -->\n";
}

// Route the slug if routing function exists
if (!empty($slug) && function_exists('lupo_route_slug')) {
    try {
        $output = lupo_route_slug($slug);
        if (!empty($output)) {
            echo $output;
            exit;
        } else {
            // Routing function returned empty - show debug info
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                echo "<!-- Routing function returned empty output -->\n";
            }
        }
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            echo "<h1>Routing Error</h1>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        } else {
            echo "<h1>Error</h1><p>An error occurred. Please check error logs.</p>";
        }
        exit;
    }
} elseif (empty($slug)) {
    // No slug - show debug info
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        echo "<h1>Debug: No Slug Extracted</h1>";
        echo "<p>Slug is empty. Check .htaccess rewrite rules.</p>";
        echo "<pre>REQUEST_URI: " . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'not set') . "</pre>";
        echo "<pre>PATH_INFO: " . htmlspecialchars($_SERVER['PATH_INFO'] ?? 'not set') . "</pre>";
        echo "<pre>GET params: " . print_r($_GET, true) . "</pre>";
    }
} elseif (!function_exists('lupo_route_slug')) {
    // Routing function doesn't exist - show debug info
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        echo "<h1>Debug: Routing Function Not Found</h1>";
        echo "<p>lupo_route_slug() function does not exist. Check if module-loader.php is loaded.</p>";
        echo "<pre>Loaded files: " . print_r(get_included_files(), true) . "</pre>";
    }
}

// Default: show homepage or 404
// TODO: Add default homepage handler or 404 page

?>