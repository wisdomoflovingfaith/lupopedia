<?php
/**
 * Registry API — Actor lookup and registration endpoints.
 *
 * Routes handled:
 *   GET  api/registry/actors/lookup?name=&type=
 *   POST api/registry/actors/register
 *
 * Uses ActorService for all database interaction.
 * Returns JSON responses following the Lupopedia API contract
 * documented in docs/api/antigravity_ide_endpoints_4.0.23.md.
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

// Determine which action based on the slug passed by the router.
// The router sets $registry_api_action before requiring this file.
$action = isset($registry_api_action) ? $registry_api_action : '';

// ── GET: Lookup ────────────────────────────────────────────────────────────────
if ($action === 'lookup' && $method === 'GET') {
    $name = isset($_GET['name']) ? trim($_GET['name']) : '';
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';

    $where = [];
    $params = [];

    if ($name !== '') {
        $where[] = 'name = :name';
        $params['name'] = $name;
    }
    if ($type !== '') {
        $where[] = 'actor_type = :actor_type';
        $params['actor_type'] = $type;
    }

    $whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
    $whereClause .= ' AND (is_deleted = 0 OR is_deleted IS NULL)';

    $t = $table_prefix . 'actors';
    $sql = "SELECT actor_id, name AS actor_name, actor_type, slug, is_active, created_ymdhis FROM {$t} {$whereClause} ORDER BY actor_id LIMIT 100";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'actors' => array_map(function ($r) {
                return [
                    'actor_id' => (int) $r['actor_id'],
                    'actor_name' => $r['actor_name'],
                    'actor_type' => $r['actor_type'],
                    'slug' => isset($r['slug']) ? $r['slug'] : null,
                    'is_active' => isset($r['is_active']) ? (bool) $r['is_active'] : true,
                    'created_ymdhis' => isset($r['created_ymdhis']) ? $r['created_ymdhis'] : null,
                ];
            }, $rows),
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => ['code' => 'QUERY_ERROR', 'message' => $e->getMessage()]]);
    }
    exit;
}

// ── POST: Register ─────────────────────────────────────────────────────────────
if ($action === 'register' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['actor_name']) || !isset($input['actor_type'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'Request body must include actor_name and actor_type.']]);
        exit;
    }

    $actor_name = trim($input['actor_name']);
    $actor_type = trim($input['actor_type']);

    if ($actor_name === '' || $actor_type === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ['code' => 'INVALID_JSON', 'message' => 'actor_name and actor_type must not be empty.']]);
        exit;
    }

    // Check if actor already exists (idempotent registration)
    $t = $table_prefix . 'actors';
    try {
        $stmt = $db->prepare("SELECT actor_id, name AS actor_name, actor_type FROM {$t} WHERE name = :name AND actor_type = :actor_type AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1");
        $stmt->execute(['name' => $actor_name, 'actor_type' => $actor_type]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            echo json_encode([
                'success' => true,
                'actor_id' => (int) $existing['actor_id'],
                'actor_name' => $existing['actor_name'],
                'actor_type' => $existing['actor_type'],
                'message' => 'Actor already registered.',
            ]);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => ['code' => 'QUERY_ERROR', 'message' => $e->getMessage()]]);
        exit;
    }

    // Allocate new actor_id using lupo_findpuka if available, otherwise MAX+1
    try {
        $actor_id = null;
        if (function_exists('lupo_findpuka')) {
            $actor_id = lupo_findpuka($db, $t, 'actor_id', 1, null);
        }
        if ($actor_id === null) {
            $stmt = $db->prepare("SELECT MAX(actor_id) AS max_id FROM {$t}");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $actor_id = ($row && $row['max_id'] !== null) ? ((int) $row['max_id'] + 1) : 1;
        }

        $now = (int) gmdate('YmdHis');
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $actor_name));
        $slug = trim($slug, '-');

        $meta = isset($input['meta']) ? json_encode($input['meta']) : null;

        $stmt = $db->prepare("INSERT INTO {$t} (actor_id, actor_name, name, actor_type, slug, is_active, is_deleted, created_ymdhis, updated_ymdhis, metadata) VALUES (:actor_id, :actor_name, :name, :actor_type, :slug, 1, 0, :created, :updated, :meta)");
        $stmt->execute([
            'actor_id' => $actor_id,
            'actor_name' => $actor_name,
            'name' => $actor_name,
            'actor_type' => $actor_type,
            'slug' => $slug,
            'created' => $now,
            'updated' => $now,
            'meta' => $meta,
        ]);

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'actor_id' => $actor_id,
            'actor_name' => $actor_name,
            'actor_type' => $actor_type,
            'registered_at' => gmdate('c'),
            'message' => 'Actor registered successfully.',
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => ['code' => 'INSERT_ERROR', 'message' => $e->getMessage()]]);
    }
    exit;
}

// ── Fallback ───────────────────────────────────────────────────────────────────
http_response_code(405);
echo json_encode(['success' => false, 'error' => ['code' => 'METHOD_NOT_ALLOWED', 'message' => "Unsupported: {$method} {$action}"]]);
exit;
