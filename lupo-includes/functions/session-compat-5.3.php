<?php
/**
 * PHP 5.3 compatibility: session_status() and PHP_SESSION_* constants (added in PHP 5.4).
 * Required by Session.php, auth-helpers.php, auth-controller.php. Load this before any session_status() use.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
if (!function_exists('session_status')) {
    if (!defined('PHP_SESSION_DISABLED')) {
        define('PHP_SESSION_DISABLED', 0);
    }
    if (!defined('PHP_SESSION_NONE')) {
        define('PHP_SESSION_NONE', 1);
    }
    if (!defined('PHP_SESSION_ACTIVE')) {
        define('PHP_SESSION_ACTIVE', 2);
    }
    function session_status() {
        $id = session_id();
        return ($id === '' || $id === false) ? PHP_SESSION_NONE : PHP_SESSION_ACTIVE;
    }
}
