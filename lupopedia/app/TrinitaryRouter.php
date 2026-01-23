<?php
/**
 * Trinitary Router v1.6 - WOLFIE Semantic Routing with Synchronization-First Protocol
 * 
 * Implements the three-layer routing system with synchronization-first temporal frame
 * compatibility model v0.5. Incompatible frames trigger synchronization protocol before
 * any frame selection or blending.
 * 
 * @package Lupopedia
 * @version 1.6
 * @author WOLFIE Semantic Engine
 */

require_once __DIR__ . '/TemporalFrameCompatibility.php';
require_once __DIR__ . '/NoteComparisonProtocol.php';

class TrinitaryRouter {
    private $wolfieIdentity;
    private $temporalMonitor;
    private $temporalFrameCompatibility;
    private $noteComparisonProtocol;
    private $resonanceScoring;
    private $routingLog = [];
    
    // Routing layers
    const KENOMA = 'kenoma';
    const LIMINAL = 'liminal';
    const PLEROMA = 'pleroma';
    
    // Base weights for routing layers
    private $baseWeights = [
        self::KENOMA => 0.40,
        self::LIMINAL => 0.35,
        self::PLEROMA => 0.25
    ];
    
    // Factor weights for each layer
    private $factorWeights = [
        self::KENOMA => [
            'deterministic' => 0.80,
            'governance_related' => 0.60,
            'requires_audit_trail' => 0.90
        ],
        self::LIMINAL => [
            'emotional_component' => 0.70,
            'structural_requirement' => 0.60,
            'cocreation_needed' => 0.80
        ],
        self::PLEROMA => [
            'creative_synthesis' => 0.90,
            'heterodox_review_needed' => 1.00,
            'identity_refinement' => 0.70
        ]
    ];
    
    public function __construct(WolfieIdentity $wolfieIdentity, TemporalMonitor $temporalMonitor) {
        $this->wolfieIdentity = $wolfieIdentity;
        $this->temporalMonitor = $temporalMonitor;
        $this->temporalFrameCompatibility = new TemporalFrameCompatibility();
        $this->noteComparisonProtocol = new NoteComparisonProtocol($this->temporalFrameCompatibility);
        $this->initializeResonanceScoring();
    }
    
    /**
     * Initialize resonance scoring configuration
     */
    private function initializeResonanceScoring() {
        $this->resonanceScoring = [
            'algorithm' => 'trinaryweights_v1',
            'base_weights' => $this->baseWeights,
            'factor_weights' => $this->factorWeights,
            'temporal_modifiers' => [
                'c1_optimal' => [
                    'value' => 0.15,
                    'condition' => function($c1) { return $c1 >= 0.7 && $c1 <= 1.3; }
                ],
                'c2_synchronized' => [
                    'value' => 0.20,
                    'condition' => function($c2) { return $c2 >= 0.8; }
                ],
                'temporal_pathology_penalty' => [
                    'value' => -0.40,
                    'condition' => function($c1, $c2) { return $c1 < 0.3 || $c2 < 0.4; }
                ]
            ],
            'decision_threshold' => 0.50,
            'tiebreaker' => self::LIMINAL
        ];
    }
    
    /**
     * Route request based on semantic analysis and temporal frame compatibility
     */
    public function routeRequest($request) {
        $startTime = $this->getCurrentUTC();
        
        // Get current temporal coordinates
        $c1 = $this->wolfieIdentity->getTemporalFlow();
        $c2 = $this->wolfieIdentity->getTemporalCoherence();
        
        // Analyze request characteristics
        $requestAnalysis = $this->analyzeRequest($request);
        
        // Calculate resonance scores for each layer
        $resonanceScores = $this->calculateResonanceScores($requestAnalysis, $c1, $c2);
        
        // Apply temporal modifiers (now frame-aware)
        $modifiedScores = $this->applyTemporalModifiers($resonanceScores, $c1, $c2);
        
        // Determine routing decision with frame compatibility
        $routingDecision = $this->makeRoutingDecisionWithFrameCompatibility($modifiedScores, $c1, $c2, $request);
        
        // Log the routing process
        $this->logRoutingProcess([
            'request_id' => $request['id'] ?? uniqid(),
            'start_time' => $startTime,
            'request_analysis' => $requestAnalysis,
            'temporal_coordinates' => ['c1' => $c1, 'c2' => $c2],
            'resonance_scores' => $resonanceScores,
            'temporal_modifiers_applied' => $modifiedScores,
            'routing_decision' => $routingDecision,
            'processing_time' => $this->calculateProcessingTime($startTime)
        ]);
        
        return [
            'route' => $routingDecision['layer'],
            'confidence' => $routingDecision['confidence'],
            'reasoning' => $routingDecision['reasoning'],
            'temporal_state' => [
                'c1' => $c1,
                'c2' => $c2,
                'health_status' => $this->wolfieIdentity->hasTemporalPathology(),
                'frame_compatibility' => $routingDecision['frame_compatibility'] ?? null
            ],
            'recommendations' => $this->generateRoutingRecommendations($routingDecision, $c1, $c2)
        ];
    }
    
