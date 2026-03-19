<?php
/**
 * Validation Error Exception
 * 
 * Thrown when validation fails due to violation of
 * system-wide validation rules or invariants.
 * 
 * @package Lupopedia\Exceptions
 * @version 4.0.83
 */

/**
 * Validation Error Exception
 * 
 * Represents a validation error that indicates a violation
 * of system-wide validation rules or invariants.
 */
class ValidationError extends Exception
{
    /**
     * Validation context
     * @var string
     */
    private $context;
    
    /**
     * Validation errors
     * @var array
     */
    private $validationErrors;
    
    /**
     * Constructor
     * 
     * @param string $message Error message
     * @param string $context Validation context
     * @param array $validationErrors Validation errors
     * @param int $code Error code
     * @param Exception $previous Previous exception
     */
    public function __construct($message, $context = 'unknown', $validationErrors = array(), $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->validationErrors = $validationErrors;
    }
    
    /**
     * Get validation context
     * 
     * @return string Validation context
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Get validation errors
     * 
     * @return array Validation errors
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
    
    /**
     * Get full validation details
     * 
     * @return array Validation details
     */
    public function getValidationDetails()
    {
        return array(
            'message' => $this->getMessage(),
            'context' => $this->context,
            'errors' => $this->validationErrors,
            'code' => $this->code,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString()
        );
    }
}
?>
