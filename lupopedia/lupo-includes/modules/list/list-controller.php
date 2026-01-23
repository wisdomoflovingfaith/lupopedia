<?php
/**
 * wolfie.header.identity: list-controller
 * wolfie.header.placement: /lupo-includes/modules/list/list-controller.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created list controller for generic introspection system. Allows viewing all Lupopedia entities (actors, agents, tables, etc.) with pagination and filtering. Doctrine-aligned implementation."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. list-controller.php cannot be called directly.");
}

/**
 * List Controller
 * 
 * Generic introspection system for all Lupopedia entities.
 * 
 * Routes:
 * - /list (GET: show entity index)
 * - /list/{entity} (GET: show entity list with pagination)
 * 
 * @package Lupopedia
 * @subpackage Modules
 */

/**
 * Allowed entities for listing
 */
function list_get_allowed_entities() {
    // Get table prefix
    global $table_prefix;
    if (!isset($table_prefix)) {
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }
    
    return [
        'actors' => [
            'table' => $table_prefix . 'actors',
            'title' => 'Actors',
            'description' => 'All actors in the system (users, AI agents, services)'
        ],
        'agents' => [
            'table' => $table_prefix . 'agents',
            'title' => 'Agents',
            'description' => 'AI agent registry entries'
        ],
        'help-topics' => [
            'table' => $table_prefix . 'help_topics',
            'title' => 'Help Topics',
            'description' => 'Help documentation topics'
        ],
        'contents' => [
            'table' => $table_prefix . 'contents',
            'title' => 'Content',
            'description' => 'Content items in the system'
        ],
        'channels' => [
            'table' => $table_prefix . 'channels',
            'title' => 'Channels',
            'description' => 'Communication channels'
        ],
        'collections' => [
            'table' => $table_prefix . 'collections',
            'title' => 'Collections',
            'description' => 'Content collections'
        ]
    ];
}

/**
 * Handle list routes
 * 
 * @param string $slug The route slug
 * @return string Rendered HTML output
 */
function list_handle_slug($slug) {
    // Normalize slug - remove leading slash, convert to lowercase
    $slug = ltrim(strtolower($slug), '/');
    $slug = preg_replace('/\.php$/', '', $slug); // Remove .php extension
    
    // Route to appropriate handler
    if ($slug === 'list' || $slug === 'list/') {
        return list_index();
    } elseif (preg_match('/^list\/(.+)$/', $slug, $matches)) {
        return list_entity($matches[1]);
    }
    
    // No match
    return '';
}

/**
 * Handle list index (show available entities)
 * 
 * @return string Rendered HTML output
 */
function list_index() {
    $entities = list_get_allowed_entities();
    
    ob_start();
    include __DIR__ . '/views/index.php';
    return ob_get_clean();
}

/**
 * Handle entity list view
 * 
 * @param string $entity Entity type
 * @return string Rendered HTML output
 */
function list_entity($entity) {
    global $table_prefix;
    
    $allowed = list_get_allowed_entities();
    
    if (!isset($allowed[$entity])) {
        http_response_code(404);
        ob_start();
        include __DIR__ . '/views/404.php';
        return ob_get_clean();
    }
    
    $entity_info = $allowed[$entity];
    // Table name is already built with prefix in list_get_allowed_entities()
    $table = $entity_info['table'];
    
    // Get pagination
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 50;
    $offset = ($page - 1) * $per_page;
    
    // Get total count
    $db = $GLOBALS['mydatabase'];
    $count_sql = "SELECT COUNT(*) FROM `{$table}`";
    if (in_array('is_deleted', array_keys($db->query("DESCRIBE `{$table}`")->fetchAll(PDO::FETCH_COLUMN)))) {
        $count_sql .= " WHERE is_deleted = 0";
    }
    $total_count = $db->query($count_sql)->fetchColumn();
    $total_pages = ceil($total_count / $per_page);
    
    // Get rows
    $sql = "SELECT * FROM `{$table}`";
    if (in_array('is_deleted', array_keys($db->query("DESCRIBE `{$table}`")->fetchAll(PDO::FETCH_COLUMN)))) {
        $sql .= " WHERE is_deleted = 0";
    }
    $sql .= " LIMIT {$per_page} OFFSET {$offset}";
    
    try {
        $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Get column names
        $columns = [];
        if (!empty($rows)) {
            $columns = array_keys($rows[0]);
        } else {
            $desc = $db->query("DESCRIBE `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
            $columns = array_column($desc, 'Field');
        }
        
        ob_start();
        include __DIR__ . '/views/entity.php';
        return ob_get_clean();
    } catch (PDOException $e) {
        error_log('List controller error: ' . $e->getMessage());
        http_response_code(500);
        return '<div class="error">Error loading entity: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
