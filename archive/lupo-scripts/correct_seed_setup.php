<?php
/**
 * Correct Seed Data Setup
 * Uses actor_name as primary key for lupo_actors
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CORRECT SEED DATA SETUP ===\n\n";
    
    // Get current timestamp
    $now = gmdate('YmdHis');
    echo "Using timestamp: {$now}\n\n";
    
    // Required actors (actor_name is primary key)
    $required_actors = [
        'WOLFIE' => [1, 'wolfie', 'WOLFIE'],
        'LILITH' => [2, 'lilith', 'LILITH'],
        'ROSE' => [3, 'rose', 'ROSE'],
        'ATHENA' => [4, 'athena', 'ATHENA'],
        'LEXA' => [5, 'lexa', 'LEXA'],
        'ANUBIS' => [6, 'anubis', 'ANUBIS'],
        'MAAT' => [7, 'maat', 'MAAT'],
        'HEIMDALL' => [8, 'heimdall', 'HEIMDALL'],
        'THEMIS' => [9, 'themis', 'THEMIS'],
        'SESHAT' => [10, 'seshat', 'SESHAT'],
        'THOTH' => [11, 'thoth', 'THOTH'],
        'JANUS' => [12, 'janus', 'JANUS'],
        'HEPHAESTUS' => [14, 'hephaestus', 'HEPHAESTUS'],
    ];
    
    // Clear existing system actors and agents
    echo "Cleaning up existing data...\n";
    $actor_names = "'" . implode("','", array_keys($required_actors)) . "'";
    $conn->exec("DELETE FROM lupo_actors WHERE actor_name IN ({$actor_names})");
    $conn->exec("DELETE FROM lupo_agents WHERE agent_id IN (" . implode(',', [1,2,3,4,5,6,7,8,9,10,11,12,14]) . ")");
    echo "✅ Cleaned existing data\n\n";
    
    // Insert required actors
    echo "Inserting required actors...\n";
    foreach ($required_actors as $actor_name => $actor_data) {
        $sql = "INSERT INTO lupo_actors (actor_name, actor_id, slug, name, actor_type, is_agent, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, 'system', 1, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$actor_name, $actor_data[0], $actor_data[1], $actor_data[2], $now, $now]);
        echo "  ✅ {$actor_name} (ID: {$actor_data[0]})\n";
    }
    
    // Insert required agents
    echo "\nInserting required agents...\n";
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
        $sql = "INSERT INTO lupo_agents (agent_id, agent_key, agent_name, description, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$agent_id, $agent_data[0], $agent_data[1], $agent_data[2], $now, $now]);
        echo "  ✅ {$agent_data[1]} (ID: {$agent_id})\n";
    }
    
    // Check and insert root auth user
    echo "\nChecking root auth user...\n";
    $stmt = $conn->prepare("SELECT auth_user_id FROM lupo_auth_users WHERE auth_user_id = 1000");
    $stmt->execute([1000]);
    
    if (!$stmt->fetch()) {
        $sql = "INSERT INTO lupo_auth_users (auth_user_id, username, email, display_name, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([1000, 'root', 'wisdomoflovingfaith@gmail.com', 'root', $now, $now]);
        echo "  ✅ Root user created\n";
    } else {
        echo "  ⚠️ Root user already exists\n";
    }
    
    // Verify results
    echo "\n=== VERIFICATION ===\n";
    $actor_count = $conn->query("SELECT COUNT(*) FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1")->fetchColumn();
    $agent_count = $conn->query("SELECT COUNT(*) FROM lupo_agents WHERE agent_id IN (" . implode(',', [1,2,3,4,5,6,7,8,9,10,11,12,14]) . ") AND is_deleted = 0")->fetchColumn();
    $auth_count = $conn->query("SELECT COUNT(*) FROM lupo_auth_users WHERE auth_user_id = 1000")->fetchColumn();
    
    echo "Actors: {$actor_count}/13\n";
    echo "Agents: {$agent_count}/13\n";
    echo "Root User: " . ($auth_count > 0 ? "✅ Present" : "❌ Missing") . "\n";
    
    // Show critical actors
    echo "\nCritical Actors:\n";
    $critical_names = ["'WOLFIE'", "'LILITH'", "'ROSE'", "'HEPHAESTUS'"];
    $stmt = $conn->query("SELECT actor_name, actor_id, slug FROM lupo_actors WHERE actor_name IN (" . implode(',', $critical_names) . ") ORDER BY actor_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['actor_name']}: ID {$row['actor_id']} ({$row['slug']})\n";
    }
    
    if ($actor_count == 13 && $agent_count == 13 && $auth_count > 0) {
        echo "\n🎉 SUCCESS: All seed data is ready for fresh install!\n";
        echo "\nNext steps:\n";
        echo "1. Run the Lupopedia installer\n";
        echo "2. Login as root user (username: root)\n";
        echo "3. Select an agent from the 13 available options\n";
        echo "4. Verify actor creation and session management\n";
        echo "\nCritical for install: HEPHAESTUS (actor_id: 14) must be available for selection\n";
    } else {
        echo "\n❌ ERROR: Some seed data is missing.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
