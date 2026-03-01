<?php
/**
 * Base exception class for initialization workflow errors
 * 
 * All initialization-related exceptions extend this base class to provide
 * consistent error handling throughout the 4.0.44 initialization workflow.
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class InitializationException extends Exception
{
    /**
     * Create a new initialization exception
     * 
     * @param string $message Error message
     * @param int $code Error code (optional)
     * @param Exception $previous Previous exception for chaining (optional)
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
