<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: module
  when_updated: "20260406014900"
  file_path_from_root: "includes/modules/actors/actors-controller.php"
  web_path: "http://www.lupopedia.com/lupopedia/includes/modules/actors/actors-controller.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "module"
  artifact_kind: "actors_controller"
  purpose: "My Profile GET/POST; PDO_DB via lupo_get_db; IdGenerator PKs; pending email 2FA OTP on lupo_auth_users (otp_* columns, HMAC); lupo_t UI strings."
  tags: ["actors", "profile", "pdo_db", "IdGenerator", "timestamp_ymdhis", "security"]
---
*/

/**
 * Actors controller — My Profile and related actor UI.
 * Uses basic template (top graphic + drop menu + content). Does NOT use content system or render_main_layout.
 * Routes: GET /my-profile, POST /my-profile/save. Rendered via basic_layout.php (UI interface with navigation).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

/** Max failed OTP checks before clearing pending (auth-layer brute-force throttle). */
if (!defined('ACTORS_PROFILE_OTP_MAX_ATTEMPTS')) {
    define('ACTORS_PROFILE_OTP_MAX_ATTEMPTS', 5);
}

/** Max avatar upload size (bytes). */
if (!defined('ACTORS_PROFILE_AVATAR_MAX_BYTES')) {
    define('ACTORS_PROFILE_AVATAR_MAX_BYTES', 5242880);
}

/**
 * Bootstrap locale for lupo_t() in this module.
 *
 * @return void
 */
function actors_controller_ensure_locale()
{
    $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    if ($root === '') {
        return;
    }
    if (!class_exists('LupoLocale', false)) {
        $p = $root . '/includes/classes/LupoLocale.php';
        if (is_file($p)) {
            require_once $p;
        }
    }
    if (class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
        LupoLocale::bootstrap($root);
    }
    if (!function_exists('lupo_t')) {
        $i18n = $root . '/includes/i18n.php';
        if (is_file($i18n)) {
            require_once $i18n;
        }
    }
}

/**
 * Canonical PDO_DB connection (DatabaseFactory / global).
 *
 * @return PDO_DB|null
 */
function actors_profile_get_db()
{
    if (!defined('LUPOPEDIA_PATH')) {
        return null;
    }
    if (!function_exists('lupo_get_db')) {
        $df = LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
        if (defined('LUPOPEDIA_CONFIG_LOADED') && is_file($df)) {
            require_once $df;
        }
    }
    if (!function_exists('lupo_get_db')) {
        return null;
    }
    try {
        $db = lupo_get_db();
    } catch (Exception $e) {
        return null;
    }
    return ($db instanceof PDO_DB) ? $db : null;
}

/**
 * @return void
 */
