<?php
/**
 * Message Builder for Big Rock 2: Dialog Channel Migration
 * 
 * Creates message entries in MySQL from parsed dialog messages.
 * Handles message validation, text truncation, and database insertion.
 * 
 * @package Lupopedia
 * @subpackage DialogChannelMigration
 * @version 4.0.102
 * @author Captain Wolfie
 */

class MessageBuilder {
    
    private $db;
    private $errors = [];
    private $warnings = [];
    
    /**
     * Constructor
     * 
     * @param PDO $database Database connection
     */
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create messages for a channel
     * 
     * @param int $channelId Channel ID
     * @param array $messages Array of parsed messages
     * @return int Number of messages created
     */
    public function createMessages($channelId, $messages) {
        $createdCount = 0;
        
        foreach ($messages as $message) {
            if ($this->createMessage($channelId, $message)) {
                $createdCount++;
            }
        }
        
        return $createdCount;
    }
    
    /**
     * Create a single message
     * 
     * @param int $channelId Channel ID
     * @param array $messageData Message data from parser
     * @return int|false Message ID on success, false on failure
     */
    public function createMessage($channelId, $messageData) {
        try {
            // Validate message data
            $validationErrors = $this->validateMessageData($messageData);
            if (!empty($validationErrors)) {
                $this->errors = array_merge($this->errors, $validationErrors);
                return false;
            }
            
            // Prepare message data
            $data = $this->prepareMessageData($channelId, $messageData);
            
            // Insert message
            $sql = "INSERT INTO lupo_dialog_doctrine (
                channel_id, dialog_thread_id, from_actor_id, to_actor_id, message_text, 
                mood_rgb, message_type, metadata_json, 
                created_ymdhis, updated_ymdhis, weight
            ) VALUES (
                :channel_id, :dialog_thread_id, :from_actor_id, :to_actor_id, :message_text,
                :mood_rgb, :message_type, :metadata_json,
                :created_ymdhis, :updated_ymdhis, :weight
            )";
            
            // Check protocol completion before insert (replaces tr_enforce_protocol_completion trigger)
            require_once __DIR__ . '/../../app/Services/TriggerReplacements/EnforceProtocolCompletionService.php';
            $protocolService = new EnforceProtocolCompletionService($this->db);
            
            // Get speaker actor_id from speaker name (assuming speaker is actor slug or name)
            // Note: This may need adjustment based on actual schema
            $speakerId = $this->getActorIdFromSpeaker($messageData['speaker'] ?? null);
            if ($speakerId) {
                try {
                    $protocolService->executeBeforeInsert($speakerId, $channelId);
                } catch (Exception $e) {
                    $this->errors[] = "Protocol violation: " . $e->getMessage();
                    return false;
                }
            }
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($data);
            
            if ($result) {
                $messageId = $this->db->lastInsertId();
                
                // Update channel message count after insert (replaces tr_dialog_messages_insert trigger)
                require_once __DIR__ . '/../../app/Services/TriggerReplacements/DialogMessagesInsertService.php';
                $insertService = new DialogMessagesInsertService($this->db);
                $insertService->executeAfterInsert($channelId);
                
                return $messageId;
            } else {
                $this->errors[] = "Failed to insert message: " . implode(', ', $stmt->errorInfo());
                return false;
            }
            
        } catch (Exception $e) {
            $this->errors[] = "Message creation error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Validate message data
     * 
     * @param array $messageData Message data to validate
     * @return array Array of validation errors
     */
    private function validateMessageData($messageData) {
        $errors = [];
        
        if (empty($messageData['speaker'])) {
            $errors[] = "Missing speaker";
        }
        
        if (empty($messageData['message_text'])) {
            $errors[] = "Missing message text";
        }
        
        if (strlen($messageData['message_text']) > 1000) {
            // Truncate and warn instead of failing
            $this->warnings[] = "Message text truncated from " . strlen($messageData['message_text']) . " to 1000 characters";
        }
        
        if (!empty($messageData['mood_rgb']) && !preg_match('/^#?[0-9A-Fa-f]{6}$/', $messageData['mood_rgb'])) {
            $errors[] = "Invalid mood_rgb format: " . $messageData['mood_rgb'];
        }
        
        return $errors;
    }
    
    /**
     * Prepare message data for database insertion
     * 
     * @param int $channelId Channel ID
     * @param array $messageData Raw message data
     * @return array Prepared data array
     */
    private function prepareMessageData($channelId, $messageData) {
        $timestamp = $this->generateTimestamp();
        
        // Get actor IDs from speaker/target names
        $fromActorId = null;
        $toActorId = null;
        if (!empty($messageData['speaker'])) {
            $fromActorId = $this->getActorIdFromSpeaker($messageData['speaker']);
        }
        if (!empty($messageData['target'])) {
            $toActorId = $this->getActorIdFromSpeaker($messageData['target']);
        }
        
        return [
            'channel_id' => $channelId,
            'dialog_thread_id' => $this->sanitizeInt($messageData['thread_id'] ?? $messageData['dialog_thread_id'] ?? null),
            'from_actor_id' => $fromActorId,
            'to_actor_id' => $toActorId,
            'message_text' => $this->truncateMessage($messageData['message_text']),
            'mood_rgb' => $this->sanitizeMoodRgb($messageData['mood_rgb']) ?: '666666',
            'message_type' => $this->validateMessageType($messageData['message_type']),
            'metadata_json' => json_encode([
                'original_length' => strlen($messageData['message_text']),
                'migration_date' => date('Y-m-d H:i:s'),
                'migration_version' => '4.0.101',
                'speaker' => $messageData['speaker'] ?? null,
                'target' => $messageData['target'] ?? null,
                'reply_to_message_id' => $messageData['reply_to_message_id'] ?? null,
                'additional_metadata' => $messageData['metadata'] ?? []
            ]),
            'created_ymdhis' => $timestamp,
            'updated_ymdhis' => $timestamp,
            'weight' => isset($messageData['weight']) ? (float)$messageData['weight'] : 0.00
        ];
    }
    
    /**
     * Sanitize string values
     * 
     * @param mixed $value Value to sanitize
     * @return string|null Sanitized string or null
     */
    private function sanitizeString($value) {
        if (empty($value)) return null;
        return trim(strip_tags($value));
    }
    
    /**
     * Sanitize integer values
     * 
     * @param mixed $value Value to sanitize
     * @return int|null Sanitized integer or null
     */
    private function sanitizeInt($value) {
        if (empty($value)) return null;
        return is_numeric($value) ? (int)$value : null;
    }
    
    /**
     * Truncate message text to 1000 characters
     * 
     * @param string $text Message text
     * @return string Truncated text
     */
    private function truncateMessage($text) {
        if (strlen($text) <= 1000) {
            return $text;
        }
        
        // Truncate at word boundary if possible
        $truncated = substr($text, 0, 997);
        $lastSpace = strrpos($truncated, ' ');
        
        if ($lastSpace !== false && $lastSpace > 800) {
            return substr($text, 0, $lastSpace) . '...';
        }
        
        return substr($text, 0, 997) . '...';
    }
    
    /**
     * Sanitize mood RGB value
     * 
     * @param mixed $value Mood RGB value
     * @return string|null Sanitized RGB value or null
     */
    private function sanitizeMoodRgb($value) {
        if (empty($value)) return null;
        
        // Ensure it starts with #
        $value = ltrim($value, '#');
        if (preg_match('/^[0-9A-Fa-f]{6}$/', $value)) {
            return '#' . strtoupper($value);
        }
        
        return null;
    }
    
    /**
     * Parse timestamp value
     * 
     * @param mixed $value Timestamp value
     * @return int|null Parsed timestamp or null
     */
    private function parseTimestamp($value) {
        if (empty($value)) return null;
        
        // If already in YYYYMMDDHHIISS format
        if (preg_match('/^\d{14}$/', $value)) {
            return (int)$value;
        }
        
        // Try to parse as date
        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return (int)date('YmdHis', $timestamp);
        }
        
        return null;
    }
    
