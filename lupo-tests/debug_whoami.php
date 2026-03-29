<?php
/**
 * Debug Who Am I - Check current session and actor state
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load required classes
require_once __DIR__ . '/lupo-includes/class-DatabaseFactory.php';
require_once __DIR__ . '/lupo-includes/classes/AuthSessionManager.php';

echo "<h1>Who Am I Debug Tool</h1>\n";

// Check session state
echo "<h2>Session State</h2>\n";
echo "Session ID: " . session_id() . "<br>\n";
echo "Session Data: <pre>" . print_r($_SESSION, true) . "</pre><br>\n";

// Check if user is logged in
if (!isset($_SESSION['auth_user_id']) || !isset($_SESSION['actor_id'])) {
    echo "<h2>❌ Not Logged In</h2>\n";
    echo "<p>You are not logged in. Please <a href='login.php'>login here</a>.</p>\n";
    exit;
}

echo "<h2>✅ Logged In User</h2>\n";

// Get session manager
$sessionManager = new AuthSessionManager();
$auth_user_id = $_SESSION['auth_user_id'];
$actor_id = $_SESSION['actor_id'];

echo "<h3>Session Details:</h3>\n";
echo "- Auth User ID: " . $auth_user_id . "<br>\n";
echo "- Actor ID: " . $actor_id . "<br>\n";
echo "- Actor Name: " . ($_SESSION['actor_name'] ?? 'Unknown') . "<br>\n";

// Get database connection
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Check auth user details
echo "<h3>Auth User Details:</h3>\n";
$sql = "SELECT auth_user_id, username, display_name, email, is_active, is_deleted 
        FROM {$prefix}auth_users 
        WHERE auth_user_id = :auth_user_id";
$user = $db->fetchRow($sql, ['auth_user_id' => $auth_user_id]);

if ($user) {
    echo "✅ User found:<br>\n";
    echo "- Username: " . htmlspecialchars($user['username']) . "<br>\n";
    echo "- Display Name: " . htmlspecialchars($user['display_name']) . "<br>\n";
    echo "- Email: " . htmlspecialchars($user['email']) . "<br>\n";
    echo "- Active: " . ($user['is_active'] ? 'YES' : 'NO') . "<br>\n";
} else {
    echo "❌ User NOT found in database<br>\n";
}

// Check current actor details
echo "<h3>Current Actor Details:</h3>\n";
$sql = "SELECT actor_id, actor_name, name, actor_type, is_active, is_deleted 
        FROM {$prefix}actors 
        WHERE actor_id = :actor_id";
$actor = $db->fetchRow($sql, ['actor_id' => $actor_id]);

if ($actor) {
    echo "✅ Actor found:<br>\n";
    echo "- Actor Name: " . htmlspecialchars($actor['actor_name']) . "<br>\n";
    echo "- Display Name: " . htmlspecialchars($actor['name']) . "<br>\n";
    echo "- Actor Type: " . htmlspecialchars($actor['actor_type']) . "<br>\n";
    echo "- Active: " . ($actor['is_active'] ? 'YES' : 'NO') . "<br>\n";
} else {
    echo "❌ Actor NOT found in database<br>\n";
}

// Check actor mapping
echo "<h3>Actor-Auth User Mapping:</h3>\n";
$sql = "SELECT * FROM {$prefix}actor_auth_users 
        WHERE auth_user_id = :auth_user_id AND actor_id = :actor_id";
$mapping = $db->fetchRow($sql, ['auth_user_id' => $auth_user_id, 'actor_id' => $actor_id]);

if ($mapping) {
    echo "✅ Mapping found:<br>\n";
    echo "- Relationship Role: " . htmlspecialchars($mapping['relationship_role']) . "<br>\n";
    echo "- Is Primary: " . ($mapping['is_primary'] ? 'YES' : 'NO') . "<br>\n";
    echo "- Status: " . htmlspecialchars($mapping['status']) . "<br>\n";
    echo "- Routing Priority: " . $mapping['routing_priority'] . "<br>\n";
} else {
    echo "❌ Mapping NOT found<br>\n";
}

// Check all actors for this user
echo "<h3>All Actors for This User:</h3>\n";
$sql = "SELECT a.* 
        FROM {$prefix}actors a
        INNER JOIN {$prefix}actor_auth_users aau ON a.actor_id = aau.actor_id
        WHERE aau.auth_user_id = :auth_user_id 
        AND aau.status = 'active'
        AND aau.is_deleted = 0
        AND a.is_deleted = 0 
        ORDER BY aau.is_primary DESC, aau.routing_priority ASC";
$actors = $db->fetchAll($sql, ['auth_user_id' => $auth_user_id]);

if ($actors) {
    echo "✅ Found " . count($actors) . " actors:<br>\n";
    foreach ($actors as $a) {
        $is_current = ($a['actor_id'] == $actor_id) ? ' ← CURRENT' : '';
        echo "- Actor ID: {$a['actor_id']}, Name: " . htmlspecialchars($a['name']) . $is_current . "<br>\n";
    }
} else {
    echo "❌ No actors found for this user<br>\n";
    
    // Check direct auth_user_id link
    $sql = "SELECT a.* 
            FROM {$prefix}actors a
            WHERE a.auth_user_id = :auth_user_id 
            AND a.is_active = 1 
            AND a.is_deleted = 0";
    $direct_actors = $db->fetchAll($sql, ['auth_user_id' => $auth_user_id]);
    
    if ($direct_actors) {
        echo "✅ Found " . count($direct_actors) . " actors via direct auth_user_id link:<br>\n";
        foreach ($direct_actors as $a) {
            $is_current = ($a['actor_id'] == $actor_id) ? ' ← CURRENT' : '';
            echo "- Actor ID: {$a['actor_id']}, Name: " . htmlspecialchars($a['name']) . $is_current . "<br>\n";
        }
    }
}

// Test getActorForAuthUser method
echo "<h3>Test getActorForAuthUser Method:</h3>\n";
$test_actor = $sessionManager->getActorForAuthUser($auth_user_id);
if ($test_actor) {
    echo "✅ Method found actor:<br>\n";
    echo "- Actor ID: " . $test_actor['actor_id'] . "<br>\n";
    echo "- Actor Name: " . htmlspecialchars($test_actor['actor_name']) . "<br>\n";
    echo "- Display Name: " . htmlspecialchars($test_actor['name']) . "<br>\n";
} else {
    echo "❌ Method did NOT find any actor<br>\n";
}

// Test getActorsUserCanActAs method
echo "<h3>Test getActorsUserCanActAs Method:</h3>\n";
$available_actors = $sessionManager->getActorsUserCanActAs($auth_user_id, false);
if ($available_actors) {
    echo "✅ Method found " . count($available_actors) . " actors:<br>\n";
    foreach ($available_actors as $a) {
        $is_current = ($a['actor_id'] == $actor_id) ? ' ← CURRENT' : '';
        echo "- Actor ID: {$a['actor_id']}, Name: " . htmlspecialchars($a['name']) . $is_current . "<br>\n";
    }
} else {
    echo "❌ Method did NOT find any actors<br>\n";
}

echo "<hr>\n";
echo "<p><a href='../my-profile'>My Profile</a> | <a href='../admin.php'>Admin</a> | <a href='../logout.php'>Logout</a></p>\n";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Who Am I Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        h3 { color: #888; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
</body>
</html>
