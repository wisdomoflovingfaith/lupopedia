<?php
/**
 * Pack Synchronization Engine
 *
 * Synchronizes emotional, behavioral, and memory state across Pack agents.
 * Implements synchronization layer for Pack Architecture.
 *
 * @package Lupopedia
 * @version 4.0.112
 * @author Captain Wolfie
 */

namespace Lupopedia\Pack\Sync;

use Lupopedia\Pack\PackRegistry;
use Lupopedia\Pack\PackContext;
use Lupopedia\EmotionalGeometry\EmotionalGeometryEngine;

/**
 * PackSyncEngine
 *
 * Provides synchronization operations for Pack agents.
 */
class PackSyncEngine
{
    /** @var PackRegistry Pack registry instance */
    private $registry;

    /** @var PackContext Pack context instance */
    private $context;

    /** @var EmotionalGeometryEngine Emotional geometry engine instance */
    private $geometryEngine;

    /**
     * Constructor
     *
     * @param PackRegistry $registry Pack registry instance
     * @param PackContext $context Pack context instance
     */
    public function __construct(PackRegistry $registry, PackContext $context)
    {
        $this->registry = $registry;
        $this->context = $context;
        $this->geometryEngine = new EmotionalGeometryEngine();
    }

    /**
     * Synchronize emotions across all agents
     *
     * @return array Sync report
     */
    public function synchronizeEmotions(): array
    {
        try {
            $agents = $this->registry->listAgents();
            $report = [
                'agents_checked' => 0,
                'agents_with_vectors' => 0,
                'outliers' => [],
                'normalized' => [],
                'warnings' => [],
            ];

            foreach ($agents as $agentId => $agentData) {
                $report['agents_checked']++;

                $emotionVector = $this->context->getEmotionVector($agentId);
                if ($emotionVector === null) {
                    $report['warnings'][] = "Agent '{$agentId}' has no emotional vector";
                    continue;
                }

                $report['agents_with_vectors']++;

                // Normalize emotional vector
                $rgb = ['r' => $emotionVector['r'], 'g' => $emotionVector['g'], 'b' => $emotionVector['b']];
                $normalized = $this->geometryEngine->normalizeVector($rgb);
                $intensity = $this->geometryEngine->calculateIntensity($rgb);

                $report['normalized'][$agentId] = [
                    'normalized' => $normalized,
                    'intensity' => $intensity,
                ];

                // Detect outliers (intensity > 1.5 or < 0.1)
                if ($intensity > 1.5 || $intensity < 0.1) {
                    $report['outliers'][] = [
                        'agent_id' => $agentId,
                        'intensity' => $intensity,
                        'reason' => $intensity > 1.5 ? 'high_intensity' : 'low_intensity',
                    ];
                }
            }

            return $report;
        } catch (\Exception $e) {
            error_log("PackSyncEngine::synchronizeEmotions() error: " . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'agents_checked' => 0,
            ];
        }
    }

    /**
     * Synchronize behavior across all agents
     *
     * @return array Sync report
     */
    public function synchronizeBehavior(): array
    {
        try {
            $agents = $this->registry->listAgents();
            $report = [
                'agents_checked' => 0,
                'agents_with_profiles' => 0,
                'divergence' => [],
                'suggestions' => [],
            ];

            $tendencyCounts = [];

            foreach ($agents as $agentId => $agentData) {
                $report['agents_checked']++;

                $profile = $this->context->getBehaviorProfile($agentId);
                if ($profile === null) {
                    $report['suggestions'][] = [
                        'agent_id' => $agentId,
                        'suggestion' => 'create_behavior_profile',
                    ];
                    continue;
                }

                $report['agents_with_profiles']++;

                $tendency = $profile['tendency'] ?? 'neutral';
                $tendencyCounts[$tendency] = ($tendencyCounts[$tendency] ?? 0) + 1;
            }

            // Identify divergence (agents with uncommon tendencies)
            $totalProfiles = $report['agents_with_profiles'];
            foreach ($tendencyCounts as $tendency => $count) {
                $percentage = ($count / max($totalProfiles, 1)) * 100;
                if ($percentage < 20 && $totalProfiles > 3) {
                    $report['divergence'][] = [
                        'tendency' => $tendency,
                        'count' => $count,
                        'percentage' => round($percentage, 2),
                        'note' => 'Uncommon tendency detected',
                    ];
                }
            }

            return $report;
        } catch (\Exception $e) {
            error_log("PackSyncEngine::synchronizeBehavior() error: " . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'agents_checked' => 0,
            ];
        }
    }

