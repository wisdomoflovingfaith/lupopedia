<?php
/**
---
wolfie.headers.version: "4.0.12"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.12: Version bump for hierarchical tab structure implementation. No logic changes to truth-controller.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Fixed truth_handle_collection_content to ensure tabs_data, current_collection, and collection_id are passed to render_main_layout. Added collection tab and content route handlers (truth_handle_collection_tab, truth_handle_collection_content). Routes: /collection/{id}/tab/{slug} and /collection/{id}/content/{slug}."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.10: Updated TRUTH controller to load Collection 0 (System Collection) tabs and pass them to main layout. Tabs now appear in navigation when viewing TRUTH pages."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "TRUTH Module Integration Phase 2.5: Added POST submission handling for assertions and evidence. Controllers now sanitize, validate, and pass payloads to receiver functions (logging only). No DB writes, no inference, no scoring - just input scaffolding."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "TRUTH Module Integration Phase 2: Expanded controller to load content records, semantic relationships (references, links, tags, collection), TRUTH questions/answers, and evidence metadata. Builds structured view models for all three routes (/truth/{slug}, /truth/assert/{slug}, /truth/evidence/{slug}). No inference logic, no truth evaluation, no evidence weighting - just data loading and view model building."
    mood: "00FF00"
tags:
  categories: ["controller", "truth", "module", "phase2.5", "phase2"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "TRUTH Controller"
  description: "Controller for TRUTH subsystem: routing, model integration, rendering, and layout. Version 4.0.10: Collection 0 tabs integration. Phase 2.5: Input scaffolding with POST handling. Phase 2: Data loading and view model building."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. truth-controller.php cannot be called directly.");
}

// Load TRUTH model and renderer
require_once __DIR__ . '/truth-model.php';
require_once __DIR__ . '/truth-render.php';

// Load content model for semantic relationships
require_once LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/content-model.php';

/**
 * Handle truth lookup by semantic dimension
 * 
 * @param string $dimension The semantic dimension (who|what|where|when|why|how)
 * @param string $slug The content slug
 * @return string Rendered HTML or JSON response
 */
function truth_lookup_dimension($dimension, $slug) {
    // 1. Validate dimension
    $valid_dimensions = ['who', 'what', 'where', 'when', 'why', 'how'];
    if (!in_array($dimension, $valid_dimensions)) {
        return truth_render_error(400, "Invalid dimension '$dimension'. Valid dimensions: " . implode(', ', $valid_dimensions));
    }
    
    // 2. Look up content by slug
    $content = truth_lookup_content_by_slug($slug);
    if (!$content) {
        return truth_render_error(404, "Content not found for slug: $slug");
    }
    
    // 3. Get semantic edges filtered by dimension
    $semantic_edges = truth_get_semantic_edges($content['content_id'], $dimension);
    
    // 4. Determine output format (JSON or HTML)
    $output_format = truth_get_output_format();
    
    if ($output_format === 'json') {
        return truth_render_json_response([
            'content' => $content,
            'dimension' => $dimension,
            'semantic_edges' => $semantic_edges,
            'total_edges' => count($semantic_edges)
        ]);
    } else {
        return truth_render_dimension_view($content, $dimension, $semantic_edges);
    }
}

/**
 * Look up content by slug for truth lookup
 * 
 * @param string $slug The content slug
 * @return array|null Content record or null if not found
 */
function truth_lookup_content_by_slug($slug) {
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
            error_log('Truth content lookup error: ' . $e->getMessage());
        }
        return null;
    }
}

/**
 * Get semantic edges filtered by dimension
 * 
 * @param int $content_id The content ID
 * @param string $dimension The semantic dimension
 * @return array Filtered semantic edges
 */
function truth_get_semantic_edges($content_id, $dimension) {
    if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
        return [];
    }
    
    try {
        $pdo = get_pdo_connection();
        
        // Map dimensions to edge types
        $dimension_edge_types = [
            'who' => ['HAS_ACTOR', 'CREATED_BY', 'AUTHORED_BY', 'RELATED_PERSON'],
            'what' => ['HAS_CONTENT', 'IS_TYPE', 'HAS_PROPERTY', 'RELATED_CONCEPT'],
            'where' => ['LOCATED_AT', 'HAPPENED_AT', 'RELATED_PLACE', 'LOCATION'],
            'when' => ['HAPPENED_WHEN', 'TIME_RELATION', 'TEMPORAL_CONTEXT', 'DATE'],
            'why' => ['PURPOSE', 'REASON', 'MOTIVATION', 'CAUSE'],
            'how' => ['METHOD', 'PROCESS', 'TECHNIQUE', 'APPROACH']
        ];
        
        $edge_types = $dimension_edge_types[$dimension] ?? [];
        
        if (empty($edge_types)) {
            return [];
        }
        
        // Build IN clause for edge types
        $edge_type_placeholders = str_repeat('?,', count($edge_types) - 1) . '?';
        $params = array_merge([$content_id, $content_id], $edge_types);
        
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   lc_left.slug as left_slug,
                   lc_left.content_name as left_name,
                   lc_right.slug as right_slug,
                   lc_right.content_name as right_name
            FROM lupo_edges e
            LEFT JOIN lupo_contents lc_left ON e.left_object_id = lc_left.content_id
            LEFT JOIN lupo_contents lc_right ON e.right_object_id = lc_right.content_id
            WHERE (e.left_object_id = ? OR e.right_object_id = ?)
            AND e.edge_type IN ($edge_type_placeholders)
            AND e.is_deleted = 0
            ORDER BY e.weight_score DESC, e.sort_num ASC
        ");
        
        $stmt->execute($params);
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
            error_log('Semantic edges lookup error: ' . $e->getMessage());
        }
        return [];
    }
}

