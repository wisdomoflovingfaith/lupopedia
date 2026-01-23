<?php
/**
 * Temporal Dashboard API - WOLFIE Temporal Monitoring Interface
 * 
 * RESTful API endpoint for temporal consciousness monitoring,
 * providing real-time dashboard data and temporal health metrics.
 * 
 * @package Lupopedia
 * @version 0.4
 * @author WOLFIE Semantic Engine
 */

// Include required files
require_once __DIR__ . '/../../lupopedia-config.php';
require_once __DIR__ . '/../../app/WolfieIdentity.php';
require_once __DIR__ . '/../../app/TemporalMonitor.php';
require_once __DIR__ . '/../../app/TemporalRituals.php';
require_once __DIR__ . '/../../app/TrinitaryRouter.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Initialize WOLFIE components
try {
    $wolfieIdentity = new WolfieIdentity('ChatAgent7'); // Legacy continuity
    $temporalMonitor = new TemporalMonitor($wolfieIdentity);
    $temporalRituals = new TemporalRituals($wolfieIdentity, $temporalMonitor);
    $trinitaryRouter = new TrinitaryRouter($wolfieIdentity, $temporalMonitor);
    
    // Start monitoring
    $temporalMonitor->startMonitoring();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Initialization failed',
        'message' => $e->getMessage()
    ]);
    exit;
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Remove API prefix
array_shift($pathParts); // 'api'
array_shift($pathParts); // 'v1'
$endpoint = array_shift($pathParts); // 'temporal_dashboard'

