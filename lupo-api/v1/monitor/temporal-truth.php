<?php

/**
 * API Endpoint: Temporal Truth Monitor
 * 
 * Monitors temporal alignment and provides RS-UTC-2026 recommendations.
 * 
 * GET /api/v1/monitor/temporal-truth - Get current temporal status
 * POST /api/v1/monitor/temporal-truth/auto-sync - Auto-cast if needed
 * 
 * @package Lupopedia\API
 * @version 2026.01.18
 * @monitor RS-UTC-2026
 */

// Include required files
require_once __DIR__ . '/../../../lupo-includes/bootstrap.php';
require_once __DIR__ . '/../../../lupo-includes/classes/TemporalTruthMonitor.php';

// Set response headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Initialize the monitor
$monitor = new TemporalTruthMonitor($db);

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

// Determine endpoint
$endpoint = end($path_parts);

if ($method === 'GET' && $endpoint === 'temporal-truth') {
    // Get current temporal alignment status
    $alignment = $monitor->checkTemporalAlignment();
    $statistics = $monitor->getSyncStatistics();
    $dashboard = $monitor->getTemporalHealthDashboard();
    
    echo json_encode([
        'temporal_alignment' => $alignment,
        'sync_statistics' => $statistics,
        'health_dashboard' => $dashboard,
        'api_info' => [
            'endpoint' => '/api/v1/monitor/temporal-truth',
            'method' => 'GET',
            'description' => 'Current temporal alignment status and recommendations',
            'timestamp' => date('c')
        ]
    ], JSON_PRETTY_PRINT);
    
} elseif ($method === 'POST' && $endpoint === 'auto-sync') {
    // Auto-cast sync if needed
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $force_emergency = isset($input['force_emergency']) && $input['force_emergency'];
    
    $result = $monitor->autoSyncIfNeeded($force_emergency);
    
    echo json_encode([
        'auto_sync_result' => $result,
        'api_info' => [
            'endpoint' => '/api/v1/monitor/temporal-truth/auto-sync',
            'method' => 'POST',
            'description' => 'Automatically cast RS-UTC-2026 if temporal drift detected',
            'timestamp' => date('c')
        ]
    ], JSON_PRETTY_PRINT);
    
} elseif ($method === 'GET' && $endpoint === 'health') {
    // Get temporal health dashboard
    $dashboard = $monitor->getTemporalHealthDashboard();
    
    echo json_encode([
        'temporal_health_dashboard' => $dashboard,
        'api_info' => [
            'endpoint' => '/api/v1/monitor/temporal-truth/health',
            'method' => 'GET',
            'description' => 'Complete temporal health dashboard',
            'timestamp' => date('c')
        ]
    ], JSON_PRETTY_PRINT);
    
} elseif ($method === 'GET' && $endpoint === 'statistics') {
    // Get sync statistics only
    $statistics = $monitor->getSyncStatistics();
    
    echo json_encode([
        'sync_statistics' => $statistics,
        'api_info' => [
            'endpoint' => '/api/v1/monitor/temporal-truth/statistics',
            'method' => 'GET',
            'description' => 'RS-UTC-2026 sync statistics',
            'timestamp' => date('c')
        ]
    ], JSON_PRETTY_PRINT);
    
} elseif ($method === 'POST' && $endpoint === 'check-alignment') {
    // Check alignment and return recommendation
    $alignment = $monitor->checkTemporalAlignment();
    
    echo json_encode([
        'alignment_check' => $alignment,
        'recommendation' => [
            'should_sync' => $alignment['recommendation'] !== 'no_action_needed',
            'suggested_spell' => $alignment['recommendation'],
            'urgency' => $alignment['drift_status'],
            'confidence' => $alignment['sync_confidence']
        ],
        'api_info' => [
            'endpoint' => '/api/v1/monitor/temporal-truth/check-alignment',
            'method' => 'POST',
            'description' => 'Check temporal alignment and get sync recommendation',
            'timestamp' => date('c')
        ]
    ], JSON_PRETTY_PRINT);
    
} else {
    // Method or endpoint not allowed
    http_response_code(404);
    echo json_encode([
        'error' => 'Endpoint not found',
        'message' => 'The requested temporal monitoring endpoint does not exist',
        'available_endpoints' => [
            'GET /api/v1/monitor/temporal-truth',
            'GET /api/v1/monitor/temporal-truth/health',
            'GET /api/v1/monitor/temporal-truth/statistics',
            'POST /api/v1/monitor/temporal-truth/auto-sync',
            'POST /api/v1/monitor/temporal-truth/check-alignment'
        ]
    ]);
}
?>
