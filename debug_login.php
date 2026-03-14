<?php
/**
 * Login debug page — displays diagnostics for login/session issues.
 * Use only in development. Remove or restrict in production.
 *
 * Access: https://localhost/lupopedia/debug_login.php
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

$config_path = null;
if (file_exists(__DIR__ . '/lupopedia-config.php')) {
    $config_path = __DIR__ . '/lupopedia-config.php';
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    $config_path = dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php';
}
if (!$config_path || !file_exists($config_path)) {
    die('lupopedia-config.php not found. Cannot run debug_login.');
}

// Load config and bootstrap (brings in DB, Session, auth helpers)
require_once $config_path;

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><meta charset=\"utf-8\"><title>Login Debug</title>";
echo "<style>
body{font-family:monospace;margin:20px;background:#1e1e1e;color:#d4d4d4;}
h1{color:#4ec9b0;} h2{color:#569cd6;margin-top:24px;}
pre{background:#252526;padding:12px;overflow:auto;border-left:4px solid #007acc;}
.ok{color:#4ec9b0;} .err{color:#f48771;} .warn{color:#dcdcaa;}
table{border-collapse:collapse;} th,td{border:1px solid #444;padding:6px 10px;text-align:left;}
th{background:#2d2d30;}
form{margin:20px 0;} input[type=text],input[type=password]{padding:6px;width:240px;} input[type=submit]{padding:8px 16px;}
.section{margin:16px 0;}
</style></head><body>";

echo "<h1>Login debug</h1>";
echo "<p><strong>URL:</strong> " . htmlspecialchars(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '') . "</p>";

// --- Constants ---
echo "<h2>1. Constants</h2><pre>";
echo "LUPOPEDIA_PUBLIC_PATH = " . (defined('LUPOPEDIA_PUBLIC_PATH') ? var_export(LUPOPEDIA_PUBLIC_PATH, true) : 'NOT DEFINED') . "\n";
echo "LUPO_TABLE_PREFIX     = " . (defined('LUPO_TABLE_PREFIX') ? var_export(LUPO_TABLE_PREFIX, true) : 'NOT DEFINED') . "\n";
echo "LUPOPEDIA_DEBUG       = " . (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG ? 'true' : 'false') . "\n";
echo "</pre>";

// --- Session (before we might start it) ---
echo "<h2>2. PHP session state</h2><pre>";
$sid = function_exists('session_id') ? session_id() : '';
$status = function_exists('session_status') ? session_status() : -1;
$statusLabel = array(PHP_SESSION_DISABLED => 'PHP_SESSION_DISABLED', PHP_SESSION_NONE => 'PHP_SESSION_NONE', PHP_SESSION_ACTIVE => 'PHP_SESSION_ACTIVE');
echo "session_status() = " . (isset($statusLabel[$status]) ? $statusLabel[$status] : $status) . "\n";
echo "session_id()     = " . ($sid === '' ? '(empty)' : substr($sid, 0, 24) . '...') . "\n";
if (function_exists('session_get_cookie_params')) {
    $p = session_get_cookie_params();
    echo "cookie path   = " . (isset($p['path']) ? $p['path'] : '?') . "\n";
    echo "cookie domain = " . (isset($p['domain']) ? $p['domain'] : '?') . "\n";
    echo "cookie secure = " . (isset($p['secure']) ? ($p['secure'] ? 'true' : 'false') : '?') . "\n";
}
echo "Received cookies: " . (isset($_COOKIE) && is_array($_COOKIE) ? count($_COOKIE) : 0) . " (" . implode(', ', array_keys(isset($_COOKIE) ? $_COOKIE : array())) . ")\n";
if (defined('LUPOPEDIA_PUBLIC_PATH')) {
    $expected_path = rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/';
    echo "Expected cookie path for this app: " . $expected_path . " (browser must send PHPSESSID for this path)\n";
}
echo "</pre>";

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sessions_table = $prefix . 'sessions';
$auth_table = $prefix . 'auth_users';

// --- Database ---
echo "<h2>3. Database</h2><pre>";
if (!$db) {
    echo "<span class=\"err\">GLOBALS['mydatabase'] is not set.</span>\n";
} else {
    echo "<span class=\"ok\">DB connection: OK</span>\n";
    echo "Table prefix: " . $prefix . "\n";
    echo "Sessions table name: " . $sessions_table . "\n";
    echo "Auth table name: " . $auth_table . "\n";
}
echo "</pre>";

// --- Table lupo_sessions ---
echo "<h2>4. Table: " . htmlspecialchars($sessions_table) . "</h2><pre>";
if ($db) {
    try {
        $stmt = $db->query("SHOW TABLES LIKE " . $db->quote($sessions_table), array());
        $row = $stmt->fetch();
        if ($row && !empty($row)) {
            echo "<span class=\"ok\">Table exists.</span>\n";
            $cols = $db->fetchAll("SHOW COLUMNS FROM `" . str_replace('`', '``', $sessions_table) . "`", array());
            echo "Columns (" . count($cols) . "): " . implode(', ', array_map(function ($c) { return $c['Field']; }, $cols)) . "\n";
            $count = $db->fetchOne("SELECT COUNT(*) FROM `" . str_replace('`', '``', $sessions_table) . "`", array());
            echo "Row count: " . $count . "\n";
            $recent = $db->fetchAll("SELECT session_id, actor_id, created_ymdhis FROM `" . str_replace('`', '``', $sessions_table) . "` ORDER BY created_ymdhis DESC LIMIT 3", array());
            if ($recent) {
                echo "Latest rows:\n";
                foreach ($recent as $r) {
                    echo "  session_id=" . substr($r['session_id'], 0, 16) . "... actor_id=" . $r['actor_id'] . " created=" . $r['created_ymdhis'] . "\n";
                }
            }
        } else {
            echo "<span class=\"err\">Table does NOT exist.</span>\n";
        }
    } catch (Exception $e) {
        echo "<span class=\"err\">Error: " . htmlspecialchars($e->getMessage()) . "</span>\n";
    }
} else {
    echo "No DB.\n";
}
echo "</pre>";

// --- Table auth_users ---
echo "<h2>5. Table: " . htmlspecialchars($auth_table) . "</h2><pre>";
if ($db) {
    try {
        $stmt = $db->query("SHOW TABLES LIKE " . $db->quote($auth_table), array());
        $row = $stmt->fetch();
        if ($row && !empty($row)) {
            echo "<span class=\"ok\">Table exists.</span>\n";
            $count = $db->fetchOne("SELECT COUNT(*) FROM `" . str_replace('`', '``', $auth_table) . "` WHERE is_deleted = 0", array());
            echo "Active users (is_deleted=0): " . $count . "\n";
            $users = $db->fetchAll("SELECT auth_user_id, email, username, is_active FROM `" . str_replace('`', '``', $auth_table) . "` WHERE is_deleted = 0 LIMIT 5", array());
            if ($users) {
                echo "Sample users:\n";
                foreach ($users as $u) {
                    echo "  auth_user_id=" . $u['auth_user_id'] . " email=" . $u['email'] . " is_active=" . $u['is_active'] . "\n";
                }
            }
        } else {
            echo "<span class=\"err\">Table does NOT exist.</span>\n";
        }
    } catch (Exception $e) {
        echo "<span class=\"err\">Error: " . htmlspecialchars($e->getMessage()) . "</span>\n";
    }
} else {
    echo "No DB.\n";
}
echo "</pre>";

// --- Globals ---
echo "<h2>6. Auth / Session globals</h2><pre>";
echo "lupo_session set?      = " . (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] ? 'yes' : 'no') . "\n";
echo "lupo_auth_service set? = " . (isset($GLOBALS['lupo_auth_service']) && $GLOBALS['lupo_auth_service'] ? 'yes' : 'no') . "\n";
echo "App\\Auth\\Session class exists? = " . (class_exists('App\Auth\Session') ? 'yes' : 'no') . "\n";
echo "</pre>";

// --- Test login form and result ---
echo "<h2>7. Test login (step-by-step)</h2>";
echo "<form method=\"post\">";
echo "Email: <input type=\"text\" name=\"debug_email\" value=\"" . htmlspecialchars(isset($_POST['debug_email']) ? $_POST['debug_email'] : '') . "\" /><br/>";
echo "Password: <input type=\"password\" name=\"debug_password\" /><br/>";
echo "<input type=\"submit\" name=\"debug_test_login\" value=\"Run login steps\" />";
echo "</form>";

if (isset($_POST['debug_test_login']) && $db) {
    $email = isset($_POST['debug_email']) ? trim($_POST['debug_email']) : '';
    $password = isset($_POST['debug_password']) ? $_POST['debug_password'] : '';
    echo "<div class=\"section\"><h3>Step-by-step result</h3><pre>";

    if ($email === '' || $password === '') {
        echo "<span class=\"warn\">Enter email and password.</span>\n";
    } else {
        // Step 1: user lookup
        echo "Step 1: Look up user by email...\n";
        try {
            $sql = "SELECT auth_user_id, username, display_name, email, password_hash, is_active, is_deleted FROM `" . str_replace('`', '``', $auth_table) . "` WHERE email = :email AND is_deleted = 0 LIMIT 1";
            $user = $db->fetchRow($sql, array('email' => $email));
            if (!$user) {
                echo "  <span class=\"err\">User NOT found for email.</span>\n";
            } else {
                echo "  <span class=\"ok\">User found: auth_user_id=" . $user['auth_user_id'] . ", is_active=" . $user['is_active'] . "</span>\n";

                // Step 2: password verify
                echo "Step 2: Verify password...\n";
                if (!function_exists('lupo_verify_password')) {
                    echo "  <span class=\"err\">lupo_verify_password() not defined.</span>\n";
                } else {
                    $password_valid = lupo_verify_password($password, $user['password_hash']);
                    echo "  " . ($password_valid ? "<span class=\"ok\">Password valid.</span>" : "<span class=\"err\">Password INVALID.</span>") . "\n";

                    if ($password_valid) {
                        // Step 3: actor_id
                        echo "Step 3: Resolve actor_id...\n";
                        $actor_id = null;
                        if (isset($GLOBALS['lupo_actor_service']) && $GLOBALS['lupo_actor_service']) {
                            $actor_id = $GLOBALS['lupo_actor_service']->getActorIdFromAuthUserId((int) $user['auth_user_id']);
                        }
                        if (!$actor_id && function_exists('lupo_get_actor_id_from_auth_user_id')) {
                            $actor_id = lupo_get_actor_id_from_auth_user_id($user['auth_user_id']);
                        }
                        if (!$actor_id) {
                            echo "  <span class=\"warn\">No actor_id; trying createActorForAuthUser...</span>\n";
                            if (function_exists('lupo_create_actor_for_auth_user')) {
                                $actor_id = lupo_create_actor_for_auth_user($user['auth_user_id'], $user['email'], isset($user['display_name']) ? $user['display_name'] : '');
                            }
                        }
                        echo "  actor_id = " . ($actor_id !== null ? $actor_id : 'NULL') . "\n";

                        if ($actor_id) {
                            // Step 4: Session::create
                            echo "Step 4: Session::create(\$db, " . $actor_id . ")...\n";
                            try {
                                $created = \App\Auth\Session::create($db, $actor_id);
                                if ($created) {
                                    echo "  <span class=\"ok\">Session::create() returned session_id (length " . strlen($created->session_id) . ")</span>\n";
                                    echo "  New session_id (first 32 chars): " . htmlspecialchars(substr($created->session_id, 0, 32)) . "\n";
                                    echo "  PHP session_id() after create: " . (function_exists('session_id') ? substr(session_id(), 0, 32) : 'N/A') . "\n";
                                    $verify = $db->fetchRow("SELECT session_id, actor_id, created_ymdhis FROM `" . str_replace('`', '``', $sessions_table) . "` WHERE session_id = :sid", array('sid' => $created->session_id));
                                    if ($verify) {
                                        echo "  <span class=\"ok\">DB verify: row exists for this session_id.</span>\n";
                                    } else {
                                        echo "  <span class=\"err\">DB verify: no row found for this session_id (cookie may not persist).</span>\n";
                                    }
                                } else {
                                    echo "  <span class=\"err\">Session::create() returned NULL (check error_log for exception).</span>\n";
                                }
                            } catch (Exception $e) {
                                echo "  <span class=\"err\">Exception: " . htmlspecialchars($e->getMessage()) . "</span>\n";
                                echo "  File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
                            }
                        } else {
                            echo "  <span class=\"err\">Cannot proceed without actor_id.</span>\n";
                        }
                    }
                }
            }
        } catch (Exception $e) {
            echo "  <span class=\"err\">Exception: " . htmlspecialchars($e->getMessage()) . "</span>\n";
        }
    }
    echo "</pre></div>";
}

echo "<p><a href=\"" . (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . "/login\">Go to login</a> | <a href=\"" . (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . "/admin.php\">Admin</a></p>";
echo "</body></html>";
