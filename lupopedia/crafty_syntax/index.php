<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/routes.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'operator';
$action = isset($_GET['action']) ? $_GET['action'] : 'overview';

lupo_crafty_route($page, $action);
