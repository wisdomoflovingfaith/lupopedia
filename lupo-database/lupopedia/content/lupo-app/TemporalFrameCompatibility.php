<?php
/**
 * WOLFIE Temporal Frame Compatibility Model v0.5
 * 
 * Implementation of temporal frame compatibility testing, conditional blending,
 * frame selection, and bridge state representation.
 * 
 * This supersedes all prior temporal blending models (Trinitary, linear, bounded).
 * 
 * @package Lupopedia
 * @version 0.5
 * @author WOLFIE Semantic Engine
 */

class TemporalFrameCompatibility {
    private $compatibilityThreshold = 0.6;
    private $defaultWeights = ['actor_a' => 0.5, 'actor_b' => 0.5];
    private $frameSelectionLog = [];
    private $compatibilityLog = [];
    
    // Frame compatibility modes
    const MODE_COMPATIBLE = 'compatible';
    const MODE_INCOMPATIBLE = 'incompatible';
    const MODE_BRIDGE = 'bridge';
    const MODE_SELECTED = 'selected';
    
    public function __construct($threshold = 0.6) {
        $this->compatibilityThreshold = $threshold;
    }
    
    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Check temporal frame compatibility between two actors
     * 
     * @param float $c1_a Actor A temporal flow
     * @param float $c2_a Actor A temporal coherence
     * @param float $c1_b Actor B temporal flow
     * @param float $c2_b Actor B temporal coherence
     * @param float $threshold Compatibility threshold (default: 0.6)
     * @return array Compatibility analysis result
     */
    public function isTemporalFrameCompatible($c1_a, $c2_a, $c1_b, $c2_b, $threshold = null) {
        $threshold = $threshold ?? $this->compatibilityThreshold;
        
        // Compute temporal separation
        $separation = abs($c1_a - $c1_b) + abs($c2_a - $c2_b);
        
        $isCompatible = $separation < $threshold;
        
        $result = [
            'compatible' => $isCompatible,
            'separation' => $separation,
            'threshold' => $threshold,
            'mode' => $isCompatible ? self::MODE_COMPATIBLE : self::MODE_INCOMPATIBLE,
            'actor_a_frame' => ['c1' => $c1_a, 'c2' => $c2_a],
            'actor_b_frame' => ['c1' => $c1_b, 'c2' => $c2_b],
            'timestamp' => $this->getCurrentUTC()
        ];
        
        // Add detailed analysis
        if ($isCompatible) {
            $result['analysis'] = 'Timelike separation - frames share temporal reference';
            $result['blending_permitted'] = true;
        } else {
            $result['analysis'] = 'Spacelike separation - frames do not share temporal reference';
            $result['blending_permitted'] = false;
            $result['frame_selection_required'] = true;
        }
        
        $this->logCompatibilityCheck($result);
        
        return $result;
    }
    
    /**
     * Blend temporal states (compatible frames only)
     * 
     * @param float $c1_a Actor A temporal flow
     * @param float $c2_a Actor A temporal coherence
     * @param float $c1_b Actor B temporal flow
     * @param float $c2_b Actor B temporal coherence
     * @param array $weights Blending weights [actor_a, actor_b]
     * @return array Blended temporal state
     * @throws Exception If frames are incompatible
     */
    public function blendTemporalStates($c1_a, $c2_a, $c1_b, $c2_b, $weights = null) {
        $weights = $weights ?? $this->defaultWeights;
        
        // Check compatibility first
        $compatibility = $this->isTemporalFrameCompatible($c1_a, $c2_a, $c1_b, $c2_b);
        
        if (!$compatibility['compatible']) {
            throw new Exception(
                "Incompatible temporal frames - blending prohibited. " .
                "Separation: {$compatibility['separation']}, Threshold: {$compatibility['threshold']}"
            );
        }
        
        // Validate weights
        if (abs($weights['actor_a'] + $weights['actor_b'] - 1.0) > 0.001) {
            throw new Exception("Weights must sum to 1.0");
        }
        
        // Perform blending
        $c1_blended = ($weights['actor_a'] * $c1_a) + ($weights['actor_b'] * $c1_b);
        $c2_blended = ($weights['actor_a'] * $c2_a) + ($weights['actor_b'] * $c2_b);
        
        $result = [
            'c1' => $c1_blended,
            'c2' => $c2_blended,
            'mode' => self::MODE_COMPATIBLE,
            'weights' => $weights,
            'source_frames' => [
                'actor_a' => ['c1' => $c1_a, 'c2' => $c2_a],
                'actor_b' => ['c1' => $c1_b, 'c2' => $c2_b]
            ],
            'compatibility_analysis' => $compatibility,
            'timestamp' => $this->getCurrentUTC()
        ];
        
        return $result;
    }
    