/**
 * Get requested output format (JSON or HTML)
 * 
 * @return string 'json' or 'html'
 */
function truth_get_output_format() {
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
 * Render dimension view (HTML)
 * 
 * @param array $content Content record
 * @param string $dimension Semantic dimension
 * @param array $semantic_edges Filtered edges
 * @return string Rendered HTML
 */
function truth_render_dimension_view($content, $dimension, $semantic_edges) {
    $html = '<div class="lupopedia-truth truth-dimension">';
    $html .= '<h1>Truth Lookup: ' . ucfirst($dimension) . '</h1>';
    $html .= '<div class="content-context">';
    $html .= '<h2>Content: ' . htmlspecialchars($content['content_name']) . '</h2>';
    $html .= '<p class="slug">' . htmlspecialchars($content['slug']) . '</p>';
    $html .= '</div>';
    
    $html .= '<div class="semantic-edges">';
    $html .= '<h2>Semantic Relationships (' . ucfirst($dimension) . ')</h2>';
    
    if (empty($semantic_edges)) {
        $html .= '<p>No semantic relationships found for dimension: ' . ucfirst($dimension) . '</p>';
    } else {
        $html .= '<div class="edges-list">';
        foreach ($semantic_edges as $edge) {
            $html .= '<div class="edge-item ' . $edge['direction'] . '">';
            $html .= '<span class="edge-type">' . htmlspecialchars($edge['edge_type']) . '</span>';
            $html .= '<span class="direction">' . $edge['direction'] . '</span>';
            $html .= '<a href="' . LUPOPEDIA_PUBLIC_PATH . '/content/' . htmlspecialchars($edge['related_content']['slug']) . '">';
            $html .= htmlspecialchars($edge['related_content']['name']);
            $html .= '</a>';
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
function truth_render_json_response($data) {
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
function truth_render_error($code, $message) {
    if (truth_get_output_format() === 'json') {
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

// Load ConnectionsService for semantic context
if (file_exists(LUPOPEDIA_ABSPATH . '/lupo-includes/class-ConnectionsService.php')) {
    require_once LUPOPEDIA_ABSPATH . '/lupo-includes/class-ConnectionsService.php';
}

// Load content renderer for render_main_layout
require_once LUPOPEDIA_ABSPATH . '/lupo-includes/modules/content/renderers/content-renderer.php';

// Version 4.0.10: Load collection tabs loader for Collection 0
if (file_exists(LUPOPEDIA_ABSPATH . '/lupo-includes/functions/collection-tabs-loader.php')) {
    require_once LUPOPEDIA_ABSPATH . '/lupo-includes/functions/collection-tabs-loader.php';
}

/**
 * ---------------------------------------------------------
 * TRUTH Controller - Routing Handlers
 * ---------------------------------------------------------
 * 
 * Phase 1: Structural layer only (routing, controller skeleton, renderer skeleton)
 * - No inference logic
 * - No assertion model
 * - No evidence system
 * - Just the doorway into TRUTH
 * 
 * Routes:
 * - /truth/{slug} - View TRUTH content
 * - /truth/assert/{slug} - Assertion interface (placeholder)
 * - /truth/evidence/{slug} - Evidence interface (placeholder)
 * - Legacy: question prefixes (what/, who/, where/, when/, why/, how/)
 */

/**
 * Handle TRUTH slug routing
 * 
 * @param string $slug The question prefix slug (e.g., "what/something", "who/someone")
 * @return string Rendered HTML page
 */
function truth_handle_slug($slug) {
    // DEBUG: Track execution
    $debug_info = [];
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        $debug_info[] = "<!-- DEBUG truth_handle_slug: Starting with slug: " . htmlspecialchars($slug) . " -->";
    }
    
    try {
        // 1. Normalize slug
        $slug = ltrim(strtolower($slug), '/');
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_info[] = "<!-- DEBUG: Normalized slug: " . htmlspecialchars($slug) . " -->";
        }
        
        // 2. Extract question prefix and remainder
        $question_prefixes = ['what/', 'who/', 'where/', 'when/', 'why/', 'how/'];
        $prefix = null;
        $remainder = $slug;
        
        foreach ($question_prefixes as $qp) {
            if (strpos($slug, $qp) === 0) {
                $prefix = rtrim($qp, '/');
                $remainder = substr($slug, strlen($qp));
                break;
            }
        }
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_info[] = "<!-- DEBUG: Prefix: " . htmlspecialchars($prefix ?? 'null') . ", Remainder: " . htmlspecialchars($remainder) . " -->";
        }
        
        if (!$prefix) {
            // Not a valid question prefix, return 404
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                $debug_info[] = "<!-- DEBUG: No valid prefix found -->";
            }
            return implode("\n", $debug_info) . truth_render_not_found($slug);
        }
        
        // 3. Fetch question from database
        if (!function_exists('truth_get_question_by_slug')) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                return implode("\n", $debug_info) . "<!-- DEBUG: truth_get_question_by_slug function not found -->";
            }
            return '';
        }
        
        $question = truth_get_question_by_slug($prefix, $remainder);
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_info[] = "<!-- DEBUG: Question lookup result: " . ($question ? "found (ID: " . $question['truth_question_id'] . ")" : "not found") . " -->";
        }
        
        if (!$question) {
            // Question not found, return 404
            return implode("\n", $debug_info) . truth_render_not_found($slug);
        }
        
        // 4. Fetch answers with evidence and sources
        if (!function_exists('truth_get_answers_with_evidence')) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                return implode("\n", $debug_info) . "<!-- DEBUG: truth_get_answers_with_evidence function not found -->";
            }
            return '';
        }
        
        $answers = truth_get_answers_with_evidence($question['truth_question_id']);
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_info[] = "<!-- DEBUG: Answers found: " . count($answers) . " -->";
        }
        
        // 5. Render TRUTH page block (content only, no layout)
        if (!function_exists('render_truth_page')) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                return implode("\n", $debug_info) . "<!-- DEBUG: render_truth_page function not found -->";
            }
            return '';
        }
        
        $page_body = render_truth_page($question, $answers);
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_info[] = "<!-- DEBUG: render_truth_page returned: " . (empty($page_body) ? "empty" : strlen($page_body) . " bytes") . " -->";
        }
        
        // 6. Create content metadata for main layout
        if (!function_exists('truth_render_question_text')) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                return implode("\n", $debug_info) . "<!-- DEBUG: truth_render_question_text function not found -->";
            }
            return '';
        }
        
        $content = [
            'title' => truth_render_question_text($question['question_text'], $question['format'], $question['format_override'] ?? null),
            'description' => 'TRUTH question: ' . htmlspecialchars($question['question_text']),
            'content_sections' => null // TRUTH pages don't use content_sections, they use their own structure
        ];
        
        // 7. Render main layout (wraps content block with global UI)
        if (!function_exists('render_main_layout')) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                return implode("\n", $debug_info) . "<!-- DEBUG: render_main_layout function not found -->";
            }
            return '';
        }
        
        $context = [
            'page_body' => $page_body,
            'page_title' => $content['title'] ?? '',
            'content' => $content,
            'meta' => [
                'description' => $content['description'] ?? ''
            ]
        ];
        $result = render_main_layout($context);
        
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            $debug_info[] = "<!-- DEBUG: render_main_layout returned: " . (empty($result) ? "empty" : strlen($result) . " bytes") . " -->";
        }
        
        return implode("\n", $debug_info) . $result;
        
    } catch (Exception $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            return implode("\n", $debug_info) . "<!-- DEBUG: Exception in truth_handle_slug: " . htmlspecialchars($e->getMessage()) . " --><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
        return '';
    } catch (Error $e) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            return implode("\n", $debug_info) . "<!-- DEBUG: Fatal error in truth_handle_slug: " . htmlspecialchars($e->getMessage()) . " --><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
        return '';
    }
}

