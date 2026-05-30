<?php
/**
 * ANUBIS Queue Processor
 *
 * Handles queue management for orphaned files awaiting header processing.
 *
 * **file_path contract (queue row):** Store **repository-relative** paths using **forward slashes**
 * (e.g. `lupo-docs/prd/00_root_constitutional_system_requirements.md`). This is the portable
 * identity across machines — it does **not** include the HTTP public folder name (which is
 * arbitrary per install) and is **not** required to be an absolute filesystem path in the DB.
 * Callers may pass an absolute path under **LUPOPEDIA_PATH** (e.g. CLI watcher); **addToQueue**
 * normalizes to repo-relative when the file lies inside the project root. **resolveFilesystemPath()**
 * joins **LUPOPEDIA_PATH** + stored path for **file_exists** / **file_get_contents** / writes.
 * Legacy rows that store absolute paths still resolve when possible.
 *
 * @package Lupopedia\ANUBIS
 * @version 4.0.53
 */

if (!class_exists('timestamp_ymdhis', false)) {
    require_once defined('LUPOPEDIA_PATH')
        ? LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php'
        : dirname(__DIR__) . '/TimestampYmdhis.php';
}

class ANUBIS_QueueProcessor
{
    private $db;
    private $actor_id = 19;
    private $queue_table;
    private $log_table;
    private $recovery_table;
    private $quarantine_table;

    public function __construct($db)
    {
        $this->db = $db;
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->queue_table = $prefix . 'anubis_queue';
        $this->log_table = $prefix . 'anubis_processing_log';
        $this->recovery_table = $prefix . 'anubis_recovery_attempts';
        $this->quarantine_table = $prefix . 'anubis_quarantine';
    }

    /**
     * Normalize to repo-relative path for DB storage (forward slashes, no leading slash).
     *
     * @param string $file_path Absolute under LUPOPEDIA_PATH, or already repo-relative
     * @return string
     */
    private function normalizePathForStorage($file_path)
    {
        if ($file_path === null || $file_path === '') {
            return '';
        }
        $norm = str_replace('\\', '/', trim($file_path));
        if ($norm === '') {
            return '';
        }
        if (!defined('LUPOPEDIA_PATH')) {
            return ltrim($norm, '/');
        }
        $rootReal = realpath(LUPOPEDIA_PATH);
        if ($rootReal === false) {
            return ltrim($norm, '/');
        }
        $rootSlash = str_replace('\\', '/', $rootReal);
        $prefix = $rootSlash . '/';

        // Input already repo-relative: resolve via project root if file exists
        $relTry = ltrim($norm, '/');
        $candidate = $rootReal . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relTry);
        $candReal = @realpath($candidate);
        if ($candReal !== false && is_file($candReal)) {
            $candNorm = str_replace('\\', '/', $candReal);
            if (strpos($candNorm . '/', $prefix) === 0) {
                return substr($candNorm, strlen($prefix));
            }
        }

        // Absolute path on disk (e.g. watcher getPathname)
        $absReal = @realpath($file_path);
        if ($absReal !== false) {
            $absNorm = str_replace('\\', '/', $absReal);
            if (strpos($absNorm . '/', $prefix) === 0) {
                return substr($absNorm, strlen($prefix));
            }
        }

