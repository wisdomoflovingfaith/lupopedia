<?php
/**
 * Context Graph REST API
 *
 * Endpoints (action set by router in $context_graph_api_action):
 *
 *   GET  api/context-graph/context   → ResolutionEngine::resolveFullContext()
 *   GET  api/context-graph/edges     → EdgeService::getEdges()
 *   POST api/context-graph/edge      → EdgeConcurrencyService::executeWithLock()
 *                                       └→ EdgeService::createEdge()
 *   DELETE api/context-graph/edge    → EdgeService::deleteEdge()
 *
 * All reads and writes go through the service layer exclusively.
 * No direct DB queries are made in this file.
 *
 * Auth: standard three-layer Lupopedia auth (lupo_auth_service → current_user()
 * → lupo_session). Client-supplied actor_id is never trusted.
 *
 * PHP 5.3 compatible.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die(json_encode(array('success' => false, 'error' => array('code' => 'CONFIG_NOT_LOADED', 'message' => 'Config not loaded.'))));
}

header('Content-Type: application/json; charset=utf-8');

// ── Resolve action (set by module-loader router) ───────────────────────────────
$cg_action = isset($context_graph_api_action) ? (string) $context_graph_api_action : '';
if ($cg_action === '') {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => array('code' => 'MISSING_ACTION', 'message' => 'No action resolved from route.')));
    exit;
}

// ── HTTP method ────────────────────────────────────────────────────────────────
$cg_method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';

// ── Autoload services ──────────────────────────────────────────────────────────
$cg_services_dir = defined('LUPOPEDIA_PATH')
    ? rtrim(LUPOPEDIA_PATH, '/\\') . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'ContextGraph' . DIRECTORY_SEPARATOR
    : dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'ContextGraph' . DIRECTORY_SEPARATOR;

foreach (array('EdgeIdService', 'EdgeValidationService', 'EdgeService', 'EdgeConcurrencyService', 'ResolutionEngine', 'ChannelThreadEdgeMapService') as $cg_class) {
    if (!class_exists($cg_class)) {
        $cg_file = $cg_services_dir . $cg_class . '.php';
        if (file_exists($cg_file)) {
            require_once $cg_file;
        }
    }
}

// ── Auth: resolve actor_id from session/auth context only ─────────────────────
$cg_actor_id = 0;

$cg_auth = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($cg_auth) {
    $cg_user = $cg_auth->getCurrentUser();
    if ($cg_user && !empty($cg_user['actor_id'])) {
        $cg_actor_id = (int) $cg_user['actor_id'];
    }
}
if (!$cg_actor_id && function_exists('current_user')) {
    $cg_user = current_user();
    if ($cg_user && !empty($cg_user['actor_id'])) {
        $cg_actor_id = (int) $cg_user['actor_id'];
    }
}
if (!$cg_actor_id && isset($GLOBALS['lupo_session'])) {
    $cg_actor_id = (int) $GLOBALS['lupo_session']->validateSession();
}

if (!$cg_actor_id) {
    http_response_code(401);
    echo json_encode(array('success' => false, 'error' => array('code' => 'UNAUTHORIZED', 'message' => 'Authentication required.')));
    exit;
}

// ── Instantiate services ───────────────────────────────────────────────────────
$cg_db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$cg_db && class_exists('DatabaseFactory')) {
    $cg_db = DatabaseFactory::getConnection();
}
if (!$cg_db) {
    http_response_code(503);
    echo json_encode(array('success' => false, 'error' => array('code' => 'DB_UNAVAILABLE', 'message' => 'Database not available.')));
    exit;
}

$cg_edge_service = new EdgeService($cg_db);
$cg_resolution_engine = new ResolutionEngine($cg_edge_service);
$cg_concurrency_service = new EdgeConcurrencyService($cg_db);

// ── Helpers ────────────────────────────────────────────────────────────────────

/**
 * Parse JSON body from request input, with $_POST fallback.
 * Returns an associative array (may be empty on parse failure).
 */
