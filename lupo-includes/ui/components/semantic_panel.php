<?php
/**
 * wolfie.header.identity: semantic-panel
 * wolfie.header.placement: /lupo-includes/ui/components/semantic_panel.php
 * wolfie.header.version: 4.0.6
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "TRUTH Module Integration Phase 1: Added TRUTH button to semantic panel. Provides access to TRUTH routes (/truth/{slug}, /truth/assert/{slug}, /truth/evidence/{slug}) from semantic metadata panel."
 *   mood: "00FF00"
 * tags:
 *   categories: ["ui", "components", "semantic", "metadata"]
 *   collections: ["core-ui"]
 *   channels: ["dev", "public"]
 * file:
 *   title: "Semantic Panel Component"
 *   description: "Sliding panel component displaying semantic metadata and relationships"
 *   version: 4.0.6
 *   status: published
 *   author: "Captain Wolfie"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. semantic_panel.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Semantic Panel Component
 * ---------------------------------------------------------
 * 
 * This component renders a sliding panel that displays semantic metadata
 * triggered by footer navigation buttons (references, context, tags, links, collection).
 * 
 * Variables expected:
 * - $content (array) - Current content row
 * - $contentReferences (array) - Array of reference content data
 * - $contentLinks (array) - Array of linked content data
 * - $contentTags (array) - Array of tag strings
 * - $semanticContext (array) - Semantic context data (atoms, parents, children, siblings, related_content)
 * - $contentCollection (array) - Collection data for current content
 */

// Initialize variables with defaults
if (!isset($content)) {
    $content = [];
}
if (!isset($contentReferences)) {
    $contentReferences = [];
}
if (!isset($contentLinks)) {
    $contentLinks = [];
}
if (!isset($contentTags)) {
    $contentTags = [];
}
if (!isset($semanticContext)) {
    $semanticContext = [
        'atoms' => [],
        'parents' => [],
        'children' => [],
        'siblings' => [],
        'related_content' => []
    ];
}
if (!isset($contentCollection)) {
    $contentCollection = null;
}

$contentId = isset($content['id']) ? (int)$content['id'] : 0;

?>
<!-- Semantic Panel (Sliding from Bottom) -->
<div id="semantic-panel" class="semantic-panel">
    <div class="semantic-panel-header">
        <h3 id="semantic-panel-title">Semantic Information</h3>
        <button class="semantic-panel-close" onclick="closeSemanticPanel()" aria-label="Close semantic panel">
            <span>×</span>
        </button>
    </div>
    
    <div class="semantic-panel-content" id="semantic-panel-content">
        <!-- Content will be dynamically loaded based on panel type -->
        <div class="semantic-panel-loading">
            <p>Loading semantic information...</p>
        </div>
    </div>
</div>

<!-- Semantic Panel CSS -->
<style>
.semantic-panel {
    position: fixed;
    bottom: 60px; /* Above footer nav bar */
    left: 0;
    right: 0;
    max-height: 60vh;
    background: white;
    border-top: 2px solid #007bff;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.15);
    z-index: 1001;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
}

.semantic-panel.open {
    transform: translateY(0);
}

.semantic-panel-header {
    padding: 12px 20px;
    background: #007bff;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #0056b3;
}

.semantic-panel-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.semantic-panel-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.semantic-panel-close:hover {
    background: rgba(255,255,255,0.2);
}

