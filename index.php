<?php
/**
 * @wolfie.headers {
 *   file_path_from_root: "index.php",
 *   system_version: "4.0.39",
 *   channel_id: 42,
 *   mood_rgb: "4169E1",
 *   purpose: "Front controller and routing entry point - loads config, resolves slug, invokes routing system",
 *   last_modified_utc: "20260224",
 *   delegation_chain: "1001:10000",
 *   actor_id: 1001,
 *   lupo_agent: "kiro",
 *   artifact_type: "controller",
 *   artifact_kind: "front_controller",
 *   traits: ["critical", "entrypoint", "routing", "p0"],
 *   hashtags: ["#front_controller", "#routing", "#entrypoint", "#critical"],
 *   engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
 *   graph_stats: { inbound_count: 10, outbound_count: 8, centrality_score: 0.95 }
 * }
 * 
 * @flip.footer {
 *   inbound_edges: [
 *     { from: "README.md", type: "references", weight: 0.9, hashtag: "#architecture" },
 *     { from: ".htaccess", type: "routes_to", weight: 1.0, hashtag: "#routing" },
 *     { from: "install.php", type: "redirects_to", weight: 0.8, hashtag: "#installer" },
 *     { from: "admin.php", type: "references", weight: 0.6, hashtag: "#admin" }
 *   ],
 *   outbound_edges: [
 *     { to: "lupopedia-config.php", type: "requires", weight: 1.0, hashtag: "#config" },
 *     { to: "lupo-includes/bootstrap.php", type: "requires", weight: 1.0, hashtag: "#bootstrap" },
 *     { to: "lupo-includes/lupopedia-loader.php", type: "requires", weight: 1.0, hashtag: "#loader" },
 *     { to: "lupo-includes/modules/module-loader.php", type: "invokes", weight: 0.9, hashtag: "#routing" },
 *     { to: "install.php", type: "redirects_to", weight: 0.7, hashtag: "#installer" }
 *   ],
 *   referenced_by_actors: [1001, 1002, 10000],
 *   references: {
 *     by_files: ["README.md", ".htaccess", "install.php"],
 *     by_actors: [1001, 10000]
 *   },
 *   semantic_tags: ["front_controller", "routing", "config_loading", "subdirectory_install"],
 *   enrichment: { llm_inferred_edges: [], federated_metrics: {} },
 *   version: "4.0.39",
 *   last_verified_utc: "20260224",
 *   last_verified_by: "kiro"
 * }
 */

/**
 * FILE: index.php
 * TYPE: php
 *
 * PURPOSE: Front controller and routing entry point. Loads config, resolves slug, invokes lupo_route_slug().
 * Dynamically detects install folder name for LUPOPEDIA_PUBLIC_PATH (Installation Path Doctrine).
 *
 * DETAILS:
 * - Defines LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH, LUPOPEDIA_CONFIG_PATH.
 * - Config search order: above DOCUMENT_ROOT, above DOCUMENT_ROOT + public path, then inside install.
 * - No schema changes, no DB access in this file.
 *
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
 * INSTALL REDIRECT DOCTRINE (4.0.6+): If lupopedia-config.php does NOT exist, ALWAYS redirect
 * to install.php. config.php MUST NOT block or override this redirect. A white page must never occur.
 *
 * Config search order for lupopedia-config.php:
 * 1. One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
 * 2. One directory above DOCUMENT_ROOT + Lupopedia public path
 * 3. Inside the Lupopedia directory itself (fallback)
 *
 * @var string
 */

$lupopediaConfigPath = null;
// Path 1: One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
}
// Path 2: One directory above DOCUMENT_ROOT + Lupopedia public path
elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php';
}
// Path 3: Inside the Lupopedia directory itself (fallback)
elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = LUPOPEDIA_PATH . '/lupopedia-config.php';
}

if ($lupopediaConfigPath !== null) {
    define('LUPOPEDIA_CONFIG_PATH', $lupopediaConfigPath);
} else {
    // lupopedia-config.php does NOT exist: ALWAYS redirect to install.php (INSTALL REDIRECT DOCTRINE).
    // config.php must NOT block this. Redirect before any output.
    $installUrl = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/') . '/install.php';
    if ($installUrl === '/install.php') {
        $installUrl = '/install.php';
    }
    header('Location: ' . $installUrl);
    exit;
}

require_once LUPOPEDIA_CONFIG_PATH;

/**
 * Extract slug from URL
 * Priority: $_GET['resolved_uri'] (T5 rewrite) > $_GET['slug'] > PATH_INFO > REQUEST_URI
 */
$slug = '';

// Method 0: 4.0.18 T5 — Server rewrite passed full path (doctrine/qa/lupo-docs/flp)
if (isset($_GET['resolved_uri']) && $_GET['resolved_uri'] !== '') {
    $slug = is_string($_GET['resolved_uri']) ? trim($_GET['resolved_uri'], '/') : '';
}
// Method 1: Check for slug parameter (from .htaccess rewrite)
elseif (isset($_GET['slug']) && !empty($_GET['slug'])) {
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
    echo "<!-- REQUEST_URI: " . htmlspecialchars(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'not set') . " -->\n";
    echo "<!-- PATH_INFO: " . htmlspecialchars(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'not set') . " -->\n";
    echo "<!-- GET slug: " . htmlspecialchars(isset($_GET['slug']) ? $_GET['slug'] : 'not set') . " -->\n";
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
        echo "<pre>REQUEST_URI: " . htmlspecialchars(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'not set') . "</pre>";
        echo "<pre>PATH_INFO: " . htmlspecialchars(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'not set') . "</pre>";
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
