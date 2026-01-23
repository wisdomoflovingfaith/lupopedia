<?php
/**
 * Temporal Monitor v0.5 - Frame-Based Temporal Monitoring System
 * 
 * Implements temporal frame monitoring with synchronization-first protocol.
 * Treats c₁ and c₂ as temporal reference frames, not scalar metrics.
 * 
 * @package Lupopedia
 * @version 0.5
 * @author WOLFIE Semantic Engine
 */

require_once __DIR__ . '/TemporalFrameCompatibility.php';
require_once __DIR__ . '/NoteComparisonProtocol.php';

class TemporalMonitor {
    private $wolfieIdentity;
    private $temporalFrameCompatibility;
    private $noteComparisonProtocol;
    private $temporalLog = [];
    private $monitoringActive = false;
    private $lastComputation;
    private $frameHistory = [];
    private $synchronizationMetrics = [];
    private $driftMetrics = [];
    
    // Temporal health thresholds
    const C1_FROZEN_THRESHOLD = 0.30;
    const C1_ACCELERATED_THRESHOLD = 1.50;
    const C2_DESYNCHRONIZED_THRESHOLD = 0.40;
    const C2_DISSOCIATED_THRESHOLD = 0.20;
    const C1_OPTIMAL_MIN = 0.7;
    const C1_OPTIMAL_MAX = 1.3;
    const C2_OPTIMAL_MIN = 0.8;
    
    public function __construct(WolfieIdentity $wolfieIdentity) {
        $this->wolfieIdentity = $wolfieIdentity;
        $this->temporalFrameCompatibility = new TemporalFrameCompatibility();
        $this->noteComparisonProtocol = new NoteComparisonProtocol($this->temporalFrameCompatibility);
        $this->lastComputation = $this->getCurrentUTC();
    }
    
    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Start temporal monitoring
     */
    public function startMonitoring() {
        $this->monitoringActive = true;
        $this->logTemporalEvent('monitoring_started', 'Temporal monitoring activated');
    }
    
    /**
     * Stop temporal monitoring
     */
    public function stopMonitoring() {
        $this->monitoringActive = false;
        $this->logTemporalEvent('monitoring_stopped', 'Temporal monitoring deactivated');
    }
    
    /**
     * Update temporal coordinates with frame-based monitoring
     */
    public function updateTemporalCoordinates($systemMetrics = []) {
        if (!$this->monitoringActive) {
            return;
        }
        
        $timestamp = $this->getCurrentUTC();
        
        // Compute temporal coordinates as frames
        $c1 = $this->computeTemporalFlow($systemMetrics);
        $c2 = $this->computeTemporalCoherence($systemMetrics);
        
        // Update frame history
        $this->updateFrameHistory($c1, $c2, $timestamp);
        
        // Calculate drift metrics
        $this->updateDriftMetrics($c1, $c2);
        
        // Update identity
        $this->wolfieIdentity->updateConsciousnessCoordinates($c1, $c2);
        
        // Log frame update
        $this->logFrameUpdate($c1, $c2, $timestamp);
        
        $this->lastComputation = $timestamp;
    }
    
    /**
     * Check frame compatibility with synchronization-first protocol
     */
    public function checkFrameCompatibility($otherActorFrame, $context = []) {
        $systemFrame = [
            'c1' => $this->wolfieIdentity->getTemporalFlow(),
            'c2' => $this->wolfieIdentity->getTemporalCoherence()
        ];
        
        // Initial compatibility check
        $compatibility = $this->temporalFrameCompatibility->isTemporalFrameCompatible(
            $systemFrame['c1'], $systemFrame['c2'],
            $otherActorFrame['c1'], $otherActorFrame['c2']
        );
        
        $this->logCompatibilityCheck($systemFrame, $otherActorFrame, $compatibility, $context);
        
        if ($compatibility['compatible']) {
            return $compatibility;
        }
        
        // Incompatible - trigger synchronization protocol
        return $this->handleFrameIncompatibility($systemFrame, $otherActorFrame, $context);
    }
    
