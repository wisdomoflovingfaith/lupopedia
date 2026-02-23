<?php
/**
 * Security helpers for Lupopedia. CSRF token generation and validation for admin actions.
 * PHP 5.3+ compatible; no external libraries.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

/**
 * Get the current CSRF token for the session. Generates and stores one if missing.
 * Requires session to be started (e.g. by auth) before use.
 *
 * @return string CSRF token value
 */
function lupo_get_csrf_token()
{
    if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
        $raw = '';
        if (function_exists('openssl_random_pseudo_bytes')) {
            $raw = openssl_random_pseudo_bytes(32);
            if ($raw === false) {
                $raw = (string) uniqid((string) mt_rand(), true) . (string) microtime(true);
            }
        } else {
            $raw = (string) uniqid((string) mt_rand(), true) . (string) microtime(true);
        }
        $sid = session_id();
        $_SESSION['csrf_token'] = sha1($raw . (is_string($sid) ? $sid : ''));
    }
    return isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
}

/**
 * Require a valid CSRF token from POST or GET. Call at the top of handlers that process
 * state-changing requests (create/update/delete). On failure sends 403 and exits.
 * Does not return on success; caller continues.
 */
function lupo_require_valid_csrf_token()
{
    $submitted = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : (isset($_GET['csrf_token']) ? (string) $_GET['csrf_token'] : '');
    $session_token = isset($_SESSION['csrf_token']) ? (string) $_SESSION['csrf_token'] : '';
    $token_present = ($submitted !== '');
    $token_valid = ($submitted !== '' && $session_token !== '' && $submitted === $session_token);
    $actor_id = 0;
    if (isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service']) && method_exists($GLOBALS['lupo_auth_service'], 'getCurrentUser')) {
        $cu = $GLOBALS['lupo_auth_service']->getCurrentUser();
        if (is_array($cu) && isset($cu['actor_id'])) {
            $actor_id = (int) $cu['actor_id'];
        }
    }

    // Hybrid Actor Security Gate (4.0.29 Centralization)
    if ($actor_id > 0) {
        try {
            if (class_exists('HybridActorSecurityService')) {
                HybridActorSecurityService::assertActorOperational($actor_id, 'csrf_validation');
            }
        } catch (SecurityException $e) {
            header('HTTP/1.0 403 Forbidden');
            echo 'Access denied: Non-operational actor.';
            exit;
        }
    }

    if (defined('LUPOPEDIA_PATH') && file_exists(LUPOPEDIA_PATH . '/lupo-includes/functions/admin_diagnostics.php')) {
        require_once LUPOPEDIA_PATH . '/lupo-includes/functions/admin_diagnostics.php';
    }
    if (function_exists('lupo_diag_csrf')) {
        lupo_diag_csrf($actor_id, $token_present, $token_valid);
    }
    if ($submitted === '' || $session_token === '' || $submitted !== $session_token) {
        if (!headers_sent()) {
            header('HTTP/1.0 403 Forbidden');
            header('Content-Type: text/plain; charset=UTF-8');
        }
        echo 'Invalid or missing CSRF token.';
        exit;
    }
}
