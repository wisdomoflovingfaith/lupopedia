<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-api/v1/tasks/complete.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-api/v1/tasks/complete.php"
#   status: "complete"
#   when_updated: "20260416154500"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/staging/2026/04/tasks-complete-api.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/tasks-complete-api"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "82"
#   content_slug: "tasks-complete-api"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Dialog Pending Tasks Completion API"
#   summary: "Marks lupo_dialog_pending_tasks rows as completed, failed, or cancelled for HERMES task consumption flow with assignee authorization."
# ---------------------------------------------------------------------
require_once __DIR__ . '/init.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    tasks_api_response(array('error' => 'POST method required'), 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || empty($input)) {
    $input = $_POST;
}

$task_id = isset($input['task_id']) ? (int) $input['task_id'] : 0;
$status = isset($input['status']) ? strtolower(trim((string) $input['status'])) : '';
$result_summary = isset($input['result_summary']) ? trim((string) $input['result_summary']) : '';
$actor_id = (int) tasks_get_current_actor();
if ($actor_id <= 0 && isset($_SERVER['HTTP_X_ACTOR_ID'])) {
    $actor_id = (int) $_SERVER['HTTP_X_ACTOR_ID'];
}

if ($task_id <= 0) {
    tasks_api_response(array('error' => 'task_id required'), 400);
}
if ($status !== 'completed' && $status !== 'failed' && $status !== 'cancelled') {
    tasks_api_response(array('error' => "status must be 'completed', 'failed', or 'cancelled'"), 400);
}
if ($actor_id <= 0) {
    tasks_api_response(array('error' => 'Unauthorized'), 401);
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$table = $prefix . 'dialog_pending_tasks';
$now = (int) timestamp_ymdhis::now();

$exists = $db->fetchRow(
    "SELECT task_id, assignee_actor_id, status
     FROM {$table}
     WHERE task_id = :task_id
     LIMIT 1",
    array('task_id' => $task_id)
);
if (!$exists) {
    tasks_api_response(array('error' => 'task not found'), 404);
}
if ((int) $exists['assignee_actor_id'] !== $actor_id) {
    tasks_api_response(array('error' => 'Task does not belong to this actor'), 403);
}
if ((string) $exists['status'] !== 'pending' && (string) $exists['status'] !== 'in_progress') {
    tasks_api_response(array('error' => 'Task is not in a completable state'), 409);
}

$params = array(
    'status' => $status,
    'updated_ymdhis' => $now,
    'completed_ymdhis' => $now,
    'task_id' => $task_id
);

$db->execute(
    "UPDATE {$table}
     SET status = :status,
         updated_ymdhis = :updated_ymdhis,
         completed_ymdhis = :completed_ymdhis
     WHERE task_id = :task_id",
    $params
);

$response = array(
    'success' => true,
    'task_id' => $task_id,
    'actor_id' => $actor_id,
    'status' => $status,
    'completed_ymdhis' => $now
);
if ($result_summary !== '') {
    // lupo_dialog_pending_tasks schema currently has no result_summary column.
    $response['result_summary_note'] = 'accepted_but_not_persisted';
    $response['result_summary'] = $result_summary;
}

tasks_api_response($response, 200);