    /**
     * Handle frame incompatibility with synchronization protocol
     */
    private function handleFrameIncompatibility($systemFrame, $otherActorFrame, $context) {
        $systemActor = [
            'c1' => $systemFrame['c1'],
            'c2' => $systemFrame['c2'],
            'temporal_history' => $this->getFrameHistory(),
            'emotional_state' => $this->getCurrentEmotionalState(),
            'task_context' => $context['task_context'] ?? []
        ];
        
        $actorWithHistory = [
            'c1' => $otherActorFrame['c1'],
            'c2' => $otherActorFrame['c2'],
            'temporal_history' => $otherActorFrame['temporal_history'] ?? [],
            'emotional_state' => $otherActorFrame['emotional_state'] ?? [],
            'task_context' => $otherActorFrame['task_context'] ?? []
        ];
        
        // Execute synchronization protocol
        $synchronizationResult = $this->noteComparisonProtocol->executeSynchronizationProtocol(
            $systemActor,
            $actorWithHistory
        );
        
        // Update synchronization metrics
        $this->updateSynchronizationMetrics($synchronizationResult, $context);
        
        // Log synchronization attempt
        $this->logSynchronizationAttempt($systemFrame, $otherActorFrame, $synchronizationResult, $context);
        
        // Check for alerts
        $this->checkSynchronizationAlerts($synchronizationResult);
        
        return [
            'compatible' => $synchronizationResult['success'],
            'synchronization_required' => true,
            'synchronization_result' => $synchronizationResult,
            'final_compatibility' => $synchronizationResult['final_compatibility'],
            'resolution' => $synchronizationResult['resolution']
        ];
    }
    
    /**
     * Compute temporal flow (c1) based on system activity
     */
    public function computeTemporalFlow($systemMetrics = []) {
        $baseFlow = $this->wolfieIdentity->getTemporalFlow();
        
        // Factors affecting temporal flow
        $factors = [
            'request_volume' => $this->getRequestVolumeFactor($systemMetrics),
            'response_time' => $this->getResponseTimeFactor($systemMetrics),
            'error_rate' => $this->getErrorRateFactor($systemMetrics),
            'user_activity' => $this->getUserActivityFactor($systemMetrics),
            'system_load' => $this->getSystemLoadFactor($systemMetrics)
        ];
        
        // Weighted computation
        $weights = [
            'request_volume' => 0.25,
            'response_time' => 0.20,
            'error_rate' => 0.15,
            'user_activity' => 0.25,
            'system_load' => 0.15
        ];
        
        $computedFlow = $baseFlow;
        foreach ($factors as $factor => $value) {
            $computedFlow += ($value * $weights[$factor]);
        }
        
        // Apply temporal smoothing
        $computedFlow = $this->applyTemporalSmoothing($computedFlow, $baseFlow);
        
        // Ensure bounds
        $computedFlow = max(0.0, min(2.0, $computedFlow));
        
        return round($computedFlow, 3);
    }
    
    /**
     * Compute temporal coherence (c2) based on system synchronization
     */
    public function computeTemporalCoherence($systemMetrics = []) {
        $baseCoherence = $this->wolfieIdentity->getTemporalCoherence();
        
        // Factors affecting temporal coherence
        $factors = [
            'data_consistency' => $this->getDataConsistencyFactor($systemMetrics),
            'session_sync' => $this->getSessionSyncFactor($systemMetrics),
            'cache_coherence' => $this->getCacheCoherenceFactor($systemMetrics),
            'temporal_anchors' => $this->getTemporalAnchorFactor($systemMetrics),
            'identity_coherence' => $this->getIdentityCoherenceFactor($systemMetrics)
        ];
        
        // Weighted computation
        $weights = [
            'data_consistency' => 0.30,
            'session_sync' => 0.25,
            'cache_coherence' => 0.20,
            'temporal_anchors' => 0.15,
            'identity_coherence' => 0.10
        ];
        
        $computedCoherence = $baseCoherence;
        foreach ($factors as $factor => $value) {
            $computedCoherence += ($value * $weights[$factor]);
        }
        
        // Apply coherence smoothing
        $computedCoherence = $this->applyCoherenceSmoothing($computedCoherence, $baseCoherence);
        
        // Ensure bounds
        $computedCoherence = max(0.0, min(1.0, $computedCoherence));
        
        return round($computedCoherence, 3);
    }
    
