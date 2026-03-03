<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_ABSPATH', __DIR__ . '/');

// Manually load AtomLoader
$loader_file = __DIR__ . '/lupo-database/lupopedia/content/lupo-app/Support/AtomLoader.php';
if (file_exists($loader_file)) {
    require_once $loader_file;
    echo "ATOM LOADER FILE FOUND\n";
    $loader = new \App\Support\AtomLoader();
    echo "ATOM LOADER INSTANTIATED\n";

    // Check version
    $version = $loader->getLupopediaVersion();
    echo "VERSION FROM LOADER: " . $version . "\n";

    // Check config dir via reflection
    $ref = new ReflectionClass($loader);
    $prop = $ref->getProperty('configDir');
    $prop->setAccessible(true);
    echo "CONFIG DIR: " . $prop->getValue($loader) . "\n";
} else {
    echo "ATOM LOADER FILE NOT FOUND at $loader_file\n";
}
