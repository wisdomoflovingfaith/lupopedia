<?php
/**
 * CSS Build Script
 * 
 * Scans /lupo-includes/css/src/ and generates minified CSS files
 * Output: /lupo-includes/css/<foldername>.css
 * 
 * Usage: php build-css.php [family]
 *   If family is specified, only builds that family
 *   Otherwise builds all families
 */

// Set base directory (where this script is located)
$baseDir = __DIR__;
$srcDir = $baseDir . '/src';
$outputDir = $baseDir;

// Auto-generated header
$header = "/*! This file is auto-generated */\n";

/**
 * Simple CSS minifier
 * 
 * Removes comments (except important comments starting with /*!),
 * removes whitespace, and optimizes spacing around CSS syntax.
 * 
 * @param string $css Raw CSS string
 * @return string Minified CSS string
 */
function minifyCSS($css) {
    // Remove comments (but keep /*! important comments */)
    $css = preg_replace('/(?<!\/\*!)\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $css);
    
    // Remove whitespace
    $css = preg_replace('/\s+/', ' ', $css);
    
    // Remove spaces around specific characters
    $css = str_replace([' {', '{ ', ' }', '} ', ' ;', '; ', ' :', ': ', ' ,', ', '], 
                       ['{', '{', '}', '}', ';', ';', ':', ':', ',', ','], $css);
    
    // Remove spaces at start/end
    $css = trim($css);
    
    return $css;
}

/**
 * Build CSS for a specific family
 * 
 * Concatenates all CSS files in a family directory, minifies them,
 * prepends the auto-generated header, and writes to output directory.
 * 
 * @param string $familyDir Full path to the family source directory
 * @param string $familyName Name of the CSS family (used for output filename)
 * @param string $outputDir Directory to write output CSS files
 * @param string $header Header string to prepend to output
 * @return bool True on success, false on failure
 */
function buildFamily($familyDir, $familyName, $outputDir, $header) {
    $cssFiles = glob($familyDir . '/*.css');
    
    if (empty($cssFiles)) {
        echo "No CSS files found in {$familyName}/, skipping...\n";
        return false;
    }
    
    // Sort files for consistent output
    sort($cssFiles);
    
    // Concatenate all CSS files
    $combined = '';
    foreach ($cssFiles as $file) {
        $content = file_get_contents($file);
        if ($content !== false) {
            $combined .= $content . "\n";
        }
    }
    
    // Minify
    $minified = minifyCSS($combined);
    
    // Add header
    $output = $header . $minified . "\n";
    
    // Write to output file
    $outputFile = $outputDir . '/' . $familyName . '.css';
    if (file_put_contents($outputFile, $output) !== false) {
        echo "✓ Generated: {$familyName}.css (" . number_format(strlen($output)) . " bytes)\n";
        return true;
    } else {
        echo "✗ Failed to write: {$familyName}.css\n";
        return false;
    }
}

// Main execution
echo "CSS Build Script\n";
echo "================\n\n";

// Get command line argument (optional family name)
$targetFamily = isset($argv[1]) ? $argv[1] : null;

// Check if src directory exists
if (!is_dir($srcDir)) {
    die("Error: Source directory not found: {$srcDir}\n");
}

// Get all family directories
$families = glob($srcDir . '/*', GLOB_ONLYDIR);

if (empty($families)) {
    die("No family directories found in {$srcDir}\n");
}

$built = 0;
$skipped = 0;

foreach ($families as $familyDir) {
    $familyName = basename($familyDir);
    
    // Skip if we're targeting a specific family
    if ($targetFamily !== null && $familyName !== $targetFamily) {
        continue;
    }
    
    if (buildFamily($familyDir, $familyName, $outputDir, $header)) {
        $built++;
    } else {
        $skipped++;
    }
}

echo "\n";
echo "Build complete: {$built} file(s) generated";
if ($skipped > 0) {
    echo ", {$skipped} skipped";
}
echo "\n";
?>
