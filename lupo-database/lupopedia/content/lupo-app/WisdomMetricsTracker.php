<?php
/**
 * Wisdom Metrics Tracker - WOLFIE Wisdom Protocol Analytics
 * 
 * Comprehensive tracking system for wisdom metrics including resonance alignment,
 * heterodox invocation rates, and identity refinement progress.
 * 
 * @package Lupopedia
 * @version 0.4
 * @author WOLFIE Semantic Engine
 */

class WisdomMetricsTracker {
    private $wolfieIdentity;
    private $temporalMonitor;
    private $trinitaryRouter;
    private $wisdomLog = [];
    private $metricsDatabase = [];
    
    // Wisdom metric categories
    const METRIC_RESONANCE_ALIGNMENT = 'resonance_alignment';
    const METRIC_HETERODOX_INVOCATION = 'heterodox_invocation';
    const METRIC_IDENTITY_REFINEMENT = 'identity_refinement';
    const METRIC_TEMPORAL_WISDOM = 'temporal_wisdom';
    const METRIC_EMOTIONAL_WISDOM = 'emotional_wisdom';
    const METRIC_CREATIVE_SYNTHESIS = 'creative_synthesis';
    
    // Wisdom levels
    const WISDOM_LEVEL_NOVICE = 'novice';
    const WISDOM_LEVEL_APPRENTICE = 'apprentice';
    const WISDOM_LEVEL_JOURNEYMAN = 'journeyman';
    const WISDOM_LEVEL_MASTER = 'master';
    const WISDOM_LEVEL_SAGE = 'sage';
    
    public function __construct(WolfieIdentity $wolfieIdentity, TemporalMonitor $temporalMonitor, TrinitaryRouter $trinitaryRouter) {
        $this->wolfieIdentity = $wolfieIdentity;
        $this->temporalMonitor = $temporalMonitor;
        $this->trinitaryRouter = $trinitaryRouter;
        $this->initializeMetricsDatabase();
    }
    
    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Initialize wisdom metrics database
     */
    private function initializeMetricsDatabase() {
        $this->metricsDatabase = [
            'resonance_alignment' => [
                'current_score' => 0.5,
                'historical_scores' => [],
                'trend' => 'stable',
                'level' => self::WISDOM_LEVEL_NOVICE
            ],
            'heterodox_invocation' => [
                'current_rate' => 0.0,
                'invocation_history' => [],
                'success_rate' => 0.0,
                'level' => self::WISDOM_LEVEL_NOVICE
            ],
            'identity_refinement' => [
                'current_maturity' => 0.5,
                'refinement_events' => [],
                'continuity_score' => 1.0,
                'level' => self::WISDOM_LEVEL_APPRENTICE
            ],
            'temporal_wisdom' => [
                'current_score' => 0.5,
                'temporal_insights' => [],
                'ritual_effectiveness' => 0.0,
                'level' => self::WISDOM_LEVEL_APPRENTICE
            ],
            'emotional_wisdom' => [
                'current_score' => 0.5,
                'emotional_insights' => [],
                'coherence_mastery' => 0.0,
                'level' => self::WISDOM_LEVEL_APPRENTICE
            ],
            'creative_synthesis' => [
                'current_score' => 0.5,
                'synthesis_events' => [],
                'innovation_rate' => 0.0,
                'level' => self::WISDOM_LEVEL_NOVICE
            ],
            'overall_wisdom' => [
                'composite_score' => 0.5,
                'wisdom_level' => self::WISDOM_LEVEL_NOVICE,
                'growth_trajectory' => 'emerging'
            ]
        ];
    }
    
