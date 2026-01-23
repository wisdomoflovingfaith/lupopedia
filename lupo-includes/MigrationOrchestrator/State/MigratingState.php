<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.43
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: WOLFIE
 *   target: @everyone
 *   message: "Created MigratingState for Migration Orchestrator. Executes migration SQL against database - the critical state where schema changes actually happen. Includes transaction management, error handling, and execution logging."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Migrating State"
 *   description: "State implementation for executing migration SQL against the database"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * MigratingState
 * 
 * State for executing migration SQL against the database. This is the
 * critical state where schema changes actually happen.
 * 
 * In this state, the orchestrator:
 * - Executes migration SQL in a transaction
 * - Captures execution results and errors
 * - Logs execution progress
 * - Updates migration status
 * - Handles rollback on failure
 * 
 * State ID: 4
 * 
 * Transition Logic:
 * - Can Enter From: ValidatingPreState (State 3) - after pre-validation passes
 * - Can Transition To: ValidatingPostState (State 5) if execution succeeds
 * - Can Transition To: RollingBackState (State 7) if execution fails
 * 
 * Safety Mechanisms:
 * - All SQL executed in transaction (can rollback on error)
 * - Execution time tracked
 * - Full error logging
 * - Progress updates during execution
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class MigratingState extends AbstractState
{
    protected const STATE_NAME = 'migrating';
    protected const STATE_ID = 4;
    
    /**
     * Get possible transitions from this state
     * 
     * MigratingState can transition to:
     * - ValidatingPostState: If execution succeeds
     * - RollingBackState: If execution fails (need to rollback changes)
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            ValidatingPostState::class,
            RollingBackState::class,
        ];
    }
    
    /**
     * Process migrating state
     * 
     * Executes migration SQL against the database using Migration::executeSql():
     * - Delegates to Migration model for atomic execution
     * - Tracks execution time
     * - Updates progress during execution
     * - Handles errors and stores error messages
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        
        $logger->info(sprintf(
            'Executing migration %d: %s',
            $migration->getId(),
            $migration->getFilename()
        ));
        
        $startTime = microtime(true);
        $success = false;
        $errorMessage = null;
        
        try {
            // Get SQL to execute
            $sql = $migration->getSql();
            
            $logger->info(sprintf(
                'Migration %d: Starting SQL execution',
                $migration->getId()
            ));
            
            // Update progress: execution starting
            $migration->updateProgress(0, 'Starting SQL execution');
            
            // Execute SQL using Migration model's executeSql() method
            // This handles transaction management, statement splitting, and rollback
            $migration->executeSql($sql);
            
            $logger->info(sprintf(
                'Migration %d: SQL execution completed successfully',
                $migration->getId()
            ));
            
            $success = true;
            
        } catch (\RuntimeException $e) {
            // Migration::executeSql() throws RuntimeException on failure
            $errorMessage = $e->getMessage();
            
            $logger->error(sprintf(
                'Migration %d: SQL execution failed: %s',
                $migration->getId(),
                $errorMessage
            ));
            
            // Store error message in migration
            $migration->setErrorMessage($errorMessage);
            
            // Update progress: execution failed
            $migration->updateProgress(0, sprintf(
                'Execution failed: %s',
                $errorMessage
            ));
            
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            $errorMessage = sprintf(
                'Unexpected error during execution: %s',
                $e->getMessage()
            );
            
            $logger->error(sprintf(
                'Migration %d: %s',
                $migration->getId(),
                $errorMessage
            ));
            
            // Store error message in migration
            $migration->setErrorMessage($errorMessage);
            
            // Update progress: execution failed
            $migration->updateProgress(0, sprintf(
                'Execution failed: %s',
                $errorMessage
            ));
        }
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 3);
        
        // Log execution result
        if ($success) {
            $logger->info(sprintf(
                'Migration %d executed successfully in %.3f seconds',
                $migration->getId(),
                $executionTime
            ));
            
            // Update migration with execution time
            $migration->updateProgress(100, sprintf(
                'Execution completed in %.3f seconds',
                $executionTime
            ));
            
        } else {
            $logger->error(sprintf(
                'Migration %d execution failed after %.3f seconds',
                $migration->getId(),
                $executionTime
            ));
        }
    }
    
    /**
     * Validate migration for migrating state
     * 
     * Ensures migration is ready for execution:
     * - Migration must have SQL content
     * - Migration must have passed pre-validation
     * - Migration must not have already been executed
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // Migrating state: migration must have SQL content
        if (empty($migration->getSql())) {
            $errors[] = 'Migration must have SQL content for execution';
        }
        
        // Migrating state: migration must have passed pre-validation
        if (!$migration->hasValidationsPassed('pre')) {
            $errors[] = 'Migration must pass pre-validation before execution';
        }
        
        // Migrating state: migration must not have already been executed
        // (Check if migration has already reached a terminal state)
        $currentState = $migration->getStateId();
        if ($currentState >= ValidatingPostState::getId()) {
            $errors[] = 'Migration has already been executed';
        }
        
        return $errors;
    }
    
    /**
     * Can the migration leave this state?
     * 
     * Migration can leave MigratingState if:
     * - Execution has completed (success or failure)
     * - Progress is at 100% (success) or 0% (failure)
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool
    {
        // Can leave if execution completed (success or failure)
        $progress = $migration->getProgress();
        
        // Progress at 100% = success, can proceed to ValidatingPostState
        // Progress at 0% with error = failure, can proceed to RollingBackState
        return $progress !== null && ($progress['percentage'] === 100.0 || $progress['percentage'] === 0.0);
    }
    
    /**
     * Get possible "from" states that can transition to migrating
     * 
     * MigratingState can be entered from:
     * - ValidatingPreState (State 3) - after pre-validation passes
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            ValidatingPreState::getId(), // State 3
        ];
    }
}
<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.42
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: WOLFIE
 *   target: @everyone
 *   message: "Created MigratingState for Migration Orchestrator. Executes migration SQL against database atomically. This is the 'dangerous' state where actual database changes happen - the point of no return. Tracks execution progress, handles failures, and ensures atomic transactions."
 *   mood: "FF6600"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Migrating State"
 *   description: "State implementation for executing migration SQL against the database"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;

/**
 * MigratingState
 * 
 * State for executing migration SQL against the database. This is the
 * "dangerous" state where actual database changes happen - the point
 * of no return.
 * 
 * In this state, the orchestrator:
 * - Executes migration SQL atomically (transaction)
 * - Tracks execution progress in real-time
 * - Detects failures immediately
 * - Handles partial execution scenarios
 * - Provides checkpoint for rollback if failure occurs
 * 
 * State ID: 4
 * 
 * Transition Logic:
 * - Can Enter From: ValidatingPreState (State 3) - after validation passes
 * - Can Transition To: ValidatingPostState (State 5) if execution succeeds
 * - Can Transition To: RollingBackState (State 7) if execution fails (partial changes made)
 * 
 * Doctrine Safeguards:
 * - Must execute SQL atomically (transaction)
 * - Must track execution progress
 * - Must detect failures immediately
 * - Cannot proceed if SQL execution fails
 * 
 * Recovery Policy:
 * On system restart, if migration is in `migrating` state → always transition
 * to `rolling_back` (safer than resuming partial execution)
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class MigratingState extends AbstractState
{
    protected const STATE_NAME = 'migrating';
    protected const STATE_ID = 4;
    
    /**
     * Execution start time (for progress tracking)
     * 
     * @var float|null
     */
    private ?float $executionStartTime = null;
    
    /**
     * Get possible transitions from this state
     * 
     * MigratingState can transition to:
     * - ValidatingPostState: If execution succeeds
     * - RollingBackState: If execution fails (partial execution occurred)
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            ValidatingPostState::class,
            RollingBackState::class,
        ];
    }
    
    /**
     * Actions to perform when entering this state
     * 
     * Records execution start time and logs entry into migrating state.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function enter(Migration $migration, Orchestrator $orchestrator): void
    {
        parent::enter($migration, $orchestrator);
        
        $logger = $orchestrator->getLogger();
        
        $logger->warning(sprintf(
            'Migration %d entering MIGRATING state: executing SQL against database (point of no return)',
            $migration->getId()
        ));
        
        // Record execution start time
        $this->executionStartTime = microtime(true);
        
        // Update progress: starting execution
        try {
            $migration->updateProgress(
                'migrating',
                3, // Phase index: 0=idle, 1=preparing, 2=validating_pre, 3=migrating
                6, // Total phases: idle, preparing, validating_pre, migrating, validating_post, completing
                1, // Files in phase
                0, // Files completed in phase
                50.0, // Percentage complete (rough estimate)
                0 // Estimated remaining seconds
            );
        } catch (\Exception $e) {
            // Progress tracking failure shouldn't stop migration
            $logger->warning(sprintf(
                'Failed to update progress for migration %d: %s',
                $migration->getId(),
                $e->getMessage()
            ));
        }
    }
    
    /**
     * Process migrating state
     * 
     * Executes migration SQL against the database atomically (transaction).
     * This is where the actual database changes happen.
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     * @throws \RuntimeException If SQL execution fails
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        $sql = $migration->getSql();
        
        if (empty($sql)) {
            throw new \RuntimeException(sprintf(
                'Migration %d has no SQL content to execute',
                $migration->getId()
            ));
        }
        
        $logger->info(sprintf(
            'Executing migration %d SQL (%d bytes)',
            $migration->getId(),
            strlen($sql)
        ));
        
        // Get database connection from migration
        // Note: Migration model has private $db property, so we need to access it
        // through a method or use reflection. For now, we'll assume Migration
        // provides a way to execute SQL, or we'll need to add that method.
        
        // Execute SQL atomically (transaction)
        try {
            $this->executeMigrationSql($migration, $sql, $logger);
            
            $executionTime = $this->executionStartTime ? 
                (microtime(true) - $this->executionStartTime) : 0;
            
            $logger->info(sprintf(
                'Migration %d SQL execution completed successfully in %.2f seconds',
                $migration->getId(),
                $executionTime
            ));
            
            // Update progress: execution complete
            try {
                $migration->updateProgress(
                    'migrating',
                    3, // Phase index
                    6, // Total phases
                    1, // Files in phase
                    1, // Files completed in phase
                    66.7, // Percentage complete (migrating done, moving to validating_post)
                    0 // Estimated remaining seconds
                );
            } catch (\Exception $e) {
                $logger->warning(sprintf(
                    'Failed to update progress after execution: %s',
                    $e->getMessage()
                ));
            }
            
        } catch (\Exception $e) {
            $executionTime = $this->executionStartTime ? 
                (microtime(true) - $this->executionStartTime) : 0;
            
            $errorMessage = sprintf(
                'Migration %d SQL execution failed after %.2f seconds: %s',
                $migration->getId(),
                $executionTime,
                $e->getMessage()
            );
            
            $logger->error($errorMessage);
            
            // Store error message in migration
            $migration->setErrorMessage($errorMessage);
            
            // Update progress: execution failed
            try {
                $migration->updateProgress(
                    'migrating',
                    3, // Phase index
                    6, // Total phases
                    1, // Files in phase
                    0, // Files completed in phase (failed)
                    50.0, // Percentage complete (stuck at migrating)
                    0 // Estimated remaining seconds
                );
            } catch (\Exception $progressError) {
                // Ignore progress update failures during error handling
            }
            
            // Re-throw to trigger transition to RollingBackState
            throw new \RuntimeException($errorMessage, 0, $e);
        }
    }
    
    /**
     * Validate migration for migrating state
     * 
     * Ensures migration is ready for execution:
     * - Migration must have SQL content
     * - Pre-validation must have passed
     * - Migration must be in validating_pre state (or transitioning from it)
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // Migrating state: migration must have SQL content
        if (empty($migration->getSql())) {
            $errors[] = 'Migration must have SQL content for execution';
        }
        
        // Migrating state: pre-validation must have passed
        if (!$migration->hasValidationsPassed('pre')) {
            $errors[] = 'Migration pre-validation must pass before execution';
        }
        
        return $errors;
    }
    
    /**
     * Can the migration leave this state?
     * 
     * Migration can leave MigratingState if:
     * - SQL execution completed successfully (no exceptions)
     * - OR execution failed (will transition to RollingBackState)
     * 
     * Note: This is checked after process() completes. If process() throws
     * an exception, the orchestrator will transition to RollingBackState.
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool
    {
        // Can leave if execution completed (no error message) OR failed (has error message)
        // The orchestrator will handle the transition based on whether process() threw an exception
        return true; // Always allow leaving (orchestrator handles success/failure transitions)
    }
    
    /**
     * Get possible "from" states that can transition to migrating
     * 
     * MigratingState can be entered from:
     * - ValidatingPreState (State 3) - after validation passes
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            ValidatingPreState::getId(), // State 3
        ];
    }
    
    /**
     * Execute migration SQL atomically (transaction)
     * 
     * Executes the migration SQL within a transaction to ensure atomicity.
     * If any part fails, the entire transaction is rolled back.
     * 
     * Uses Migration model's executeSql() method which handles transaction
     * management and statement execution.
     * 
     * @param Migration $migration The migration object
     * @param string $sql The SQL to execute
     * @param object $logger Logger instance
     * @return void
     * @throws \RuntimeException If SQL execution fails
     */
    private function executeMigrationSql(Migration $migration, string $sql, $logger): void
    {
        // Use Migration model's executeSql() method
        // This handles transaction management and statement execution
        $migration->executeSql($sql);
        
        $logger->info(sprintf(
            'Migration %d: SQL executed successfully',
            $migration->getId()
        ));
    }
}
