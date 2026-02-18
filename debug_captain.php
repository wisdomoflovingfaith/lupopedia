<?php
/**
 * Debug: Captain (user 10000) — verify you are logged in as auth_user_id 10000 / actor_id 10000
 * and have all permissions/roles needed for admin access.
 * Requires config and login. Run while logged in as captain@lupopedia.com.
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

$configPath = null;
if (isset($_SERVER['DOCUMENT_ROOT']) && file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    $configPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
} elseif (isset($_SERVER['DOCUMENT_ROOT']) && file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    $configPath = dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php';
} elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    $configPath = LUPOPEDIA_PATH . '/lupopedia-config.php';
} elseif (isset($_SERVER['DOCUMENT_ROOT']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/lupopedia-config.php')) {
    $configPath = $_SERVER['DOCUMENT_ROOT'] . '/lupopedia-config.php';
}
if (!$configPath) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>Config not found</h1><p>Cannot run debug without lupopedia-config.php.</p>';
    exit;
}
require_once $configPath;

// Require login so we see the actual logged-in user
$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService && method_exists($authService, 'requireLogin')) {
    $authService->requireLogin();
}

header('Content-Type: text/html; charset=utf-8');
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;

echo "<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>Debug Captain (10000)</title>";
echo "<style>body{font-family:sans-serif;margin:1rem;} table{border-collapse:collapse;margin:1rem 0;} th,td{border:1px solid #ccc;padding:6px 10px;text-align:left;} th{background:#eee;} .ok{color:green;} .fail{color:red;} .warn{color:orange;} pre{background:#f5f5f5;padding:8px;overflow:auto;} h2{margin-top:1.5rem;}</style></head><body>";
echo "<h1>Debug: Captain (user 10000) and admin access</h1>";

if (!$db) {
    echo "<p class=\"fail\">Database not available.</p></body></html>";
    exit;
}

$sid = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : (isset($_COOKIE['lupo_session_id']) ? $_COOKIE['lupo_session_id'] : '');
echo "<h2>1. Session</h2>";
echo "<p>Session cookie present: " . ($sid !== '' ? '<span class="ok">yes</span>' : '<span class="fail">no — not logged in?</span>') . "</p>";

$session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
$sessionActorId = null;
if ($session && method_exists($session, 'validateSession')) {
    $sessionActorId = $session->validateSession();
}
echo "<p>Session actor_id (from validateSession): " . ($sessionActorId !== null && $sessionActorId !== false ? (int) $sessionActorId : '<span class="fail">none / not logged in</span>') . "</p>";

$currentUser = false;
if ($authService && method_exists($authService, 'getCurrentUser')) {
    $currentUser = $authService->getCurrentUser();
}
echo "<h2>2. Current user (AuthService::getCurrentUser)</h2>";
if ($currentUser === false || empty($currentUser)) {
    echo "<p class=\"fail\">Not logged in or getCurrentUser() returned false. AuthService requires lupo_actors.actor_source_type = 'user' (not 'lupo_auth_users').</p>";
} else {
    echo "<table><tr><th>Key</th><th>Value</th></tr>";
    foreach ($currentUser as $k => $v) {
        echo "<tr><td>" . htmlspecialchars($k) . "</td><td>" . htmlspecialchars(is_bool($v) ? ($v ? 'true' : 'false') : (string) $v) . "</td></tr>";
    }
    echo "</table>";
    $isExpected = (isset($currentUser['auth_user_id']) && (int) $currentUser['auth_user_id'] === 10000)
        && (isset($currentUser['actor_id']) && (int) $currentUser['actor_id'] === 10000);
    echo "<p>Expected auth_user_id=10000 and actor_id=10000: " . ($isExpected ? '<span class="ok">yes</span>' : '<span class="fail">no</span>') . "</p>";
    echo "<p>is_admin: " . (!empty($currentUser['is_admin']) ? '<span class="ok">true</span>' : '<span class="fail">false — this is why admin.php denies access</span>') . "</p>";
}

$actorId = $sessionActorId !== null && $sessionActorId !== false ? (int) $sessionActorId : (($currentUser && isset($currentUser['actor_id'])) ? (int) $currentUser['actor_id'] : 0);

echo "<h2>3. lupo_auth_users (auth_user_id = 10000)</h2>";
$au = $db->quoteIdentifier($prefix . 'auth_users');
$row = $db->fetchRow("SELECT auth_user_id, username, display_name, email, is_active, is_deleted FROM {$au} WHERE auth_user_id = 10000 LIMIT 1", array());
if ($row) {
    echo "<table><tr><th>Column</th><th>Value</th></tr>";
    foreach ($row as $k => $v) {
        echo "<tr><td>" . htmlspecialchars($k) . "</td><td>" . htmlspecialchars((string) $v) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class=\"fail\">No row for auth_user_id 10000. Create via install wizard Step 7 (Config) or seed.</p>";
}

echo "<h2>4. lupo_actors (actor_id = 10000)</h2>";
$a = $db->quoteIdentifier($prefix . 'actors');
$row = $db->fetchRow("SELECT actor_id, actor_type, slug, name, actor_source_id, actor_source_type, is_active, is_deleted FROM {$a} WHERE actor_id = 10000 LIMIT 1", array());
if ($row) {
    echo "<table><tr><th>Column</th><th>Value</th></tr>";
    foreach ($row as $k => $v) {
        echo "<tr><td>" . htmlspecialchars($k) . "</td><td>" . htmlspecialchars((string) $v) . "</td></tr>";
    }
    echo "</table>";
    $srcType = isset($row['actor_source_type']) ? trim($row['actor_source_type']) : '';
    if ($srcType !== 'user') {
        echo "<p class=\"warn\"><strong>Fix:</strong> AuthService::getCurrentUser() requires actor_source_type = 'user'. Yours is '" . htmlspecialchars($srcType) . "'. Run: <code>UPDATE " . $prefix . "actors SET actor_source_type = 'user' WHERE actor_id = 10000;</code></p>";
    }
} else {
    echo "<p class=\"fail\">No row for actor_id 10000. Seed or migration should create it.</p>";
}

echo "<h2>5. lupo_actor_channels (actor_id = 10000)</h2>";
$ac = $db->quoteIdentifier($prefix . 'actor_channels');
$rows = $db->fetchAll("SELECT actor_channel_id, actor_id, channel_id, status, is_deleted FROM {$ac} WHERE actor_id = 10000 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY channel_id", array());
if (!empty($rows)) {
    echo "<table><tr><th>actor_channel_id</th><th>actor_id</th><th>channel_id</th><th>status</th></tr>";
    foreach ($rows as $r) {
        echo "<tr><td>" . (int) $r['actor_channel_id'] . "</td><td>" . (int) $r['actor_id'] . "</td><td>" . (int) $r['channel_id'] . "</td><td>" . htmlspecialchars($r['status']) . "</td></tr>";
    }
    echo "</table>";
    $ch1 = false;
    foreach ($rows as $r) {
        if ((int) $r['channel_id'] === 1) {
            $ch1 = true;
            break;
        }
    }
    echo "<p>Has channel 1 (admin default channel): " . ($ch1 ? '<span class="ok">yes</span>' : '<span class="warn">no — add row for channel_id=1</span>') . "</p>";
} else {
    echo "<p class=\"fail\">No actor_channels rows for actor_id 10000. Run migration_add_main_admin_channel_membership.sql or ensure seed ran.</p>";
}

echo "<h2>6. lupo_actor_channel_roles (actor_id = 10000)</h2>";
$acr = $db->quoteIdentifier($prefix . 'actor_channel_roles');
$rows = $db->fetchAll("SELECT actor_channel_role_id, actor_id, channel_id, role_key, is_deleted FROM {$acr} WHERE actor_id = 10000 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY channel_id", array());
if (!empty($rows)) {
    echo "<table><tr><th>actor_channel_role_id</th><th>actor_id</th><th>channel_id</th><th>role_key</th></tr>";
    foreach ($rows as $r) {
        echo "<tr><td>" . (int) $r['actor_channel_role_id'] . "</td><td>" . (int) $r['actor_id'] . "</td><td>" . (int) $r['channel_id'] . "</td><td>" . htmlspecialchars($r['role_key']) . "</td></tr>";
    }
    echo "</table>";
    $ch1Role = false;
    foreach ($rows as $r) {
        if ((int) $r['channel_id'] === 1 && in_array($r['role_key'], array('captain', 'administrator', 'monitor'))) {
            $ch1Role = true;
            break;
        }
    }
    echo "<p>Has captain/administrator/monitor on channel 1: " . ($ch1Role ? '<span class="ok">yes</span>' : '<span class="fail">no — admin.php uses channel 1 for is_admin. Add role on channel 1.</span>') . "</p>";
} else {
    echo "<p class=\"fail\">No actor_channel_roles for actor_id 10000.</p>";
}

echo "<h2>7. lupo_department_roles (actor_id = 10000, department_id = 0)</h2>";
$dr = $db->quoteIdentifier($prefix . 'department_roles');
$rows = $db->fetchAll("SELECT department_role_id, actor_id, department_id, role_key FROM {$dr} WHERE actor_id = 10000 AND department_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL)", array());
if (!empty($rows)) {
    echo "<table><tr><th>department_role_id</th><th>actor_id</th><th>department_id</th><th>role_key</th></tr>";
    foreach ($rows as $r) {
        echo "<tr><td>" . (int) $r['department_role_id'] . "</td><td>" . (int) $r['actor_id'] . "</td><td>" . (int) $r['department_id'] . "</td><td>" . htmlspecialchars($r['role_key']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class=\"warn\">No department_roles for actor 10000 on department 0 (system). Optional for admin; channel 1 role or permissions can suffice.</p>";
}

echo "<h2>8. lupo_permissions (user_id = 10000, admin module owner)</h2>";
$mod = $db->quoteIdentifier($prefix . 'modules');
$adminMod = $db->fetchRow("SELECT module_id, module_key FROM {$mod} WHERE module_key = 'admin' AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array());
if ($adminMod) {
    $perm = $db->quoteIdentifier($prefix . 'permissions');
    $row = $db->fetchRow("SELECT permission_id, target_type, target_id, user_id, permission FROM {$perm} WHERE target_type = 'module' AND target_id = :mid AND user_id = 10000 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array('mid' => $adminMod['module_id']));
    if ($row) {
        echo "<p class=\"ok\">Owner permission on admin module present.</p><pre>" . htmlspecialchars(print_r($row, true)) . "</pre>";
    } else {
        echo "<p class=\"warn\">No owner permission on admin module for user_id 10000. Install wizard Step 7 adds this; or add via migration.</p>";
    }
} else {
    echo "<p class=\"warn\">Admin module not found in lupo_modules.</p>";
}

echo "<h2>9. Channel 1 exists?</h2>";
$ch = $db->quoteIdentifier($prefix . 'channels');
$row = $db->fetchRow("SELECT channel_id, channel_name, department_id FROM {$ch} WHERE channel_id = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1", array());
if ($row) {
    echo "<p class=\"ok\">Channel 1 exists: " . htmlspecialchars($row['channel_name']) . " (department_id=" . (int) $row['department_id'] . ").</p>";
} else {
    echo "<p class=\"warn\">Channel 1 does not exist. AuthRoleResolver uses channel_id=1 for isAdmin(). Add channel 1 (e.g. Administration) or ensure actor 10000 has department_id=0 administrator or owner on admin module.</p>";
}

echo "<h2>10. isAdmin(10000) check</h2>";
if ($authService && method_exists($authService, 'isAdmin')) {
    $isAdmin = $authService->isAdmin(10000);
    echo "<p>AuthService::isAdmin(10000): " . ($isAdmin ? '<span class="ok">true</span>' : '<span class="fail">false</span>') . "</p>";
}

echo "<p><a href=\"" . (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . "/admin.php\">Go to admin.php</a> | <a href=\"" . (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . "/index.php\">Home</a></p>";
echo "</body></html>";
