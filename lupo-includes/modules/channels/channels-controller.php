<?php
/**
 * Channels controller — channel 3-panel interface.
 * Resolves session actor_id, verifies channel access via lupo_actor_channel_roles / lupo_actor_channels,
 * loads threads (dialog_threads), channel roles/visitors (lupo_actor_channel_roles, lupo_actors, lupo_actor_channels),
 * and renders the channel interface view.
 * All paths use LUPOPEDIA_PUBLIC_PATH. New schema only.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

/**
 * Handle GET /channels/{channel_id}/ — show channel 3-panel interface (captain/administrator/monitor).
 *
 * @param int $channel_id Channel ID
 * @return string HTML from render_main_layout
 */
function channels_handle_show($channel_id) {
    $channel_id = (int) $channel_id;
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    // Resolve session actor_id (AuthService when available)
    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        $login_url = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/login?redirect=' . urlencode((defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/channels/' . $channel_id . '/');
        if (function_exists('lupo_safe_redirect')) {
            lupo_safe_redirect($login_url, 0, 'Please sign in to use the channel.');
        } else {
            header('Location: ' . $login_url);
        }
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>Database unavailable.</p>',
            'page_title' => 'Channel ' . $channel_id,
        ));
    }

    // Verify actor has access to this channel (lupo_actor_channels) or is global admin (access to any channel)
    $has_channel_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_channel_access = true;
    }
    if (!$has_channel_access && $authService && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_channel_access = true;
        }
    }
    if (!$has_channel_access) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>You do not have access to this channel.</p>',
            'page_title' => 'Access denied',
        ));
    }

    // Load channel row
    $stmt = $db->prepare("SELECT channel_id, channel_name, channel_key, channel_slug, status_flag FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id));
    $channel = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$channel) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>Channel not found.</p>',
            'page_title' => 'Channel ' . $channel_id,
        ));
    }

    // Actor's role in this channel (lupo_actor_channel_roles) or global admin — used for Channel Log button and role-based authority
    $actor_has_channel_role = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id, ':actor_id' => $actor_id));
    if ($stmt->fetch() !== false) {
        $actor_has_channel_role = true;
    }
    if (!$actor_has_channel_role && $authService && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $actor_has_channel_role = true;
        }
    }

    // All active threads (dialog_threads) for composer dropdown and thread bg_color map
    $threads = array();
    $thread_colors = array();
    $stmt = $db->prepare("SELECT dialog_thread_id, channel_id, task_name, summary_text, status, bg_color, text_color, created_ymdhis, updated_ymdhis, created_by_actor_id FROM {$table_prefix}dialog_threads WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY updated_ymdhis DESC LIMIT 100");
    $stmt->execute(array(':channel_id' => $channel_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $threads[] = $row;
        $tid = (int) $row['dialog_thread_id'];
        $bg = isset($row['bg_color']) && preg_match('/^[0-9A-Fa-f]{6}$/', $row['bg_color']) ? $row['bg_color'] : 'FFFACD';
        $thread_colors[$tid] = $bg;
    }

    // Legacy "clear to now": only show messages from (last_ymdhis - 2) so view is cleared (docs §10)
    $message_after = '0';
    $clear = isset($_GET['clear']) ? $_GET['clear'] : (isset($_GET['cleartonow']) ? (int) $_GET['cleartonow'] : null);
    if ($clear === 'now' || $clear === 1) {
        $stmt = $db->prepare("SELECT created_ymdhis FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY created_ymdhis DESC LIMIT 1");
        $stmt->execute(array(':channel_id' => $channel_id));
        $last = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($last && !empty($last['created_ymdhis'])) {
            $message_after = (string) max(0, (int) $last['created_ymdhis'] - 2);
        }
    }

    // Single unified message stream: all dialog_messages for channel, ORDER BY created_ymdhis ASC (legacy timeof; docs §1)
    $messages = array();
    $stmt = $db->prepare("SELECT dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 AND created_ymdhis > :after ORDER BY created_ymdhis ASC LIMIT 500");
    $stmt->execute(array(':channel_id' => $channel_id, ':after' => $message_after));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = $row;
    }

    // Initial after_ymdhis for client-side polling (last message timestamp or message_after)
    $initial_after_ymdhis = $message_after;
    foreach ($messages as $m) {
        $t = (string) ($m['created_ymdhis'] ?? '');
        if ($t > $initial_after_ymdhis) {
            $initial_after_ymdhis = $t;
        }
    }

    // Actor names for message display (from_actor_id => name)
    $actor_names = array();
    if (!empty($messages)) {
        $actor_ids = array_unique(array_filter(array_column($messages, 'from_actor_id')));
        if (!empty($actor_ids)) {
            $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
            $stmt = $db->prepare("SELECT actor_id, name FROM {$table_prefix}actors WHERE actor_id IN ($placeholders) AND is_deleted = 0");
            $stmt->execute(array_values($actor_ids));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $actor_names[(int) $row['actor_id']] = $row['name'];
            }
        }
    }

    // Channel roles for this channel (lupo_actor_channel_roles + lupo_actors)
    $operators = array();
    $stmt = $db->prepare("SELECT r.actor_channel_role_id, r.actor_id, r.channel_id, r.role_key AS role_type, a.name AS actor_name, a.slug AS actor_slug FROM {$table_prefix}actor_channel_roles r LEFT JOIN {$table_prefix}actors a ON a.actor_id = r.actor_id AND a.is_deleted = 0 WHERE r.channel_id = :channel_id AND r.is_deleted = 0");
    $stmt->execute(array(':channel_id' => $channel_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $operators[] = $row;
    }

    // Department for this channel (for pending-visitors poll)
    $department_id = 0;
    try {
        $stmt = $db->prepare("SELECT department_id FROM {$table_prefix}channels WHERE channel_id = :cid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':cid' => $channel_id));
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r && isset($r['department_id'])) {
            $department_id = (int) $r['department_id'];
        }
    } catch (Throwable $e) {
        // ignore
    }
    if ($department_id <= 0) {
        $stmt = $db->prepare("SELECT department_id FROM {$table_prefix}actor_departments WHERE actor_id = :aid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':aid' => $actor_id));
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r && !empty($r['department_id'])) {
            $department_id = (int) $r['department_id'];
        }
    }

    // Pending visitors (session metadata crafty_syntax.status = pending, department_id match) — initial load; panel will poll
    $pending_visitors = array();
    $meta_col = 'metadata_json';
    try {
        $stmt = $db->prepare("SELECT session_id, last_seen_ymdhis, created_ymdhis, name_key, is_named, {$meta_col} FROM {$table_prefix}sessions WHERE actor_id = 0 AND is_deleted = 0 AND {$meta_col} IS NOT NULL AND {$meta_col} != ''");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $meta = json_decode($row[$meta_col] ?? '{}', true);
            if (!is_array($meta) || empty($meta['crafty_syntax'])) {
                continue;
            }
            $cs = $meta['crafty_syntax'];
            if ((isset($cs['status']) ? (string) $cs['status'] : '') !== 'pending') {
                continue;
            }
            if ($department_id > 0 && (isset($cs['department_id']) ? (int) $cs['department_id'] : 0) !== $department_id) {
                continue;
            }
            $tid = isset($cs['dialog_thread_id']) ? (int) $cs['dialog_thread_id'] : 0;
            if ($tid <= 0) {
                continue;
            }
            $pending_visitors[] = array(
                'visitor_session_id' => $row['session_id'],
                'dialog_thread_id'   => $tid,
                'department_id'      => (int)(isset($cs['department_id']) ? $cs['department_id'] : 0),
                'created_ymdhis'     => isset($row['created_ymdhis']) ? $row['created_ymdhis'] : (isset($row['last_seen_ymdhis']) ? $row['last_seen_ymdhis'] : ''),
                'name_key'           => isset($row['name_key']) ? (string) $row['name_key'] : null,
                'is_named'           => isset($row['is_named']) ? (int) $row['is_named'] : 0,
            );
        }
    } catch (Throwable $e) {
        // ignore
    }

    // Visitors/actors in this channel: lupo_actor_channels (channel roles/other actors) + sessions with metadata.crafty_syntax.channel_id = this channel, status = active
    $visitors = array();
    $stmt = $db->prepare("SELECT ac.actor_channel_id, ac.actor_id, ac.channel_id, ac.status, ac.channel_color, a.name AS actor_name, a.slug AS actor_slug, a.actor_type FROM {$table_prefix}actor_channels ac LEFT JOIN {$table_prefix}actors a ON a.actor_id = ac.actor_id AND a.is_deleted = 0 WHERE ac.channel_id = :channel_id AND ac.is_deleted = 0 ORDER BY ac.updated_ymdhis DESC");
    $stmt->execute(array(':channel_id' => $channel_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visitors[] = $row;
    }
    try {
        $stmt = $db->prepare("SELECT session_id, last_seen_ymdhis, name_key, is_named, {$meta_col} FROM {$table_prefix}sessions WHERE actor_id = 0 AND is_deleted = 0 AND {$meta_col} IS NOT NULL AND {$meta_col} != ''");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $meta = json_decode($row[$meta_col] ?? '{}', true);
            if (!is_array($meta) || empty($meta['crafty_syntax'])) {
                continue;
            }
            $cs = $meta['crafty_syntax'];
            if ((isset($cs['status']) ? (string) $cs['status'] : '') !== 'active') {
                continue;
            }
            if ((isset($cs['channel_id']) ? (int) $cs['channel_id'] : 0) !== $channel_id) {
                continue;
            }
            $tid = isset($cs['dialog_thread_id']) ? (int) $cs['dialog_thread_id'] : 0;
            $visitor_name = (!empty($row['is_named']) && isset($row['name_key']) && $row['name_key'] !== '') ? (string) $row['name_key'] : ('Visitor ' . substr($row['session_id'], 0, 8));
            $visitors[] = array(
                'actor_channel_id' => 0,
                'actor_id'         => 0,
                'channel_id'       => $channel_id,
                'status'           => 'A',
                'actor_name'       => $visitor_name,
                'actor_slug'       => 'visitor-' . substr($row['session_id'], 0, 8),
                'actor_type'       => 'anonymous',
                'visitor_session_id' => $row['session_id'],
                'dialog_thread_id'   => $tid,
                'name_key'         => isset($row['name_key']) ? (string) $row['name_key'] : null,
                'is_named'         => isset($row['is_named']) ? (int) $row['is_named'] : 0,
            );
        }
    } catch (Throwable $e) {
        // ignore
    }

    // Load render_main_layout if not already loaded
    if (!function_exists('render_main_layout')) {
        $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($renderer)) {
            require_once $renderer;
        }
    }

    $public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $view_path = $app_root . '/lupo-includes/modules/channels/views/show.php';
    if (!file_exists($view_path)) {
        return render_main_layout(array(
            'page_body' => '<p>Channel view not found.</p>',
            'page_title' => $channel['channel_name'],
        ));
    }

    // Selected thread for composer (tab); legacy channelsplit = channel__userid → we use dialog_thread_id
    $selected_thread_id = isset($_GET['thread']) ? (int) $_GET['thread'] : (!empty($threads) ? (int) $threads[0]['dialog_thread_id'] : 0);

    // Current actor display name for composer typing
    $current_actor_name = 'Staff';
    $stmt = $db->prepare("SELECT name FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id));
    $ar = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ar && !empty($ar['name'])) {
        $current_actor_name = $ar['name'];
    }

    ob_start();
    extract(array(
        'channel_id'             => $channel_id,
        'channel'                => $channel,
        'threads'                => $threads,
        'messages'               => $messages,
        'thread_colors'           => $thread_colors,
        'actor_names'             => $actor_names,
        'operators'               => $operators,
        'visitors'                => $visitors,
        'pending_visitors'        => $pending_visitors,
        'department_id'           => $department_id,
        'current_actor_id'       => $actor_id,
        'current_actor_name'     => $current_actor_name,
        'channel_public_path'    => $public_path,
        'initial_after_ymdhis'   => $initial_after_ymdhis,
        'selected_thread_id'     => $selected_thread_id,
        'actor_has_channel_role' => $actor_has_channel_role,
    ), EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();

    return render_main_layout(array(
        'page_body'  => $page_body,
        'page_title' => $channel['channel_name'],
    ));
}

