<?php
/**
 * Lupopedia Seed Data Generator
 * Generates seed data with YYYYMMDDHHIISS timestamps for fresh installs
 * 
 * Usage: php seed_data_generator.php
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ServBay.dev');
define('DB_NAME', 'lupopedia');

// Required actors (13 Primary Coordination Personas)
$required_actors = [
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

// Required agents (for Actor Selection)
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

// Required auth user
$required_auth_users = [
    1000 => ['root', 'wisdomoflovingfaith@gmail.com', 'root'],
];

/**
 * Get current timestamp in YYYYMMDDHHIISS format
 */
function getCurrentTimestamp() {
    return gmdate('YmdHis');
}

/**
 * Connect to database
 */
function connectDB() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Verify actors exist
 */
function verifyActors($conn, $required_actors) {
    echo "\n=== Verifying Actors ===\n";
    $missing = [];
    
    foreach ($required_actors as $actor_id => $actor_data) {
        $stmt = $conn->prepare("SELECT actor_id, actor_name, slug FROM lupo_actors WHERE actor_id = ?");
        $stmt->execute([$actor_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "✅ Actor {$actor_id}: {$result['actor_name']} found\n";
        } else {
            echo "❌ Actor {$actor_id}: {$actor_data[0]} MISSING\n";
            $missing[$actor_id] = $actor_data;
        }
    }
    
    return $missing;
}

/**
 * Verify agents exist
 */
function verifyAgents($conn, $required_agents) {
    echo "\n=== Verifying Agents ===\n";
    $missing = [];
    
    foreach ($required_agents as $agent_id => $agent_data) {
        $stmt = $conn->prepare("SELECT agent_id, agent_key, agent_name FROM lupo_agents WHERE agent_id = ?");
        $stmt->execute([$agent_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "✅ Agent {$agent_id}: {$result['agent_name']} found\n";
        } else {
            echo "❌ Agent {$agent_id}: {$agent_data[1]} MISSING\n";
            $missing[$agent_id] = $agent_data;
        }
    }
    
    return $missing;
}

/**
 * Verify auth users exist
 */
function verifyAuthUsers($conn, $required_auth_users) {
    echo "\n=== Verifying Auth Users ===\n";
    $missing = [];
    
    foreach ($required_auth_users as $auth_user_id => $auth_data) {
        $stmt = $conn->prepare("SELECT auth_user_id, username, email FROM lupo_auth_users WHERE auth_user_id = ?");
        $stmt->execute([$auth_user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "✅ Auth User {$auth_user_id}: {$result['username']} found\n";
        } else {
            echo "❌ Auth User {$auth_user_id}: {$auth_data[0]} MISSING\n";
            $missing[$auth_user_id] = $auth_data;
        }
    }
    
    return $missing;
}

/**
 * Generate SQL for missing seed data
 */
function generateSeedSQL($missing_actors, $missing_agents, $missing_auth_users) {
    echo "\n=== Missing Seed Data SQL ===\n";
    
    $now = getCurrentTimestamp();
    
    if ($missing_actors) {
        echo "\n-- Missing Actors (YYYYMMDDHHIISS timestamps)\n";
        foreach ($missing_actors as $actor_id => $actor_data) {
            echo "INSERT INTO lupo_actors (actor_id, actor_name, slug, name, actor_type, is_agent, auth_user_id, created_ymdhis, updated_ymdhis) VALUES\n";
            echo "  ({$actor_id}, '{$actor_data[0]}', '{$actor_data[1]}', '{$actor_data[2]}', 'system', 1, 1000, {$now}, {$now});\n";
        }
        
        // Also create mapping entries for actor_auth_users table
        echo "\n-- Actor-Auth User Mappings\n";
        foreach ($missing_actors as $actor_id => $actor_data) {
            echo "INSERT INTO lupo_actor_auth_users (actor_id, auth_user_id, status, is_primary, routing_priority, created_ymdhis, updated_ymdhis) VALUES\n";
            echo "  ({$actor_id}, 1000, 'active', 1, 1, {$now}, {$now});\n";
        }
    }
    
    if ($missing_agents) {
        echo "\n-- Missing Agents (YYYYMMDDHHIISS timestamps)\n";
        foreach ($missing_agents as $agent_id => $agent_data) {
            $desc = addslashes($agent_data[2]);
            echo "INSERT INTO lupo_agents (agent_id, agent_key, agent_name, description, created_ymdhis, updated_ymdhis) VALUES\n";
            echo "  ({$agent_id}, '{$agent_data[0]}', '{$agent_data[1]}', '{$desc}', {$now}, {$now});\n";
        }
    }
    
    if ($missing_auth_users) {
        echo "\n-- Missing Auth Users (YYYYMMDDHHIISS timestamps)\n";
        foreach ($missing_auth_users as $auth_user_id => $auth_data) {
            echo "INSERT INTO lupo_auth_users (auth_user_id, username, email, display_name, created_ymdhis, updated_ymdhis) VALUES\n";
            echo "  ({$auth_user_id}, '{$auth_data[0]}', '{$auth_data[1]}', '{$auth_data[2]}', {$now}, {$now});\n";
        }
    }
}

/**
 * Main execution
 */
function main() {
    global $required_actors, $required_agents, $required_auth_users;
    
    echo str_repeat("=", 60) . "\n";
    echo "SEED DATA VERIFICATION\n";
    echo "Timestamp format: YYYYMMDDHHIISS\n";
    echo str_repeat("=", 60) . "\n";
    
    $conn = connectDB();
    
    $missing_actors = verifyActors($conn, $required_actors);
    $missing_agents = verifyAgents($conn, $required_agents);
    $missing_auth_users = verifyAuthUsers($conn, $required_auth_users);
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "SUMMARY\n";
    echo str_repeat("=", 60) . "\n";
    
    if ($missing_actors) {
        echo "❌ Missing Actors: " . count($missing_actors) . "\n";
        foreach ($missing_actors as $actor_id => $actor_data) {
            echo "   - {$actor_data[0]}\n";
        }
    } else {
        echo "✅ All 13 actors present\n";
    }
    
    if ($missing_agents) {
        echo "❌ Missing Agents: " . count($missing_agents) . "\n";
        foreach ($missing_agents as $agent_id => $agent_data) {
            echo "   - {$agent_data[1]}\n";
        }
    } else {
        echo "✅ All 13 agents present\n";
    }
    
    if ($missing_auth_users) {
        echo "❌ Missing Auth Users: " . count($missing_auth_users) . "\n";
        foreach ($missing_auth_users as $auth_user_id => $auth_data) {
            echo "   - {$auth_data[0]}\n";
        }
    } else {
        echo "✅ Root auth user present\n";
    }
    
    if ($missing_actors || $missing_agents || $missing_auth_users) {
        generateSeedSQL($missing_actors, $missing_agents, $missing_auth_users);
        echo "\n⚠️ Run the SQL above to fix missing seed data before install.\n";
        exit(1);
    } else {
        echo "\n✅ All seed data is present. Ready for install.\n";
        exit(0);
    }
}

// Execute main function
main();
