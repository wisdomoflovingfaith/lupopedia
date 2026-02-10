<?php
/**
 * Session helpers — DEPRECATED: logic migrated to App\Auth\Session
 *
 * All session logic now lives in App\Auth\Session (app/auth/Session.php).
 * Bootstrap creates one instance as $GLOBALS['lupo_session'].
 *
 * Use: $session = $GLOBALS['lupo_session'] ?? new \App\Auth\Session($db, new \App\Auth\UnifiedSessionHandler($db));
 * Then: $session->start(), $session->getSessionId(), $session->validateSession(), $session->createSession(), etc.
 *
 * No procedural session functions. Do not add new helpers here.
 *
 * @package Lupopedia
 * @deprecated Use App\Auth\Session
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. session-helpers.php cannot be called directly.");
}

// No functions. Session class is loaded and instantiated in bootstrap.
