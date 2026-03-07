<?php
/**
 * Antigravity context access — auth user and actor for conflict resolution.
 * Include this when Antigravity (actor_id 42) needs current auth/actor context.
 * Sets $GLOBALS['antigravity_context'] (AntigravityContext instance).
 *
 * @package Lupopedia\Antigravity
 * @version 4.0.61
 */
$root = dirname(dirname(dirname(__FILE__)));
if (!defined('ABSPATH')) {
    define('ABSPATH', $root . '/');
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', ABSPATH);
}
if (!file_exists($root . '/lupopedia-config.php')) {
    return;
}
require_once $root . '/lupopedia-config.php';
require_once $root . '/lupo-includes/classes/ContextKernel.php';
require_once $root . '/lupo-includes/classes/AntigravityContext.php';

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$state_file = $root . '/.lupo_actor';

$kernel = ContextKernel::getInstance();
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
$kernel->bootstrap($db, $table_prefix, $state_file, $root, $authService);

$GLOBALS['antigravity_context'] = new AntigravityContext(null, $authService);
