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
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/permissions.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/emotional.php';
require_once __DIR__ . '/includes/expertise.php';
require_once __DIR__ . '/includes/escalation.php';

require_login();
require_operator();
