<?php
/**
 * Interface for version extraction and classification
 * 
 * Defines the contract for extracting version information from files
 * and classifying files based on version relevance.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface VersionClassifierInterface
{
    /**
     * Extract version from file content
     * 
     * Attempts to extract version from FLIP header or content.
     * Returns null if no version found.
     * 
     * @param string $content File content
     * @return string|null Version string (e.g., "4.0.44") or null
     */
    public function extractVersion($content);
    
    /**
     * Classify file based on version
     * 
     * Applies classification rules:
     * - 4.0.42 or later: retain
     * - 4.0.35 through 4.0.41: archive
     * - 4.0.34 or earlier: deprecate
     * - No version: retain (default)
     * 
     * @param string|null $version Version string or null
     * @return string Disposition: "retain", "archive", or "deprecate"
     */
    public function classifyFile($version);
    
    /**
     * Get rationale for classification
     * 
     * @param string|null $version Version string or null
     * @param string $disposition Classification result
     * @return string Human-readable rationale
     */
    public function getRationale($version, $disposition);
}