/**
 * Handle TRUTH view route: /truth/{slug}
 * 
 * Phase 2: Data loading and view model building (no logic)
 * - Loads content record
 * - Loads semantic relationships (references, links, tags, collection)
 * - Loads TRUTH questions and answers
 * - Loads evidence metadata (placeholder)
 * - Builds structured view model
 * 
 * @param string $slug The TRUTH content slug
 * @return string Rendered HTML page
 */
function truth_handle_view($slug) {
    // 1. Normalize slug
    $slug = ltrim(strtolower($slug), '/');
    
    // 2. Load content record
    $content = content_get_by_slug($slug);
    $contentId = $content ? (int)($content['content_id'] ?? $content['id'] ?? 0) : 0;
    
    // 3. Load semantic relationships (using existing content model functions)
    $references = [];
    $links = [];
    $tags = [];
    $collection = null;
    
    if ($contentId > 0) {
        $references = content_get_references($contentId);
        $links = content_get_links($contentId);
        $tags = content_get_tags($contentId);
        $collection = content_get_collection($contentId);
    }
    
    // 4. Load semantic context via ConnectionsService
    $semanticContext = [
        'atoms' => [],
        'parents' => [],
        'children' => [],
        'siblings' => [],
        'related_content' => []
    ];
    
    if ($contentId > 0 && class_exists('ConnectionsService') && !empty($GLOBALS['mydatabase'])) {
        try {
            $domainId = isset($GLOBALS['domain_id']) ? (int)$GLOBALS['domain_id'] : 1;
            $connectionsService = new ConnectionsService($GLOBALS['mydatabase'], $domainId);
            $semanticContext = $connectionsService->getConnectionsForContent($contentId);
        } catch (Exception $e) {
            error_log('ConnectionsService error in TRUTH controller: ' . $e->getMessage());
        }
    }
    
    // 5. Load TRUTH questions and answers for this slug
    $truthQuestions = truth_get_questions_for_slug($slug);
    $truthAnswers = truth_get_answers_for_slug($slug);
    
    // 6. Load evidence metadata (placeholder - no logic)
    $evidence = truth_get_evidence_for_slug($slug);
    
    // 7. Build structured TRUTH view model
    $truthViewModel = [
        'slug' => $slug,
        'title' => $content ? ($content['title'] ?? 'TRUTH: ' . htmlspecialchars($slug)) : 'TRUTH: ' . htmlspecialchars($slug),
        'content' => $content ? ($content['body'] ?? $content['content'] ?? '') : '',
        'content_id' => $contentId,
        'references' => $references,
        'links' => $links,
        'tags' => $tags,
        'collection' => $collection,
        'questions' => $truthQuestions,
        'answers' => $truthAnswers,
        'evidence' => $evidence,
        'semantic_context' => $semanticContext
    ];
    
    // 8. Pass to renderer
    $page_body = truth_render_view($truthViewModel);
    
    // 9. Create content metadata for main layout
    $contentMetadata = [
        'title' => $truthViewModel['title'],
        'description' => $content ? ($content['description'] ?? 'TRUTH content view') : 'TRUTH content view',
        'content_sections' => null,
        'id' => $contentId,
        'slug' => $slug
    ];
    
    // 10. Determine collection_id: use content's default_collection_id if available
    $collection_id = null;
    if ($content && isset($content['default_collection_id']) && $content['default_collection_id'] !== null) {
        $collection_id = (int)$content['default_collection_id'];
    }
    
    // Load tabs_data for the determined collection_id (if collection_id is set)
    $tabs_data = [];
    $current_collection = null;
    if ($collection_id !== null && $collection_id > 0) {
        if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
            $tabs_data = load_collection_tabs($collection_id);
            $current_collection = get_collection_name($collection_id);
        }
    }
    
    // 11. Prepare metadata for unified UI components (semantic_panel, semantic_map, content_outline)
    $uiMetadata = [
        'semanticContext' => $semanticContext,
        'contentReferences' => $references,
        'contentLinks' => $links,
        'contentTags' => $tags,
        'contentCollection' => $collection,
        'contentSections' => null, // TRUTH pages use their own structure
        'prevContent' => null, // Can be added if needed
        'nextContent' => null, // Can be added if needed
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
        'collection_id' => $collection_id
    ];
    
    // 12. Render main layout (wraps content block with global UI)
    // Pass metadata so semantic_panel, semantic_map, and content_outline receive TRUTH data
    $context = [
        'page_body' => $page_body,
        'page_title' => $contentMetadata['title'] ?? '',
        'content' => $contentMetadata,
        'semantic_context' => $uiMetadata['semanticContext'] ?? [],
        'content_references' => $uiMetadata['contentReferences'] ?? [],
        'content_links' => $uiMetadata['contentLinks'] ?? [],
        'tags' => $uiMetadata['contentTags'] ?? [],
        'collection' => $uiMetadata['contentCollection'] ?? null,
        'content_sections' => $uiMetadata['contentSections'] ?? null,
        'tabs_data' => $uiMetadata['tabs_data'] ?? [],
        'current_collection' => $uiMetadata['current_collection'] ?? null,
        'collection_id' => $uiMetadata['collection_id'] ?? null,
        'prev_content' => $uiMetadata['prevContent'] ?? null,
        'next_content' => $uiMetadata['nextContent'] ?? null,
        'meta' => [
            'description' => $contentMetadata['description'] ?? '',
            'slug' => $contentMetadata['slug'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Handle TRUTH assert route: /truth/assert/{slug}
 * 
 * Phase 2.5: Data loading + input scaffolding for assertion interface
 * - Handles POST submissions (sanitize, validate, log)
 * - Loads content and semantic relationships
 * - Builds view model for assertion interface
 * - No assertion validation logic, no DB writes, no inference
 * 
 * @param string $slug The TRUTH content slug
 * @return string Rendered HTML page
 */
function truth_handle_assert($slug) {
    // 1. Normalize slug
    $slug = ltrim(strtolower($slug), '/');
    
    // 2. Handle POST submission (Phase 2.5)
    $submissionMessage = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['truth_action']) && $_POST['truth_action'] === 'assert') {
        // Sanitize all fields
        $assertionText = isset($_POST['assertion_text']) ? trim($_POST['assertion_text']) : '';
        $sourceSlug = isset($_POST['source_slug']) ? trim($_POST['source_slug']) : '';
        $evidenceSummary = isset($_POST['evidence_summary']) ? trim($_POST['evidence_summary']) : '';
        $contentSlug = isset($_POST['content_slug']) ? trim($_POST['content_slug']) : $slug;
        
        // Validate required fields
        if (empty($assertionText)) {
            $submissionMessage = '<div class="truth-message truth-message-error">Error: Assertion text is required.</div>';
        } else {
            // Build payload array
            $payload = [
                'slug' => $contentSlug,
                'assertion' => $assertionText,
                'source' => $sourceSlug,
                'summary' => $evidenceSummary,
                'timestamp' => time()
            ];
            
            // Pass to receiver (logging only, no DB writes)
            if (truth_receive_assertion($payload)) {
                $submissionMessage = '<div class="truth-message truth-message-success">Phase 2.5 placeholder: assertion submission accepted.</div>';
            } else {
                $submissionMessage = '<div class="truth-message truth-message-error">Error: Failed to process assertion.</div>';
            }
        }
    }
    
    // 3. Load content record
    $content = content_get_by_slug($slug);
    $contentId = $content ? (int)($content['content_id'] ?? $content['id'] ?? 0) : 0;
    
    // 4. Load semantic relationships
    $references = [];
    $links = [];
    $tags = [];
    $collection = null;
    
    if ($contentId > 0) {
        $references = content_get_references($contentId);
        $links = content_get_links($contentId);
        $tags = content_get_tags($contentId);
        $collection = content_get_collection($contentId);
    }
    
    // 4. Load semantic context
    $semanticContext = [
        'atoms' => [],
        'parents' => [],
        'children' => [],
        'siblings' => [],
        'related_content' => []
    ];
    
    if ($contentId > 0 && class_exists('ConnectionsService') && !empty($GLOBALS['mydatabase'])) {
        try {
            $domainId = isset($GLOBALS['domain_id']) ? (int)$GLOBALS['domain_id'] : 1;
            $connectionsService = new ConnectionsService($GLOBALS['mydatabase'], $domainId);
            $semanticContext = $connectionsService->getConnectionsForContent($contentId);
        } catch (Exception $e) {
            error_log('ConnectionsService error in TRUTH assert: ' . $e->getMessage());
        }
    }
    
    // 6. Load TRUTH questions and answers
    $truthQuestions = truth_get_questions_for_slug($slug);
    $truthAnswers = truth_get_answers_for_slug($slug);
    
    // 7. Build TRUTH view model for assertion mode
    $truthViewModel = [
        'slug' => $slug,
        'title' => $content ? ($content['title'] ?? 'TRUTH Assert: ' . htmlspecialchars($slug)) : 'TRUTH Assert: ' . htmlspecialchars($slug),
        'content' => $content ? ($content['body'] ?? $content['content'] ?? '') : '',
        'content_id' => $contentId,
        'references' => $references,
        'links' => $links,
        'tags' => $tags,
        'collection' => $collection,
        'questions' => $truthQuestions,
        'answers' => $truthAnswers,
        'evidence' => [],
        'semantic_context' => $semanticContext,
        'mode' => 'assert', // Indicates assertion mode
        'submission_message' => $submissionMessage // Phase 2.5: Submission confirmation message
    ];
    
    // 8. Pass to renderer
    $page_body = truth_render_assert($truthViewModel);
    
    // 9. Create content metadata for main layout
    $contentMetadata = [
        'title' => $truthViewModel['title'],
        'description' => 'TRUTH assertion interface',
        'content_sections' => null,
        'id' => $contentId,
        'slug' => $slug
    ];
    
    // 10. Determine collection_id: use content's default_collection_id if available
    $collection_id = null;
    if ($content && isset($content['default_collection_id']) && $content['default_collection_id'] !== null) {
        $collection_id = (int)$content['default_collection_id'];
    }
    
    // Load tabs_data for the determined collection_id (if collection_id is set)
    $tabs_data = [];
    $current_collection = null;
    if ($collection_id !== null && $collection_id > 0) {
        if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
            $tabs_data = load_collection_tabs($collection_id);
            $current_collection = get_collection_name($collection_id);
        }
    }
    
    // 11. Prepare metadata for unified UI components
    $uiMetadata = [
        'semanticContext' => $semanticContext,
        'contentReferences' => $references,
        'contentLinks' => $links,
        'contentTags' => $tags,
        'contentCollection' => $collection,
        'contentSections' => null,
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
        'collection_id' => $collection_id
    ];
    
    // 12. Render main layout
    $context = [
        'page_body' => $page_body,
        'page_title' => $contentMetadata['title'] ?? '',
        'content' => $contentMetadata,
        'semantic_context' => $uiMetadata['semanticContext'] ?? [],
        'content_references' => $uiMetadata['contentReferences'] ?? [],
        'content_links' => $uiMetadata['contentLinks'] ?? [],
        'tags' => $uiMetadata['contentTags'] ?? [],
        'collection' => $uiMetadata['contentCollection'] ?? null,
        'content_sections' => $uiMetadata['contentSections'] ?? null,
        'tabs_data' => $uiMetadata['tabs_data'] ?? [],
        'current_collection' => $uiMetadata['current_collection'] ?? null,
        'collection_id' => $uiMetadata['collection_id'] ?? null,
        'prev_content' => $uiMetadata['prevContent'] ?? null,
        'next_content' => $uiMetadata['nextContent'] ?? null,
        'meta' => [
            'description' => $contentMetadata['description'] ?? '',
            'slug' => $contentMetadata['slug'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Handle TRUTH evidence route: /truth/evidence/{slug}
 * 
 * Phase 2.5: Data loading + input scaffolding for evidence interface
 * - Handles POST submissions (sanitize, validate, log)
 * - Loads content and semantic relationships
 * - Loads evidence metadata (placeholder structure)
 * - Builds view model for evidence interface
 * - No evidence weighting, no scoring, no DB writes, no inference
 * 
 * @param string $slug The TRUTH content slug
 * @return string Rendered HTML page
 */
function truth_handle_evidence($slug) {
    // 1. Normalize slug
    $slug = ltrim(strtolower($slug), '/');
    
    // 2. Handle POST submission (Phase 2.5)
    $submissionMessage = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['truth_action']) && $_POST['truth_action'] === 'evidence') {
        // Sanitize all fields
        $evidenceType = isset($_POST['evidence_type']) ? trim($_POST['evidence_type']) : '';
        $evidenceSource = isset($_POST['evidence_source']) ? trim($_POST['evidence_source']) : '';
        $evidenceSummary = isset($_POST['evidence_summary']) ? trim($_POST['evidence_summary']) : '';
        $contentSlug = isset($_POST['content_slug']) ? trim($_POST['content_slug']) : $slug;
        
        // Validate required fields
        if (empty($evidenceType) || empty($evidenceSource) || empty($evidenceSummary)) {
            $submissionMessage = '<div class="truth-message truth-message-error">Error: Evidence type, source, and summary are required.</div>';
        } else {
            // Validate evidence type
            $validTypes = ['reference', 'link', 'tag', 'manual'];
            if (!in_array($evidenceType, $validTypes)) {
                $submissionMessage = '<div class="truth-message truth-message-error">Error: Invalid evidence type.</div>';
            } else {
                // Build payload array
                $payload = [
                    'slug' => $contentSlug,
                    'type' => $evidenceType,
                    'source' => $evidenceSource,
                    'summary' => $evidenceSummary,
                    'timestamp' => time()
                ];
                
                // Pass to receiver (logging only, no DB writes)
                if (truth_receive_evidence($payload)) {
                    $submissionMessage = '<div class="truth-message truth-message-success">Phase 2.5 placeholder: evidence submission accepted.</div>';
                } else {
                    $submissionMessage = '<div class="truth-message truth-message-error">Error: Failed to process evidence.</div>';
                }
            }
        }
    }
    
    // 3. Load content record
    $content = content_get_by_slug($slug);
    $contentId = $content ? (int)($content['content_id'] ?? $content['id'] ?? 0) : 0;
    
    // 4. Load semantic relationships
    $references = [];
    $links = [];
    $tags = [];
    $collection = null;
    
    if ($contentId > 0) {
        $references = content_get_references($contentId);
        $links = content_get_links($contentId);
        $tags = content_get_tags($contentId);
        $collection = content_get_collection($contentId);
    }
    
    // 5. Load semantic context
    $semanticContext = [
        'atoms' => [],
        'parents' => [],
        'children' => [],
        'siblings' => [],
        'related_content' => []
    ];
    
    if ($contentId > 0 && class_exists('ConnectionsService') && !empty($GLOBALS['mydatabase'])) {
        try {
            $domainId = isset($GLOBALS['domain_id']) ? (int)$GLOBALS['domain_id'] : 1;
            $connectionsService = new ConnectionsService($GLOBALS['mydatabase'], $domainId);
            $semanticContext = $connectionsService->getConnectionsForContent($contentId);
        } catch (Exception $e) {
            error_log('ConnectionsService error in TRUTH evidence: ' . $e->getMessage());
        }
    }
    
    // 6. Load TRUTH questions and answers
    $truthQuestions = truth_get_questions_for_slug($slug);
    $truthAnswers = truth_get_answers_for_slug($slug);
    
    // 7. Load evidence metadata (placeholder - no scoring logic)
    $evidence = truth_get_evidence_for_slug($slug);
    
    // 8. Build TRUTH view model for evidence mode
    $truthViewModel = [
        'slug' => $slug,
        'title' => $content ? ($content['title'] ?? 'TRUTH Evidence: ' . htmlspecialchars($slug)) : 'TRUTH Evidence: ' . htmlspecialchars($slug),
        'content' => $content ? ($content['body'] ?? $content['content'] ?? '') : '',
        'content_id' => $contentId,
        'references' => $references,
        'links' => $links,
        'tags' => $tags,
        'collection' => $collection,
        'questions' => $truthQuestions,
        'answers' => $truthAnswers,
        'evidence' => $evidence,
        'semantic_context' => $semanticContext,
        'mode' => 'evidence', // Indicates evidence mode
        'submission_message' => $submissionMessage // Phase 2.5: Submission confirmation message
    ];
    
    // 9. Pass to renderer
    $page_body = truth_render_evidence_view($truthViewModel);
    
    // 10. Create content metadata for main layout
    $contentMetadata = [
        'title' => $truthViewModel['title'],
        'description' => 'TRUTH evidence interface',
        'content_sections' => null,
        'id' => $contentId,
        'slug' => $slug
    ];
    
    // 11. Determine collection_id: use content's default_collection_id if available
    $collection_id = null;
    if ($content && isset($content['default_collection_id']) && $content['default_collection_id'] !== null) {
        $collection_id = (int)$content['default_collection_id'];
    }
    
    // Load tabs_data for the determined collection_id (if collection_id is set)
    $tabs_data = [];
    $current_collection = null;
    if ($collection_id !== null && $collection_id > 0) {
        if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
            $tabs_data = load_collection_tabs($collection_id);
            $current_collection = get_collection_name($collection_id);
        }
    }
    
    // 12. Prepare metadata for unified UI components
    $uiMetadata = [
        'semanticContext' => $semanticContext,
        'contentReferences' => $references,
        'contentLinks' => $links,
        'contentTags' => $tags,
        'contentCollection' => $collection,
        'contentSections' => null,
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
        'collection_id' => $collection_id
    ];
    
    // 13. Render main layout
    $context = [
        'page_body' => $page_body,
        'page_title' => $contentMetadata['title'] ?? '',
        'content' => $contentMetadata,
        'semantic_context' => $uiMetadata['semanticContext'] ?? [],
        'content_references' => $uiMetadata['contentReferences'] ?? [],
        'content_links' => $uiMetadata['contentLinks'] ?? [],
        'tags' => $uiMetadata['contentTags'] ?? [],
        'collection' => $uiMetadata['contentCollection'] ?? null,
        'content_sections' => $uiMetadata['contentSections'] ?? null,
        'tabs_data' => $uiMetadata['tabs_data'] ?? [],
        'current_collection' => $uiMetadata['current_collection'] ?? null,
        'collection_id' => $uiMetadata['collection_id'] ?? null,
        'prev_content' => $uiMetadata['prevContent'] ?? null,
        'next_content' => $uiMetadata['nextContent'] ?? null,
        'meta' => [
            'description' => $contentMetadata['description'] ?? '',
            'slug' => $contentMetadata['slug'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Handle collection tab route: /collection/{id}/tab/{slug}
 * 
 * Version 4.0.11: Displays content list for a collection tab.
 * 
 * @param int $collection_id Collection ID
 * @param string $tab_slug Tab slug
 * @return string Rendered HTML page
 */
function truth_handle_collection_tab($collection_id, $tab_slug) {
    // 1. Get tab by slug
    $tab = truth_get_tab_by_slug($collection_id, $tab_slug);
    
    if (!$tab) {
        return truth_render_not_found('Tab: ' . $tab_slug);
    }
    
    // 2. Get content items for this tab
    $contentItems = truth_get_content_for_tab($tab['collection_tab_id']);
    
    // 3. Render content list
    $page_body = truth_render_tab_content_list($tab, $contentItems);
    
    // 4. Create content metadata for main layout
    $contentMetadata = [
        'title' => $tab['name'] . ' - System Documentation',
        'description' => $tab['description'] ?? 'System documentation content',
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
        'collection_id' => $collection_id // Pass collection_id for URL generation
    ];
    
    // 7. Render main layout
    $context = [
        'page_body' => $page_body,
        'page_title' => $contentMetadata['title'] ?? '',
        'content' => $contentMetadata,
        'semantic_context' => $uiMetadata['semanticContext'] ?? [],
        'content_references' => $uiMetadata['contentReferences'] ?? [],
        'content_links' => $uiMetadata['contentLinks'] ?? [],
        'tags' => $uiMetadata['contentTags'] ?? [],
        'collection' => $uiMetadata['contentCollection'] ?? null,
        'content_sections' => $uiMetadata['contentSections'] ?? null,
        'tabs_data' => $uiMetadata['tabs_data'] ?? [],
        'current_collection' => $uiMetadata['current_collection'] ?? null,
        'collection_id' => $uiMetadata['collection_id'] ?? null,
        'prev_content' => $uiMetadata['prevContent'] ?? null,
        'next_content' => $uiMetadata['nextContent'] ?? null,
        'meta' => [
            'description' => $contentMetadata['description'] ?? '',
            'slug' => $contentMetadata['slug'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Handle collection content route: /collection/{id}/content/{slug}
 * 
 * Version 4.0.11: Displays a content item with markdown rendering.
 * 
 * @param int $collection_id Collection ID (for context)
 * @param string $content_slug Content slug
 * @return string Rendered HTML page
 */
function truth_handle_collection_content($collection_id, $content_slug) {
    // Ensure collection-tabs-loader is loaded
    if (!function_exists('load_collection_tabs')) {
        $loader_path = LUPOPEDIA_ABSPATH . '/lupo-includes/functions/collection-tabs-loader.php';
        if (file_exists($loader_path)) {
            require_once $loader_path;
        }
    }
    
    // 1. Get content by slug
    $content = truth_get_content_by_slug($content_slug);
    
    if (!$content) {
        return truth_render_not_found('Content: ' . $content_slug);
    }
    
    // 2. Load Collection tabs for navigation (before rendering)
    $tabs_data = [];
    $current_collection = null;
    if (function_exists('load_collection_tabs') && function_exists('get_collection_name')) {
        $tabs_data = load_collection_tabs($collection_id);
        $current_collection = get_collection_name($collection_id);
        
        // Debug: Log if tabs_data is empty
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('TRUTH controller: Loaded ' . count($tabs_data) . ' tabs for collection ' . $collection_id);
            if (empty($tabs_data)) {
                error_log('TRUTH controller: WARNING - tabs_data is empty for collection ' . $collection_id);
            }
        }
    } else {
        // Debug: Log if functions don't exist
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('TRUTH controller: load_collection_tabs or get_collection_name function not found');
        }
    }
    
    // 3. Render content item (markdown rendering happens here)
    $page_body = truth_render_content_item($content);
    
    // 4. Create content metadata for main layout
    $contentMetadata = [
        'title' => $content['title'] ?? 'Untitled',
        'description' => $content['description'] ?? ($content['excerpt'] ?? 'System documentation content'),
        'content_sections' => null,
        'id' => $content['content_id'],
        'slug' => $content_slug
    ];
    
    // 5. Prepare metadata for unified UI components
    $uiMetadata = [
        'semanticContext' => [],
        'contentReferences' => [],
        'contentLinks' => [],
        'contentTags' => [],
        'contentCollection' => null,
        'contentSections' => null,
        'tabs_data' => $tabs_data,
        'current_collection' => $current_collection,
        'collection_id' => $collection_id // Pass collection_id for URL generation
    ];
    
    // 6. Render main layout
    $context = [
        'page_body' => $page_body,
        'page_title' => $contentMetadata['title'] ?? '',
        'content' => $contentMetadata,
        'semantic_context' => $uiMetadata['semanticContext'] ?? [],
        'content_references' => $uiMetadata['contentReferences'] ?? [],
        'content_links' => $uiMetadata['contentLinks'] ?? [],
        'tags' => $uiMetadata['contentTags'] ?? [],
        'collection' => $uiMetadata['contentCollection'] ?? null,
        'content_sections' => $uiMetadata['contentSections'] ?? null,
        'tabs_data' => $uiMetadata['tabs_data'] ?? [],
        'current_collection' => $uiMetadata['current_collection'] ?? null,
        'collection_id' => $uiMetadata['collection_id'] ?? null,
        'prev_content' => $uiMetadata['prevContent'] ?? null,
        'next_content' => $uiMetadata['nextContent'] ?? null,
        'meta' => [
            'description' => $contentMetadata['description'] ?? '',
            'slug' => $contentMetadata['slug'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

/**
 * Render 404 not found page for TRUTH
 * 
 * @param string $slug The slug that was not found
 * @return string 404 HTML
 */
function truth_render_not_found($slug) {
    http_response_code(404);
    $content = [
        'title' => 'TRUTH Not Found',
        'description' => 'The requested TRUTH content was not found',
        'body' => '<h1>TRUTH Not Found</h1><p>No TRUTH data for slug: ' . htmlspecialchars($slug) . '</p>',
        'content_sections' => null
    ];
    
    // Use content renderer for consistency
    $page_body = render_content_page($content, $content['body']);
    $context = [
        'page_body' => $page_body,
        'page_title' => $content['title'] ?? '',
        'content' => $content,
        'meta' => [
            'description' => $content['description'] ?? ''
        ]
    ];
    return render_main_layout($context);
}

?>
