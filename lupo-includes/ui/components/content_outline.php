<?php
/**
 * wolfie.header.identity: content-outline
 * wolfie.header.placement: /lupo-includes/ui/components/content_outline.php
 * wolfie.header.version: 4.0.6
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created content_outline.php component: right panel displaying content sections/anchors extracted from HTML headers. Provides persistent navigation for long-form content. Parses content_sections from database and renders hierarchical outline with smooth scrolling."
 *   mood: "00FF00"
 * tags:
 *   categories: ["ui", "components", "navigation", "outline"]
 *   collections: ["core-ui"]
 *   channels: ["dev", "public"]
 * file:
 *   title: "Content Outline Component"
 *   description: "Right panel component displaying content section navigation"
 *   version: 4.0.6
 *   status: published
 *   author: "Captain Wolfie"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. content_outline.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Content Outline Component (Right Panel)
 * ---------------------------------------------------------
 * 
 * This component renders a persistent right-side panel showing the content outline
 * (table of contents) extracted from HTML headers with IDs.
 * 
 * Variables expected:
 * - $content (array) - Current content row with content_sections field
 * - $contentSections (array) - Optional: pre-parsed sections array
 */

// Get content sections from the current content
$content_sections = [];

if (isset($contentSections) && is_array($contentSections)) {
    // Use pre-parsed sections if provided
    $content_sections = $contentSections;
} elseif (isset($content) && !empty($content['content_sections'])) {
    // Parse from content_sections database field
    $sections_data = $content['content_sections'];
    
    if (is_string($sections_data)) {
        $decoded = json_decode($sections_data, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $content_sections = $decoded;
        }
    } elseif (is_array($sections_data)) {
        $content_sections = $sections_data;
    }
}

// If still empty, try to extract from page_body if available
if (empty($content_sections) && isset($page_body)) {
    // Extract section IDs from rendered HTML
    if (preg_match_all('/<h([1-6])[^>]*id="([^"]+)"[^>]*>/', $page_body, $matches)) {
        $content_sections = $matches[2];
    }
}

$has_sections = !empty($content_sections);

?>
<!-- Content Outline Panel (Right Side) -->
<aside id="content-outline-panel" class="content-outline-panel">
    <div class="content-outline-header">
        <h3>Contents</h3>
        <button class="content-outline-toggle" onclick="toggleContentOutline()" aria-label="Toggle content outline">
            <span class="toggle-icon">▼</span>
        </button>
    </div>
    
    <nav class="content-outline-nav" id="contentOutlineNav">
        <?php if ($has_sections): ?>
            <ul class="content-outline-list">
                <?php foreach ($content_sections as $section_id): ?>
                    <?php
                    // Convert section ID to readable label
                    $label = ucwords(str_replace(['-', '_'], ' ', $section_id));
                    $url = '#' . htmlspecialchars($section_id);
                    ?>
                    <li class="content-outline-item">
                        <a href="<?= $url ?>" 
                           class="content-outline-link"
                           data-section-id="<?= htmlspecialchars($section_id) ?>"
                           onclick="scrollToSection('<?= htmlspecialchars($section_id) ?>'); return false;">
                            <?= htmlspecialchars($label) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="content-outline-empty">
                <p>No sections available.</p>
                <p class="content-outline-hint">Sections are extracted from HTML headers with IDs.</p>
            </div>
        <?php endif; ?>
    </nav>
</aside>

<!-- Content Outline CSS -->
<style>
.content-outline-panel {
    position: fixed;
    right: 0;
    top: 107px; /* Below topbar + collection nav */
    width: 280px;
    max-height: calc(100vh - 107px - 60px); /* Account for footer */
    background: #f8f9fa;
    border-left: 2px solid #dee2e6;
    box-shadow: -2px 0 4px rgba(0,0,0,0.1);
    z-index: 999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.content-outline-panel.collapsed {
    transform: translateX(100%);
}

.content-outline-header {
    padding: 12px 16px;
    background: #e9ecef;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.content-outline-header h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #495057;
}

.content-outline-toggle {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px 8px;
    color: #6c757d;
    font-size: 12px;
    transition: transform 0.2s ease;
}

.content-outline-toggle:hover {
    color: #495057;
}

.content-outline-toggle.collapsed .toggle-icon {
    transform: rotate(-90deg);
}

.content-outline-nav {
    flex: 1;
    overflow-y: auto;
    padding: 8px 0;
}

.content-outline-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.content-outline-item {
    margin: 0;
    padding: 0;
}

.content-outline-link {
    display: block;
    padding: 8px 16px;
    color: #495057;
    text-decoration: none;
    font-size: 13px;
    line-height: 1.4;
    transition: background-color 0.2s ease, color 0.2s ease;
    border-left: 3px solid transparent;
}

.content-outline-link:hover {
    background: #e9ecef;
    color: #007bff;
    border-left-color: #007bff;
}

.content-outline-link.active {
    background: #e7f3ff;
    color: #0056b3;
    border-left-color: #0056b3;
    font-weight: 600;
}

.content-outline-empty {
    padding: 20px 16px;
    text-align: center;
    color: #6c757d;
}

.content-outline-empty p {
    margin: 0 0 8px 0;
    font-size: 13px;
}

.content-outline-hint {
    font-size: 11px;
    font-style: italic;
    color: #adb5bd;
}

/* Responsive: Hide on small screens */
@media (max-width: 1024px) {
    .content-outline-panel {
        transform: translateX(100%);
    }
    
    .content-outline-panel.mobile-visible {
        transform: translateX(0);
    }
}
</style>

<!-- Content Outline JavaScript -->
<script>
/**
 * Toggle content outline panel visibility
 */
function toggleContentOutline() {
    const panel = document.getElementById('content-outline-panel');
    const toggle = document.querySelector('.content-outline-toggle');
    
    if (panel) {
        panel.classList.toggle('collapsed');
        if (toggle) {
            toggle.classList.toggle('collapsed');
        }
    }
}

/**
 * Scroll to section and highlight in outline
 * @param {string} sectionId - Section ID to scroll to
 */
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        // Smooth scroll to section
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Update active link in outline
        const links = document.querySelectorAll('.content-outline-link');
        links.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-section-id') === sectionId) {
                link.classList.add('active');
            }
        });
    }
}

/**
 * Update active outline link based on scroll position
 */
function updateActiveOutlineLink() {
    const sections = document.querySelectorAll('[id]');
    const outlineLinks = document.querySelectorAll('.content-outline-link');
    
    if (sections.length === 0 || outlineLinks.length === 0) {
        return;
    }
    
    // Find section currently in viewport
    const viewportTop = window.scrollY + 150; // Offset for fixed header
    
    let activeSection = null;
    sections.forEach(section => {
        const sectionId = section.id;
        const sectionTop = section.offsetTop;
        
        // Check if this section is in viewport
        if (sectionTop <= viewportTop) {
            // Check if there's a link for this section
            outlineLinks.forEach(link => {
                if (link.getAttribute('data-section-id') === sectionId) {
                    activeSection = sectionId;
                }
            });
        }
    });
    
    // Update active state
    outlineLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('data-section-id') === activeSection) {
            link.classList.add('active');
        }
    });
}

// Update active link on scroll (throttled)
let scrollTimeout;
window.addEventListener('scroll', function() {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(updateActiveOutlineLink, 100);
});

// Update on page load
document.addEventListener('DOMContentLoaded', function() {
    updateActiveOutlineLink();
});
</script>
