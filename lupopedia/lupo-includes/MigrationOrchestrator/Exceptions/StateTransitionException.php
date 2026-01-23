<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.34
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created StateTransitionException for Migration Orchestrator. Exception thrown when invalid state transitions are attempted."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "exceptions"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "State Transition Exception"
 *   description: "Exception thrown when invalid state transitions are attempted in the migration orchestrator"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\Exceptions;

/**
 * StateTransitionException
 * 
 * Exception thrown when an invalid state transition is attempted in the
 * Migration Orchestrator state machine.
 * 
 * @package Lupopedia\MigrationOrchestrator\Exceptions
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class StateTransitionException extends \RuntimeException
{
    /**
     * Constructor
     * 
     * @param string $message Exception message
     * @param int $code Exception code
     * @param \Throwable|null $previous Previous exception
     */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
