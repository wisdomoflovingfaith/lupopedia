<?php
/**
 * Version Classifier
 * 
 * Extracts version information from files and classifies them based on
 * version relevance rules. Determines whether files should be retained,
 * archived, or deprecated based on their system_version.
 * 
 * Classification Rules:
 * - Version 4.0.42 or later: retain (current development cycle)
 * - Version 4.0.35 through 4.0.41: archive (recent but not current)
 * - Version 4.0.34 or earlier: deprecate (legacy)
 * - No version metadata: retain (default, assume current)
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class VersionClassifier implements VersionClassifierInterface
{
    /**
     * FLIP header parser instance
     * 
     * @var FLIPHeaderParserInterface
     */
    private $parser;
    
    /**
     * Constructor
     * 
     * @param FLIPHeaderParserInterface $parser FLIP header parser
     */
    public function __construct(FLIPHeaderParserInterface $parser)
    {
        $this->parser = $parser;
    }
    
    /**
     * Extract version from file content
     * 
     * Attempts to extract version from FLIP header or content.
     * Returns null if no version found.
     * 
     * Priority:
     * 1. system_version from FLIP header
     * 2. Pattern matching in content (e.g., "version 4.0.44", "v4.0.44")
     * 
     * @param string $content File content
     * @return string|null Version string (e.g., "4.0.44") or null
     */
    public function extractVersion($content)
    {
        if (empty($content)) {
            return null;
        }
        
        // Try to extract from FLIP header first
        $version = $this->extractVersionFromHeader($content);
        if ($version !== null) {
            return $version;
        }
        
        // Fall back to content pattern matching
        return $this->extractVersionFromContent($content);
    }
    
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
    public function classifyFile($version)
    {
        // Default to retain if no version
        if ($version === null || $version === '') {
            return 'retain';
        }
        
        // Parse version into comparable format
        $versionNumber = $this->parseVersionNumber($version);
        if ($versionNumber === null) {
            // If we can't parse it, default to retain
            return 'retain';
        }
        
        // Apply classification rules
        if ($versionNumber >= 40042) {
            return 'retain';
        } elseif ($versionNumber >= 40035) {
            return 'archive';
        } else {
            return 'deprecate';
        }
    }
    
    /**
     * Get rationale for classification
     * 
     * @param string|null $version Version string or null
     * @param string $disposition Classification result
     * @return string Human-readable rationale
     */
    public function getRationale($version, $disposition)
    {
        if ($version === null || $version === '') {
            return 'No version metadata found; defaulting to retain';
        }
        
        switch ($disposition) {
            case 'retain':
                return 'Version ' . $version . ' is current (4.0.42+)';
            case 'archive':
                return 'Version ' . $version . ' is recent but not current (4.0.35-4.0.41)';
            case 'deprecate':
                return 'Version ' . $version . ' is legacy (4.0.34 or earlier)';
            default:
                return 'Unknown disposition';
        }
    }
    
    /**
     * Extract version from FLIP header
     * 
     * @param string $content File content
     * @return string|null Version string or null
     */
    private function extractVersionFromHeader($content)
    {
        // Check if content has a FLIP header
        if (!$this->parser->hasHeader($content)) {
            return null;
        }
        
        // Parse the header
        $parsed = $this->parser->parse($content);
        
        // Look for system_version in various locations
        // Direct field
        if (isset($parsed['system_version'])) {
            return $this->normalizeVersion($parsed['system_version']);
        }
        
        // Inside wolfie.headers
        if (isset($parsed['wolfie.headers']) && is_array($parsed['wolfie.headers'])) {
            if (isset($parsed['wolfie.headers']['system_version'])) {
                return $this->normalizeVersion($parsed['wolfie.headers']['system_version']);
            }
        }
        
        // Inside flip.footer
        if (isset($parsed['flip.footer']) && is_array($parsed['flip.footer'])) {
            if (isset($parsed['flip.footer']['system_version'])) {
                return $this->normalizeVersion($parsed['flip.footer']['system_version']);
            }
        }
        
        return null;
    }
    
    /**
     * Extract version from content using pattern matching
     * 
     * Looks for patterns like:
     * - "version 4.0.44"
     * - "v4.0.44"
     * - "4.0.44"
     * 
     * @param string $content File content
     * @return string|null Version string or null
     */
    private function extractVersionFromContent($content)
    {
        // Pattern: version 4.0.44 or v4.0.44
        if (preg_match('/\b(?:version|v)\s*(\d+\.\d+\.\d+)\b/i', $content, $matches)) {
            return $this->normalizeVersion($matches[1]);
        }
        
        // Pattern: standalone version number (4.0.44)
        // Be more restrictive to avoid false positives
        if (preg_match('/\b(4\.0\.\d+)\b/', $content, $matches)) {
            return $this->normalizeVersion($matches[1]);
        }
        
        return null;
    }
    
    /**
     * Normalize version string
     * 
     * Ensures version is in X.Y.Z format.
     * 
     * @param mixed $version Version value (string or number)
     * @return string|null Normalized version or null
     */
    private function normalizeVersion($version)
    {
        if ($version === null) {
            return null;
        }
        
        // Convert to string
        $version = (string)$version;
        $version = trim($version);
        
        if (empty($version)) {
            return null;
        }
        
        // Remove leading 'v' if present
        if (strpos($version, 'v') === 0 || strpos($version, 'V') === 0) {
            $version = substr($version, 1);
        }
        
        // Validate format: X.Y.Z
        if (preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            return $version;
        }
        
        return null;
    }
    
    /**
     * Parse version string into comparable integer
     * 
     * Converts "4.0.44" to 40044 for easy comparison.
     * 
     * @param string $version Version string (e.g., "4.0.44")
     * @return int|null Version number or null if invalid
     */
    private function parseVersionNumber($version)
    {
        if ($version === null || $version === '') {
            return null;
        }
        
        // Split by dots
        $parts = explode('.', $version);
        
        if (count($parts) !== 3) {
            return null;
        }
        
        // Validate each part is numeric
        foreach ($parts as $part) {
            if (!is_numeric($part)) {
                return null;
            }
        }
        
        // Convert to integer: major * 10000 + minor * 1000 + patch
        $major = (int)$parts[0];
        $minor = (int)$parts[1];
        $patch = (int)$parts[2];
        
        return ($major * 10000) + ($minor * 1000) + $patch;
    }
}
