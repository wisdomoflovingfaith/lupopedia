<?php
/**
 * Lupopedia config for Laravel/app usage.
 * Table prefix MUST match lupopedia-config.php (LUPO_TABLE_PREFIX).
 * Loads lupopedia-config.php if not already defined so one source of truth.
 */

$configPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!defined('LUPO_TABLE_PREFIX') && is_file($configPath)) {
    require_once $configPath;
}

return [
    'table_prefix' => defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_',
];