/**
 * Handle GET /channels/{channel_id}/stream — iframe message stream only (legacy livehelp pattern).
 * Returns a minimal HTML document with the message list and polling script; no main layout.
 *
 * @param int $channel_id Channel ID
 * @return string Raw HTML for iframe content
 */
function channels_handle_stream($channel_id) {
    $channel_id = (int) $channel_id;
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $actor_id = null;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Channel stream</title></head><body><p>Please sign in.</p></body></html>';
        exit;
    }

    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if (!$db) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Channel stream</title></head><body><p>Database unavailable.</p></body></html>';
        exit;
    }

    $has_channel_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array('actor_id' => $actor_id, 'channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_channel_access = true;
    }
    if (!$has_channel_access && $authService && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_channel_access = true;
        }
    }
    if (!$has_channel_access) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Channel stream</title></head><body><p>You do not have access to this channel.</p></body></html>';
        exit;
    }

    $stmt = $db->prepare("SELECT channel_id, channel_name FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array('channel_id' => $channel_id));
    $channel = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$channel) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Channel stream</title></head><body><p>Channel not found.</p></body></html>';
        exit;
    }

    $thread_colors = array();
    $stmt = $db->prepare("SELECT dialog_thread_id, bg_color FROM {$table_prefix}dialog_threads WHERE channel_id = :channel_id AND is_deleted = 0");
    $stmt->execute(array('channel_id' => $channel_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tid = (int) $row['dialog_thread_id'];
        $bg = isset($row['bg_color']) && preg_match('/^[0-9A-Fa-f]{6}$/', $row['bg_color']) ? $row['bg_color'] : 'FFFACD';
        $thread_colors[$tid] = $bg;
    }

    $message_after = '0';
    $clear = isset($_GET['clear']) ? $_GET['clear'] : (isset($_GET['cleartonow']) ? (int) $_GET['cleartonow'] : null);
    if ($clear === 'now' || $clear === 1) {
        $stmt = $db->prepare("SELECT created_ymdhis FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY created_ymdhis DESC LIMIT 1");
        $stmt->execute(array('channel_id' => $channel_id));
        $last = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($last && !empty($last['created_ymdhis'])) {
            $message_after = (string) max(0, (int) $last['created_ymdhis'] - 2);
        }
    }

    $messages = array();
    $stmt = $db->prepare("SELECT dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 AND created_ymdhis > :after ORDER BY created_ymdhis ASC LIMIT 500");
    $stmt->execute(array('channel_id' => $channel_id, 'after' => $message_after));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = $row;
    }

    $initial_after_ymdhis = $message_after;
    foreach ($messages as $m) {
        $t = (string) (isset($m['created_ymdhis']) ? $m['created_ymdhis'] : '');
        if ($t > $initial_after_ymdhis) {
            $initial_after_ymdhis = $t;
        }
    }

    $actor_names = array();
    if (!empty($messages)) {
        $actor_ids = array_unique(array_filter(array_column($messages, 'from_actor_id')));
        if (!empty($actor_ids)) {
            $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
            $stmt = $db->prepare("SELECT actor_id, name FROM {$table_prefix}actors WHERE actor_id IN ($placeholders) AND is_deleted = 0");
            $stmt->execute(array_values($actor_ids));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $actor_names[(int) $row['actor_id']] = $row['name'];
            }
        }
    }

    $public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $view_path = $app_root . '/lupo-includes/modules/channels/views/stream.php';
    if (!file_exists($view_path)) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><head><title>Channel stream</title></head><body><p>Stream view not found.</p></body></html>';
        exit;
    }

    ob_start();
    extract(array(
        'channel_id' => $channel_id,
        'channel' => $channel,
        'messages' => $messages,
        'thread_colors' => $thread_colors,
        'actor_names' => $actor_names,
        'channel_public_path' => $public_path,
        'initial_after_ymdhis' => $initial_after_ymdhis,
    ), EXTR_SKIP);
    include $view_path;
    $html = ob_get_clean();

    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    exit;
}

