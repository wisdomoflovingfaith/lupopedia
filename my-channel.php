<?php
/**
 * Entry point: My Channels. Redirects to the canonical route /channels/my-channels
 * so the router (index.php) serves the My Channels list. Kept for backward compatibility
 * and direct links (e.g. https://localhost/lupopedia/my-channel.php).
 *
 * @package Lupopedia
 */

define('LUPOPEDIA_PATH', __DIR__);
$publicPath = '/' . basename(__DIR__);

$lupopediaConfigPath = null;
if (isset($_SERVER['DOCUMENT_ROOT']) && file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
} elseif (isset($_SERVER['DOCUMENT_ROOT']) && file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . $publicPath . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . $publicPath . '/lupopedia-config.php';
} elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = LUPOPEDIA_PATH . '/lupopedia-config.php';
}

if ($lupopediaConfigPath !== null) {
    require_once $lupopediaConfigPath;
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : $publicPath;
} else {
    $base = $publicPath;
}

$redirect = rtrim($base, '/') . '/channels/my-channels';
header('Location: ' . $redirect);
exit;
