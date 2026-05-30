<?php
/**
 * Edge Concurrency Service
 * 
 * Provides deterministic write serialization and conflict handling for edge mutations.
 * Pure coordination service - no edge data mutation, only safe execution sequencing.
 * 
 * @package App\Services\ContextGraph
 * @version 4.0.86
 */

class EdgeConcurrencyService {

    private $db;
    private $lockTimeoutSeconds = 30;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Acquire deterministic lock for edge write space.
     * 
     * Lock key derived from source/target identity to prevent concurrent writes
     * to same logical edge space. Uses MySQL GET_LOCK for deterministic behavior.
     * 
     * @param string $sourceType Source entity type
     * @param int $sourceId Source entity ID
     * @param string $targetType Target entity type
     * @param int $targetId Target entity ID
     * @return array Lock result with token or failure
     */
    public function acquireLock($sourceType, $sourceId, $targetType, $targetId) {
        $lockKey = $this->deriveLockKey($sourceType, $sourceId, $targetType, $targetId);
        
        // Try immediate acquisition first
        if ($this->tryAcquireLock($lockKey)) {
            return array(
                'success' => true,
                'lock_key' => $lockKey,
                'token' => $lockKey // Use key as token for simplicity
            );
        }
        
        // Retry with fixed backoff schedule
        $retrySchedule = array(500000, 1000000, 2000000); // microseconds: 0.5s, 1s, 2s
        
        foreach ($retrySchedule as $delay) {
            usleep($delay);
            if ($this->tryAcquireLock($lockKey)) {
                return array(
                    'success' => true,
                    'lock_key' => $lockKey,
                    'token' => $lockKey
                );
            }
        }
        
        // All retries failed
        return array(
            'success' => false,
            'reason' => 'Lock acquisition failed after retries',
            'lock_key' => $lockKey
        );
    }
    
    /**
     * Release acquired lock.
     * 
     * @param string $lockToken Lock token (same as lock key)
     * @return bool Success status
     */
    public function releaseLock($lockToken) {
        try {
            $stmt = $this->db->prepare("SELECT RELEASE_LOCK(?)");
            $stmt->execute(array($lockToken));
            $result = $stmt->fetchColumn();
            return $result == 1; // 1 = lock released, 0 = lock not held
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Execute edge mutation within lock protection.
     * 
     * Wrapper that ensures safe execution of edge mutations by:
     * 1. Acquiring lock
     * 2. Executing callback
     * 3. Releasing lock
     * 
     * @param string $sourceType
     * @param int $sourceId
     * @param string $targetType
     * @param int $targetId
     * @param callable $mutationCallback Function to execute while holding lock
     * @return array Execution result
     */
    public function executeWithLock($sourceType, $sourceId, $targetType, $targetId, $mutationCallback) {
        $lockResult = $this->acquireLock($sourceType, $sourceId, $targetType, $targetId);
        
        if (!$lockResult['success']) {
            return array(
                'success' => false,
                'reason' => 'Failed to acquire lock: ' . $lockResult['reason']
            );
        }
        
        try {
            $result = call_user_func($mutationCallback);
            return array(
                'success' => true,
                'result' => $result
            );
        } catch (Exception $e) {
            return array(
                'success' => false,
                'reason' => 'Mutation failed: ' . $e->getMessage()
            );
        } finally {
            $this->releaseLock($lockResult['token']);
        }
    }
    
    /**
     * Derive deterministic lock key from edge identity.
     * 
     * Lock key format: lupo_edge_{source_type}_{source_id}_{target_type}_{target_id}
     * Normalized to lowercase for consistency.
     * 
     * @param string $sourceType
     * @param int $sourceId
     * @param string $targetType
     * @param int $targetId
     * @return string Lock key
     */
    private function deriveLockKey($sourceType, $sourceId, $targetType, $targetId) {
        return sprintf(
            'lupo_edge_%s_%d_%s_%d',
            strtolower(trim($sourceType)),
            (int)$sourceId,
            strtolower(trim($targetType)),
            (int)$targetId
        );
    }
    
    /**
     * Attempt to acquire MySQL named lock.
     * 
     * @param string $lockKey Lock key
     * @return bool True if lock acquired
     */
    private function tryAcquireLock($lockKey) {
        try {
            $stmt = $this->db->prepare("SELECT GET_LOCK(?, ?)");
            $stmt->execute(array($lockKey, $this->lockTimeoutSeconds));
            $result = $stmt->fetchColumn();
            return $result == 1; // 1 = lock acquired, 0 = lock not available
        } catch (Exception $e) {
            return false;
        }
    }
}
