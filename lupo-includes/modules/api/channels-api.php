<?php
/**
 * Channels REST API — RESTful message send/receive for VSX extension.
 *
 * Routes handled (set via $channels_api_channel_id and $channels_api_action):
 *   GET  api/channels/{id}/messages?since=
 *   POST api/channels/{id}/messages
 *
 * @package Lupopedia
 * @since   4.0.27
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die(json_encode(['success' => false, 'error' => ['code' => 'CONFIG_NOT_LOADED', 'message' => 'Config not loaded.']]));
}

header('Content-Type: application/json; charset=utf-8');

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(['success' => false, 'error' => ['code' => 'DB_UNAVAILABLE', 'message' => 'Database not available.']]);
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

// Set by the router before requiring this file.
$channel_id = isset($channels_api_channel_id) ? (int) $channels_api_channel_id : 0;

if ($channel_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_CHANNEL', 'message' => 'channel_id must be a positive integer.']]);
    exit;
}

// ── GET: Retrieve messages ─────────────────────────────────────────────────────
if ($method === 'GET') {
    $since = isset($_GET['since']) ? trim($_GET['since']) : '';
    $limit = isset($_GET['limit']) ? min(200, max(1, (int) $_GET['limit'])) : 50;
    $offset = isset($_GET['offset']) ? max(0, (int) $_GET['offset']) : 0;

    $t_msg = $table_prefix . 'dialog_messages';
    $t_act = $table_prefix . 'actors';

    $params = ['channel_id' => $channel_id];
    $sinceClause = '';
    if ($since !== '' && preg_match('/^\d{14}$/', $since)) {
        $sinceClause = ' AND m.created_ymdhis > :since';
        $params['since'] = (int) $since;
    }

    $sql = "SELECT m.dialog_message_id AS message_id, m.from_actor_id AS actor_id, "
         . "a.name AS actor_name, a.actor_type, m.channel_id, "
         . "m.message_text AS body, m.created_ymdhis AS created_at "
         . "FROM {$t_msg} m "
         . "LEFT JOIN {$t_act} a ON a.actor_id = m.from_actor_id "
         . "WHERE m.channel_id = :channel_id{$sinceClause} "
         . "ORDER BY m.created_ymdhis ASC "
         . "LIMIT {$limit} OFFSET {$offset}";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $messages = array_map(function ($r) {
            return [
                'message_id'  => isset($r['message_id']) ? (int) $r['message_id'] : null,
                'actor_id'    => (int) $r['actor_id'],
                'actor_name'  => $r['actor_name'],
                'actor_type'  => $r['actor_type'],
                'channel_id'  => (int) $r['channel_id'],
                'body'        => $r['body'],
                'created_at'  => $r['created_at'],
            ];
        }, $rows);

        echo json_encode([
            'success'    => true,
            'channel_id' => $channel_id,
            'messages'   => $messages,
            'total'      => count($messages),
            'limit'      => $limit,
            'offset'     => $offset,
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => ['code' => 'QUERY_ERROR', 'message' => $e->getMessage()]]);
    }
    exit;
}

// ── POST: Send message ─────────────────────────────────────────────────────────
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['actor_id']) || !isset($input['body'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include actor_id and body.']]);
        exit;
    }

    $actor_id = (int) $input['actor_id'];
    $body = trim($input['body']);
    $message_type = isset($input['message_type']) ? trim($input['message_type']) : 'text';
    $meta = isset($input['meta']) ? json_encode($input['meta']) : null;

    if ($actor_id <= 0 || $body === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'actor_id must be positive and body must not be empty.']]);
        exit;
    }

    $t_msg = $table_prefix . 'dialog_messages';
    $now = (int) gmdate('YmdHis');

    try {
        // Allocate message_id
        $message_id = null;
        if (function_exists('lupo_findpuka')) {
            $message_id = lupo_findpuka($db, $t_msg, 'dialog_message_id', 1, null);
        }
        if ($message_id === null) {
            $stmt = $db->prepare("SELECT MAX(dialog_message_id) AS max_id FROM {$t_msg}");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $message_id = ($row && $row['max_id'] !== null) ? ((int) $row['max_id'] + 1) : 1;
        }

        $stmt = $db->prepare(
            "INSERT INTO {$t_msg} (dialog_message_id, channel_id, from_actor_id, message_text, message_type, metadata_json, created_ymdhis) "
            . "VALUES (:msg_id, :channel_id, :actor_id, :body, :msg_type, :meta, :created)"
        );
        $stmt->execute([
            'msg_id'     => $message_id,
            'channel_id' => $channel_id,
            'actor_id'   => $actor_id,
            'body'       => $body,
            'msg_type'   => $message_type,
            'meta'       => $meta,
            'created'    => $now,
        ]);

        http_response_code(201);
        echo json_encode([
            'success'    => true,
            'accepted'   => true,
            'message_id' => $message_id,
            'channel_id' => $channel_id,
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => ['code' => 'INSERT_ERROR', 'message' => $e->getMessage()]]);
    }
    exit;
}

// ── Fallback ───────────────────────────────────────────────────────────────────
http_response_code(405);
echo json_encode(['success' => false, 'error' => ['code' => 'METHOD_NOT_ALLOWED', 'message' => "Unsupported method: {$method}"]]);
exit;
