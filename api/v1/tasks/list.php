<?php
require_once __DIR__ . '/init.php';

$filters = [
    'channel_id' => $_GET['channel_id'] ?? null,
    'status' => $_GET['status'] ?? null,
    'actor_id' => $_GET['actor_id'] ?? null
];

try {
    $tasks = $taskService->listTasks($filters);
    tasks_api_response(['success' => true, 'tasks' => $tasks]);
} catch (Exception $e) {
    tasks_api_response(['error' => $e->getMessage()], 500);
}
