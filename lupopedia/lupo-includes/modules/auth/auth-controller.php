<?php
/**
 * wolfie.header.identity: auth-controller
 * wolfie.header.placement: /lupo-includes/modules/auth/auth-controller.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Temporal correction: WOLFIE header sync to 4.1.1 for doctrine alignment. Auth controller maintains email-only authentication (canonical login identifier). All validation, queries, and error messages reference email. Prevents Actor Pillar and Doctrine Pillar drift."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-controller.php cannot be called directly.");
}

// Load required helpers
require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/security/password-hash.php');
require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/functions/session-helpers.php');
require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/functions/auth-helpers.php');
require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/functions/redirect-helpers.php');
require_once(__DIR__ . '/auth-renderer.php');

/**
 * Authentication Controller
 * 
 * Handles authentication routes:
 * - /login (GET: show form, POST: process login)
 * - /logout (destroy session)
 * - /admin (protected admin dashboard)
 * 
 * @package Lupopedia
 * @subpackage Modules
 */

/**
 * Handle authentication routes
 * 
 * Routes:
 * - login - Login form and processing
 * - logout - Session destruction
 * - admin - Admin dashboard (protected)
 * 
 * @param string $slug The route slug
 * @return string Rendered HTML output
 */
function auth_handle_slug($slug) {
    // Normalize slug - remove leading slash, convert to lowercase, remove .php extension
    $slug = ltrim(strtolower($slug), '/');
    $slug = preg_replace('/\.php$/', '', $slug); // Remove .php extension
    
    // Route to appropriate handler
    if ($slug === 'login') {
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return login_handle_post();
        } else {
            return login_handle_view();
        }
    } elseif ($slug === 'logout') {
        return logout_handle();
    } elseif ($slug === 'change-password' || $slug === 'change_password') {
        // Handle password change (for MD5 password upgrades)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return change_password_handle_post();
        } else {
            return change_password_handle_view();
        }
    } elseif (strpos($slug, 'admin') === 0) {
        return admin_handle_view($slug);
    }
    
    // No match
    return '';
}

/**
 * Handle login form view (GET request)
 * 
 * Shows login form. If user is already logged in, redirects to admin or home.
 * 
 * @return string Rendered login form HTML
 */
