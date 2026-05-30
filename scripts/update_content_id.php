<?php
/**
 * Update file headers with content_id after database insertion
 * This creates a bidirectional link between filesystem and lupo_contents table
 */

/**
 * Update a file's header with content_id after successful database insertion
 * @param string $file_path Path to the file to update
 * @param int $content_id The content_id from lupo_contents table
 * @return bool Success status
 */
function updateFileWithContentId($file_path, $content_id) {
    if (!file_exists($file_path)) {
        echo "ERROR: File not found: $file_path\n";
        return false;
    }
    
    $content = file_get_contents($file_path);
    
    // Check if content_id already exists
    if (strpos($content, 'content_id:') !== false) {
        // Replace existing content_id
        $content = preg_replace(
            '/content_id:\s*\d+/',
            "content_id: $content_id",
            $content
        );
    } else {
        // Add content_id after file_path_from_root or at start of headers block
        $content = preg_replace(
            '/(file_path_from_root:[^\n]+)/',
            "$1\n  content_id: $content_id",
            $content
        );
        
        // If that didn't work, add after lupopedia.headers
        if (strpos($content, 'content_id:') === false) {
            $content = preg_replace(
                '/(lupopedia\.headers:[^\n]+)/',
                "$1\n  content_id: $content_id",
                $content
            );
        }
    }
    
    // Update the file
    if (file_put_contents($file_path, $content) === false) {
        echo "ERROR: Could not update file: $file_path\n";
        return false;
    }
    
    echo "SUCCESS: Updated $file_path with content_id: $content_id\n";
    return true;
}

// If run directly, handle command line args
if (php_sapi_name() === 'cli' && isset($argv[1]) && isset($argv[2])) {
    updateFileWithContentId($argv[1], intval($argv[2]));
    exit(0);
}

?>
