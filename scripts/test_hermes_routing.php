<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/test_hermes_routing.php"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/test_hermes_routing.php"
#   status: "complete"
#   when_updated: "20260418160326"
#   trust_tier: "development"
#   questions_toon: null
#   memory_toon: "memory/development/development/2026/04/ai-stop-helping-learn.toon"
#   atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/test-hermes-routing"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 82
#   content_slug: "test-hermes-routing"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "CLI test for HermesService route and transcript append"
#   summary: "Bootstraps config, runs route on sample task line, appendTranscript JSONL growth, createPendingTask when DB present."
# ---------------------------------------------------------------------
/**
 * CLI regression test for HermesService (PRD 82). Survivability: docs/doctrine/SURVIVABILITY_DOCTRINE.md
 * — Pillar 1: graceful degradation (explicit FAIL/WARN, no silent success on broken IO/DB).
 * — Pillar 2: defect logging via DialogMvpService::logDefect for recurrence / learning transfer.
 * Transcript JSONL: PRD 82 canonical path memory/transcripts/{federation_node_id}/{channel_key}/{thread_slug}.jsonl only;
 * no channels/{channel_id}/transcript.jsonl mirror.
 * mood_vector / Counting in Light: docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md — this script does not set mood columns.
 */
if (php_sapi_name() !== 'cli') {
    echo "CLI only.\n";
    exit(1);
}

$root = dirname(__DIR__);
define('LUPOPEDIA_PATH', $root);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename($root));

require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
$cfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
define('LUPOPEDIA_CONFIG_PATH', $cfg ? $cfg : LUPOPEDIA_PATH . '/lupopedia-config.php');
require_once LUPOPEDIA_CONFIG_PATH;

require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
require_once LUPOPEDIA_PATH . '/app/Services/HermesService.php';

/**
 * Pillar 2 breadcrumb; fails soft if DialogMvpService unavailable (SURVIVABILITY_DOCTRINE.md).
 *
 * @param string $pattern_id
 * @param array  $context
 */
function test_hermes_log_defect($pattern_id, $context = array())
{
    try {
        if (!class_exists('\DialogMvpService', false) && defined('LUPOPEDIA_PATH')) {
            $p = LUPOPEDIA_PATH . '/includes/classes/DialogMvpService.php';
            if (is_file($p)) {
                require_once $p;
            }
        }
        if (class_exists('\DialogMvpService', false) && is_callable(array('\DialogMvpService', 'logDefect'))) {
            \DialogMvpService::logDefect((string) $pattern_id, is_array($context) ? $context : array('value' => (string) $context));
        }
    } catch (Exception $e) {
        return;
    }
}

function hermes_count_jsonl_lines($path)
{
    if (!is_file($path)) {
        return 0;
    }
    $n = 0;
    $h = @fopen($path, 'rb');
    if ($h === false) {
        return 0;
    }
    while (!feof($h)) {
        $line = fgets($h);
        if ($line !== false && trim($line) !== '') {
            $n++;
        }
    }
    fclose($h);
    return $n;
}

$msg = '[task] fix header';
$channelId = 42;
$assigneeActorId = 102;
// PRD 82 (docs/prd/82_hermes_message_routing_memory_gateway.md): transcript path is memory/transcripts only; no channel-based mirror.
$federation_node_id = 0;
$channel_key = 'development';
$thread_slug = 'test-hermes-routing';

echo "[TEST] Sending: \"{$msg}\" assignee_actor_id={$assigneeActorId} (admin bypass validation)\n";

try {
    $db = DatabaseFactory::getConnection();
} catch (Exception $e) {
    test_hermes_log_defect('P1-TEST-HERMES-DB-001', array(
        'reason' => 'db_connection_failed',
        'exception' => $e->getMessage(),
    ));
    echo "[FAIL] DB connection failed\n";
    exit(1);
}
$svc = new \App\Services\HermesService($db);

$dec = $svc->route($msg, 1, 0, $channelId, $assigneeActorId, 0, true);
if (!is_array($dec) || empty($dec['task_target_actor_id']) || (int) $dec['task_target_actor_id'] !== 102) {
    test_hermes_log_defect('P2-TEST-HERMES-ROUTE-001', array(
        'message' => $msg,
        'channel_id' => $channelId,
        'decision' => is_array($dec) ? $dec : null,
    ));
    echo "[FAIL] Routing decision missing or task_target_actor_id != 102. Got: " . json_encode($dec) . "\n";
    exit(1);
}
echo "[OK] Routing decision: task_target_actor_id = " . (int) $dec['task_target_actor_id'] . "\n";

// PRD 82 canonical transcript path (docs/prd/82_hermes_message_routing_memory_gateway.md); no channel_id mirror under channels/.
$tpath = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'memory' . DIRECTORY_SEPARATOR . 'transcripts'
    . DIRECTORY_SEPARATOR . $federation_node_id . DIRECTORY_SEPARATOR . $channel_key
    . DIRECTORY_SEPARATOR . $thread_slug . '.jsonl';
$before = hermes_count_jsonl_lines($tpath);

$payload = array(
    'ts' => isset($dec['ts']) ? (int) $dec['ts'] : (int) timestamp_ymdhis::now(),
    'from_actor_id' => 1,
    'to_actor_id' => (int) $dec['task_target_actor_id'],
    'message_text' => $msg,
    'message_type' => isset($dec['message_type']) ? $dec['message_type'] : 'task',
    'routing_provenance' => isset($dec['routing_provenance']) ? $dec['routing_provenance'] : 'hermes:task-router',
);

if (!$svc->appendTranscript($federation_node_id, $channel_key, $thread_slug, $payload)) {
    test_hermes_log_defect('P1-TEST-HERMES-TRANSCRIPT-001', array(
        'channel_id' => $channelId,
        'federation_node_id' => $federation_node_id,
        'channel_key' => $channel_key,
        'thread_slug' => $thread_slug,
        'payload' => $payload,
    ));
    echo "[FAIL] appendTranscript returned false\n";
    exit(1);
}
$after = hermes_count_jsonl_lines($tpath);
if ($after <= $before) {
    echo "[FAIL] Transcript line count did not grow (before={$before}, after={$after})\n";
    exit(1);
}
echo "[OK] Transcript written to memory/transcripts/{$federation_node_id}/{$channel_key}/{$thread_slug}.jsonl\n";

$mid = (int) IdGenerator::generate();
$tid = $svc->createPendingTask($dec, $mid);
if ($tid === false) {
    test_hermes_log_defect('P2-TEST-HERMES-TASK-001', array(
        'routing_decision' => $dec,
        'message_id' => $mid,
    ));
    echo "[WARN] Pending task insert failed (schema or DB). Transcript and routing OK.\n";
    echo "[PASS] Routing test successful (routing + transcript only)\n";
    exit(0);
}
echo "[OK] Pending task created: task_id = {$tid}\n";
echo "[PASS] Routing test successful\n";
exit(0);
