<?php
/**
 * Update Dialog Headers for Identity Propagation
 * 
 * Updates all dialog files to use "Captain_Wolfie" as speaker
 * and adds author_type: human where needed.
 * 
 * @package Lupopedia
 * @version 4.0.20
 * @author Captain Wolfie
 */

$dialogsDir = __DIR__ . '/../dialogs';
$files = glob($dialogsDir . '/*.md');

if (empty($files)) {
    echo "No dialog files found in $dialogsDir\n";
    exit(1);
}

$updated = 0;
$errors = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // Replace WOLFIE, Wolfie, or Eric with Captain_Wolfie in speaker field
    $content = preg_replace(
        '/speaker:\s*(WOLFIE|Wolfie|Eric)\b/i',
        'speaker: Captain_Wolfie',
        $content
    );
    
    // Add author_type: human if Captain_Wolfie is speaker and author_type is missing
    if (strpos($content, 'speaker: Captain_Wolfie') !== false) {
        if (strpos($content, 'author_type:') === false) {
            // Insert author_type after speaker line
            $content = preg_replace(
                '/(speaker:\s*Captain_Wolfie\n)/',
                "$1  author_type: human\n",
                $content
            );
        }
    }
    
    // Only write if content changed
    if ($content !== $originalContent) {
        if (file_put_contents($file, $content)) {
            $updated++;
            echo "✓ Updated: " . basename($file) . "\n";
        } else {
            $errors++;
            echo "✗ Error updating: " . basename($file) . "\n";
        }
    }
}

echo "\n";
echo "========================================\n";
echo "Dialog Header Update Complete\n";
echo "========================================\n";
echo "Files updated: $updated\n";
echo "Errors: $errors\n";
echo "Total files processed: " . count($files) . "\n";
