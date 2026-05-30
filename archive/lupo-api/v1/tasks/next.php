<?php
require_once __DIR__ . '/init.php';

$actorId = $_GET['agent'] ?? $_GET['actor_id'] ?? null;

if (!$actorId) {
    tasks_api_response(['error' => 'agent/actor_id parameter required'], 400);
}

try {
    $task = $taskService->getNextTaskForAgent((int)$actorId);
    tasks_api_response(['success' => true, 'task' => $task]);
} catch (Exception $e) {
    tasks_api_response(['error' => $e->getMessage()], 500);
}
