<?php
/**
 * wolfie.header.identity: default-tabs
 * wolfie.header.placement: /lupo-includes/ui/components/default_tabs.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: default-tabs
 *   message: "Updated default tabs component: reads content sections from content_sections database column (JSON array of section IDs extracted from HTML headers). Displays sections as anchor links. Handles empty case when sections not yet populated."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. default_tabs.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Default Tabs Component (Content Sections)
 * ---------------------------------------------------------
 * 
 * Renders the contents dropdown menu with section anchors from the current content.
 * Sections are extracted from HTML headers with IDs and stored in content_sections column.
 */

// Get content sections from the current content (passed from render_main_layout)
$content_sections = [];

if (isset($content) && !empty($content['content_sections'])) {
    // content_sections is stored as JSON string in database
    $sections_data = $content['content_sections'];
    
    // If it's a string, decode it; if it's already an array, use it
    if (is_string($sections_data)) {
        $decoded = json_decode($sections_data, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $content_sections = $decoded;
        }
    } elseif (is_array($sections_data)) {
        $content_sections = $sections_data;
    }
}

// If no sections found, show empty state
$has_sections = !empty($content_sections);

?>
<div class="dropdown">
    <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/contents.png" 
         width="42" 
         height="42" 
         onclick="toggleMenu('contentsDropdown')" 
         style="cursor:pointer;"
         alt="Contents">
    
    <div id="contentsDropdown" class="dropdown-content">
        <?php if ($has_sections): ?>
            <?php foreach ($content_sections as $section_id): ?>
                <?php
                // Convert section ID to readable label (replace hyphens/underscores with spaces, title case)
                $label = ucwords(str_replace(['-', '_'], ' ', $section_id));
                $url = '#' . htmlspecialchars($section_id);
                ?>
                <a href="<?= $url ?>"><?= htmlspecialchars($label) ?></a>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="padding: 10px; color: #666; font-style: italic;">
                No sections available. Sections are extracted from HTML headers with IDs.
            </div>
        <?php endif; ?>
    </div>
</div>
