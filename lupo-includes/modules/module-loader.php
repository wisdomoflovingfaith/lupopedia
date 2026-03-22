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

// Define essential constants BEFORE loading modules
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', defined('ABSPATH') ? ABSPATH : dirname(dirname(dirname(__FILE__))) . '/');
}

if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', str_replace('\\', '/', dirname(__FILE__)));
}

if (!defined('LUPO_INCLUDES_DIR')) {
    define('LUPO_INCLUDES_DIR', LUPOPEDIA_ABSPATH . 'lupo-includes/');
}

if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    // Calculate public path for web access
    $script_name = basename($_SERVER['SCRIPT_NAME'], '.php');
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);

    $web_path_to_config = str_replace('\\', '/', str_replace(dirname($_SERVER['SCRIPT_FILENAME']), '', $script_dir));

    // Remove common subdirectory patterns
    $patterns = array('/lupo-includes/', '/lupo-tests/', '/lupo-admin/', '/lupo-database/', '/lupo-docs/', '/lupo-scripts/', '/lupo-tools/', '/legacy/', '/lupo-channels/', '/lupo-uploads/');
    $clean_path = $web_path_to_config;

    foreach ($patterns as $pattern) {
        if (strpos($clean_path, $pattern) !== false) {
            $clean_path = substr($clean_path, 0, strpos($clean_path, $pattern) + strlen($pattern));
        }
    }

    define('LUPOPEDIA_PUBLIC_PATH', '/' . trim($clean_path, '/') . '/');
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
 * 1.5. Load OAuth Module
 * ---------------------------------------------------------
 * Handles OAuth authentication routes: /oauth/login/{provider}, /oauth/callback/{provider}
 */
