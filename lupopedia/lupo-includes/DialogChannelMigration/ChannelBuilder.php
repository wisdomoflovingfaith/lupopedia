<?php
/**
 * Channel Builder for Big Rock 2: Dialog Channel Migration
 * 
 * Creates channel entries in MySQL from parsed dialog metadata.
 * Handles database insertion with proper validation and error handling.
 * 
 * @package Lupopedia
 * @subpackage DialogChannelMigration
 * @version 4.0.102
 * @author Captain Wolfie
 */

class ChannelBuilder {
    
    private $db;
    private $errors = [];
    
    /**
     * Constructor
     * 
     * @param PDO $database Database connection
     */
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Create a dialog channel from parsed metadata
     * 
     * @param array $metadata Parsed metadata from DialogParser
     * @param string $fileName Original filename
     * @return int|false Channel ID on success, false on failure
     */
    public function createChannel($metadata, $fileName) {
        try {
            // Generate channel name from filename
            $channelName = $this->generateChannelName($fileName);
            
            // Check if channel already exists
            if ($this->channelExists($channelName)) {
                $this->errors[] = "Channel already exists: {$channelName}";
                return false;
            }
            
            // Prepare channel data
            $channelData = $this->prepareChannelData($metadata, $fileName, $channelName);
            
            // Insert channel
            $sql = "INSERT INTO lupo_dialog_channels (
                channel_name, file_source, title, description, speaker, target, 
                mood_rgb, categories, collections, channels, tags, version, 
                status, author, created_timestamp, modified_timestamp, metadata_json
            ) VALUES (
                :channel_name, :file_source, :title, :description, :speaker, :target,
                :mood_rgb, :categories, :collections, :channels, :tags, :version,
                :status, :author, :created_timestamp, :modified_timestamp, :metadata_json
            )";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($channelData);
            
            if ($result) {
                return $this->db->lastInsertId();
            } else {
                $this->errors[] = "Failed to insert channel: " . implode(', ', $stmt->errorInfo());
                return false;
            }
            
        } catch (Exception $e) {
            $this->errors[] = "Channel creation error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Generate channel name from filename
     * 
     * @param string $fileName Original filename
     * @return string Generated channel name
     */
    private function generateChannelName($fileName) {
        // Remove .md extension
        $name = preg_replace('/\.md$/', '', $fileName);
        
        // Replace special characters with underscores
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        
        // Remove multiple underscores
        $name = preg_replace('/_+/', '_', $name);
        
        // Trim underscores from ends
        $name = trim($name, '_');
        
        return strtolower($name);
    }
    
    /**
     * Check if channel already exists
     * 
     * @param string $channelName Channel name to check
     * @return bool True if exists, false otherwise
     */
    private function channelExists($channelName) {
        $sql = "SELECT COUNT(*) FROM lupo_dialog_channels WHERE channel_name = :channel_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['channel_name' => $channelName]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Prepare channel data for database insertion
     * 
     * @param array $metadata Parsed metadata
     * @param string $fileName Original filename
     * @param string $channelName Generated channel name
     * @return array Prepared data array
     */
    private function prepareChannelData($metadata, $fileName, $channelName) {
        $timestamp = $this->generateTimestamp();
        
        return [
            'channel_name' => $channelName,
            'file_source' => $fileName,
            'title' => $this->sanitizeString($metadata['title']),
            'description' => $this->sanitizeString($metadata['description']),
            'speaker' => $this->sanitizeString($metadata['speaker']),
            'target' => $this->sanitizeString($metadata['target']),
            'mood_rgb' => $this->sanitizeMoodRgb($metadata['mood_rgb']),
            'categories' => $this->encodeJsonArray($metadata['categories']),
            'collections' => $this->encodeJsonArray($metadata['collections']),
            'channels' => $this->encodeJsonArray($metadata['channels']),
            'tags' => $this->encodeJsonArray($metadata['tags']),
            'version' => $this->sanitizeString($metadata['version']) ?: '4.0.101',
            'status' => $this->validateStatus($metadata['status']),
            'author' => $this->sanitizeString($metadata['author']) ?: 'Captain Wolfie',
            'created_timestamp' => $timestamp,
            'modified_timestamp' => $timestamp,
            'metadata_json' => json_encode([
                'raw_header' => $metadata['raw_header'],
                'migration_date' => date('Y-m-d H:i:s'),
                'migration_version' => '4.0.101'
            ])
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
     * Encode array as JSON
     * 
     * @param array $array Array to encode
     * @return string|null JSON string or null
     */
    private function encodeJsonArray($array) {
        if (empty($array) || !is_array($array)) return null;
        return json_encode(array_values($array));
    }
    
    /**
     * Validate status value
     * 
     * @param mixed $status Status value
     * @return string Valid status
     */
    private function validateStatus($status) {
        $validStatuses = ['draft', 'published', 'archived'];
        return in_array($status, $validStatuses) ? $status : 'published';
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
     * Update channel message count
     * 
     * @param int $channelId Channel ID
     * @param int $messageCount New message count
     * @return bool Success status
     */
    public function updateMessageCount($channelId, $messageCount) {
        try {
            $sql = "UPDATE lupo_dialog_channels 
                    SET message_count = :count, modified_timestamp = :timestamp 
                    WHERE channel_id = :channel_id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'count' => $messageCount,
                'timestamp' => $this->generateTimestamp(),
                'channel_id' => $channelId
            ]);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to update message count: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get channel by name
     * 
     * @param string $channelName Channel name
     * @return array|false Channel data or false if not found
     */
    public function getChannelByName($channelName) {
        try {
            $sql = "SELECT * FROM lupo_dialog_channels WHERE channel_name = :channel_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['channel_name' => $channelName]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to get channel: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Get all channels
     * 
     * @return array Array of channel data
     */
    public function getAllChannels() {
        try {
            $sql = "SELECT * FROM lupo_dialog_channels ORDER BY created_timestamp DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to get channels: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * Delete channel and all its messages
     * 
     * @param int $channelId Channel ID
     * @return bool Success status
     */
    public function deleteChannel($channelId) {
        try {
            // Foreign key constraint will cascade delete messages
            $sql = "DELETE FROM lupo_dialog_channels WHERE channel_id = :channel_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['channel_id' => $channelId]);
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to delete channel: " . $e->getMessage();
            return false;
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
     * Clear errors
     */
    public function clearErrors() {
        $this->errors = [];
    }
}