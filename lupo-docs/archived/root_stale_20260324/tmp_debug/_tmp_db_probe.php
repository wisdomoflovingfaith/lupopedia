<?php
require 'lupopedia-config.php';
require 'lupo-includes/bootstrap.php';
$db = DatabaseFactory::getConnection();
$tables = array('lupo_human_requests','lupo_human_request_responses','lupo_auth_users','lupo_actors');
foreach ($tables as $t) {
    try {
        $c = $db->fetchOne('SELECT COUNT(*) FROM ' . $t);
        echo $t . ': ' . $c . PHP_EOL;
    } catch (Exception $e) {
        echo $t . ': ERROR ' . $e->getMessage() . PHP_EOL;
    }
}
?>
