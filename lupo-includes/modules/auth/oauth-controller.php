<?php
/**
 * OAuth Authentication Controller
 * 
 * Handles OAuth 2.0 authentication callbacks and redirects.
 * Integrates with OAuthService for provider communication.
 * 
 * @package Lupopedia
 * @subpackage Auth
 * @version 4.0.31
 * @x_lupo_forwarded 1001:10000
 */

// Define LUPOPEDIA_PATH if not already defined
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__FILE__) . '/');
}

// Cross-platform path construction
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', str_replace('\\', '/', dirname(__FILE__)));
}

require_once LUPOPEDIA_ABSPATH . 'app/Services/OAuthService.php';

/**
 * Initiate OAuth login flow
 * 
 * @param string $provider Provider name (google, github)
 * @return void Redirects to OAuth provider
 */
function oauth_login_initiate($provider) {
    $db = lupo_get_db();
    $oauthService = new \App\Services\OAuthService($db);
    
    // Validate provider
    if (!$oauthService->isProviderConfigured($provider)) {
        $_SESSION['login_error'] = 'OAuth provider not configured: ' . htmlspecialchars($provider);
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Generate CSRF state token
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;
    $_SESSION['oauth_provider'] = $provider;
    
    // Store redirect URL if provided
    if (isset($_GET['redirect'])) {
        $_SESSION['oauth_redirect'] = $_GET['redirect'];
    }
    
    // Build callback URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $redirectUri = $protocol . '://' . $host . LUPOPEDIA_PUBLIC_PATH . '/oauth/callback/' . $provider;
    
    // Get authorization URL
    $authUrl = $oauthService->getAuthorizationUrl($provider, $redirectUri, $state);
    
    if (!$authUrl) {
        $_SESSION['login_error'] = 'Failed to generate OAuth URL';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Redirect to OAuth provider
    header('Location: ' . $authUrl);
    exit;
}

/**
 * Handle OAuth callback
 * 
 * @param string $provider Provider name
 * @return void Redirects after processing
 */
function oauth_callback_handle($provider) {
    $db = lupo_get_db();
    $oauthService = new \App\Services\OAuthService($db);
    
    // Verify state token (CSRF protection)
    if (!isset($_GET['state']) || !isset($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
        $_SESSION['login_error'] = 'Invalid OAuth state token';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Verify provider matches
    if (!isset($_SESSION['oauth_provider']) || $_SESSION['oauth_provider'] !== $provider) {
        $_SESSION['login_error'] = 'OAuth provider mismatch';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Check for error from provider
    if (isset($_GET['error'])) {
        $errorDesc = isset($_GET['error_description']) ? $_GET['error_description'] : $_GET['error'];
        $_SESSION['login_error'] = 'OAuth error: ' . htmlspecialchars($errorDesc);
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Get authorization code
    if (!isset($_GET['code'])) {
        $_SESSION['login_error'] = 'No authorization code received';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    $code = $_GET['code'];
    
    // Build callback URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $redirectUri = $protocol . '://' . $host . LUPOPEDIA_PUBLIC_PATH . '/oauth/callback/' . $provider;
    
    // Exchange code for token
    $tokenData = $oauthService->exchangeCodeForToken($provider, $code, $redirectUri);
    
    if (!$tokenData || !isset($tokenData['access_token'])) {
        $_SESSION['login_error'] = 'Failed to exchange authorization code';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Get user info from provider
    $userData = $oauthService->getUserInfo($provider, $tokenData['access_token']);
    
    if (!$userData || empty($userData['provider_id'])) {
        $_SESSION['login_error'] = 'Failed to retrieve user information';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Find or create user
    $user = $oauthService->findOrCreateUser($userData);
    
    if (!$user) {
        $_SESSION['login_error'] = 'Failed to create user account';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Check if user is active
    if (!$user['is_active']) {
        $_SESSION['login_error'] = 'Account is inactive';
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/login');
        exit;
    }
    
    // Create session
    $_SESSION['user_id'] = $user['auth_user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['display_name'] = $user['display_name'];
    $_SESSION['auth_provider'] = $provider;
    
    // Clean up OAuth session data
    unset($_SESSION['oauth_state']);
    unset($_SESSION['oauth_provider']);
    
    // Determine redirect URL
    $redirectUrl = LUPOPEDIA_PUBLIC_PATH . '/admin';
    
    if (isset($_SESSION['oauth_redirect'])) {
        $redirectUrl = $_SESSION['oauth_redirect'];
        unset($_SESSION['oauth_redirect']);
    }
    
    // Redirect to destination
    header('Location: ' . $redirectUrl);
    exit;
}

/**
 * Route OAuth requests
 * 
 * @param string $slug URL slug
 * @return void
 */
function oauth_route_request($slug) {
    // Parse OAuth routes
    // /oauth/login/google
    // /oauth/login/github
    // /oauth/callback/google
    // /oauth/callback/github
    
    $parts = explode('/', trim($slug, '/'));
    
    if (count($parts) < 3 || $parts[0] !== 'oauth') {
        return;
    }
    
    $action = $parts[1];
    $provider = $parts[2];
    
    if ($action === 'login') {
        oauth_login_initiate($provider);
    } elseif ($action === 'callback') {
        oauth_callback_handle($provider);
    }
}