function login_handle_view() {
    // Start session if not already started
    lupo_start_session();
    
    // Check if already logged in
    $user = current_user();
    if ($user) {
        // Already logged in, redirect
        $redirect = $_GET['redirect'] ?? (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin');
        // Ensure redirect includes public path if it's a relative path
        if (strpos($redirect, '/') === 0 && strpos($redirect, LUPOPEDIA_PUBLIC_PATH) !== 0 && defined('LUPOPEDIA_PUBLIC_PATH')) {
            $redirect = LUPOPEDIA_PUBLIC_PATH . $redirect;
        }
        lupo_safe_redirect($redirect, 2, 'Login successful! Redirecting...');
    }
    
    // Get redirect URL from query string or session
    $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin';
    $redirect_url = $_GET['redirect'] ?? ($_SESSION['login_redirect'] ?? $default_redirect);
    
    // Get any error message from session
    $error_message = $_SESSION['login_error'] ?? null;
    unset($_SESSION['login_error']);
    
    // Render login form
    return login_form($error_message, $redirect_url);
}

/**
 * Handle login form submission (POST request)
 * 
 * Processes login attempt:
 * 1. Validates input
 * 2. Looks up user in database
 * 3. Verifies password
 * 4. Upgrades MD5 to bcrypt if needed
 * 5. Creates session
 * 6. Redirects to admin or previous page
 * 
 * @return string|void Rendered HTML on error, or redirects on success
 */
function login_handle_post() {
    // Start session if not already started
    lupo_start_session();
    
    // Validate input - email-only login
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin';
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : $default_redirect;
    
    // Sanitize redirect URL (prevent open redirect)
    $redirect = filter_var($redirect, FILTER_SANITIZE_URL);
    if (empty($redirect) || strpos($redirect, 'http') === 0) {
        $redirect = $default_redirect;
    }
    // Ensure redirect includes public path if it's a relative path
    if (strpos($redirect, '/') === 0 && strpos($redirect, LUPOPEDIA_PUBLIC_PATH) !== 0 && defined('LUPOPEDIA_PUBLIC_PATH')) {
        $redirect = LUPOPEDIA_PUBLIC_PATH . $redirect;
    }
    
    // Build login URL with public path
    $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
    
    // Validate required fields
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Email and password are required.';
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }
    
    // Validate email format
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = 'Invalid email format.';
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }
    
    // Normalize email (lowercase)
    $email = strtolower($email);
    
    if (!isset($GLOBALS['mydatabase'])) {
        $_SESSION['login_error'] = 'Database connection error. Please try again later.';
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Look up user
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        
        // Debug: Log login attempt
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH DEBUG: Login attempt - Email: " . htmlspecialchars($email) . ", Table prefix: " . $table_prefix);
        }
        
        // Look up user by email (canonical login identifier)
        $sql = "SELECT 
            auth_user_id,
            username,
            display_name,
            email,
            password_hash,
            is_active,
            is_deleted
        FROM {$table_prefix}auth_users
        WHERE email = :email
          AND is_deleted = 0
        LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug: Log user lookup result
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            if ($user) {
                error_log("AUTH DEBUG: User found - ID: " . $user['auth_user_id'] . ", Email: " . $user['email'] . ", Username: " . $user['username'] . ", Password hash length: " . strlen($user['password_hash']));
            } else {
                error_log("AUTH DEBUG: User NOT found for email: " . htmlspecialchars($email));
            }
        }
        
        // Generic error message (don't reveal which field is wrong)
        $generic_error = 'Invalid email or password.';
        
        if (!$user) {
            // Log failed attempt
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH: Failed login attempt for email: " . htmlspecialchars($email) . " (user not found)");
            }
            $_SESSION['login_error'] = $generic_error;
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 2, 'Login failed. Redirecting...');
        }
        
        // Check if account is active
        if ($user['is_active'] != 1) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH: Failed login attempt for inactive user: " . htmlspecialchars($email));
            }
            $_SESSION['login_error'] = 'Your account is inactive. Please contact an administrator.';
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 2, 'Login failed. Redirecting...');
        }
        
        // Verify password
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH DEBUG: Verifying password - Hash type: " . (lupo_is_md5_hash($user['password_hash']) ? 'MD5' : (lupo_is_bcrypt_hash($user['password_hash']) ? 'bcrypt' : 'unknown')));
        }
        
        $password_valid = lupo_verify_password($password, $user['password_hash']);
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH DEBUG: Password verification result: " . ($password_valid ? 'VALID' : 'INVALID'));
        }
        
        if (!$password_valid) {
            // Log failed attempt
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH: Failed login attempt for email: " . htmlspecialchars($email) . " (invalid password)");
                error_log("AUTH DEBUG: Stored hash: " . substr($user['password_hash'], 0, 20) . "... (length: " . strlen($user['password_hash']) . ")");
            }
            $_SESSION['login_error'] = $generic_error;
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 2, 'Login failed. Redirecting...');
        }
        
        // Check if password is MD5 - if so, require password change instead of auto-upgrading
        if (lupo_password_needs_upgrade($user['password_hash'])) {
            // MD5 password detected - require password change
            // Set session flag to force password change
            $_SESSION['password_change_required'] = true;
            $_SESSION['password_change_user_id'] = $user['auth_user_id'];
            $_SESSION['password_change_actor_id'] = null; // Will be set after actor lookup
            
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH: MD5 password detected for user: " . htmlspecialchars($email) . " - requiring password change");
            }
        }
        
        // Get or create actor_id
        $actor_id = lupo_get_actor_id_from_auth_user_id($user['auth_user_id']);
        
        if (!$actor_id) {
            // Create actor record (slug derived from email, not username)
            $actor_id = lupo_create_actor_for_auth_user(
                $user['auth_user_id'],
                $user['email'],  // Use email for slug generation (canonical login identifier)
                $user['display_name']
            );
            
            if (!$actor_id) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log("AUTH ERROR: Failed to create actor for user: " . htmlspecialchars($email));
                }
                $_SESSION['login_error'] = 'Account setup error. Please contact an administrator.';
                header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
                exit;
            }
        }
        
        // If password change is required (MD5 detected), update session flag with actor_id
        if (isset($_SESSION['password_change_required']) && $_SESSION['password_change_required']) {
            $_SESSION['password_change_actor_id'] = $actor_id;
        }
        
        // Ensure session is started before creating database session
        lupo_start_session();
        
        // Debug: Log before session creation
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH DEBUG: About to create session - Email: " . htmlspecialchars($email) . ", Actor ID: " . $actor_id . ", PHP Session ID: " . (session_id() ?: 'NULL'));
        }
        
        // Create session
        $session_id = lupo_create_session($actor_id, 'password', 'local');
        
        // Debug: Log after session creation attempt
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH DEBUG: Session creation returned: " . ($session_id ? substr($session_id, 0, 20) . "..." : 'FALSE'));
        }
        
        if (!$session_id) {
            $error_details = '';
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                $error_details = " (Check error logs and page source for details)";
                error_log("AUTH ERROR: Failed to create session for user: " . htmlspecialchars($email));
                error_log("AUTH ERROR: Actor ID was: " . $actor_id);
                error_log("AUTH ERROR: Session ID from PHP: " . (session_id() ?: 'NULL'));
                error_log("AUTH ERROR: Session status: " . session_status());
                
                // Check if table exists
                if (isset($GLOBALS['mydatabase'])) {
                    $db = $GLOBALS['mydatabase'];
                    try {
                        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);
                        $check_sql = "SELECT COUNT(*) as count FROM {$table_prefix}sessions LIMIT 1";
                        $check_stmt = $db->prepare($check_sql);
                        $check_stmt->execute();
                        error_log("AUTH DEBUG: Table {$table_prefix}sessions exists and is accessible");
                    } catch (PDOException $e) {
                        error_log("AUTH ERROR: Table {$table_prefix}sessions check failed: " . $e->getMessage());
                    }
                }
            }
            $_SESSION['login_error'] = 'Session creation failed. Please try again.' . $error_details;
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 3, 'Login failed. Redirecting...');
        }
        
        // Update last login timestamp
        $now = lupo_utc_timestamp();
        $update_sql = "UPDATE {$table_prefix}auth_users
        SET last_login_ymdhis = :last_login_ymdhis,
            updated_ymdhis = :updated_ymdhis
        WHERE auth_user_id = :auth_user_id";
        
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->execute([
            ':last_login_ymdhis' => $now,
            ':updated_ymdhis' => $now,
            ':auth_user_id' => $user['auth_user_id']
        ]);
        
        // Clear any error messages
        unset($_SESSION['login_error']);
        unset($_SESSION['login_redirect']);
        
        // Log successful login
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH: Successful login for user: " . htmlspecialchars($email) . " (actor_id: " . $actor_id . ")");
        }
        
        // If password change is required (MD5 password), redirect to password change page
        if (isset($_SESSION['password_change_required']) && $_SESSION['password_change_required']) {
            $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
            lupo_safe_redirect($change_password_url, 2, 'Password change required. Redirecting...');
        }
        
        // Redirect to admin or previous page (redirect already includes public path from earlier processing)
        lupo_safe_redirect($redirect, 2, 'Login successful! Redirecting...');
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Exception during login for email: " . htmlspecialchars($email) . " - " . $e->getMessage());
        }
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
        $_SESSION['login_error'] = 'An error occurred. Please try again later.';
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }
}

