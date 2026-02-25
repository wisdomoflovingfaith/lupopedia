<?php
/**
 * Interface for validating initialization workflow outputs
 * 
 * Defines the contract for running validation checks to ensure
 * the initialization workflow completed successfully.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface ValidatorInterface
{
    /**
     * Validate initialization workflow outputs
     * 
     * Runs all validation checks and generates a summary with pass/fail
     * status for each check.
     * 
     * @param array $context Validation context with paths and data to validate
     * @return array Validation summary with pass/fail status for each check
     * @throws ValidationException If critical validation fails
     */
    public function validateInitialization($context);
    
    /**
     * Check if validation passed
     * 
     * @return bool True if all checks passed, false otherwise
     */
    public function isValid();
    
    /**
     * Get validation errors
     * 
     * @return array Array of validation error messages
     */
    public function getErrors();
}
