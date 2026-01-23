<?php
/**
 * Pack Memory Service
 *
 * Public API service for Pack memory operations.
 * Wraps PackMemoryEngine and PackContext for controllers and agents.
 *
 * @package Lupopedia
 * @version 4.0.110
 * @author Captain Wolfie
 */

namespace App\Services\Pack;

use Lupopedia\Pack\Memory\PackMemoryEngine;
use Lupopedia\Pack\PackContext;

/**
 * PackMemoryService
 *
 * Provides public API for Pack memory operations.
 */
class PackMemoryService
{
    /** @var PackMemoryEngine Memory engine instance */
    private $memoryEngine;

    /** @var PackContext Pack context instance */
    private $context;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->memoryEngine = new PackMemoryEngine();
        $this->context = new PackContext();
    }

    /**
     * Get episodic memory for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of events to return
     * @return array Episodic events
     */
    public function episodic(string $agentId, ?int $limit = null): array
    {
        return $this->context->getEpisodicEvents($agentId, $limit);
    }

    /**
     * Get emotional memory for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of snapshots to return
     * @return array Emotional snapshots
     */
    public function emotional(string $agentId, ?int $limit = null): array
    {
        return $this->context->getEmotionalSnapshots($agentId, $limit);
    }

    /**
     * Get behavioral memory for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of snapshots to return
     * @return array Behavior snapshots
     */
    public function behavioral(string $agentId, ?int $limit = null): array
    {
        return $this->context->getBehaviorSnapshots($agentId, $limit);
    }

    /**
     * Get handoff memory for an agent
     *
     * @param string $agentId Agent identifier
     * @return array Handoff events
     */
    public function handoffs(string $agentId): array
    {
        return $this->context->getHandoffMemory($agentId);
    }

    /**
     * Record memory event
     *
     * @param string $agentId Agent identifier
     * @param string $type Event type
     * @param array $payload Event payload
     * @return bool Success status
     */
    public function record(string $agentId, string $type, array $payload): bool
    {
        $event = [
            'type' => $type,
            'payload' => $payload,
        ];

        return $this->context->addEpisodicEvent($agentId, $event);
    }
}
