<?php
/**
 * Accept visitor API — POST.
 * Moves visitor onto channel: UPDATE dialog_threads/dialog_messages, session metadata status=active.
 * Actor must have a role in the channel (lupo_actor_channel_roles). All paths use LUPOPEDIA_PUBLIC_PATH.
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
if (!$actor_id) {
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
$operator_channel_id = isset($_POST['operator_channel_id']) ? (int) $_POST['operator_channel_id'] : 0;
$dialog_thread_id = isset($_POST['dialog_thread_id']) ? (int) $_POST['dialog_thread_id'] : 0;
$visitor_session_id = isset($_POST['visitor_session_id']) ? (string) $_POST['visitor_session_id'] : '';
$department_id = isset($_POST['department_id']) ? (int) $_POST['department_id'] : 0;

if ($operator_channel_id <= 0 || $dialog_thread_id <= 0 || $visitor_session_id === '') {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'operator_channel_id, dialog_thread_id, and visitor_session_id required'));
    exit;
}

// Verify actor has a role in this channel (lupo_actor_channel_roles)
$stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channel_roles WHERE channel_id = :channel_id AND actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
$stmt->execute(array(':channel_id' => $operator_channel_id, ':actor_id' => $actor_id));
if ($stmt->fetch() === false) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Access denied to channel'));
    exit;
}

// Verify thread exists and is pending (channel_id IS NULL)
$stmt = $db->prepare("SELECT dialog_thread_id, channel_id FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND is_deleted = 0 LIMIT 1");
$stmt->execute(array(':tid' => $dialog_thread_id));
$thread = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$thread) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Thread not found'));
    exit;
}
if (isset($thread['channel_id']) && $thread['channel_id'] !== null && (int) $thread['channel_id'] > 0) {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Visitor already accepted'));
    exit;
}

// Verify visitor session has this thread as pending
$meta_col = 'metadata_json';
$stmt = $db->prepare("SELECT {$meta_col} FROM {$table_prefix}sessions WHERE session_id = :sid AND actor_id = 0 AND is_deleted = 0 LIMIT 1");
$stmt->execute(array(':sid' => $visitor_session_id));
$srow = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$srow || empty($srow[$meta_col])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Invalid visitor session'));
    exit;
}
$meta = json_decode($srow[$meta_col], true);
$meta_thread = isset($meta['crafty_syntax']['dialog_thread_id']) ? (int) $meta['crafty_syntax']['dialog_thread_id'] : 0;
if (!is_array($meta) || empty($meta['crafty_syntax']) || $meta_thread !== $dialog_thread_id || (isset($meta['crafty_syntax']['status']) ? $meta['crafty_syntax']['status'] : '') !== 'pending') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Visitor session does not match pending thread'));
    exit;
}

$now = gmdate('YmdHis');

// SYSTEM_LIMITS enforcement:
// - Channel thread limit (>= 999): block accepting the pending visitor thread into this channel.
// - Channel near-limit (>= 950): set channel status_flag to "retiring" (numeric doctrine mapping).
$thread_count_sql = "SELECT COUNT(DISTINCT dialog_thread_id) AS thread_count
                     FROM {$table_prefix}dialog_threads
                     WHERE channel_id = :channel_id AND is_deleted = 0";
$stmtCount = $db->prepare($thread_count_sql);
$stmtCount->execute(array(':channel_id' => $operator_channel_id));
$countRow = $stmtCount->fetch(PDO::FETCH_ASSOC);
$thread_count = $countRow ? (int) $countRow['thread_count'] : 0;

// Doctrine mapping: status_flag tinyint where 1=active, 2=retiring.
$retiring_status_flag = 2;
$warning_msg = null;
$near_limit = ($thread_count >= 950);
$hard_limit = ($thread_count >= 999);
$set_retiring_inside_tx = false;

if ($near_limit) {
    if ($hard_limit) {
        // Best-effort retiring marking before hard block response.
        try {
            $stmt = $db->prepare(
                "UPDATE {$table_prefix}channels
                 SET status_flag = :status_flag, updated_ymdhis = :now
                 WHERE channel_id = :channel_id AND is_deleted = 0"
            );
            $stmt->execute(array(
                ':status_flag' => $retiring_status_flag,
                ':now'          => $now,
                ':channel_id'  => $operator_channel_id
            ));
        } catch (Exception $e) {
            // If status update fails, still enforce the hard limit below.
        }
    } else {
        $warning_msg = 'Channel ' . $operator_channel_id . ' is nearing max threads (' . $thread_count . '/999). Channel marked as retiring.';
        $set_retiring_inside_tx = true;
    }
}

if ($hard_limit) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array(
        'error' => 'CHANNEL_THREAD_LIMIT_REACHED',
        'message' => 'Channel ' . $operator_channel_id . ' has reached max threads (999). Channel must be retired.',
        'channel_id' => $operator_channel_id,
        'thread_count' => $thread_count,
        'limit' => 999
    ));
    exit;
}

try {
    $db->beginTransaction();

    // Near-limit state marking (>=950) is part of the same transaction as accepting the thread.
    if ($set_retiring_inside_tx) {
        try {
            $stmt = $db->prepare(
                "UPDATE {$table_prefix}channels
                 SET status_flag = :status_flag, updated_ymdhis = :now
                 WHERE channel_id = :channel_id AND is_deleted = 0"
            );
            $stmt->execute(array(
                ':status_flag' => $retiring_status_flag,
                ':now'          => $now,
                ':channel_id'  => $operator_channel_id
            ));
        } catch (Exception $e) {
            // Best-effort: accept the thread even if status update fails.
        }
    }

    // 1) Move thread onto operator's channel
    $stmt = $db->prepare("UPDATE {$table_prefix}dialog_threads SET channel_id = :cid, updated_ymdhis = :now WHERE dialog_thread_id = :tid");
    $stmt->execute(array(':cid' => $operator_channel_id, ':now' => $now, ':tid' => $dialog_thread_id));

    // 2) Move all messages in this thread onto operator's channel
    $stmt = $db->prepare("UPDATE {$table_prefix}dialog_messages SET channel_id = :cid, updated_ymdhis = :now WHERE dialog_thread_id = :tid AND is_deleted = 0");
    $stmt->execute(array(':cid' => $operator_channel_id, ':now' => $now, ':tid' => $dialog_thread_id));

    // 3) Update visitor session metadata: status = active, channel_id = operator_channel_id
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $helper_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-session-helper.php';
    if (is_file($helper_path)) {
        require_once $helper_path;
        if (function_exists('crafty_syntax_visitor_set_accepted')) {
            crafty_syntax_visitor_set_accepted($visitor_session_id, $operator_channel_id);
        } else {
            $meta['crafty_syntax']['channel_id'] = $operator_channel_id;
            $meta['crafty_syntax']['status'] = 'active';
            $json = json_encode($meta);
            $stmt = $db->prepare("UPDATE {$table_prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
            $stmt->execute(array(':meta' => $json, ':now' => $now, ':sid' => $visitor_session_id]);
        }
    } else {
        $meta['crafty_syntax']['channel_id'] = $operator_channel_id;
        $meta['crafty_syntax']['status'] = 'active';
        $json = json_encode($meta);
        $stmt = $db->prepare("UPDATE {$table_prefix}sessions SET {$meta_col} = :meta, last_seen_ymdhis = :now, updated_ymdhis = :now WHERE session_id = :sid");
        $stmt->execute(array(':meta' => $json, ':now' => $now, ':sid' => $visitor_session_id]);
    }

    $db->commit();
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Accept failed'));
    exit;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(array(
    'ok'                => true,
    'dialog_thread_id'  => $dialog_thread_id,
    'operator_channel_id' => $operator_channel_id,
    'warning'          => $warning_msg,
));
