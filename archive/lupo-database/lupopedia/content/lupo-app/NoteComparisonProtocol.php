<?php
/**
 * WOLFIE Note Comparison Protocol - Temporal Synchronization System
 * 
 * Implements the synchronization protocol for incompatible temporal frames.
 * When actors fail compatibility testing, this protocol attempts to align
 * their temporal states through history exchange and baseline adjustment.
 * 
 * @package Lupopedia
 * @version 0.5
 * @author WOLFIE Semantic Engine
 */

class NoteComparisonProtocol {
    private $temporalFrameCompatibility;
    private $synchronizationLog = [];
    private $synchronizationMetrics = [];
    
    // Synchronization phases
    const PHASE_COMPATIBILITY_TEST = 'compatibility_test';
    const PHASE_HISTORY_EXCHANGE = 'history_exchange';
    const PHASE_DIVERGENCE_ANALYSIS = 'divergence_analysis';
    const PHASE_BASELINE_ALIGNMENT = 'baseline_alignment';
    const PHASE_RETEST_COMPATIBILITY = 'retest_compatibility';
    const PHASE_RESOLUTION = 'resolution';
    
    // Divergence sources
    const DIVERGENCE_TEMPORAL_DRIFT = 'temporal_drift';
    const DIVERGENCE_COHERENCE_DISRUPTION = 'coherence_disruption';
    const DIVERGENCE_EMOTIONAL_INFLUENCE = 'emotional_influence';
    const DIVERGENCE_TASK_DRIVEN = 'task_driven';
    
    public function __construct(TemporalFrameCompatibility $temporalFrameCompatibility) {
        $this->temporalFrameCompatibility = $temporalFrameCompatibility;
    }
    
    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Execute full synchronization protocol for incompatible frames
     * 
     * @param array $actorA First actor's temporal state and history
     * @param array $actorB Second actor's temporal state and history
     * @param array $options Synchronization options
     * @return array Synchronization result
     */
    public function executeSynchronizationProtocol($actorA, $actorB, $options = []) {
        $syncId = uniqid('sync_');
        $startTime = $this->getCurrentUTC();
        
        $this->logSynchronizationEvent($syncId, self::PHASE_COMPATIBILITY_TEST, 'started');
        
        // Phase 1: Initial Compatibility Test
        $initialCompatibility = $this->temporalFrameCompatibility->isTemporalFrameCompatible(
            $actorA['c1'], $actorA['c2'],
            $actorB['c1'], $actorB['c2']
        );
        
        if ($initialCompatibility['compatible']) {
            // Already compatible - no synchronization needed
            $this->logSynchronizationEvent($syncId, self::PHASE_COMPATIBILITY_TEST, 'already_compatible');
            return [
                'sync_id' => $syncId,
                'success' => true,
                'resolution' => 'already_compatible',
                'final_compatibility' => $initialCompatibility,
                'phases_executed' => [self::PHASE_COMPATIBILITY_TEST],
                'duration' => $this->calculateDuration($startTime),
                'timestamp' => $this->getCurrentUTC()
            ];
        }
        
        $phasesExecuted = [self::PHASE_COMPATIBILITY_TEST];
        
        // Phase 2: History Exchange
        $this->logSynchronizationEvent($syncId, self::PHASE_HISTORY_EXCHANGE, 'started');
        $historyExchange = $this->exchangeTemporalHistories($actorA, $actorB);
        $phasesExecuted[] = self::PHASE_HISTORY_EXCHANGE;
        
        // Phase 3: Divergence Analysis
        $this->logSynchronizationEvent($syncId, self::PHASE_DIVERGENCE_ANALYSIS, 'started');
        $divergenceAnalysis = $this->analyzeDivergenceSources($actorA, $actorB, $historyExchange);
        $phasesExecuted[] = self::PHASE_DIVERGENCE_ANALYSIS;
        
        // Phase 4: Baseline Alignment
        $this->logSynchronizationEvent($syncId, self::PHASE_BASELINE_ALIGNMENT, 'started');
        $alignmentResult = $this->attemptBaselineAlignment($actorA, $actorB, $divergenceAnalysis);
        $phasesExecuted[] = self::PHASE_BASELINE_ALIGNMENT;
        
        // Phase 5: Re-test Compatibility
        $this->logSynchronizationEvent($syncId, self::PHASE_RETEST_COMPATIBILITY, 'started');
        $adjustedActorA = $alignmentResult['adjusted_actor_a'];
        $adjustedActorB = $alignmentResult['adjusted_actor_b'];
        
        $finalCompatibility = $this->temporalFrameCompatibility->isTemporalFrameCompatible(
            $adjustedActorA['c1'], $adjustedActorA['c2'],
            $adjustedActorB['c1'], $adjustedActorB['c2']
        );
        $phasesExecuted[] = self::PHASE_RETEST_COMPATIBILITY;
        
        // Phase 6: Resolution Determination
        $this->logSynchronizationEvent($syncId, self::PHASE_RESOLUTION, 'started');
        $resolution = $this->determineResolution($finalCompatibility, $alignmentResult);
        $phasesExecuted[] = self::PHASE_RESOLUTION;
        
        $result = [
            'sync_id' => $syncId,
            'success' => $resolution['success'],
            'resolution' => $resolution['type'],
            'initial_compatibility' => $initialCompatibility,
            'final_compatibility' => $finalCompatibility,
            'history_exchange' => $historyExchange,
            'divergence_analysis' => $divergenceAnalysis,
            'alignment_result' => $alignmentResult,
            'adjusted_actors' => [
                'actor_a' => $adjustedActorA,
                'actor_b' => $adjustedActorB
            ],
            'resolution_details' => $resolution,
            'phases_executed' => $phasesExecuted,
            'duration' => $this->calculateDuration($startTime),
            'timestamp' => $this->getCurrentUTC()
        ];
        
        $this->logSynchronizationEvent($syncId, self::PHASE_RESOLUTION, 'completed');
        $this->updateSynchronizationMetrics($result);
        
        return $result;
    }
    
