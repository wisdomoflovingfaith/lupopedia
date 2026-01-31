<?php
/**
 * wolfie.header.identity: auth-helpers
 * wolfie.header.placement: /lupo-includes/functions/auth-helpers.php
 * wolfie.header.version: 4.0.9
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Updated actor slug generation to use email instead of username for version 4.0.9. Actor slugs are now derived from the email address (local part before @) to align with email-only login. Example: lupopedia@gmail.com -> slug 'lupopedia'. This ensures consistency since email is the canonical login identifier."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-helpers.php cannot be called directly.");
}

/**
 * Authentication Helper Functions
 * 
 * Provides user authentication, authorization, and actor linkage functions.
 * Handles the relationship between auth_users, actors, roles, and permissions.
 * 
 * @package Lupopedia
 * @subpackage Functions
 */

// Ensure session helpers are loaded
if (!function_exists('lupo_validate_session')) {
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'session-helpers.php');
}

// Ensure redirect helpers are loaded
if (!function_exists('lupo_safe_redirect')) {
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'redirect-helpers.php');
}

/**
 * Get actor ID from auth user ID
 * 
 * Finds the actor_id for a given auth_user_id by querying lupo_actors table.
 * 
 * @param int $auth_user_id Auth user ID
 * @return int|false Actor ID on success, false if not found
 */
function lupo_get_actor_id_from_auth_user_id($auth_user_id) {
    if (empty($auth_user_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "SELECT actor_id
        FROM {$table_prefix}actors
        WHERE actor_source_type = 'user'
          AND actor_source_id = :auth_user_id
          AND is_deleted = 0
        LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':auth_user_id' => (int)$auth_user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return (int)$result['actor_id'];
        }
        
        return false;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to get actor ID from auth user ID: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Create actor record for authenticated user
 * 
 * Creates an actor record in lupo_actors table for a given auth_user.
 * Slug is derived from email (canonical login identifier) for consistency.
 * 
 * @param int $auth_user_id Auth user ID
 * @param string $email Email address (used for slug generation)
 * @param string $display_name Display name (used for name)
 * @return int|false Actor ID on success, false on failure
 */
function lupo_create_actor_for_auth_user($auth_user_id, $email, $display_name) {
    if (empty($auth_user_id) || empty($email)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    $now = lupo_utc_timestamp();
    
    // Generate slug from email (include domain to ensure uniqueness)
    // Example: "lupopedia@gmail.com" -> "lupopedia-at-gmail-com"
    // Example: "someone@thisdomain.com" -> "someone-at-thisdomain-com"
    // Example: "someone@thatdomain.com" -> "someone-at-thatdomain-com"
    $email_normalized = strtolower(trim($email));
    
    // Replace @ with -at- separator, then replace dots with hyphens
    $slug = str_replace('@', '-at-', $email_normalized);
    $slug = str_replace('.', '-', $slug);
    
    // Sanitize: keep only alphanumeric and hyphens
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Ensure slug is unique (append number if needed)
    $base_slug = $slug;
    $counter = 1;
    while (lupo_actor_slug_exists($slug)) {
        $slug = $base_slug . '-' . $counter;
        $counter++;
    }
    
    // Use display_name or fallback to email local part
    $email_local = strpos($email_normalized, '@') !== false 
        ? substr($email_normalized, 0, strpos($email_normalized, '@'))
        : $email_normalized;
    $name = !empty($display_name) ? $display_name : $email_local;
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "INSERT INTO {$table_prefix}actors (
            actor_type,
            slug,
            name,
            created_ymdhis,
            updated_ymdhis,
            is_active,
            is_deleted,
            actor_source_id,
            actor_source_type
        ) VALUES (
            'user',
            :slug,
            :name,
            :created_ymdhis,
            :updated_ymdhis,
            1,
            0,
            :actor_source_id,
            'user'
        )";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':slug' => $slug,
            ':name' => $name,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now,
            ':actor_source_id' => (int)$auth_user_id
        ]);
        
        return (int)$db->lastInsertId();
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to create actor for auth user: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Check if actor slug exists
 * 
 * @param string $slug Actor slug to check
 * @return bool True if slug exists, false otherwise
 */
function lupo_actor_slug_exists($slug) {
    if (empty($slug)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "SELECT COUNT(*) as count
        FROM {$table_prefix}actors
        WHERE slug = :slug
          AND is_deleted = 0";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($result['count'] > 0);
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get current authenticated user information
 * 
 * Returns array with user data if logged in, false if not logged in.
 * 
 * @return array|false User data array or false if not logged in
 */
function current_user() {
    // Validate session
    $actor_id = lupo_validate_session();
    
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("AUTH DEBUG: current_user() - Validated actor_id: " . ($actor_id ?: 'FALSE'));
    }

    if (!$actor_id) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        
        // Get actor and auth_user data
        $sql = "SELECT 
            a.actor_id,
            a.actor_source_id as auth_user_id,
            au.username,
            au.display_name,
            au.email,
            au.is_active as user_is_active
        FROM {$table_prefix}actors a
        JOIN {$table_prefix}auth_users au ON a.actor_source_id = au.auth_user_id
        WHERE a.actor_id = :actor_id
          AND a.actor_source_type = 'user'
          AND a.is_deleted = 0
          AND au.is_deleted = 0
        LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':actor_id' => $actor_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH DEBUG: current_user() - No user found for actor_id: " . $actor_id);
            }
            return false;
        }
        
        // Check if user account is active
        if ($user['user_is_active'] != 1) {
            return false;
        }
        
        // Check admin status
        $user['is_admin'] = lupo_is_admin($actor_id);
        
        return [
            'actor_id' => (int)$user['actor_id'],
            'auth_user_id' => (int)$user['auth_user_id'],
            'username' => $user['username'],
            'display_name' => $user['display_name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin']
        ];
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to get current user: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Check if an actor has admin role
 * 
 * Checks multiple sources for admin status:
 * 1. lupo_actor_roles (role_key = 'admin') - highest priority
 * 2. lupo_permissions (permission = 'owner' on admin module)
 * 3. lupo_actor_group_membership (membership in admin group)
 * 
 * @param int $actor_id Actor ID to check
 * @return bool True if admin, false otherwise
 */
function lupo_is_admin($actor_id) {
    if (empty($actor_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        
        // Check 1: Actor roles (highest priority)
        $sql = "SELECT COUNT(*) as count
        FROM {$table_prefix}actor_roles ar
        WHERE ar.actor_id = :actor_id
          AND ar.role_key = 'admin'
          AND ar.is_deleted = 0";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':actor_id' => $actor_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return true;
        }
        
        // Check 2: Permissions (owner on admin module)
        // First, find admin module ID (if it exists)
        // NOTE: Using module_key, not name (from TOON file: lupo_modules has module_key column)
        $sql = "SELECT module_id
        FROM {$table_prefix}modules
        WHERE module_key = 'admin'
          AND is_active = 1
          AND is_deleted = 0
        LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $admin_module = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin_module) {
            // Get auth_user_id from actor
            $auth_user_id = lupo_get_auth_user_id_from_actor_id($actor_id);
            
            if ($auth_user_id) {
                $sql = "SELECT COUNT(*) as count
                FROM {$table_prefix}permissions p
                WHERE p.target_type = 'module'
                  AND p.target_id = :module_id
                  AND p.user_id = :user_id
                  AND p.permission = 'owner'
                  AND p.is_deleted = 0";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':module_id' => $admin_module['module_id'],
                    ':user_id' => $auth_user_id
                ]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result['count'] > 0) {
                    return true;
                }
            }
        }
        
        // Check 3: Group membership (lowest priority)
        // NOTE: Schema issue - actor_group_membership_id is auto_increment PK, not actor_id FK
        // The comment says it references actors.actor_id, but schema shows it's the primary key
        // This check is disabled until schema is clarified or migration is created
        // For now, admin status is determined by actor_roles and permissions only
        
        return false;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("AUTH ERROR: Failed to check admin status: " . $e->getMessage());
        }
        return false;
    }
}

