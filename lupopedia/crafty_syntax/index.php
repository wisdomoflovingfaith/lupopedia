<?php
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(LUPOPEDIA_PATH));
}

if (!defined('LUPOPEDIA_CONFIG_PATH')) {
    if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php');
    } elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
    } elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
    }
}

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    if (!defined('LUPOPEDIA_CONFIG_PATH') || !file_exists(LUPOPEDIA_CONFIG_PATH)) {
        header('HTTP/1.1 500 Internal Server Error');
        echo "Config not loaded.";
        exit;
    }
    require_once LUPOPEDIA_CONFIG_PATH;
}

require_once LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php';

$user = current_user();
if (!$user) {
    $return_to = $_SERVER['REQUEST_URI'] ?? (LUPOPEDIA_PUBLIC_PATH . '/crafty_syntax/');
    $_SESSION['return_to'] = $return_to;
    $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
    header('Location: ' . $login_url);
    exit;
}

$operator_id = lupo_get_operator_id_from_auth_user_id($user['auth_user_id']);
if (!$operator_id) {
    echo "Access denied\n";
    exit;
}

echo "Operator Console\n";
echo "next step here\n";
