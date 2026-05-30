<?php
/**
 * REST: api/prompts/save | list | get | dispatch
 *
 * Routed via lupo_route_slug; requires bootstrap (session + mydatabase).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => 'Config not loaded'));
    exit;
}

$action = isset($GLOBALS['lupo_prompts_api_action']) ? (string) $GLOBALS['lupo_prompts_api_action'] : '';
if ($action === '') {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(array('ok' => false, 'error' => 'Missing route action'));
    exit;
}

$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
require_once rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'PromptLibraryService.php';

use App\Services\PromptLibraryService;

header('Content-Type: application/json; charset=utf-8');

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(array('ok' => false, 'error' => 'Database unavailable'));
    exit;
}

$actor_id = 0;
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
    $user = $authService->getCurrentUser();
    if (is_array($user) && !empty($user['actor_id'])) {
        $actor_id = (int) $user['actor_id'];
    }
}
if ($actor_id <= 0 && function_exists('current_user')) {
    $user = current_user();
    if (is_array($user) && !empty($user['actor_id'])) {
        $actor_id = (int) $user['actor_id'];
    }
}
if ($actor_id <= 0 && isset($GLOBALS['lupo_session']) && is_object($GLOBALS['lupo_session'])) {
    $s = $GLOBALS['lupo_session'];
    if (method_exists($s, 'validateSession')) {
        $aid = $s->validateSession();
        if ($aid !== false && $aid !== null) {
            $actor_id = (int) $aid;
        }
    }
    if ($actor_id <= 0 && method_exists($s, 'getActorId')) {
        $actor_id = (int) $s->getActorId();
    }
}

if ($actor_id <= 0) {
    http_response_code(401);
    echo json_encode(array('ok' => false, 'error' => 'Not authenticated'));
    exit;
}

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : '';

if ($action === 'list') {
    if ($method !== 'GET') {
        http_response_code(405);
        echo json_encode(array('ok' => false, 'error' => 'GET only'));
        exit;
    }
    $channel_key = isset($_GET['channel_key']) ? trim((string) $_GET['channel_key']) : '';
    if ($channel_key === '') {
        http_response_code(400);
        echo json_encode(array('ok' => false, 'error' => 'channel_key required'));
        exit;
    }
    if (!PromptLibraryService::actorCanAccessChannelKey($db, $actor_id, $channel_key)) {
        http_response_code(403);
        echo json_encode(array('ok' => false, 'error' => 'Forbidden'));
        exit;
    }
    $rows = PromptLibraryService::listByChannelKey($db, $channel_key, 200);
    $out = array();
    foreach ($rows as $r) {
        $pt = isset($r['prompt_text']) ? (string) $r['prompt_text'] : '';
        $pv = preg_replace('/\s+/', ' ', trim($pt));
        if (strlen($pv) > 120) {
            $pv = substr($pv, 0, 117) . '...';
        }
        $out[] = array(
            'prompt_id' => isset($r['prompt_id']) ? (int) $r['prompt_id'] : 0,
            'title' => isset($r['title']) ? (string) $r['title'] : '',
            'preview' => $pv,
            'status' => isset($r['status']) ? (string) $r['status'] : '',
            'last_updated_ymdhis' => isset($r['last_updated_ymdhis']) ? (int) $r['last_updated_ymdhis'] : 0,
            'created_by_actor_id' => isset($r['created_by_actor_id']) ? (int) $r['created_by_actor_id'] : 0,
            'actors_involved' => array(),
        );
    }
    echo json_encode(array('ok' => true, 'prompts' => $out));
    exit;
}

if ($action === 'get') {
    if ($method !== 'GET') {
        http_response_code(405);
        echo json_encode(array('ok' => false, 'error' => 'GET only'));
        exit;
    }
    $pid = isset($_GET['prompt_id']) ? (int) $_GET['prompt_id'] : 0;
    if ($pid <= 0) {
        http_response_code(400);
        echo json_encode(array('ok' => false, 'error' => 'prompt_id required'));
        exit;
    }
    $row = PromptLibraryService::getByIdForActor($db, $pid, $actor_id);
    if (!$row) {
        http_response_code(404);
        echo json_encode(array('ok' => false, 'error' => 'Not found'));
        exit;
    }
    echo json_encode(array('ok' => true, 'prompt' => $row));
    exit;
}

$input = array();
if ($action === 'save' || $action === 'dispatch') {
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(array('ok' => false, 'error' => 'POST only'));
        exit;
    }
    if (file_exists($app_root . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'security.php')) {
        require_once $app_root . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'security.php';
    }
    $raw = file_get_contents('php://input');
    if ($raw !== false && trim($raw) !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $input = $decoded;
        }
    }
    if (function_exists('lupo_verify_csrf_token')) {
        $tok = isset($input['csrf_token']) ? (string) $input['csrf_token'] : '';
        if (!lupo_verify_csrf_token($tok)) {
            http_response_code(403);
            echo json_encode(array('ok' => false, 'error' => 'CSRF validation failed'));
            exit;
        }
    }
    if ($action === 'save') {
        $res = PromptLibraryService::savePrompt($db, $actor_id, $input);
        if (empty($res['ok'])) {
            http_response_code(400);
            echo json_encode(array('ok' => false, 'error' => isset($res['error']) ? (string) $res['error'] : 'Save failed'));
            exit;
        }
        echo json_encode(array('ok' => true, 'prompt_id' => isset($res['prompt_id']) ? (int) $res['prompt_id'] : 0));
        exit;
    }
    if ($action === 'dispatch') {
        $res = PromptLibraryService::dispatchPrompt($db, $actor_id, $input);
        if (empty($res['ok'])) {
            http_response_code(400);
            echo json_encode(array('ok' => false, 'error' => isset($res['error']) ? (string) $res['error'] : 'Dispatch failed'));
            exit;
        }
        echo json_encode(array('ok' => true, 'message_id' => isset($res['message_id']) ? (int) $res['message_id'] : 0));
        exit;
    }
}

http_response_code(404);
echo json_encode(array('ok' => false, 'error' => 'Unknown action'));
