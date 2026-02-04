<?php
/**
 * Channels controller — operator 3-panel interface for a channel.
 * Resolves session actor_id, verifies channel access via lupo_actor_channels,
 * loads threads (dialog_threads), operators/visitors (lupo_operators, lupo_actors, lupo_actor_channels),
 * and renders the operator interface view.
 * All paths use LUPOPEDIA_PUBLIC_PATH. New schema only.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

/**
 * Handle GET /channels/{channel_id}/ — show operator 3-panel interface.
 *
 * @param int $channel_id Channel ID
 * @return string HTML from render_main_layout
 */
function channels_handle_show($channel_id) {
    $channel_id = (int) $channel_id;
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    // Resolve session actor_id (same as my-channel.php)
    $actor_id = null;
    if (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && function_exists('lupo_validate_session')) {
        $actor_id = lupo_validate_session();
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
        return render_main_layout([
            'page_body' => '<p>Database unavailable.</p>',
            'page_title' => 'Channel ' . $channel_id,
        ]);
    }

    // Verify operator has access to this channel (lupo_actor_channels)
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':actor_id' => $actor_id, ':channel_id' => $channel_id]);
    if ($stmt->fetch() === false) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout([
            'page_body' => '<p>You do not have access to this channel.</p>',
            'page_title' => 'Access denied',
        ]);
    }

    // Load channel row
    $stmt = $db->prepare("SELECT channel_id, channel_name, channel_key, channel_slug, status_flag FROM {$table_prefix}channels WHERE channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':channel_id' => $channel_id]);
    $channel = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$channel) {
        if (!function_exists('render_main_layout')) {
            $renderer = $app_root . '/lupo-includes/modules/content/renderers/content-renderer.php';
            if (file_exists($renderer)) {
                require_once $renderer;
            }
        }
        return render_main_layout([
            'page_body' => '<p>Channel not found.</p>',
            'page_title' => 'Channel ' . $channel_id,
        ]);
    }

    // All active threads (dialog_threads) for composer dropdown and thread bg_color map
    $threads = [];
    $thread_colors = [];
    $stmt = $db->prepare("SELECT dialog_thread_id, channel_id, task_name, summary_text, status, bg_color, text_color, created_ymdhis, updated_ymdhis, created_by_actor_id FROM {$table_prefix}dialog_threads WHERE channel_id = :channel_id AND is_deleted = 0 ORDER BY updated_ymdhis DESC LIMIT 100");
    $stmt->execute([':channel_id' => $channel_id]);
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
        $stmt->execute([':channel_id' => $channel_id]);
        $last = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($last && !empty($last['created_ymdhis'])) {
            $message_after = (string) max(0, (int) $last['created_ymdhis'] - 2);
        }
    }

    // Single unified message stream: all dialog_messages for channel, ORDER BY created_ymdhis ASC (legacy timeof; docs §1)
    $messages = [];
    $stmt = $db->prepare("SELECT dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 AND created_ymdhis > :after ORDER BY created_ymdhis ASC LIMIT 500");
    $stmt->execute([':channel_id' => $channel_id, ':after' => $message_after]);
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
    $actor_names = [];
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

    // Operators for this channel (lupo_operators + lupo_actors)
    $operators = [];
    $stmt = $db->prepare("SELECT o.operator_id, o.actor_id, o.channel_id, o.availability_status, a.name AS actor_name, a.slug AS actor_slug FROM {$table_prefix}operators o LEFT JOIN {$table_prefix}actors a ON a.actor_id = o.actor_id AND a.is_deleted = 0 WHERE o.channel_id = :channel_id AND o.is_active = 1");
    $stmt->execute([':channel_id' => $channel_id]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $operators[] = $row;
    }

    // Department for this operator/channel (for pending-visitors poll)
    $department_id = 0;
    try {
        $stmt = $db->prepare("SELECT department_id FROM {$table_prefix}channels WHERE channel_id = :cid AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':cid' => $channel_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r && isset($r['department_id'])) {
            $department_id = (int) $r['department_id'];
        }
    } catch (Throwable $e) {
        // ignore
    }
    if ($department_id <= 0) {
        $stmt = $db->prepare("SELECT department_id FROM {$table_prefix}actor_departments WHERE actor_id = :aid AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':aid' => $actor_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($r && !empty($r['department_id'])) {
            $department_id = (int) $r['department_id'];
        }
    }

    // Pending visitors (session metadata crafty_syntax.status = pending, department_id match) — initial load; panel will poll
    $pending_visitors = [];
    $meta_col = 'metadata_json';
    try {
        $stmt = $db->prepare("SELECT session_id, last_seen_ymdhis, created_ymdhis, {$meta_col} FROM {$table_prefix}sessions WHERE actor_id = 0 AND is_deleted = 0 AND {$meta_col} IS NOT NULL AND {$meta_col} != ''");
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
            $pending_visitors[] = [
                'visitor_session_id' => $row['session_id'],
                'dialog_thread_id'   => $tid,
                'department_id'      => (int)($cs['department_id'] ?? 0),
                'created_ymdhis'     => $row['created_ymdhis'] ?? $row['last_seen_ymdhis'] ?? '',
            ];
        }
    } catch (Throwable $e) {
        // ignore
    }

    // Visitors/actors in this channel: lupo_actor_channels (operators/other actors) + sessions with metadata.crafty_syntax.channel_id = this channel, status = active
    $visitors = [];
    $stmt = $db->prepare("SELECT ac.actor_channel_id, ac.actor_id, ac.channel_id, ac.status, ac.channel_color, a.name AS actor_name, a.slug AS actor_slug, a.actor_type FROM {$table_prefix}actor_channels ac LEFT JOIN {$table_prefix}actors a ON a.actor_id = ac.actor_id AND a.is_deleted = 0 WHERE ac.channel_id = :channel_id AND ac.is_deleted = 0 ORDER BY ac.updated_ymdhis DESC");
    $stmt->execute([':channel_id' => $channel_id]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visitors[] = $row;
    }
    try {
        $stmt = $db->prepare("SELECT session_id, last_seen_ymdhis, {$meta_col} FROM {$table_prefix}sessions WHERE actor_id = 0 AND is_deleted = 0 AND {$meta_col} IS NOT NULL AND {$meta_col} != ''");
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
            $visitors[] = [
                'actor_channel_id' => 0,
                'actor_id'         => 0,
                'channel_id'       => $channel_id,
                'status'           => 'A',
                'actor_name'       => 'Visitor ' . substr($row['session_id'], 0, 8),
                'actor_slug'       => 'visitor-' . substr($row['session_id'], 0, 8),
                'actor_type'       => 'anonymous',
                'visitor_session_id' => $row['session_id'],
                'dialog_thread_id'   => $tid,
            ];
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
        return render_main_layout([
            'page_body' => '<p>Channel view not found.</p>',
            'page_title' => $channel['channel_name'],
        ]);
    }

    // Selected thread for composer (tab); legacy channelsplit = channel__userid → we use dialog_thread_id
    $selected_thread_id = isset($_GET['thread']) ? (int) $_GET['thread'] : (!empty($threads) ? (int) $threads[0]['dialog_thread_id'] : 0);

    // Current operator display name for composer typing
    $current_actor_name = 'Operator';
    $stmt = $db->prepare("SELECT name FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':actor_id' => $actor_id]);
    $ar = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ar && !empty($ar['name'])) {
        $current_actor_name = $ar['name'];
    }

    ob_start();
    extract([
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
    ], EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();

    return render_main_layout([
        'page_body'  => $page_body,
        'page_title' => $channel['channel_name'],
    ]);
}
