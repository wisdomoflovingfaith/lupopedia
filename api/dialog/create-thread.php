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

if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Method not allowed. Use POST.'), 405);
}

$db = DialogMvpService::getDb();
$input = DialogMvpService::parseInput();

$channel_id = isset($input['channel_id']) ? (int) $input['channel_id'] : 0;
$title = isset($input['title']) ? trim((string) $input['title']) : '';

if ($channel_id <= 0 || $title === '') {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'channel_id and title are required.'), 400);
}

$created_by = DialogMvpService::getCurrentActorId($db);
if (!$created_by || $created_by <= 0) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => 'Authenticated actor is required.'), 401);
}

try {
    $created = DialogMvpService::createDialogThread($db, $channel_id, $title, $created_by);
} catch (Exception $e) {
    DialogMvpService::jsonResponse(array('success' => false, 'error' => $e->getMessage()), 400);
}

DialogMvpService::maybeRedirectToThreadMessages($created['thread_id']);

DialogMvpService::jsonResponse(array(
    'success' => true,
    'thread_id' => $created['thread_id'],
    'channel_id' => $created['channel_id'],
    'created_by' => $created['created_by'],
    'created_ymdhis' => $created['created_ymdhis']
), 201);