<?php
/**
 * file_path_from_root: lupo-includes/modules/auth/oauth_controller.php
 * file.last_modified_system_version: 4.0.29
 * file.last_modified_utc: 20260223145600
 * file.created_by_agent: warp
 * file.purpose: OAuth route controller for Google and GitHub login
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. oauth_controller.php cannot be called directly.");
}

/**
 * OAuth Controller
 *
 * Routes:
 *   auth/google          — redirect to Google OAuth consent
 *   auth/google/callback — handle Google OAuth callback
 *   auth/github          — redirect to GitHub OAuth consent
 *   auth/github/callback — handle GitHub OAuth callback
 *
 * Delegates to App\Services\OAuthService for the actual OAuth2 flow.
 *
 * @package Lupopedia
 * @subpackage Modules
 */

/**
 * Handle OAuth-related slugs.
 *
 * @param string $slug The URL slug (already lowercase, trimmed)
 * @return string HTML output or empty string if not an OAuth route
 */
function oauth_handle_slug($slug)
{
    // Only handle auth/ prefixed routes
    if (strpos($slug, 'auth/') !== 0) {
        return '';
    }

    // Ensure OAuthService is loaded
    $oauthServicePath = defined('LUPOPEDIA_ABSPATH')
        ? LUPOPEDIA_ABSPATH . 'app/Services/OAuthService.php'
        : dirname(dirname(dirname(__DIR__))) . '/app/Services/OAuthService.php';

    if (!class_exists('App\\Services\\OAuthService')) {
        if (is_file($oauthServicePath)) {
            require_once $oauthServicePath;
        } else {
            return _oauth_error_page('OAuth service not available.');
        }
    }

    if (!isset($GLOBALS['mydatabase'])) {
        return _oauth_error_page('Database connection not available.');
    }

    $db = $GLOBALS['mydatabase'];
    $oauthService = new \App\Services\OAuthService($db);

    // Parse the route
    $parts = explode('/', $slug);
    // $parts[0] = 'auth', $parts[1] = provider, $parts[2] = 'callback' (optional)
    $provider = isset($parts[1]) ? strtolower($parts[1]) : '';
    $isCallback = isset($parts[2]) && $parts[2] === 'callback';

    // Validate provider
    $allowedProviders = array('google', 'github');
    if (!in_array($provider, $allowedProviders)) {
        return _oauth_error_page('Unknown OAuth provider: ' . htmlspecialchars($provider));
    }

    if ($isCallback) {
        return _oauth_handle_callback($oauthService, $provider);
    } else {
        return _oauth_handle_redirect($oauthService, $provider);
    }
}

/**
 * Redirect user to the OAuth provider's consent screen.
 *
 * @param \App\Services\OAuthService $oauthService
 * @param string $provider 'google' or 'github'
 * @return string Empty on success (redirects), or error HTML
 */
function _oauth_handle_redirect($oauthService, $provider)
{
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Store redirect target for after login
    $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : $default_redirect;
    $_SESSION['oauth_login_redirect'] = $redirect;

    // Build callback URL
    $callbackUrl = _oauth_build_callback_url($provider);

    // Generate CSRF state token
    $state = $oauthService->generateStateToken();

    // Get authorization URL
    $authUrl = $oauthService->getAuthorizationUrl($provider, $callbackUrl, $state);
    if (!$authUrl) {
        return _oauth_error_page('OAuth provider "' . htmlspecialchars($provider) . '" is not configured. Please set client_id and client_secret in lupo_auth_providers.');
    }

    // Redirect to provider
    if (!headers_sent()) {
        header('Location: ' . $authUrl);
        exit;
    }

    // Fallback: JS redirect
    return '<html><head><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($authUrl) . '"></head><body>Redirecting to ' . htmlspecialchars($provider) . '...</body></html>';
}

/**
 * Handle OAuth callback from provider.
 *
 * @param \App\Services\OAuthService $oauthService
 * @param string $provider 'google' or 'github'
 * @return string HTML output or empty on redirect
 */