function lupo_cg_parse_request_body()
{
    $raw = file_get_contents('php://input');
    if ($raw !== false && $raw !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }
    return is_array($_POST) ? $_POST : array();
}

/**
 * Emit a structured error response and halt.
 *
 * @param int    $http_code
 * @param string $code
 * @param string $message
 * @param array  $details
 */
function lupo_cg_error($http_code, $code, $message, $details = array())
{
    http_response_code($http_code);
    $response = array('success' => false, 'error' => array('code' => $code, 'message' => $message));
    if (!empty($details)) {
        $response['error']['details'] = $details;
    }
    echo json_encode($response);
    exit;
}

/**
 * Verify actor can access a channel via membership or global admin.
 *
 * @param object $db
 * @param string $table_prefix
 * @param int $actor_id
 * @param object|null $auth_service
 * @param int $channel_id
 * @return bool
 */
function lupo_cg_can_access_channel($db, $table_prefix, $actor_id, $auth_service, $channel_id)
{
    $actor_id = (int) $actor_id;
    $channel_id = (int) $channel_id;
    if ($actor_id <= 0 || $channel_id <= 0) {
        return false;
    }

    $t_actor_channels = $table_prefix . 'actor_channels';
    $stmt = $db->prepare("SELECT 1 FROM {$t_actor_channels} WHERE actor_id = :actor_id AND channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        return true;
    }

    if ($auth_service && is_object($auth_service) && method_exists($auth_service, 'isAdmin')) {
        if ($auth_service->isAdmin($actor_id)) {
            return true;
        }
    }

    return false;
}

// ── Route dispatch ─────────────────────────────────────────────────────────────

// ── GET api/context-graph/context ─────────────────────────────────────────────
if ($cg_action === 'context' && $cg_method === 'GET') {
    $source_type = isset($_GET['source_type']) ? trim((string) $_GET['source_type']) : '';
    $source_id   = isset($_GET['source_id']) ? (int) $_GET['source_id'] : 0;

    if ($source_type === '') {
        lupo_cg_error(400, 'MISSING_PARAM', 'source_type is required.');
    }
    if ($source_id <= 0) {
        lupo_cg_error(400, 'INVALID_PARAM', 'source_id must be a positive integer.');
    }

    $context = $cg_resolution_engine->resolveFullContext($source_type, $source_id);

    echo json_encode(array(
        'success'      => true,
        'source_type'  => $source_type,
        'source_id'    => $source_id,
        'context'      => $context
    ));
    exit;
}

// ── GET api/context-graph/edges ───────────────────────────────────────────────
if ($cg_action === 'edges' && $cg_method === 'GET') {
    $source_type = isset($_GET['source_type']) ? trim((string) $_GET['source_type']) : '';
    $source_id   = isset($_GET['source_id']) ? (int) $_GET['source_id'] : 0;

    if ($source_type === '') {
        lupo_cg_error(400, 'MISSING_PARAM', 'source_type is required.');
    }
    if ($source_id <= 0) {
        lupo_cg_error(400, 'INVALID_PARAM', 'source_id must be a positive integer.');
    }

    $edges = $cg_edge_service->getEdges($source_type, $source_id);

    echo json_encode(array(
        'success'    => true,
        'source_type' => $source_type,
        'source_id'  => $source_id,
        'edges'      => $edges
    ));
    exit;
}

// ── GET api/context-graph/channel-map ─────────────────────────────────────────
if ($cg_action === 'channel-map' && $cg_method === 'GET') {
    $channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 0;
    $thread_limit = isset($_GET['thread_limit']) ? (int) $_GET['thread_limit'] : 200;
    $edge_limit = isset($_GET['edge_limit']) ? (int) $_GET['edge_limit'] : 2000;

    if ($channel_id <= 0) {
        lupo_cg_error(400, 'INVALID_PARAM', 'channel_id must be a positive integer.');
    }

    $cg_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    if (!lupo_cg_can_access_channel($cg_db, $cg_prefix, $cg_actor_id, $cg_auth, $channel_id)) {
        lupo_cg_error(403, 'FORBIDDEN', 'Actor does not have access to this channel.');
    }

    $map_service = new ChannelThreadEdgeMapService($cg_db, $cg_prefix);
    $channel_map = $map_service->buildChannelMap($channel_id, $thread_limit, $edge_limit);
    if (!$channel_map) {
        lupo_cg_error(404, 'NOT_FOUND', 'Channel not found.');
    }

    echo json_encode(array(
        'success' => true,
        'channel_id' => $channel_id,
        'map' => $channel_map
    ));
    exit;
}