/**
 * Handle GET /channels/{channel_id}/log — show channel governance log.
 * Loads channel, actor's role (lupo_actor_channel_roles), log entries (lupo_channel_logs), log types (lupo_channel_log_types).
 * View shows "New Log Entry" button only if actor has a role in lupo_actor_channel_roles.
 *
 * @param int $channel_id Channel ID
 * @return string HTML from render_main_layout
 */
function channels_handle_log_show($channel_id) {
    $channel_id = (int) $channel_id;
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        $login_url = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/login?redirect=' . urlencode((defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/channels/' . $channel_id . '/log');
        if (function_exists('lupo_safe_redirect')) {
            lupo_safe_redirect($login_url, 0, 'Please sign in to view the channel log.');
        } else {
            header('Location: ' . $login_url);
        }
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>Database unavailable.</p>',
            'page_title' => 'Channel Log',
        ));
    }

    $has_channel_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_channel_access = true;
    }
    if (!$has_channel_access && $authService && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_channel_access = true;
        }
    }
    if (!$has_channel_access) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>You do not have access to this channel.</p>',
            'page_title' => 'Access denied',
        ));
    }

    $stmt = $db->prepare("SELECT channel_id, channel_name, channel_key, channel_slug, status_flag FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id));
    $channel = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$channel) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>Channel not found.</p>',
            'page_title' => 'Channel Log',
        ));
    }

    // Actor's role in this channel (lupo_actor_channel_roles row) or global admin; used for log entry permission
    $actor_role = null;
    $log_entries = array();
    $log_types = array();
    $actor_names = array();
    if ($db instanceof \PDO_DB) {
        $actor_role = $db->fetchRow(
            "SELECT actor_channel_role_id, role_key AS role_type FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1",
            array('channel_id' => $channel_id, 'actor_id' => $actor_id)
        );
        if (!$actor_role && $authService && method_exists($authService, 'isAdmin') && $authService->isAdmin($actor_id)) {
            $actor_role = array('actor_channel_role_id' => 0, 'role_type' => 'captain');
        }
        $log_entries = $db->fetchAll(
            "SELECT l.channel_log_id, l.channel_id, l.actor_id, l.role_type, l.log_type_id, l.log_text, l.metadata_json, l.pinned, l.created_ymdhis, l.updated_ymdhis FROM {$table_prefix}channel_logs l WHERE l.channel_id = :channel_id AND l.is_deleted = 0 ORDER BY l.pinned DESC, l.created_ymdhis DESC",
            ['channel_id' => $channel_id]
        );
        $log_types = $db->fetchAll("SELECT log_type_id, type_key, type_label, description FROM {$table_prefix}channel_log_types WHERE is_deleted = 0 ORDER BY type_label");
    }
    $actor_ids = array_unique(array_filter(array_column($log_entries, 'actor_id')));
    if (!empty($actor_ids) && $db instanceof \PDO_DB) {
        $params = array();
        $ph = array();
        foreach (array_values($actor_ids) as $i => $id) {
            $k = 'aid' . $i;
            $params[$k] = $id;
            $ph[] = ':' . $k;
        }
        $placeholders = implode(',', $ph);
        $rows = $db->fetchAll("SELECT actor_id, name FROM {$table_prefix}actors WHERE actor_id IN ($placeholders) AND is_deleted = 0", $params);
        foreach ($rows as $row) {
            $actor_names[(int) $row['actor_id']] = $row['name'];
        }
    }

    $log_type_map = array();
    foreach ($log_types as $lt) {
        $log_type_map[(int) $lt['log_type_id']] = $lt;
    }

    if (!function_exists('render_main_layout')) {
        $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($renderer)) {
            require_once $renderer;
        }
    }

    $public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $view_path = $app_root . '/lupo-includes/modules/channels/views/channel-log.php';
    if (!file_exists($view_path)) {
        return render_main_layout(array(
            'page_body' => '<p>Channel log view not found.</p>',
            'page_title' => $channel['channel_name'] . ' — Log',
        ));
    }

    ob_start();
    extract(array(
        'channel_id'          => $channel_id,
        'channel'             => $channel,
        'actor_role'          => $actor_role,
        'log_entries'         => $log_entries,
        'log_types'           => $log_types,
        'log_type_map'        => $log_type_map,
        'actor_names'         => $actor_names,
        'channel_public_path' => $public_path,
    ), EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();

    return render_main_layout(array(
        'page_body'  => $page_body,
        'page_title' => $channel['channel_name'] . ' — Channel Log',
    ));
}

