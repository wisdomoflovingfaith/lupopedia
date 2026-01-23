<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.46
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: WOLFIE
 *   target: @everyone
 *   message: "Created RollingBackState for Migration Orchestrator. Handles rollback operations when migrations fail - executes rollback SQL, logs rollback events, cleans up partial changes, and transitions to FailedState. This completes the 8-state machine (100%)."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Rolling Back State"
 *   description: "State implementation for rolling back failed migrations"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * RollingBackState
 * 
 * State for rolling back failed migrations. This is the failure path recovery
 * state where the migration attempts to undo changes made during execution.
 * 
 * In this state, the orchestrator:
 * - Executes rollback SQL (if available)
 * - Logs rollback events to migration_rollback_log
 * - Updates migration status
 * - Cleans up any partial changes
 * - Transitions to FailedState (State 8)
 * 
 * State ID: 7
 * 
 * Transition Logic:
 * - Can Enter From: MigratingState (State 4) - if SQL execution fails
 * - Can Enter From: ValidatingPostState (State 5) - if post-validation fails
 * - Can Transition To: FailedState (State 8) - after rollback completes (success or failure)
 * 
 * Rollback Strategy:
 * - Attempts to execute rollback SQL if provided
 * - Logs all rollback attempts and results
 * - Does not fail if rollback SQL fails (already in failure path)
 * - Always transitions to FailedState after rollback attempt
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class RollingBackState extends AbstractState
{
    protected const STATE_NAME = 'rolling_back';
    protected const STATE_ID = 7;
    
    /**
     * Get possible transitions from this state
     * 
     * RollingBackState can transition to:
     * - FailedState: Always transitions to failed after rollback attempt (success or failure)
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            FailedState::class,
        ];
    }
    
    /**
     * Process rolling_back state
     * 
     * Attempts to rollback the migration:
     * - Executes rollback SQL if available
     * - Logs rollback events
     * - Updates migration status
     * - Always transitions to FailedState after attempt
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        
        $logger->warning(sprintf(
            'Rolling back migration %d: %s',
            $migration->getId(),
            $migration->getFilename()
        ));
        
        $startTime = microtime(true);
        $rollbackSuccess = false;
        $rollbackMessage = null;
        
        // Get rollback SQL if available
        $rollbackSql = $migration->getRollbackSql();
        
        if (empty($rollbackSql)) {
            $rollbackMessage = 'No rollback SQL provided - cannot undo changes';
            
            $logger->warning(sprintf(
                'Migration %d: %s',
                $migration->getId(),
                $rollbackMessage
            ));
            
            // Log rollback attempt (no SQL available)
            $migration->logRollback(
                'no_sql',
                false,
                $rollbackMessage
            );
            
        } else {
            try {
                $logger->info(sprintf(
                    'Migration %d: Executing rollback SQL',
                    $migration->getId()
                ));
                
                // Execute rollback SQL using Migration model's executeSql() method
                // Note: This may fail, but we're already in failure path
                $migration->executeRollbackSql($rollbackSql);
                
                $rollbackSuccess = true;
                $rollbackMessage = 'Rollback SQL executed successfully';
                
                $logger->info(sprintf(
                    'Migration %d: %s',
                    $migration->getId(),
                    $rollbackMessage
                ));
                
                // Log successful rollback
                $migration->logRollback(
                    'sql_executed',
                    true,
                    $rollbackMessage
                );
                
            } catch (\RuntimeException $e) {
                // Rollback SQL failed - log but don't throw (already in failure path)
                $rollbackMessage = sprintf(
                    'Rollback SQL execution failed: %s',
                    $e->getMessage()
                );
                
                $logger->error(sprintf(
                    'Migration %d: %s',
                    $migration->getId(),
                    $rollbackMessage
                ));
                
                // Log failed rollback
                $migration->logRollback(
                    'sql_failed',
                    false,
                    $rollbackMessage
                );
                
            } catch (\Exception $e) {
                // Unexpected error during rollback
                $rollbackMessage = sprintf(
                    'Unexpected error during rollback: %s',
                    $e->getMessage()
                );
                
                $logger->error(sprintf(
                    'Migration %d: %s',
                    $migration->getId(),
                    $rollbackMessage
                ));
                
                // Log failed rollback
                $migration->logRollback(
                    'unexpected_error',
                    false,
                    $rollbackMessage
                );
            }
        }
        
        $endTime = microtime(true);
        $rollbackTime = round($endTime - $startTime, 3);
        
        // Log rollback completion
        if ($rollbackSuccess) {
            $logger->info(sprintf(
                'Migration %d rollback completed successfully in %.3f seconds',
                $migration->getId(),
                $rollbackTime
            ));
        } else {
            $logger->warning(sprintf(
                'Migration %d rollback completed with issues in %.3f seconds: %s',
                $migration->getId(),
                $rollbackTime,
                $rollbackMessage
            ));
        }
        
        // Update progress: rollback complete (whether successful or not)
        $migration->updateProgress(0, sprintf(
            'Rollback %s in %.3f seconds: %s',
            $rollbackSuccess ? 'succeeded' : 'failed',
            $rollbackTime,
            $rollbackMessage
        ));
    }
    
    /**
     * Validate migration for rolling_back state
     * 
     * Ensures migration is ready for rollback:
     * - Migration must have an error message (indicating why rollback is needed)
     * - Migration must have attempted execution (progress exists)
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // RollingBack state: migration should have an error message
        // (This indicates why the rollback is happening)
        if (empty($migration->getErrorMessage())) {
            $errors[] = 'Migration should have an error message before rollback';
        }
        
        // RollingBack state: migration should have progress data
        // (This indicates execution was attempted)
        $progress = $migration->getProgress();
        if ($progress === null) {
            $errors[] = 'Migration should have progress data before rollback';
        }
        
        return $errors;
    }
    
    /**
     * Can the migration leave this state?
     * 
     * Migration can leave RollingBackState after:
     * - Rollback has been attempted (logged in migration_rollback_log)
     * - Progress has been updated
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool
    {
        // Can leave if rollback has been logged
        $rollbackLogs = $migration->getRollbackLogs();
        
        // Must have at least one rollback log entry
        return !empty($rollbackLogs);
    }
    
    /**
     * Get possible "from" states that can transition to rolling_back
     * 
     * RollingBackState can be entered from:
     * - MigratingState (State 4) - if SQL execution fails
     * - ValidatingPostState (State 5) - if post-validation fails
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            MigratingState::getId(),        // State 4
            ValidatingPostState::getId(),   // State 5
        ];
    }
}
