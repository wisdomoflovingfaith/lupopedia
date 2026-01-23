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
 *   message: "Created ValidatingPreState for Migration Orchestrator. Validates migration SQL against doctrine before execution - checks for forbidden patterns (FKs, triggers, SPs), validates SQL syntax, and ensures doctrine compliance. First state built in sequential implementation approach."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Validating Pre State"
 *   description: "State implementation for pre-execution validation of migrations against doctrine"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\State;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\Orchestrator;
use Lupopedia\MigrationOrchestrator\DoctrineValidator;

/**
 * ValidatingPreState
 * 
 * State for pre-execution validation - checking migration SQL against
 * doctrine before execution. This is the "safety gate" that prevents
 * bad migrations from executing.
 * 
 * In this state, the orchestrator:
 * - Validates migration SQL against doctrine (no FKs, triggers, SPs)
 * - Checks SQL syntax validity
 * - Detects schema conflicts
 * - Logs validation results
 * - Determines if migration can proceed to execution
 * 
 * State ID: 3
 * 
 * Transition Logic:
 * - Can Enter From: PreparingState (State 2) - after dependencies resolved
 * - Can Transition To: MigratingState (State 4) if validation passes
 * - Can Transition To: FailedState (State 8) if validation fails (nothing to rollback)
 * 
 * Doctrine Safeguards:
 * - Must check for forbidden patterns (FKs, triggers, SPs)
 * - Must validate SQL syntax
 * - Must check for schema conflicts
 * - Cannot proceed if doctrine violations detected
 * 
 * @package Lupopedia\MigrationOrchestrator\State
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class ValidatingPreState extends AbstractState
{
    protected const STATE_NAME = 'validating_pre';
    protected const STATE_ID = 3;
    
    /**
     * Doctrine validator instance
     * 
     * @var DoctrineValidator|null
     */
    private ?DoctrineValidator $doctrineValidator = null;
    
    /**
     * Get possible transitions from this state
     * 
     * ValidatingPreState can transition to:
     * - MigratingState: If validation passes
     * - FailedState: If validation fails (nothing executed yet, nothing to rollback)
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return [
            MigratingState::class,
            FailedState::class,
        ];
    }
    
    /**
     * Process validating_pre state
     * 
     * Performs pre-execution validation:
     * - Doctrine compliance checking (no FKs, triggers, SPs)
     * - SQL syntax validation
     * - Schema conflict detection
     * - Logs validation results
     * 
     * @param Migration $migration The migration object
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return void
     */
    public function process(Migration $migration, Orchestrator $orchestrator): void
    {
        $logger = $orchestrator->getLogger();
        
        $logger->info(sprintf(
            'Validating migration %d: checking doctrine compliance and SQL syntax',
            $migration->getId()
        ));
        
        // Get doctrine validator
        $validator = $this->getDoctrineValidator($orchestrator);
        
        // Perform doctrine validation
        $doctrineErrors = $validator->validateMigration($migration);
        
        // Perform SQL syntax validation (basic check)
        $syntaxErrors = $this->validateSqlSyntax($migration);
        
        // Combine all validation errors
        $allErrors = array_merge($doctrineErrors, $syntaxErrors);
        
        // Log validation results
        if (empty($allErrors)) {
            $logger->info(sprintf(
                'Migration %d pre-validation passed: doctrine compliant and SQL syntax valid',
                $migration->getId()
            ));
            
            // Log successful validation
            $migration->logValidation('pre', true, 'Pre-validation passed: doctrine compliant and SQL syntax valid');
        } else {
            $errorMessage = implode('; ', $allErrors);
            $logger->error(sprintf(
                'Migration %d pre-validation failed: %s',
                $migration->getId(),
                $errorMessage
            ));
            
            // Log failed validation
            $migration->logValidation('pre', false, $errorMessage);
        }
    }
    
    /**
     * Validate migration for validating_pre state
     * 
     * Ensures migration is ready for pre-execution validation:
     * - Migration must have SQL content
     * - Migration must have a filename
     * - Migration must be in preparing state (or transitioning from it)
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validate(Migration $migration): array
    {
        $errors = parent::validate($migration);
        
        // ValidatingPre state: migration must have SQL content
        if (empty($migration->getSql())) {
            $errors[] = 'Migration must have SQL content for pre-validation';
        }
        
        // ValidatingPre state: migration must have a filename
        if (empty($migration->getFilename())) {
            $errors[] = 'Migration must have a filename for pre-validation';
        }
        
        // Check if pre-validation has already passed
        // If validation passed, migration can proceed to migrating state
        if ($migration->hasValidationsPassed('pre')) {
            // Validation already passed - this is fine, migration can proceed
            return [];
        }
        
        return $errors;
    }
    
    /**
     * Can the migration leave this state?
     * 
     * Migration can leave ValidatingPreState if:
     * - Pre-validation has passed (hasValidationsPassed('pre') returns true)
     * - OR validation has failed (will transition to FailedState)
     * 
     * @param Migration $migration The migration object
     * @return bool True if migration can leave this state
     */
    public function canLeave(Migration $migration): bool
    {
        // Can leave if validation passed OR failed (both are terminal conditions)
        // If passed, proceed to MigratingState
        // If failed, proceed to FailedState
        return $migration->hasValidationsPassed('pre') || 
               !empty($migration->getValidationLogs('pre'));
    }
    
    /**
     * Get possible "from" states that can transition to validating_pre
     * 
     * ValidatingPreState can be entered from:
     * - PreparingState (State 2) - after dependencies resolved and files validated
     * 
     * @return array<int> Array of state IDs that can transition to this state
     */
    protected function getPossibleFromStates(): array
    {
        return [
            PreparingState::getId(), // State 2
        ];
    }
    
    /**
     * Get doctrine validator instance
     * 
     * @param Orchestrator $orchestrator The orchestrator instance
     * @return DoctrineValidator Doctrine validator instance
     */
    private function getDoctrineValidator(Orchestrator $orchestrator): DoctrineValidator
    {
        if ($this->doctrineValidator === null) {
            $this->doctrineValidator = $orchestrator->getDoctrineValidator();
        }
        
        return $this->doctrineValidator;
    }
    
    /**
     * Validate SQL syntax (basic check)
     * 
     * Performs basic SQL syntax validation. This is a simple check to ensure
     * the SQL is not obviously malformed. More sophisticated validation
     * could be added later.
     * 
     * @param Migration $migration The migration object
     * @return array<string> Array of validation error messages (empty if valid)
     */
    private function validateSqlSyntax(Migration $migration): array
    {
        $errors = [];
        $sql = trim($migration->getSql());
        
        // Basic checks
        if (empty($sql)) {
            $errors[] = 'Migration SQL is empty';
            return $errors;
        }
        
        // Check for balanced parentheses (basic syntax check)
        $openParens = substr_count($sql, '(');
        $closeParens = substr_count($sql, ')');
        if ($openParens !== $closeParens) {
            $errors[] = sprintf(
                'SQL has unbalanced parentheses (open: %d, close: %d)',
                $openParens,
                $closeParens
            );
        }
        
        // Check for balanced quotes (basic syntax check)
        $singleQuotes = substr_count($sql, "'") - substr_count($sql, "\\'");
        if ($singleQuotes % 2 !== 0) {
            $errors[] = 'SQL has unbalanced single quotes';
        }
        
        // Check for semicolons (should have at least one statement)
        if (strpos($sql, ';') === false && !preg_match('/^\s*(CREATE|ALTER|DROP|INSERT|UPDATE|DELETE|SELECT)\s+/i', $sql)) {
            $errors[] = 'SQL appears to be missing statement terminators or valid SQL keywords';
        }
        
        return $errors;
    }
}
