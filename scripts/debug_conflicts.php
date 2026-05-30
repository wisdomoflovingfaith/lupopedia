<?php
/**
 * Debug Actor Conflicts
 * Shows what's causing the unique constraint violations
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG ACTOR CONFLICTS ===\n\n";
    
    // Check existing actors
    echo "Current actors in database:\n";
    $stmt = $conn->query("SELECT actor_name, actor_id, slug FROM lupo_actors ORDER BY actor_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['actor_name']}: ID {$row['actor_id']} ({$row['slug']})\n";
    }
    
    echo "\nCurrent agents in database:\n";
    $stmt = $conn->query("SELECT agent_id, agent_key, agent_name FROM lupo_agents ORDER BY agent_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  Agent {$row['agent_id']}: {$row['agent_name']} ({$row['agent_key']})\n";
    }
    
    // Check for conflicts with required IDs
    $required_ids = [1,2,3,4,5,6,7,8,9,10,11,12,14];
    echo "\nChecking for conflicts with required IDs: " . implode(',', $required_ids) . "\n";
    
    foreach ($required_ids as $id) {
        $stmt = $conn->prepare("SELECT actor_name, actor_id FROM lupo_actors WHERE actor_id = ?");
        $stmt->execute([$id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo "  Conflict ID {$id}: {$existing['actor_name']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
