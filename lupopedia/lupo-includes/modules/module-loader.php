<?php
/**
 * wolfie.header.identity: module-loader
 * wolfie.header.placement: /lupo-includes/modules/module-loader.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Version 4.1.1: Added HELP and LIST module routes. HELP handles /help documentation system. LIST handles /list entity introspection. Both routes have priority before TRUTH and CONTENT."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. module-loader.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Module Loading Order (Routing Priority)
 * ---------------------------------------------------------
 * 
 * Modules are loaded in this order to match routing priority:
 * 1. AUTH (authentication routes: /login, /logout, /admin)
 * 2. TRUTH (question prefixes: what/, who/, where/, when/, why/, how/)
 * 3. CRAFTY_SYNTAX (legacy system)
 * 4. CONTENT (default)
 */

/**
 * ---------------------------------------------------------
 * 1. Load AUTH Module (Highest Priority)
 * ---------------------------------------------------------
 * Handles authentication routes: /login, /logout, /admin
 */
$auth_module = LUPOPEDIA_ABSPATH . 'lupo-includes/modules/auth/auth-controller.php';
if (file_exists($auth_module)) {
    require_once $auth_module;
}

/**
 * ---------------------------------------------------------
 * 2. Load TRUTH Module
 * ---------------------------------------------------------
 * Handles question prefixes: what/, who/, where/, when/, why/, how/
 */
$truth_module = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/truth/truth-controller.php';
if (file_exists($truth_module)) {
    require_once $truth_module;
}

/**
 * ---------------------------------------------------------
 * 2. Load CRAFTY_SYNTAX Module (Legacy System)
 * ---------------------------------------------------------
 * Handles legacy Crafty Syntax routes
 */
$crafty_syntax_module = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/crafty_syntax/crafty_syntax-controller.php';
if (file_exists($crafty_syntax_module)) {
    require_once $crafty_syntax_module;
}

/**
 * ---------------------------------------------------------
 * 3. Load HELP Module
 * ---------------------------------------------------------
 * Handles help documentation routes: /help, /help/{slug}, /help/search
 */
$help_module = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/help/help-controller.php';
if (file_exists($help_module)) {
    require_once $help_module;
}

/**
 * ---------------------------------------------------------
 * 3.5. Load LIST Module
 * ---------------------------------------------------------
 * Handles entity introspection routes: /list, /list/{entity}
 */
$list_module = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/list/list-controller.php';
if (file_exists($list_module)) {
    require_once $list_module;
}

/**
 * ---------------------------------------------------------
 * 4. Load CONTENT Module (Default)
 * ---------------------------------------------------------
 * Handles default content routing
 */
$content_module = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/content-controller.php';
if (file_exists($content_module)) {
    require_once $content_module;
}

/**
 * ---------------------------------------------------------
 * Load Other Modules (Lower Priority)
 * ---------------------------------------------------------
 * Load any other modules that don't have routing priority
 */
// Example: Load questions module if it exists
$questions_module = LUPOPEDIA_ABSPATH . '/modules/questions/questions-controller.php';
if (file_exists($questions_module)) {
    require_once $questions_module;
}

// Example: Load leads module if it exists
$leads_module = LUPOPEDIA_ABSPATH . '/modules/leads/leads-controller.php';
if (file_exists($leads_module)) {
    require_once $leads_module;
}

/**
 * ---------------------------------------------------------
 * Module Routing Function
 * ---------------------------------------------------------
 * Routes slugs to the appropriate module based on routing priority:
 * 1. TRUTH (question prefixes)
 * 2. CRAFTY_SYNTAX (legacy system)
 * 3. CONTENT (default)
 * 
 * @param string $slug The URL slug to route
 * @return string The response from the routed module
 */