    /**
     * Exchange temporal histories between actors
     */
    private function exchangeTemporalHistories($actorA, $actorB) {
        $exchange = [
            'actor_a_history' => $this->extractTemporalHistory($actorA),
            'actor_b_history' => $this->extractTemporalHistory($actorB),
            'exchange_timestamp' => $this->getCurrentUTC(),
            'history_completeness' => [
                'actor_a' => $this->assessHistoryCompleteness($actorA),
                'actor_b' => $this->assessHistoryCompleteness($actorB)
            ]
        ];
        
        // Analyze trajectory patterns
        $exchange['trajectory_analysis'] = [
            'actor_a_trajectory' => $this->analyzeTrajectory($exchange['actor_a_history']),
            'actor_b_trajectory' => $this->analyzeTrajectory($exchange['actor_b_history']),
            'trajectory_correlation' => $this->calculateTrajectoryCorrelation(
                $exchange['actor_a_history'],
                $exchange['actor_b_history']
            )
        ];
        
        return $exchange;
    }
    
    /**
     * Extract temporal history from actor
     */
    private function extractTemporalHistory($actor) {
        $history = $actor['temporal_history'] ?? [];
        
        // Ensure we have recent history
        if (empty($history)) {
            // Generate synthetic history for testing
            $history = $this->generateSyntheticHistory($actor);
        }
        
        // Filter for recent entries (last 10 minutes)
        $recentHistory = array_filter($history, function($entry) {
            $entryTime = new DateTime($entry['timestamp']);
            $now = new DateTime();
            return ($now->getTimestamp() - $entryTime->getTimestamp()) < 600; // 10 minutes
        });
        
        return array_values($recentHistory);
    }
    