/**
 * Handle POST /channels/{channel_id}/log/create — create a new channel log entry.
 * Validates actor has a role in lupo_actor_channel_roles; inserts into lupo_channel_logs; redirects to GET log.
 *
 * @param int $channel_id Channel ID
 * @return void Redirects or outputs error
 */
function channels_handle_log_create($channel_id) {
    $channel_id = (int) $channel_id;
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        header('Location: ' . $base . '/login?redirect=' . urlencode($base . '/channels/' . $channel_id . '/log'));
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        header('Location: ' . $base . '/channels/' . $channel_id . '/log');
        exit;
    }

    $stmt = $db->prepare("SELECT actor_channel_role_id, role_key AS role_type FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id, ':actor_id' => $actor_id));
    $role_row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$role_row && $authService && method_exists($authService, 'isAdmin') && $authService->isAdmin($actor_id)) {
        $role_row = array('actor_channel_role_id' => 0, 'role_type' => 'captain');
    }
    if (!$role_row) {
        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        header('Location: ' . $base . '/channels/' . $channel_id . '/log');
        exit;
    }

    $log_type_id = isset($_POST['log_type_id']) ? (int) $_POST['log_type_id'] : 0;
    $log_text = isset($_POST['log_text']) ? trim((string) $_POST['log_text']) : '';
    $metadata_json = null;
    if (isset($_POST['metadata_json']) && trim((string) $_POST['metadata_json']) !== '') {
        $dec = json_decode(trim((string) $_POST['metadata_json']), true);
        $metadata_json = (json_last_error() === JSON_ERROR_NONE && $dec !== null) ? json_encode($dec) : null;
    }

    if ($log_type_id <= 0 || $log_text === '') {
        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        header('Location: ' . $base . '/channels/' . $channel_id . '/log');
        exit;
    }

    $now = date('YmdHis');
    $role_type = $role_row['role_type'];

    $stmt = $db->prepare(
        "INSERT INTO {$table_prefix}channel_logs (channel_id, actor_id, role_type, log_type_id, log_text, metadata_json, created_ymdhis, updated_ymdhis, is_deleted) " .
        "VALUES (:channel_id, :actor_id, :role_type, :log_type_id, :log_text, :metadata_json, :created_ymdhis, :updated_ymdhis, 0)"
    );
    $stmt->execute(array(
        ':channel_id'     => $channel_id,
        ':actor_id'       => $actor_id,
        ':role_type'      => $role_type,
        ':log_type_id'    => $log_type_id,
        ':log_text'       => $log_text,
        ':metadata_json'  => $metadata_json,
        ':created_ymdhis' => $now,
        ':updated_ymdhis' => $now,
    ));

    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    header('Location: ' . $base . '/channels/' . $channel_id . '/log');
    exit;
}

