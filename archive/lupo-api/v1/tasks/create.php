<?php
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    tasks_api_response(['error' => 'POST method required'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$actorId = tasks_get_current_actor();

if (!$actorId && isset($input['owner_actor_id'])) {
    $actorId = (int)$input['owner_actor_id']; // For agents/CLI bypass
}

if (!$actorId) {
    tasks_api_response(['error' => 'Authentication required'], 401);
}

$data = [
    'channel_id' => $input['channel_id'] ?? 0,
    'owner_actor_id' => $actorId,
    'title' => $input['title'] ?? 'New Task',
    'description' => $input['description'] ?? null,
    'status' => $input['status'] ?? 'pending',
    'priority' => $input['priority'] ?? 'normal',
    'type' => $input['type'] ?? 'general',
    'assigned_to_id' => $input['assigned_to_id'] ?? null,
    'visibility' => $input['visibility'] ?? 'active'
];

try {
    $taskId = $taskService->createTask($data);
    tasks_api_response(['success' => true, 'task_id' => $taskId]);
} catch (Exception $e) {
    tasks_api_response(['error' => $e->getMessage()], 500);
}