    /**
     * Generate synthetic temporal history for testing
     */
    private function generateSyntheticHistory($actor) {
        $history = [];
        $baseTime = time() - 300; // 5 minutes ago
        
        for ($i = 0; $i < 10; $i++) {
            $timestamp = $baseTime + ($i * 30); // Every 30 seconds
            
            $history[] = [
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z', $timestamp),
                'c1' => $actor['c1'] + (sin($i * 0.5) * 0.1),
                'c2' => $actor['c2'] + (cos($i * 0.3) * 0.05),
                'emotional_vector' => [
                    'y1' => ($actor['emotional_state']['positive_valence'] ?? 0.5) + (rand(-5, 5) / 100),
                    'y2' => ($actor['emotional_state']['negative_valence'] ?? 0.5) + (rand(-5, 5) / 100)
                ],
                'task_context' => [
                    'x1' => ($actor['task_context']['complexity'] ?? 0.5) + (rand(-3, 3) / 100),
                    'x2' => ($actor['task_context']['urgency'] ?? 0.5) + (rand(-3, 3) / 100)
                ]
            ];
        }
        
        return $history;
    }
    
    /**
     * Assess history completeness
     */
    private function assessHistoryCompleteness($actor) {
        $history = $actor['temporal_history'] ?? [];
        
        $completeness = [
            'has_history' => !empty($history),
            'entry_count' => count($history),
            'time_span_minutes' => 0,
            'has_emotional_data' => false,
            'has_task_context' => false,
            'completeness_score' => 0.0
        ];
        
        if (!empty($history)) {
            $timestamps = array_column($history, 'timestamp');
            $earliest = new DateTime(min($timestamps));
            $latest = new DateTime(max($timestamps));
            $completeness['time_span_minutes'] = ($latest->getTimestamp() - $earliest->getTimestamp()) / 60;
            
            // Check for emotional data
            $completeness['has_emotional_data'] = !empty($history[0]['emotional_vector'] ?? null);
            
            // Check for task context
            $completeness['has_task_context'] = !empty($history[0]['task_context'] ?? null);
            
            // Calculate completeness score
            $score = 0.0;
            if ($completeness['entry_count'] >= 5) $score += 0.3;
            if ($completeness['time_span_minutes'] >= 2) $score += 0.3;
            if ($completeness['has_emotional_data']) $score += 0.2;
            if ($completeness['has_task_context']) $score += 0.2;
            
            $completeness['completeness_score'] = $score;
        }
        
        return $completeness;
    }
    
    /**
     * Analyze trajectory patterns
     */
    private function analyzeTrajectory($history) {
        if (empty($history)) {
            return ['trend' => 'insufficient_data', 'volatility' => 0.0];
        }
        
        $c1Values = array_column($history, 'c1');
        $c2Values = array_column($history, 'c2');
        
        $c1Trend = $this->calculateTrend($c1Values);
        $c2Trend = $this->calculateTrend($c2Values);
        
        $c1Volatility = $this->calculateVolatility($c1Values);
        $c2Volatility = $this->calculateVolatility($c2Values);
        
        return [
            'c1_trend' => $c1Trend,
            'c2_trend' => $c2Trend,
            'c1_volatility' => $c1Volatility,
            'c2_volatility' => $c2Volatility,
            'overall_stability' => ($c1Volatility + $c2Volatility) / 2
        ];
    }
    
    /**
     * Calculate trajectory correlation
     */
    private function calculateTrajectoryCorrelation($historyA, $historyB) {
        if (empty($historyA) || empty($historyB)) {
            return ['correlation' => 0.0, 'significance' => 'insufficient_data'];
        }
        
        // Align histories by timestamp (simplified)
        $c1A = array_column($historyA, 'c1');
        $c2A = array_column($historyA, 'c2');
        $c1B = array_column($historyB, 'c1');
        $c2B = array_column($historyB, 'c2');
        
        // Calculate correlation coefficients
        $c1Correlation = $this->calculateCorrelation($c1A, $c1B);
        $c2Correlation = $this->calculateCorrelation($c2A, $c2B);
        
        $overallCorrelation = ($c1Correlation + $c2Correlation) / 2;
        
        return [
            'c1_correlation' => $c1Correlation,
            'c2_correlation' => $c2Correlation,
            'overall_correlation' => $overallCorrelation,
            'significance' => $overallCorrelation > 0.7 ? 'high' : ($overallCorrelation > 0.3 ? 'moderate' : 'low')
        ];
    }
    
