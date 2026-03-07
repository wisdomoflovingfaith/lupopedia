<?php
define('LUPOPEDIA_CONFIG_LOADED', true);
require_once __DIR__ . '/lupopedia-config.php';
require_once __DIR__ . '/lupo-includes/modules/actors/agent-www-controller.php';

// Mock some globals for direct testing
$db = DatabaseFactory::getConnection();
$GLOBALS['mydatabase'] = $db;

$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : ABSPATH;
$www_dir = $app_root . 'lupo-actors/wolfie/www';

if (!is_dir($www_dir)) {
    mkdir($www_dir, 0777, true);
}

// Case 1: Only readme.md exists
@unlink($www_dir . '/index.htm');
@unlink($www_dir . '/index.html');
@unlink($www_dir . '/index.php');
file_put_contents($www_dir . '/readme.md', "# Welcome to Wolfie's Den\n\nThis is a test of the actor WWW system.");

echo "Testing Case 1 (only readme.md)...\n";
// Temporarily bypass render_main_layout to see raw body
function render_main_layout_mock($context)
{
    return "BODY_DEBUG_START[" . $context['page_body'] . "]BODY_DEBUG_END";
}

// We can't redefine functions, so we'll just check if agent_www_handle_request calls it.
// Actually, let's just make sure render_markdown is working.

if (function_exists('render_markdown')) {
    echo "render_markdown() exists.\n";
    $test_md = "# Title";
    echo "MD Test: " . render_markdown($test_md) . "\n";
} else {
    echo "render_markdown() DOES NOT EXIST.\n";
}

$output = agent_www_handle_request('wolfie', '');
if (strpos($output, "<h1>Welcome to Wolfie") !== false) {
    echo "SUCCESS: Found H1 in output.\n";
} else {
    echo "FAIL: Expected H1 not found. Output length: " . strlen($output) . "\n";
    // echo substr($output, 0, 1000) . "\n";
}

// Case 2: index.htm added (readme.md should still win)
file_put_contents($www_dir . '/index.htm', "<h1>Index HTM</h1>");
echo "Testing Case 2 (readme.md vs index.htm)...\n";
$output = agent_www_handle_request('wolfie', '');
if (strpos($output, "Welcome to Wolfie") !== false) {
    echo "SUCCESS: readme.md won.\n";
} else {
    echo "FAIL: Expected readme.md to win. (Maybe index.htm won or something went wrong?)\n";
    if (strpos($output, "Index HTM") !== false) {
        echo " - Actually index.htm won.\n";
    }
}
