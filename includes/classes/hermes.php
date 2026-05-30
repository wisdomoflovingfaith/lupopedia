<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.6"
#   file_path_from_root: "includes/classes/hermes.php"
#   web_path: "https://www.lupopedia.com/lupopedia/includes/classes/hermes.php"
#   status: "active"
#   when_updated: "20260427122000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/hermes-php.toon"
#   atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/hermes-php"
#   artifact_type: "implementation"
#   artifact_kind: "library"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   prd_cluster: "00_A-i_16_B-i_16_C-i_82_A-i"
#   title: "HERMES — Message Routing & Memory Gateway"
#   summary: "PRD 82 implementation: routes messages between actors, appends transcript JSONL records, creates pending task records, preserves deterministic routing provenance."
# ---------------------------------------------------------------------

/**
 * HERMES — Message Routing & Memory Gateway
 *
 * Implements PRD 82 requirements:
 * - Routes messages between actors
 * - Appends transcript JSONL records (file-based, not SQL)
 * - Creates pending task records for valid tasks
 * - Preserves deterministic routing provenance
 * - Respects actor/channel/task assignment rules
 *
 * @package Lupopedia
 * @version 4.1.6
 */

# ---------------------------------------------------------------------
# FUNCTION LIST
# ---------------------------------------------------------------------
# Public Methods:
#   __construct($db = null)
#   route($message, $from_actor_id, $to_actor_id, $channel_id, $task_assignee_id = 0, $auth_user_id = 0, $task_scope_admin_bypass = false)
#   appendTranscript($federation_node_id, $channel_key, $prd_cluster, array $message_data)
#   createPendingTask(array $routing_decision, $message_id)
#
# Protected Helper Methods:
#   isTaskMessage($message)
#   detectMessageType($message)
#   routeToSecurityActor($from_actor_id)
#   routeToAuthorityActor($from_actor_id)
#   routeToExpertActor($message, $from_actor_id)
#   createHermesEnvelope($routing_decision, $message, $timestamp)
#   getChannelKey($channel_id)
# ---------------------------------------------------------------------

require_once __DIR__ . '/IdGenerator.php';
require_once __DIR__ . '/DatabaseFactory.php';
require_once __DIR__ . '/TimestampYmdhis.php';

class HERMES
{
    protected $db;
    protected $pdo;

    public function __construct($db = null)
    {
        if ($db === null) {
            $this->db = DatabaseFactory::getConnection();
        } else {
            $this->db = $db;
        }
        $this->pdo = $this->db->getPdo();
    }

