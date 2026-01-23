<?php
/**
 * Fix all include paths in Lupopedia codebase
 * Replaces relative includes with absolute paths using __DIR__
 */

function fix_includes_in_file($file_path) {
    if (!file_exists($file_path)) {
        echo "File not found: $file_path\n";
        return false;
    }
    
    $content = file_get_contents($file_path);
    if ($content === false) {
        echo "Could not read file: $file_path\n";
        return false;
    }
    
    $original_content = $content;
    $changes = 0;
    
    // Pattern to match require_once/include_once with LUPO_INCLUDES_DIR
    $patterns = [
        '/require_once\s*\(\s*LUPO_INCLUDES_DIR\s*\.\/([\'"])([^\'"]+)\1\s*\)/',
        '/include_once\s*\(\s*LUPO_INCLUDES_DIR\s*\.\/([\'"])([^\'"]+)\1\s*\)/',
        '/require_once\s*LUPO_INCLUDES_DIR\s*\.\/([\'"])([^\'"]+)\1\s*;/',
        '/include_once\s*LUPO_INCLUDES_DIR\s*\.\/([\'"])([^\'"]+)\1\s*;/'
    ];
    
    foreach ($patterns as $pattern) {
        $content = preg_replace_callback(
            $pattern,
            function($matches) {
                $relative_path = $matches[2];
                $absolute_path = '__DIR__ . DIRECTORY_SEPARATOR . \'' . $relative_path . '\'';
                return str_replace('LUPO_INCLUDES_DIR . \'/{$relative_path}\'', $absolute_path, $matches[0]);
            },
            $content,
            -1,
            $count
        );
        $changes += $count;
    }
    
    // Special case for functions directory
    $content = preg_replace_callback(
        '/require_once\s*\(\s*LUPO_INCLUDES_DIR\s*\.\/([\'"])functions\/([^\'"]+)\1\s*\)/',
        function($matches) {
            $function_file = $matches[2];
            return 'require_once(__DIR__ . DIRECTORY_SEPARATOR . \'' . $function_file . '\')';
        },
        $content,
        -1,
        $count
    );
    $changes += $count;
    
    if ($content !== $original_content) {
        if (file_put_contents($file_path, $content) !== false) {
            echo "Fixed $changes includes in: $file_path\n";
            return true;
        } else {
            echo "Could not write to file: $file_path\n";
            return false;
        }
    }
    
    return false;
}

// Find all PHP files in the lupo-includes directory
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(__DIR__ . '/lupo-includes'),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$files_fixed = 0;
$total_files = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $total_files++;
        if (fix_includes_in_file($file->getPathname())) {
            $files_fixed++;
        }
    }
}

echo "\nSummary:\n";
echo "Total PHP files processed: $total_files\n";
echo "Files with fixes applied: $files_fixed\n";

?>
