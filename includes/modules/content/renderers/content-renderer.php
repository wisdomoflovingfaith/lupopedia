<?php
/**
---
wolfie.headers.version: "3.0.11"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.11: Updated render_main_layout() to extract collection_id from metadata and make it available to collection_tabs component for URL generation."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 3.0.10: Updated render_main_layout() to extract tabs_data and current_collection from metadata for Collection 0 tabs display."
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
  description: "Master renderer that routes to format-specific renderers (HTML, Markdown, JSON, Atom) and extracts sections. Version 3.0.10: Collection tabs metadata support."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. content-renderer.php cannot be called directly.");
}

require_once __DIR__ . '/../../../theme/theme-loader.php';
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
    $sections = array();
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
function render_main_layout($context_or_body, $content = null, $metadata = array()) {
    $context = array();
    if (is_array($context_or_body) && array_key_exists('page_body', $context_or_body)) {
        $context = $context_or_body;
    } else {
        $context = array(
            'page_body' => $context_or_body,
            'content' => $content
        );
        if (is_array($metadata)) {
            if (isset($metadata['related_edges'])) {
                $context['related_edges'] = $metadata['related_edges'];
            }
            $context['semantic_context'] = isset($metadata['semanticContext']) ? $metadata['semanticContext'] : array();
            $context['content_references'] = isset($metadata['contentReferences']) ? $metadata['contentReferences'] : array();
            $context['content_links'] = isset($metadata['contentLinks']) ? $metadata['contentLinks'] : array();
            $context['tags'] = isset($metadata['contentTags']) ? $metadata['contentTags'] : array();
            $context['collection'] = isset($metadata['contentCollection']) ? $metadata['contentCollection'] : null;
            $context['prev_content'] = isset($metadata['prevContent']) ? $metadata['prevContent'] : null;
            $context['next_content'] = isset($metadata['nextContent']) ? $metadata['nextContent'] : null;
            $context['content_sections'] = isset($metadata['contentSections']) ? $metadata['contentSections'] : array();
            $context['tabs_data'] = isset($metadata['tabs_data']) ? $metadata['tabs_data'] : array();
            $context['current_collection'] = isset($metadata['current_collection']) ? $metadata['current_collection'] : null;
            $context['collection_id'] = isset($metadata['collection_id']) ? $metadata['collection_id'] : null;
        }
    }

    $page_body = isset($context['page_body']) ? $context['page_body'] : '';
    $content = isset($context['content']) ? $context['content'] : null;
    $page_title = isset($context['page_title']) ? $context['page_title'] : '';
    if ($page_title === '' && is_array($content)) {
        $page_title = isset($content['title']) ? $content['title'] : (isset($content['content_name']) ? $content['content_name'] : '');
    }
    $meta = isset($context['meta']) ? $context['meta'] : array();
    $related_edges = isset($context['related_edges']) ? $context['related_edges'] : array();
    $content_type = isset($context['content_type']) ? $context['content_type'] : (isset($meta['content_type']) ? $meta['content_type'] : (is_array($content) ? (isset($content['content_type']) ? $content['content_type'] : null) : null));

    $is_html = function($value) {
        if (!is_string($value) || $value === '') {
            return false;
        }
        return preg_match('/<[^>]+>/', $value) === 1;
    };

    if ($content_type === 'markdown' && !$is_html($page_body)) {
        if (function_exists('render_markdown')) {
            $page_body = render_markdown($page_body);
            $context['page_body'] = $page_body;
        }
    }

    $semanticContext = isset($context['semantic_context']) ? $context['semantic_context'] : (isset($context['semanticContext']) ? $context['semanticContext'] : array());
    $contentReferences = isset($context['content_references']) ? $context['content_references'] : (isset($context['contentReferences']) ? $context['contentReferences'] : array());
    $contentLinks = isset($context['content_links']) ? $context['content_links'] : (isset($context['contentLinks']) ? $context['contentLinks'] : array());
    $contentTags = isset($context['tags']) ? $context['tags'] : (isset($context['contentTags']) ? $context['contentTags'] : array());
    $contentCollection = isset($context['collection']) ? $context['collection'] : (isset($context['contentCollection']) ? $context['contentCollection'] : null);
    $prevContent = isset($context['prev_content']) ? $context['prev_content'] : (isset($context['prevContent']) ? $context['prevContent'] : null);
    $nextContent = isset($context['next_content']) ? $context['next_content'] : (isset($context['nextContent']) ? $context['nextContent'] : null);
    $contentSections = isset($context['content_sections']) ? $context['content_sections'] : (isset($context['contentSections']) ? $context['contentSections'] : array());
    $tabs_data = isset($context['tabs_data']) ? $context['tabs_data'] : array();
    $current_collection = isset($context['current_collection']) ? $context['current_collection'] : null;
    $collection_id = isset($context['collection_id']) && $context['collection_id'] !== null
        ? (int)$context['collection_id']
        : null;
    $semantic_widget_context = (isset($context['semantic_widget_context']) && is_array($context['semantic_widget_context']))
        ? $context['semantic_widget_context']
        : array();

    $context['page_body'] = $page_body;
    $context['content'] = $content;
    $context['page_title'] = $page_title;
    $context['meta'] = $meta;
    $context['related_edges'] = $related_edges;
    $context['content_type'] = $content_type;
    $context['semantic_context'] = $semanticContext;
    $context['semanticContext'] = $semanticContext;
    $context['content_references'] = $contentReferences;
    $context['contentReferences'] = $contentReferences;
    $context['content_links'] = $contentLinks;
    $context['contentLinks'] = $contentLinks;
    $context['tags'] = $contentTags;
    $context['contentTags'] = $contentTags;
    $context['collection'] = $contentCollection;
    $context['contentCollection'] = $contentCollection;
    $context['prev_content'] = $prevContent;
    $context['prevContent'] = $prevContent;
    $context['next_content'] = $nextContent;
    $context['nextContent'] = $nextContent;
    $context['content_sections'] = $contentSections;
    $context['contentSections'] = $contentSections;
    $context['tabs_data'] = $tabs_data;
    $context['current_collection'] = $current_collection;
    $context['collection_id'] = $collection_id;
    $context['semantic_widget_context'] = $semantic_widget_context;

    $layout_file = (defined('LUPO_LAYOUT') && LUPO_LAYOUT !== '') ? LUPO_LAYOUT : 'main_layout.php';
    ob_start();
    lupo_theme_include_layout($layout_file, $context);
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
