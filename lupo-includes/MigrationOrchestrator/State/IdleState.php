<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.34
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created IdleState for Migration Orchestrator. Initial state when no migration is active. Can transition to PreparingState."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Idle State"
 *   description: "Initial state implementation for migration orchestrator state machine"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * IdleState
 * 
 * Initial state when no migration is active. This is the starting point
 * for all migrations. From idle, migrations can transition to preparing.
 * 
 * State ID: 1
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class IdleState extends AbstractState
{
    protected const STATE_NAME = 'idle';
    protected const STATE_ID = 1;
    
    /**
     * Get possible transitions from this state
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            PreparingState::class,
        ];
    }
    
    /**
     * Process idle state
     * 
     * In idle state, no processing is needed. Migration is waiting to start.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        // Idle state: no processing needed
        // Migration is waiting to be started
        $orchestrator->getLogger()->debug(sprintf(
            'Migration %d is idle, waiting to start',
            $migration->getId()
        ));
    }
    
    /**
     * Validate migration for idle state
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // Idle state: migration should exist but not be executing
        // No additional validation needed for idle state
        
        return $errors;
    }
    
    /**
     * Get possible "from" states that can transition to idle
     * 
     * Idle can be entered from:
     * - Initial creation (no previous state, ID 0)
     * - RollingBackState (after rollback completes)
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            0, // Initial state (no previous state)
            RollingBackState::getId(), // After rollback completes
        ];
    }
}
