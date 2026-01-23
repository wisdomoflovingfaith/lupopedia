<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.44
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: WOLFIE
 *   target: @everyone
 *   message: "Created CompletingState for Migration Orchestrator. Finalizes successful migrations - marks migration as completed, updates batch progress, logs completion events, and transitions back to idle. This is the success path completion state."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Completing State"
 *   description: "State implementation for finalizing successful migrations"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * CompletingState
 * 
 * State for finalizing successful migrations. This is the success path completion
 * state where the migration is marked as completed and batch progress is updated.
 * 
 * In this state, the orchestrator:
 * - Marks migration as completed (sets file_status to 'completed')
 * - Updates batch progress (increments processed_files)
 * - Logs completion events
 * - Transitions back to IdleState (State 1)
 * 
 * State ID: 6
 * 
 * Transition Logic:
 * - Can Enter From: ValidatingPostState (State 5) - after post-validation passes
 * - Can Transition To: IdleState (State 1) - after completion finalized
 * 
 * Critical Operations:
 * - Calls Migration::markAsCompleted() - validates state_id === 6 before allowing completion
 * - Updates batch progress via Migration::updateBatchProgress()
 * - Saves migration state to database
 * - Logs completion for audit trail
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class CompletingState extends AbstractState
{
    protected const STATE_NAME = 'completing';
    protected const STATE_ID = 6;
    
    /**
     * Get possible transitions from this state
     * 
     * CompletingState can transition to:
     * - IdleState: After completion is finalized (success path complete)
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            IdleState::class,
        ];
    }
    
    /**
     * Process completing state
     * 
     * Finalizes successful migration:
     * - Marks migration as completed (validates state_id === 6)
     * - Updates batch progress
     * - Logs completion events
     * - Prepares for transition to IdleState
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        
        $logger->info(sprintf(
            'Completing migration %d: finalizing successful migration',
            $migration->getId()
        ));
        
        try {
            // Set state_id to 6 (CompletingState) before marking as completed
            // markAsCompleted() validates that state_id === 6, so we must set it first
            $migration->setStateId(self::STATE_ID);
            
            // Mark migration as completed
            // This validates that state_id === 6 before allowing completion
            $migration->markAsCompleted();
            
            // Update batch progress (increment processed_files)
            $batch = $migration->getBatch();
            if ($batch) {
                $currentProcessed = (int)($batch['processed_files'] ?? 0);
                $currentFailed = (int)($batch['failed_files'] ?? 0);
                
                // Increment processed_files count
                $migration->updateBatchProgress($currentProcessed + 1, $currentFailed);
                
                $logger->info(sprintf(
                    'Updated batch %d progress: %d processed, %d failed',
                    $migration->getBatchId(),
                    $currentProcessed + 1,
                    $currentFailed
                ));
            }
            
            // Save migration state (markAsCompleted() sets file_status, but we need to save)
            $migration->save();
            
            $logger->info(sprintf(
                'Migration %d completed successfully: marked as completed, batch progress updated',
                $migration->getId()
            ));
            
        } catch (\RuntimeException $e) {
            // markAsCompleted() throws if state_id !== 6
            // This should never happen if state machine is working correctly
            $logger->error(sprintf(
                'Migration %d completion failed: %s',
                $migration->getId(),
                $e->getMessage()
            ));
            
            // Store error message
            $migration->setErrorMessage($e->getMessage());
            $migration->save();
            
            // Re-throw to trigger transition to FailedState
            throw $e;
        }
    }
    
    /**
     * Validate migration for completing state
     * 
     * Ensures migration is ready for completion:
     * - Migration must have passed post-validation (hasValidationsPassed('post') === true)
     * - Migration must be in ValidatingPostState (State 5) or transitioning from it
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // Completing state: migration must have passed post-validation
        if (!$migration->hasValidationsPassed('post')) {
            $errors[] = 'Migration must have passed post-validation before completion';
        }
        
        // Check that post-validation logs exist
        $postValidationLogs = $migration->getValidationLogs('post');
        if (empty($postValidationLogs)) {
            $errors[] = 'Migration must have post-validation logs before completion';
        } else {
            // Verify at least one post-validation passed
            $hasPassed = false;
            foreach ($postValidationLogs as $log) {
                if (isset($log['validation_status']) && $log['validation_status'] === 'passed') {
                    $hasPassed = true;
                    break;
                }
            }
            
            if (!$hasPassed) {
                $errors[] = 'Migration post-validation did not pass - cannot complete';
            }
        }
        
        return $errors;
    }
    
    /**
     * Can the migration leave this state?
     * 
     * Migration can leave CompletingState if:
     * - Completion has been finalized (markAsCompleted() succeeded)
     * - Batch progress has been updated
     * - Migration has been saved
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool
    {
        // Can leave if migration is marked as completed
        return $migration->getFileStatus() === 'completed';
    }
    
    /**
     * Get possible "from" states that can transition to completing
     * 
     * CompletingState can be entered from:
     * - ValidatingPostState (State 5) - after post-validation passes
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            ValidatingPostState::getId(), // State 5
        ];
    }
}
