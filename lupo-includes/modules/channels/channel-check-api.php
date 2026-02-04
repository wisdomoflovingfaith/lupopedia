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

if ($channel_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['refresh' => false]);
    exit;
}

$stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actor_channels WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
$stmt->execute([':actor_id' => $actor_id, ':channel_id' => $channel_id]);
if ($stmt->fetch() === false) {
    header('Content-Type: application/json');
    echo json_encode(['refresh' => false]);
    exit;
}

// Legacy: SELECT timeof FROM livehelp_messages WHERE typeof != 'writediv' AND timeof > $message_test
$stmt = $db->prepare("SELECT 1 FROM {$table_prefix}dialog_messages WHERE channel_id = :channel_id AND is_deleted = 0 AND created_ymdhis > :after LIMIT 1");
$stmt->execute([':channel_id' => $channel_id, ':after' => $after_ymdhis]);
$refresh = $stmt->fetch() !== false;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode(['refresh' => $refresh]);
