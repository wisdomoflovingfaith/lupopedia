<?php
/**
 * PHP render helpers for saved-collections-nav try2 (.dropdown / .floating-submenu).
 * Loaded by saved-collections-nav-try2.php and debug_collections_dropmenus.php.
 */

if (!function_exists('lupo_try2_index_content_query_href')) {
    /**
     * Subdirectory-safe front-controller URL for a content slug (no pretty-path assumption).
     * Pattern: {public_path}/index.php?slug=&artifact_type=&memory_key=
     * Defaults: artifact_type text/markdown; memory_key content:{slug}
     *
     * @param string $public_path Rtrimmed LUPOPEDIA_PUBLIC_PATH (e.g. /lupopedia)
     * @param string $slug        Content slug (required; empty returns #)
     * @param string|null $artifact_type Optional override
     * @param string|null $memory_key    Optional override
     * @return string
     */
    function lupo_try2_index_content_query_href($public_path, $slug, $artifact_type = null, $memory_key = null)
    {
        $slug = is_string($slug) ? trim($slug) : '';
        if ($slug === '') {
            return '#';
        }
        $base = is_string($public_path) ? rtrim($public_path, '/') : '';
        if ($artifact_type === null || $artifact_type === '') {
            $artifact_type = 'text/markdown';
        }
        if ($memory_key === null || $memory_key === '') {
            $memory_key = 'content:' . $slug;
        }
        $q = http_build_query(
            array(
                'slug' => $slug,
                'artifact_type' => $artifact_type,
                'memory_key' => $memory_key,
            ),
            '',
            '&',
            PHP_QUERY_RFC3986
        );

        return $base . '/index.php?' . $q;
    }
}

if (!function_exists('lupo_try2_render_tab_children_links')) {
    /**
     * @param array $children
     * @param string $public_path Rtrimmed LUPOPEDIA_PUBLIC_PATH
     * @return void
     */
    function lupo_try2_render_tab_children_links($children, $public_path)
    {
        if (!is_array($children)) {
            return;
        }
        foreach ($children as $child) {
            if (!is_array($child)) {
                continue;
            }
            $itype = isset($child['item_type']) ? $child['item_type'] : '';
            if ($itype === 'tab') {
                $sub = isset($child['children']) ? $child['children'] : array();
                $label = isset($child['tab_name']) ? $child['tab_name'] : '';
                if ($label === '') {
                    continue;
                }
                echo '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
                echo '<span class="menu-item-label">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
                echo '<div class="floating-submenu">';
                lupo_try2_render_tab_children_links($sub, $public_path);
                echo '</div></div>';
            } elseif ($itype === 'content') {
                $cid = isset($child['content_id']) ? (int) $child['content_id'] : (isset($child['item_id']) ? (int) $child['item_id'] : 0);
                $title = isset($child['title']) ? $child['title'] : 'Content';
                $cslug = isset($child['slug']) && is_string($child['slug']) ? trim($child['slug']) : '';
                if ($cslug !== '' && function_exists('lupo_try2_index_content_query_href')) {
                    $at = (isset($child['content_artifact_type']) && is_string($child['content_artifact_type']) && $child['content_artifact_type'] !== '')
                        ? $child['content_artifact_type'] : null;
                    $mk = (isset($child['content_memory_key']) && is_string($child['content_memory_key']) && $child['content_memory_key'] !== '')
                        ? $child['content_memory_key'] : null;
                    $url = lupo_try2_index_content_query_href($public_path, $cslug, $at, $mk);
                } else {
                    $url = $public_path . '/content.php?id=' . $cid;
                }
                echo '<a class="menu-item" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</a>';
            } elseif ($itype === 'link') {
                $url = isset($child['url']) ? $child['url'] : '#';
                $label = isset($child['label']) ? $child['label'] : 'Link';
                echo '<a class="menu-item" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
            }
        }
    }
}

