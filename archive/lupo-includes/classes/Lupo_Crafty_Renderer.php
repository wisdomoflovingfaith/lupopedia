<?php
// Lupo_Crafty_Renderer: Legacy Crafty Syntax HTML renderer
class Lupo_Crafty_Renderer {
    public static function render($text) {
        // Legacy: convert [b]...[/b], [i]...[/i], [url=...]...[/url], [code]...[/code]
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\[b\](.*?)\[\/b\]/s', '<strong>$1</strong>', $text);
        $text = preg_replace('/\[i\](.*?)\[\/i\]/s', '<em>$1</em>', $text);
        $text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/s', '<a href="$1" target="_blank">$2</a>', $text);
        $text = preg_replace('/\[code\](.*?)\[\/code\]/s', '<pre><code>$1</code></pre>', $text);
        // Paragraphs
        $text = preg_replace('/\n{2,}/', "</p><p>", $text);
        return '<p>' . $text . '</p>';
    }
}
