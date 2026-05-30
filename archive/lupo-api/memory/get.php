<?php
/**
 * GET /api/memory/get
 * 
 * Serves a TOON file as JSON to API clients (Gemini, Cursor, etc.)
 * 
 * Query parameters:
 *   - path: Path relative to lupo-memory/ (e.g., development/staging/2026/04/00_root_constitutional_system_requirements.toon)
 * 
 * Response:
 *   - 200: JSON representation of the TOON file
 *   - 400: Missing path parameter
 *   - 403: Invalid path (directory traversal attempt)
 *   - 404: File not found
 *   - 500: Parse error
 */

require_once '../../lupo-includes/bootstrap.php';
require_once '../../lupo-includes/classes/ToonBridge.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get and validate path parameter
$path = $_GET['path'] ?? '';
if (empty($path)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing path parameter']);
    exit;
}

// Security: ensure path stays within lupo-memory/
$memoryBase = LUPOPEDIA_PATH . '/lupo-memory';
if (!ToonBridge::isSafeMemoryPath($path, $memoryBase)) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid path', 'path' => $path]);
    exit;
}

// Ensure .toon extension (add if missing)
if (!str_ends_with($path, '.toon')) {
    $path .= '.toon';
}

$fullPath = $memoryBase . '/' . ltrim($path, '/');

if (!file_exists($fullPath)) {
    http_response_code(404);
    echo json_encode(['error' => 'File not found', 'path' => $path]);
    exit;
}

try {
    $bridge = new ToonBridge();
    $json = $bridge->toonToJsonString($fullPath);
    
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');  // Allow agents to fetch
    echo $json;
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Parse error', 'message' => $e->getMessage()]);
}
