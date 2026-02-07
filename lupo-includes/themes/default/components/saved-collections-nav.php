<?php
/**
 * Saved Collections Navigation Component
 * 
 * This component renders the saved collections navigation HTML.
 * It receives data arrays from the renderer and outputs HTML.
 * 
 * @param array $collectionsData Array structure from render_saved_collections()
 * @param bool $isUserLoggedIn Whether the user is logged in
 */

// Ensure data is set
if (!isset($collectionsData)) {
    $collectionsData = [];
}
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}

/**
 * Generate submenu ID from prefix and tab name
 * 
 * Creates a unique, sanitized ID for submenu elements.
 * Used to maintain consistent IDs for JavaScript interaction.
 * 
 * @param string $prefix Collection type prefix (e.g., 'who', 'what')
 * @param string $tabName The tab name
 * @return string Sanitized submenu ID
 */
function generate_submenu_id($prefix, $tabName) {
    $sanitized = strtolower(preg_replace('/[^a-zA-Z0-9_]+/', '-', $tabName));
    return 'submenu-' . $prefix . '-' . $sanitized;
}

/**
 * Render a tab item and its children recursively
 * 
 * Outputs HTML for a tab including its trigger and submenu content.
 * Recursively handles nested tabs, content items, and links.
 * Preserves all CSS classes, aria attributes, and JavaScript function names.
 * 
 * @param array $tab Tab data array (must include: tab_name, children, item_count, id)
 * @param string $prefix Collection type prefix for ID generation
 * @param PDO|null $db Database connection (not used, kept for compatibility)
 * @return void Outputs HTML directly
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
                        // Recursive call for nested tabs - child already has tab structure
                        $nestedPrefix = $prefix . '-' . preg_replace('/[^a-zA-Z0-9_]+/', '-', strtolower($tab['tab_name']));
                        render_tab_item([
                            'tab_name' => $child['tab_name'],
                            'children' => isset($child['children']) ? $child['children'] : [],
                            'item_count' => isset($child['item_count']) ? $child['item_count'] : 0,
                            'id' => isset($child['tab_id']) ? $child['tab_id'] : $child['item_id']
                        ], $nestedPrefix);
                    } elseif ($child['item_type'] === 'content') {
                        // Render content link
                        $url = '/content.php?id=' . (isset($child['content_id']) ? $child['content_id'] : $child['item_id']);
                        $title = isset($child['title']) ? $child['title'] : 'Content';
                        ?>
                        <a href="<?php echo htmlspecialchars($url); ?>" 
                           class="saved-collections-item"
                           role="menuitem"
                           tabindex="0">
                            <?php echo htmlspecialchars($title); ?>
                        </a>
                        <?php
                    } elseif ($child['item_type'] === 'link') {
                        // Render external link
                        $url = isset($child['url']) ? $child['url'] : '#';
                        $label = isset($child['label']) ? $child['label'] : 'Link';
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

?>

<script>
// Pass PHP login state to JavaScript
var isUserLoggedIn = <?php echo $isUserLoggedIn ? 'true' : 'false'; ?>;
</script>

<nav class="saved-collections-nav">
    <!-- Spacer div -->
    <div style="width: 50px; height: 40px;"></div>

    <div class="saved-collections-container">
        <?php foreach ($collectionsData as $type => $collectionTypeData): ?>
            <?php
            $dropdownId = 'dropdown-' . $type;
            $count = isset($collectionTypeData['count']) ? $collectionTypeData['count'] : 0;
            ?>
            <div class="saved-collections-dropdown" data-qa-type="<?php echo htmlspecialchars($type); ?>">
                <button class="saved-collections-button" 
                        onclick="toggleSavedCollectionsDropdown(this)"
                        aria-expanded="false"
                        aria-haspopup="true"
                        aria-controls="<?php echo htmlspecialchars($dropdownId); ?>"
                        data-qa-type="<?php echo htmlspecialchars($type); ?>">
                    <?php echo strtoupper(htmlspecialchars($type)); ?> <span class="count"><?php echo $count; ?></span>
                </button>
                <div class="saved-collections-dropdown-content" 
                     id="<?php echo htmlspecialchars($dropdownId); ?>"
                     role="menu">
                    <?php
                    if (!empty($collectionTypeData['tabs'])) {
                        foreach ($collectionTypeData['tabs'] as $tab) {
                            render_tab_item($tab, $type);
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div style="margin-left: auto; display: flex; gap: 8px;">
            <button class="recently-viewed-button" onclick="checkLoginAndSave()" style="background: #28a745; border-color: #28a745; color: #fff;">
                Save
            </button>
            <button class="recently-viewed-button" onclick="checkLoginAndLoad()" style="background: #17a2b8; border-color: #17a2b8; color: #fff;">
                Load
            </button>
            <button class="recently-viewed-button" id="editCollectionBtn" onclick="checkLoginAndEdit()" style="background: #ffc107; border-color: #ffc107; color: #000;">
                Edit
            </button>
        </div>
        
        <script>
        // Check login before allowing save/load/edit actions
        function checkLoginAndSave() {
            if (!isUserLoggedIn) {
                alert('Please log in to save collections.');
                return false;
            }
            showSaveCollectionModal();
        }
        
        function checkLoginAndLoad() {
            if (!isUserLoggedIn) {
                alert('Please log in to load collections.');
                return false;
            }
            showLoadCollectionModal();
        }
        
        function checkLoginAndEdit() {
            if (!isUserLoggedIn) {
                alert('Please log in to edit collections.');
                return false;
            }
            editCurrentCollection();
        }
        </script>
    </div>
</nav>
