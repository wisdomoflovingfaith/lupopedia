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
 *   message: "Created DoctrineValidator for Migration Orchestrator. Validates migrations comply with Lupopedia doctrine (no foreign keys, triggers, stored procedures, etc.)."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "validation"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Doctrine Validator"
 *   description: "Validates migrations comply with Lupopedia database doctrine"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator;

use Lupopedia\MigrationOrchestrator\Models\Migration;

/**
 * DoctrineValidator
 * 
 * Validates that migrations comply with Lupopedia database doctrine:
 * - No foreign key constraints
 * - No triggers
 * - No stored procedures or functions
 * - UTC timestamps (YYYYMMDDHHMMSS format)
 * - Soft deletes (is_deleted, deleted_ymdhis)
 * - Application logic first, database logic second
 * 
 * @package Lupopedia\MigrationOrchestrator
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class DoctrineValidator
{
    /**
     * Validate migration SQL against doctrine
     * 
     * @param Migration $migration Migration to validate
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validateMigration(Migration $migration): array
    {
        $errors = [];
        $sql = $migration->getSql();
        
        // Check for foreign keys
        if (preg_match('/FOREIGN\s+KEY/i', $sql)) {
            $errors[] = 'Migration contains FOREIGN KEY constraints (violates doctrine: no foreign keys)';
        }
        
        // Check for triggers
        if (preg_match('/CREATE\s+TRIGGER/i', $sql)) {
            $errors[] = 'Migration contains CREATE TRIGGER (violates doctrine: no triggers)';
        }
        
        // Check for stored procedures
        if (preg_match('/CREATE\s+(PROCEDURE|FUNCTION)/i', $sql)) {
            $errors[] = 'Migration contains stored procedures or functions (violates doctrine: no stored procedures)';
        }
        
        // Check for hard deletes (DELETE without WHERE is_deleted)
        // This is a heuristic - may need refinement
        if (preg_match('/DELETE\s+FROM\s+\w+\s+(?!WHERE.*is_deleted)/i', $sql)) {
            // Allow DELETE if it's part of a soft-delete pattern
            if (!preg_match('/UPDATE.*SET.*is_deleted/i', $sql)) {
                $errors[] = 'Migration may contain hard deletes (violates doctrine: use soft deletes with is_deleted)';
            }
        }
        
        return $errors;
    }
    
    /**
     * Validate migration file name follows convention
     * 
     * @param string $filename Migration filename
     * @return array<string> Array of validation error messages (empty if valid)
     */
    public function validateFilename(string $filename): array
    {
        $errors = [];
        
        // Check filename follows pattern: {name}_{version}.sql
        if (!preg_match('/^[a-z0-9_-]+_\d+\.\d+\.\d+\.sql$/i', $filename)) {
            $errors[] = sprintf(
                'Migration filename "%s" does not follow convention: {name}_{version}.sql (e.g., doctrine_mapping_4_0_33.sql)',
                $filename
            );
        }
        
        return $errors;
    }
}