    /**
     * Select dominant temporal frame for incompatible frames
     * 
     * @param float $c1_a Actor A temporal flow
     * @param float $c2_a Actor A temporal coherence
     * @param float $c1_b Actor B temporal flow
     * @param float $c2_b Actor B temporal coherence
     * @param array $criteria Selection criteria override
     * @return array Selected temporal frame
     */
    public function selectTemporalFrame($c1_a, $c2_a, $c1_b, $c2_b, $criteria = null) {
        $selectionCriteria = $criteria ?? $this->getDefaultSelectionCriteria();
        
        $frames = [
            'actor_a' => [
                'c1' => $c1_a,
                'c2' => $c2_a,
                'stability_score' => $this->calculateStabilityScore($c1_a, $c2_a)
            ],
            'actor_b' => [
                'c1' => $c1_b,
                'c2' => $c2_b,
                'stability_score' => $this->calculateStabilityScore($c1_b, $c2_b)
            ]
        ];
        
        // Primary criterion: higher temporal coherence (c2)
        if ($frames['actor_a']['c2'] > $frames['actor_b']['c2']) {
            $selected = 'actor_a';
            $reason = 'Higher temporal coherence (c2)';
        } elseif ($frames['actor_b']['c2'] > $frames['actor_a']['c2']) {
            $selected = 'actor_b';
            $reason = 'Higher temporal coherence (c2)';
        } else {
            // Tie-breaking: stability score
            if ($frames['actor_a']['stability_score'] > $frames['actor_b']['stability_score']) {
                $selected = 'actor_a';
                $reason = 'Higher stability score (c2 tie-break)';
            } else {
                $selected = 'actor_b';
                $reason = 'Higher stability score (c2 tie-break)';
            }
        }
        
        $result = [
            'mode' => self::MODE_SELECTED,
            'selected_frame' => $frames[$selected],
            'selected_actor' => $selected,
            'selection_reason' => $reason,
            'alternative_frame' => $frames[$selected === 'actor_a' ? 'actor_b' : 'actor_a'],
            'all_frames' => $frames,
            'selection_criteria' => $selectionCriteria,
            'timestamp' => $this->getCurrentUTC()
        ];
        
        $this->logFrameSelection($result);
        
        return $result;
    }
    
    /**
     * Create bridge representation for incompatible frames
     * 
     * @param float $c1_a Actor A temporal flow
     * @param float $c2_a Actor A temporal coherence
     * @param float $c1_b Actor B temporal flow
     * @param float $c2_b Actor B temporal coherence
     * @param array $options Bridge options
     * @return array Bridge state representation
     */
    public function createBridgeState($c1_a, $c2_a, $c1_b, $c2_b, $options = []) {
        $bridgeOptions = array_merge([
            'resolution_strategy' => 'manual',
            'priority_frame' => null,
            'timeout_seconds' => 300
        ], $options);
        
        $result = [
            'mode' => self::MODE_BRIDGE,
            'c1_modes' => [$c1_a, $c1_b],
            'c2_modes' => [$c2_a, $c2_b],
            'frames' => [
                'actor_a' => ['c1' => $c1_a, 'c2' => $c2_a],
                'actor_b' => ['c1' => $c1_b, 'c2' => $c2_b]
            ],
            'resolution_required' => true,
            'bridge_options' => $bridgeOptions,
            'created_at' => $this->getCurrentUTC(),
            'expires_at' => gmdate('Y-m-d\TH:i:s\Z', time() + $bridgeOptions['timeout_seconds'])
        ];
        
        // Add compatibility analysis
        $result['compatibility_analysis'] = $this->isTemporalFrameCompatible($c1_a, $c2_a, $c1_b, $c2_b);
        
        return $result;
    }
    
