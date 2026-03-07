<?php
require_once __DIR__ . '/lupopedia-config.php';
require_once __DIR__ . '/lupo-includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$tasks = $db->fetchAll(
    "SELECT * FROM {$prefix}tasks WHERE channel_id = 0 AND task_status = 'pending' AND is_deleted = 0"
);

echo json_encode($tasks, JSON_PRETTY_PRINT);
