<?php
/**
 * Test delegation chain activation
 */

require_once 'lupo-includes/bootstrap.php';
require_once 'lupo-includes/functions/ai_activation.php';
require_once 'lupo-includes/functions/ai_checks.php';

echo "=== Testing Delegation Chain Activation ===\n\n";

// Test cases
$test_cases = [
    '2:1:10000' => 'Should activate LILITH (2) and CAPTAIN WOLFIE (1), skip human 10000',
    '19:25:10000' => 'Should activate ANUBIS (19) and VISHWAKARMA (25)',
    '1000:10000' => 'Should activate KIRO (1000)',
    '999:10000' => 'Should fail (actor 999 does not exist)'
];

foreach ($test_cases as $chain => $description) {
    echo "\n🔍 Testing: $chain\n";
    echo "   $description\n";
    
    $results = processDelegationChain($chain, $db);
    
    foreach ($results as $actor_id => $status) {
        $status_display = $status === 'activated' ? '✅' : ($status === 'skipped_human' ? '⏭️' : '❌');
        echo "   $status_display Actor $actor_id: $status\n";
    }
}

echo "\n✅ Test complete\n";
?>
