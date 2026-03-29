<?php
/**
 * Actors controller — My Profile and related actor UI.
 * Uses basic template (top graphic + drop menu + content). Does NOT use content system or render_main_layout.
 * Routes: GET /my-profile, POST /my-profile/save. Rendered via basic_layout.php (UI interface with navigation).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
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
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $basic_layout_path = $app_root . '/lupo-includes/themes/default/layouts/basic_layout.php';

    $actor_id = null;
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
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        $login_url = $base . '/login?redirect=' . urlencode($base . '/my-profile');
        if (function_exists('lupo_safe_redirect')) {
            lupo_safe_redirect($login_url, 0, 'Please sign in to view your profile.');
        } else {
            header('Location: ' . $login_url);
        }
        exit;
    }

    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if (!$db) {
        error_log("My Profile: Database not available");
        $content = array('title' => 'My Profile', 'hide_heading' => true);
        $page_body = '<p>Database unavailable.</p>';
        $isUserLoggedIn = true;
        ob_start();
        include $basic_layout_path;
        return ob_get_clean();
    }

    // Load auth_user_id for the current actor
    $auth_user_id = null;
    $current_email = null;
    // The relationship is: lupo_auth_users.auth_user_id = lupo_actors.actor_id (for imported users)
    // For users created through Lupopedia installer, auth_user_id = actor_id directly
    $stmt = $db->prepare("SELECT au.auth_user_id, au.email FROM {$table_prefix}auth_users au 
        JOIN {$table_prefix}actors a ON a.actor_id = au.auth_user_id 
        WHERE a.actor_id = :actor_id AND a.is_deleted = 0 AND au.is_deleted = 0 LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $auth_user_id = (int) $row['auth_user_id'];
        $current_email = $row['email'];
    }

    $actor = null;
    $actor = function_exists('lupo_get_actor') ? lupo_get_actor($actor_id) : null;
    if (!$actor) {
        $stmt = $db->prepare("SELECT actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, avatar_hash, metadata FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':actor_id' => $actor_id));
        $actor = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    if (!$actor) {
        error_log("My Profile: Actor not found for actor_id: $actor_id");
        $content = array('title' => 'My Profile', 'hide_heading' => true);
        $page_body = '<p>Actor not found.</p>';
        $isUserLoggedIn = true;
        ob_start();
        include $basic_layout_path;
        return ob_get_clean();
    }

    $actor_properties = array();
    $stmt = $db->prepare("SELECT property_key, property_value FROM {$table_prefix}metadata WHERE entity_type = 'actor' AND entity_id = :actor_id AND is_deleted = 0");
    $stmt->execute(array(':actor_id' => $actor_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $actor_properties[$row['property_key']] = $row['property_value'];
    }

    $avatar_public_path = '';
    $avatar_storage = isset($actor_properties['avatar_storage_path']) ? $actor_properties['avatar_storage_path'] : '';
    if ($avatar_storage !== '') {
        $avatar_public_path = $base . '/lupo-uploads/' . ltrim($avatar_storage, '/');
    } elseif (!empty($actor['avatar_hash'])) {
        $ym = gmdate('Y/m');
        $avatar_public_path = $base . '/lupo-uploads/actors/' . $ym . '/' . $actor['avatar_hash'];
    }

    $view_path = $app_root . '/lupo-includes/modules/actors/views/my-profile.php';
    if (!file_exists($view_path)) {
        error_log("My Profile: View file not found at: $view_path");
        $content = array('title' => 'My Profile', 'hide_heading' => true);
        $page_body = '<p>Profile view not found.</p>';
        $isUserLoggedIn = true;
        ob_start();
        include $basic_layout_path;
        return ob_get_clean();
    }

    ob_start();
    extract(array(
        'actor' => $actor,
        'actor_id' => $actor_id,
        'auth_user_id' => $auth_user_id,
        'current_email' => $current_email,
        'actor_properties' => $actor_properties,
        'avatar_public_path' => $avatar_public_path,
        'base' => $base,
    ), EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();
    $content = array('title' => 'My Profile', 'hide_heading' => true);
    $isUserLoggedIn = true;

    ob_start();
    include $basic_layout_path;
    return ob_get_clean();
}

/**
 * Handle POST /my-profile/save — save actor name, properties, and optional avatar upload.
 * Uses TOON: lupo_actors (name, updated_ymdhis, actor_id, avatar_hash), lupo_metadata
 * (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted),
 * lupo_uploads (upload_id, actor_id, ...). metadata_id has no auto_increment in install — we allocate next id.
 * PHP 5.3+ compatible; PDO_DB only (query, fetchRow, fetchOne, update, insert).
 *
 * @return void Redirects to /my-profile
 */
