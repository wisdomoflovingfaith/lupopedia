<?php
/**
 * Lookup Helper Functions for Lupopedia Canonical Routing
 * 
 * Provides helper functions for slug resolution, content lookup,
 * and semantic filtering without modifying database schema
 * 
 * @package Lupopedia
 * @version 2026.1.0.9
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. lookup-helpers.php cannot be called directly.");
}

/**
 * Resolve slug to content_id
 * 
 * @param string $slug The content slug
 * @return int|null Content ID or null if not found
 */
function resolve_slug_to_content_id($slug) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return null;
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT content_id FROM lupo_contents 
            WHERE slug = :slug AND is_deleted = 0 
            LIMIT 1
        ");
        
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['content_id'] : null;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Slug resolution error: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Resolve content_id to edges (both incoming and outgoing)
 * 
 * @param int $content_id The content ID
 * @return array Array of edges with metadata
 */
function resolve_content_id_to_edges($content_id) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   lc_left.slug as left_slug,
                   lc_left.content_name as left_name,
                   lc_right.slug as right_slug,
                   lc_right.content_name as right_name
            FROM lupo_edges e
            LEFT JOIN lupo_contents lc_left ON e.left_object_id = lc_left.content_id
            LEFT JOIN lupo_contents lc_right ON e.right_object_id = lc_right.content_id
            WHERE (e.left_object_id = :content_id OR e.right_object_id = :content_id)
            AND e.is_deleted = 0
            ORDER BY e.weight_score DESC, e.sort_num ASC
        ");
        
        $stmt->execute(['content_id' => $content_id]);
        $edges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse edge metadata and determine direction
        foreach ($edges as &$edge) {
            if ($edge['metadata_json']) {
                $edge['metadata'] = json_decode($edge['metadata_json'], true);
            }
            
            // Determine edge direction relative to the content
            if ($edge['left_object_id'] == $content_id) {
                $edge['direction'] = 'outgoing';
                $edge['related_content'] = [
                    'id' => $edge['right_object_id'],
                    'slug' => $edge['right_slug'],
                    'name' => $edge['right_name']
                ];
            } else {
                $edge['direction'] = 'incoming';
                $edge['related_content'] = [
                    'id' => $edge['left_object_id'],
                    'slug' => $edge['left_slug'],
                    'name' => $edge['left_name']
                ];
            }
        }
        
        return $edges;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Content ID to edges resolution error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Resolve dimension to semantic filter
 * 
 * @param string $dimension The semantic dimension (who|what|where|when|why|how)
 * @return array Array of edge types for the dimension
 */
function resolve_dimension_to_semantic_filter($dimension) {
    // Map dimensions to edge types
    $dimension_edge_types = [
        'who' => [
            'HAS_ACTOR', 'CREATED_BY', 'AUTHORED_BY', 'RELATED_PERSON',
            'MENTIONS', 'REFERENCES_PERSON', 'CONNECTED_TO', 'COLLABORATOR'
        ],
        'what' => [
            'HAS_CONTENT', 'IS_TYPE', 'HAS_PROPERTY', 'RELATED_CONCEPT',
            'CONTAINS', 'INCLUDES', 'REFERENCES', 'ABOUT', 'TOPIC'
        ],
        'where' => [
            'LOCATED_AT', 'HAPPENED_AT', 'RELATED_PLACE', 'LOCATION',
            'GEOGRAPHIC', 'VENUE', 'SITE', 'PLACE', 'REGION'
        ],
        'when' => [
            'HAPPENED_WHEN', 'TIME_RELATION', 'TEMPORAL_CONTEXT', 'DATE',
            'TIMESTAMP', 'DURATION', 'PERIOD', 'ERA', 'SEQUENCE'
        ],
        'why' => [
            'PURPOSE', 'REASON', 'MOTIVATION', 'CAUSE',
            'JUSTIFICATION', 'RATIONALE', 'EXPLANATION', 'INTENT', 'GOAL'
        ],
        'how' => [
            'METHOD', 'PROCESS', 'TECHNIQUE', 'APPROACH',
            'PROCEDURE', 'ALGORITHM', 'STRATEGY', 'IMPLEMENTATION', 'WORKFLOW'
        ]
    ];
    
    return $dimension_edge_types[$dimension] ?? [];
}

/**
 * Validate semantic dimension
 * 
 * @param string $dimension The dimension to validate
 * @return bool True if valid, false otherwise
 */
