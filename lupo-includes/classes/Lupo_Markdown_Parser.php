<?php
// Lupo_Markdown_Parser: Doctrine-compliant markdown renderer
class Lupo_Markdown_Parser {
    public static function render($markdown) {
        // Minimal safe markdown: bold, italic, links, lists, code
        $markdown = htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8');
        // Bold
        $markdown = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $markdown);
        // Italic
        $markdown = preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $markdown);
        // Inline code
        $markdown = preg_replace('/`([^`]+)`/', '<code>$1</code>', $markdown);
        // Links
        $markdown = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2" target="_blank">$1</a>', $markdown);
        // Lists
        $markdown = preg_replace('/^\s*\* (.*)$/m', '<li>$1</li>', $markdown);
        $markdown = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $markdown);
        // Paragraphs
        $markdown = preg_replace('/\n{2,}/', "</p><p>", $markdown);
        return '<p>' . $markdown . '</p>';
    }
}