/**
 * Handle GET /channels/my-channels — list all channels the current actor has a role in (lupo_actor_channel_roles).
 * Replaces single "My Channel" with multi-channel list; authority is role-based, not creator-based.
 *
 * @return string HTML from render_main_layout
 */
function channels_handle_my_channels() {
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        $login_url = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/login?redirect=' . urlencode((defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/channels/my-channels');
        if (function_exists('lupo_safe_redirect')) {
            lupo_safe_redirect($login_url, 0, 'Please sign in to view your channels.');
        } else {
            header('Location: ' . $login_url);
        }
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout(array(
            'page_body' => '<p>Database unavailable.</p>',
            'page_title' => 'My Channels',
        ));
    }

    $roles = array();
    $stmt = $db->prepare("SELECT channel_id, role_key AS role_type FROM {$table_prefix}actor_channel_roles WHERE actor_id = :actor_id AND is_deleted = 0 ORDER BY channel_id ASC");
    $stmt->execute(array(':actor_id' => $actor_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row;
    }

    $my_channels = array();
    foreach ($roles as $r) {
        $cid = (int) $r['channel_id'];
        $stmt = $db->prepare("SELECT channel_id, channel_name, channel_key, channel_slug, status_flag FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':channel_id' => $cid));
        $ch = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($ch) {
            $my_channels[] = array(
                'channel_id'   => $cid,
                'channel_name' => isset($ch['channel_name']) ? $ch['channel_name'] : ('Channel ' . $cid),
                'role_type'     => $r['role_type'],
            );
        }
    }

    if (!function_exists('render_main_layout')) {
        $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($renderer)) {
            require_once $renderer;
        }
    }

    $public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $view_path = $app_root . '/lupo-includes/modules/channels/views/my-channels.php';
    if (!file_exists($view_path)) {
        return render_main_layout(array(
            'page_body' => '<p>My Channels view not found.</p>',
            'page_title' => 'My Channels',
        ));
    }

    ob_start();
    extract(array(
        'my_channels'        => $my_channels,
        'channel_public_path' => $public_path,
    ), EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();

    return render_main_layout(array(
        'page_body'  => $page_body,
        'page_title' => 'My Channels',
    ));
}

/**
 * Handle GET /channels/{channel_id}/edit — channel edit page (captain/administrator/monitor only).
 * Loads channel (name, description, website_link) and all roles; passes to edit view.
 *
 * @param int $channel_id Channel ID
 * @return string HTML or redirect
 */
function channels_handle_edit_channel($channel_id) {
    $channel_id = (int) $channel_id;
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        header('Location: ' . $base . '/login?redirect=' . urlencode($base . '/channels/' . $channel_id . '/edit'));
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        header('Location: ' . $base . '/channels/' . $channel_id . '/');
        exit;
    }

    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id, ':actor_id' => $actor_id));
    if ($stmt->fetch() === false) {
        header('Location: ' . $base . '/channels/' . $channel_id . '/');
        exit;
    }

    $stmt = $db->prepare("SELECT channel_id, channel_name, description, website_link FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id));
    $channel = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$channel) {
        header('Location: ' . $base . '/channels/' . $channel_id . '/');
        exit;
    }

    $roles = array();
    $stmt = $db->prepare("SELECT actor_channel_role_id, channel_id, actor_id, role_key AS role_type FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY role_key, actor_id");
    $stmt->execute(array(':channel_id' => $channel_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row;
    }

    $captain = array();
    $administrators = array();
    $monitors = array();
    foreach ($roles as $r) {
        $r['actor_id'] = (int) $r['actor_id'];
        if ($r['role_type'] === 'captain') {
            $captain[] = $r;
        } elseif ($r['role_type'] === 'administrator') {
            $administrators[] = $r;
        } else {
            $monitors[] = $r;
        }
    }

    $actor_ids = array_unique(array_filter(array_column($roles, 'actor_id')));
    $actor_names = array();
    if (!empty($actor_ids)) {
        $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
        $stmt = $db->prepare("SELECT actor_id, name FROM {$table_prefix}actors WHERE actor_id IN ($placeholders) AND is_deleted = 0");
        $stmt->execute(array_values($actor_ids));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $actor_names[(int) $row['actor_id']] = $row['name'];
        }
    }

    if (!function_exists('render_main_layout')) {
        $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($renderer)) {
            require_once $renderer;
        }
    }

    $view_path = $app_root . '/lupo-includes/modules/channels/views/edit-channel.php';
    if (!file_exists($view_path)) {
        return render_main_layout(array(
            'page_body' => '<p>Edit channel view not found.</p>',
            'page_title' => 'Edit Channel',
        ));
    }

    ob_start();
    extract(array(
        'channel_id'           => $channel_id,
        'channel'              => $channel,
        'captain'              => $captain,
        'administrators'       => $administrators,
        'monitors'             => $monitors,
        'actor_names'          => $actor_names,
        'channel_public_path'  => $base,
    ), EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();

    return render_main_layout(array(
        'page_body'  => $page_body,
        'page_title' => 'Edit Channel — ' . ($channel['channel_name'] ?? 'Channel'),
    ));
}

