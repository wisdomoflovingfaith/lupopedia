<?php
/**
 * EdgeConcurrencyService
 *
 * Write serialization for context graph edge mutations.
 * Acquires a deterministic MySQL named lock keyed on the source/target pair
 * before executing a mutation callback, then releases it unconditionally.
 *
 * This is a pure coordination service — it does not read, write, or delete
 * edge data.  The mutation callback is responsible for all data changes.
 *
 * Lock key format: lupo_edge_{source_type}_{source_id}_{target_type}_{target_id}
 *
 * Retry schedule on lock acquisition failure: 500ms, 1s, 2s.
 * After 3 failures → returns success=false, caller must handle as hard error.
 *
 * PHP 5.3 compatible — no type hints, no finally, no arrow functions.
 */

if (!class_exists('DatabaseFactory')) {
    require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-DatabaseFactory.php';
}

class EdgeConcurrencyService
{
    private $db;
    private $lockTimeoutSeconds = 30;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : DatabaseFactory::getConnection();
    }

    /**
     * Acquire a deterministic MySQL named lock for the given edge space.
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @param string $targetType
     * @param int    $targetId
     * @return array  array('success' => bool, 'lock_key' => string, 'token' => string, 'reason' => string)
     */
    public function acquireLock($sourceType, $sourceId, $targetType, $targetId)
    {
        $lockKey = $this->deriveLockKey($sourceType, $sourceId, $targetType, $targetId);

        if ($this->tryAcquireLock($lockKey)) {
            return array(
                'success' => true,
                'lock_key' => $lockKey,
                'token' => $lockKey
            );
        }

        $retrySchedule = array(500000, 1000000, 2000000);
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

        return array(
            'success' => false,
            'reason' => 'Lock acquisition failed after retries',
            'lock_key' => $lockKey,
            'token' => ''
        );
    }

    /**
     * Release a named lock by token (= lock key).
     *
     * @param string $lockToken
     * @return bool
     */
    public function releaseLock($lockToken)
    {
        if ($lockToken === '') {
            return false;
        }
        try {
            $stmt = $this->db->prepare("SELECT RELEASE_LOCK(?)");
            $stmt->execute(array($lockToken));
            $result = $stmt->fetchColumn();
            return $result == 1;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Execute an edge mutation callback inside a named lock window.
     *
     * The callback receives no arguments — it must close over any required
     * data via PHP 5.3+ use() capture.
     *
     * Lock is always released after the callback runs, whether it succeeds
     * or throws.
     *
     * @param string   $sourceType
     * @param int      $sourceId
     * @param string   $targetType
     * @param int      $targetId
     * @param callable $mutationCallback  function() — returns mutation result
     * @return array
     *   array(
     *     'success' => bool,
     *     'result'  => mixed,   // set on success
     *     'reason'  => string   // set on failure
     *   )
     */
    public function executeWithLock($sourceType, $sourceId, $targetType, $targetId, $mutationCallback)
    {
        $lockResult = $this->acquireLock($sourceType, $sourceId, $targetType, $targetId);

        if (!$lockResult['success']) {
            return array(
                'success' => false,
                'reason' => 'Failed to acquire lock: ' . $lockResult['reason']
            );
        }

        // PHP 5.3: no finally — release lock explicitly in both success and failure paths.
        try {
            $result = call_user_func($mutationCallback);
            $this->releaseLock($lockResult['token']);
            return array(
                'success' => true,
                'result' => $result
            );
        } catch (Exception $e) {
            $this->releaseLock($lockResult['token']);
            return array(
                'success' => false,
                'reason' => 'Mutation failed: ' . $e->getMessage()
            );
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Derive deterministic lock key.
     *
     * @param string $sourceType
     * @param int    $sourceId
     * @param string $targetType
     * @param int    $targetId
     * @return string
     */
    private function deriveLockKey($sourceType, $sourceId, $targetType, $targetId)
    {
        return sprintf(
            'lupo_edge_%s_%d_%s_%d',
            strtolower(trim((string) $sourceType)),
            (int) $sourceId,
            strtolower(trim((string) $targetType)),
            (int) $targetId
        );
    }

    /**
     * Attempt one GET_LOCK acquisition.
     *
     * @param string $lockKey
     * @return bool
     */
    private function tryAcquireLock($lockKey)
    {
        try {
            $stmt = $this->db->prepare("SELECT GET_LOCK(?, ?)");
            $stmt->execute(array($lockKey, $this->lockTimeoutSeconds));
            $result = $stmt->fetchColumn();
            return $result == 1;
        } catch (Exception $e) {
            return false;
        }
    }
}