    /**
     * Synchronize memory structures across all agents
     *
     * @return array Sync report
     */
    public function synchronizeMemory(): array
    {
        try {
            $agents = $this->registry->listAgents();
            $report = [
                'agents_checked' => 0,
                'episodic_issues' => [],
                'emotional_issues' => [],
                'behavioral_issues' => [],
                'fixed' => [],
            ];

            foreach ($agents as $agentId => $agentData) {
                $report['agents_checked']++;

                // Check episodic memory
                $episodicEvents = $this->context->getEpisodicEvents($agentId);
                foreach ($episodicEvents as $event) {
                    if (!isset($event['timestamp']) || !isset($event['type'])) {
                        $report['episodic_issues'][] = [
                            'agent_id' => $agentId,
                            'issue' => 'missing_required_fields',
                            'event' => $event,
                        ];
                    }
                }

                // Check emotional memory
                $emotionalSnapshots = $this->context->getEmotionalSnapshots($agentId);
                foreach ($emotionalSnapshots as $snapshot) {
                    if (!isset($snapshot['r']) || !isset($snapshot['g']) || !isset($snapshot['b'])) {
                        $report['emotional_issues'][] = [
                            'agent_id' => $agentId,
                            'issue' => 'missing_rgb_fields',
                            'snapshot' => $snapshot,
                        ];
                    }
                }

                // Check behavioral memory
                $behaviorSnapshots = $this->context->getBehaviorSnapshots($agentId);
                foreach ($behaviorSnapshots as $snapshot) {
                    if (!isset($snapshot['tendency']) || !isset($snapshot['intensity'])) {
                        $report['behavioral_issues'][] = [
                            'agent_id' => $agentId,
                            'issue' => 'missing_required_fields',
                            'snapshot' => $snapshot,
                        ];
                    }
                }
            }

            return $report;
        } catch (\Exception $e) {
            error_log("PackSyncEngine::synchronizeMemory() error: " . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'agents_checked' => 0,
            ];
        }
    }

    /**
     * Run full synchronization
     *
     * @return array Unified sync payload
     */
    public function fullSync(): array
    {
        try {
            $emotionsReport = $this->synchronizeEmotions();
            $behaviorReport = $this->synchronizeBehavior();
            $memoryReport = $this->synchronizeMemory();

            // Determine overall status
            $status = 'ok';
            $hasErrors = false;
            $hasWarnings = false;

            if (isset($emotionsReport['error']) || isset($behaviorReport['error']) || isset($memoryReport['error'])) {
                $hasErrors = true;
                $status = 'error';
            } elseif (!empty($emotionsReport['outliers']) || !empty($emotionsReport['warnings']) ||
                      !empty($behaviorReport['divergence']) || !empty($memoryReport['episodic_issues']) ||
                      !empty($memoryReport['emotional_issues']) || !empty($memoryReport['behavioral_issues'])) {
                $hasWarnings = true;
                $status = 'warning';
            }

            return [
                'emotions' => $emotionsReport,
                'behavior' => $behaviorReport,
                'memory' => $memoryReport,
                'status' => $status,
                'timestamp' => gmdate('YmdHis'),
            ];
        } catch (\Exception $e) {
            error_log("PackSyncEngine::fullSync() error: " . $e->getMessage());
            return [
                'emotions' => ['error' => $e->getMessage()],
                'behavior' => ['error' => $e->getMessage()],
                'memory' => ['error' => $e->getMessage()],
                'status' => 'error',
                'timestamp' => gmdate('YmdHis'),
            ];
        }
    }
}
