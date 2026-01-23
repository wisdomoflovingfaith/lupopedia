<?php
/**
 * wolfie.header.identity: render-json
 * wolfie.header.placement: /lupo-includes/modules/content/renderers/render-json.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: render-json
 *   message: "Implemented JSON/JSONFeed renderer: parses JSON and JSONFeed formats, renders as structured HTML with syntax highlighting and feed item display."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. render-json.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * JSON/JSONFeed Renderer
 * ---------------------------------------------------------
 */

/**
 * Render JSON/JSONFeed content to HTML
 * 
 * @param string $body JSON content
 * @return string Rendered HTML
 */
function render_json($body) {
    if (empty($body)) {
        return '<p>Empty JSON content.</p>';
    }
    
    $json_data = json_decode($body, true);
    
    if ($json_data === null) {
        // Invalid JSON - return as formatted text
        return '<div class="json-error"><p><strong>Invalid JSON:</strong></p><pre>' . htmlspecialchars($body) . '</pre></div>';
    }
    
    // Check if this is JSONFeed format
    if (isset($json_data['version']) && isset($json_data['items']) && is_array($json_data['items'])) {
        return render_jsonfeed($json_data);
    }
    
    // Regular JSON - render as formatted structure
    return render_json_structure($json_data);
}

/**
 * Render JSONFeed format
 * 
 * @param array $feed JSONFeed data
 * @return string HTML
 */
function render_jsonfeed($feed) {
    $html = '<div class="jsonfeed">';
    
    // Feed header
    if (isset($feed['title'])) {
        $html .= '<h1>' . htmlspecialchars($feed['title']) . '</h1>';
    }
    
    if (isset($feed['description'])) {
        $html .= '<p class="feed-description">' . htmlspecialchars($feed['description']) . '</p>';
    }
    
    if (isset($feed['home_page_url'])) {
        $html .= '<p class="feed-url"><a href="' . htmlspecialchars($feed['home_page_url']) . '">' . htmlspecialchars($feed['home_page_url']) . '</a></p>';
    }
    
    // Feed items
    if (isset($feed['items']) && is_array($feed['items'])) {
        $html .= '<div class="feed-items">';
        foreach ($feed['items'] as $item) {
            $html .= '<article class="feed-item">';
            
            if (isset($item['title'])) {
                $html .= '<h2>' . htmlspecialchars($item['title']) . '</h2>';
            }
            
            if (isset($item['date_published'])) {
                $html .= '<time class="feed-date">' . htmlspecialchars($item['date_published']) . '</time>';
            }
            
            if (isset($item['url'])) {
                $html .= '<p class="feed-item-url"><a href="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['url']) . '</a></p>';
            }
            
            if (isset($item['content_html'])) {
                $html .= '<div class="feed-content">' . $item['content_html'] . '</div>';
            } elseif (isset($item['content_text'])) {
                $html .= '<div class="feed-content"><p>' . nl2br(htmlspecialchars($item['content_text'])) . '</p></div>';
            } elseif (isset($item['summary'])) {
                $html .= '<div class="feed-summary"><p>' . nl2br(htmlspecialchars($item['summary'])) . '</p></div>';
            }
            
            $html .= '</article>';
        }
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Render JSON structure as HTML
 * 
 * @param mixed $data JSON data
 * @param int $depth Current depth (for indentation)
 * @return string HTML
 */
function render_json_structure($data, $depth = 0) {
    $indent = str_repeat('  ', $depth);
    $html = '';
    
    if (is_array($data)) {
        // Check if associative array
        if (array_keys($data) !== range(0, count($data) - 1)) {
            // Associative array (object)
            $html .= '<dl class="json-object">';
            foreach ($data as $key => $value) {
                $html .= '<dt>' . htmlspecialchars($key) . ':</dt>';
                $html .= '<dd>' . render_json_structure($value, $depth + 1) . '</dd>';
            }
            $html .= '</dl>';
        } else {
            // Numeric array (list)
            $html .= '<ul class="json-array">';
            foreach ($data as $value) {
                $html .= '<li>' . render_json_structure($value, $depth + 1) . '</li>';
            }
            $html .= '</ul>';
        }
    } elseif (is_object($data)) {
        $html .= '<dl class="json-object">';
        foreach ((array)$data as $key => $value) {
            $html .= '<dt>' . htmlspecialchars($key) . ':</dt>';
            $html .= '<dd>' . render_json_structure($value, $depth + 1) . '</dd>';
        }
        $html .= '</dl>';
    } elseif (is_string($data)) {
        $html .= '<span class="json-string">' . htmlspecialchars($data) . '</span>';
    } elseif (is_numeric($data)) {
        $html .= '<span class="json-number">' . htmlspecialchars($data) . '</span>';
    } elseif (is_bool($data)) {
        $html .= '<span class="json-boolean">' . ($data ? 'true' : 'false') . '</span>';
    } elseif (is_null($data)) {
        $html .= '<span class="json-null">null</span>';
    } else {
        $html .= '<span class="json-unknown">' . htmlspecialchars((string)$data) . '</span>';
    }
    
    return $html;
}

?>