    /**
     * Route a message through HERMES
     *
     * @param string $message Message content
     * @param int $from_actor_id Sender actor ID
     * @param int $to_actor_id Recipient actor ID
     * @param int $channel_id Channel ID
     * @param int $task_assignee_id Task assignee ID (0 if not a task)
     * @param int $auth_user_id Authenticated user ID (0 if none)
     * @param bool $task_scope_admin_bypass Admin bypass for task assignment
     * @return array Routing decision with provenance
     */
    public function route($message, $from_actor_id, $to_actor_id, $channel_id, $task_assignee_id = 0, $auth_user_id = 0, $task_scope_admin_bypass = false)
    {
        // Generate message ID using deterministic ID generator
        $message_id = IdGenerator::generate();
        
        // Get current UTC timestamp in packed format using timestamp helper
        $timestamp_ymdhis = timestamp_ymdhis::now();
        
        // Initialize routing decision
        $routing_decision = array(
            'message_id' => $message_id,
            'from_actor_id' => $from_actor_id,
            'to_actor_id' => $to_actor_id,
            'channel_id' => $channel_id,
            'task_assignee_id' => $task_assignee_id,
            'auth_user_id' => $auth_user_id,
            'message_type' => 'unknown',
            'routing_action' => 'route',
            'timestamp_ymdhis' => $timestamp_ymdhis,
            'hermes_envelope' => null
        );

        // Check if this is a task message
        if ($this->isTaskMessage($message)) {
            $routing_decision['message_type'] = 'task';
            
            // For task messages, task_assignee_id is authoritative
            if ($task_assignee_id <= 0) {
                // Invalid task assignment - route as error
                $routing_decision['routing_action'] = 'hermes:error';
                $routing_decision['to_actor_id'] = 0; // No valid recipient
            } else {
                // Valid task assignment
                $routing_decision['to_actor_id'] = $task_assignee_id;
                $routing_decision['routing_action'] = 'create_task';
                
                // Create pending task record
                $this->createPendingTask($routing_decision, $message_id);
            }
        } else {
            // Route non-task messages based on content
            $routing_decision['message_type'] = $this->detectMessageType($message);
            $routing_decision['routing_action'] = 'route';
            
            // Apply message type routing rules
            switch ($routing_decision['message_type']) {
                case 'alert':
                    // Route to security/admin actors
                    $routing_decision['to_actor_id'] = $this->routeToSecurityActor($from_actor_id);
                    break;
                case 'decision':
                    // Route to authority figure
                    $routing_decision['to_actor_id'] = $this->routeToAuthorityActor($from_actor_id);
                    break;
                case 'question':
                    // Route to appropriate expert based on content
                    $routing_decision['to_actor_id'] = $this->routeToExpertActor($message, $from_actor_id);
                    break;
                default:
                    // Preserve original routing if no special handling
                    break;
            }
        }

        // Create HERMES envelope for provenance
        $routing_decision['hermes_envelope'] = $this->createHermesEnvelope(
            $routing_decision,
            $message,
            $timestamp_ymdhis
        );

        return $routing_decision;
    }

    /**
     * Append message to transcript JSONL file (file-based, not SQL)
     *
     * @param int $federation_node_id Federation node ID
     * @param string $channel_key Channel key
     * @param string $prd_cluster PRD cluster identifier
     * @param array $message_data Message data to append
     * @return bool Success status
     */
    public function appendTranscript($federation_node_id, $channel_key, $prd_cluster, array $message_data)
    {
        // Build transcript file path exactly as specified in PRD 82
        $transcript_dir = "memory/transcripts/{$federation_node_id}/{$channel_key}";
        $transcript_file = "{$transcript_dir}/{$prd_cluster}.jsonl";
        
        // Ensure directory exists
        if (!is_dir($transcript_dir)) {
            if (!mkdir($transcript_dir, 0755, true)) {
                error_log("HERMES: Failed to create transcript directory: {$transcript_dir}");
                return false;
            }
        }
        
        // Prepare JSONL record with timestamp using timestamp helper
        $record = array_merge($message_data, array(
            'timestamp_ymdhis' => timestamp_ymdhis::now(),
            'federation_node_id' => $federation_node_id,
            'channel_key' => $channel_key,
            'prd_cluster' => $prd_cluster
        ));
        
        // Convert to JSON line (one JSON object per line, no array wrapper)
        $json_line = json_encode($record) . "\n";
        
        // Append to file with exclusive lock, UTF-8, LF endings
        $bytes_written = file_put_contents(
            $transcript_file,
            $json_line,
            FILE_APPEND | LOCK_EX
        );
        
        if ($bytes_written === false) {
            error_log("HERMES: Failed to write to transcript file: {$transcript_file}");
            return false;
        }
        
        return true;
    }