function actors_handle_my_profile_save()
{
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';

    $actor_id = null;
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
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        header('Location: ' . $base . '/login?redirect=' . urlencode($base . '/my-profile'));
        exit;
    }

    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if (!$db) {
        header('Location: ' . $base . '/my-profile');
        exit;
    }

    $actors_table = $table_prefix . 'actors';
    $metadata_table = $table_prefix . 'metadata';
    $uploads_table = $table_prefix . 'uploads';
    $now = gmdate('YmdHis');


    // --- 2FA Management ---
    if (isset($_POST['2fa_action'])) {
        $action = $_POST['2fa_action'];
        if ($action === 'start') {
            // Generate code, store in session, send email
            $code = str_pad(strval(mt_rand(100000, 999999)), 6, '0', STR_PAD_LEFT);
            $_SESSION['2fa_code'] = $code;
            $_SESSION['2fa_pending'] = 1;
            $_SESSION['2fa_code_time'] = time();
            // Send code to user email
            require_once($app_root . '/lupo-includes/PHPMailer/PHPMailer.php');
            require_once($app_root . '/lupo-includes/PHPMailer/SMTP.php');
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'localhost'; // TODO: Set real SMTP host
            $mail->Port = 25; // TODO: Set real SMTP port
            $mail->setFrom('no-reply@lupopedia.local', 'Lupopedia');
            $mail->addAddress($current_email);
            $mail->Subject = 'Your Lupopedia 2FA Verification Code';
            $mail->Body = "Your verification code is: $code\n\nIf you did not request this, ignore this email.";
            if (!$mail->send()) {
                $_SESSION['profile_error'] = 'Failed to send verification email. Contact support.';
            } else {
                $_SESSION['profile_error'] = 'Verification code sent to your email.';
            }
            header('Location: ' . $base . '/my-profile');
            exit;
        } elseif ($action === 'verify') {
            $input_code = isset($_POST['2fa_code']) ? trim($_POST['2fa_code']) : '';
            $valid = isset($_SESSION['2fa_code']) && $input_code === $_SESSION['2fa_code'] && (time() - $_SESSION['2fa_code_time'] < 600);
            if ($valid) {
                // Enable 2FA for actor
                $db->update($actors_table, array('two_factor_enabled' => 1, 'updated_ymdhis' => $now), 'actor_id = :actor_id', array('actor_id' => $actor_id));
                unset($_SESSION['2fa_code'], $_SESSION['2fa_pending'], $_SESSION['2fa_code_time']);
                $_SESSION['profile_error'] = 'Two-factor authentication enabled.';
            } else {
                $_SESSION['profile_error'] = 'Invalid or expired code.';
            }
            header('Location: ' . $base . '/my-profile');
            exit;
        } elseif ($action === 'disable') {
            $db->update($actors_table, array('two_factor_enabled' => 0, 'updated_ymdhis' => $now), 'actor_id = :actor_id', array('actor_id' => $actor_id));
            unset($_SESSION['2fa_code'], $_SESSION['2fa_pending'], $_SESSION['2fa_code_time']);
            $_SESSION['profile_error'] = 'Two-factor authentication disabled.';
            header('Location: ' . $base . '/my-profile');
            exit;
        }
    }

    $actor_name = isset($_POST['actor_name']) ? trim((string) $_POST['actor_name']) : '';
    if ($actor_name !== '') {
        $db->update($actors_table, array('name' => $actor_name, 'updated_ymdhis' => $now), 'actor_id = :actor_id', array('actor_id' => $actor_id));
    }

    // Handle email update with uniqueness validation
    $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
    if ($email !== '') {
        // Normalize email
        $email = strtolower(trim($email));

        // Basic email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set error in session to display on GET request
            $_SESSION['profile_error'] = 'Invalid email format.';
            header('Location: ' . $base . '/my-profile');
            exit;
        }

        // Get current auth_user_id first
        $auth_users_table = $table_prefix . 'auth_users';
        $current_auth = $db->fetchRow(
            "SELECT au.auth_user_id FROM " . $db->quoteIdentifier($auth_users_table) . " au 
            JOIN {$table_prefix}actors a ON a.actor_id = au.auth_user_id 
            WHERE a.actor_id = :actor_id AND a.is_deleted = 0 AND au.is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id)
        );

        if ($current_auth && isset($current_auth['auth_user_id'])) {
            // Check email uniqueness (excluding current user)
            $existing = $db->fetchRow(
                "SELECT auth_user_id FROM " . $db->quoteIdentifier($auth_users_table) . " WHERE email = :email AND is_deleted = 0 AND auth_user_id != :current_auth_user_id LIMIT 1",
                array('email' => $email, 'current_auth_user_id' => $current_auth['auth_user_id'])
            );

            if ($existing && isset($existing['auth_user_id'])) {
                $_SESSION['profile_error'] = 'Email address is already in use by another user.';
                header('Location: ' . $base . '/my-profile');
                exit;
            }

            // Update email
            $db->update($auth_users_table, array('email' => $email, 'updated_ymdhis' => $now), 'auth_user_id = :auth_user_id', array('auth_user_id' => $current_auth['auth_user_id']));
        } else {
            // If we can't get current auth_user_id, we can't validate uniqueness properly
            $_SESSION['profile_error'] = 'Unable to validate email uniqueness. Please try again.';
            header('Location: ' . $base . '/my-profile');
            exit;
        }
    }

    $actor_type = 'user';
    $row = $db->fetchRow("SELECT actor_type FROM " . $db->quoteIdentifier($actors_table) . " WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1", array('actor_id' => $actor_id));
    if ($row && isset($row['actor_type'])) {
        $actor_type = $row['actor_type'];
    }

    foreach ($_POST as $key => $value) {
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
            "SELECT metadata_id FROM " . $db->quoteIdentifier($metadata_table) . " WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = :pk AND is_deleted = 0 LIMIT 1",
            array('actor_id' => $actor_id, 'pk' => $property_key)
        );
        if ($existing && isset($existing['metadata_id'])) {
            $db->update($metadata_table, array('property_value' => $property_value, 'updated_ymdhis' => $now), 'metadata_id = :id', array('id' => $existing['metadata_id']));
        } else {
            $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM " . $db->quoteIdentifier($metadata_table), array());
            $db->insert($metadata_table, array(
                'metadata_id' => $next_id,
                'entity_type' => 'actor',
                'entity_id' => $actor_id,
                'domain_id' => null,
                'meta_type' => null,
                'property_key' => $property_key,
                'property_value' => $property_value,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
            ));
        }
    }

    if (!empty($_FILES['avatar']['tmp_name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
        $ym = gmdate('Y') . '/' . gmdate('m');
        $upload_dir = $app_root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $ym);
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'), true)) {
            $ext = 'jpg';
        }
        $stored_filename = 'avatar_' . $actor_id . '_' . $now . '.' . $ext;
        $full_path = $upload_dir . DIRECTORY_SEPARATOR . $stored_filename;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $full_path)) {
            $storage_path = 'actors/' . $ym . '/' . $stored_filename;
            $mime = (isset($_FILES['avatar']['type']) && $_FILES['avatar']['type'] !== '') ? $_FILES['avatar']['type'] : 'image/jpeg';
            $size = (int) $_FILES['avatar']['size'];
            $original = basename($_FILES['avatar']['name']);

            $upload_next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(upload_id), 0) + 1 FROM " . $db->quoteIdentifier($uploads_table), array());
            $db->insert($uploads_table, array(
                'upload_id' => $upload_next_id,
                'actor_id' => $actor_id,
                'channel_id' => null,
                'original_filename' => $original,
                'stored_filename' => $stored_filename,
                'file_extension' => $ext,
                'mime_type' => $mime,
                'file_size_bytes' => $size,
                'storage_path' => $storage_path,
                'metadata_json' => null,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
            ));

            $db->update($actors_table, array('avatar_hash' => $stored_filename, 'updated_ymdhis' => $now), 'actor_id = :actor_id', array('actor_id' => $actor_id));

            $ex = $db->fetchRow(
                "SELECT metadata_id FROM " . $db->quoteIdentifier($metadata_table) . " WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = 'avatar_storage_path' AND is_deleted = 0 LIMIT 1",
                array('actor_id' => $actor_id)
            );
            if ($ex && isset($ex['metadata_id'])) {
                $db->update($metadata_table, array('property_value' => $storage_path, 'updated_ymdhis' => $now), 'metadata_id = :id', array('id' => $ex['metadata_id']));
            } else {
                $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM " . $db->quoteIdentifier($metadata_table), array());
                $db->insert($metadata_table, array(
                    'metadata_id' => $next_id,
                    'entity_type' => 'actor',
                    'entity_id' => $actor_id,
                    'domain_id' => null,
                    'meta_type' => null,
                    'property_key' => 'avatar_storage_path',
                    'property_value' => $storage_path,
                    'created_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'is_deleted' => 0,
                ));
            }
        }
    }

    header('Location: ' . $base . '/my-profile');
    exit;
}
