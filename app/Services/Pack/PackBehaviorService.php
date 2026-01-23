<?php
/**
 * Pack Behavior Service
 *
 * Public API service for Pack behavioral operations.
 * Wraps PackBehaviorEngine for controllers and agents.
 *
 * @package Lupopedia
 * @version 4.0.109
 * @author Captain Wolfie
 */

namespace App\Services\Pack;

use Lupopedia\Pack\Behavior\PackBehaviorEngine;
use Lupopedia\Pack\PackContext;
use Lupopedia\Pack\PackHandoffProtocol;

/**
 * PackBehaviorService
 *
 * Provides public API for Pack behavioral operations.
 */
class PackBehaviorService
{
    /** @var PackBehaviorEngine Behavior engine instance */
    private $behaviorEngine;

    /** @var PackContext Pack context instance */
    private $context;

    /** @var PackHandoffProtocol Handoff protocol instance */
    private $handoffProtocol;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->behaviorEngine = new PackBehaviorEngine();
        $this->context = new PackContext();
        $this->handoffProtocol = new PackHandoffProtocol($this->context);
    }

    /**
     * Get behavior profile for an agent
     *
     * @param string $agentId Agent identifier
     * @return array Behavior profile
     */
    public function getProfile(string $agentId): array
    {
        // Try to get from context first
        $profile = $this->context->getBehaviorProfile($agentId);

        if ($profile === null) {
            // Generate profile from current emotional vector
            $emotionVector = $this->context->getEmotionVector($agentId);
            $profile = $this->behaviorEngine->getBehaviorProfile($agentId, $emotionVector);
            // Store in context
            $this->context->setBehaviorProfile($agentId, $profile);
        }

        return $profile;
    }

    /**
     * Set behavior profile for an agent
     *
     * @param string $agentId Agent identifier
     * @param array $profile Behavior profile
     * @return bool Success status
     */
    public function setProfile(string $agentId, array $profile): bool
    {
        return $this->context->setBehaviorProfile($agentId, $profile);
    }

    /**
     * Determine behavior based on emotional vector and context
     *
     * @param string $agentId Agent identifier
     * @param array|null $emotionVector Encoded emotional geometry
     * @param array $context Contextual information
     * @return string Behavior label
     */
    public function determine(string $agentId, ?array $emotionVector, array $context = []): string
    {
        $behavior = $this->behaviorEngine->determineBehavior($agentId, $emotionVector, $context);

        // Update profile in context
        $profile = $this->behaviorEngine->getBehaviorProfile($agentId, $emotionVector);
        $this->context->setBehaviorProfile($agentId, $profile);

        return $behavior;
    }

    /**
     * Get behavioral compatibility between two agents
     *
     * @param string $fromAgent Source agent identifier
     * @param string $toAgent Target agent identifier
     * @return array Compatibility assessment
     */
    public function compatibility(string $fromAgent, string $toAgent): array
    {
        return $this->handoffProtocol->evaluateBehavioralCompatibility($fromAgent, $toAgent);
    }
}
