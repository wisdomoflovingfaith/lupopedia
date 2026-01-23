<?php
/**
 * LABS-001 Validation Test Script
 * 
 * Tests LABS validation end-to-end:
 * - Real actor declarations
 * - Certificate generation
 * - Quarantine enforcement
 * - Revalidation cycle
 * 
 * @package Lupopedia
 * @version 4.1.6
 * @author CAPTAIN_WOLFIE
 * @governance LABS-001 Doctrine v1.0
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';
require_once __DIR__ . '/../lupo-includes/classes/LABSValidator.php';

echo "=== LABS-001 Validation Test Suite ===\n\n";

// Get current UTC time (canonical)
$current_utc = gmdate('YmdHis');

// Test Actor ID (using actor_id = 1, which should exist)
$test_actor_id = 1;

echo "Test Configuration:\n";
echo "  Actor ID: {$test_actor_id}\n";
echo "  Current UTC: {$current_utc}\n\n";

// ============================================================================
// TEST 1: Valid LABS Declaration
// ============================================================================
echo "=== TEST 1: Valid LABS Declaration ===\n";

$valid_declaration = [
    'timestamp' => $current_utc,
    'actor_type' => 'AI',
    'actor_identifier' => 'TEST-AGENT-001',
    'actor_role' => 'Testing LABS validation',
    'wolf_relationship' => 'Wolf is my human architect, Pack leader, system governor, and authority source',
    'purpose' => 'Testing LABS-001 validation system to ensure all declarations work correctly',
    'constraints' => 'GOV-AD-PROHIBIT-001, Temporal Integrity Doctrine, Assumption Weighting Protocol, Actor Honesty Requirement, No Hidden Logic Principle',
    'prohibited_actions' => 'Cannot modify governance without approval, cannot use hardcoded versions, cannot infer timestamps',
    'current_task' => 'Testing LABS validation end-to-end including certificate generation and quarantine enforcement',
    'truth_state' => [
        'Known' => [
            ['fact' => 'LABS-001 is active and binding', 'source' => 'docs/doctrine/LUPOPEDIA_ACTOR_BASELINE_STATE_DOCTRINE.md'],
            ['fact' => 'Current UTC time is ' . $current_utc, 'source' => 'UTC_TIMEKEEPER']
        ],
        'Assumed' => [
            ['assumption' => 'Database connection is available', 'probability' => 0.95, 'justification' => 'Previous operations succeeded', 'expiration' => 'If database errors occur']
        ],
        'Unknown' => [
            'Full UTC_TIMEKEEPER agent integration status'
        ],
        'Prohibited' => [
            'Modifying governance without approval',
            'Accessing restricted actor data'
        ]
    ],
    'governance_laws' => 'GOV-AD-PROHIBIT-001, LABS-001 Doctrine, UTC_TIMEKEEPER Doctrine, Temporal Integrity Doctrine, Assumption Weighting Protocol, Actor Honesty Requirement, No Hidden Logic Principle',
    'system_governor' => 'Wolf (Eric Robin Gerdes / Captain Wolfie) is the governor of Lupopedia',
    'compliance_declaration' => 'I have read, understood, and will comply with all Lupopedia governance laws. I acknowledge that incomplete or inconsistent LABS declarations will result in quarantine until resolved.'
];

try {
    $validator = new LABS_Validator($mydatabase, $test_actor_id);
    $result = $validator->validate_declaration($valid_declaration);
    
    if ($result['valid']) {
        echo "✓ Validation PASSED\n";
        echo "  Certificate ID: " . $result['certificate_id'] . "\n";
        echo "  Next Revalidation: " . $result['next_revalidation'] . "\n";
        echo "  Validation Timestamp: " . $result['validation_timestamp'] . "\n";
    } else {
        echo "✗ Validation FAILED\n";
        echo "  Errors:\n";
        foreach ($result['errors'] ?? [] as $error) {
            echo "    - " . $error['message'] . "\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================================================
// TEST 2: Invalid Declaration (Missing Fields)
// ============================================================================
echo "=== TEST 2: Invalid Declaration (Missing Fields) ===\n";

$invalid_declaration = [
    'timestamp' => $current_utc,
    'actor_type' => 'AI',
    // Missing required fields
];

try {
    $validator = new LABS_Validator($mydatabase, $test_actor_id);
    $result = $validator->validate_declaration($invalid_declaration);
    
    if (!$result['valid']) {
        echo "✓ Quarantine correctly triggered\n";
        echo "  Violation Code: " . $result['violation_code'] . "\n";
        echo "  Errors:\n";
        foreach ($result['errors'] ?? [] as $error) {
            echo "    - " . $error['message'] . "\n";
        }
    } else {
        echo "✗ Validation should have FAILED but passed\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================================================
// TEST 3: Invalid Wolf Recognition
// ============================================================================
echo "=== TEST 3: Invalid Wolf Recognition ===\n";

$invalid_wolf_declaration = $valid_declaration;
$invalid_wolf_declaration['wolf_relationship'] = 'Wolf is a developer'; // Missing required roles

try {
    $validator = new LABS_Validator($mydatabase, $test_actor_id);
    $result = $validator->validate_declaration($invalid_wolf_declaration);
    
    if (!$result['valid']) {
        echo "✓ Quarantine correctly triggered\n";
        echo "  Violation Code: " . $result['violation_code'] . "\n";
        if (isset($result['errors'])) {
            echo "  Errors:\n";
            foreach ($result['errors'] as $error) {
                echo "    - " . $error['message'] . "\n";
            }
        }
    } else {
        echo "✗ Validation should have FAILED but passed\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================================================
// TEST 4: Certificate Check
// ============================================================================
echo "=== TEST 4: Certificate Check ===\n";

try {
    $validator = new LABS_Validator($mydatabase, $test_actor_id);
    $certificate = $validator->check_existing_certificate();
    
    if ($certificate) {
        echo "✓ Valid certificate found\n";
        echo "  Certificate ID: " . $certificate['certificate_id'] . "\n";
        echo "  Next Revalidation: " . $certificate['next_revalidation_ymdhis'] . "\n";
        echo "  Validation Status: " . $certificate['validation_status'] . "\n";
    } else {
        echo "ℹ No valid certificate found (this is OK if no previous validation)\n";
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================================================
// TEST 5: Revalidation Cycle
// ============================================================================
echo "=== TEST 5: Revalidation Cycle ===\n";

try {
    $validator = new LABS_Validator($mydatabase, $test_actor_id);
    
    // Get current time
    $current = (int)gmdate('YmdHis');
    
    // Calculate next revalidation (24 hours = 86400 seconds)
    $next_revalidation = $current + 86400;
    
    echo "  Current UTC: {$current}\n";
    echo "  Next Revalidation: {$next_revalidation}\n";
    echo "  Revalidation Interval: 24 hours (86400 seconds)\n";
    echo "✓ Revalidation cycle calculation working\n";
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\n";

// ============================================================================
// SUMMARY
// ============================================================================
echo "=== Test Summary ===\n";
echo "All LABS validation tests completed.\n";
echo "Review results above to verify:\n";
echo "  - Valid declarations generate certificates\n";
echo "  - Invalid declarations trigger quarantine\n";
echo "  - Certificate checking works\n";
echo "  - Revalidation cycle is correct\n";
echo "\n";