$oauth_module = LUPOPEDIA_ABSPATH . 'lupo-includes/modules/auth/oauth-controller.php';
if (file_exists($oauth_module)) {
    require_once $oauth_module;
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
function lupo_route_slug($slug)
{
    $slug = ltrim(trim($slug), '/');
    if ($slug === '') {
        return '';
    }

    // 4.0.18 T3 — Web path resolution (doctrine/qa/docs/flp, or canonical slug flare_apply). Check before lowercasing (paths are case-sensitive).
    if ((preg_match('#^(doctrine|qa|docs|flp)/#i', $slug) || $slug === 'flare_apply') && function_exists('lupo_resolve_web_path')) {
        $resolved = lupo_resolve_web_path($slug);
        if ($resolved && is_array($resolved)) {
            $canonical = isset($resolved['canonical']) ? trim($resolved['canonical'], '/') : '';
            $is_alias = isset($resolved['is_alias']) && $resolved['is_alias'];
            $alias_redirect = isset($resolved['alias_redirect']) ? $resolved['alias_redirect'] : true;
            $content_id = isset($resolved['content_id']) ? (int) $resolved['content_id'] : 0;

            // 4.0.18 T7 — Ban at Gate: before redirect or content, block banned actors.
            if (function_exists('lupo_get_current_actor_id') && function_exists('lupo_is_actor_banned')) {
                $current_actor_id = lupo_get_current_actor_id();
                if ($current_actor_id !== null && lupo_is_actor_banned($current_actor_id)) {
                    if (function_exists('lupo_log_ban_event')) {
                        lupo_log_ban_event($current_actor_id, $slug, $canonical !== '' ? $canonical : $slug);
                    }
                    if (!headers_sent()) {
                        header('HTTP/1.0 403 Forbidden');
                    }
                    $tpl = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '');
                    $tpl = rtrim(str_replace('\\', '/', $tpl), '/') . '/templates/errors/403_banned.php';
                    if (is_file($tpl)) {
                        $requested_path = $slug;
                        ob_start();
                        include $tpl;
                        return ob_get_clean();
                    }
                    return '<div class="lupopedia-403"><h1>Access Denied</h1><p>Your account is restricted from accessing this content.</p><p>Requested path: <code>' . htmlspecialchars($slug) . '</code></p></div>';
                }
            }

            if ($is_alias && $alias_redirect && $canonical !== '') {
                $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
                $redirect_url = $base . '/' . $canonical;
                if (!headers_sent()) {
                    header('Location: ' . $redirect_url, true, 302);
                    exit;
                }
            }
            if ($content_id > 0) {
                $content_controller = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/content-controller.php';
                if (file_exists($content_controller)) {
                    require_once $content_controller;
                    if (function_exists('content_show_by_content_id')) {
                        $out = content_show_by_content_id($content_id);
                        if ($out !== null && $out !== '') {
                            return $out;
                        }
                    }
                }
            }
        } else {
            // 4.0.18 T7 — Ban at Gate: block banned actors before Smart 404.
            if (function_exists('lupo_get_current_actor_id') && function_exists('lupo_is_actor_banned')) {
                $current_actor_id = lupo_get_current_actor_id();
                if ($current_actor_id !== null && lupo_is_actor_banned($current_actor_id)) {
                    if (function_exists('lupo_log_ban_event')) {
                        lupo_log_ban_event($current_actor_id, $slug, '');
                    }
                    if (!headers_sent()) {
                        header('HTTP/1.0 403 Forbidden');
                    }
                    $tpl = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '');
                    $tpl = rtrim(str_replace('\\', '/', $tpl), '/') . '/templates/errors/403_banned.php';
                    if (is_file($tpl)) {
                        $requested_path = $slug;
                        ob_start();
                        include $tpl;
                        return ob_get_clean();
                    }
                    return '<div class="lupopedia-403"><h1>Access Denied</h1><p>Your account is restricted from accessing this content.</p><p>Requested path: <code>' . htmlspecialchars($slug) . '</code></p></div>';
                }
            }
            // 4.0.18 T4 — Smart 404: only when resolved_uri was passed, resolver returned null, under doctrine/qa/docs/flp
            $is_auth = false;
            $auth = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
            if ($auth && is_object($auth) && method_exists($auth, 'getCurrentUser')) {
                $user = $auth->getCurrentUser();
                $is_auth = is_array($user) && !empty($user);
            } elseif (function_exists('current_user')) {
                $user = current_user();
                $is_auth = is_array($user) && !empty($user);
            }
            if (function_exists('lupo_smart_404')) {
                $data = lupo_smart_404($slug, $is_auth);
                $data['authenticated'] = $is_auth;
                if (!headers_sent()) {
                    header('HTTP/1.0 404 Not Found');
                }
                $tpl = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '');
                $tpl = rtrim(str_replace('\\', '/', $tpl), '/') . '/templates/errors/smart_404.php';
                if (is_file($tpl)) {
                    ob_start();
                    include $tpl;
                    return ob_get_clean();
                }
                $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
                $html = '<div class="lupopedia-404"><h1>Page not found</h1><p>The path <code>' . htmlspecialchars($data['requested']) . '</code> was not found.</p>';
                if (!empty($data['suggestions'])) {
                    $html .= '<ul>';
                    foreach ($data['suggestions'] as $p) {
                        $html .= '<li><a href="' . htmlspecialchars($base . '/' . $p) . '">' . htmlspecialchars($p) . '</a></li>';
                    }
                    $html .= '</ul>';
                }
                $html .= '</div>';
                return $html;
            }
        }
    }

    // Normalize slug for all other routes
    $slug = strtolower($slug);

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
    if (
        $normalized_slug === 'login' || $normalized_slug === 'logout' ||
        $normalized_slug === 'change-password' || $normalized_slug === 'change_password' ||
        strpos($normalized_slug, 'admin') === 0
    ) {
        if (function_exists('auth_handle_slug')) {
            $result = auth_handle_slug($slug);
            if (!empty($result)) {
                return $result;
            }
        }
    }

    // Check for OAuth routes (high priority, after auth)
    if (strpos($normalized_slug, 'oauth/') === 0) {
        if (function_exists('oauth_route_request')) {
            oauth_route_request($slug);
            // OAuth controller handles redirects and exits
            return '';
        }
    }

    // ── AGENT WWW ROUTE: /agent/<actor_name>/[path] ─────────────────────────
    if (preg_match('#^agent/([^/]+)(?:/(.*))?$#', $normalized_slug, $matches)) {
        $actor_name = $matches[1];
        $sub_path = isset($matches[2]) ? $matches[2] : '';
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $controller_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'agent-www-controller.php';
        if (file_exists($controller_path)) {
            require_once $controller_path;
            if (function_exists('agent_www_handle_request')) {
                return agent_www_handle_request($actor_name, $sub_path);
            }
        }
    }

    // GET /my-profile — actor profile page (standalone UI, no content system)
    if ($normalized_slug === 'my-profile' && (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') !== 'POST') {
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
    if ($slug === 'my-profile/save' && (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '') === 'POST') {
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

    // Semantic Navbar generator (4.0.71); semantic_navbar.php is integration entry, semantic-navbar-js.php is canonical JS output
    if ($normalized_slug === 'nav/semantic-navbar' || $normalized_slug === 'nav/semantic-navbar-js' || $normalized_slug === 'nav/semantic_navbar') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $nav_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'nav' . DIRECTORY_SEPARATOR . 'semantic_navbar.php';
        if (file_exists($nav_path)) {
            require $nav_path;
            exit;
        }
        $js_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'nav' . DIRECTORY_SEPARATOR . 'semantic-navbar-js.php';
        if (file_exists($js_path)) {
            require $js_path;
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
    if ($normalized_slug === 'image' && (empty($_GET['what']) || in_array((string) $_GET['what'], ['getstate', 'getcredit', 'userstat'], true))) {
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
            $channel_id = (int) $_POST['channel_id'];

            $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
            if ($db && $channel_id > 0) {
                if (!function_exists('current_user')) {
                    require_once LUPOPEDIA_ABSPATH . '/lupo-includes/functions/auth-helpers.php';
                }
                $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
                $current_user = $authService ? $authService->getCurrentUser() : current_user();
                $actor_id = $current_user ? (isset($current_user['actor_id']) ? $current_user['actor_id'] : null) : null;
                if ($actor_id) {
                    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
                    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
                    $stmt->execute(array(':channel_id' => $channel_id, ':actor_id' => $actor_id));
                    if ($stmt->fetch()) {
                        $channel_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
                        $channel_url .= '/lupo-channels/' . $channel_id;
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
        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        $current_user = $authService ? $authService->getCurrentUser() : current_user();

        $channels = array();
        if ($current_user) {
            $actor_id = isset($current_user['actor_id']) ? $current_user['actor_id'] : null;
            if ($actor_id) {
                $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
                if ($db) {
                    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
                    $stmt = $db->prepare(
                        "SELECT r.channel_id, r.role_key AS role_type, c.channel_name, c.department_id " .
                        "FROM {$table_prefix}actor_channel_roles r " .
                        "INNER JOIN {$table_prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 " .
                        "WHERE r.actor_id = :actor_id AND (r.is_deleted = 0 OR r.is_deleted IS NULL)"
                    );
                    $stmt->execute(array(':actor_id' => $actor_id));
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

        $context = array(
            'page_body' => $page_body,
            'page_title' => 'Channel Sign-On'
        );
        return render_main_layout($context);
    }

    // Dialog MVP web views
    if ($slug === 'channels') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $view_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-views' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'channels.php';
        if (file_exists($view_path)) {
            require_once $view_path;
            exit;
        }
    }

    if ($slug === 'threads') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $view_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-views' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'threads.php';
        if (file_exists($view_path)) {
            require_once $view_path;
            exit;
        }
    }

    if ($slug === 'messages') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $view_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-views' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'messages.php';
        if (file_exists($view_path)) {
            require_once $view_path;
            exit;
        }
    }

    // Dialog MVP APIs
    if ($slug === 'thread') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-api' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'create-thread.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    if ($slug === 'message') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-api' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'post-message.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    if (preg_match('#^message/(\d+)/actor/?$#', $slug, $api_m)) {
        $dialog_assign_message_id = (int) $api_m[1];
        $GLOBALS['dialog_assign_message_id'] = $dialog_assign_message_id;
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-api' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'assign-actor.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    if (preg_match('#^message/(\d+)/route/?$#', $slug, $api_m)) {
        $dialog_route_message_id = (int) $api_m[1];
        $GLOBALS['dialog_route_message_id'] = $dialog_route_message_id;
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-api' . DIRECTORY_SEPARATOR . 'dialog' . DIRECTORY_SEPARATOR . 'trigger-routing.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    // ── REST API: Registry (actor lookup / register) ─────────────────────────
    if (preg_match('#^api/registry/actors/(lookup|register)$#', $slug, $api_m)) {
        $registry_api_action = $api_m[1];
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'registry-api.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    // ── REST API: Channels Admin ──────────────────
    if (preg_match('#^api/lupo-channels/admin/(operators|departments|settings)(?:/(\d+))?$#', $slug, $api_m)) {
        $channels_admin_resource = $api_m[1];
        $channels_admin_id = isset($api_m[2]) ? (int) $api_m[2] : 0;
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'channels-admin-api.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }
    // ── REST API: Channels (RESTful messages for VSX extension) ────────────────
    if (preg_match('#^api/lupo-channels/(\d+)/messages/?$#', $slug, $api_m)) {
        $channels_api_channel_id = (int) $api_m[1];
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'channels-api.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
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

    // ── REST API: Semantic Navbar (4.0.71) ──────────────────────
    if (preg_match('#^(edges|contexts|hashtags|folders|qa|references|namespaces|next|previous)/(.+)$#', $slug, $api_m)) {
        $navbar_api_type = $api_m[1];
        $navbar_api_slug = $api_m[2];
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $api_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'semantic-navbar-api.php';
        if (file_exists($api_path)) {
            require_once $api_path;
            exit;
        }
    }

    // CHANNELS ROUTE: /lupo-channels/my-channels — list channels the current actor has a role in
    if ($slug === 'channels/my-channels') {
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $channels_controller_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channels-controller.php';
        if (file_exists($channels_controller_path)) {
            require_once $channels_controller_path;
            if (function_exists('channels_handle_my_channels')) {
                return channels_handle_my_channels();
            }
        }
        $content_renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($content_renderer)) {
            require_once $content_renderer;
        }
        return render_main_layout(array(
            'page_body' => '<p>My Channels unavailable.</p>',
            'page_title' => 'My Channels',
        ));
    }

    // CHANNELS ROUTE: /lupo-channels/{channel_id}/stream — iframe message stream (legacy livehelp pattern)
    if (preg_match('#^channels/(\d+)/stream/?$#', $slug, $matches)) {
        $channel_id = (int) $matches[1];
        $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
        $channels_controller_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'channels-controller.php';
        if (file_exists($channels_controller_path)) {
            require_once $channels_controller_path;
            if (function_exists('channels_handle_stream')) {
                return channels_handle_stream($channel_id);
            }
        }
    }

    // CHANNELS ROUTE: /lupo-channels/{channel_id}/ or /lupo-channels/{channel_id}
    if (preg_match('#^channels/(\d+)/?$#', $slug, $matches)) {
        $channel_id = (int) $matches[1];
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
        return render_main_layout(array(
            'page_body' => '<p>Channel interface unavailable.</p>',
            'page_title' => 'Channel ' . $channel_id,
        ));
    }

    // EDGES ROUTE: /edges/{edge_id}
    if (preg_match('#^edges/(\d+)$#', $slug, $matches)) {
        $edge_id = (int) $matches[1];

        // Load EdgesController
        $edges_controller_path = LUPOPEDIA_ABSPATH . LUPO_APP_DIR . '/Http/Controllers/EdgesController.php';
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
            $context = array(
                'page_body' => $page_body,
                'page_title' => 'Edge ' . $edge_id
            );
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
            $context = array(
                'page_body' => $page_body,
                'page_title' => 'Q/A'
            );
            return render_main_layout($context);
        }
        // Q/A question page: /qa/<slug>
        else {
            $qa_question_view = LUPOPEDIA_ABSPATH . '/lupo-includes/modules/qa/views/question.php';

            // Look up truth question by slug
            $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
            if (!$db) {
                $page_body = '<h1>Error</h1><p>Database not available</p>';
                $context = array(
                    'page_body' => $page_body,
                    'page_title' => 'Error'
                );
                return render_main_layout($context);
            }

            $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $stmt = $db->prepare("SELECT * FROM {$table_prefix}truth_questions WHERE slug = :slug LIMIT 1");
            $stmt->execute(array(':slug' => $qa_slug));
            $question = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$question) {
                $page_body = '<h1>404 Not Found</h1><p>Question not found: ' . htmlspecialchars($qa_slug) . '</p>';
                $context = array(
                    'page_body' => $page_body,
                    'page_title' => 'Not Found'
                );
                return render_main_layout($context);
            }

            // Determine collection context
            if (isset($_SESSION['collection_id'])) {
                $collection_id = $_SESSION['collection_id'];
            } else {
                $collection_id = isset($question['default_collection_id']) ? $question['default_collection_id'] : null;
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
            $context = array(
                'page_body' => $page_body,
                'page_title' => isset($question['question_text']) ? $question['question_text'] : 'Q/A',
                'collection_id' => $collection_id
            );
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
        $collection_id = (int) $matches[1];
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

    // Check for VISIBILITY/HUMAN_REQUESTS routes
    if (strpos($slug, 'visibility/human-inbox') === 0) {
        $human_requests_controller = LUPOPEDIA_ABSPATH . '/lupo-routes/human_requests.php';
        if (file_exists($human_requests_controller)) {
            require_once $human_requests_controller;
            if (function_exists('human_requests_handle_slug')) {
                return human_requests_handle_slug($slug);
            }
        }
    }

    // Question prefixes for TRUTH routing
    $question_prefixes = array(
        'what/',
        'who/',
        'where/',
        'when/',
        'why/',
        'how/'
    );

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
