<?php
/**
 * Semantic Navbar integration entry point (4.0.71)
 *
 * Canonical PHP integration for the semantic floating navbar.
 * Delegates to semantic-navbar-js.php for JS output.
 * Exists to satisfy references to lupopedia/nav/semantic_navbar.php;
 * URL route nav/semantic-navbar or nav/semantic-navbar-js both use semantic-navbar-js.php.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

$app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : dirname(dirname(dirname(__DIR__))));
$js_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'nav' . DIRECTORY_SEPARATOR . 'semantic-navbar-js.php';
if (file_exists($js_path)) {
    require $js_path;
    return;
}
http_response_code(503);
header('Content-Type: text/plain; charset=utf-8');
echo 'Semantic navbar generator unavailable.';
