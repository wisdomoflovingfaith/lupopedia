<?php
/**
 * HTML partial: content library index (title, slug, summary rows).
 * Invoked from content_show_library_index() after LUPOPEDIA_CONFIG_LOADED.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

/**
 * @param array  $items       List of arrays: title, slug, summary, content_type
 * @param string $index_title Page H1
 * @param string $intro       Intro paragraph
 * @param string $public_path LUPOPEDIA_PUBLIC_PATH (leading slash + folder)
 * @return string HTML
 */
function render_library_list_html($items, $index_title, $intro, $public_path) {
    $base = rtrim((string) $public_path, '/');
    $emptyMsg = function_exists('lupo_t') ? lupo_t('content.library.empty', 'No entries to show.') : 'No entries to show.';

    $html = '<div class="content-library-index">';
    $html .= '<h1>' . htmlspecialchars($index_title, ENT_QUOTES, 'UTF-8') . '</h1>';
    $html .= '<p class="content-library-intro">' . htmlspecialchars($intro, ENT_QUOTES, 'UTF-8') . '</p>';

    if (empty($items) || !is_array($items)) {
        $html .= '<p class="content-library-empty">' . htmlspecialchars($emptyMsg, ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '</div>';
        return $html;
    }

    $html .= '<ul class="content-library-list">';
    foreach ($items as $row) {
        if (!is_array($row)) {
            continue;
        }
        $slug = isset($row['slug']) ? $row['slug'] : '';
        if ($slug === '') {
            continue;
        }
        $title = isset($row['title']) ? $row['title'] : $slug;
        $ct = isset($row['content_type']) ? $row['content_type'] : '';
        $summary = isset($row['summary']) ? $row['summary'] : '';
        $mk = 'content:' . $slug;
        $href = $base . '/content/index.php?slug=' . rawurlencode($slug)
            . '&artifact_type=' . rawurlencode($ct)
            . '&memory_key=' . rawurlencode($mk);
        $html .= '<li>';
        $html .= '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</a>';
        $html .= ' <span class="content-lib-meta">(' . htmlspecialchars($ct, ENT_QUOTES, 'UTF-8') . ')</span>';
        if ($summary !== '') {
            $html .= '<span class="content-library-summary">' . htmlspecialchars($summary, ENT_QUOTES, 'UTF-8') . '</span>';
        }
        $html .= '</li>';
    }
    $html .= '</ul></div>';

    return $html;
}
