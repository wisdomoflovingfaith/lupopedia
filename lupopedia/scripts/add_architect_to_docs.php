<?php
/**
 * Add Architect Field to Documentation Headers
 * 
 * Adds architect: Captain Wolfie field to documentation files that reference
 * Captain Wolfie but don't have the architect field.
 * 
 * @package Lupopedia
 * @version 4.0.20
 * @author Captain Wolfie
 */

$docsDir = __DIR__ . '/../docs';
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($docsDir),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$updated = 0;
$skipped = 0;
$errors = 0;

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'md') {
        $filePath = $file->getRealPath();
        $content = file_get_contents($filePath);
        $originalContent = $content;
        
        // Only process files that reference Captain Wolfie
        if (strpos($content, 'Captain Wolfie') === false) {
            $skipped++;
            continue;
        }
        
        // Check if architect field already exists
        if (preg_match('/^architect:\s*Captain Wolfie/m', $content)) {
            $skipped++;
            continue;
        }
        
        // Check if file has YAML frontmatter (starts with ---)
        if (!preg_match('/^---\s*\n/', $content)) {
            // No frontmatter, skip
            $skipped++;
            continue;
        }
        
        // Find author field and add architect after it
        if (preg_match('/^author:\s*(.+)$/m', $content, $matches)) {
            // Add architect field after author
            $content = preg_replace(
                '/^(author:\s*.+)$/m',
                "$1\narchitect: Captain Wolfie",
                $content,
                1
            );
        } elseif (preg_match('/^file:\s*\n/', $content)) {
            // Add architect in file section
            $content = preg_replace(
                '/^(file:\s*\n)/m',
                "$1  architect: Captain Wolfie\n",
                $content,
                1
            );
        } else {
            // Add after first --- if no author field found
            $content = preg_replace(
                '/^(---\s*\n)/',
                "$1architect: Captain Wolfie\n",
                $content,
                1
            );
        }
        
        // Only write if content changed
        if ($content !== $originalContent) {
            if (file_put_contents($filePath, $content)) {
                $updated++;
                echo "✓ Updated: " . str_replace(__DIR__ . '/../', '', $filePath) . "\n";
            } else {
                $errors++;
                echo "✗ Error updating: " . str_replace(__DIR__ . '/../', '', $filePath) . "\n";
            }
        } else {
            $skipped++;
        }
    }
}

echo "\n";
echo "========================================\n";
echo "Documentation Architect Field Update\n";
echo "========================================\n";
echo "Files updated: $updated\n";
echo "Files skipped: $skipped\n";
echo "Errors: $errors\n";
echo "Total files processed: " . ($updated + $skipped + $errors) . "\n";
