<?php
/**
 * Shared helpers for saved-collections-nav dropdown markup (submenus, nested tabs).
 * Loaded by saved-collections-nav.php and saved-collections-dropdown-group.php.
 */

if (!function_exists('generate_submenu_id')) {
    /**
     * @param string $prefix
     * @param string $tabName
     * @return string
     */
    function generate_submenu_id($prefix, $tabName) {
        $sanitized = strtolower(preg_replace('/[^a-zA-Z0-9_]+/', '-', $tabName));
        return 'submenu-' . $prefix . '-' . $sanitized;
    }
}

if (!function_exists('render_tab_item')) {
    /**
     * @param array $tab
     * @param string $prefix
     * @param mixed $db Unused (compatibility)
     * @return void
     */
    function render_tab_item($tab, $prefix, $db = null) {
        $submenuId = generate_submenu_id($prefix, $tab['tab_name']);
        $hasChildren = !empty($tab['children']);

        ?>
    <div class="saved-collections-submenu">
        <span class="saved-collections-submenu-trigger"
              onclick="toggleSubmenu(this, event)"
              role="menuitem"
              aria-expanded="false"
              aria-haspopup="<?php echo $hasChildren ? 'true' : 'false'; ?>"
              data-submenu-id="<?php echo htmlspecialchars($submenuId); ?>"
              tabindex="0"
              onkeydown="if(event.key==='Enter'||event.key===' '){toggleSubmenu(this,event);}">
            <span><?php echo htmlspecialchars($tab['tab_name']); ?></span>
            <?php if (isset($tab['item_count']) && $tab['item_count'] > 0): ?>
                <span class="count"><?php echo $tab['item_count']; ?></span>
            <?php endif; ?>
        </span>
        <?php if ($hasChildren): ?>
            <div class="saved-collections-submenu-content"
                 id="<?php echo htmlspecialchars($submenuId); ?>"
                 role="menu">
                <?php
                foreach ($tab['children'] as $child) {
                    if ($child['item_type'] === 'tab') {
                        $nestedPrefix = $prefix . '-' . preg_replace('/[^a-zA-Z0-9_]+/', '-', strtolower($tab['tab_name']));
                        render_tab_item(array(
                            'tab_name' => $child['tab_name'],
                            'children' => isset($child['children']) ? $child['children'] : array(),
                            'item_count' => isset($child['item_count']) ? $child['item_count'] : 0,
                            'id' => isset($child['tab_id']) ? $child['tab_id'] : $child['item_id'],
                        ), $nestedPrefix);
                    } elseif ($child['item_type'] === 'content') {
                        $pub = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
                        if (!function_exists('lupo_try2_index_content_query_href') && defined('LUPOPEDIA_PATH')) {
                            $try2f = LUPOPEDIA_PATH . '/includes/themes/default/components/saved-collections-nav-try2-functions.php';
                            if (is_file($try2f)) {
                                require_once $try2f;
                            }
                        }
                        $cslug = isset($child['slug']) && is_string($child['slug']) ? trim($child['slug']) : '';
                        if ($cslug !== '' && function_exists('lupo_try2_index_content_query_href')) {
                            $at = (isset($child['content_artifact_type']) && is_string($child['content_artifact_type']) && $child['content_artifact_type'] !== '')
                                ? $child['content_artifact_type'] : null;
                            $mk = (isset($child['content_memory_key']) && is_string($child['content_memory_key']) && $child['content_memory_key'] !== '')
                                ? $child['content_memory_key'] : null;
                            $url = lupo_try2_index_content_query_href($pub, $cslug, $at, $mk);
                        } else {
                            $url = ($pub !== '' ? $pub : '') . '/content.php?id=' . (isset($child['content_id']) ? $child['content_id'] : $child['item_id']);
                        }
                        $title = isset($child['title']) ? $child['title'] : (function_exists('lupo_t') ? lupo_t('header.content_link_default', 'Content') : 'Content');
                        ?>
                        <a href="<?php echo htmlspecialchars($url); ?>"
                           class="saved-collections-item"
                           role="menuitem"
                           tabindex="0">
                            <?php echo htmlspecialchars($title); ?>
                        </a>
                        <?php
                    } elseif ($child['item_type'] === 'link') {
                        $url = isset($child['url']) ? $child['url'] : '#';
                        $label = isset($child['label']) ? $child['label'] : (function_exists('lupo_t') ? lupo_t('header.link_default', 'Link') : 'Link');
                        ?>
                        <a href="<?php echo htmlspecialchars($url); ?>"
                           class="saved-collections-item"
                           role="menuitem"
                           tabindex="0">
                            <?php echo htmlspecialchars($label); ?>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
        <?php
    }
}
