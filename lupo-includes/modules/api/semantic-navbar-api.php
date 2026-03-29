<?php
/**
 * Semantic Navbar Backend API (4.0.71)
 *
 * Handles requests for semantic data related to a specific page slug.
 * Targets: edges, contexts, hashtags, folders, qa, references, namespaces, next, previous.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die(json_encode(['success' => false, 'error' => 'Config not loaded.']));
}

header('Content-Type: application/json; charset=utf-8');

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    http_response_code(503);
    echo json_encode(['success' => false, 'error' => 'Database unavailable.']);
    exit;
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

// Expecting $navbar_api_type and $navbar_api_slug from module-loader
if (empty($navbar_api_type) || empty($navbar_api_slug)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing type or slug.']);
    exit;
}

// Find the content_id for the given slug to use in mappings (channel_id for namespaces/next/previous)
$content = $db->fetchRow("SELECT content_id, title, channel_id, hashtags, question_mappings, content_references FROM {$prefix}contents WHERE (slug = :slug OR custom_path = :slug) AND is_deleted = 0 LIMIT 1", array('slug' => $navbar_api_slug));

if (!$content) {
    echo json_encode(['success' => true, 'type' => $navbar_api_type, 'slug' => $navbar_api_slug, 'data' => [], 'message' => 'Path not found in contents.']);
    exit;
}

$content_id = (int)$content['content_id'];
$response_data = [];

switch ($navbar_api_type) {
    case 'edges':
        // Retrieve edges from lupo_edge_map
        $rows = $db->fetchAll("SELECT m.*, t.label as edge_label, t.slug as edge_type_slug 
                               FROM {$prefix}edge_map m 
                               JOIN {$prefix}edge_types t ON m.edge_type_id = t.edge_type_id
                               WHERE m.source_type = 'content' AND m.source_id = :id AND m.is_deleted = 0", 
                               array('id' => $content_id));
        foreach ($rows as $row) {
            $response_data[] = [
                'edge_id' => $row['edge_id'],
                'type' => $row['edge_label'],
                'target_type' => $row['target_type'],
                'target_id' => $row['target_id']
            ];
        }
        break;

    case 'contexts':
        // Retrieve collections/contexts from lupo_collection_map
        $rows = $db->fetchAll("SELECT c.* FROM {$prefix}collections c
                               JOIN {$prefix}collection_map m ON c.collection_id = m.collection_id
                               WHERE m.object_type = 'content' AND m.object_id = :id AND m.is_deleted = 0",
                               array('id' => $content_id));
        foreach ($rows as $row) {
            $response_data[] = [
                'collection_id' => $row['collection_id'],
                'name' => $row['name'],
                'slug' => $row['slug']
            ];
        }
        break;

    case 'hashtags':
        // Retrieve hashtags from canonical mapping table only (Option A compliant)
        $rows = $db->fetchAll("SELECT h.* FROM {$prefix}hashtags h
                               JOIN {$prefix}hashtag_map m ON h.hashtag_id = m.hashtag_id
                               WHERE m.object_type = 'content' AND m.object_id = :id AND m.is_deleted = 0",
                               array('id' => $content_id));
        foreach ($rows as $row) {
            $response_data[] = [
                'hashtag_id' => $row['hashtag_id'],
                'label' => $row['label'],
                'slug' => $row['tag_slug']
            ];
        }
        break;

    case 'folders':
        // Retrieve folders from lupo_folder_map
        $rows = $db->fetchAll("SELECT f.* FROM {$prefix}folders f
                               JOIN {$prefix}folder_map m ON f.folder_id = m.folder_id
                               WHERE m.object_type = 'content' AND m.object_id = :id AND m.is_deleted = 0",
                               array('id' => $content_id));
        foreach ($rows as $row) {
            $response_data[] = [
                'folder_id' => $row['folder_id'],
                'name' => $row['name'],
                'slug' => $row['slug']
            ];
        }
        break;

    case 'qa':
        // Retrieve questions from canonical mapping table only (Option A compliant)
        $rows = $db->fetchAll("SELECT q.*, a.answer_text FROM {$prefix}questions q
                               JOIN {$prefix}question_map m ON q.question_id = m.question_id
                               LEFT JOIN {$prefix}answers a ON q.question_id = a.question_id AND a.is_deleted = 0
                               WHERE m.object_type = 'content' AND m.object_id = :id AND m.is_deleted = 0",
                               array('id' => $content_id));
        foreach ($rows as $row) {
            $response_data[] = [
                'question_id' => $row['question_id'],
                'question' => $row['question_text'],
                'answer' => $row['answer_text']
            ];
        }
        break;

    case 'references':
        // Citations/source links: lupo_reference_links -> lupo_references (distinct from contexts)
        $rows = $db->fetchAll("SELECT r.reference_id, r.url, r.title, r.citation_text, r.source_entity_type, r.source_entity_id
                               FROM {$prefix}reference_links l
                               JOIN {$prefix}references r ON r.reference_id = l.reference_id AND r.is_deleted = 0
                               WHERE l.object_type = 'content' AND l.object_id = :id AND l.is_deleted = 0
                               ORDER BY l.sort_order",
                               array('id' => $content_id));
        foreach ($rows as $row) {
            $response_data[] = [
                'reference_id' => $row['reference_id'],
                'url' => $row['url'],
                'title' => $row['title'],
                'citation_text' => $row['citation_text']
            ];
        }
        break;

    case 'namespaces':
        // Namespace context: channel_id plus collection slugs this content belongs to (semantic model)
        $channel_id = isset($content['channel_id']) ? (int)$content['channel_id'] : 0;
        $rows = $db->fetchAll("SELECT c.slug, c.name FROM {$prefix}collections c
                               JOIN {$prefix}collection_map m ON c.collection_id = m.collection_id
                               WHERE m.object_type = 'content' AND m.object_id = :id AND m.is_deleted = 0",
                               array('id' => $content_id));
        $response_data = array('channel_id' => $channel_id, 'collections' => array());
        foreach ($rows as $row) {
            $response_data['collections'][] = array('slug' => $row['slug'], 'name' => $row['name']);
        }
        break;

    case 'next':
        // Deterministic next content: same channel, content_id ordering (canonical ordering)
        $channel_id = isset($content['channel_id']) ? (int)$content['channel_id'] : 0;
        $next_sql = "SELECT content_id, slug, title FROM {$prefix}contents WHERE is_deleted = 0 AND content_id > :id";
        $next_params = array('id' => $content_id);
        if ($channel_id > 0) {
            $next_sql .= " AND channel_id = :channel_id";
            $next_params['channel_id'] = $channel_id;
        }
        $next_sql .= " ORDER BY content_id ASC LIMIT 1";
        $next_row = $db->fetchRow($next_sql, $next_params);
        if ($next_row) {
            $response_data[] = array('content_id' => (int)$next_row['content_id'], 'slug' => $next_row['slug'], 'title' => $next_row['title']);
        }
        break;

    case 'previous':
        // Deterministic previous content: same channel, content_id ordering
        $channel_id = isset($content['channel_id']) ? (int)$content['channel_id'] : 0;
        $prev_sql = "SELECT content_id, slug, title FROM {$prefix}contents WHERE is_deleted = 0 AND content_id < :id";
        $prev_params = array('id' => $content_id);
        if ($channel_id > 0) {
            $prev_sql .= " AND channel_id = :channel_id";
            $prev_params['channel_id'] = $channel_id;
        }
        $prev_sql .= " ORDER BY content_id DESC LIMIT 1";
        $prev_row = $db->fetchRow($prev_sql, $prev_params);
        if ($prev_row) {
            $response_data[] = array('content_id' => (int)$prev_row['content_id'], 'slug' => $prev_row['slug'], 'title' => $prev_row['title']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Unknown type.']);
        exit;
}

echo json_encode([
    'success' => true,
    'type' => $navbar_api_type,
    'slug' => $navbar_api_slug,
    'data' => $response_data
]);
exit;
