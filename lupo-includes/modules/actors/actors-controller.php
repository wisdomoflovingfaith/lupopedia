<?php
/**
 * Actors controller — My Profile and related actor UI.
 * Standalone interface (like channel cockpit). Does NOT use content system, render_main_layout, or slug lookup.
 * Routes: GET /my-profile, POST /my-profile/save. Rendered via layout-topnav.php (top nav only).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

/**
 * Handle GET /my-profile — show current actor's profile form.
 * Loads actor from lupo_actors, properties from lupo_actor_properties, computes avatar path.
 * Renders dedicated view my-profile.php via layout-topnav.php; no content/slug rendering.
 *
 * @return string HTML (standalone layout with top nav only)
 */
function actors_handle_my_profile() {
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
    $layout_path = $app_root . '/lupo-includes/modules/actors/views/layout-topnav.php';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
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
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
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

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        $page_title = 'My Profile';
        $page_body = '<p>Database unavailable.</p>';
        $head_extra = '';
        ob_start();
        include $layout_path;
        return ob_get_clean();
    }

    $actor = null;
    $stmt = $db->prepare("SELECT actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, avatar_hash, metadata FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':actor_id' => $actor_id]);
    $actor = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$actor) {
        $page_title = 'My Profile';
        $page_body = '<p>Actor not found.</p>';
        $head_extra = '';
        ob_start();
        include $layout_path;
        return ob_get_clean();
    }

    $actor_properties = [];
    $stmt = $db->prepare("SELECT property_key, property_value FROM {$table_prefix}actor_properties WHERE actor_id = :actor_id AND (actor_type = :actor_type OR actor_type = 'user') AND is_deleted = 0");
    $stmt->execute([':actor_id' => $actor_id, ':actor_type' => $actor['actor_type'] ?? 'user']);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $actor_properties[$row['property_key']] = $row['property_value'];
    }

    $avatar_public_path = '';
    $avatar_storage = $actor_properties['avatar_storage_path'] ?? '';
    if ($avatar_storage !== '') {
        $avatar_public_path = $base . '/uploads/' . ltrim($avatar_storage, '/');
    } elseif (!empty($actor['avatar_hash'])) {
        $ym = gmdate('Y/m');
        $avatar_public_path = $base . '/uploads/actors/' . $ym . '/' . $actor['avatar_hash'];
    }

    $view_path = $app_root . '/lupo-includes/modules/actors/views/my-profile.php';
    if (!file_exists($view_path)) {
        $page_title = 'My Profile';
        $page_body = '<p>Profile view not found.</p>';
        $head_extra = '';
        ob_start();
        include $layout_path;
        return ob_get_clean();
    }

    ob_start();
    extract([
        'actor'             => $actor,
        'actor_id'          => $actor_id,
        'actor_properties'  => $actor_properties,
        'avatar_public_path'=> $avatar_public_path,
        'base'              => $base,
    ], EXTR_SKIP);
    include $view_path;
    $page_body = ob_get_clean();
    $page_title = 'My Profile';
    if (!isset($head_extra)) {
        $head_extra = '';
    }

    ob_start();
    include $layout_path;
    return ob_get_clean();
}

/**
 * Handle POST /my-profile/save — save actor name, properties, and optional avatar upload.
 * Inserts lupo_uploads for avatar; updates lupo_actors.name and lupo_actor_properties; redirects to /my-profile.
 * Does not use content system.
 *
 * @return void Redirects to /my-profile
 */
