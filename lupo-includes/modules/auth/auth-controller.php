<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: module
  when_updated: "20260406035358"
  file_path_from_root: "lupo-includes/modules/auth/auth-controller.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/modules/auth/auth-controller.php"
  last_modified_utc: "20260406035358"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "module"
  artifact_kind: "auth_controller"
  purpose: "Legacy auth routes (/login, /logout, /change-password, /admin); PDO_DB via DatabaseFactory; lupo_sessions metadata only (no $_SESSION authority); $UNTRUSTED for GET/POST/SERVER; lupo_t() errors."
  tags: ["auth", "session", "pdo_db", "untrusted", "model_a"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-controller.php cannot be called directly.");
}
$session_compat_path = (defined('LUPOPEDIA_ABSPATH') ? rtrim(LUPOPEDIA_ABSPATH, DIRECTORY_SEPARATOR) : dirname(dirname(dirname(__DIR__)))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-compat-5.6.php';
if (is_file($session_compat_path)) {
    require_once $session_compat_path;
}

require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/security/password-hash.php');
require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/functions/auth-helpers.php');
require_once(LUPOPEDIA_ABSPATH . 'lupo-includes/functions/redirect-helpers.php');
require_once(__DIR__ . '/auth-renderer.php');

/**
 * Bootstrap locale for lupo_t() in this module.
 *
 * @return void
 */
function auth_controller_ensure_locale()
{
    $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
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
 * §17.8 — Request superglobal snapshots (read once per request).
 *
 * @return array
 */
function lupo_auth_controller_untrusted()
{
    static $u = null;
    if ($u !== null) {
        return $u;
    }
    $u = array(
        'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
        'get' => (isset($_GET) && is_array($_GET)) ? $_GET : array(),
        'post' => (isset($_POST) && is_array($_POST)) ? $_POST : array(),
    );
    return $u;
}

/**
 * @return PDO_DB|null
 */
function lupo_auth_controller_get_db()
{
    if (!class_exists('DatabaseFactory', false) && defined('LUPOPEDIA_ABSPATH')) {
        $df = LUPOPEDIA_ABSPATH . 'lupo-includes/classes/DatabaseFactory.php';
        if (is_file($df)) {
            require_once $df;
        }
    }
    if (class_exists('DatabaseFactory', false)) {
        try {
            return DatabaseFactory::getConnection();
        } catch (Exception $e) {
            return null;
        }
    }
    return null;
}

/**
 * Flash login error via lupo_sessions.metadata (Model A only).
 *
 * @param string $msg
 * @return void
 */
function lupo_auth_controller_set_login_error($msg)
{
    $db = lupo_auth_controller_get_db();
    if ($db && function_exists('lupo_session_metadata_merge_current')) {
        lupo_session_metadata_merge_current($db, array('login_error' => (string) $msg));
    }
}

/**
 * Whether MD5 / forced password change is required (lupo_sessions.metadata only).
 *
 * @return bool
 */
function lupo_auth_controller_password_change_required()
{
    $db = lupo_auth_controller_get_db();
    if ($db && function_exists('lupo_session_metadata_current')) {
        $m = lupo_session_metadata_current($db);
        if (!empty($m['password_change_required'])) {
            return true;
        }
    }
    return false;
}

/**
 * Flash password-change form error into lupo_sessions.metadata.
 *
 * @param string $msg
 * @return void
 */
function lupo_auth_controller_set_password_change_error($msg)
{
    $db = lupo_auth_controller_get_db();
    if ($db && function_exists('lupo_session_metadata_merge_current')) {
        lupo_session_metadata_merge_current($db, array('password_change_error' => (string) $msg));
    }
}

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
function auth_handle_slug($slug)
{
    auth_controller_ensure_locale();

    $slug = ltrim(strtolower($slug), '/');
    $slug = preg_replace('/\.php$/', '', $slug);

    $U = lupo_auth_controller_untrusted();
    $rm = isset($U['server']['REQUEST_METHOD']) ? $U['server']['REQUEST_METHOD'] : '';

    // No mod_rewrite: canonical login is login.php — redirect legacy slug=login to login.php
    if ($slug === 'login') {
        $r = isset($U['get']['redirect']) ? $U['get']['redirect'] : '';
        $target = function_exists('lupo_login_url') ? lupo_login_url($r !== '' ? $r : null) : ((defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '') . '/login.php' . ($r !== '' ? '?' . http_build_query(array('redirect' => $r)) : ''));
        if (!headers_sent()) {
            header('Location: ' . $target, true, 302);
        }
        exit;
    }

    if ($slug === 'logout') {
        return logout_handle();
    }
    if ($slug === 'change-password' || $slug === 'change_password') {
        if ($rm === 'POST') {
            return change_password_handle_post();
        }
        return change_password_handle_view();
    }
    if (strpos($slug, 'admin') === 0) {
        return admin_handle_view($slug);
    }

    return '';
}

/**
 * Handle login form view (GET request)
 *
 * @return string Rendered login form HTML
 */
function login_handle_view()
{
    $U = lupo_auth_controller_untrusted();
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $user = $authService ? $authService->getCurrentUser() : current_user();
    if ($user) {
        $redirect = isset($U['get']['redirect']) ? $U['get']['redirect'] : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/admin' : '/admin');
        if (strpos($redirect, '/') === 0 && strpos($redirect, LUPOPEDIA_PUBLIC_PATH) !== 0 && defined('LUPOPEDIA_PUBLIC_PATH')) {
            $redirect = LUPOPEDIA_PUBLIC_PATH . $redirect;
        }
        lupo_safe_redirect($redirect, 2, 'Login successful! Redirecting...');
    }

    $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
    $dbView = lupo_auth_controller_get_db();
    $metaView = ($dbView && function_exists('lupo_session_metadata_current')) ? lupo_session_metadata_current($dbView) : array();
    $redirect_url = isset($U['get']['redirect']) ? $U['get']['redirect'] : (isset($metaView['login_redirect']) ? $metaView['login_redirect'] : $default_redirect);
    $error_message = isset($metaView['login_error']) ? $metaView['login_error'] : null;
    if ($error_message !== null && $dbView && function_exists('lupo_session_metadata_merge_current')) {
        lupo_session_metadata_merge_current($dbView, array('login_error' => null));
    }

    return login_form($error_message, $redirect_url);
}

/**
 * Handle login form submission (POST request)
 *
 * @return string|void
 */
function login_handle_post()
{
    auth_controller_ensure_locale();
    $U = lupo_auth_controller_untrusted();

    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $email = isset($U['post']['email']) ? trim((string) $U['post']['email']) : '';
    $password = isset($U['post']['password']) ? $U['post']['password'] : '';
    $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
    $redirect = isset($U['post']['redirect']) ? $U['post']['redirect'] : $default_redirect;

    $redirect = filter_var($redirect, FILTER_SANITIZE_URL);
    if (empty($redirect) || strpos($redirect, 'http') === 0) {
        $redirect = $default_redirect;
    }
    if (strpos($redirect, '/') === 0 && strpos($redirect, LUPOPEDIA_PUBLIC_PATH) !== 0 && defined('LUPOPEDIA_PUBLIC_PATH')) {
        $redirect = LUPOPEDIA_PUBLIC_PATH . $redirect;
    }

    $login_url = lupo_login_url();

    if (empty($email) || $password === '') {
        lupo_auth_controller_set_login_error(lupo_t('auth_controller.login_error_required', 'Email and password are required.'));
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        lupo_auth_controller_set_login_error(lupo_t('auth_controller.login_error_invalid_email', 'Invalid email format.'));
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }

    $email = strtolower($email);

    $db = lupo_auth_controller_get_db();
    if (!$db) {
        lupo_auth_controller_set_login_error(lupo_t('auth_controller.login_error_db', 'Database connection error. Please try again later.'));
        header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
        exit;
    }

    $needs_password_change = false;

    try {
        if (defined('LUPO_TABLE_PREFIX')) {
            $table_prefix = LUPO_TABLE_PREFIX;
        } else {
            $table_prefix = str_replace('-', '_', LUPO_PREFIX);
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH DEBUG: Login attempt — table prefix: ' . $table_prefix);
        }

        $sql = "SELECT auth_user_id, username, display_name, email, password_hash, is_active, is_deleted
        FROM {$table_prefix}auth_users
        WHERE email = :email
          AND is_deleted = 0
        LIMIT 1";

        $user = $db->fetchRow($sql, array('email' => $email));

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            if ($user) {
                error_log('AUTH DEBUG: User found — id: ' . (int) $user['auth_user_id']);
            } else {
                error_log('AUTH DEBUG: User not found for submitted email');
            }
        }

        $generic_error = lupo_t('auth_controller.login_error_invalid', 'Invalid email or password.');

        if (!$user) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('AUTH: Failed login (user not found)');
            }
            lupo_auth_controller_set_login_error($generic_error);
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 2, 'Login failed. Redirecting...');
        }

        if ($user['is_active'] != 1) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('AUTH: Failed login (inactive user)');
            }
            lupo_auth_controller_set_login_error(lupo_t('auth_controller.login_error_inactive', 'Your account is inactive. Please contact an administrator.'));
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 2, 'Login failed. Redirecting...');
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $ht = lupo_is_md5_hash($user['password_hash']) ? 'MD5' : (lupo_is_bcrypt_hash($user['password_hash']) ? 'bcrypt' : 'unknown');
            error_log('AUTH DEBUG: Stored hash type: ' . $ht);
        }

        $password_valid = lupo_verify_password($password, $user['password_hash']);

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH DEBUG: Password verification: ' . ($password_valid ? 'valid' : 'invalid'));
        }

        if (!$password_valid) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('AUTH: Failed login (invalid password)');
            }
            lupo_auth_controller_set_login_error($generic_error);
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 2, 'Login failed. Redirecting...');
        }

        $needs_password_change = lupo_password_needs_upgrade($user['password_hash']);
        if ($needs_password_change && defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH: MD5 password — password change required');
        }

        $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
        $actor_id = $actorService ? $actorService->getActorIdFromAuthUserId((int) $user['auth_user_id']) : lupo_get_actor_id_from_auth_user_id($user['auth_user_id']);

        if (!$actor_id) {
            $actor_id = $actorService
                ? $actorService->createActorForAuthUser((int) $user['auth_user_id'], $user['email'], isset($user['display_name']) ? $user['display_name'] : '')
                : lupo_create_actor_for_auth_user($user['auth_user_id'], $user['email'], isset($user['display_name']) ? $user['display_name'] : '');

            if (!$actor_id) {
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log('AUTH ERROR: Failed to create actor');
                }
                lupo_auth_controller_set_login_error(lupo_t('auth_controller.login_error_setup', 'Account setup error. Please contact an administrator.'));
                header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
                exit;
            }
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH DEBUG: Creating session — actor_id: ' . (int) $actor_id . ', php_sid: ' . (session_id() ? 'set' : 'empty'));
        }

        $session_id = $session ? $session->createSession($actor_id, 'password', 'local') : false;

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH DEBUG: Session create: ' . ($session_id ? 'ok' : 'fail'));
        }

        if (!$session_id) {
            $error_details = '';
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                $error_details = lupo_t('auth_controller.login_error_session_debug', 'Session creation failed. Please try again. (Check error logs and page source for details)');
                error_log('AUTH ERROR: Session create failed — actor_id: ' . (int) $actor_id);
                try {
                    $db->fetchRow("SELECT 1 AS ok FROM {$table_prefix}sessions LIMIT 1", array());
                    error_log('AUTH DEBUG: sessions table reachable');
                } catch (Exception $e) {
                    error_log('AUTH ERROR: sessions check: ' . $e->getMessage());
                }
            } else {
                $error_details = lupo_t('auth_controller.login_error_session', 'Session creation failed. Please try again.');
            }
            lupo_auth_controller_set_login_error($error_details);
            lupo_safe_redirect($login_url . '?redirect=' . urlencode($redirect), 3, 'Login failed. Redirecting...');
        }

        $now = $session ? $session->utcTimestamp() : (class_exists('timestamp_ymdhis') ? timestamp_ymdhis::now() : (int) gmdate('YmdHis'));
        $authTable = $table_prefix . 'auth_users';
        $db->update(
            $authTable,
            array(
                'last_login_ymdhis' => $now,
                'updated_ymdhis' => $now,
            ),
            'auth_user_id = :wid',
            array('wid' => $user['auth_user_id'])
        );

        if (!class_exists('App\\Auth\\Session', false)) {
            require_once LUPOPEDIA_ABSPATH . 'app/auth/Session.php';
        }
        if (class_exists('App\\Auth\\Session', false)) {
            App\Auth\Session::mergeSessionMetadata($db, $session_id, array('login_error' => null));
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH: Successful login — actor_id: ' . (int) $actor_id);
        }

        if ($needs_password_change && class_exists('App\\Auth\\Session', false)) {
            $pwMeta = array(
                'password_change_required' => true,
                'password_change_user_id' => (int) $user['auth_user_id'],
                'password_change_actor_id' => (int) $actor_id,
            );
            App\Auth\Session::mergeSessionMetadata($db, $session_id, $pwMeta);
            if ($redirect !== $default_redirect) {
                App\Auth\Session::mergeSessionMetadata($db, $session_id, array('login_redirect' => $redirect));
            }
            $change_password_url = lupo_change_password_url();
            lupo_safe_redirect($change_password_url, 2, 'Password change required. Redirecting...');
        }

        if (class_exists('App\\Auth\\Session', false)) {
            App\Auth\Session::mergeSessionMetadata($db, $session_id, array('login_redirect' => null));
        }

        $site_root = $default_redirect;
        $qa_landing = (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/qa/lupopedia' : '/qa/lupopedia';
        if ($redirect === $site_root || rtrim($redirect, '/') === rtrim($site_root, '/')) {
            $redirect = $qa_landing;
        }

        if (function_exists('session_write_close')) {
            session_write_close();
        }
        lupo_safe_redirect($redirect, 2, 'Login successful! Redirecting...');
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH ERROR: Exception during login — ' . $e->getMessage());
        }
        $login_url_ex = lupo_login_url();
        lupo_auth_controller_set_login_error(lupo_t('auth_controller.login_error_generic', 'An error occurred. Please try again later.'));
        header('Location: ' . $login_url_ex . '?redirect=' . urlencode($redirect));
        exit;
    }
}

