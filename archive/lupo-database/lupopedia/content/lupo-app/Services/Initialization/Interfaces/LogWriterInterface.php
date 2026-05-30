<?php
/**
 * Interface for writing system initialization logs
 * 
 * Defines the contract for creating detailed system logs documenting
 * all initialization activities with complete audit trail.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface LogWriterInterface
{
    /**
     * Write detailed system initialization log
     * 
     * Creates a comprehensive log file with FLIP header, timestamps,
     * activity listings, anomalies, and checksums.
     * 
     * @param string $outputPath Path where log should be written
     * @param string $startTime Initialization start timestamp (YYYYMMDDHHMMSS)
     * @param string $endTime Initialization end timestamp (YYYYMMDDHHMMSS)
     * @param array $channelsScanned Array of channel scan data
     * @param array $threadsCreated Array of thread creation data
     * @param array $doctrinesLoaded Array of doctrine metadata
     * @param array $filesAudited Array of file audit data
     * @param array $anomalies Array of anomaly descriptions
     * @param array $checksums Array of file checksums
     * @return string Path to created log file
     * @throws InitializationException If log writing fails
     */
    public function writeLog($outputPath, $startTime, $endTime, $channelsScanned, $threadsCreated, $doctrinesLoaded, $filesAudited, $anomalies, $checksums);
}
