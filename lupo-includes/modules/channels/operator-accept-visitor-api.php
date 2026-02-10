<?php
/**
 * Accept visitor API — POST.
 * Moves visitor onto channel: UPDATE dialog_threads/dialog_messages, session metadata status=active.
 * Actor must have a role in the channel (lupo_channel_roles). All paths use LUPOPEDIA_PUBLIC_PATH.
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
if (function_exists('current_user')) {
    $user = current_user();
    if ($user && !empty($user['actor_id'])) {
        $actor_id = (int) $user['actor_id'];
    }
}
if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
    $actor_id = $s->validateSession();
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
$operator_channel_id = isset($_POST['operator_channel_id']) ? (int) $_POST['operator_channel_id'] : 0;
$dialog_thread_id = isset($_POST['dialog_thread_id']) ? (int) $_POST['dialog_thread_id'] : 0;
$visitor_session_id = isset($_POST['visitor_session_id']) ? (string) $_POST['visitor_session_id'] : '';
$department_id = isset($_POST['department_id']) ? (int) $_POST['department_id'] : 0;

if ($operator_channel_id <= 0 || $dialog_thread_id <= 0 || $visitor_session_id === '') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'operator_channel_id, dialog_thread_id, and visitor_session_id required']);
    exit;
}

// Verify actor has a role in this channel (lupo_channel_roles)
$stmt = $db->prepare("SELECT 1 FROM {$table_prefix}channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
$stmt->execute([':channel_id' => $operator_channel_id, ':actor_id' => $actor_id]);
if ($stmt->fetch() === false) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Access denied to channel']);
    exit;
}

// Verify thread exists and is pending (channel_id IS NULL)
$stmt = $db->prepare("SELECT dialog_thread_id, channel_id FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND is_deleted = 0 LIMIT 1");
$stmt->execute([':tid' => $dialog_thread_id]);
$thread = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$thread) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Thread not found']);
    exit;
}
if (isset($thread['channel_id']) && $thread['channel_id'] !== null && (int) $thread['channel_id'] > 0) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Visitor already accepted']);
    exit;
}

// Verify visitor session has this thread as pending
$meta_col = 'metadata_json';
$stmt = $db->prepare("SELECT {$meta_col} FROM {$table_prefix}sessions WHERE session_id = :sid AND actor_id = 0 AND is_deleted = 0 LIMIT 1");
$stmt->execute([':sid' => $visitor_session_id]);
$srow = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$srow || empty($srow[$meta_col])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid visitor session']);
    exit;
}
$meta = json_decode($srow[$meta_col], true);
if (!is_array($meta) || empty($meta['crafty_syntax']) || (int)($meta['crafty_syntax']['dialog_thread_id'] ?? 0) !== $dialog_thread_id || (isset($meta['crafty_syntax']['status']) ? $meta['crafty_syntax']['status'] : '') !== 'pending') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Visitor session does not match pending thread']);
    exit;
}

$now = date('YmdHis');

try {
    $db->beginTransaction();

    // 1) Move thread onto operator's channel
    $stmt = $db->prepare("UPDATE {$table_prefix}dialog_threads SET channel_id = :cid, updated_ymdhis = :now WHERE dialog_thread_id = :tid");
    $stmt->execute([':cid' => $operator_channel_id, ':now' => $now, ':tid' => $dialog_thread_id]);

    // 2) Move all messages in this thread onto operator's channel
    $stmt = $db->prepare("UPDATE {$table_prefix}dialog_messages SET channel_id = :cid, updated_ymdhis = :now WHERE dialog_thread_id = :tid AND is_deleted = 0");
    $stmt->execute([':cid' => $operator_channel_id, ':now' => $now, ':tid' => $dialog_thread_id]);

    // 3) Update visitor session metadata: status = active, channel_id = operator_channel_id
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $helper_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-session-helper.php';
    if (is_file($helper_path)) {
        require_once $helper_path;
        if (function_exists('crafty_syntax_visitor_set_accepted')) {
            crafty_syntax_visitor_set_accepted($visitor_session_id, $operator_channel_id);
        } else {
            $meta['crafty_syntax']['channel_id'] = $operator_channel_id;
            $meta['crafty_syntax']['status'] = 'active';
            $json = json_encode($meta);
            $stmt = $db->prepare("UPDATE {$table_prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
            $stmt->execute([':meta' => $json, ':now' => $now, ':sid' => $visitor_session_id]);
        }
    } else {
        $meta['crafty_syntax']['channel_id'] = $operator_channel_id;
        $meta['crafty_syntax']['status'] = 'active';
        $json = json_encode($meta);
        $stmt = $db->prepare("UPDATE {$table_prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
        $stmt->execute([':meta' => $json, ':now' => $now, ':sid' => $visitor_session_id]);
    }

    $db->commit();
} catch (Throwable $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Accept failed']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'ok'                => true,
    'dialog_thread_id'  => $dialog_thread_id,
    'operator_channel_id' => $operator_channel_id,
]);