/**
 * Handle POST /channels/{channel_id}/edit/save — save channel edit form.
 * Updates lupo_channels (name, description, website_link) and lupo_actor_channel_roles (captain, admins, monitors).
 *
 * @param int $channel_id Channel ID
 */
function channels_handle_edit_channel_save($channel_id) {
    $channel_id = (int) $channel_id;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        header('Location: ' . $base . '/login?redirect=' . urlencode($base . '/channels/' . $channel_id . '/edit'));
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        header('Location: ' . $base . '/channels/' . $channel_id . '/edit');
        exit;
    }

    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':channel_id' => $channel_id, ':actor_id' => $actor_id));
    if ($stmt->fetch() === false) {
        header('Location: ' . $base . '/channels/' . $channel_id . '/');
        exit;
    }

    $channel_name = isset($_POST['channel_name']) ? trim((string) $_POST['channel_name']) : '';
    $description = isset($_POST['description']) ? trim((string) $_POST['description']) : '';
    $website_link = isset($_POST['website_link']) ? trim((string) $_POST['website_link']) : '';
    $website_link = strlen($website_link) > 512 ? substr($website_link, 0, 512) : $website_link;

    $captain_actor_id = isset($_POST['captain_actor_id']) ? (int) $_POST['captain_actor_id'] : 0;
    $administrator_actor_ids = isset($_POST['administrator_actor_ids']) && is_array($_POST['administrator_actor_ids'])
        ? array_values(array_unique(array_filter(array_map('intval', $_POST['administrator_actor_ids']))))
        : array();
    $monitor_actor_ids = isset($_POST['monitor_actor_ids']) && is_array($_POST['monitor_actor_ids'])
        ? array_values(array_unique(array_filter(array_map('intval', $_POST['monitor_actor_ids']))))
        : array();

    $now = date('YmdHis');

    if ($channel_name !== '') {
        $stmt = $db->prepare("UPDATE {$table_prefix}channels SET channel_name = :name, description = :desc, website_link = :link, updated_ymdhis = :now WHERE channel_id = :cid");
        $stmt->execute(array(
            ':name' => $channel_name,
            ':desc' => $description,
            ':link' => $website_link === '' ? null : $website_link,
            ':now'  => $now,
            ':cid'  => $channel_id,
        ));
    }

    $now_char = $now;

    // Ensure exactly one captain: soft-delete all current captains, then insert/restore one for captain_actor_id
    $stmt = $db->prepare("UPDATE {$table_prefix}actor_channel_roles SET is_deleted = 1, deleted_ymdhis = :now WHERE channel_id = :cid AND role_key = 'captain'");
    $stmt->execute(array(':now' => $now_char, ':cid' => $channel_id));

    if ($captain_actor_id > 0) {
        $stmt = $db->prepare("SELECT actor_channel_role_id FROM {$table_prefix}actor_channel_roles WHERE channel_id = :cid AND actor_id = :aid AND role_key = 'captain' LIMIT 1");
        $stmt->execute(array(':cid' => $channel_id, ':aid' => $captain_actor_id));
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            $stmt = $db->prepare("UPDATE {$table_prefix}actor_channel_roles SET is_deleted = 0, deleted_ymdhis = NULL, updated_ymdhis = :now WHERE actor_channel_role_id = :id");
            $stmt->execute(array(':now' => $now_char, ':id' => $existing['actor_channel_role_id']));
        } else {
            $cr_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $table_prefix . 'actor_channel_roles', 'actor_channel_role_id', 1, null) : (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM " . $db->quoteIdentifier($table_prefix . 'actor_channel_roles'), array());
            if ($cr_id !== null) {
                $stmt = $db->prepare("INSERT INTO {$table_prefix}actor_channel_roles (actor_channel_role_id, channel_id, actor_id, role_key, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:id, :cid, :aid, 'captain', :now, :now, 0)");
                $stmt->execute(array(':id' => $cr_id, ':cid' => $channel_id, ':aid' => $captain_actor_id, ':now' => $now_char));
            }
        }
    }

    // Administrators: soft-delete all, then restore/insert for each in list
    $stmt = $db->prepare("UPDATE {$table_prefix}actor_channel_roles SET is_deleted = 1, deleted_ymdhis = :now WHERE channel_id = :cid AND role_key = 'administrator'");
    $stmt->execute(array(':now' => $now_char, ':cid' => $channel_id));

    foreach ($administrator_actor_ids as $aid) {
        if ($aid <= 0) {
            continue;
        }
        $stmt = $db->prepare("SELECT actor_channel_role_id, is_deleted FROM {$table_prefix}actor_channel_roles WHERE channel_id = :cid AND actor_id = :aid AND role_key = 'administrator' LIMIT 1");
        $stmt->execute(array(':cid' => $channel_id, ':aid' => $aid));
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            if (!empty($existing['is_deleted'])) {
                $stmt = $db->prepare("UPDATE {$table_prefix}actor_channel_roles SET is_deleted = 0, deleted_ymdhis = NULL, updated_ymdhis = :now WHERE actor_channel_role_id = :id");
                $stmt->execute(array(':now' => $now_char, ':id' => $existing['actor_channel_role_id']));
            }
        } else {
            $cr_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $table_prefix . 'actor_channel_roles', 'actor_channel_role_id', 1, null) : (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM " . $db->quoteIdentifier($table_prefix . 'actor_channel_roles'), array());
            if ($cr_id !== null) {
                $stmt = $db->prepare("INSERT INTO {$table_prefix}actor_channel_roles (actor_channel_role_id, channel_id, actor_id, role_key, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:id, :cid, :aid, 'administrator', :now, :now, 0)");
                $stmt->execute(array(':id' => $cr_id, ':cid' => $channel_id, ':aid' => $aid, ':now' => $now_char));
            }
        }
    }

    // Monitors: same as administrators
    $stmt = $db->prepare("UPDATE {$table_prefix}actor_channel_roles SET is_deleted = 1, deleted_ymdhis = :now WHERE channel_id = :cid AND role_key = 'monitor'");
    $stmt->execute(array(':now' => $now_char, ':cid' => $channel_id));
    foreach ($monitor_actor_ids as $aid) {
        if ($aid <= 0) {
            continue;
        }
        $stmt = $db->prepare("SELECT actor_channel_role_id FROM {$table_prefix}actor_channel_roles WHERE channel_id = :cid AND actor_id = :aid AND role_key = 'monitor' LIMIT 1");
        $stmt->execute(array(':cid' => $channel_id, ':aid' => $aid));
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            $stmt = $db->prepare("UPDATE {$table_prefix}actor_channel_roles SET is_deleted = 0, deleted_ymdhis = NULL, updated_ymdhis = :now WHERE actor_channel_role_id = :id");
            $stmt->execute(array(':now' => $now_char, ':id' => $existing['actor_channel_role_id']));
        } else {
            $cr_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $table_prefix . 'actor_channel_roles', 'actor_channel_role_id', 1, null) : (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM " . $db->quoteIdentifier($table_prefix . 'actor_channel_roles'), array());
            if ($cr_id !== null) {
                $stmt = $db->prepare("INSERT INTO {$table_prefix}actor_channel_roles (actor_channel_role_id, channel_id, actor_id, role_key, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:id, :cid, :aid, 'monitor', :now, :now, 0)");
                $stmt->execute(array(':id' => $cr_id, ':cid' => $channel_id, ':aid' => $aid, ':now' => $now_char));
            }
        }
    }

    header('Location: ' . $base . '/channels/' . $channel_id . '/edit');
    exit;
}
