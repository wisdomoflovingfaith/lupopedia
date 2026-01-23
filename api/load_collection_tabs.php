<?php
/**
 * API endpoint to load collection tabs
 * 
 * Returns tabs data for a given collection_id in JSON format.
 * Used by AJAX calls to populate the tab navigation bar.
 */

// Load config
$config_paths = [
    dirname(dirname(__DIR__)) . '/lupopedia-config.php',
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/../lupopedia-config.php'
];

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
    echo json_encode(['success' => false, 'error' => 'Config file not found']);
    exit;
}

// Load bootstrap
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php';
}

// Load collection tabs loader
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/functions/collection-tabs-loader.php')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/collection-tabs-loader.php';
}

// Get collection_id from request
$collection_id = isset($_GET['collection_id']) ? (int)$_GET['collection_id'] : null;

if ($collection_id === null || $collection_id <= 0) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid collection_id']);
    exit;
}

// Load tabs data
$tabs_data = [];
$current_collection = null;

if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
    $tabs_data = load_collection_tabs($collection_id);
    $current_collection = get_collection_name($collection_id);
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'collection_id' => $collection_id,
    'collection_name' => $current_collection,
    'tabs_data' => $tabs_data
]);