// ── POST api/context-graph/edge ───────────────────────────────────────────────
if ($cg_action === 'edge' && $cg_method === 'POST') {
    $body = lupo_cg_parse_request_body();

    $source_type   = isset($body['source_type']) ? trim((string) $body['source_type']) : '';
    $source_id     = isset($body['source_id']) ? (int) $body['source_id'] : 0;
    $target_type   = isset($body['target_type']) ? trim((string) $body['target_type']) : '';
    $target_id     = isset($body['target_id']) ? (int) $body['target_id'] : 0;
    $edge_type     = isset($body['edge_type']) ? trim((string) $body['edge_type']) : '';
    $direction     = isset($body['direction']) ? trim((string) $body['direction']) : '';
    $metadata_json = isset($body['metadata_json']) ? (string) $body['metadata_json'] : null;

    $missing = array();
    if ($source_type === '') { $missing[] = 'source_type'; }
    if ($source_id <= 0)    { $missing[] = 'source_id'; }
    if ($target_type === '') { $missing[] = 'target_type'; }
    if ($target_id <= 0)    { $missing[] = 'target_id'; }
    if ($edge_type === '')   { $missing[] = 'edge_type'; }
    if ($direction === '')   { $missing[] = 'direction'; }
    if (!empty($missing)) {
        lupo_cg_error(400, 'MISSING_PARAMS', 'Required fields missing.', $missing);
    }

    // Execute under named lock to serialize concurrent writes to the same edge space.
    $lock_result = $cg_concurrency_service->executeWithLock(
        $source_type,
        $source_id,
        $target_type,
        $target_id,
        function () use ($cg_edge_service, $source_type, $source_id, $target_type, $target_id, $edge_type, $direction, $metadata_json) {
            return $cg_edge_service->createEdge(
                $source_type,
                $source_id,
                $target_type,
                $target_id,
                $edge_type,
                $direction,
                $metadata_json
            );
        }
    );

    if (!$lock_result['success']) {
        http_response_code(409);
        echo json_encode(array(
            'success' => false,
            'error' => array(
                'code'    => 'CONCURRENCY_FAILURE',
                'message' => $lock_result['reason']
            )
        ));
        exit;
    }

    http_response_code(201);
    echo json_encode(array(
        'success' => true,
        'edge'    => $lock_result['result']
    ));
    exit;
}

// ── DELETE api/context-graph/edge ─────────────────────────────────────────────
if ($cg_action === 'edge' && $cg_method === 'DELETE') {
    // Accept edge_id from JSON body (standard for DELETE with body) or query string.
    $edge_id = null;

    $raw = file_get_contents('php://input');
    if ($raw !== false && $raw !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded) && !empty($decoded['edge_id'])) {
            $edge_id = (string) $decoded['edge_id'];
        }
    }
    if ($edge_id === null && isset($_GET['edge_id'])) {
        $edge_id = (string) $_GET['edge_id'];
    }

    if ($edge_id === null || $edge_id === '') {
        lupo_cg_error(400, 'MISSING_PARAM', 'edge_id is required.');
    }

    $deleted = $cg_edge_service->deleteEdge($edge_id);

    if ($deleted === false) {
        lupo_cg_error(404, 'NOT_FOUND', 'Edge not found or already deleted.');
    }

    echo json_encode(array(
        'success' => true,
        'edge_id' => $edge_id
    ));
    exit;
}

// ── Unmatched method/action combination ───────────────────────────────────────
lupo_cg_error(405, 'METHOD_NOT_ALLOWED', 'Method ' . $cg_method . ' is not allowed on action ' . $cg_action . '.');
