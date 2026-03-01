<?php
/**
 * Interface for parsing FLIP headers from files
 * 
 * Defines the contract for extracting and parsing YAML front-matter
 * FLIP headers from Markdown and other text files.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface FLIPHeaderParserInterface
{
    /**
     * Parse FLIP header from file content
     * 
     * Extracts YAML front-matter block and parses it into structured data.
     * Handles malformed or missing headers gracefully.
     * 
     * @param string $content File content
     * @return array Parsed FLIP header fields, or empty array if no header
     */
    public function parse($content);
    
    /**
     * Check if content has a valid FLIP header
     * 
     * @param string $content File content
     * @return bool True if valid FLIP header exists, false otherwise
     */
    public function hasHeader($content);
    
    /**
     * Extract specific field from FLIP header
     * 
     * @param string $content File content
     * @param string $fieldName Field name to extract
     * @param mixed $default Default value if field not found
     * @return mixed Field value or default
     */
    public function getField($content, $fieldName, $default);
}