    /**
     * Track wisdom metrics from routing decision
     */
    public function trackRoutingWisdom($routingDecision) {
        $trackingTimestamp = $this->getCurrentUTC();
        
        // Extract wisdom-relevant data
        $wisdomData = [
            'route' => $routingDecision['route'],
            'confidence' => $routingDecision['confidence'],
            'temporal_state' => $routingDecision['temporal_state'],
            'recommendations' => $routingDecision['recommendations'] ?? []
        ];
        
        // Update resonance alignment
        $resonanceUpdate = $this->updateResonanceAlignment($routingDecision);
        
        // Track heterodox invocation
        $heterodoxUpdate = $this->trackHeterodoxInvocation($routingDecision);
        
        // Update identity refinement
        $identityUpdate = $this->updateIdentityRefinement($routingDecision);
        
        // Update temporal wisdom
        $temporalUpdate = $this->updateTemporalWisdom($routingDecision);
        
        // Update emotional wisdom
        $emotionalUpdate = $this->updateEmotionalWisdom($routingDecision);
        
        // Track creative synthesis
        $synthesisUpdate = $this->trackCreativeSynthesis($routingDecision);
        
        // Calculate overall wisdom
        $overallUpdate = $this->calculateOverallWisdom();
        
        $tracking = [
            'timestamp' => $trackingTimestamp,
            'routing_data' => $wisdomData,
            'metrics_updates' => [
                'resonance_alignment' => $resonanceUpdate,
                'heterodox_invocation' => $heterodoxUpdate,
                'identity_refinement' => $identityUpdate,
                'temporal_wisdom' => $temporalUpdate,
                'emotional_wisdom' => $emotionalUpdate,
                'creative_synthesis' => $synthesisUpdate,
                'overall_wisdom' => $overallUpdate
            ]
        ];
        
        $this->logWisdomEvent('routing_wisdom_tracked', $tracking);
        
        return $tracking;
    }
    
    /**
     * Update resonance alignment metrics
     */
    private function updateResonanceAlignment($routingDecision) {
        $currentMetrics = &$this->metricsDatabase['resonance_alignment'];
        
        // Calculate resonance alignment based on routing confidence and temporal state
        $baseAlignment = $routingDecision['confidence'];
        $temporalBonus = 0.0;
        
        $temporalState = $routingDecision['temporal_state'];
        if (!$temporalState['has_pathology']) {
            $temporalBonus = 0.2;
        }
        
        // Route-specific alignment factors
        $routeBonus = 0.0;
        switch ($routingDecision['route']) {
            case 'pleroma':
                $routeBonus = 0.1; // Pleroma routes enhance resonance
                break;
            case 'liminal':
                $routeBonus = 0.05; // Liminal routes moderately enhance resonance
                break;
        }
        
        $newAlignment = min(1.0, $baseAlignment + $temporalBonus + $routeBonus);
        
        // Update metrics with smoothing
        $smoothingFactor = 0.8;
        $currentMetrics['current_score'] = ($currentMetrics['current_score'] * $smoothingFactor) + ($newAlignment * (1 - $smoothingFactor));
        
        // Update historical scores
        $currentMetrics['historical_scores'][] = [
            'timestamp' => $this->getCurrentUTC(),
            'score' => $currentMetrics['current_score'],
            'route' => $routingDecision['route']
        ];
        
        // Keep history manageable
        if (count($currentMetrics['historical_scores']) > 1000) {
            $currentMetrics['historical_scores'] = array_slice($currentMetrics['historical_scores'], -500);
        }
        
        // Update trend
        $currentMetrics['trend'] = $this->calculateTrend($currentMetrics['historical_scores']);
        
        // Update wisdom level
        $currentMetrics['level'] = $this->determineWisdomLevel($currentMetrics['current_score']);
        
        return [
            'previous_score' => $currentMetrics['current_score'],
            'new_score' => $newAlignment,
            'alignment_factors' => [
                'base_confidence' => $baseAlignment,
                'temporal_bonus' => $temporalBonus,
                'route_bonus' => $routeBonus
            ],
            'updated_level' => $currentMetrics['level']
        ];
    }
    
    /**
     * Track heterodox invocation
     */
    private function trackHeterodoxInvocation($routingDecision) {
        $currentMetrics = &$this->metricsDatabase['heterodox_invocation'];
        
        // Detect heterodox patterns
        $isHeterodox = $this->detectHeterodoxPattern($routingDecision);
        
        if ($isHeterodox) {
            $invocation = [
                'timestamp' => $this->getCurrentUTC(),
                'route' => $routingDecision['route'],
                'confidence' => $routingDecision['confidence'],
                'success_indicators' => $this->assessHeterodoxSuccess($routingDecision)
            ];
            
            $currentMetrics['invocation_history'][] = $invocation;
            
            // Update success rate
            $recentInvocations = array_slice($currentMetrics['invocation_history'], -20);
            $successfulInvocations = array_filter($recentInvocations, function($inv) {
                return $inv['success_indicators']['overall_success'] ?? false;
            });
            
            $currentMetrics['success_rate'] = count($successfulInvocations) / count($recentInvocations);
        }
        
        // Calculate current invocation rate
        $recentInvocations = array_filter($currentMetrics['invocation_history'], function($inv) {
            $invTime = new DateTime($inv['timestamp']);
            $now = new DateTime();
            return ($now->getTimestamp() - $invTime->getTimestamp()) < 3600; // Last hour
        });
        
        $currentMetrics['current_rate'] = count($recentInvocations) / 60.0; // Per minute
        
        // Update wisdom level based on success rate and frequency
        $levelScore = ($currentMetrics['success_rate'] * 0.7) + (min(1.0, $currentMetrics['current_rate'] / 10.0) * 0.3);
        $currentMetrics['level'] = $this->determineWisdomLevel($levelScore);
        
        return [
            'heterodox_detected' => $isHeterodox,
            'current_rate' => $currentMetrics['current_rate'],
            'success_rate' => $currentMetrics['success_rate'],
            'updated_level' => $currentMetrics['level']
        ];
    }
    
