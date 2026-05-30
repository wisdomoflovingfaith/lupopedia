<?php
/**
 * Check Database Status
 * Shows what's currently in the database
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DATABASE STATUS ===\n\n";
    
    // Check actors
    echo "ACTORS:\n";
    $stmt = $conn->query("SELECT actor_id, actor_name, slug FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1 ORDER BY actor_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['actor_id']}: {$row['actor_name']} ({$row['slug']})\n";
    }
    
    echo "\nAGENTS:\n";
    $stmt = $conn->query("SELECT agent_id, agent_key, agent_name FROM lupo_agents WHERE is_deleted = 0 ORDER BY agent_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['agent_id']}: {$row['agent_name']} ({$row['agent_key']})\n";
    }
    
    echo "\nAUTH USERS:\n";
    $stmt = $conn->query("SELECT auth_user_id, username, email FROM lupo_auth_users ORDER BY auth_user_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['auth_user_id']}: {$row['username']} ({$row['email']})\n";
    }
    
    // Counts
    $actor_count = $conn->query("SELECT COUNT(*) FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1")->fetchColumn();
    $agent_count = $conn->query("SELECT COUNT(*) FROM lupo_agents WHERE is_deleted = 0")->fetchColumn();
    $auth_count = $conn->query("SELECT COUNT(*) FROM lupo_auth_users")->fetchColumn();
    
    echo "\nSUMMARY:\n";
    echo "  Actors: {$actor_count}/13\n";
    echo "  Agents: {$agent_count}/13\n";
    echo "  Auth Users: {$auth_count}\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
