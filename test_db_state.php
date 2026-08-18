<?php
require_once __DIR__ . '/includes/classes/LupopediaConfigResolver.php';
$lupoCfgPath = LupopediaConfigResolver::resolve(__DIR__, LupopediaConfigResolver::publicPathFromRequest(__DIR__));
require_once $lupoCfgPath;
require_once __DIR__ . '/includes/classes/DatabaseFactory.php';

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$output = [];
$output['auth_user_10000'] = $db->fetchRow("SELECT * FROM {$prefix}auth_users WHERE auth_user_id = 10000");
$output['actor_10000'] = $db->fetchRow("SELECT * FROM {$prefix}actors WHERE actor_id = 10000");
$output['actor_1'] = $db->fetchRow("SELECT * FROM {$prefix}actors WHERE actor_id = 1");
$output['aau_10000'] = $db->fetchAll("SELECT * FROM {$prefix}actor_auth_users WHERE auth_user_id = 10000");

echo json_encode($output, JSON_PRETTY_PRINT);
