<?php
/**
 * wolfie.header.identity: main-layout
 * wolfie.header.placement: /lupo-includes/ui/layouts/main_layout.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: main-layout
 *   message: "Updated main layout to match template structure: decorative border frame, collections dropdown, AJAX-loaded tabs, bottom action bar. Maintains all PHP logic while updating UI structure."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. main_layout.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Main Layout Template
 * ---------------------------------------------------------
 * 
 * This is the global Lupopedia UI - the "desktop environment"
 * that wraps all content. Updated to match template structure.
 */

// Define UI path if not already defined
if (!defined('LUPO_UI_PATH')) {
    define('LUPO_UI_PATH', LUPOPEDIA_PATH . '/lupo-includes/ui');
}

// Extract content fields with defaults
$page_title = isset($content['title']) ? $content['title'] : 'Lupopedia';
$page_description = isset($content['description']) ? $content['description'] : '';
$page_keywords = isset($content['seo_keywords']) ? $content['seo_keywords'] : '';

// Initialize user session variables
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}
if (!isset($currentUserId)) {
    $currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $isUserLoggedIn = ($currentUserId > 0);
}

// Load collections data for saved collections nav
if (!isset($collectionsData)) {
    if (function_exists('render_saved_collections')) {
        $collectionsData = render_saved_collections($currentUserId);
    } else {
        // Load the function if available
        $renderer_path = LUPOPEDIA_PATH . '/lupo-includes/functions/render-saved-collections.php';
        if (file_exists($renderer_path)) {
            require_once $renderer_path;
            $collectionsData = render_saved_collections($currentUserId);
        } else {
            $collectionsData = [];
        }
    }
}

// Ensure required variables exist for collection tabs
if (!isset($current_collection) || $current_collection === null) {
    $current_collection = 'System Collection';
}
if (!isset($tabs_data) || !is_array($tabs_data)) {
    $tabs_data = [];
}
if (!isset($collection_id)) {
    $collection_id = null;
}

