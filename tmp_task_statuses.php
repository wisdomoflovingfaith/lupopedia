<?php
require_once __DIR__ . '/lupopedia-config.php';
require_once __DIR__ . '/lupo-includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

$statuses = $db->fetchAll(
    "SELECT task_status, COUNT(*) as count FROM {$prefix}tasks GROUP BY task_status"
);

echo json_encode($statuses, JSON_PRETTY_PRINT);
