<?php
/**
 * wolfie.header.identity: content-model
 * wolfie.header.placement: /lupo-includes/modules/content/content-model.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: content-model
 *   message: "Created content model layer for database access: content_get_by_slug, content_update_sections, content_fetch_remote."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. content-model.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Content Model - Database Access Layer
 * ---------------------------------------------------------
 */

/**
 * Get content by slug
 * 
 * @param string $slug The content slug
 * @return array|null Content row or null if not found
 */
function content_get_by_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}contents
            WHERE slug = ?
              AND is_deleted = 0
              AND is_active = 1
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('Content model error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Update content sections
 * 
 * @param int $content_id Content ID
 * @param array $sections Array of section IDs
 * @return bool Success status
 */
function content_update_sections($content_id, $sections) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return false;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "UPDATE {$table_prefix}contents
            SET content_sections = ?
            WHERE content_id = ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([json_encode($sections), $content_id]);
        return true;
    } catch (PDOException $e) {
        error_log('Content update sections error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Get content references (content that references this content)
 * 
 * @param int $content_id Content ID
 * @return array Array of reference content rows
 */
function content_get_references($content_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($content_id)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Find content that links to this content via edges
    // Edges table uses left_object_type/left_object_id and right_object_type/right_object_id
    $sql = "SELECT DISTINCT c.content_id, c.title, c.slug, e.weight_score as weight
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}contents c ON c.content_id = e.left_object_id
            WHERE e.right_object_type = 'content'
              AND e.right_object_id = ?
              AND e.left_object_type = 'content'
              AND e.is_deleted = 0
              AND c.is_deleted = 0
              AND c.is_active = 1
            ORDER BY e.weight_score DESC
            LIMIT 20";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$content_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Content get references error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get content links (content linked from this content)
 * 
 * @param int $content_id Content ID
 * @return array Array of linked content rows
 */
function content_get_links($content_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($content_id)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Find content linked from this content via edges
    // Edges table uses left_object_type/left_object_id and right_object_type/right_object_id
    $sql = "SELECT DISTINCT c.content_id, c.title, c.slug, e.weight_score as weight
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}contents c ON c.content_id = e.right_object_id
            WHERE e.left_object_type = 'content'
              AND e.left_object_id = ?
              AND e.right_object_type = 'content'
              AND e.is_deleted = 0
              AND c.is_deleted = 0
              AND c.is_active = 1
            ORDER BY e.weight_score DESC
            LIMIT 20";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$content_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Content get links error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get content tags
 * 
 * @param int $content_id Content ID
 * @return array Array of tag strings
 */
function content_get_tags($content_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($content_id)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Get tags via atoms connected to this content
    // Edges table uses left_object_type/left_object_id and right_object_type/right_object_id
    // Atoms table uses atom_name (not label or slug)
    $sql = "SELECT DISTINCT a.atom_name as label
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}atoms a ON a.atom_id = e.right_object_id
            WHERE e.left_object_type = 'content'
              AND e.left_object_id = ?
              AND e.right_object_type = 'atom'
              AND e.is_deleted = 0
            ORDER BY e.weight_score DESC
            LIMIT 50";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$content_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($results, 'label');
    } catch (PDOException $e) {
        error_log('Content get tags error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get content collection
 * 
 * @param int $content_id Content ID
 * @return array|null Collection row or null
 */
function content_get_collection($content_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($content_id)) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Get collection via edges (contents table does NOT have collection_id column)
    // Collections are linked via edges table with edge_type='collection'
    $sql = "SELECT DISTINCT col.collection_id, col.name, col.description, col.slug
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}collections col ON col.collection_id = e.right_object_id
            WHERE e.left_object_type = 'content'
              AND e.left_object_id = ?
              AND e.right_object_type = 'collection'
              AND e.edge_type = 'collection'
              AND e.is_deleted = 0
              AND col.is_deleted = 0
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$content_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('Content get collection error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Get previous and next content in sequence
 * 
 * @param int $content_id Content ID
 * @param int $collection_id Optional collection ID for sequence
 * @return array ['prev' => array|null, 'next' => array|null]
 */
function content_get_prev_next($content_id, $collection_id = null) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($content_id)) {
        return ['prev' => null, 'next' => null];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Get current content's created_ymdhis for ordering
    // Contents table does NOT have collection_id column - collections are linked via edges
    $current_sql = "SELECT created_ymdhis FROM {$table_prefix}contents WHERE content_id = ? LIMIT 1";
    $current_stmt = $db->prepare($current_sql);
    $current_stmt->execute([$content_id]);
    $current = $current_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$current) {
        return ['prev' => null, 'next' => null];
    }
    
    $created_ymdhis = $current['created_ymdhis'];
    
    $prev = null;
    $next = null;
    
    // Get previous content (if collection_id provided, filter via edges)
    $prev_sql = "SELECT c.content_id, c.title, c.slug FROM {$table_prefix}contents c";
    if ($collection_id) {
        $prev_sql .= " JOIN {$table_prefix}edges e ON e.left_object_id = c.content_id
                       AND e.left_object_type = 'content'
                       AND e.right_object_type = 'collection'
                       AND e.right_object_id = ?
                       AND e.is_deleted = 0";
    }
    $prev_sql .= " WHERE c.created_ymdhis < ?
                     AND c.is_deleted = 0
                     AND c.is_active = 1";
    $prev_sql .= " ORDER BY c.created_ymdhis DESC LIMIT 1";
    
    $prev_stmt = $db->prepare($prev_sql);
    if ($collection_id) {
        $prev_stmt->execute([$collection_id, $created_ymdhis]);
    } else {
        $prev_stmt->execute([$created_ymdhis]);
    }
    $prev = $prev_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get next content (if collection_id provided, filter via edges)
    $next_sql = "SELECT c.content_id, c.title, c.slug FROM {$table_prefix}contents c";
    if ($collection_id) {
        $next_sql .= " JOIN {$table_prefix}edges e ON e.left_object_id = c.content_id
                       AND e.left_object_type = 'content'
                       AND e.right_object_type = 'collection'
                       AND e.right_object_id = ?
                       AND e.is_deleted = 0";
    }
    $next_sql .= " WHERE c.created_ymdhis > ?
                     AND c.is_deleted = 0
                     AND c.is_active = 1";
    $next_sql .= " ORDER BY c.created_ymdhis ASC LIMIT 1";
    
    $next_stmt = $db->prepare($next_sql);
    if ($collection_id) {
        $next_stmt->execute([$collection_id, $created_ymdhis]);
    } else {
        $next_stmt->execute([$created_ymdhis]);
    }
    $next = $next_stmt->fetch(PDO::FETCH_ASSOC);
    
    return ['prev' => $prev, 'next' => $next];
}

/**
 * Fetch remote content
 * 
 * @param string $url Remote content URL
 * @return string|false Remote content body or false on failure
 */
function content_fetch_remote($url) {
    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 3,
            'user_agent' => 'Lupopedia/4.0.6',
            'follow_location' => 1,
            'max_redirects' => 3
        ]
    ]);
    
    $content = @file_get_contents($url, false, $ctx);
    
    return $content !== false ? $content : false;
}

?>
