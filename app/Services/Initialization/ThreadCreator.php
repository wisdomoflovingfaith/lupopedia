<?php
/**
 * ThreadCreator - Creates development coordination threads in Channel 42
 * 
 * This class handles the creation of thread directories and metadata files
 * for development coordination. It creates a thread directory structure and
 * generates a thread.json file with all required metadata fields.
 * 
 * Thread Structure:
 *   channels/42/threads/DEVELOPMENT_CYCLE_4_0_44/
 *     thread.json - Thread metadata
 *     (messages will be added here later)
 * 
 * Usage:
 *   $creator = new ThreadCreator($timestampHelper, '/path/to/lupopedia');
 *   $metadata = $creator->createThread(
 *       'DEVELOPMENT_CYCLE_4_0_44',
 *       'Crafty Syntax / Lupopedia Development — Version 4.0.44',
 *       1001,
 *       42
 *   );
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class ThreadCreator implements ThreadCreatorInterface
{
    /**
     * @var TimestampHelperInterface Timestamp utility
     */
    private $timestampHelper;
    
    /**
     * @var string Base path to Lupopedia installation
     */
    private $basePath;
    
    /**
     * Constructor
     * 
     * @param TimestampHelperInterface $timestampHelper Timestamp utility
     * @param string $basePath Base path to Lupopedia installation
     */
    public function __construct(TimestampHelperInterface $timestampHelper, $basePath)
    {
        $this->timestampHelper = $timestampHelper;
        $this->basePath = rtrim($basePath, '/\\');
    }
    
    /**
     * Create a new development thread in Channel 42
     * 
     * Creates the thread directory and thread.json metadata file with all
     * required fields. Handles existing thread directories gracefully by
     * checking if the directory exists and throwing an exception if it does.
     * 
     * Thread metadata includes:
     * - thread_id: Unique thread identifier
     * - title: Human-readable thread title
     * - type: Thread type (e.g., "development")
     * - priority: Thread priority (e.g., "high")
     * - visibility: Thread visibility (e.g., "system")
     * - created_ymdhis: Creation timestamp in YYYYMMDDHHMMSS format
     * - created_by_actor_id: Actor ID of creator
     * - channel_id: Channel ID where thread exists
     * 
     * @param string $threadId Thread identifier (e.g., "DEVELOPMENT_CYCLE_4_0_44")
     * @param string $title Thread title
     * @param int $actorId Actor ID creating the thread (e.g., 1001 for KIRO)
     * @param int $channelId Channel ID (e.g., 42 for development)
     * @return array Thread metadata
     * @throws ThreadCreationException If thread creation fails or thread already exists
     */
    public function createThread($threadId, $title, $actorId, $channelId)
    {
        // Check if thread already exists
        if ($this->threadExists($threadId)) {
            throw new ThreadCreationException(
                ErrorMessages::threadAlreadyExists($threadId)
            );
        }
        
        // Generate thread directory path
        $threadPath = $this->getThreadPath($threadId);
        
        // Create thread directory
        if (!$this->createDirectory($threadPath)) {
            throw new ThreadCreationException(
                ErrorMessages::directoryCreationFailed($threadPath, 'ThreadCreator')
            );
        }
        
        // Generate thread metadata
        $metadata = $this->generateMetadata($threadId, $title, $actorId, $channelId);
        
        // Write thread.json file
        $jsonPath = $threadPath . '/thread.json';
        if (!$this->writeThreadJson($jsonPath, $metadata)) {
            throw new ThreadCreationException(
                ErrorMessages::fileWriteFailed($jsonPath, 'ThreadCreator')
            );
        }
        
        return $metadata;
    }
    
    /**
     * Check if a thread already exists
     * 
     * @param string $threadId Thread identifier
     * @return bool True if thread exists, false otherwise
     */
    public function threadExists($threadId)
    {
        $threadPath = $this->getThreadPath($threadId);
        return is_dir($threadPath);
    }
    
    /**
     * Get full path to thread directory
     * 
     * @param string $threadId Thread identifier
     * @return string Full path to thread directory
     */
    private function getThreadPath($threadId)
    {
        return $this->basePath . '/channels/42/threads/' . $threadId;
    }
    
    /**
     * Create directory with proper permissions
     * 
     * @param string $path Directory path
     * @return bool True on success, false on failure
     */
    private function createDirectory($path)
    {
        if (is_dir($path)) {
            return true;
        }
        
        // Create directory with 0755 permissions
        // Use recursive mode to create parent directories if needed
        return @mkdir($path, 0755, true);
    }
    
    /**
     * Generate thread metadata
     * 
     * Creates a structured array with all required thread metadata fields.
     * Sets appropriate values for type, priority, and visibility based on
     * the requirements.
     * 
     * @param string $threadId Thread identifier
     * @param string $title Thread title
     * @param int $actorId Actor ID creating the thread
     * @param int $channelId Channel ID
     * @return array Thread metadata
     */
    private function generateMetadata($threadId, $title, $actorId, $channelId)
    {
        $createdTimestamp = $this->timestampHelper->getCurrentUTC();
        
        return array(
            'thread_id' => $threadId,
            'title' => $title,
            'type' => 'development',
            'priority' => 'high',
            'visibility' => 'system',
            'created_ymdhis' => $createdTimestamp,
            'created_by_actor_id' => $actorId,
            'channel_id' => $channelId
        );
    }
    
    /**
     * Write thread metadata to JSON file
     * 
     * Encodes metadata as pretty-printed JSON and writes to file.
     * Uses JSON_PRETTY_PRINT for human readability.
     * 
     * @param string $path Path to thread.json file
     * @param array $metadata Thread metadata
     * @return bool True on success, false on failure
     */
    private function writeThreadJson($path, $metadata)
    {
        // Encode as pretty-printed JSON
        $json = json_encode($metadata, JSON_PRETTY_PRINT);
        
        if ($json === false) {
            return false;
        }
        
        // Write to file
        $result = @file_put_contents($path, $json);
        
        return $result !== false;
    }
}
