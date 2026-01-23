<?php
/**
---
wolfie.headers.version: "4.0.11"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Updated render_main_layout() to extract collection_id from metadata and make it available to collection_tabs component for URL generation."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.10: Updated render_main_layout() to extract tabs_data and current_collection from metadata for Collection 0 tabs display."
    mood: "00FF00"
  - speaker: Wolfie
    target: content-renderer
    message: "Created content renderer: master renderer that routes to format-specific renderers (HTML, Markdown, JSON, Atom) and extracts sections."
    mood: "336699"
tags:
  categories: ["renderer", "content"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "Content Renderer"
  description: "Master renderer that routes to format-specific renderers (HTML, Markdown, JSON, Atom) and extracts sections. Version 4.0.10: Collection tabs metadata support."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. content-renderer.php cannot be called directly.");
}

require_once __DIR__ . '/render-html.php';
require_once __DIR__ . '/render-markdown.php';
require_once __DIR__ . '/render-json.php';
require_once __DIR__ . '/render-atom.php';

/**
 * ---------------------------------------------------------
 * Content Renderer - Master Renderer
 * ---------------------------------------------------------
 */

/**
 * Render content body based on type and format
 * 
 * @param string $body Raw content body
 * @param string $type Content type (html, markdown, json, atom, etc.)
 * @param string $format Content format
 * @return string Rendered HTML
 */
function content_render_body($body, $type, $format) {
    switch ($type) {
        case 'markdown':
            return render_markdown($body);
            
        case 'json':
        case 'jsonfeed':
            return render_json($body);
            
        case 'atom':
        case 'rss':
            return render_atom($body);
            
        case 'html':
        case 'article':
        default:
            return render_html($body);
    }
}

/**
 * Extract section anchors from HTML
 * 
 * @param string $html Rendered HTML
 * @return array Array of section IDs
 */
function content_extract_sections($html) {
    $sections = [];
    if (preg_match_all('/<h([1-6])[^>]*id="([^"]+)"[^>]*>/', $html, $matches)) {
        foreach ($matches[2] as $id) {
            $sections[] = $id;
        }
    }
    return $sections;
}

/**
 * Render content page block (content only, no layout)
 * 
 * @param array $content Content row
 * @param string $body_html Rendered body HTML
 * @return string Content block HTML
 */
function render_content_page($content, $body_html) {
    ob_start();
    include __DIR__ . '/../templates/content-page.php';
    return ob_get_clean();
}

/**
 * Render main layout wrapping content block
 * 
 * @param string $page_body Content block HTML
 * @param array $content Content row (for metadata like title, description)
 * @param array $metadata Optional metadata array with semantic context, references, links, tags, collection, prev/next
 * @return string Complete HTML page with global UI
 */
function render_main_layout($page_body, $content, $metadata = []) {
    // Define UI path if not already defined
    if (!defined('LUPO_UI_PATH')) {
        define('LUPO_UI_PATH', LUPOPEDIA_PATH . '/lupo-includes/ui');
    }
    
    // Extract metadata with defaults
    $semanticContext = isset($metadata['semanticContext']) ? $metadata['semanticContext'] : [];
    $contentReferences = isset($metadata['contentReferences']) ? $metadata['contentReferences'] : [];
    $contentLinks = isset($metadata['contentLinks']) ? $metadata['contentLinks'] : [];
    $contentTags = isset($metadata['contentTags']) ? $metadata['contentTags'] : [];
    $contentCollection = isset($metadata['contentCollection']) ? $metadata['contentCollection'] : null;
    $prevContent = isset($metadata['prevContent']) ? $metadata['prevContent'] : null;
    $nextContent = isset($metadata['nextContent']) ? $metadata['nextContent'] : null;
    $contentSections = isset($metadata['contentSections']) ? $metadata['contentSections'] : [];
    
    // Extract collection tabs data (may be empty if collection_id is not set)
    $tabs_data = isset($metadata['tabs_data']) ? $metadata['tabs_data'] : [];
    $current_collection = isset($metadata['current_collection']) ? $metadata['current_collection'] : null;
    $collection_id = isset($metadata['collection_id']) && $metadata['collection_id'] !== null 
        ? (int)$metadata['collection_id'] 
        : null;
    
    // Make ALL metadata available to main_layout.php
    // These variables will be available in the included template
    // Note: Variables extracted above are in local scope and will be available to included file
    ob_start();
    include LUPO_UI_PATH . '/layouts/main_layout.php';
    return ob_get_clean();
}

/**
 * Render 404 not found page
 * 
 * @param string $slug The slug that was not found
 * @return string 404 HTML
 */
function content_render_not_found($slug) {
    http_response_code(404);
    return "<h1>Content Not Found</h1><p>No content for slug: " . htmlspecialchars($slug) . "</p>";
}

?>
