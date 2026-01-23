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
 *   message: "Created StateTransitionRecorder for Migration Orchestrator. Records state transitions to lupopedia_orchestration.lupo_migration_system_state table."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-tracking"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator State Transition Recorder"
 *   description: "Records state transitions to the orchestration schema database tables"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator;

/**
 * StateTransitionRecorder
 * 
 * Records state transitions to the lupopedia_orchestration schema.
 * Updates lupo_migration_system_state table with current state information.
 * 
 * @package Lupopedia\MigrationOrchestrator
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class StateTransitionRecorder
{
    private $db;
    
    /**
     * Constructor
     * 
     * @param object $db Database connection (PDO_DB instance)
     */
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Record transition start
     * 
     * Records that a state transition has started.
     * 
     * @param int $migrationId Migration ID
     * @param int $fromStateId Source state ID
     * @param int $toStateId Target state ID
     * @return void
     */
    public function recordTransitionStart(int $migrationId, int $fromStateId, int $toStateId): void
    {
        $now = gmdate('YmdHis');
        
        // Update migration system state
        $sql = "
            UPDATE lupopedia_orchestration.lupo_migration_system_state
            SET 
                previous_state_id = current_state_id,
                current_state_id = :to_state_id,
                state_entered_ymdhis = :now,
                updated_ymdhis = :now
            WHERE migration_batch_id = :migration_id
        ";
        
        $this->db->execute($sql, [
            ':migration_id' => $migrationId,
            ':to_state_id' => $toStateId,
            ':now' => $now
        ]);
    }
    
    /**
     * Record transition completion
     * 
     * Records that a state transition has completed successfully.
     * 
     * @param int $migrationId Migration ID
     * @param int $stateId Current state ID
     * @return void
     */
    public function recordTransitionComplete(int $migrationId, int $stateId): void
    {
        $now = gmdate('YmdHis');
        
        // Update migration system state with completion timestamp
        $sql = "
            UPDATE lupopedia_orchestration.lupo_migration_system_state
            SET 
                updated_ymdhis = :now,
                state_data = JSON_SET(
                    COALESCE(state_data, '{}'),
                    '$.last_transition_completed',
                    :now
                )
            WHERE migration_batch_id = :migration_id
              AND current_state_id = :state_id
        ";
        
        $this->db->execute($sql, [
            ':migration_id' => $migrationId,
            ':state_id' => $stateId,
            ':now' => $now
        ]);
    }
}
