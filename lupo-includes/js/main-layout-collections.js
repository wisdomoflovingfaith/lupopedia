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

window.loadCollectionTabs = function(collectionId, collectionName) {
    currentLoadedCollectionId = collectionId;
    currentLoadedCollectionName = collectionName;

    fetch(lupoPublicPath() + '/lupo-api/load_collection_tabs.php?collection_id=' + collectionId)
        .then(function(response) { return response.json(); })
        .then(function(data) {
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

                        html += '<div class="saved-collections-dropdown" data-qa-type="' + htmlEscape(tabType) + '">';
                        var actualSubTabCount = 0;
                        if (isArray(subTabs)) {
                            for (var key in subTabs) {
                                if (subTabs.hasOwnProperty(key) && key !== '_slug') {
                                    actualSubTabCount++;
                                }
                            }
                        }

                        html += '<button class="saved-collections-button" onclick="toggleSavedCollectionsDropdown(this)" aria-expanded="false" aria-haspopup="true" aria-controls="' + htmlEscape(dropdownId) + '" data-qa-type="' + htmlEscape(tabType) + '">';
                        html += htmlEscape(mainTab.toUpperCase()) + ' <span class="count">' + actualSubTabCount + '</span>';
                        html += '</button>';
                        html += '<div class="saved-collections-dropdown-content" id="' + htmlEscape(dropdownId) + '" role="menu">';

                        if (isArray(subTabs)) {
                            for (var k2 in subTabs) {
                                if (!subTabs.hasOwnProperty(k2) || k2 === '_slug') {
                                    continue;
                                }
                                var subTabName = subTabs[k2];
                                var subTabSlug = subTabName.toLowerCase().replace(/\s+/g, '-');
                                var subTabUrl = base + '/collection/' + collectionId + '/tab/' + subTabSlug;
                                html += '<a href="' + htmlEscape(subTabUrl) + '" class="saved-collections-item" role="menuitem" tabindex="0">';
                                html += htmlEscape(subTabName);
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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSaveCollectionModal();
        closeLoadCollectionModal();
    }
});