function lupo_route_slug($slug) {
    // Normalize slug
    $slug = ltrim(strtolower($slug), '/');
    
    // CANONICAL ROUTING DOCTRINE (New Standard)
    // Priority order:
    // 1. AUTH (authentication routes: /login, /logout, /admin)
    // 2. CANONICAL CONTENT ROUTE: /content/<slug>
    // 3. TRUTH LOOKUP ROUTE: /truth/<who|what|where|when|why|how>/<slug>
    // 4. EDGE TRAVERSAL ROUTE: /edge/<slug> or /edge/id/<content_id>
    // 5. HELP (help documentation: /help, /help/{slug}, /help/search)
    // 6. LIST (entity introspection: /list, /list/{entity})
    // 7. LEGACY COLLECTION ROUTE: /collection/<id>/content/<slug> (redirect only)
    // 8. CRAFTY_SYNTAX (legacy system)
    // 9. CONTENT (default)
    
    // Check for AUTH routes first (highest priority)
    $normalized_slug = preg_replace('/\.php$/', '', $slug);
    if ($normalized_slug === 'login' || $normalized_slug === 'logout' || 
        $normalized_slug === 'change-password' || $normalized_slug === 'change_password' ||
        strpos($normalized_slug, 'admin') === 0) {
        if (function_exists('auth_handle_slug')) {
            $result = auth_handle_slug($slug);
            if (!empty($result)) {
                return $result;
            }
        }
    }
    
    // CANONICAL CONTENT ROUTE: /content/<slug>
    if (preg_match('#^content/(.+)$#', $slug, $matches)) {
        $content_slug = $matches[1];
        
        $content_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/content-controller.php';
        if (file_exists($content_controller)) {
            require_once $content_controller;
            
            try {
                if (function_exists('content_show_by_slug')) {
                    $result = content_show_by_slug($content_slug);
                    if (!empty($result)) {
                        return $result;
                    }
                }
            } catch (Exception $e) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log('Canonical content routing error: ' . $e->getMessage());
                }
            }
        }
    }
    
    // TRUTH LOOKUP ROUTE: /truth/<who|what|where|when|why|how>/<slug>
    if (preg_match('#^truth/(who|what|where|when|why|how)/(.+)$#', $slug, $matches)) {
        $dimension = $matches[1];
        $truth_slug = $matches[2];
        
        $truth_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/truth/truth-controller.php';
        if (file_exists($truth_controller)) {
            require_once $truth_controller;
            
            try {
                if (function_exists('truth_lookup_dimension')) {
                    $result = truth_lookup_dimension($dimension, $truth_slug);
                    if (!empty($result)) {
                        return $result;
                    }
                }
            } catch (Exception $e) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log('Truth lookup routing error: ' . $e->getMessage());
                }
            }
        }
    }
    
    // EDGE TRAVERSAL ROUTE: /edge/<slug> or /edge/id/<content_id>
    if (preg_match('#^edge/(.+)$#', $slug, $matches)) {
        $edge_param = $matches[1];
        
        $edge_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/edge-controller.php';
        if (file_exists($edge_controller)) {
            require_once $edge_controller;
            
            try {
                if (function_exists('edge_traversal_slug')) {
                    $result = edge_traversal_slug($edge_param);
                    if (!empty($result)) {
                        return $result;
                    }
                }
            } catch (Exception $e) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log('Edge traversal routing error: ' . $e->getMessage());
                }
            }
        }
    }
    
    // LEGACY COLLECTION ROUTE: /collection/<id>/content/<slug> (301 redirect only)
    if (preg_match('#^collection/(\d+)/content/(.+)$#', $slug, $matches)) {
        $collection_id = (int)$matches[1];
        $content_slug = $matches[2];
        
        // Perform 301 redirect to canonical URL
        $canonical_url = LUPOPEDIA_PUBLIC_PATH . '/content/' . $content_slug;
        
        // Log redirect for analytics
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("Legacy redirect: /collection/$collection_id/content/$content_slug -> /content/$content_slug");
        }
        
        // Perform 301 redirect
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $canonical_url);
        exit;
    }
    
    // Check for HELP routes
    if (strpos($slug, 'help') === 0) {
        if (function_exists('help_handle_slug')) {
            $result = help_handle_slug($slug);
            if (!empty($result)) {
                return $result;
            }
        }
    }
    
    // Check for LIST routes
    if (strpos($slug, 'list') === 0) {
        if (function_exists('list_handle_slug')) {
            $result = list_handle_slug($slug);
            if (!empty($result)) {
                return $result;
            }
        }
    }
    
    // Question prefixes for TRUTH routing
    $question_prefixes = [
        'what/',
        'who/',
        'where/',
        'when/',
        'why/',
        'how/'
    ];
    
    // Determine if slug matches a TRUTH prefix or TRUTH route
    $is_truth = false;
    $truth_route_type = null;
    
    // Check for TRUTH explicit routes
    if (strpos($slug, 'truth/') === 0) {
        $is_truth = true;
        $remainder = substr($slug, 6); // Remove 'truth/' prefix
        
        // Check for specific TRUTH routes
        if (strpos($remainder, 'assert/') === 0) {
            $truth_route_type = 'assert';
            $truth_slug = substr($remainder, 7); // Remove 'assert/' prefix
        } elseif (strpos($remainder, 'evidence/') === 0) {
            $truth_route_type = 'evidence';
            $truth_slug = substr($remainder, 9); // Remove 'evidence/' prefix
        } else {
            $truth_route_type = 'view';
            $truth_slug = $remainder;
        }
    } else {
        // Check for question prefixes (legacy TRUTH routing)
        foreach ($question_prefixes as $prefix) {
            if (strpos($slug, $prefix) === 0) {
                $is_truth = true;
                $truth_route_type = 'question';
                $truth_slug = $slug;
                break;
            }
        }
    }
    
    if ($is_truth) {
        // Route to TRUTH controller
        // Fix: Use correct path with lupo-includes directory
        $truth_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/truth/truth-controller.php';
        if (file_exists($truth_controller)) {
            require_once $truth_controller;
            
            // Route based on type
            try {
                if ($truth_route_type === 'assert' && function_exists('truth_handle_assert')) {
                    $result = truth_handle_assert($truth_slug);
                    if (empty($result) && defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                        return "<!-- DEBUG: truth_handle_assert returned empty for slug: " . htmlspecialchars($truth_slug) . " -->";
                    }
                    return $result;
                } elseif ($truth_route_type === 'evidence' && function_exists('truth_handle_evidence')) {
                    $result = truth_handle_evidence($truth_slug);
                    if (empty($result) && defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                        return "<!-- DEBUG: truth_handle_evidence returned empty for slug: " . htmlspecialchars($truth_slug) . " -->";
                    }
                    return $result;
                } elseif ($truth_route_type === 'view' && function_exists('truth_handle_view')) {
                    $result = truth_handle_view($truth_slug);
                    if (empty($result) && defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                        return "<!-- DEBUG: truth_handle_view returned empty for slug: " . htmlspecialchars($truth_slug) . " -->";
                    }
                    return $result;
                } elseif ($truth_route_type === 'question' && function_exists('truth_handle_slug')) {
                    // Legacy question prefix routing
                    $result = truth_handle_slug($truth_slug);
                    if (empty($result) && defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                        return "<!-- DEBUG: truth_handle_slug returned empty for slug: " . htmlspecialchars($truth_slug) . " -->";
                    }
                    return $result;
                } elseif (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    return "<!-- DEBUG: TRUTH route type '" . htmlspecialchars($truth_route_type) . "' but handler function not found -->";
                }
            } catch (Exception $e) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    return "<!-- DEBUG: Exception in TRUTH routing: " . htmlspecialchars($e->getMessage()) . " --><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                }
                return '';
            } catch (Error $e) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    return "<!-- DEBUG: Fatal error in TRUTH routing: " . htmlspecialchars($e->getMessage()) . " --><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                }
                return '';
            }
        } elseif (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            return "<!-- DEBUG: TRUTH controller file not found at: " . htmlspecialchars($truth_controller) . " -->";
        }
    } elseif (strpos($slug, 'crafty_syntax') !== false) {
        // Route to Crafty Syntax module
        $crafty_syntax_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/crafty_syntax/crafty_syntax-controller.php';
        if (file_exists($crafty_syntax_controller)) {
            require_once $crafty_syntax_controller;
            if (function_exists('craftysyntax_handle_slug')) {
                return craftysyntax_handle_slug($slug);
            }
        }
    } else {
        // Route to CONTENT tables
        $content_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/content-controller.php';
        if (file_exists($content_controller)) {
            require_once $content_controller;
            if (function_exists('content_handle_slug')) {
                return content_handle_slug($slug);
            }
        }
    }
    
    // Default: return empty if no route matched
    return '';
}

?>