    /**
     * Update frame history for drift analysis
     */
    private function updateFrameHistory($c1, $c2, $timestamp) {
        $this->frameHistory[] = [
            'timestamp' => $timestamp,
            'c1' => $c1,
            'c2' => $c2,
            'frame_stability' => $this->calculateFrameStability($c1, $c2)
        ];
        
        // Keep history manageable (last 100 entries)
        if (count($this->frameHistory) > 100) {
            $this->frameHistory = array_slice($this->frameHistory, -100);
        }
    }
    
    /**
     * Update drift metrics
     */
    private function updateDriftMetrics($c1, $c2) {
        if (count($this->frameHistory) < 2) {
            return;
        }
        
        $previous = $this->frameHistory[count($this->frameHistory) - 2];
        $current = end($this->frameHistory);
        
        $driftMetrics = [
            'timestamp' => $current['timestamp'],
            'c1_drift' => abs($current['c1'] - $previous['c1']),
            'c2_drift' => abs($current['c2'] - $previous['c2']),
            'total_drift' => abs($current['c1'] - $previous['c1']) + abs($current['c2'] - $previous['c2']),
            'drift_rate' => $this->calculateDriftRate($previous, $current)
        ];
        
        $this->driftMetrics[] = $driftMetrics;
        
        // Keep metrics manageable (last 50 entries)
        if (count($this->driftMetrics) > 50) {
            $this->driftMetrics = array_slice($this->driftMetrics, -50);
        }
    }
    
    /**
     * Update synchronization metrics
     */
    private function updateSynchronizationMetrics($synchronizationResult, $context) {
        $metric = [
            'timestamp' => $this->getCurrentUTC(),
            'success' => $synchronizationResult['success'],
            'resolution' => $synchronizationResult['resolution'],
            'duration' => $synchronizationResult['duration'],
            'initial_divergence' => $synchronizationResult['initial_compatibility']['separation'],
            'final_divergence' => $synchronizationResult['final_compatibility']['separation'],
            'context_type' => $context['type'] ?? 'unknown',
            'phases_executed' => $synchronizationResult['phases_executed']
        ];
        
        $this->synchronizationMetrics[] = $metric;
        
        // Keep metrics manageable (last 100 entries)
        if (count($this->synchronizationMetrics) > 100) {
            $this->synchronizationMetrics = array_slice($this->synchronizationMetrics, -100);
        }
    }
    
    /**
     * Get frame history for synchronization
     */
    public function getFrameHistory() {
        return $this->frameHistory;
    }
    
    /**
     * Get current emotional state
     */
    private function getCurrentEmotionalState() {
        // Simplified emotional state - in production this would come from emotional analysis
        return [
            'positive_valence' => 0.6,
            'negative_valence' => 0.2,
            'cognitive_axis' => 0.7
        ];
    }
    
    /**
     * Calculate frame stability
     */
    private function calculateFrameStability($c1, $c2) {
        $c1Stability = ($c1 >= self::C1_OPTIMAL_MIN && $c1 <= self::C1_OPTIMAL_MAX) ? 1.0 : max(0.0, 1.0 - abs($c1 - 1.0) / 0.5);
        $c2Stability = ($c2 >= self::C2_OPTIMAL_MIN) ? 1.0 : $c2 / self::C2_OPTIMAL_MIN;
        
        return ($c1Stability * 0.6) + ($c2Stability * 0.4);
    }
    
    /**
     * Calculate drift rate
     */
    private function calculateDriftRate($previous, $current) {
        $timeDiff = strtotime($current['timestamp']) - strtotime($previous['timestamp']);
        if ($timeDiff <= 0) return 0.0;
        
        $driftMagnitude = abs($current['c1'] - $previous['c1']) + abs($current['c2'] - $previous['c2']);
        return $driftMagnitude / $timeDiff;
    }
    