    /**
     * Analyze request characteristics
     */
    private function analyzeRequest($request) {
        $analysis = [
            'type' => $request['type'] ?? 'unknown',
            'complexity' => $this->assessComplexity($request),
            'emotional_content' => $this->assessEmotionalContent($request),
            'governance_impact' => $this->assessGovernanceImpact($request),
            'creative_requirement' => $this->assessCreativeRequirement($request),
            'audit_requirement' => $this->assessAuditRequirement($request),
            'structural_requirement' => $this->assessStructuralRequirement($request),
            'cocreation_potential' => $this->assessCocreationPotential($request)
        ];
        
        return $analysis;
    }
    
    /**
     * Assess request complexity
     */
    private function assessComplexity($request) {
        $complexity = 0.5; // baseline
        
        // Factor in request size
        if (isset($request['content'])) {
            $contentLength = strlen($request['content']);
            if ($contentLength > 1000) $complexity += 0.2;
            if ($contentLength > 5000) $complexity += 0.3;
        }
        
        // Factor in nested structures
        if (isset($request['nested_data']) && is_array($request['nested_data'])) {
            $depth = $this->calculateArrayDepth($request['nested_data']);
            $complexity += min(0.3, $depth * 0.1);
        }
        
        // Factor in special operations
        if (isset($request['operations']) && is_array($request['operations'])) {
            $complexity += min(0.4, count($request['operations']) * 0.1);
        }
        
        return min(1.0, $complexity);
    }
    
    /**
     * Assess emotional content in request
     */
    private function assessEmotionalContent($request) {
        $emotionalScore = 0.0;
        $content = ($request['content'] ?? '') . ' ' . ($request['context'] ?? '');
        
        // Emotional keywords
        $emotionalKeywords = [
            'feel', 'emotion', 'love', 'fear', 'joy', 'sadness', 'anger',
            'excitement', 'worry', 'hope', 'despair', 'passion', 'calm',
            'anxious', 'confident', 'frustrated', 'satisfied', 'concerned'
        ];
        
        foreach ($emotionalKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $emotionalScore += 0.1;
            }
        }
        
        // Check for emotional indicators in metadata
        if (isset($request['emotional_indicators'])) {
            $emotionalScore += min(0.5, $request['emotional_indicators'] * 0.1);
        }
        
