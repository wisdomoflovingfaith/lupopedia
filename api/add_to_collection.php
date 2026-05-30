<?php
/**
 * Pin current content into a collection tab (lupo_collection_tab_map).
 * POST JSON: collection_tab_id (or tab_id), collection_id (optional),
 * content_id and/or id, and/or content_slug (slug resolved to content_id), optional title (stored in properties JSON).
 */

$config_paths = array(
    dirname(dirname(__DIR__)) . '/lupopedia-config.php',
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/../lupopedia-config.php',
);

$config_loaded = false;
foreach ($config_paths as $config_path) {
    if (file_exists($config_path)) {
        require_once $config_path;
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'error' => 'Config file not found'));
    exit;
}

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}

if (file_exists(LUPOPEDIA_PATH . '/includes/bootstrap.php')) {
    require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';
}

header('Content-Type: application/json');

$actorId = 0;
if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
    $aid = $GLOBALS['lupo_session']->getActorId();
    $actorId = $aid !== null ? (int) $aid : 0;
}

if ($actorId <= 0) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'error' => 'Login required to pin content to a tab'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => 'POST required'));
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'Invalid JSON body'));
    exit;
}

$content_id = isset($input['content_id']) ? (int) $input['content_id'] : 0;
if ($content_id <= 0 && isset($input['id'])) {
    $content_id = (int) $input['id'];
}
$collection_tab_id = isset($input['collection_tab_id']) ? (int) $input['collection_tab_id'] : 0;
if ($collection_tab_id <= 0 && isset($input['tab_id'])) {
    $collection_tab_id = (int) $input['tab_id'];
}
$collection_id = isset($input['collection_id']) ? (int) $input['collection_id'] : 0;
$pin_title = isset($input['title']) ? trim((string) $input['title']) : '';
$content_slug = isset($input['content_slug']) ? trim((string) $input['content_slug']) : '';

if ($collection_tab_id <= 0) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'collection_tab_id (or tab_id) is required'));
    exit;
}

if (!class_exists('DatabaseFactory', false)) {
    require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';
}
if (!class_exists('IdGenerator', false)) {
    require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
}

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$tabsTable = $prefix . 'collection_tabs';
$mapTable = $prefix . 'collection_tab_map';
$contentsTable = $prefix . 'contents';

if ($content_id <= 0 && $content_slug !== '') {
    $slugRow = $db->fetchRow(
        'SELECT content_id FROM ' . $db->quoteIdentifier($contentsTable) .
        ' WHERE slug = :slug AND is_deleted = 0 LIMIT 1',
        array('slug' => $content_slug)
    );
    if ($slugRow && isset($slugRow['content_id'])) {
        $content_id = (int) $slugRow['content_id'];
    }
}

if ($content_id <= 0) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'content_id, id, or resolvable content_slug is required'));
    exit;
}

$tabRow = $db->fetchRow(
    'SELECT collection_tab_id, collection_id, federations_node_id FROM ' . $db->quoteIdentifier($tabsTable) .
    ' WHERE collection_tab_id = :tid AND (is_deleted = 0 OR is_deleted IS NULL) AND is_active = 1 LIMIT 1',
    array('tid' => $collection_tab_id)
);

if (!$tabRow) {
    http_response_code(404);
    echo json_encode(array('success' => false, 'error' => 'Collection tab not found'));
    exit;
}

if ($collection_id > 0 && (int) $tabRow['collection_id'] !== $collection_id) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'Tab does not belong to the active collection'));
    exit;
}

$contentRow = $db->fetchRow(
    'SELECT content_id FROM ' . $db->quoteIdentifier($contentsTable) .
    ' WHERE content_id = :cid AND is_deleted = 0 LIMIT 1',
    array('cid' => $content_id)
);

if (!$contentRow) {
    http_response_code(404);
    echo json_encode(array('success' => false, 'error' => 'Content not found'));
    exit;
}

$now = (int) gmdate('YmdHis');
$federations_node_id = (int) $tabRow['federations_node_id'];

$properties_val = '';
if ($pin_title !== '') {
    $properties_val = json_encode(array('pin_title' => $pin_title));
}

$exist = $db->fetchRow(
    'SELECT collection_tab_map_id, is_deleted FROM ' . $db->quoteIdentifier($mapTable) .
    ' WHERE collection_tab_id = :ctid AND item_type = :itype AND item_id = :iid LIMIT 1',
    array('ctid' => $collection_tab_id, 'itype' => 'content', 'iid' => $content_id)
);

$maxSort = $db->fetchRow(
    'SELECT COALESCE(MAX(sort_order), 0) AS m FROM ' . $db->quoteIdentifier($mapTable) .
    ' WHERE collection_tab_id = :ctid AND (is_deleted = 0 OR is_deleted IS NULL)',
    array('ctid' => $collection_tab_id)
);
$sort_order = isset($maxSort['m']) ? (int) $maxSort['m'] + 1 : 1;

if ($exist) {
    if ((int) $exist['is_deleted'] === 0) {
        echo json_encode(array(
            'success' => true,
            'already_mapped' => true,
            'collection_tab_map_id' => (string) $exist['collection_tab_map_id'],
        ));
        exit;
    }
    $reviveData = array(
        'is_deleted' => 0,
        'deleted_ymdhis' => null,
        'updated_ymdhis' => $now,
        'sort_order' => $sort_order,
    );
    if ($properties_val !== '') {
        $reviveData['properties'] = $properties_val;
    }
    $db->update(
        $mapTable,
        $reviveData,
        'collection_tab_map_id = :mid',
        array('mid' => $exist['collection_tab_map_id'])
    );
    echo json_encode(array(
        'success' => true,
        'revived' => true,
        'collection_tab_map_id' => (string) $exist['collection_tab_map_id'],
    ));
    exit;
}

$newId = IdGenerator::generate();

$insertData = array(
    'collection_tab_map_id' => $newId,
    'collection_tab_id' => $collection_tab_id,
    'federations_node_id' => $federations_node_id,
    'item_type' => 'content',
    'item_id' => $content_id,
    'sort_order' => $sort_order,
    'properties' => ($properties_val !== '') ? $properties_val : '',
    'created_ymdhis' => $now,
    'updated_ymdhis' => $now,
    'is_deleted' => 0,
    'deleted_ymdhis' => null,
);

$ins = $db->insert($mapTable, $insertData);
if ($ins === false) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Failed to insert tab map'));
    exit;
}

echo json_encode(array(
    'success' => true,
    'collection_tab_map_id' => (string) $newId,
));
