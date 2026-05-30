<?php
/*
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "lupo-includes/modules/api/channels-admin-api.php"
  system_version: "4.0.49"
  channel_id: 42
  actor_id: 1007
  questions_toon: null
  delegation_chain: "1007:10000"
  artifact_type: "api_module"
  purpose: "Channels admin API endpoints for operators, departments, and settings"
  dialog_message: "Implements channels admin REST endpoints with session auth and channel role checks."
  mood_vector: "4169E1"
  artifact_kind: "api"
  traits: ["channels", "admin", "api"]
  tags: ["channels", "admin", "api", "4.0.49"]
  lupo_agent: "jetbrains"
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die(json_encode(array('success' => false, 'error' => array('code' => 'CONFIG_NOT_LOADED', 'message' => 'Config not loaded.'))));
}

header('Content-Type: application/json; charset=utf-8');

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(array('success' => false, 'error' => array('code' => 'DB_UNAVAILABLE', 'message' => 'Database not available.')));
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$resource = isset($channels_admin_resource) ? $channels_admin_resource : '';
$resource_id = isset($channels_admin_id) ? (int) $channels_admin_id : 0;

function channels_admin_json_error($code, $message, $status = 400) {
    http_response_code($status);
    echo json_encode(array('success' => false, 'error' => array('code' => $code, 'message' => $message)));
    exit;
}

function channels_admin_get_actor_id() {
    $actor_id = 0;
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($authService && method_exists($authService, 'getCurrentUser')) {
        $user = $authService->getCurrentUser();
        if ($user && isset($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && function_exists('current_user')) {
        $user = current_user();
        if (is_array($user) && isset($user['actor_id'])) {
            $actor_id = (int) $user['actor_id'];
        }
    }
    if (!$actor_id && isset($GLOBALS['lupo_session']) && method_exists($GLOBALS['lupo_session'], 'validateSession')) {
        $actor_id = (int) $GLOBALS['lupo_session']->validateSession();
    }
    return (int) $actor_id;
}

function channels_admin_require_auth() {
    $actor_id = channels_admin_get_actor_id();
    if ($actor_id <= 0) {
        channels_admin_json_error('UNAUTHORIZED', 'Authentication required.', 401);
    }
    return $actor_id;
}

function channels_admin_has_access($db, $prefix, $actor_id, $channel_id) {
    $roles_table = $prefix . 'actor_channel_roles';
    $channels_table = $prefix . 'actor_channels';
    $has_access = false;

    $stmt = $db->prepare("SELECT 1 FROM {$roles_table} WHERE actor_id = :actor_id AND channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
    $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
    if ($stmt->fetch() !== false) {
        $has_access = true;
    }

    if (!$has_access) {
        $stmt = $db->prepare("SELECT 1 FROM {$channels_table} WHERE actor_id = :actor_id AND channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
        $stmt->execute(array(':actor_id' => $actor_id, ':channel_id' => $channel_id));
        if ($stmt->fetch() !== false) {
            $has_access = true;
        }
    }

    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if (!$has_access && $authService && method_exists($authService, 'isAdmin')) {
        if ($authService->isAdmin($actor_id)) {
            $has_access = true;
        }
    }

    return $has_access;
}

function channels_admin_require_access($db, $prefix, $actor_id, $channel_id) {
    if ($channel_id <= 0) {
        channels_admin_json_error('INVALID_CHANNEL', 'channel_id must be a positive integer.', 400);
    }
    if (!channels_admin_has_access($db, $prefix, $actor_id, $channel_id)) {
        channels_admin_json_error('FORBIDDEN', 'Actor does not have access to this channel.', 403);
    }
}

function channels_admin_require_csrf($payload) {
    if (!isset($_SESSION)) {
        channels_admin_json_error('CSRF_MISSING', 'Session not initialized.', 419);
    }
    $token = '';
    if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = (string) $_SERVER['HTTP_X_CSRF_TOKEN'];
    }
    if ($token === '' && isset($_POST['csrf_token'])) {
        $token = (string) $_POST['csrf_token'];
    }
    if ($token === '' && $payload && isset($payload['csrf_token'])) {
        $token = (string) $payload['csrf_token'];
    }
    if ($token === '' || !isset($_SESSION['csrf_token'])) {
        channels_admin_json_error('CSRF_INVALID', 'Invalid CSRF token.', 419);
    }
    $session_token = (string) $_SESSION['csrf_token'];
    $matches = function_exists('hash_equals') ? hash_equals($session_token, $token) : ($session_token === $token);
    if (!$matches) {
        channels_admin_json_error('CSRF_INVALID', 'Invalid CSRF token.', 419);
    }
}

function channels_admin_log_audit($db, $prefix, $channel_id, $entity_type, $entity_id, $event_type, $table_name, $table_id, $payload) {
    $now = (int) gmdate('YmdHis');
    $payload_json = $payload ? json_encode($payload) : null;
    try {
        $next_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $prefix . 'audit_log', 'audit_log_id', 1, null) : null;
        if ($next_id === null) {
            $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(audit_log_id), 0) + 1 FROM {$prefix}audit_log", array());
        }
        $db->insert($prefix . 'audit_log', array(
            'audit_log_id' => $next_id,
            'channel_id' => $channel_id,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'event_type' => $event_type,
            'table_name' => $table_name,
            'table_id' => $table_id,
            'payload_json' => $payload_json,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        ));
    } catch (Exception $e) {
        // Audit logging should never block the request.
    }
}

function channels_admin_parse_payload() {
    $payload = json_decode(file_get_contents('php://input'), true);
    if ($payload && is_array($payload)) {
        return $payload;
    }
    return array();
}

$actor_id = channels_admin_require_auth();
$payload = channels_admin_parse_payload();
$channel_id = 0;
if (isset($_GET['channel_id'])) {
    $channel_id = (int) $_GET['channel_id'];
} elseif (isset($payload['channel_id'])) {
    $channel_id = (int) $payload['channel_id'];
} elseif ($resource === 'settings' && $resource_id > 0) {
    $channel_id = $resource_id;
}
if ($channel_id <= 0) {
    $channel_id = 1;
}
channels_admin_require_access($db, $table_prefix, $actor_id, $channel_id);

if ($method !== 'GET') {
    channels_admin_require_csrf($payload);
}

if ($resource === 'operators') {
    $auth_table = $table_prefix . 'auth_users';
    $actors_table = $table_prefix . 'actors';
    $roles_table = $table_prefix . 'actor_channel_roles';
    $now = (int) gmdate('YmdHis');

    if ($method === 'GET') {
        $limit = isset($_GET['limit']) ? max(1, min(200, (int) $_GET['limit'])) : 100;
        $offset = isset($_GET['offset']) ? max(0, (int) $_GET['offset']) : 0;
        $sql = "SELECT u.auth_user_id, u.username, u.display_name, u.email, u.is_active, "
             . "a.actor_id, r.role_key "
             . "FROM {$auth_table} u "
             . "LEFT JOIN {$actors_table} a ON a.actor_source_id = u.auth_user_id "
             . "AND a.actor_source_type = 'user' AND (a.is_deleted = 0 OR a.is_deleted IS NULL) "
             . "LEFT JOIN {$roles_table} r ON r.actor_id = a.actor_id AND r.channel_id = :channel_id "
             . "AND (r.is_deleted = 0 OR r.is_deleted IS NULL) "
             . "WHERE (u.is_deleted = 0 OR u.is_deleted IS NULL) "
             . "ORDER BY u.username "
             . "LIMIT {$limit} OFFSET {$offset}";
        $rows = $db->fetchAll($sql, array('channel_id' => $channel_id));
        echo json_encode(array('success' => true, 'operators' => $rows, 'limit' => $limit, 'offset' => $offset));
        exit;
    }

    if ($method === 'POST') {
        $username = isset($payload['username']) ? trim((string) $payload['username']) : '';
        $display_name = isset($payload['display_name']) ? trim((string) $payload['display_name']) : '';
        $email = isset($payload['email']) ? trim((string) $payload['email']) : '';
        $password = isset($payload['password']) ? (string) $payload['password'] : '';
        $role_key = isset($payload['role_key']) ? trim((string) $payload['role_key']) : '';

        if ($username === '' || $display_name === '') {
            channels_admin_json_error('INVALID_PAYLOAD', 'username and display_name are required.');
        }

        require_once LUPOPEDIA_ABSPATH . '/lupo-includes/security/password-hash.php';
        $password_hash = $password !== '' ? lupo_hash_password($password) : null;
        if ($password !== '' && !$password_hash) {
            channels_admin_json_error('HASH_FAILED', 'Password hash failed.');
        }

        $auth_user_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $auth_table, 'auth_user_id', 1, null) : null;
        if ($auth_user_id === null) {
            $auth_user_id = (int) $db->fetchOne("SELECT COALESCE(MAX(auth_user_id), 0) + 1 FROM {$auth_table}", array());
        }

        $db->insert($auth_table, array(
            'auth_user_id' => $auth_user_id,
            'username' => $username,
            'display_name' => $display_name,
            'email' => $email !== '' ? $email : null,
            'password_hash' => $password_hash,
            'auth_provider' => 'local',
            'provider_id' => null,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        ));

        $db->insert($actors_table, array(
            'actor_id' => $auth_user_id,
            'actor_type' => 'user',
            'slug' => 'user-' . $auth_user_id,
            'name' => $display_name,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_active' => 1,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
            'actor_source_id' => $auth_user_id,
            'actor_source_type' => 'user',
            'primary_federation_node_id' => 1,
            'is_kernel' => 0,
            'can_login' => 1,
            'is_agent' => 0,
            'paired_actor_id' => 0,
            'last_sync_ymdhis' => 0,
        ));

        if ($role_key !== '') {
            $role_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $roles_table, 'actor_channel_role_id', 1, null) : null;
            if ($role_id === null) {
                $role_id = (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM {$roles_table}", array());
            }
            $db->insert($roles_table, array(
                'actor_channel_role_id' => $role_id,
                'actor_id' => $auth_user_id,
                'channel_id' => $channel_id,
                'role_key' => $role_key,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
            ));
        }

        channels_admin_log_audit($db, $table_prefix, $channel_id, 'operator', $auth_user_id, 'create', $auth_table, $auth_user_id, array('username' => $username));

        http_response_code(201);
        echo json_encode(array('success' => true, 'auth_user_id' => $auth_user_id));
        exit;
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        if ($resource_id <= 0 && isset($payload['auth_user_id'])) {
            $resource_id = (int) $payload['auth_user_id'];
        }
        if ($resource_id <= 0) {
            channels_admin_json_error('INVALID_ID', 'auth_user_id is required.');
        }

        $display_name = isset($payload['display_name']) ? trim((string) $payload['display_name']) : null;
        $email = isset($payload['email']) ? trim((string) $payload['email']) : null;
        $is_active = isset($payload['is_active']) ? (int) ($payload['is_active'] ? 1 : 0) : null;
        $role_key = isset($payload['role_key']) ? trim((string) $payload['role_key']) : null;

        $update_data = array('updated_ymdhis' => $now);
        if ($display_name !== null && $display_name !== '') {
            $update_data['display_name'] = $display_name;
        }
        if ($email !== null) {
            $update_data['email'] = $email !== '' ? $email : null;
        }
        if ($is_active !== null) {
            $update_data['is_active'] = $is_active ? 1 : 0;
        }
        $db->update($auth_table, $update_data, 'auth_user_id = :id', array(':id' => $resource_id));

        if ($display_name !== null && $display_name !== '') {
            $db->update($actors_table, array('name' => $display_name, 'updated_ymdhis' => $now), 'actor_source_id = :id AND actor_source_type = :type', array(':id' => $resource_id, ':type' => 'user'));
        }

        if ($role_key !== null) {
            $actor_row = $db->fetchRow("SELECT actor_id FROM {$actors_table} WHERE actor_source_id = :id AND actor_source_type = 'user' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array('id' => $resource_id));
            $actor_id_for_role = $actor_row ? (int) $actor_row['actor_id'] : 0;
            if ($actor_id_for_role > 0) {
                $db->query("UPDATE {$roles_table} SET is_deleted = 1, deleted_ymdhis = :now WHERE actor_id = :aid AND channel_id = :cid", array(':now' => $now, ':aid' => $actor_id_for_role, ':cid' => $channel_id));
                if ($role_key !== '') {
                    $role_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $roles_table, 'actor_channel_role_id', 1, null) : null;
                    if ($role_id === null) {
                        $role_id = (int) $db->fetchOne("SELECT COALESCE(MAX(actor_channel_role_id), 0) + 1 FROM {$roles_table}", array());
                    }
                    $db->insert($roles_table, array(
                        'actor_channel_role_id' => $role_id,
                        'actor_id' => $actor_id_for_role,
                        'channel_id' => $channel_id,
                        'role_key' => $role_key,
                        'created_ymdhis' => $now,
                        'updated_ymdhis' => $now,
                        'is_deleted' => 0,
                    ));
                }
            }
        }

        channels_admin_log_audit($db, $table_prefix, $channel_id, 'operator', $resource_id, 'update', $auth_table, $resource_id, array('channel_id' => $channel_id));
        echo json_encode(array('success' => true, 'auth_user_id' => $resource_id));
        exit;
    }

    if ($method === 'DELETE') {
        if ($resource_id <= 0 && isset($payload['auth_user_id'])) {
            $resource_id = (int) $payload['auth_user_id'];
        }
        if ($resource_id <= 0) {
            channels_admin_json_error('INVALID_ID', 'auth_user_id is required.');
        }
        $db->update($auth_table, array('is_deleted' => 1, 'is_active' => 0, 'deleted_ymdhis' => $now, 'updated_ymdhis' => $now), 'auth_user_id = :id', array(':id' => $resource_id));
        $db->update($actors_table, array('is_deleted' => 1, 'is_active' => 0, 'deleted_ymdhis' => $now, 'updated_ymdhis' => $now), 'actor_source_id = :id AND actor_source_type = :type', array(':id' => $resource_id, ':type' => 'user'));
        channels_admin_log_audit($db, $table_prefix, $channel_id, 'operator', $resource_id, 'delete', $auth_table, $resource_id, array('channel_id' => $channel_id));
        echo json_encode(array('success' => true, 'auth_user_id' => $resource_id));
        exit;
    }

    channels_admin_json_error('METHOD_NOT_ALLOWED', 'Unsupported method.', 405);
}

if ($resource === 'departments') {
    $dept_table = $table_prefix . 'departments';
    $now = (int) gmdate('YmdHis');

    if ($method === 'GET') {
        $rows = $db->fetchAll("SELECT department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis FROM {$dept_table} WHERE is_deleted = 0 ORDER BY name", array());
        echo json_encode(array('success' => true, 'departments' => $rows));
        exit;
    }

    if ($method === 'POST') {
        $name = isset($payload['name']) ? trim((string) $payload['name']) : '';
        $department_type = isset($payload['department_type']) ? trim((string) $payload['department_type']) : 'general';
        $description = isset($payload['description']) ? trim((string) $payload['description']) : null;
        $default_actor_id = isset($payload['default_actor_id']) ? (int) $payload['default_actor_id'] : 1;
        $settings_json = isset($payload['settings_json']) ? json_encode($payload['settings_json']) : null;
        $federation_node_id = isset($payload['federation_node_id']) ? (int) $payload['federation_node_id'] : 1;

        if ($name === '') {
            channels_admin_json_error('INVALID_PAYLOAD', 'name is required.');
        }

        $dept_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $dept_table, 'department_id', 1, null) : null;
        if ($dept_id === null) {
            $dept_id = (int) $db->fetchOne("SELECT COALESCE(MAX(department_id), 0) + 1 FROM {$dept_table}", array());
        }

        $db->insert($dept_table, array(
            'department_id' => $dept_id,
            'federation_node_id' => $federation_node_id,
            'name' => $name,
            'description' => $description,
            'department_type' => $department_type,
            'default_actor_id' => $default_actor_id,
            'settings_json' => $settings_json,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        ));

        channels_admin_log_audit($db, $table_prefix, $channel_id, 'department', $dept_id, 'create', $dept_table, $dept_id, array('name' => $name));
        http_response_code(201);
        echo json_encode(array('success' => true, 'department_id' => $dept_id));
        exit;
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        if ($resource_id <= 0 && isset($payload['department_id'])) {
            $resource_id = (int) $payload['department_id'];
        }
        if ($resource_id <= 0) {
            channels_admin_json_error('INVALID_ID', 'department_id is required.');
        }
        $update = array('updated_ymdhis' => $now);
        if (isset($payload['name'])) {
            $update['name'] = trim((string) $payload['name']);
        }
        if (isset($payload['description'])) {
            $update['description'] = trim((string) $payload['description']);
        }
        if (isset($payload['department_type'])) {
            $update['department_type'] = trim((string) $payload['department_type']);
        }
        if (isset($payload['default_actor_id'])) {
            $update['default_actor_id'] = (int) $payload['default_actor_id'];
        }
        if (isset($payload['settings_json'])) {
            $update['settings_json'] = json_encode($payload['settings_json']);
        }
        $db->update($dept_table, $update, 'department_id = :id', array(':id' => $resource_id));
        channels_admin_log_audit($db, $table_prefix, $channel_id, 'department', $resource_id, 'update', $dept_table, $resource_id, array());
        echo json_encode(array('success' => true, 'department_id' => $resource_id));
        exit;
    }

    if ($method === 'DELETE') {
        if ($resource_id <= 0 && isset($payload['department_id'])) {
            $resource_id = (int) $payload['department_id'];
        }
        if ($resource_id <= 0) {
            channels_admin_json_error('INVALID_ID', 'department_id is required.');
        }
        $db->update($dept_table, array('is_deleted' => 1, 'deleted_ymdhis' => $now, 'updated_ymdhis' => $now), 'department_id = :id', array(':id' => $resource_id));
        channels_admin_log_audit($db, $table_prefix, $channel_id, 'department', $resource_id, 'delete', $dept_table, $resource_id, array());
        echo json_encode(array('success' => true, 'department_id' => $resource_id));
        exit;
    }

    channels_admin_json_error('METHOD_NOT_ALLOWED', 'Unsupported method.', 405);
}

if ($resource === 'settings') {
    $channels_table = $table_prefix . 'channels';
    $now = (int) gmdate('YmdHis');
    if ($method === 'GET') {
        $row = $db->fetchRow("SELECT channel_id, channel_name, description, status_flag FROM {$channels_table} WHERE channel_id = :id AND is_deleted = 0", array('id' => $channel_id));
        echo json_encode(array('success' => true, 'settings' => $row));
        exit;
    }
    if ($method === 'PUT' || $method === 'PATCH') {
        $update = array('updated_ymdhis' => $now);
        if (isset($payload['channel_name'])) {
            $update['channel_name'] = trim((string) $payload['channel_name']);
        }
        if (isset($payload['description'])) {
            $update['description'] = trim((string) $payload['description']);
        }
        if (isset($payload['status_flag'])) {
            $update['status_flag'] = (int) $payload['status_flag'];
        }
        if (count($update) === 1) {
            channels_admin_json_error('INVALID_PAYLOAD', 'No editable fields provided.');
        }
        $db->update($channels_table, $update, 'channel_id = :id', array(':id' => $channel_id));
        channels_admin_log_audit($db, $table_prefix, $channel_id, 'channel', $channel_id, 'update', $channels_table, $channel_id, array());
        echo json_encode(array('success' => true, 'channel_id' => $channel_id));
        exit;
    }
    channels_admin_json_error('METHOD_NOT_ALLOWED', 'Unsupported method.', 405);
}

channels_admin_json_error('NOT_FOUND', 'Unknown admin API resource.', 404);
