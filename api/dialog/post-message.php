<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "api/dialog/post-message.php"
#   web_path: "https://www.lupopedia.com/lupopedia/api/dialog/post-message.php"
#   status: "complete"
#   when_updated: "20260418162214"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/18_channel_chat_display.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/dialog-post-message"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 18
#   content_slug: "dialog-post-message"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Dialog POST: create channel message (AJAX feed)"
#   summary: "POST JSON thread_id and message_text; session actor; optional mood_vector for ROSE or CARMEN; createDialogMessage; optional RuntimeActorLoopService; returns line or error JSON. P2 defect codes on validation, auth, thread, membership, insert, runtime."
# ---------------------------------------------------------------------
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}
require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', LupopediaConfigResolver::publicPathFromRequest(LUPOPEDIA_PATH));
}

require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/classes/DialogMvpService.php';
require_once LUPO_INCLUDES_DIR . '/classes/LlmRuntimeService.php';
require_once LUPO_INCLUDES_DIR . '/classes/EscalationTaskService.php';
require_once LUPO_INCLUDES_DIR . '/classes/RuntimeActorLoopService.php';

/**
 * Pillar 2 defect breadcrumb via DialogMvpService::logDefect; never throws to the caller.
 *
 * @param string $pattern_id
 * @param array  $context
 */
function post_message_log_defect($pattern_id, $context = array())
{
    try {
        if (!is_array($context)) {
            $context = array('value' => (string) $context);
        }
        $context['emitter'] = 'api/dialog/post-message.php';
        if (!class_exists('DialogMvpService', false) || !is_callable(array('DialogMvpService', 'logDefect'))) {
            return;
        }
        DialogMvpService::logDefect((string) $pattern_id, $context);
    } catch (Exception $e) {
        return;
    }
}

if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Method not allowed. Use POST.'), 405);
}

$db    = DialogMvpService::getDb();
$input = DialogMvpService::parseInput();

// Strip noise fields that must never reach the DB insert arrays
// is_ajax, csrf_token, submit_button, channel are UI-layer only
$message_text = isset($input['message_text']) ? trim((string) $input['message_text']) : '';
$thread_id    = isset($input['thread_id'])    ? (int) $input['thread_id']             : 0;
$message_type = isset($input['message_type']) ? trim((string) $input['message_type']) : 'text';
$to_actor_id  = isset($input['to_actor_id'])  ? (int) $input['to_actor_id']           : null;
if ($to_actor_id !== null && $to_actor_id <= 0) {
    $to_actor_id = null;
}

// docs/doctrine/COUNTING_IN_LIGHT_DOCTRINE.md: default neutral 666666 on this path.
// Full six-hex axis is accepted only from ROSE (actor_id 3) or CARMEN (actor_id 706); others remain neutral.
// ROSE annotation uses metadata_json (DialogMvpService::roseAnnotatePendingMessages), not mood_vector as a synthetic marker.
$mood_vector = '666666';

if ($message_text === '' || $thread_id <= 0) {
    post_message_log_defect('P2-POSTMSG-VALIDATION-001', array(
        'thread_id'      => $thread_id,
        'message_length' => strlen($message_text),
        'message_type'    => $message_type,
    ));
    DialogMvpService::jsonResponse(array(
        'ok'    => false,
        'error' => 'thread_id and message_text are required.'
    ), 400);
}

// Resolve actor from session — getCurrentActorId covers lupo_auth_service,
// current_user(), and lupo_session->validateSession() paths.
$from_actor_id = DialogMvpService::getCurrentActorId($db);
if (!$from_actor_id || $from_actor_id <= 0) {
    post_message_log_defect('P2-AUTH-FAIL-002', array(
        'from_actor_id' => (int) $from_actor_id,
        'auth_path'     => 'getCurrentActor_id_empty_or_invalid',
    ));
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Authenticated actor is required.'), 401);
}

// ROSE (3) or CARMEN (706) may submit mood_vector; validate six-hex if provided
if (((int) $from_actor_id === DialogMvpService::ROSE_ACTOR_ID
    || (int) $from_actor_id === DialogMvpService::CARMEN_ACTOR_ID) && isset($input['mood_vector'])) {
    $candidate = strtoupper(trim((string) $input['mood_vector']));
    if (preg_match('/^[0-9A-F]{6}$/', $candidate)) {
        $mood_vector = $candidate;
    }
}

// Auth check: isAuthenticatedHumanActor requires a full auth_user<->actor mapping
// (lupo_actor_auth_users). For session-based actors (e.g. WOLFIE via lupo_session),
// that mapping may not exist. Fall back to verifying the actor is live in the DB.
$auth_ok = DialogMvpService::isAuthenticatedHumanActor($db, $from_actor_id);
if (!$auth_ok) {
    $auth_ok = DialogMvpService::ensureActorExists($db, $from_actor_id);
}
if (!$auth_ok) {
    post_message_log_defect('P2-AUTH-FAIL-002', array(
        'from_actor_id' => (int) $from_actor_id,
        'auth_path'     => 'isAuthenticatedHumanActor_and_ensureActorExists_failed',
    ));
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Authenticated actor mapping required.'), 403);
}