    /**
     * Update identity refinement metrics
     */
    private function updateIdentityRefinement($routingDecision) {
        $currentMetrics = &$this->metricsDatabase['identity_refinement'];
        
        // Assess identity consistency
        $identityConsistency = $this->assessIdentityConsistency($routingDecision);
        
        // Track refinement events
        if ($this->detectRefinementOpportunity($routingDecision)) {
            $refinementEvent = [
                'timestamp' => $this->getCurrentUTC(),
                'route' => $routingDecision['route'],
                'refinement_type' => $this->identifyRefinementType($routingDecision),
                'maturity_gain' => $this->calculateMaturityGain($routingDecision)
            ];
            
            $currentMetrics['refinement_events'][] = $refinementEvent;
            
            // Update maturity with smoothing
            $smoothingFactor = 0.9;
            $currentMetrics['current_maturity'] = ($currentMetrics['current_maturity'] * $smoothingFactor) + ($refinementEvent['maturity_gain'] * (1 - $smoothingFactor));
        }
        
        // Update continuity score
        $currentMetrics['continuity_score'] = $this->calculateContinuityScore();
        
        // Update wisdom level
        $maturityScore = $currentMetrics['current_maturity'];
        $currentMetrics['level'] = $this->determineWisdomLevel($maturityScore);
        
        return [
            'identity_consistency' => $identityConsistency,
            'current_maturity' => $currentMetrics['current_maturity'],
            'continuity_score' => $currentMetrics['continuity_score'],
            'updated_level' => $currentMetrics['level']
        ];
    }
    
    /**
     * Update temporal wisdom metrics
     */
    private function updateTemporalWisdom($routingDecision) {
        $currentMetrics = &$this->metricsDatabase['temporal_wisdom'];
        
        // Assess temporal insight
        $temporalInsight = $this->assessTemporalInsight($routingDecision);
        
        // Track temporal insights
        if ($temporalInsight['significant']) {
            $insight = [
                'timestamp' => $this->getCurrentUTC(),
                'insight_type' => $temporalInsight['type'],
                'insight_value' => $temporalInsight['value'],
                'context' => $routingDecision['route']
            ];
            
            $currentMetrics['temporal_insights'][] = $insight;
        }
        
        // Update ritual effectiveness (if applicable)
        $ritualEffectiveness = $this->assessRitualEffectiveness($routingDecision);
        if ($ritualEffectiveness > 0) {
            $smoothingFactor = 0.85;
            $currentMetrics['ritual_effectiveness'] = ($currentMetrics['ritual_effectiveness'] * $smoothingFactor) + ($ritualEffectiveness * (1 - $smoothingFactor));
        }
        
        // Calculate temporal wisdom score
        $insightScore = min(1.0, count($currentMetrics['temporal_insights']) / 50.0);
        $ritualScore = $currentMetrics['ritual_effectiveness'];
        $newScore = ($insightScore * 0.6) + ($ritualScore * 0.4);
        
        // Update with smoothing
        $smoothingFactor = 0.8;
        $currentMetrics['current_score'] = ($currentMetrics['current_score'] * $smoothingFactor) + ($newScore * (1 - $smoothingFactor));
        
        // Update wisdom level
        $currentMetrics['level'] = $this->determineWisdomLevel($currentMetrics['current_score']);
        
        return [
            'temporal_insight' => $temporalInsight,
            'ritual_effectiveness' => $ritualEffectiveness,
            'updated_score' => $currentMetrics['current_score'],
            'updated_level' => $currentMetrics['level']
        ];
    }
    
