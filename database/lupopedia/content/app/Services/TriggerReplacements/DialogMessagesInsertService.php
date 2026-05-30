<?php
/**
 * Dialog Messages Insert Trigger Replacement Service
 * 
 * Replaces: tr_dialog_messages_insert trigger
 * Original Logic: Updates message_count and modified_timestamp after INSERT on lupo_dialog_messages
 * 
 * @package Lupopedia
 * @version 3.0.102
 * @author Trigger Extraction Migration
 */

class DialogMessagesInsertService
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
     * Updates lupo_dialog_channels.message_count and updated_ymdhis,
     * and lupo_dialog_threads.last_message_ymdhis.
     * 
     * @param int $channelId The channel_id from the inserted message
     * @param int|null $threadId The dialog_thread_id from the inserted message
     * @return bool Success status
     */
    public function executeAfterInsert($channelId, $threadId = null)
    {
        $channelId = (int) $channelId;

        // LIMITS enforcement (dry-run mode in 3.0.103)
        // Check weekend mode before updating (non-blocking, logs warnings only)
        if (file_exists(__DIR__ . '/../../includes/functions/limits_logger.php')) {
            require_once __DIR__ . '/../../includes/functions/limits_logger.php';
            safe_check_weekend_mode();
        }

        // Get PDO instance if PDO_DB wrapper is used
        $pdo = ($this->db instanceof PDO_DB) ? $this->db->getPdo() : $this->db;

        try {
            // Calculate current message count for this channel (excluding soft-deleted)
            $countSql = "SELECT COUNT(*) as message_count 
                        FROM lupo_dialog_messages 
                        WHERE channel_id = :channel_id AND is_deleted = 0";
            $countStmt = $pdo->prepare($countSql);
            $countStmt->execute([':channel_id' => $channelId]);
            $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
            $messageCount = (int) $countResult['message_count'];

            // Update channel with new message count and updated timestamp
            // Using current UTC timestamp in YMDHIS format
            $updatedTimestamp = gmdate('YmdHis');

            $updateSql = "UPDATE lupo_dialog_channels 
                         SET message_count = :message_count,
                             updated_ymdhis = :updated_ymdhis
                         WHERE channel_id = :channel_id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                ':message_count' => $messageCount,
                ':updated_ymdhis' => $updatedTimestamp,
                ':channel_id' => $channelId
            ]);

            // Update thread with last message timestamp
            if ($threadId !== null && (int) $threadId > 0) {
                $threadSql = "UPDATE lupo_dialog_threads 
                             SET last_message_ymdhis = :last_message
                             WHERE dialog_thread_id = :tid";
                $threadStmt = $pdo->prepare($threadSql);
                $threadStmt->execute([
                    ':last_message' => $updatedTimestamp,
                    ':tid' => (int) $threadId
                ]);
            }

            return true;
        } catch (Exception $e) {
            // Log error but don't throw - preserve trigger behavior
            error_log("DialogMessagesInsertService error: " . $e->getMessage());
            return false;
        }
    }
}
