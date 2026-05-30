<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/example_agent_flow.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "scripts/example_agent_flow.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Lupopedia Example Agent Flow
 * Demonstration of multi-agent consensus: Lilith -> THEMIS -> WOLFIE
 */
require_once dirname(__FILE__) . '/../lupopedia-config.php';
require_once dirname(__FILE__) . '/../includes/classes/ConsensusService.php';

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
        "Verify that the channels structure meets the empathetic AI requirements."
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

echo "\nCheck 'channels/42/tasks/' and 'channels/42/threads/' for results.\n";
?>