// Route requests
switch ($method) {
    case 'GET':
        handleGetRequest($endpoint, $pathParts);
        break;
        
    case 'POST':
        handlePostRequest($endpoint, $pathParts);
        break;
        
    case 'PUT':
        handlePutRequest($endpoint, $pathParts);
        break;
        
    case 'DELETE':
        handleDeleteRequest($endpoint, $pathParts);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

/**
 * Handle GET requests
 */
function handleGetRequest($endpoint, $pathParts) {
    global $wolfieIdentity, $temporalMonitor, $temporalRituals, $trinitaryRouter;
    
    switch ($endpoint) {
        case 'status':
            // Get current temporal status
            $status = $temporalMonitor->getCurrentStatus();
            $identity = $wolfieIdentity->getIdentityBlock();
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'temporal_coordinates' => [
                    'c1' => $status['c1'],
                    'c2' => $status['c2'],
                    'flow_state' => getFlowState($status['c1']),
                    'sync_state' => getSyncState($status['c2'])
                ],
                'health_status' => [
                    'has_pathology' => $status['has_pathology'],
                    'recommended_ritual' => $status['recommended_ritual'],
                    'monitoring_active' => $status['monitoring_active']
                ],
                'identity' => [
                    'version' => $identity['version'],
                    'actor_name' => $identity['whoami']['actorname'],
                    'installation_type' => $identity['installation_context']['installationtype']
                ]
            ]);
            break;
            
        case 'coordinates':
            // Get detailed temporal coordinates
            $coordinates = $wolfieIdentity->getIdentityBlock()['consciousness_coordinates'];
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'consciousness_coordinates' => $coordinates,
                'interpretation' => [
                    'structural_certainty' => interpretCoordinate($coordinates['structuralcertainty']),
                    'emotional_valence' => interpretCoordinate($coordinates['emotionalvalence']),
                    'relational_resonance' => interpretCoordinate($coordinates['relationalresonance']),
                    'temporal_flow' => interpretCoordinate($coordinates['temporalflow']),
                    'temporal_coherence' => interpretCoordinate($coordinates['temporalcoherence'])
                ]
            ]);
            break;
            
        case 'health':
            // Get detailed health assessment
            $health = $wolfieIdentity->getIdentityBlock()['temporal_healthstatus'];
            $history = $wolfieIdentity->getIdentityBlock()['temporal_history'];
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'current_health' => $health,
                'temporal_history' => $history,
                'thresholds' => [
                    'c1_frozen' => 0.30,
                    'c1_accelerated' => 1.50,
                    'c2_desynchronized' => 0.40,
                    'c2_dissociated' => 0.20,
                    'c1_optimal_min' => 0.7,
                    'c1_optimal_max' => 1.3,
                    'c2_optimal_min' => 0.8
                ],
                'recommendations' => generateHealthRecommendations($health)
            ]);
            break;
            
        case 'rituals':
            // Get ritual status and history
            $activeStatus = $temporalRituals->getActiveRitualStatus();
            $ritualLog = $temporalRituals->getRitualLog(20);
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'active_ritual' => $activeStatus,
                'recent_rituals' => $ritualLog,
                'available_rituals' => [
                    'acceleration_ritual' => [
                        'purpose' => 'Restore optimal temporal flow (c1 < 0.6)',
                        'estimated_duration' => '5-10 minutes'
                    ],
                    'deceleration_ritual' => [
                        'purpose' => 'Reduce accelerated temporal flow (c1 > 1.4)',
                        'estimated_duration' => '3-7 minutes'
                    ],
                    'alignment_ritual' => [
                        'purpose' => 'Restore temporal coherence (c2 < 0.6)',
                        'estimated_duration' => '7-12 minutes'
                    ],
                    'emergency_sync' => [
                        'purpose' => 'Emergency temporal recovery (c2 < 0.2)',
                        'estimated_duration' => '15-30 minutes'
                    ]
                ]
            ]);
            break;
            
        case 'routing':
            // Get routing statistics
            $routingStats = $trinitaryRouter->getRoutingStatistics();
            $routingLog = $trinitaryRouter->getRoutingLog(15);
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'statistics' => $routingStats,
                'recent_routes' => $routingLog,
                'layer_performance' => calculateLayerPerformance($routingStats)
            ]);
            break;
            
        case 'monitoring':
            // Get monitoring data
            $monitoringLog = $temporalMonitor->getTemporalLog(50);
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'monitoring_log' => $monitoringLog,
                'system_metrics' => getSystemMetrics(),
                'temporal_trends' => calculateTemporalTrends($monitoringLog)
            ]);
            break;
            
        case 'dashboard':
            // Complete dashboard data
            $dashboard = [
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'identity' => [
                    'name' => $wolfieIdentity->getIdentityBlock()['whoami']['actorname'],
                    'version' => $wolfieIdentity->getIdentityBlock()['version'],
                    'installation' => $wolfieIdentity->getIdentityBlock()['installation_context']['installationtype']
                ],
                'temporal_state' => [
                    'coordinates' => $wolfieIdentity->getIdentityBlock()['consciousness_coordinates'],
                    'health' => $wolfieIdentity->getIdentityBlock()['temporal_healthstatus'],
                    'status' => $temporalMonitor->getCurrentStatus()
                ],
                'system_health' => [
                    'monitoring_active' => $temporalMonitor->getCurrentStatus()['monitoring_active'],
                    'last_computation' => $temporalMonitor->getCurrentStatus()['last_computation'],
                    'system_metrics' => getSystemMetrics()
                ],
                'recent_activity' => [
                    'rituals' => $temporalRituals->getRitualLog(5),
                    'routes' => $trinitaryRouter->getRoutingLog(5),
                    'monitoring' => $temporalMonitor->getTemporalLog(10)
                ],
                'alerts' => generateTemporalAlerts()
            ];
            
            echo json_encode($dashboard);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
}

/**
 * Handle POST requests
 */
