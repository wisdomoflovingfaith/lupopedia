<?php
/**
 * wolfie.header.identity: auth-ui-helpers
 * wolfie.header.placement: /lupo-includes/functions/auth-ui-helpers.php
 * wolfie.header.version: 3.0.9
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Updated authentication UI helper for version 3.0.9. Enhanced lupo_render_login_status() to show profile avatar with dropdown menu when logged in (instead of just text links). All links now use LUPOPEDIA_PUBLIC_PATH for subdirectory compatibility. Profile avatar uses auth_user_id for filename lookup."
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
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $user = $authService ? $authService->getCurrentUser() : current_user();

    if ($user) {
        // User is logged in - show profile avatar with dropdown
        $display_name = htmlspecialchars(isset($user['display_name']) ? $user['display_name'] : (isset($user['username']) ? $user['username'] : 'User'));
        $email = htmlspecialchars(isset($user['email']) ? $user['email'] : '');
        $auth_user_id = (int)(isset($user['auth_user_id']) ? $user['auth_user_id'] : 0);

        // Build avatar URL (use auth_user_id for avatar filename)
        $avatar_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
        $avatar_url = $avatar_path . '/uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        $avatar_fallback = $avatar_path . '/images/logoface.png';

        // Check if avatar file exists (for cache busting and fallback)
        $avatar_file_path = '';
        if (defined('LUPOPEDIA_PATH')) {
            $avatar_file_path = LUPOPEDIA_PATH . '/uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        } elseif (defined('ABSPATH')) {
            $avatar_file_path = ABSPATH . 'uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        }

        $avatar_timestamp = ($avatar_file_path && file_exists($avatar_file_path)) ? '?t=' . time() : '';
        $final_avatar_url = ($avatar_file_path && file_exists($avatar_file_path))
            ? $avatar_url . $avatar_timestamp
            : $avatar_fallback;

        $logout_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/logout' : '/logout';
        $admin_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin';
        $profile_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/my-profile' : '/my-profile';
        $operator_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/crafty_syntax/' : '/crafty_syntax/';

        // Use AuthService for channel-role check (is_operator)
        $actor_id = (int)(isset($user['actor_id']) ? $user['actor_id'] : 0);
        $is_operator = $authService && $actor_id ? $authService->hasAnyChannelRole($actor_id) : false;

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

            // Add Channel Sign-On menu item
            $channel_signon_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/operator/signon' : '/operator/signon';
            $html .= '<a href="' . htmlspecialchars($channel_signon_url) . '" class="dropdown-item" style="color: #16a085; font-weight: 600;">';
            $html .= '<span class="dropdown-icon">✓</span> Channel Sign-On</a>';
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
        $current_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
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
 * Get current user data for template variables (thin wrapper — AuthService).
 *
 * @return array|null User data array or null if not logged in
 */
function lupo_get_current_user_data() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->getCurrentUserData() : null;
}

/**
 * Check if user is logged in (thin wrapper — AuthService).
 *
 * @return bool True if logged in, false otherwise
 */
function lupo_is_logged_in() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->isLoggedIn() : (current_user() !== false);
}

/**
 * Get current username (thin wrapper — AuthService).
 *
 * @return string Username or empty string
 */
function lupo_get_username() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->getUsername() : (($u = current_user()) ? $u['username'] : '');
}

/**
 * Get current display name (thin wrapper — AuthService).
 *
 * @return string Display name or empty string
 */
function lupo_get_display_name() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $u = current_user();
    return $s ? $s->getDisplayName() : ($u ? (isset($u['display_name']) ? $u['display_name'] : (isset($u['username']) ? $u['username'] : '')) : '');
}

?>
