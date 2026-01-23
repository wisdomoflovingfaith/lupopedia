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
 *   message: "Created LoggerInterface for Migration Orchestrator. Defines logging contract for migration events."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "logging"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Logger Interface"
 *   description: "Interface defining the logging contract for migration orchestrator events"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator;

/**
 * LoggerInterface
 * 
 * Defines the logging contract for migration orchestrator events.
 * Implementations should log migration state transitions, validation results,
 * execution progress, and errors.
 * 
 * @package Lupopedia\MigrationOrchestrator
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
interface LoggerInterface
{
    /**
     * Log an informational message
     * 
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public function info(string $message, array $context = []): void;
    
    /**
     * Log a warning message
     * 
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public function warning(string $message, array $context = []): void;
    
    /**
     * Log an error message
     * 
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public function error(string $message, array $context = []): void;
    
    /**
     * Log a debug message
     * 
     * @param string $message Log message
     * @param array $context Additional context data
     * @return void
     */
    public function debug(string $message, array $context = []): void;
}
