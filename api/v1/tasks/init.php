<?php
/**
 * Tasks API shared initialization
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_PATH', realpath(__DIR__ . '/../../../'));
    define('LUPOPEDIA_PUBLIC_PATH', '/lupopedia');
    
    require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
    $configPath = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
    if ($configPath) {
        require_once $configPath;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Config not found']);
        exit;
    }
}

require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/includes/classes/TaskService.php';

$db = DatabaseFactory::getConnection();
$taskService = new TaskService($db);

function tasks_api_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function tasks_get_current_actor() {
    if (isset($GLOBALS['lupo_auth_service'])) {
        $user = $GLOBALS['lupo_auth_service']->getCurrentUser();
        return $user['actor_id'] ?? null;
    }
    return null;
}
