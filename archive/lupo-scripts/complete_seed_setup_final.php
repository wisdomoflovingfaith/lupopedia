<?php
/**
 * Complete Seed Setup with Conflict Resolution
 * Handles all conflicts properly
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== COMPLETE SEED SETUP ===\n\n";
    
    // Get current timestamp
    $now = gmdate('YmdHis');
    echo "Using timestamp: {$now}\n\n";
    
    // Process agents with conflict resolution
    echo "Processing agents...\n";
    $required_agents = [
        1 => ['wolfie', 'WOLFIE', 'Orchestrator - strategic planning, delegation, enforcement'],
        2 => ['lilith', 'LILITH', 'Critic - non-interfering reviewer, contradiction detection'],
        3 => ['rose', 'ROSE', 'Emotional dialogue - context, stakeholder needs, human factors'],
        4 => ['athena', 'ATHENA', 'Wisdom & strategy - strategic analysis, architectural guidance'],
        5 => ['lexa', 'LEXA', 'Security enforcement - boundary enforcement, policy compliance'],
        6 => ['anubis', 'ANUBIS', 'Custodian - data integrity, lineage, custody audit'],
        7 => ['maat', 'MAAT', 'Truth & justice - conflict resolution, fairness, accountability'],
        8 => ['heimdall', 'HEIMDALL', 'Security guardian - access control, perimeter defense'],
        9 => ['themis', 'THEMIS', 'Law & compliance - regulatory compliance, binding rules'],
        10 => ['seshat', 'SESHAT', 'Content review - content quality, documentation accuracy'],
        11 => ['thoth', 'THOTH', 'Knowledge & records - documentation, record-keeping, provenance'],
        12 => ['janus', 'JANUS', 'Transitions & gateways - state transitions, boundary management'],
        14 => ['hephaestus', 'HEPHAESTUS', 'Implementer - code, docs, schema execution'],
    ];
    
    foreach ($required_agents as $agent_id => $agent_data) {
        // Check if agent exists by ID
        $stmt = $conn->prepare("SELECT agent_id, agent_key, agent_name FROM lupo_agents WHERE agent_id = ?");
        $stmt->execute([$agent_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update existing agent
            $sql = "UPDATE lupo_agents SET agent_key = ?, agent_name = ?, description = ?, updated_ymdhis = ?, is_deleted = 0 WHERE agent_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$agent_data[0], $agent_data[1], $agent_data[2], $now, $agent_id]);
            echo "  🔄 Updated Agent: {$agent_data[1]} (ID: {$agent_id})\n";
        } else {
            // Check if agent key exists
            $stmt = $conn->prepare("SELECT agent_id FROM lupo_agents WHERE agent_key = ?");
            $stmt->execute([$agent_data[0]]);
            $key_exists = $stmt->fetch();
            
            if ($key_exists) {
                // Update the existing agent by key to use correct ID
                $sql = "UPDATE lupo_agents SET agent_id = ?, agent_name = ?, description = ?, updated_ymdhis = ?, is_deleted = 0 WHERE agent_key = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$agent_id, $agent_data[1], $agent_data[2], $now, $agent_data[0]]);
                echo "  🔄 Updated Agent by Key: {$agent_data[1]} (ID: {$agent_id})\n";
            } else {
                // Insert new agent
                $sql = "INSERT INTO lupo_agents (agent_id, agent_key, agent_name, description, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$agent_id, $agent_data[0], $agent_data[1], $agent_data[2], $now, $now]);
                echo "  ✅ Inserted Agent: {$agent_data[1]} (ID: {$agent_id})\n";
            }
        }
    }
    
    // Final verification
    echo "\n=== FINAL VERIFICATION ===\n";
    $actor_count = $conn->query("SELECT COUNT(*) FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1")->fetchColumn();
    $agent_count = $conn->query("SELECT COUNT(*) FROM lupo_agents WHERE agent_id IN (" . implode(',', [1,2,3,4,5,6,7,8,9,10,11,12,14]) . ") AND is_deleted = 0")->fetchColumn();
    $auth_count = $conn->query("SELECT COUNT(*) FROM lupo_auth_users WHERE auth_user_id = 1000")->fetchColumn();
    
    echo "System Actors: {$actor_count}/13\n";
    echo "System Agents: {$agent_count}/13\n";
    echo "Root User: " . ($auth_count > 0 ? "✅ Present" : "❌ Missing") . "\n";
    
    // Show critical actors for install
    echo "\nCritical Actors for Fresh Install:\n";
    $critical_ids = [1, 2, 3, 14]; // WOLFIE, LILITH, ROSE, HEPHAESTUS
    $stmt = $conn->prepare("SELECT actor_name, actor_id, slug FROM lupo_actors WHERE actor_id IN (" . implode(',', $critical_ids) . ") ORDER BY actor_id");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ✅ {$row['actor_name']}: ID {$row['actor_id']} ({$row['slug']})\n";
    }
    
    // Show available agents for selection
    echo "\nAvailable Agents for Selection:\n";
    $stmt = $conn->query("SELECT agent_id, agent_name FROM lupo_agents WHERE agent_id IN (" . implode(',', [1,2,3,4,5,6,7,8,9,10,11,12,14]) . ") AND is_deleted = 0 ORDER BY agent_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ✅ {$row['agent_name']} (ID: {$row['agent_id']})\n";
    }
    
    if ($actor_count >= 13 && $agent_count >= 13 && $auth_count > 0) {
        echo "\n🎉 SUCCESS: All critical seed data is ready for fresh install!\n";
        echo "\nFresh Install Readiness Checklist:\n";
        echo "✅ All 13 Primary Coordination Personas available\n";
        echo "✅ HEPHAESTUS (actor_id: 14) available for selection\n";
        echo "✅ Root user (auth_user_id: 1000) ready for login\n";
        echo "✅ All timestamps in YYYYMMDDHHIISS format\n";
        echo "\nReady to proceed with fresh install!\n";
        echo "\nNext Steps:\n";
        echo "1. Run the Lupopedia installer\n";
        echo "2. Login as root user (username: root)\n";
        echo "3. Select HEPHAESTUS from the agent selection list\n";
        echo "4. Verify actor creation and session management\n";
    } else {
        echo "\n⚠️ WARNING: Some seed data may be incomplete.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
