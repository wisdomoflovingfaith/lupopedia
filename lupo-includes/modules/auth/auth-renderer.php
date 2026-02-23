<?php
/**
 * wolfie.header.identity: auth-renderer
 * wolfie.header.placement: /lupo-includes/modules/auth/auth-renderer.php
 * wolfie.header.version: 3.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Refreshed login form page for version 3.1.1. Updated WOLFIE header version to current ecosystem version. Login form continues to use email-only authentication with proper email input type and autocomplete attributes."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-renderer.php cannot be called directly.");
}

/**
 * Authentication Renderer
 * 
 * Provides HTML rendering functions for authentication UI:
 * - Login form
 * - Admin dashboard
 * 
 * @package Lupopedia
 * @subpackage Modules
 */

/**
 * Render login form
 * 
 * @param string|null $error_message Error message to display (if any)
 * @param string $redirect_url URL to redirect to after successful login
 * @return string Rendered login form HTML
 */
function login_form($error_message = null, $redirect_url = '/admin') {
    // Debug output in development mode
    $debug_output = '';
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG && ini_get('display_errors')) {
        $debug_output = '<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 4px; font-family: monospace; font-size: 12px;">';
        $debug_output .= '<strong>DEBUG MODE:</strong><br>';
        $debug_output .= 'Session ID: ' . (session_id() ?: 'NOT SET') . '<br>';
        $debug_output .= 'Table Prefix: ' . (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'NOT DEFINED') . '<br>';
        $debug_output .= 'Database Connected: ' . (isset($GLOBALS['mydatabase']) ? 'YES' : 'NO') . '<br>';
        if (isset($GLOBALS['mydatabase'])) {
            try {
                $db = $GLOBALS['mydatabase'];
                $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);
                $test_sql = "SELECT COUNT(*) as count FROM {$table_prefix}sessions";
                $test_stmt = $db->prepare($test_sql);
                $test_stmt->execute();
                $debug_output .= 'Sessions Table Accessible: YES<br>';
            } catch (Exception $e) {
                $debug_output .= 'Sessions Table Error: ' . htmlspecialchars($e->getMessage()) . '<br>';
            }
        }
        $debug_output .= '</div>';
    }
    
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lupopedia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #4a90e2;
        }
        .submit-button {
            width: 100%;
            padding: 12px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .submit-button:hover {
            background: #357abd;
        }
        .submit-button:active {
            background: #2a5f8f;
        }
        .oauth-divider {
            display: flex;
            align-items: center;
            margin: 24px 0 20px;
            color: #999;
            font-size: 13px;
        }
        .oauth-divider::before,
        .oauth-divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        .oauth-divider span {
            padding: 0 12px;
        }
        .oauth-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .oauth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
        }
        .oauth-btn:hover {
            background: #f8f8f8;
            border-color: #bbb;
            text-decoration: none;
        }
        .oauth-btn svg {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            flex-shrink: 0;
        }
        .oauth-btn-google {
            border-color: #dadce0;
        }
        .oauth-btn-github {
            border-color: #d0d7de;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Lupopedia</h1>
            <p>Sign in to your account</p>
        </div>';
    
    // Show debug output in development mode
    if (!empty($debug_output)) {
        $html .= $debug_output;
    }
    
    // Show error message if present
    if (!empty($error_message)) {
        $html .= '
        <div class="error-message">' . htmlspecialchars($error_message) . '</div>';
    }
    
    // Build login URL with public path
    $login_action = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
    
    $html .= '
        <form method="POST" action="' . htmlspecialchars($login_action) . '">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            
            <input type="hidden" name="redirect" value="' . htmlspecialchars($redirect_url) . '">
            
            <button type="submit" class="submit-button">Sign In</button>
        </form>

        <div class="oauth-divider"><span>or continue with</span></div>

        <div class="oauth-buttons">';

    // Build OAuth URLs with redirect passthrough
    $oauth_base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $oauth_redirect_param = !empty($redirect_url) ? '?redirect=' . urlencode($redirect_url) : '';
    $google_url = $oauth_base . '/auth/google' . $oauth_redirect_param;
    $github_url = $oauth_base . '/auth/github' . $oauth_redirect_param;

    $html .= '
            <a href="' . htmlspecialchars($google_url) . '" class="oauth-btn oauth-btn-google">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Sign in with Google
            </a>
            <a href="' . htmlspecialchars($github_url) . '" class="oauth-btn oauth-btn-github">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" fill="#333"/></svg>
                Sign in with GitHub
            </a>
        </div>
    </div>
</body>
</html>';
    
    return $html;
}

