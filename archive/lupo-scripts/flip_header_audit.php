<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/flip_header_audit.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/flip_header_audit.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * FLIP Header Completion Audit for 4.0.29
 * 
 * Audits all documentation and migration files for complete FLIP headers
 * before 4.0.30 development begins.
 */

// Define paths
$docsPath = __DIR__ . '/../docs';
$migrationsPath = __DIR__ . '/../database/migrations';
$outputFile = __DIR__ . '/../docs/audit/flip_header_audit_4.0.29.json';

// Initialize results
$auditResults = [
    'total_files_processed' => 0,
    'missing_headers' => [],
    'malformed_headers' => [],
    'overall_status' => 'PASS',
    'recommendations' => []
];

// Required FLIP header fields
$requiredFields = [
    'wolfie.headers: explicit architecture with structured clarity for every file.',
    'file_path_from_root',
    'file.last_modified_system_version: "4.0.29"',
    'file.last_modified_utc'
];

// Forbidden fields
$forbiddenFields = [
    'featured',
    'channel_status'
];

// Function to audit a single file
function auditFile($filePath, $relativePath) {
    global $requiredFields, $forbiddenFields, $auditResults;
    
    $auditResults['total_files_processed']++;
    
    $content = file_get_contents($filePath);
    if ($content === false) {
        $auditResults['malformed_headers'][] = [
            'file' => $relativePath,
            'error' => 'Cannot read file'
        ];
        return;
    }
    
    // Check for FLIP header block
    if (!preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
        $auditResults['missing_headers'][] = [
            'file' => $relativePath,
            'error' => 'No FLIP header block found'
        ];
        return;
    }
    
    $headerContent = $matches[1];
    $headerLines = explode("\n", $headerContent);
    
    // Check required fields
    $foundRequired = [];
    $foundForbidden = [];
    
    foreach ($headerLines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        
        // Check required fields
        foreach ($requiredFields as $field) {
            if (strpos($line, $field) !== false) {
                $foundRequired[] = $field;
            }
        }
        
        // Check forbidden fields
        foreach ($forbiddenFields as $field) {
            if (strpos($line, $field) !== false) {
                $foundForbidden[] = $field;
            }
        }
    }
    
    // Validate required fields
    $missingRequired = array_diff($requiredFields, $foundRequired);
    if (!empty($missingRequired)) {
        $auditResults['malformed_headers'][] = [
            'file' => $relativePath,
            'error' => 'Missing required fields: ' . implode(', ', $missingRequired)
        ];
    }
    
    // Check forbidden fields
    if (!empty($foundForbidden)) {
        $auditResults['malformed_headers'][] = [
            'file' => $relativePath,
            'error' => 'Contains forbidden fields: ' . implode(', ', $foundForbidden)
        ];
    }
}

// Function to recursively find files
function findFiles($dir, $extensions, $excludePaths = []) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        
        $filePath = $file->getPathname();
        $relativePath = str_replace(__DIR__ . '/../', '', $filePath);
        
        // Skip excluded paths
        foreach ($excludePaths as $exclude) {
            if (strpos($relativePath, $exclude) === 0) {
                continue 2;
            }
        }
        
        // Check extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($extension, $extensions)) {
            $files[$relativePath] = $filePath;
        }
    }
    
    return $files;
}

// Find all documentation files
echo "Finding documentation files...\n";
$docFiles = findFiles($docsPath, ['md'], ['docs/README.md', 'docs/index.md']);

// Find all migration files
echo "Finding migration files...\n";
$migrationFiles = findFiles($migrationsPath, ['sql'], ['migrations/README.sql']);

// Audit all files
echo "Auditing files...\n";
foreach ($docFiles as $relativePath => $filePath) {
    auditFile($filePath, $relativePath);
}

foreach ($migrationFiles as $relativePath => $filePath) {
    auditFile($filePath, $relativePath);
}

// Determine overall status
if (!empty($auditResults['missing_headers']) || !empty($auditResults['malformed_headers'])) {
    $auditResults['overall_status'] = 'FAIL';
    
    // Add recommendations
    if (!empty($auditResults['missing_headers'])) {
        $auditResults['recommendations'][] = 'Add missing FLIP headers to ' . count($auditResults['missing_headers']) . ' files';
    }
    
    if (!empty($auditResults['malformed_headers'])) {
        $auditResults['recommendations'][] = 'Fix malformed FLIP headers in ' . count($auditResults['malformed_headers']) . ' files';
    }
} else {
    $auditResults['recommendations'][] = 'All files have complete and valid FLIP headers';
}

// Ensure output directory exists
$outputDir = dirname($outputFile);
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Write results to JSON file
file_put_contents($outputFile, json_encode($auditResults, JSON_PRETTY_PRINT));

// Output summary
echo "\n=== FLIP Header Audit Results ===\n";
echo "Total files processed: " . $auditResults['total_files_processed'] . "\n";
echo "Files with missing headers: " . count($auditResults['missing_headers']) . "\n";
echo "Files with malformed headers: " . count($auditResults['malformed_headers']) . "\n";
echo "Overall status: " . $auditResults['overall_status'] . "\n";
echo "Results saved to: " . $outputFile . "\n";

// Show details if there are issues
if (!empty($auditResults['missing_headers'])) {
    echo "\nMissing headers:\n";
    foreach ($auditResults['missing_headers'] as $issue) {
        echo "- {$issue['file']}: {$issue['error']}\n";
    }
}

if (!empty($auditResults['malformed_headers'])) {
    echo "\nMalformed headers:\n";
    foreach ($auditResults['malformed_headers'] as $issue) {
        echo "- {$issue['file']}: {$issue['error']}\n";
    }
}

echo "\nRecommendations:\n";
foreach ($auditResults['recommendations'] as $recommendation) {
    echo "- " . $recommendation . "\n";
}

echo "\n";
