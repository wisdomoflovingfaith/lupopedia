<?php
/**
 * Lupopedia CLI Tool: Get FLARE Context
 * 
 * This script reads a file, parses its FLARE header, and determines its location
 * within the Lupopedia content architecture (e.g., channels, content, etc.).
 *
 * Usage: php get_flare_context.php <file_path>
 */

require_once __DIR__ . '/../includes/classes/FlareParser.php';

if ($argc < 2) {
    echo json_encode(['error' => 'Usage: php get_flare_context.php <file_path>']);
    exit(1);
}

$file_path = $argv[1];

if (!file_exists($file_path)) {
    echo json_encode(['error' => 'File not found: ' . $file_path]);
    exit(1);
}

// Read the file content
$content = file_get_contents($file_path);

// Parse the FLARE headers
$parsed_data = FlareParser::parseSafe($content);

// Determine the location
$location = 'other';
if (strpos($file_path, '/channels/') !== false) {
    $location = 'channels';
} elseif (strpos($file_path, '/content/') !== false) {
    $location = 'content';
}

// Prepare the output
$output = [
    'file_path' => $file_path,
    'location' => $location,
    'flare_headers' => $parsed_data['headers'],
];

// Output as JSON
header('Content-Type: application/json');
echo json_encode($output, JSON_PRETTY_PRINT);
