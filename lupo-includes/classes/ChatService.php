<?php
/**
 * ChatService – Manage active chats and operator chat interactions
 * 
 * Responsible for:
 * - Getting active chats for an operator
 * - Getting incoming chat invitations for an operator
 * - Accepting/declining chat invitations
 * - Ending chats and generating transcripts
 * - Chat state transitions
 */

class ChatService
{
    private $db;
    private $table_prefix;
    
    const CHAT_STATUS_WAITING = 'waiting_for_acceptance';
    const CHAT_STATUS_ACTIVE = 'active';
    const CHAT_STATUS_CLOSED = 'closed';
    
    /**
     * Constructor
     * 
     * @param PDO_DB $db Database connection
     * @param string $table_prefix Table prefix (default: 'lupo_')
     */
    public function __construct($db, $table_prefix = 'lupo_')
    {
        $this->db = $db;
        $this->table_prefix = $table_prefix;
    }
    
    /**
     * Get active chats for an operator
     * 
     * Returns all chats in 'active' state assigned to this operator.
     * 
     * @param int $operator_actor_id
     * @return array Array of active chat objects with visitor info
     */
    public function getActiveChatsForOperator($operator_actor_id)
    {
        $chats = $this->db->fetchAll(
            "SELECT c.collection_id, 
                    c.metadata,
                    c.created_ymdhis,
                    c.channel_id
             FROM {$this->table_prefix}collections c
             WHERE c.collection_type = 'live_help_chat'
             AND JSON_EXTRACT(c.metadata, '$.operator_actor_id') = :op_id
             AND JSON_EXTRACT(c.metadata, '$.status') = :status
             AND c.is_deleted = 0
             ORDER BY c.created_ymdhis DESC",
            [
                'op_id' => $operator_actor_id,
                'status' => self::CHAT_STATUS_ACTIVE,
            ]
        );
        
        return $this->_enrichChatObjects($chats);
    }
    
    /**
     * Get incoming chat invitations for an operator
     * 
     * Returns all chats in 'waiting_for_acceptance' state assigned to this operator.
     * These are chat invitations the operator hasn't accepted or declined yet.
     * 
     * @param int $operator_actor_id
     * @return array Array of pending invitation objects with visitor info
     */
    public function getIncomingInvitationsForOperator($operator_actor_id)
    {
        $chats = $this->db->fetchAll(
            "SELECT c.collection_id, 
                    c.metadata,
                    c.created_ymdhis,
                    c.channel_id
             FROM {$this->table_prefix}collections c
             WHERE c.collection_type = 'live_help_chat'
             AND JSON_EXTRACT(c.metadata, '$.operator_actor_id') = :op_id
             AND JSON_EXTRACT(c.metadata, '$.status') = :status
             AND c.is_deleted = 0
             ORDER BY c.created_ymdhis ASC",
            [
                'op_id' => $operator_actor_id,
                'status' => self::CHAT_STATUS_WAITING,
            ]
        );
        
        return $this->_enrichChatObjects($chats);
    }
    
    /**
     * Accept a chat invitation
     * 
     * Operator accepts the incoming chat invitation. Transitions status from
     * 'waiting_for_acceptance' to 'active'.
     * 
     * @param int $operator_actor_id
     * @param int $chat_collection_id
     * @return bool True on success
     */
    public function acceptChat($operator_actor_id, $chat_collection_id)
    {
        $now = gmdate('YmdHis');
        
        // Get current chat metadata
        $chat = $this->db->fetchRow(
            "SELECT metadata FROM {$this->table_prefix}collections 
             WHERE collection_id = :id 
             AND is_deleted = 0",
            ['id' => $chat_collection_id]
        );
        
        if (!$chat) {
            return false;
        }
        
        $metadata = json_decode($chat['metadata'], true) ?: [];
        
        // Verify operator matches
        if ($metadata['operator_actor_id'] !== $operator_actor_id) {
            return false;
        }
        
        // Update chat status and metadata
        $metadata['status'] = self::CHAT_STATUS_ACTIVE;
        $metadata['operator_joined_ymdhis'] = $now;
        
        $result = $this->db->update(
            $this->table_prefix . 'collections',
            [
                'metadata' => json_encode($metadata),
                'updated_ymdhis' => $now,
            ],
            ['collection_id' => $chat_collection_id]
        );
        
        return (bool) $result;
    }
    
