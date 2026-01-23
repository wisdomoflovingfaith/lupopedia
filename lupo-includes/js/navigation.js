/**
 * wolfie.header.identity: navigation-js
 * wolfie.header.placement: /lupo-includes/js/navigation.js
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: navigation-js
 *   message: "Created navigation JavaScript: extracted functions for saved collections dropdowns, submenus, and tabs from header.php mockup. Includes toggleSavedCollectionsDropdown, toggleSubmenu, toggleMenu, and addNewItem functions."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

/**
 * ---------------------------------------------------------
 * Navigation JavaScript Functions
 * ---------------------------------------------------------
 * 
 * Handles dropdown menus, submenus, and tab navigation.
 * Extracted from header.php mockup.
 */

/**
 * Toggle saved collections dropdown
 * 
 * @param {HTMLElement} button The button that triggered the toggle
 */
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

/**
 * Store references to open submenus for cleanup
 */
let openSubmenuContent = null;

/**
 * Toggle submenu (nested dropdown)
 * 
 * @param {HTMLElement} trigger The trigger element
 * @param {Event} event The click event
 */
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

/**
 * Recalculate submenu position on scroll and resize (debounced)
 */
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

/**
 * Close dropdowns when clicking outside
 */
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

/**
 * Keyboard navigation support
 */
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

/**
 * Toggle menu dropdown (for tabs and contents)
 * 
 * @param {string} menuId The ID of the menu to toggle
 */
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

/**
 * Close dropdown if user clicks outside
 */
window.onclick = function(event) {
    if (!event.target.matches('img')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].classList.remove('show');
        }
    }
};

/**
 * Add new item (main tab or sub-tab)
 * 
 * @param {string} type 'main' or 'sub'
 * @param {string} parentName Parent name for sub-tabs
 */
function addNewItem(type, parentName = '') {
    // Stop the click from bubbling up to window.onclick
    if (event) event.stopPropagation();
    
    let message = (type === 'main') 
        ? "Enter name for new Main Tab:" 
        : `Enter new Sub-Tab name for "${parentName}":`;
    
    let userInput = prompt(message);
    
    if (userInput !== null && userInput.trim() !== "") {
        // Logic to save to your database/backend goes here
        console.log(`Action: Create ${type}, Name: ${userInput}, Parent: ${parentName}`);
        
        alert(`Successfully added "${userInput}" to your collection!`);
        
        // Optional: Refresh the page or update UI dynamically
        // location.reload(); 
    }
}