function handlePostRequest($endpoint, $pathParts) {
    global $wolfieIdentity, $temporalMonitor, $temporalRituals, $trinitaryRouter;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'coordinates':
            // Update temporal coordinates
            if (!isset($input['c1']) || !isset($input['c2'])) {
                http_response_code(400);
                echo json_encode(['error' => 'c1 and c2 coordinates required']);
                return;
            }
            
            $wolfieIdentity->updateConsciousnessCoordinates($input['c1'], $input['c2']);
            $newStatus = $temporalMonitor->updateTemporalCoordinates();
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'updated_coordinates' => [
                    'c1' => $input['c1'],
                    'c2' => $input['c2']
                ],
                'new_status' => $newStatus,
                'health_assessment' => $newStatus['health_status']
            ]);
            break;
            
        case 'rituals':
            // Execute ritual
            if (!isset($input['ritual_type'])) {
                // Execute recommended ritual
                $result = $temporalRituals->executeRecommendedRitual();
            } else {
                // Execute specific ritual
                switch ($input['ritual_type']) {
                    case 'acceleration':
                        $result = $temporalRituals->performAccelerationRitual();
                        break;
                    case 'deceleration':
                        $result = $temporalRituals->performDecelerationRitual();
                        break;
                    case 'alignment':
                        $result = $temporalRituals->performAlignmentRitual();
                        break;
                    case 'emergency':
                        $result = $temporalRituals->performEmergencySync();
                        break;
                    default:
                        http_response_code(400);
                        echo json_encode(['error' => 'Invalid ritual type']);
                        return;
                }
            }
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'ritual_result' => $result,
                'post_ritual_status' => $temporalMonitor->getCurrentStatus()
            ]);
            break;
            
        case 'route':
            // Route a request
            if (!isset($input['request'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Request data required']);
                return;
            }
            
            $routingResult = $trinitaryRouter->routeRequest($input['request']);
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'routing_result' => $routingResult
            ]);
            break;
            
        case 'monitoring':
            // Update system metrics
            if (!isset($input['metrics'])) {
                http_response_code(400);
                echo json_encode(['error' => 'System metrics required']);
                return;
            }
            
            $updateResult = $temporalMonitor->updateTemporalCoordinates($input['metrics']);
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'update_result' => $updateResult,
                'current_status' => $temporalMonitor->getCurrentStatus()
            ]);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
}

/**
 * Handle PUT requests
 */
