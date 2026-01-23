<?php
/**
 * Update WOLFIE Headers to 4.1.6 Standard
 * 
 * This script updates all files with old header versions to 4.1.6
 * and adds required header fields based on current standards.
 * 
 * Required updates:
 * - file.last_modified_system_version: 4.1.6
 * - header_atoms: GLOBAL_CURRENT_LUPOPEDIA_VERSION, GLOBAL_CURRENT_AUTHORS
 * - dialog.mood_RGB (not mood)
 * - Remove/simplify Wheeler Mode complexity
 */

$root = __DIR__ . '/..';
$updated_count = 0;
$errors = [];

// Patterns to find and update
$old_version_pattern = '/file\.last_modified_system_version:\s*4\.(0\.|1\.[0-5])/';
$mood_pattern = '/mood:\s*"/';
$mood_rgb_pattern = '/mood_RGB:\s*"/';

// Required header fields for 4.1.6
$required_atoms = [
    'GLOBAL_CURRENT_LUPOPEDIA_VERSION',
    'GLOBAL_CURRENT_AUTHORS'
];

function updateFileHeader($filepath) {
    global $updated_count, $errors;
    
    $content = file_get_contents($filepath);
    if ($content === false) {
        $errors[] = "Could not read: $filepath";
        return false;
    }
    
    $original = $content;
    $updated = false;
    
    // Update version to 4.1.6
    if (preg_match('/file\.last_modified_system_version:\s*4\.(0\.|1\.[0-5])/', $content)) {
        $content = preg_replace(
            '/file\.last_modified_system_version:\s*4\.\d+\.\d+/',
            'file.last_modified_system_version: 4.1.6',
            $content
        );
        $updated = true;
    }
    
    // Fix mood -> mood_RGB
    if (preg_match('/mood:\s*"/', $content) && !preg_match('/mood_RGB:/', $content)) {
        $content = preg_replace('/mood:\s*"/', 'mood_RGB: "', $content);
        $updated = true;
    }
    
    // Ensure header_atoms includes required atoms
    if (preg_match('/^---\s*\nwolfie\.headers:/m', $content)) {
        // Check if header_atoms exists
        if (!preg_match('/header_atoms:/', $content)) {
            // Add header_atoms after file.last_modified_system_version
            $content = preg_replace(
                '/(file\.last_modified_system_version:\s*4\.1\.6)\n/',
                "$1\nheader_atoms:\n  - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n  - GLOBAL_CURRENT_AUTHORS\n",
                $content
            );
            $updated = true;
        } else {
            // Ensure required atoms are present
            if (!preg_match('/GLOBAL_CURRENT_LUPOPEDIA_VERSION/', $content)) {
                $content = preg_replace(
                    '/(header_atoms:\s*\n(?:\s*-\s*[^\n]+\n)*)/',
                    "$1  - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n",
                    $content
                );
                $updated = true;
            }
            if (!preg_match('/GLOBAL_CURRENT_AUTHORS/', $content)) {
                $content = preg_replace(
                    '/(header_atoms:\s*\n(?:\s*-\s*[^\n]+\n)*)/',
                    "$1  - GLOBAL_CURRENT_AUTHORS\n",
                    $content
                );
                $updated = true;
            }
        }
    }
    
    // Simplify Wheeler Mode - remove complex quantum stuff
    if (preg_match('/wheeler_mode:/', $content)) {
        // Keep it simple - just mark if it was created during emergent architecture
        $content = preg_replace(
            '/wheeler_mode:\s*\n\s*active:\s*(true|false)\s*\n\s*reason:\s*"[^"]*"\s*\n\s*notes:\s*\n(?:\s*-\s*"[^"]*"\s*\n)*/',
            "wheeler_mode:\n  active: $1\n  reason: \"File created during emergent architecture discovery\"\n",
            $content
        );
        $updated = true;
    }
    
    if ($updated && $content !== $original) {
        if (file_put_contents($filepath, $content) !== false) {
            $updated_count++;
            echo "✅ Updated: $filepath\n";
            return true;
        } else {
            $errors[] = "Could not write: $filepath";
            return false;
        }
    }
    
    return false;
}

// Find all markdown files
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'md') {
        $filepath = $file->getRealPath();
        // Skip certain directories
        if (strpos($filepath, '/node_modules/') !== false ||
            strpos($filepath, '/.git/') !== false ||
            strpos($filepath, '/vendor/') !== false) {
            continue;
        }
        
        updateFileHeader($filepath);
    }
}

echo "\n📊 Summary:\n";
echo "Files updated: $updated_count\n";
if (!empty($errors)) {
    echo "Errors: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}
