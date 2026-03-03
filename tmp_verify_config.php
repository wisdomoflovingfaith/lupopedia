<?php
define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_ABSPATH', __DIR__ . '/');
require_once __DIR__ . '/lupopedia-config.php';

echo "CONFIG LOADED\n";
echo "LUPOPEDIA_VERSION: " . (defined('LUPOPEDIA_VERSION') ? LUPOPEDIA_VERSION : 'NOT DEFINED') . "\n";
echo "LUPOPEDIA_CONFIG_LOADED: " . (defined('LUPOPEDIA_CONFIG_LOADED') ? 'YES' : 'NO') . "\n";

if (isset($GLOBALS['lupo_atom_loader'])) {
    echo "ATOM LOADER: CREATED\n";
    $loader = $GLOBALS['lupo_atom_loader'];
    // Reflection to check configDir since it's private
    $ref = new ReflectionClass($loader);
    $prop = $ref->getProperty('configDir');
    $prop->setAccessible(true);
    echo "CONFIG DIR: " . $prop->getValue($loader) . "\n";
} else {
    echo "ATOM LOADER: NOT CREATED\n";
}
