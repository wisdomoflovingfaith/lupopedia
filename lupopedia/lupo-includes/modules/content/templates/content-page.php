<?php
/**
 * wolfie.header.identity: content-page-template
 * wolfie.header.placement: /lupo-includes/modules/content/templates/content-page.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: content-page-template
 *   message: "Updated content page template: returns ONLY the content block (no HTML/head/CSS/JS). Main layout wraps this with global UI."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. content-page.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Content Page Template
 * ---------------------------------------------------------
 * 
 * Returns ONLY the content block (no HTML, head, CSS, JS).
 * The main layout wraps this with the global Lupopedia UI.
 */

// Extract content fields with defaults
$title = isset($content['title']) ? $content['title'] : 'Untitled';
$source_url = isset($content['source_url']) ? $content['source_url'] : '';
$source_title = isset($content['source_title']) ? $content['source_title'] : '';

?>
<div class="content-page">
    <header class="content-header">
        <h1><?= htmlspecialchars($title) ?></h1>
        <?php if (!empty($source_url)): ?>
            <div class="source">
                Source: <a href="<?= htmlspecialchars($source_url) ?>" target="_blank" rel="noopener noreferrer">
                    <?= htmlspecialchars($source_title ?: $source_url) ?>
                </a>
            </div>
        <?php endif; ?>
    </header>
    <main class="content-body">
        <?= $body_html ?>
    </main>
</div>
