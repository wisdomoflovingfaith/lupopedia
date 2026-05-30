<?php
/**
 * Edge Controller - Graph API for Lupopedia
 * 
 * Handles edge traversal routes for semantic graph navigation
 * Supports both slug-based and ID-based lookups
 * 
 * @package Lupopedia
 * @version 2026.1.0.9
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. edge-controller.php cannot be called directly.");
}

// Load content model for edge relationships
require_once __DIR__ . '/content-model.php';

/**
 * Handle edge traversal by slug or ID
 * 
 * @param string $param Either slug or 'id/<content_id>'
 * @return string Rendered HTML or JSON response
 */
function edge_traversal_slug($param) {
    // 1. Determine if this is an ID lookup
    $content_id = null;
    $slug = null;
    
    if (preg_match('#^id/(\d+)$#', $param, $matches)) {
        // ID-based lookup: /edge/id/<content_id>
        $content_id = (int)$matches[1];
        $content = edge_lookup_content_by_id($content_id);
        if ($content) {
            $slug = $content['slug'];
        }
    } else {
        // Slug-based lookup: /edge/<slug>
        $slug = $param;
        $content = edge_lookup_content_by_slug($slug);
        if ($content) {
            $content_id = $content['content_id'];
        }
    }
    
    // 2. Validate content exists
    if (!$content || !$content_id) {
        return edge_render_error(404, "Content not found for: $param");
    }
    
    // 3. Get incoming and outgoing edges
    $incoming_edges = edge_get_incoming_edges($content_id);
    $outgoing_edges = edge_get_outgoing_edges($content_id);
    
    // 4. Determine output format
    $output_format = edge_get_output_format();
    
    if ($output_format === 'json') {
        return edge_render_json_response([
            'content' => $content,
            'incoming_edges' => $incoming_edges,
            'outgoing_edges' => $outgoing_edges,
            'total_edges' => count($incoming_edges) + count($outgoing_edges),
            'content_id' => $content_id,
            'slug' => $slug
        ]);
    } else {
        return edge_render_graph_view($content, $incoming_edges, $outgoing_edges);
    }
}

/**
 * Look up content by slug for edge traversal
 * 
 * @param string $slug The content slug
 * @return array|null Content record or null if not found
 */
function edge_lookup_content_by_slug($slug) {
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
            }
            
            return $content;
        }
        
        return null;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Edge content slug lookup error: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Look up content by ID for edge traversal
 * 
 * @param int $content_id The content ID
 * @return array|null Content record or null if not found
 */
function edge_lookup_content_by_id($content_id) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return null;
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT * FROM lupo_contents 
            WHERE content_id = :content_id AND is_deleted = 0 
            LIMIT 1
        ");
        
        $stmt->execute(['content_id' => $content_id]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($content) {
            // Parse JSON metadata
            if ($content['metadata_json']) {
                $content['metadata'] = json_decode($content['metadata_json'], true);
            }
            
            return $content;
        }
        
        return null;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Edge content ID lookup error: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Get incoming edges for content
 * 
 * @param int $content_id The content ID
 * @return array Incoming edges
 */
function edge_get_incoming_edges($content_id) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   lc_left.slug as left_slug,
                   lc_left.content_name as left_name,
                   lc_left.channel_name as left_channel
            FROM lupo_edges e
            LEFT JOIN lupo_contents lc_left ON e.left_object_id = lc_left.content_id
            WHERE e.right_object_id = :content_id
            AND e.is_deleted = 0
            ORDER BY e.weight_score DESC, e.sort_num ASC
        ");
        
        $stmt->execute(['content_id' => $content_id]);
        $edges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse edge metadata
        foreach ($edges as &$edge) {
            if ($edge['metadata_json']) {
                $edge['metadata'] = json_decode($edge['metadata_json'], true);
            }
            
            // Add source content info
            $edge['source_content'] = [
                'id' => $edge['left_object_id'],
                'slug' => $edge['left_slug'],
                'name' => $edge['left_name'],
                'channel' => $edge['left_channel']
            ];
        }
        
        return $edges;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Incoming edges lookup error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Get outgoing edges for content
 * 
 * @param int $content_id The content ID
 * @return array Outgoing edges
 */
function edge_get_outgoing_edges($content_id) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   lc_right.slug as right_slug,
                   lc_right.content_name as right_name,
                   lc_right.channel_name as right_channel
            FROM lupo_edges e
            LEFT JOIN lupo_contents lc_right ON e.right_object_id = lc_right.content_id
            WHERE e.left_object_id = :content_id
            AND e.is_deleted = 0
            ORDER BY e.weight_score DESC, e.sort_num ASC
        ");
        
        $stmt->execute(['content_id' => $content_id]);
        $edges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse edge metadata
        foreach ($edges as &$edge) {
            if ($edge['metadata_json']) {
                $edge['metadata'] = json_decode($edge['metadata_json'], true);
            }
            
            // Add target content info
            $edge['target_content'] = [
                'id' => $edge['right_object_id'],
                'slug' => $edge['right_slug'],
                'name' => $edge['right_name'],
                'channel' => $edge['right_channel']
            ];
        }
        
        return $edges;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Outgoing edges lookup error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Get requested output format (JSON or HTML)
 * 
 * @return string 'json' or 'html'
 */
function edge_get_output_format() {
    // Check for JSON request
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        return 'json';
    }
    
    // Check for format parameter
    if (isset($_GET['format']) && strtolower($_GET['format']) === 'json') {
        return 'json';
    }
    
    // Default to HTML
    return 'html';
}

