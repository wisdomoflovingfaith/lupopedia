<?php
/**
 * Lupopedia REST API — Update Project
 * PUT /api/v1/projects/update.php (body: id + fields to update)
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

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$raw = file_get_contents('php://input');
$in = json_decode($raw, true);
if (!is_array($in) || !isset($in['project_id'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'JSON body must include project_id', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$id = (int) $in['project_id'];
$updated_by = isset($in['updated_by_actor_id']) ? (int) $in['updated_by_actor_id'] : null;
unset($in['project_id'], $in['updated_by_actor_id']);

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

$ok = $svc->updateProject($id, $in, $updated_by);
if (!$ok) {
    http_response_code(400);
    echo json_encode(array('error' => 'Update failed', 'project_id' => $id, 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$project = $svc->getProjectById($id);
echo json_encode(array(
    'project' => $project,
    'utc_timestamp' => gmdate('YmdHis'),
    'system_version' => '4.0.76',
));