    /**
     * Create a pending task record
     *
     * @param array $routing_decision Routing decision data
     * @param string $message_id Message ID
     * @return string|false Task ID or false on failure
     */
    public function createPendingTask(array $routing_decision, $message_id)
    {
        $task_id = IdGenerator::generate();
        $timestamp = timestamp_ymdhis::now();
        
        try {
            $sql = "INSERT INTO lupo_dialog_pending_tasks (
                dialog_pending_task,
                message_id,
                channel_id,
                assignee_actor_id,
                creator_actor_id,
                task_body,
                status,
                priority,
                created_ymdhis,
                updated_ymdhis,
                task_type,
                payload
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            
            $payload = json_encode(array(
                'routing_decision' => $routing_decision,
                'hermes_envelope' => $routing_decision['hermes_envelope'] ?? null
            ));
            
            $result = $stmt->execute(array(
                $task_id,
                $message_id,
                $routing_decision['channel_id'],
                $routing_decision['task_assignee_id'],
                $routing_decision['from_actor_id'],
                '', // task_body - will be filled by task processing
                'pending',
                1, // default priority
                $timestamp,
                $timestamp,
                'hermes_routed',
                $payload
            ));
            
            if (!$result) {
                error_log("HERMES: Failed to create pending task: " . implode(', ', $stmt->errorInfo()));
                return false;
            }
            
            return $task_id;
            
        } catch (Exception $e) {
            error_log("HERMES: Exception creating pending task: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if message is a task message
     *
     * @param string $message Message content
     * @return bool True if task message
     */
    protected function isTaskMessage($message)
    {
        // Task messages start with [task]
        return strpos(trim($message), '[task]') === 0;
    }

    /**
     * Detect message type from content
     *
     * @param string $message Message content
     * @return string Message type
     */
    protected function detectMessageType($message)
    {
        $trimmed = trim($message);
        
        if (strpos($trimmed, '[alert]') === 0) {
            return 'alert';
        }
        if (strpos($trimmed, '[decision]') === 0) {
            return 'decision';
        }
        if (strpos($trimmed, '[question]') === 0) {
            return 'question';
        }
        
        return 'message';
    }

    /**
     * Route to security actor for alerts
     *
     * @param int $from_actor_id Sender actor ID
     * @return int Target security actor ID
     */
    protected function routeToSecurityActor($from_actor_id)
    {
        // TODO: Implement proper security actor routing
        // For now, return a placeholder
        return 102; // Placeholder security actor
    }

    /**
     * Route to authority actor for decisions
     *
     * @param int $from_actor_id Sender actor ID
     * @return int Target authority actor ID
     */
    protected function routeToAuthorityActor($from_actor_id)
    {
        // TODO: Implement proper authority actor routing
        // For now, return a placeholder
        return 1; // Captain WOLFIE
    }

    /**
     * Route to expert actor based on content
     *
     * @param string $message Message content
     * @param int $from_actor_id Sender actor ID
     * @return int Target expert actor ID
     */
    protected function routeToExpertActor($message, $from_actor_id)
    {
        // TODO: Implement content-based expert routing
        // For now, return a placeholder
        return 22; // Placeholder expert actor
    }

    /**
     * Create HERMES envelope for provenance
     *
     * @param array $routing_decision Routing decision
     * @param string $message Message content
     * @param int $timestamp UTC timestamp in YYYYMMDDHHIISS format
     * @return array HERMES envelope
     */
    protected function createHermesEnvelope($routing_decision, $message, $timestamp)
    {
        return array(
            'from_actor' => $routing_decision['from_actor_id'],
            'to_actor' => $routing_decision['to_actor_id'],
            'channel_key' => $this->getChannelKey($routing_decision['channel_id']),
            'federation_node' => 0, // Default to local node
            'auth_user' => $routing_decision['auth_user_id'],
            'timestamp_utc' => $timestamp,
            'message_type' => $routing_decision['message_type']
            // Note: kapakai and pono must be provided by caller
            // No fake defaults or invented meaning per PRD 82
        );
    }


    /**
     * Get channel key from channel ID
     *
     * @param int $channel_id Channel ID
     * @return string Channel key
     */
    protected function getChannelKey($channel_id)
    {
        // TODO: Implement proper channel key lookup
        // For now, return a default
        return 'development';
    }
}

?>
