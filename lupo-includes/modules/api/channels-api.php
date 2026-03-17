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

/**
 * Lightweight security event logger for channel API.
 *
 * @param string $event_type  Event type: unauthorized, forbidden
 * @param int    $channel_id  Channel ID
 * @param int|null $actor_id  Actor ID if known, null otherwise
 */
function lupo_channels_api_log_security_event($event_type, $channel_id, $actor_id)
{
    $log = array(
        'event' => 'channel_api_security',
        'event_type' => $event_type,
        'channel_id' => (int) $channel_id,
        'actor_id' => $actor_id !== null ? (int) $actor_id : null,
        'timestamp_ymdhis' => (int) gmdate('YmdHis'),
    );

    // Prefer project-level security logger if one is introduced later.
    if (function_exists('lupo_security_log')) {
        lupo_security_log($log);
    } else {
        error_log('[lupopedia_security] ' . json_encode($log));
    }
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
    // Require authenticated actor and membership (or admin) for message retrieval.
    $actor_id = null;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null)) {
        if (is_object($s) && method_exists($s, 'validateSession')) {
            $actor_id = $s->validateSession();
            if ($actor_id !== null && $actor_id !== false) {
                $actor_id = (int) $actor_id;
            } else {
                $actor_id = null;
            }
        }
    }

    if (!$actor_id || $actor_id <= 0) {
        lupo_channels_api_log_security_event('unauthorized', $channel_id, null);
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => ['code' => 'UNAUTHORIZED', 'message' => 'Authenticated actor required to read messages.']]);
        exit;
    }

    // Membership enforcement for GET: same rule as POST.
    $t_actor_channels = $table_prefix . 'actor_channels';
    $has_channel_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$t_actor_channels} WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_channel_access = true;
    }
    if (!$has_channel_access && $authService && is_object($authService) && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_channel_access = true;
        }
    }
    if (!$has_channel_access) {
        lupo_channels_api_log_security_event('forbidden', $channel_id, $actor_id);
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => ['code' => 'FORBIDDEN', 'message' => 'Actor not a member of this channel.']]);
        exit;
    }

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
    if (!$input || !isset($input['body'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include body.']]);
        exit;
    }

    $body = trim($input['body']);
    $message_type = isset($input['message_type']) ? trim($input['message_type']) : 'text';
    $meta = isset($input['meta']) ? json_encode($input['meta']) : null;

    if ($body === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'body must not be empty.']]);
        exit;
    }

    // Actor identity MUST come from authenticated session / server-side context only.
    // Client-supplied actor_id is never trusted for authorization or insertion.
    $actor_id = null;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null)) {
        if (is_object($s) && method_exists($s, 'validateSession')) {
            $actor_id = $s->validateSession();
            if ($actor_id !== null && $actor_id !== false) {
                $actor_id = (int) $actor_id;
            } else {
                $actor_id = null;
            }
        }
    }

    if (!$actor_id || $actor_id <= 0) {
        lupo_channels_api_log_security_event('unauthorized', $channel_id, null);
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => ['code' => 'UNAUTHORIZED', 'message' => 'Authenticated actor required to post.']]);
        exit;
    }

    // Membership enforcement: actor must be in lupo_actor_channels for this channel, or global admin.
    $t_actor_channels = $table_prefix . 'actor_channels';
    $has_channel_access = false;
    $stmt = $db->prepare("SELECT 1 FROM {$t_actor_channels} WHERE actor_id = :actor_id AND channel_id = :channel_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_channel_access = true;
    }
    if (!$has_channel_access && $authService && is_object($authService) && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_channel_access = true;
        }
    }
    if (!$has_channel_access) {
        lupo_channels_api_log_security_event('forbidden', $channel_id, $actor_id);
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => ['code' => 'FORBIDDEN', 'message' => 'Actor not a member of this channel.']]);
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
