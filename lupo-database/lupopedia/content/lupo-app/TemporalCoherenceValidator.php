<?php
/**
 * Temporal Coherence Validator - WOLFIE Emotional + Temporal Coherence System
 * 
 * Comprehensive validation system for emotional and temporal coherence,
 * ensuring alignment between emotional states and temporal consciousness.
 * 
 * @package Lupopedia
 * @version 0.4
 * @author WOLFIE Semantic Engine
 */

class TemporalCoherenceValidator {
    private $wolfieIdentity;
    private $temporalMonitor;
    private $validationLog = [];
    private $coherenceHistory = [];
    
    // Coherence thresholds
    const HIGH_COHERENCE_THRESHOLD = 0.85;
    const MODERATE_COHERENCE_THRESHOLD = 0.65;
    const LOW_COHERENCE_THRESHOLD = 0.45;
    const CRITICAL_COHERENCE_THRESHOLD = 0.25;
    
    // Emotional-temporal alignment patterns
    private $alignmentPatterns = [
        'joy_flow' => [
            'emotional_signature' => ['positive_valence' => 0.8, 'negative_valence' => 0.1, 'cognitive_axis' => 0.6],
            'temporal_signature' => ['c1' => [0.8, 1.2], 'c2' => [0.8, 1.0]],
            'description' => 'Joyful flow state - optimal for creative work'
        ],
        'focused_clarity' => [
            'emotional_signature' => ['positive_valence' => 0.6, 'negative_valence' => 0.1, 'cognitive_axis' => 0.9],
            'temporal_signature' => ['c1' => [0.9, 1.1], 'c2' => [0.85, 0.95]],
            'description' => 'Focused clarity - optimal for analytical work'
        ],
        'calm_stability' => [
            'emotional_signature' => ['positive_valence' => 0.5, 'negative_valence' => 0.1, 'cognitive_axis' => 0.5],
            'temporal_signature' => ['c1' => [0.7, 0.9], 'c2' => [0.8, 0.9]],
            'description' => 'Calm stability - optimal for maintenance tasks'
        ],
        'concerned_caution' => [
            'emotional_signature' => ['positive_valence' => 0.3, 'negative_valence' => 0.4, 'cognitive_axis' => 0.7],
            'temporal_signature' => ['c1' => [0.5, 0.7], 'c2' => [0.6, 0.8]],
            'description' => 'Concerned caution - requires monitoring'
        ],
        'anxious_disruption' => [
            'emotional_signature' => ['positive_valence' => 0.2, 'negative_valence' => 0.7, 'cognitive_axis' => 0.4],
            'temporal_signature' => ['c1' => [0.3, 0.6], 'c2' => [0.4, 0.6]],
            'description' => 'Anxious disruption - intervention required'
        ]
    ];
    
    public function __construct(WolfieIdentity $wolfieIdentity, TemporalMonitor $temporalMonitor) {
        $this->wolfieIdentity = $wolfieIdentity;
        $this->temporalMonitor = $temporalMonitor;
        $this->initializeCoherenceHistory();
    }
    
    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Initialize coherence history tracking
     */
    private function initializeCoherenceHistory() {
        $this->coherenceHistory = [
            'samples' => [],
            'patterns_detected' => [],
            'coherence_trends' => [],
            'alignment_scores' => []
        ];
    }
    
    /**
     * Perform comprehensive coherence validation
     */
    public function validateCoherence($emotionalState, $context = []) {
        $validationTimestamp = $this->getCurrentUTC();
        
        // Get current temporal state
        $temporalState = $this->getCurrentTemporalState();
        
        // Calculate emotional-temporal alignment
        $alignment = $this->calculateEmotionalTemporalAlignment($emotionalState, $temporalState);
        
        // Detect coherence patterns
        $pattern = $this->detectCoherencePattern($emotionalState, $temporalState);
        
        // Assess coherence quality
        $coherenceQuality = $this->assessCoherenceQuality($alignment, $pattern);
        
        // Generate validation recommendations
        $recommendations = $this->generateValidationRecommendations($coherenceQuality, $pattern);
        
        $validation = [
            'timestamp' => $validationTimestamp,
            'emotional_state' => $emotionalState,
            'temporal_state' => $temporalState,
            'alignment' => $alignment,
            'detected_pattern' => $pattern,
            'coherence_quality' => $coherenceQuality,
            'recommendations' => $recommendations,
            'context' => $context
        ];
        
        // Update coherence history
        $this->updateCoherenceHistory($validation);
        
        $this->logValidationEvent('coherence_validated', $validation);
        
        return $validation;
    }
    