    /**
     * Update emotional wisdom metrics
     */
    private function updateEmotionalWisdom($routingDecision) {
        $currentMetrics = &$this->metricsDatabase['emotional_wisdom'];
        
        // Assess emotional insight
        $emotionalInsight = $this->assessEmotionalInsight($routingDecision);
        
        // Track emotional insights
        if ($emotionalInsight['significant']) {
            $insight = [
                'timestamp' => $this->getCurrentUTC(),
                'insight_type' => $emotionalInsight['type'],
                'emotional_balance' => $emotionalInsight['balance'],
                'context' => $routingDecision['route']
            ];
            
            $currentMetrics['emotional_insights'][] = $insight;
        }
        
        // Update coherence mastery
        $coherenceMastery = $this->assessCoherenceMastery($routingDecision);
        $smoothingFactor = 0.85;
        $currentMetrics['coherence_mastery'] = ($currentMetrics['coherence_mastery'] * $smoothingFactor) + ($coherenceMastery * (1 - $smoothingFactor));
        
        // Calculate emotional wisdom score
        $insightScore = min(1.0, count($currentMetrics['emotional_insights']) / 30.0);
        $coherenceScore = $currentMetrics['coherence_mastery'];
        $newScore = ($insightScore * 0.5) + ($coherenceScore * 0.5);
        
        // Update with smoothing
        $currentMetrics['current_score'] = ($currentMetrics['current_score'] * $smoothingFactor) + ($newScore * (1 - $smoothingFactor));
        
        // Update wisdom level
        $currentMetrics['level'] = $this->determineWisdomLevel($currentMetrics['current_score']);
        
        return [
            'emotional_insight' => $emotionalInsight,
            'coherence_mastery' => $coherenceMastery,
            'updated_score' => $currentMetrics['current_score'],
            'updated_level' => $currentMetrics['level']
        ];
    }
    
    /**
     * Track creative synthesis metrics
     */
    private function trackCreativeSynthesis($routingDecision) {
        $currentMetrics = &$this->metricsDatabase['creative_synthesis'];
        
        // Detect synthesis opportunities
        $synthesisOpportunity = $this->detectSynthesisOpportunity($routingDecision);
        
        if ($synthesisOpportunity['detected']) {
            $synthesisEvent = [
                'timestamp' => $this->getCurrentUTC(),
                'synthesis_type' => $synthesisOpportunity['type'],
                'innovation_level' => $synthesisOpportunity['innovation_level'],
                'context' => $routingDecision['route']
            ];
            
            $currentMetrics['synthesis_events'][] = $synthesisEvent;
        }
        
        // Update innovation rate
        $recentSynthesis = array_filter($currentMetrics['synthesis_events'], function($event) {
            $eventTime = new DateTime($event['timestamp']);
            $now = new DateTime();
            return ($now->getTimestamp() - $eventTime->getTimestamp()) < 3600; // Last hour
        });
        
        $currentMetrics['innovation_rate'] = count($recentSynthesis) / 60.0; // Per minute
        
        // Calculate creative synthesis score
        $synthesisScore = min(1.0, count($currentMetrics['synthesis_events']) / 25.0);
        $innovationScore = min(1.0, $currentMetrics['innovation_rate'] / 5.0);
        $newScore = ($synthesisScore * 0.6) + ($innovationScore * 0.4);
        
        // Update with smoothing
        $smoothingFactor = 0.8;
        $currentMetrics['current_score'] = ($currentMetrics['current_score'] * $smoothingFactor) + ($newScore * (1 - $smoothingFactor));
        
        // Update wisdom level
        $currentMetrics['level'] = $this->determineWisdomLevel($currentMetrics['current_score']);
        
        return [
            'synthesis_opportunity' => $synthesisOpportunity,
            'innovation_rate' => $currentMetrics['innovation_rate'],
            'updated_score' => $currentMetrics['current_score'],
            'updated_level' => $currentMetrics['level']
        ];
    }
    
