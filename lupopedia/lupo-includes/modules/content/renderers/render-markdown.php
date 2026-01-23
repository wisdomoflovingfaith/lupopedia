<?php
/**
 * wolfie.header.identity: render-markdown
 * wolfie.header.placement: /lupo-includes/modules/content/renderers/render-markdown.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: render-markdown
 *   message: "Implemented Markdown to HTML renderer: basic parser supporting headers, bold, italic, links, lists, code blocks, and paragraphs."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. render-markdown.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Markdown Renderer
 * ---------------------------------------------------------
 * 
 * Basic Markdown parser (no external dependencies)
 * Supports: headers, bold, italic, links, lists, code blocks, paragraphs
 */

/**
 * Process inline Markdown (bold, italic, links, code, images)
 * 
 * @param string $text Text to process
 * @return string HTML
 */
function process_inline_markdown($text) {
    // Code blocks should already be handled, so process inline code
    $text = preg_replace_callback('/`([^`]+)`/', function($matches) {
        return '<code>' . htmlspecialchars($matches[1]) . '</code>';
    }, $text);
    
    // Images ![alt](url)
    $text = preg_replace_callback('/!\[([^\]]*)\]\(([^\)]+)\)/', function($matches) {
        $alt = htmlspecialchars($matches[1]);
        $url = htmlspecialchars($matches[2]);
        return '<img src="' . $url . '" alt="' . $alt . '">';
    }, $text);
    
    // Links [text](url)
    $text = preg_replace_callback('/\[([^\]]+)\]\(([^\)]+)\)/', function($matches) {
        $link_text = htmlspecialchars($matches[1]);
        $url = htmlspecialchars($matches[2]);
        return '<a href="' . $url . '">' . $link_text . '</a>';
    }, $text);
    
    // Bold (**text** or __text__) - do before italic to avoid conflicts
    $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
    $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);
    
    // Italic (*text* or _text_) - but not if already in bold
    $text = preg_replace('/(?<!\*)\*([^*]+?)\*(?!\*)/', '<em>$1</em>', $text);
    $text = preg_replace('/(?<!_)_([^_]+?)_(?!_)/', '<em>$1</em>', $text);
    
    // Escape remaining HTML
    $text = htmlspecialchars($text);
    
    // Restore already-processed HTML tags
    $text = str_replace(
        ['&lt;strong&gt;', '&lt;/strong&gt;', '&lt;em&gt;', '&lt;/em&gt;', '&lt;code&gt;', '&lt;/code&gt;', '&lt;a href=&quot;', '&quot;&gt;', '&lt;/a&gt;', '&lt;img src=&quot;', ' alt=&quot;', '&gt;'],
        ['<strong>', '</strong>', '<em>', '</em>', '<code>', '</code>', '<a href="', '">', '</a>', '<img src="', ' alt="', '>'],
        $text
    );
    
    return $text;
}

/**
 * Close open blocks (paragraphs, lists)
 * 
 * @param array &$result Output array
 * @param array &$paragraph_lines Current paragraph lines
 * @param array &$list_items Current list items
 * @param bool &$in_list Whether in a list
 * @param string &$list_type List type ('ul' or 'ol')
 */
function close_markdown_blocks(&$result, &$paragraph_lines, &$list_items, &$in_list, &$list_type) {
    if ($in_list) {
        $result[] = '<' . $list_type . '>' . implode('', $list_items) . '</' . $list_type . '>';
        $list_items = [];
        $in_list = false;
        $list_type = null;
    }
    if (!empty($paragraph_lines)) {
        $result[] = '<p>' . implode(' ', $paragraph_lines) . '</p>';
        $paragraph_lines = [];
    }
}

/**
 * Render Markdown content to HTML
 * 
 * @param string $body Markdown content
 * @return string Rendered HTML
 */
function render_markdown($body) {
    if (empty($body)) {
        return '';
    }
    
    // Split into lines for processing
    $lines = explode("\n", $body);
    $result = [];
    $in_code_block = false;
    $code_block_content = [];
    $in_list = false;
    $list_type = null;
    $list_items = [];
    $paragraph_lines = [];
    
    foreach ($lines as $line) {
        $trimmed = trim($line);
        $original_line = $line;
        
        // Code blocks (```code```)
        if (preg_match('/^```/', $trimmed)) {
            if ($in_code_block) {
                // Close code block
                $result[] = '<pre><code>' . htmlspecialchars(implode("\n", $code_block_content)) . '</code></pre>';
                $code_block_content = [];
                $in_code_block = false;
            } else {
                // Open code block - close any open paragraph/list first
                close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
                $in_code_block = true;
            }
            continue;
        }
        
        if ($in_code_block) {
            $code_block_content[] = $original_line;
            continue;
        }
        
        // Headers (must be at start of line)
        if (preg_match('/^###### (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<h6>' . htmlspecialchars($matches[1]) . '</h6>';
            continue;
        }
        if (preg_match('/^##### (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<h5>' . htmlspecialchars($matches[1]) . '</h5>';
            continue;
        }
        if (preg_match('/^#### (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<h4>' . htmlspecialchars($matches[1]) . '</h4>';
            continue;
        }
        if (preg_match('/^### (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<h3>' . htmlspecialchars($matches[1]) . '</h3>';
            continue;
        }
        if (preg_match('/^## (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<h2>' . htmlspecialchars($matches[1]) . '</h2>';
            continue;
        }
        if (preg_match('/^# (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<h1>' . htmlspecialchars($matches[1]) . '</h1>';
            continue;
        }
        
        // Horizontal rule
        if (preg_match('/^---+$/', $trimmed)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<hr>';
            continue;
        }
        
        // Blockquotes
        if (preg_match('/^> (.+)$/', $trimmed, $matches)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            $result[] = '<blockquote>' . process_inline_markdown($matches[1]) . '</blockquote>';
            continue;
        }
        
        // Lists
        if (preg_match('/^[\-\*] (.+)$/', $trimmed, $matches)) {
            if (!$in_list || $list_type !== 'ul') {
                if ($in_list && $list_type === 'ol') {
                    $result[] = '<ol>' . implode('', $list_items) . '</ol>';
                    $list_items = [];
                }
                $in_list = true;
                $list_type = 'ul';
            }
            $list_items[] = '<li>' . process_inline_markdown($matches[1]) . '</li>';
            continue;
        }
        
        if (preg_match('/^\d+\. (.+)$/', $trimmed, $matches)) {
            if (!$in_list || $list_type !== 'ol') {
                if ($in_list && $list_type === 'ul') {
                    $result[] = '<ul>' . implode('', $list_items) . '</ul>';
                    $list_items = [];
                }
                $in_list = true;
                $list_type = 'ol';
            }
            $list_items[] = '<li>' . process_inline_markdown($matches[1]) . '</li>';
            continue;
        }
        
        // Empty line - close blocks
        if (empty($trimmed)) {
            close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
            continue;
        }
        
        // Regular paragraph line
        $paragraph_lines[] = process_inline_markdown($trimmed);
    }
    
    // Close any remaining blocks
    close_markdown_blocks($result, $paragraph_lines, $list_items, $in_list, $list_type);
    
    return implode("\n", $result);
}

?>
