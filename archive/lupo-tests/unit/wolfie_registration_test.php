<?php
/**
 * WOLFIE Registration Validation Test
 * Actor ID 1 - Main Orchestrating Actor
 */

echo "=== WOLFIE Registration Validation Test ===\n";

// Test 1: Check registry entry
echo "Test 1: Checking actor registry entry...\n";
$registryFile = __DIR__ . '/../../lupo-database/lupopedia/actors/actor_id/registry.json';
if (file_exists($registryFile)) {
    $registry = json_decode(file_get_contents($registryFile), true);
    $wolfieFound = false;
    foreach ($registry['actors'] as $actor) {
        if ($actor['id'] === 1 && $actor['slug'] === 'wolfie') {
            $wolfieFound = true;
            echo "✓ WOLFIE found in registry: id=1, slug=wolfie, type={$actor['type']}\n";
            break;
        }
    }
    if (!$wolfieFound) {
        echo "✗ WOLFIE not found in registry\n";
        exit(1);
    }
} else {
    echo "✗ Registry file not found\n";
    exit(1);
}

// Test 2: Check agent configuration
echo "\nTest 2: Checking agent configuration...\n";
$agentConfigFile = __DIR__ . '/../../lupo-agents/1/agent.json';
if (file_exists($agentConfigFile)) {
    $agentConfig = json_decode(file_get_contents($agentConfigFile), true);
    if ($agentConfig['code'] === 'WOLFIE' && $agentConfig['role'] === 'main_orchestrating_actor') {
        echo "✓ Agent configuration valid: code=WOLFIE, role=main_orchestrating_actor\n";
        // Check aliases
        if (isset($agentConfig['aliases']) && 
            in_array('CAPTAIN', $agentConfig['aliases']) && 
            in_array('ROOT', $agentConfig['aliases'])) {
            echo "✓ Aliases configured: CAPTAIN, ROOT\n";
        } else {
            echo "✗ Missing aliases CAPTAIN and/or ROOT\n";
            exit(1);
        }
    } else {
        echo "✗ Invalid agent configuration\n";
        exit(1);
    }
} else {
    echo "✗ Agent configuration file not found\n";
    exit(1);
}

// Test 3: Check capabilities
echo "\nTest 3: Checking capabilities...\n";
$capabilitiesFile = __DIR__ . '/../../lupo-agents/1/capabilities.json';
if (file_exists($capabilitiesFile)) {
    $capabilities = json_decode(file_get_contents($capabilitiesFile), true);
    $requiredCapabilities = ['orchestration', 'coordination', 'continuity_maintenance'];
    $allFound = true;
    foreach ($requiredCapabilities as $cap) {
        if (!in_array($cap, $capabilities['capabilities'])) {
            echo "✗ Missing capability: $cap\n";
            $allFound = false;
        }
    }
    if ($allFound) {
        echo "✓ All required capabilities present\n";
    }
} else {
    echo "✗ Capabilities file not found\n";
    exit(1);
}

// Test 4: Check system prompt
echo "\nTest 4: Checking system prompt...\n";
$systemPromptFile = __DIR__ . '/../../lupo-agents/1/system_prompt.txt';
if (file_exists($systemPromptFile)) {
    $prompt = file_get_contents($systemPromptFile);
    if (strpos($prompt, 'WOLFIE') !== false && strpos($prompt, 'main orchestrating actor') !== false) {
        echo "✓ System prompt contains required elements\n";
    } else {
        echo "✗ System prompt missing required elements\n";
        exit(1);
    }
} else {
    echo "✗ System prompt file not found\n";
    exit(1);
}

// Test 5: Check actor directory
echo "\nTest 5: Checking actor directory structure...\n";
$actorDirFiles = [
    'agent.json',
    'capabilities.json', 
    'properties.json',
    'system_prompt.txt',
    'README.md'
];
$allFilesExist = true;
foreach ($actorDirFiles as $file) {
    $filePath = __DIR__ . "/../../lupo-actors/1/$file";
    if (!file_exists($filePath)) {
        echo "✗ Missing file in actor directory: $file\n";
        $allFilesExist = false;
    }
}
if ($allFilesExist) {
    echo "✓ All required files present in actor directory\n";
}

// Test 6: Check paired actor relationship
echo "\nTest 6: Checking paired actor relationship...\n";
$propertiesFile = __DIR__ . '/../../lupo-agents/1/properties.json';
if (file_exists($propertiesFile)) {
    $properties = json_decode(file_get_contents($propertiesFile), true);
    if (isset($properties['properties']['paired_actor_id']) && 
        $properties['properties']['paired_actor_id'] === 102) {
        echo "✓ Properly paired with Cursor IDE (actor_id 102)\n";
    } else {
        echo "✗ Invalid or missing paired actor relationship\n";
        exit(1);
    }
} else {
    echo "✗ Properties file not found\n";
    exit(1);
}

echo "\n=== All WOLFIE Registration Tests Passed! ===\n";
echo "WOLFIE (actor_id 1) is properly registered as the main orchestrating actor.\n";
echo "Ready to support Cursor IDE and maintain system continuity.\n";