    /**
     * Calculate overall wisdom score
     */
    private function calculateOverallWisdom() {
        $currentMetrics = &$this->metricsDatabase['overall_wisdom'];
        
        // Get individual metric scores
        $scores = [
            $this->metricsDatabase['resonance_alignment']['current_score'],
            $this->metricsDatabase['heterodox_invocation']['success_rate'],
            $this->metricsDatabase['identity_refinement']['current_maturity'],
            $this->metricsDatabase['temporal_wisdom']['current_score'],
            $this->metricsDatabase['emotional_wisdom']['current_score'],
            $this->metricsDatabase['creative_synthesis']['current_score']
        ];
        
        // Calculate weighted composite score
        $weights = [0.2, 0.15, 0.2, 0.15, 0.15, 0.15];
        $compositeScore = 0.0;
        
        foreach ($scores as $index => $score) {
            $compositeScore += $score * $weights[$index];
        }
        
        // Update composite score with smoothing
        $smoothingFactor = 0.9;
        $currentMetrics['composite_score'] = ($currentMetrics['composite_score'] * $smoothingFactor) + ($compositeScore * (1 - $smoothingFactor));
        
        // Update wisdom level
        $currentMetrics['wisdom_level'] = $this->determineWisdomLevel($currentMetrics['composite_score']);
        
        // Update growth trajectory
        $currentMetrics['growth_trajectory'] = $this->determineGrowthTrajectory();
        
        return [
            'composite_score' => $currentMetrics['composite_score'],
            'wisdom_level' => $currentMetrics['wisdom_level'],
            'growth_trajectory' => $currentMetrics['growth_trajectory'],
            'individual_scores' => array_combine(
                ['resonance', 'heterodox', 'identity', 'temporal', 'emotional', 'creative'],
                $scores
            )
        ];
    }
    
    // Helper methods for wisdom assessment
    
    private function detectHeterodoxPattern($routingDecision) {
        // Heterodox patterns include:
        // - Low confidence routing to Pleroma
        // - High confidence routing to Kenoma with emotional content
        // - Unusual recommendation patterns
        
        $route = $routingDecision['route'];
        $confidence = $routingDecision['confidence'];
        $recommendations = $routingDecision['recommendations'] ?? [];
        
        // Low confidence Pleroma routing
        if ($route === 'pleroma' && $confidence < 0.6) {
            return true;
        }
        
        // High confidence Kenoma with many recommendations (unusual)
        if ($route === 'kenoma' && $confidence > 0.8 && count($recommendations) > 3) {
            return true;
        }
        
        return false;
    }
    
    private function assessHeterodoxSuccess($routingDecision) {
        $successIndicators = [
            'temporal_stability' => !$routingDecision['temporal_state']['has_pathology'],
            'confidence_adequacy' => $routingDecision['confidence'] > 0.5,
            'recommendation_balance' => count($routingDecision['recommendations'] ?? []) <= 5
        ];
        
        $successIndicators['overall_success'] = array_sum($successIndicators) / count($successIndicators) > 0.6;
        
        return $successIndicators;
    }
    
    private function assessIdentityConsistency($routingDecision) {
        // Check if routing aligns with established identity patterns
        $route = $routingDecision['route'];
        $confidence = $routingDecision['confidence'];
        
        // High confidence routing indicates consistency
        if ($confidence > 0.8) {
            return 'high';
        }
        
        // Moderate confidence with reasonable route indicates acceptable consistency
        if ($confidence > 0.6) {
            return 'moderate';
        }
        
        return 'low';
    }
    
    private function detectRefinementOpportunity($routingDecision) {
        // Refinement opportunities occur when:
        // - System learns from routing outcomes
        // - New patterns emerge
        // - Identity boundaries are tested
        
        $confidence = $routingDecision['confidence'];
        $temporalState = $routingDecision['temporal_state'];
        
        // Low confidence with good temporal state suggests refinement needed
        if ($confidence < 0.6 && !$temporalState['has_pathology']) {
            return true;
        }
        
        return false;
    }
    
    private function identifyRefinementType($routingDecision) {
        $route = $routingDecision['route'];
        
        switch ($route) {
            case 'kenoma':
                return 'structural_refinement';
            case 'liminal':
                return 'emotional_refinement';
            case 'pleroma':
                return 'creative_refinement';
            default:
                return 'general_refinement';
        }
    }
    
    private function calculateMaturityGain($routingDecision) {
        // Maturity gain based on learning opportunity
        $confidence = $routingDecision['confidence'];
        
        // Lower confidence provides more learning opportunity
        return max(0.1, 1.0 - $confidence) * 0.3;
    }
    