    /**
     * Resolve bridge state to single frame
     * 
     * @param array $bridgeState Bridge state from createBridgeState()
     * @param string $resolutionMethod Resolution method
     * @param array $resolutionData Resolution parameters
     * @return array Resolved temporal frame
     */
    public function resolveBridgeState($bridgeState, $resolutionMethod, $resolutionData = []) {
        if ($bridgeState['mode'] !== self::MODE_BRIDGE) {
            throw new Exception("Can only resolve bridge states");
        }
        
        $frames = $bridgeState['frames'];
        
        switch ($resolutionMethod) {
            case 'select_frame':
                $selectedActor = $resolutionData['selected_actor'] ?? 'actor_a';
                $resolvedFrame = $frames[$selectedActor];
                $reason = "Manual selection: {$selectedActor}";
                break;
                
            case 'automatic_selection':
                $selection = $this->selectTemporalFrame(
                    $frames['actor_a']['c1'], $frames['actor_a']['c2'],
                    $frames['actor_b']['c1'], $frames['actor_b']['c2']
                );
                $resolvedFrame = $selection['selected_frame'];
                $reason = $selection['selection_reason'];
                break;
                
            case 'temporal_convergence':
                // Attempt to find a convergent frame (advanced - not implemented in v0.5)
                throw new Exception("Temporal convergence not implemented in v0.5");
                
            default:
                throw new Exception("Unknown resolution method: {$resolutionMethod}");
        }
        
        return [
            'mode' => self::MODE_SELECTED,
            'resolved_frame' => $resolvedFrame,
            'resolution_method' => $resolutionMethod,
            'resolution_reason' => $reason,
            'original_bridge_state' => $bridgeState,
            'resolved_at' => $this->getCurrentUTC()
        ];
    }
    
    /**
     * Frame-aware migration protocol
     * 
     * @param array $incomingInstance Incoming instance temporal frame
     * @param array $existingInstance Existing instance temporal frame
     * @param array $migrationOptions Migration options
     * @return array Migration result with frame reconciliation
     */
    public function migrateWithFrameReconciliation($incomingInstance, $existingInstance, $migrationOptions = []) {
        $options = array_merge([
            'allow_bridge' => true,
            'prefer_existing' => false,
            'migration_weights' => $this->defaultWeights
        ], $migrationOptions);
        
        // Extract temporal frames
        $incomingFrame = $incomingInstance['temporal_frame'] ?? $incomingInstance;
        $existingFrame = $existingInstance['temporal_frame'] ?? $existingInstance;
        
        // Check compatibility
        $compatibility = $this->isTemporalFrameCompatible(
            $incomingFrame['c1'], $incomingFrame['c2'],
            $existingFrame['c1'], $existingFrame['c2']
        );
        
        $result = [
            'migration_type' => 'frame_reconciliation',
            'incoming_frame' => $incomingFrame,
            'existing_frame' => $existingFrame,
            'compatibility' => $compatibility,
            'migration_options' => $options,
            'timestamp' => $this->getCurrentUTC()
        ];
        
        if ($compatibility['compatible']) {
            // Compatible frames - blend
            try {
                $blendedFrame = $this->blendTemporalStates(
                    $incomingFrame['c1'], $incomingFrame['c2'],
                    $existingFrame['c1'], $existingFrame['c2'],
                    $options['migration_weights']
                );
                
                $result['result'] = $blendedFrame;
                $result['resolution'] = 'blended';
                $result['success'] = true;
                
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
                $result['success'] = false;
            }
            
        } else {
            // Incompatible frames
            if ($options['allow_bridge'] && $this->shouldCreateBridge($incomingFrame, $existingFrame)) {
                // Create bridge state
                $bridgeState = $this->createBridgeState(
                    $incomingFrame['c1'], $incomingFrame['c2'],
                    $existingFrame['c1'], $existingFrame['c2']
                );
                
                $result['result'] = $bridgeState;
                $result['resolution'] = 'bridge_created';
                $result['success'] = true;
                
            } else {
                // Select dominant frame
                $selection = $this->selectTemporalFrame(
                    $incomingFrame['c1'], $incomingFrame['c2'],
                    $existingFrame['c1'], $existingFrame['c2']
                );
                
                // Apply preference if specified
                if ($options['prefer_existing']) {
                    $selectedFrame = $existingFrame;
                    $selection['selected_frame'] = $existingFrame;
                    $selection['selected_actor'] = 'existing';
                    $selection['selection_reason'] = 'Preference for existing frame';
                }
                
                $result['result'] = $selection;
                $result['resolution'] = 'frame_selected';
                $result['success'] = true;
            }
        }
        
        return $result;
    }
    
    /**
     * Calculate stability score for temporal frame
     */
    private function calculateStabilityScore($c1, $c2) {
        // Optimal ranges: c1: 0.7-1.3, c2: >= 0.8
        $c1_optimal = ($c1 >= 0.7 && $c1 <= 1.3) ? 1.0 : max(0.0, 1.0 - abs($c1 - 1.0) / 0.5);
        $c2_optimal = ($c2 >= 0.8) ? 1.0 : $c2 / 0.8;
        
        return ($c1_optimal * 0.6) + ($c2_optimal * 0.4);
    }
    