/**
 * Get auth_user_id from actor_id
 * 
 * @param int $actor_id Actor ID
 * @return int|false Auth user ID on success, false otherwise
 */
function lupo_get_auth_user_id_from_actor_id($actor_id) {
    if (empty($actor_id)) {
        return false;
    }
    
    if (!isset($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    try {
        // Use table prefix constant if defined, otherwise fallback to LUPO_PREFIX with underscore replacement
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }
        $sql = "SELECT actor_source_id as auth_user_id
        FROM {$table_prefix}actors
        WHERE actor_id = :actor_id
          AND actor_source_type = 'user'
          AND is_deleted = 0
        LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':actor_id' => $actor_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return (int)$result['auth_user_id'];
        }
        
        return false;
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Require user to be logged in
 * 
 * Redirects to login page if user is not logged in.
 * Stores current URL in session for post-login redirect.
 * 
 * @return void Exits script if not logged in
 */
function require_login() {
    $user = current_user();
    
    if (!$user) {
        // Store redirect URL
        $redirect_url = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Start session if not started
        lupo_start_session();
        
        // Store redirect in session
        $_SESSION['login_redirect'] = $redirect_url;

        // Redirect to login
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/login' : '/login';
        $login_url .= '?redirect=' . urlencode($redirect_url);
        lupo_safe_redirect($login_url, 2, 'Please log in to continue.');
    }
    
    // Check if password change is required (MD5 password detected)
    lupo_start_session();
    if (isset($_SESSION['password_change_required']) && $_SESSION['password_change_required']) {
        $change_password_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/change-password' : '/change-password';
        lupo_safe_redirect($change_password_url, 2, 'Password change required. Redirecting...');
    }
}

/**
 * Require user to be admin
 * 
 * First checks if user is logged in, then checks admin status.
 * Shows 403 error if not admin.
 * 
 * @return void Exits script if not admin
 */
function require_admin() {
    // First require login
    require_login();
    
    $user = current_user();
    
    if (!$user || !$user['is_admin']) {
        // Show 403 error
        header('HTTP/1.1 403 Forbidden');
        echo '<h1>403 Forbidden</h1>';
        echo '<p>You do not have permission to access this page.</p>';
        exit;
    }
}

?>
