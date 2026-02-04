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

if ($channel_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'channel_id required', 'messages' => [], 'thread_colors' => [], 'actor_names' => []]);
    exit;
}

// Verify channel access
$stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
$stmt->execute([':actor_id' => $actor_id, ':channel_id' => $channel_id]);
if ($stmt->fetch() === false) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Access denied', 'messages' => [], 'thread_colors' => [], 'actor_names' => []]);
    exit;
}

// New messages: created_ymdhis > after_ymdhis, ORDER BY created_ymdhis ASC (legacy ORDER BY timeof)
$stmt = $db->prepare("SELECT m.dialog_message_id, m.dialog_thread_id, m.channel_id, m.from_actor_id, m.to_actor_id, m.message_text, m.message_type, m.created_ymdhis FROM {$table_prefix}dialog_messages m WHERE m.channel_id = :channel_id AND m.is_deleted = 0 AND m.created_ymdhis > :after ORDER BY m.created_ymdhis ASC LIMIT 200");
$stmt->execute([':channel_id' => $channel_id, ':after' => $after_ymdhis]);
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

// Thread colors (dialog_threads.bg_color — legacy channelcolor from livehelp_operator_channels)
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
