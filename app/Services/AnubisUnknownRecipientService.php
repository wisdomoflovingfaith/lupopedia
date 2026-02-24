<?php
/**
 * ANUBIS Unknown Recipient Service
 * 
 * Handles routing of files with unknown FLIP header recipients to ANUBIS
 * for orphan resolution, adoption, and safe placement into the system.
 * 
 * @package Lupopedia\App\Services
 * @version 4.0.29
 * @author Captain Wolfie Stoned - Lupopedia LLC 2026
 */

class AnubisUnknownRecipientService
{
    const ANUBIS_ACTOR_ID = 59;
    const DEFAULT_CHANNEL_ID = 42;
    const DEFAULT_THREAD_ID = 1;
    const QUARANTINE_CHANNEL_ID = 666;
    
    private $db;
    
    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
    }
    
    /**
     * Determine the appropriate recipient for a file based on FLIP headers
     * 
     * @param string $filePath Path to the file being processed
     * @param array $flipHeader Parsed FLIP header data
     * @return array Recipient information with type and id
     */
    public function determineRecipient($filePath, $flipHeader)
    {
        // 1. Valid channel
        if (isset($flipHeader['channel_id']) && $this->channelExists($flipHeader['channel_id'])) {
            return [
                'type' => 'channel',
                'id' => $flipHeader['channel_id'],
                'reason' => 'valid_channel'
            ];
        }
        
        // 2. Valid actor
        if (isset($flipHeader['actor_id']) && $this->actorExists($flipHeader['actor_id'])) {
            return [
                'type' => 'actor',
                'id' => $flipHeader['actor_id'],
                'reason' => 'valid_actor'
            ];
        }
        
        // 3. Valid edge
        if (isset($flipHeader['edge_id']) && $this->edgeExists($flipHeader['edge_id'])) {
            return [
                'type' => 'edge',
                'id' => $flipHeader['edge_id'],
                'reason' => 'valid_edge'
            ];
        }
        
        // 4. Unknown → ANUBIS
        return [
            'type' => 'actor',
            'id' => self::ANUBIS_ACTOR_ID,
            'reason' => 'unknown_recipient',
            'original_recipient' => $this->getOriginalRecipient($flipHeader)
        ];
    }
    
    /**
     * Process an orphan file through ANUBIS protocol
     * 
     * @param string $filePath Path to the orphan file
     * @param array $flipHeader Original FLIP header
     * @param string $reasonCode Reason for orphan status
     * @return array Processing result
     */
    public function processOrphanFile($filePath, $flipHeader, $reasonCode)
    {
        // Step 1: Intake logging
        $logId = $this->logIntake($filePath, $flipHeader, $reasonCode);
        
        // Step 2: Classification
        $classification = $this->classifyOrphan($filePath, $flipHeader, $reasonCode);
        
        // Step 3: Adoption decision
        $adoption = $this->makeAdoptionDecision($classification);
        
        // Step 4: Execute adoption
        $result = $this->executeAdoption($filePath, $flipHeader, $adoption, $logId);
        
        return $result;
    }
    
    /**
     * Check if an edge exists and is operational
     * 
     * @param int $edgeId Edge ID to check
     * @return bool True if edge exists and is active
     */
    private function edgeExists($edgeId)
    {
        // For now, check if edge exists in lupo_contents
        // Future: check dedicated lupo_edges table
        $result = $this->db->fetch(
            "SELECT edge_id FROM lupo_contents 
             WHERE edge_id = :id AND is_deleted = 0",
            ['id' => $edgeId]
        );
        return !empty($result);
    }
    
    /**
     * Check if a channel exists and is operational
     * 
     * @param int $channelId Channel ID to check
     * @return bool True if channel exists and is active
     */
    private function channelExists($channelId)
    {
        $result = $this->db->fetch(
            "SELECT channel_id FROM lupo_channels 
             WHERE channel_id = :id AND is_deleted = 0",
            ['id' => $channelId]
        );
        return !empty($result);
    }
    
    /**
     * Check if an actor exists and is operational
     * 
     * @param int $actorId Actor ID to check
     * @return bool True if actor exists and is active
     */
    private function actorExists($actorId)
    {
        $result = $this->db->fetch(
            "SELECT actor_id FROM lupo_actors 
             WHERE actor_id = :id AND is_deleted = 0 AND is_active = 1",
            ['id' => $actorId]
        );
        return !empty($result);
    }
    
    /**
     * Extract original recipient information from FLIP header
     * 
     * @param array $flipHeader FLIP header data
     * @return string Original recipient description
     */
    private function getOriginalRecipient($flipHeader)
    {
        $parts = [];
        
        if (isset($flipHeader['channel_id'])) {
            $parts[] = "channel:{$flipHeader['channel_id']}";
        }
        
        if (isset($flipHeader['actor_id'])) {
            $parts[] = "actor:{$flipHeader['actor_id']}";
        }
        
        if (isset($flipHeader['edge_id'])) {
            $parts[] = "edge:{$flipHeader['edge_id']}";
        }
        
        return implode(', ', $parts) ?: 'none';
    }
    
    /**
     * Log orphan file intake
     * 
     * @param string $filePath File path
     * @param array $flipHeader FLIP header
     * @param string $reasonCode Reason for orphan status
     * @return string Log ID
     */
    private function logIntake($filePath, $flipHeader, $reasonCode)
    {
        $logId = $this->generateUuid();
        $timestamp = gmdate('YmdHis');
        
        $this->db->insert('lupo_anubis_log', [
            'log_id' => $logId,
            'file_path' => $filePath,
            'original_recipient' => $this->getOriginalRecipient($flipHeader),
            'reason_code' => $reasonCode,
            'processed_ymdhis' => $timestamp,
            'actor_id' => self::ANUBIS_ACTOR_ID,
            'created_ymdhis' => $timestamp,
            'updated_ymdhis' => $timestamp
        ]);
        
        return $logId;
    }
    
    /**
     * Classify orphan file based on analysis
     * 
     * @param string $filePath File path
     * @param array $flipHeader FLIP header
     * @param string $reasonCode Original reason
     * @return array Classification data
     */
    private function classifyOrphan($filePath, $flipHeader, $reasonCode)
    {
        $classification = [
            'reason_code' => $reasonCode,
            'file_size' => filesize($filePath) ?: 0,
            'file_type' => $this->detectFileType($filePath),
            'risk_level' => 'low'
        ];
        
        // Risk assessment
        if ($classification['file_size'] > 10 * 1024 * 1024) { // > 10MB
            $classification['risk_level'] = 'high';
        }
        
        if (in_array($classification['file_type'], ['executable', 'script'])) {
            $classification['risk_level'] = 'high';
        }
        
        if ($reasonCode === 'MALFORMED_HEADER') {
            $classification['risk_level'] = 'high';
        }
        
        return $classification;
    }
    
    /**
     * Make adoption decision based on classification
     * 
     * @param array $classification Classification data
     * @return array Adoption decision
     */
    private function makeAdoptionDecision($classification)
    {
        $decision = [
            'action' => 'adopt',
            'target_channel_id' => self::DEFAULT_CHANNEL_ID,
            'target_thread_id' => self::DEFAULT_THREAD_ID,
            'status' => 'adopted',
            'visibility' => 'public'
        ];
        
        // High-risk files go to quarantine
        if ($classification['risk_level'] === 'high') {
            $decision['action'] = 'quarantine';
            $decision['target_channel_id'] = self::QUARANTINE_CHANNEL_ID;
            $decision['target_thread_id'] = 2; // Quarantine thread
            $decision['status'] = 'quarantined';
            $decision['visibility'] = 'private';
        }
        
        // Malformed headers are rejected
        if ($classification['reason_code'] === 'MALFORMED_HEADER') {
            $decision['action'] = 'reject';
            $decision['target_channel_id'] = self::QUARANTINE_CHANNEL_ID;
            $decision['target_thread_id'] = 3; // Rejection thread
            $decision['status'] = 'rejected';
            $decision['visibility'] = 'hidden';
        }
        
        return $decision;
    }
    
    /**
     * Execute adoption decision
     * 
     * @param string $filePath File path
     * @param array $flipHeader FLIP header
     * @param array $adoption Adoption decision
     * @param string $logId Log ID
     * @return array Execution result
     */
    private function executeAdoption($filePath, $flipHeader, $adoption, $logId)
    {
        $timestamp = gmdate('YmdHis');
        
        // Create adoption message
        $messageId = $this->generateMessageId();
        
        $this->db->insert('lupo_dialog_doctrine', [
            'dialog_message_id' => $messageId,
            'dialog_thread_id' => $adoption['target_thread_id'],
            'channel_id' => $adoption['target_channel_id'],
            'from_actor_id' => self::ANUBIS_ACTOR_ID,
            'message_text' => $this->buildAdoptionMessage($filePath, $flipHeader, $adoption),
            'message_type' => 'adoption',
            'created_ymdhis' => $timestamp,
            'updated_ymdhis' => $timestamp,
            'is_deleted' => 0
        ]);
        
        // Update log with adoption decision
        $this->db->update('lupo_anubis_log', 
            [
                'decision' => $adoption['action'],
                'target_channel_id' => $adoption['target_channel_id'],
                'target_thread_id' => $adoption['target_thread_id'],
                'updated_ymdhis' => $timestamp
            ],
            'log_id = :log_id',
            ['log_id' => $logId]
        );
        
        return [
            'success' => true,
            'action' => $adoption['action'],
            'message_id' => $messageId,
            'log_id' => $logId,
            'target_channel' => $adoption['target_channel_id'],
            'target_thread' => $adoption['target_thread_id']
        ];
    }
    
    /**
     * Build adoption message text
     * 
     * @param string $filePath File path
     * @param array $flipHeader FLIP header
     * @param array $adoption Adoption decision
     * @return string Message text
     */
    private function buildAdoptionMessage($filePath, $flipHeader, $adoption)
    {
        $parts = [
            "ANUBIS: {$adoption['action']} orphan file",
            "Path: {$filePath}",
            "Reason: {$flipHeader['reason_code'] ?? 'unknown'}",
            "Action: {$adoption['status']}",
            "Target: Channel {$adoption['target_channel_id']}, Thread {$adoption['target_thread_id']}"
        ];
        
        return implode(' | ', $parts);
    }
    
    /**
     * Detect file type from path and content
     * 
     * @param string $filePath File path
     * @return string File type
     */
    private function detectFileType($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $typeMap = [
            'md' => 'markdown',
            'txt' => 'text',
            'php' => 'script',
            'js' => 'script',
            'sql' => 'database',
            'json' => 'data',
            'yaml' => 'data',
            'yml' => 'data',
            'html' => 'web',
            'css' => 'web',
            'exe' => 'executable',
            'bin' => 'executable'
        ];
        
        return $typeMap[$extension] ?? 'unknown';
    }
    
    /**
     * Generate UUID for log entries
     * 
     * @return string UUID
     */
    private function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    /**
     * Generate message ID for dialog messages
     * 
     * @return int Message ID
     */
    private function generateMessageId()
    {
        // Use timestamp + random for uniqueness
        return (int)substr(gmdate('YmdHis') . rand(100, 999), -10);
    }
    
    /**
     * Get ANUBIS statistics for monitoring
     * 
     * @param int $hours Number of hours to look back
     * @return array Statistics
     */
    public function getAnubisStats($hours = 24)
    {
        $cutoff = gmdate('YmdHis', time() - ($hours * 3600));
        
        $stats = $this->db->fetchAll(
            "SELECT 
                reason_code,
                decision,
                COUNT(*) as count,
                AVG(CASE WHEN file_size > 0 THEN file_size ELSE NULL END) as avg_size
             FROM lupo_anubis_log 
             WHERE processed_ymdhis >= :cutoff
             GROUP BY reason_code, decision
             ORDER BY count DESC",
            ['cutoff' => $cutoff]
        );
        
        return $stats;
    }
}
