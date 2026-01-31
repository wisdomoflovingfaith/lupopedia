<?php
// Force session name BEFORE any session activity to ensure compatibility with main Lupopedia auth
// This MUST happen before any includes that might trigger session_start
if (session_status() === PHP_SESSION_ACTIVE) {
    if (session_name() !== 'PHPSESSID') {
        session_write_close();
        session_name('PHPSESSID');
        session_start();
    }
} else {
    session_name('PHPSESSID');
    session_start();
}
if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
    error_log("CRAFTY BOOTSTRAP: Session Name forced to: " . session_name() . ", ID: " . session_id());
}

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(__DIR__));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(LUPOPEDIA_PATH));
}

if (!defined('LUPOPEDIA_CONFIG_PATH')) {
    if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php');
    } elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
    } elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
    }
}

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    if (!defined('LUPOPEDIA_CONFIG_PATH') || !file_exists(LUPOPEDIA_CONFIG_PATH)) {
        header('HTTP/1.1 500 Internal Server Error');
        echo "Config not loaded.";
        exit;
    }
    require_once LUPOPEDIA_CONFIG_PATH;
}

require_once LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php';

if (isset($_GET['debug_session'])) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name('PHPSESSID');
        session_start();
    }
    echo "<h1>Debug Session</h1>";
    echo "<pre>";
    echo "Session Name: " . session_name() . "\n";
    echo "Session ID: " . session_id() . "\n";
    echo "Cookies: "; print_r($_COOKIE);
    echo "User (current_user()): "; print_r(current_user());
    echo "lupo_validate_session(): "; var_dump(lupo_validate_session());
    
    // Check database directly
    if (isset($GLOBALS['mydatabase'])) {
        $db = $GLOBALS['mydatabase'];
        $actor_id = lupo_validate_session();
        $auth_user_id = 0;
        
        echo "<h2>Database Check</h2>";
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        if ($actor_id) {
            $stmt = $db->prepare("SELECT * FROM {$table_prefix}actors WHERE actor_id = ?");
            $stmt->execute([$actor_id]);
            $actor_rec = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Actor Record: "; print_r($actor_rec);
            
            if ($actor_rec && $actor_rec['actor_source_type'] === 'user') {
                 $auth_user_id = $actor_rec['actor_source_id'];
            }
        } else {
             echo "<h2>Session/User Check</h2>";
             $sid = session_id();
             $stmt = $db->prepare("SELECT * FROM {$table_prefix}sessions WHERE session_id = ?");
             $stmt->execute([$sid]);
             $srec = $stmt->fetch(PDO::FETCH_ASSOC);
             echo "Session Record for $sid: "; print_r($srec);
             if ($srec) {
                 $stmt = $db->prepare("SELECT * FROM {$table_prefix}actors WHERE actor_id = ?");
                 $stmt->execute([$srec['actor_id']]);
                 $actor_rec = $stmt->fetch(PDO::FETCH_ASSOC);
                 echo "Actor Record: "; print_r($actor_rec);
                 if ($actor_rec && $actor_rec['actor_source_type'] === 'user') {
                     $auth_user_id = $actor_rec['actor_source_id'];
                 }
             }
        }

        if ($auth_user_id) {
            $stmt = $db->prepare("SELECT * FROM {$table_prefix}auth_users WHERE auth_user_id = ?");
            $stmt->execute([$auth_user_id]);
            echo "Auth User: "; print_r($stmt->fetch(PDO::FETCH_ASSOC));

            $stmt = $db->prepare("SELECT * FROM {$table_prefix}operators WHERE auth_user_id = ?");
            $stmt->execute([$auth_user_id]);
            echo "Operator Record: "; print_r($stmt->fetch(PDO::FETCH_ASSOC));
            
            $actor_id = $actor_id ?: ($actor_rec['actor_id'] ?? 0);
            echo "Is Admin (lupo_is_admin): "; var_dump(lupo_is_admin($actor_id));
            $stmt = $db->prepare("SELECT * FROM {$table_prefix}actor_roles WHERE actor_id = ?");
            $stmt->execute([$actor_id]);
            echo "Actor Roles Table: "; print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } else {
        echo "<p>Database not connected.</p>";
    }
    echo "</pre>";
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

if (isset($_GET['debug_session'])) {
    echo "<pre>";
    echo "Crafty Operator: "; print_r(lupo_crafty_operator());
    echo "</pre>";
}

// Placeholder for permissions (not needed yet)
// require_once __DIR__ . '/includes/permissions.php';

if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
    error_log("CRAFTY BOOTSTRAP: Final state check - Name: " . session_name() . ", ID: " . session_id());
    error_log("CRAFTY BOOTSTRAP: COOKIE cslhOPERATOR: " . ($_COOKIE['cslhOPERATOR'] ?? 'NONE'));
    error_log("CRAFTY BOOTSTRAP: COOKIE PHPSESSID: " . ($_COOKIE['PHPSESSID'] ?? 'NONE'));
    error_log("CRAFTY BOOTSTRAP: REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'UNKNOWN'));
    $user = current_user();
    error_log("CRAFTY BOOTSTRAP: Current User: " . ($user ? $user['email'] : 'NONE'));
    $operator = lupo_crafty_operator();
    error_log("CRAFTY BOOTSTRAP: Operator Found: " . ($operator ? 'YES (ID:'.$operator['operator_id'].')' : 'NO'));
}

// Emotional metadata calculation
require_once __DIR__ . '/includes/emotional.php';

// Placeholder for future includes (not needed yet)
// require_once __DIR__ . '/includes/expertise.php';
// require_once __DIR__ . '/includes/escalation.php';

require_login();
require_operator();
