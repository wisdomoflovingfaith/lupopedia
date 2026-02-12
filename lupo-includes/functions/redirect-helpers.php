<?php
/**
 * wolfie.header.identity: redirect-helpers
 * wolfie.header.placement: /lupo-includes/functions/redirect-helpers.php
 * wolfie.header.version: 3.0.0
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created safe redirect helper function for version 3.0.9. Handles 'headers already sent' errors by checking if headers are sent before using header() redirect. If headers are already sent, falls back to meta refresh, JavaScript redirect, and clickable link (old-school approach that always works)."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. redirect-helpers.php cannot be called directly.");
}

/**
 * Safe redirect (thin wrapper — App\Support\RedirectUtils).
 *
 * @param string $url URL to redirect to (absolute or relative)
 * @param int $delay Delay in seconds for meta refresh (default: 3)
 * @param string|null $message Optional message to display
 * @return void Exits after redirect
 */
function lupo_safe_redirect($url, $delay = 3, $message = null) {
    if (class_exists('App\Support\RedirectUtils')) {
        \App\Support\RedirectUtils::safeRedirect((string) $url, (int) $delay, $message);
        return;
    }
    if (strpos($url, 'http') !== 0 && defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '/') {
        $url = (strpos($url, '/') === 0) ? (LUPOPEDIA_PUBLIC_PATH . $url) : (LUPOPEDIA_PUBLIC_PATH . '/' . ltrim($url, '/'));
    } elseif (strpos($url, 'http') !== 0 && strpos($url, '/') !== 0) {
        $url = '/' . ltrim($url, '/');
    }
    $url = filter_var($url, FILTER_SANITIZE_URL) ?: (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/');
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    }
    $msg = $message ?: 'Redirecting...';
    echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="' . (int)$delay . ';url=' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"></head><body><p>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">Click here</a></body></html>';
    exit;
}
