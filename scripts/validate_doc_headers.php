<?php
/**
 * Validate Documentation Headers
 * 
 * Validates that documentation files referencing Captain Wolfie
 * have the architect field in their headers.
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

$total = 0;
$withCaptainWolfie = 0;
$withArchitectField = 0;
$missingArchitect = [];

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'md') {
        $total++;
        $content = file_get_contents($file->getRealPath());
        
        // Check if file references Captain Wolfie
        $normalizedPath = str_replace('\\', '/', $file->getRealPath());
        if (strpos($content, 'Captain Wolfie') !== false) {
            $withCaptainWolfie++;
            
            // Check if file has valid YAML frontmatter with architect field
            if (preg_match('/^---[\s\S]*?---/m', $content)) {
                // Extract frontmatter and check for architect field
                if (preg_match('/architect:\s*Captain Wolfie/m', $content)) {
                    $withArchitectField++;
                } else {
                    $missingArchitect[] = $normalizedPath;
                }
            } else {
                $missingArchitect[] = $normalizedPath;
            }
        }
    }
}

echo "\n";
echo "========================================\n";
echo "Documentation Header Validation\n";
echo "========================================\n\n";
echo "Total documentation files: $total\n";
echo "Files referencing Captain Wolfie: $withCaptainWolfie\n";
echo "Files with architect field: $withArchitectField\n";
echo "Files missing architect field: " . count($missingArchitect) . "\n";

if (!empty($missingArchitect)) {
    echo "\nFiles missing architect field:\n";
    foreach (array_slice($missingArchitect, 0, 10) as $file) {
        echo "  - $file\n";
    }
    if (count($missingArchitect) > 10) {
        echo "  ... and " . (count($missingArchitect) - 10) . " more\n";
    }
}

$passed = empty($missingArchitect);
echo "\n";
echo "Validation: " . ($passed ? "✓ PASSED" : "✗ FAILED") . "\n";
echo "\n";

exit($passed ? 0 : 1);
