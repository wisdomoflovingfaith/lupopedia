<?php
/**
 * Dialog Messages Delete Trigger Replacement Service
 * 
 * Replaces: tr_dialog_messages_delete trigger
 * Original Logic: Updates message_count and modified_timestamp after DELETE on lupo_dialog_doctrine
 * 
 * @package Lupopedia
 * @version 4.0.102
 * @author Trigger Extraction Migration
 */

class DialogMessagesDeleteService
{
    protected $db;

    /**
     * Constructor
     * 
     * @param PDO|PDO_DB $db Database connection instance
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Execute the trigger logic after DELETE
     * 
     * Updates lupo_dialog_channels.message_count and updated_ymdhis
     * after a message is deleted from lupo_dialog_doctrine
     * 
     * @param int $channelId The channel_id from the deleted message
     * @return bool Success status
     */
    public function executeAfterDelete($channelId)
    {
        $channelId = (int)$channelId;

        // LIMITS enforcement (dry-run mode in 4.0.103)
        // Check weekend mode before updating (non-blocking, logs warnings only)
        if (file_exists(__DIR__ . '/../../lupo-includes/functions/limits_logger.php')) {
            require_once __DIR__ . '/../../lupo-includes/functions/limits_logger.php';
            safe_check_weekend_mode();
        }

        // Get PDO instance if PDO_DB wrapper is used
        $pdo = ($this->db instanceof PDO_DB) ? $this->db->getPdo() : $this->db;
        
        try {
            // Calculate current message count for this channel (excluding soft-deleted)
            $countSql = "SELECT COUNT(*) as message_count 
                        FROM lupo_dialog_doctrine 
                        WHERE channel_id = :channel_id AND is_deleted = 0";
            $countStmt = $pdo->prepare($countSql);
            $countStmt->execute([':channel_id' => $channelId]);
            $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
            $messageCount = (int)$countResult['message_count'];
            
            // Update channel with new message count and updated timestamp
            // Using current UTC timestamp in YMDHIS format
            $updatedTimestamp = gmdate('YmdHis');
            
            $updateSql = "UPDATE lupo_dialog_channels 
                         SET message_count = :message_count,
                             modified_timestamp = :modified_timestamp
                         WHERE channel_id = :channel_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                ':message_count' => $messageCount,
                ':modified_timestamp' => $updatedTimestamp,
                ':channel_id' => $channelId
            ]);
            
            return true;
        } catch (Exception $e) {
            // Log error but don't throw - preserve trigger behavior
            error_log("DialogMessagesDeleteService error: " . $e->getMessage());
            return false;
        }
    }
}
