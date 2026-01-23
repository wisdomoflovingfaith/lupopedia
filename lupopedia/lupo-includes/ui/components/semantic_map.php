<?php
/**
 * wolfie.header.identity: semantic-map
 * wolfie.header.placement: /lupo-includes/ui/components/semantic_map.php
 * wolfie.header.version: 4.0.6
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "TRUTH Module Integration Phase 1: Added placeholder TRUTH layer to semantic map. Provides visual entry point for TRUTH routes from semantic map panel. TRUTH logic will be integrated in later phases."
 *   mood: "00FF00"
 * tags:
 *   categories: ["ui", "components", "semantic", "visualization"]
 *   collections: ["core-ui"]
 *   channels: ["dev", "public"]
 * file:
 *   title: "Semantic Map Component"
 *   description: "Left panel component visualizing semantic relationships and atom hierarchy"
 *   version: 4.0.6
 *   status: published
 *   author: "Captain Wolfie"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. semantic_map.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Semantic Map Component (Left Panel)
 * ---------------------------------------------------------
 * 
 * This component renders a left-side panel visualizing semantic relationships
 * using atoms, edges, and ConnectionsService data.
 * 
 * Variables expected:
 * - $content (array) - Current content row
 * - $semanticContext (array) - Semantic context from ConnectionsService
 *   - atoms: Direct atom connections
 *   - parents: Parent atoms (hierarchical)
 *   - children: Child atoms (hierarchical)
 *   - siblings: Sibling atoms
 *   - related_content: Related content via shared atoms
 *   - edge_type_summary: Summary of edge types
 */

// Initialize variables with defaults
if (!isset($content)) {
    $content = [];
}
if (!isset($semanticContext)) {
    $semanticContext = [
        'atoms' => [],
        'parents' => [],
        'children' => [],
        'siblings' => [],
        'related_content' => [],
        'edge_type_summary' => []
    ];
}

$contentId = isset($content['id']) ? (int)$content['id'] : 0;
$hasSemanticData = !empty($semanticContext['atoms']) || 
                  !empty($semanticContext['parents']) || 
                  !empty($semanticContext['children']) || 
                  !empty($semanticContext['siblings']);

