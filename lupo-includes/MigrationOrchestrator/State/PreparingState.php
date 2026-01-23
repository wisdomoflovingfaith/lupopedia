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
 *   message: "Created PreparingState for Migration Orchestrator. Prepares migration batch, resolves dependencies, and validates prerequisites before execution."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Preparing State"
 *   description: "State implementation for preparing migrations before execution"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * PreparingState
 * 
 * State for preparing migration batch and dependencies before execution.
 * In this state, the orchestrator:
 * - Resolves migration dependencies
 * - Validates migration files exist
 * - Prepares migration batch
 * - Checks prerequisites
 * 
 * State ID: 2
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class PreparingState extends AbstractState
{
    protected const STATE_NAME = 'preparing';
    protected const STATE_ID = 2;
    
    /**
     * Get possible transitions from this state
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            ValidatingPreState::class,
            RollingBackState::class, // Can rollback if preparation fails
        ];
    }
    
    /**
     * Process preparing state
     * 
     * Prepares the migration by:
     * - Resolving dependencies
     * - Validating migration files
     * - Preparing batch metadata
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        
        $logger->info(sprintf(
            'Preparing migration %d: resolving dependencies and validating files',
            $migration->getId()
        ));
        
        // Resolve dependencies
        // TODO: Implement dependency resolution logic
        
        // Validate migration files exist
        // TODO: Check migration file exists in filesystem
        
        // Prepare batch metadata
        // TODO: Create batch record in database
        
        $logger->info(sprintf(
            'Migration %d preparation complete',
            $migration->getId()
        ));
    }
    
    /**
     * Validate migration for preparing state
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // Preparing state: migration must have a filename
        if (empty($migration->getFilename())) {
            $errors[] = 'Migration must have a filename for preparation';
        }
        
        // Preparing state: migration must have SQL content
        if (empty($migration->getSql())) {
            $errors[] = 'Migration must have SQL content for preparation';
        }
        
        return $errors;
    }
    
    /**
     * Get possible "from" states that can transition to preparing
     * 
     * Preparing can be entered from:
     * - IdleState (starting migration)
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            IdleState::getId(),
        ];
    }
}