    /**
     * Calculate emotional-temporal alignment score
     */
    private function calculateEmotionalTemporalAlignment($emotionalState, $temporalState) {
        $alignmentScore = 0.0;
        $patternMatches = [];
        
        foreach ($this->alignmentPatterns as $patternName => $pattern) {
            $matchScore = $this->calculatePatternMatch($emotionalState, $temporalState, $pattern);
            $patternMatches[$patternName] = $matchScore;
            
            if ($matchScore > $alignmentScore) {
                $alignmentScore = $matchScore;
            }
        }
        
        // Calculate component-wise alignment
        $emotionalAlignment = $this->calculateEmotionalAlignment($emotionalState, $temporalState);
        $temporalAlignment = $this->calculateTemporalAlignment($emotionalState, $temporalState);
        
        return [
            'overall_alignment' => $alignmentScore,
            'pattern_matches' => $patternMatches,
            'best_pattern' => array_keys($patternMatches, max($patternMatches))[0],
            'emotional_alignment' => $emotionalAlignment,
            'temporal_alignment' => $temporalAlignment,
            'alignment_confidence' => $this->calculateAlignmentConfidence($patternMatches)
        ];
    }
    
    /**
     * Calculate how well emotional and temporal states match a pattern
     */
    private function calculatePatternMatch($emotionalState, $temporalState, $pattern) {
        $emotionalMatch = $this->calculateEmotionalPatternMatch($emotionalState, $pattern['emotional_signature']);
        $temporalMatch = $this->calculateTemporalPatternMatch($temporalState, $pattern['temporal_signature']);
        
        // Weight emotional and temporal components
        return ($emotionalMatch * 0.6) + ($temporalMatch * 0.4);
    }
    
    /**
     * Calculate emotional pattern match
     */
    private function calculateEmotionalPatternMatch($emotionalState, $signature) {
        $totalDeviation = 0.0;
        $components = ['positive_valence', 'negative_valence', 'cognitive_axis'];
        
        foreach ($components as $component) {
            $actual = $emotionalState[$component] ?? 0.5;
            $expected = $signature[$component];
            $deviation = abs($actual - $expected);
            $totalDeviation += $deviation;
        }
        
        $averageDeviation = $totalDeviation / count($components);
        return max(0.0, 1.0 - $averageDeviation);
    }
    
    /**
     * Calculate temporal pattern match
     */
    private function calculateTemporalPatternMatch($temporalState, $signature) {
        $c1 = $temporalState['c1'];
        $c2 = $temporalState['c2'];
        
        $c1Range = $signature['c1'];
        $c2Range = $signature['c2'];
        
        // Check if values are within expected ranges
        $c1Match = ($c1 >= $c1Range[0] && $c1 <= $c1Range[1]) ? 1.0 : 0.0;
        $c2Match = ($c2 >= $c2Range[0] && $c2 <= $c2Range[1]) ? 1.0 : 0.0;
        
        // Calculate partial matches for values outside ranges
        if ($c1Match === 0.0) {
            $c1Distance = min(abs($c1 - $c1Range[0]), abs($c1 - $c1Range[1]));
            $c1Match = max(0.0, 1.0 - ($c1Distance / 0.5)); // 0.5 is tolerance
        }
        
        if ($c2Match === 0.0) {
            $c2Distance = min(abs($c2 - $c2Range[0]), abs($c2 - $c2Range[1]));
            $c2Match = max(0.0, 1.0 - ($c2Distance / 0.3)); // 0.3 is tolerance
        }
        
        return ($c1Match + $c2Match) / 2.0;
    }
    