    /**
     * Decline a chat invitation
     * 
     * Operator declines the chat. Triggers re-routing to next available operator.
     * 
     * @param int $operator_actor_id
     * @param int $chat_collection_id
     * @param string $reason_text Optional reason for decline
     * @return bool True on success
     */
    public function declineChat($operator_actor_id, $chat_collection_id, $reason_text = '')
    {
        $now = gmdate('YmdHis');
        
        // Get current chat
        $chat = $this->db->fetchRow(
            "SELECT metadata, channel_id FROM {$this->table_prefix}collections 
             WHERE collection_id = :id 
             AND is_deleted = 0",
            ['id' => $chat_collection_id]
        );
        
        if (!$chat) {
            return false;
        }
        
        $metadata = json_decode($chat['metadata'], true) ?: [];
        
        // Verify operator matches
        if ($metadata['operator_actor_id'] !== $operator_actor_id) {
            return false;
        }
        
        // Mark as declined, store reason
        $metadata['status'] = 'declined';
        $metadata['declined_by_actor_id'] = $operator_actor_id;
        $metadata['declined_ymdhis'] = $now;
        $metadata['decline_reason'] = $reason_text;
        
        $result = $this->db->update(
            $this->table_prefix . 'collections',
            [
                'metadata' => json_encode($metadata),
                'updated_ymdhis' => $now,
            ],
            ['collection_id' => $chat_collection_id]
        );
        
        // Trigger re-routing in ChatRoutingService
        if ($result) {
            $routing_service = new ChatRoutingService(
                $this->db,
                $chat['channel_id'],
                $this->table_prefix
            );
            $routing_service->declineChat($chat_collection_id);
        }
        
        return (bool) $result;
    }
    
    /**
     * End a chat (operator ends the conversation)
     * 
     * Marks chat as closed, captures end time, generates transcript.
     * 
     * @param int $chat_collection_id
     * @param string $reason Optional end reason (operator_ended, visitor_left, timeout)
     * @return bool True on success
     */
    public function endChat($chat_collection_id, $reason = 'operator_ended')
    {
        $now = gmdate('YmdHis');
        
        // Get chat metadata
        $chat = $this->db->fetchRow(
            "SELECT metadata FROM {$this->table_prefix}collections 
             WHERE collection_id = :id 
             AND is_deleted = 0",
            ['id' => $chat_collection_id]
        );
        
        if (!$chat) {
            return false;
        }
        
        $metadata = json_decode($chat['metadata'], true) ?: [];
        
        // Calculate chat duration
        $start_time = $metadata['operator_joined_ymdhis'] ?? $metadata['initiated_ymdhis'] ?? $now;
        $duration_seconds = $this->_calculateDurationSeconds($start_time, $now);
        
        // Update metadata
        $metadata['status'] = self::CHAT_STATUS_CLOSED;
        $metadata['chat_end_ymdhis'] = $now;
        $metadata['end_reason'] = $reason;
        $metadata['duration_seconds'] = $duration_seconds;
        
        $result = $this->db->update(
            $this->table_prefix . 'collections',
            [
                'metadata' => json_encode($metadata),
                'updated_ymdhis' => $now,
            ],
            ['collection_id' => $chat_collection_id]
        );
        
        // Generate transcript
        if ($result) {
            $this->_generateTranscript($chat_collection_id, $metadata);
        }
        
        return (bool) $result;
    }
    
    /**
     * Get chat transcript as array of messages
     * 
     * @param int $chat_collection_id
     * @return array Array of messages in chronological order
     */
    public function getChatTranscript($chat_collection_id)
    {
        $messages = $this->db->fetchAll(
            "SELECT actor_id, message_body, created_ymdhis, message_type
             FROM {$this->table_prefix}channel_messages
             WHERE thread_id = :thread_id
             AND is_deleted = 0
             ORDER BY created_ymdhis ASC",
            ['thread_id' => $chat_collection_id]
        );
        
        return $messages ?: [];
    }
    
    /**
     * Check if operator is at max concurrent chat capacity
     * 
     * @param int $operator_actor_id
     * @param int $channel_id
     * @return bool True if at capacity
     */
    public function isOperatorAtCapacity($operator_actor_id, $channel_id)
    {
        $active_chats = count($this->getActiveChatsForOperator($operator_actor_id));
        
        $config = $this->db->fetchRow(
            "SELECT config_value FROM {$this->table_prefix}system_config 
             WHERE config_key = :key 
             AND is_deleted = 0",
            ['key' => 'chat.settings.channel_' . $channel_id . '.max_concurrent_chats']
        );
        
        $max_chats = $config ? (int) $config['config_value'] : 10;
        
        return $active_chats >= $max_chats;
    }
    
    // ===== PRIVATE HELPERS =====
    