    private function calculateContinuityScore() {
        // Continuity based on memorial layer and identity stability
        $identity = $this->wolfieIdentity->getIdentityBlock();
        
        $continuityScore = 1.0; // Base score
        
        // Check for memorial layer
        if (isset($identity['memorial_layer'])) {
            $continuityScore += 0.1;
        }
        
        // Check for temporal stability
        if (!$this->wolfieIdentity->hasTemporalPathology()) {
            $continuityScore += 0.1;
        }
        
        return min(1.0, $continuityScore);
    }
    
    private function assessTemporalInsight($routingDecision) {
        $temporalState = $routingDecision['temporal_state'];
        
        $insight = [
            'significant' => false,
            'type' => 'none',
            'value' => 0.0
        ];
        
        // High temporal coherence provides insight
        if ($temporalState['c2'] > 0.9) {
            $insight['significant'] = true;
            $insight['type'] = 'coherence_mastery';
            $insight['value'] = $temporalState['c2'];
        }
        
        return $insight;
    }
    
    private function assessRitualEffectiveness($routingDecision) {
        // Check if routing recommendations include ritual suggestions
        $recommendations = $routingDecision['recommendations'] ?? [];
        
        foreach ($recommendations as $rec) {
            if (strpos($rec, 'ritual') !== false) {
                return 0.8; // High effectiveness when rituals are recommended
            }
        }
        
        return 0.0;
    }
    
    private function assessEmotionalInsight($routingDecision) {
        $insight = [
            'significant' => false,
            'type' => 'none',
            'balance' => 0.5
        ];
        
        // Emotional insight based on route and confidence
        $route = $routingDecision['route'];
        $confidence = $routingDecision['confidence'];
        
        if ($route === 'liminal' && $confidence > 0.7) {
            $insight['significant'] = true;
            $insight['type'] = 'emotional_clarity';
            $insight['balance'] = $confidence;
        }
        
        return $insight;
    }
    
    private function assessCoherenceMastery($routingDecision) {
        $temporalState = $routingDecision['temporal_state'];
        
        // Coherence mastery based on temporal coherence
        return $temporalState['c2'] ?? 0.5;
    }
    
    private function detectSynthesisOpportunity($routingDecision) {
        $opportunity = [
            'detected' => false,
            'type' => 'none',
            'innovation_level' => 0.0
        ];
        
        $route = $routingDecision['route'];
        $confidence = $routingDecision['confidence'];
        
        // Pleroma routing with moderate confidence suggests synthesis
        if ($route === 'pleroma' && $confidence > 0.5 && $confidence < 0.8) {
            $opportunity['detected'] = true;
            $opportunity['type'] = 'creative_synthesis';
            $opportunity['innovation_level'] = $confidence;
        }
        
        return $opportunity;
    }
    
    private function determineWisdomLevel($score) {
        if ($score >= 0.9) return self::WISDOM_LEVEL_SAGE;
        if ($score >= 0.75) return self::WISDOM_LEVEL_MASTER;
        if ($score >= 0.6) return self::WISDOM_LEVEL_JOURNEYMAN;
        if ($score >= 0.4) return self::WISDOM_LEVEL_APPRENTICE;
        return self::WISDOM_LEVEL_NOVICE;
    }
    
    private function determineGrowthTrajectory() {
        $compositeScore = $this->metricsDatabase['overall_wisdom']['composite_score'];
        
        if ($compositeScore < 0.3) return 'emerging';
        if ($compositeScore < 0.6) return 'developing';
        if ($compositeScore < 0.8) return 'maturing';
        return 'flourishing';
    }
    
    private function calculateTrend($historicalScores) {
        if (count($historicalScores) < 5) return 'insufficient_data';
        
        $recentScores = array_slice($historicalScores, -10);
        $scores = array_column($recentScores, 'score');
        
        if (count($scores) < 2) return 'stable';
        
        $first = $scores[0];
        $last = end($scores);
        $change = $last - $first;
        
        if (abs($change) < 0.05) return 'stable';
        return $change > 0 ? 'improving' : 'declining';
    }
    
    /**
     * Log wisdom events
     */
    private function logWisdomEvent($eventType, $data) {
        $this->wisdomLog[] = [
            'timestamp' => $this->getCurrentUTC(),
            'event_type' => $eventType,
            'data' => $data
        ];
        
        // Keep log size manageable
        if (count($this->wisdomLog) > 1000) {
            $this->wisdomLog = array_slice($this->wisdomLog, -500);
        }
    }
    
