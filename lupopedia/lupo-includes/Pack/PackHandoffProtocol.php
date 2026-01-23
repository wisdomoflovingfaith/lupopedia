<?php
/**
 * Pack Handoff Protocol
 *
 * Manages agent handoff coordination for Pack Architecture.
 * Prepares system for Pack Architecture activation in 4.1.0.
 *
 * Emotional Geometry Axis Mapping:
 * - R (Red) = +1 axis (positive pole)
 * - G (Green) = 0 axis (neutral plane)
 * - B (Blue) = -1 axis (negative pole)
 *
 * @package Lupopedia
 * @version 4.0.114
 * @author Captain Wolfie
 */

namespace Lupopedia\Pack;

use Lupopedia\Pack\PackContext;
use Lupopedia\Pack\PackRegistry;
use Lupopedia\EmotionalGeometry\EmotionalGeometryEngine;

/**
 * PackHandoffProtocol
 *
 * Coordinates agent handoffs within Pack Architecture.
 */
class PackHandoffProtocol
{
    /** @var PackContext Pack context instance */
    private $context;

    /** @var PackRegistry Pack registry instance */
    private $registry;

    /** @var EmotionalGeometryEngine Emotional geometry engine instance */
    private $geometryEngine;

    /**
     * Constructor
     *
     * @param PackContext $context Pack context instance
     * @param PackRegistry|null $registry Pack registry instance (optional)
     */
    public function __construct(PackContext $context, PackRegistry $registry = null)
    {
        $this->context = $context;
        $this->registry = $registry ?? new PackRegistry();
        $this->geometryEngine = new EmotionalGeometryEngine();
    }

    /**
     * Request handoff from one agent to another
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @param string $reason Handoff reason
     * @return array Handoff result with status and message
     */
    public function requestHandoff(string $fromAgent, string $toAgent, string $reason): array
    {
        try {
            // Validate handoff
            $validation = $this->validateHandoff($fromAgent, $toAgent, $reason);
            if (!$validation['valid']) {
                return [
                    'status' => 'error',
                    'message' => $validation['message'],
                ];
            }

            // Record emotional state at handoff moment
            $fromEmotion = $this->context->getEmotion($fromAgent);
            $toEmotion = $this->context->getEmotion($toAgent);

            // Record handoff
            $recorded = $this->recordHandoff($fromAgent, $toAgent, $reason, [
                'from_emotion' => $fromEmotion,
                'to_emotion' => $toEmotion,
            ]);

            if (!$recorded) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to record handoff',
                ];
            }

