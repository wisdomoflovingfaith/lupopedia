<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: include
  when_updated: "20260406015355"
  file_path_from_root: "lupo-includes/header.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/header.php"
  last_modified_utc: "20260406015355"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "include"
  artifact_kind: "site_header"
  purpose: "Shared HTML head, main nav, saved collections chrome, modals; lupo_t and LUPOPEDIA_PUBLIC_PATH."
  tags: ["ui", "header", "locale", "navigation"]
---
*/

$root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '';
if ($root !== '' && !class_exists('LupoLocale', false)) {
    $lp = $root . '/lupo-includes/classes/LupoLocale.php';
    if (is_file($lp)) {
        require_once $lp;
    }
}
if ($root !== '' && class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
    LupoLocale::bootstrap($root);
}
if (!function_exists('lupo_t')) {
    $i18n = ($root !== '' ? $root . '/lupo-includes/lupo-i18n.php' : '');
    if ($i18n !== '' && is_file($i18n)) {
        require_once $i18n;
    }
}

$lupoPublicBase = (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '');
$lupoPubSlash = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
$lupoOgUrl = ($lupoPublicBase !== '' ? htmlspecialchars($lupoPublicBase . '/questions.php', ENT_QUOTES, 'UTF-8') : '');

$lupoHdrJs = array(
    'base' => $lupoPublicBase,
    'strings' => array(
        'collections_save_login' => function_exists('lupo_t') ? lupo_t('header.collections.alert_save_login', 'Please log in to save collections.') : 'Please log in to save collections.',
        'collections_load_login' => function_exists('lupo_t') ? lupo_t('header.collections.alert_load_login', 'Please log in to load collections.') : 'Please log in to load collections.',
        'collections_edit_login' => function_exists('lupo_t') ? lupo_t('header.collections.alert_edit_login', 'Please log in to edit collections.') : 'Please log in to edit collections.',
        'collections_edit_save_first' => function_exists('lupo_t') ? lupo_t('header.collections.edit_save_first', 'Please save this collection first, then you can edit it!') : 'Please save this collection first, then you can edit it!',
        'collections_edit_open_save' => function_exists('lupo_t') ? lupo_t('header.collections.edit_open_save', 'Click OK to open the Save dialog.') : 'Click OK to open the Save dialog.',
        'collections_name_required' => function_exists('lupo_t') ? lupo_t('header.collections.name_required', 'Please enter a name for this collection') : 'Please enter a name for this collection',
        'collections_saved_ok' => function_exists('lupo_t') ? lupo_t('header.collections.saved_ok', 'Collection saved successfully!') : 'Collection saved successfully!',
        'collections_error_prefix' => function_exists('lupo_t') ? lupo_t('header.collections.error_prefix', 'Error: ') : 'Error: ',
        'collections_save_failed' => function_exists('lupo_t') ? lupo_t('header.collections.save_failed', 'Failed to save collection') : 'Failed to save collection',
        'collections_save_try_again' => function_exists('lupo_t') ? lupo_t('header.collections.save_try_again', 'Error saving collection. Please try again.') : 'Error saving collection. Please try again.',
        'collections_loading_short' => function_exists('lupo_t') ? lupo_t('header.collections.loading_short', 'Loading...') : 'Loading...',
        'collections_no_description' => function_exists('lupo_t') ? lupo_t('header.collections.no_description', 'No description') : 'No description',
        'collections_saved_items' => function_exists('lupo_t') ? lupo_t('header.collections.saved_items', 'saved items') : 'saved items',
        'collections_created' => function_exists('lupo_t') ? lupo_t('header.collections.created', 'Created:') : 'Created:',
        'collections_load_btn' => function_exists('lupo_t') ? lupo_t('header.collections.load_btn', 'Load') : 'Load',
        'collections_delete_btn' => function_exists('lupo_t') ? lupo_t('header.collections.delete_btn', 'Delete') : 'Delete',
        'collections_active_prefix' => function_exists('lupo_t') ? lupo_t('header.collections.active_prefix', '[Active] ') : '[Active] ',
        'collections_empty' => function_exists('lupo_t') ? lupo_t('header.collections.empty', 'No saved collections yet.') : 'No saved collections yet.',
        'collections_empty_hint' => function_exists('lupo_t') ? lupo_t('header.collections.empty_hint', 'Click Save to save your first collection!') : 'Click Save to save your first collection!',
        'collections_list_error' => function_exists('lupo_t') ? lupo_t('header.collections.list_error', 'Error loading collections') : 'Error loading collections',
        'collections_confirm_load' => function_exists('lupo_t') ? lupo_t('header.collections.confirm_load', 'Load collection "%s"? This will replace your current recently viewed items.') : 'Load collection "%s"? This will replace your current recently viewed items.',
        'collections_loaded_ok' => function_exists('lupo_t') ? lupo_t('header.collections.loaded_ok', 'Collection loaded! Refreshing page...') : 'Collection loaded! Refreshing page...',
        'collections_load_failed' => function_exists('lupo_t') ? lupo_t('header.collections.load_failed', 'Failed to load collection') : 'Failed to load collection',
        'collections_load_try_again' => function_exists('lupo_t') ? lupo_t('header.collections.load_try_again', 'Error loading collection. Please try again.') : 'Error loading collection. Please try again.',
        'collections_delete_confirm' => function_exists('lupo_t') ? lupo_t('header.collections.delete_confirm', 'Delete this collection? This cannot be undone.') : 'Delete this collection? This cannot be undone.',
        'collections_delete_failed' => function_exists('lupo_t') ? lupo_t('header.collections.delete_failed', 'Failed to delete collection') : 'Failed to delete collection',
        'collections_delete_try_again' => function_exists('lupo_t') ? lupo_t('header.collections.delete_try_again', 'Error deleting collection. Please try again.') : 'Error deleting collection. Please try again.',
        'prompt_main_tab' => function_exists('lupo_t') ? lupo_t('header.shortcut.prompt_main_tab', 'Enter name for new Main Tab:') : 'Enter name for new Main Tab:',
        'prompt_sub_tab' => function_exists('lupo_t') ? lupo_t('header.shortcut.prompt_sub_tab', 'Enter new Sub-Tab name for "%s":') : 'Enter new Sub-Tab name for "%s":',
        'add_success' => function_exists('lupo_t') ? lupo_t('header.shortcut.add_success', 'Successfully added "%s" to your collection!') : 'Successfully added "%s" to your collection!',
    ),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.html_title', 'Lupopedia — Ontology knowledge platform') : 'Lupopedia — Ontology knowledge platform', ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($lupoPubSlash !== '' ? $lupoPubSlash . '/favicon.ico' : 'favicon.ico', ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="shortcut icon" href="<?php echo htmlspecialchars($lupoPubSlash !== '' ? $lupoPubSlash . '/favicon.ico' : 'favicon.ico', ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($lupoPubSlash !== '' ? $lupoPubSlash . '/css/main.css' : '/css/main.css', ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($lupoPubSlash !== '' ? $lupoPubSlash . '/css/components.css' : '/css/components.css', ENT_QUOTES, 'UTF-8'); ?>">
 
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($lupoPubSlash !== '' ? $lupoPubSlash . '/lupo-images/logoface.png' : 'lupo-images/logoface.png', ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Meta collections -->
    <meta name="description" content="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.meta_description', 'Browse Q/A content from source of truth database') : 'Browse Q/A content from source of truth database', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.meta_keywords', 'spirituality, religion, faith, wisdom, sacred texts, prayers, songs, AI, guidance') : 'spirituality, religion, faith, wisdom, sacred texts, prayers, songs, AI, guidance', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.meta_author', 'Captain WOLFIE (Eric Robin Gerdes)') : 'Captain WOLFIE (Eric Robin Gerdes)', ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.og_title', 'Questions & Answers') : 'Questions & Answers', ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.og_description', 'Browse Q/A content from source of truth database') : 'Browse Q/A content from source of truth database', ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="website">
    <?php if ($lupoOgUrl !== ''): ?>
    <meta property="og:url" content="<?php echo $lupoOgUrl; ?>">
    <?php endif; ?>
    <meta property="og:image" content="<?php echo htmlspecialchars(($lupoPublicBase !== '' ? $lupoPublicBase : '') . '/lupo-images/s1.png', ENT_QUOTES, 'UTF-8'); ?>">

    <script>
    window.LUPO_HDR = <?php echo json_encode($lupoHdrJs); ?>;
    </script>
    
        
        
    <!-- Recently Viewed Navigation Styles (global) -->
    <style>
    /* Authentication Status Indicator (Version 3.0.8) */
    .auth-status-logged-in,
    .auth-status-logged-out {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-right: 15px;
    }
    
    .auth-username {
        color: #333;
        font-size: 14px;
        font-weight: 500;
    }
    
    .auth-login-link,
    .auth-logout-link {
        color: #4a90e2;
        text-decoration: none;
        font-size: 14px;
        padding: 6px 12px;
        border: 1px solid #4a90e2;
        border-radius: 4px;
        transition: background 0.2s, color 0.2s;
    }
    
    .auth-login-link:hover,
    .auth-logout-link:hover {
        background: #4a90e2;
        color: white;
    }
    
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
        height: 49px; /* Explicit height: 10px top padding + content + 10px bottom padding */
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
    
    /* Submenu trigger with visual indicator */
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
    
    /* Secondary submenu - positioned via JavaScript, appended to body */
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

 
    #eye-close-btn {
    position: fixed;
    bottom: 120px;
    right: 0px;
    width: 32px;
    height: 32px;
    background: white;
    color: red;
    border-radius: 50%;
    font-weight: bold;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.1s ease;
    box-shadow: 0 0 6px rgba(0,0,0,0.3);
    user-select: none;
}

#firstHeading { 
    font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif; 
    font-size: 32px; /* Comfortable default */ 
    line-height: 42px; /* Ensures total height never exceeds 42px */ 
    font-weight: 600; /* Slightly bold, clean look */ 
    margin: 0; 
    padding: 0; 
    overflow: hidden; /* Prevents spillover if text gets tall */ 
    word-break: break-word;
}


    </style>
    <script>
        function toggleSavedCollectionsDropdown(button) {
            const dropdown = button.closest('.saved-collections-dropdown');
            const isActive = dropdown.classList.contains('active');
            
            // Close all other dropdowns and their submenus
            document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('active');
                    // Close submenus in other dropdowns
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
            
            // Update ARIA attributes
            button.setAttribute('aria-expanded', newState.toString());
        }
        
        // Store references to open submenus for cleanup
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
            if (!submenuContent) return; // Must have content to be a submenu trigger
            
            // 1. Get DOM hierarchy BEFORE any content movement
            const isOpening = !isActive;
            
            // Find the DIRECT CONTAINER of the current submenu (This is the parent's content div, in the original DOM)
            // This element holds all siblings of the current submenu.
            const container = submenu.parentElement;
            
            // Store event coordinates for positioning if trigger position fails
            let mouseX = null;
            let mouseY = null;
            if (event && event.clientX && event.clientY) {
                mouseX = event.clientX;
                mouseY = event.clientY;
            }
            
            // 2. Cleanup: Close all SIBLINGS at the current level
            // This prevents the root tag from closing when a child tag opens.
            if (container) {
                // Iterate over all siblings of the current submenu
                container.querySelectorAll(':scope > .saved-collections-submenu').forEach(s => {
                    if (s !== submenu) {
                        // This is a sibling, so close it and its content
                        s.classList.remove('active');
                        const content = s.querySelector('.saved-collections-submenu-content');
                        
                        // Remove content from body if it was moved/cloned there
                        if (content && content.classList.contains('active') && content.parentNode === document.body) {
                            content.remove();
                        } else if (content) {
                            content.style.display = 'none';
                        }
                        if (content) {
                            content.classList.remove('active');
                        }
                        
                        // Also close any nested submenus within this sibling (cleanup)
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
            
            // 3. Clean up any orphaned submenu in body from a previous operation
            // This prevents a previous submenu at a different level from interfering.
            if (openSubmenuContent && openSubmenuContent.parentNode === document.body && 
                !openSubmenuContent.contains(submenu) && openSubmenuContent.id !== submenuContent.id) {
                openSubmenuContent.remove();
            }
            
            // 4. Toggle the current submenu
            if (isOpening) {
                submenu.classList.add('active');
                
                // --- Positioning Logic ---
                const triggerRect = trigger.getBoundingClientRect();
                
                // Clone or move submenu content to body to avoid overflow clipping
                let positionedSubmenu = submenuContent;
                
                // Check if the content is ALREADY in the body, or if it's currently hidden in the source HTML
                if (submenuContent.parentNode !== document.body || !submenuContent.classList.contains('active')) {
                    // Create a NEW element if not already moved (or if we need a fresh copy)
                    positionedSubmenu = submenuContent.cloneNode(true);
                    if (submenuContent.id) {
                        positionedSubmenu.setAttribute('data-source-id', submenuContent.id); // Track source ID
                    }
                    
                    // Remove any nested submenu content from the *clone* so we don't accidentally move
                    // nested menu content multiple times if it was already moved/cloned
                    positionedSubmenu.querySelectorAll('.saved-collections-submenu-content').forEach(c => {
                        c.style.display = 'none';
                        c.classList.remove('active');
                    });
                    
                    document.body.appendChild(positionedSubmenu);
                    openSubmenuContent = positionedSubmenu; // Store reference to the one in body
                    
                } else {
                    // If it's already the one in the body, just use it
                    positionedSubmenu = openSubmenuContent;
                }
                
                // Must happen AFTER appending to body so it calculates size correctly
                positionedSubmenu.classList.add('active');
                positionedSubmenu.style.position = 'absolute';
                positionedSubmenu.style.display = 'block';
                
                // Calculate position
                let leftPos, topPos;
                
                // Fall back to trigger element position (most reliable for complex menus)
                leftPos = triggerRect.right + 4;
                topPos = triggerRect.top;
                
                // Fallback for positioning issues
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
                
                // Boundary checks
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
                
                // Apply position
                positionedSubmenu.style.left = Math.max(0, leftPos) + 'px';
                positionedSubmenu.style.top = Math.max(0, topPos) + 'px';
                positionedSubmenu.style.zIndex = '10001';
                
            } else {
                // 5. Close submenu (when clicking the active trigger again)
                submenu.classList.remove('active');
                
                // Find the currently displayed version (in body)
                let positionedSubmenu = null;
                if (submenuContent.id) {
                    positionedSubmenu = document.body.querySelector(`#${submenuContent.id}.active`) || 
                                       document.body.querySelector(`[data-source-id="${submenuContent.id}"].active`);
                } else {
                    // Fallback: find by matching content structure
                    document.querySelectorAll('.saved-collections-submenu-content').forEach(c => {
                        if (c.parentNode === document.body && c.classList.contains('active') && 
                            c.getAttribute('data-source-id') === submenuContent.id) {
                            positionedSubmenu = c;
                        }
                    });
                }
                
                if (positionedSubmenu) {
                    positionedSubmenu.remove();
                }
                
                // Also ensure the original content element is reset/hidden
                submenuContent.classList.remove('active');
                submenuContent.style.display = 'none';
                
                openSubmenuContent = null;
            }
        }
        
        // Recalculate submenu position on scroll and resize (debounced)
        let resizeTimeout;
        function handleResizeOrScroll() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const activeSubmenu = document.querySelector('.saved-collections-submenu.active');
                if (activeSubmenu) {
                    const trigger = activeSubmenu.querySelector('.saved-collections-submenu-trigger');
                    if (trigger) {
                        // Reposition if submenu is open
                        toggleSubmenu(trigger, null);
                        toggleSubmenu(trigger, null); // Toggle twice to reopen in new position
                    }
                }
            }, 100);
        }
        
        window.addEventListener('scroll', handleResizeOrScroll, true);
        window.addEventListener('resize', handleResizeOrScroll);
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            // Check if click is outside saved collections area
            if (!event.target.closest('.saved-collections-dropdown') && 
                !event.target.closest('.saved-collections-submenu-content')) {
                // Close all primary dropdowns
                document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
                    d.classList.remove('active');
                });
                
                // Close all submenus and remove from body
                document.querySelectorAll('.saved-collections-submenu').forEach(s => {
                    s.classList.remove('active');
                });
                
                // Clean up all submenu content in body and reset originals
                document.querySelectorAll('.saved-collections-submenu-content').forEach(content => {
                    if (content.parentNode === document.body) {
                        content.remove();
                    }
                    // Also reset the original, hidden content element
                    content.classList.remove('active');
                    content.style.display = 'none';
                });
                openSubmenuContent = null;
            }
        });
        
        // Keyboard navigation support
        document.addEventListener('keydown', function(event) {
            // Close on Escape key
            if (event.key === 'Escape') {
                document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
                    d.classList.remove('active');
                });
                document.querySelectorAll('.saved-collections-submenu').forEach(s => {
                    s.classList.remove('active');
                });
                
                // Clean up all submenu content in body and reset originals
                document.querySelectorAll('.saved-collections-submenu-content').forEach(content => {
                    if (content.parentNode === document.body) {
                        content.remove();
                    }
                    // Also reset the original, hidden content element
                    content.classList.remove('active');
                    content.style.display = 'none';
                });
                openSubmenuContent = null;
            }
        });
    </script>
