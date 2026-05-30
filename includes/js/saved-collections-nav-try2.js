/**
 * Try2 collections nav: lupoDbgNavToggle, hover flyouts, master list hydrate.
 * Load after main-layout.js; after main-layout-collections.js if loadCollectionTabs is required.
 */
(function () {
    window.lupoDbgNavActiveSubmenu = window.lupoDbgNavActiveSubmenu || null;

    function lupoDbgHtmlEsc(s) {
        var d = document.createElement('div');
        d.textContent = s === null || typeof s === 'undefined' ? '' : String(s);
        return d.innerHTML;
    }

    window.lupoDbgNavCloseAll = function () {
        var nav = document.querySelector('.debug-try2-nav');
        if (nav) {
            nav.querySelectorAll('.dropdown').forEach(function (el) {
                el.classList.remove('active');
            });
        }
        if (window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
            window.lupoDbgNavActiveSubmenu = null;
        }
    };

    window.lupoDbgNavToggle = function (btn, ev) {
        if (ev) {
            if (ev.stopImmediatePropagation) {
                ev.stopImmediatePropagation();
            } else if (ev.stopPropagation) {
                ev.stopPropagation();
            }
        }
        var dropdown = btn.closest('.dropdown');
        if (!dropdown) {
            return;
        }
        var wasActive = dropdown.classList.contains('active');
        document.querySelectorAll('.debug-try2-nav .dropdown-button').forEach(function (b) {
            b.setAttribute('aria-expanded', 'false');
        });
        document.querySelectorAll('.debug-try2-nav .dropdown').forEach(function (d) {
            d.classList.remove('active');
        });
        if (window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
            window.lupoDbgNavActiveSubmenu = null;
        }
        if (!wasActive) {
            dropdown.classList.add('active');
            btn.setAttribute('aria-expanded', 'true');
            if (dropdown.classList.contains('master-collections-wrap')) {
                lupoDbgMasterHydrateIfNeeded();
            }
        }
    };

    window.lupoDbgNavOpenSubmenu = function (item) {
        if (window.lupoDbgNavActiveSubmenu) {
            window.lupoDbgNavActiveSubmenu.classList.remove('active');
        }
        var submenu = item._lupoFloatingSubmenu || item.querySelector('.floating-submenu');
        if (!submenu) {
            return;
        }
        item._lupoFloatingSubmenu = submenu;
        var rect = item.getBoundingClientRect();
        submenu.style.top = rect.top + 'px';
        submenu.style.left = (rect.right + 5) + 'px';
        submenu.classList.add('active');
        window.lupoDbgNavActiveSubmenu = submenu;
        if (!submenu.parentElement || submenu.parentElement.tagName !== 'BODY') {
            document.body.appendChild(submenu);
        }
    };

    function lupoDbgPublicBase() {
        if (window.LUPO_MAIN_LAYOUT && window.LUPO_MAIN_LAYOUT.publicPath) {
            return String(window.LUPO_MAIN_LAYOUT.publicPath);
        }
        if (window.LUPO_HDR && window.LUPO_HDR.base) {
            return String(window.LUPO_HDR.base);
        }
        return '';
    }

    /** Full page: same path as current script, set lupo_collection_id, keep other query params. */
    function lupoDbgCollectionSwitchHref(collectionId) {
        try {
            var u = new URL(window.location.href);
            u.searchParams.set('lupo_collection_id', String(collectionId));
            var q = u.searchParams.toString();
            return u.pathname + (q ? '?' + q : '') + (window.location.hash || '');
        } catch (e1) {
            return window.location.pathname + '?lupo_collection_id=' + encodeURIComponent(String(collectionId));
        }
    }

    function lupoDbgMasterGroupedMenuHtml(groups) {
        if (!groups || !groups.length) {
            return '';
        }
        var html = '';
        var gi;
        var ii;
        for (gi = 0; gi < groups.length; gi++) {
            var g = groups[gi];
            if (!g || !g.group_label || !g.items || !g.items.length) {
                continue;
            }
            html += '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
            html += '<span class="menu-item-label">' + lupoDbgHtmlEsc(String(g.group_label)) + '</span>';
            html += '<div class="floating-submenu">';
            for (ii = 0; ii < g.items.length; ii++) {
                var it = g.items[ii];
                if (!it || it.collection_id == null) {
                    continue;
                }
                var id = parseInt(String(it.collection_id), 10);
                var lab = it.label ? String(it.label) : ('Collection ' + id);
                var href = lupoDbgCollectionSwitchHref(id);
                html += '<a href="' + lupoDbgHtmlEsc(href) + '" class="menu-item" role="menuitem" tabindex="0">' + lupoDbgHtmlEsc(lab) + '</a>';
            }
            html += '</div></div>';
        }
        return html;
    }

    function lupoDbgMasterHydrateIfNeeded() {
        var panel = document.getElementById('try2-master-panel') || document.getElementById('dbg-master-panel');
        if (!panel || panel.getAttribute('data-hydrated') === '1') {
            return;
        }
        if (panel.getAttribute('data-master-nav-server') === '1') {
            panel.setAttribute('data-hydrated', '1');
            return;
        }
        if (panel.querySelector('.menu-item, a.menu-item')) {
            panel.setAttribute('data-hydrated', '1');
            return;
        }
        var base = lupoDbgPublicBase();
        var url = (base === '' ? '' : base) + '/api/list_nav_collections_grouped.php';
        fetch(url)
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                var master = document.querySelector('.master-collections-wrap');
                if (!master || !master.classList.contains('active')) {
                    return;
                }
                var grouped = data && data.success && data.groups && data.groups.length;
                var html = grouped ? lupoDbgMasterGroupedMenuHtml(data.groups) : '';
                if (!html) {
                    panel.innerHTML = '<span class="menu-item" style="color:#6c757d;">No collections</span>';
                    panel.setAttribute('data-hydrated', '1');
                    return;
                }
                panel.innerHTML = html;
                panel.setAttribute('data-hydrated', '1');
            })
            .catch(function () {
                var m = document.querySelector('.master-collections-wrap');
                if (!m || !m.classList.contains('active')) {
                    return;
                }
                panel.innerHTML = '<span class="menu-item" style="color:#dc3545;">Could not load collections</span>';
            });
    }
})();
