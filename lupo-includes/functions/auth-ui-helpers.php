<?php
/**
 * wolfie.header.identity: auth-ui-helpers
 * wolfie.header.placement: /lupo-includes/functions/auth-ui-helpers.php
 * wolfie.header.version: 4.0.9
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Updated authentication UI helper for version 4.0.9. Enhanced lupo_render_login_status() to show profile avatar with dropdown menu when logged in (instead of just text links). All links now use LUPOPEDIA_PUBLIC_PATH for subdirectory compatibility. Profile avatar uses auth_user_id for filename lookup."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-ui-helpers.php cannot be called directly.");
}

/**
 * Authentication UI Helper Functions
 * 
 * Provides UI rendering functions for authentication status indicators
 * that can be used in headers, navigation bars, and templates.
 * 
 * @package Lupopedia
 * @subpackage Functions
 */

// Ensure auth helpers are loaded
if (!function_exists('current_user')) {
    require_once(LUPOPEDIA_PATH . '/lupo-includes/functions/auth-helpers.php');
}

/**
 * Render login status indicator with profile avatar
 * 
 * Returns HTML for login/logout with profile avatar dropdown when logged in.
 * Can be inserted into header/navigation bars.
 * 
 * @return string HTML for login status indicator
 */
function lupo_render_login_status() {
    $user = current_user();
    
    if ($user) {
        // User is logged in - show profile avatar with dropdown
        $display_name = htmlspecialchars($user['display_name'] ?? $user['username'] ?? 'User');
        $email = htmlspecialchars($user['email'] ?? '');
        $auth_user_id = (int)($user['auth_user_id'] ?? 0);
        
        // Build avatar URL (use auth_user_id for avatar filename)
        $avatar_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        $avatar_url = $avatar_path . '/uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        $avatar_fallback = $avatar_path . '/images/logoface.png';
        
        // Check if avatar file exists (for cache busting and fallback)
        // Use filesystem path (LUPOPEDIA_PATH) to check file existence
        $avatar_file_path = '';
        if (defined('LUPOPEDIA_PATH')) {
            $avatar_file_path = LUPOPEDIA_PATH . '/uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        } elseif (defined('ABSPATH')) {
            $avatar_file_path = ABSPATH . 'uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        }
        
        $avatar_timestamp = ($avatar_file_path && file_exists($avatar_file_path)) ? '?t=' . time() : '';
        
        // Use avatar if exists, otherwise fallback to logo
        $final_avatar_url = ($avatar_file_path && file_exists($avatar_file_path)) 
            ? $avatar_url . $avatar_timestamp 
            : $avatar_fallback;
        
        $logout_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/logout' : '/logout';
        $admin_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin';
        $profile_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/profile' : '/profile';
        $operator_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/lupopedia/crafty_syntax/' : '/lupopedia/crafty_syntax/';

        // Check if user is an operator
        $is_operator = false;
        try {
            $db = lupo_get_db();
            $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);
            $operator_check_sql = "SELECT operator_id FROM {$table_prefix}operators WHERE auth_user_id = :auth_user_id AND is_active = 1 LIMIT 1";
            $operator_stmt = $db->prepare($operator_check_sql);
            $operator_stmt->execute([':auth_user_id' => $auth_user_id]);
            $is_operator = ($operator_stmt->rowCount() > 0);
        } catch (Exception $e) {
            // Silently fail - if operators table doesn't exist or query fails, user is not an operator
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("AUTH UI: Operator check failed: " . $e->getMessage());
            }
        }

        $html = '<div class="user-dropdown">';
        $html .= '<button class="user-profile-btn" onclick="toggleUserDropdown()">';
        $html .= '<div class="user-avatar">';
        $html .= '<img src="' . htmlspecialchars($final_avatar_url) . '" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">';
        $html .= '</div>';
        $html .= '<span class="dropdown-arrow">▼</span>';
        $html .= '</button>';
        $html .= '<div class="user-dropdown-menu" id="userDropdownMenu">';
        $html .= '<div class="dropdown-header">';
        $html .= '<div class="user-info">';
        $html .= '<div class="user-avatar-large">';
        $html .= '<img src="' . htmlspecialchars($final_avatar_url) . '" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">';
        $html .= '</div>';
        $html .= '<div class="user-details">';
        $html .= '<div class="user-name-large">' . $display_name . '</div>';
        $html .= '<div class="user-email">' . $email . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="dropdown-divider"></div>';
        $html .= '<a href="' . htmlspecialchars($profile_url) . '" class="dropdown-item">';
        $html .= '<span class="dropdown-icon">👤</span> Edit Profile</a>';

        // Show Crafty Syntax Operator Admin only if user is an operator
        if ($is_operator) {
            $html .= '<a href="' . htmlspecialchars($operator_url) . '" class="dropdown-item" style="color: #16a085; font-weight: 600;">';
            $html .= '<span class="dropdown-icon">🎧</span> Crafty Syntax Operator Admin</a>';
        }

        $html .= '<a href="#" class="dropdown-item" style="color: #999;" onclick="return false;">';
        $html .= '<span class="dropdown-icon">🔔</span> Notifications</a>';
        $html .= '<a href="' . htmlspecialchars($admin_url) . '" class="dropdown-item" style="color: #16a085; font-weight: 600;">';
        $html .= '<span class="dropdown-icon">🔧</span> Lupopedia Semantic Admin</a>';
        $html .= '<div class="dropdown-divider"></div>';
        $html .= '<a href="' . htmlspecialchars($logout_url) . '" class="dropdown-item logout-item">';
        $html .= '<span class="dropdown-icon">🚪</span> Sign Out</a>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    } else {
        // User is not logged in - show login link
        $current_url = $_SERVER['REQUEST_URI'] ?? '/';
        $login_url = defined('LUPOPEDIA_PUBLIC_PATH')
            ? LUPOPEDIA_PUBLIC_PATH . '/login?redirect=' . urlencode($current_url)
            : '/login?redirect=' . urlencode($current_url);

        $html = '<div class="nav-user">';
        $html .= '<a href="' . htmlspecialchars($login_url) . '" class="nav-link">Sign In</a>';
        $html .= '</div>';

        return $html;
    }
}

/**
 * Get current user data for template variables
 * 
 * Returns user data array that can be used in templates.
 * Returns null if user is not logged in.
 * 
 * @return array|null User data array or null if not logged in
 */
function lupo_get_current_user_data() {
    $user = current_user();
    
    if (!$user) {
        return null;
    }
    
    return [
        'actor_id' => $user['actor_id'],
        'auth_user_id' => $user['auth_user_id'],
        'username' => $user['username'],
        'display_name' => $user['display_name'],
        'email' => $user['email'],
        'is_admin' => $user['is_admin']
    ];
}

/**
 * Check if user is logged in
 * 
 * Simple boolean check for login status.
 * 
 * @return bool True if logged in, false otherwise
 */
function lupo_is_logged_in() {
    $user = current_user();
    return ($user !== false);
}

/**
 * Get current username
 * 
 * Returns username if logged in, empty string otherwise.
 * 
 * @return string Username or empty string
 */
function lupo_get_username() {
    $user = current_user();
    return $user ? $user['username'] : '';
}

/**
 * Get current display name
 * 
 * Returns display name if logged in, empty string otherwise.
 * 
 * @return string Display name or empty string
 */
function lupo_get_display_name() {
    $user = current_user();
    return $user ? ($user['display_name'] ?? $user['username']) : '';
}

?>