</head>
<body>
 

 
    <!-- Navigation Header -->
    <header class="main-header">
    <div class="nav-logo-container" style="position: absolute; top: 20px; left: 0; z-index: 2000;">
 
<a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/index.php' : '/index.php', ENT_QUOTES, 'UTF-8'); ?>" class="nav-logo" onclick="scrollToTop()" title="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.logo_title', 'Lupopedia home') : 'Lupopedia home', ENT_QUOTES, 'UTF-8'); ?>">
     <img src="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/lupo-images/logoface.png' : 'lupo-images/logoface.png', ENT_QUOTES, 'UTF-8'); ?>?1766754946" alt="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.logo_alt', 'Lupopedia') : 'Lupopedia', ENT_QUOTES, 'UTF-8'); ?>" width="50" height="50" border="0" style="border-radius: 50%;" />
 </a>
 </div>
        <nav class="main-nav">
            <div class="nav-container">

                <!-- Main Navigation Links -->
                <div class="nav-links">
                    <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/' : '/', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link active"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.home', 'Home') : 'Home', ENT_QUOTES, 'UTF-8'); ?></a>
                    <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/qa/' : '/qa/', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link active"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.qa', 'Q/A') : 'Q/A', ENT_QUOTES, 'UTF-8'); ?></a>
                    <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/search.php' : '/search.php', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link "><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.content', 'Content') : 'Content', ENT_QUOTES, 'UTF-8'); ?></a>
                    <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/collections.php' : '/collections.php', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link "><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.collections', 'Collections') : 'Collections', ENT_QUOTES, 'UTF-8'); ?></a>
                    <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/users.php' : '/users.php', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link "><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.users', 'Users') : 'Users', ENT_QUOTES, 'UTF-8'); ?></a>
                    <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/agents.php' : '/agents.php', ENT_QUOTES, 'UTF-8'); ?>" class="nav-link "><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.agents', 'Agents') : 'Agents', ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                
                <!-- User Profile Section -->
                <div class="nav-user">
                    <?php
                    // Version 3.0.9: Authentication status indicator with profile avatar
                    // Ensure auth UI helpers are loaded
                    if (!function_exists('lupo_render_login_status')) {
                        $auth_ui_helpers_path = defined('LUPOPEDIA_PATH') 
                            ? LUPOPEDIA_PATH . '/lupo-includes/functions/auth-ui-helpers.php'
                            : (defined('LUPO_INCLUDES_DIR') ? LUPO_INCLUDES_DIR . '/functions/auth-ui-helpers.php' : '');
                        if ($auth_ui_helpers_path && file_exists($auth_ui_helpers_path)) {
                            require_once $auth_ui_helpers_path;
                        }
                    }
                    
                    // Render login status — user row and operator flag resolved here (Session/AuthService); helper does not read globals for render.
                    if (function_exists('lupo_render_login_status')) {
                        $hdr_auth_user = null;
                        $hdr_is_operator = false;
                        $hdr_auth = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
                        if ($hdr_auth) {
                            $hdr_auth_user = $hdr_auth->getCurrentUser();
                            if ($hdr_auth_user && is_array($hdr_auth_user) && isset($hdr_auth_user['actor_id']) && (int) $hdr_auth_user['actor_id'] > 0) {
                                $hdr_is_operator = $hdr_auth->hasAnyChannelRole((int) $hdr_auth_user['actor_id']);
                            }
                        } elseif (function_exists('current_user')) {
                            $cu = current_user();
                            if ($cu && is_array($cu)) {
                                $hdr_auth_user = $cu;
                            }
                        }
                        echo lupo_render_login_status(
                            ($hdr_auth_user && is_array($hdr_auth_user)) ? $hdr_auth_user : null,
                            $hdr_is_operator
                        );
                    } else {
                        $login_url = function_exists('lupo_login_url') ? lupo_login_url() : (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/login.php' : '/login.php');
                        $signIn = function_exists('lupo_t') ? lupo_t('nav.sign_in', 'Sign In') : 'Sign In';
                        echo '<a href="' . htmlspecialchars($login_url) . '" class="nav-link">' . htmlspecialchars($signIn, ENT_QUOTES, 'UTF-8') . '</a>';
                    }
                    ?>
                </div>
            </div>
        </nav>
    </header>

    
    <!-- Saved Collections Navigation (all pages including index.php) -->
    <?php
    $isUserLoggedIn = false;
    $collectionsData = array();
    if (!defined('LUPO_INCLUDES_DIR')) {
        // Bootstrap incomplete; skip collections chrome.
    } else {
    // Load renderer function
    require_once(LUPO_INCLUDES_DIR . '/functions/render-saved-collections.php');
    
    // Model A: identity from DB. Resolve via Session.
    $currentUserId = 0;
    if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
        $aid = $GLOBALS['lupo_session']->getActorId();
        $currentUserId = $aid !== null ? (int) $aid : 0;
    }
    $isUserLoggedIn = ($currentUserId > 0);
    
    // Render saved collections data (always render for viewing, permissions apply to editing only)
    $collectionsData = render_saved_collections($currentUserId);
    
    // Include the component template
    include(LUPO_INCLUDES_DIR . '/themes/default/components/saved-collections-nav.php');
    }
    ?>
    
    <!-- Recently Viewed Navigation removed (replaced by Saved Collections Nav) -->
    <!-- Nav removed -->
        
    <!-- Save Collection Modal -->
    <div id="saveCollectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%;">
            <h3 style="margin-top: 0; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.save_modal_title', 'Save recently viewed collection') : 'Save recently viewed collection', ENT_QUOTES, 'UTF-8'); ?></h3>
            <p style="color: #6c757d; margin-bottom: 20px;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.save_modal_intro', 'Give this collection a name to save your current browsing session.') : 'Give this collection a name to save your current browsing session.', ENT_QUOTES, 'UTF-8'); ?></p>
            
            <div id="updateExistingNotice" style="display: none; background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin-bottom: 15px;">
                <strong><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.update_label', 'Update existing:') : 'Update existing:', ENT_QUOTES, 'UTF-8'); ?></strong> <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.update_body_before_name', 'You are currently viewing collection') : 'You are currently viewing collection', ENT_QUOTES, 'UTF-8'); ?> "<span id="currentCollectionName"></span>"<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.update_body_after_name', '. Save to update it, or enter a new name to create a copy.') : '. Save to update it, or enter a new name to create a copy.', ENT_QUOTES, 'UTF-8'); ?>
            </div>
            
            <label for="collectionName" style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.name_label', 'Collection name:') : 'Collection name:', ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="text" id="collectionName" placeholder="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.name_placeholder', 'e.g., Bible study session, research project') : 'e.g., Bible study session, research project', ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; padding: 12px; border: 2px solid #D4AF37; border-radius: 6px; font-size: 1rem; margin-bottom: 10px;">
            
            <label for="collectionDescription" style="display: block; margin-bottom: 8px; margin-top: 15px; font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.desc_label', 'Description (optional):') : 'Description (optional):', ENT_QUOTES, 'UTF-8'); ?></label>
            <textarea id="collectionDescription" placeholder="<?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.desc_placeholder', 'What is this collection for?') : 'What is this collection for?', ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; padding: 12px; border: 2px solid #D4AF37; border-radius: 6px; font-size: 1rem; margin-bottom: 20px; min-height: 80px;"></textarea>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closeSaveCollectionModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.cancel', 'Cancel') : 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
                <button onclick="saveCollection()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.save_submit', 'Save collection') : 'Save collection', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
    
    <!-- Load Collection Modal -->
    <div id="loadCollectionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3 style="margin-top: 0; color: #2c3e50;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.load_modal_title', 'Load saved collection') : 'Load saved collection', ENT_QUOTES, 'UTF-8'); ?></h3>
            <p style="color: #6c757d; margin-bottom: 20px;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.load_modal_intro', 'Select a saved collection to restore your browsing session.') : 'Select a saved collection to restore your browsing session.', ENT_QUOTES, 'UTF-8'); ?></p>
            
            <div id="collectionsList" style="margin-bottom: 20px;">
                <div style="text-align: center; padding: 40px; color: #6c757d;">
                    <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.loading_list', 'Loading your collections...') : 'Loading your collections...', ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closeLoadCollectionModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.close', 'Close') : 'Close', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Collection Management -->
    <script>
    function lupoHdrStr(key, fallback) {
        if (window.LUPO_HDR && window.LUPO_HDR.strings && window.LUPO_HDR.strings[key]) {
            return window.LUPO_HDR.strings[key];
        }
        return fallback;
    }
    function lupoPubUrl(path) {
        var base = (window.LUPO_HDR && window.LUPO_HDR.base) ? window.LUPO_HDR.base : '';
        path = String(path).replace(/^\//, '');
        return base ? (base + '/' + path) : path;
    }
    function hdrFmtOne(msg, a) {
        return String(msg).replace('%s', String(a));
    }
    // Track currently loaded collection (if any)
    var currentLoadedCollectionId = null;
    var currentLoadedCollectionName = '';
    
    function editCurrentCollection() {
        if (currentLoadedCollectionId) {
            window.location.href = lupoPubUrl('edit_collection.php?id=' + currentLoadedCollectionId);
        } else {
            var m1 = lupoHdrStr('collections_edit_save_first', 'Please save this collection first, then you can edit it!');
            var m2 = lupoHdrStr('collections_edit_open_save', 'Click OK to open the Save dialog.');
            alert(m1 + '\n\n' + m2);
            showSaveCollectionModal();
        }
    }
    
    function showSaveCollectionModal() {
        var modal = document.getElementById('saveCollectionModal');
        var nameInput = document.getElementById('collectionName');
        var updateNotice = document.getElementById('updateExistingNotice');
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
        var modal = document.getElementById('loadCollectionModal');
        modal.style.display = 'flex';
        loadCollectionsList();
    }
    
    function closeLoadCollectionModal() {
        document.getElementById('loadCollectionModal').style.display = 'none';
    }
    
    function saveCollection() {
        var name = document.getElementById('collectionName').value.trim();
        var description = document.getElementById('collectionDescription').value.trim();
        if (!name) {
            alert(lupoHdrStr('collections_name_required', 'Please enter a name for this collection'));
            return;
        }
        var isUpdate = currentLoadedCollectionId && name === currentLoadedCollectionName;
        fetch(lupoPubUrl('lupo-api/save_collection.php'), {
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
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                alert(lupoHdrStr('collections_saved_ok', 'Collection saved successfully!'));
                currentLoadedCollectionId = data.collection_id;
                currentLoadedCollectionName = name;
                closeSaveCollectionModal();
            } else {
                alert(lupoHdrStr('collections_error_prefix', 'Error: ') + (data.error || lupoHdrStr('collections_save_failed', 'Failed to save collection')));
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            alert(lupoHdrStr('collections_save_try_again', 'Error saving collection. Please try again.'));
        });
    }
    
    function loadCollectionsList() {
        var container = document.getElementById('collectionsList');
        var loading = lupoHdrStr('collections_loading_short', 'Loading...');
        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #6c757d;">' + htmlEscape(loading) + '</div>';
        fetch(lupoPubUrl('lupo-api/list_collections.php'))
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success && data.collections.length > 0) {
                var html = '';
                var S = window.LUPO_HDR && window.LUPO_HDR.strings ? window.LUPO_HDR.strings : {};
                data.collections.forEach(function(collection) {
                    var isCurrentlyLoaded = (collection.id == currentLoadedCollectionId);
                    var desc = collection.description || lupoHdrStr('collections_no_description', 'No description');
                    var activeP = lupoHdrStr('collections_active_prefix', '[Active] ');
                    var nItems = collection.saved_collections_count || collection.item_count || 0;
                    var itemsLbl = lupoHdrStr('collections_saved_items', 'saved items');
                    var createdLbl = lupoHdrStr('collections_created', 'Created:');
                    var loadLbl = lupoHdrStr('collections_load_btn', 'Load');
                    var delLbl = lupoHdrStr('collections_delete_btn', 'Delete');
                    html += '<div style="border: 2px solid ' + (isCurrentlyLoaded ? '#28a745' : '#D4AF37') + '; padding: 15px; border-radius: 8px; margin-bottom: 10px; ' + (isCurrentlyLoaded ? 'background: #d4edda;' : 'background: #f8f9fa;') + '">' +
                        '<div style="display: flex; justify-content: space-between; align-items: start;">' +
                        '<div style="flex: 1;">' +
                        '<h4 style="margin: 0 0 8px 0; color: #2c3e50;">' +
                        (isCurrentlyLoaded ? htmlEscape(activeP) : '') + htmlEscape(collection.collection_name) +
                        '</h4>' +
                        '<p style="margin: 0 0 8px 0; color: #6c757d; font-size: 0.9rem;">' + htmlEscape(desc) + '</p>' +
                        '<p style="margin: 0; color: #6c757d; font-size: 0.85rem;">' +
                        nItems + ' ' + htmlEscape(itemsLbl) +
                        '<br><small>' + htmlEscape(createdLbl) + ' ' + new Date(collection.created_at).toLocaleString() + '</small>' +
                        '</p></div>' +
                        '<div style="display: flex; gap: 8px;">' +
                        '<button onclick="loadCollectionById(' + collection.id + ', ' + JSON.stringify(collection.collection_name) + ')" style="padding: 8px 16px; background: #17a2b8; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; white-space: nowrap;">' +
                        htmlEscape(loadLbl) + '</button>' +
                        '<button onclick="deleteCollection(' + collection.id + ')" style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">' +
                        htmlEscape(delLbl) + '</button>' +
                        '</div></div></div>';
                });
                container.innerHTML = html;
            } else {
                var empty = lupoHdrStr('collections_empty', 'No saved collections yet.');
                var hint = lupoHdrStr('collections_empty_hint', 'Click Save to save your first collection!');
                container.innerHTML = '<div style="text-align: center; padding: 40px; color: #6c757d;"><p>' + htmlEscape(empty) + '</p><p style="font-size: 0.9rem;">' + htmlEscape(hint) + '</p></div>';
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            var err = lupoHdrStr('collections_list_error', 'Error loading collections');
            container.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc3545;">' + htmlEscape(err) + '</div>';
        });
    }
    
    function loadCollectionById(collectionId, collectionName) {
        var tmpl = lupoHdrStr('collections_confirm_load', 'Load collection "%s"? This will replace your current recently viewed items.');
        if (!confirm(hdrFmtOne(tmpl, collectionName))) {
            return;
        }
        fetch(lupoPubUrl('lupo-api/load_collection.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                collection_id: collectionId
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                currentLoadedCollectionId = collectionId;
                currentLoadedCollectionName = collectionName;
                alert(lupoHdrStr('collections_loaded_ok', 'Collection loaded! Refreshing page...'));
                location.reload();
            } else {
                alert(lupoHdrStr('collections_error_prefix', 'Error: ') + (data.error || lupoHdrStr('collections_load_failed', 'Failed to load collection')));
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            alert(lupoHdrStr('collections_load_try_again', 'Error loading collection. Please try again.'));
        });
    }
    
    function deleteCollection(collectionId) {
        if (!confirm(lupoHdrStr('collections_delete_confirm', 'Delete this collection? This cannot be undone.'))) {
            return;
        }
        fetch(lupoPubUrl('lupo-api/delete_collection.php'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                collection_id: collectionId
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                if (collectionId == currentLoadedCollectionId) {
                    currentLoadedCollectionId = null;
                    currentLoadedCollectionName = null;
                }
                loadCollectionsList();
            } else {
                alert(lupoHdrStr('collections_error_prefix', 'Error: ') + (data.error || lupoHdrStr('collections_delete_failed', 'Failed to delete collection')));
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            alert(lupoHdrStr('collections_delete_try_again', 'Error deleting collection. Please try again.'));
        });
    }
    
    function htmlEscape(str) {
        return String(str).replace(/[&<>"']/g, function(match) {
            var escape = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            };
            return escape[match];
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSaveCollectionModal();
            closeLoadCollectionModal();
        }
    });
    </script>
    
    <!-- Main Content -->
    <main class="main-content">
    
        
    <!-- JavaScript for User Dropdown -->
    <script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdownMenu');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdownMenu');
        const profileBtn = document.querySelector('.user-profile-btn');
        
        if (dropdown && profileBtn && !profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Close dropdown on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const dropdown = document.getElementById('userDropdownMenu');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
        }
    });
    </script>



