<?php
/**
 * Execute Seed Data SQL (Robust Version)
 * Checks for existing records before inserting
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n";
    
    // Get current timestamp
    $now = gmdate('YmdHis');
    echo "Using timestamp: {$now}\n";
    
    // Required actors
    $actors = [
        1 => ['WOLFIE', 'wolfie', 'WOLFIE'],
        2 => ['LILITH', 'lilith', 'LILITH'],
        3 => ['ROSE', 'rose', 'ROSE'],
        4 => ['ATHENA', 'athena', 'ATHENA'],
        5 => ['LEXA', 'lexa', 'LEXA'],
        6 => ['ANUBIS', 'anubis', 'ANUBIS'],
        7 => ['MAAT', 'maat', 'MAAT'],
        8 => ['HEIMDALL', 'heimdall', 'HEIMDALL'],
        9 => ['THEMIS', 'themis', 'THEMIS'],
        10 => ['SESHAT', 'seshat', 'SESHAT'],
        11 => ['THOTH', 'thoth', 'THOTH'],
        12 => ['JANUS', 'janus', 'JANUS'],
        14 => ['HEPHAESTUS', 'hephaestus', 'HEPHAESTUS'],
    ];
    
    foreach ($actors as $actor_id => $actor_data) {
        // Check if exists
        $stmt = $conn->prepare("SELECT actor_id FROM lupo_actors WHERE actor_id = ?");
        $stmt->execute([$actor_id]);
        
        if (!$stmt->fetch()) {
            // Insert if not exists
            $sql = "INSERT INTO lupo_actors (actor_id, actor_name, slug, name, actor_type, is_agent, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, 'system', 1, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$actor_id, $actor_data[0], $actor_data[1], $actor_data[2], $now, $now]);
            echo "✅ Inserted Actor: {$actor_data[0]}\n";
        } else {
            echo "⚠️ Actor already exists: {$actor_data[0]}\n";
        }
    }
    
    // Required agents
    $agents = [
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
    
    foreach ($agents as $agent_id => $agent_data) {
        // Check if exists
        $stmt = $conn->prepare("SELECT agent_id FROM lupo_agents WHERE agent_id = ?");
        $stmt->execute([$agent_id]);
        
        if (!$stmt->fetch()) {
            // Insert if not exists
            $sql = "INSERT INTO lupo_agents (agent_id, agent_key, agent_name, description, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$agent_id, $agent_data[0], $agent_data[1], $agent_data[2], $now, $now]);
            echo "✅ Inserted Agent: {$agent_data[1]}\n";
        } else {
            echo "⚠️ Agent already exists: {$agent_data[1]}\n";
        }
    }
    
    // Check and insert auth user
    $stmt = $conn->prepare("SELECT auth_user_id FROM lupo_auth_users WHERE auth_user_id = ?");
    $stmt->execute([1000]);
    
    if (!$stmt->fetch()) {
        $sql = "INSERT INTO lupo_auth_users (auth_user_id, username, email, display_name, created_ymdhis, updated_ymdhis) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([1000, 'root', 'wisdomoflovingfaith@gmail.com', 'root', $now, $now]);
        echo "✅ Inserted Auth User: root\n";
    } else {
        echo "⚠️ Auth User already exists: root\n";
    }
    
    echo "\n✅ Seed data check completed!\n";
    
    // Verify counts
    $actor_count = $conn->query("SELECT COUNT(*) FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1")->fetchColumn();
    $agent_count = $conn->query("SELECT COUNT(*) FROM lupo_agents WHERE is_deleted = 0")->fetchColumn();
    $auth_count = $conn->query("SELECT COUNT(*) FROM lupo_auth_users WHERE auth_user_id = 1000")->fetchColumn();
    
    echo "Actors: {$actor_count}/13\n";
    echo "Agents: {$agent_count}/13\n";
    echo "Root User: " . ($auth_count > 0 ? "✅ Present" : "❌ Missing") . "\n";
    
    if ($actor_count == 13 && $agent_count == 13 && $auth_count > 0) {
        echo "\n🎉 All seed data is ready for install!\n";
    } else {
        echo "\n⚠️ Some seed data may still be missing.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
