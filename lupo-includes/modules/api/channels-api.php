<?php
/**
 * Channels REST API — RESTful message send/receive (VSX extension and channel UI).
 *
 * Routes handled (module-loader sets $channels_api_channel_id):
 *   GET  api/lupo-channels/{id}/messages?since=&format=json|buffer|image
 *   POST api/lupo-channels/{id}/messages
 *
 * Query (GET): since, thread_id (optional), limit, offset.
 *   format=json (default), format=buffer (text/plain JSON), format=image (302 to digit GIF).
 *   Image: whatplace= or position= hundreds|tens|ones; image_metric=time|count.
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

/**
 * Resolve authenticated effective actor for channel operations.
 * Uses active actor and chat identity preferences via EffectiveActorResolver.
 *
 * @param object $db
 * @param int $channel_id
 * @return array
 */
function lupo_channels_api_resolve_effective_actor($db, $channel_id)
{
    require_once __DIR__ . '/../../classes/EffectiveActorResolver.php';
    return EffectiveActorResolver::resolveForCurrentUser($db, (int) $channel_id);
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
    // Require authenticated effective actor and membership (or admin) for message retrieval.
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $resolved_actor = lupo_channels_api_resolve_effective_actor($db, $channel_id);
    $actor_id = isset($resolved_actor['actor_id']) ? (int) $resolved_actor['actor_id'] : 0;

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

    $format = isset($_GET['format']) ? strtolower(trim((string) $_GET['format'])) : 'json';
    if ($format !== 'json' && $format !== 'buffer' && $format !== 'image') {
        http_response_code(400);
        echo json_encode(array(
            'success' => false,
            'error' => array(
                'code' => 'INVALID_FORMAT',
                'message' => 'format must be json, buffer, or image.',
            ),
        ));
        exit;
    }

    $since = isset($_GET['since']) ? trim($_GET['since']) : '';
    $limit = isset($_GET['limit']) ? min(200, max(1, (int) $_GET['limit'])) : 50;
    $offset = isset($_GET['offset']) ? max(0, (int) $_GET['offset']) : 0;

    $t_msg = $table_prefix . 'dialog_messages';
    $t_act = $table_prefix . 'actors';

    $params = array('channel_id' => $channel_id);
    $sinceClause = '';
    if ($since !== '' && preg_match('/^\d{14}$/', $since)) {
        $sinceClause = ' AND m.created_ymdhis > :since';
        $params['since'] = (int) $since;
    }

    $threadClause = '';
    $thread_id_q = isset($_GET['thread_id']) ? (int) $_GET['thread_id'] : 0;
    if ($thread_id_q < 0) {
        $thread_id_q = 0;
    }
    if ($thread_id_q > 0) {
        $threadClause = ' AND m.dialog_thread_id = :thread_id';
        $params['thread_id'] = $thread_id_q;
    }

    $sql = "SELECT m.dialog_message_id AS message_id, m.dialog_thread_id AS dialog_thread_id, m.from_actor_id AS actor_id, "
         . "a.name AS actor_name, a.actor_type, m.channel_id, "
         . "m.message_text AS body, m.created_ymdhis AS created_at "
         . "FROM {$t_msg} m "
         . "LEFT JOIN {$t_act} a ON a.actor_id = m.from_actor_id "
         . "WHERE m.channel_id = :channel_id AND m.is_deleted = 0{$sinceClause}{$threadClause} "
         . "ORDER BY m.created_ymdhis ASC "
         . "LIMIT {$limit} OFFSET {$offset}";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $messages = array_map(function ($r) {
            $tid = isset($r['dialog_thread_id']) ? $r['dialog_thread_id'] : null;
            return array(
                'message_id'         => isset($r['message_id']) ? (int) $r['message_id'] : null,
                'dialog_thread_id' => ($tid === null || $tid === '') ? null : (int) $tid,
                'actor_id'         => (int) $r['actor_id'],
                'actor_name'       => $r['actor_name'],
                'actor_type'       => $r['actor_type'],
                'channel_id'       => (int) $r['channel_id'],
                'body'             => $r['body'],
                'created_at'       => $r['created_at'],
            );
        }, $rows);

        if ($format === 'image') {
            $whatplace = isset($_GET['whatplace']) ? strtolower(trim((string) $_GET['whatplace'])) : '';
            if ($whatplace === '' && isset($_GET['position'])) {
                $whatplace = strtolower(trim((string) $_GET['position']));
            }
            if ($whatplace !== 'hundreds' && $whatplace !== 'tens' && $whatplace !== 'ones') {
                http_response_code(400);
                echo json_encode(array(
                    'success' => false,
                    'error' => array(
                        'code' => 'INVALID_WHATPLACE',
                        'message' => 'format=image requires whatplace= or position=hundreds|tens|ones',
                    ),
                ));
                exit;
            }
            $imageMetric = isset($_GET['image_metric']) ? strtolower(trim((string) $_GET['image_metric'])) : 'time';
            $n = 0;
            $rowCount = count($rows);
            if ($imageMetric === 'count') {
                $n = $rowCount % 1000;
            } elseif ($rowCount > 0) {
                $last = $rows[$rowCount - 1];
                $created = isset($last['created_at']) ? (int) $last['created_at'] : 0;
                $n = $created % 1000;
            }
            $h = (int) floor($n / 100);
            $t = (int) floor(($n - $h * 100) / 10);
            $o = $n - $h * 100 - $t * 10;
            $digit = 0;
            if ($whatplace === 'hundreds') {
                $digit = $h;
            } elseif ($whatplace === 'tens') {
                $digit = $t;
            } else {
                $digit = $o;
            }
            if ($digit < 0) {
                $digit = 0;
            }
            if ($digit > 9) {
                $digit = 9;
            }
            // Redirect so the browser's final img.src ends with digitN.gif (legacy parsers).
            if (function_exists('header_remove')) {
                header_remove('Content-Type');
            }
            $pub = defined('LUPOPEDIA_PUBLIC_PATH') ? (string) LUPOPEDIA_PUBLIC_PATH : '';
            $pub = rtrim($pub, '/');
            $loc = ($pub === '' ? '' : $pub) . '/lupo-ui/images/digit' . $digit . '.gif';
            header('Location: ' . $loc, true, 302);
            header('Cache-Control: no-store, no-cache, must-revalidate');
            exit;
        }

        $payload = array(
            'success'    => true,
            'channel_id' => $channel_id,
            'messages'   => $messages,
            'total'      => count($messages),
            'limit'      => $limit,
            'offset'     => $offset,
        );

        if ($format === 'buffer') {
            header('Content-Type: text/plain; charset=utf-8', true);
            echo json_encode($payload);
            exit;
        }

        echo json_encode($payload);
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
    $routing_type = isset($input['routing_type']) ? trim($input['routing_type']) : 'broadcast';
    $to_actor_id = isset($input['to_actor_id']) ? (int) $input['to_actor_id'] : null;
    $thread_raw = isset($input['thread_id']) ? $input['thread_id'] : null;
    $thread_id = null;
    if ($routing_type === 'thread') {
        require_once __DIR__ . '/../../classes/Lupo_Channel_Artifact_Validator.php';
        if (!Lupo_Channel_Artifact_Validator::isValidDialogThreadId($thread_raw)) {
            http_response_code(400);
            echo json_encode(array('success' => false, 'error' => array('code' => 'INVALID_THREAD_ID', 'message' => 'thread_id must be a positive integer matching lupo_dialog_threads.dialog_thread_id')));
            exit;
        }
        $thread_id = (int) $thread_raw;
    }

    if ($body === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'body must not be empty.']]);
        exit;
    }

    if ($routing_type === 'thread') {
        require_once __DIR__ . '/../../classes/Lupo_Channel_Artifact_Validator.php';
        $thrErr = Lupo_Channel_Artifact_Validator::validateThreadPostBody($body, $message_type, $meta);
        if ($thrErr !== null) {
            $code = (stripos($thrErr, 'help_response') !== false) ? 'THREAD_HELP_RESPONSE_BODY' : 'THREAD_REVIEW_BODY';
            http_response_code(400);
            echo json_encode(array('success' => false, 'error' => array('code' => $code, 'message' => $thrErr)));
            exit;
        }
    }

    // Actor identity MUST come from authenticated session / server-side effective context only.
    // Client-supplied actor_id is never trusted for authorization or insertion.
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $resolved_actor = lupo_channels_api_resolve_effective_actor($db, $channel_id);
    $actor_id = isset($resolved_actor['actor_id']) ? (int) $resolved_actor['actor_id'] : 0;

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

    require_once __DIR__ . '/../../classes/Lupo_Channel_Artifact_Validator.php';
    $is_admin = ($authService && is_object($authService) && method_exists($authService, 'isAdmin') && $authService->isAdmin($actor_id));

    $t_roles = $table_prefix . 'actor_channel_roles';
    $role_keys = array();
    $stmt = $db->prepare("SELECT DISTINCT role_key FROM {$t_roles} WHERE actor_id = :a AND channel_id = :c AND is_deleted = 0");
    $stmt->execute(array(':a' => $actor_id, ':c' => $channel_id));
    while ($rk = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($rk['role_key'])) {
            $role_keys[] = strtolower(trim($rk['role_key']));
        }
    }

    $coord_action = isset($input['coordination_action']) ? strtolower(trim((string) $input['coordination_action'])) : '';

    $broadcast_roles = array('captain', 'guardian', 'critic', 'steward', 'administrator', 'monitor', 'orchestrator');
    $content_roles = array('editor', 'author', 'custodian', 'administrator', 'captain', 'orchestrator');
    $task_roles = array('assignee', 'owner', 'administrator', 'captain', 'orchestrator');
    $rule_roles = array('guardian', 'orchestrator', 'administrator', 'captain');

    $has_any = function ($need, $keys) {
        foreach ($need as $n) {
            if (in_array($n, $keys, true)) {
                return true;
            }
        }
        return false;
    };

    if (!$is_admin && $routing_type === 'broadcast' && count($role_keys) > 0 && !$has_any($broadcast_roles, $role_keys)) {
        lupo_channels_api_log_security_event('forbidden', $channel_id, $actor_id);
        http_response_code(403);
        echo json_encode(array('success' => false, 'error' => array('code' => 'FORBIDDEN_ROLE', 'message' => 'Broadcast requires captain, guardian, critic, steward, administrator, monitor, or orchestrator role on this channel.')));
        exit;
    }

    if (!$is_admin && $coord_action !== '') {
        $need = null;
        if ($coord_action === 'content') {
            $need = $content_roles;
        } elseif ($coord_action === 'task') {
            $need = $task_roles;
        } elseif ($coord_action === 'rule') {
            $need = $rule_roles;
        } elseif ($coord_action === 'broadcast') {
            $need = $broadcast_roles;
        }
        if ($need !== null && !$has_any($need, $role_keys)) {
            lupo_channels_api_log_security_event('forbidden', $channel_id, $actor_id);
            http_response_code(403);
            echo json_encode(array('success' => false, 'error' => array('code' => 'FORBIDDEN_ROLE', 'message' => 'coordination_action requires appropriate channel role.')));
            exit;
        }
    }

    require_once __DIR__ . '/../../classes/Lupo_Channel_Message_Router.php';
    $router = new Lupo_Channel_Message_Router($db, $table_prefix);
    if ($routing_type === 'direct') {
        $result = $router->handleDirectMessage($channel_id, $actor_id, $to_actor_id, $body, $message_type, $meta);
    } elseif ($routing_type === 'thread') {
        $result = $router->handleThreadMessage($channel_id, $thread_id, $actor_id, $body, $message_type, $meta);
    } else {
        $result = $router->handleBroadcast($channel_id, $actor_id, $body, $message_type, $meta);
    }

    if (!empty($result['success'])) {
        http_response_code(201);
        $fp = isset($result['file_path']) ? $result['file_path'] : null;
        echo json_encode(array(
            'success' => true,
            'accepted' => true,
            'message_id' => $result['message_id'],
            'channel_id' => $channel_id,
            'routing_type' => $routing_type,
            'file_path' => $fp,
        ));
    } else {
        $err = isset($result['error']) ? $result['error'] : 'Unknown error';
        $code = (strpos((string) $err, 'require') !== false) ? 400 : 500;
        http_response_code($code);
        echo json_encode(array('success' => false, 'error' => array('code' => 'ROUTER_ERROR', 'message' => $err)));
    }
    exit;
}

// ── Fallback ───────────────────────────────────────────────────────────────────
http_response_code(405);
echo json_encode(['success' => false, 'error' => ['code' => 'METHOD_NOT_ALLOWED', 'message' => "Unsupported method: {$method}"]]);
exit;
