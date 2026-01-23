<?php
/**
 * wolfie.header.identity: help-model
 * wolfie.header.placement: /lupo-includes/modules/help/help-model.php
 * wolfie.header.version: 4.4.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created help model layer for database access: help_get_all_by_category, help_get_by_slug, help_search. Doctrine-aligned: BIGINT timestamps, no foreign keys, soft deletes."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. help-model.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Help Model - Database Access Layer
 * ---------------------------------------------------------
 */

/**
 * Get all help topics by category
 * 
 * @param string|null $category Category filter (optional)
 * @return array Array of help topic rows
 */
function help_get_all_by_category($category = null) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}help_topics 
            WHERE is_deleted = 0";
    $params = [];
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY category ASC, title ASC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Help model error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get help topic by slug
 * 
 * @param string $slug The help topic slug
 * @return array|null Help topic row or null if not found
 */
function help_get_by_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}help_topics
            WHERE slug = ?
              AND is_deleted = 0
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('Help model error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Search help topics by query
 * 
 * @param string $query Search query
 * @return array Array of matching help topic rows
 */
function help_search($query) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($query)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    $search_term = '%' . $query . '%';
    
    $sql = "SELECT * FROM {$table_prefix}help_topics 
            WHERE is_deleted = 0 
              AND (title LIKE ? OR content_html LIKE ?)
            ORDER BY title ASC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$search_term, $search_term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Help model error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get all unique categories
 * 
 * @return array Array of category names
 */
function help_get_categories() {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT DISTINCT category 
            FROM {$table_prefix}help_topics 
            WHERE is_deleted = 0 
              AND category IS NOT NULL 
              AND category != ''
            ORDER BY category ASC";
    
    try {
        $stmt = $db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $results ?: [];
    } catch (PDOException $e) {
        error_log('Help model error: ' . $e->getMessage());
        return [];
    }
}
