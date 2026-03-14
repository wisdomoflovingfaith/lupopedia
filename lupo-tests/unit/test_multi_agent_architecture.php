<?php
/**
 * Test for ChannelService and TaskService
 */
require_once dirname(__FILE__) . '/../lupo-includes/bootstrap.php';
require_once dirname(__FILE__) . '/../lupo-includes/classes/ChannelService.php';
require_once dirname(__FILE__) . '/../lupo-includes/classes/TaskService.php';

function test_multi_agent_architecture()
{
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $channelService = new ChannelService($db, $prefix);
    $taskService = new TaskService($db, $prefix);

    $channelId = 42;
    $taskKey = 'test_task_' . time();
    $actorId = 2038; // Lilith

    echo "Testing Task Creation...\n";
    $taskId = $taskService->createTask($channelId, $taskKey, $actorId, "Architecture Test", "Verifying the lupo-channels structure.");
    assert($taskId > 0, "Task ID should be positive");

    echo "Testing Path Verification...\n";
    $pendingPath = $taskService->getTaskPath($channelId, 'pending', $taskKey);
    assert(is_file($pendingPath), "Pending task file should exist at $pendingPath");

    echo "Testing Message Posting...\n";
    $posted = $channelService->postMessage($channelId, 'system_test', $actorId, "Automated architecture test message.");
    assert($posted === true, "Message should be posted");

    echo "Testing Status Promotion...\n";
    $promoted = $taskService->updateStatus($channelId, $taskKey, 'active');
    assert($promoted === true, "Task should be promoted to active");
    assert(!is_file($pendingPath), "Old pending file should be removed");
    $activePath = $taskService->getTaskPath($channelId, 'active', $taskKey);
    assert(is_file($activePath), "Active task file should exist at $activePath");

    echo "✓ All multi-agent architecture tests passed!\n";
}

try {
    test_multi_agent_architecture();
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