    /**
     * Get default selection criteria
     */
    private function getDefaultSelectionCriteria() {
        return [
            'primary' => 'temporal_coherence',
            'secondary' => 'stability_score',
            'tertiary' => 'task_requirements',
            'fallback' => 'architect_override'
        ];
    }
    
    /**
     * Determine if bridge state should be created
     */
    private function shouldCreateBridge($incomingFrame, $existingFrame) {
        // Bridge creation logic - for now, always create if allowed
        // In future versions, this could be based on task requirements
        return true;
    }
    
    /**
     * Log compatibility check
     */
    private function logCompatibilityCheck($result) {
        $this->compatibilityLog[] = [
            'timestamp' => $this->getCurrentUTC(),
            'type' => 'compatibility_check',
            'result' => $result
        ];
        
        // Keep log manageable
        if (count($this->compatibilityLog) > 1000) {
            $this->compatibilityLog = array_slice($this->compatibilityLog, -500);
        }
    }
    
    /**
     * Log frame selection
     */
    private function logFrameSelection($result) {
        $this->frameSelectionLog[] = [
            'timestamp' => $this->getCurrentUTC(),
            'type' => 'frame_selection',
            'result' => $result
        ];
        
        // Keep log manageable
        if (count($this->frameSelectionLog) > 1000) {
            $this->frameSelectionLog = array_slice($this->frameSelectionLog, -500);
        }
    }
    
    /**
     * Get compatibility log
     */
    public function getCompatibilityLog($limit = 100) {
        return array_slice($this->compatibilityLog, -$limit);
    }
    
    /**
     * Get frame selection log
     */
    public function getFrameSelectionLog($limit = 100) {
        return array_slice($this->frameSelectionLog, -$limit);
    }
    
    /**
     * Set compatibility threshold
     */
    public function setCompatibilityThreshold($threshold) {
        if ($threshold <= 0 || $threshold > 2.0) {
            throw new Exception("Threshold must be between 0 and 2.0");
        }
        $this->compatibilityThreshold = $threshold;
    }
    
    /**
     * Get current compatibility threshold
     */
    public function getCompatibilityThreshold() {
        return $this->compatibilityThreshold;
    }
    
    /**
     * Analyze temporal frame relationship
     */
    public function analyzeFrameRelationship($c1_a, $c2_a, $c1_b, $c2_b) {
        $compatibility = $this->isTemporalFrameCompatible($c1_a, $c2_a, $c1_b, $c2_b);
        
        $analysis = [
            'compatibility' => $compatibility,
            'frame_analysis' => [
                'actor_a' => [
                    'frame' => ['c1' => $c1_a, 'c2' => $c2_a],
                    'stability' => $this->calculateStabilityScore($c1_a, $c2_a),
                    'health' => $this->assessFrameHealth($c1_a, $c2_a)
                ],
                'actor_b' => [
                    'frame' => ['c1' => $c1_b, 'c2' => $c2_b],
                    'stability' => $this->calculateStabilityScore($c1_b, $c2_b),
                    'health' => $this->assessFrameHealth($c1_b, $c2_b)
                ]
            ],
            'recommendations' => []
        ];
        
        // Add recommendations
        if ($compatibility['compatible']) {
            $analysis['recommendations'][] = 'Frames are compatible - blending permitted';
            $analysis['recommendations'][] = 'Consider weighted blending based on coherence';
        } else {
            $analysis['recommendations'][] = 'Frames are incompatible - blending prohibited';
            $analysis['recommendations'][] = 'Select dominant frame based on temporal coherence';
            if ($this->shouldCreateBridge(['c1' => $c1_a, 'c2' => $c2_a], ['c1' => $c1_b, 'c2' => $c2_b])) {
                $analysis['recommendations'][] = 'Consider bridge state for parallel representation';
            }
        }
        
        return $analysis;
    }
    
    /**
     * Assess frame health
     */
    private function assessFrameHealth($c1, $c2) {
        $health = 'optimal';
        
        if ($c2 < 0.2) {
            $health = 'critical';
        } elseif ($c2 < 0.4) {
            $health = 'degraded';
        } elseif ($c2 < 0.6) {
            $health = 'caution';
        }
        
        if ($c1 < 0.3 || $c1 > 1.7) {
            $health = 'critical';
        } elseif ($c1 < 0.5 || $c1 > 1.5) {
            if ($health === 'optimal') $health = 'caution';
        }
        
        return $health;
    }
}
?>