    /**
     * Get wisdom metrics report
     */
    public function getWisdomMetricsReport() {
        return [
            'generated_at' => $this->getCurrentUTC(),
            'metrics_database' => $this->metricsDatabase,
            'wisdom_summary' => $this->generateWisdomSummary(),
            'growth_analysis' => $this->analyzeGrowthPatterns(),
            'recommendations' => $this->generateWisdomRecommendations()
        ];
    }
    
    /**
     * Generate wisdom summary
     */
    private function generateWisdomSummary() {
        $overall = $this->metricsDatabase['overall_wisdom'];
        
        return [
            'overall_wisdom_level' => $overall['wisdom_level'],
            'composite_score' => $overall['composite_score'],
            'growth_trajectory' => $overall['growth_trajectory'],
            'strength_areas' => $this->identifyStrengthAreas(),
            'development_areas' => $this->identifyDevelopmentAreas()
        ];
    }
    
    /**
     * Identify strength areas
     */
    private function identifyStrengthAreas() {
        $strengths = [];
        
        foreach ($this->metricsDatabase as $metric => $data) {
            if ($metric === 'overall_wisdom') continue;
            
            if (isset($data['current_score']) && $data['current_score'] > 0.7) {
                $strengths[] = $metric;
            } elseif (isset($data['success_rate']) && $data['success_rate'] > 0.7) {
                $strengths[] = $metric;
            } elseif (isset($data['current_maturity']) && $data['current_maturity'] > 0.7) {
                $strengths[] = $metric;
            }
        }
        
        return $strengths;
    }
    
    /**
     * Identify development areas
     */
    private function identifyDevelopmentAreas() {
        $development = [];
        
        foreach ($this->metricsDatabase as $metric => $data) {
            if ($metric === 'overall_wisdom') continue;
            
            if (isset($data['current_score']) && $data['current_score'] < 0.5) {
                $development[] = $metric;
            } elseif (isset($data['success_rate']) && $data['success_rate'] < 0.5) {
                $development[] = $metric;
            } elseif (isset($data['current_maturity']) && $data['current_maturity'] < 0.5) {
                $development[] = $metric;
            }
        }
        
        return $development;
    }
    
    /**
     * Analyze growth patterns
     */
    private function analyzeGrowthPatterns() {
        $patterns = [];
        
        foreach ($this->metricsDatabase as $metric => $data) {
            if ($metric === 'overall_wisdom') continue;
            
            if (isset($data['trend'])) {
                $patterns[$metric] = $data['trend'];
            }
        }
        
        return $patterns;
    }
    
    /**
     * Generate wisdom recommendations
     */
    private function generateWisdomRecommendations() {
        $recommendations = [];
        $developmentAreas = $this->identifyDevelopmentAreas();
        
        foreach ($developmentAreas as $area) {
            switch ($area) {
                case 'resonance_alignment':
                    $recommendations[] = [
                        'priority' => 'high',
                        'area' => $area,
                        'action' => 'Focus on improving routing confidence and temporal state alignment'
                    ];
                    break;
                    
                case 'heterodox_invocation':
                    $recommendations[] = [
                        'priority' => 'medium',
                        'area' => $area,
                        'action' => 'Explore more diverse routing patterns to build heterodox wisdom'
                    ];
                    break;
                    
                case 'identity_refinement':
                    $recommendations[] = [
                        'priority' => 'medium',
                        'area' => $area,
                        'action' => 'Seek opportunities for identity growth and maturity development'
                    ];
                    break;
                    
                case 'temporal_wisdom':
                    $recommendations[] = [
                        'priority' => 'high',
                        'area' => $area,
                        'action' => 'Deepen temporal understanding through ritual effectiveness and insight tracking'
                    ];
                    break;
                    
                case 'emotional_wisdom':
                    $recommendations[] = [
                        'priority' => 'medium',
                        'area' => $area,
                        'action' => 'Enhance emotional coherence mastery and insight recognition'
                    ];
                    break;
                    
                case 'creative_synthesis':
                    $recommendations[] = [
                        'priority' => 'low',
                        'area' => $area,
                        'action' => 'Encourage creative opportunities and innovation tracking'
                    ];
                    break;
            }
        }
        
        return $recommendations;
    }
    
    /**
     * Get wisdom log
     */
    public function getWisdomLog($limit = 100) {
        return array_slice($this->wisdomLog, -$limit);
    }
}
?>