/**
 * Handle logout
 *
 * @return void
 */
function logout_handle()
{
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->destroy();
    }
    $home_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
    lupo_safe_redirect($home_url, 2, 'Logged out. Redirecting...');
}

/**
 * Handle password change view (GET request)
 *
 * @return string
 */
function change_password_handle_view()
{
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!lupo_auth_controller_password_change_required()) {
        $login_url = lupo_login_url();
        header('Location: ' . $login_url);
        exit;
    }

    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $user = $authService ? $authService->getCurrentUser() : current_user();
    if (!$user) {
        $login_url = lupo_login_url();
        header('Location: ' . $login_url);
        exit;
    }

    $dbPw = lupo_auth_controller_get_db();
    $error_message = null;
    if ($dbPw && function_exists('lupo_session_metadata_current')) {
        $pm = lupo_session_metadata_current($dbPw);
        $error_message = isset($pm['password_change_error']) ? $pm['password_change_error'] : null;
        if ($error_message !== null && function_exists('lupo_session_metadata_merge_current')) {
            lupo_session_metadata_merge_current($dbPw, array('password_change_error' => null));
        }
    }

    return change_password_form($error_message);
}

/**
 * Handle password change submission (POST request)
 *
 * @return string|void
 */
function change_password_handle_post()
{
    auth_controller_ensure_locale();
    $U = lupo_auth_controller_untrusted();

    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!lupo_auth_controller_password_change_required()) {
        $login_url = lupo_login_url();
        header('Location: ' . $login_url);
        exit;
    }

    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $user = $authService ? $authService->getCurrentUser() : current_user();
    if (!$user) {
        $login_url = lupo_login_url();
        header('Location: ' . $login_url);
        exit;
    }

    $dbPost = lupo_auth_controller_get_db();

    $new_password = isset($U['post']['new_password']) ? $U['post']['new_password'] : '';
    $confirm_password = isset($U['post']['confirm_password']) ? $U['post']['confirm_password'] : '';

    if ($new_password === '' || $confirm_password === '') {
        lupo_auth_controller_set_password_change_error(lupo_t('auth_controller.pw_change_both_required', 'Both password fields are required.'));
        $change_password_url = lupo_change_password_url();
        header('Location: ' . $change_password_url);
        exit;
    }

    if ($new_password !== $confirm_password) {
        lupo_auth_controller_set_password_change_error(lupo_t('auth_controller.pw_change_mismatch', 'Passwords do not match.'));
        $change_password_url = lupo_change_password_url();
        header('Location: ' . $change_password_url);
        exit;
    }

    if (strlen($new_password) < 8) {
        lupo_auth_controller_set_password_change_error(lupo_t('auth_controller.pw_change_short', 'Password must be at least 8 characters long.'));
        $change_password_url = lupo_change_password_url();
        header('Location: ' . $change_password_url);
        exit;
    }

    $db = lupo_auth_controller_get_db();
    if (!$db) {
        lupo_auth_controller_set_password_change_error(lupo_t('auth_controller.pw_change_db', 'Database connection error. Please try again later.'));
        $change_password_url = lupo_change_password_url();
        header('Location: ' . $change_password_url);
        exit;
    }

    try {
        $new_hash = lupo_hash_password($new_password);
        if (!$new_hash) {
            lupo_auth_controller_set_password_change_error(lupo_t('auth_controller.pw_change_hash', 'Error hashing password. Please try again.'));
            $change_password_url = lupo_change_password_url();
            lupo_safe_redirect($change_password_url, 2, 'Password change required. Redirecting...');
        }

        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);
        $s = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
        $now = $s ? $s->utcTimestamp() : (class_exists('timestamp_ymdhis') ? timestamp_ymdhis::now() : (int) gmdate('YmdHis'));
        $authTable = $table_prefix . 'auth_users';
        $db->update(
            $authTable,
            array(
                'password_hash' => $new_hash,
                'updated_ymdhis' => $now,
            ),
            'auth_user_id = :wid',
            array('wid' => $user['auth_user_id'])
        );

        $default_redirect = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
        $metaAfter = ($dbPost && function_exists('lupo_session_metadata_current')) ? lupo_session_metadata_current($dbPost) : array();
        $redirect_url = isset($metaAfter['login_redirect']) ? $metaAfter['login_redirect'] : $default_redirect;

        if (!class_exists('App\\Auth\\Session', false)) {
            require_once LUPOPEDIA_ABSPATH . 'app/auth/Session.php';
        }
        if ($dbPost && class_exists('App\\Auth\\Session', false)) {
            $appS = new App\Auth\Session($dbPost);
            $sidClr = $appS->getSessionId();
            if ($sidClr) {
                App\Auth\Session::mergeSessionMetadata($dbPost, $sidClr, array(
                    'password_change_required' => null,
                    'password_change_user_id' => null,
                    'password_change_actor_id' => null,
                    'password_change_error' => null,
                    'login_redirect' => null,
                ));
            }
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH: Password upgraded to bcrypt');
        }

        $qa_landing = (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/qa/lupopedia' : '/qa/lupopedia';
        if ($redirect_url === $default_redirect || rtrim($redirect_url, '/') === rtrim($default_redirect, '/')) {
            $redirect_url = $qa_landing;
        }

        lupo_safe_redirect($redirect_url, 2, 'Password changed successfully! Redirecting...');
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('AUTH ERROR: Exception during password change — ' . $e->getMessage());
        }
        lupo_auth_controller_set_password_change_error(lupo_t('auth_controller.pw_change_generic', 'An error occurred. Please try again later.'));
        $change_password_url = lupo_change_password_url();
        header('Location: ' . $change_password_url);
        exit;
    }
}

/**
 * Handle admin dashboard view
 *
 * @param string $slug
 * @return string
 */
function admin_handle_view($slug)
{
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session) {
        $session->start();
    } elseif (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (lupo_auth_controller_password_change_required()) {
        $change_password_url = lupo_change_password_url();
        header('Location: ' . $change_password_url);
        exit;
    }

    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService) {
        $authService->requireAdmin();
    } else {
        require_admin();
    }
    $user = $authService ? $authService->getCurrentUser() : current_user();

    return admin_dashboard($user);
}

?>
