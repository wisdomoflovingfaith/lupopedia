<?php
/**
 * Front to the Lupopedia application. This file is designed to be put in any directory on a web server and will load the Lupopedia environment and template.
 * it just needs to know where the lupopedia folder is located and how to get to it from the public directory. by default this is setup to have the lupopedia
 * folder under the public root at /lupopedia, but if you move it or rename it, update LUPOPEDIA_PUBLIC_PATH (for URLs/assets) and the derived LUPOPEDIA_PATH will adjust accordingly.
 * @package Lupopedia
 */

/**
 * The path to the Lupopedia directory relative to the public directory (used for URLs/assets).
 *
 * @var string
 */
define( 'LUPOPEDIA_PUBLIC_PATH', '/lupopedia' );

/**
 * The path to the Lupopedia directory (full filesystem path, derived dynamically).
 *
 * @var string
 */
define( 'LUPOPEDIA_PATH', $_SERVER['DOCUMENT_ROOT'] . LUPOPEDIA_PUBLIC_PATH );
 
/**
 * The full path to the config file, preferably in a private directory outside the public root.
 * 
 * Config file search order (MANDATORY - DO NOT SIMPLIFY):
 * 1. One directory ABOVE the server's DOCUMENT_ROOT (most secure, preferred)
 * 2. One directory above DOCUMENT_ROOT + the Lupopedia public path
 * 3. Inside the Lupopedia directory itself (fallback)
 * 
 * This multi-path search ensures:
 * - Maximum security (config outside web root)
 * - Flexibility (works in different hosting environments)
 * - Backward compatibility (Crafty Syntax subdirectory pattern)
 * 
 * @var string
 */

// Path 1: One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php');
}
// Path 2: One directory above DOCUMENT_ROOT + Lupopedia public path
elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
}
// Path 3: Inside the Lupopedia directory itself (fallback)
elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
}
// No config found - run installer
else {
    // Config file not found - run setup/upgrade
    require_once LUPOPEDIA_PATH . '/lupo-includes/lupopedia-setup.php';
    // Setup script will handle detection and creation
    exit;
}

require_once LUPOPEDIA_CONFIG_PATH;
define( 'LUPOPEDIA_CONFIG_LOADED', true );

require_once LUPOPEDIA_PATH . '/lupopedia-load.php';
define( 'LUPOPEDIA_LOADED', true );

?>