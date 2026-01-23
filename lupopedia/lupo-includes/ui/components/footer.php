<?php
/**
 * wolfie.header.identity: footer
 * wolfie.header.placement: /lupo-includes/ui/components/footer.php
 * wolfie.header.version: 4.0.6
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "TRUTH Module Integration Phase 1: Added TRUTH button to footer semantic navigation bar. Provides quick access to TRUTH panel from footer navigation. TRUTH icon uses fallback Unicode checkmark if image not available."
 *   mood: "00FF00"
 * tags:
 *   categories: ["ui", "components", "navigation", "footer"]
 *   collections: ["core-ui"]
 *   channels: ["dev", "public"]
 * file:
 *   title: "Footer Component with Semantic Navigation Bar"
 *   description: "Global footer with semantic navigation icons and Crafty Syntax live help integration"
 *   version: 4.0.6
 *   status: published
 *   author: "Captain Wolfie"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. footer.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Footer Component with Semantic Navigation Bar
 * ---------------------------------------------------------
 * 
 * This component renders the global footer for Lupopedia.
 * It includes:
 * - Semantic navigation bar (prevpage, references, context, hashtag, share, like, links, folder, nextpage, help)
 * - Crafty Syntax live help integration
 * 
 * Variables expected:
 * - $content (array) - Current content row (for semantic navigation context)
 * - $prevContent (array) - Previous content in sequence (optional)
 * - $nextContent (array) - Next content in sequence (optional)
 * - $contentReferences (array) - Array of reference content IDs (optional)
 * - $contentLinks (array) - Array of linked content IDs (optional)
 * - $contentTags (array) - Array of tag strings (optional)
 * - $isUserLoggedIn (bool) - Whether user is logged in (for like/share functionality)
 */

// Initialize variables with defaults if not set
if (!isset($content)) {
    $content = [];
}
if (!isset($prevContent)) {
    $prevContent = null;
}
if (!isset($nextContent)) {
    $nextContent = null;
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
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}

// Get content ID for navigation
$contentId = isset($content['content_id']) ? (int)$content['content_id'] : (isset($content['id']) ? (int)$content['id'] : 0);
$contentSlug = isset($content['slug']) ? $content['slug'] : '';
$contentUrl = $contentSlug ? '/' . $contentSlug : ($contentId > 0 ? '/content.php?id=' . $contentId : '');

// Determine if we have previous/next content (check for slug or content_id)
$hasPrev = !empty($prevContent) && (isset($prevContent['slug']) || isset($prevContent['content_id']) || isset($prevContent['id']));
$hasNext = !empty($nextContent) && (isset($nextContent['slug']) || isset($nextContent['content_id']) || isset($nextContent['id']));

