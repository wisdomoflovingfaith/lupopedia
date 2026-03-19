<?php
/**
 * System Error Exception
 * 
 * Thrown when a critical system error occurs that violates
 * system-wide enforcement rules or invariants.
 * 
 * @package Lupopedia\Exceptions
 * @version 4.0.83
 */

/**
 * System Error Exception
 * 
 * Represents a critical system error that indicates a violation
 * of system-wide enforcement rules or invariants.
 */
class SystemError extends Exception
{
    /**
     * System error context
     * @var string
     */
    private $context;
    
    /**
     * System error code
     * @var int
     */
    private $errorCode;
    
    /**
     * Constructor
     * 
     * @param string $message Error message
     * @param string $context Error context
     * @param int $code Error code
     * @param Exception $previous Previous exception
     */
    public function __construct($message, $context = 'unknown', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->errorCode = $code;
    }
    
    /**
     * Get error context
     * 
     * @return string Error context
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Get error code
     * 
     * @return int Error code
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    
    /**
     * Get full error details
     * 
     * @return array Error details
     */
    public function getErrorDetails()
    {
        return array(
            'message' => $this->getMessage(),
            'context' => $this->context,
            'code' => $this->errorCode,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString()
        );
    }
}
?>
