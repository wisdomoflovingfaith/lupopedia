<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: functions
  when_updated: "20260406011027"
  file_path_from_root: "lupo-includes/functions/auth-ui-helpers.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/functions/auth-ui-helpers.php"
  last_modified_utc: "20260406011027"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "functions"
  artifact_kind: "ui_helpers"
  purpose: "Auth UI rendering; lupo_t strings; lupo_render_login_status($user,$is_operator) expects caller-resolved user (no globals inside renderer)."
  tags: ["auth", "ui", "helpers", "locale"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-ui-helpers.php cannot be called directly.");
}

if (!function_exists('current_user')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/auth-helpers.php';
}

/**
 * Bootstrap locale for lupo_t() when this file is loaded before themes run LupoLocale.
 *
 * @return void
 */
function lupo_auth_ui_ensure_locale()
{
    $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '';
    if ($root === '') {
        return;
    }
    if (!class_exists('LupoLocale', false)) {
        $p = $root . '/lupo-includes/classes/LupoLocale.php';
        if (is_file($p)) {
            require_once $p;
        }
    }
    if (class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
        LupoLocale::bootstrap($root);
    }
    if (!function_exists('lupo_t')) {
        $i18n = $root . '/lupo-includes/lupo-i18n.php';
        if (is_file($i18n)) {
            require_once $i18n;
        }
    }
}

/**
 * Render login status (avatar dropdown or Sign In).
 *
 * Caller must resolve session/auth and pass $user (Model A: identity from lupo_sessions / AuthService.getCurrentUser()).
 * This function does not read $GLOBALS['lupo_auth_service'] or call current_user().
 *
 * @param array|null $user Logged-in user row (e.g. from AuthService::getCurrentUser()), or null/omitted for guest
 * @param bool       $is_operator Whether user may see operator links (caller computes, e.g. hasAnyChannelRole)
 * @return string HTML
 */