    /**
     * Enrich chat objects with visitor actor data
     * 
     * @param array $chats Raw chat records from DB
     * @return array Enriched chat objects
     */
    private function _enrichChatObjects($chats)
    {
        $enriched = [];
        
        foreach ($chats as $chat) {
            $metadata = json_decode($chat['metadata'], true) ?: [];
            
            // Get visitor actor info
            $visitor = $this->db->fetchRow(
                "SELECT actor_id, name, actor_name 
                 FROM {$this->table_prefix}actors 
                 WHERE actor_id = :id",
                ['id' => $metadata['visitor_actor_id'] ?? null]
            );
            
            $enriched[] = [
                'chat_collection_id' => $chat['collection_id'],
                'channel_id' => $chat['channel_id'],
                'status' => $metadata['status'] ?? 'unknown',
                'visitor_actor_id' => $metadata['visitor_actor_id'] ?? null,
                'visitor_name' => $visitor['name'] ?? 'Guest Visitor',
                'initiated_ymdhis' => $metadata['initiated_ymdhis'] ?? null,
                'duration_seconds' => $metadata['duration_seconds'] ?? null,
                'full_metadata' => $metadata,
            ];
        }
        
        return $enriched;
    }
    
    /**
     * Calculate chat duration in seconds
     * 
     * @param string $start_ymdhis Start time (YYYYMMDDHHIISS)
     * @param string $end_ymdhis End time (YYYYMMDDHHIISS)
     * @return int Duration in seconds
     */
    private function _calculateDurationSeconds($start_ymdhis, $end_ymdhis)
    {
        $start_ts = strtotime($start_ymdhis);
        $end_ts = strtotime($end_ymdhis);
        
        return max(0, $end_ts - $start_ts);
    }
    
    /**
     * Generate and store transcript for chat
     * 
     * Creates a record for the transcript (optionally emails it to visitor).
     * 
     * @param int $chat_collection_id
     * @param array $chat_metadata Chat metadata including visitor/operator info
     */
    private function _generateTranscript($chat_collection_id, $chat_metadata)
    {
        $transcript_id = $this->_allocateTranscriptId();
        $now = gmdate('YmdHis');
        
        // Get all messages in this chat
        $messages = $this->getChatTranscript($chat_collection_id);
        
        // Build transcript HTML
        $transcript_html = $this->_buildTranscriptHTML($messages, $chat_metadata);
        
        // Store transcript
        $this->db->insert(
            $this->table_prefix . 'chat_transcripts',
            [
                'transcript_id' => $transcript_id,
                'chat_collection_id' => $chat_collection_id,
                'visitor_actor_id' => $chat_metadata['visitor_actor_id'] ?? null,
                'operator_actor_id' => $chat_metadata['operator_actor_id'] ?? null,
                'channel_id' => $this->db->fetchRow(
                    "SELECT channel_id FROM {$this->table_prefix}collections 
                     WHERE collection_id = :id",
                    ['id' => $chat_collection_id]
                )['channel_id'] ?? null,
                'transcript_body' => $transcript_html,
                'duration_seconds' => $chat_metadata['duration_seconds'] ?? 0,
                'created_ymdhis' => $now,
                'is_deleted' => 0,
            ]
        );
    }
    
    /**
     * Build transcript HTML from messages
     * 
     * @param array $messages Array of message objects
     * @param array $metadata Chat metadata
     * @return string HTML transcript
     */
    private function _buildTranscriptHTML($messages, $metadata)
    {
        $html = '<div class="chat-transcript">';
        $html .= '<header class="transcript-header">';
        $html .= '<h3>Chat Transcript</h3>';
        $html .= '<p>Started: ' . ($metadata['initiated_ymdhis'] ?? 'N/A') . '</p>';
        $html .= '<p>Ended: ' . ($metadata['chat_end_ymdhis'] ?? 'N/A') . '</p>';
        $html .= '<p>Duration: ' . ($metadata['duration_seconds'] ?? 0) . ' seconds</p>';
        $html .= '</header>';
        
        foreach ($messages as $msg) {
            $html .= '<div class="message message-' . htmlspecialchars($msg['message_type']) . '">';
            $html .= '<span class="timestamp">[' . $msg['created_ymdhis'] . ']</span> ';
            $html .= '<span class="actor">Actor ' . $msg['actor_id'] . ':</span> ';
            $html .= '<span class="text">' . nl2br(htmlspecialchars($msg['message_body'])) . '</span>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Allocate transcript ID
     * 
     * @return int
     */
    private function _allocateTranscriptId()
    {
        if (class_exists('DeterministicIdService')) {
            $service = new DeterministicIdService($this->db, $this->table_prefix);
            return $service->allocateId('chat_transcripts');
        }
        
        $last = $this->db->fetchRow(
            "SELECT MAX(transcript_id) as max_id FROM {$this->table_prefix}chat_transcripts"
        );
        
        return ($last && $last['max_id']) ? (int) $last['max_id'] + 1 : 1001;
    }
}