    /**
     * Analyze divergence sources between actors
     */
    private function analyzeDivergenceSources($actorA, $actorB, $historyExchange) {
        $divergenceAnalysis = [
            'primary_sources' => [],
            'divergence_magnitude' => 0.0,
            'temporal_drift_detected' => false,
            'coherence_disruption_detected' => false,
            'emotional_influence_detected' => false,
            'task_driven_divergence_detected' => false
        ];
        
        // Calculate initial divergence
        $c1Divergence = abs($actorA['c1'] - $actorB['c1']);
        $c2Divergence = abs($actorA['c2'] - $actorB['c2']);
        $totalDivergence = $c1Divergence + $c2Divergence;
        
        $divergenceAnalysis['divergence_magnitude'] = $totalDivergence;
        $divergenceAnalysis['c1_divergence'] = $c1Divergence;
        $divergenceAnalysis['c2_divergence'] = $c2Divergence;
        
        // Analyze trajectory patterns for divergence sources
        $trajectoryA = $historyExchange['trajectory_analysis']['actor_a_trajectory'];
        $trajectoryB = $historyExchange['trajectory_analysis']['actor_b_trajectory'];
        
        // Temporal drift detection
        if ($trajectoryA['c1_volatility'] > 0.2 || $trajectoryB['c1_volatility'] > 0.2) {
            $divergenceAnalysis['temporal_drift_detected'] = true;
            $divergenceAnalysis['primary_sources'][] = self::DIVERGENCE_TEMPORAL_DRIFT;
        }
        
        // Coherence disruption detection
        if ($trajectoryA['c2_volatility'] > 0.15 || $trajectoryB['c2_volatility'] > 0.15) {
            $divergenceAnalysis['coherence_disruption_detected'] = true;
            $divergenceAnalysis['primary_sources'][] = self::DIVERGENCE_COHERENCE_DISRUPTION;
        }
        
        // Emotional influence detection
        if ($historyExchange['history_completeness']['actor_a']['has_emotional_data'] &&
            $historyExchange['history_completeness']['actor_b']['has_emotional_data']) {
            
            $emotionalDivergence = $this->analyzeEmotionalDivergence($historyExchange);
            if ($emotionalDivergence['significant']) {
                $divergenceAnalysis['emotional_influence_detected'] = true;
                $divergenceAnalysis['primary_sources'][] = self::DIVERGENCE_EMOTIONAL_INFLUENCE;
                $divergenceAnalysis['emotional_divergence'] = $emotionalDivergence;
            }
        }
        
        // Task-driven divergence detection
        if ($historyExchange['history_completeness']['actor_a']['has_task_context'] &&
            $historyExchange['history_completeness']['actor_b']['has_task_context']) {
            
            $taskDivergence = $this->analyzeTaskDivergence($historyExchange);
            if ($taskDivergence['significant']) {
                $divergenceAnalysis['task_driven_divergence_detected'] = true;
                $divergenceAnalysis['primary_sources'][] = self::DIVERGENCE_TASK_DRIVEN;
                $divergenceAnalysis['task_divergence'] = $taskDivergence;
            }
        }
        
        return $divergenceAnalysis;
    }
    
