<?php
/**
 * Channel send message API — POST only.
 * Legacy: admin_chat_bot.php whattodo=send. Insert dialog_message, clear all typing (writediv), timestamp uniqueness.
 * Schema: lupo_dialog_messages, file-based typing cache. All paths use LUPOPEDIA_PUBLIC_PATH.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Config not loaded']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if ($method !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$actor_id = null;
$visitor_mode = false;

$visitor_sid = isset($_POST['cslhVISITOR']) ? (string) $_POST['cslhVISITOR'] : '';
if ($visitor_sid !== '') {
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $helper_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-session-helper.php';
    if (is_file($helper_path)) {
        require_once $helper_path;
        if (crafty_syntax_validate_visitor_session($visitor_sid)) {
            $actor_id = 0;
            $visitor_mode = true;
        }
    }
}

if (!$visitor_mode) {
    if (function_exists('current_user')) {
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
$channel_id = isset($_POST['channel_id']) ? (int) $_POST['channel_id'] : 0;
$dialog_thread_id = isset($_POST['dialog_thread_id']) ? (int) $_POST['dialog_thread_id'] : 0;
$message_text = isset($_POST['message_text']) ? trim((string) $_POST['message_text']) : '';
$to_actor_id = isset($_POST['to_actor_id']) ? (int) $_POST['to_actor_id'] : null;

if ($dialog_thread_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'dialog_thread_id required']);
    exit;
}
if ($channel_id < 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'channel_id required']);
    exit;
}
if ($message_text === '') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'message_text required']);
    exit;
}

if ($visitor_mode) {
    if ($channel_id === 0) {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id IS NULL AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id]);
    } else {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id, ':channel_id' => $channel_id]);
    }
    if ($stmt->fetch() === false) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Access denied to channel']);
        exit;
    }
} else {
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':actor_id' => $actor_id, ':channel_id' => $channel_id]);
    if ($stmt->fetch() === false) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Access denied to channel']);
        exit;
    }
}

// Legacy: ensure message_text length (livehelp stored in message; we use message_text varchar(1000))
$message_text = substr($message_text, 0, 1000);

// Legacy: timestamp uniqueness — loop until created_ymdhis is unique (admin_chat_bot.php)
$now = date('YmdHis');
$stmt_check = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_messages WHERE created_ymdhis = :t LIMIT 1");
$stmt_check->bindParam(':t', $now, PDO::PARAM_STR);
while (true) {
    $stmt_check->execute();
    if ($stmt_check->fetch() === false) {
        break;
    }
    if (function_exists('sleep')) {
        sleep(1);
    }
    $now = date('YmdHis');
}

// Insert message. Pending visitor: channel_id NULL. Accepted: channel_id set.
$ins_channel_id = $channel_id > 0 ? $channel_id : null;
$stmt_ins = $db->prepare("INSERT INTO {$table_prefix}dialog_messages (dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:dialog_thread_id, :channel_id, :from_actor_id, :to_actor_id, :message_text, 'text', :created_ymdhis, :updated_ymdhis, 0)");
$stmt_ins->execute([
    ':dialog_thread_id' => $dialog_thread_id,
    ':channel_id'      => $ins_channel_id,
    ':from_actor_id'   => $actor_id,
    ':to_actor_id'     => $to_actor_id ?: null,
    ':message_text'    => $message_text,
    ':created_ymdhis'  => $now,
    ':updated_ymdhis'  => $now,
]);

// Legacy: on send, clear typing for the channel (skip when pending/channel_id=0)
if ($channel_id > 0) {
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    $cache_dir = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . 'cache';
    $typing_file = $cache_dir . DIRECTORY_SEPARATOR . 'typing_' . $channel_id . '.json';
    if (is_file($typing_file)) {
        @file_put_contents($typing_file, '{}', LOCK_EX);
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok' => true, 'created_ymdhis' => $now]);