<style>.content-list-container {
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
    background: url('lupo-images/s1b.png');
}

.resources-top-center {
    width: calc(100vw - 118px);
    height: 42px;
    background: url('lupo-images/s2b.png');
    background-repeat: repeat;
    display: flex; 
    align-items: flex-start; /* Aligns everything to the TOP */
}

.resources-top-right {
    width: 54px;
    height: 42px;
    background: url('lupo-images/s3b.png');
}

/* Row 2: Middle Border and Content */
.resources-middle-left {
    width: 54px;
    height: calc(100vh - 107px - 78px);
    background: url('lupo-images/s4b.png');
    background-repeat: repeat-y;
}

.resources-middle-center {
    width: calc(100vw - 118px);
    height: calc(100vh - 107px - 78px);
    background: url('lupo-images/s5.png');
    background-repeat: repeat;
    overflow-y: auto;
    padding: 20px;
}

.resources-middle-right {
    width: 54px;
    height: calc(100vh - 107px - 78px);
    background: url('lupo-images/s6b.png');
    background-repeat: repeat-y;
}

/* Row 3: Bottom Border */
.resources-bottom-left {
    width: 54px;
    height: 42px;
    background: url('lupo-images/s7b.png');
}

.resources-bottom-center {
    width: calc(100vw - 118px);
    height: 42px;
    background: url('lupo-images/s8b.png');
    background-repeat: repeat;
}