        // New or missing file: keep logical repo-relative form
        return $relTry;
    }

    /**
     * Map stored queue path to an absolute filesystem path for I/O.
     *
     * @param string $stored_path Value from DB (repo-relative or legacy absolute)
     * @return string
     */
    private function resolveFilesystemPath($stored_path)
    {
        if ($stored_path === null || $stored_path === '') {
            return '';
        }
        $stored = str_replace('\\', '/', $stored_path);
        $stored = trim($stored);
        if ($stored === '') {
            return '';
        }
        // Legacy: Unix absolute
        if (isset($stored[0]) && $stored[0] === '/') {
            return $stored;
        }
        // Legacy: Windows absolute
        if (strlen($stored) > 2 && ctype_alpha($stored[0]) && $stored[1] === ':' && ($stored[2] === '/' || $stored[2] === '\\')) {
            return str_replace('\\', '/', $stored_path);
        }
        if (!defined('LUPOPEDIA_PATH')) {
            return $stored_path;
        }
        $base = rtrim(str_replace('\\', '/', LUPOPEDIA_PATH), '/');
        return $base . '/' . ltrim($stored, '/');
    }

    /**
     * Add a file to the processing queue
     * 
     * @param string $file_path Repo-relative path and/or absolute path under LUPOPEDIA_PATH
     * @param string $detection_method How it was detected
     * @param int $priority 1-10 (lower = higher priority)
     * @param string|null $header_snapshot Partial header if any
     * @return int|false Queue ID or false on failure
     */
    public function addToQueue($file_path, $detection_method, $priority = 5, $header_snapshot = null, $file_content = null)
    {
        $stored_path = $this->normalizePathForStorage($file_path);
        if ($stored_path === '') {
            return false;
        }

        // Check if already in queue (canonical stored form)
        $existing = $this->db->fetchRow(
            "SELECT queue_id FROM {$this->queue_table} 
             WHERE file_path = :path AND status IN ('pending', 'processing')",
            array(':path' => $stored_path)
        );

        if ($existing) {
            return $existing['queue_id']; // Already queued
        }

        $resolved = $this->resolveFilesystemPath($stored_path);
        $on_disk = ($resolved !== '') && file_exists($resolved);

        // Calculate file hash if possible
        $file_hash = '';
        if ($on_disk) {
            $file_hash = hash_file('sha256', $resolved);
        }

        $now = gmdate('YmdHis');

        $data = array(
            'file_path' => $stored_path,
            'file_hash' => $file_hash,
            'file_content' => $file_content,
            'filesystem_copy_exists' => $on_disk ? 1 : 0,
            'detected_utc' => $now,
            'priority' => $priority,
            'status' => 'pending',
            'detection_method' => $detection_method,
            'header_snapshot' => $header_snapshot,
            'created_utc' => $now,
            'updated_utc' => $now
        );

        $result = $this->db->insert($this->queue_table, $data);

        if ($result) {
            $queue_id = $this->db->lastInsertId();
            $this->log($queue_id, $stored_path, 'queued', array('detection_method' => $detection_method));
            return $queue_id;
        }

        return false;
    }

    /**
     * Process next batch from queue
     * 
     * @param int $batch_size Number of items to process
     * @return array Results
     */
    public function processQueue($batch_size = 10)
    {
        // Get next pending items, highest priority first
        $items = $this->db->fetchAll(
            "SELECT * FROM {$this->queue_table} 
             WHERE status = 'pending' 
             AND is_deleted = 0
             ORDER BY priority ASC, detected_utc ASC 
             LIMIT :limit",
            array('limit' => (int) $batch_size)
        );

        $results = array();
        foreach ($items as $item) {
            $results[$item['queue_id']] = $this->processQueueItem($item);
        }

        return $results;
    }

    /**
     * Process a single queue item
     * 
     * @param array $item Queue item data
     * @return string Status after processing
     */
    private function processQueueItem($item)
    {
        // Mark as processing
        $this->updateQueueStatus($item['queue_id'], 'processing');

        try {
            // Determine content source
            $content = null;
            $resolved = $this->resolveFilesystemPath($item['file_path']);
            if ($item['filesystem_copy_exists'] && $resolved !== '' && file_exists($resolved)) {
                $content = file_get_contents($resolved);
            } elseif (!empty($item['file_content'])) {
                $content = $item['file_content'];
            }

            if ($content === null) {
                $this->updateQueueStatus($item['queue_id'], 'failed', array('error' => 'no_content_available'));
                return 'failed';
            }

            // Attempt recovery with content
            $recovery_result = $this->attemptRecovery($item, $content);

            if ($recovery_result['success']) {
                // Successfully recovered
                $this->updateQueueStatus(
                    $item['queue_id'],
                    'recovered'
                );
                $this->log($item['queue_id'], $item['file_path'], 'recovered', $recovery_result);
                return 'recovered';
            }

            // Check if we've exceeded max attempts
            if ($item['attempts'] >= 3) {
                // Move to quarantine
                $quarantine_result = $this->moveToQuarantine($item);
                $this->updateQueueStatus($item['queue_id'], 'quarantined');
                $this->log(
                    $item['queue_id'],
                    $item['file_path'],
                    'quarantined',
                    array('reason' => 'max_attempts_exceeded', 'attempts' => $item['attempts'], 'quarantine_path' => $quarantine_result)
                );
                return 'quarantined';
            }

            // Increment attempts and leave as pending for retry
            $this->incrementAttempts($item['queue_id']);
            $this->log(
                $item['queue_id'],
                $item['file_path'],
                'retry_pending',
                array('attempt' => $item['attempts'] + 1)
            );
            return 'retry_pending';

        } catch (Exception $e) {
            // Log error and failed
            $this->log(
                $item['queue_id'],
                $item['file_path'],
                'error',
                array('error' => $e->getMessage())
            );
            $this->updateQueueStatus($item['queue_id'], 'failed', array('error' => $e->getMessage()));
            return 'failed';
        }
    }

    /**
     * Attempt to recover a file by generating headers
     * 
     * @param array $item Queue item
     * @return array Recovery result
     */
    private function attemptRecovery($item, $file_content)
    {
        $result = array(
            'success' => false,
            'strategy' => 'unknown',
            'file_path' => $item['file_path']
        );

        // Strategy 1: Try to infer from file path and content
        $content = $file_content;

        // Try to generate minimal header
        $header = $this->generateMinimalHeader($item['file_path'], $content);
        if ($header) {
            // Prepend header to file
            $new_content = "---\n" . $header . "\n---\n\n" . $content;
            $write_path = $this->resolveFilesystemPath($item['file_path']);
            if ($write_path !== '') {
                file_put_contents($write_path, $new_content);
            }

            $result['success'] = true;
            $result['strategy'] = 'minimal_header_generation';
            $result['header'] = $header;

            // Log recovery attempt
            $this->logRecoveryAttempt($item['queue_id'], (int) $item['attempts'] + 1, 'minimal_header_generation', true, $header);

            return $result;
        }

        // Log failed attempt
        $this->logRecoveryAttempt(
            $item['queue_id'],
            (int) $item['attempts'] + 1,
            'all_strategies',
            false,
            null,
            'Could not generate valid header'
        );

        return $result;
    }

    /**
     * Generate a minimal header for a file
     * 
     * @param string $file_path
     * @param string $content
     * @return string|null YAML header or null
     */
    private function generateMinimalHeader($file_path, $content)
    {
        // Try to determine channel from path
        $channel_id = 1; // Default
        if (preg_match('#channels/(\d+)/#', $file_path, $matches)) {
            $channel_id = (int) $matches[1];
        }

        // Try to infer actor from content (look for @mentions, etc.)
        $actor_id = 19; // Default to ANUBIS for recovered files

        // Determine purpose from first line
        $first_line = strtok($content, "\n");
        $purpose = substr((string) $first_line, 0, 100);

        // Generate minimal header
        $header = "flare.headers:\n";
        $header .= "  flare.version: \"1.0\"\n";
        $header .= "  flare.schema: \"documentation\"\n";
        $header .= "  file_path_from_root: \"$file_path\"\n";
        $header .= "  system_version: \"4.0.53\"\n";
        $header .= "  last_modified_utc: \"" . gmdate('YmdHis') . "\"\n";
        $header .= "  channel_id: $channel_id\n";
        $header .= "  actor_id: $actor_id\n";
        $header .= "  delegation_chain: \"19:10000\"\n";
        $header .= "  artifact_type: \"recovered\"\n";
        $header .= "  purpose: \"$purpose\"\n";
        $header .= "  lupo_agent: \"anubis\"\n";

        return $header;
    }

    /**
     * Move a file to quarantine
     * 
     * @param array $item Queue item
     * @return string|bool Quarantine path or false
     */
    private function moveToQuarantine($item, $file_content = null)
    {
        $quarantine_dir = LUPOPEDIA_PATH . '/storage/quarantine/' . gmdate('Y/m/d');
        if (!is_dir($quarantine_dir)) {
            mkdir($quarantine_dir, 0755, true);
        }

        $filename = basename(str_replace('\\', '/', $item['file_path']));
        $quarantine_path = $quarantine_dir . '/' . $item['queue_id'] . '_' . $filename;

        // Move file to quarantine if it exists
        $source = $this->resolveFilesystemPath($item['file_path']);
        if ($source !== '' && file_exists($source)) {
            copy($source, $quarantine_path);
            // We keep the original too or move it? Let's copy for now.
        }

        // Record in quarantine table
        $now = gmdate('YmdHis');
        $expires = (string) timestamp_ymdhis::addSeconds((int) $now, 30 * 86400);

        $this->db->insert(
            $this->quarantine_table,
            array(
                'queue_id' => $item['queue_id'],
                'file_path' => $item['file_path'],
                'file_hash' => $item['file_hash'],
                'file_content' => $file_content ? $file_content : $item['file_content'],
                'quarantine_path' => $quarantine_path,
                'reason' => 'Failed recovery after ' . $item['attempts'] . ' attempts',
                'quarantined_utc' => $now,
                'expires_utc' => $expires,
                'is_deleted' => 0
            )
        );

        return $quarantine_path;
    }

    /**
     * Update queue item status
     */
    private function updateQueueStatus($queue_id, $status, $extra = array())
    {
        $now = gmdate('YmdHis');
        $data = array('status' => $status, 'updated_utc' => $now);

        if ($status === 'processing') {
            $data['assigned_to_actor_id'] = $this->actor_id;
            $data['last_attempt_utc'] = $now;
        }

        if (isset($extra['error'])) {
            $data['error_message'] = $extra['error'];
        }

        return $this->db->update($this->queue_table, $data, array('queue_id' => $queue_id));
    }

    /**
     * Increment attempt counter
     */
    private function incrementAttempts($queue_id)
    {
        $now = gmdate('YmdHis');
        $this->db->query(
            "UPDATE {$this->queue_table} 
             SET attempts = attempts + 1, last_attempt_utc = :last_attempt, updated_utc = :updated
             WHERE queue_id = :queue_id",
            array(
                'queue_id' => $queue_id,
                'last_attempt' => $now,
                'updated' => $now
            )
        );
    }

    /**
     * Log processing activity
     */
    private function log($queue_id, $file_path, $action, $details = array())
    {
        $this->db->insert($this->log_table, array(
            'queue_id' => $queue_id,
            'file_path' => $file_path,
            'action' => $action,
            'details' => json_encode($details),
            'actor_id' => $this->actor_id,
            'created_utc' => gmdate('YmdHis')
        ));
    }

    /**
     * Log recovery attempt
     */
    private function logRecoveryAttempt($queue_id, $attempt_number, $strategy, $success, $header = null, $error = null)
    {
        $this->db->insert($this->recovery_table, array(
            'queue_id' => $queue_id,
            'attempt_number' => $attempt_number,
            'attempt_utc' => gmdate('YmdHis'),
            'strategy' => $strategy,
            'success' => $success ? 1 : 0,
            'generated_header' => $header,
            'error_details' => $error ? json_encode(array('message' => $error)) : null
        ));
    }

    /**
     * Get queue statistics
     */
    public function getQueueStats()
    {
        return $this->db->fetchAll(
            "SELECT 
                status, 
                COUNT(*) as count,
                MIN(priority) as min_priority,
                MAX(priority) as max_priority,
                SUM(filesystem_copy_exists) as filesystem_copies
             FROM {$this->queue_table}
             WHERE is_deleted = 0
             GROUP BY status"
        );
    }
}
?>
