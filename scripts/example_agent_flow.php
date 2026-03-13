<?php
/**
 * Lupopedia Example Agent Flow
 * Demonstration of multi-agent consensus: Lilith -> THEMIS -> WOLFIE
 */
require_once dirname(__FILE__) . '/../lupopedia-config.php';
require_once dirname(__FILE__) . '/../lupo-includes/classes/ConsensusService.php';

// Initialize core services
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$consensus = new ConsensusService($db, $prefix);

$channelId = 42;
$lilithActorId = 2038;

echo "--- Lupopedia Multi-Agent Flow Start ---\n";

try {
    // 1. Lilith initiates a task
    echo "[1] Lilith proposing architectural review...\n";
    $taskKey = $consensus->initiateConsensusTask(
        $channelId,
        $lilithActorId,
        "Validate Version 4.0.66 Migration",
        "Verify that the lupo-channels structure meets the empathetic AI requirements."
    );
    echo "Task Created: $taskKey (Status: Pending)\n\n";

    // 2. THEMIS Audits
    echo "[2] Triggering THEMIS audit (Slot 107)...\n";
    $auditPassed = $consensus->auditTask($channelId, $taskKey);

    if ($auditPassed) {
        echo "Audit Passed! Task moved to Active.\n\n";

        // 3. WOLFIE Approves
        echo "[3] Requesting Final Approval from WOLFIE (Slot 1)...\n";
        $finalized = $consensus->finalizeConsensus($channelId, $taskKey);

        if ($finalized) {
            echo "Workflow COMPLETED successfully.\n";
        }
    } else {
        echo "Audit FAILED. Flow terminated.\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\nCheck 'lupo-channels/42/tasks/' and 'lupo-channels/42/threads/' for results.\n";
?>
