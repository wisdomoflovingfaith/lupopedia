<?php
define('LUPOPEDIA_CONFIG_LOADED', true);
require_once __DIR__ . '/lupopedia-config.php';
echo "LUPOPEDIA_PATH: " . (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : 'NOT DEFINED') . "\n";
echo "ABSPATH: " . (defined('ABSPATH') ? ABSPATH : 'NOT DEFINED') . "\n";
require_once __DIR__ . '/includes/modules/actors/agent-www-controller.php';

// Create a test file in wolfie/www
$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : ABSPATH;
$www_dir = $app_root . 'actors/1/www';
if (!is_dir($www_dir)) {
    mkdir($www_dir, 0777, true);
}
file_put_contents($www_dir . '/readme.md', "# Welcome to Wolfie's Den\n\nThis is a test of the actor WWW system.");

echo "Testing agent_www_handle_request('wolfie', '')...\n";
$db = DatabaseFactory::getConnection();
$GLOBALS['mydatabase'] = $db;
require_once __DIR__ . '/database/lupopedia/content/app/Services/ActorService.php';
$service = new \App\Services\ActorService($db);
$actor = $service->getActorByName('wolfie');
echo "Actor lookup ('wolfie'): " . ($actor ? "FOUND (ID: " . $actor['actor_id'] . ")" : "NOT FOUND") . "\n";

$output = agent_www_handle_request('wolfie', '');
echo "DEBUG OUTPUT (len=" . strlen($output) . "): " . substr($output, 0, 100) . "\n";
if (strpos($output, "Welcome to Wolfie's Den") !== false) {
    echo "SUCCESS: Markdown content rendered.\n";
} else {
    echo "FAIL: Markdown content not found in output.\n";
    // echo $output;
}

// Test index.htm priority
file_put_contents($www_dir . '/index.htm', "<h1>Wolfie HTML</h1>");
echo "Testing agent_www_handle_request('wolfie', '') with index.htm [readme.md should still win]...\n";
$output = agent_www_handle_request('wolfie', '');
if (strpos($output, "Welcome to Wolfie's Den") !== false) {
    echo "SUCCESS: readme.md still prioritized over index.htm.\n";
} else {
    echo "FAIL: Expected readme.md to win.\n";
}

// Test specific file
echo "Testing agent_www_handle_request('wolfie', 'index.htm')...\n";
$output = agent_www_handle_request('wolfie', 'index.htm');
if (strpos($output, "Wolfie HTML") !== false) {
    echo "SUCCESS: index.htm rendered.\n";
} else {
    echo "FAIL: index.htm not found in output.\n";
}
