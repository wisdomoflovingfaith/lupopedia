<?php
/**
 * Enforce Protocol Completion Trigger Replacement Service
 * 
 * Replaces: tr_enforce_protocol_completion trigger
 * Original Logic: Enforces protocol completion before allowing communication
 * 
 * @package Lupopedia
 * @version 4.0.101
 * @author Trigger Extraction Migration
 */

class EnforceProtocolCompletionService
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
     * Execute the trigger logic before INSERT
     * 
     * Checks if the actor has completed all protocols for this channel
     * before allowing a message to be inserted
     * 
     * @param int $speakerId The actor_id (speaker) attempting to send message
     * @param int $channelId The channel_id where message is being sent
     * @return bool True if protocol is complete, false otherwise
     * @throws Exception If protocol violation detected (matches trigger SIGNAL behavior)
     */
    public function executeBeforeInsert($speakerId, $channelId)
    {
        $speakerId = (int)$speakerId;
        $channelId = (int)$channelId;
        
        // Get PDO instance if PDO_DB wrapper is used
        $pdo = ($this->db instanceof PDO_DB) ? $this->db->getPdo() : $this->db;
        
        try {
            // Check if the actor has completed all protocols for this channel
            $checkSql = "SELECT protocol_completion_status 
                        FROM lupo_actor_channel_roles 
                        WHERE actor_id = :actor_id AND channel_id = :channel_id";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                ':actor_id' => $speakerId,
                ':channel_id' => $channelId
            ]);
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            $protocolStatus = $result ? $result['protocol_completion_status'] : null;
            
            // Enforce invariant: No communication before protocol completion
            if ($protocolStatus === null || $protocolStatus !== 'ready') {
                throw new Exception('PROTOCOL VIOLATION: Agent must complete AAL + RSHAP + CJP before communication');
            }
            
            return true;
        } catch (Exception $e) {
            // Re-throw protocol violations (matches trigger SIGNAL behavior)
            if (strpos($e->getMessage(), 'PROTOCOL VIOLATION') !== false) {
                throw $e;
            }
            // Log other errors
            error_log("EnforceProtocolCompletionService error: " . $e->getMessage());
            throw $e;
        }
    }
}