.semantic-panel-content {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.semantic-panel-loading {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.semantic-panel-section {
    margin-bottom: 24px;
}

.semantic-panel-section:last-child {
    margin-bottom: 0;
}

.semantic-panel-section h4 {
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.semantic-panel-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.semantic-panel-item {
    padding: 8px 12px;
    margin-bottom: 4px;
    background: #f8f9fa;
    border-left: 3px solid #007bff;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.semantic-panel-item:hover {
    background: #e9ecef;
}

.semantic-panel-link {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
}

.semantic-panel-link:hover {
    text-decoration: underline;
}

.semantic-panel-tag {
    display: inline-block;
    padding: 4px 8px;
    margin: 4px 4px 4px 0;
    background: #e7f3ff;
    color: #0056b3;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.semantic-panel-empty {
    padding: 20px;
    text-align: center;
    color: #6c757d;
    font-style: italic;
}

/* Atom hierarchy display */
.semantic-atom-item {
    padding: 8px 12px;
    margin-bottom: 4px;
    background: #f8f9fa;
    border-radius: 4px;
}

.semantic-atom-label {
    font-weight: 600;
    color: #495057;
}

.semantic-atom-weight {
    font-size: 12px;
    color: #6c757d;
    margin-left: 8px;
}
</style>

<!-- Semantic Panel JavaScript -->
<script>
/**
 * Global semantic panel state
 */
let semanticPanelState = {
    isOpen: false,
    currentType: null
};

/**
 * Toggle semantic panel (called from footer navigation buttons)
 * @param {string} panelType - Type of panel to show (references, context, tags, links, collection)
 */
window.toggleSemanticPanel = function(panelType) {
    const panel = document.getElementById('semantic-panel');
    const panelContent = document.getElementById('semantic-panel-content');
    const panelTitle = document.getElementById('semantic-panel-title');
    
    if (!panel || !panelContent) {
        return;
    }
    
    // If same type clicked, close panel
    if (semanticPanelState.isOpen && semanticPanelState.currentType === panelType) {
        closeSemanticPanel();
        return;
    }
    
    // Set panel type and title
    semanticPanelState.currentType = panelType;
    semanticPanelState.isOpen = true;
    
    // Update title
    const titles = {
        'references': 'References',
        'context': 'Semantic Context',
        'tags': 'Tags',
        'links': 'Linked Content',
        'collection': 'Collection',
        'truth': 'TRUTH'
    };
    if (panelTitle) {
        panelTitle.textContent = titles[panelType] || 'Semantic Information';
    }
    
    // Load panel content
    loadSemanticPanelContent(panelType, panelContent);
    
    // Show panel
    panel.classList.add('open');
}

/**
 * Close semantic panel
 */
window.closeSemanticPanel = function() {
    const panel = document.getElementById('semantic-panel');
    if (panel) {
        panel.classList.remove('open');
        semanticPanelState.isOpen = false;
        semanticPanelState.currentType = null;
    }
};

/**
 * Load content for semantic panel
 * @param {string} panelType - Type of panel
 * @param {HTMLElement} container - Container element
 */
function loadSemanticPanelContent(panelType, container) {
    if (!container) return;
    
    // Get data from PHP variables (passed via data attributes or fetch from API)
    const contentId = <?= $contentId ?>;
    
    let html = '';
    
    switch (panelType) {
        case 'references':
            html = renderReferencesPanel(<?= json_encode($contentReferences) ?>);
            break;
        case 'context':
            html = renderContextPanel(<?= json_encode($semanticContext) ?>);
            break;
        case 'tags':
            html = renderTagsPanel(<?= json_encode($contentTags) ?>);
            break;
        case 'links':
            html = renderLinksPanel(<?= json_encode($contentLinks) ?>);
            break;
        case 'collection':
            html = renderCollectionPanel(<?= json_encode($contentCollection) ?>);
            break;
        case 'truth':
            html = renderTruthPanel(<?= json_encode($content) ?>);
            break;
        default:
            html = '<div class="semantic-panel-empty">Unknown panel type</div>';
    }
    
    container.innerHTML = html;
};

/**
 * Render references panel
 */
function renderReferencesPanel(references) {
    if (!references || references.length === 0) {
        return '<div class="semantic-panel-empty">No references found.</div>';
    }
    
    let html = '<div class="semantic-panel-section"><h4>Content Referencing This Page</h4><ul class="semantic-panel-list">';
    references.forEach(ref => {
        const title = ref.title || 'Untitled';
        const slug = ref.slug || '';
        const url = slug ? '/' + slug : '#';
        html += `<li class="semantic-panel-item"><a href="${url}" class="semantic-panel-link">${escapeHtml(title)}</a></li>`;
    });
    html += '</ul></div>';
    return html;
}

/**
 * Render context panel
 */
function renderContextPanel(context) {
    if (!context) {
        return '<div class="semantic-panel-empty">No semantic context available.</div>';
    }
    
    let html = '';
    
    // Atoms
    if (context.atoms && context.atoms.length > 0) {
        html += '<div class="semantic-panel-section"><h4>Connected Atoms</h4><ul class="semantic-panel-list">';
        context.atoms.forEach(atom => {
            const label = atom.label || atom.atom_id;
            const weight = atom.weight ? ` (weight: ${atom.weight})` : '';
            html += `<li class="semantic-atom-item"><span class="semantic-atom-label">${escapeHtml(label)}</span><span class="semantic-atom-weight">${weight}</span></li>`;
        });
        html += '</ul></div>';
    }
    
        // Related Content
        if (context.related_content && context.related_content.length > 0) {
            html += '<div class="semantic-panel-section"><h4>Related Content</h4><ul class="semantic-panel-list">';
            context.related_content.forEach(item => {
                const title = item.title || 'Untitled';
                const contentId = item.content_id || '';
                const url = contentId ? '/content/' + contentId : '#';
                html += `<li class="semantic-panel-item"><a href="${url}" class="semantic-panel-link">${escapeHtml(title)}</a></li>`;
            });
            html += '</ul></div>';
        }
    
    if (!html) {
        return '<div class="semantic-panel-empty">No semantic context available.</div>';
    }
    
    return html;
}

/**
 * Render tags panel
 */
function renderTagsPanel(tags) {
    if (!tags || tags.length === 0) {
        return '<div class="semantic-panel-empty">No tags assigned.</div>';
    }
    
    let html = '<div class="semantic-panel-section"><h4>Tags</h4><div>';
    tags.forEach(tag => {
        html += `<span class="semantic-panel-tag">${escapeHtml(tag)}</span>`;
    });
    html += '</div></div>';
    return html;
}

/**
 * Render links panel
 */
function renderLinksPanel(links) {
    if (!links || links.length === 0) {
        return '<div class="semantic-panel-empty">No linked content.</div>';
    }
    
    let html = '<div class="semantic-panel-section"><h4>Linked Content</h4><ul class="semantic-panel-list">';
    links.forEach(link => {
        const title = link.title || 'Untitled';
        const slug = link.slug || '';
        const url = slug ? '/' + slug : '#';
        html += `<li class="semantic-panel-item"><a href="${url}" class="semantic-panel-link">${escapeHtml(title)}</a></li>`;
    });
    html += '</ul></div>';
    return html;
}

/**
 * Render collection panel
 */
function renderCollectionPanel(collection) {
    if (!collection) {
        return '<div class="semantic-panel-empty">No collection information available.</div>';
    }
    
    const name = collection.name || 'Unknown Collection';
    const description = collection.description || '';
    
    let html = '<div class="semantic-panel-section">';
    html += `<h4>Collection</h4>`;
    html += `<div class="semantic-panel-item"><strong>${escapeHtml(name)}</strong>`;
    if (description) {
        html += `<br><small>${escapeHtml(description)}</small>`;
    }
    html += '</div></div>';
    return html;
}

/**
 * Render TRUTH panel (Phase 1 placeholder)
 */
function renderTruthPanel(content) {
    const contentId = content && content.id ? content.id : 0;
    const slug = content && content.slug ? content.slug : '';
    
    let html = '<div class="semantic-panel-section">';
    html += '<h4>TRUTH</h4>';
    html += '<div class="semantic-panel-item">';
    html += '<p><strong>TRUTH Module (Phase 1 - Structural Layer)</strong></p>';
    html += '<p>TRUTH integration provides semantic truth tracking and assertion management.</p>';
    
    if (slug) {
        html += `<p><a href="/truth/${escapeHtml(slug)}" class="semantic-panel-link">View TRUTH</a></p>`;
        html += `<p><a href="/truth/assert/${escapeHtml(slug)}" class="semantic-panel-link">Assert</a></p>`;
        html += `<p><a href="/truth/evidence/${escapeHtml(slug)}" class="semantic-panel-link">Evidence</a></p>`;
    } else {
        html += '<p><a href="/truth" class="semantic-panel-link">Browse TRUTH</a></p>';
    }
    
    html += '<p class="semantic-panel-hint">TRUTH logic and inference engine will be implemented in later phases.</p>';
    html += '</div></div>';
    return html;
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close panel on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && semanticPanelState.isOpen) {
        closeSemanticPanel();
    }
});

// Close panel when clicking outside
document.addEventListener('click', function(event) {
    const panel = document.getElementById('semantic-panel');
    if (panel && semanticPanelState.isOpen && !panel.contains(event.target)) {
        // Don't close if clicking footer nav buttons
        if (!event.target.closest('.semantic-nav-bar')) {
            closeSemanticPanel();
        }
    }
});
</script>
