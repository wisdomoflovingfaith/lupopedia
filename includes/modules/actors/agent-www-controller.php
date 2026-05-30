<?php
/**
 * wolfie.headers: {
 *   file_path_from_root: "includes/modules/actors/agent-www-controller.php",
 *   system_version: "4.0.64",
 *   channel_id: 42,
 *   actor_id: 42,
 *   purpose: "Controller for actor-specific web content from the 'www' subdirectory.",
 *   last_modified_utc: "20260307"
 * }
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    exit;
}

require_once LUPOPEDIA_ABSPATH . 'includes/functions/actor-helpers.php';
require_once LUPOPEDIA_ABSPATH . 'includes/modules/content/renderers/content-renderer.php';

/**
 * Handle request for agent/<actor_name>/[path]
 *
 * @param string $actor_name
 * @param string $sub_path
 * @return string Rendered HTML
 */
function agent_www_handle_request($actor_name, $sub_path)
{
    global $lupo_actor_service;
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    $service = isset($lupo_actor_service) ? $lupo_actor_service : (class_exists('App\Services\ActorService') ? new \App\Services\ActorService($db) : null);

    $actor = null;
    if ($service) {
        $actor = $service->getActorByName($actor_name);
    } else {
        // Fallback if service unavailable
        $actor = lupo_get_actor($actor_name);
    }

    if (!$actor) {
        return content_render_not_found("agent/" . $actor_name);
    }

    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $actors_dir = defined('LUPO_ACTORS_DIR') ? LUPO_ACTORS_DIR : 'actors';

    // Resolve actor root (prefer name-based)
    $actor_root = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . $actors_dir . DIRECTORY_SEPARATOR . $actor_name;
    if (!is_dir($actor_root)) {
        // Fallback to ID-based if name-based doesn't exist on disk
        $actor_id = isset($actor['actor_id']) ? $actor['actor_id'] : 0;
        if ($actor_id > 0) {
            $actor_root = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . $actors_dir . DIRECTORY_SEPARATOR . $actor_id;
        }
    }

    $www_dir = $actor_root . DIRECTORY_SEPARATOR . 'www';
    if (!is_dir($www_dir)) {
        return render_main_layout(array(
            'page_body' => '<h1>Agent: ' . htmlspecialchars($actor_name) . '</h1><p>This agent has no public web content.</p>',
            'page_title' => $actor_name . ' Profile'
        ));
    }

    // Determine target file
    $target_file = null;
    $content_type = 'html';

    if ($sub_path === '' || $sub_path === '/') {
        // Priority logic for root
        $priority = array(
            'readme.md' => 'markdown',
            'index.htm' => 'html',
            'index.html' => 'html',
            'index.php' => 'php'
        );

        foreach ($priority as $file => $type) {
            if (is_file($www_dir . DIRECTORY_SEPARATOR . $file)) {
                $target_file = $www_dir . DIRECTORY_SEPARATOR . $file;
                $content_type = $type;
                break;
            }
        }
    } else {
        // Specific path request
        $target_file = $www_dir . DIRECTORY_SEPARATOR . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $sub_path);
        if (is_dir($target_file)) {
            // Check for index files in subdir
            $priority = array('readme.md' => 'markdown', 'index.htm' => 'html', 'index.html' => 'html', 'index.php' => 'php');
            foreach ($priority as $file => $type) {
                if (is_file($target_file . DIRECTORY_SEPARATOR . $file)) {
                    $target_file = $target_file . DIRECTORY_SEPARATOR . $file;
                    $content_type = $type;
                    break;
                }
            }
        } else {
            $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $content_type = ($ext === 'md') ? 'markdown' : (($ext === 'php') ? 'php' : 'html');
        }
    }

    // Security check: must be under $www_dir
    if ($target_file && strpos(realpath($target_file), realpath($www_dir)) !== 0) {
        header('HTTP/1.0 403 Forbidden');
        return 'Access Denied: Path traversal detected.';
    }

    if (!$target_file || !is_file($target_file)) {
        return content_render_not_found("agent/" . $actor_name . "/" . $sub_path);
    }

    // Render body
    $page_body = '';
    if ($content_type === 'php') {
        ob_start();
        include $target_file;
        $page_body = ob_get_clean();
    } elseif ($content_type === 'markdown') {
        $raw = file_get_contents($target_file);
        if (function_exists('render_markdown')) {
            $page_body = render_markdown($raw);
        } else {
            $page_body = '<pre>' . htmlspecialchars($raw) . '</pre>';
        }
    } else {
        $page_body = file_get_contents($target_file);
    }

    $context = array(
        'page_body' => $page_body,
        'page_title' => $actor_name . ' - ' . basename($target_file),
        'actor' => $actor
    );

    return render_main_layout($context);
}
