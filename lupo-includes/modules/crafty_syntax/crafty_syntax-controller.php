<?php
/**
 * wolfie.header.identity: crafty-syntax-controller
 * wolfie.header.placement: /lupo-includes/modules/crafty_syntax/crafty_syntax-controller.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: crafty-syntax-controller
 *   message: "Created Crafty Syntax controller skeleton: handles legacy Crafty Syntax routes. Follows same pattern as content-controller with render_main_layout integration."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. crafty_syntax-controller.php cannot be called directly.");
}

// Load content renderer for render_main_layout
require_once LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php';

/**
 * ---------------------------------------------------------
 * Crafty Syntax Controller - Legacy System Routing
 * ---------------------------------------------------------
 * 
 * Handles legacy Crafty Syntax routes
 * This is the second priority routing (checked after TRUTH, before CONTENT)
 */

/**
 * Handle Crafty Syntax slug routing
 * 
 * @param string $slug The Crafty Syntax slug
 * @return string Rendered HTML page
 */
function craftysyntax_handle_slug($slug) {
    // 1. Normalize slug
    $slug = ltrim(strtolower($slug), '/');
    
    // 2. Check if this is an AI actors route
    if (strpos($slug, 'ai-actors') === 0) {
        return craftysyntax_handle_ai_actors($slug);
    }
    
    // 3. Check if this is a Crafty Syntax route
    if (strpos($slug, 'crafty_syntax') === false && strpos($slug, 'livehelp') === false) {
        // Not a Crafty Syntax route, return 404
        return craftysyntax_render_not_found($slug);
    }
    
    // 4. Route to the modern Crafty Syntax operator console
    $console = craftysyntax_handle_operator_console($slug);
    if ($console !== null) {
        return $console;
    }

    return craftysyntax_render_not_found($slug);
}

/**
 * Render the modern Crafty Syntax operator console (procedural PHP).
 *
 * @param string $slug
 * @return string|null
 */
function craftysyntax_handle_operator_console($slug) {
    $console_path = LUPOPEDIA_ABSPATH . '/crafty_syntax/index.php';
    if (!file_exists($console_path)) {
        return null;
    }

    $parts = explode('/', trim($slug, '/'));
    $page = 'operator';
    $action = 'overview';
    $index = array_search('crafty_syntax', $parts, true);
    if ($index !== false && isset($parts[$index + 1]) && $parts[$index + 1] !== '') {
        $page = $parts[$index + 1];
        if (isset($parts[$index + 2]) && $parts[$index + 2] !== '') {
            $action = $parts[$index + 2];
        }
    }

    $original_page = $_GET['page'] ?? null;
    $original_action = $_GET['action'] ?? null;
    $_GET['page'] = $page;
    $_GET['action'] = $action;

    ob_start();
    include $console_path;
    $output = ob_get_clean();

    if ($original_page === null) {
        unset($_GET['page']);
    } else {
        $_GET['page'] = $original_page;
    }
    if ($original_action === null) {
        unset($_GET['action']);
    } else {
        $_GET['action'] = $original_action;
    }

    return $output;
}

/**
 * Handle AI Actors routes
 * 
 * @param string $slug The AI actors slug
 * @return string Rendered HTML page
 */
function craftysyntax_handle_ai_actors($slug) {
    // Parse AI actors path
    $path_parts = explode('/', trim($slug, '/'));
    
    // Default to index if no specific agent
    if (count($path_parts) === 1) {
        // Include the AI actors index
        $ai_actors_file = LUPOPEDIA_ABSPATH . '/ai-actors/index.php';
        if (file_exists($ai_actors_file)) {
            // Capture output and return as content
            ob_start();
            include $ai_actors_file;
            $content = ob_get_clean();
            return $content;
        }
    }
    
    // Handle specific agent routes
    if (count($path_parts) >= 2) {
        $agent_key = $path_parts[1];
        $agent_file = LUPOPEDIA_ABSPATH . "/ai-actors/{$agent_key}.php";
        
        if (file_exists($agent_file)) {
            ob_start();
            include $agent_file;
            $content = ob_get_clean();
            return $content;
        }
    }
    
    // Fallback to main AI actors interface
    $ai_actors_file = LUPOPEDIA_ABSPATH . '/ai-actors/index.php';
    if (file_exists($ai_actors_file)) {
        ob_start();
        include $ai_actors_file;
        $content = ob_get_clean();
        return $content;
    }
    
    // If nothing found, render not found
    return craftysyntax_render_not_found($slug);
}

/**
 * Render 404 not found page for Crafty Syntax
 * 
 * @param string $slug The slug that was not found
 * @return string 404 HTML
 */
function craftysyntax_render_not_found($slug) {
    http_response_code(404);
    $content = [
        'title' => 'Crafty Syntax Route Not Found',
        'description' => 'The requested Crafty Syntax route was not found',
        'body' => '<h1>Crafty Syntax Route Not Found</h1><p>No Crafty Syntax data for slug: ' . htmlspecialchars($slug) . '</p>',
        'content_sections' => null
    ];
    $page_body = render_content_page($content, $content['body']);
    $context = [
        'page_body' => $page_body,
        'page_title' => $content['title'] ?? '',
        'content' => $content,
        'content_type' => $content['content_type'] ?? null,
        'meta' => [
            'description' => $content['description'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

?>
