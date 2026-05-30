<?php
/**
 * JSON: grouped nav-menu collections for master Collections dropdown (flyouts by nav_group).
 */

$config_paths = array(
    dirname(dirname(__DIR__)) . '/lupopedia-config.php',
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/../lupopedia-config.php',
);

$config_loaded = false;
foreach ($config_paths as $config_path) {
    if (file_exists($config_path)) {
        require_once $config_path;
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'error' => 'Config file not found'));
    exit;
}

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}

if (file_exists(LUPOPEDIA_PATH . '/includes/bootstrap.php')) {
    require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';
}

$groups = array();
if (isset($GLOBALS['lupo_collection_tabs_service']) && $GLOBALS['lupo_collection_tabs_service'] !== null) {
    $svc = $GLOBALS['lupo_collection_tabs_service'];
    if (method_exists($svc, 'getNavMenuCollectionsGrouped')) {
        $groups = $svc->getNavMenuCollectionsGrouped();
    }
}

header('Content-Type: application/json');
echo json_encode(array(
    'success' => true,
    'groups' => is_array($groups) ? $groups : array(),
));
