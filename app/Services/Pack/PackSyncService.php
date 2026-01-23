<?php
/**
 * Pack Synchronization Service
 *
 * Public API service for Pack synchronization operations.
 * Wraps PackSyncEngine for controllers and agents.
 *
 * @package Lupopedia
 * @version 4.0.112
 * @author Captain Wolfie
 */

namespace App\Services\Pack;

use Lupopedia\Pack\Sync\PackSyncEngine;
use Lupopedia\Pack\PackRegistry;
use Lupopedia\Pack\PackContext;

/**
 * PackSyncService
 *
 * Provides public API for Pack synchronization operations.
 */
class PackSyncService
{
    /** @var PackSyncEngine Sync engine instance */
    private $syncEngine;

    /** @var PackRegistry Pack registry instance */
    private $registry;

    /** @var PackContext Pack context instance */
    private $context;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registry = new PackRegistry();
        $this->context = new PackContext();
        $this->syncEngine = new PackSyncEngine($this->registry, $this->context);
    }

    /**
     * Run full synchronization
     *
     * @return array Sync payload
     */
    public function run(): array
    {
        $report = $this->syncEngine->fullSync();

        // Update PackContext with sync timestamp and report
        $this->context->setLastSyncTimestamp($report['timestamp']);
        $this->context->setLastSyncReport($report);

        return $report;
    }

    /**
     * Synchronize emotions
     *
     * @return array Emotional sync report
     */
    public function emotions(): array
    {
        return $this->syncEngine->synchronizeEmotions();
    }

    /**
     * Synchronize behavior
     *
     * @return array Behavioral sync report
     */
    public function behavior(): array
    {
        return $this->syncEngine->synchronizeBehavior();
    }

    /**
     * Synchronize memory
     *
     * @return array Memory sync report
     */
    public function memory(): array
    {
        return $this->syncEngine->synchronizeMemory();
    }
}