?>
<!-- Footer -->
<footer class="main-footer">
    <!-- Semantic Navigation Bar -->
    <div class="semantic-nav-bar">
        <div class="semantic-nav-container">
            <!-- Previous Page -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-prev"
                title="Previous Page"
                aria-label="Navigate to previous page"
                <?= $hasPrev ? '' : 'disabled' ?>
                onclick="<?= $hasPrev ? 'window.location.href=\'/' . htmlspecialchars($prevContent['slug'] ?? 'content.php?id=' . ($prevContent['content_id'] ?? $prevContent['id'] ?? '')) . '\'' : '' ?>">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/prevpage.png" width="32" height="32" alt="Previous Page">
            </button>
            
            <!-- References -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-references"
                title="View References"
                aria-label="Show content references"
                onclick="toggleSemanticPanel('references')">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/references.png" width="32" height="32" alt="References">
                <?php if (count($contentReferences) > 0): ?>
                    <span class="semantic-nav-badge"><?= count($contentReferences) ?></span>
                <?php endif; ?>
            </button>
            
            <!-- Context -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-context"
                title="View Context"
                aria-label="Show semantic context"
                onclick="toggleSemanticPanel('context')">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/context.png" width="32" height="32" alt="Context">
            </button>
            
            <!-- Hashtags/Tags -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-hashtag"
                title="View Tags"
                aria-label="Show content tags"
                onclick="toggleSemanticPanel('tags')">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/hashtag.png" width="32" height="32" alt="Tags">
                <?php if (count($contentTags) > 0): ?>
                    <span class="semantic-nav-badge"><?= count($contentTags) ?></span>
                <?php endif; ?>
            </button>
            
            <!-- Share -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-share"
                title="Share Content"
                aria-label="Share this content"
                onclick="shareContent(<?= $contentId ?>)">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/share.png" width="32" height="32" alt="Share">
            </button>
            
            <!-- Like -->
            <?php if ($isUserLoggedIn): ?>
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-like"
                title="Like Content"
                aria-label="Like this content"
                onclick="likeContent(<?= $contentId ?>)">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/like.png" width="32" height="32" alt="Like">
            </button>
            <?php endif; ?>
            
            <!-- Links -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-links"
                title="View Links"
                aria-label="Show linked content"
                onclick="toggleSemanticPanel('links')">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/links.png" width="32" height="32" alt="Links">
                <?php if (count($contentLinks) > 0): ?>
                    <span class="semantic-nav-badge"><?= count($contentLinks) ?></span>
                <?php endif; ?>
            </button>
            
            <!-- Folder/Collection -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-folder"
                title="View Collection"
                aria-label="Show content collection"
                onclick="toggleSemanticPanel('collection')">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/folder.png" width="32" height="32" alt="Collection">
            </button>
            
            <!-- Next Page -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-next"
                title="Next Page"
                aria-label="Navigate to next page"
                <?= $hasNext ? '' : 'disabled' ?>
                onclick="<?= $hasNext ? 'window.location.href=\'/' . htmlspecialchars($nextContent['slug'] ?? 'content.php?id=' . ($nextContent['content_id'] ?? $nextContent['id'] ?? '')) . '\'' : '' ?>">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/nextpage.png" width="32" height="32" alt="Next Page">
            </button>
            
            <!-- TRUTH -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-truth"
                title="TRUTH"
                aria-label="Show TRUTH information"
                onclick="toggleSemanticPanel('truth')">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/truth.png" width="32" height="32" alt="TRUTH" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <span style="display:none; font-size:20px; color:#9370DB;">✓</span>
            </button>
            
            <!-- Help -->
            <button 
                class="semantic-nav-icon" 
                id="semantic-nav-help"
                title="Help"
                aria-label="Show help information"
                onclick="showHelp()">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/help.png" width="32" height="32" alt="Help">
            </button>
        </div>
    </div>
    
    <!-- Crafty Syntax Live Help -->
    <div id="craftysyntax_1">
        <script type="text/javascript" src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lh/livehelp_js.php?eo=0&amp;relative=Y&amp;department=1&amp;serversession=1&amp;pingtimes=10&amp;dynamic=Y&amp;creditline=N"></script>
    </div>
</footer>

<!-- Semantic Navigation JavaScript -->
<script>
/**
 * Toggle semantic panel visibility
 * This function calls the actual implementation in semantic_panel.php
 * Note: The actual implementation is in semantic_panel.php component
 * This is a wrapper that ensures the function is available when footer loads first
 */
function toggleSemanticPanel(panelType) {
    // Call the function from semantic_panel.php component (loaded via window object)
    if (typeof window.toggleSemanticPanel === 'function') {
        window.toggleSemanticPanel(panelType);
    } else {
        // Fallback: show alert if semantic_panel.php hasn't loaded yet
        // This should not happen in normal flow since semantic_panel.php loads before footer
        console.warn('Semantic panel component not loaded yet. Panel type:', panelType);
        alert('Semantic panel: ' + panelType + '\n\nSemantic panel component is loading...');
    }
}

/**
 * Share content
 * @param {number} contentId - Content ID to share
 */
function shareContent(contentId) {
    if (navigator.share && contentId > 0) {
        const url = window.location.href;
        const title = document.title;
        
        navigator.share({
            title: title,
            url: url
        }).catch(err => {
            console.log('Error sharing:', err);
            // Fallback: copy to clipboard
            copyToClipboard(url);
        });
    } else {
        // Fallback: copy URL to clipboard
        copyToClipboard(window.location.href);
    }
}