    /**
     * Validate message type
     * 
     * @param mixed $type Message type
     * @return string Valid message type
     */
    private function validateMessageType($type) {
        $validTypes = ['text', 'command', 'system', 'error'];
        return in_array($type, $validTypes) ? $type : 'text';
    }
    
    /**
     * Generate timestamp in YYYYMMDDHHIISS format
     * 
     * @return int Timestamp
     */
    private function generateTimestamp() {
        return (int)date('YmdHis');
    }
    
    /**
     * Get messages for a channel
     * 
     * @param int $channelId Channel ID
     * @param int $limit Optional limit
     * @param int $offset Optional offset
     * @return array Array of messages
     */
    public function getChannelMessages($channelId, $limit = null, $offset = 0) {
        try {
            $sql = "SELECT * FROM lupo_dialog_doctrine 
                    WHERE channel_id = :channel_id 
                    AND is_deleted = 0
                    ORDER BY created_ymdhis ASC";
            
            if ($limit !== null) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':channel_id', $channelId, PDO::PARAM_INT);
            
            if ($limit !== null) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to get messages: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Count messages in a channel
     * 
     * @param int $channelId Channel ID
     * @return int Message count
     */
    public function countChannelMessages($channelId) {
        try {
            $sql = "SELECT COUNT(*) FROM lupo_dialog_doctrine WHERE channel_id = :channel_id AND is_deleted = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['channel_id' => $channelId]);
            return (int)$stmt->fetchColumn();
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to count messages: " . $e->getMessage();
            return 0;
        }
    }
    
    /**
     * Delete message
     * 
     * @param int $messageId Message ID
     * @return bool Success status
     */
    public function deleteMessage($messageId) {
        try {
            // Get channel_id before deletion (needed for trigger replacement)
            $getChannelSql = "SELECT channel_id FROM lupo_dialog_doctrine WHERE dialog_message_id = :dialog_message_id";
            $getChannelStmt = $this->db->prepare($getChannelSql);
            $getChannelStmt->execute(['dialog_message_id' => $messageId]);
            $channelData = $getChannelStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$channelData) {
                $this->errors[] = "Message not found: " . $messageId;
                return false;
            }
            
            $channelId = $channelData['channel_id'];
            
            // Soft delete: set is_deleted flag and deleted_ymdhis timestamp
            $timestamp = $this->generateTimestamp();
            $sql = "UPDATE lupo_dialog_doctrine 
                    SET is_deleted = 1, deleted_ymdhis = :deleted_ymdhis, updated_ymdhis = :updated_ymdhis 
                    WHERE dialog_message_id = :dialog_message_id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'dialog_message_id' => $messageId,
                'deleted_ymdhis' => $timestamp,
                'updated_ymdhis' => $timestamp
            ]);
            