function validate_semantic_dimension($dimension) {
    $valid_dimensions = ['who', 'what', 'where', 'when', 'why', 'how'];
    return in_array($dimension, $valid_dimensions);
}

/**
 * Get content with metadata by slug
 * 
 * @param string $slug The content slug
 * @return array|null Content record with parsed metadata
 */
function get_content_with_metadata_by_slug($slug) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return null;
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT * FROM lupo_contents 
            WHERE slug = :slug AND is_deleted = 0 
            LIMIT 1
        ");
        
        $stmt->execute(['slug' => $slug]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($content) {
            // Parse JSON metadata
            if ($content['metadata_json']) {
                $content['metadata'] = json_decode($content['metadata_json'], true);
            } else {
                $content['metadata'] = [];
            }
            
            // Parse AAL metadata if available
            if ($content['aal_metadata_json']) {
                $content['aal_metadata'] = json_decode($content['aal_metadata_json'], true);
            } else {
                $content['aal_metadata'] = [];
            }
            
            // Parse fleet composition if available
            if ($content['fleet_composition_json']) {
                $content['fleet_composition'] = json_decode($content['fleet_composition_json'], true);
            } else {
                $content['fleet_composition'] = [];
            }
            
            return $content;
        }
        
        return null;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Content with metadata lookup error: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Filter edges by semantic dimension
 * 
 * @param array $edges Array of edges
 * @param string $dimension The semantic dimension
 * @return array Filtered edges
 */
function filter_edges_by_dimension($edges, $dimension) {
    $allowed_edge_types = resolve_dimension_to_semantic_filter($dimension);
    
    if (empty($allowed_edge_types)) {
        return [];
    }
    
    return array_filter($edges, function($edge) use ($allowed_edge_types) {
        return in_array($edge['edge_type'], $allowed_edge_types);
    });
}

/**
 * Get edge statistics for content
 * 
 * @param int $content_id The content ID
 * @return array Statistics about edges
 */
function get_edge_statistics_for_content($content_id) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(CASE WHEN left_object_id = :content_id THEN 1 END) as outgoing_count,
                COUNT(CASE WHEN right_object_id = :content_id THEN 1 END) as incoming_count,
                COUNT(*) as total_count,
                AVG(weight_score) as avg_weight,
                MAX(weight_score) as max_weight,
                MIN(weight_score) as min_weight
            FROM lupo_edges 
            WHERE (left_object_id = :content_id OR right_object_id = :content_id)
            AND is_deleted = 0
        ");
        
        $stmt->execute(['content_id' => $content_id]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Convert to proper types
        return [
            'outgoing_count' => (int)$stats['outgoing_count'],
            'incoming_count' => (int)$stats['incoming_count'],
            'total_count' => (int)$stats['total_count'],
            'avg_weight' => round((float)$stats['avg_weight'], 2),
            'max_weight' => (int)$stats['max_weight'],
            'min_weight' => (int)$stats['min_weight']
        ];
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Edge statistics error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Find related content by edge type
 * 
 * @param int $content_id The content ID
 * @param string $edge_type The edge type to filter by
 * @param int $limit Maximum number of results
 * @return array Related content items
 */
function find_related_content_by_edge_type($content_id, $edge_type, $limit = 10) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT DISTINCT 
                CASE 
                    WHEN e.left_object_id = :content_id THEN lc_right
                    ELSE lc_left
                END as related_content
            FROM lupo_edges e
            LEFT JOIN lupo_contents lc_left ON e.left_object_id = lc_left.content_id
            LEFT JOIN lupo_contents lc_right ON e.right_object_id = lc_right.content_id
            WHERE (e.left_object_id = :content_id OR e.right_object_id = :content_id)
            AND e.edge_type = :edge_type
            AND e.is_deleted = 0
            AND lc_left.is_deleted = 0
            AND lc_right.is_deleted = 0
            LIMIT :limit
        ");
        
        $stmt->execute([
            'content_id' => $content_id,
            'edge_type' => $edge_type,
            'limit' => $limit
        ]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse metadata for related content
        foreach ($results as &$result) {
            if ($result['related_content']['metadata_json']) {
                $result['related_content']['metadata'] = json_decode($result['related_content']['metadata_json'], true);
            }
        }
        
        return array_column($results, 'related_content');
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Related content lookup error: ' . $e->getMessage());
        }
        return [];
    }
}

?>