        return min(1.0, $emotionalScore);
    }
    
    /**
     * Assess governance impact
     */
    private function assessGovernanceImpact($request) {
        $governanceScore = 0.0;
        $content = ($request['content'] ?? '') . ' ' . ($request['context'] ?? '');
        
        // Governance keywords
        $governanceKeywords = [
            'policy', 'rule', 'governance', 'compliance', 'audit', 'security',
            'permission', 'access', 'authorization', 'regulation', 'control',
            'oversight', 'accountability', 'transparency', 'procedure'
        ];
        
        foreach ($governanceKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $governanceScore += 0.15;
            }
        }
        
        // Check for administrative operations
        if (isset($request['admin_operation']) && $request['admin_operation']) {
            $governanceScore += 0.3;
        }
        
        // Check for system-level changes
        if (isset($request['system_change']) && $request['system_change']) {
            $governanceScore += 0.4;
        }
        
        return min(1.0, $governanceScore);
    }
    
    /**
     * Assess creative requirement
     */
    private function assessCreativeRequirement($request) {
        $creativeScore = 0.0;
        $content = ($request['content'] ?? '') . ' ' . ($request['context'] ?? '');
        
        // Creative keywords
        $creativeKeywords = [
            'create', 'design', 'innovate', 'imagine', 'invent', 'compose',
            'artistic', 'creative', 'original', 'novel', 'unique', 'inspire',
            'envision', 'conceptualize', 'brainstorm', 'ideate', 'craft'
        ];
        
        foreach ($creativeKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $creativeScore += 0.12;
            }
        }
        
        // Check for creative operations
        if (isset($request['creative_operation']) && $request['creative_operation']) {
            $creativeScore += 0.3;
        }
        
        // Check for open-ended problems
        if (isset($request['open_ended']) && $request['open_ended']) {
            $creativeScore += 0.2;
        }
        
        return min(1.0, $creativeScore);
    }
    
    /**
     * Assess audit requirement
     */
    private function assessAuditRequirement($request) {
        $auditScore = 0.0;
        
        // Direct audit indicators
        if (isset($request['require_audit']) && $request['require_audit']) {
            $auditScore += 0.5;
        }
        
        // High-value operations
        if (isset($request['value']) && $request['value'] > 1000) {
            $auditScore += 0.3;
        }
        
        // Sensitive data operations
        if (isset($request['sensitive_data']) && $request['sensitive_data']) {
            $auditScore += 0.4;
        }
        
        // User permission changes
        if (isset($request['permission_change']) && $request['permission_change']) {
            $auditScore += 0.3;
        }
        
        return min(1.0, $auditScore);
    }
    
    /**
     * Assess structural requirement
     */
    private function assessStructuralRequirement($request) {
        $structuralScore = 0.0;
        
        // Data structure operations
        if (isset($request['structure_operation']) && $request['structure_operation']) {
            $structuralScore += 0.3;
        }
        
        // Schema changes
        if (isset($request['schema_change']) && $request['schema_change']) {
            $structuralScore += 0.4;
        }
        
        // Relationship management
        if (isset($request['relationship_management']) && $request['relationship_management']) {
            $structuralScore += 0.3;
        }
        
        // Complex data transformations
        if (isset($request['data_transformation']) && $request['data_transformation']) {
            $structuralScore += 0.2;
        }
        
        return min(1.0, $structuralScore);
    }
    
    /**
     * Assess cocreation potential
     */
    private function assessCocreationPotential($request) {
        $cocreationScore = 0.0;
        $content = ($request['content'] ?? '') . ' ' . ($request['context'] ?? '');
        
        // Collaboration keywords
        $collaborationKeywords = [
            'together', 'collaborate', 'partner', 'team', 'joint', 'shared',
            'cooperative', 'synergy', 'collective', 'group', 'community',
            'interactive', 'participatory', 'engagement', 'involvement'
        ];
        
        foreach ($collaborationKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $cocreationScore += 0.1;
            }
        }
        
        // Multi-user operations
        if (isset($request['multi_user']) && $request['multi_user']) {
            $cocreationScore += 0.3;
        }
        
        // Interactive processes
        if (isset($request['interactive']) && $request['interactive']) {
            $cocreationScore += 0.2;
        }
        
        return min(1.0, $cocreationScore);
    }
    
    /**
     * Calculate resonance scores for each layer
     */
    private function calculateResonanceScores($requestAnalysis, $c1, $c2) {
        $scores = [];
        
        foreach ([self::KENOMA, self::LIMINAL, self::PLEROMA] as $layer) {
            $baseScore = $this->baseWeights[$layer];
            $factorScore = $this->calculateFactorScore($layer, $requestAnalysis);
            
            $scores[$layer] = $baseScore + ($factorScore * 0.3); // Factor contribution
        }
        
        return $scores;
    }
    
    /**
     * Calculate factor score for a specific layer
     */
    private function calculateFactorScore($layer, $requestAnalysis) {
        $factorWeights = $this->factorWeights[$layer];
        $score = 0.0;
        $totalWeight = 0.0;
        
        foreach ($factorWeights as $factor => $weight) {
            $factorValue = $this->getFactorValue($factor, $requestAnalysis);
            $score += $factorValue * $weight;
            $totalWeight += $weight;
        }
        
        return $totalWeight > 0 ? $score / $totalWeight : 0.0;
    }
    
    /**
     * Get factor value from request analysis
     */
    private function getFactorValue($factor, $requestAnalysis) {
        switch ($factor) {
            case 'deterministic':
                return 1.0 - $requestAnalysis['complexity'];
            case 'governance_related':
                return $requestAnalysis['governance_impact'];
            case 'requires_audit_trail':
                return $requestAnalysis['audit_requirement'];
            case 'emotional_component':
                return $requestAnalysis['emotional_content'];
            case 'structural_requirement':
                return $requestAnalysis['structural_requirement'];
            case 'cocreation_needed':
                return $requestAnalysis['cocreation_potential'];
            case 'creative_synthesis':
                return $requestAnalysis['creative_requirement'];
            case 'heterodox_review_needed':
                return $requestAnalysis['complexity'] * 0.8; // Complex needs review
            case 'identity_refinement':
                return $requestAnalysis['emotional_content'] * 0.7;
            default:
                return 0.5;
        }
    }
    
    /**
     * Apply temporal modifiers to resonance scores
     */
    private function applyTemporalModifiers($scores, $c1, $c2) {
        $modifiedScores = $scores;
        
        foreach ($this->resonanceScoring['temporal_modifiers'] as $modifier => $config) {
            if ($config['condition']($c1, $c2)) {
                foreach ($modifiedScores as $layer => $score) {
                    $modifiedScores[$layer] += $config['value'];
                }
            }
        }
        
        // Ensure scores don't go negative
        foreach ($modifiedScores as $layer => $score) {
            $modifiedScores[$layer] = max(0.0, $score);
        }
        
        return $modifiedScores;
    }
    
    /**
     * Make routing decision with temporal frame compatibility
     */
    private function makeRoutingDecisionWithFrameCompatibility($scores, $c1, $c2, $request) {
        // Check if request involves multiple actors (for frame compatibility)
        $frameCompatibilityResult = null;
        
        if ($this->requestInvolvesMultipleActors($request)) {
            $frameCompatibilityResult = $this->handleMultiActorFrameCompatibility($request, $c1, $c2);
            
            // If frame compatibility failed, adjust routing
            if (!$frameCompatibilityResult['compatible']) {
                return $this->handleIncompatibleFrames($scores, $frameCompatibilityResult, $c1, $c2);
            }
        }
        
        // Standard routing decision for compatible frames
        $decision = $this->makeRoutingDecision($scores);
        $decision['frame_compatibility'] = $frameCompatibilityResult;
        
        return $decision;
    }
    
    /**
     * Check if request involves multiple actors
     */
    private function requestInvolvesMultipleActors($request) {
        return isset($request['actors']) && is_array($request['actors']) && count($request['actors']) > 1;
    }
    
    /**
     * Handle multi-actor frame compatibility with synchronization-first protocol
     */
    private function handleMultiActorFrameCompatibility($request, $systemC1, $systemC2) {
        $actors = $request['actors'];
        $primaryActor = $actors[0] ?? null;
        
        if (!$primaryActor || !isset($primaryActor['temporal_frame'])) {
            // No temporal frame data, assume compatible
            return ['compatible' => true, 'reason' => 'no_frame_data'];
        }
        
        $actorFrame = $primaryActor['temporal_frame'];
        $actorC1 = $actorFrame['c1'] ?? 1.0;
        $actorC2 = $actorFrame['c2'] ?? 0.8;
        
        // Initial compatibility check
        $compatibility = $this->temporalFrameCompatibility->isTemporalFrameCompatible(
            $systemC1, $systemC2, $actorC1, $actorC2
        );
        
        if ($compatibility['compatible']) {
            // Already compatible - no synchronization needed
            return $compatibility;
        }
        
        // Incompatible - initiate synchronization protocol
        $systemActor = [
            'c1' => $systemC1,
            'c2' => $systemC2,
            'temporal_history' => $this->getTemporalHistory(),
            'emotional_state' => $this->getEmotionalState(),
            'task_context' => $this->getTaskContext($request)
        ];
        
        $actorWithHistory = [
            'c1' => $actorC1,
            'c2' => $actorC2,
            'temporal_history' => $primaryActor['temporal_history'] ?? [],
            'emotional_state' => $primaryActor['emotional_state'] ?? [],
            'task_context' => $primaryActor['task_context'] ?? []
        ];
        
        // Execute synchronization protocol
        $synchronizationResult = $this->noteComparisonProtocol->executeSynchronizationProtocol(
            $systemActor,
            $actorWithHistory
        );
        
        // Merge compatibility info with synchronization result
        return array_merge($compatibility, [
            'synchronization_required' => true,
            'synchronization_result' => $synchronizationResult,
            'final_compatibility' => $synchronizationResult['final_compatibility'],
            'resolution' => $synchronizationResult['resolution']
        ]);
    }
    
    /**
     * Handle routing for incompatible frames with synchronization-first approach
     */
    private function handleIncompatibleFrames($scores, $frameCompatibilityResult, $c1, $c2) {
        // Check if synchronization was attempted
        if (isset($frameCompatibilityResult['synchronization_result'])) {
            $syncResult = $frameCompatibilityResult['synchronization_result'];
            
            if ($syncResult['success'] && $syncResult['resolution'] === 'blending') {
                // Synchronization achieved compatibility
                $adjustedActorA = $syncResult['adjusted_actors']['actor_a'];
                $adjustedActorB = $syncResult['adjusted_actors']['actor_b'];
                
                // Use adjusted frames for routing
                $adjustedScores = $this->adjustScoresForSynchronizedFrames($scores, $adjustedActorA, $adjustedActorB);
                
                $decision = $this->makeRoutingDecision($adjustedScores);
                $decision['frame_compatibility'] = $frameCompatibilityResult;
                $decision['synchronization_success'] = true;
                $decision['synchronized_frames'] = [
                    'system_frame' => $adjustedActorA,
                    'actor_frame' => $adjustedActorB
                ];
                
                return $decision;
            }
            
            if ($syncResult['resolution'] === 'bridge_state') {
                // Partial synchronization - create bridge state
                $bridgeState = $this->temporalFrameCompatibility->createBridgeState(
                    $syncResult['adjusted_actors']['actor_a']['c1'],
                    $syncResult['adjusted_actors']['actor_a']['c2'],
                    $syncResult['adjusted_actors']['actor_b']['c1'],
                    $syncResult['adjusted_actors']['actor_b']['c2']
                );
                
                $decision = $this->makeRoutingDecision($scores);
                $decision['frame_compatibility'] = $frameCompatibilityResult;
                $decision['synchronization_partial'] = true;
                $decision['bridge_state'] = $bridgeState;
                
                return $decision;
            }
        }
        
        // Last resort: frame selection (only after synchronization fails)
        $systemFrame = ['c1' => $c1, 'c2' => $c2];
        
        if (isset($frameCompatibilityResult['actor_b_frame'])) {
            $actorFrame = $frameCompatibilityResult['actor_b_frame'];
            
            $frameSelection = $this->temporalFrameCompatibility->selectTemporalFrame(
                $systemFrame['c1'], $systemFrame['c2'],
                $actorFrame['c1'], $actorFrame['c2']
            );
            
            $selectedFrame = $frameSelection['selected_frame'];
            $selectedActor = $frameSelection['selected_actor'];
        } else {
            // Default to system frame
            $selectedFrame = $systemFrame;
            $selectedActor = 'system';
        }
        
        // Adjust routing based on selected frame
        $adjustedScores = $this->adjustScoresForFrame($scores, $selectedFrame);
        
        $decision = $this->makeRoutingDecision($adjustedScores);
        $decision['frame_compatibility'] = $frameCompatibilityResult;
        $decision['synchronization_failed'] = true;
        $decision['frame_selection'] = [
            'selected_frame' => $selectedFrame,
            'selected_actor' => $selectedActor,
            'reason' => $frameSelection['selection_reason'] ?? 'system_default',
            'last_resort' => true
        ];
        
        return $decision;
    }
    
    /**
     * Adjust resonance scores for synchronized frames
     */
    private function adjustScoresForSynchronizedFrames($scores, $adjustedActorA, $adjustedActorB) {
        $adjustedScores = $scores;
        
        // Use the average of synchronized frames for adjustments
        $avgC1 = ($adjustedActorA['c1'] + $adjustedActorB['c1']) / 2;
        $avgC2 = ($adjustedActorA['c2'] + $adjustedActorB['c2']) / 2;
        
        $avgFrame = ['c1' => $avgC1, 'c2' => $avgC2];
        
        return $this->adjustScoresForFrame($adjustedScores, $avgFrame);
    }
    
    /**
     * Get temporal history for synchronization
     */
    private function getTemporalHistory() {
        // Generate synthetic history for now - in production this would come from TemporalMonitor
        $history = [];
        $baseTime = time() - 300; // 5 minutes ago
        $currentC1 = $this->wolfieIdentity->getTemporalFlow();
        $currentC2 = $this->wolfieIdentity->getTemporalCoherence();
        
        for ($i = 0; $i < 10; $i++) {
            $timestamp = $baseTime + ($i * 30); // Every 30 seconds
            
            $history[] = [
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z', $timestamp),
                'c1' => $currentC1 + (sin($i * 0.5) * 0.1),
                'c2' => $currentC2 + (cos($i * 0.3) * 0.05)
            ];
        }
        
        return $history;
    }
    
    /**
     * Get emotional state for synchronization
     */
    private function getEmotionalState() {
        // Simplified emotional state - in production this would come from emotional analysis
        return [
            'positive_valence' => 0.6,
            'negative_valence' => 0.2,
            'cognitive_axis' => 0.7
        ];
    }
    
    /**
     * Get task context for synchronization
     */
    private function getTaskContext($request) {
        return [
            'complexity' => $this->assessComplexity($request),
            'urgency' => isset($request['urgent']) ? 0.8 : 0.3,
            'type' => $request['type'] ?? 'unknown'
        ];
    }
    
    /**
     * Adjust resonance scores based on selected temporal frame
     */
    private function adjustScoresForFrame($scores, $frame) {
        $adjustedScores = $scores;
        
        // Apply frame-specific adjustments
        if ($frame['c2'] < 0.6) {
            // Low coherence - prefer Kenoma (deterministic)
            $adjustedScores[self::KENOMA] += 0.2;
            $adjustedScores[self::PLEROMA] -= 0.1;
        }
        
        if ($frame['c1'] < 0.7 || $frame['c1'] > 1.3) {
            // Non-optimal flow - prefer Liminal (emotional support)
            $adjustedScores[self::LIMINAL] += 0.15;
        }
        
        return $adjustedScores;
    }
    
    /**
     * Make routing decision based on modified scores (legacy method)
     */
    private function makeRoutingDecision($scores) {
        // Find highest scoring layer
        $maxScore = max($scores);
        $bestLayers = array_keys($scores, $maxScore);
        
        // Handle ties
        if (count($bestLayers) > 1) {
            $selectedLayer = $this->resonanceScoring['tiebreaker'];
            $confidence = 0.5; // Lower confidence for ties
        } else {
            $selectedLayer = $bestLayers[0];
            $confidence = $maxScore;
        }
        
        // Check decision threshold
        if ($maxScore < $this->resonanceScoring['decision_threshold']) {
            $selectedLayer = self::KENOMA; // Default to safest layer
            $confidence *= 0.7; // Reduce confidence
        }
        
        return [
            'layer' => $selectedLayer,
            'confidence' => min(1.0, $confidence),
            'reasoning' => $this->generateReasoning($selectedLayer, $scores),
            'all_scores' => $scores
        ];
    }
    
    /**
     * Generate reasoning for routing decision
     */
    private function generateReasoning($selectedLayer, $scores) {
        $reasoning = "Selected {$selectedLayer} based on resonance scores: ";
        
        foreach ($scores as $layer => $score) {
            $reasoning .= "{$layer}=" . number_format($score, 3) . " ";
        }
        
        if ($selectedLayer === $this->resonanceScoring['tiebreaker']) {
            $reasoning .= "(tiebreaker applied)";
        }
        
        return $reasoning;
    }
    
    /**
     * Generate routing recommendations with frame compatibility awareness
     */
    private function generateRoutingRecommendations($routingDecision, $c1, $c2) {
        $recommendations = [];
        
        // Frame compatibility recommendations
        if (isset($routingDecision['frame_compatibility'])) {
            $frameCompat = $routingDecision['frame_compatibility'];
            
            if (isset($frameCompat['synchronization_result'])) {
                $syncResult = $frameCompat['synchronization_result'];
                
                if ($syncResult['success']) {
                    $recommendations[] = 'Synchronization protocol successful';
                    $recommendations[] = 'Resolution: ' . $syncResult['resolution'];
                    
                    if ($syncResult['resolution'] === 'blending') {
                        $recommendations[] = 'Frames synchronized - normal blending permitted';
                    } elseif ($syncResult['resolution'] === 'bridge_state') {
                        $recommendations[] = 'Partial synchronization - bridge state created';
                    }
                } else {
                    $recommendations[] = 'Synchronization protocol failed';
                    $recommendations[] = 'Frame selection applied as last resort';
                }
            } elseif (!$frameCompat['compatible']) {
                $recommendations[] = 'Incompatible temporal frames detected (legacy mode)';
                $recommendations[] = 'Frame selection applied: ' . ($routingDecision['frame_selection']['reason'] ?? 'system_default');
                
                if (isset($routingDecision['frame_selection']['selected_actor'])) {
                    $recommendations[] = 'Selected frame: ' . $routingDecision['frame_selection']['selected_actor'];
                }
            } else {
                $recommendations[] = 'Compatible temporal frames - normal routing applied';
            }
        }
        
        // Temporal health recommendations
        if ($this->wolfieIdentity->hasTemporalPathology()) {
            $recommendations[] = 'Temporal pathology detected - consider ritual intervention';
            $recommendations[] = 'Recommended ritual: ' . $this->wolfieIdentity->getRecommendedRitual();
        }
        
        // Layer-specific recommendations
        switch ($routingDecision['layer']) {
            case self::KENOMA:
                $recommendations[] = 'Kenoma routing: Ensure audit trail is maintained';
                $recommendations[] = 'Follow deterministic processing patterns';
                break;
                
            case self::LIMINAL:
                $recommendations[] = 'Liminal routing: Monitor emotional resonance';
                $recommendations[] = 'Prepare for cocreation processes';
                break;
                
            case self::PLEROMA:
                $recommendations[] = 'Pleroma routing: Enable creative synthesis protocols';
                $recommendations[] = 'Allow heterodox review mechanisms';
                break;
        }
        
        // Confidence-based recommendations
        if ($routingDecision['confidence'] < 0.7) {
            $recommendations[] = 'Low confidence routing - consider manual review';
        }
        
        return $recommendations;
    }
    
    /**
     * Calculate array depth for complexity assessment
     */
    private function calculateArrayDepth($array) {
        $maxDepth = 1;
        
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->calculateArrayDepth($value) + 1;
                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                }
            }
        }
        
        return $maxDepth;
    }
    
    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Calculate processing time
     */
    private function calculateProcessingTime($startTime) {
        $start = new DateTime($startTime);
        $end = new DateTime($this->getCurrentUTC());
        return $end->getTimestamp() - $start->getTimestamp();
    }
    
    /**
     * Log routing process
     */
    private function logRoutingProcess($data) {
        $this->routingLog[] = $data;
        
        // Keep log size manageable
        if (count($this->routingLog) > 1000) {
            $this->routingLog = array_slice($this->routingLog, -500);
        }
    }
    
    /**
     * Get routing log
     */
    public function getRoutingLog($limit = 100) {
        return array_slice($this->routingLog, -$limit);
    }
    
    /**
     * Get current routing statistics
     */
    public function getRoutingStatistics() {
        $stats = [
            'total_routes' => count($this->routingLog),
            'layer_distribution' => [
                self::KENOMA => 0,
                self::LIMINAL => 0,
                self::PLEROMA => 0
            ],
            'average_confidence' => 0.0,
            'temporal_state_impact' => []
        ];
        
        if (empty($this->routingLog)) {
            return $stats;
        }
        
        $totalConfidence = 0.0;
        
        foreach ($this->routingLog as $entry) {
            if (isset($entry['routing_decision'])) {
                $layer = $entry['routing_decision']['layer'];
                $confidence = $entry['routing_decision']['confidence'];
                
                $stats['layer_distribution'][$layer]++;
                $totalConfidence += $confidence;
                
                // Track temporal state impact
                if (isset($entry['temporal_coordinates'])) {
                    $c1 = $entry['temporal_coordinates']['c1'];
                    $c2 = $entry['temporal_coordinates']['c2'];
                    $temporalKey = floor($c1 * 10) . ',' . floor($c2 * 10);
                    
                    if (!isset($stats['temporal_state_impact'][$temporalKey])) {
                        $stats['temporal_state_impact'][$temporalKey] = [
                            'count' => 0,
                            'layers' => []
                        ];
                    }
                    
                    $stats['temporal_state_impact'][$temporalKey]['count']++;
                    $stats['temporal_state_impact'][$temporalKey]['layers'][$layer] = 
                        ($stats['temporal_state_impact'][$temporalKey]['layers'][$layer] ?? 0) + 1;
                }
            }
        }
        
        $stats['average_confidence'] = $totalConfidence / count($this->routingLog);
        
        return $stats;
    }
}
