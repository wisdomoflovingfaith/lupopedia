<?php
/**
 * Admin section info panel. Expects: $admin_section_title, $admin_section_description, optional $admin_section_links (array of label => url).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$admin_section_title = isset($admin_section_title) ? $admin_section_title : 'Section';
$admin_section_description = isset($admin_section_description) ? $admin_section_description : 'Content for this section will be implemented here.';
$admin_section_links = isset($admin_section_links) && is_array($admin_section_links) ? $admin_section_links : array();
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
?>
<div class="admin-section-info">
    <p class="admin-section-description"><?= nl2br(htmlspecialchars($admin_section_description)) ?></p>
    <?php if (!empty($admin_section_links)): ?>
    <ul class="admin-section-links">
        <?php foreach ($admin_section_links as $label => $url): ?>
        <li><a href="<?= htmlspecialchars($base . '/' . ltrim($url, '/')) ?>" class="admin-link"><?= htmlspecialchars($label) ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
