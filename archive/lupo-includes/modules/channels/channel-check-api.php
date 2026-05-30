<?php
/**
 * Channel check API — GET. Legacy: admin_image.php what=messagecheck (image-size polling).
 * Returns JSON { refresh: true } if new messages exist after after_ymdhis, else { refresh: false }.
 * Used as secondary/fallback poll; client triggers full page reload when refresh is true.
 * All paths use LUPOPEDIA_PUBLIC_PATH.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['refresh' => false]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if ($method !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['refresh' => false]);
    exit;
}

$actor_id = null;
$visitor_mode = false;
$dialog_thread_id_visitor = 0;

$visitor_sid = '';
$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
$helper_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'crafty_syntax' . DIRECTORY_SEPARATOR . 'visitor-session-helper.php';
if (is_file($helper_path)) {
    require_once $helper_path;
    $visitor_sid = function_exists('crafty_syntax_visitor_session_id') ? crafty_syntax_visitor_session_id() : '';
}
if ($visitor_sid !== '' && function_exists('crafty_syntax_validate_visitor_session') && crafty_syntax_validate_visitor_session($visitor_sid)) {
    $actor_id = 0;
    $visitor_mode = true;
    $dialog_thread_id_visitor = isset($_GET['dialog_thread_id']) ? (int) $_GET['dialog_thread_id'] : 0;
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
    header('Content-Type: application/json');
    echo json_encode(['refresh' => false]);
    exit;
}

$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    header('Content-Type: application/json');
    echo json_encode(['refresh' => false]);
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
    echo json_encode(['refresh' => false]);
    exit;
}

if ($visitor_mode) {
    if ($dialog_thread_id_visitor <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['refresh' => false]);
        exit;
    }
    if ($channel_id === 0) {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id IS NULL AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id_visitor]);
    } else {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_threads WHERE dialog_thread_id = :tid AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id_visitor, ':channel_id' => $channel_id]);
    }
    if ($stmt->fetch() === false) {
        header('Content-Type: application/json');
        echo json_encode(['refresh' => false]);
        exit;
    }
    if ($channel_id === 0) {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_messages WHERE dialog_thread_id = :tid AND channel_id IS NULL AND is_deleted = 0 AND created_ymdhis > :after LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id_visitor, ':after' => $after_ymdhis]);
    } else {
        $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_messages WHERE dialog_thread_id = :tid AND channel_id = :channel_id AND is_deleted = 0 AND created_ymdhis > :after LIMIT 1");
        $stmt->execute([':tid' => $dialog_thread_id_visitor, ':channel_id' => $channel_id, ':after' => $after_ymdhis]);
    }
} else {
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
        header('Content-Type: application/json');
        echo json_encode(['refresh' => false]);
        exit;
    }
    $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 AND created_ymdhis > :after LIMIT 1");
    $stmt->execute([':channel_id' => $channel_id, ':after' => $after_ymdhis]);
}
$refresh = $stmt->fetch() !== false;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode(['refresh' => $refresh]);
