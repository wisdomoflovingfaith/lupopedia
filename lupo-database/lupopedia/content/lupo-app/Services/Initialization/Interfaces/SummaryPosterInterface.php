<?php
/**
 * Interface for posting summaries to Channel 42
 * 
 * Defines the contract for creating concise summary messages in Channel 42
 * threads to communicate initialization outcomes.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface SummaryPosterInterface
{
    /**
     * Post initialization summary to Channel 42
     * 
     * Creates a message file in the specified thread with a concise summary
     * of initialization outcomes (max 1000 characters).
     * 
     * @param string $threadPath Path to thread directory
     * @param int $doctrineCount Number of doctrines ingested
     * @param array $dispositionCounts Disposition category counts
     * @param array $risks Array of critical risks or anomalies
     * @param array $nextSteps Array of recommended next steps
     * @return string Path to created message file
     * @throws InitializationException If posting fails
     */
    public function postSummary($threadPath, $doctrineCount, $dispositionCounts, $risks, $nextSteps);
}
