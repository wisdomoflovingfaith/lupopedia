<?php
/**
 * Renders one or more saved-collections dropdowns from $collectionsData.
 *
 * Expects:
 * - $collectionsData (array) from render_saved_collections()
 * - $typesOnly (array|null) if set, only these type keys are rendered (e.g. array('collections')).
 * - $excludeTypes (array) optional; type keys to skip (e.g. array('collections') when rendered separately in main_layout).
 */
if (!isset($collectionsData) || !is_array($collectionsData)) {
    return;
}
$nav_funcs = __DIR__ . '/saved-collections-nav-functions.php';
if (is_file($nav_funcs)) {
    require_once $nav_funcs;
}
$filterTypes = (isset($typesOnly) && is_array($typesOnly) && count($typesOnly) > 0) ? $typesOnly : null;
$excludeTypes = (isset($excludeTypes) && is_array($excludeTypes)) ? $excludeTypes : array();

foreach ($collectionsData as $type => $collectionTypeData) {
    if (in_array($type, $excludeTypes, true)) {
        continue;
    }
    if ($filterTypes !== null && !in_array($type, $filterTypes, true)) {
        continue;
    }
    $dropdownId = 'dropdown-' . $type;
    $count = isset($collectionTypeData['count']) ? $collectionTypeData['count'] : 0;
    $label = strtoupper(htmlspecialchars($type));
    if ($type === 'collections' && function_exists('lupo_t')) {
        $label = htmlspecialchars(lupo_t('header.collections.dropdown_label', 'Collections'), ENT_QUOTES, 'UTF-8');
    }
    ?>
    <div class="saved-collections-dropdown" data-qa-type="<?php echo htmlspecialchars($type); ?>">
        <button class="saved-collections-button"
                onclick="toggleSavedCollectionsDropdown(this, event)"
                aria-expanded="false"
                aria-haspopup="true"
                aria-controls="<?php echo htmlspecialchars($dropdownId); ?>"
                data-qa-type="<?php echo htmlspecialchars($type); ?>">
            <?php echo $label; ?> <span class="count"><?php echo (int) $count; ?></span>
        </button>
        <div class="saved-collections-dropdown-content"
             id="<?php echo htmlspecialchars($dropdownId); ?>"
             role="menu">
            <?php
            if (!empty($collectionTypeData['tabs']) && function_exists('render_tab_item')) {
                foreach ($collectionTypeData['tabs'] as $tab) {
                    render_tab_item($tab, $type);
                }
            }
            ?>
        </div>
    </div>
    <?php
}
