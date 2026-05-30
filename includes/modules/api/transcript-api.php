<?php
/**
 * PRD 16 §9 — Transcript append API (DB-first).
 *
 * Route: POST api/transcript/append
 * Also: index.php?slug=api/transcript/append
 *
 * Auth: session (actor must match from_actor_id and have channel access) OR
 *       X-Lupo-Api-Token / X-API-Token matching LUPO_TRANSCRIPT_API_TOKEN (optional define in config).
 *
 * JSON body: channel_key, message, from_actor_id, dialog_transcript;
 * optional: created_ymdhis, task, context, to_actor_id (directed recipient for you/your rewrite), metadata_json (JSON object string merged into row metadata_json)
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => array('code' => 'CONFIG_NOT_LOADED', 'message' => 'Config not loaded.')));
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => array('code' => 'METHOD_NOT_ALLOWED', 'message' => 'POST only')));
    exit;
}

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(array('success' => false, 'error' => array('code' => 'DB_UNAVAILABLE', 'message' => 'Database not available.')));
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw !== false ? $raw : '', true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => array('code' => 'invalid_json', 'message' => 'Request body must be JSON object')));
    exit;
}

$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
$dm_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DialogMvpService.php';
$ts_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TranscriptAppendService.php';
if (is_file($dm_path)) {
    require_once $dm_path;
}
if (is_file($ts_path)) {
    require_once $ts_path;
}

if (!class_exists('TranscriptAppendService', false) || !class_exists('DialogMvpService', false)) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => array('code' => 'CLASS_MISSING', 'message' => 'Transcript services not loadable')));
    exit;
}

$hdr_token = '';
if (isset($_SERVER['HTTP_X_LUPO_API_TOKEN'])) {
    $hdr_token = trim((string) $_SERVER['HTTP_X_LUPO_API_TOKEN']);
} elseif (isset($_SERVER['HTTP_X_API_TOKEN'])) {
    $hdr_token = trim((string) $_SERVER['HTTP_X_API_TOKEN']);
}

$token_auth = false;
$cfg_token = defined('LUPO_TRANSCRIPT_API_TOKEN') ? (string) LUPO_TRANSCRIPT_API_TOKEN : '';
if ($cfg_token !== '' && $hdr_token !== '' && hash_equals($cfg_token, $hdr_token)) {
    $token_auth = true;
}

$from_body = isset($body['from_actor_id']) ? (int) $body['from_actor_id'] : 0;

if (!$token_auth) {
    $actor_id = null;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
        $user = $authService->getCurrentUser();
        if (is_array($user) && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if (is_array($user) && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if ($actor_id === null && isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session'])) {
        $s = $GLOBALS['lupo_session'];
        if (method_exists($s, 'validateSession')) {
            $aid = $s->validateSession();
            if ($aid !== false && $aid !== null) {
                $actor_id = (int) $aid;
            }
        }
    }

    if ($actor_id === null || $actor_id <= 0) {
        http_response_code(401);
        echo json_encode(array('success' => false, 'error' => array('code' => 'unauthorized', 'message' => 'Session or API token required')));
        exit;
    }

    if ($from_body > 0 && $from_body !== $actor_id) {
        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        $is_admin = $authService && is_object($authService) && method_exists($authService, 'isAdmin') && $authService->isAdmin($actor_id);
        if (!$is_admin) {
            http_response_code(403);
            echo json_encode(array('success' => false, 'error' => array('code' => 'forbidden', 'message' => 'from_actor_id must match session actor')));
            exit;
        }
    }

    if ($from_body <= 0) {
        $body['from_actor_id'] = $actor_id;
    }
}

$result = TranscriptAppendService::append($db, $body, array('token_auth' => $token_auth));

if (empty($result['ok'])) {
    $code = isset($result['http_status']) ? (int) $result['http_status'] : 400;
    if ($code < 400 || $code > 599) {
        $code = 400;
    }
    http_response_code($code);
    echo json_encode(array(
        'success' => false,
        'error' => array(
            'code' => isset($result['error_code']) ? $result['error_code'] : 'error',
            'message' => isset($result['message']) ? $result['message'] : '',
        ),
    ));
    exit;
}

echo json_encode(array(
    'dialog_message_id' => $result['dialog_message_id'],
    'dialog_thread_id' => $result['dialog_thread_id'],
    'status' => 'ok',
));
