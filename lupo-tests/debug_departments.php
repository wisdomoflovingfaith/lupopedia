<?php
/**
 * Test Department-Based Actor Access Control
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load required classes
require_once __DIR__ . '/lupo-includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/lupo-includes/classes/AuthSessionManager.php';

echo "<h1>Department-Based Access Control Test</h1>\n";

// Get database connection
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Check if department 0 exists
echo "<h2>Department 0 Check</h2>\n";
$sql = "SELECT * FROM {$prefix}departments WHERE department_id = 0";
$dept0 = $db->fetchRow($sql);

if ($dept0) {
    echo "✅ Department 0 found:<br>\n";
    echo "- Name: " . htmlspecialchars($dept0['name']) . "<br>\n";
    echo "- Type: " . htmlspecialchars($dept0['department_type']) . "<br>\n";
    echo "- Default Actor ID: " . $dept0['default_actor_id'] . "<br>\n";
} else {
    echo "❌ Department 0 NOT found<br>\n";
    echo "<p>Please run: <code>mysql -u root -p lupopedia < lupo-database/lupopedia/mysql/seed/seed_departments.sql</code></p>\n";
}

// Check actor department mappings
echo "<h2>Actor Department Mappings</h2>\n";
$sql = "SELECT ad.actor_id, a.actor_name, ad.department_id, d.name as dept_name 
        FROM {$prefix}actor_departments ad
        INNER JOIN {$prefix}actors a ON ad.actor_id = a.actor_id
        INNER JOIN {$prefix}departments d ON ad.department_id = d.department_id
        WHERE a.actor_id <= 14 AND ad.is_deleted = 0
        ORDER BY a.actor_id";
$actor_mappings = $db->fetchAll($sql);

if ($actor_mappings) {
    echo "✅ System actors (1-14) department mappings:<br>\n";
    foreach ($actor_mappings as $mapping) {
        echo "- Actor ID: {$mapping['actor_id']}, Name: " . htmlspecialchars($mapping['actor_name']) . ", Dept: {$mapping['department_id']} ({$mapping['dept_name']})<br>\n";
    }
} else {
    echo "❌ No actor department mappings found<br>\n";
}

// Check auth user department mappings
echo "<h2>Auth User Department Mappings</h2>\n";
$sql = "SELECT aud.auth_user_id, au.username, au.display_name, aud.department_id, d.name as dept_name, aud.is_primary
        FROM {$prefix}auth_user_departments aud
        INNER JOIN {$prefix}auth_users au ON aud.auth_user_id = au.auth_user_id
        INNER JOIN {$prefix}departments d ON aud.department_id = d.department_id
        WHERE aud.is_deleted = 0 AND au.is_deleted = 0
        ORDER BY aud.is_primary DESC, au.username";
$user_mappings = $db->fetchAll($sql);

if ($user_mappings) {
    echo "✅ Auth user department mappings:<br>\n";
    foreach ($user_mappings as $mapping) {
        $primary = $mapping['is_primary'] ? ' (PRIMARY)' : '';
        echo "- User ID: {$mapping['auth_user_id']}, Username: " . htmlspecialchars($mapping['username']) . ", Dept: {$mapping['department_id']} ({$mapping['dept_name']}){$primary}<br>\n";
    }
} else {
    echo "❌ No auth user department mappings found<br>\n";
}

// Test user department lookup
if (isset($_SESSION['auth_user_id'])) {
    echo "<h2>User Department Test</h2>\n";
    $sessionManager = new AuthSessionManager();
    $user_dept = $sessionManager->getUserDepartment($_SESSION['auth_user_id']);
    echo "User Primary Department: {$user_dept}<br>\n";
    
    // Get all user departments
    $user_depts = $sessionManager->getUserDepartments($_SESSION['auth_user_id']);
    if ($user_depts) {
        echo "All User Departments:<br>\n";
        foreach ($user_depts as $dept) {
            $primary = $dept['is_primary'] ? ' (PRIMARY)' : '';
            echo "- Dept ID: {$dept['department_id']}, Name: " . htmlspecialchars($dept['name']) . $primary . "<br>\n";
        }
    }
    
    // Test getActorsUserCanActAs
    echo "<h2>Available Actors for User</h2>\n";
    $available_actors = $sessionManager->getActorsUserCanActAs($_SESSION['auth_user_id']);
    
    if ($available_actors) {
        echo "✅ Found " . count($available_actors) . " actors:<br>\n";
        foreach ($available_actors as $actor) {
            // Check if actor is in user's departments
            $actor_in_user_dept = false;
            foreach ($user_depts as $dept) {
                if ($actor['department_id'] == $dept['department_id']) {
                    $actor_in_user_dept = true;
                    break;
                }
            }
            $dept_match = $actor_in_user_dept ? "✅" : "❌";
            echo "{$dept_match} Actor ID: {$actor['actor_id']}, Name: " . htmlspecialchars($actor['name']) . ", Dept: {$actor['department_id']}<br>\n";
        }
    } else {
        echo "❌ No actors available<br>\n";
    }
} else {
    echo "<h2>Login Required</h2>\n";
    echo "<p>Please <a href='../login.php'>login</a> to test user-specific access.</p>\n";
}

echo "<hr>\n";
echo "<p><a href='../my-profile'>My Profile</a> | <a href='../admin.php'>Admin</a> | <a href='../select_agent.php'>Select Agent</a></p>\n";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Access Control Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
</body>
</html>
