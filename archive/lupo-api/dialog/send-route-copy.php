<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-api/dialog/send-route-copy.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-api/dialog/send-route-copy.php"
#   status: "complete"
#   when_updated: "20260417203317"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/staging/2026/04/send-route-copy-api.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/send-route-copy-api"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "02"
#   content_slug: "send-route-copy-api"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "POST copy of dialog message to another channel"
#   summary: "Cross-channel routed copy with optional lupo_routing_events audit row."
# ---------------------------------------------------------------------
define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(LUPOPEDIA_PATH));
}

if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Method not allowed. Use POST.'), 405);
}

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/LupopediaConfigResolver.php';
$lupoResolvedCfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
define('LUPOPEDIA_CONFIG_PATH', $lupoResolvedCfg ?: LUPOPEDIA_PATH . '/lupopedia-config.php');
require_once LUPOPEDIA_CONFIG_PATH;

if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/functions/security.php')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/functions/security.php';
}

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DialogMvpService.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php';

$db = DatabaseFactory::getConnection();
$input = DialogMvpService::parseInput();

$csrf = isset($input['csrf_token']) ? (string) $input['csrf_token'] : '';
if (function_exists('lupo_verify_csrf_token') && !lupo_verify_csrf_token($csrf)) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'CSRF validation failed.'), 403);
}

$dialog_message_id = isset($input['dialog_message_id']) ? (int) $input['dialog_message_id'] : 0;
$source_channel_id = isset($input['source_channel_id']) ? (int) $input['source_channel_id'] : 0;
$destination_channel_key = isset($input['destination_channel_key']) ? trim((string) $input['destination_channel_key']) : '';
$destination_actor_id = isset($input['destination_actor_id']) ? (int) $input['destination_actor_id'] : 0;
$routing_explanation = isset($input['routing_explanation']) ? trim((string) $input['routing_explanation']) : '';

if ($dialog_message_id <= 0 || $source_channel_id <= 0 || $destination_channel_key === '') {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'dialog_message_id, source_channel_id, and destination_channel_key are required.'), 400);
}

$routed_by = DialogMvpService::getCurrentActorId($db);
if ($routed_by <= 0) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Authenticated actor is required.'), 401);
}

$auth_ok = DialogMvpService::isAuthenticatedHumanActor($db, $routed_by);
if (!$auth_ok) {
    $auth_ok = DialogMvpService::ensureActorExists($db, $routed_by);
}
if (!$auth_ok) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Authenticated actor mapping required.'), 403);
}

$prefix = DialogMvpService::getTablePrefix();

$src = DialogMvpService::fetchMessage($db, $dialog_message_id);
if (!$src) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Source message not found.'), 404);
}
if ((int) $src['channel_id'] !== $source_channel_id) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'source_channel_id does not match message channel.'), 400);
}

if (!DialogMvpService::actorHasChannelAccess($db, $routed_by, $source_channel_id)) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Not a member of the source channel.'), 403);
}

$dest_ch = $db->fetchRow(
    "SELECT channel_id, channel_key FROM {$prefix}channels
     WHERE channel_key = :ck AND is_deleted = 0 LIMIT 1",
    array('ck' => $destination_channel_key)
);
if (!$dest_ch || empty($dest_ch['channel_id'])) {
    DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Destination channel not found.'), 404);
}
$dest_channel_id = (int) $dest_ch['channel_id'];
$dest_key = isset($dest_ch['channel_key']) ? (string) $dest_ch['channel_key'] : $destination_channel_key;

DialogMvpService::ensureChannelMembership($db, $routed_by, $dest_channel_id, $routed_by, '');

if ($destination_actor_id > 0) {
    $mem = $db->fetchRow(
        "SELECT 1 FROM {$prefix}actor_channels
         WHERE actor_id = :aid AND channel_id = :cid AND is_deleted = 0 AND status = 'A' LIMIT 1",
        array('aid' => $destination_actor_id, 'cid' => $dest_channel_id)
    );
    if (!$mem) {
        DialogMvpService::jsonResponse(array('ok' => false, 'error' => 'Destination actor is not an active member of that channel.'), 400);
    }
}

$today_thread_key = gmdate('Y-m-d');
$t = $db->fetchRow(
    "SELECT dialog_thread_id FROM {$prefix}dialog_threads
     WHERE thread_key = :tk AND channel_id = :cid AND is_deleted = 0 LIMIT 1",
    array('tk' => $today_thread_key, 'cid' => $dest_channel_id)
);
if ($t && !empty($t['dialog_thread_id'])) {
    $dest_thread_id = (int) $t['dialog_thread_id'];
} else {
    $res = DialogMvpService::createDialogThread($db, $dest_channel_id, $dest_key . ' / ' . $today_thread_key, $routed_by);
    $dest_thread_id = (int) $res['thread_id'];
}

$orig_text = isset($src['message_text']) ? (string) $src['message_text'] : '';
$prefix_txt = '[routed copy: msg ' . (int) $dialog_message_id . ' from channel_id ' . (int) $source_channel_id . ']' . "\n";
if ($routing_explanation !== '') {
    $prefix_txt .= 'Explanation: ' . $routing_explanation . "\n";
}
$prefix_txt .= "---\n";
$new_text = $prefix_txt . $orig_text;
if (strlen($new_text) > 65000) {
    $new_text = substr($new_text, 0, 65000);
}

$to_actor = ($destination_actor_id > 0) ? $destination_actor_id : null;
$meta = array(
    'routing_provenance' => 'ui:send-route-copy',
    'source_dialog_message_id' => (int) $dialog_message_id,
    'source_channel_id' => (int) $source_channel_id,
);
$meta_json = json_encode($meta);

$created = DialogMvpService::createDialogMessage(
    $db,
    $dest_thread_id,
    $routed_by,
    $new_text,
    'stdout',
    $to_actor,
    '666666',
    $meta_json
);

$t_route = $prefix . 'routing_events';
if (DialogMvpService::tableExists($db, $t_route)) {
    try {
        $source_from = isset($src['from_actor_id']) ? (int) $src['from_actor_id'] : 0;
        $routing_id = IdGenerator::generate();
        $now = DialogMvpService::nowYmdHis();
        $db->insert($t_route, array(
            'routing_id' => $routing_id,
            'source_message_id' => (int) $dialog_message_id,
            'source_channel_id' => (int) $source_channel_id,
            'source_actor_id' => $source_from,
            'destination_channel_id' => $dest_channel_id,
            'destination_actor_id' => ($destination_actor_id > 0) ? $destination_actor_id : 0,
            'routing_explanation' => ($routing_explanation !== '') ? $routing_explanation : null,
            'routed_by_actor_id' => $routed_by,
            'created_ymdhis' => $now,
        ));
    } catch (Exception $e) {
        error_log('[send-route-copy] routing_events insert skipped: ' . $e->getMessage());
    }
}

DialogMvpService::jsonResponse(array(
    'ok' => true,
    'new_message_id' => isset($created['message_id']) ? (int) $created['message_id'] : 0,
    'destination_channel_id' => $dest_channel_id,
    'destination_thread_id' => $dest_thread_id,
), 200);