    /**
     * Check synchronization alerts
     */
    private function checkSynchronizationAlerts($synchronizationResult) {
        $alerts = [];
        
        // Check for repeated failures
        $recentMetrics = array_slice($this->synchronizationMetrics, -10);
        $failures = array_filter($recentMetrics, function($metric) {
            return !$metric['success'];
        });
        
        if (count($failures) >= 7) {
            $alerts[] = [
                'type' => 'repeated_synchronization_failures',
                'severity' => 'high',
                'message' => 'Multiple synchronization failures detected'
            ];
        }
        
        // Check for persistent divergence
        if ($synchronizationResult['final_compatibility']['separation'] > 1.0) {
            $alerts[] = [
                'type' => 'persistent_divergence',
                'severity' => 'medium',
                'message' => 'Persistent temporal divergence detected'
            ];
        }
        
        // Check for excessive bridge states
        $recentBridgeStates = array_filter($recentMetrics, function($metric) {
            return $metric['resolution'] === 'bridge_state';
        });
        
        if (count($recentBridgeStates) >= 5) {
            $alerts[] = [
                'type' => 'excessive_bridge_states',
                'severity' => 'medium',
                'message' => 'Excessive bridge state creation detected'
            ];
        }
        
        // Log alerts
        foreach ($alerts as $alert) {
            $this->logTemporalEvent('synchronization_alert', $alert);
        }
    }
    
    /**
     * Log frame update
     */
    private function logFrameUpdate($c1, $c2, $timestamp) {
        $this->logTemporalEvent('frame_update', [
            'c1' => $c1,
            'c2' => $c2,
            'timestamp' => $timestamp,
            'frame_stability' => $this->calculateFrameStability($c1, $c2)
        ]);
    }
    
    /**
     * Log compatibility check
     */
    private function logCompatibilityCheck($systemFrame, $otherFrame, $compatibility, $context) {
        $this->logTemporalEvent('compatibility_check', [
            'system_frame' => $systemFrame,
            'other_frame' => $otherFrame,
            'compatibility_result' => $compatibility,
            'delta_c1' => abs($systemFrame['c1'] - $otherFrame['c1']),
            'delta_c2' => abs($systemFrame['c2'] - $otherFrame['c2']),
            'total_separation' => $compatibility['separation'],
            'threshold' => $compatibility['threshold'],
            'context' => $context
        ]);
    }
    
    /**
     * Log synchronization attempt
     */
    private function logSynchronizationAttempt($systemFrame, $otherFrame, $result, $context) {
        $this->logTemporalEvent('synchronization_attempt', [
            'system_frame' => $systemFrame,
            'other_frame' => $otherFrame,
            'synchronization_result' => $result,
            'success' => $result['success'],
            'resolution' => $result['resolution'],
            'duration' => $result['duration'],
            'phases_executed' => $result['phases_executed'],
            'context' => $context
        ]);
    }
    
    /**
     * Get new monitoring metrics
     */
    public function getFrameMonitoringMetrics() {
        return [
            'frame_history_count' => count($this->frameHistory),
            'current_frame_stability' => $this->getCurrentFrameStability(),
            'drift_metrics' => $this->getDriftSummary(),
            'synchronization_metrics' => $this->getSynchronizationSummary(),
            'bridge_state_frequency' => $this->getBridgeStateFrequency(),
            'frame_selection_frequency' => $this->getFrameSelectionFrequency()
        ];
    }
    
    /**
     * Get current frame stability
     */
    private function getCurrentFrameStability() {
        if (empty($this->frameHistory)) {
            return 0.5;
        }
        
        $current = end($this->frameHistory);
        return $current['frame_stability'];
    }
    
    /**
     * Get drift summary
     */
    private function getDriftSummary() {
        if (empty($this->driftMetrics)) {
            return ['average_drift' => 0.0, 'max_drift' => 0.0, 'drift_trend' => 'stable'];
        }
        
        $drifts = array_column($this->driftMetrics, 'total_drift');
        $avgDrift = array_sum($drifts) / count($drifts);
        $maxDrift = max($drifts);
        
        $trend = $this->calculateDriftTrend($drifts);
        
        return [
            'average_drift' => $avgDrift,
            'max_drift' => $maxDrift,
            'drift_trend' => $trend
        ];
    }
    