function actors_handle_my_profile_save() {
    $app_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : LUPOPEDIA_ABSPATH;
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';

    $actor_id = null;
    $authService = $GLOBALS['lupo_auth_service'] ?? null;
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
    if (!$actor_id && ($s = $GLOBALS['lupo_session'] ?? null)) {
        $actor_id = $s->validateSession();
    }
    if (!$actor_id) {
        header('Location: ' . $base . '/login?redirect=' . urlencode($base . '/my-profile'));
        exit;
    }

    $db = $GLOBALS['mydatabase'] ?? null;
    if (!$db) {
        header('Location: ' . $base . '/my-profile');
        exit;
    }

    $now = gmdate('YmdHis');
    $actor_name = isset($_POST['actor_name']) ? trim((string) $_POST['actor_name']) : '';
    if ($actor_name !== '') {
        $stmt = $db->prepare("UPDATE {$table_prefix}actors SET name = :name, updated_ymdhis = :now WHERE actor_id = :actor_id");
        $stmt->execute([':name' => $actor_name, ':now' => $now, ':actor_id' => $actor_id]);
    }

    $actor_type = 'user';
    $stmt = $db->prepare("SELECT actor_type FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1");
    $stmt->execute([':actor_id' => $actor_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $actor_type = $row['actor_type'] ?? 'user';
    }

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'prop_') === 0) {
            $property_key = substr($key, 5);
            if ($property_key === '') continue;
            $property_key = preg_replace('/[^a-zA-Z0-9_-]/', '', $property_key);
            if ($property_key === '') continue;
            $property_value = is_string($value) ? trim($value) : (string) $value;
            $stmt = $db->prepare("SELECT actor_property_id FROM {$table_prefix}actor_properties WHERE actor_id = :actor_id AND property_key = :pk AND is_deleted = 0 LIMIT 1");
            $stmt->execute([':actor_id' => $actor_id, ':pk' => $property_key]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                $stmt = $db->prepare("UPDATE {$table_prefix}actor_properties SET property_value = :v, updated_ymdhis = :now WHERE actor_property_id = :id");
                $stmt->execute([':v' => $property_value, ':now' => $now, ':id' => $existing['actor_property_id']]);
            } else {
                $stmt = $db->prepare("INSERT INTO {$table_prefix}actor_properties (actor_type, actor_id, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:at, :aid, :pk, :v, :now, :now, 0)");
                $stmt->execute([':at' => $actor_type, ':aid' => $actor_id, ':pk' => $property_key, ':v' => $property_value, ':now' => $now]);
            }
        }
    }

    if (!empty($_FILES['avatar']['tmp_name']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
        $ym = gmdate('Y') . '/' . gmdate('m');
        $upload_dir = $app_root . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $ym);
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $ext = 'jpg';
        }
        $stored_filename = 'avatar_' . $actor_id . '_' . $now . '.' . $ext;
        $full_path = $upload_dir . DIRECTORY_SEPARATOR . $stored_filename;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $full_path)) {
            $storage_path = 'actors/' . $ym . '/' . $stored_filename;
            $mime = $_FILES['avatar']['type'] ?: 'image/jpeg';
            $size = (int) $_FILES['avatar']['size'];
            $original = basename($_FILES['avatar']['name']);

            $stmt = $db->prepare("INSERT INTO {$table_prefix}uploads (actor_id, channel_id, original_filename, stored_filename, file_extension, mime_type, file_size_bytes, storage_path, metadata_json, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:aid, NULL, :orig, :stored, :ext, :mime, :sz, :path, NULL, :now, :now, 0)");
            $stmt->execute([
                ':aid' => $actor_id,
                ':orig' => $original,
                ':stored' => $stored_filename,
                ':ext' => $ext,
                ':mime' => $mime,
                ':sz' => $size,
                ':path' => $storage_path,
                ':now' => $now,
            ]);

            $stmt = $db->prepare("UPDATE {$table_prefix}actors SET avatar_hash = :hash, updated_ymdhis = :now WHERE actor_id = :actor_id");
            $stmt->execute([':hash' => $stored_filename, ':now' => $now, ':actor_id' => $actor_id]);

            $stmt = $db->prepare("SELECT actor_property_id FROM {$table_prefix}actor_properties WHERE actor_id = :actor_id AND property_key = 'avatar_storage_path' AND is_deleted = 0 LIMIT 1");
            $stmt->execute([':actor_id' => $actor_id]);
            $ex = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ex) {
                $stmt = $db->prepare("UPDATE {$table_prefix}actor_properties SET property_value = :v, updated_ymdhis = :now WHERE actor_property_id = :id");
                $stmt->execute([':v' => $storage_path, ':now' => $now, ':id' => $ex['actor_property_id']]);
            } else {
                $stmt = $db->prepare("INSERT INTO {$table_prefix}actor_properties (actor_type, actor_id, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted) VALUES (:at, :aid, 'avatar_storage_path', :v, :now, :now, 0)");
                $stmt->execute([':at' => $actor_type, ':aid' => $actor_id, ':v' => $storage_path, ':now' => $now]);
            }
        }
    }

    header('Location: ' . $base . '/my-profile');
    exit;
}
