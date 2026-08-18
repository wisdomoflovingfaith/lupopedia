<?php
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

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
if ($method !== 'POST') {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Method not allowed. Use POST.'), 405);
}

$input = DialogMvpService::parseInput();
$message_id = isset($GLOBALS['dialog_route_message_id']) ? (int) $GLOBALS['dialog_route_message_id'] : 0;
if ($message_id <= 0 && isset($input['message_id'])) {
    $message_id = (int) $input['message_id'];
}
if ($message_id <= 0) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'message_id is required.'), 400);
}

$db = DialogMvpService::getDb();
$prefix = DialogMvpService::getTablePrefix();
$message = DialogMvpService::fetchMessage($db, $message_id);
if (!$message) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Message not found.'), 404);
}

$thread_id = isset($message['dialog_thread_id']) ? (int) $message['dialog_thread_id'] : 0;

$llm = new LlmRuntimeService(DialogMvpService::getRuntimeActorsConfigPath());
$escalations = new EscalationTaskService($db, $prefix);
$runtime = new RuntimeActorLoopService($db, $prefix, $llm, $escalations);
$result = $runtime->processMessage($message_id);

DialogMvpService::maybeRedirectToMessages($thread_id);

DialogMvpService::jsonResponse($result, 201);