function _oauth_handle_callback($oauthService, $provider)
{
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check for error from provider
    if (isset($_GET['error'])) {
        $errorMsg = isset($_GET['error_description']) ? $_GET['error_description'] : $_GET['error'];
        return _oauth_error_page('OAuth error from ' . htmlspecialchars($provider) . ': ' . htmlspecialchars($errorMsg));
    }

    // Validate required params
    $code = isset($_GET['code']) ? $_GET['code'] : '';
    $state = isset($_GET['state']) ? $_GET['state'] : '';

    if ($code === '' || $state === '') {
        return _oauth_error_page('Missing authorization code or state parameter.');
    }

    // Validate CSRF state token
    if (!$oauthService->validateStateToken($state)) {
        return _oauth_error_page('Invalid state token. Please try logging in again.');
    }

    // Build callback URL (must match what was sent in the authorization request)
    $callbackUrl = _oauth_build_callback_url($provider);

    // Exchange code for user info
    $oauthUser = $oauthService->handleCallback($provider, $code, $callbackUrl);
    if (!$oauthUser) {
        return _oauth_error_page('Failed to authenticate with ' . htmlspecialchars($provider) . '. Please try again.');
    }

    // Find or create user + actor
    $result = $oauthService->findOrCreateOAuthUser($oauthUser);
    if (!$result || !isset($result['actor_id']) || !$result['actor_id']) {
        return _oauth_error_page('Failed to create or link user account. Please try again.');
    }

    $actorId = (int) $result['actor_id'];

    // Create session (OOP Session)
    $sessionId = $session ? $session->createSession($actorId, 'oauth', $provider) : false;
    if (!$sessionId) {
        return _oauth_error_page('Session creation failed. Please try again.');
    }

    // Get redirect target
    $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
    $redirect = isset($_SESSION['oauth_login_redirect']) ? $_SESSION['oauth_login_redirect'] : $default_redirect;
    unset($_SESSION['oauth_login_redirect']);

    // If no meaningful redirect, go to QA landing
    $site_root = $default_redirect;
    $qa_landing = (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '')
        ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/qa/lupopedia'
        : '/qa/lupopedia';
    if ($redirect === $site_root || rtrim($redirect, '/') === rtrim($site_root, '/')) {
        $redirect = $qa_landing;
    }

    if (function_exists('lupo_safe_redirect')) {
        lupo_safe_redirect($redirect, 2, 'OAuth login successful! Redirecting...');
    } else {
        if (!headers_sent()) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    return '';
}

/**
 * Build the full callback URL for a provider.
 *
 * @param string $provider
 * @return string
 */
function _oauth_build_callback_url($provider)
{
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $publicPath = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    return $scheme . '://' . $host . $publicPath . '/auth/' . $provider . '/callback';
}

/**
 * Render a simple OAuth error page.
 *
 * @param string $message
 * @return string HTML
 */
function _oauth_error_page($message)
{
    $loginUrl = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
    return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAuth Error - Lupopedia</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .error-container { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 40px; width: 100%; max-width: 500px; text-align: center; }
        h1 { color: #c33; font-size: 20px; margin-bottom: 15px; }
        p { color: #666; font-size: 14px; margin-bottom: 20px; }
        .error-detail { background: #fee; border: 1px solid #fcc; color: #c33; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 13px; text-align: left; }
        a { color: #4a90e2; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Authentication Error</h1>
        <div class="error-detail">' . htmlspecialchars($message) . '</div>
        <p><a href="' . htmlspecialchars($loginUrl) . '">Return to Login</a></p>
    </div>
</body>
</html>';
}

/*
 * flip.footer:
 *   referenced_by:
 *     - lupo-includes/modules/auth/auth-controller.php
 *     - lupo-includes/modules/module-loader.php
 *   consumed_by_services:
 *     - App\Services\OAuthService
 *   cited_by_docs:
 *     - docs/directives/channel_42_broadcast.md
 *   related_toons:
 *     - docs/toons/lupo_auth_providers.toon.json
 *     - docs/toons/lupo_auth_users.toon.json
 *   channels:
 *     - 42
 */