if (!function_exists('lupo_try2_green_tab_child_entry_count')) {
    /**
     * Badge count contribution for one _children row (mapped list = number of links).
     *
     * @param array $child
     * @return int
     */
    function lupo_try2_green_tab_child_entry_count($child)
    {
        if (!is_array($child)) {
            return 0;
        }
        if (!empty($child['map_contents']) && is_array($child['map_contents'])) {
            $n = 0;
            foreach ($child['map_contents'] as $mc) {
                if (is_array($mc) && !empty($mc['name']) && is_string($mc['name'])) {
                    $n++;
                }
            }
            return $n > 0 ? $n : 0;
        }
        $cname = isset($child['name']) && is_string($child['name']) ? $child['name'] : '';
        return ($cname !== '') ? 1 : 0;
    }
}

if (!function_exists('lupo_try2_green_tab_badge_count')) {
    /**
     * Sum of dropdown link rows for a root tab (for green button badge).
     *
     * @param array $sub_tabs
     * @return int
     */
    function lupo_try2_green_tab_badge_count($sub_tabs)
    {
        if (!is_array($sub_tabs)) {
            return 0;
        }
        $sum = 0;
        if (!empty($sub_tabs['_children']) && is_array($sub_tabs['_children'])) {
            foreach ($sub_tabs['_children'] as $ch) {
                $sum += lupo_try2_green_tab_child_entry_count($ch);
            }
            return $sum;
        }
        foreach ($sub_tabs as $key => $value) {
            if (is_string($key) && strlen($key) > 0 && $key[0] === '_') {
                continue;
            }
            if (is_string($value)) {
                $sum++;
            }
        }
        return $sum;
    }
}

if (!function_exists('lupo_try2_render_green_tab_child_row')) {
    /**
     * One row in green tab dropdown: flyout for map_contents, else single link.
     *
     * @param array  $child
     * @param string $public_path
     * @return void
     */
    function lupo_try2_render_green_tab_child_row($child, $public_path)
    {
        if (!is_array($child)) {
            return;
        }
        $cname = isset($child['name']) && is_string($child['name']) ? $child['name'] : '';
        if (!empty($child['map_contents']) && is_array($child['map_contents'])) {
            if ($cname === '') {
                return;
            }
            echo '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
            echo '<span class="menu-item-label">' . htmlspecialchars($cname, ENT_QUOTES, 'UTF-8') . '</span>';
            echo '<div class="floating-submenu">';
            foreach ($child['map_contents'] as $mc) {
                if (!is_array($mc)) {
                    continue;
                }
                $title = isset($mc['name']) && is_string($mc['name']) ? $mc['name'] : '';
                if ($title === '') {
                    continue;
                }
                $cslug = isset($mc['slug']) && is_string($mc['slug']) ? trim($mc['slug']) : '';
                if ($cslug === '') {
                    continue;
                }
                $sub_tab_url = function_exists('lupo_try2_index_content_query_href')
                    ? lupo_try2_index_content_query_href($public_path, $cslug, null, null)
                    : ($public_path . '/index.php?slug=' . rawurlencode($cslug));
                echo '<a class="menu-item" href="' . htmlspecialchars($sub_tab_url, ENT_QUOTES, 'UTF-8') . '" role="menuitem" tabindex="0">'
                    . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</a>';
            }
            echo '</div></div>';
            return;
        }
        if ($cname === '') {
            return;
        }
        if (isset($child['slug']) && is_string($child['slug']) && $child['slug'] !== '') {
            $sub_tab_slug = $child['slug'];
        } else {
            $sub_tab_slug = strtolower(str_replace(' ', '-', $cname));
        }
        $sub_at = (isset($child['content_artifact_type']) && is_string($child['content_artifact_type']) && $child['content_artifact_type'] !== '')
            ? $child['content_artifact_type'] : null;
        $sub_mk = (isset($child['content_memory_key']) && is_string($child['content_memory_key']) && $child['content_memory_key'] !== '')
            ? $child['content_memory_key'] : null;
        $sub_tab_url = function_exists('lupo_try2_index_content_query_href')
            ? lupo_try2_index_content_query_href($public_path, $sub_tab_slug, $sub_at, $sub_mk)
            : ($public_path . '/index.php?slug=' . rawurlencode($sub_tab_slug));
        echo '<a href="' . htmlspecialchars($sub_tab_url, ENT_QUOTES, 'UTF-8') . '" class="menu-item" role="menuitem" tabindex="0">'
            . htmlspecialchars($cname, ENT_QUOTES, 'UTF-8') . '</a>';
    }
}

