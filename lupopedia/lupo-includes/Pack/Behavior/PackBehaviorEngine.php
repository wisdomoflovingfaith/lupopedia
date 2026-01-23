<?php
/**
 * Pack Behavior Engine
 *
 * Determines agent behavior based on emotional geometry and context.
 * Implements behavioral layer for Pack Architecture.
 *
 * @package Lupopedia
 * @version 4.0.109
 * @author Captain Wolfie
 */

namespace Lupopedia\Pack\Behavior;

use Lupopedia\EmotionalGeometry\EmotionalGeometryEngine;

/**
 * PackBehaviorEngine
 *
 * Provides behavior determination and adjustment for Pack agents.
 */
class PackBehaviorEngine
{
    /** @var EmotionalGeometryEngine Emotional geometry engine instance */
    private $geometryEngine;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->geometryEngine = new EmotionalGeometryEngine();
    }

    /**
     * Determine behavior based on emotional vector and context
     *
     * @param string $agentId Agent identifier
     * @param array|null $emotionVector Encoded emotional geometry (or null for neutral)
     * @param array $context Contextual information
     * @return string Behavior label
     */
    public function determineBehavior(string $agentId, ?array $emotionVector, array $context = []): string
    {
        try {
            // If no emotional vector, return neutral
            if ($emotionVector === null || empty($emotionVector)) {
                return 'neutral';
            }

            // Extract normalized components
            $normalized = $emotionVector['normalized'] ?? null;
            if ($normalized === null) {
                return 'neutral';
            }

            $r = $normalized['r'] ?? 0.0;
            $g = $normalized['g'] ?? 0.0;
            $b = $normalized['b'] ?? 0.0;

            // Get intensity
            $intensity = $emotionVector['intensity'] ?? 0.0;

            // Determine behavior based on emotional geometry
            // High positive R + moderate intensity → supportive
            if ($r > 0.5 && $intensity > 0.5 && $intensity < 1.2) {
                return 'supportive';
            }

            // High positive R + high intensity → assertive
            if ($r > 0.5 && $intensity >= 1.2) {
                return 'assertive';
            }

            // High positive G + moderate intensity → analytical
            if ($g > 0.5 && $intensity > 0.5 && $intensity < 1.2) {
                return 'analytical';
            }

            // High positive B + moderate intensity → protective
            if ($b > 0.5 && $intensity > 0.5 && $intensity < 1.2) {
                return 'protective';
            }

            // Negative components or low intensity → neutral
            if ($intensity < 0.3 || ($r < 0 && $g < 0 && $b < 0)) {
                return 'neutral';
            }

            // Default to neutral
            return 'neutral';
        } catch (\Exception $e) {
            error_log("PackBehaviorEngine::determineBehavior() error: " . $e->getMessage());
            return 'neutral';
        }
    }

    /**
     * Adjust behavioral tendency based on PackContext events
     *
     * @param string $agentId Agent identifier
     * @param float $delta Adjustment delta (-1.0 to 1.0)
     * @return bool Success status
     */
    public function adjustBehavior(string $agentId, float $delta): bool
    {
        try {
            // Clamp delta to valid range
            $delta = max(-1.0, min(1.0, $delta));

            // This method is a placeholder for future behavioral adjustment logic
            // In a full implementation, this would update behavior profiles based on events
            error_log("PackBehaviorEngine::adjustBehavior() called for agent '{$agentId}' with delta: {$delta}");

            return true;
        } catch (\Exception $e) {
            error_log("PackBehaviorEngine::adjustBehavior() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get behavior profile for an agent
     *
     * @param string $agentId Agent identifier
     * @param array|null $emotionVector Current emotional vector
     * @return array Behavior profile
     */
    public function getBehaviorProfile(string $agentId, ?array $emotionVector = null): array
    {
        try {
            $behavior = $this->determineBehavior($agentId, $emotionVector);
            $intensity = $emotionVector['intensity'] ?? 0.0;

            return [
                'agent_id' => $agentId,
                'tendency' => $behavior,
                'intensity' => $intensity,
                'last_update' => gmdate('YmdHis'),
            ];
        } catch (\Exception $e) {
            error_log("PackBehaviorEngine::getBehaviorProfile() error: " . $e->getMessage());
            return [
                'agent_id' => $agentId,
                'tendency' => 'neutral',
                'intensity' => 0.0,
                'last_update' => gmdate('YmdHis'),
            ];
        }
    }

    /**
     * Record behavior event
     *
     * @param string $agentId Agent identifier
     * @param array $event Event data
     * @return bool Success status
     */
    public function recordBehaviorEvent(string $agentId, array $event): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackBehaviorEngine::recordBehaviorEvent() error: Agent ID is required");
                return false;
            }

            // Add timestamp and agent ID to event
            $event['agent_id'] = $agentId;
            $event['timestamp'] = gmdate('YmdHis');

            // This method is a placeholder for future event recording logic
            // In a full implementation, this would store events in PackContext
            error_log("PackBehaviorEngine::recordBehaviorEvent() called for agent '{$agentId}': " . json_encode($event));

            return true;
        } catch (\Exception $e) {
            error_log("PackBehaviorEngine::recordBehaviorEvent() error: " . $e->getMessage());
            return false;
        }
    }
}