/**
 * Like content
 * @param {number} contentId - Content ID to like
 */
function likeContent(contentId) {
    if (contentId <= 0) return;
    
    // TODO: Implement like functionality via API
    console.log('Like content:', contentId);
    
    // Placeholder: show feedback
    const btn = document.getElementById('semantic-nav-like');
    if (btn) {
        btn.classList.add('liked');
        setTimeout(() => btn.classList.remove('liked'), 1000);
    }
}

/**
 * Show help information
 */
function showHelp() {
    // TODO: Implement help modal or panel
    alert('Lupopedia Semantic Navigation Help\n\n' +
          '• Previous/Next: Navigate between content items\n' +
          '• References: View content that references this page\n' +
          '• Context: View semantic context and relationships\n' +
          '• Tags: View content tags and categories\n' +
          '• Share: Share this content\n' +
          '• Like: Like this content (requires login)\n' +
          '• Links: View linked content\n' +
          '• Folder: View content collection\n' +
          '• Help: Show this help information');
}

/**
 * Copy text to clipboard
 * @param {string} text - Text to copy
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            alert('URL copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
        });
    } else {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('URL copied to clipboard!');
    }
}

// Keyboard shortcuts for semantic navigation
document.addEventListener('keydown', function(event) {
    // Only activate if not typing in an input field
    if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
        return;
    }
    
    // Alt + Left Arrow: Previous page
    if (event.altKey && event.key === 'ArrowLeft') {
        const prevBtn = document.getElementById('semantic-nav-prev');
        if (prevBtn && !prevBtn.disabled) {
            prevBtn.click();
        }
    }
    
    // Alt + Right Arrow: Next page
    if (event.altKey && event.key === 'ArrowRight') {
        const nextBtn = document.getElementById('semantic-nav-next');
        if (nextBtn && !nextBtn.disabled) {
            nextBtn.click();
        }
    }
    
    // Alt + R: References
    if (event.altKey && event.key === 'r') {
        document.getElementById('semantic-nav-references')?.click();
    }
    
    // Alt + C: Context
    if (event.altKey && event.key === 'c') {
        document.getElementById('semantic-nav-context')?.click();
    }
    
    // Alt + T: Tags
    if (event.altKey && event.key === 't') {
        document.getElementById('semantic-nav-hashtag')?.click();
    }
    
    // Alt + S: Share
    if (event.altKey && event.key === 's') {
        document.getElementById('semantic-nav-share')?.click();
    }
    
    // Alt + L: Links
    if (event.altKey && event.key === 'l') {
        document.getElementById('semantic-nav-links')?.click();
    }
    
    // Alt + H: Help
    if (event.altKey && event.key === 'h') {
        document.getElementById('semantic-nav-help')?.click();
    }
});
</script>

<!-- Semantic Navigation CSS -->
<style>
.semantic-nav-bar {
    background: #f8f9fa;
    border-top: 2px solid #dee2e6;
    padding: 10px 20px;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
}

.semantic-nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.semantic-nav-icon {
    position: relative;
    background: transparent;
    border: none;
    padding: 4px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.2s ease, transform 0.1s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.semantic-nav-icon:hover:not(:disabled) {
    background: rgba(0, 0, 0, 0.05);
    transform: scale(1.1);
}

.semantic-nav-icon:active:not(:disabled) {
    transform: scale(0.95);
}

.semantic-nav-icon:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.semantic-nav-icon.liked {
    animation: likePulse 0.5s ease;
}

@keyframes likePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

.semantic-nav-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #dc3545;
    color: white;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 5px;
    border-radius: 10px;
    min-width: 16px;
    text-align: center;
    line-height: 1.2;
}

.main-footer {
    margin-top: auto;
    padding-top: 60px; /* Space for semantic nav bar */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .semantic-nav-container {
        gap: 4px;
    }
    
    .semantic-nav-icon img {
        width: 28px;
        height: 28px;
    }
}
</style>
