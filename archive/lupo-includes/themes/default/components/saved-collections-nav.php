<?php
/**
 * Saved Collections Navigation Component
 * 
 * This component renders the saved collections navigation HTML.
 * It receives data arrays from the renderer and outputs HTML.
 * 
 * @param array $collectionsData Array structure from render_saved_collections()
 * @param bool $isUserLoggedIn Whether the user is logged in
 */

// Ensure data is set
if (!isset($collectionsData)) {
    $collectionsData = [];
}
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}

$lupo_sc_nav_funcs = __DIR__ . '/saved-collections-nav-functions.php';
if (is_file($lupo_sc_nav_funcs)) {
    require_once $lupo_sc_nav_funcs;
}

?>

<script>
// Pass PHP login state to JavaScript
var isUserLoggedIn = <?php echo $isUserLoggedIn ? 'true' : 'false'; ?>;
</script>

<nav class="saved-collections-nav">
    <!-- Spacer div -->
    <div style="width: 50px; height: 40px;"></div>

    <div class="saved-collections-container">
        <?php
        $typesOnly = null;
        $dd_group = __DIR__ . '/saved-collections-dropdown-group.php';
        if (is_file($dd_group)) {
            include $dd_group;
        }
        ?>
        
        <div style="margin-left: auto; display: flex; gap: 8px;">
            <button class="recently-viewed-button" onclick="checkLoginAndSave()" style="background: #28a745; border-color: #28a745; color: #fff;">
                <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.btn_save', 'Save') : 'Save', ENT_QUOTES, 'UTF-8'); ?>
            </button>
            <button class="recently-viewed-button" onclick="checkLoginAndLoad()" style="background: #17a2b8; border-color: #17a2b8; color: #fff;">
                <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.btn_load', 'Load') : 'Load', ENT_QUOTES, 'UTF-8'); ?>
            </button>
            <button class="recently-viewed-button" id="editCollectionBtn" onclick="checkLoginAndEdit()" style="background: #ffc107; border-color: #ffc107; color: #000;">
                <?php echo htmlspecialchars(function_exists('lupo_t') ? lupo_t('header.collections.btn_edit', 'Edit') : 'Edit', ENT_QUOTES, 'UTF-8'); ?>
            </button>
        </div>
        
        <script>
        function lupoHdrStrNav(key, fallback) {
            if (window.LUPO_HDR && window.LUPO_HDR.strings && window.LUPO_HDR.strings[key]) {
                return window.LUPO_HDR.strings[key];
            }
            return fallback;
        }
        function checkLoginAndSave() {
            if (!isUserLoggedIn) {
                alert(lupoHdrStrNav('collections_save_login', 'Please log in to save collections.'));
                return false;
            }
            showSaveCollectionModal();
        }
        
        function checkLoginAndLoad() {
            if (!isUserLoggedIn) {
                alert(lupoHdrStrNav('collections_load_login', 'Please log in to load collections.'));
                return false;
            }
            showLoadCollectionModal();
        }
        
        function checkLoginAndEdit() {
            if (!isUserLoggedIn) {
                alert(lupoHdrStrNav('collections_edit_login', 'Please log in to edit collections.'));
                return false;
            }
            editCurrentCollection();
        }
        </script>
    </div>
</nav>