            return [
                'status' => 'ok',
                'message' => "Handoff from {$fromAgent} to {$toAgent} completed",
            ];
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::requestHandoff() error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Exception during handoff: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Record handoff in context
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @param string $reason Handoff reason
     * @param array $emotionData Optional emotional metadata
     * @return bool Success status
     */
    public function recordHandoff(string $fromAgent, string $toAgent, string $reason, array $emotionData = []): bool
    {
        try {
            $handoffInfo = [
                'from_agent' => $fromAgent,
                'to_agent' => $toAgent,
                'reason' => $reason,
            ];

            // Include emotional metadata if provided
            if (!empty($emotionData)) {
                $handoffInfo['emotion_data'] = $emotionData;
            }

            $this->context->trackLastHandoff($handoffInfo);
            $this->context->trackActiveAgent($toAgent);

            return true;
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::recordHandoff() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate handoff request
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @param string $reason Handoff reason
     * @return array Validation result with 'valid' and 'message' keys
     */
    public function validateHandoff(string $fromAgent, string $toAgent, string $reason): array
    {
        // Basic validation
        if (empty($fromAgent)) {
            return [
                'valid' => false,
                'message' => 'Source agent identifier is required',
            ];
        }

        if (empty($toAgent)) {
            return [
                'valid' => false,
                'message' => 'Target agent identifier is required',
            ];
        }

        if ($fromAgent === $toAgent) {
            return [
                'valid' => false,
                'message' => 'Source and target agents cannot be the same',
            ];
        }

        if (empty($reason)) {
            return [
                'valid' => false,
                'message' => 'Handoff reason is required',
            ];
        }

        // Validate both agents exist in registry
        $fromAgentData = $this->registry->getAgent($fromAgent);
        if ($fromAgentData === null) {
            return [
                'valid' => false,
                'message' => "Source agent '{$fromAgent}' is not registered",
            ];
        }

        $toAgentData = $this->registry->getAgent($toAgent);
        if ($toAgentData === null) {
            return [
                'valid' => false,
                'message' => "Target agent '{$toAgent}' is not registered",
            ];
        }

        // Ensure emotional metadata exists (optional but recommended)
        $fromEmotion = $this->context->getEmotion($fromAgent);
        $toEmotion = $this->context->getEmotion($toAgent);

        // Note: Emotional metadata is optional, so we don't fail validation if missing
        // But we log a warning if it's missing
        if ($fromEmotion === null) {
            error_log("PackHandoffProtocol::validateHandoff() warning: No emotional metadata for source agent '{$fromAgent}'");
        }

        if ($toEmotion === null) {
            error_log("PackHandoffProtocol::validateHandoff() warning: No emotional metadata for target agent '{$toAgent}'");
        }

        return [
            'valid' => true,
            'message' => 'Handoff validation passed',
        ];
    }

    /**
     * Calibrate handoff between two agents
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @return array Calibration payload
     */
    public function calibrateHandoff(string $fromAgent, string $toAgent): array
    {
        try {
            $vectorA = $this->context->getEmotionVector($fromAgent);
            $vectorB = $this->context->getEmotionVector($toAgent);

            if ($vectorA === null || $vectorB === null) {
                return [
                    'affinity' => 0.0,
                    'intensity_delta' => 0.0,
                    'handoff_quality' => 'low',
                    'error' => 'Missing emotional vectors for one or both agents',
                ];
            }

            // Calculate affinity
            $rgbA = ['r' => $vectorA['r'], 'g' => $vectorA['g'], 'b' => $vectorA['b']];
            $rgbB = ['r' => $vectorB['r'], 'g' => $vectorB['g'], 'b' => $vectorB['b']];
            $affinity = $this->geometryEngine->calculateAffinity($rgbA, $rgbB);

            // Calculate intensity delta
            $intensityA = $vectorA['intensity'] ?? 0.0;
            $intensityB = $vectorB['intensity'] ?? 0.0;
            $intensityDelta = abs($intensityA - $intensityB);

            // Determine handoff quality
            $handoffQuality = 'medium';
            if ($affinity >= 0.7 && $intensityDelta <= 0.5) {
                $handoffQuality = 'high';
            } elseif ($affinity < 0.3 || $intensityDelta > 1.5) {
                $handoffQuality = 'low';
            }

            return [
                'affinity' => $affinity,
                'intensity_delta' => $intensityDelta,
                'handoff_quality' => $handoffQuality,
                'from_intensity' => $intensityA,
                'to_intensity' => $intensityB,
            ];
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::calibrateHandoff() error: " . $e->getMessage());
            return [
                'affinity' => 0.0,
                'intensity_delta' => 0.0,
                'handoff_quality' => 'low',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Record calibrated handoff
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @param string $reason Handoff reason
     * @return bool Success status
     */
    public function recordCalibratedHandoff(string $fromAgent, string $toAgent, string $reason): bool
    {
        try {
            // Calibrate handoff
            $calibration = $this->calibrateHandoff($fromAgent, $toAgent);

            // Record handoff with calibration data
            $handoffInfo = [
                'from_agent' => $fromAgent,
                'to_agent' => $toAgent,
                'reason' => $reason,
                'calibration' => $calibration,
            ];

            $this->context->trackLastHandoff($handoffInfo);
            $this->context->trackActiveAgent($toAgent);

            return true;
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::recordCalibratedHandoff() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Evaluate behavioral compatibility between two agents
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @return array Compatibility assessment
     */
    public function evaluateBehavioralCompatibility(string $fromAgent, string $toAgent): array
    {
        try {
            // Get behavior profiles
            $profileA = $this->context->getBehaviorProfile($fromAgent);
            $profileB = $this->context->getBehaviorProfile($toAgent);

            // Get emotional vectors for affinity calculation
            $vectorA = $this->context->getEmotionVector($fromAgent);
            $vectorB = $this->context->getEmotionVector($toAgent);

            // Calculate emotional affinity
            $affinity = 0.0;
            if ($vectorA !== null && $vectorB !== null) {
                $rgbA = ['r' => $vectorA['r'], 'g' => $vectorA['g'], 'b' => $vectorA['b']];
                $rgbB = ['r' => $vectorB['r'], 'g' => $vectorB['g'], 'b' => $vectorB['b']];
                $affinity = $this->geometryEngine->calculateAffinity($rgbA, $rgbB);
            }

            // Compare behavior profiles
            $tendencyA = $profileA['tendency'] ?? 'neutral';
            $tendencyB = $profileB['tendency'] ?? 'neutral';

            // Calculate compatibility score
            $compatibility = 0.5; // Base compatibility

            // Same tendency increases compatibility
            if ($tendencyA === $tendencyB) {
                $compatibility += 0.3;
            }

            // Complementary behaviors increase compatibility
            $complementaryPairs = [
                'supportive' => 'analytical',
                'analytical' => 'supportive',
                'assertive' => 'protective',
                'protective' => 'assertive',
            ];

            if (isset($complementaryPairs[$tendencyA]) && $complementaryPairs[$tendencyA] === $tendencyB) {
                $compatibility += 0.2;
            }

            // Emotional affinity influences compatibility
            $compatibility = ($compatibility + $affinity) / 2;

            // Clamp to [0, 1] range
            $compatibility = max(0.0, min(1.0, $compatibility));

            // Determine risk level
            $risk = 'medium';
            if ($compatibility >= 0.7) {
                $risk = 'low';
            } elseif ($compatibility < 0.4) {
                $risk = 'high';
            }

            return [
                'compatibility' => round($compatibility, 3),
                'risk' => $risk,
                'tendency_a' => $tendencyA,
                'tendency_b' => $tendencyB,
                'affinity' => round($affinity, 3),
            ];
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::evaluateBehavioralCompatibility() error: " . $e->getMessage());
            return [
                'compatibility' => 0.5,
                'risk' => 'medium',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Record behavioral transition
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @return bool Success status
     */
    public function recordBehavioralTransition(string $fromAgent, string $toAgent): bool
    {
        try {
            // Evaluate compatibility
            $compatibility = $this->evaluateBehavioralCompatibility($fromAgent, $toAgent);

            // Record in PackContext
            $transition = [
                'from_agent' => $fromAgent,
                'to_agent' => $toAgent,
                'compatibility' => $compatibility['compatibility'],
                'risk' => $compatibility['risk'],
                'timestamp' => gmdate('YmdHis'),
            ];

            $this->context->recordBehaviorEvent($fromAgent, [
                'type' => 'behavioral_transition',
                'to_agent' => $toAgent,
                'compatibility' => $compatibility,
            ]);

            $this->context->recordBehaviorEvent($toAgent, [
                'type' => 'behavioral_transition',
                'from_agent' => $fromAgent,
                'compatibility' => $compatibility,
            ]);

            return true;
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::recordBehavioralTransition() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Record handoff memory
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @param array $calibration Calibration data
     * @return bool Success status
     */
    public function recordHandoffMemory(string $fromAgent, string $toAgent, array $calibration): bool
    {
        try {
            $handoffEvent = [
                'from_agent' => $fromAgent,
                'to_agent' => $toAgent,
                'calibration' => $calibration,
                'timestamp' => gmdate('YmdHis'),
            ];

            $this->context->addHandoffMemory($handoffEvent);

            return true;
        } catch (\Exception $e) {
            error_log("PackHandoffProtocol::recordHandoffMemory() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get handoff history for an agent
     *
     * @param string $agentId Agent identifier
     * @return array Handoff events involving the agent
     */
    public function getHandoffHistory(string $agentId): array
    {
        return $this->context->getHandoffMemory($agentId);
    }
}
