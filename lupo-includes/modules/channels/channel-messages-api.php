<?php
/**
 * Channel messages poll API — GET. Legacy: xmlhttp.php whattodo=messages.
 * Returns new dialog_messages for channel after after_ymdhis, ORDER BY created_ymdhis ASC.
 * Used for primary polling (XHR every ~2.1s). Schema: lupo_dialog_messages, lupo_dialog_threads, lupo_actors.
 * All paths use LUPOPEDIA_PUBLIC_PATH.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Config not loaded']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if ($method !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$actor_id = null;
$visitor_mode = false;
$dialog_thread_id_visitor = 0;

// Visitor mode: cslhVISITOR present and valid → actor_id = 0, restrict to dialog_thread_id
$visitor_sid = '';
$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
$visitor_helper_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-session-helper.php';
if (is_file($visitor_helper_path)) {
    require_once $visitor_helper_path;
    $visitor_sid = function_exists('crafty_syntax_visitor_session_id') ? crafty_syntax_visitor_session_id() : '';
    if ($visitor_sid !== '' && function_exists('crafty_syntax_validate_visitor_session') && crafty_syntax_validate_visitor_session($visitor_sid)) {
        $actor_id = 0;
        $visitor_mode = true;
        $dialog_thread_id_visitor = isset($_GET['dialog_thread_id']) ? (int) $_GET['dialog_thread_id'] : 0;
    }
}

if (!$visitor_mode) {
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
}
if ($actor_id === null) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    http_response_code(503);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database unavailable']);
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 0;
$after_ymdhis = isset($_GET['after_ymdhis']) ? (string) preg_replace('/\D/', '', $_GET['after_ymdhis']) : '0';
if (strlen($after_ymdhis) !== 14) {
    $after_ymdhis = '0';
}

if ($channel_id < 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'channel_id required', 'messages' => [], 'thread_colors' => [], 'actor_names' => []]);
    exit;
}

if ($visitor_mode) {
    if ($dialog_thread_id_visitor <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'dialog_thread_id required for visitor', 'messages' => [], 'thread_colors' => [], 'actor_names' => []]);
        exit;
    }
    // Pending: channel_id=0, thread has channel_id IS NULL. Accepted: channel_id>0, thread belongs to that channel.
    if ($channel_id === 0) {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id IS NULL AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id_visitor]);
    } else {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id_visitor, ':channel_id' => $channel_id]);
    }
    if ($stmt->fetch() === false) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Access denied', 'messages' => [], 'thread_colors' => [], 'actor_names' => []]);
        exit;
    }
} else {
    // Verify channel access (operator) or global admin (access to any channel)
    $has_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':actor_id' => $actor_id, ':channel_id' => $channel_id]);
    if ($stmt->fetch() !== false) {
        $has_access = true;
    }
    if (!$has_access && isset($GLOBALS['lupo_auth_service'])) {
        $auth = $GLOBALS['lupo_auth_service'];
        if (is_object($auth) && method_exists($auth, 'isAdmin') && $auth->isAdmin($actor_id)) {
            $has_access = true;
        }
    }
    if (!$has_access) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Access denied', 'messages' => [], 'thread_colors' => [], 'actor_names' => []]);
        exit;
    }
}

// Visitor: only this thread's messages. Pending thread: channel_id IS NULL. Order by created_ymdhis ASC (legacy timeof).
if ($visitor_mode) {
    if ($channel_id === 0) {
        $stmt = $db->prepare("SELECT m.dialog_message_id, m.dialog_thread_id, m.channel_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis FROM {$table_prefix}dialog_messages m WHERE m.dialog_thread_id = :dialog_thread_id AND m.channel_id IS NULL AND m.is_deleted = 0 AND m.created_ymdhis > :after ORDER BY m.created_ymdhis ASC LIMIT 200");
        $stmt->execute([':dialog_thread_id' => $dialog_thread_id_visitor, ':after' => $after_ymdhis]);
    } else {
        $stmt = $db->prepare("SELECT m.dialog_message_id, m.dialog_thread_id, m.channel_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis FROM {$table_prefix}dialog_messages m WHERE m.dialog_thread_id = :dialog_thread_id AND m.channel_id = :channel_id AND m.is_deleted = 0 AND m.created_ymdhis > :after ORDER BY m.created_ymdhis ASC LIMIT 200");
        $stmt->execute([':dialog_thread_id' => $dialog_thread_id_visitor, ':channel_id' => $channel_id, ':after' => $after_ymdhis]);
    }
} else {
    $stmt = $db->prepare("SELECT m.dialog_message_id, m.dialog_thread_id, m.channel_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis FROM {$table_prefix}dialog_messages m WHERE m.channel_id = :channel_id AND m.is_deleted = 0 AND m.created_ymdhis > :after ORDER BY m.created_ymdhis ASC LIMIT 200");
    $stmt->execute([':channel_id' => $channel_id, ':after' => $after_ymdhis]);
}
$messages = [];
$thread_ids = [];
$actor_ids = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $messages[] = $row;
    $tid = (int) $row['dialog_thread_id'];
    if ($tid && !in_array($tid, $thread_ids, true)) {
        $thread_ids[] = $tid;
    }
    $aid = (int) $row['from_actor_id'];
    if ($aid && !in_array($aid, $actor_ids, true)) {
        $actor_ids[] = $aid;
    }
}

// Thread colors (dialog_threads.bg_color — legacy channel color from operator channels)
$thread_colors = [];
if (!empty($thread_ids)) {
    $placeholders = implode(',', array_fill(0, count($thread_ids), '?'));
    $stmt = $db->prepare("SELECT dialog_thread_id, bg_color FROM {$table_prefix}dialog_threads WHERE dialog_thread_id IN ($placeholders) AND is_deleted = 0");
    $stmt->execute(array_values($thread_ids));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tid = (int) $row['dialog_thread_id'];
        $bg = isset($row['bg_color']) && preg_match('/^[0-9A-Fa-f]{6}$/', $row['bg_color']) ? $row['bg_color'] : 'FFFACD';
        $thread_colors[$tid] = $bg;
    }
}

// Actor names (lupo_actors.name)
$actor_names = [];
if (!empty($actor_ids)) {
    $placeholders = implode(',', array_fill(0, count($actor_ids), '?'));
    $stmt = $db->prepare("SELECT actor_id, name FROM {$table_prefix}actors WHERE actor_id IN ($placeholders) AND is_deleted = 0");
    $stmt->execute(array_values($actor_ids));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $actor_names[(int) $row['actor_id']] = $row['name'];
    }
}

$last_ymdhis = $after_ymdhis;
foreach ($messages as $m) {
    $t = (string) ($m['created_ymdhis'] ?? '');
    if ($t > $last_ymdhis) {
        $last_ymdhis = $t;
    }
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode([
    'messages'       => $messages,
    'thread_colors'  => $thread_colors,
    'actor_names'    => $actor_names,
    'after_ymdhis'   => $after_ymdhis,
    'last_ymdhis'    => $last_ymdhis,
]);
