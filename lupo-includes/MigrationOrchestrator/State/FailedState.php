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
 *   speaker: LILITH
 *   target: @everyone
 *   message: "Created FailedState as guardrail for migration orchestrator. Explicit failure state prevents migrations from being stuck in ambiguous states. Can be entered from any state when failure occurs, can transition to idle for retry or remain in failed for manual intervention."
 *   mood: "FF6600"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Failed State"
 *   description: "Explicit failure state for migrations that cannot proceed - guardrail added in 4.0.35"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * FailedState
 * 
 * Explicit failure state for migrations that cannot proceed.
 * 
 * This state serves as a guardrail - migrations enter this state when:
 * - Validation fails before execution (validating_pre → failed)
 * - Rollback fails (rolling_back → failed)
 * - Any unrecoverable error occurs
 * 
 * From failed state, migrations can:
 * - Transition to idle for retry (if error is resolved)
 * - Remain in failed for manual intervention
 * 
 * State ID: 8
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class FailedState extends AbstractState
{
    protected const STATE_NAME = 'failed';
    protected const STATE_ID = 8;
    
    /**
     * Get possible transitions from this state
     * 
     * Failed migrations can:
     * - Go to idle for retry (if error is resolved)
     * - Remain in failed (for manual intervention)
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            IdleState::class, // Can retry after fixing error
        ];
    }
    
    /**
     * Process failed state
     * 
     * In failed state, migration is stopped. Log the failure and
     * create alerts for manual intervention.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        
        $logger->error(sprintf(
            'Migration %d is in failed state. Error: %s',
            $migration->getId(),
            $migration->getErrorMessage() ?? 'Unknown error'
        ));
        
        // Create alert for failed migration
        if ($migration->getId()) {
            $migration->createAlert(
                'error',
                'Migration Failed',
                sprintf(
                    'Migration %d (%s) has failed and cannot proceed. Error: %s',
                    $migration->getId(),
                    $migration->getFilename(),
                    $migration->getErrorMessage() ?? 'Unknown error'
                ),
                1 // Escalation level 1
            );
        }
    }
    
    /**
     * Validate migration for failed state
     * 
     * Failed state requires an error message to be set.
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // Failed state: migration must have an error message
        if (empty($migration->getErrorMessage())) {
            $errors[] = 'Failed state requires an error message';
        }
        
        return $errors;
    }
    
    /**
     * Get possible "from" states that can transition to failed
     * 
     * Failed can be entered from:
     * - validating_pre (validation failed, nothing to rollback)
     * - rolling_back (rollback failed)
     * - Any state when unrecoverable error occurs
     * 
     * Note: Can be entered from any state - this is the failure sink.
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        // Failed can be entered from any state when failure occurs
        // Empty array means can be entered from any state (failure sink)
        return [];
    }
}
