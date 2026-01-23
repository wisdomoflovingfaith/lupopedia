<?php
/**
 * Map all documentation files from actual directories
 * 
 * Scans documentation directories and extracts:
 * - filename (from disk)
 * - slug (derived from filename)
 * - title (from first H1 in file)
 * 
 * Outputs JSON-like structure for hierarchical tab design
 */

$basePath = __DIR__ . '/../../';
$docsPath = $basePath . 'docs/';

// Directory mapping
$dirMapping = [
    'overview' => 'overview',
    'doctrine' => 'doctrine',
    'architecture' => 'architecture',
    'schema' => 'schema',
    'agents' => 'agents',
    'ui-ux' => 'ui-ux',
    'developer' => 'developer',
    'history' => 'history',
    'appendix' => 'appendix'
];

$result = [];

foreach ($dirMapping as $category => $dirName) {
    $dirPath = $docsPath . $dirName;
    $result[$category] = [];
    
    if (!is_dir($dirPath)) {
        continue;
    }
    
    // Get all .md files
    $files = glob($dirPath . '/*.md');
    sort($files);
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Skip README files
        if (strtolower($filename) === 'readme.md') {
            continue;
        }
        
        // Derive slug from filename (remove .md extension, convert to lowercase)
        $slug = preg_replace('/\.md$/i', '', $filename);
        $slug = strtolower($slug);
        // Replace underscores and spaces with hyphens
        $slug = preg_replace('/[\s_]+/', '-', $slug);
        // Remove invalid characters
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        // Collapse multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        // Trim hyphens
        $slug = trim($slug, '-');
        
        // Extract title from first H1 in file
        $content = file_get_contents($file);
        $title = $filename; // Default to filename if no H1 found
        
        // Look for first H1 (# Title or #Title)
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            $title = trim($matches[1]);
        } elseif (preg_match('/^#(.+)$/m', $content, $matches)) {
            $title = trim($matches[1]);
        }
        
        $result[$category][] = [
            'filename' => $filename,
            'slug' => $slug,
            'title' => $title
        ];
    }
    
    // Sort by filename
    usort($result[$category], function($a, $b) {
        return strcmp($a['filename'], $b['filename']);
    });
}

// Output JSON structure (properly encoded)
$jsonOutput = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if ($jsonOutput === false) {
    die("ERROR: Failed to encode JSON: " . json_last_error_msg() . "\n");
}
echo $jsonOutput . "\n";
