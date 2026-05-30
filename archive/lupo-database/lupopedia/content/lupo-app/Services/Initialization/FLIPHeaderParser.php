<?php
/**
 * FLIP Header Parser
 * 
 * Extracts and parses YAML front-matter blocks (FLIP headers) from files.
 * Handles both inline JSON-like syntax and multi-line YAML format.
 * 
 * FLIP headers are delimited by --- at the start of files and contain
 * metadata like actor_id, channel_id, system_version, etc.
 * 
 * Example formats:
 * 
 * Inline JSON-like:
 * ---
 * wolfie.headers: {
 *   file_path_from_root: "example.md",
 *   system_version: "4.0.44"
 * }
 * ---
 * 
 * Multi-line YAML:
 * ---
 * wolfie.headers:
 *   file_path_from_root: "example.md"
 *   system_version: "4.0.44"
 * ---
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class FLIPHeaderParser implements FLIPHeaderParserInterface
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
    public function parse($content)
    {
        if (!$this->hasHeader($content)) {
            return array();
        }
        
        $yamlBlock = $this->extractYamlBlock($content);
        if (empty($yamlBlock)) {
            return array();
        }
        
        return $this->parseYamlBlock($yamlBlock);
    }
    
    /**
     * Check if content has a valid FLIP header
     * 
     * @param string $content File content
     * @return bool True if valid FLIP header exists, false otherwise
     */
    public function hasHeader($content)
    {
        if (empty($content)) {
            return false;
        }
        
        // FLIP headers must start with --- at the beginning of the file
        $trimmed = ltrim($content);
        return strpos($trimmed, '---') === 0;
    }
    
    /**
     * Extract specific field from FLIP header
     * 
     * @param string $content File content
     * @param string $fieldName Field name to extract
     * @param mixed $default Default value if field not found
     * @return mixed Field value or default
     */
    public function getField($content, $fieldName, $default = null)
    {
        $parsed = $this->parse($content);
        
        if (isset($parsed[$fieldName])) {
            return $parsed[$fieldName];
        }
        
        return $default;
    }
    
    /**
     * Extract YAML block from content
     * 
     * @param string $content File content
     * @return string YAML block content between --- delimiters
     */
    private function extractYamlBlock($content)
    {
        $trimmed = ltrim($content);
        
        // Must start with ---
        if (strpos($trimmed, '---') !== 0) {
            return '';
        }
        
        // Find the closing ---
        $start = 3; // After first ---
        $end = strpos($trimmed, '---', $start);
        
        if ($end === false) {
            return '';
        }
        
        return substr($trimmed, $start, $end - $start);
    }
    
    /**
     * Parse YAML block into associative array
     * 
     * Handles both inline JSON-like syntax and multi-line YAML.
     * This is a simplified parser that handles the common FLIP header patterns.
     * 
     * @param string $yamlBlock YAML content
     * @return array Parsed fields
     */
    /**
     * Parse YAML block into associative array
     * 
     * Handles both inline JSON-like syntax and multi-line YAML.
     * This is a simplified parser that handles the common FLIP header patterns.
     * 
     * @param string $yamlBlock YAML content
     * @return array Parsed fields
     */
    private function parseYamlBlock($yamlBlock)
    {
        $result = array();
        $lines = explode("\n", trim($yamlBlock));

        $currentKey = null;
        $inInlineObject = false;
        $inlineBuffer = '';
        $braceDepth = 0;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            // Handle inline JSON-like objects: key: { ... }
            if (preg_match('/^([a-zA-Z0-9_\.]+):\s*\{(.*)$/', $line, $matches)) {
                $currentKey = $matches[1];
                $inlineBuffer = '{' . $matches[2];

                // Count braces to handle nested objects
                $braceDepth = 1;
                for ($i = 0; $i < strlen($matches[2]); $i++) {
                    if ($matches[2][$i] === '{') {
                        $braceDepth++;
                    } elseif ($matches[2][$i] === '}') {
                        $braceDepth--;
                    }
                }

                // Check if object closes on same line
                if ($braceDepth === 0) {
                    $result[$currentKey] = $this->parseInlineObject($inlineBuffer);
                    $currentKey = null;
                    $inInlineObject = false;
                    $inlineBuffer = '';
                } else {
                    $inInlineObject = true;
                }
                continue;
            }

            // Continue collecting inline object
            if ($inInlineObject) {
                $inlineBuffer .= ' ' . $line;

                // Count braces
                for ($i = 0; $i < strlen($line); $i++) {
                    if ($line[$i] === '{') {
                        $braceDepth++;
                    } elseif ($line[$i] === '}') {
                        $braceDepth--;
                    }
                }

                if ($braceDepth === 0) {
                    $result[$currentKey] = $this->parseInlineObject($inlineBuffer);
                    $currentKey = null;
                    $inInlineObject = false;
                    $inlineBuffer = '';
                }
                continue;
            }

            // Handle simple key: value pairs
            if (preg_match('/^([a-zA-Z0-9_\.]+):\s*(.*)$/', $line, $matches)) {
                $key = $matches[1];
                $value = trim($matches[2]);

                // Handle quoted strings
                if (preg_match('/^["\'](.+)["\']$/', $value, $valueMatches)) {
                    $value = $valueMatches[1];
                }

                // Skip if value is empty (might be multi-line)
                if (empty($value)) {
                    continue;
                }

                $result[$key] = $this->castValue($value);
                continue;
            }
        }

        return $result;
    }

    
    /**
     * Parse inline JSON-like object syntax
     * 
     * Handles: { key: value, key2: value2 }
     * 
     * @param string $inline Inline object string
     * @return array Parsed object
     */
    private function parseInlineObject($inline)
    {
        $result = array();
        
        // Remove opening and closing braces
        $inline = str_replace('{', '', $inline);
        $inline = str_replace('}', '', $inline);
        $inline = trim($inline);
        
        if (empty($inline)) {
            return $result;
        }
        
        // Split by comma, but be careful with nested structures
        $parts = $this->splitByComma($inline);
        
        foreach ($parts as $part) {
            $part = trim($part);
            
            if (empty($part)) {
                continue;
            }
            
            // Handle key: value
            if (strpos($part, ':') !== false) {
                list($key, $value) = explode(':', $part, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove quotes
                if (preg_match('/^["\'](.+)["\']$/', $value, $matches)) {
                    $value = $matches[1];
                }
                
                $result[$key] = $this->castValue($value);
            }
        }
        
        return $result;
    }
    
    /**
     * Split string by comma, respecting nested structures
     * 
     * @param string $str String to split
     * @return array Parts
     */
    private function splitByComma($str)
    {
        $parts = array();
        $current = '';
        $depth = 0;
        $inString = false;
        $stringChar = null;
        
        for ($i = 0; $i < strlen($str); $i++) {
            $char = $str[$i];
            
            // Handle string delimiters
            if (($char === '"' || $char === "'") && ($i === 0 || $str[$i - 1] !== '\\')) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar) {
                    $inString = false;
                    $stringChar = null;
                }
                $current .= $char;
                continue;
            }
            
            if ($inString) {
                $current .= $char;
                continue;
            }
            
            // Track nesting depth
            if ($char === '[' || $char === '{') {
                $depth++;
            } elseif ($char === ']' || $char === '}') {
                $depth--;
            }
            
            // Split on comma only at depth 0
            if ($char === ',' && $depth === 0) {
                $parts[] = $current;
                $current = '';
            } else {
                $current .= $char;
            }
        }
        
        if (!empty($current)) {
            $parts[] = $current;
        }
        
        return $parts;
    }
    
    /**
     * Cast string value to appropriate type
     * 
     * @param string $value String value
     * @return mixed Casted value
     */
    private function castValue($value)
    {
        if (empty($value)) {
            return $value;
        }
        
        // Boolean
        if ($value === 'true') {
            return true;
        }
        if ($value === 'false') {
            return false;
        }
        
        // Null
        if ($value === 'null') {
            return null;
        }
        
        // Integer
        if (preg_match('/^-?\d+$/', $value)) {
            return (int)$value;
        }
        
        // Float
        if (preg_match('/^-?\d+\.\d+$/', $value)) {
            return (float)$value;
        }
        
        return $value;
    }
}