/**
 * Render admin dashboard
 * 
 * Minimal admin dashboard placeholder for version 3.0.8.
 * 
 * @param array $user Current user data from current_user()
 * @return string Rendered admin dashboard HTML
 */
/**
 * Render password change form
 * 
 * @param string|null $error_message Error message to display (if any)
 * @return string Rendered password change form HTML
 */
function change_password_form($error_message = null) {
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Lupopedia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .password-change-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        .password-change-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .password-change-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .password-change-header p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }
        .password-change-header .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 4px;
            margin-top: 15px;
            font-size: 13px;
        }
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        .password-requirements {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
        }
        .password-requirements h4 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #333;
        }
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }
        .password-requirements li {
            margin-bottom: 4px;
        }
        .submit-button {
            width: 100%;
            padding: 12px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .submit-button:hover {
            background: #357abd;
        }
        .submit-button:active {
            background: #2a5f8f;
        }
    </style>
</head>
<body>
    <div class="password-change-container">
        <div class="password-change-header">
            <h1>Change Password Required</h1>
            <p>Your account is using an outdated password format. Please set a new secure password to continue.</p>
            <div class="warning">
                ⚠️ You must change your password before accessing other parts of the system.
            </div>
        </div>';
    
    // Show error message if present
    if (!empty($error_message)) {
        $html .= '
        <div class="error-message">' . htmlspecialchars($error_message) . '</div>';
    }
    
    // Build form action URL with public path
    $change_password_action = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
    
    $html .= '
        <div class="password-requirements">
            <h4>Password Requirements:</h4>
            <ul>
                <li>At least 8 characters long</li>
                <li>Use a mix of letters, numbers, and symbols for better security</li>
            </ul>
        </div>
        
        <form method="POST" action="' . htmlspecialchars($change_password_action) . '">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required autofocus autocomplete="new-password" minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password" minlength="8">
            </div>
            
            <button type="submit" class="submit-button">Change Password</button>
        </form>
    </div>
</body>
</html>';
    
    return $html;
}

function admin_dashboard($user) {
    $username = htmlspecialchars(isset($user['username']) ? $user['username'] : 'User');
    $display_name = htmlspecialchars(isset($user['display_name']) ? $user['display_name'] : $username);
    $email = htmlspecialchars(isset($user['email']) ? $user['email'] : '');
    
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lupopedia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .admin-header {
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            font-size: 24px;
            color: #333;
        }
        .admin-header .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .admin-header .user-name {
            color: #666;
            font-size: 14px;
        }
        .admin-header .logout-link {
            color: #4a90e2;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 16px;
            border: 1px solid #4a90e2;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .admin-header .logout-link:hover {
            background: #4a90e2;
            color: white;
        }
        .admin-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 40px;
        }
        .welcome-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 30px;
        }
        .welcome-card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .welcome-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .info-item {
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .info-item label {
            display: block;
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-item .value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        .placeholder-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            color: #999;
        }
        .placeholder-section h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Lupopedia Admin</h1>
        <div class="user-info">
            <div class="user-name">' . $display_name . '</div>
            <a href="' . htmlspecialchars((defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/logout') . '" class="logout-link">Logout</a>
        </div>
    </div>
    
    <div class="admin-content">
        <div class="welcome-card">
            <h2>Welcome, ' . $display_name . '!</h2>
            <p>This is the Lupopedia admin dashboard. Version 3.0.8 provides basic authentication and admin access control. Additional admin features will be added in future versions.</p>
            
            <div class="info-grid">
                <div class="info-item">
                    <label>Username</label>
                    <div class="value">' . $username . '</div>
                </div>
                <div class="info-item">
                    <label>Display Name</label>
                    <div class="value">' . $display_name . '</div>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <div class="value">' . ($email ?: 'Not set') . '</div>
                </div>
                <div class="info-item">
                    <label>Role</label>
                    <div class="value">Administrator</div>
                </div>
            </div>
        </div>
        
        <div class="placeholder-section">
            <h3>Admin Features</h3>
            <p>Additional admin features will be available in future versions.</p>
        </div>
    </div>
</body>
</html>';
    
    return $html;
}

?>