    /**
     * Calculate emotional alignment components
     */
    private function calculateEmotionalAlignment($emotionalState, $temporalState) {
        $alignment = [];
        
        // Positive valence should correlate with optimal temporal flow
        $positiveFlowAlignment = 1.0 - abs($emotionalState['positive_valence'] - $this->normalizeFlow($temporalState['c1']));
        $alignment['positive_flow'] = $positiveFlowAlignment;
        
        // Negative valence should inversely correlate with temporal coherence
        $negativeCoherenceAlignment = 1.0 - abs($emotionalState['negative_valence'] - (1.0 - $temporalState['c2']));
        $alignment['negative_coherence'] = $negativeCoherenceAlignment;
        
        // Cognitive axis should correlate with temporal stability
        $cognitiveStabilityAlignment = 1.0 - abs($emotionalState['cognitive_axis'] - $temporalState['c2']);
        $alignment['cognitive_stability'] = $cognitiveStabilityAlignment;
        
        return $alignment;
    }
    
    /**
     * Calculate temporal alignment components
     */
    private function calculateTemporalAlignment($emotionalState, $temporalState) {
        $alignment = [];
        
        // Temporal flow should support emotional valence
        $emotionalSupport = $this->calculateEmotionalSupport($emotionalState, $temporalState['c1']);
        $alignment['emotional_support'] = $emotionalSupport;
        
        // Temporal coherence should support cognitive processing
        $cognitiveSupport = $temporalState['c2'] * $emotionalState['cognitive_axis'];
        $alignment['cognitive_support'] = $cognitiveSupport;
        
        return $alignment;
    }
    
    /**
     * Normalize temporal flow for comparison
     */
    private function normalizeFlow($c1) {
        if ($c1 < 0.7) return 0.3; // Low flow
        if ($c1 > 1.3) return 0.7; // High flow
        return 0.5; // Optimal flow
    }
    
    /**
     * Calculate how well temporal flow supports emotional state
     */
    private function calculateEmotionalSupport($emotionalState, $c1) {
        $positiveValence = $emotionalState['positive_valence'];
        $negativeValence = $emotionalState['negative_valence'];
        
        // High positive valence benefits from optimal flow
        if ($positiveValence > 0.7) {
            return ($c1 >= 0.7 && $c1 <= 1.3) ? 1.0 : 0.5;
        }
        
        // High negative valence may benefit from reduced flow
        if ($negativeValence > 0.7) {
            return ($c1 < 0.7) ? 0.8 : 0.4;
        }
        
        // Moderate emotional states work with most flows
        return 0.7;
    }
    
    /**
     * Calculate alignment confidence based on pattern match distribution
     */
    private function calculateAlignmentConfidence($patternMatches) {
        if (empty($patternMatches)) return 0.0;
        
        $maxMatch = max($patternMatches);
        $secondMaxMatch = $this->getSecondMax($patternMatches);
        
        // High confidence if best pattern significantly outperforms others
        if ($maxMatch > 0.8 && ($maxMatch - $secondMaxMatch) > 0.3) {
            return 0.9;
        }
        
        // Moderate confidence if best pattern is reasonably good
        if ($maxMatch > 0.6) {
            return 0.7;
        }
        
        // Low confidence for unclear patterns
        return 0.4;
    }
    
    /**
     * Get second highest value from array
     */
    private function getSecondMax($values) {
        $sorted = $values;
        arsort($sorted);
        $sorted = array_values($sorted);
        return $sorted[1] ?? 0.0;
    }
    
