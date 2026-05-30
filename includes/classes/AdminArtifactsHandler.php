<?php
/**
 * Admin Content (artifacts) section — lists lupo_contents with links to the physical content viewer.
 * PHP 5.6+ compatible; PDO_DB and LUPO_TABLE_PREFIX.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminArtifactsHandler
{

    /**
     * @param object $db PDO_DB
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base Public path base (e.g. /lupopedia)
     * @return string HTML
     */
    public static function render($db, $prefix, $base)
    {
        $t = $db->quoteIdentifier($prefix . 'contents');
        $rows = $db->fetchAll(
            "SELECT content_id, title, slug, content_type, status, visibility, department_id
             FROM {$t}
             WHERE is_deleted = 0 AND (is_active = 1 OR is_active IS NULL)
             ORDER BY title ASC
             LIMIT 500",
            array()
        );

        $intro = function_exists('lupo_t')
            ? lupo_t('admin.artifacts.table_intro', 'Open an entry in the public content viewer (physical route: content/index.php?slug=…).')
            : 'Open an entry in the public content viewer (physical route: content/index.php?slug=…).';

        $html = '<div class="admin-artifacts">';
        $html .= '<p class="admin-section-description">' . htmlspecialchars($intro, ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '<table class="admin-table admin-artifacts-table"><thead><tr>';
        $html .= '<th>' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.col_title', 'Title') : 'Title', ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.col_slug', 'Slug') : 'Slug', ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.col_type', 'Type') : 'Type', ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.col_memory_key', 'Memory key') : 'Memory key', ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.col_view', 'View') : 'View', ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '</tr></thead><tbody>';

        if (empty($rows)) {
            $html .= '<tr><td colspan="5">' . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.empty', 'No content rows found.') : 'No content rows found.', ENT_QUOTES, 'UTF-8') . '</td></tr>';
        } else {
            $baseTrim = rtrim((string) $base, '/');
            foreach ($rows as $row) {
                $slug = isset($row['slug']) ? (string) $row['slug'] : '';
                $title = isset($row['title']) ? (string) $row['title'] : $slug;
                $ctype = isset($row['content_type']) ? (string) $row['content_type'] : '';
                $memoryKey = ($slug !== '') ? ('content:' . $slug) : '';
                $href = $baseTrim . '/content/index.php?slug=' . rawurlencode($slug)
                    . '&artifact_type=' . rawurlencode($ctype)
                    . '&memory_key=' . rawurlencode($memoryKey);
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</td>';
                $html .= '<td><code>' . htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') . '</code></td>';
                $html .= '<td>' . htmlspecialchars($ctype, ENT_QUOTES, 'UTF-8') . '</td>';
                $html .= '<td><code>' . htmlspecialchars($memoryKey, ENT_QUOTES, 'UTF-8') . '</code></td>';
                $html .= '<td><a class="admin-link" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">'
                    . htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.artifacts.col_view', 'View') : 'View', ENT_QUOTES, 'UTF-8')
                    . '</a></td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table></div>';
        return $html;
    }
}