function lupo_render_login_status($user = null, $is_operator = false)
{
    lupo_auth_ui_ensure_locale();

    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
    $UNTRUSTED = array(
        'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
    );
    $request_uri = '';
    if (isset($UNTRUSTED['server']['REQUEST_URI']) && is_string($UNTRUSTED['server']['REQUEST_URI'])) {
        $request_uri = $UNTRUSTED['server']['REQUEST_URI'];
    }

    if ($user !== null && is_array($user) && !empty($user)) {
        $display_name = htmlspecialchars(
            isset($user['display_name']) && $user['display_name'] !== ''
                ? $user['display_name']
                : (isset($user['username']) ? $user['username'] : (function_exists('lupo_t') ? lupo_t('auth.display_user', 'User') : 'User'))
        );
        $email = htmlspecialchars(isset($user['email']) ? $user['email'] : '');
        $auth_user_id = isset($user['auth_user_id']) ? (int) $user['auth_user_id'] : 0;

        $avatar_url = $base . '/lupo-uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        $avatar_fallback = $base . '/lupo-images/logoface.png';

        $avatar_file_path = '';
        if (defined('LUPOPEDIA_PATH')) {
            $avatar_file_path = LUPOPEDIA_PATH . '/lupo-uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        } elseif (defined('ABSPATH')) {
            $avatar_file_path = ABSPATH . 'uploads/avatars/' . $auth_user_id . '_avatar.jpg';
        }

        $avatar_timestamp = ($avatar_file_path !== '' && file_exists($avatar_file_path)) ? '?t=' . gmdate('YmdHis') : '';
        $final_avatar_url = ($avatar_file_path !== '' && file_exists($avatar_file_path))
            ? $avatar_url . $avatar_timestamp
            : $avatar_fallback;

        $logout_url = $base . '/logout.php';
        $admin_url = $base . '/admin.php';
        $profile_url = $base . '/my-profile';
        $operator_url = $base . '/crafty_syntax/';
        $channel_signon_url = $base . '/operator/signon';

        $avatar_alt = function_exists('lupo_t') ? lupo_t('nav.avatar_alt', 'Avatar') : 'Avatar';

        $html = '<div class="user-dropdown">';
        $html .= '<button class="user-profile-btn" onclick="toggleUserDropdown()">';
        $html .= '<div class="user-avatar">';
        $html .= '<img src="' . htmlspecialchars($final_avatar_url) . '" alt="' . htmlspecialchars($avatar_alt) . '" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">';
        $html .= '</div>';
        $html .= '<span class="dropdown-arrow">▼</span>';
        $html .= '</button>';
        $html .= '<div class="user-dropdown-menu" id="userDropdownMenu">';
        $html .= '<div class="dropdown-header">';
        $html .= '<div class="user-info">';
        $html .= '<div class="user-avatar-large">';
        $html .= '<img src="' . htmlspecialchars($final_avatar_url) . '" alt="' . htmlspecialchars($avatar_alt) . '" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">';
        $html .= '</div>';
        $html .= '<div class="user-details">';
        $html .= '<div class="user-name-large">' . $display_name . '</div>';
        $html .= '<div class="user-email">' . $email . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="dropdown-divider"></div>';
        $html .= '<a href="' . htmlspecialchars($profile_url) . '" class="dropdown-item">';
        $html .= '<span class="dropdown-icon">👤</span> ' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.edit_profile', 'Edit Profile') : 'Edit Profile') . '</a>';

        if ($is_operator) {
            $html .= '<a href="' . htmlspecialchars($operator_url) . '" class="dropdown-item" style="color: #16a085; font-weight: 600;">';
            $html .= '<span class="dropdown-icon">🎧</span> ' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.crafty_operator_admin', 'Crafty Syntax Operator Admin') : 'Crafty Syntax Operator Admin') . '</a>';

            $html .= '<a href="' . htmlspecialchars($channel_signon_url) . '" class="dropdown-item" style="color: #16a085; font-weight: 600;">';
            $html .= '<span class="dropdown-icon">✓</span> ' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.channel_sign_on', 'Channel Sign-On') : 'Channel Sign-On') . '</a>';
        }

        $html .= '<a href="#" class="dropdown-item" style="color: #999;" onclick="return false;">';
        $html .= '<span class="dropdown-icon">🔔</span> ' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.notifications', 'Notifications') : 'Notifications') . '</a>';
        $html .= '<a href="' . htmlspecialchars($admin_url) . '" class="dropdown-item" style="color: #16a085; font-weight: 600;">';
        $html .= '<span class="dropdown-icon">🔧</span> ' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.lupo_semantic_admin', 'Lupopedia Semantic Admin') : 'Lupopedia Semantic Admin') . '</a>';
        $html .= '<div class="dropdown-divider"></div>';
        $html .= '<a href="' . htmlspecialchars($logout_url) . '" class="dropdown-item logout-item">';
        $html .= '<span class="dropdown-icon">🚪</span> ' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.sign_out', 'Sign Out') : 'Sign Out') . '</a>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    $redir = $request_uri !== '' ? $request_uri : '/';
    $login_url = function_exists('lupo_login_url') ? lupo_login_url($redir) : ($base . '/login.php?redirect=' . rawurlencode($redir));
    $html = '<div class="nav-user">';
    $html .= '<a href="' . htmlspecialchars($login_url) . '" class="nav-link">';
    $html .= htmlspecialchars(function_exists('lupo_t') ? lupo_t('auth.sign_in', 'Sign In') : 'Sign In');
    $html .= '</a>';
    $html .= '</div>';

    return $html;
}

/**
 * Get current user data for template variables (thin wrapper — AuthService).
 *
 * @return array|null
 */
function lupo_get_current_user_data()
{
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->getCurrentUserData() : null;
}

/**
 * Check if user is logged in (thin wrapper — AuthService / current_user).
 *
 * @return bool
 */
function lupo_is_logged_in()
{
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->isLoggedIn() : (function_exists('current_user') ? (current_user() !== false) : false);
}

/**
 * Get current username (thin wrapper — AuthService).
 *
 * @return string
 */
function lupo_get_username()
{
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($s) {
        return $s->getUsername();
    }
    if (function_exists('current_user')) {
        $u = current_user();
        return ($u && is_array($u) && isset($u['username'])) ? $u['username'] : '';
    }
    return '';
}

/**
 * Get current display name (thin wrapper — AuthService).
 *
 * @return string
 */
function lupo_get_display_name()
{
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($s) {
        return $s->getDisplayName();
    }
    if (function_exists('current_user')) {
        $u = current_user();
        if ($u && is_array($u)) {
            if (isset($u['display_name']) && $u['display_name'] !== '') {
                return $u['display_name'];
            }
            if (isset($u['username'])) {
                return $u['username'];
            }
        }
    }
    return '';
}