.resources-bottom-right {
    width: 54px;
    height: 42px;
    background: url('lupo-images/s9b.png');
}

.resources-filters {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 20px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.filter-select, .search-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.content-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.content-table thead {
    background-color: #6c757d;
    color: white;
}

.content-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
}

.content-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.content-table tbody tr {
    cursor: pointer;
    transition: background-color 0.2s;
}

.content-table tbody tr:hover {
    background-color: #f8f9fa;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    padding: 20px;
}

.pagination a {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #333;
}

.pagination a:hover {
    background-color: #f8f9fa;
}

.pagination .current {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    font-weight: 600;
}

/* Container for the dropdown */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Style for the actual menu */
.dropdown-content {
  display: none; /* Hidden by default */
  position: absolute;
  background-color: #f9f9f9;
  min-width: 450px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  border-radius: 4px;
}

/* Style the links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 2px 6px;
  text-decoration: none;
  display: block;
}

/* Change color on hover */
.dropdown-content a:hover {
  background-color: #f1f1f1;
}

/* The class we will toggle with JavaScript */
.show {
  display: block;
}

#contentsDropdown {
  max-height: 400px;
  overflow-y: auto;
}

#folderDropdown {
  max-height: 400px;
  overflow-y: auto;
}

#nextDropdown {
  max-height: 400px;
  overflow-y: auto;
}

