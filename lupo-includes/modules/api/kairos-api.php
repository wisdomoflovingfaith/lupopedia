<?php
/**
 * KAIROS REST API — background consolidation tick (web chat companion).
 *
 * Route: POST api/lupo-kairos/tick
 * Optional JSON body: { "department_id": <int|null> }
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die(json_encode(array('success' => false, 'error' => array('code' => 'CONFIG_NOT_LOADED', 'message' => 'Config not loaded.'))));
}

header('Content-Type: application/json; charset=utf-8');

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(array('success' => false, 'error' => array('code' => 'DB_UNAVAILABLE', 'message' => 'Database not available.')));
    exit;
}

$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => array('code' => 'METHOD_NOT_ALLOWED', 'message' => 'Use POST.')));
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$minInterval = 90;
$lastUnix = isset($_SESSION['kairos_tick_last_unix']) ? (int) $_SESSION['kairos_tick_last_unix'] : 0;
if ($lastUnix > 0 && (time() - $lastUnix) < $minInterval) {
    echo json_encode(
        array(
            'success' => true,
            'skipped' => true,
            'reason' => 'rate_limited',
            'min_interval_seconds' => $minInterval,
        )
    );
    exit;
}

$actorId = 0;
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService && is_object($authService) && method_exists($authService, 'getCurrentUser')) {
    $user = $authService->getCurrentUser();
    if ($user && !empty($user['actor_id'])) {
        $actorId = (int) $user['actor_id'];
    }
}

if ($actorId <= 0) {
    http_response_code(401);
    echo json_encode(array('success' => false, 'error' => array('code' => 'UNAUTHORIZED', 'message' => 'Login required for KAIROS tick.')));
    exit;
}

$departmentId = null;
$raw = file_get_contents('php://input');
if ($raw !== false && $raw !== '') {
    $body = json_decode($raw, true);
    if (is_array($body) && array_key_exists('department_id', $body) && $body['department_id'] !== null && $body['department_id'] !== '') {
        $departmentId = (int) $body['department_id'];
    }
}

$appRoot = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
require_once $appRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Kairos' . DIRECTORY_SEPARATOR . 'KairosTemporalAnchor.php';
require_once $appRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Kairos' . DIRECTORY_SEPARATOR . 'KairosConsolidationService.php';

$svc = new KairosConsolidationService($db, null, 115);
$stats = $svc->consolidateMemories($actorId, $departmentId);

$_SESSION['kairos_tick_last_unix'] = time();

echo json_encode(
    array(
        'success' => true,
        'skipped' => false,
        'actor_id' => $actorId,
        'anchor_utc' => KairosTemporalAnchor::getUtcYmdHis(),
        'stats' => $stats,
    )
);
