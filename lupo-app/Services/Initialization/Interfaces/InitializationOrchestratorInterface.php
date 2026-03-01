<?php
/**
 * Interface for orchestrating the complete initialization workflow
 * 
 * Defines the contract for coordinating all initialization components
 * and executing the complete workflow sequence.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface InitializationOrchestratorInterface
{
    /**
     * Execute the complete initialization workflow
     * 
     * Coordinates all components in the correct sequence:
     * 1. Doctrine ingestion from Channel 0
     * 2. Development thread creation in Channel 42
     * 3. Status directory audit
     * 4. Audit report generation
     * 5. Channel 42 summary posting
     * 6. System log writing
     * 7. Validation
     * 8. Completion notification
     * 
     * Implements "continue on error" strategy - logs errors and proceeds
     * with remaining tasks.
     * 
     * @return array Final status report with successes and failures
     * @throws InitializationException If critical failure prevents continuation
     */
    public function run();
    
    /**
     * Get workflow execution results
     * 
     * @return array Detailed results from each workflow step
     */
    public function getResults();
    
    /**
     * Check if workflow completed successfully
     * 
     * @return bool True if all critical steps succeeded, false otherwise
     */
    public function isSuccessful();
}
