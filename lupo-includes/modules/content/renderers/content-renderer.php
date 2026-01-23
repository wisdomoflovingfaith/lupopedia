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
 * Render main layout wrapping content block.
 *
 * Supports both legacy signature (page body + content + metadata)
 * and the unified context signature.
 *
 * @param mixed $context_or_body
 * @param array|null $content
 * @param array $metadata
 * @return string
 */
function render_main_layout($context_or_body, $content = null, $metadata = []) {
    // Define UI path if not already defined
    if (!defined('LUPO_UI_PATH')) {
        define('LUPO_UI_PATH', LUPOPEDIA_PATH . '/lupo-includes/ui');
    }
    
    $context = [];
    if (is_array($context_or_body) && array_key_exists('page_body', $context_or_body)) {
        $context = $context_or_body;
    } else {
        $context = [
            'page_body' => $context_or_body,
            'content' => $content
        ];
        if (is_array($metadata)) {
            if (isset($metadata['related_edges'])) {
                $context['related_edges'] = $metadata['related_edges'];
            }
            $context['semantic_context'] = $metadata['semanticContext'] ?? [];
            $context['content_references'] = $metadata['contentReferences'] ?? [];
            $context['content_links'] = $metadata['contentLinks'] ?? [];
            $context['tags'] = $metadata['contentTags'] ?? [];
            $context['collection'] = $metadata['contentCollection'] ?? null;
            $context['prev_content'] = $metadata['prevContent'] ?? null;
            $context['next_content'] = $metadata['nextContent'] ?? null;
            $context['content_sections'] = $metadata['contentSections'] ?? [];
            $context['tabs_data'] = $metadata['tabs_data'] ?? [];
            $context['current_collection'] = $metadata['current_collection'] ?? null;
            $context['collection_id'] = $metadata['collection_id'] ?? null;
        }
    }

    $page_body = isset($context['page_body']) ? $context['page_body'] : '';
    $content = isset($context['content']) ? $context['content'] : null;
    $page_title = isset($context['page_title']) ? $context['page_title'] : '';
    if ($page_title === '' && is_array($content)) {
        $page_title = $content['title'] ?? ($content['content_name'] ?? '');
    }
    $meta = isset($context['meta']) ? $context['meta'] : [];
    $related_edges = $context['related_edges'] ?? [];

    $semanticContext = $context['semantic_context'] ?? ($context['semanticContext'] ?? []);
    $contentReferences = $context['content_references'] ?? ($context['contentReferences'] ?? []);
    $contentLinks = $context['content_links'] ?? ($context['contentLinks'] ?? []);
    $contentTags = $context['tags'] ?? ($context['contentTags'] ?? []);
    $contentCollection = $context['collection'] ?? ($context['contentCollection'] ?? null);
    $prevContent = $context['prev_content'] ?? ($context['prevContent'] ?? null);
    $nextContent = $context['next_content'] ?? ($context['nextContent'] ?? null);
    $contentSections = $context['content_sections'] ?? ($context['contentSections'] ?? []);
    $tabs_data = $context['tabs_data'] ?? [];
    $current_collection = $context['current_collection'] ?? null;
    $collection_id = isset($context['collection_id']) && $context['collection_id'] !== null
        ? (int)$context['collection_id']
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
