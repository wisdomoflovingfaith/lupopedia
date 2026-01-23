<?php
/**
 * wolfie.header.identity: collections-dropdown
 * wolfie.header.placement: /lupo-includes/ui/components/collections_dropdown.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: collections-dropdown
 *   message: "Version 4.0.18: Created collections dropdown component for selecting and managing user collections. Lists collections via AJAX, includes Save/Load/Edit actions, triggers tab loading on selection."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "00FF00"
 */

/**
 * Collections Dropdown Component
 * 
 * Renders a dropdown menu that lists all collections the user has access to.
 * When a collection is selected, it triggers AJAX to load tabs for that collection.
 * 
 * @version 4.0.18
 * @param int|null $currentCollectionId Currently selected collection ID (if any)
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. collections_dropdown.php cannot be called directly.");
}

// Get current collection ID if set
$currentCollectionId = isset($currentCollectionId) ? (int)$currentCollectionId : null;
$currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// Get login state for JavaScript (check if passed from parent, otherwise determine from session)
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = ($currentUserId > 0);
}

?>
<div class="saved-collections-dropdown" data-qa-type="collections" style="display: inline-block; position: relative;">
    <button class="nav-link" 
            onclick="toggleSavedCollectionsDropdown(this); return false;"
            aria-expanded="false"
            aria-haspopup="true"
            aria-controls="dropdown-collections"
            data-qa-type="collections"
            id="collections-dropdown-button"
            style="background: none; border: none; padding: 0; margin: 0; font: inherit; color: inherit; cursor: pointer; text-decoration: none;">
        Collections <span class="count" id="collections-count" style="background: rgba(255,255,255,0.3); color: inherit; border-radius: 10px; padding: 2px 6px; font-size: 11px; font-weight: 600; margin-left: 4px;">0</span>
    </button>
    <div class="saved-collections-dropdown-content" 
         id="dropdown-collections"
         role="menu"
         style="top: 100%; left: 0; margin-top: 4px;">
        <div style="padding: 10px; border-bottom: 1px solid #ddd; background: #f9f9f9;">
            <b>Select Collection:</b><br>
            <small style="color: #6c757d;">Choose a collection to load its tabs</small>
        </div>
        <div id="collections-list" style="max-height: 300px; overflow-y: auto;">
            <div style="text-align: center; padding: 20px; color: #6c757d;">
                Loading collections...
            </div>
        </div>
        <div style="border-top: 1px solid #ddd; padding: 8px 0; background: #f9f9f9;">
            <a href="javascript:void(0)" 
               class="saved-collections-item" 
               role="menuitem"
               onclick="checkLoginAndSave()"
               style="display: block; padding: 8px 12px; color: #28a745; font-weight: 600;">
                💾 Save Current Collection
            </a>
            <a href="javascript:void(0)" 
               class="saved-collections-item" 
               role="menuitem"
               onclick="checkLoginAndLoad()"
               style="display: block; padding: 8px 12px; color: #17a2b8; font-weight: 600;">
                📂 Load New Collection
            </a>
            <a href="javascript:void(0)" 
               class="saved-collections-item" 
               role="menuitem"
               onclick="checkLoginAndEdit()"
               style="display: block; padding: 8px 12px; color: #ffc107; font-weight: 600;">
                ✏️ Edit Current Collection
            </a>
        </div>
    </div>
</div>

<script>
(function() {
    // Load collections list when dropdown is opened
    const collectionsButton = document.getElementById('collections-dropdown-button');
    const collectionsList = document.getElementById('collections-list');
    const collectionsCount = document.getElementById('collections-count');
    let collectionsLoaded = false;
    
    // Track currently selected collection
    let selectedCollectionId = <?php echo $currentCollectionId !== null ? $currentCollectionId : 'null'; ?>;
    
    // Load collections from API
    function loadCollections() {
        if (collectionsLoaded) return;
        
        collectionsList.innerHTML = '<div style="text-align: center; padding: 20px; color: #6c757d;">Loading collections...</div>';
        
        fetch('<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/api/list_user_collections.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.collections && data.collections.length > 0) {
                    let html = '';
                    data.collections.forEach(collection => {
                        const isSelected = (selectedCollectionId !== null && collection.collection_id == selectedCollectionId);
                        const collectionName = escapeHtml(collection.name || 'Unnamed Collection');
                        const collectionDesc = escapeHtml(collection.description || 'No description');
                        
                        html += `
                            <a href="javascript:void(0)" 
                               class="saved-collections-item ${isSelected ? 'selected' : ''}"
                               role="menuitem"
                               tabindex="0"
                               onclick="selectCollection(${collection.collection_id}, '${collectionName.replace(/'/g, "\\'")}')"
                               style="${isSelected ? 'background: #e3f2fd; font-weight: 600;' : ''}">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong>${collectionName}</strong>
                                        ${collectionDesc ? '<br><small style="color: #6c757d;">' + collectionDesc + '</small>' : ''}
                                    </div>
                                    ${isSelected ? '<span style="color: #2973e4;">✓</span>' : ''}
                                </div>
                            </a>
                        `;
                    });
                    collectionsList.innerHTML = html;
                    collectionsCount.textContent = data.collections.length;
                    collectionsLoaded = true;
                } else {
                    collectionsList.innerHTML = `
                        <div style="text-align: center; padding: 20px; color: #6c757d;">
                            <p>No collections available.</p>
                        </div>
                    `;
                    collectionsCount.textContent = '0';
                }
            })
            .catch(error => {
                console.error('Error loading collections:', error);
                collectionsList.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #dc3545;">
                        Error loading collections. Please try again.
                    </div>
                `;
            });
    }
    
    // Select a collection and load its tabs
    window.selectCollection = function(collectionId, collectionName) {
        selectedCollectionId = collectionId;
        
        // Update UI to show selected collection
        collectionsList.querySelectorAll('.saved-collections-item').forEach(item => {
            item.classList.remove('selected');
            item.style.background = '';
            item.style.fontWeight = '';
        });
        
        const selectedItem = collectionsList.querySelector(`[onclick*="selectCollection(${collectionId}"]`);
        if (selectedItem) {
            selectedItem.classList.add('selected');
            selectedItem.style.background = '#e3f2fd';
            selectedItem.style.fontWeight = '600';
        }
        
        // Load tabs for this collection via AJAX
        loadCollectionTabs(collectionId, collectionName);
        
        // Close the dropdown
        const dropdown = collectionsButton.closest('.saved-collections-dropdown');
        if (dropdown) {
            dropdown.classList.remove('active');
            collectionsButton.setAttribute('aria-expanded', 'false');
        }
    };
    
    // Load tabs for a collection
    function loadCollectionTabs(collectionId, collectionName) {
        // This will be called by the main layout's tab loading system
        // Trigger the existing AJAX tab loader
        if (typeof window.loadCollectionTabs === 'function') {
            window.loadCollectionTabs(collectionId, collectionName);
        } else {
            // Fallback: reload page with collection parameter
            const url = new URL(window.location.href);
            url.searchParams.set('collection_id', collectionId);
            window.location.href = url.toString();
        }
    }
    
    // Load collections when dropdown button is clicked
    if (collectionsButton) {
        collectionsButton.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.closest('.saved-collections-dropdown');
            const isActive = dropdown.classList.contains('active');
            
            // Toggle dropdown
            toggleSavedCollectionsDropdown(this);
            
            if (!collectionsLoaded) {
                loadCollections();
            }
        });
    }
    
    // Escape HTML helper
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Load collections on page load if dropdown should be open
    if (selectedCollectionId !== null) {
        loadCollections();
    }
})();

// Pass PHP login state to JavaScript (from main layout)
var isUserLoggedIn = <?php echo (isset($isUserLoggedIn) && $isUserLoggedIn) ? 'true' : 'false'; ?>;

function checkLoginAndSave() {
    if (!isUserLoggedIn) {
        alert('Please log in to save collections.');
        return false;
    }
    if (typeof showSaveCollectionModal === 'function') {
        showSaveCollectionModal();
    } else {
        alert('Save collection functionality not available.');
    }
}

function checkLoginAndLoad() {
    if (!isUserLoggedIn) {
        alert('Please log in to load collections.');
        return false;
    }
    if (typeof showLoadCollectionModal === 'function') {
        showLoadCollectionModal();
    } else {
        alert('Load collection functionality not available.');
    }
}

function checkLoginAndEdit() {
    if (!isUserLoggedIn) {
        alert('Please log in to edit collections.');
        return false;
    }
    if (typeof editCurrentCollection === 'function') {
        editCurrentCollection();
    } else {
        alert('Edit collection functionality not available.');
    }
}
</script>
