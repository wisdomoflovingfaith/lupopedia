<?php
/**
 * Pack Context
 *
 * Stores ephemeral emotional metadata and tracks active agent state.
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

use Lupopedia\EmotionalGeometry\EmotionalGeometryEngine;

/**
 * PackContext
 *
 * Manages ephemeral context for Pack Architecture coordination.
 */
class PackContext
{
    /** @var array Ephemeral emotional metadata */
    private $emotionalMetadata = [];

    /** @var array Agent-specific emotional metadata */
    private $agentEmotions = [];

    /** @var array Agent-specific encoded emotional geometry */
    private $agentEmotionVectors = [];

    /** @var array Agent-specific behavior profiles */
    private $agentBehaviorProfiles = [];

    /** @var array Agent-specific behavior event history */
    private $agentBehaviorHistory = [];

    /** @var array Agent-specific episodic memory */
    private $agentEpisodicMemory = [];

    /** @var array Agent-specific emotional memory snapshots */
    private $agentEmotionalMemory = [];

    /** @var array Agent-specific behavior memory snapshots */
    private $agentBehaviorMemory = [];

    /** @var array Handoff memory (all handoff events) */
    private $handoffMemory = [];

    /** @var string|null Last sync timestamp */
    private $lastSyncTimestamp = null;

    /** @var array|null Last sync report */
    private $lastSyncReport = null;

    /** @var string|null Currently active agent ID */
    private $activeAgent = null;

