<?php
/**
 * Lupopedia REST API — Projects List
 * GET /api/v1/projects/list (or /lupo-api/v1/projects/list.php)
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

$node = isset($_GET['federation_node_id']) ? (int) $_GET['federation_node_id'] : 1;
$status = isset($_GET['status']) ? (string) $_GET['status'] : null;
if ($status === '') {
    $status = null;
}

$svc = isset($GLOBALS['lupo_project_service']) ? $GLOBALS['lupo_project_service'] : null;
if (!$svc) {
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : (function_exists('DatabaseFactory::getConnection') ? \DatabaseFactory::getConnection() : null);
    if ($db) {
        $app_services = (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '') . (defined('LUPO_APP_DIR') ? LUPO_APP_DIR : 'lupo-database/lupopedia/content/lupo-app') . '/Services';
        $ps = $app_services . DIRECTORY_SEPARATOR . 'ProjectService.php';
        if (is_file($ps)) {
            require_once $ps;
            $svc = new \App\Services\ProjectService($db);
        }
    }
}

if (!$svc) {
    http_response_code(503);
    echo json_encode(array('error' => 'Project service unavailable', 'utc_timestamp' => gmdate('YmdHis')));
    exit;
}

$list = $svc->listProjects($node, $status);
echo json_encode(array(
    'projects' => $list,
    'utc_timestamp' => gmdate('YmdHis'),
    'system_version' => '4.0.76',
));
