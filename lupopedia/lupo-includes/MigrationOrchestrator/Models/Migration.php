<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.44
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Enhanced Migration model to bridge 8-state machine with all 8 orchestration tables. Fixed critical state-to-status mapping architecture - added automatic synchronization between state_id (8 states) and file_status (5-state enum). Dual-storage system now properly synchronized. This model is the central data access layer connecting states to database operations."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "models"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator Migration Model"
 *   description: "Model class representing a migration file in the orchestrator system"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator\Models;

use Lupopedia\MigrationOrchestrator\State\StateInterface;

/**
 * Migration
 * 
 * Represents a migration file in the Migration Orchestrator system.
 * Acts as the bridge between the 8-state machine and all 8 orchestration tables.
 * 
 * **Dual Storage Architecture:**
 * - `state_id` (int, stored in properties JSON): Detailed 8-state machine (1-8)
 *   - 1: idle, 2: preparing, 3: validating_pre, 4: migrating, 5: validating_post,
 *     6: completing, 7: rolling_back, 8: failed
 * - `file_status` (enum): Simple 5-state status for fast queries
 *   - 'pending', 'processing', 'completed', 'failed', 'rolled_back'
 * 
 * **State-to-Status Mapping:**
 * - When `setStateId()` is called, `file_status` is automatically updated:
 *   - State 1 → 'pending'
 *   - States 2-6 → 'processing'
 *   - State 7 → 'rolled_back'
 *   - State 8 → 'failed'
 * - 'completed' is a terminal status (not a state) and must be set explicitly
 *   via `markAsCompleted()` when the migration finishes successfully.
 * 
 * This model provides unified access to:
 * 1. migration_files - Core migration record (primary table)
 * 2. migration_batches - Batch grouping and execution tracking
 * 3. migration_dependencies - Dependency graph resolution
 * 4. migration_validation_log - Pre/during/post validation tracking
 * 5. migration_rollback_log - Rollback execution history
 * 6. migration_system_state - System freeze/thaw management
 * 7. migration_progress - Real-time progress tracking
 * 8. migration_alerts - Failure notification and escalation
 * 
 * @package Lupopedia\MigrationOrchestrator\Models
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class Migration
{
    private $db;
    private $fileId;
    private $batchId;
    private $filePath;
    private $fileType;
    private $fileStatus;
    private $fileHash;
    private $stateId;
    private $sql;
    private $errorMessage;
    private $retryCount;
    private $sortOrder;
    private $properties;
    private $createdYmdhis;
    private $updatedYmdhis;
    private $isDeleted;
    private $deletedYmdhis;
    
    /**
     * Constructor
     * 
     * @param object $db Database connection (PDO_DB instance)
     * @param int|null $fileId Migration file ID (null for new migration)
     */
    public function __construct($db, ?int $fileId = null)
    {
        $this->db = $db;
        
        if ($fileId !== null) {
            $this->loadFromDatabase($fileId);
        }
    }
    
    /**
     * Load migration from database
     * 
     * @param int $fileId Migration file ID
     * @return void
     * @throws \RuntimeException If migration not found
     */
    private function loadFromDatabase(int $fileId): void
    {
        $sql = "
            SELECT 
                file_id,
                batch_id,
                file_path,
                file_type,
                file_status,
                file_hash,
                properties,
                retry_count,
                sort_order,
                error_message,
                created_ymdhis,
                updated_ymdhis,
                is_deleted,
                deleted_ymdhis
            FROM lupopedia_orchestration.migration_files
            WHERE file_id = :file_id
              AND is_deleted = 0
        ";
        
        $row = $this->db->fetchRow($sql, [':file_id' => $fileId]);
        
        if (!$row) {
            throw new \RuntimeException(sprintf('Migration file %d not found', $fileId));
        }
        
        $this->fileId = (int)$row['file_id'];
        $this->batchId = (int)$row['batch_id'];
        $this->filePath = $row['file_path'];
        $this->fileType = $row['file_type'];
        $this->fileStatus = $row['file_status'];
        $this->fileHash = $row['file_hash'];
        $this->retryCount = (int)$row['retry_count'];
        $this->sortOrder = (int)$row['sort_order'];
        $this->errorMessage = $row['error_message'];
        $this->createdYmdhis = (int)$row['created_ymdhis'];
        $this->updatedYmdhis = (int)$row['updated_ymdhis'];
        $this->isDeleted = (int)$row['is_deleted'];
        $this->deletedYmdhis = $row['deleted_ymdhis'] ? (int)$row['deleted_ymdhis'] : null;
        
        // Load properties JSON
        $this->properties = $row['properties'] ? json_decode($row['properties'], true) : [];
        
        // Load state ID from properties or migration_system_state
        $this->stateId = $this->properties['state_id'] ?? $this->loadStateId();
        
        // Load SQL content from file
        $this->sql = $this->loadSqlFromFile();
    }
    
    /**
     * Load state ID from migration_system_state table or properties
     * 
     * State is stored in migration_files.properties['state_id'] or
     * can be derived from file_status. Defaults to 1 (idle).
     * 
     * @return int State ID (defaults to 1 = idle if not found)
     */
    private function loadStateId(): int
    {
        // First check properties
        if (isset($this->properties['state_id'])) {
            return (int)$this->properties['state_id'];
        }
        
        // Map file_status to state_id if needed
        // For now, default to idle (state 1)
        return 1; // Default to idle state
    }
    
    /**
     * Load SQL content from migration file
     * 
     * @return string SQL content
     */
    private function loadSqlFromFile(): string
    {
        if (empty($this->filePath)) {
            return '';
        }
        
        $fullPath = ABSPATH . $this->filePath;
        
        if (!file_exists($fullPath)) {
            return '';
        }
        
        return file_get_contents($fullPath);
    }
    
    /**
     * Save migration to database
     * 
     * @return void
     */
    public function save(): void
    {
        $now = gmdate('YmdHis');
        
        // Update properties with current state
        $this->properties['state_id'] = $this->stateId;
        $this->properties['last_updated'] = $now;
        
        if ($this->fileId) {
            // Update existing migration
            $sql = "
                UPDATE lupopedia_orchestration.migration_files
                SET 
                    file_status = :file_status,
                    file_hash = :file_hash,
                    error_message = :error_message,
                    retry_count = :retry_count,
                    properties = :properties,
                    updated_ymdhis = :updated_ymdhis
                WHERE file_id = :file_id
            ";
            
            $this->db->execute($sql, [
                ':file_id' => $this->fileId,
                ':file_status' => $this->fileStatus,
                ':file_hash' => $this->fileHash ?? $this->calculateHash(),
                ':error_message' => $this->errorMessage,
                ':retry_count' => $this->retryCount,
                ':properties' => json_encode($this->properties),
                ':updated_ymdhis' => $now
            ]);
        } else {
            // Insert new migration
            $sql = "
                INSERT INTO lupopedia_orchestration.migration_files
                (
                    batch_id,
                    file_path,
                    file_type,
                    file_status,
                    file_hash,
                    retry_count,
                    sort_order,
                    properties,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted
                )
                VALUES
                (
                    :batch_id,
                    :file_path,
                    :file_type,
                    :file_status,
                    :file_hash,
                    :retry_count,
                    :sort_order,
                    :properties,
                    :created_ymdhis,
                    :updated_ymdhis,
                    0
                )
            ";
            
            $this->db->execute($sql, [
                ':batch_id' => $this->batchId,
                ':file_path' => $this->filePath,
                ':file_type' => $this->fileType,
                ':file_status' => $this->fileStatus ?? 'pending',
                ':file_hash' => $this->fileHash ?? $this->calculateHash(),
                ':retry_count' => $this->retryCount ?? 0,
                ':sort_order' => $this->sortOrder ?? 0,
                ':properties' => json_encode($this->properties),
                ':created_ymdhis' => $now,
                ':updated_ymdhis' => $now
            ]);
            
            $this->fileId = (int)$this->db->lastInsertId();
        }
        
        $this->updatedYmdhis = (int)$now;
    }
    
    /**
     * Calculate file hash
     * 
     * @return string SHA256 hash of file content
     */
    private function calculateHash(): string
    {
        return hash('sha256', $this->sql);
    }
    
    // Getters
    
    public function getId(): ?int
    {
        return $this->fileId;
    }
    
    public function getBatchId(): int
    {
        return $this->batchId;
    }
    
    public function getFilePath(): string
    {
        return $this->filePath ?? '';
    }
    
    public function getFilename(): string
    {
        return basename($this->filePath ?? '');
    }
    
    public function getFileType(): string
    {
        return $this->fileType ?? '';
    }
    
    public function getFileStatus(): string
    {
        return $this->fileStatus ?? 'pending';
    }
    
    public function getSql(): string
    {
        return $this->sql ?? '';
    }
    
    public function getStateId(): int
    {
        return $this->stateId ?? 1; // Default to idle
    }
    
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
    
    public function getRetryCount(): int
    {
        return $this->retryCount ?? 0;
    }
    
    public function getProperties(): array
    {
        return $this->properties ?? [];
    }
    
    public function getDescription(): string
    {
        return $this->properties['description'] ?? $this->getFilename();
    }
    
    public function getAuthorId(): ?int
    {
        return $this->properties['author_id'] ?? null;
    }
    
    // Setters
    
    public function setBatchId(int $batchId): void
    {
        $this->batchId = $batchId;
    }
    
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
        // Reload SQL when path changes
        $this->sql = $this->loadSqlFromFile();
        $this->fileHash = $this->calculateHash();
    }
    
    public function setFileType(string $fileType): void
    {
        $this->fileType = $fileType;
    }
    
    public function setFileStatus(string $fileStatus): void
    {
        $this->fileStatus = $fileStatus;
    }
    
    /**
     * Mark migration as completed
     * 
     * Sets file_status to 'completed' (terminal status). This should be called
     * when the migration finishes successfully, typically from CompletingState.
     * 
     * **State Validation:**
     * This method validates that the migration is in CompletingState (state 6)
     * before marking as completed. This prevents invalid state transitions and
     * catches programming errors early.
     * 
     * **Note:** This does NOT change state_id - the state machine may remain in
     * completing state (6) or transition to idle (1) after completion. The
     * validation ensures we're in the correct state when marking completion.
     * 
     * @throws \RuntimeException If migration is not in CompletingState (state 6)
     * @return void
     */
    public function markAsCompleted(): void
    {
        // Validate state_id is 6 (CompletingState)
        if ($this->stateId !== 6) {
            throw new \RuntimeException(
                sprintf(
                    'Cannot mark migration %d as completed: migration is in state %d, not state 6 (completing). ' .
                    'Migration must be in CompletingState before marking as completed.',
                    $this->fileId ?? 0,
                    $this->stateId
                )
            );
        }
        
        // Set terminal status
        $this->fileStatus = 'completed';
    }
    
    /**
     * Map state ID to file_status enum value
     * 
     * Maps the 8-state machine (state_id) to the 5-state enum (file_status):
     * - State 1 (idle) → 'pending'
     * - States 2-6 (preparing, validating_pre, migrating, validating_post, completing) → 'processing'
     * - State 7 (rolling_back) → 'rolled_back'
     * - State 8 (failed) → 'failed'
     * 
     * Note: When completing state finishes successfully, file_status should be
     * explicitly set to 'completed' (this is a terminal status, not a state).
     * 
     * @param int $stateId The state ID (1-8)
     * @return string The corresponding file_status enum value
     */
    private function stateIdToFileStatus(int $stateId): string
    {
        return match($stateId) {
            1 => 'pending',           // idle
            2, 3, 4, 5, 6 => 'processing', // preparing, validating_pre, migrating, validating_post, completing
            7 => 'rolled_back',       // rolling_back
            8 => 'failed',            // failed
            default => 'pending',     // fallback to pending for unknown states
        };
    }
    
    public function setStateId(int $stateId): void
    {
        $this->stateId = $stateId;
        $this->properties['state_id'] = $stateId;
        
        // Automatically update file_status to keep enum in sync with state machine
        // Exception: 'completed' status must be set explicitly (it's terminal, not a state)
        if ($this->fileStatus !== 'completed') {
            $this->fileStatus = $this->stateIdToFileStatus($stateId);
        }
    }
    
    public function setSql(string $sql): void
    {
        $this->sql = $sql;
        $this->fileHash = $this->calculateHash();
    }
    
    public function setErrorMessage(?string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }
    
    public function setRetryCount(int $retryCount): void
    {
        $this->retryCount = $retryCount;
    }
    
    public function setDescription(string $description): void
    {
        $this->properties['description'] = $description;
    }
    
    public function setAuthorId(?int $authorId): void
    {
        $this->properties['author_id'] = $authorId;
    }
    
    /**
     * Get current state object
     * 
     * Note: This requires StateInterface implementations to be available.
     * Returns null if state class cannot be determined.
     * 
     * @return StateInterface|null Current state instance
     */
    public function getCurrentState(): ?StateInterface
    {
        // This would require the state classes to be loaded
        // For now, return null - states should be managed by Orchestrator
        return null;
    }
    
    /**
     * Increment retry count
     * 
     * @return void
     */
    public function incrementRetryCount(): void
    {
        $this->retryCount = ($this->retryCount ?? 0) + 1;
    }
    
    // ======================================================================
    // BATCH OPERATIONS (migration_batches table)
    // ======================================================================
    
    /**
     * Get batch information
     * 
     * @return array|null Batch data or null if not found
     */
    public function getBatch(): ?array
    {
        if (!$this->batchId) {
            return null;
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_batches
            WHERE batch_id = :batch_id
              AND is_deleted = 0
        ";
        
        return $this->db->fetchRow($sql, [':batch_id' => $this->batchId]);
    }
    
    /**
     * Update batch progress
     * 
     * @param int $processedFiles Number of processed files
     * @param int $failedFiles Number of failed files
     * @return void
     */
    public function updateBatchProgress(int $processedFiles, int $failedFiles = 0): void
    {
        if (!$this->batchId) {
            return;
        }
        
        $now = gmdate('YmdHis');
        $sql = "
            UPDATE lupopedia_orchestration.migration_batches
            SET 
                processed_files = :processed_files,
                failed_files = :failed_files,
                updated_ymdhis = :updated_ymdhis
            WHERE batch_id = :batch_id
        ";
        
        $this->db->execute($sql, [
            ':batch_id' => $this->batchId,
            ':processed_files' => $processedFiles,
            ':failed_files' => $failedFiles,
            ':updated_ymdhis' => $now
        ]);
    }
    
    // ======================================================================
    // DEPENDENCY OPERATIONS (migration_dependencies table)
    // ======================================================================
    
    /**
     * Get all dependencies for this migration
     * 
     * @return array Array of dependency records
     */
    public function getDependencies(): array
    {
        if (!$this->fileId) {
            return [];
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_dependencies
            WHERE file_id = :file_id
              AND is_deleted = 0
            ORDER BY sort_order ASC
        ";
        
        return $this->db->fetchAll($sql, [':file_id' => $this->fileId]) ?: [];
    }
    
    /**
     * Get migrations this migration depends on
     * 
     * @return array Array of migration file IDs
     */
    public function getDependentOn(): array
    {
        $dependencies = $this->getDependencies();
        $fileIds = [];
        
        foreach ($dependencies as $dep) {
            if ($dep['dependency_type'] === 'required' || $dep['dependency_type'] === 'optional') {
                $fileIds[] = (int)$dep['depends_on_file_id'];
            }
        }
        
        return $fileIds;
    }
    
    /**
     * Add a dependency
     * 
     * @param int $dependsOnFileId File ID this migration depends on
     * @param string $dependencyType 'required', 'optional', or 'conflict'
     * @param string|null $description Optional description
     * @return int Dependency ID
     */
    public function addDependency(int $dependsOnFileId, string $dependencyType = 'required', ?string $description = null): int
    {
        if (!$this->fileId) {
            throw new \RuntimeException('Migration must be saved before adding dependencies');
        }
        
        $now = gmdate('YmdHis');
        
        // Get max sort_order
        $maxOrderSql = "
            SELECT MAX(sort_order) as max_order
            FROM lupopedia_orchestration.migration_dependencies
            WHERE file_id = :file_id
        ";
        $maxOrder = $this->db->fetchRow($maxOrderSql, [':file_id' => $this->fileId]);
        $nextOrder = ($maxOrder['max_order'] ?? 0) + 1;
        
        $sql = "
            INSERT INTO lupopedia_orchestration.migration_dependencies
            (
                file_id,
                depends_on_file_id,
                dependency_type,
                dependency_description,
                sort_order,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            )
            VALUES
            (
                :file_id,
                :depends_on_file_id,
                :dependency_type,
                :dependency_description,
                :sort_order,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ";
        
        $this->db->execute($sql, [
            ':file_id' => $this->fileId,
            ':depends_on_file_id' => $dependsOnFileId,
            ':dependency_type' => $dependencyType,
            ':dependency_description' => $description,
            ':sort_order' => $nextOrder,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Check if all required dependencies are satisfied
     * 
     * @return array Array of unsatisfied dependencies (empty if all satisfied)
     */
    public function checkDependenciesSatisfied(): array
    {
        $dependencies = $this->getDependencies();
        $unsatisfied = [];
        
        foreach ($dependencies as $dep) {
            if ($dep['dependency_type'] === 'required') {
                $depFileId = (int)$dep['depends_on_file_id'];
                
                // Check if dependency migration exists and is completed
                $checkSql = "
                    SELECT file_status
                    FROM lupopedia_orchestration.migration_files
                    WHERE file_id = :file_id
                      AND is_deleted = 0
                ";
                $depFile = $this->db->fetchRow($checkSql, [':file_id' => $depFileId]);
                
                if (!$depFile || $depFile['file_status'] !== 'completed') {
                    $unsatisfied[] = [
                        'file_id' => $depFileId,
                        'dependency_id' => $dep['dependency_id'],
                        'reason' => $depFile ? 'Not completed' : 'Not found'
                    ];
                }
            }
        }
        
        return $unsatisfied;
    }
    
    // ======================================================================
    // VALIDATION OPERATIONS (migration_validation_log table)
    // ======================================================================
    
    /**
     * Log a validation result
     * 
     * @param string $phase 'pre', 'during', or 'post'
     * @param string $validationType Type of validation (e.g., 'doctrine_check', 'schema_check')
     * @param string $status 'passed', 'failed', or 'warning'
     * @param string|null $result Validation result message
     * @param int $executionTimeMs Execution time in milliseconds
     * @param array|null $properties Additional properties
     * @return int Validation log ID
     */
    public function logValidation(
        string $phase,
        string $validationType,
        string $status,
        ?string $result = null,
        int $executionTimeMs = 0,
        ?array $properties = null
    ): int {
        if (!$this->fileId) {
            throw new \RuntimeException('Migration must be saved before logging validation');
        }
        
        $now = gmdate('YmdHis');
        
        $sql = "
            INSERT INTO lupopedia_orchestration.migration_validation_log
            (
                batch_id,
                file_id,
                validation_phase,
                validation_type,
                validation_status,
                validation_result,
                execution_time_ms,
                properties,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            )
            VALUES
            (
                :batch_id,
                :file_id,
                :validation_phase,
                :validation_type,
                :validation_status,
                :validation_result,
                :execution_time_ms,
                :properties,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ";
        
        $this->db->execute($sql, [
            ':batch_id' => $this->batchId,
            ':file_id' => $this->fileId,
            ':validation_phase' => $phase,
            ':validation_type' => $validationType,
            ':validation_status' => $status,
            ':validation_result' => $result,
            ':execution_time_ms' => $executionTimeMs,
            ':properties' => $properties ? json_encode($properties) : null,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Get validation logs for this migration
     * 
     * @param string|null $phase Filter by phase ('pre', 'during', 'post')
     * @return array Array of validation log records
     */
    public function getValidationLogs(?string $phase = null): array
    {
        if (!$this->fileId) {
            return [];
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_validation_log
            WHERE file_id = :file_id
              AND is_deleted = 0
        ";
        
        $params = [':file_id' => $this->fileId];
        
        if ($phase) {
            $sql .= " AND validation_phase = :validation_phase";
            $params[':validation_phase'] = $phase;
        }
        
        $sql .= " ORDER BY created_ymdhis ASC";
        
        return $this->db->fetchAll($sql, $params) ?: [];
    }
    
    /**
     * Check if all validations passed for a phase
     * 
     * @param string $phase 'pre', 'during', or 'post'
     * @return bool True if all validations passed
     */
    public function hasValidationsPassed(string $phase): bool
    {
        $logs = $this->getValidationLogs($phase);
        
        if (empty($logs)) {
            return false; // No validations run yet
        }
        
        foreach ($logs as $log) {
            if ($log['validation_status'] !== 'passed') {
                return false;
            }
        }
        
        return true;
    }
    
    // ======================================================================
    // ROLLBACK OPERATIONS (migration_rollback_log table)
    // ======================================================================
    
    /**
     * Log a rollback operation
     * 
     * @param string $classification 'A', 'B', 'C', or 'D'
     * @param string $reason Rollback reason
     * @param int $filesAffected Number of files affected
     * @param int $rollbackTimeMs Rollback execution time in milliseconds
     * @param string|null $errorMessage Error message if rollback failed
     * @return int Rollback log ID
     */
    public function logRollback(
        string $classification,
        string $reason,
        int $filesAffected = 1,
        int $rollbackTimeMs = 0,
        ?string $errorMessage = null
    ): int {
        if (!$this->fileId) {
            throw new \RuntimeException('Migration must be saved before logging rollback');
        }
        
        $now = gmdate('YmdHis');
        
        $sql = "
            INSERT INTO lupopedia_orchestration.migration_rollback_log
            (
                batch_id,
                file_id,
                rollback_classification,
                rollback_reason,
                rollback_status,
                files_affected,
                rollback_time_ms,
                error_message,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            )
            VALUES
            (
                :batch_id,
                :file_id,
                :rollback_classification,
                :rollback_reason,
                'completed',
                :files_affected,
                :rollback_time_ms,
                :error_message,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ";
        
        $this->db->execute($sql, [
            ':batch_id' => $this->batchId,
            ':file_id' => $this->fileId,
            ':rollback_classification' => $classification,
            ':rollback_reason' => $reason,
            ':files_affected' => $filesAffected,
            ':rollback_time_ms' => $rollbackTimeMs,
            ':error_message' => $errorMessage,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Get rollback logs for this migration
     * 
     * @return array Array of rollback log records
     */
    public function getRollbackLogs(): array
    {
        if (!$this->fileId) {
            return [];
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_rollback_log
            WHERE file_id = :file_id
              AND is_deleted = 0
            ORDER BY created_ymdhis DESC
        ";
        
        return $this->db->fetchAll($sql, [':file_id' => $this->fileId]) ?: [];
    }
    
    // ======================================================================
    // PROGRESS OPERATIONS (migration_progress table)
    // ======================================================================
    
    /**
     * Update progress tracking
     * 
     * @param string $currentPhase Current phase name
     * @param int $currentPhaseIndex Current phase index (0-based)
     * @param int $totalPhases Total number of phases
     * @param int $filesInPhase Files in current phase
     * @param int $filesCompletedInPhase Files completed in current phase
     * @param float $percentageComplete Percentage complete (0-100)
     * @param int $estimatedRemainingSeconds Estimated remaining seconds
     * @return int Progress record ID
     */
    public function updateProgress(
        string $currentPhase,
        int $currentPhaseIndex,
        int $totalPhases,
        int $filesInPhase,
        int $filesCompletedInPhase,
        float $percentageComplete,
        int $estimatedRemainingSeconds = 0
    ): int {
        if (!$this->batchId) {
            throw new \RuntimeException('Migration must belong to a batch to track progress');
        }
        
        $now = gmdate('YmdHis');
        
        // Check if progress record exists
        $checkSql = "
            SELECT progress_id
            FROM lupopedia_orchestration.migration_progress
            WHERE batch_id = :batch_id
              AND is_deleted = 0
            LIMIT 1
        ";
        $existing = $this->db->fetchRow($checkSql, [':batch_id' => $this->batchId]);
        
        if ($existing) {
            // Update existing
            $sql = "
                UPDATE lupopedia_orchestration.migration_progress
                SET 
                    current_phase = :current_phase,
                    current_phase_index = :current_phase_index,
                    total_phases = :total_phases,
                    files_in_phase = :files_in_phase,
                    files_completed_in_phase = :files_completed_in_phase,
                    percentage_complete = :percentage_complete,
                    estimated_remaining_seconds = :estimated_remaining_seconds,
                    updated_ymdhis = :updated_ymdhis
                WHERE progress_id = :progress_id
            ";
            
            $this->db->execute($sql, [
                ':progress_id' => $existing['progress_id'],
                ':current_phase' => $currentPhase,
                ':current_phase_index' => $currentPhaseIndex,
                ':total_phases' => $totalPhases,
                ':files_in_phase' => $filesInPhase,
                ':files_completed_in_phase' => $filesCompletedInPhase,
                ':percentage_complete' => $percentageComplete,
                ':estimated_remaining_seconds' => $estimatedRemainingSeconds,
                ':updated_ymdhis' => $now
            ]);
            
            return (int)$existing['progress_id'];
        } else {
            // Insert new
            $sql = "
                INSERT INTO lupopedia_orchestration.migration_progress
                (
                    batch_id,
                    current_phase,
                    total_phases,
                    current_phase_index,
                    files_in_phase,
                    files_completed_in_phase,
                    percentage_complete,
                    estimated_remaining_seconds,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted
                )
                VALUES
                (
                    :batch_id,
                    :current_phase,
                    :total_phases,
                    :current_phase_index,
                    :files_in_phase,
                    :files_completed_in_phase,
                    :percentage_complete,
                    :estimated_remaining_seconds,
                    :created_ymdhis,
                    :updated_ymdhis,
                    0
                )
            ";
            
            $this->db->execute($sql, [
                ':batch_id' => $this->batchId,
                ':current_phase' => $currentPhase,
                ':current_phase_index' => $currentPhaseIndex,
                ':total_phases' => $totalPhases,
                ':files_in_phase' => $filesInPhase,
                ':files_completed_in_phase' => $filesCompletedInPhase,
                ':percentage_complete' => $percentageComplete,
                ':estimated_remaining_seconds' => $estimatedRemainingSeconds,
                ':created_ymdhis' => $now,
                ':updated_ymdhis' => $now
            ]);
            
            return (int)$this->db->lastInsertId();
        }
    }
    
    /**
     * Get current progress for this migration's batch
     * 
     * @return array|null Progress data or null if not found
     */
    public function getProgress(): ?array
    {
        if (!$this->batchId) {
            return null;
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_progress
            WHERE batch_id = :batch_id
              AND is_deleted = 0
            LIMIT 1
        ";
        
        return $this->db->fetchRow($sql, [':batch_id' => $this->batchId]);
    }
    
    // ======================================================================
    // ALERT OPERATIONS (migration_alerts table)
    // ======================================================================
    
    /**
     * Create an alert
     * 
     * @param string $alertType 'error', 'warning', 'info', or 'critical'
     * @param string $title Alert title
     * @param string $message Alert message
     * @param int $escalationLevel Escalation level (0 = none)
     * @return int Alert ID
     */
    public function createAlert(
        string $alertType,
        string $title,
        string $message,
        int $escalationLevel = 0
    ): int {
        if (!$this->fileId) {
            throw new \RuntimeException('Migration must be saved before creating alerts');
        }
        
        $now = gmdate('YmdHis');
        
        $sql = "
            INSERT INTO lupopedia_orchestration.migration_alerts
            (
                batch_id,
                file_id,
                alert_type,
                alert_title,
                alert_message,
                alert_status,
                escalation_level,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            )
            VALUES
            (
                :batch_id,
                :file_id,
                :alert_type,
                :alert_title,
                :alert_message,
                'new',
                :escalation_level,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ";
        
        $this->db->execute($sql, [
            ':batch_id' => $this->batchId,
            ':file_id' => $this->fileId,
            ':alert_type' => $alertType,
            ':alert_title' => $title,
            ':alert_message' => $message,
            ':escalation_level' => $escalationLevel,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Get alerts for this migration
     * 
     * @param string|null $alertType Filter by alert type
     * @param string|null $alertStatus Filter by alert status
     * @return array Array of alert records
     */
    public function getAlerts(?string $alertType = null, ?string $alertStatus = null): array
    {
        if (!$this->fileId) {
            return [];
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_alerts
            WHERE file_id = :file_id
              AND is_deleted = 0
        ";
        
        $params = [':file_id' => $this->fileId];
        
        if ($alertType) {
            $sql .= " AND alert_type = :alert_type";
            $params[':alert_type'] = $alertType;
        }
        
        if ($alertStatus) {
            $sql .= " AND alert_status = :alert_status";
            $params[':alert_status'] = $alertStatus;
        }
        
        $sql .= " ORDER BY created_ymdhis DESC";
        
        return $this->db->fetchAll($sql, $params) ?: [];
    }
    
    // ======================================================================
    // SYSTEM STATE OPERATIONS (migration_system_state table)
    // ======================================================================
    
    /**
     * Get current system state for this migration's batch
     * 
     * @return array|null System state data or null if not found
     */
    public function getSystemState(): ?array
    {
        if (!$this->batchId) {
            return null;
        }
        
        $sql = "
            SELECT *
            FROM lupopedia_orchestration.migration_system_state
            WHERE batch_id = :batch_id
              AND is_deleted = 0
            ORDER BY created_ymdhis DESC
            LIMIT 1
        ";
        
        return $this->db->fetchRow($sql, [':batch_id' => $this->batchId]);
    }
    
    /**
     * Update system state
     * 
     * @param string $systemStatus 'normal', 'frozen', 'thawing', or 'emergency'
     * @param string|null $freezeReason Reason for freeze (if applicable)
     * @param array|null $affectedComponents Affected components JSON
     * @param string|null $userMessage User-facing message
     * @return int System state record ID
     */
    public function updateSystemState(
        string $systemStatus,
        ?string $freezeReason = null,
        ?array $affectedComponents = null,
        ?string $userMessage = null
    ): int {
        if (!$this->batchId) {
            throw new \RuntimeException('Migration must belong to a batch to update system state');
        }
        
        $now = gmdate('YmdHis');
        
        $sql = "
            INSERT INTO lupopedia_orchestration.migration_system_state
            (
                batch_id,
                system_status,
                freeze_reason,
                affected_components,
                user_message,
                created_ymdhis,
                updated_ymdhis,
                is_deleted
            )
            VALUES
            (
                :batch_id,
                :system_status,
                :freeze_reason,
                :affected_components,
                :user_message,
                :created_ymdhis,
                :updated_ymdhis,
                0
            )
        ";
        
        $this->db->execute($sql, [
            ':batch_id' => $this->batchId,
            ':system_status' => $systemStatus,
            ':freeze_reason' => $freezeReason,
            ':affected_components' => $affectedComponents ? json_encode($affectedComponents) : null,
            ':user_message' => $userMessage,
            ':created_ymdhis' => $now,
            ':updated_ymdhis' => $now
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    /**
     * Execute migration SQL atomically (transaction)
     * 
     * Executes the migration SQL within a transaction to ensure atomicity.
     * If any part fails, the entire transaction is rolled back.
     * 
     * This method is used by MigratingState to execute the actual migration SQL.
     * 
     * @param string $sql The SQL to execute
     * @return void
     * @throws \RuntimeException If SQL execution fails
     */
    public function executeSql(string $sql): void
    {
        if (empty(trim($sql))) {
            throw new \RuntimeException('Cannot execute empty SQL');
        }
        
        try {
            // Begin transaction
            $this->db->beginTransaction();
            
            // Split SQL into individual statements and execute each
            $statements = $this->splitSqlStatements($sql);
            
            foreach ($statements as $index => $statement) {
                $statement = trim($statement);
                if (empty($statement)) {
                    continue;
                }
                
                // Execute statement
                $this->db->execute($statement);
            }
            
            // Commit transaction
            $this->db->commit();
            
        } catch (\Exception $e) {
            // Rollback transaction on any error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            throw new \RuntimeException(
                sprintf('Migration SQL execution failed: %s', $e->getMessage()),
                0,
                $e
            );
        }
    }
    
    /**
     * Split SQL into individual statements
     * 
     * Splits SQL string into individual statements, handling:
     * - Semicolon-separated statements
     * - Statements in stored procedures (preserves procedure body)
     * - Comments and whitespace
     * 
     * @param string $sql The SQL to split
     * @return array<string> Array of SQL statements
     */
    private function splitSqlStatements(string $sql): array
    {
        // Remove comments (basic implementation)
        $sql = preg_replace('/--.*$/m', '', $sql); // Single-line comments
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Multi-line comments
        
        // Split by semicolon, but preserve procedure bodies
        $statements = [];
        $currentStatement = '';
        $inProcedure = false;
        $delimiter = ';';
        
        $lines = explode("\n", $sql);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // Check for DELIMITER command (MySQL stored procedure syntax)
            if (preg_match('/^\s*DELIMITER\s+(\S+)/i', $trimmed, $matches)) {
                $delimiter = $matches[1];
                continue;
            }
            
            // Check for procedure/function start
            if (preg_match('/CREATE\s+(PROCEDURE|FUNCTION|TRIGGER)/i', $trimmed)) {
                $inProcedure = true;
            }
            
            $currentStatement .= $line . "\n";
            
            // Check for procedure/function end
            if ($inProcedure && preg_match('/END\s*$/i', $trimmed)) {
                $inProcedure = false;
            }
            
            // Check for statement delimiter (if not in procedure)
            if (!$inProcedure && substr(rtrim($trimmed), -1) === $delimiter) {
                $statement = trim($currentStatement);
                if (!empty($statement)) {
                    $statements[] = $statement;
                }
                $currentStatement = '';
            }
        }
        
        // Add remaining statement if any
        $remaining = trim($currentStatement);
        if (!empty($remaining)) {
            $statements[] = $remaining;
        }
        
        return array_filter($statements, function($stmt) {
            return !empty(trim($stmt));
        });
    }
}