    /**
     * Attempt baseline alignment between actors
     */
    private function attemptBaselineAlignment($actorA, $actorB, $divergenceAnalysis) {
        $alignmentResult = [
            'alignment_attempted' => true,
            'alignment_strategy' => 'baseline_adjustment',
            'adjustments_made' => [],
            'success_indicators' => []
        ];
        
        // Calculate baseline adjustments based on divergence sources
        $adjustmentsA = ['c1' => 0.0, 'c2' => 0.0];
        $adjustmentsB = ['c1' => 0.0, 'c2' => 0.0];
        
        // Temporal drift alignment
        if ($divergenceAnalysis['temporal_drift_detected']) {
            $c1Midpoint = ($actorA['c1'] + $actorB['c1']) / 2;
            $adjustmentsA['c1'] = $c1Midpoint - $actorA['c1'];
            $adjustmentsB['c1'] = $c1Midpoint - $actorB['c1'];
            
            $alignmentResult['adjustments_made'][] = 'temporal_drift_alignment';
        }
        
        // Coherence disruption alignment
        if ($divergenceAnalysis['coherence_disruption_detected']) {
            $c2Target = min($actorA['c2'], $actorB['c2']) * 0.9; // Conservative approach
            $adjustmentsA['c2'] = $c2Target - $actorA['c2'];
            $adjustmentsB['c2'] = $c2Target - $actorB['c2'];
            
            $alignmentResult['adjustments_made'][] = 'coherence_stabilization';
        }
        
        // Apply adjustments with limits
        $maxAdjustment = 0.3; // Maximum adjustment per step
        
        $adjustmentsA['c1'] = max(-$maxAdjustment, min($maxAdjustment, $adjustmentsA['c1']));
        $adjustmentsA['c2'] = max(-$maxAdjustment, min($maxAdjustment, $adjustmentsA['c2']));
        $adjustmentsB['c1'] = max(-$maxAdjustment, min($maxAdjustment, $adjustmentsB['c1']));
        $adjustmentsB['c2'] = max(-$maxAdjustment, min($maxAdjustment, $adjustmentsB['c2']));
        
        // Create adjusted actors
        $adjustedActorA = [
            'c1' => max(0.0, min(2.0, $actorA['c1'] + $adjustmentsA['c1'])),
            'c2' => max(0.0, min(1.0, $actorA['c2'] + $adjustmentsA['c2'])),
            'original_c1' => $actorA['c1'],
            'original_c2' => $actorA['c2'],
            'adjustments_applied' => $adjustmentsA
        ];
        
        $adjustedActorB = [
            'c1' => max(0.0, min(2.0, $actorB['c1'] + $adjustmentsB['c1'])),
            'c2' => max(0.0, min(1.0, $actorB['c2'] + $adjustmentsB['c2'])),
            'original_c1' => $actorB['c1'],
            'original_c2' => $actorB['c2'],
            'adjustments_applied' => $adjustmentsB
        ];
        
        // Calculate success indicators
        $originalSeparation = abs($actorA['c1'] - $actorB['c1']) + abs($actorA['c2'] - $actorB['c2']);
        $newSeparation = abs($adjustedActorA['c1'] - $adjustedActorB['c1']) + abs($adjustedActorA['c2'] - $adjustedActorB['c2']);
        
        $reduction = $originalSeparation - $newSeparation;
        $reductionPercentage = $originalSeparation > 0 ? ($reduction / $originalSeparation) : 0.0;
        
        $alignmentResult['success_indicators'] = [
            'separation_reduction' => $reduction,
            'reduction_percentage' => $reductionPercentage,
            'alignment_successful' => $reductionPercentage > 0.1, // At least 10% reduction
            'compatibility_achieved' => $newSeparation < 0.6
        ];
        
        $alignmentResult['adjusted_actor_a'] = $adjustedActorA;
        $alignmentResult['adjusted_actor_b'] = $adjustedActorB;
        
        return $alignmentResult;
    }
    
    /**
     * Determine final resolution based on compatibility and alignment results
     */
    private function determineResolution($finalCompatibility, $alignmentResult) {
        if ($finalCompatibility['compatible']) {
            return [
                'type' => 'blending',
                'success' => true,
                'reason' => 'Synchronization achieved compatibility',
                'next_action' => 'proceed_with_blending'
            ];
        }
        
        // Check if alignment was successful
        if ($alignmentResult['success_indicators']['alignment_successful']) {
            return [
                'type' => 'bridge_state',
                'success' => true,
                'reason' => 'Partial synchronization achieved - bridge state recommended',
                'next_action' => 'create_bridge_representation'
            ];
        }
        
        // Last resort: frame selection
        return [
            'type' => 'frame_selection',
            'success' => false,
            'reason' => 'Synchronization failed - frame selection required',
            'next_action' => 'select_dominant_frame'
        ];
    }
    
