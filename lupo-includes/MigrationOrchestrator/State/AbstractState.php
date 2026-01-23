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
 *   message: "Created AbstractState base class for Migration Orchestrator states. Provides common functionality for all state implementations: logging, validation, state tracking."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Abstract State"
 *   description: "Base class providing common functionality for all migration state implementations"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * AbstractState
 * 
 * Base class for all migration state implementations. Provides common
 * functionality for state management, logging, and validation.
 * 
 * Subclasses must implement:
 * - getName() - Return state name
 * - getId() - Return state ID
 * - getPossibleTransitions() - Return allowed next states
 * - process() - State-specific processing logic
 * - validate() - State-specific validation
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
abstract class AbstractState implements StateInterface
{
    /**
     * Get the canonical state name
     * 
     * @return string State name
     */
    public static function getName(): string
    {
        return static::STATE_NAME;
    }
    
    /**
     * Get the state ID as stored in the database
     * 
     * @return int State ID
     */
    public static function getId(): int
    {
        return static::STATE_ID;
    }
    
    /**
     * Can the migration transition to this state?
     * 
     * Default implementation checks if migration's current state is in
     * the list of allowed "from" states. Override for custom logic.
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can enter this state
     */
    public function canEnter(Migration $migration): bool
    {
        $allowedFromStates = $this->getPossibleFromStates();
        
        // If empty array, allow from any state (initial states)
        if (empty($allowedFromStates)) {
            return true;
        }
        
        // Check if migration's current state is in allowed list
        $currentStateId = $migration->getStateId();
        return in_array($currentStateId, $allowedFromStates);
    }
    
    /**
     * Actions to perform when entering this state
     * 
     * Default implementation logs the state entry. Override for custom logic.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function enter(Migration $migration, Orchestrator $orchestrator): void
    {
        $orchestrator->getLogger()->info(sprintf(
            'Migration %d entering state: %s',
            $migration->getId(),
            static::getName()
        ));
        
        // Update migration state in database
        $migration->setStateId(static::getId());
    }
    
    /**
     * Actions to perform while in this state
     * 
     * Default implementation does nothing. Override for state-specific processing.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        // Default: no processing needed
        // Override in subclasses for state-specific logic
    }
    
    /**
     * Can the migration leave this state?
     * 
     * Default implementation checks if state processing is complete.
     * Override for custom logic.
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool
    {
        // Default: can leave if validation passes
        $errors = $this->validate($migration);
        return empty($errors);
    }
    
    /**
     * Actions to perform when leaving this state
     * 
     * Default implementation logs the state exit. Override for custom logic.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function leave(Migration $migration, Orchestrator $orchestrator): void
    {
        $orchestrator->getLogger()->info(sprintf(
            'Migration %d leaving state: %s',
            $migration->getId(),
            static::getName()
        ));
    }
    
    /**
     * Validate the migration is ready for this state
     * 
     * Default implementation performs basic validation. Override for
     * state-specific validation rules.
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = [];
        
        // Basic validation: migration must exist
        if (!$migration->getId()) {
            $errors[] = 'Migration must have an ID';
        }
        
        // Basic validation: migration SQL must exist
        if (empty($migration->getSql())) {
            $errors[] = 'Migration must have SQL content';
        }
        
        return $errors;
    }
    
    /**
     * Get list of state IDs that can transition to this state
     * 
     * Returns an array of state IDs that are valid "from" states for
     * transitioning into this state. Empty array means can be entered
     * from any state (typically initial states).
     * 
     * Override in subclasses to define allowed transitions.
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        // Default: can be entered from any state (override for restrictions)
        return [];
    }
}