$prefix = DialogMvpService::getTablePrefix();
$thread = DialogMvpService::fetchThread($db, $thread_id);
if (!$thread) {
    post_message_log_defect('P2-POSTMSG-THREAD-001', array('thread_id' => $thread_id));
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Thread not found.'), 404);
}

if (!DialogMvpService::actorHasChannelAccess($db, $from_actor_id, (int) $thread['channel_id'])) {
    // Auto-join: add actor to channel so subsequent requests pass.
    // This mirrors channels/index.php ensureChannelMembership on page load.
    try {
        DialogMvpService::ensureChannelMembership($db, $from_actor_id, (int) $thread['channel_id'], $from_actor_id, '');
    } catch (Exception $join_e) {
        post_message_log_defect('P1-POSTMSG-MEMBERSHIP-001', array(
            'actor_id'   => (int) $from_actor_id,
            'channel_id' => (int) $thread['channel_id'],
            'exception'  => $join_e->getMessage(),
        ));
    }
}

error_log('[post-message.php] actor=' . $from_actor_id . ' thread=' . $thread_id . ' msg=' . substr($message_text, 0, 60));

try {
    $created = DialogMvpService::createDialogMessage(
        $db,
        $thread_id,
        $from_actor_id,
        $message_text,
        $message_type,
        $to_actor_id,
        $mood_vector,
        null
    );
} catch (Exception $e) {
    post_message_log_defect('P2-DIALOG-INSERT-FAIL-001', array(
        'thread_id'      => $thread_id,
        'from_actor_id'  => (int) $from_actor_id,
        'exception'      => $e->getMessage(),
    ));
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Message insert failed.'), 500);
}

if (!is_array($created) || !isset($created['message_id']) || (int) $created['message_id'] <= 0) {
    post_message_log_defect('P2-DIALOG-INSERT-FAIL-001', array(
        'thread_id'      => $thread_id,
        'from_actor_id'  => (int) $from_actor_id,
        'invalid_result' => true,
    ));
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Message insert failed.'), 500);
}

// Reserved for future auto-routing / runtime-feedback phase; not exposed in JSON yet (channels/index.php contract unchanged).
$runtime_result = null;
$auto_route = true;
if (isset($input['auto_route']) && (string) $input['auto_route'] === '0') {
    $auto_route = false;
}

if ($auto_route) {
    $llm = new LlmRuntimeService(DialogMvpService::getRuntimeActorsConfigPath());
    $escalations = new EscalationTaskService($db, $prefix);
    $runtime = new RuntimeActorLoopService($db, $prefix, $llm, $escalations);
    try {
        $runtime_result = $runtime->processMessage($created['message_id']);
    } catch (Exception $e) {
        post_message_log_defect('P2-POSTMSG-RUNTIME-001', array(
            'message_id' => isset($created['message_id']) ? (int) $created['message_id'] : 0,
            'exception'  => $e->getMessage(),
        ));
        $runtime_result = array('success' => false, 'error' => $e->getMessage());
    }
}

// Build {ok, line, bg, fg} for the live-feed AJAX handler in channels/index.php.
// Fetch actor display name and thread colors for the formatted line string.
$actor_row = $db->fetchRow(
    "SELECT name, actor_name FROM {$prefix}actors
     WHERE actor_id = :aid AND (is_deleted = 0 OR is_deleted IS NULL)
     LIMIT 1",
    array('aid' => $from_actor_id)
);
$actor_label = strtoupper(
    ($actor_row && isset($actor_row['name'])       && $actor_row['name']       !== '')
        ? $actor_row['name']
        : (($actor_row && isset($actor_row['actor_name']) && $actor_row['actor_name'] !== '')
            ? $actor_row['actor_name']
            : 'ACTOR')
);

$thread_colors = $db->fetchRow(
    "SELECT bg_color, text_color FROM {$prefix}dialog_threads
     WHERE dialog_thread_id = :tid AND is_deleted = 0
     LIMIT 1",
    array('tid' => $thread_id)
);

$thoth_id = DialogMvpService::THOTH_ACTOR_ID;
$is_thoth = ((int) $from_actor_id === $thoth_id);
$bg_color = $is_thoth ? '8B0000' : ($thread_colors ? $thread_colors['bg_color']   : 'fefdcd');
$fg_color = $is_thoth ? 'FFD700' : ($thread_colors ? $thread_colors['text_color'] : '426446');

// Format HH:MM:SS from BIGINT YYYYMMDDHHIISS — string offsets only (doctrine §1)
$ts_str  = str_pad((string)(int) $created['created_ymdhis'], 14, '0', STR_PAD_LEFT);
$hms_str = substr($ts_str, 8, 2) . ':' . substr($ts_str, 10, 2) . ':' . substr($ts_str, 12, 2);
$line_str = '[' . $hms_str . '] [' . $actor_label . '] ' . $message_text;

DialogMvpService::maybeRedirectToMessages($thread_id);

DialogMvpService::jsonResponse(array(
    'ok'         => true,
    'line'       => $line_str,
    'bg'         => '#' . $bg_color,
    'fg'         => '#' . $fg_color,
    'message_id' => $created['message_id'],
    'thread_id'  => $thread_id,
), 201);
