<?php
/**
 * Interface for creating development threads in Channel 42
 * 
 * Defines the contract for creating thread directories and metadata files
 * for development coordination.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface ThreadCreatorInterface
{
    /**
     * Create a new development thread in Channel 42
     * 
     * Creates the thread directory and thread.json metadata file with all
     * required fields (thread_id, title, type, priority, visibility, etc.)
     * 
     * @param string $threadId Thread identifier (e.g., "DEVELOPMENT_CYCLE_4_0_44")
     * @param string $title Thread title
     * @param int $actorId Actor ID creating the thread (e.g., 1001 for KIRO)
     * @param int $channelId Channel ID (e.g., 42 for development)
     * @return array Thread metadata
     * @throws ThreadCreationException If thread creation fails
     */
    public function createThread($threadId, $title, $actorId, $channelId);
    
    /**
     * Check if a thread already exists
     * 
     * @param string $threadId Thread identifier
     * @return bool True if thread exists, false otherwise
     */
    public function threadExists($threadId);
}
