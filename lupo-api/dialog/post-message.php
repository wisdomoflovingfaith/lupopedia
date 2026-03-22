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

if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Method not allowed. Use POST.'), 405);
}

$db = DialogMvpService::getDb();
$input = DialogMvpService::parseInput();

$message_text = isset($input['message_text']) ? trim((string) $input['message_text']) : '';
$thread_id = isset($input['thread_id']) ? (int) $input['thread_id'] : 0;
$message_type = isset($input['message_type']) ? trim((string) $input['message_type']) : 'text';
$mood_rgb = isset($input['mood_rgb']) ? strtoupper(trim((string) $input['mood_rgb'])) : '666666';
$to_actor_id = isset($input['to_actor_id']) ? (int) $input['to_actor_id'] : null;

if ($message_text === '' || $thread_id <= 0) {
    DialogMvpService::jsonResponse(array(
        'success' => false,
        'error' => 'thread_id and message_text are required.'
    ), 400);
}

if (!preg_match('/^[0-9A-F]{6}$/', $mood_rgb)) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'mood_rgb must be 6 hex digits.'), 400);
}

$from_actor_id = DialogMvpService::getCurrentActorId($db);
if (!$from_actor_id || $from_actor_id <= 0) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Authenticated actor is required.'), 401);
}

if (!DialogMvpService::isAuthenticatedHumanActor($db, $from_actor_id)) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Authenticated human mapping is required.'), 403);
}

$prefix = DialogMvpService::getTablePrefix();
$thread = DialogMvpService::fetchThread($db, $thread_id);
if (!$thread) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Thread not found.'), 404);
}

if (!DialogMvpService::actorHasChannelAccess($db, $from_actor_id, (int) $thread['channel_id'])) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Actor is not authorized for this thread channel.'), 403);
}

$created = DialogMvpService::createDialogMessage(
    $db,
    $thread_id,
    $from_actor_id,
    $message_text,
    $message_type,
    $to_actor_id,
    $mood_rgb,
    null
);

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
        $runtime_result = array(
            'success' => false,
            'error' => $e->getMessage()
        );
    }
}

DialogMvpService::maybeRedirectToMessages($thread_id);

DialogMvpService::jsonResponse(array(
    'success' => true,
    'message_id' => $created['message_id'],
    'thread_id' => $thread_id,
    'channel_id' => $created['channel_id'],
    'from_actor_id' => $from_actor_id,
    'to_actor_id' => $to_actor_id,
    'created_ymdhis' => $created['created_ymdhis'],
    'runtime_result' => $runtime_result
), 201);
