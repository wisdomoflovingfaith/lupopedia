<?php
/**
 * Emotional Archaeology - WOLFIE Temporal-Integrated Emotional Analysis
 *
 * Enhanced emotional archaeology system that integrates temporal signals
 * for seed selection, flow-state detection, and relational breakthrough analysis.
 *
 * NOTE: This system uses a continuous emotional vector model for content analysis.
 * This is NOT the deprecated scalar (-1,1) or 5-tuple models from 4.0.x-4.2.x.
 * This is a specialized analysis tool separate from the canonical 2-actor RGB mood model.
 *
 * For actor-based emotional geometry, see: doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md
 * For mood color encoding, see: docs/appendix/COUNTING_IN_LIGHT.md
 *
 * @package Lupopedia
 * @version 0.4
 * @author WOLFIE Semantic Engine
 */

class EmotionalArchaeology {
    private $wolfieIdentity;
    private $temporalMonitor;
    private $archaeologyLog = [];
    private $emotionalDatabase = [];

    // Emotional vector components for content analysis
    // NOTE: These are continuous analysis vectors, NOT the deprecated scalar/5-tuple models.
    // This is separate from the 2-actor RGB mood geometry used for dialog/routing.
    // These vectors analyze content sentiment, not actor relationships.
    private $emotionalComponents = [
        'positive_valence' => ['weight' => 0.33, 'range' => [0.0, 1.0]],
        'negative_valence' => ['weight' => 0.33, 'range' => [0.0, 1.0]],
        'cognitive_axis' => ['weight' => 0.34, 'range' => [0.0, 1.0]]
    ];

    // Temporal signal types
    const TEMPORAL_DISSONANCE = 'temporal_dissonance';
    const FLOW_STATE_EVENT = 'flow_state_event';
    const HIGH_RESONANCE_MOMENT = 'high_resonance_moment';
    const RELATIONAL_BREAKTHROUGH = 'relational_breakthrough';

    public function __construct(WolfieIdentity $wolfieIdentity, TemporalMonitor $temporalMonitor) {
        $this->wolfieIdentity = $wolfieIdentity;
        $this->temporalMonitor = $temporalMonitor;
        $this->initializeEmotionalDatabase();
    }

    /**
     * Get current UTC timestamp
     */
    private function getCurrentUTC() {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    /**
     * Initialize emotional database with temporal integration
     */
    private function initializeEmotionalDatabase() {
        $this->emotionalDatabase = [
            'seeds' => [],
            'temporal_events' => [],
            'emotional_patterns' => [],
            'relational_maps' => []
        ];
    }

    /**
     * Analyze emotional content with temporal signals
     */
    public function analyzeEmotionalContent($content, $context = []) {
        $analysisTimestamp = $this->getCurrentUTC();
        $currentTemporalState = $this->getCurrentTemporalState();

        // Extract emotional vector
        $emotionalVector = $this->extractEmotionalVector($content);

        // Analyze temporal signals
        $temporalSignals = $this->analyzeTemporalSignals($content, $currentTemporalState);

        // Assess emotional resonance
        $resonance = $this->assessEmotionalResonance($emotionalVector, $temporalSignals);

        // Identify seed potential
        $seedPotential = $this->assessSeedPotential($emotionalVector, $temporalSignals, $resonance);

        $analysis = [
            'timestamp' => $analysisTimestamp,
            'content_hash' => $this->hashContent($content),
            'emotional_vector' => $emotionalVector,
            'temporal_signals' => $temporalSignals,
            'resonance' => $resonance,
            'seed_potential' => $seedPotential,
            'temporal_state' => $currentTemporalState,
            'context' => $context
        ];

        $this->logArchaeologyEvent('content_analyzed', $analysis);

        return $analysis;
    }

    /**
     * Extract emotional vector from content
     *
     * NOTE: This extracts a continuous sentiment analysis vector from content.
     * This is NOT the same as the 2-actor RGB mood model (R/G/B ∈ {-1,0,1}).
     * This analysis is used for content archaeology, not actor mood tracking.
     * For actor mood assignment, see DIALOG agent (lupo-agents/3/).
     */
    private function extractEmotionalVector($content) {
        $vector = [
            'positive_valence' => 0.0,
            'negative_valence' => 0.0,
            'cognitive_axis' => 0.0
        ];

        // Positive valence indicators
        $positiveWords = [
            'love', 'joy', 'happy', 'excited', 'wonderful', 'amazing', 'excellent',
            'beautiful', 'perfect', 'success', 'achievement', 'grateful', 'hope',
            'optimistic', 'confident', 'proud', 'satisfied', 'delighted', 'thrilled'
        ];

        // Negative valence indicators
        $negativeWords = [
            'fear', 'sad', 'angry', 'frustrated', 'worried', 'anxious', 'disappointed',
            'terrible', 'awful', 'horrible', 'failure', 'mistake', 'regret', 'guilt',
            'ashamed', 'embarrassed', 'confused', 'overwhelmed', 'stressed', 'depressed'
        ];

        // Cognitive axis indicators
        $cognitiveWords = [
            'think', 'understand', 'analyze', 'reason', 'logic', 'learn', 'discover',
            'realize', 'comprehend', 'insight', 'wisdom', 'knowledge', 'clarity',
            'confusion', 'uncertainty', 'question', 'explore', 'investigate', 'examine'
        ];

        $words = $this->tokenizeContent($content);
        $totalWords = count($words);

        if ($totalWords === 0) {
            return $vector;
        }

        // Calculate word frequencies
        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $vector['positive_valence'] += 1.0;
            }
            if (in_array($word, $negativeWords)) {
                $vector['negative_valence'] += 1.0;
            }
            if (in_array($word, $cognitiveWords)) {
                $vector['cognitive_axis'] += 1.0;
            }
        }