/**
 * Handle logout
 * 
 * Destroys session and redirects to homepage.
 * 
 * @return void Redirects to homepage
 */
function logout_handle() {
    // Destroy session
    lupo_destroy_session();
    
    // Clear session data
    $_SESSION = [];
    
    // Redirect to homepage (with public path)
    $home_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
        lupo_safe_redirect($home_url, 2, 'Logged out. Redirecting...');
}

/**
 * Handle password change view (GET request)
 * 
 * Shows password change form for users with MD5 passwords.
 * Requires session flag set during login.
 * 
 * @return string Rendered password change form HTML
 */
function change_password_handle_view() {
    // Start session
    lupo_start_session();
    
    // Check if password change is required
    if (!isset($_SESSION['password_change_required']) || !$_SESSION['password_change_required']) {
        // Not required - redirect to login
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
        header('Location: ' . $login_url);
        exit;
    }
    
    // Check if user is logged in
    $user = current_user();
    if (!$user) {
        // Not logged in - redirect to login
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
        header('Location: ' . $login_url);
        exit;
    }
    
    // Get any error message from session
    $error_message = $_SESSION['password_change_error'] ?? null;
    unset($_SESSION['password_change_error']);
    
    // Render password change form
    return change_password_form($error_message);
}

/**
 * Handle password change submission (POST request)
 * 
 * Processes password change for users with MD5 passwords.
 * 
 * @return string|void Rendered HTML on error, or redirects on success
 */
