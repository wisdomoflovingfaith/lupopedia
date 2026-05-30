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
    
    // Toggle this dropdown (parent + content: unified .active for CSS)
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

if (typeof window.lupoOpenSubmenuContent === 'undefined') {
    window.lupoOpenSubmenuContent = null;
}

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
        var dbgLabel = (trigger && trigger.textContent) ? String(trigger.textContent).replace(/^\s+|\s+$/g, '') : '';
        console.log('toggleSubmenu (main-layout.js):', dbgLabel, 'isOpening=', isOpening);
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

document.addEventListener('click', function (event) {
    var t = event.target;
    var el = (t && t.nodeType === 3 && t.parentElement) ? t.parentElement : t;
    if (!el || !el.closest) {
        return;
    }
    /* Toggles (triggers) vs panels: IMG in .dropdown must count as toggle (shortcut uses lupoOpen*, not toggleMenu). */
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

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.debug-try2-nav .lupo-dropdown').forEach(function (d) {
            d.classList.remove('active');
        });
        if (typeof window.lupoDbgNavActiveSubmenu !== 'undefined' && window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
            window.lupoDbgNavActiveSubmenu = null;
        }
        document.querySelectorAll('.saved-collections-dropdown').forEach(function(d) {
            d.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-dropdown-content').forEach(function (p) {
            p.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-submenu').forEach(function(s) {
            s.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-submenu-content').forEach(function(content) {
            if (content.parentNode === document.body) {
                content.remove();
            }
            content.classList.remove('active');
            content.style.display = 'none';
        });
        window.lupoOpenSubmenuContent = null;
        document.querySelectorAll('.dropdown-content').forEach(function (el) {
            el.classList.remove('show', 'active');
        });
    }
});

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
 * Hydrate green collection-tab buttons when server rendered none inside #collection-tabs-container.
 */
document.addEventListener('DOMContentLoaded', function () {
    if (window.lupoMainLayoutCollectionTabsRan) {
        return;
    }
    var container = document.getElementById('collection-tabs-container');
    if (!container || typeof window.loadCollectionTabs !== 'function') {
        return;
    }
    var innerDrops = container.querySelectorAll('.saved-collections-dropdown');
    var try2Drops = container.querySelectorAll('.lupo-dropdown');
    if (innerDrops.length > 0 || try2Drops.length > 0) {
        return;
    }
    window.lupoMainLayoutCollectionTabsRan = true;
    var input = document.getElementById('active-collection-id');
    var rawId = input ? input.value : null;
    if (rawId === null || rawId === '') {
        rawId = '0';
    }
    var collectionId = parseInt(rawId, 10);
    if (isNaN(collectionId) || collectionId < 0) {
        collectionId = 0;
    }
    var name = (window.LUPO_MAIN_LAYOUT && window.LUPO_MAIN_LAYOUT.currentCollectionName)
        ? String(window.LUPO_MAIN_LAYOUT.currentCollectionName)
        : 'System Collection';
    window.loadCollectionTabs(collectionId, name);
});

function addNewItem(type, parentName) {
    if (typeof parentName === 'undefined') {
        parentName = '';
    }
    if (typeof event !== 'undefined' && event) {
        event.stopPropagation();
    }
    var S = (window.LUPO_HDR && window.LUPO_HDR.strings) ? window.LUPO_HDR.strings : {};
    var message = (type === 'main')
        ? (S.prompt_main_tab || 'Enter name for new Main Tab:')
        : (S.prompt_sub_tab || 'Enter new Sub-Tab name for "%s":').replace('%s', parentName);
    var userInput = prompt(message);
    if (userInput !== null && userInput.trim() !== '') {
        var okTmpl = S.add_success || 'Successfully added "%s" to your collection!';
        alert(okTmpl.replace('%s', userInput));
    }
}

window.__lupoMainLayoutDropdownCoreLoaded = true;

/** Toggle footer fixed semantic bar (#lupo-footer-semantic-nav in footer.php); body class drives title-row hide icon + .main-footer padding. */
function lupoSemanticBottomNavSetVisible(visible) {
    var bar = document.getElementById('lupo-footer-semantic-nav');
    if (bar) {
        if (visible) {
            bar.removeAttribute('hidden');
        } else {
            bar.setAttribute('hidden', '');
        }
    }
    if (visible) {
        document.body.classList.add('lupo-semantic-nav-visible');
    } else {
        document.body.classList.remove('lupo-semantic-nav-visible');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        var t = e.target;
        if (!t || !t.closest) {
            return;
        }
        if (t.closest('.lupo-semantic-show-nav-trigger')) {
            e.preventDefault();
            lupoSemanticBottomNavSetVisible(true);
            return;
        }
        if (t.closest('.lupo-semantic-hide-nav-trigger')) {
            e.preventDefault();
            lupoSemanticBottomNavSetVisible(false);
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== ' ') {
            return;
        }
        var el = e.target;
        if (!el || !el.classList) {
            return;
        }
        if (el.classList.contains('lupo-semantic-show-nav-trigger')) {
            e.preventDefault();
            lupoSemanticBottomNavSetVisible(true);
            return;
        }
        if (el.classList.contains('lupo-semantic-hide-nav-trigger')) {
            e.preventDefault();
            lupoSemanticBottomNavSetVisible(false);
        }
    });
});
