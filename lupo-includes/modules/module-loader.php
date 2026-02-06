<?php
/**
 * wolfie.header.identity: module-loader
 * wolfie.header.placement: /lupo-includes/modules/module-loader.php
 * wolfie.header.version: 3.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Version 3.1.1: Added HELP and LIST module routes. HELP handles /help documentation system. LIST handles /list entity introspection. Both routes have priority before TRUTH and CONTENT."
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

    // GET /my-profile — actor profile page (standalone UI, no content system)
    if ($normalized_slug === 'my-profile' && ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $actors_controller_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'actors-controller.php';
        if (file_exists($actors_controller_path)) {
            require_once $actors_controller_path;
            if (function_exists('actors_handle_my_profile')) {
                return actors_handle_my_profile();
            }
        }
    }

    // POST /my-profile/save — save profile form
    if ($slug === 'my-profile/save' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $actors_controller_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'actors-controller.php';
        if (file_exists($actors_controller_path)) {
            require_once $actors_controller_path;
            if (function_exists('actors_handle_my_profile_save')) {
                return actors_handle_my_profile_save();
            }
        }
    }

    // My Channel entry point (slug may be my-channel or my-channel.php)
    if ($normalized_slug === 'my-channel') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $my_channel = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'my-channel.php';
        if (file_exists($my_channel)) {
            require $my_channel;
            exit;
        }
    }

    // Crafty Syntax visitor: livehelp_js.php (legacy livehelp_js.php equivalent)
    if ($normalized_slug === 'livehelp_js' || $normalized_slug === 'livehelp-js') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $livehelp_js_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'livehelp-js.php';
        if (file_exists($livehelp_js_path)) {
            require $livehelp_js_path;
            exit;
        }
    }

    // Crafty Syntax visitor: image.php (getstate, getcredit, userstat)
    if ($normalized_slug === 'image' && (empty($_GET['what']) || in_array((string)$_GET['what'], ['getstate', 'getcredit', 'userstat'], true))) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $visitor_image_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-image.php';
        if (file_exists($visitor_image_path)) {
            require $visitor_image_path;
            exit;
        }
    }

    // Crafty Syntax visitor: livehelp.php and chat (same entry; redirects to choosedepartment when no department)
    if ($normalized_slug === 'livehelp' || $normalized_slug === 'chat') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $livehelp_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'livehelp.php';
        if (file_exists($livehelp_path)) {
            require $livehelp_path;
            exit;
        }
    }

    // Crafty Syntax visitor: choosedepartment.php
    if ($normalized_slug === 'choosedepartment') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $choosedepartment_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'choosedepartment.php';
        if (file_exists($choosedepartment_path)) {
            require $choosedepartment_path;
            exit;
        }
    }

    // Crafty Syntax visitor: visitor-chat-stream.php (iframe content for livehelp.php)
    if ($normalized_slug === 'visitor-chat-stream') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $stream_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-chat-stream.php';
        if (file_exists($stream_path)) {
            require $stream_path;
            exit;
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
    
    // CHANNEL SIGN-ON ROUTE: /operator/signon (channel-based; actor must have role in channel)
    if ($slug === 'operator/signon') {
        // Load render_main_layout function
        $content_renderer = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($content_renderer)) {
            require_once $content_renderer;
        }

        // Handle POST request (channel selection)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['channel_id'])) {
            $channel_id = (int)$_POST['channel_id'];

            $db = $GLOBALS['mydatabase'] ?? null;
            if ($db && $channel_id > 0) {
                if (!function_exists('current_user')) {
                    require_once LUPOPEDIA_ABSPATH . '/lupo-includes/functions/auth-helpers.php';
                }
                $current_user = current_user();
                $actor_id = $current_user['actor_id'] ?? null;
                if ($actor_id) {
                    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
                    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
                    $stmt->execute([':channel_id' => $channel_id, ':actor_id' => $actor_id]);
                    if ($stmt->fetch()) {
                        $channel_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
                        $channel_url .= '/channels/' . $channel_id;
                        header('Location: ' . $channel_url);
                        exit;
                    }
                }
            }
        }

        // Get current user and channels where they have a role
        if (!function_exists('current_user')) {
            require_once LUPOPEDIA_ABSPATH . '/lupo-includes/functions/auth-helpers.php';
        }
        $current_user = current_user();

        $channels = [];
        if ($current_user) {
            $actor_id = $current_user['actor_id'] ?? null;
            if ($actor_id) {
                $db = $GLOBALS['mydatabase'] ?? null;
                if ($db) {
                    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
                    $stmt = $db->prepare(
                        "SELECT r.channel_id, r.role_type, c.channel_name, c.department_id " .
                        "FROM {$table_prefix}channel_roles r " .
                        "INNER JOIN {$table_prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 " .
                        "WHERE r.actor_id = :actor_id AND r.is_deleted = 0"
                    );
                    $stmt->execute([':actor_id' => $actor_id]);
                    $channels = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        }

        // Load view (channels list; POST channel_id)
        $operator_signon_view = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/operator/views/signon.php';
        if (file_exists($operator_signon_view)) {
            ob_start();
            include $operator_signon_view;
            $page_body = ob_get_clean();
        } else {
            $page_body = '<p>Channel sign-on view not found</p>';
        }

        $context = [
            'page_body' => $page_body,
            'page_title' => 'Channel Sign-On'
        ];
        return render_main_layout($context);
    }

    // API: Channel typing preview (GET = operator poll, POST = visitor submit draft)
    if (preg_match('#^api/channel/typing$#', $slug)) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $typing_api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channel-typing-api.php';
        if (file_exists($typing_api_path)) {
            require_once $typing_api_path;
            exit;
        }
    }

    // API: Channel send message (POST). Legacy: admin_chat_bot whattodo=send.
    if (preg_match('#^api/channel/send$#', $slug)) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $send_api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channel-send-api.php';
        if (file_exists($send_api_path)) {
            require_once $send_api_path;
            exit;
        }
    }

    // API: Channel messages poll (GET). Legacy: xmlhttp whattodo=messages.
    if (preg_match('#^api/channel/messages$#', $slug)) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $messages_api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channel-messages-api.php';
        if (file_exists($messages_api_path)) {
            require_once $messages_api_path;
            exit;
        }
    }

    // API: Channel check (GET). Legacy: admin_image what=messagecheck; returns refresh flag for fallback.
    if (preg_match('#^api/channel/check$#', $slug)) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $check_api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channel-check-api.php';
        if (file_exists($check_api_path)) {
            require_once $check_api_path;
            exit;
        }
    }

    // API: Pending visitors (GET). Channel-based; unassigned visitors for department.
    if (preg_match('#^api/operator/pending-visitors$#', $slug)) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'operator-pending-visitors-api.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    // API: Accept visitor (POST). Moves visitor onto channel; actor must have channel role.
    if (preg_match('#^api/operator/accept-visitor$#', $slug)) {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'operator-accept-visitor-api.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    // CHANNELS ROUTE: /channels/{channel_id}/ or /channels/{channel_id}
    if (preg_match('#^channels/(\d+)/?$#', $slug, $matches)) {
        $channel_id = (int)$matches[1];
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $channels_controller_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channels-controller.php';
        if (file_exists($channels_controller_path)) {
            require_once $channels_controller_path;
            if (function_exists('channels_handle_show')) {
                return channels_handle_show($channel_id);
            }
        }
        $content_renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($content_renderer)) {
            require_once $content_renderer;
        }
        return render_main_layout([
            'page_body' => '<p>Channel interface unavailable.</p>',
            'page_title' => 'Channel ' . $channel_id,
        ]);
    }

    // EDGES ROUTE: /edges/{edge_id}
    if (preg_match('#^edges/(\d+)$#', $slug, $matches)) {
        $edge_id = (int)$matches[1];

        // Load EdgesController
        $edges_controller_path = (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH) . '/app/Http/Controllers/EdgesController.php';
        if (file_exists($edges_controller_path)) {
            require_once $edges_controller_path;
            $controller = new EdgesController();
            $controller->show($edge_id);
            return '';
        } else {
            // Fallback if controller not found
            $content_renderer = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($content_renderer)) {
                require_once $content_renderer;
            }

            $page_body = '<p>edges interface goes here</p>';
            $context = [
                'page_body' => $page_body,
                'page_title' => 'Edge ' . $edge_id
            ];
            return render_main_layout($context);
        }
    }

    // Q/A ROUTE: /qa/ and /qa/<slug>
    if ($slug === 'qa' || strpos($slug, 'qa/') === 0) {
        // Load render_main_layout function if not already loaded
        $content_renderer = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($content_renderer)) {
            require_once $content_renderer;
        }

        // Extract Q/A slug (empty for root /qa/)
        $qa_slug = $slug === 'qa' ? '' : substr($slug, 3); // Remove 'qa/' prefix

        // Root Q/A page: /qa/
        if (empty($qa_slug)) {
            $qa_index_view = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/qa/views/index.php';
            if (file_exists($qa_index_view)) {
                ob_start();
                include $qa_index_view;
                $page_body = ob_get_clean();
            } else {
                // Fallback placeholder
                $page_body = '<p>root level nav tree of questions goes here</p>';
            }

            // Wrap in main layout
            $context = [
                'page_body' => $page_body,
                'page_title' => 'Q/A'
            ];
            return render_main_layout($context);
        }
        // Q/A question page: /qa/<slug>
        else {
            $qa_question_view = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/qa/views/question.php';

            // Look up truth question by slug
            $db = $GLOBALS['mydatabase'] ?? null;
            if (!$db) {
                $page_body = '<h1>Error</h1><p>Database not available</p>';
                $context = [
                    'page_body' => $page_body,
                    'page_title' => 'Error'
                ];
                return render_main_layout($context);
            }

            $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $stmt = $db->prepare("SELECT * FROM {$table_prefix}truth_questions WHERE slug = :slug LIMIT 1");
            $stmt->execute([':slug' => $qa_slug]);
            $question = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$question) {
                $page_body = '<h1>404 Not Found</h1><p>Question not found: ' . htmlspecialchars($qa_slug) . '</p>';
                $context = [
                    'page_body' => $page_body,
                    'page_title' => 'Not Found'
                ];
                return render_main_layout($context);
            }

            // Determine collection context
            if (isset($_SESSION['collection_id'])) {
                $collection_id = $_SESSION['collection_id'];
            } else {
                $collection_id = $question['default_collection_id'] ?? null;
            }

            // Set variables for view
            $slug = $qa_slug;

            if (file_exists($qa_question_view)) {
                ob_start();
                include $qa_question_view;
                $page_body = ob_get_clean();
            } else {
                // Fallback placeholder
                $page_body = '<p>Question view for slug: ' . htmlspecialchars($qa_slug) . '</p>';
                $page_body .= '<p>Collection context: ' . htmlspecialchars($collection_id) . '</p>';
            }

            // Wrap in main layout
            $context = [
                'page_body' => $page_body,
                'page_title' => $question['question_text'] ?? 'Q/A',
                'collection_id' => $collection_id
            ];
            return render_main_layout($context);
        }
    }

    // TRUTH LOOKUP ROUTE: /truth/<who|what|where|when|why|how>/<slug>
    // Redirect old Truth routes to /qa/
    if (preg_match('#^truth/(who|what|where|when|why|how)/(.+)$#', $slug, $matches)) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/qa/');
        exit;
    }

    // Redirect standalone /truth to /qa/
    if ($slug === 'truth') {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/qa/');
        exit;
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
    
    // Redirect old question prefix routes to /qa/
    foreach ($question_prefixes as $prefix) {
        if (strpos($slug, $prefix) === 0) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/qa/');
            exit;
        }
    }

    if (strpos($slug, 'crafty_syntax') !== false) {
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