?>
<!-- Semantic Map Panel (Left Side) -->
<aside id="semantic-map-panel" class="semantic-map-panel">
    <div class="semantic-map-header">
        <h3>Semantic Map</h3>
        <button class="semantic-map-toggle" onclick="toggleSemanticMap()" aria-label="Toggle semantic map">
            <span class="toggle-icon">◀</span>
        </button>
    </div>
    
    <div class="semantic-map-content" id="semanticMapContent">
        <?php if ($hasSemanticData): ?>
            <!-- Atom Hierarchy -->
            <?php if (!empty($semanticContext['parents'])): ?>
            <div class="semantic-map-section">
                <h4 class="semantic-map-section-title">Parent Atoms</h4>
                <ul class="semantic-map-list">
                    <?php foreach ($semanticContext['parents'] as $atom): ?>
                        <li class="semantic-map-item semantic-map-parent">
                            <span class="semantic-map-label"><?= htmlspecialchars($atom['label'] ?? 'Atom ' . $atom['atom_id']) ?></span>
                            <?php if (isset($atom['weight'])): ?>
                                <span class="semantic-map-weight"><?= htmlspecialchars($atom['weight']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Direct Atoms -->
            <?php if (!empty($semanticContext['atoms'])): ?>
            <div class="semantic-map-section">
                <h4 class="semantic-map-section-title">Connected Atoms</h4>
                <ul class="semantic-map-list">
                    <?php foreach ($semanticContext['atoms'] as $atom): ?>
                        <li class="semantic-map-item semantic-map-direct">
                            <span class="semantic-map-label"><?= htmlspecialchars($atom['label'] ?? 'Atom ' . $atom['atom_id']) ?></span>
                            <?php if (isset($atom['weight'])): ?>
                                <span class="semantic-map-weight"><?= htmlspecialchars($atom['weight']) ?></span>
                            <?php endif; ?>
                            <?php if (isset($atom['edge_type'])): ?>
                                <span class="semantic-map-edge-type"><?= htmlspecialchars($atom['edge_type']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Child Atoms -->
            <?php if (!empty($semanticContext['children'])): ?>
            <div class="semantic-map-section">
                <h4 class="semantic-map-section-title">Child Atoms</h4>
                <ul class="semantic-map-list semantic-map-children">
                    <?php foreach ($semanticContext['children'] as $atom): ?>
                        <li class="semantic-map-item semantic-map-child">
                            <span class="semantic-map-label"><?= htmlspecialchars($atom['label'] ?? 'Atom ' . $atom['atom_id']) ?></span>
                            <?php if (isset($atom['weight'])): ?>
                                <span class="semantic-map-weight"><?= htmlspecialchars($atom['weight']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Sibling Atoms -->
            <?php if (!empty($semanticContext['siblings'])): ?>
            <div class="semantic-map-section">
                <h4 class="semantic-map-section-title">Sibling Atoms</h4>
                <ul class="semantic-map-list">
                    <?php foreach ($semanticContext['siblings'] as $atom): ?>
                        <li class="semantic-map-item semantic-map-sibling">
                            <span class="semantic-map-label"><?= htmlspecialchars($atom['label'] ?? 'Atom ' . $atom['atom_id']) ?></span>
                            <?php if (isset($atom['weight'])): ?>
                                <span class="semantic-map-weight"><?= htmlspecialchars($atom['weight']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Edge Type Summary -->
            <?php if (!empty($semanticContext['edge_type_summary'])): ?>
            <div class="semantic-map-section">
                <h4 class="semantic-map-section-title">Edge Types</h4>
                <ul class="semantic-map-list">
                    <?php foreach ($semanticContext['edge_type_summary'] as $edge): ?>
                        <li class="semantic-map-item semantic-map-edge">
                            <span class="semantic-map-label"><?= htmlspecialchars($edge['edge_type'] ?? 'unknown') ?></span>
                            <span class="semantic-map-count"><?= (int)($edge['total'] ?? 0) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- TRUTH Layer (Phase 1 Placeholder) -->
            <div class="semantic-map-section">
                <h4 class="semantic-map-section-title">TRUTH</h4>
                <div class="semantic-map-item semantic-map-truth">
                    <span class="semantic-map-label">TRUTH Module</span>
                    <span class="semantic-map-hint">Phase 1 - Structural Layer</span>
                </div>
                <?php if ($contentId > 0): ?>
                    <?php $contentSlug = isset($content['slug']) ? htmlspecialchars($content['slug']) : ''; ?>
                    <?php if ($contentSlug): ?>
                        <ul class="semantic-map-list">
                            <li class="semantic-map-item semantic-map-truth-link">
                                <a href="/truth/<?= $contentSlug ?>" class="semantic-map-link">View TRUTH</a>
                            </li>
                            <li class="semantic-map-item semantic-map-truth-link">
                                <a href="/truth/assert/<?= $contentSlug ?>" class="semantic-map-link">Assert</a>
                            </li>
                            <li class="semantic-map-item semantic-map-truth-link">
                                <a href="/truth/evidence/<?= $contentSlug ?>" class="semantic-map-link">Evidence</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="semantic-map-empty">
                <p>No semantic relationships found.</p>
                <p class="semantic-map-hint">Semantic map shows atom connections and content relationships.</p>
            </div>
        <?php endif; ?>
    </div>
</aside>

<!-- Semantic Map CSS -->
<style>
.semantic-map-panel {
    position: fixed;
    left: 0;
    top: 107px; /* Below topbar + collection nav */
    width: 280px;
    max-height: calc(100vh - 107px - 60px); /* Account for footer */
    background: #f8f9fa;
    border-right: 2px solid #dee2e6;
    box-shadow: 2px 0 4px rgba(0,0,0,0.1);
    z-index: 999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.semantic-map-panel.collapsed {
    transform: translateX(-100%);
}

.semantic-map-header {
    padding: 12px 16px;
    background: #e9ecef;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.semantic-map-header h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #495057;
}

.semantic-map-toggle {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    color: #6c757d;
    font-size: 12px;
    transition: transform 0.2s ease;
}

.semantic-map-toggle:hover {
    color: #495057;
}

.semantic-map-toggle.collapsed .toggle-icon {
    transform: rotate(180deg);
}

.semantic-map-content {
    flex: 1;
    overflow-y: auto;
    padding: 12px 0;
}

.semantic-map-section {
    margin-bottom: 20px;
    padding: 0 16px;
}

.semantic-map-section:last-child {
    margin-bottom: 0;
}

.semantic-map-section-title {
    margin: 0 0 8px 0;
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.semantic-map-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.semantic-map-item {
    padding: 6px 10px;
    margin-bottom: 4px;
    background: white;
    border-left: 3px solid #dee2e6;
    border-radius: 4px;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: border-color 0.2s ease, background-color 0.2s ease;
}

.semantic-map-item:hover {
    background: #f8f9fa;
}

.semantic-map-parent {
    border-left-color: #28a745;
}

.semantic-map-direct {
    border-left-color: #007bff;
}

.semantic-map-child {
    border-left-color: #17a2b8;
    padding-left: 20px; /* Indent children */
}

.semantic-map-sibling {
    border-left-color: #ffc107;
}

.semantic-map-edge {
    border-left-color: #6c757d;
}

.semantic-map-truth {
    border-left-color: #9370DB;
}

.semantic-map-truth-link {
    padding-left: 20px;
    border-left-color: #9370DB;
}

.semantic-map-link {
    color: #007bff;
    text-decoration: none;
    font-size: 13px;
}

.semantic-map-link:hover {
    text-decoration: underline;
}

.semantic-map-label {
    flex: 1;
    color: #495057;
}

.semantic-map-weight {
    font-size: 11px;
    color: #6c757d;
    margin-left: 8px;
    font-weight: 500;
}

.semantic-map-edge-type {
    font-size: 11px;
    color: #6c757d;
    margin-left: 8px;
    font-style: italic;
}

.semantic-map-count {
    font-size: 11px;
    color: #6c757d;
    margin-left: 8px;
    font-weight: 600;
}

.semantic-map-empty {
    padding: 40px 20px;
    text-align: center;
    color: #6c757d;
}

.semantic-map-empty p {
    margin: 0 0 8px 0;
    font-size: 13px;
}

.semantic-map-hint {
    font-size: 11px;
    font-style: italic;
    color: #adb5bd;
}

/* Responsive: Hide on small screens */
@media (max-width: 1024px) {
    .semantic-map-panel {
        transform: translateX(-100%);
    }
    
    .semantic-map-panel.mobile-visible {
        transform: translateX(0);
    }
}
</style>

<!-- Semantic Map JavaScript -->
<script>
/**
 * Toggle semantic map panel visibility
 */
function toggleSemanticMap() {
    const panel = document.getElementById('semantic-map-panel');
    const toggle = document.querySelector('.semantic-map-toggle');
    
    if (panel) {
        panel.classList.toggle('collapsed');
        if (toggle) {
            toggle.classList.toggle('collapsed');
        }
    }
}
</script>