#prevDropdown {
  max-height: 400px;
  overflow-y: auto;
}

/* Style for the shortcut dropdown specifically */
#shortcutDropdown {
    min-width: 450px;
    padding: 10px;
    background: #fff;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
}
/* Style for the 'Add' buttons inside the menu */
.add-action {
    color: #28a745 !important; /* Green to signify adding */
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
function toggleMenu(menuId) {
  // 1. Close any other open dropdowns first
  var dropdowns = document.getElementsByClassName("dropdown-content");
  for (var i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.id !== menuId) {
      openDropdown.classList.remove('show');
    }
  }

  // 2. Toggle the one that was clicked
  document.getElementById(menuId).classList.toggle("show");
}

// Close the dropdown if the user clicks anywhere outside the images
window.onclick = function(event) {
  if (!event.target.matches('img')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      dropdowns[i].classList.remove('show');
    }
  }
}

function addNewItem(type, parentName) {
    parentName = parentName || '';
    if (typeof event !== 'undefined' && event) {
        event.stopPropagation();
    }
    var mainPrompt = (window.LUPO_HDR && window.LUPO_HDR.strings && window.LUPO_HDR.strings.prompt_main_tab)
        ? window.LUPO_HDR.strings.prompt_main_tab : 'Enter name for new Main Tab:';
    var subTmpl = (window.LUPO_HDR && window.LUPO_HDR.strings && window.LUPO_HDR.strings.prompt_sub_tab)
        ? window.LUPO_HDR.strings.prompt_sub_tab : 'Enter new Sub-Tab name for "%s":';
    var message = (type === 'main') ? mainPrompt : subTmpl.replace('%s', parentName);
    var userInput = prompt(message);
    if (userInput !== null && userInput.trim() !== '') {
        console.log('Action: Create ' + type + ', Name: ' + userInput + ', Parent: ' + parentName);
        var okTmpl = (window.LUPO_HDR && window.LUPO_HDR.strings && window.LUPO_HDR.strings.add_success)
            ? window.LUPO_HDR.strings.add_success : 'Successfully added "%s" to your collection!';
        alert(okTmpl.replace('%s', userInput));
    }
}

    </script>

