<?php
/**
 * Channel send message API — POST only.
 * Legacy: admin_chat_bot.php whattodo=send. Insert dialog_message, clear all typing (writediv), timestamp uniqueness.
 * Schema: lupo_dialog_messages; clears lupo_channel_typing_previews on send. All paths use LUPOPEDIA_PUBLIC_PATH.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Config not loaded'));
    exit;
}

$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
if ($method !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Method not allowed'));
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
if (!$actor_id && ($s = (isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null))) {
    $actor_id = $s->validateSession();
}
}
if ($actor_id === null) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Not authenticated'));
    exit;
}

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Database unavailable'));
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$channel_id = isset($_POST['channel_id']) ? (int) $_POST['channel_id'] : 0;
$dialog_thread_id = isset($_POST['dialog_thread_id']) ? (int) $_POST['dialog_thread_id'] : 0;
$message_text = isset($_POST['message_text']) ? trim((string) $_POST['message_text']) : '';
$to_actor_id = isset($_POST['to_actor_id']) ? (int) $_POST['to_actor_id'] : null;

if ($dialog_thread_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'dialog_thread_id required'));
    exit;
}
if ($channel_id < 0) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'channel_id required'));
    exit;
}
if ($message_text === '') {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'message_text required'));
    exit;
}

if ($visitor_mode) {
    if ($channel_id === 0) {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id IS NULL AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':tid' => $dialog_thread_id));
    } else {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':tid' => $dialog_thread_id, ':channel_id' => $channel_id));
    }
    if ($stmt->fetch() === false) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Access denied to channel'));
        exit;
    }
} else {
    $has_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
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
        echo json_encode(array('error' => 'Access denied to channel'));
        exit;
    }
}

// Pre-action authorization: TraitEnforcer (4.0.69). Columns from install/TOON.
if (!class_exists('TraitEnforcer')) {
    $trait_enforcer_path = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    $trait_enforcer_path = rtrim($trait_enforcer_path, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TraitEnforcer.php';
    if (is_file($trait_enforcer_path)) {
        require_once $trait_enforcer_path;
    }
}
if (class_exists('TraitEnforcer')) {
    $enforcer = new TraitEnforcer($db);
    if (!$enforcer->isActionAuthorized($actor_id, 'dialog.send_message', $channel_id > 0 ? $channel_id : null)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Not authorized to send messages'));
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

// Handle forwarding attribution from POST data or FLIP headers
$forwarded_by_actor_id = null;
$original_sender_actor_id = null;

// Check POST data first (for manual forwarding)
if (isset($_POST['forwarded_by_actor_id']) && $_POST['forwarded_by_actor_id'] !== '') {
    $forwarded_by_actor_id = (int) $_POST['forwarded_by_actor_id'];
}
if (isset($_POST['original_sender_actor_id']) && $_POST['original_sender_actor_id'] !== '') {
    $original_sender_actor_id = (int) $_POST['original_sender_actor_id'];
}

// Check FLIP headers for forwarding attribution
if (!$forwarded_by_actor_id && isset($_SERVER['HTTP_X_FLIP_FORWARDED_BY_ACTOR_ID'])) {
    $forwarded_by_actor_id = (int) $_SERVER['HTTP_X_FLIP_FORWARDED_BY_ACTOR_ID'];
}
if (!$original_sender_actor_id && isset($_SERVER['HTTP_X_FLIP_ORIGINAL_SENDER_ACTOR_ID'])) {
    $original_sender_actor_id = (int) $_SERVER['HTTP_X_FLIP_ORIGINAL_SENDER_ACTOR_ID'];
}

// Validate forwarding actor IDs if provided
if ($forwarded_by_actor_id && $forwarded_by_actor_id > 0) {
    $stmt_check_actor = $db->prepare("SELECT 1 FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt_check_actor->execute(array(':actor_id' => $forwarded_by_actor_id));
    if ($stmt_check_actor->fetch() === false) {
        $forwarded_by_actor_id = null; // Invalid actor ID, ignore
    }
}

if ($original_sender_actor_id && $original_sender_actor_id > 0) {
    $stmt_check_actor = $db->prepare("SELECT 1 FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt_check_actor->execute(array(':actor_id' => $original_sender_actor_id));
    if ($stmt_check_actor->fetch() === false) {
        $original_sender_actor_id = null; // Invalid actor ID, ignore
    }
}

// Faucet traceability: source_faucet_slug, source_faucet_instance_id (install/TOON: lupo_dialog_messages)
$source_faucet_slug = defined('LUPO_FAUCET_SLUG') ? LUPO_FAUCET_SLUG : null;
$source_faucet_instance_id = defined('LUPO_FAUCET_INSTANCE_ID') ? LUPO_FAUCET_INSTANCE_ID : null;

$stmt_ins = $db->prepare("INSERT INTO {$table_prefix}dialog_messages (dialog_thread_id, channel_id, from_actor_id, source_faucet_slug, source_faucet_instance_id, to_actor_id, message_text, message_type, created_ymdhis, updated_ymdhis, is_deleted, forwarded_by_actor_id, original_sender_actor_id) VALUES (:dialog_thread_id, :channel_id, :from_actor_id, :source_faucet_slug, :source_faucet_instance_id, :to_actor_id, :message_text, 'text', :created_ymdhis, :updated_ymdhis, 0, :forwarded_by_actor_id, :original_sender_actor_id)");
$stmt_ins->execute(array(
    ':dialog_thread_id' => $dialog_thread_id,
    ':channel_id'      => $ins_channel_id,
    ':from_actor_id'   => $actor_id,
    ':source_faucet_slug' => $source_faucet_slug,
    ':source_faucet_instance_id' => $source_faucet_instance_id,
    ':to_actor_id'     => $to_actor_id ? $to_actor_id : null,
    ':message_text'    => $message_text,
    ':created_ymdhis'  => $now,
    ':updated_ymdhis'  => $now,
    ':forwarded_by_actor_id' => $forwarded_by_actor_id,
    ':original_sender_actor_id' => $original_sender_actor_id,
));

// On send, clear typing previews for the channel (DB; skip when pending/channel_id=0)
if ($channel_id > 0) {
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    if (!class_exists('timestamp_ymdhis', false) && $app_root !== '') {
        require_once rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TimestampYmdhis.php';
    }
    $nowClr = class_exists('timestamp_ymdhis', false) ? (string) timestamp_ymdhis::now() : gmdate('YmdHis');
    $db->update(
        $table_prefix . 'channel_typing_previews',
        array(
            'preview_text'   => '',
            'updated_ymdhis' => $nowClr,
            'is_deleted'     => 1,
            'deleted_ymdhis' => $nowClr,
        ),
        'channel_id = :cid AND is_deleted = 0',
        array('cid' => $channel_id)
    );
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(array('ok' => true, 'created_ymdhis' => $now));