if (!function_exists('lupo_try2_render_green_collection_tab_panel')) {
    /**
     * Echo dropdown-panel body for one root green tab.
     *
     * @param array  $sub_tabs
     * @param string $public_path
     * @return void
     */
    function lupo_try2_render_green_collection_tab_panel($sub_tabs, $public_path)
    {
        if (!is_array($sub_tabs)) {
            return;
        }
        if (!empty($sub_tabs['_children']) && is_array($sub_tabs['_children'])) {
            foreach ($sub_tabs['_children'] as $child) {
                lupo_try2_render_green_tab_child_row($child, $public_path);
            }
            return;
        }
        foreach ($sub_tabs as $key => $value) {
            if (is_string($key) && strlen($key) > 0 && $key[0] === '_') {
                continue;
            }
            if (!is_string($value)) {
                continue;
            }
            $sub_tab_slug = strtolower(str_replace(' ', '-', $value));
            $sub_tab_url = function_exists('lupo_try2_index_content_query_href')
                ? lupo_try2_index_content_query_href($public_path, $sub_tab_slug)
                : ($public_path . '/index.php?slug=' . rawurlencode($sub_tab_slug));
            echo '<a href="' . htmlspecialchars($sub_tab_url, ENT_QUOTES, 'UTF-8') . '" class="menu-item" role="menuitem" tabindex="0">'
                . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</a>';
        }
    }
}

if (!function_exists('lupo_try2_render_master_nav_groups')) {
    /**
     * Master Collections dropdown: group row + flyout links (full reload with lupo_collection_id).
     *
     * @param array $groups From lupo_nav_menu_collection_groups()
     * @return void
     */
    function lupo_try2_render_master_nav_groups($groups)
    {
        if (!is_array($groups) || empty($groups)) {
            return;
        }
        foreach ($groups as $g) {
            if (!is_array($g) || empty($g['group_label'])) {
                continue;
            }
            $items = isset($g['items']) && is_array($g['items']) ? $g['items'] : array();
            if (empty($items)) {
                continue;
            }
            $gl = (string) $g['group_label'];
            echo '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
            echo '<span class="menu-item-label">' . htmlspecialchars($gl, ENT_QUOTES, 'UTF-8') . '</span>';
            echo '<div class="floating-submenu">';
            foreach ($items as $it) {
                if (!is_array($it) || !isset($it['collection_id'])) {
                    continue;
                }
                $cid = (int) $it['collection_id'];
                $lab = isset($it['label']) ? (string) $it['label'] : ('Collection ' . $cid);
                $href = function_exists('lupo_collection_context_switch_href') ? lupo_collection_context_switch_href($cid) : '#';
                echo '<a class="menu-item" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" role="menuitem" tabindex="0">'
                    . htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') . '</a>';
            }
            echo '</div></div>';
        }
    }
}

if (!function_exists('lupo_try2_render_tabs_for_type')) {
    /**
     * @param array $tabs
     * @param string $public_path
     * @return void
     */
    function lupo_try2_render_tabs_for_type($tabs, $public_path)
    {
        if (empty($tabs) || !is_array($tabs)) {
            return;
        }
        foreach ($tabs as $tab) {
            if (!is_array($tab) || empty($tab['tab_name'])) {
                continue;
            }
            $name = $tab['tab_name'];
            $hasChildren = !empty($tab['children']);
            if ($hasChildren) {
                echo '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
                echo '<span class="menu-item-label">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</span>';
                echo '<div class="floating-submenu">';
                lupo_try2_render_tab_children_links($tab['children'], $public_path);
                echo '</div></div>';
            } else {
                echo '<span class="menu-item">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</span>';
            }
        }
    }
}