function actors_profile_require_timestamp_class()
{
    if (!class_exists('timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
        require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
    }
}

/**
 * @return void
 */
function actors_profile_require_id_generator()
{
    if (!class_exists('IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
        require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
    }
}

/**
 * Packed UTC int for BIGINT columns.
 *
 * @return int
 */
function actors_profile_now_packed_int()
{
    actors_profile_require_timestamp_class();
    if (class_exists('timestamp_ymdhis', false)) {
        return timestamp_ymdhis::now();
    }
    return (int) gmdate('YmdHis');
}

/**
 * @return string
 */
function actors_profile_now_packed_string()
{
    return (string) actors_profile_now_packed_int();
}

/**
 * Resolve current actor_id from auth service / session.
 *
 * @return int
 */
function actors_profile_resolve_actor_id()
{
    $actor_id = 0;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService) {
        $user = $authService->getCurrentUser();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    } elseif (function_exists('current_user')) {
        $user = current_user();
        if ($user && !empty($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && ($s = (isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null))) {
        $actor_id = (int) $s->validateSession();
    }
    return $actor_id;
}

/**
 * @param PDO_DB $db
 * @param string $table_prefix
 * @param int $actor_id
 * @return array|null auth_user_id, email
 */
function actors_profile_get_auth_row_for_actor($db, $table_prefix, $actor_id)
{
    $au = $db->quoteIdentifier($table_prefix . 'auth_users');
    $ac = $db->quoteIdentifier($table_prefix . 'actors');
    return $db->fetchRow(
        "SELECT au.auth_user_id, au.email, au.two_factor_enabled, au.otp_code_hash, au.otp_issued_ymdhis, au.otp_attempts "
        . "FROM {$au} au INNER JOIN {$ac} a ON a.actor_id = au.auth_user_id "
        . "WHERE a.actor_id = :actor_id AND a.is_deleted = 0 AND au.is_deleted = 0 LIMIT 1",
        array('actor_id' => $actor_id)
    );
}

/**
 * HMAC key for email OTP (auth-layer secret; not stored in DB).
 *
 * @return string
 */
function actors_profile_otp_hmac_key()
{
    if (defined('SECURE_AUTH_KEY') && SECURE_AUTH_KEY !== '') {
        return SECURE_AUTH_KEY;
    }
    if (defined('AUTH_KEY') && AUTH_KEY !== '') {
        return AUTH_KEY;
    }
    return 'lupopedia_otp_hmac_fallback';
}

/**
 * Hash a 6-digit OTP for storage on lupo_auth_users (plain code never persisted).
 *
 * @param string $code
 * @param int $auth_user_id
 * @return string
 */
function actors_profile_hash_email_otp($code, $auth_user_id)
{
    return hash_hmac('sha256', (string) $code . '|' . (string) $auth_user_id, actors_profile_otp_hmac_key());
}

/**
 * @param array $authRow Row from actors_profile_get_auth_row_for_actor (otp_* columns).
 * @return bool
 */
function actors_profile_auth_row_has_email_otp_pending($authRow)
{
    if (!is_array($authRow)) {
        return false;
    }
    $h = isset($authRow['otp_code_hash']) ? (string) $authRow['otp_code_hash'] : '';
    $issued = isset($authRow['otp_issued_ymdhis']) ? (int) $authRow['otp_issued_ymdhis'] : 0;
    return ($h !== '' && $issued > 0);
}

/**
 * Clear pending email OTP columns on auth user (§5.3 auth-layer state).
 *
 * @param PDO_DB $db
 * @param string $auth_users_table
 * @param int $auth_user_id
 * @param string $nowStr
 * @return void
 */
function actors_profile_clear_auth_email_otp($db, $auth_users_table, $auth_user_id, $nowStr)
{
    if ($auth_user_id <= 0) {
        return;
    }
    $db->update(
        $auth_users_table,
        array(
            'otp_code_hash' => '',
            'otp_issued_ymdhis' => 0,
            'otp_attempts' => 0,
            'updated_ymdhis' => $nowStr,
        ),
        'auth_user_id = :id',
        array('id' => $auth_user_id)
    );
}

/**
 * Store pending email OTP (hashed) on lupo_auth_users.
 *
 * @param PDO_DB $db
 * @param string $auth_users_table
 * @param int $auth_user_id
 * @param string $plain_code
 * @param int $issuedInt
 * @param string $nowStr
 * @return void
 */
function actors_profile_save_auth_email_otp($db, $auth_users_table, $auth_user_id, $plain_code, $issuedInt, $nowStr)
{
    if ($auth_user_id <= 0) {
        return;
    }
    $hash = actors_profile_hash_email_otp($plain_code, $auth_user_id);
    $db->update(
        $auth_users_table,
        array(
            'otp_code_hash' => $hash,
            'otp_issued_ymdhis' => $issuedInt,
            'otp_attempts' => 0,
            'updated_ymdhis' => $nowStr,
        ),
        'auth_user_id = :id',
        array('id' => $auth_user_id)
    );
}

/**
 * @return string six-digit string
 */
function actors_profile_random_otp_digits()
{
    if (function_exists('random_bytes')) {
        $b = random_bytes(4);
    } else {
        $b = openssl_random_pseudo_bytes(4);
        if ($b === false || strlen($b) < 4) {
            return '000000';
        }
    }
    $n = 0;
    for ($i = 0; $i < 4; $i++) {
        $n = ($n << 8) | ord($b[$i]);
    }
    $n = $n & 0x7fffffff;
    return str_pad((string) ($n % 1000000), 6, '0', STR_PAD_LEFT);
}

/**
 * True if SMTP or PHP mail() may be used (no hardcoded localhost assumption).
 *
 * @return bool
 */
function actors_profile_mail_may_attempt()
{
    if (defined('LUPO_SMTP_HOST') && LUPO_SMTP_HOST !== '') {
        return true;
    }
    if (function_exists('mail')) {
        return true;
    }
    return false;
}

/**
 * @param string $app_root
 * @param string $to
 * @param string $subject
 * @param string $body
 * @return bool
 */
function actors_profile_send_mail($app_root, $to, $subject, $body)
{
    if (defined('LUPO_SMTP_HOST') && LUPO_SMTP_HOST !== '') {
        if (!is_file($app_root . '/includes/PHPMailer/PHPMailer.php')) {
            return false;
        }
        require_once $app_root . '/includes/PHPMailer/PHPMailer.php';
        require_once $app_root . '/includes/PHPMailer/SMTP.php';
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = LUPO_SMTP_HOST;
        $mail->Port = defined('LUPO_SMTP_PORT') ? (int) LUPO_SMTP_PORT : 587;
        if (defined('LUPO_SMTP_USER') && LUPO_SMTP_USER !== '') {
            $mail->SMTPAuth = true;
            $mail->Username = LUPO_SMTP_USER;
            $mail->Password = defined('LUPO_SMTP_PASSWORD') ? LUPO_SMTP_PASSWORD : '';
        }
        $from = defined('LUPO_MAIL_FROM') ? LUPO_MAIL_FROM : 'no-reply@localhost';
        $fromName = defined('LUPO_MAIL_FROM_NAME') ? LUPO_MAIL_FROM_NAME : 'Lupopedia';
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        return $mail->send();
    }
    if (function_exists('mail')) {
        $headers = 'From: ' . (defined('LUPO_MAIL_FROM') ? LUPO_MAIL_FROM : 'no-reply@localhost') . "\r\n";
        return @mail($to, $subject, $body, $headers);
    }
    return false;
}

/**
 * Ensure upload directory exists; do not chmod.
 *
 * @param string $upload_dir OS path
 * @return bool
 */
function actors_profile_ensure_upload_dir($upload_dir)
{
    if (is_dir($upload_dir)) {
        return is_writable($upload_dir);
    }
    if (!@mkdir($upload_dir, 0755, true)) {
        return false;
    }
    return is_dir($upload_dir) && is_writable($upload_dir);
}

/**
 * @param string $dirReal
 * @param string $uploadsRootReal
 * @return bool
 */
function actors_profile_path_under_uploads($dirReal, $uploadsRootReal)
{
    if ($dirReal === false || $uploadsRootReal === false) {
        return false;
    }
    $base = rtrim(str_replace('\\', '/', $uploadsRootReal), '/');
    $path = rtrim(str_replace('\\', '/', $dirReal), '/');
    if ($path === $base) {
        return true;
    }
    $need = $base . '/';
    return (strlen($path) > strlen($base) && strpos($path, $need) === 0);
}

/**
 * Handle GET /my-profile — show current actor's profile form.
 * Loads actor from lupo_actors, properties from lupo_metadata, computes avatar path.
 * Renders via basic_layout.php (top graphic, drop menu, content) so My Profile has full UI navigation.
 *
 * @return string HTML (basic template with navigation)
 */
function actors_handle_my_profile()
{
    actors_controller_ensure_locale();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $basic_layout_path = $app_root . '/includes/themes/default/layouts/basic_layout.php';

    $actor_id = actors_profile_resolve_actor_id();
    if (!$actor_id) {
        $after = function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')));
        $login_url = function_exists('lupo_login_url') ? lupo_login_url($after) : (rtrim($base, '/') . '/login.php?redirect=' . urlencode($after));
        $msg = function_exists('lupo_t') ? lupo_t('actors.profile.sign_in_redirect', 'Please sign in to view your profile.') : 'Please sign in to view your profile.';
        if (function_exists('lupo_safe_redirect')) {
            lupo_safe_redirect($login_url, 0, $msg);
        } else {
            header('Location: ' . $login_url);
        }
        exit;
    }

    $db = actors_profile_get_db();
    if (!$db) {
        error_log('My Profile: Database not available');
        $content = array('title' => function_exists('lupo_t') ? lupo_t('actors.profile.title', 'My Profile') : 'My Profile', 'hide_heading' => true);
        $page_body = '<p>' . (function_exists('lupo_t') ? lupo_t('actors.profile.db_unavailable', 'Database unavailable.') : 'Database unavailable.') . '</p>';
        $isUserLoggedIn = true;
        ob_start();
        include $basic_layout_path;
        return ob_get_clean();
    }

    $metadata_table = $table_prefix . 'metadata';
    $auth_user_id = null;
    $current_email = null;
    $authRow = actors_profile_get_auth_row_for_actor($db, $table_prefix, $actor_id);
    $two_factor_enabled = 0;
    if ($authRow) {
        $auth_user_id = (int) $authRow['auth_user_id'];
        $current_email = isset($authRow['email']) ? $authRow['email'] : null;
        if (isset($authRow['two_factor_enabled'])) {
            $two_factor_enabled = (int) $authRow['two_factor_enabled'];
        }
    }
    $profile_2fa_pending = actors_profile_auth_row_has_email_otp_pending($authRow);

    $actor = function_exists('lupo_get_actor') ? lupo_get_actor($actor_id) : null;
    if (!$actor) {
        $actors_table = $table_prefix . 'actors';
        $actor = $db->fetchRow(
            "SELECT actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, avatar_hash, metadata FROM "
            . $db->quoteIdentifier($actors_table) . " WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id)
        );
    }
    if (!$actor) {
        error_log('My Profile: Actor not found for actor_id: ' . $actor_id);
        $content = array('title' => function_exists('lupo_t') ? lupo_t('actors.profile.title', 'My Profile') : 'My Profile', 'hide_heading' => true);
        $page_body = '<p>' . (function_exists('lupo_t') ? lupo_t('actors.profile.actor_not_found', 'Actor not found.') : 'Actor not found.') . '</p>';
        $isUserLoggedIn = true;
        ob_start();
        include $basic_layout_path;
        return ob_get_clean();
    }

    $actor_properties = array();
    $rows = $db->fetchAll(
        "SELECT property_key, property_value FROM " . $db->quoteIdentifier($metadata_table)
        . " WHERE entity_type = 'actor' AND entity_id = :actor_id AND is_deleted = 0",
        array('actor_id' => $actor_id)
    );
    if (is_array($rows)) {
        foreach ($rows as $row) {
            if (isset($row['property_key'])) {
                $actor_properties[$row['property_key']] = isset($row['property_value']) ? $row['property_value'] : '';
            }
        }
    }

    $avatar_public_path = '';
    $avatar_storage = isset($actor_properties['avatar_storage_path']) ? $actor_properties['avatar_storage_path'] : '';
    if ($avatar_storage !== '') {
        $avatar_public_path = $base . '/uploads/' . ltrim($avatar_storage, '/');
    } elseif (!empty($actor['avatar_hash'])) {
        actors_profile_require_timestamp_class();
        $ym = gmdate('Y/m');
        $avatar_public_path = $base . '/uploads/actors/' . $ym . '/' . $actor['avatar_hash'];
    }

    // Flash error (read once; view must not touch $_SESSION for this key)
    $profile_error = '';
    if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE && isset($_SESSION) && is_array($_SESSION) && isset($_SESSION['profile_error'])) {
        $pe = $_SESSION['profile_error'];
        if (is_string($pe)) {
            $profile_error = htmlspecialchars($pe, ENT_QUOTES, 'UTF-8');
        }
        unset($_SESSION['profile_error']);
    }

    // Current actor display + act-as list (AuthSessionManager only in controller; mapping helpers are deprecated but authoritative for cleanup + department scope)
    $profile_current_actor_name = function_exists('lupo_t') ? lupo_t('profile.actor_unknown', 'Unknown') : 'Unknown';
    if (!empty($actor['name'])) {
        $profile_current_actor_name = $actor['name'];
    } elseif (!empty($actor['actor_name'])) {
        $profile_current_actor_name = $actor['actor_name'];
    }
    $profile_user_can_switch_actors = false;
    if ($auth_user_id !== null && (int) $auth_user_id > 0) {
        $asmPath = $app_root . '/includes/classes/AuthSessionManager.php';
        if (is_file($asmPath)) {
            require_once $asmPath;
        }
        if (class_exists('AuthSessionManager')) {
            $asm = new AuthSessionManager();
            $mapped = $asm->getActorForAuthUser((int) $auth_user_id);
            if (is_array($mapped)) {
                if (!empty($mapped['name'])) {
                    $profile_current_actor_name = $mapped['name'];
                } elseif (!empty($mapped['actor_name'])) {
                    $profile_current_actor_name = $mapped['actor_name'];
                }
            }
            $allowed = $asm->getActorsUserCanActAs((int) $auth_user_id, false);
            $profile_user_can_switch_actors = is_array($allowed) && count($allowed) > 1;
        }
    }

    $view_path = $app_root . '/includes/modules/actors/views/my-profile.php';
    if (!file_exists($view_path)) {
        error_log('My Profile: View file not found at: ' . $view_path);
        $content = array('title' => function_exists('lupo_t') ? lupo_t('actors.profile.title', 'My Profile') : 'My Profile', 'hide_heading' => true);
        $page_body = '<p>' . (function_exists('lupo_t') ? lupo_t('actors.profile.view_not_found', 'Profile view not found.') : 'Profile view not found.') . '</p>';
        $isUserLoggedIn = true;
        ob_start();
        include $basic_layout_path;
        return ob_get_clean();
    }

    ob_start();
    extract(array(
        'actor' => $actor,
        'actor_id' => $actor_id,
        'auth_user_id' => $auth_user_id !== null ? (int) $auth_user_id : 0,
        'current_email' => $current_email,
        'actor_properties' => $actor_properties,
        'avatar_public_path' => $avatar_public_path,
        'base' => $base,
        'two_factor_enabled' => $two_factor_enabled,
        'profile_2fa_pending' => $profile_2fa_pending,
        'profile_error' => $profile_error,
        'profile_current_actor_name' => $profile_current_actor_name,
        'profile_user_can_switch_actors' => $profile_user_can_switch_actors,
    ), EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();
    $content = array('title' => function_exists('lupo_t') ? lupo_t('actors.profile.title', 'My Profile') : 'My Profile', 'hide_heading' => true);
    $isUserLoggedIn = true;

    ob_start();
    include $basic_layout_path;
    return ob_get_clean();
}

/**
 * Handle POST /my-profile/save — save actor name, properties, and optional avatar upload.
 * Uses PDO_DB, IdGenerator for new metadata/upload PKs; pending email 2FA OTP on lupo_auth_users (not $_SESSION).
 *
 * @return void Redirects to /my-profile
 */
function actors_handle_my_profile_save()
{
    actors_controller_ensure_locale();

    $UNTRUSTED = array(
        'post' => isset($_POST) && is_array($_POST) ? $_POST : array(),
        'files' => isset($_FILES) && is_array($_FILES) ? $_FILES : array(),
    );

    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';

    $actor_id = actors_profile_resolve_actor_id();
    if (!$actor_id) {
        $after = function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')));
        header('Location: ' . (function_exists('lupo_login_url') ? lupo_login_url($after) : (rtrim($base, '/') . '/login.php?redirect=' . urlencode($after))));
        exit;
    }

    $db = actors_profile_get_db();
    if (!$db) {
        header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
        exit;
    }

    $actors_table = $table_prefix . 'actors';
    $metadata_table = $table_prefix . 'metadata';
    $uploads_table = $table_prefix . 'uploads';
    $auth_users_table = $table_prefix . 'auth_users';
    $nowStr = actors_profile_now_packed_string();
    $nowInt = actors_profile_now_packed_int();

    $authRow = actors_profile_get_auth_row_for_actor($db, $table_prefix, $actor_id);
    $auth_user_id = ($authRow && isset($authRow['auth_user_id'])) ? (int) $authRow['auth_user_id'] : 0;
    $current_email = ($authRow && isset($authRow['email'])) ? trim((string) $authRow['email']) : '';

    if (isset($UNTRUSTED['post']['2fa_action'])) {
        $action = (string) $UNTRUSTED['post']['2fa_action'];
        if ($action === 'start') {
            if ($auth_user_id <= 0 || $current_email === '' || !filter_var($current_email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_no_email', 'No valid email on file for this account.')
                    : 'No valid email on file for this account.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            if (!actors_profile_mail_may_attempt()) {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.mail_not_configured', 'Mail is not configured. Set LUPO_SMTP_HOST or enable PHP mail().')
                    : 'Mail is not configured.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            $code = actors_profile_random_otp_digits();
            actors_profile_save_auth_email_otp($db, $auth_users_table, $auth_user_id, $code, $nowInt, $nowStr);
            $subj = function_exists('lupo_t') ? lupo_t('actors.profile.2fa_email_subject', 'Your verification code') : 'Your verification code';
            $bodyTpl = function_exists('lupo_t')
                ? lupo_t('actors.profile.2fa_email_body', "Your verification code is: %s\n\nIf you did not request this, ignore this email.")
                : "Your verification code is: %s\n\nIf you did not request this, ignore this email.";
            $body = sprintf($bodyTpl, $code);
            if (!actors_profile_send_mail($app_root, $current_email, $subj, $body)) {
                actors_profile_clear_auth_email_otp($db, $auth_users_table, $auth_user_id, $nowStr);
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_send_failed', 'Failed to send verification email. Contact support.')
                    : 'Failed to send verification email.';
            } else {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_sent', 'Verification code sent to your email.')
                    : 'Verification code sent to your email.';
            }
            header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
            exit;
        }
        if ($action === 'verify') {
            $input_code = isset($UNTRUSTED['post']['2fa_code']) ? trim((string) $UNTRUSTED['post']['2fa_code']) : '';
            if (!preg_match('/^\d{6}$/', $input_code)) {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_invalid_format', 'Enter the 6-digit code.')
                    : 'Invalid or expired code.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            if ($auth_user_id <= 0) {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_invalid', 'Invalid or expired code.')
                    : 'Invalid or expired code.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            $otpRow = $db->fetchRow(
                "SELECT otp_code_hash, otp_issued_ymdhis, otp_attempts FROM " . $db->quoteIdentifier($auth_users_table)
                . " WHERE auth_user_id = :id AND is_deleted = 0 LIMIT 1",
                array('id' => $auth_user_id)
            );
            $hasPending = ($otpRow && isset($otpRow['otp_code_hash'], $otpRow['otp_issued_ymdhis'])
                && (string) $otpRow['otp_code_hash'] !== ''
                && (int) $otpRow['otp_issued_ymdhis'] > 0);
            if (!$hasPending) {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_invalid', 'Invalid or expired code.')
                    : 'Invalid or expired code.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            actors_profile_require_timestamp_class();
            $ageOk = false;
            if (class_exists('timestamp_ymdhis', false)) {
                $issued = (int) $otpRow['otp_issued_ymdhis'];
                $ageOk = ($issued > 0 && timestamp_ymdhis::diffInSeconds(timestamp_ymdhis::now(), $issued) < 600);
            }
            if (!$ageOk) {
                actors_profile_clear_auth_email_otp($db, $auth_users_table, $auth_user_id, $nowStr);
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_invalid', 'Invalid or expired code.')
                    : 'Invalid or expired code.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            $expectedHash = actors_profile_hash_email_otp($input_code, $auth_user_id);
            $valid = hash_equals((string) $otpRow['otp_code_hash'], $expectedHash);
            if ($valid) {
                $db->update(
                    $auth_users_table,
                    array(
                        'two_factor_enabled' => 1,
                        'otp_code_hash' => '',
                        'otp_issued_ymdhis' => 0,
                        'otp_attempts' => 0,
                        'updated_ymdhis' => $nowStr,
                    ),
                    'auth_user_id = :id',
                    array('id' => $auth_user_id)
                );
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_enabled', 'Two-factor authentication enabled.')
                    : 'Two-factor authentication enabled.';
            } else {
                $attempts = isset($otpRow['otp_attempts']) ? (int) $otpRow['otp_attempts'] : 0;
                $attempts++;
                if ($attempts >= ACTORS_PROFILE_OTP_MAX_ATTEMPTS) {
                    actors_profile_clear_auth_email_otp($db, $auth_users_table, $auth_user_id, $nowStr);
                } else {
                    $db->update(
                        $auth_users_table,
                        array('otp_attempts' => $attempts, 'updated_ymdhis' => $nowStr),
                        'auth_user_id = :id',
                        array('id' => $auth_user_id)
                    );
                }
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.2fa_invalid', 'Invalid or expired code.')
                    : 'Invalid or expired code.';
            }
            header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
            exit;
        }
        if ($action === 'disable') {
            if ($auth_user_id > 0) {
                $db->update(
                    $auth_users_table,
                    array(
                        'two_factor_enabled' => 0,
                        'otp_code_hash' => '',
                        'otp_issued_ymdhis' => 0,
                        'otp_attempts' => 0,
                        'updated_ymdhis' => $nowStr,
                    ),
                    'auth_user_id = :id',
                    array('id' => $auth_user_id)
                );
            }
            $_SESSION['profile_error'] = function_exists('lupo_t')
                ? lupo_t('actors.profile.2fa_disabled', 'Two-factor authentication disabled.')
                : 'Two-factor authentication disabled.';
            header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
            exit;
        }
    }

    $actor_name = isset($UNTRUSTED['post']['actor_name']) ? trim((string) $UNTRUSTED['post']['actor_name']) : '';
    if ($actor_name !== '') {
        $db->update($actors_table, array('name' => $actor_name, 'updated_ymdhis' => $nowStr), 'actor_id = :actor_id', array('actor_id' => $actor_id));
    }

    $email = isset($UNTRUSTED['post']['email']) ? trim((string) $UNTRUSTED['post']['email']) : '';
    if ($email !== '') {
        $email = strtolower($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['profile_error'] = function_exists('lupo_t')
                ? lupo_t('actors.profile.email_invalid', 'Invalid email format.')
                : 'Invalid email format.';
            header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
            exit;
        }
        $auth_users_table = $table_prefix . 'auth_users';
        $current_auth = $db->fetchRow(
            "SELECT au.auth_user_id FROM " . $db->quoteIdentifier($auth_users_table) . " au "
            . "INNER JOIN " . $db->quoteIdentifier($actors_table) . " a ON a.actor_id = au.auth_user_id "
            . "WHERE a.actor_id = :actor_id AND a.is_deleted = 0 AND au.is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id)
        );
        if ($current_auth && isset($current_auth['auth_user_id'])) {
            $existing = $db->fetchRow(
                "SELECT auth_user_id FROM " . $db->quoteIdentifier($auth_users_table)
                . " WHERE email = :email AND is_deleted = 0 AND auth_user_id != :cur LIMIT 1",
                array('email' => $email, 'cur' => $current_auth['auth_user_id'])
            );
            if ($existing && isset($existing['auth_user_id'])) {
                $_SESSION['profile_error'] = function_exists('lupo_t')
                    ? lupo_t('actors.profile.email_in_use', 'Email address is already in use by another user.')
                    : 'Email address is already in use.';
                header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
                exit;
            }
            $db->update(
                $auth_users_table,
                array('email' => $email, 'updated_ymdhis' => $nowStr),
                'auth_user_id = :auth_user_id',
                array('auth_user_id' => $current_auth['auth_user_id'])
            );
        } else {
            $_SESSION['profile_error'] = function_exists('lupo_t')
                ? lupo_t('actors.profile.email_validate_failed', 'Unable to validate email uniqueness. Please try again.')
                : 'Unable to validate email.';
            header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
            exit;
        }
    }

    foreach ($UNTRUSTED['post'] as $key => $value) {
        if (strpos($key, 'prop_') !== 0) {
            continue;
        }
        $property_key = substr($key, 5);
        if ($property_key === '') {
            continue;
        }
        $property_key = preg_replace('/[^a-zA-Z0-9_-]/', '', $property_key);
        if ($property_key === '') {
            continue;
        }
        $property_value = is_string($value) ? trim($value) : (string) $value;
        $existing = $db->fetchRow(
            "SELECT metadata_id FROM " . $db->quoteIdentifier($metadata_table)
            . " WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = :pk AND is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id, 'pk' => $property_key)
        );
        if ($existing && isset($existing['metadata_id'])) {
            $db->update(
                $metadata_table,
                array('property_value' => $property_value, 'updated_ymdhis' => $nowStr),
                'metadata_id = :id',
                array('id' => $existing['metadata_id'])
            );
        } else {
            actors_profile_require_id_generator();
            $newId = IdGenerator::generate();
            $db->insert($metadata_table, array(
                'metadata_id' => $newId,
                'entity_type' => 'actor',
                'entity_id' => $actor_id,
                'domain_id' => null,
                'meta_type' => null,
                'property_key' => $property_key,
                'property_value' => $property_value,
                'created_ymdhis' => $nowStr,
                'updated_ymdhis' => $nowStr,
                'is_deleted' => 0,
            ));
        }
    }

    $avatarFile = isset($UNTRUSTED['files']['avatar']) ? $UNTRUSTED['files']['avatar'] : null;
    if (is_array($avatarFile) && !empty($avatarFile['tmp_name']) && is_uploaded_file($avatarFile['tmp_name'])) {
        $size = isset($avatarFile['size']) ? (int) $avatarFile['size'] : 0;
        if ($size > 0 && $size <= ACTORS_PROFILE_AVATAR_MAX_BYTES) {
            $ym = gmdate('Y') . '/' . gmdate('m');
            $upload_dir = $app_root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $ym);
            $uploads_root = realpath($app_root . DIRECTORY_SEPARATOR . 'uploads');
            if (actors_profile_ensure_upload_dir($upload_dir)) {
                $dirReal = realpath($upload_dir);
                if (actors_profile_path_under_uploads($dirReal, $uploads_root)) {
                    $ext = strtolower(pathinfo(isset($avatarFile['name']) ? $avatarFile['name'] : '', PATHINFO_EXTENSION));
                    if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'), true)) {
                        $ext = 'jpg';
                    }
                    actors_profile_require_id_generator();
                    $stored_filename = 'avatar_' . $actor_id . '_' . IdGenerator::generate() . '.' . $ext;
                    $full_path = $upload_dir . DIRECTORY_SEPARATOR . $stored_filename;
                    if (move_uploaded_file($avatarFile['tmp_name'], $full_path)) {
                        $finalReal = realpath($full_path);
                        if ($finalReal !== false && actors_profile_path_under_uploads(dirname($finalReal), $uploads_root)) {
                            $storage_path = 'actors/' . $ym . '/' . $stored_filename;
                            $mime = (isset($avatarFile['type']) && $avatarFile['type'] !== '') ? $avatarFile['type'] : 'image/jpeg';
                            $original = basename(isset($avatarFile['name']) ? $avatarFile['name'] : 'avatar');

                            $upload_new_id = IdGenerator::generate();
                            $db->insert($uploads_table, array(
                                'upload_id' => $upload_new_id,
                                'actor_id' => $actor_id,
                                'channel_id' => null,
                                'original_filename' => $original,
                                'stored_filename' => $stored_filename,
                                'file_extension' => $ext,
                                'mime_type' => $mime,
                                'file_size_bytes' => $size,
                                'storage_path' => $storage_path,
                                'metadata_json' => null,
                                'created_ymdhis' => $nowStr,
                                'updated_ymdhis' => $nowStr,
                                'is_deleted' => 0,
                            ));

                            $db->update(
                                $actors_table,
                                array('avatar_hash' => $stored_filename, 'updated_ymdhis' => $nowStr),
                                'actor_id = :actor_id',
                                array('actor_id' => $actor_id)
                            );

                            $ex = $db->fetchRow(
                                "SELECT metadata_id FROM " . $db->quoteIdentifier($metadata_table)
                                . " WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = 'avatar_storage_path' AND is_deleted = 0 LIMIT 1",
                                array('actor_id' => $actor_id)
                            );
                            if ($ex && isset($ex['metadata_id'])) {
                                $db->update(
                                    $metadata_table,
                                    array('property_value' => $storage_path, 'updated_ymdhis' => $nowStr),
                                    'metadata_id = :id',
                                    array('id' => $ex['metadata_id'])
                                );
                            } else {
                                $meta_new_id = IdGenerator::generate();
                                $db->insert($metadata_table, array(
                                    'metadata_id' => $meta_new_id,
                                    'entity_type' => 'actor',
                                    'entity_id' => $actor_id,
                                    'domain_id' => null,
                                    'meta_type' => null,
                                    'property_key' => 'avatar_storage_path',
                                    'property_value' => $storage_path,
                                    'created_ymdhis' => $nowStr,
                                    'updated_ymdhis' => $nowStr,
                                    'is_deleted' => 0,
                                ));
                            }
                        }
                    }
                }
            }
        }
    }

    header('Location: ' . (function_exists('lupo_index_slug_url') ? lupo_index_slug_url('my-profile') : (rtrim($base, '/') . '/index.php?' . http_build_query(array('slug' => 'my-profile')))));
    exit;
}