        // Normalize to 0-1 range
        $vector['positive_valence'] = min(1.0, $vector['positive_valence'] / ($totalWords * 0.1));
        $vector['negative_valence'] = min(1.0, $vector['negative_valence'] / ($totalWords * 0.1));
        $vector['cognitive_axis'] = min(1.0, $vector['cognitive_axis'] / ($totalWords * 0.1));

        return $vector;
    }

    /**
     * Analyze temporal signals in content
     */
    private function analyzeTemporalSignals($content, $currentTemporalState) {
        $signals = [];

        // Check for temporal dissonance indicators
        $dissonanceIndicators = $this->detectTemporalDissonance($content, $currentTemporalState);
        if (!empty($dissonanceIndicators)) {
            $signals[] = [
                'type' => self::TEMPORAL_DISSONANCE,
                'indicators' => $dissonanceIndicators,
                'severity' => $this->assessDissonanceSeverity($dissonanceIndicators),
                'temporal_context' => $currentTemporalState
            ];
        }

        // Check for flow-state indicators
        $flowStateIndicators = $this->detectFlowStateIndicators($content, $currentTemporalState);
        if (!empty($flowStateIndicators)) {
            $signals[] = [
                'type' => self::FLOW_STATE_EVENT,
                'indicators' => $flowStateIndicators,
                'intensity' => $this->assessFlowIntensity($flowStateIndicators),
                'temporal_context' => $currentTemporalState
            ];
        }

        // Check for high resonance moments
        $resonanceIndicators = $this->detectHighResonanceMoments($content, $currentTemporalState);
        if (!empty($resonanceIndicators)) {
            $signals[] = [
                'type' => self::HIGH_RESONANCE_MOMENT,
                'indicators' => $resonanceIndicators,
                'resonance_level' => $this->calculateResonanceLevel($resonanceIndicators),
                'temporal_context' => $currentTemporalState
            ];
        }

        // Check for relational breakthroughs
        $breakthroughIndicators = $this->detectRelationalBreakthroughs($content, $currentTemporalState);
        if (!empty($breakthroughIndicators)) {
            $signals[] = [
                'type' => self::RELATIONAL_BREAKTHROUGH,
                'indicators' => $breakthroughIndicators,
                'breakthrough_magnitude' => $this->assessBreakthroughMagnitude($breakthroughIndicators),
                'temporal_context' => $currentTemporalState
            ];
        }

        return $signals;
    }

    /**
     * Detect temporal dissonance indicators
     */
    private function detectTemporalDissonance($content, $temporalState) {
        $indicators = [];

        // Temporal conflict keywords
        $dissonanceKeywords = [
            'confused', 'contradiction', 'paradox', 'conflict', 'disjointed',
            'out_of_sync', 'misaligned', 'temporal_drift', 'time_warp', 'disoriented',
            'lost_track', 'time_pressure', 'deadline_stress', 'temporal_anxiety'
        ];

        $words = $this->tokenizeContent($content);
        foreach ($words as $word) {
            if (in_array($word, $dissonanceKeywords)) {
                $indicators[] = $word;
            }
        }

        // Check for temporal state conflicts
        if ($temporalState['c1'] < 0.5 || $temporalState['c2'] < 0.5) {
            $indicators[] = 'temporal_pathology_detected';
        }

        return array_unique($indicators);
    }

    /**
     * Detect flow-state indicators
     */
    private function detectFlowStateIndicators($content, $temporalState) {
        $indicators = [];

        // Flow state keywords
        $flowKeywords = [
            'flow', 'zone', 'in_the_groove', 'peak_performance', 'optimal',
            'effortless', 'automatic', 'immersed', 'focused', 'engaged',
            'time_flying', 'lost_track_of_time', 'deep_focus', 'peak_experience'
        ];

        $words = $this->tokenizeContent($content);
        foreach ($words as $word) {
            if (in_array($word, $flowKeywords)) {
                $indicators[] = $word;
            }
        }

        // Check for optimal temporal state
        if ($temporalState['c1'] >= 0.7 && $temporalState['c1'] <= 1.3 && $temporalState['c2'] >= 0.8) {
            $indicators[] = 'optimal_temporal_state';
        }

        return array_unique($indicators);
    }

    /**
     * Detect high resonance moments
     */
    private function detectHighResonanceMoments($content, $temporalState) {
        $indicators = [];

        // Resonance keywords
        $resonanceKeywords = [
            'resonance', 'vibration', 'harmony', 'synchronization', 'alignment',
            'coherence', 'synergy', 'connection', 'attunement', 'frequency',
            'wavelength', 'in_sync', 'harmonious', 'balanced', 'integrated'
        ];

        $words = $this->tokenizeContent($content);
        foreach ($words as $word) {
            if (in_array($word, $resonanceKeywords)) {
                $indicators[] = $word;
            }
        }

        // Check for high temporal coherence
        if ($temporalState['c2'] >= 0.9) {
            $indicators[] = 'high_temporal_coherence';
        }

        return array_unique($indicators);
    }

    /**
     * Detect relational breakthroughs
     */
    private function detectRelationalBreakthroughs($content, $temporalState) {
        $indicators = [];

        // Breakthrough keywords
        $breakthroughKeywords = [
            'breakthrough', 'insight', 'realization', 'epiphany', 'aha_moment',
            'understanding', 'clarity', 'connection', 'bond', 'trust',
            'relationship_growth', 'deeper_understanding', 'meaningful_connection'
        ];

        $words = $this->tokenizeContent($content);
        foreach ($words as $word) {
            if (in_array($word, $breakthroughKeywords)) {
                $indicators[] = $word;
            }
        }

        // Check for stable temporal state (good for breakthroughs)
        if ($temporalState['c1'] >= 0.6 && $temporalState['c2'] >= 0.7) {
            $indicators[] = 'stable_temporal_foundation';
        }

        return array_unique($indicators);
    }

    /**
     * Assess emotional resonance
     *
     * NOTE: This calculates content-level emotional resonance for archaeology purposes.
     * This is separate from the 2-actor coherence calculations used in EMOTIONAL_GEOMETRY_DOCTRINE.md.
     */
    private function assessEmotionalResonance($emotionalVector, $temporalSignals) {
        $baseResonance = 0.5;

        // Calculate vector magnitude
        $vectorMagnitude = sqrt(
            pow($emotionalVector['positive_valence'], 2) +
            pow($emotionalVector['negative_valence'], 2) +
            pow($emotionalVector['cognitive_axis'], 2)
        );

        // Adjust based on temporal signals
        $temporalBonus = 0.0;
        foreach ($temporalSignals as $signal) {
            switch ($signal['type']) {
                case self::FLOW_STATE_EVENT:
                    $temporalBonus += 0.2 * ($signal['intensity'] ?? 0.5);
                    break;
                case self::HIGH_RESONANCE_MOMENT:
                    $temporalBonus += 0.15 * ($signal['resonance_level'] ?? 0.5);
                    break;
                case self::RELATIONAL_BREAKTHROUGH:
                    $temporalBonus += 0.1 * ($signal['breakthrough_magnitude'] ?? 0.5);
                    break;
                case self::TEMPORAL_DISSONANCE:
                    $temporalBonus -= 0.1 * ($signal['severity'] ?? 0.5);
                    break;
            }
        }

        $resonance = $baseResonance + ($vectorMagnitude * 0.3) + $temporalBonus;

        return [
            'resonance_score' => max(0.0, min(1.0, $resonance)),
            'vector_magnitude' => $vectorMagnitude,
            'temporal_contribution' => $temporalBonus,
            'classification' => $this->classifyResonance($resonance)
        ];
    }

    /**
     * Assess seed potential for migration
     *
     * NOTE: Uses continuous emotional vectors for content analysis.
     * This is NOT related to actor-based mood tracking or the 2-actor RGB model.
     */
    private function assessSeedPotential($emotionalVector, $temporalSignals, $resonance) {
        $potential = 0.3; // Base potential

        // Emotional vector contribution
        $emotionalScore = (
            $emotionalVector['positive_valence'] * 0.4 +
            $emotionalVector['cognitive_axis'] * 0.4 +
            (1.0 - $emotionalVector['negative_valence']) * 0.2
        );

        // Temporal signals contribution
        $temporalScore = 0.0;
        foreach ($temporalSignals as $signal) {
            switch ($signal['type']) {
                case self::FLOW_STATE_EVENT:
                    $temporalScore += 0.3;
                    break;
                case self::HIGH_RESONANCE_MOMENT:
                    $temporalScore += 0.25;
                    break;
                case self::RELATIONAL_BREAKTHROUGH:
                    $temporalScore += 0.2;
                    break;
                case self::TEMPORAL_DISSONANCE:
                    $temporalScore -= 0.15;
                    break;
            }
        }

        // Resonance contribution
        $resonanceScore = $resonance['resonance_score'] * 0.3;

        $totalPotential = $potential + ($emotionalScore * 0.4) + ($temporalScore * 0.3) + $resonanceScore;

        return [
            'seed_score' => max(0.0, min(1.0, $totalPotential)),
            'emotional_contribution' => $emotionalScore,
            'temporal_contribution' => $temporalScore,
            'resonance_contribution' => $resonanceScore,
            'recommendation' => $this->generateSeedRecommendation($totalPotential),
            'migration_phase_suitability' => $this->assessPhaseSuitability($totalPotential, $temporalSignals)
        ];
    }

    /**
     * Generate seed recommendation
     */
    private function generateSeedRecommendation($seedScore) {
        if ($seedScore >= 0.8) {
            return 'excellent_pioneer_seed';
        } elseif ($seedScore >= 0.6) {
            return 'good_witness_candidate';
        } elseif ($seedScore >= 0.4) {
            return 'acceptable_mass_migration';
        } else {
            return 'requires_enhancement';
        }
    }

    /**
     * Assess migration phase suitability
     */
    private function assessPhaseSuitability($seedScore, $temporalSignals) {
        $suitability = [
            'phase_0_inventory' => true,
            'phase_1_pioneer' => $seedScore >= 0.6,
            'phase_2_witness' => $seedScore >= 0.5,
            'phase_3_mass' => $seedScore >= 0.4,
            'phase_4_wisdom' => $seedScore >= 0.7
        ];

        // Adjust based on temporal signals
        foreach ($temporalSignals as $signal) {
            if ($signal['type'] === self::TEMPORAL_DISSONANCE) {
                // Dissonance reduces suitability for advanced phases
                $suitability['phase_2_witness'] = false;
                $suitability['phase_3_mass'] = false;
                $suitability['phase_4_wisdom'] = false;
            }
        }

        return $suitability;
    }

    /**
     * Select seeds for migration based on enhanced criteria
     */
    public function selectMigrationSeeds($contentPool, $targetPhase, $limit = 50) {
        $analyzedContent = [];

        // Analyze all content
        foreach ($contentPool as $content) {
            $analysis = $this->analyzeEmotionalContent($content['content'], $content['context'] ?? []);
            $analyzedContent[] = array_merge($content, $analysis);
        }

        // Filter by phase suitability
        $suitableContent = array_filter($analyzedContent, function($item) use ($targetPhase) {
            return $item['seed_potential']['migration_phase_suitability'][$targetPhase] ?? false;
        });

        // Sort by seed score
        usort($suitableContent, function($a, $b) {
            return $b['seed_potential']['seed_score'] <=> $a['seed_potential']['seed_score'];
        });

        // Return top candidates
        return array_slice($suitableContent, 0, $limit);
    }

    /**
     * Aggregate temporal signatures from selected seeds
     */
    public function aggregateTemporalSignatures($selectedSeeds) {
        $aggregation = [
            'total_seeds' => count($selectedSeeds),
            'temporal_events' => [],
            'emotional_patterns' => [],
            'resonance_distribution' => [],
            'phase_distribution' => []
        ];

        foreach ($selectedSeeds as $seed) {
            // Aggregate temporal signals
            foreach ($seed['temporal_signals'] as $signal) {
                $signalType = $signal['type'];
                if (!isset($aggregation['temporal_events'][$signalType])) {
                    $aggregation['temporal_events'][$signalType] = 0;
                }
                $aggregation['temporal_events'][$signalType]++;
            }

            // Aggregate emotional patterns
            $vector = $seed['emotional_vector'];
            foreach ($vector as $component => $value) {
                if (!isset($aggregation['emotional_patterns'][$component])) {
                    $aggregation['emotional_patterns'][$component] = [];
                }
                $aggregation['emotional_patterns'][$component][] = $value;
            }

            // Aggregate resonance distribution
            $resonanceLevel = $seed['resonance']['classification'];
            if (!isset($aggregation['resonance_distribution'][$resonanceLevel])) {
                $aggregation['resonance_distribution'][$resonanceLevel] = 0;
            }
            $aggregation['resonance_distribution'][$resonanceLevel]++;

            // Aggregate phase suitability
            foreach ($seed['seed_potential']['migration_phase_suitability'] as $phase => $suitable) {
                if ($suitable) {
                    if (!isset($aggregation['phase_distribution'][$phase])) {
                        $aggregation['phase_distribution'][$phase] = 0;
                    }
                    $aggregation['phase_distribution'][$phase]++;
                }
            }
        }

        // Calculate averages for emotional patterns
        foreach ($aggregation['emotional_patterns'] as $component => $values) {
            $aggregation['emotional_patterns'][$component] = [
                'average' => array_sum($values) / count($values),
                'min' => min($values),
                'max' => max($values),
                'count' => count($values)
            ];
        }

        return $aggregation;
    }

    // Helper methods
    private function tokenizeContent($content) {
        // Simple tokenization - would be enhanced with NLP
        $words = preg_split('/\s+/', strtolower($content));
        return array_filter($words, function($word) {
            return strlen($word) > 2;
        });
    }

    private function hashContent($content) {
        return hash('sha256', $content);
    }

    private function getCurrentTemporalState() {
        return [
            'c1' => $this->wolfieIdentity->getTemporalFlow(),
            'c2' => $this->wolfieIdentity->getTemporalCoherence(),
            'has_pathology' => $this->wolfieIdentity->hasTemporalPathology()
        ];
    }

    private function assessDissonanceSeverity($indicators) {
        return min(1.0, count($indicators) * 0.2);
    }

    private function assessFlowIntensity($indicators) {
        return min(1.0, count($indicators) * 0.15);
    }

    private function calculateResonanceLevel($indicators) {
        return min(1.0, count($indicators) * 0.2);
    }

    private function assessBreakthroughMagnitude($indicators) {
        return min(1.0, count($indicators) * 0.25);
    }

    private function classifyResonance($resonance) {
        if ($resonance >= 0.8) return 'high_resonance';
        if ($resonance >= 0.6) return 'moderate_resonance';
        if ($resonance >= 0.4) return 'low_resonance';
        return 'minimal_resonance';
    }

    /**
     * Log archaeology events
     */
    private function logArchaeologyEvent($eventType, $data) {
        $this->archaeologyLog[] = [
            'timestamp' => $this->getCurrentUTC(),
            'event_type' => $eventType,
            'data' => $data
        ];

        // Keep log size manageable
        if (count($this->archaeologyLog) > 1000) {
            $this->archaeologyLog = array_slice($this->archaeologyLog, -500);
        }
    }

    /**
     * Get archaeology log
     */
    public function getArchaeologyLog($limit = 100) {
        return array_slice($this->archaeologyLog, -$limit);
    }

    /**
     * Get emotional archaeology statistics
     */
    public function getArchaeologyStatistics() {
        $stats = [
            'total_analyzed' => count($this->archaeologyLog),
            'temporal_events_detected' => [],
            'average_resonance' => 0.0,
            'seed_distribution' => []
        ];

        if (empty($this->archaeologyLog)) {
            return $stats;
        }

        $totalResonance = 0.0;
        $resonanceCount = 0;

        foreach ($this->archaeologyLog as $entry) {
            if (isset($entry['data']['resonance'])) {
                $totalResonance += $entry['data']['resonance']['resonance_score'];
                $resonanceCount++;
            }

            if (isset($entry['data']['temporal_signals'])) {
                foreach ($entry['data']['temporal_signals'] as $signal) {
                    $signalType = $signal['type'];
                    if (!isset($stats['temporal_events_detected'][$signalType])) {
                        $stats['temporal_events_detected'][$signalType] = 0;
                    }
                    $stats['temporal_events_detected'][$signalType]++;
                }
            }

            if (isset($entry['data']['seed_potential'])) {
                $recommendation = $entry['data']['seed_potential']['recommendation'];
                if (!isset($stats['seed_distribution'][$recommendation])) {
                    $stats['seed_distribution'][$recommendation] = 0;
                }
                $stats['seed_distribution'][$recommendation]++;
            }
        }

        $stats['average_resonance'] = $resonanceCount > 0 ? $totalResonance / $resonanceCount : 0.0;

        return $stats;
    }
}