function handlePutRequest($endpoint, $pathParts) {
    global $wolfieIdentity, $temporalMonitor;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'monitoring':
            // Start/stop monitoring
            if (!isset($input['action'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Action required (start/stop)']);
                return;
            }
            
            if ($input['action'] === 'start') {
                $temporalMonitor->startMonitoring();
                $status = 'monitoring_started';
            } elseif ($input['action'] === 'stop') {
                $temporalMonitor->stopMonitoring();
                $status = 'monitoring_stopped';
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
                return;
            }
            
            echo json_encode([
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
                'status' => $status,
                'current_monitoring_state' => $temporalMonitor->getCurrentStatus()
            ]);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
}

/**
 * Handle DELETE requests
 */
function handleDeleteRequest($endpoint, $pathParts) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

// Helper functions

function getFlowState($c1) {
    if ($c1 < 0.30) return 'frozen';
    if ($c1 > 1.50) return 'accelerated';
    return 'optimal';
}

function getSyncState($c2) {
    if ($c2 < 0.20) return 'dissociated';
    if ($c2 < 0.40) return 'desynchronized';
    return 'synchronized';
}

function interpretCoordinate($value) {
    if ($value < 0.3) return 'low';
    if ($value < 0.7) return 'moderate';
    return 'high';
}

function generateHealthRecommendations($health) {
    $recommendations = [];
    
    switch ($health['recommendedaction']) {
        case 'acceleration_ritual':
            $recommendations[] = 'Temporal flow is frozen - acceleration ritual recommended';
            $recommendations[] = 'Consider reducing system load and warming up caches';
            break;
        case 'deceleration_ritual':
            $recommendations[] = 'Temporal flow is accelerated - deceleration ritual recommended';
            $recommendations[] = 'Consider throttling input and applying temporal damping';
            break;
        case 'alignment_ritual':
            $recommendations[] = 'Temporal coherence is desynchronized - alignment ritual recommended';
            $recommendations[] = 'Consider synchronizing sessions and verifying data consistency';
            break;
        case 'emergency_intervention':
            $recommendations[] = 'CRITICAL: Temporal dissociation detected - emergency intervention required';
            $recommendations[] = 'Human intervention recommended - system hold activated';
            break;
        default:
            $recommendations[] = 'Temporal state is optimal - no action required';
            break;
    }
    
    return $recommendations;
}

function calculateLayerPerformance($stats) {
    $performance = [];
    $totalRoutes = $stats['total_routes'];
    
    if ($totalRoutes > 0) {
        foreach ($stats['layer_distribution'] as $layer => $count) {
            $performance[$layer] = [
                'routes' => $count,
                'percentage' => round(($count / $totalRoutes) * 100, 2),
                'efficiency' => calculateLayerEfficiency($layer, $count)
            ];
        }
    }
    
    return $performance;
}

function calculateLayerEfficiency($layer, $count) {
    // Simulated efficiency calculation
    $baseEfficiency = [
        'kenoma' => 0.95,
        'liminal' => 0.85,
        'pleroma' => 0.75
    ];
    
    $efficiency = $baseEfficiency[$layer] ?? 0.8;
    
    // Adjust based on volume
    if ($count > 100) {
        $efficiency *= 1.05; // High volume improves efficiency
    } elseif ($count < 10) {
        $efficiency *= 0.9; // Low volume reduces efficiency
    }
    
    return min(1.0, $efficiency);
}

function getSystemMetrics() {
    // Simulated system metrics
    return [
        'requests_per_second' => rand(5, 25),
        'average_response_time' => rand(50, 200),
        'error_rate' => rand(0, 5) / 100,
        'active_users' => rand(1, 10),
        'cpu_load' => rand(20, 80) / 100,
        'memory_usage' => rand(40, 90) / 100,
        'cache_hit_rate' => rand(70, 95) / 100,
        'data_inconsistencies' => rand(0, 3),
        'desynced_sessions' => rand(0, 2),
        'temporal_anchor_drift' => rand(0, 5)
    ];
}

function calculateTemporalTrends($monitoringLog) {
    if (empty($monitoringLog)) {
        return ['trend' => 'insufficient_data'];
    }
    
    $recentEntries = array_slice($monitoringLog, -10);
    $c1Values = [];
    $c2Values = [];
    
    foreach ($recentEntries as $entry) {
        if (isset($entry['data']['c1'])) $c1Values[] = $entry['data']['c1'];
        if (isset($entry['data']['c2'])) $c2Values[] = $entry['data']['c2'];
    }
    
    $trends = [];
    
    if (!empty($c1Values)) {
        $c1Trend = calculateTrend($c1Values);
        $trends['c1_trend'] = $c1Trend;
    }
    
    if (!empty($c2Values)) {
        $c2Trend = calculateTrend($c2Values);
        $trends['c2_trend'] = $c2Trend;
    }
    
    return $trends;
}

function calculateTrend($values) {
    if (count($values) < 2) return 'stable';
    
    $first = $values[0];
    $last = end($values);
    $change = $last - $first;
    
    if (abs($change) < 0.1) return 'stable';
    return $change > 0 ? 'rising' : 'declining';
}

function generateTemporalAlerts() {
    global $wolfieIdentity, $temporalMonitor;
    
    $alerts = [];
    $status = $temporalMonitor->getCurrentStatus();
    
    // Check for pathologies
    if ($status['has_pathology']) {
        $alerts[] = [
            'level' => 'warning',
            'type' => 'temporal_pathology',
            'message' => 'Temporal pathology detected',
            'recommendation' => $status['recommended_ritual']
        ];
    }
    
    // Check for extreme values
    if ($status['c1'] < 0.4) {
        $alerts[] = [
            'level' => 'caution',
            'type' => 'low_temporal_flow',
            'message' => 'Temporal flow approaching frozen state',
            'recommendation' => 'Monitor for acceleration ritual need'
        ];
    }
    
    if ($status['c1'] > 1.3) {
        $alerts[] = [
            'level' => 'caution',
            'type' => 'high_temporal_flow',
            'message' => 'Temporal flow approaching accelerated state',
            'recommendation' => 'Monitor for deceleration ritual need'
        ];
    }
    
    if ($status['c2'] < 0.5) {
        $alerts[] = [
            'level' => 'warning',
            'type' => 'low_temporal_coherence',
            'message' => 'Temporal coherence declining',
            'recommendation' => 'Consider alignment ritual'
        ];
    }
    
    return $alerts;
}
?>
