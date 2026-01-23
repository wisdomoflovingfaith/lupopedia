<?php
/**
 * wolfie.header.identity: render-atom
 * wolfie.header.placement: /lupo-includes/modules/content/renderers/render-atom.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: render-atom
 *   message: "Implemented Atom/RSS renderer: parses Atom and RSS feed formats, renders as structured HTML with feed metadata and item display."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. render-atom.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Atom/RSS Renderer
 * ---------------------------------------------------------
 */

/**
 * Render Atom/RSS feed content to HTML
 * 
 * @param string $body Atom/RSS XML content
 * @return string Rendered HTML
 */
function render_atom($body) {
    if (empty($body)) {
        return '<p>Empty feed content.</p>';
    }
    
    // Suppress XML errors for malformed feeds
    libxml_use_internal_errors(true);
    
    $xml = @simplexml_load_string($body);
    
    if ($xml === false) {
        // Invalid XML - return as formatted text
        $errors = libxml_get_errors();
        libxml_clear_errors();
        return '<div class="feed-error"><p><strong>Invalid XML Feed:</strong></p><pre>' . htmlspecialchars($body) . '</pre></div>';
    }
    
    // Detect feed type
    if (isset($xml->entry)) {
        // Atom feed
        return render_atom_feed($xml);
    } elseif (isset($xml->channel) || isset($xml->item)) {
        // RSS feed
        return render_rss_feed($xml);
    } else {
        // Unknown format
        return '<div class="feed-error"><p><strong>Unknown feed format</strong></p><pre>' . htmlspecialchars($body) . '</pre></div>';
    }
}

/**
 * Render Atom feed
 * 
 * @param SimpleXMLElement $xml Atom feed XML
 * @return string HTML
 */
function render_atom_feed($xml) {
    $html = '<div class="atom-feed">';
    
    // Feed header
    if (isset($xml->title)) {
        $html .= '<h1>' . htmlspecialchars((string)$xml->title) . '</h1>';
    }
    
    if (isset($xml->subtitle)) {
        $html .= '<p class="feed-description">' . htmlspecialchars((string)$xml->subtitle) . '</p>';
    }
    
    if (isset($xml->link)) {
        $href = '';
        if (is_array($xml->link)) {
            foreach ($xml->link as $link) {
                $attrs = $link->attributes();
                if (isset($attrs['rel']) && (string)$attrs['rel'] === 'alternate') {
                    $href = (string)$attrs['href'];
                    break;
                }
            }
        } else {
            $attrs = $xml->link->attributes();
            $href = (string)$attrs['href'];
        }
        if ($href) {
            $html .= '<p class="feed-url"><a href="' . htmlspecialchars($href) . '">' . htmlspecialchars($href) . '</a></p>';
        }
    }
    
    // Feed entries
    if (isset($xml->entry) && count($xml->entry) > 0) {
        $html .= '<div class="feed-items">';
        foreach ($xml->entry as $entry) {
            $html .= '<article class="feed-item">';
            
            if (isset($entry->title)) {
                $html .= '<h2>' . htmlspecialchars((string)$entry->title) . '</h2>';
            }
            
            if (isset($entry->published)) {
                $html .= '<time class="feed-date">' . htmlspecialchars((string)$entry->published) . '</time>';
            } elseif (isset($entry->updated)) {
                $html .= '<time class="feed-date">' . htmlspecialchars((string)$entry->updated) . '</time>';
            }
            
            if (isset($entry->link)) {
                $href = '';
                if (is_array($entry->link)) {
                    foreach ($entry->link as $link) {
                        $attrs = $link->attributes();
                        if (isset($attrs['rel']) && (string)$attrs['rel'] === 'alternate') {
                            $href = (string)$attrs['href'];
                            break;
                        }
                    }
                } else {
                    $attrs = $entry->link->attributes();
                    $href = (string)$attrs['href'];
                }
                if ($href) {
                    $html .= '<p class="feed-item-url"><a href="' . htmlspecialchars($href) . '">' . htmlspecialchars($href) . '</a></p>';
                }
            }
            
            if (isset($entry->content)) {
                $content = (string)$entry->content;
                $attrs = $entry->content->attributes();
                if (isset($attrs['type']) && (string)$attrs['type'] === 'html') {
                    $html .= '<div class="feed-content">' . $content . '</div>';
                } else {
                    $html .= '<div class="feed-content"><p>' . nl2br(htmlspecialchars($content)) . '</p></div>';
                }
            } elseif (isset($entry->summary)) {
                $html .= '<div class="feed-summary"><p>' . nl2br(htmlspecialchars((string)$entry->summary)) . '</p></div>';
            }
            
            $html .= '</article>';
        }
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Render RSS feed
 * 
 * @param SimpleXMLElement $xml RSS feed XML
 * @return string HTML
 */
function render_rss_feed($xml) {
    $html = '<div class="rss-feed">';
    
    // Get channel (RSS 2.0) or use root (RSS 1.0)
    $channel = isset($xml->channel) ? $xml->channel : $xml;
    
    // Feed header
    if (isset($channel->title)) {
        $html .= '<h1>' . htmlspecialchars((string)$channel->title) . '</h1>';
    }
    
    if (isset($channel->description)) {
        $html .= '<p class="feed-description">' . htmlspecialchars((string)$channel->description) . '</p>';
    }
    
    if (isset($channel->link)) {
        $html .= '<p class="feed-url"><a href="' . htmlspecialchars((string)$channel->link) . '">' . htmlspecialchars((string)$channel->link) . '</a></p>';
    }
    
    // Feed items
    $items = isset($channel->item) ? $channel->item : (isset($xml->item) ? $xml->item : []);
    
    if (count($items) > 0) {
        $html .= '<div class="feed-items">';
        foreach ($items as $item) {
            $html .= '<article class="feed-item">';
            
            if (isset($item->title)) {
                $html .= '<h2>' . htmlspecialchars((string)$item->title) . '</h2>';
            }
            
            if (isset($item->pubDate)) {
                $html .= '<time class="feed-date">' . htmlspecialchars((string)$item->pubDate) . '</time>';
            } elseif (isset($item->date)) {
                $html .= '<time class="feed-date">' . htmlspecialchars((string)$item->date) . '</time>';
            }
            
            if (isset($item->link)) {
                $html .= '<p class="feed-item-url"><a href="' . htmlspecialchars((string)$item->link) . '">' . htmlspecialchars((string)$item->link) . '</a></p>';
            }
            
            if (isset($item->description)) {
                $html .= '<div class="feed-content"><p>' . nl2br(htmlspecialchars((string)$item->description)) . '</p></div>';
            } elseif (isset($item->content)) {
                $html .= '<div class="feed-content"><p>' . nl2br(htmlspecialchars((string)$item->content)) . '</p></div>';
            }
            
            $html .= '</article>';
        }
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

?>
