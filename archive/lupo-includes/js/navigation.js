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

console.log('%c navigation.js LOADED', 'color: red; font-weight: bold;');
window.DEBUG_DROPDOWNS = true;

var __lupoNavSkipDupBundle = false;
if (typeof window.__lupoMainLayoutDropdownCoreLoaded !== 'undefined' && window.__lupoMainLayoutDropdownCoreLoaded === true) {
    __lupoNavSkipDupBundle = true;
} else if (typeof window.toggleSubmenu === 'function' && window.toggleSubmenu.toString && window.toggleSubmenu.toString().length > 500) {
    __lupoNavSkipDupBundle = true;
}

if (__lupoNavSkipDupBundle) {
    console.warn('navigation.js: main-layout dropdown core already loaded (or toggleSubmenu already defined) — skipping duplicate definitions and listeners.');
} else {

if (typeof window.lupoOpenSubmenuContent === 'undefined') {
    window.lupoOpenSubmenuContent = null;
}

/**
 * Toggle saved collections dropdown
 * 
 * @param {HTMLElement} button The button that triggered the toggle
 */
function toggleSavedCollectionsDropdown(button, evt) {
    if (evt && evt.stopPropagation) {
        evt.stopPropagation();
    }
    const dropdown = button.closest('.saved-collections-dropdown');
    if (!dropdown) {
        return;
    }
    const isActive = dropdown.classList.contains('active');
    const panel = dropdown.querySelector('.saved-collections-dropdown-content');

    // Close all other dropdowns and their submenus
    document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
        if (d !== dropdown) {
            d.classList.remove('active');
            const p = d.querySelector('.saved-collections-dropdown-content');
            if (p) {
                p.classList.remove('active');
            }
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
    
    const newState = !isActive;
    dropdown.classList.toggle('active', newState);
    if (panel) {
        panel.classList.toggle('active', newState);
    }
    button.setAttribute('aria-expanded', newState.toString());
    if (newState && button.closest('.lupo-master-collections-wrap')) {
        var d = dropdown;
        var p = panel;
        var tryHydrate = function () {
            if (typeof window.lupoMasterCollectionsHydrateIfEmpty === 'function') {
                window.lupoMasterCollectionsHydrateIfEmpty(d, p);
                return true;
            }
            return false;
        };
        if (!tryHydrate()) {
            window.setTimeout(function () {
                tryHydrate();
            }, 0);
        }
    }
}

/**
 * Toggle submenu (nested dropdown)
 * 
 * @param {HTMLElement} trigger The trigger element
 * @param {Event} event The click event
 */
function toggleSubmenu(trigger, event) {
    if (event) {
        event.preventDefault();
        if (event.stopImmediatePropagation) {
            event.stopImmediatePropagation();
        } else {
            event.stopPropagation();
        }
    }

    const submenu = trigger.closest('.saved-collections-submenu');
    if (!submenu) {
        return;
    }

    const content = submenu.querySelector('.saved-collections-submenu-content');
    if (!content) {
        return;
    }

    const isOpening = !submenu.classList.contains('active');
    if (window.DEBUG_DROPDOWNS) {
        var dbgLabelNav = (trigger && trigger.textContent) ? String(trigger.textContent).replace(/^\s+|\s+$/g, '') : '';
        console.log('toggleSubmenu (navigation.js):', dbgLabelNav, 'isOpening=', isOpening);
    }
    const parentEl = submenu.parentElement;

    if (parentEl) {
        parentEl.querySelectorAll(':scope > .saved-collections-submenu').forEach(function (sib) {
            if (sib === submenu) {
                return;
            }
            sib.classList.remove('active');
            const sibContent = sib.querySelector('.saved-collections-submenu-content');
            if (!sibContent) {
                return;
            }
            sibContent.classList.remove('active');
            if (sibContent.parentNode === document.body) {
                sibContent.remove();
            }
            sibContent.style.display = '';
        });
    }

    if (window.lupoOpenSubmenuContent && window.lupoOpenSubmenuContent.parentNode === document.body &&
        !submenu.contains(window.lupoOpenSubmenuContent)) {
        var dsOrphan = window.lupoOpenSubmenuContent.getAttribute('data-source-id');
        if (content.id) {
            if (dsOrphan !== content.id) {
                window.lupoOpenSubmenuContent.remove();
                window.lupoOpenSubmenuContent = null;
            }
        } else {
            window.lupoOpenSubmenuContent.remove();
            window.lupoOpenSubmenuContent = null;
        }
    }

    if (isOpening) {
        submenu.classList.add('active');

        let floating = content;
        if (content.parentNode !== document.body) {
            floating = content.cloneNode(true);
            if (content.id) {
                floating.setAttribute('data-source-id', content.id);
            }
            floating.removeAttribute('id');
            floating.querySelectorAll('.saved-collections-submenu-content').forEach(function (c) {
                c.style.display = 'none';
                c.classList.remove('active');
            });
            document.body.appendChild(floating);
            window.lupoOpenSubmenuContent = floating;
            content.style.display = 'none';
            content.classList.remove('active');
        } else {
            window.lupoOpenSubmenuContent = floating;
        }

        floating.classList.add('active');
        const rect = trigger.getBoundingClientRect();
        floating.style.position = 'fixed';
        floating.style.display = 'block';
        floating.style.left = (rect.right + 4) + 'px';
        floating.style.top = rect.top + 'px';
        floating.style.zIndex = '10001';

        const approxW = floating.offsetWidth || 280;
        if (rect.right + approxW > window.innerWidth) {
            floating.style.left = Math.max(4, rect.left - approxW - 4) + 'px';
        }
        const vh = window.innerHeight;
        const fh = floating.offsetHeight || 0;
        let topPx = parseFloat(floating.style.top, 10);
        if (isNaN(topPx)) {
            topPx = rect.top;
        }
        if (fh > 0 && topPx + fh > vh) {
            floating.style.top = Math.max(4, vh - fh - 8) + 'px';
        }
    } else {
        submenu.classList.remove('active');
        content.classList.remove('active');
        content.style.display = '';
        const rm = window.lupoOpenSubmenuContent;
        if (rm && rm.parentNode === document.body) {
            rm.remove();
        }
        if (content.id) {
            document.querySelectorAll('.saved-collections-submenu-content[data-source-id]').forEach(function (node) {
                if (node.parentNode === document.body && node.getAttribute('data-source-id') === content.id) {
                    node.remove();
                }
            });
        }
        window.lupoOpenSubmenuContent = null;
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
 * Close dropdowns when clicking outside (aligned with main-layout.js — no window.onclick).
 */
document.addEventListener('click', function (event) {
    var t = event.target;
    var el = (t && t.nodeType === 3 && t.parentElement) ? t.parentElement : t;
    if (!el || !el.closest) {
        return;
    }
    var isToggle = !!(
        el.closest('.saved-collections-button') ||
        el.closest('.nav-link') ||
        (el.tagName === 'IMG' && el.closest('.dropdown')) ||
        el.closest('.debug-try2-nav .dropdown-button')
    );
    var isMenu = !!(
        el.closest('.saved-collections-dropdown-content') ||
        el.closest('.dropdown-content') ||
        el.closest('.saved-collections-submenu-content') ||
        el.closest('.debug-try2-nav .dropdown-panel') ||
        el.closest('.debug-try2-nav .lupo-dropdown') ||
        el.closest('.floating-submenu')
    );
    if (isToggle || isMenu) {
        return;
    }
    document.querySelectorAll('.dropdown-content').forEach(function (node) {
        node.classList.remove('show', 'active');
    });
    document.querySelectorAll('.saved-collections-dropdown').forEach(function (d) {
        d.classList.remove('active');
    });
    document.querySelectorAll('.saved-collections-dropdown-content').forEach(function (p) {
        p.classList.remove('active');
    });
    document.querySelectorAll('.saved-collections-submenu').forEach(function (s) {
        s.classList.remove('active');
    });
    document.querySelectorAll('.saved-collections-submenu-content').forEach(function (content) {
        if (content.parentNode === document.body) {
            content.remove();
        }
        content.classList.remove('active');
        content.style.display = 'none';
    });
    window.lupoOpenSubmenuContent = null;
    document.querySelectorAll('.debug-try2-nav .lupo-dropdown').forEach(function (d) {
        d.classList.remove('active');
    });
    if (typeof window.lupoDbgNavActiveSubmenu !== 'undefined' && window.lupoDbgNavActiveSubmenu) {
        window.lupoDbgNavActiveSubmenu.classList.remove('active');
        window.lupoDbgNavActiveSubmenu = null;
    }
});

/**
 * Keyboard navigation support
 */
document.addEventListener('keydown', function(event) {
    // Close on Escape key
    if (event.key === 'Escape') {
        document.querySelectorAll('.debug-try2-nav .lupo-dropdown').forEach(function (d) {
            d.classList.remove('active');
        });
        if (typeof window.lupoDbgNavActiveSubmenu !== 'undefined' && window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
            window.lupoDbgNavActiveSubmenu = null;
        }
        document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
            d.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-dropdown-content').forEach(function (p) {
            p.classList.remove('active');
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
        window.lupoOpenSubmenuContent = null;
        document.querySelectorAll('.dropdown-content').forEach(function (el) {
            el.classList.remove('show', 'active');
        });
    }
});

/**
 * Toggle menu dropdown (for tabs and contents)
 *
 * @param {string} menuId The ID of the menu to toggle
 */
function toggleMenu(menuId, evt) {
    if (evt) {
        if (evt.stopImmediatePropagation) {
            evt.stopImmediatePropagation();
        } else if (evt.stopPropagation) {
            evt.stopPropagation();
        }
    }
    var targetEl = document.getElementById(menuId);
    if (!targetEl) {
        return;
    }
    var dropdowns = document.getElementsByClassName('dropdown-content');
    for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.id !== menuId) {
            openDropdown.classList.remove('show', 'active');
        }
    }
    var nowActive = targetEl.classList.toggle('active');
    targetEl.classList.toggle('show', nowActive);
}

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

}
