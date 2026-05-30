<?php
/**
 * Lupopedia REST API — Archive Project
 * POST /api/v1/projects/archive.php (body: project_id [, updated_by_actor_id])
 *
 * @version 4.0.76
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(dirname(__DIR__))));
}
$config = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (is_file($config)) {
    require_once $config;
} else {
    header('Content-Type: application/json');
    http_response_code(503);
    echo json_encode(array('error' => 'Configuration not loaded', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$raw = file_get_contents('php://input');
$in = is_string($raw) && $raw !== '' ? json_decode($raw, true) : array();
if (!is_array($in)) {
    $in = array();
}
$id = isset($in['project_id']) ? (int) $in['project_id'] : (isset($_POST['project_id']) ? (int) $_POST['project_id'] : 0);
$updated_by = isset($in['updated_by_actor_id']) ? (int) $in['updated_by_actor_id'] : null;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(array('error' => 'Missing or invalid project_id', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : null;
if (!$svc && isset($GLOBALS['mydatabase'])) {
    $app_services = (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '') . (defined('LUPO_APP_DIR') ? LUPO_APP_DIR : 'lupo-database/lupopedia/content/lupo-app') . '/Services';
    $ps = $app_services . DIRECTORY_SEPARATOR . 'ProjectService.php';
    if (is_file($ps)) {
        require_once $ps;
        $svc = new \App\Services\ProjectService($GLOBALS['mydatabase']);
    }
}

if (!$svc) {
    http_response_code(503);
    echo json_encode(array('error' => 'Project service unavailable', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$ok = $svc->archiveProject($id, $updated_by);
if (!$ok) {
    http_response_code(400);
    echo json_encode(array('error' => 'Archive failed', 'project_id' => $id, 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$project = $svc->getProjectById($id);
echo json_encode(array(
    'project' => $project,
    'utc_timestamp' => gmdate('YmdHis'),
    'system_version' => '4.0.76',
));
