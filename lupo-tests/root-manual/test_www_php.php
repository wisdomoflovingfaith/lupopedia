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

// Only index.php exists
@unlink($www_dir . '/index.htm');
@unlink($www_dir . '/index.html');
@unlink($www_dir . '/readme.md');
file_put_contents($www_dir . '/index.php', "<?php echo \"PHPTarget_\" . (2+2); ?>");

echo "Testing index.php execution...\n";
$output = agent_www_handle_request('wolfie', '');
if (strpos($output, "PHPTarget_4") !== false) {
    echo "SUCCESS: index.php executed correctly.\n";
} else {
    echo "FAIL: Expected output not found in output. Length: " . strlen($output) . "\n";
    // echo $output;
}
