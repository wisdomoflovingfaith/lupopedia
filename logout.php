<?php
/**
 * Logout handler
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', LupopediaConfigResolver::publicPathFromRequest(LUPOPEDIA_PATH));
}
$configPath = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
$configLoaded = false;
if ($configPath !== null && is_file($configPath)) {
    require_once $configPath;
    $configLoaded = true;
}
if (!$configLoaded) {
    $legacy = array(
        __DIR__ . '/lupopedia-config.php',
        __DIR__ . '/../lupopedia-config.php',
        dirname(__DIR__) . '/lupopedia-config.php',
        __DIR__ . '/config.php',
        __DIR__ . '/../config.php',
    );
    foreach ($legacy as $p) {
        if (file_exists($p)) {
            require_once $p;
            $configLoaded = true;
            break;
        }
    }
}

if (!$configLoaded) {
    die('Configuration file not found. Please ensure lupopedia-config.php exists.');
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load required classes
require_once __DIR__ . '/includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/includes/classes/AuthService.php';

$authService = new AuthService();
$authService->logout();

$lupoLogin = (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '') . '/login.php';
$loginUrlEsc = htmlspecialchars($lupoLogin, ENT_QUOTES, 'UTF-8');
$loginUrlJs = json_encode($lupoLogin);

// Clear admin intro sessionStorage (same tab survives PHP logout; intro would otherwise skip).
header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Logging out</title>';
echo '<meta http-equiv="refresh" content="0;url=' . $loginUrlEsc . '">';
echo '</head><body>';
echo '<script>try{sessionStorage.removeItem("lupo_admin_scroll_intro_v1");}catch(e){}';
echo 'window.location.replace(' . $loginUrlJs . ');</script>';
echo '<p><a href="' . $loginUrlEsc . '">Continue to login</a></p>';
echo '</body></html>';
exit;
