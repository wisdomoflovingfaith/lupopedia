/* Collection modals, save/load/delete, AJAX tabs — prefers window.LUPO_MAIN_LAYOUT from main_layout.php; fallbacks if missing. */
function lupoPublicPath() {
    var c = window.LUPO_MAIN_LAYOUT || {};
    var p = c.publicPath;
    if (p !== undefined && p !== null && String(p) !== '') {
        return String(p);
    }
    var hdr = window.LUPO_HDR || {};
    if (hdr.base !== undefined && hdr.base !== null && String(hdr.base) !== '') {
        return String(hdr.base);
    }
    var path = window.location.pathname || '';
    var match = path.match(/^(\/[^/]+)\//);
    return match ? match[1] : '';
}

function lupoHdrStrings() {
    return (window.LUPO_HDR && window.LUPO_HDR.strings) ? window.LUPO_HDR.strings : {};
}

var currentLoadedCollectionId = 0;
var currentLoadedCollectionName = null;

(function initMainLayoutCollections() {
    var c = window.LUPO_MAIN_LAYOUT || {};
    var id = c.collectionId;
    if (id !== undefined && id !== null) {
        currentLoadedCollectionId = parseInt(String(id), 10);
        if (isNaN(currentLoadedCollectionId)) {
            currentLoadedCollectionId = 0;
        }
    }
    currentLoadedCollectionName = (c.currentCollectionName !== undefined) ? c.currentCollectionName : null;
})();

/**
 * Client-side page context: URL query (slug, id, artifact_type, memory_key) + DOM title.
 * Exposed as window.get_current_page_context for tooling/docs.
 */
function lupoPathnameAfterPublicBase() {
    var pn = window.location.pathname || '';
    var base = lupoPublicPath();
    if (base && pn.indexOf(base) === 0) {
        pn = pn.slice(base.length);
    }
    pn = pn.replace(/^\/+/, '').replace(/\/+$/, '');
    return pn;
}

function get_current_page_context() {
    var ctx = { slug: '', id: 0, artifact_type: '', memory_key: '', title: '' };
    try {
        var u = new URL(window.location.href);
        var sp = u.searchParams;
        if (sp.get) {
            ctx.slug = sp.get('slug') || '';
            var idRaw = sp.get('id') || sp.get('content_id') || '';
            ctx.id = idRaw ? parseInt(String(idRaw), 10) : 0;
            ctx.artifact_type = sp.get('artifact_type') || '';
            ctx.memory_key = sp.get('memory_key') || '';
        }
    } catch (e1) {
        var q = window.location.search || '';
        if (q.charAt(0) === '?') {
            q = q.slice(1);
        }
        var parts = q.split('&');
        for (var i = 0; i < parts.length; i++) {
            var kv = parts[i].split('=');
            var k = decodeURIComponent(kv[0] || '');
            var v = decodeURIComponent((kv[1] || '').replace(/\+/g, ' '));
            if (k === 'slug') {
                ctx.slug = v;
            }
            if (k === 'id' || k === 'content_id') {
                ctx.id = parseInt(v, 10) || 0;
            }
            if (k === 'artifact_type') {
                ctx.artifact_type = v;
            }
            if (k === 'memory_key') {
                ctx.memory_key = v;
            }
        }
    }
    if (isNaN(ctx.id)) {
        ctx.id = 0;
    }
    /* Canonical paths: .../content/<slug> and .../collection/<id>/content/<slug> (query string optional). */
    if (!ctx.slug) {
        var rel = lupoPathnameAfterPublicBase();
        var m1 = rel.match(/^content\/([^/?#]+)$/i);
        if (m1 && m1[1]) {
            try {
                ctx.slug = decodeURIComponent(m1[1]);
            } catch (e2) {
                ctx.slug = m1[1];
            }
        } else {
            var m2 = rel.match(/^collection\/\d+\/content\/([^/?#]+)$/i);
            if (m2 && m2[1]) {
                try {
                    ctx.slug = decodeURIComponent(m2[1]);
                } catch (e3) {
                    ctx.slug = m2[1];
                }
            }
        }
    }
    var hid = document.getElementById('lupo-current-content-id');
    if (hid && hid.value) {
        var hidId = parseInt(String(hid.value), 10);
        if (!isNaN(hidId) && hidId > 0) {
            ctx.id = hidId;
        }
    }
    var tEl = document.querySelector('#firstHeading .mw-page-title-main');
    if (tEl && tEl.textContent) {
        ctx.title = String(tEl.textContent).replace(/^\s+|\s+$/g, '');
    }
    if (!ctx.title && document.title) {
        var dt = document.title;
        var idx = dt.indexOf(' - ');
        ctx.title = idx > 0 ? dt.slice(0, idx).replace(/^\s+|\s+$/g, '') : dt.replace(/^\s+|\s+$/g, '');
    }
    return ctx;
}

/**
 * Merge URL/DOM page context into LUPO_MAIN_LAYOUT so shortcut pin and dropdowns
 * see slug/title/content id even if server-rendered hidden fields lag path-based routes.
 */
function lupoMasterPanelLooksEmpty(panel) {
    if (!panel) {
        return true;
    }
    var el = panel.querySelector('.saved-collections-submenu, .saved-collections-item, .menu-item, a');
    if (el) {
        return false;
    }
    var t = (panel.textContent || '').replace(/^\s+|\s+$/g, '');
    return t.length === 0;
}

var lupoMasterHydrateInflight = false;

function lupoMasterNavGroupedHtml(groups) {
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
        html += '<span class="menu-item-label">' + htmlEscape(String(g.group_label)) + '</span>';
        html += '<div class="floating-submenu">';
        for (ii = 0; ii < g.items.length; ii++) {
            var it = g.items[ii];
            if (!it || it.collection_id == null) {
                continue;
            }
            var id = parseInt(String(it.collection_id), 10);
            var lab = it.label ? String(it.label) : ('Collection ' + id);
            var hrefFn = typeof lupoCollectionContextSwitchHref === 'function' ? lupoCollectionContextSwitchHref : null;
            var href = hrefFn ? hrefFn(id) : (window.location.pathname + '?lupo_collection_id=' + encodeURIComponent(String(id)));
            html += '<a href="' + htmlEscape(href) + '" class="menu-item" role="menuitem" tabindex="0">' +
                htmlEscape(lab) + '</a>';
        }
        html += '</div></div>';
    }
    return html;
}

/**
 * When master (light blue) panel is server-empty, fill from list_nav_collections_grouped.php once.
 */
function lupoMasterCollectionsHydrateIfEmpty(dropdown, panel) {
    if (!dropdown || !panel) {
        return;
    }
    if (!dropdown.classList.contains('lupo-master-collections-wrap')) {
        return;
    }
    if (panel.getAttribute('data-lupo-master-list-hydrated') === '1') {
        return;
    }
    if (panel.getAttribute('data-lupo-master-nav-server') === '1') {
        panel.setAttribute('data-lupo-master-list-hydrated', '1');
        return;
    }
    if (!lupoMasterPanelLooksEmpty(panel)) {
        return;
    }
    if (lupoMasterHydrateInflight) {
        return;
    }
    lupoMasterHydrateInflight = true;
    panel.innerHTML = '<span class="menu-item" style="color:#6c757d;font-style:italic;">Loading…</span>';
    var url = lupoPublicPath() + '/lupo-api/list_nav_collections_grouped.php';
    fetch(url)
        .then(function (response) { return response.json(); })
        .then(function (data) {
            lupoMasterHydrateInflight = false;
            if (!dropdown.classList.contains('active')) {
                return;
            }
            var grouped = data && data.success && data.groups && data.groups.length;
            var html = grouped ? lupoMasterNavGroupedHtml(data.groups) : '';
            if (!html) {
                if (dropdown.classList.contains('active')) {
                    panel.innerHTML = '<span class="menu-item" style="color:#6c757d;">No collections</span>';
                }
                return;
            }
            if (!dropdown.classList.contains('active')) {
                return;
            }
            panel.innerHTML = html;
            panel.setAttribute('data-lupo-master-list-hydrated', '1');
        })
        .catch(function () {
            lupoMasterHydrateInflight = false;
            if (!dropdown.classList.contains('active')) {
                return;
            }
            panel.innerHTML = '<span class="menu-item" style="color:#dc3545;">Could not load collections</span>';
        });
}

function lupoCloseMasterCollectionsDropdown() {
    var w = document.querySelector('.lupo-master-collections-wrap');
    if (!w) {
        return;
    }
    var p = w.querySelector('.saved-collections-dropdown-content') || w.querySelector('.dropdown-panel');
    w.classList.remove('active');
    if (p) {
        p.classList.remove('active');
    }
    var btn = w.querySelector('.saved-collections-button') || w.querySelector('.dropdown-button');
    if (btn) {
        btn.setAttribute('aria-expanded', 'false');
    }
}

window.lupoMasterCollectionsHydrateIfEmpty = lupoMasterCollectionsHydrateIfEmpty;
window.lupoCloseMasterCollectionsDropdown = lupoCloseMasterCollectionsDropdown;

function lupoSyncPageContextToMainLayout() {
    if (!window.LUPO_MAIN_LAYOUT) {
        window.LUPO_MAIN_LAYOUT = {};
    }
    if (typeof get_current_page_context !== 'function') {
        return;
    }
    var c = get_current_page_context();
    window.LUPO_MAIN_LAYOUT.pageSlug = c.slug ? String(c.slug) : '';
    window.LUPO_MAIN_LAYOUT.pageTitle = c.title ? String(c.title) : '';
    if (c.id > 0) {
        window.LUPO_MAIN_LAYOUT.contentId = c.id;
        var hid = document.getElementById('lupo-current-content-id');
        if (hid) {
            var cur = parseInt(String(hid.value || ''), 10);
            if (isNaN(cur) || cur <= 0) {
                hid.value = String(c.id);
            }
        }
    }
}

function lupoBuildShortcutTabsListHtml(tabsData) {
    var html = '';
    if (!tabsData || typeof tabsData !== 'object') {
        return html;
    }
    for (var mainTab in tabsData) {
        if (!tabsData.hasOwnProperty(mainTab)) {
            continue;
        }
        var subTabs = tabsData[mainTab];
        var rootId = (subTabs && subTabs._collection_tab_id != null) ? parseInt(String(subTabs._collection_tab_id), 10) : 0;
        if (isNaN(rootId)) {
            rootId = 0;
        }
        html += '<a href="javascript:void(0)" class="main-tab lupo-shortcut-pin" role="button" data-collection-tab-id="' + rootId + '" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">| ' + htmlEscape(mainTab) + '</a>';
        if (subTabs && subTabs._children && subTabs._children.length) {
            for (var ci = 0; ci < subTabs._children.length; ci++) {
                var ch = subTabs._children[ci];
                var cname = ch && ch.name ? String(ch.name) : '';
                var ctid = ch && ch.collection_tab_id != null ? parseInt(String(ch.collection_tab_id), 10) : 0;
                if (isNaN(ctid)) {
                    ctid = 0;
                }
                if (!cname) {
                    continue;
                }
                html += '<a href="javascript:void(0)" class="sub-tab lupo-shortcut-pin" role="button" data-collection-tab-id="' + ctid + '" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">|— ' + htmlEscape(cname) + '</a>';
            }
        } else if (isArray(subTabs)) {
            for (var key in subTabs) {
                if (!subTabs.hasOwnProperty(key) || key === '_slug') {
                    continue;
                }
                if (String(key).charAt(0) === '_') {
                    continue;
                }
                var val = subTabs[key];
                if (typeof val !== 'string') {
                    continue;
                }
                html += '<a href="javascript:void(0)" class="sub-tab lupo-shortcut-pin" role="button" data-collection-tab-id="0" onclick="lupoAddCurrentPageToCollectionTab(this); return false;">|— ' + htmlEscape(val) + '</a>';
            }
        }
        html += '<a href="javascript:void(0)" class="add-action" onclick="addNewItem(\'sub\', ' + JSON.stringify(mainTab) + ')">' + htmlEscape('+ New Sub-Tab for ' + mainTab) + '</a>';
    }
    return html;
}

function lupoSyncShortcutTabsFromServerData(tabsData, collectionName) {
    var dyn = document.getElementById('shortcut-tabs-dynamic');
    if (dyn) {
        var keys = tabsData && typeof tabsData === 'object' ? Object.keys(tabsData) : [];
        if (keys.length === 0) {
            var S = lupoHdrStrings();
            dyn.innerHTML = '<span style="padding:8px;color:#6c757d;font-style:italic;display:block;">' +
                htmlEscape(S.shortcut_tabs_empty || 'No tabs for this collection. Load collection 0 seed tabs or choose another collection.') +
                '</span>';
        } else {
            dyn.innerHTML = lupoBuildShortcutTabsListHtml(tabsData);
        }
    }
    if (collectionName) {
        var dsp = document.getElementById('current-collection-display');
        if (dsp) {
            dsp.textContent = collectionName;
        }
        if (window.LUPO_MAIN_LAYOUT) {
            window.LUPO_MAIN_LAYOUT.currentCollectionName = collectionName;
        }
        currentLoadedCollectionName = collectionName;
    }
}

function lupoRefreshShortcutDropdown(done) {
    lupoSyncPageContextToMainLayout();
    var collectionId = currentLoadedCollectionId;
    var hid = document.getElementById('active-collection-id');
    if (hid && hid.value !== '') {
        var parsed = parseInt(hid.value, 10);
        if (!isNaN(parsed)) {
            collectionId = parsed;
        }
    }
    var ml = window.LUPO_MAIN_LAYOUT || {};
    if (ml.collectionId !== undefined && ml.collectionId !== null) {
        var p = parseInt(String(ml.collectionId), 10);
        if (!isNaN(p)) {
            collectionId = p;
        }
    }
    var dyn = document.getElementById('shortcut-tabs-dynamic');
    if (!dyn) {
        if (typeof done === 'function') {
            done();
        }
        return;
    }
    var S = lupoHdrStrings();
    dyn.innerHTML = '<span class="shortcut-tabs-loading" style="padding:8px;color:#6c757d;display:block;">' +
        htmlEscape(S.shortcut_tabs_loading || 'Loading tabs…') + '</span>';
    var url = lupoPublicPath() + '/lupo-api/load_collection_tabs.php?collection_id=' + collectionId;
    fetch(url)
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data && data.success) {
                var td = (data.tabs_data && Object.keys(data.tabs_data).length > 0) ? data.tabs_data : {};
                lupoSyncShortcutTabsFromServerData(td, data.collection_name);
            } else {
                dyn.innerHTML = '<span style="padding:8px;color:#dc3545;">' +
                    htmlEscape(S.shortcut_tabs_error || 'Could not load tabs.') + '</span>';
            }
            if (typeof done === 'function') {
                done();
            }
        })
        .catch(function () {
            dyn.innerHTML = '<span style="padding:8px;color:#dc3545;">' +
                htmlEscape(S.shortcut_tabs_error || 'Could not load tabs.') + '</span>';
            if (typeof done === 'function') {
                done();
            }
        });
}

function lupoOpenShortcutDropdown(e) {
    if (e) {
        if (e.stopImmediatePropagation) {
            e.stopImmediatePropagation();
        } else if (e.stopPropagation) {
            e.stopPropagation();
        }
        if (e.preventDefault) {
            e.preventDefault();
        }
    }
    lupoSyncPageContextToMainLayout();
    var sd = document.getElementById('shortcutDropdown');
    if (!sd) {
        return;
    }
    var isShowing = sd.classList.contains('active') || sd.classList.contains('show');
    if (isShowing) {
        sd.classList.remove('active', 'show');
        return;
    }
    lupoRefreshShortcutDropdown(function () {
        var dropdowns = document.getElementsByClassName('dropdown-content');
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            if (dropdowns[i].id !== 'shortcutDropdown') {
                dropdowns[i].classList.remove('show', 'active');
            }
        }
        sd.classList.add('active', 'show');
    });
}

window.get_current_page_context = get_current_page_context;
window.lupoOpenShortcutDropdown = lupoOpenShortcutDropdown;
window.lupoRefreshShortcutDropdown = lupoRefreshShortcutDropdown;
window.lupoSyncPageContextToMainLayout = lupoSyncPageContextToMainLayout;
window.lupoSyncShortcutTabsFromServerData = lupoSyncShortcutTabsFromServerData;

function editCurrentCollection() {
    if (currentLoadedCollectionId) {
        window.location.href = lupoPublicPath() + '/edit_collection.php?id=' + currentLoadedCollectionId;
    } else {
        var S = lupoHdrStrings();
        alert((S.collections_edit_save_first || '') + '\n\n' + (S.collections_edit_open_save || ''));
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
    var S = lupoHdrStrings();
    var name = document.getElementById('collectionName').value.trim();
    var description = document.getElementById('collectionDescription').value.trim();

    if (!name) {
        alert(S.collections_name_required || 'Please enter a name for this collection');
        return;
    }

    var isUpdate = currentLoadedCollectionId && name === currentLoadedCollectionName;

    fetch(lupoPublicPath() + '/lupo-api/save_collection.php', {
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
            alert(S.collections_saved_ok || 'Collection saved successfully!');
            currentLoadedCollectionId = data.collection_id;
            currentLoadedCollectionName = name;
            closeSaveCollectionModal();
        } else {
            var errPre = S.collections_error_prefix || 'Error: ';
            var fail = S.collections_save_failed || 'Failed to save collection';
            alert(errPre + (data.error || fail));
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert(S.collections_save_try_again || 'Error saving collection. Please try again.');
    });
}

function loadCollectionsList() {
    var S = lupoHdrStrings();
    var container = document.getElementById('collectionsList');
    var loadingShort = S.collections_loading_short || 'Loading...';
    container.innerHTML = '<div style="text-align: center; padding: 40px; color: #6c757d;">' + htmlEscape(loadingShort) + '</div>';

    fetch(lupoPublicPath() + '/lupo-api/list_collections.php')
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success && data.collections.length > 0) {
            var html = '';
            var activeP = S.collections_active_prefix || '[Active] ';
            var nd = S.collections_no_description || 'No description';
            var savedItems = S.collections_saved_items || 'saved items';
            var createdLbl = S.collections_created || 'Created:';
            var loadBtn = S.collections_load_btn || 'Load';
            var delBtn = S.collections_delete_btn || 'Delete';
            data.collections.forEach(function(collection) {
                var isCurrentlyLoaded = (collection.id == currentLoadedCollectionId);
                var border = isCurrentlyLoaded ? '#28a745' : '#D4AF37';
                var bg = isCurrentlyLoaded ? 'background: #d4edda;' : 'background: #f8f9fa;';
                html += '<div style="border: 2px solid ' + border + '; padding: 15px; border-radius: 8px; margin-bottom: 10px; ' + bg + '">';
                html += '<div style="display: flex; justify-content: space-between; align-items: start;">';
                html += '<div style="flex: 1;">';
                html += '<h4 style="margin: 0 0 8px 0; color: #2c3e50;">';
                html += (isCurrentlyLoaded ? htmlEscape(activeP) : '') + htmlEscape(collection.collection_name);
                html += '</h4>';
                html += '<p style="margin: 0 0 8px 0; color: #6c757d; font-size: 0.9rem;">';
                html += htmlEscape(collection.description || nd);
                html += '</p>';
                html += '<p style="margin: 0; color: #6c757d; font-size: 0.85rem;">';
                html += (collection.saved_collections_count || collection.item_count || 0) + ' ' + htmlEscape(savedItems);
                html += '<br><small>' + htmlEscape(createdLbl) + ' ' + new Date(collection.created_at).toLocaleString() + '</small>';
                html += '</p></div>';
                html += '<div style="display: flex; gap: 8px;">';
                html += '<button onclick="loadCollectionById(' + collection.id + ', ' + JSON.stringify(collection.collection_name) + ')" ';
                html += 'style="padding: 8px 16px; background: #17a2b8; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; white-space: nowrap;">';
                html += htmlEscape(loadBtn) + '</button>';
                html += '<button onclick="deleteCollection(' + collection.id + ')" ';
                html += 'style="padding: 8px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">';
                html += htmlEscape(delBtn) + '</button>';
                html += '</div></div></div>';
            });
            container.innerHTML = html;
        } else {
            var empty = S.collections_empty || 'No saved collections yet.';
            var hint = S.collections_empty_hint || 'Click Save to save your first collection!';
            container.innerHTML = '<div style="text-align: center; padding: 40px; color: #6c757d;"><p>' + htmlEscape(empty) + '</p><p style="font-size: 0.9rem;">' + htmlEscape(hint) + '</p></div>';
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        var errMsg = S.collections_list_error || 'Error loading collections';
        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #dc3545;">' + htmlEscape(errMsg) + '</div>';
    });
}

function loadCollectionById(collectionId, collectionName) {
    var S = lupoHdrStrings();
    var tmpl = S.collections_confirm_load || 'Load collection "%s"? This will replace your current recently viewed items.';
    if (!confirm(tmpl.replace('%s', collectionName))) {
        return;
    }

    fetch(lupoPublicPath() + '/lupo-api/load_collection.php', {
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
            alert(S.collections_loaded_ok || 'Collection loaded! Refreshing page...');
            location.reload();
        } else {
            var errPre = S.collections_error_prefix || 'Error: ';
            var fail = S.collections_load_failed || 'Failed to load collection';
            alert(errPre + (data.error || fail));
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert(S.collections_load_try_again || 'Error loading collection. Please try again.');
    });
}

function deleteCollection(collectionId) {
    var S = lupoHdrStrings();
    if (!confirm(S.collections_delete_confirm || 'Delete this collection? This cannot be undone.')) {
        return;
    }

    fetch(lupoPublicPath() + '/lupo-api/delete_collection.php', {
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
            var errPre = S.collections_error_prefix || 'Error: ';
            var fail = S.collections_delete_failed || 'Failed to delete collection';
            alert(errPre + (data.error || fail));
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert(S.collections_delete_try_again || 'Error deleting collection. Please try again.');
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

/**
 * Subdir install: {publicPath}/index.php?slug=&artifact_type=&memory_key=
 * Defaults match PHP lupo_try2_index_content_query_href (text/markdown, content:{slug}).
 */
function lupoTry2IndexContentHref(basePath, slug, artifactTypeOpt, memoryKeyOpt) {
    var slugStr = slug !== undefined && slug !== null ? String(slug).replace(/^\s+|\s+$/g, '') : '';
    if (slugStr === '') {
        return '#';
    }
    var base = basePath !== undefined && basePath !== null ? String(basePath).replace(/\/+$/, '') : '';
    var at = (artifactTypeOpt !== undefined && artifactTypeOpt !== null && String(artifactTypeOpt) !== '')
        ? String(artifactTypeOpt) : 'text/markdown';
    var mk = (memoryKeyOpt !== undefined && memoryKeyOpt !== null && String(memoryKeyOpt) !== '')
        ? String(memoryKeyOpt) : ('content:' + slugStr);
    return base + '/index.php?slug=' + encodeURIComponent(slugStr) + '&artifact_type=' + encodeURIComponent(at) + '&memory_key=' + encodeURIComponent(mk);
}

/** Same path as current page; set lupo_collection_id; keep other query params (PHP lupo_collection_context_switch_href). */
function lupoCollectionContextSwitchHref(collectionId) {
    try {
        var u = new URL(window.location.href);
        u.searchParams.set('lupo_collection_id', String(collectionId));
        var q = u.searchParams.toString();
        return u.pathname + (q ? '?' + q : '') + (window.location.hash || '');
    } catch (e1) {
        return window.location.pathname + '?lupo_collection_id=' + encodeURIComponent(String(collectionId));
    }
}

function lupoTry2GreenTabChildEntryCount(ch) {
    if (!ch || typeof ch !== 'object') {
        return 0;
    }
    if (ch.map_contents && ch.map_contents.length) {
        var n = 0;
        var mi;
        for (mi = 0; mi < ch.map_contents.length; mi++) {
            var m = ch.map_contents[mi];
            if (m && m.name) {
                n++;
            }
        }
        return n;
    }
    return (ch.name && String(ch.name).replace(/^\s+|\s+$/g, '') !== '') ? 1 : 0;
}

function lupoTry2GreenTabBadgeCount(subTabs) {
    if (!subTabs || typeof subTabs !== 'object') {
        return 0;
    }
    var sum = 0;
    if (subTabs._children && subTabs._children.length) {
        var i;
        for (i = 0; i < subTabs._children.length; i++) {
            sum += lupoTry2GreenTabChildEntryCount(subTabs._children[i]);
        }
        return sum;
    }
    for (var k in subTabs) {
        if (!subTabs.hasOwnProperty(k) || String(k).charAt(0) === '_') {
            continue;
        }
        if (typeof subTabs[k] === 'string') {
            sum++;
        }
    }
    return sum;
}

window.lupoCollectionContextSwitchHref = lupoCollectionContextSwitchHref;

function lupoAddCurrentPageToCollectionTab(el) {
    lupoSyncPageContextToMainLayout();
    var S = lupoHdrStrings();
    if (typeof isUserLoggedIn !== 'undefined' && !isUserLoggedIn) {
        alert(S.collections_save_login || 'Please log in to save collections.');
        return;
    }
    var tabIdRaw = el && el.getAttribute ? el.getAttribute('data-collection-tab-id') : null;
    var tabId = tabIdRaw ? parseInt(String(tabIdRaw), 10) : 0;
    if (!tabId) {
        alert(S.shortcut_pin_no_tab || 'This tab cannot be pinned. Reload the page or choose another tab.');
        return;
    }
    var ctx = typeof get_current_page_context === 'function' ? get_current_page_context() : {};
    var ml = window.LUPO_MAIN_LAYOUT || {};
    var cidEl = document.getElementById('lupo-current-content-id');
    var contentId = cidEl ? parseInt(String(cidEl.value), 10) : 0;
    if (isNaN(contentId)) {
        contentId = 0;
    }
    if (contentId <= 0 && ml.contentId !== undefined && ml.contentId !== null) {
        var mid = parseInt(String(ml.contentId), 10);
        if (!isNaN(mid) && mid > 0) {
            contentId = mid;
        }
    }
    if (contentId <= 0 && ctx.id > 0) {
        contentId = ctx.id;
    }
    var slug = (ctx.slug && String(ctx.slug).replace(/^\s+|\s+$/g, '')) || '';
    if (!slug && ml.pageSlug) {
        slug = String(ml.pageSlug).replace(/^\s+|\s+$/g, '');
    }
    if (contentId <= 0 && !slug) {
        alert(S.shortcut_pin_no_content || 'This page has no content record to pin.');
        return;
    }
    var collId = ml.collectionId !== undefined && ml.collectionId !== null ? parseInt(String(ml.collectionId), 10) : 0;
    var pinTitle = (ctx.title && String(ctx.title).replace(/^\s+|\s+$/g, '')) || (ml.pageTitle && String(ml.pageTitle).replace(/^\s+|\s+$/g, '')) || '';
    var payload = {
        collection_tab_id: tabId,
        tab_id: tabId,
        collection_id: collId,
        content_id: contentId > 0 ? contentId : 0,
        content_slug: slug,
        title: pinTitle
    };
    fetch(lupoPublicPath() + '/lupo-api/add_to_collection.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (data && data.success) {
                alert(S.shortcut_pin_ok || 'Page pinned to this tab.');
                var sdClose = document.getElementById('shortcutDropdown');
                if (sdClose) {
                    sdClose.classList.remove('show', 'active');
                }
            } else {
                var errPre = S.collections_error_prefix || 'Error: ';
                alert(errPre + (data && data.error ? data.error : 'Pin failed'));
            }
        })
        .catch(function () {
            alert(S.collections_save_try_again || 'Error saving collection. Please try again.');
        });
}

window.lupoAddCurrentPageToCollectionTab = lupoAddCurrentPageToCollectionTab;

window.loadCollectionTabs = function(collectionId, collectionName) {
    currentLoadedCollectionId = collectionId;
    currentLoadedCollectionName = collectionName;
    var hidNav = document.getElementById('active-collection-id');
    if (hidNav) {
        hidNav.value = String(collectionId);
    }
    if (window.LUPO_MAIN_LAYOUT) {
        window.LUPO_MAIN_LAYOUT.collectionId = collectionId;
    }

    fetch(lupoPublicPath() + '/lupo-api/load_collection_tabs.php?collection_id=' + collectionId)
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data && data.success) {
                var td = (data.tabs_data && Object.keys(data.tabs_data).length > 0) ? data.tabs_data : {};
                lupoSyncShortcutTabsFromServerData(td, data.collection_name);
            }
            if (data.success && data.tabs_data && Object.keys(data.tabs_data).length > 0) {
                var container = document.getElementById('collection-tabs-container');
                if (container) {
                    var html = '';
                    var base = lupoPublicPath();
                    for (var mainTab in data.tabs_data) {
                        if (!data.tabs_data.hasOwnProperty(mainTab)) {
                            continue;
                        }
                        var subTabs = data.tabs_data[mainTab];
                        var tabSlug = (subTabs && subTabs._slug) ? subTabs._slug : mainTab.toLowerCase().replace(/\s+/g, '-');
                        var dropdownId = 'dropdown-' + mainTab.toLowerCase().replace(/\s+/g, '-');
                        var tabType = mainTab.toLowerCase().replace(/\s+/g, '-');

                        html += '<div class="lupo-dropdown" data-qa-type="' + htmlEscape(tabType) + '">';
                        var actualSubTabCount = lupoTry2GreenTabBadgeCount(subTabs);

                        html += '<button type="button" class="dropdown-button btn-green" onclick="lupoDbgNavToggle(this, event)" aria-expanded="false" aria-haspopup="true" aria-controls="' + htmlEscape(dropdownId) + '" data-qa-type="' + htmlEscape(tabType) + '">';
                        html += htmlEscape(mainTab.toUpperCase()) + ' <span class="count-badge">' + actualSubTabCount + '</span>';
                        html += '</button>';
                        html += '<div class="dropdown-panel" id="' + htmlEscape(dropdownId) + '" role="menu">';

                        if (subTabs && subTabs._children && subTabs._children.length) {
                            for (var ci = 0; ci < subTabs._children.length; ci++) {
                                var ch = subTabs._children[ci];
                                var subTabName = ch && ch.name ? String(ch.name) : '';
                                if (ch && ch.map_contents && ch.map_contents.length && subTabName) {
                                    html += '<div class="menu-item has-submenu" onmouseenter="lupoDbgNavOpenSubmenu(this)">';
                                    html += '<span class="menu-item-label">' + htmlEscape(subTabName) + '</span>';
                                    html += '<div class="floating-submenu">';
                                    var mj;
                                    for (mj = 0; mj < ch.map_contents.length; mj++) {
                                        var mc = ch.map_contents[mj];
                                        if (!mc || !mc.name || !mc.slug) {
                                            continue;
                                        }
                                        var murl = lupoTry2IndexContentHref(base, String(mc.slug));
                                        html += '<a href="' + htmlEscape(murl) + '" class="menu-item" role="menuitem" tabindex="0">';
                                        html += htmlEscape(String(mc.name));
                                        html += '</a>';
                                    }
                                    html += '</div></div>';
                                    continue;
                                }
                                if (!subTabName) {
                                    continue;
                                }
                                var subSlug = (ch && ch.slug) ? String(ch.slug) : subTabName.toLowerCase().replace(/\s+/g, '-');
                                var atOpt = (ch && ch.content_artifact_type) ? ch.content_artifact_type : undefined;
                                var mkOpt = (ch && ch.content_memory_key) ? ch.content_memory_key : undefined;
                                var subTabUrl = lupoTry2IndexContentHref(base, subSlug, atOpt, mkOpt);
                                html += '<a href="' + htmlEscape(subTabUrl) + '" class="menu-item" role="menuitem" tabindex="0">';
                                html += htmlEscape(subTabName);
                                html += '</a>';
                            }
                        } else if (isArray(subTabs)) {
                            for (var k2 in subTabs) {
                                if (!subTabs.hasOwnProperty(k2) || k2 === '_slug') {
                                    continue;
                                }
                                if (String(k2).charAt(0) === '_') {
                                    continue;
                                }
                                var subTabName2 = subTabs[k2];
                                if (typeof subTabName2 !== 'string') {
                                    continue;
                                }
                                var subTabSlug2 = subTabName2.toLowerCase().replace(/\s+/g, '-');
                                var subTabUrl2 = lupoTry2IndexContentHref(base, subTabSlug2);
                                html += '<a href="' + htmlEscape(subTabUrl2) + '" class="menu-item" role="menuitem" tabindex="0">';
                                html += htmlEscape(subTabName2);
                                html += '</a>';
                            }
                        }

                        html += '</div></div>';
                    }
                    container.innerHTML = html;
                }
            }
        })
        .catch(function(error) {
            console.error('Error loading collection tabs:', error);
        });
};

function isArray(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]' || (obj && typeof obj === 'object' && obj.constructor === Object);
}

document.addEventListener('DOMContentLoaded', function () {
    lupoSyncPageContextToMainLayout();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSaveCollectionModal();
        closeLoadCollectionModal();
    }
});