<div class="content-list-container">
    <!-- Row 1: Top Border -->
    <div class="resources-top-left"></div>
   <div class="resources-top-center">
    
   <div class="dropdown">
    <img src="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/lupo-images/addshortcut.png' : 'lupo-images/addshortcut.png', ENT_QUOTES, 'UTF-8'); ?>" width="42" height="42" onclick="toggleMenu('shortcutDropdown')" style="cursor:pointer;"> 
    <div id="shortcutDropdown" class="dropdown-content">
     <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
        <b><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.current_label', 'Current collection:') : 'Current collection:', ENT_QUOTES, 'UTF-8'); ?></b> <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.current_desktop', 'DESKTOP') : 'DESKTOP', ENT_QUOTES, 'UTF-8'); ?><br>
        <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.instructions', 'Click the tab or sub-tab name to add this shortcut; use the blue collections control to pick a different collection.') : 'Click the tab or sub-tab name to add this shortcut; use the blue collections control to pick a different collection.', ENT_QUOTES, 'UTF-8'); ?>
     </div>

     <a href="#who" class="main-tab">| <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.who_main', 'WHO') : 'WHO', ENT_QUOTES, 'UTF-8'); ?></a>
     <a href="#wolfie" class="sub-tab">|— <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.wolfie_sub', 'WOLFIE') : 'WOLFIE', ENT_QUOTES, 'UTF-8'); ?></a>
     <a href="javascript:void(0)" class="add-action" onclick="addNewItem('sub', 'WHO')"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.new_sub_who', '+ New sub-tab for WHO') : '+ New sub-tab for WHO', ENT_QUOTES, 'UTF-8'); ?></a> 
     <a href="#what" class="main-tab">| <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.what_main', 'WHAT') : 'WHAT', ENT_QUOTES, 'UTF-8'); ?></a>
     <a href="#software" class="sub-tab">|— <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.software_sub', 'SOFTWARE') : 'SOFTWARE', ENT_QUOTES, 'UTF-8'); ?></a>
     <a href="javascript:void(0)" class="add-action" onclick="addNewItem('sub', 'WHAT')"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.new_sub_what', '+ New sub-tab for WHAT') : '+ New sub-tab for WHAT', ENT_QUOTES, 'UTF-8'); ?></a>
     <hr>
     <a href="javascript:void(0)" class="add-action global" onclick="addNewItem('main')"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.shortcut.create_main', '+ Create new main tab') : '+ Create new main tab', ENT_QUOTES, 'UTF-8'); ?></a>
    </div>
   </div>
 
 
  <div class="dropdown">
    <img src="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/lupo-images/contents.png' : 'lupo-images/contents.png', ENT_QUOTES, 'UTF-8'); ?>" width="42" height="42" onclick="toggleMenu('contentsDropdown')" style="cursor:pointer;">
 
     
    <div id="contentsDropdown" class="dropdown-content">
  
       <a href="#news"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.news', 'News and Updates') : 'News and Updates', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#download"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.download_cs', 'Download Crafty Syntax 3.7.5') : 'Download Crafty Syntax 3.7.5', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#howto"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.howto', 'How do I use Crafty Syntax right now?') : 'How do I use Crafty Syntax right now?', ENT_QUOTES, 'UTF-8'); ?></a>
        <a href="#legacy"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.legacy', 'Historical overview (where we came from)') : 'Historical overview (where we came from)', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#whycraftysyntax"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.why_name', 'Why the Crafty Syntax name returns in 3.7.5') : 'Why the Crafty Syntax name returns in 3.7.5', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/planfor_next_version_craftysyntax.php' : 'planfor_next_version_craftysyntax.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.plan_38', 'Crafty Syntax 3.8.0 modernization plan') : 'Crafty Syntax 3.8.0 modernization plan', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#future-roadmap"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.roadmap', 'Roadmap highlights') : 'Roadmap highlights', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#download"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.howto_dup', 'How do I use Crafty Syntax right now?') : 'How do I use Crafty Syntax right now?', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/lupopedia.php' : 'lupopedia.php', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.what_lupopedia', 'What is Lupopedia version 4.2.x?') : 'What is Lupopedia version 4.2.x?', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#challenge"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.chat_problem', 'Real-time chat problem') : 'Real-time chat problem', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#fallback"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.fallback', 'Fallback engineering') : 'Fallback engineering', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#fingerprinting"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.fingerprinting', 'Session fingerprinting') : 'Session fingerprinting', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#security"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.security', 'Security hardening') : 'Security hardening', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#documentation"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.documentation', 'Documentation discipline') : 'Documentation discipline', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#timestamps"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.timestamps', 'Timestamp discipline') : 'Timestamp discipline', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#innovations"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.innovations', 'Technical innovations') : 'Technical innovations', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#timeline"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.timeline', 'Complete timeline') : 'Complete timeline', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#features"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.features', 'Key features') : 'Key features', ENT_QUOTES, 'UTF-8'); ?></a>
        <a href="#distribution"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.distribution', 'Distribution and auto-installers') : 'Distribution and auto-installers', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#license"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.license', 'GPL license notes') : 'GPL license notes', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#video-demo"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.demo_video', 'Demo video') : 'Demo video', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#continuity"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.continuity', 'Crafty Syntax to WOLFIE') : 'Crafty Syntax to WOLFIE', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#next-steps"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.next_steps', 'Next steps') : 'Next steps', ENT_QUOTES, 'UTF-8'); ?></a> 
        <a href="#references"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.contents.references', 'References') : 'References', ENT_QUOTES, 'UTF-8'); ?></a> 


    </div>
  </div>

 
  <h1 id="firstHeading" class="firstHeading mw-first-heading">
    <span class="mw-page-title-main"><?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.page_title_main', 'Crafty Syntax Live Help') : 'Crafty Syntax Live Help', ENT_QUOTES, 'UTF-8'); ?></span>
  </h1>
 &nbsp;
  <img src="<?php echo htmlspecialchars($lupoPublicBase !== '' ? $lupoPublicBase . '/lupo-images/edges.png' : 'lupo-images/edges.png', ENT_QUOTES, 'UTF-8'); ?>" width="77" height="42"   style="cursor:pointer; ">

 


</div>
    <div class="resources-top-right"></div>
    <!-- Row 2: Middle Border and Content -->
    <div class="resources-middle-left"></div>
    <div class="resources-middle-center">
 
