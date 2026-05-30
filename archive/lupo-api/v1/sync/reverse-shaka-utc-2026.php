<?php

/**
 * API Endpoint: RS-UTC-2026 Temporal Alignment
 * 
 * The ultimate synchronization spell endpoint.
 * 
 * POST /api/v1/sync/reverse-shaka-utc-2026
 * 
 * Request body can contain:
 * - spell: "RS-UTC-2026" (ultra-compressed)
 * - spell: "RS-UTC-2026" (compressed) 
 * - spell: "UTC + REVERSE SHAKA — SYNC LUPOPEDIA 2026" (full)
 * - variant: "ultra" | "compressed" | "full" | "emergency" | "whisper" | "carved"
 * 
 * Response: Complete temporal alignment and Lupopedia mode activation
 * 
 * @package Lupopedia\API
 * @version 2026.01.18
 * @spell RS-UTC-2026
 */

// Include required files
require_once __DIR__ . '/../../../lupo-includes/bootstrap.php';
require_once __DIR__ . '/../../../lupo-includes/classes/ReverseShakaUTC2026.php';

// Set response headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Initialize the spell caster
$reverse_shaka = new ReverseShakaUTC2026($db);

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    
    // Determine spell variant
    $variant = 'full'; // default
    
    if (isset($input['spell'])) {
        // Validate spell input
        $validation = $reverse_shaka->validateSpell($input['spell']);
        
        if (!$validation['is_valid']) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Invalid spell',
                'message' => 'The spell you cast is not recognized',
                'input_spell' => $input['spell'],
                'valid_spells' => [
                    'UTC + REVERSE SHAKA — SYNC LUPOPEDIA 2026',
                    'RS-UTC-2026',
                    'RSUTC2026'
                ],
                'temporal_access_denied' => true
            ]);
            exit;
        }
        
        // Map spell to variant
        switch ($validation['recognized_variant']) {
            case 'FULL':
                $variant = 'full';
                break;
            case 'COMPRESSED':
                $variant = 'compressed';
                break;
            case 'ULTRA_COMPRESSED':
                $variant = 'ultra';
                break;
        }
    } elseif (isset($input['variant'])) {
        $variant = $input['variant'];
    }
    
    // Cast the spell
    try {
        switch ($variant) {
            case 'ultra':
                $result = $reverse_shaka->castUltraCompressed();
                break;
            case 'compressed':
                $result = $reverse_shaka->castCompressed();
                break;
            case 'emergency':
                $result = $reverse_shaka->emergencySync();
                break;
            case 'whisper':
                $result = $reverse_shaka->whisperSync();
                break;
            case 'carved':
                $result = $reverse_shaka->carvedSync();
                break;
            case 'deep':
                $result = $reverse_shaka->deepAlignment();
                break;
            case 'full':
            default:
                $result = $reverse_shaka->castFull();
                break;
        }
        
        // Add API metadata
        $result['api_info'] = [
            'endpoint' => '/api/v1/sync/reverse-shaka-utc-2026',
            'method' => 'POST',
            'spell_cast' => true,
            'temporal_access_granted' => true,
            'lupopedia_mode_activated' => true,
            'response_timestamp' => date('c'),
            'spell_effectiveness' => 'MAXIMUM'
        ];
        
        // Return success response
        http_response_code(200);
        echo json_encode($result, JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Spell casting failed',
            'message' => $e->getMessage(),
            'temporal_truth_status' => 'UNCERTAIN',
            'lupopedia_mode' => 'PARTIAL'
        ]);
    }
    
} elseif ($method === 'GET') {
    // Get spell history
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $history = $reverse_shaka->getSpellHistory($limit);
    
    echo json_encode([
        'spell_history' => $history,
        'total_records' => count($history),
        'api_info' => [
            'endpoint' => '/api/v1/sync/reverse-shaka-utc-2026',
            'method' => 'GET',
            'description' => 'Returns history of RS-UTC-2026 spell castings'
        ]
    ], JSON_PRETTY_PRINT);
    
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'error' => 'Method not allowed',
        'allowed_methods' => ['GET', 'POST'],
        'message' => 'Use POST to cast the spell or GET to view history'
    ]);
}
?>