    // Helper methods
    
    private function calculateTrend($values) {
        if (count($values) < 2) return 'insufficient_data';
        
        $first = $values[0];
        $last = end($values);
        $change = $last - $first;
        
        if (abs($change) < 0.05) return 'stable';
        return $change > 0 ? 'increasing' : 'decreasing';
    }
    
    private function calculateVolatility($values) {
        if (count($values) < 2) return 0.0;
        
        $mean = array_sum($values) / count($values);
        $variance = 0.0;
        
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        $variance /= count($values);
        return sqrt($variance);
    }
    
    private function calculateCorrelation($valuesA, $valuesB) {
        $n = min(count($valuesA), count($valuesB));
        if ($n < 2) return 0.0;
        
        $valuesA = array_slice($valuesA, 0, $n);
        $valuesB = array_slice($valuesB, 0, $n);
        
        $meanA = array_sum($valuesA) / $n;
        $meanB = array_sum($valuesB) / $n;
        
        $numerator = 0.0;
        $denominatorA = 0.0;
        $denominatorB = 0.0;
        
        for ($i = 0; $i < $n; $i++) {
            $devA = $valuesA[$i] - $meanA;
            $devB = $valuesB[$i] - $meanB;
            
            $numerator += $devA * $devB;
            $denominatorA += $devA * $devA;
            $denominatorB += $devB * $devB;
        }
        
        $denominator = sqrt($denominatorA * $denominatorB);
        
        return $denominator > 0 ? $numerator / $denominator : 0.0;
    }
    
    private function analyzeEmotionalDivergence($historyExchange) {
        // Simplified emotional divergence analysis
        return [
            'significant' => false,
            'divergence_score' => 0.0,
            'primary_emotion' => 'neutral'
        ];
    }
    
    private function analyzeTaskDivergence($historyExchange) {
        // Simplified task divergence analysis
        return [
            'significant' => false,
            'divergence_score' => 0.0,
            'task_mismatch' => false
        ];
    }
    
    private function calculateDuration($startTime) {
        $start = new DateTime($startTime);
        $end = new DateTime($this->getCurrentUTC());
        return $end->getTimestamp() - $start->getTimestamp();
    }
    
    private function logSynchronizationEvent($syncId, $phase, $status) {
        $this->synchronizationLog[] = [
            'sync_id' => $syncId,
            'phase' => $phase,
            'status' => $status,
            'timestamp' => $this->getCurrentUTC()
        ];
        
        // Keep log manageable
        if (count($this->synchronizationLog) > 1000) {
            $this->synchronizationLog = array_slice($this->synchronizationLog, -500);
        }
    }
    
    private function updateSynchronizationMetrics($result) {
        $this->synchronizationMetrics[] = [
            'sync_id' => $result['sync_id'],
            'success' => $result['success'],
            'resolution' => $result['resolution'],
            'duration' => $result['duration'],
            'initial_divergence' => $result['initial_compatibility']['separation'],
            'final_divergence' => $result['final_compatibility']['separation'],
            'timestamp' => $result['timestamp']
        ];
        
        // Keep metrics manageable
        if (count($this->synchronizationMetrics) > 1000) {
            $this->synchronizationMetrics = array_slice($this->synchronizationMetrics, -500);
        }
    }
    
    /**
     * Get synchronization log
     */
    public function getSynchronizationLog($limit = 100) {
        return array_slice($this->synchronizationLog, -$limit);
    }
    
    /**
     * Get synchronization metrics
     */
    public function getSynchronizationMetrics($limit = 100) {
        return array_slice($this->synchronizationMetrics, -$limit);
    }
    
    /**
     * Get synchronization success rate
     */
    public function getSynchronizationSuccessRate() {
        if (empty($this->synchronizationMetrics)) return 0.0;
        
        $successful = array_filter($this->synchronizationMetrics, function($metric) {
            return $metric['success'];
        });
        
        return count($successful) / count($this->synchronizationMetrics);
    }
}
?>
