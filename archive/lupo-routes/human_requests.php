<?php
/**
 * Human Requests Routes (Thread 1038)
 *
 * Module-loader entrypoint:
 * - /visibility/human-inbox
 * - /visibility/human-inbox/api/*
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. human_requests.php cannot be called directly.');
}

require_once LUPOPEDIA_ABSPATH . '/lupo-includes/HumanRequestService.php';

function human_requests_handle_slug($slug)
{
    $slug = trim((string) $slug, '/');
    if (strpos($slug, 'visibility/human-inbox') !== 0) {
        return '';
    }

    $parts = explode('/', $slug);

    if (isset($parts[2]) && $parts[2] === 'api') {
        return human_requests_handle_api($parts);
    }

    return human_requests_render_inbox();
}

function human_requests_handle_api($parts)
{
    $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
    $action = isset($parts[3]) ? $parts[3] : '';
    $service = new HumanRequestService();

    if ($action === 'requests' && $method === 'GET') {
        $auth_user_id = human_requests_current_auth_user_id();
        if ($auth_user_id <= 0) {
            return human_requests_json_error('Authentication required', 401);
        }

        $filters = array(
            'priority' => isset($_GET['priority']) ? $_GET['priority'] : '',
            'thread_id' => isset($_GET['thread_id']) ? (int) $_GET['thread_id'] : 0,
            'request_type' => isset($_GET['request_type']) ? $_GET['request_type'] : ''
        );

        $requests = $service->getPendingRequests($auth_user_id, $filters);
        return human_requests_json_ok(array('requests' => $requests, 'count' => count($requests)));
    }

    if ($action === 'requests' && $method === 'POST') {
        $actor_id = human_requests_current_actor_id();
        if ($actor_id <= 0) {
            return human_requests_json_error('Authentication required', 401);
        }

        $input = human_requests_read_json_input();
        if ($input === null) {
            return human_requests_json_error('Invalid JSON payload', 400);
        }

        try {
            $input['initiator_actor_id'] = $actor_id;
            $request_id = $service->createRequest($input);
            return human_requests_json_ok(array('request_id' => $request_id), 201);
        } catch (Exception $e) {
            return human_requests_json_error($e->getMessage(), 400);
        }
    }

    if ($action === 'route' && $method === 'POST') {
        $actor_id = human_requests_current_actor_id();
        if ($actor_id <= 0) {
            return human_requests_json_error('Authentication required', 401);
        }

        $input = human_requests_read_json_input();
        if ($input === null || !isset($input['thread_id']) || !isset($input['trigger_type'])) {
            return human_requests_json_error('thread_id and trigger_type are required', 400);
        }

        try {
            $routing = $service->routeToHumanMvp(
                (int) $actor_id,
                (int) $input['thread_id'],
                (string) $input['trigger_type'],
                $input
            );
            return human_requests_json_ok(array('routing' => $routing), 201);
        } catch (Exception $e) {
            return human_requests_json_error($e->getMessage(), 400);
        }
    }

    if ($action === 'respond' && $method === 'POST') {
        $auth_user_id = human_requests_current_auth_user_id();
        $actor_id = human_requests_current_actor_id();
        if ($auth_user_id <= 0 || $actor_id <= 0) {
            return human_requests_json_error('Authentication required', 401);
        }

        $input = human_requests_read_json_input();
        if ($input === null || !isset($input['request_id'])) {
            return human_requests_json_error('request_id is required', 400);
        }

        try {
            $input['auth_user_id'] = $auth_user_id;
            $input['actor_id'] = $actor_id;
            $response_id = $service->respondToRequest((int) $input['request_id'], $input);
            return human_requests_json_ok(array('response_id' => $response_id));
        } catch (Exception $e) {
            return human_requests_json_error($e->getMessage(), 400);
        }
    }

    if ($action === 'status' && ($method === 'POST' || $method === 'PUT')) {
        $actor_id = human_requests_current_actor_id();
        if ($actor_id <= 0) {
            return human_requests_json_error('Authentication required', 401);
        }

        $input = human_requests_read_json_input();
        if ($input === null || !isset($input['request_id']) || !isset($input['status'])) {
            return human_requests_json_error('request_id and status are required', 400);
        }

        try {
            $service->transitionStatus((int) $input['request_id'], $input['status'], $actor_id, array());
            return human_requests_json_ok(array('request_id' => (int) $input['request_id'], 'status' => $input['status']));
        } catch (Exception $e) {
            return human_requests_json_error($e->getMessage(), 400);
        }
    }

    if ($action === 'thread' && isset($parts[4]) && $method === 'GET') {
        $thread_id = (int) $parts[4];
        if ($thread_id <= 0) {
            return human_requests_json_error('thread_id is required', 400);
        }

        $requests = $service->getThreadRequests($thread_id);
        $summary = $service->getThreadRequestSummary($thread_id);
        return human_requests_json_ok(array('thread_id' => $thread_id, 'summary' => $summary, 'requests' => $requests));
    }

    if ($action === 'request' && isset($parts[4]) && $method === 'GET') {
        $request_id = (int) $parts[4];
        if ($request_id <= 0) {
            return human_requests_json_error('request_id is required', 400);
        }

        $request = $service->getRequest($request_id);
        if (!$request) {
            return human_requests_json_error('Request not found', 404);
        }

        $responses = $service->getRequestResponses($request_id);
        return human_requests_json_ok(array('request' => $request, 'responses' => $responses));
    }

    if ($action === 'expire' && $method === 'POST') {
        $actor_id = human_requests_current_actor_id();
        if ($actor_id !== 1) {
            return human_requests_json_error('Only WOLFIE can expire requests', 403);
        }

        try {
            $expired_count = $service->expireOpenRequests($actor_id);
            return human_requests_json_ok(array('expired_count' => $expired_count));
        } catch (Exception $e) {
            return human_requests_json_error($e->getMessage(), 400);
        }
    }

    return human_requests_json_error('Endpoint not found', 404);
}

function human_requests_render_inbox()
{
    $auth_user_id = human_requests_current_auth_user_id();
    if ($auth_user_id <= 0) {
        return '<h1>Login Required</h1><p>Please log in to access the human inbox.</p>';
    }

    $service = new HumanRequestService();
    $pending_requests = $service->getPendingRequests($auth_user_id, array());

    $grouped_requests = array();
    foreach ($pending_requests as $req) {
        $thread_id = isset($req['thread_id']) ? (int) $req['thread_id'] : 0;
        if (!isset($grouped_requests[$thread_id])) {
            $grouped_requests[$thread_id] = array(
                'thread_title' => !empty($req['thread_title']) ? $req['thread_title'] : ('Thread ' . $thread_id),
                'channel_name' => !empty($req['channel_name']) ? $req['channel_name'] : ('Channel ' . (int) $req['channel_id']),
                'requests' => array()
            );
        }
        $grouped_requests[$thread_id]['requests'][] = $req;
    }

    $actor_id = human_requests_current_actor_id();
    $view_path = LUPOPEDIA_ABSPATH . '/lupo-views/visibility/human_inbox.php';
    if (!file_exists($view_path)) {
        return '<h1>Error</h1><p>Human inbox view file not found.</p>';
    }

    ob_start();
    include $view_path;
    return ob_get_clean();
}

function human_requests_current_auth_user_id()
{
    if (isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service'])) {
        $auth_service = $GLOBALS['lupo_auth_service'];
        if (method_exists($auth_service, 'isLoggedIn') && $auth_service->isLoggedIn()) {
            if (method_exists($auth_service, 'getCurrentUser')) {
                $user = $auth_service->getCurrentUser();
                if (is_array($user) && isset($user['auth_user_id'])) {
                    return (int) $user['auth_user_id'];
                }
            }
        }
    }

    if (function_exists('current_user')) {
        $user = current_user();
        if (is_array($user) && isset($user['auth_user_id'])) {
            return (int) $user['auth_user_id'];
        }
    }

    return isset($_SESSION['auth_user_id']) ? (int) $_SESSION['auth_user_id'] : 0;
}

function human_requests_current_actor_id()
{
    if (isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service'])) {
        $auth_service = $GLOBALS['lupo_auth_service'];
        if (method_exists($auth_service, 'isLoggedIn') && $auth_service->isLoggedIn()) {
            if (method_exists($auth_service, 'getCurrentUser')) {
                $user = $auth_service->getCurrentUser();
                if (is_array($user) && isset($user['actor_id'])) {
                    return (int) $user['actor_id'];
                }
            }
        }
    }

    if (function_exists('current_user')) {
        $user = current_user();
        if (is_array($user) && isset($user['actor_id'])) {
            return (int) $user['actor_id'];
        }
    }

    return isset($_SESSION['actor_id']) ? (int) $_SESSION['actor_id'] : 0;
}

function human_requests_read_json_input()
{
    $raw = file_get_contents('php://input');
    if (!is_string($raw) || trim($raw) === '') {
        return array();
    }

    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        return null;
    }
    return $decoded;
}

function human_requests_json_ok($data, $status_code)
{
    if ($status_code === null) {
        $status_code = 200;
    }
    http_response_code((int) $status_code);
    header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'data' => $data));
    exit;
}

function human_requests_json_error($message, $status_code)
{
    http_response_code((int) $status_code);
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'error' => (string) $message));
    exit;
}