    /** @var array|null Last handoff information */
    private $lastHandoff = null;

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
     * Store ephemeral emotional metadata
     *
     * @param string $key Metadata key
     * @param mixed $value Metadata value
     * @return bool Success status
     */
    public function storeEmotionalMetadata(string $key, $value): bool
    {
        try {
            $this->emotionalMetadata[$key] = [
                'value' => $value,
                'timestamp' => gmdate('YmdHis'),
            ];
            return true;
        } catch (\Exception $e) {
            error_log("PackContext::storeEmotionalMetadata() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get emotional metadata
     *
     * @param string $key Metadata key
     * @return mixed|null Metadata value or null if not found
     */
    public function getEmotionalMetadata(string $key)
    {
        return $this->emotionalMetadata[$key]['value'] ?? null;
    }

    /**
     * Set emotional metadata for an agent
     *
     * @param string $agentId Agent identifier
     * @param array|string $emotionVector Emotional metadata (array or string)
     * @return bool Success status
     */
    public function setEmotion(string $agentId, $emotionVector): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::setEmotion() error: Agent ID is required");
                return false;
            }

            $this->agentEmotions[$agentId] = [
                'emotion' => $emotionVector,
                'timestamp' => gmdate('YmdHis'),
            ];

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::setEmotion() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get emotional metadata for an agent
     *
     * @param string $agentId Agent identifier
     * @return array|string|null Emotional metadata or null if not found
     */
    public function getEmotion(string $agentId)
    {
        return $this->agentEmotions[$agentId]['emotion'] ?? null;
    }

    /**
     * Track active agent
     *
     * @param string $agentId Agent identifier
     * @return bool Success status
     */
    public function trackActiveAgent(string $agentId): bool
    {
        try {
            $this->activeAgent = $agentId;
            return true;
        } catch (\Exception $e) {
            error_log("PackContext::trackActiveAgent() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Set active agent (alias for trackActiveAgent)
     *
     * @param string $agentId Agent identifier
     * @return bool Success status
     */
    public function setActiveAgent(string $agentId): bool
    {
        return $this->trackActiveAgent($agentId);
    }

    /**
     * Get active agent
     *
     * @return string|null Active agent ID or null
     */
    public function getActiveAgent(): ?string
    {
        return $this->activeAgent;
    }

    /**
     * Track last handoff
     *
     * @param array $handoffInfo Handoff information
     * @return bool Success status
     */
    public function trackLastHandoff(array $handoffInfo): bool
    {
        try {
            $this->lastHandoff = array_merge($handoffInfo, [
                'timestamp' => gmdate('YmdHis'),
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("PackContext::trackLastHandoff() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get last handoff information
     *
     * @return array|null Last handoff information or null
     */
    public function getLastHandoff(): ?array
    {
        return $this->lastHandoff;
    }

    /**
     * Set emotional vector for an agent (uses EmotionalGeometryEngine)
     *
     * @param string $agentId Agent identifier
     * @param array|string $rgb RGB values
     * @return bool Success status
     */
    public function setEmotionVector(string $agentId, $rgb): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::setEmotionVector() error: Agent ID is required");
                return false;
            }

            $encoded = $this->geometryEngine->encode($rgb);
            $this->agentEmotionVectors[$agentId] = $encoded;

            // Also update legacy emotion storage for backward compatibility
            $this->setEmotion($agentId, $rgb);

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::setEmotionVector() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get encoded emotional vector for an agent
     *
     * @param string $agentId Agent identifier
     * @return array|null Encoded emotional geometry or null if not found
     */
    public function getEmotionVector(string $agentId): ?array
    {
        return $this->agentEmotionVectors[$agentId] ?? null;
    }

    /**
     * Get emotional intensity for an agent
     *
     * @param string $agentId Agent identifier
     * @return float|null Intensity value or null if not found
     */
    public function getEmotionIntensity(string $agentId): ?float
    {
        $vector = $this->getEmotionVector($agentId);
        return $vector['intensity'] ?? null;
    }

    /**
     * Get affinity between two agents
     *
     * @param string $agentA First agent identifier
     * @param string $agentB Second agent identifier
     * @return float|null Affinity value or null if either agent not found
     */
    public function getAffinityBetween(string $agentA, string $agentB): ?float
    {
        $vectorA = $this->getEmotionVector($agentA);
        $vectorB = $this->getEmotionVector($agentB);

        if ($vectorA === null || $vectorB === null) {
            return null;
        }

        // Extract RGB values from encoded vectors
        $rgbA = ['r' => $vectorA['r'], 'g' => $vectorA['g'], 'b' => $vectorA['b']];
        $rgbB = ['r' => $vectorB['r'], 'g' => $vectorB['g'], 'b' => $vectorB['b']];

        return $this->geometryEngine->calculateAffinity($rgbA, $rgbB);
    }

    /**
     * Set behavior profile for an agent
     *
     * @param string $agentId Agent identifier
     * @param array $profile Behavior profile
     * @return bool Success status
     */
    public function setBehaviorProfile(string $agentId, array $profile): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::setBehaviorProfile() error: Agent ID is required");
                return false;
            }

            // Ensure required fields
            $profile['agent_id'] = $agentId;
            $profile['last_update'] = $profile['last_update'] ?? gmdate('YmdHis');

            $this->agentBehaviorProfiles[$agentId] = $profile;

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::setBehaviorProfile() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get behavior profile for an agent
     *
     * @param string $agentId Agent identifier
     * @return array|null Behavior profile or null if not found
     */
    public function getBehaviorProfile(string $agentId): ?array
    {
        return $this->agentBehaviorProfiles[$agentId] ?? null;
    }

    /**
     * Record behavior event for an agent
     *
     * @param string $agentId Agent identifier
     * @param array $event Event data
     * @return bool Success status
     */
    public function recordBehaviorEvent(string $agentId, array $event): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::recordBehaviorEvent() error: Agent ID is required");
                return false;
            }

            // Initialize history array if needed
            if (!isset($this->agentBehaviorHistory[$agentId])) {
                $this->agentBehaviorHistory[$agentId] = [];
            }

            // Add event with timestamp
            $event['timestamp'] = gmdate('YmdHis');
            $this->agentBehaviorHistory[$agentId][] = $event;

            // Keep only last 100 events per agent
            if (count($this->agentBehaviorHistory[$agentId]) > 100) {
                $this->agentBehaviorHistory[$agentId] = array_slice($this->agentBehaviorHistory[$agentId], -100);
            }

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::recordBehaviorEvent() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get behavior history for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of events to return
     * @return array Behavior event history
     */
    public function getBehaviorHistory(string $agentId, ?int $limit = null): array
    {
        $history = $this->agentBehaviorHistory[$agentId] ?? [];

        if ($limit !== null && $limit > 0) {
            return array_slice($history, -$limit);
        }

        return $history;
    }

    /**
     * Add episodic event for an agent
     *
     * @param string $agentId Agent identifier
     * @param array $event Event data
     * @return bool Success status
     */
    public function addEpisodicEvent(string $agentId, array $event): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::addEpisodicEvent() error: Agent ID is required");
                return false;
            }

            // Initialize episodic memory array if needed
            if (!isset($this->agentEpisodicMemory[$agentId])) {
                $this->agentEpisodicMemory[$agentId] = [];
            }

            // Structure event
            $structuredEvent = [
                'timestamp' => gmdate('YmdHis'),
                'type' => $event['type'] ?? 'unknown',
                'payload' => $event['payload'] ?? $event,
                'agent_id' => $agentId,
            ];

            $this->agentEpisodicMemory[$agentId][] = $structuredEvent;

            // Keep only last 100 events per agent
            if (count($this->agentEpisodicMemory[$agentId]) > 100) {
                $this->agentEpisodicMemory[$agentId] = array_slice($this->agentEpisodicMemory[$agentId], -100);
            }

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::addEpisodicEvent() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get episodic events for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of events to return
     * @return array Episodic events
     */
    public function getEpisodicEvents(string $agentId, ?int $limit = null): array
    {
        $events = $this->agentEpisodicMemory[$agentId] ?? [];

        if ($limit !== null && $limit > 0) {
            return array_slice($events, -$limit);
        }

        return $events;
    }

    /**
     * Add emotional snapshot for an agent
     *
     * @param string $agentId Agent identifier
     * @param array $encodedVector Encoded emotional geometry
     * @return bool Success status
     */
    public function addEmotionalSnapshot(string $agentId, array $encodedVector): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::addEmotionalSnapshot() error: Agent ID is required");
                return false;
            }

            // Initialize emotional memory array if needed
            if (!isset($this->agentEmotionalMemory[$agentId])) {
                $this->agentEmotionalMemory[$agentId] = [];
            }

            // Add timestamp
            $snapshot = $encodedVector;
            $snapshot['timestamp'] = gmdate('YmdHis');
            $snapshot['agent_id'] = $agentId;

            $this->agentEmotionalMemory[$agentId][] = $snapshot;

            // Keep only last 50 snapshots per agent
            if (count($this->agentEmotionalMemory[$agentId]) > 50) {
                $this->agentEmotionalMemory[$agentId] = array_slice($this->agentEmotionalMemory[$agentId], -50);
            }

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::addEmotionalSnapshot() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get emotional snapshots for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of snapshots to return
     * @return array Emotional snapshots
     */
    public function getEmotionalSnapshots(string $agentId, ?int $limit = null): array
    {
        $snapshots = $this->agentEmotionalMemory[$agentId] ?? [];

        if ($limit !== null && $limit > 0) {
            return array_slice($snapshots, -$limit);
        }

        return $snapshots;
    }

    /**
     * Add behavior snapshot for an agent
     *
     * @param string $agentId Agent identifier
     * @param array $profile Behavior profile
     * @return bool Success status
     */
    public function addBehaviorSnapshot(string $agentId, array $profile): bool
    {
        try {
            if (empty($agentId)) {
                error_log("PackContext::addBehaviorSnapshot() error: Agent ID is required");
                return false;
            }

            // Initialize behavior memory array if needed
            if (!isset($this->agentBehaviorMemory[$agentId])) {
                $this->agentBehaviorMemory[$agentId] = [];
            }

            // Add timestamp
            $snapshot = $profile;
            $snapshot['timestamp'] = gmdate('YmdHis');
            $snapshot['agent_id'] = $agentId;

            $this->agentBehaviorMemory[$agentId][] = $snapshot;

            // Keep only last 50 snapshots per agent
            if (count($this->agentBehaviorMemory[$agentId]) > 50) {
                $this->agentBehaviorMemory[$agentId] = array_slice($this->agentBehaviorMemory[$agentId], -50);
            }

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::addBehaviorSnapshot() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get behavior snapshots for an agent
     *
     * @param string $agentId Agent identifier
     * @param int|null $limit Maximum number of snapshots to return
     * @return array Behavior snapshots
     */
    public function getBehaviorSnapshots(string $agentId, ?int $limit = null): array
    {
        $snapshots = $this->agentBehaviorMemory[$agentId] ?? [];

        if ($limit !== null && $limit > 0) {
            return array_slice($snapshots, -$limit);
        }

        return $snapshots;
    }

    /**
     * Add handoff memory event
     *
     * @param array $handoffEvent Handoff event data
     * @return bool Success status
     */
    public function addHandoffMemory(array $handoffEvent): bool
    {
        try {
            $handoffEvent['timestamp'] = gmdate('YmdHis');
            $this->handoffMemory[] = $handoffEvent;

            // Keep only last 200 handoff events
            if (count($this->handoffMemory) > 200) {
                $this->handoffMemory = array_slice($this->handoffMemory, -200);
            }

            return true;
        } catch (\Exception $e) {
            error_log("PackContext::addHandoffMemory() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get handoff memory for an agent
     *
     * @param string $agentId Agent identifier
     * @return array Handoff events involving the agent
     */
    public function getHandoffMemory(string $agentId): array
    {
        $filtered = array_filter($this->handoffMemory, function ($event) use ($agentId) {
            return ($event['from_agent'] ?? null) === $agentId || ($event['to_agent'] ?? null) === $agentId;
        });

        return array_values($filtered);
    }

    /**
     * Set last sync timestamp
     *
     * @param string $timestamp Sync timestamp
     * @return bool Success status
     */
    public function setLastSyncTimestamp(string $timestamp): bool
    {
        try {
            $this->lastSyncTimestamp = $timestamp;
            return true;
        } catch (\Exception $e) {
            error_log("PackContext::setLastSyncTimestamp() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get last sync timestamp
     *
     * @return string|null Last sync timestamp or null
     */
    public function getLastSyncTimestamp(): ?string
    {
        return $this->lastSyncTimestamp;
    }

    /**
     * Set last sync report
     *
     * @param array $report Sync report
     * @return bool Success status
     */
    public function setLastSyncReport(array $report): bool
    {
        try {
            $this->lastSyncReport = $report;
            return true;
        } catch (\Exception $e) {
            error_log("PackContext::setLastSyncReport() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get last sync report
     *
     * @return array|null Last sync report or null
     */
    public function getLastSyncReport(): ?array
    {
        return $this->lastSyncReport;
    }
}
