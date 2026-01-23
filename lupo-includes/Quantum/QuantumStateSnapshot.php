<?php
/**
 * Quantum State Snapshot
 *
 * Represents a quantum state snapshot with uncertainty metadata.
 * Implements quantum state management doctrine as defined in version 4.0.79.
 *
 * @package Lupopedia
 * @version 4.0.106
 * @author Captain Wolfie
 */

namespace Lupopedia\Quantum;

/**
 * QuantumStateSnapshot
 *
 * Encapsulates a quantum state snapshot with state data and uncertainty metadata.
 */
class QuantumStateSnapshot
{
    /** @var string Unique state identifier */
    private $stateId;

    /** @var array State data */
    private $stateData;

    /** @var array Metadata (observer, timestamp, uncertainty type, etc.) */
    private $metadata;

    /**
     * Constructor
     *
     * @param string $stateId Unique identifier for this state
     * @param array $stateData State data
     * @param array $metadata Optional metadata
     */
    public function __construct(string $stateId, array $stateData, array $metadata = [])
    {
        $this->stateId = $stateId;
        $this->stateData = $stateData;
        $this->metadata = array_merge([
            'timestamp' => gmdate('YmdHis'),
            'observer' => null,
            'uncertainty_type' => 'superposition',
        ], $metadata);
    }

    /**
     * Get state identifier
     *
     * @return string
     */
    public function getStateId(): string
    {
        return $this->stateId;
    }

    /**
     * Get state data
     *
     * @return array
     */
    public function getStateData(): array
    {
        return $this->stateData;
    }

    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Export snapshot as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'state_id' => $this->stateId,
            'state_data' => $this->stateData,
            'metadata' => $this->metadata,
        ];
    }
}
