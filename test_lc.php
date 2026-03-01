<?php
require_once 'lupopedia-config.php';
require_once 'lupo-bin/channel_startup_lifecycle.php';

$lc = new ChannelStartupLifecycle();
$id = $lc->startLifecycle(0, 'test_session', 'test', array(0));
$db = DatabaseFactory::getConnection();
echo "Last Insert ID: " . $db->lastInsertId() . "\n";
echo "Last Error: " . $db->getLastError() . "\n";
if ($id) {
    echo "Success: ID $id\n";
} else {
    echo "Failed\n";
    print_r($lc->getErrors());
}
