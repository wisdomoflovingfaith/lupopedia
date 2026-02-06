<?php
/**
 * Pending visitors API — GET.
 * Returns visitors whose session metadata.crafty_syntax.status = "pending" for the given department.
 * Used by channel interface; department resolved from channel_id or actor_departments.
 * All paths use LUPOPEDIA_PUBLIC_PATH.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Config not loaded', 'pending_visitors' => []]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? '';
if ($method !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed', 'pending_visitors' => []]);
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
    echo json_encode(['error' => 'Not authenticated', 'pending_visitors' => []]);
    exit;
}

$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    header('Content-Type: application/json');
    echo json_encode(['pending_visitors' => []]);
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$department_id = isset($_GET['department_id']) ? (int) $_GET['department_id'] : 0;

// If no department_id, try to resolve from channel or actor_departments
if ($department_id <= 0 && isset($_GET['channel_id'])) {
    $ch = (int) $_GET['channel_id'];
    if ($ch > 0) {
        $stmt = $db->prepare("SELECT department_id FROM {$table_prefix}channels WHERE channel_id = :cid AND is_deleted = 0 LIMIT 1");
        $stmt->execute([':cid' => $ch]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['department_id'])) {
            $department_id = (int) $row['department_id'];
        }
    }
}
if ($department_id <= 0) {
    $stmt = $db->prepare("SELECT department_id FROM {$table_prefix}actor_departments WHERE actor_id = :aid AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':aid' => $actor_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['department_id'])) {
        $department_id = (int) $row['department_id'];
    }
}

$pending = [];
if ($department_id > 0) {
    $meta_col = 'metadata_json';
    try {
        $stmt = $db->prepare(
            "SELECT s.session_id, s.last_seen_ymdhis, s.created_ymdhis, s.{$meta_col} FROM {$table_prefix}sessions s " .
            "WHERE s.actor_id = 0 AND s.is_deleted = 0 AND s.{$meta_col} IS NOT NULL AND s.{$meta_col} != '' LIMIT 100"
        );
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
            $dept = isset($cs['department_id']) ? (int) $cs['department_id'] : 0;
            if ($dept !== $department_id) {
                continue;
            }
            $tid = isset($cs['dialog_thread_id']) ? (int) $cs['dialog_thread_id'] : 0;
            if ($tid <= 0) {
                continue;
            }
            $pending[] = [
                'visitor_session_id' => $row['session_id'],
                'dialog_thread_id'   => $tid,
                'department_id'      => $department_id,
                'created_ymdhis'     => $row['created_ymdhis'] ?? $row['last_seen_ymdhis'] ?? '',
                'last_seen_ymdhis'   => $row['last_seen_ymdhis'] ?? '',
            ];
        }
    } catch (Throwable $e) {
        // return empty
    }
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode(['pending_visitors' => $pending]);
