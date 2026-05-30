<?php
/**
 * Semantic API — AI-driven semantic explanation and analysis.
 *
 * Routes handled:
 *   POST api/semantic/explain
 *   POST api/semantic/flip-header
 *   POST api/semantic/related
 *   POST api/semantic/paths
 *
 * Follows Doctrine rules:
 * - No foreign keys or triggers.
 * - BIGINT(14) timestamps (YYYYMMDDHHMMSS).
 * - Dynamic table prefix (LUPO_TABLE_PREFIX).
 *
 * @package Lupopedia
 * @since   4.0.27
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die(json_encode(['success' => false, 'error' => ['code' => 'CONFIG_NOT_LOADED', 'message' => 'Config not loaded.']]));
}

header('Content-Type: application/json; charset=utf-8');

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(['success' => false, 'error' => ['code' => 'DB_UNAVAILABLE', 'message' => 'Database not available.']]);
    exit;
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
$action = isset($semantic_api_action) ? $semantic_api_action : '';

// ── POST: explain ─────────────────────────────────────────────────────────────
if ($action === 'explain' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['query'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include query.']]);
        exit;
    }

    // Stub implementation returning mock explanation as per docs
    echo json_encode([
        'success'        => true,
        'explanation'    => 'Semantic analysis of "' . htmlspecialchars($input['query']) . '" currently in stub mode. In a full implementation, this would query the semantic layer and LLM.',
        'related_atoms'  => ['system_onboarding', 'semantic_lookup'],
        'confidence'     => 1.0,
    ]);
    exit;
}

// ── POST: flip-header ─────────────────────────────────────────────────────────
if ($action === 'flip-header' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['file_path'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include file_path.']]);
        exit;
    }

    $now = (int) gmdate('YmdHis');
    echo json_encode([
        'success'     => true,
        'flip_header' => "FLIP: 4.0.26 / {$now} / semantic_stub / " . basename($input['file_path']) . " / 1.0",
        'metadata'    => [
            'atoms_referenced'    => 0,
            'relationships_count' => 0,
            'confidence_score'    => 1.0,
        ],
    ]);
    exit;
}

// ── POST: related ─────────────────────────────────────────────────────────────
if ($action === 'related' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['content_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include content_id.']]);
        exit;
    }

    echo json_encode([
        'success'         => true,
        'related_content' => [
            [
                'content_id'        => (int) $input['content_id'],
                'relationship_type' => 'matches',
                'strength'          => 1.0,
                'target_content'    => 'semantic_stub_placeholder',
            ]
        ],
    ]);
    exit;
}

// ── POST: paths ───────────────────────────────────────────────────────────────
if ($action === 'paths' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['source_atom_id']) || !isset($input['target_atom_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include source_atom_id and target_atom_id.']]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'paths'   => [
            [
                'semantic_path_id' => 0,
                'source_page_id'   => (int) $input['source_atom_id'],
                'target_page_id'   => (int) $input['target_atom_id'],
                'layer'            => 'direct',
                'weight'           => 1.0,
                'created_at'       => (int) gmdate('YmdHis'),
            ]
        ],
    ]);
    exit;
}

// ── Fallback ───────────────────────────────────────────────────────────────────
http_response_code(405);
echo json_encode(['success' => false, 'error' => ['code' => 'METHOD_NOT_ALLOWED', 'message' => "Unsupported: {$method} {$action}"]]);
exit;
