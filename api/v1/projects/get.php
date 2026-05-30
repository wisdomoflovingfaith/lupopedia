<?php
/**
 * Lupopedia REST API — Get Project by ID
 * GET /api/v1/projects/get.php?id=1
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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(array('error' => 'Missing or invalid id', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : null;
if (!$svc && isset($GLOBALS['mydatabase'])) {
    $app_services = (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '') . (defined('LUPO_APP_DIR') ? LUPO_APP_DIR : 'database/lupopedia/content/app') . '/Services';
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

$project = $svc->getProjectById($id);
if (!$project) {
    http_response_code(404);
    echo json_encode(array('error' => 'Project not found', 'project_id' => $id, 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

echo json_encode(array(
    'project' => $project,
    'utc_timestamp' => gmdate('YmdHis'),
    'system_version' => '4.0.76',
));