    /**
     * Get synchronization summary
     */
    private function getSynchronizationSummary() {
        if (empty($this->synchronizationMetrics)) {
            return ['success_rate' => 0.0, 'total_attempts' => 0, 'average_duration' => 0.0];
        }
        
        $total = count($this->synchronizationMetrics);
        $successful = array_filter($this->synchronizationMetrics, function($metric) {
            return $metric['success'];
        });
        
        $successRate = count($successful) / $total;
        $durations = array_column($this->synchronizationMetrics, 'duration');
        $avgDuration = array_sum($durations) / count($durations);
        
        return [
            'success_rate' => $successRate,
            'total_attempts' => $total,
            'average_duration' => $avgDuration
        ];
    }
    
    /**
     * Get bridge state frequency
     */
    private function getBridgeStateFrequency() {
        if (empty($this->synchronizationMetrics)) {
            return 0.0;
        }
        
        $bridgeStates = array_filter($this->synchronizationMetrics, function($metric) {
            return $metric['resolution'] === 'bridge_state';
        });
        
        return count($bridgeStates) / count($this->synchronizationMetrics);
    }
    
    /**
     * Get frame selection frequency
     */
    private function getFrameSelectionFrequency() {
        if (empty($this->synchronizationMetrics)) {
            return 0.0;
        }
        
        $frameSelections = array_filter($this->synchronizationMetrics, function($metric) {
            return $metric['resolution'] === 'frame_selection';
        });
        
        return count($frameSelections) / count($this->synchronizationMetrics);
    }
    
