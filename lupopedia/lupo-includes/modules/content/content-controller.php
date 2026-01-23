<?php
/**
 * wolfie.header.identity: content-controller
 * wolfie.header.placement: /lupo-includes/modules/content/content-controller.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: content-controller
 *   message: "Version 4.0.18: Added content_handle_collection_tab() function to handle /collection/{id}/tab/{slug} routes for content items. Loads content items from collection_tab_map and renders content list with proper navigation context."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. content-controller.php cannot be called directly.");
}

require_once __DIR__ . '/content-model.php';
require_once __DIR__ . '/renderers/content-renderer.php';

// Load ConnectionsService for semantic context
if (file_exists(LUPOPEDIA_ABSPATH . '/lupo-includes/class-ConnectionsService.php')) {
    require_once LUPOPEDIA_ABSPATH . '/lupo-includes/class-ConnectionsService.php';
}

/**
 * Resolve the active PDO connection.
 *
 * @return PDO|null
 */
function get_pdo_connection() {
    if (!empty($GLOBALS['mydatabase'])) {
        return $GLOBALS['mydatabase'];
    }

    return null;
}

/**
 * ---------------------------------------------------------
 * Content Controller - Main Entry Point
 * ---------------------------------------------------------
 * 
 * Handles content routing when slug is NOT TRUTH and NOT Crafty Syntax
 */

/**
 * Handle canonical content slug routing
 * 
 * @param string $slug The content slug
 * @return string Rendered HTML page
 */
