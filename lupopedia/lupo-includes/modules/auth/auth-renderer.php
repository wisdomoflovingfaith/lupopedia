<?php
/**
 * wolfie.header.identity: auth-renderer
 * wolfie.header.placement: /lupo-includes/modules/auth/auth-renderer.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Refreshed login form page for version 4.1.1. Updated WOLFIE header version to current ecosystem version. Login form continues to use email-only authentication with proper email input type and autocomplete attributes."
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
function login_form($error_message = null, $redirect_url = '/') {
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
    </div>
</body>
</html>';
    
    return $html;
}

/**
 * Render admin dashboard
 * 
 * Minimal admin dashboard placeholder for version 4.0.8.
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
    $username = htmlspecialchars($user['username'] ?? 'User');
    $display_name = htmlspecialchars($user['display_name'] ?? $username);
    $email = htmlspecialchars($user['email'] ?? '');
    
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
            <p>This is the Lupopedia admin dashboard. Version 4.0.8 provides basic authentication and admin access control. Additional admin features will be added in future versions.</p>
            
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