// Initialize content sections for contents dropdown
if (!isset($contentSections)) {
    $contentSections = isset($content['content_sections']) ? $content['content_sections'] : [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - LUPOPEDIA</title>
    <link rel="icon" type="image/x-icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="shortcut icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main.css">
    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/lupopedia.js"></script>
    
    <?php
    // Load UI assets (CSS and JS) from ui-loader.php
    if (!function_exists('lupo_print_ui_css')) {
        if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/ui/ui-loader.php')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/ui/ui-loader.php';
        }
    }
    if (function_exists('lupo_print_ui_css')) {
        lupo_print_ui_css();
    }
    ?>
    
    <?php if (!empty($page_description)): ?>
        <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <?php endif; ?>
    
    <?php if (!empty($page_keywords)): ?>
        <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <?php endif; ?>
    
    <!-- Saved Collections Navigation Styles -->
    <style>
    /* Saved Collections Navigation - Q/A Tag Based */
    .saved-collections-nav {
        background: #E8F5E9;
        padding: 10px 20px;
        border-bottom: 2px solid #4CAF50;
        margin-bottom: 0;
        position: fixed;
        top: 58px;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        height: 49px;
        display: flex;
        align-items: center;
    }
    
    .saved-collections-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        width: 100%;
        height: 48px;
    }
    
    .saved-collections-dropdown {
        position: relative;
        display: inline-block;
    }
    
    .saved-collections-button {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    /* collections tab uses blue color instead of green */
    .saved-collections-button[data-qa-type="collections"],
    .saved-collections-dropdown[data-qa-type="collections"] .saved-collections-button {
        background: #2973e4;
    }
    
    .saved-collections-dropdown[data-qa-type="collections"] .saved-collections-button:hover {
        background: #1f5bb8;
    }
    
    .saved-collections-button:hover {
        background: #45a049;
    }
    
    .saved-collections-button .count {
        background: rgba(255,255,255,0.3);
        color: white;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .saved-collections-dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        min-width: 220px;
        max-width: 300px;
        box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
        border: 1px solid #ddd;
        border-radius: 4px;
        z-index: 10000;
        max-height: 500px;
        overflow-y: auto;
        margin-top: 2px;
        padding: 4px 0;
    }
    
    .saved-collections-dropdown.active .saved-collections-dropdown-content {
        display: block;
    }
    
    .saved-collections-submenu {
        position: relative;
    }
    
    .saved-collections-submenu-trigger {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        color: #333;
        text-decoration: none;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
        white-space: nowrap;
    }
    
    .saved-collections-submenu-trigger:hover {
        background: #f5f5f5;
    }
    
    .saved-collections-submenu-trigger::after {
        content: '▶';
        margin-left: 8px;
        font-size: 10px;
        color: #999;
        transition: transform 0.2s;
    }
    
    .saved-collections-submenu.active .saved-collections-submenu-trigger::after {
        transform: rotate(90deg);
    }
    
    .saved-collections-submenu-trigger .count {
        background: #4CAF50;
        color: white;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
        margin-left: auto;
        margin-right: 8px;
    }
    
    .saved-collections-submenu-content {
        display: none;
        position: absolute;
        background: white;
        min-width: 280px;
        max-width: 400px;
        box-shadow: 4px 4px 12px rgba(0,0,0,0.25);
        border: 1px solid #ccc;
        border-radius: 4px;
        z-index: 10001;
        max-height: 500px;
        overflow-y: auto;
        padding: 4px 0;
    }
    
    .saved-collections-submenu-content.active {
        display: block;
    }
    
    .saved-collections-item {
        display: block;
        padding: 8px 12px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
        font-size: 13px;
    }
    
    .saved-collections-item:hover {
        background: #f0f7ff;
        color: #0066cc;
    }
    
    .saved-collections-item:last-child {
        border-bottom: none;
    }
    
    .saved-collections-item.selected {
        background: #e3f2fd;
        font-weight: 600;
    }

    #firstHeading { 
        font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif; 
        font-size: 32px;
        line-height: 42px;
        font-weight: 600;
        margin: 0;
        padding: 0;
        overflow: hidden;
        white-space: nowrap;
    }

    /* Content Container with Decorative Borders */
    .content-list-container {
        display: flex;
        flex-wrap: wrap;
        width: 100vw;
        height: calc(100vh - 107px);
        position: fixed;
        top: 107px;
        left: 0;
    }

    /* Row 1: Top Border */
    .resources-top-left {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s1b.png');
    }

    .resources-top-center {
        width: calc(100vw - 118px);
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s2b.png');
        background-repeat: repeat;
        display: flex;
        align-items: flex-start;
    }

    .resources-top-right {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s3b.png');
    }

    /* Row 2: Middle Border and Content */
    .resources-middle-left {
        width: 54px;
        height: calc(100vh - 107px - 78px);
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s4b.png');
        background-repeat: repeat-y;
    }

    .resources-middle-center {
        width: calc(100vw - 118px);
        height: calc(100vh - 107px - 78px);
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s5.png');
        background-repeat: repeat;
        overflow-y: auto;
        padding: 20px;
    }

    .resources-middle-right {
        width: 54px;
        height: calc(100vh - 107px - 78px);
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s6b.png');
        background-repeat: repeat-y;
    }

    /* Row 3: Bottom Border */
    .resources-bottom-left {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s7b.png');
    }

    .resources-bottom-center {
        width: calc(100vw - 118px);
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s8b.png');
        background-repeat: repeat;
    }

    .resources-bottom-right {
        width: 54px;
        height: 42px;
        background: url('<?= LUPOPEDIA_PUBLIC_PATH ?>/images/s9b.png');
    }

    /* Dropdown Styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 450px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 4px;
    }

    .dropdown-content a {
        color: black;
        padding: 2px 6px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown-content.show {
        display: block;
    }

    #contentsDropdown {
        max-height: 400px;
        overflow-y: auto;
    }

    #shortcutDropdown {
        min-width: 450px;
        padding: 10px;
        background: #fff;
        box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
    }

    .add-action {
        color: #28a745 !important;
        font-size: 0.85em;
        font-style: italic;
        padding-left: 25px !important;
    }

    .add-action:hover {
        background-color: #e8f5e9 !important;
        text-decoration: underline;
    }

    .add-action.global {
        font-weight: bold;
        font-style: normal;
        background-color: #f0fdf4;
    }

    .main-tab { font-weight: bold; }
    .sub-tab { padding-left: 20px !important; color: #555; }
    </style>
    
    <script>
        function toggleSavedCollectionsDropdown(button) {
            const dropdown = button.closest('.saved-collections-dropdown');
            const isActive = dropdown.classList.contains('active');
            
            // Close all other dropdowns and their submenus
            document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('active');
                    d.querySelectorAll('.saved-collections-submenu').forEach(s => {
                        s.classList.remove('active');
                        const content = s.querySelector('.saved-collections-submenu-content');
                        if (content && content.parentNode === document.body) {
                            content.remove();
                        } else if (content) {
                            content.style.display = 'none';
                        }
                        content.classList.remove('active');
                    });
                }
            });
            
            // Toggle this dropdown
            const newState = !isActive;
            dropdown.classList.toggle('active', newState);
            button.setAttribute('aria-expanded', newState.toString());
        }
        
        let openSubmenuContent = null;
        
        function toggleSubmenu(trigger, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const submenu = trigger.closest('.saved-collections-submenu');
            if (!submenu) return;
            
            const isActive = submenu.classList.contains('active');
            const submenuContent = submenu.querySelector('.saved-collections-submenu-content');
            if (!submenuContent) return;
            
            const isOpening = !isActive;
            const container = submenu.parentElement;
            
            let mouseX = null;
            let mouseY = null;
            if (event && event.clientX && event.clientY) {
                mouseX = event.clientX;
                mouseY = event.clientY;
            }
            
            if (container) {
                container.querySelectorAll(':scope > .saved-collections-submenu').forEach(s => {
                    if (s !== submenu) {
                        s.classList.remove('active');
                        const content = s.querySelector('.saved-collections-submenu-content');
                        if (content && content.classList.contains('active') && content.parentNode === document.body) {
                            content.remove();
                        } else if (content) {
                            content.style.display = 'none';
                        }
                        if (content) {
                            content.classList.remove('active');
                        }
                        s.querySelectorAll('.saved-collections-submenu').forEach(nested => {
                            nested.classList.remove('active');
                            const nestedContent = nested.querySelector('.saved-collections-submenu-content');
                            if (nestedContent && nestedContent.parentNode === document.body) {
                                nestedContent.remove();
                            } else if (nestedContent) {
                                nestedContent.style.display = 'none';
                            }
                            if (nestedContent) {
                                nestedContent.classList.remove('active');
                            }
                        });
                    }
                });
            }
            
            if (openSubmenuContent && openSubmenuContent.parentNode === document.body && 
                !openSubmenuContent.contains(submenu) && openSubmenuContent.id !== submenuContent.id) {
                openSubmenuContent.remove();
            }
            
            if (isOpening) {
                submenu.classList.add('active');
                const triggerRect = trigger.getBoundingClientRect();
                let positionedSubmenu = submenuContent;
                
                if (submenuContent.parentNode !== document.body || !submenuContent.classList.contains('active')) {
                    positionedSubmenu = submenuContent.cloneNode(true);
                    if (submenuContent.id) {
                        positionedSubmenu.setAttribute('data-source-id', submenuContent.id);
                    }
                    positionedSubmenu.querySelectorAll('.saved-collections-submenu-content').forEach(c => {
                        c.style.display = 'none';
                        c.classList.remove('active');
                    });
                    document.body.appendChild(positionedSubmenu);
                    openSubmenuContent = positionedSubmenu;
                } else {
                    positionedSubmenu = openSubmenuContent;
                }
                
                positionedSubmenu.classList.add('active');
                positionedSubmenu.style.position = 'absolute';
                positionedSubmenu.style.display = 'block';
                
                let leftPos = triggerRect.right + 4;
                let topPos = triggerRect.top;
                
                if (!triggerRect || triggerRect.width === 0 || triggerRect.height === 0 || 
                    isNaN(leftPos) || leftPos <= 0 || isNaN(topPos) || topPos <= 0) {
                    const parentRect = trigger.parentElement?.getBoundingClientRect();
                    if (parentRect && parentRect.width > 0 && parentRect.height > 0) {
                        leftPos = parentRect.right + 4;
                        topPos = parentRect.top;
                    } else if (mouseX !== null && mouseY !== null) {
                        leftPos = mouseX + 4;
                        topPos = mouseY;
                    } else {
                        leftPos = 200;
                        topPos = 200;
                    }
                }
                
                const viewportWidth = window.innerWidth;
                const submenuWidth = positionedSubmenu.offsetWidth || 280;
                if (leftPos + submenuWidth > viewportWidth) {
                    leftPos = triggerRect.left - submenuWidth - 4;
                    if (leftPos < 0) {
                        leftPos = Math.max(4, (viewportWidth - submenuWidth) / 2);
                    }
                }
                
                const viewportHeight = window.innerHeight;
                const submenuHeight = positionedSubmenu.offsetHeight;
                if (topPos + submenuHeight > viewportHeight) {
                    topPos = Math.max(4, viewportHeight - submenuHeight - 10);
                }
                
                positionedSubmenu.style.left = Math.max(0, leftPos) + 'px';
                positionedSubmenu.style.top = Math.max(0, topPos) + 'px';
                positionedSubmenu.style.zIndex = '10001';
            } else {
                submenu.classList.remove('active');
                let positionedSubmenu = null;
                if (submenuContent.id) {
                    positionedSubmenu = document.body.querySelector(`#${submenuContent.id}.active`) || 
                                       document.body.querySelector(`[data-source-id="${submenuContent.id}"].active`);
                }
                if (positionedSubmenu) {
                    positionedSubmenu.remove();
                }
                submenuContent.classList.remove('active');
                submenuContent.style.display = 'none';
                openSubmenuContent = null;
            }
        }
        
        let resizeTimeout;
        function handleResizeOrScroll() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const activeSubmenu = document.querySelector('.saved-collections-submenu.active');
                if (activeSubmenu) {
                    const trigger = activeSubmenu.querySelector('.saved-collections-submenu-trigger');
                    if (trigger) {
                        toggleSubmenu(trigger, null);
                        toggleSubmenu(trigger, null);
                    }
                }
            }, 100);
        }
        
        window.addEventListener('scroll', handleResizeOrScroll, true);
        window.addEventListener('resize', handleResizeOrScroll);
        
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.saved-collections-dropdown') && 
                !event.target.closest('.saved-collections-submenu-content')) {
                document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
                    d.classList.remove('active');
                });
                document.querySelectorAll('.saved-collections-submenu').forEach(s => {
                    s.classList.remove('active');
                });
                document.querySelectorAll('.saved-collections-submenu-content').forEach(content => {
                    if (content.parentNode === document.body) {
                        content.remove();
                    }
                    content.classList.remove('active');
                    content.style.display = 'none';
                });
                openSubmenuContent = null;
            }
        });
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
                    d.classList.remove('active');
                });
                document.querySelectorAll('.saved-collections-submenu').forEach(s => {
                    s.classList.remove('active');
                });
                document.querySelectorAll('.saved-collections-submenu-content').forEach(content => {
                    if (content.parentNode === document.body) {
                        content.remove();
                    }
                    content.classList.remove('active');
                    content.style.display = 'none';
                });
                openSubmenuContent = null;
            }
        });

        function toggleMenu(menuId) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.id !== menuId) {
                    openDropdown.classList.remove('show');
                }
            }
            document.getElementById(menuId).classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('img')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }

        function addNewItem(type, parentName = '') {
            if (event) event.stopPropagation();
            let message = (type === 'main') 
                ? "Enter name for new Main Tab:" 
                : `Enter new Sub-Tab name for "${parentName}":`;
            let userInput = prompt(message);
            if (userInput !== null && userInput.trim() !== "") {
                console.log(`Action: Create ${type}, Name: ${userInput}, Parent: ${parentName}`);
                alert(`Successfully added "${userInput}" to your collection!`);
            }
        }
    </script>
</head>
<body>

<?php 
// Top navigation bar
if (file_exists(LUPO_UI_PATH . '/components/topbar.php')) {
    include LUPO_UI_PATH . '/components/topbar.php';
}
?>

<!-- Saved Collections Navigation -->
<nav class="saved-collections-nav">
    <!-- Spacer div -->
    <div style="width: 50px; height: 40px;"></div>

    <div class="saved-collections-container">
        <!-- Tabs loaded by AJAX starts here -->
        <div id="collection-tabs-container">
            <?php
            // Render tabs if tabs_data is available
            if ($collection_id !== null && $collection_id > 0 && !empty($tabs_data) && is_array($tabs_data)) {
    foreach ($tabs_data as $main_tab => $sub_tabs) {
        $tab_slug = null;
        if (is_array($sub_tabs) && isset($sub_tabs['_slug'])) {
            $tab_slug = $sub_tabs['_slug'];
        } else {
            $tab_slug = strtolower(str_replace(' ', '-', $main_tab));
        }
                    $dropdownId = 'dropdown-' . strtolower(str_replace(' ', '-', $main_tab));
                    ?>
                    <div class="saved-collections-dropdown" data-qa-type="<?= htmlspecialchars(strtolower(str_replace(' ', '-', $main_tab))) ?>">
                        <button class="saved-collections-button" 
                                onclick="toggleSavedCollectionsDropdown(this)"
                                aria-expanded="false"
                                aria-haspopup="true"
                                aria-controls="<?= htmlspecialchars($dropdownId) ?>"
                                data-qa-type="<?= htmlspecialchars(strtolower(str_replace(' ', '-', $main_tab))) ?>">
                            <?= htmlspecialchars(strtoupper($main_tab)) ?> <span class="count"><?php 
                                $childCount = 0;
                                if (is_array($sub_tabs)) {
                                    foreach ($sub_tabs as $key => $value) {
                                        if ($key !== '_slug') {
                                            $childCount++;
                                        }
                                    }
                                }
                                echo $childCount;
                            ?></span>
                        </button>
                        <div class="saved-collections-dropdown-content" 
                             id="<?= htmlspecialchars($dropdownId) ?>"
                             role="menu">
                            <?php
                            // Render sub-tabs if available
                            if (is_array($sub_tabs)) {
                                foreach ($sub_tabs as $key => $value) {
                                    if ($key !== '_slug') {
                                        $sub_tab_slug = strtolower(str_replace(' ', '-', $value));
                                        $sub_tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $sub_tab_slug;
                                        ?>
                                        <a href="<?= htmlspecialchars($sub_tab_url) ?>" 
                                           class="saved-collections-item"
                                           role="menuitem"
                                           tabindex="0">
                                            <?= htmlspecialchars($value) ?>
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <!-- Tabs loaded by AJAX ends here -->
    </div>
</nav>

<!-- Save Collection Modal -->
<div id="saveCollectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
        <h3 style="margin-top: 0; color: #2c3e50;">💾 Save Recently Viewed Collection</h3>
        <p style="color: #6c757d; margin-bottom: 20px;">Give this collection a name to save your current browsing session.</p>
        
        <div id="updateExistingNotice" style="display: none; background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin-bottom: 15px;">
            <strong>⚠️ Update Existing:</strong> You're currently viewing collection "<span id="currentCollectionName"></span>". Save to update it, or enter a new name to create a copy.
        </div>
        
        <label for="collectionName" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Collection Name:</label>
        <input type="text" id="collectionName" placeholder="e.g., Bible Study Session, Research Project" style="width: 100%; padding: 12px; border: 2px solid #D4AF37; border-radius: 6px; font-size: 1rem; margin-bottom: 10px;">
        
        <label for="collectionDescription" style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600; color: #2c3e50;">Description (optional):</label>
        <textarea id="collectionDescription" placeholder="What is this collection for?" style="width: 100%; padding: 12px; border: 2px solid #D4AF37; border-radius: 6px; font-size: 1rem; margin-bottom: 20px; min-height: 80px;"></textarea>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeSaveCollectionModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
            <button onclick="saveCollection()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">💾 Save Collection</button>
        </div>
    </div>
</div>

<!-- Load Collection Modal -->
<div id="loadCollectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 12px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <h3 style="margin-top: 0; color: #2c3e50;">📂 Load Saved Collection</h3>
        <p style="color: #6c757d; margin-bottom: 20px;">Select a saved collection to restore your browsing session.</p>
        
        <div id="collectionsList" style="margin-bottom: 20px;">
            <div style="text-align: center; padding: 40px; color: #6c757d;">
                Loading your collections...
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeLoadCollectionModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Close</button>
        </div>
    </div>
</div>

<!-- JavaScript for Collection Management -->
<script>
let currentLoadedCollectionId = <?php echo $collection_id !== null ? $collection_id : 'null'; ?>;
let currentLoadedCollectionName = <?php echo $current_collection !== null ? json_encode($current_collection) : 'null'; ?>;

function editCurrentCollection() {
    if (currentLoadedCollectionId) {
        window.location.href = '<?= LUPOPEDIA_PUBLIC_PATH ?>/edit_collection.php?id=' + currentLoadedCollectionId;
    } else {
        alert('💡 Please save this collection first, then you can edit it!\n\nClick OK to open the Save dialog.');
        showSaveCollectionModal();
    }
}

function showSaveCollectionModal() {
    const modal = document.getElementById('saveCollectionModal');
    const nameInput = document.getElementById('collectionName');
    const updateNotice = document.getElementById('updateExistingNotice');
    
    if (currentLoadedCollectionId) {
        updateNotice.style.display = 'block';
        document.getElementById('currentCollectionName').textContent = currentLoadedCollectionName;
        nameInput.value = currentLoadedCollectionName;
} else {
        updateNotice.style.display = 'none';
        nameInput.value = '';
    }
    
    document.getElementById('collectionDescription').value = '';
    modal.style.display = 'flex';
    nameInput.focus();
}

function closeSaveCollectionModal() {
    document.getElementById('saveCollectionModal').style.display = 'none';
}

function showLoadCollectionModal() {
    const modal = document.getElementById('loadCollectionModal');
    modal.style.display = 'flex';
    loadCollectionsList();
}

function closeLoadCollectionModal() {
    document.getElementById('loadCollectionModal').style.display = 'none';
}

function saveCollection() {
    const name = document.getElementById('collectionName').value.trim();
    const description = document.getElementById('collectionDescription').value.trim();
    
    if (!name) {
        alert('Please enter a name for this collection');
        return;
    }
    
    const isUpdate = currentLoadedCollectionId && name === currentLoadedCollectionName;
    
    fetch('<?= LUPOPEDIA_PUBLIC_PATH ?>/api/save_collection.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            collection_name: name,
            description: description,
            update_existing: isUpdate,
            existing_collection_id: isUpdate ? currentLoadedCollectionId : null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Collection saved successfully!');
            currentLoadedCollectionId = data.collection_id;
            currentLoadedCollectionName = name;
            closeSaveCollectionModal();
        } else {
            alert('Error: ' + (data.error || 'Failed to save collection'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving collection. Please try again.');
    });
}

function loadCollectionsList() {
    const container = document.getElementById('collectionsList');
    container.innerHTML = '<div style="text-align: center; padding: 40px; color: #6c757d;">Loading...</div>';
    
    fetch('<?= LUPOPEDIA_PUBLIC_PATH ?>/api/list_collections.php')
    .then(response => response.json())
    .then(data => {
        if (data.success && data.collections.length > 0) {
            let html = '';
            data.collections.forEach(collection => {
                const isCurrentlyLoaded = (collection.id == currentLoadedCollectionId);
                html += `
                    <div style="border: 2px solid ${isCurrentlyLoaded ? '#28a745' : '#D4AF37'}; padding: 15px; border-radius: 8px; margin-bottom: 10px; ${isCurrentlyLoaded ? 'background: #d4edda;' : 'background: #f8f9fa;'}">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 8px 0; color: #2c3e50;">
                                    ${isCurrentlyLoaded ? '[Active] ' : ''}${htmlEscape(collection.collection_name)}
                                </h4>
                                <p style="margin: 0 0 8px 0; color: #6c757d; font-size: 0.9rem;">${htmlEscape(collection.description || 'No description')}</p>
                                <p style="margin: 0; color: #6c757d; font-size: 0.85rem;">
                                    ${collection.saved_collections_count || collection.item_count || 0} saved items
                                    <br><small>Created: ${new Date(collection.created_at).toLocaleString()}</small>
                                </p>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button onclick="loadCollectionById(${collection.id}, '${htmlEscape(collection.collection_name)}')" 
                                        style="padding: 8px 16px; background: #17a2b8; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; white-space: nowrap;">
                                    Load
                                </button>
                                <button onclick="deleteCollection(${collection.id})" 
                                        style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        } else {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #6c757d;">
                    <p>No saved collections yet.</p>
                    <p style="font-size: 0.9rem;">Click "💾 Save" to save your first collection!</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc3545;">Error loading collections</div>';
    });
}

function loadCollectionById(collectionId, collectionName) {
    if (!confirm(`Load collection "${collectionName}"? This will replace your current recently viewed items.`)) {
        return;
    }
    
    fetch('<?= LUPOPEDIA_PUBLIC_PATH ?>/api/load_collection.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            collection_id: collectionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentLoadedCollectionId = collectionId;
            currentLoadedCollectionName = collectionName;
            alert('✅ Collection loaded! Refreshing page...');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to load collection'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading collection. Please try again.');
    });
}

function deleteCollection(collectionId) {
    if (!confirm('Delete this collection? This cannot be undone.')) {
        return;
    }
    
    fetch('<?= LUPOPEDIA_PUBLIC_PATH ?>/api/delete_collection.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            collection_id: collectionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (collectionId == currentLoadedCollectionId) {
                currentLoadedCollectionId = null;
                currentLoadedCollectionName = null;
            }
            loadCollectionsList();
        } else {
            alert('Error: ' + (data.error || 'Failed to delete collection'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting collection. Please try again.');
    });
}

function htmlEscape(str) {
    return String(str).replace(/[&<>"']/g, function(match) {
        const escape = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return escape[match];
    });
}

// Global function to load tabs when collection is selected
window.loadCollectionTabs = function(collectionId, collectionName) {
    currentLoadedCollectionId = collectionId;
    currentLoadedCollectionName = collectionName;
    
    // Load tabs via AJAX
    fetch('<?= LUPOPEDIA_PUBLIC_PATH ?>/api/load_collection_tabs.php?collection_id=' + collectionId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.tabs_data && Object.keys(data.tabs_data).length > 0) {
                // Render tabs in the container
                const container = document.getElementById('collection-tabs-container');
                if (container) {
                    let html = '';
                    for (let mainTab in data.tabs_data) {
                        const subTabs = data.tabs_data[mainTab];
                        const tabSlug = (subTabs && subTabs._slug) ? subTabs._slug : mainTab.toLowerCase().replace(/\s+/g, '-');
                        const dropdownId = 'dropdown-' + mainTab.toLowerCase().replace(/\s+/g, '-');
                        const tabType = mainTab.toLowerCase().replace(/\s+/g, '-');
                        
                        html += '<div class="saved-collections-dropdown" data-qa-type="' + htmlEscape(tabType) + '">';
                        // Count child tabs (excluding _slug metadata)
                        let actualSubTabCount = 0;
                        if (isArray(subTabs)) {
                            for (let key in subTabs) {
                                if (key !== '_slug') {
                                    actualSubTabCount++;
                                }
                            }
                        }
                        
                        html += '<button class="saved-collections-button" onclick="toggleSavedCollectionsDropdown(this)" aria-expanded="false" aria-haspopup="true" aria-controls="' + htmlEscape(dropdownId) + '" data-qa-type="' + htmlEscape(tabType) + '">';
                        html += htmlEscape(mainTab.toUpperCase()) + ' <span class="count">' + actualSubTabCount + '</span>';
                        html += '</button>';
                        html += '<div class="saved-collections-dropdown-content" id="' + htmlEscape(dropdownId) + '" role="menu">';
                        
                        if (isArray(subTabs)) {
                            for (let key in subTabs) {
                                if (key !== '_slug') {
                                    const subTabName = subTabs[key];
                                    const subTabSlug = subTabName.toLowerCase().replace(/\s+/g, '-');
                                    const subTabUrl = '<?= LUPOPEDIA_PUBLIC_PATH ?>/collection/' + collectionId + '/tab/' + subTabSlug;
                                    html += '<a href="' + htmlEscape(subTabUrl) + '" class="saved-collections-item" role="menuitem" tabindex="0">';
                                    html += htmlEscape(subTabName);
                                    html += '</a>';
                                }
                            }
                        }
                        
                        html += '</div></div>';
                    }
                    container.innerHTML = html;
                }
            }
        })
        .catch(error => {
            console.error('Error loading collection tabs:', error);
        });
};

function isArray(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]' || (obj && typeof obj === 'object' && obj.constructor === Object);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSaveCollectionModal();
        closeLoadCollectionModal();
    }
});
</script>

<!-- Content Container with Decorative Borders -->
<div class="content-list-container">
    <!-- Row 1: Top Border -->
    <div class="resources-top-left"></div>
    <div class="resources-top-center">
        <!-- Shortcut Dropdown -->
        <div class="dropdown">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/addshortcut.png" width="42" height="42" onclick="toggleMenu('shortcutDropdown')" style="cursor:pointer;"> 
            <div id="shortcutDropdown" class="dropdown-content">
                <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
                    <b>Current Collection:</b> <span id="current-collection-display"><?= htmlspecialchars($current_collection) ?></span><br>
                    Click on the name of the tab or subtab you would like to add this shortcut to. Use the blue collections tab to select a different collection.
                </div>
                <div id="shortcut-tabs-list">
                    <?php if (!empty($tabs_data) && is_array($tabs_data)): ?>
                        <?php foreach ($tabs_data as $main_tab => $sub_tabs): ?>
                            <?php
                            $tab_slug = null;
                            if (is_array($sub_tabs) && isset($sub_tabs['_slug'])) {
                                $tab_slug = $sub_tabs['_slug'];
                            } else {
                                $tab_slug = strtolower(str_replace(' ', '-', $main_tab));
                            }
                            $tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $tab_slug;
                            ?>
                            <a href="<?= htmlspecialchars($tab_url) ?>" class="main-tab">| <?= htmlspecialchars($main_tab) ?></a>
                            <?php if (is_array($sub_tabs)): ?>
                                <?php foreach ($sub_tabs as $key => $value): ?>
                                    <?php if ($key !== '_slug'): ?>
                                        <?php
                                        $sub_tab_slug = strtolower(str_replace(' ', '-', $value));
                                        $sub_tab_url = LUPOPEDIA_PUBLIC_PATH . '/collection/' . $collection_id . '/tab/' . $sub_tab_slug;
                                        ?>
                                        <a href="<?= htmlspecialchars($sub_tab_url) ?>" class="sub-tab">|— <?= htmlspecialchars($value) ?></a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <a href="javascript:void(0)" class="add-action" onclick="addNewItem('sub', '<?= htmlspecialchars($main_tab) ?>')">+ New Sub-Tab for <?= htmlspecialchars($main_tab) ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <hr>
                    <a href="javascript:void(0)" class="add-action global" onclick="addNewItem('main')">+ Create New Main Tab</a>
                </div>
            </div>
        </div>

        <!-- Contents Dropdown -->
        <div class="dropdown">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/contents.png" width="42" height="42" onclick="toggleMenu('contentsDropdown')" style="cursor:pointer;">
            <div id="contentsDropdown" class="dropdown-content">
                <?php if (!empty($contentSections) && is_array($contentSections)): ?>
                    <?php foreach ($contentSections as $section): ?>
                        <?php
                        $section_anchor = isset($section['anchor']) ? $section['anchor'] : '';
                        $section_title = isset($section['title']) ? $section['title'] : '';
                        if ($section_anchor):
                        ?>
                            <a href="#<?= htmlspecialchars($section_anchor) ?>"><?= htmlspecialchars($section_title) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="#" style="color: #999; font-style: italic;">No sections available</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Page Title -->
        <h1 id="firstHeading" class="firstHeading mw-first-heading">
            <span class="mw-page-title-main"><?= htmlspecialchars($page_title) ?></span>
        </h1>
        &nbsp;
        <div style="display: flex; align-items: right; margin-left: auto;">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/edges.png" width="194" height="42" style="cursor:pointer; margin-left: auto;">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/help.png" width="44" height="42">
        </div>
    </div>
    <div class="resources-top-right"></div>
    
    <!-- Row 2: Middle Border and Content -->
    <div class="resources-middle-left"></div>
    <div class="resources-middle-center">
        <!-- Page Content -->
        <?= $page_body ?>
    </div>
    <div class="resources-middle-right"></div>
    
    <!-- Row 3: Bottom Border -->
    <div class="resources-bottom-left"></div>
    <div class="resources-bottom-center">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/prevpage.png" width="32" height="32" <?php if ($prevContent): ?>onclick="window.location.href='<?= htmlspecialchars($prevContent['url'] ?? '#') ?>'" style="cursor:pointer;"<?php endif; ?>>
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/references.png" width="32" height="32" style="cursor:pointer;" title="References">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/context.png" width="32" height="32" style="cursor:pointer;" title="Context">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/hashtag.png" width="32" height="32" style="cursor:pointer;" title="Tags">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/share.png" width="32" height="32" style="cursor:pointer;" title="Share">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/like.png" width="32" height="32" style="cursor:pointer;" title="Like">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/comment.png" width="32" height="32" style="cursor:pointer;" title="Comment">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/links.png" width="32" height="32" style="cursor:pointer;" title="Links">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/folder.png" width="32" height="32" style="cursor:pointer;" title="Folder">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/atoms.png" width="32" height="32" style="cursor:pointer;" title="Atoms">
        <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/nextpage.png" width="32" height="32" <?php if ($nextContent): ?>onclick="window.location.href='<?= htmlspecialchars($nextContent['url'] ?? '#') ?>'" style="cursor:pointer;"<?php endif; ?>>
    </div>
    <div class="resources-bottom-right"></div>
</div>

<!-- Auto-load collection tabs when page loads if collection_id is set -->
<script>
(function() {
    var collectionId = <?php echo ($collection_id !== null && $collection_id > 0) ? $collection_id : 'null'; ?>;
    var tabsData = <?php echo !empty($tabs_data) ? json_encode($tabs_data) : 'null'; ?>;
    
    // If collection_id is set but tabs_data is empty, load via AJAX
    if (collectionId !== null && collectionId > 0 && (!tabsData || Object.keys(tabsData).length === 0)) {
        fetch('<?= LUPOPEDIA_PUBLIC_PATH ?>/api/load_collection_tabs.php?collection_id=' + collectionId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.tabs_data && Object.keys(data.tabs_data).length > 0) {
                    // Update tabs container
                    if (typeof window.loadCollectionTabs === 'function') {
                        window.loadCollectionTabs(collectionId, data.collection_name || 'Collection ' + collectionId);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading collection tabs:', error);
            });
    }
})();
</script>

<?php 
// Footer
if (file_exists(LUPO_UI_PATH . '/components/footer.php')) {
    include LUPO_UI_PATH . '/components/footer.php';
}

// Load UI JavaScript at end of body
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/ui/ui-loader.php')) {
    if (function_exists('lupo_print_ui_js')) {
        lupo_print_ui_js();
    }
}
?>

</body>
</html>
