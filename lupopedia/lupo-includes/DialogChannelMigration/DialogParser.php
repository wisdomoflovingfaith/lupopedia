<?php
/**
 * Dialog Parser for Big Rock 2: Dialog Channel Migration
 * 
 * Extracts WOLFIE headers and dialog blocks from .md files for database migration.
 * Handles YAML frontmatter parsing and message extraction with validation.
 * 
 * @package Lupopedia
 * @subpackage DialogChannelMigration
 * @version 4.0.102
 * @author Captain Wolfie
 */

class DialogParser {
    
    private $errors = [];
    private $warnings = [];
    
    /**
     * Parse a dialog file and extract metadata and messages
     * 
     * @param string $filePath Path to the .md dialog file
     * @return array Parsed data with metadata and messages
     * @throws Exception If file cannot be read or parsed
     */
    public function parseDialogFile($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("Dialog file not found: {$filePath}");
        }
        
        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new Exception("Cannot read dialog file: {$filePath}");
        }
        
        $result = [
            'file_path' => $filePath,
            'file_name' => basename($filePath),
            'metadata' => [],
            'messages' => [],
            'errors' => [],
            'warnings' => []
        ];
        
        // Extract WOLFIE header
        $headerData = $this->extractWolfieHeader($content, $filePath);
        $result['metadata'] = $headerData;
        
        // Extract dialog messages
        $messages = $this->extractDialogMessages($content, $filePath);
        $result['messages'] = $messages;
        
        // Add any parsing errors/warnings
        $result['errors'] = $this->errors;
        $result['warnings'] = $this->warnings;
        
        return $result;
    }
    
    /**
     * Extract WOLFIE header from file content
     * 
     * @param string $content File content
     * @param string $filePath File path for error reporting
     * @return array Parsed header metadata
     */
    private function extractWolfieHeader($content, $filePath) {
        $metadata = [
            'title' => null,
            'description' => null,
            'speaker' => null,
            'target' => null,
            'mood_rgb' => null,
            'categories' => [],
            'collections' => [],
            'channels' => [],
            'tags' => [],
            'version' => null,
            'status' => 'published',
            'author' => null,
            'raw_header' => null
        ];
        
        // Check for YAML frontmatter
        if (!preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
            $this->warnings[] = "No WOLFIE header found in {$filePath}";
            return $metadata;
        }
        
        $yamlContent = $matches[1];
        $metadata['raw_header'] = $yamlContent;
        
        try {
            // Parse YAML manually (simple key-value extraction)
            $lines = explode("\n", $yamlContent);
            $currentSection = null;
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || $line[0] === '#') continue;
                
                // Handle nested sections
                if (preg_match('/^(\w+):$/', $line, $matches)) {
                    $currentSection = $matches[1];
                    continue;
                }
                
                // Handle key-value pairs
                if (preg_match('/^(\w+):\s*(.*)$/', $line, $matches)) {
                    $key = $matches[1];
                    $value = trim($matches[2], '"\'');
                    
                    if ($currentSection === 'dialog') {
                        if ($key === 'speaker') $metadata['speaker'] = $value;
                        if ($key === 'target') $metadata['target'] = $value;
                        if ($key === 'mood_RGB') $metadata['mood_rgb'] = $value;
                    } elseif ($currentSection === 'file') {
                        if ($key === 'title') $metadata['title'] = $value;
                        if ($key === 'description') $metadata['description'] = $value;
                        if ($key === 'version') $metadata['version'] = $value;
                        if ($key === 'status') $metadata['status'] = $value;
                        if ($key === 'author') $metadata['author'] = $value;
                    } elseif ($currentSection === 'tags') {
                        if ($key === 'categories') {
                            $metadata['categories'] = $this->parseJsonArray($value);
                        }
                        if ($key === 'collections') {
                            $metadata['collections'] = $this->parseJsonArray($value);
                        }
                        if ($key === 'channels') {
                            $metadata['channels'] = $this->parseJsonArray($value);
                        }
                    }
                }
            }
            
        } catch (Exception $e) {
            $this->errors[] = "YAML parsing error in {$filePath}: " . $e->getMessage();
        }
        
        return $metadata;
    }
    
    /**
     * Extract dialog messages from file content
     * 
     * @param string $content File content
     * @param string $filePath File path for error reporting
     * @return array Array of parsed messages
     */
    private function extractDialogMessages($content, $filePath) {
        $messages = [];
        
        // Remove WOLFIE header from content
        $content = preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', $content);
        
        // Split content into potential message blocks
        $blocks = preg_split('/\n\s*\n/', $content);
        $messageOrder = 1;
        
        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;
            
            // Look for dialog patterns
            $message = $this->parseMessageBlock($block, $messageOrder, $filePath);
            if ($message) {
                $messages[] = $message;
                $messageOrder++;
            }
        }
        
        return $messages;
    }
    
    /**
     * Parse individual message block
     * 
     * @param string $block Text block to parse
     * @param int $order Message order
     * @param string $filePath File path for error reporting
     * @return array|null Parsed message or null if not a message
     */
    private function parseMessageBlock($block, $order, $filePath) {
        $message = [
            'message_order' => $order,
            'speaker' => null,
            'target' => null,
            'message_text' => '',
            'mood_rgb' => null,
            'timestamp' => null,
            'message_type' => 'dialog',
            'thread_id' => null,
            'metadata' => []
        ];
        
        // Pattern 1: **Speaker:** format
        if (preg_match('/\*\*Speaker:\*\*\s*(.+?)\s*\n\*\*Target:\*\*\s*(.+?)\s*\n\*\*Message:\*\*\s*"(.+?)"/s', $block, $matches)) {
            $message['speaker'] = trim($matches[1]);
            $message['target'] = trim($matches[2]);
            $message['message_text'] = trim($matches[3]);
            
            // Check for mood
            if (preg_match('/\*\*Mood:\*\*\s*"([^"]+)"/', $block, $moodMatches)) {
                $message['mood_rgb'] = $moodMatches[1];
            }
            
            return $message;
        }
        
        // Pattern 2: Simple Speaker: format
        if (preg_match('/^(.+?):\s*(.+)$/s', $block, $matches)) {
            $speaker = trim($matches[1]);
            $text = trim($matches[2]);
            
            // Skip if it looks like a header or metadata
            if (in_array(strtolower($speaker), ['date', 'type', 'summary', 'changes', 'files', 'status'])) {
                return null;
            }
            
            $message['speaker'] = $speaker;
            $message['message_text'] = $text;
            return $message;
        }
        
        // Pattern 3: Changelog entry format
        if (preg_match('/^##\s+(.+?)\s*\n\*\*Speaker:\*\*\s*(.+?)\s*\n\*\*Target:\*\*\s*(.+?)\s*\n\*\*Message:\*\*\s*"(.+?)"/s', $block, $matches)) {
            $message['speaker'] = trim($matches[2]);
            $message['target'] = trim($matches[3]);
            $message['message_text'] = trim($matches[4]);
            $message['metadata']['section_title'] = trim($matches[1]);
            return $message;
        }
        
        return null;
    }
    
    /**
     * Parse JSON array from YAML value
     * 
     * @param string $value YAML array value
     * @return array Parsed array
     */
    private function parseJsonArray($value) {
        // Handle JSON format
        if (preg_match('/^\[.*\]$/', $value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        // Handle YAML array format
        if (strpos($value, ',') !== false) {
            return array_map('trim', explode(',', $value));
        }
        
        // Single value
        return empty($value) ? [] : [$value];
    }
    
    /**
     * Validate parsed message data
     * 
     * @param array $message Message data
     * @return array Validation errors
     */
    public function validateMessage($message) {
        $errors = [];
        
        if (empty($message['speaker'])) {
            $errors[] = "Missing speaker";
        }
        
        if (empty($message['message_text'])) {
            $errors[] = "Missing message text";
        }
        
        if (strlen($message['message_text']) > 272) {
            $errors[] = "Message text exceeds 272 character limit (" . strlen($message['message_text']) . " chars)";
        }
        
        if (!empty($message['mood_rgb']) && !preg_match('/^#?[0-9A-Fa-f]{6}$/', $message['mood_rgb'])) {
            $errors[] = "Invalid mood_rgb format";
        }
        
        return $errors;
    }
    
    /**
     * Get parsing errors
     * 
     * @return array Array of error messages
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get parsing warnings
     * 
     * @return array Array of warning messages
     */
    public function getWarnings() {
        return $this->warnings;
    }
    
    /**
     * Clear errors and warnings
     */
    public function clearMessages() {
        $this->errors = [];
        $this->warnings = [];
    }
}