function content_show_by_slug($slug) {
    // 1. Normalize slug
    $slug = trim($slug, '/');
    
    // 2. Look up content by slug (canonical lookup)
    $content = content_lookup_by_slug($slug);
    
    if (!$content) {
        // 3. Content not found - return 404 with suggestions
        return content_render_404($slug);
    }
    
    // 4. Get related edges for semantic context
    $related_edges = content_get_related_edges($content['content_id']);
    
    // 5. Render content with metadata
    $page_body = content_render_canonical($content, $related_edges);
    $context = [
        'page_body' => $page_body,
        'page_title' => $content['title'] ?? ($content['content_name'] ?? ''),
        'content' => $content,
        'content_type' => $content['content_type'] ?? 'markdown',
        'related_edges' => $related_edges,
        'meta' => [
            'description' => $content['description'] ?? '',
            'slug' => $content['slug'] ?? '',
            'created_ymdhis' => $content['created_ymdhis'] ?? '',
            'updated_ymdhis' => $content['updated_ymdhis'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Look up content by slug (canonical resolver)
 * 
 * @param string $slug The content slug
 * @return array|null Content record or null if not found
 */
function content_lookup_by_slug($slug) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return null;
    }
    
    try {
        $pdo = get_pdo_connection();
        if (!$pdo) {
            return null;
        }
        
        $stmt = $pdo->prepare("
            SELECT * FROM lupo_contents 
            WHERE slug = :slug AND is_deleted = 0 
            LIMIT 1
        ");
        
        $stmt->execute(['slug' => $slug]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($content) {
            // Parse JSON metadata
            if (!empty($content['metadata_json'])) {
                $content['metadata'] = json_decode($content['metadata_json'], true);
            }

            // Parse AAL metadata if available
            if (!empty($content['aal_metadata_json'])) {
                $content['aal_metadata'] = json_decode($content['aal_metadata_json'], true);
            }

            if (!isset($content['content_name']) && isset($content['title'])) {
                $content['content_name'] = $content['title'];
            }
            if (!isset($content['content_body']) && isset($content['body'])) {
                $content['content_body'] = $content['body'];
            }

            return $content;
        }
        
        return null;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Content lookup error: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Get related edges for content
 * 
 * @param int $content_id The content ID
 * @return array Related edges
 */
function content_get_related_edges($content_id) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        if (!$pdo) {
            return [];
        }
        
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   lc_left.slug as left_slug,
                   lc_right.slug as right_slug,
                   lc_left.channel_name as left_channel,
                   lc_right.channel_name as right_channel
            FROM lupo_edges e
            LEFT JOIN lupo_contents lc_left ON e.left_object_id = lc_left.content_id
            LEFT JOIN lupo_contents lc_right ON e.right_object_id = lc_right.content_id
            WHERE (e.left_object_id = :content_id OR e.right_object_id = :content_id)
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
        }
        
        return $edges;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Related edges lookup error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Render canonical content page
 * 
 * @param array $content Content record
 * @param array $related_edges Related edges
 * @return string Rendered HTML
 */
function content_render_canonical($content, $related_edges) {
    // Load content renderer
    if (file_exists(LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php')) {
        require_once LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php';
        
        if (function_exists('content_renderer_render_canonical')) {
            return content_renderer_render_canonical($content, $related_edges);
        }
    }
    
    // Fallback rendering
    $content_name = isset($content['content_name'])
        ? (string)$content['content_name']
        : (isset($content['title']) ? (string)$content['title'] : '');
    $content_body = isset($content['content_body'])
        ? (string)$content['content_body']
        : (isset($content['body']) ? (string)$content['body'] : '');
    $rendered_body = $content_body;

    if ($content_body === '' || strcasecmp(trim($content_body), 'see file') === 0) {
        $resolved = content_resolve_body_from_file($content);
        if (!empty($resolved['body'])) {
            $content_body = $resolved['body'];
            $rendered_body = $resolved['rendered_body'];
        }
    }

    $html = '<div class="lupopedia-content canonical-content">';
    $html .= '<h1>' . htmlspecialchars($content_name) . '</h1>';
    $html .= '<div class="content-body">' . $rendered_body . '</div>';
    
    if (!empty($related_edges)) {
        $html .= '<div class="related-edges">';
        $html .= '<h2>Related Connections</h2>';
        foreach ($related_edges as $edge) {
            $html .= '<div class="edge">';
            $html .= '<span class="edge-type">' . htmlspecialchars($edge['edge_type']) . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Resolve content body from documentation files when content says "see file".
 *
 * @param array $content
 * @return array{body:string,rendered_body:string}|null
 */
function content_resolve_body_from_file($content) {
    $doc_root = rtrim(LUPOPEDIA_ABSPATH, '/\\') . '/docs';
    $directories = [
        $doc_root . '/channels/overview',
        $doc_root . '/channels/doctrine',
        $doc_root . '/channels/architecture',
        $doc_root . '/channels/schema',
        $doc_root . '/channels/agents',
        $doc_root . '/channels/ui-ux',
        $doc_root . '/channels/developer',
        $doc_root . '/channels/history',
        $doc_root . '/channels/appendix',
        $doc_root . '/overview',
        $doc_root . '/doctrine',
        $doc_root . '/architecture',
        $doc_root . '/schema',
        $doc_root . '/agents',
        $doc_root . '/ui-ux',
        $doc_root . '/developer',
        $doc_root . '/history',
        $doc_root . '/appendix'
    ];

    $bases = [];
    if (!empty($content['content_name'])) {
        $bases[] = $content['content_name'];
    }
    if (!empty($content['title'])) {
        $bases[] = $content['title'];
    }
    if (!empty($content['slug'])) {
        $bases[] = $content['slug'];
        if (strpos($content['slug'], '-') !== false) {
            $bases[] = substr($content['slug'], strrpos($content['slug'], '-') + 1);
        }
    }
    if (!empty($content['content_slug'])) {
        $bases[] = $content['content_slug'];
    }

    $candidates = [];
    foreach ($bases as $base) {
        $normalized = trim($base);
        if ($normalized === '') {
            continue;
        }
        $candidates[] = $normalized . '.md';
        $candidates[] = strtoupper($normalized) . '.md';
        $candidates[] = strtoupper(str_replace([' ', '-'], '_', $normalized)) . '.md';
        $candidates[] = str_replace([' ', '_'], '-', strtolower($normalized)) . '.md';
    }
    $candidates = array_values(array_unique($candidates));

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            continue;
        }
        foreach ($candidates as $candidate) {
            $path = $dir . '/' . $candidate;
            if (file_exists($path)) {
                $body = file_get_contents($path);

                // Strip YAML front-matter if present.
                $lines = preg_split('/\r\n|\r|\n/', $body);
                if (!empty($lines) && trim($lines[0]) === '---') {
                    for ($i = 1; $i < count($lines); $i++) {
                        if (trim($lines[$i]) === '---') {
                            $lines = array_slice($lines, $i + 1);
                            $body = implode("\n", $lines);
                            break;
                        }
                    }
                }

                $rendered_body = $body;
                if (function_exists('content_render_body')) {
                    $rendered_body = content_render_body($body, 'markdown', 'markdown');
                }
                return [
                    'body' => $body,
                    'rendered_body' => $rendered_body
                ];
            }
        }
    }

    return null;
}

/**
 * Render 404 page with suggestions
 * 
 * @param string $slug The requested slug
 * @return string Rendered HTML
 */
function content_render_404($slug) {
    header('HTTP/1.0 404 Not Found');
    
    // Try to find similar slugs for suggestions
    $suggestions = content_find_similar_slugs($slug);
    
    $html = '<div class="lupopedia-404">';
    $html .= '<h1>Content Not Found</h1>';
    $html .= '<p>The content "<code>' . htmlspecialchars($slug) . '</code>" was not found.</p>';
    
    if (!empty($suggestions)) {
        $html .= '<h2>Did you mean:</h2>';
        $html .= '<ul>';
        foreach ($suggestions as $suggestion) {
            $html .= '<li><a href="' . LUPOPEDIA_PUBLIC_PATH . '/content/' . htmlspecialchars($suggestion['slug']) . '">';
            $html .= htmlspecialchars($suggestion['content_name']) . '</a></li>';
        }
        $html .= '</ul>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Find similar slugs for 404 suggestions
 * 
 * @param string $slug The requested slug
 * @return array Similar content suggestions
 */
function content_find_similar_slugs($slug) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        // Simple similarity search - look for slugs containing parts of the requested slug
        $parts = explode('-', $slug);
        $like_conditions = [];
        $params = [];
        
        foreach ($parts as $part) {
            if (strlen($part) > 2) { // Only use parts longer than 2 characters
                $like_conditions[] = "slug LIKE :part_" . count($params);
                $params[":part_" . count($params)] = "%" . $part . "%";
            }
        }
        
        if (empty($like_conditions)) {
            return [];
        }
        
        $sql = "
            SELECT content_id, slug, content_name 
            FROM lupo_contents 
            WHERE (" . implode(" OR ", $like_conditions) . ")
            AND is_deleted = 0 
            AND slug != :original_slug
            LIMIT 5
        ";
        
        $params[':original_slug'] = $slug;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('Similar slug lookup error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Handle default content routing by slug.
 *
 * @param string $slug
 * @param int|null $collection_id
 * @return string
 */
function content_handle_slug($slug, $collection_id = null) {
    // 1. Normalize slug
    $slug = trim($slug, '/');
    if ($slug === '') {
        return '';
    }

    // 2. Fetch content row
    $content = content_get_by_slug($slug);
    
    if (!$content || (isset($content['is_deleted']) && $content['is_deleted'] == 1) || (isset($content['is_active']) && $content['is_active'] == 0)) {
        return content_render_not_found($slug);
    }
    
    // 3. Remote content?
    if (!empty($content['content_url'])) {
        $remote_body = content_fetch_remote($content['content_url']);
        if ($remote_body !== false) {
            $content['body'] = $remote_body;
        }
    }
    
    // 4. Render body based on type + format
    $content_type = isset($content['content_type']) ? $content['content_type'] : 'html';
    $format = isset($content['format']) ? $content['format'] : 'html';
    $body = isset($content['body']) ? $content['body'] : '';
    
    $rendered_body = content_render_body($body, $content_type, $format);
    
    // 5. Extract section anchors (cached)
    if (empty($content['content_sections'])) {
        $sections = content_extract_sections($rendered_body);
        if (!empty($sections) && isset($content['content_id'])) {
            content_update_sections($content['content_id'], $sections);
            $content['content_sections'] = $sections;
        }
    }
    
    // 6. Load semantic context and metadata
    $content_id = isset($content['content_id']) ? (int)$content['content_id'] : 0;
    $semanticContext = [];
    $contentReferences = [];
    $contentLinks = [];
    $contentTags = [];
    $contentCollection = null;
    $prevNext = ['prev' => null, 'next' => null];
    
    if ($content_id > 0) {
        // Load semantic context via ConnectionsService
        if (class_exists('ConnectionsService') && !empty($GLOBALS['mydatabase'])) {
            try {
                $domainId = isset($GLOBALS['domain_id']) ? (int)$GLOBALS['domain_id'] : 0;
                $connectionsService = new ConnectionsService($GLOBALS['mydatabase'], $domainId);
                $semanticContext = $connectionsService->getConnectionsForContent($content_id);
            } catch (Exception $e) {
                error_log('ConnectionsService error: ' . $e->getMessage());
            }
        }
        
        // Load content metadata
        $contentReferences = content_get_references($content_id);
        $contentLinks = content_get_links($content_id);
        $contentTags = content_get_tags($content_id);
        $contentCollection = content_get_collection($content_id);
        $prevNext = content_get_prev_next($content_id, $contentCollection['collection_id'] ?? null);
    }
    
    // 7. Determine collection_id: URL takes precedence, then content's default_collection_id
    if ($collection_id === null) {
        // Fall back to content's default_collection_id if URL didn't specify collection
        $collection_id = isset($content['default_collection_id']) && $content['default_collection_id'] !== null 
            ? (int)$content['default_collection_id'] 
            : null;
    }
    
    // 8. Load tabs_data for the determined collection_id (if collection_id is set)
    $tabs_data = [];
    $current_collection = null;
    if ($collection_id !== null && $collection_id > 0) {
        // Load collection tabs loader function if available
        if (!function_exists('load_collection_tabs')) {
            $loader_path = LUPOPEDIA_ABSPATH . '/lupo-includes/functions/collection-tabs-loader.php';
            if (file_exists($loader_path)) {
                require_once $loader_path;
            }
        }
        if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
            $tabs_data = load_collection_tabs($collection_id);
            $current_collection = get_collection_name($collection_id);
        }
    }
    
    // 9. Render content block (content only, no layout)
    $page_body = render_content_page($content, $rendered_body);
    
    // 10. Render main layout (wraps content block with global UI)
    // Pass all semantic data to layout for component integration
    $context = [
        'page_body' => $page_body,
        'page_title' => $content['title'] ?? '',
        'content' => $content,
        'content_type' => $content_type,
        'semantic_context' => $semanticContext,
        'content_references' => $contentReferences,
        'content_links' => $contentLinks,
        'tags' => $contentTags,
        'collection' => $contentCollection,
        'prev_content' => $prevNext['prev'],
        'next_content' => $prevNext['next'],
        'content_sections' => isset($content['content_sections']) ? $content['content_sections'] : [],
        'collection_id' => $collection_id,
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
        'meta' => [
            'description' => $content['description'] ?? '',
            'slug' => $content['slug'] ?? '',
            'created_ymdhis' => $content['created_ymdhis'] ?? '',
            'updated_ymdhis' => $content['updated_ymdhis'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Handle collection tab route: /collection/{id}/tab/{slug}
 * 
 * Displays content list for a collection tab (for content items, not TRUTH items).
 * 
 * @param int $collection_id Collection ID
 * @param string $tab_slug Tab slug
 * @return string Rendered HTML page
 */
function content_handle_collection_tab($collection_id, $tab_slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return content_render_not_found('Database connection not available');
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // 1. Get tab by slug
    $sql = "SELECT collection_tab_id, name, slug, description
            FROM {$table_prefix}collection_tabs
            WHERE collection_id = :collection_id
              AND slug = :slug
              AND is_active = 1
              AND is_deleted = 0
            LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':collection_id' => $collection_id,
        ':slug' => $tab_slug
    ]);
    $tab = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$tab) {
        return content_render_not_found('Tab: ' . $tab_slug);
    }
    
    // 2. Get content items for this tab from collection_tab_map
    $sql = "SELECT ctm.item_id, ctm.item_type, ctm.sort_order, c.content_id, c.slug, c.title, c.description
            FROM {$table_prefix}collection_tab_map ctm
            JOIN {$table_prefix}contents c ON c.content_id = ctm.item_id AND ctm.item_type = 'content'
            WHERE ctm.collection_tab_id = :tab_id
              AND c.is_deleted = 0
              AND c.is_active = 1
            ORDER BY ctm.sort_order ASC, c.title ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([':tab_id' => $tab['collection_tab_id']]);
    $contentItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 3. Render content list
    $page_body = '<div class="collection-tab-content">';
    $page_body .= '<h1>' . htmlspecialchars($tab['name']) . '</h1>';
    
    if (!empty($tab['description'])) {
        $page_body .= '<p class="tab-description">' . htmlspecialchars($tab['description']) . '</p>';
    }
    
    if (!empty($contentItems)) {
        $page_body .= '<ul class="content-list">';
        foreach ($contentItems as $item) {
            $content_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/content/' . $item['slug'];
            $page_body .= '<li>';
            $page_body .= '<a href="' . htmlspecialchars($content_url) . '">';
            $page_body .= '<h3>' . htmlspecialchars($item['title']) . '</h3>';
            if (!empty($item['description'])) {
                $page_body .= '<p>' . htmlspecialchars($item['description']) . '</p>';
            }
            $page_body .= '</a>';
            $page_body .= '</li>';
        }
        $page_body .= '</ul>';
    } else {
        $page_body .= '<p>No content items found in this tab.</p>';
    }
    
    $page_body .= '</div>';
    
    // 4. Create content metadata for main layout
    $contentMetadata = [
        'title' => $tab['name'] . ' - Content',
        'description' => $tab['description'] ?? 'Content items in this collection tab',
        'content_sections' => null,
        'id' => $tab['collection_tab_id'],
        'slug' => $tab_slug
    ];
    
    // 5. Load Collection tabs for navigation
    $tabs_data = [];
    $current_collection = null;
    if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
        $tabs_data = load_collection_tabs($collection_id);
        $current_collection = get_collection_name($collection_id);
    }
    
    // 6. Prepare metadata for unified UI components
    $uiMetadata = [
        'semanticContext' => [],
        'contentReferences' => [],
        'contentLinks' => [],
        'contentTags' => [],
        'contentCollection' => null,
        'contentSections' => null,
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
        'collection_id' => $collection_id
    ];
    
    // 7. Render main layout
    $context = [
        'page_body' => $page_body,
        'page_title' => $contentMetadata['title'] ?? '',
        'content' => $contentMetadata,
        'content_type' => $contentMetadata['content_type'] ?? 'html',
        'semantic_context' => $uiMetadata['semanticContext'] ?? [],
        'content_references' => $uiMetadata['contentReferences'] ?? [],
        'content_links' => $uiMetadata['contentLinks'] ?? [],
        'tags' => $uiMetadata['contentTags'] ?? [],
        'collection' => $uiMetadata['contentCollection'] ?? null,
        'content_sections' => $uiMetadata['contentSections'] ?? null,
        'tabs_data' => $uiMetadata['tabs_data'] ?? [],
        'current_collection' => $uiMetadata['current_collection'] ?? null,
        'collection_id' => $uiMetadata['collection_id'] ?? null,
        'meta' => [
            'description' => $contentMetadata['description'] ?? '',
            'slug' => $contentMetadata['slug'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

?>