function change_password_handle_post() {
    // Start session
    lupo_start_session();
    
    // Check if password change is required
    if (!isset($_SESSION['password_change_required']) || !$_SESSION['password_change_required']) {
        // Not required - redirect to login
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
        header('Location: ' . $login_url);
        exit;
    }
    
    // Check if user is logged in
    $user = current_user();
    if (!$user) {
        // Not logged in - redirect to login
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
        header('Location: ' . $login_url);
        exit;
    }
    
    // Validate input
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validate required fields
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['password_change_error'] = 'Both password fields are required.';
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        header('Location: ' . $change_password_url);
        exit;
    }
    
    // Validate password match
    if ($new_password !== $confirm_password) {
        $_SESSION['password_change_error'] = 'Passwords do not match.';
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        header('Location: ' . $change_password_url);
        exit;
    }
    
    // Validate password strength (minimum 8 characters)
    if (strlen($new_password) < 8) {
        $_SESSION['password_change_error'] = 'Password must be at least 8 characters long.';
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        header('Location: ' . $change_password_url);
        exit;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        $_SESSION['password_change_error'] = 'Database connection error. Please try again later.';
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        header('Location: ' . $change_password_url);
        exit;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Hash new password with bcrypt
        $new_hash = lupo_hash_password($new_password);
        if (!$new_hash) {
            $_SESSION['password_change_error'] = 'Error hashing password. Please try again.';
            $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
            lupo_safe_redirect($change_password_url, 2, 'Password change required. Redirecting...');
        }
        
        // Update password hash in database
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);
        $now = lupo_utc_timestamp();
        $update_sql = "UPDATE {$table_prefix}auth_users
        SET password_hash = :password_hash,
            updated_ymdhis = :updated_ymdhis
        WHERE auth_user_id = :auth_user_id";
        
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->execute([
            ':password_hash' => $new_hash,
            ':updated_ymdhis' => $now,
            ':auth_user_id' => $user['auth_user_id']
        ]);
        
        // Clear password change requirement flag
        unset($_SESSION['password_change_required']);
        unset($_SESSION['password_change_user_id']);
        unset($_SESSION['password_change_actor_id']);
        unset($_SESSION['password_change_error']);
        
        // Log successful password change
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH: Password changed from MD5 to bcrypt for user: " . htmlspecialchars($user['username']));
        }
        
        // Redirect to admin dashboard
        $admin_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin';
        lupo_safe_redirect($admin_url, 2, 'Redirecting to admin...');
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Exception during password change: " . $e->getMessage());
        }
        $_SESSION['password_change_error'] = 'An error occurred. Please try again later.';
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        header('Location: ' . $change_password_url);
        exit;
    }
}

/**
 * Handle admin dashboard view
 * 
 * Protected route that requires login and admin status.
 * Shows minimal admin dashboard placeholder.
 * 
 * @param string $slug Admin route slug (e.g., 'admin', 'admin/dashboard')
 * @return string Rendered admin dashboard HTML
 */
function admin_handle_view($slug) {
    // Check if password change is required first
    lupo_start_session();
    if (isset($_SESSION['password_change_required']) && $_SESSION['password_change_required']) {
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        header('Location: ' . $change_password_url);
        exit;
    }
    
    // Require admin access (this will redirect if not logged in or not admin)
    require_admin();
    
    // Get current user (we know they're admin at this point)
    $user = current_user();
    
    // Render admin dashboard
    return admin_dashboard($user);
}

?>