/**
 * Render graph view (HTML)
 * 
 * @param array $content Content record
 * @param array $incoming_edges Incoming edges
 * @param array $outgoing_edges Outgoing edges
 * @return string Rendered HTML
 */
function edge_render_graph_view($content, $incoming_edges, $outgoing_edges) {
    $html = '<div class="lupopedia-edges edge-graph">';
    $html .= '<h1>Edge Traversal: ' . htmlspecialchars($content['content_name']) . '</h1>';
    $html .= '<div class="content-context">';
    $html .= '<p class="slug">Slug: ' . htmlspecialchars($content['slug']) . '</p>';
    $html .= '<p class="content-id">ID: ' . $content['content_id'] . '</p>';
    $html .= '</div>';
    
    // Incoming edges
    $html .= '<div class="incoming-edges">';
    $html .= '<h2>Incoming Connections (' . count($incoming_edges) . ')</h2>';
    
    if (empty($incoming_edges)) {
        $html .= '<p>No incoming connections found.</p>';
    } else {
        $html .= '<div class="edges-list">';
        foreach ($incoming_edges as $edge) {
            $html .= '<div class="edge-item incoming">';
            $html .= '<span class="edge-type">' . htmlspecialchars($edge['edge_type']) . '</span>';
            $html .= '<span class="weight">Weight: ' . $edge['weight_score'] . '</span>';
            $html .= '<a href="' . LUPOPEDIA_PUBLIC_PATH . '/content/' . htmlspecialchars($edge['source_content']['slug']) . '">';
            $html .= htmlspecialchars($edge['source_content']['name']);
            $html .= '</a>';
            if ($edge['source_content']['channel']) {
                $html .= ' <span class="channel">(' . htmlspecialchars($edge['source_content']['channel']) . ')</span>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    
    // Outgoing edges
    $html .= '<div class="outgoing-edges">';
    $html .= '<h2>Outgoing Connections (' . count($outgoing_edges) . ')</h2>';
    
    if (empty($outgoing_edges)) {
        $html .= '<p>No outgoing connections found.</p>';
    } else {
        $html .= '<div class="edges-list">';
        foreach ($outgoing_edges as $edge) {
            $html .= '<div class="edge-item outgoing">';
            $html .= '<span class="edge-type">' . htmlspecialchars($edge['edge_type']) . '</span>';
            $html .= '<span class="weight">Weight: ' . $edge['weight_score'] . '</span>';
            $html .= '<a href="' . LUPOPEDIA_PUBLIC_PATH . '/content/' . htmlspecialchars($edge['target_content']['slug']) . '">';
            $html .= htmlspecialchars($edge['target_content']['name']);
            $html .= '</a>';
            if ($edge['target_content']['channel']) {
                $html .= ' <span class="channel">(' . htmlspecialchars($edge['target_content']['channel']) . ')</span>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Render JSON response
 * 
 * @param array $data Response data
 * @return string JSON response
 */
function edge_render_json_response($data) {
    header('Content-Type: application/json');
    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

/**
 * Render error response
 * 
 * @param int $code HTTP status code
 * @param string $message Error message
 * @return string Rendered error
 */
function edge_render_error($code, $message) {
    if (edge_get_output_format() === 'json') {
        header('Content-Type: application/json');
        http_response_code($code);
        return json_encode([
            'error' => true,
            'code' => $code,
            'message' => $message
        ], JSON_PRETTY_PRINT);
    } else {
        http_response_code($code);
        $html = '<div class="lupopedia-error">';
        $html .= '<h1>Error ' . $code . '</h1>';
        $html .= '<p>' . htmlspecialchars($message) . '</p>';
        $html .= '</div>';
        return $html;
    }
}

?>