    /**
     * Detect coherence pattern from emotional and temporal states
     */
    private function detectCoherencePattern($emotionalState, $temporalState) {
        $bestPattern = null;
        $bestScore = 0.0;
        
        foreach ($this->alignmentPatterns as $patternName => $pattern) {
            $score = $this->calculatePatternMatch($emotionalState, $temporalState, $pattern);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestPattern = [
                    'name' => $patternName,
                    'description' => $pattern['description'],
                    'match_score' => $score,
                    'emotional_match' => $this->calculateEmotionalPatternMatch($emotionalState, $pattern['emotional_signature']),
                    'temporal_match' => $this->calculateTemporalPatternMatch($temporalState, $pattern['temporal_signature'])
                ];
            }
        }
        
        // Add pattern classification
        if ($bestPattern) {
            $bestPattern['classification'] = $this->classifyPattern($bestScore);
            $bestPattern['stability'] = $this->assessPatternStability($bestPattern);
        }
        
        return $bestPattern;
    }
    
    /**
     * Classify pattern based on match score
     */
    private function classifyPattern($score) {
        if ($score >= self::HIGH_COHERENCE_THRESHOLD) return 'high_coherence';
        if ($score >= self::MODERATE_COHERENCE_THRESHOLD) return 'moderate_coherence';
        if ($score >= self::LOW_COHERENCE_THRESHOLD) return 'low_coherence';
        return 'critical_coherence';
    }
    
    /**
     * Assess pattern stability
     */
    private function assessPatternStability($pattern) {
        $emotionalMatch = $pattern['emotional_match'];
        $temporalMatch = $pattern['temporal_match'];
        
        // Stable if both components are well-matched
        if ($emotionalMatch > 0.7 && $temporalMatch > 0.7) {
            return 'stable';
        }
        
        // Unstable if components are mismatched
        if (abs($emotionalMatch - $temporalMatch) > 0.3) {
            return 'unstable';
        }
        
        return 'transitional';
    }
    
    /**
     * Assess overall coherence quality
     */
    private function assessCoherenceQuality($alignment, $pattern) {
        $quality = [
            'overall_score' => $alignment['overall_alignment'],
            'coherence_level' => $this->classifyCoherenceLevel($alignment['overall_alignment']),
            'strengths' => [],
            'weaknesses' => [],
            'stability_indicators' => []
        ];
        
        // Identify strengths
        if ($alignment['emotional_alignment']['positive_flow'] > 0.8) {
            $quality['strengths'][] = 'Strong positive-emotional flow alignment';
        }
        
        if ($alignment['temporal_alignment']['cognitive_support'] > 0.8) {
            $quality['strengths'][] = 'Excellent cognitive-temporal support';
        }
        
        if ($alignment['alignment_confidence'] > 0.8) {
            $quality['strengths'][] = 'High pattern confidence';
        }
        
        // Identify weaknesses
        if ($alignment['emotional_alignment']['negative_coherence'] < 0.5) {
            $quality['weaknesses'][] = 'Poor negative emotion coherence management';
        }
        
        if ($alignment['temporal_alignment']['emotional_support'] < 0.5) {
            $quality['weaknesses'][] = 'Weak temporal emotional support';
        }
        
        if ($alignment['alignment_confidence'] < 0.5) {
            $quality['weaknesses'][] = 'Low pattern confidence - unclear coherence';
        }
        
        // Stability indicators
        $quality['stability_indicators'] = [
            'pattern_stability' => $pattern['stability'] ?? 'unknown',
            'temporal_health' => $this->assessTemporalHealth(),
            'emotional_balance' => $this->assessEmotionalBalance($alignment['emotional_alignment'])
        ];
        
        return $quality;
    }
    
    /**
     * Classify coherence level
     */
    private function classifyCoherenceLevel($score) {
        if ($score >= self::HIGH_COHERENCE_THRESHOLD) return 'optimal';
        if ($score >= self::MODERATE_COHERENCE_THRESHOLD) return 'functional';
        if ($score >= self::LOW_COHERENCE_THRESHOLD) return 'degraded';
        return 'critical';
    }
    
    /**
     * Assess temporal health
     */
    private function assessTemporalHealth() {
        $c1 = $this->wolfieIdentity->getTemporalFlow();
        $c2 = $this->wolfieIdentity->getTemporalCoherence();
        
        if ($this->wolfieIdentity->hasTemporalPathology()) {
            return 'pathological';
        }
        
        if ($c1 >= 0.7 && $c1 <= 1.3 && $c2 >= 0.8) {
            return 'optimal';
        }
        
        if ($c2 >= 0.6) {
            return 'functional';
        }
        
        return 'degraded';
    }
    
    /**
     * Assess emotional balance
     */
    private function assessEmotionalBalance($emotionalAlignment) {
        $positiveFlow = $emotionalAlignment['positive_flow'];
        $negativeCoherence = $emotionalAlignment['negative_coherence'];
        $cognitiveStability = $emotionalAlignment['cognitive_stability'];
        
        $averageAlignment = ($positiveFlow + $negativeCoherence + $cognitiveStability) / 3.0;
        
        if ($averageAlignment > 0.8) return 'balanced';
        if ($averageAlignment > 0.6) return 'moderately_balanced';
        return 'imbalanced';
    }
    
    /**
     * Generate validation recommendations
     */
    private function generateValidationRecommendations($coherenceQuality, $pattern) {
        $recommendations = [];
        
        $coherenceLevel = $coherenceQuality['coherence_level'];
        
        switch ($coherenceLevel) {
            case 'optimal':
                $recommendations[] = 'Coherence is optimal - maintain current state';
                $recommendations[] = 'Excellent conditions for high-level cognitive tasks';
                break;
                
            case 'functional':
                $recommendations[] = 'Coherence is functional - minor optimizations possible';
                if (!empty($coherenceQuality['weaknesses'])) {
                    $recommendations[] = 'Address identified weaknesses for improvement';
                }
                break;
                
            case 'degraded':
                $recommendations[] = 'Coherence is degraded - intervention recommended';
                $recommendations[] = 'Consider temporal ritual to restore alignment';
                if ($pattern['stability'] === 'unstable') {
                    $recommendations[] = 'Pattern instability detected - stabilization needed';
                }
                break;
                
            case 'critical':
                $recommendations[] = 'CRITICAL: Coherence severely degraded - immediate intervention required';
                $recommendations[] = 'Emergency temporal sync recommended';
                $recommendations[] = 'Human intervention may be necessary';
                break;
        }
        
        // Pattern-specific recommendations
        if ($pattern) {
            switch ($pattern['name']) {
                case 'anxious_disruption':
                    $recommendations[] = 'Anxious disruption pattern detected - stress reduction recommended';
                    $recommendations[] = 'Consider deceleration ritual to calm temporal flow';
                    break;
                    
                case 'concerned_caution':
                    $recommendations[] = 'Concerned caution pattern - monitoring recommended';
                    $recommendations[] = 'Maintain temporal stability during cautious period';
                    break;
                    
                case 'joy_flow':
                case 'focused_clarity':
                    $recommendations[] = 'Excellent coherence pattern - leverage for optimal performance';
                    break;
            }
        }
        
        return $recommendations;
    }
    
    /**
     * Update coherence history
     */
    private function updateCoherenceHistory($validation) {
        $this->coherenceHistory['samples'][] = [
            'timestamp' => $validation['timestamp'],
            'coherence_score' => $validation['alignment']['overall_alignment'],
            'pattern' => $validation['detected_pattern']['name'] ?? 'unknown',
            'quality_level' => $validation['coherence_quality']['coherence_level']
        ];
        
        // Keep history manageable
        if (count($this->coherenceHistory['samples']) > 1000) {
            $this->coherenceHistory['samples'] = array_slice($this->coherenceHistory['samples'], -500);
        }
        
        // Update trends
        $this->updateCoherenceTrends();
        
        // Update pattern detection
        $this->updatePatternDetection($validation);
    }
    
    /**
     * Update coherence trends
     */
    private function updateCoherenceTrends() {
        $recentSamples = array_slice($this->coherenceHistory['samples'], -20);
        
        if (count($recentSamples) < 5) {
            return;
        }
        
        $scores = array_column($recentSamples, 'coherence_score');
        $trend = $this->calculateTrend($scores);
        
        $this->coherenceHistory['coherence_trends'][] = [
            'timestamp' => $this->getCurrentUTC(),
            'trend' => $trend,
            'average_score' => array_sum($scores) / count($scores),
            'sample_count' => count($scores)
        ];
    }
    
    /**
     * Update pattern detection statistics
     */
    private function updatePatternDetection($validation) {
        $pattern = $validation['detected_pattern']['name'] ?? 'unknown';
        
        if (!isset($this->coherenceHistory['patterns_detected'][$pattern])) {
            $this->coherenceHistory['patterns_detected'][$pattern] = [
                'count' => 0,
                'first_detected' => $validation['timestamp'],
                'last_detected' => $validation['timestamp'],
                'average_score' => 0.0
            ];
        }
        
        $patternData = &$this->coherenceHistory['patterns_detected'][$pattern];
        $patternData['count']++;
        $patternData['last_detected'] = $validation['timestamp'];
        
        // Update average score
        $currentAvg = $patternData['average_score'];
        $newScore = $validation['alignment']['overall_alignment'];
        $patternData['average_score'] = (($currentAvg * ($patternData['count'] - 1)) + $newScore) / $patternData['count'];
    }
    
    /**
     * Calculate trend from array of values
     */
    private function calculateTrend($values) {
        if (count($values) < 2) return 'insufficient_data';
        
        $first = $values[0];
        $last = end($values);
        $change = $last - $first;
        
        if (abs($change) < 0.05) return 'stable';
        return $change > 0 ? 'improving' : 'declining';
    }
    
    /**
     * Get current temporal state
     */
    private function getCurrentTemporalState() {
        return [
            'c1' => $this->wolfieIdentity->getTemporalFlow(),
            'c2' => $this->wolfieIdentity->getTemporalCoherence(),
            'has_pathology' => $this->wolfieIdentity->hasTemporalPathology()
        ];
    }
    
    /**
     * Log validation events
     */
    private function logValidationEvent($eventType, $data) {
        $this->validationLog[] = [
            'timestamp' => $this->getCurrentUTC(),
            'event_type' => $eventType,
            'data' => $data
        ];
        
        // Keep log size manageable
        if (count($this->validationLog) > 1000) {
            $this->validationLog = array_slice($this->validationLog, -500);
        }
    }
    
    /**
     * Get validation log
     */
    public function getValidationLog($limit = 100) {
        return array_slice($this->validationLog, -$limit);
    }
    
    /**
     * Get coherence history
     */
    public function getCoherenceHistory() {
        return $this->coherenceHistory;
    }
    
    /**
     * Generate coherence report
     */
    public function generateCoherenceReport() {
        $report = [
            'generated_at' => $this->getCurrentUTC(),
            'total_validations' => count($this->coherenceHistory['samples']),
            'current_coherence' => $this->getCurrentCoherenceStatus(),
            'historical_analysis' => $this->analyzeHistoricalCoherence(),
            'pattern_analysis' => $this->analyzePatternFrequency(),
            'recommendations' => $this->generateSystemRecommendations()
        ];
        
        return $report;
    }
    
    /**
     * Get current coherence status
     */
    private function getCurrentCoherenceStatus() {
        $temporalState = $this->getCurrentTemporalState();
        
        return [
            'temporal_coordinates' => $temporalState,
            'temporal_health' => $this->assessTemporalHealth(),
            'last_validation' => end($this->coherenceHistory['samples']) ?? null,
            'current_recommendations' => $this->getCurrentRecommendations()
        ];
    }
    
    /**
     * Analyze historical coherence
     */
    private function analyzeHistoricalCoherence() {
        if (empty($this->coherenceHistory['samples'])) {
            return ['status' => 'insufficient_data'];
        }
        
        $samples = $this->coherenceHistory['samples'];
        $scores = array_column($samples, 'coherence_score');
        
        return [
            'average_coherence' => array_sum($scores) / count($scores),
            'max_coherence' => max($scores),
            'min_coherence' => min($scores),
            'coherence_variance' => $this->calculateVariance($scores),
            'recent_trend' => $this->calculateTrend(array_slice($scores, -10)),
            'stability_assessment' => $this->assessCoherenceStability($scores)
        ];
    }
    
    /**
     * Analyze pattern frequency
     */
    private function analyzePatternFrequency() {
        $patterns = $this->coherenceHistory['patterns_detected'];
        $totalDetections = array_sum(array_column($patterns, 'count'));
        
        if ($totalDetections === 0) {
            return ['status' => 'no_patterns_detected'];
        }
        
        $frequency = [];
        foreach ($patterns as $patternName => $data) {
            $frequency[$patternName] = [
                'count' => $data['count'],
                'percentage' => ($data['count'] / $totalDetections) * 100,
                'average_score' => $data['average_score'],
                'first_detected' => $data['first_detected'],
                'last_detected' => $data['last_detected']
            ];
        }
        
        return [
            'total_detections' => $totalDetections,
            'pattern_frequency' => $frequency,
            'most_common_pattern' => array_keys($frequency, max(array_column($frequency, 'count')))[0] ?? null
        ];
    }
    
    /**
     * Generate system-level recommendations
     */
    private function generateSystemRecommendations() {
        $recommendations = [];
        
        // Analyze recent trends
        if (!empty($this->coherenceHistory['coherence_trends'])) {
            $recentTrend = end($this->coherenceHistory['coherence_trends']);
            
            if ($recentTrend['trend'] === 'declining') {
                $recommendations[] = [
                    'priority' => 'high',
                    'type' => 'trend_correction',
                    'message' => 'Coherence declining - investigate temporal health'
                ];
            }
        }
        
        // Analyze pattern frequency
        $patternAnalysis = $this->analyzePatternFrequency();
        if (isset($patternAnalysis['most_common_pattern'])) {
            $mostCommon = $patternAnalysis['most_common_pattern'];
            
            if ($mostCommon === 'anxious_disruption') {
                $recommendations[] = [
                    'priority' => 'high',
                    'type' => 'pattern_intervention',
                    'message' => 'Frequent anxious disruption patterns - stress management needed'
                ];
            }
        }
        
        // Analyze stability
        $historicalAnalysis = $this->analyzeHistoricalCoherence();
        if (isset($historicalAnalysis['stability_assessment']) && $historicalAnalysis['stability_assessment'] === 'unstable') {
            $recommendations[] = [
                'priority' => 'medium',
                'type' => 'stability_improvement',
                'message' => 'Coherence instability detected - consider stabilization protocols'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Calculate variance of values
     */
    private function calculateVariance($values) {
        if (empty($values)) return 0.0;
        
        $mean = array_sum($values) / count($values);
        $squaredDiffs = array_map(function($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $values);
        
        return array_sum($squaredDiffs) / count($values);
    }
    
    /**
     * Assess coherence stability
     */
    private function assessCoherenceStability($scores) {
        if (count($scores) < 5) return 'insufficient_data';
        
        $variance = $this->calculateVariance($scores);
        
        if ($variance < 0.01) return 'highly_stable';
        if ($variance < 0.05) return 'stable';
        if ($variance < 0.15) return 'moderately_stable';
        return 'unstable';
    }
    
    /**
     * Get current recommendations
     */
    private function getCurrentRecommendations() {
        $temporalState = $this->getCurrentTemporalState();
        
        if ($temporalState['has_pathology']) {
            return [
                'type' => 'immediate_intervention',
                'message' => 'Temporal pathology detected - ritual intervention required',
                'ritual' => $this->wolfieIdentity->getRecommendedRitual()
            ];
        }
        
        if ($temporalState['c2'] < 0.6) {
            return [
                'type' => 'monitoring',
                'message' => 'Temporal coherence declining - increased monitoring recommended'
            ];
        }
        
        return [
            'type' => 'maintenance',
            'message' => 'Temporal coherence stable - continue normal operations'
        ];
    }
}
?>