            if ($result) {
                // Update channel message count after delete (replaces tr_dialog_messages_delete trigger)
                require_once __DIR__ . '/../../app/Services/TriggerReplacements/DialogMessagesDeleteService.php';
                $deleteService = new DialogMessagesDeleteService($this->db);
                $deleteService->executeAfterDelete($channelId);
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to delete message: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get actor_id from speaker name/slug
     * Helper method for protocol enforcement
     * 
     * @param string $speaker Speaker name or slug
     * @return int|null Actor ID or null if not found
     */
    private function getActorIdFromSpeaker($speaker) {
        if (empty($speaker)) {
            return null;
        }
        
        try {
            // Try to find actor by slug or name
            $sql = "SELECT actor_id FROM lupo_actors 
                    WHERE (slug = :speaker OR name = :speaker) 
                    AND is_deleted = 0 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['speaker' => $speaker]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? (int)$result['actor_id'] : null;
        } catch (Exception $e) {
            // If lookup fails, return null (protocol check will be skipped)
            return null;
        }
    }
    
    /**
     * Get errors
     * 
     * @return array Array of error messages
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get warnings
     * 
     * @return array Array of warning messages
     */
    public function getWarnings() {
        return $this->warnings;
    }
    
    /**
     * Clear errors and warnings
     */
    public function clearMessages() {
        $this->errors = [];
        $this->warnings = [];
    }
}