<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.35
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created StateInterface for Migration Orchestrator state machine. Defines contract for all migration states: idle, preparing, validating_pre, migrating, validating_post, completing, rolling_back."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator State Interface"
 *   description: "Interface defining the contract for all migration state implementations in the orchestrator state machine"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * StateInterface
 * 
 * Defines the contract for all migration state implementations in the
 * Migration Orchestrator state machine.
 * 
 * The state machine follows these 8 states:
 * 1. idle - Initial state, no migration active
 * 2. preparing - Preparing migration batch and dependencies
 * 3. validating_pre - Pre-migration validation checks
 * 4. migrating - Executing migration SQL
 * 5. validating_post - Post-migration validation checks
 * 6. completing - Finalizing migration, updating state
 * 7. rolling_back - Executing rollback (if migration failed)
 * 8. failed - Explicit failure state (guardrail added 4.0.35)
 * 
 * Each state must implement:
 * - State identification (name, ID)
 * - Entry/exit validation (canEnter, canLeave)
 * - State actions (enter, process, leave)
 * - Transition rules (getPossibleTransitions)
 * - State-specific validation (validate)
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
interface StateInterface
{
    /**
     * Get the canonical state name (e.g., "idle", "preparing", "migrating")
     * 
     * @return string State name
     */
    public static function getName(): string;
    
    /**
     * Get the state ID as stored in the database
     * 
     * State IDs should match the values in lupo_migration_states table:
     * 1 = idle
     * 2 = preparing
     * 3 = validating_pre
     * 4 = migrating
     * 5 = validating_post
     * 6 = completing
     * 7 = rolling_back
     * 
     * @return int State ID
     */
    public static function getId(): int;
    
    /**
     * Can the migration transition to this state?
     * 
     * Validates that the migration is in a valid state to enter this state.
     * Checks prerequisites, dependencies, and current migration status.
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can enter this state
     */
    public function canEnter(Migration $migration): bool;
    
    /**
     * Actions to perform when entering this state
     * 
     * Called when transitioning into this state. Should:
     * - Update migration state in database
     * - Log state entry
     * - Initialize state-specific resources
     * - Update migration progress
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function enter(Migration $migration, Orchestrator $orchestrator): void;
    
    /**
     * Actions to perform while in this state
     * 
     * Called repeatedly while the migration remains in this state.
     * Should perform the actual work of the state (validation, execution, etc.).
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void;
    
    /**
     * Can the migration leave this state?
     * 
     * Validates that the migration has completed all work in this state
     * and is ready to transition to the next state.
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool;
    
    /**
     * Actions to perform when leaving this state
     * 
     * Called when transitioning out of this state. Should:
     * - Clean up state-specific resources
     * - Log state exit
     * - Update migration progress
     * - Prepare for next state
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function leave(Migration $migration, Orchestrator $orchestrator): void;
    
    /**
     * List of states that can be transitioned to from this state
     * 
     * Returns an array of state class names (fully qualified) that are
     * valid next states from this state. Used by the orchestrator to
     * validate state transitions.
     * 
     * Example:
     * ```php
     * return [
     *     IdleState::class,
     *     PreparingState::class,
     * ];
     * ```
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array;
    
    /**
     * Validate the migration is ready for this state
     * 
     * Performs state-specific validation checks. Returns an array of
     * validation errors (empty array if validation passes).
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array;
}
