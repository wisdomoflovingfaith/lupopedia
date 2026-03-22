<?php
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(dirname(__DIR__))));
}

require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/classes/DialogMvpService.php';
require_once LUPO_INCLUDES_DIR . '/classes/LlmRuntimeService.php';
require_once LUPO_INCLUDES_DIR . '/classes/EscalationTaskService.php';
require_once LUPO_INCLUDES_DIR . '/classes/RuntimeActorLoopService.php';

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$input = DialogMvpService::parseInput();
$method_override = isset($input['_method']) ? strtoupper(trim((string) $input['_method'])) : '';

if ($method !== 'PATCH' && !($method === 'POST' && $method_override === 'PATCH')) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Method not allowed. Use PATCH.'), 405);
}

$message_id = isset($GLOBALS['dialog_assign_message_id']) ? (int) $GLOBALS['dialog_assign_message_id'] : 0;
if ($message_id <= 0 && isset($input['message_id'])) {
    $message_id = (int) $input['message_id'];
}
$actor_id = isset($input['actor_id']) ? (int) $input['actor_id'] : 0;

if ($message_id <= 0 || $actor_id <= 0) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'message_id and actor_id are required.'), 400);
}

$db = DialogMvpService::getDb();
$prefix = DialogMvpService::getTablePrefix();
$t_messages = $prefix . 'dialog_messages';
$t_threads = $prefix . 'dialog_threads';

$requester_actor_id = DialogMvpService::getCurrentActorId($db);
if (!$requester_actor_id || $requester_actor_id <= 0) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Authenticated actor is required.'), 401);
}

if (!DialogMvpService::isAuthenticatedHumanActor($db, $requester_actor_id)) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Only authenticated humans may assign actors.'), 403);
}

if (!DialogMvpService::ensureActorExists($db, $actor_id)) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Target actor does not exist.'), 404);
}

$message = $db->fetchRow(
    "SELECT dialog_message_id, dialog_thread_id, channel_id FROM {$t_messages} WHERE dialog_message_id = :message_id AND is_deleted = 0 LIMIT 1",
    array('message_id' => $message_id)
);
if (!$message) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Message not found.'), 404);
}

$channel_id = isset($message['channel_id']) ? (int) $message['channel_id'] : 0;
if ($channel_id <= 0) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Message channel is invalid.'), 400);
}

if (!DialogMvpService::actorHasChannelAccess($db, $requester_actor_id, $channel_id)) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Requester is not authorized to assign actors on this channel.'), 403);
}

$now = DialogMvpService::nowYmdHis();
$db->update(
    $t_messages,
    array(
        'to_actor_id' => $actor_id,
        'updated_ymdhis' => $now
    ),
    'dialog_message_id = :message_id',
    array('message_id' => $message_id)
);

$thread_id = isset($message['dialog_thread_id']) ? (int) $message['dialog_thread_id'] : 0;
if ($thread_id > 0) {
    $db->update(
        $t_threads,
        array(
            'assigned_actor_id' => $actor_id,
            'updated_ymdhis' => $now
        ),
        'dialog_thread_id = :thread_id',
        array('thread_id' => $thread_id)
    );
}

$routing_result = null;
try {
    $llm = new LlmRuntimeService(DialogMvpService::getRuntimeActorsConfigPath());
    $escalations = new EscalationTaskService($db, $prefix);
    $runtime = new RuntimeActorLoopService($db, $prefix, $llm, $escalations);
    $routing_result = $runtime->processMessage($message_id);
} catch (Exception $e) {
    $routing_result = array(
        'success' => false,
        'error' => $e->getMessage()
    );
}

DialogMvpService::maybeRedirectToMessages($thread_id);

DialogMvpService::jsonResponse(array(
    'success' => true,
    'message_id' => $message_id,
    'assigned_actor_id' => $actor_id,
    'assigned_by_actor_id' => (int) $requester_actor_id,
    'thread_id' => $thread_id,
    'updated_ymdhis' => $now,
    'routing_result' => $routing_result
), 200);
