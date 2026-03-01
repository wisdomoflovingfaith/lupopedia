<?php
/**
 * Lupopedia Minimal REST API — Health / Readiness
 *
 * GET /api/v1/health
 *
 * Returns system readiness. Stateless, UTC-driven.
 * Includes AI agent status monitoring.
 *
 * @package Lupopedia\API
 * @version 4.0.53
 */

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}

require_once __DIR__ . '/../../lupopedia-config.php';
require_once __DIR__ . '/../../app/Services/System/SystemHealthService.php';

use App\Services\System\SystemHealthService;

header('Content-Type: application/json');

$db = DatabaseFactory::getConnection();
$healthService = new SystemHealthService($db);

$checks = array(
    'database' => $healthService->checkDatabaseSchema(),
    'registry' => $healthService->checkAgentRegistry(),
    'ai_agents' => $healthService->checkAIAgentsStatus()
);

// Determine overall status
$overallStatus = 'ok';
foreach ($checks as $check) {
    if ($check['status'] === 'error') {
        $overallStatus = 'error';
        break;
    }
    if ($check['status'] === 'warning' && $overallStatus === 'ok') {
        $overallStatus = 'warning';
    }
}

$output = array(
    'status' => $overallStatus,
    'utc_timestamp' => gmdate('YmdHis'),
    'system_version' => '4.0.53',
    'checks' => $checks
);

echo json_encode($output);