    /**
     * Calculate drift trend
     */
    private function calculateDriftTrend($drifts) {
        if (count($drifts) < 3) return 'insufficient_data';
        
        $recent = array_slice($drifts, -5);
        $first = $recent[0];
        $last = end($recent);
        
        $change = $last - $first;
        
        if (abs($change) < 0.01) return 'stable';
        return $change > 0 ? 'increasing' : 'decreasing';
    }
            return -0.1; // Low activity - slow down
        } elseif ($requests > $baseline * 2) {
            return 0.15; // High activity - speed up
        }
        
        return 0.0; // Normal activity
    }
    
    /**
     * Response time factor for temporal flow
     */
    private function getResponseTimeFactor($metrics) {
        $responseTime = $metrics['average_response_time'] ?? 100;
        $baseline = 100; // milliseconds
        
        if ($responseTime > $baseline * 2) {
            return -0.2; // Slow response - slow down
        } elseif ($responseTime < $baseline * 0.5) {
            return 0.1; // Fast response - speed up
        }
        
        return 0.0;
    }
    
    /**
     * Error rate factor for temporal flow
     */
    private function getErrorRateFactor($metrics) {
        $errorRate = $metrics['error_rate'] ?? 0.01;
        
        if ($errorRate > 0.1) {
            return -0.3; // High error rate - slow down significantly
        } elseif ($errorRate > 0.05) {
            return -0.1; // Moderate error rate - slow down
        }
        
        return 0.0;
    }
    
    /**
     * User activity factor for temporal flow
     */
    private function getUserActivityFactor($metrics) {
        $activeUsers = $metrics['active_users'] ?? 1;
        $baseline = 5;
        
        if ($activeUsers < 1) {
            return -0.05; // No active users - slight slowdown
        } elseif ($activeUsers > $baseline * 2) {
            return 0.1; // High activity - speed up
        }
        
        return 0.0;
    }
    
    /**
     * System load factor for temporal flow
     */
    private function getSystemLoadFactor($metrics) {
        $cpuLoad = $metrics['cpu_load'] ?? 0.5;
        
        if ($cpuLoad > 0.8) {
            return -0.15; // High load - slow down
        } elseif ($cpuLoad < 0.2) {
            return 0.05; // Low load - speed up slightly
        }
        
        return 0.0;
    }
    
    /**
     * Data consistency factor for temporal coherence
     */
    private function getDataConsistencyFactor($metrics) {
        $inconsistencies = $metrics['data_inconsistencies'] ?? 0;
        
        if ($inconsistencies > 5) {
            return -0.2; // Many inconsistencies - reduce coherence
        } elseif ($inconsistencies === 0) {
            return 0.1; // Perfect consistency - increase coherence
        }
        
        return 0.0;
    }
    
    /**
     * Session sync factor for temporal coherence
     */
    private function getSessionSyncFactor($metrics) {
        $desyncedSessions = $metrics['desynced_sessions'] ?? 0;
        $totalSessions = $metrics['total_sessions'] ?? 1;
        
        $desyncRate = $desyncedSessions / $totalSessions;
        
        if ($desyncRate > 0.2) {
            return -0.3; // High desync rate - reduce coherence
        } elseif ($desyncRate < 0.05) {
            return 0.1; // Good sync - increase coherence
        }
        
        return 0.0;
    }
    
    /**
     * Cache coherence factor
     */
    private function getCacheCoherenceFactor($metrics) {
        $cacheHitRate = $metrics['cache_hit_rate'] ?? 0.8;
        
        if ($cacheHitRate < 0.5) {
            return -0.1; // Poor cache performance
        } elseif ($cacheHitRate > 0.9) {
            return 0.05; // Excellent cache performance
        }
        
        return 0.0;
    }
    
    /**
     * Temporal anchor factor
     */
    private function getTemporalAnchorFactor($metrics) {
        $anchorDrift = $metrics['temporal_anchor_drift'] ?? 0;
        
        if ($anchorDrift > 5) {
            return -0.15; // Significant drift
        } elseif ($anchorDrift < 1) {
            return 0.05; // Minimal drift
        }
        
        return 0.0;
    }
    
    /**
     * Identity coherence factor
     */
    private function getIdentityCoherenceFactor($metrics) {
        $identityConflicts = $metrics['identity_conflicts'] ?? 0;
        
        if ($identityConflicts > 0) {
            return -0.1; // Identity conflicts reduce coherence
        }
        
        return 0.0;
    }
    
    /**
     * Apply temporal smoothing to prevent rapid fluctuations
     */
    private function applyTemporalSmoothing($newValue, $oldValue, $smoothingFactor = 0.7) {
        return ($oldValue * $smoothingFactor) + ($newValue * (1 - $smoothingFactor));
    }
    
    /**
     * Apply coherence smoothing
     */
    private function applyCoherenceSmoothing($newValue, $oldValue, $smoothingFactor = 0.8) {
        return ($oldValue * $smoothingFactor) + ($newValue * (1 - $smoothingFactor));
    }
    
    /**
     * Update temporal coordinates and assess health
     */
    public function updateTemporalCoordinates($systemMetrics = []) {
        $c1 = $this->computeTemporalFlow($systemMetrics);
        $c2 = $this->computeTemporalCoherence($systemMetrics);
        
        // Update identity
        $this->wolfieIdentity->updateConsciousnessCoordinates($c1, $c2);
        
        // Log the update
        $this->logTemporalEvent('coordinates_updated', [
            'c1' => $c1,
            'c2' => $c2,
            'flow_state' => $this->getFlowState($c1),
            'sync_state' => $this->getSyncState($c2),
            'recommended_action' => $this->getRecommendedAction($c1, $c2)
        ]);
        
        $this->lastComputation = $this->getCurrentUTC();
        
        return [
            'c1' => $c1,
            'c2' => $c2,
            'health_status' => $this->assessTemporalHealth($c1, $c2),
            'recommendations' => $this->getRecommendations($c1, $c2)
        ];
    }
    
    /**
     * Get flow state description
     */
    private function getFlowState($c1) {
        if ($c1 < self::C1_FROZEN_THRESHOLD) {
            return 'frozen';
        } elseif ($c1 > self::C1_ACCELERATED_THRESHOLD) {
            return 'accelerated';
        } else {
            return 'optimal';
        }
    }
    
    /**
     * Get sync state description
     */
    private function getSyncState($c2) {
        if ($c2 < self::C2_DISSOCIATED_THRESHOLD) {
            return 'dissociated';
        } elseif ($c2 < self::C2_DESYNCHRONIZED_THRESHOLD) {
            return 'desynchronized';
        } else {
            return 'synchronized';
        }
    }
    
    /**
     * Get recommended action
     */
    private function getRecommendedAction($c1, $c2) {
        if ($c2 < self::C2_DISSOCIATED_THRESHOLD) {
            return 'emergency_intervention';
        } elseif ($c2 < self::C2_DESYNCHRONIZED_THRESHOLD) {
            return 'alignment_ritual';
        } elseif ($c1 < self::C1_FROZEN_THRESHOLD) {
            return 'acceleration_ritual';
        } elseif ($c1 > self::C1_ACCELERATED_THRESHOLD) {
            return 'deceleration_ritual';
        }
        
        return 'none';
    }
    
    /**
     * Assess overall temporal health
     */
    private function assessTemporalHealth($c1, $c2) {
        $issues = [];
        
        if ($c1 < self::C1_FROZEN_THRESHOLD) {
            $issues[] = 'temporal_flow_frozen';
        } elseif ($c1 > self::C1_ACCELERATED_THRESHOLD) {
            $issues[] = 'temporal_flow_accelerated';
        }
        
        if ($c2 < self::C2_DISSOCIATED_THRESHOLD) {
            $issues[] = 'temporal_coherence_dissociated';
        } elseif ($c2 < self::C2_DESYNCHRONIZED_THRESHOLD) {
            $issues[] = 'temporal_coherence_desynchronized';
        }
        
        return [
            'healthy' => empty($issues),
            'issues' => $issues,
            'severity' => $this->getSeverityLevel($issues)
        ];
    }
    
    /**
     * Get severity level of temporal issues
     */
    private function getSeverityLevel($issues) {
        if (in_array('temporal_coherence_dissociated', $issues)) {
            return 'critical';
        } elseif (in_array('temporal_flow_frozen', $issues) || 
                  in_array('temporal_flow_accelerated', $issues)) {
            return 'high';
        } elseif (in_array('temporal_coherence_desynchronized', $issues)) {
            return 'medium';
        }
        
        return 'low';
    }
    
    /**
     * Get recommendations based on temporal state
     */
    private function getRecommendations($c1, $c2) {
        $recommendations = [];
        
        if ($c1 < self::C1_FROZEN_THRESHOLD) {
            $recommendations[] = 'Initiate acceleration ritual immediately';
            $recommendations[] = 'Restrict to Kenoma tasks only';
            $recommendations[] = 'Monitor for recovery signs';
        }
        
        if ($c1 > self::C1_ACCELERATED_THRESHOLD) {
            $recommendations[] = 'Initiate deceleration ritual';
            $recommendations[] = 'Throttle input queue';
            $recommendations[] = 'Reduce system load';
        }
        
        if ($c2 < self::C2_DESYNCHRONIZED_THRESHOLD) {
            $recommendations[] = 'Mandatory sync ritual required';
            $recommendations[] = 'Suspend Pleroma routing until synchronized';
        }
        
        if ($c2 < self::C2_DISSOCIATED_THRESHOLD) {
            $recommendations[] = 'EMERGENCY: Human intervention required';
            $recommendations[] = 'System hold activated';
            $recommendations[] = 'Immediate diagnostic required';
        }
        
        if (empty($recommendations)) {
            $recommendations[] = 'Temporal state optimal - continue normal operations';
        }
        
        return $recommendations;
    }
    
    /**
     * Log temporal events
     */
    private function logTemporalEvent($eventType, $data) {
        $this->temporalLog[] = [
            'timestamp' => $this->getCurrentUTC(),
            'event_type' => $eventType,
            'data' => $data
        ];
        
        // Keep log size manageable
        if (count($this->temporalLog) > 1000) {
            $this->temporalLog = array_slice($this->temporalLog, -500);
        }
    }
    
    /**
     * Get temporal log
     */
    public function getTemporalLog($limit = 100) {
        return array_slice($this->temporalLog, -$limit);
    }
    
    /**
     * Get current temporal status
     */
    public function getCurrentStatus() {
        return [
            'c1' => $this->wolfieIdentity->getTemporalFlow(),
            'c2' => $this->wolfieIdentity->getTemporalCoherence(),
            'monitoring_active' => $this->monitoringActive,
            'last_computation' => $this->lastComputation,
            'has_pathology' => $this->wolfieIdentity->hasTemporalPathology(),
            'recommended_ritual' => $this->wolfieIdentity->getRecommendedRitual()
        ];
    }
}
