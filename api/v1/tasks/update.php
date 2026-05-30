<?php
require_once __DIR__ . '/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    tasks_api_response(['error' => 'POST method required'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$taskId = $input['task_id'] ?? null;

if (!$taskId) {
    tasks_api_response(['error' => 'task_id required'], 400);
}

try {
    $success = $taskService->updateTask($taskId, $input);
    tasks_api_response(['success' => $success]);
} catch (Exception $e) {
    tasks_api_response(['error' => $e->getMessage()], 500);
}
