<?php
/**
 * wolfie.header.identity: topbar
 * wolfie.header.placement: /lupo-includes/ui/components/topbar.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Version 4.0.18: Removed static Collections link, integrated Collections dropdown component into navigation bar. Collections dropdown now appears after Agents link with Save/Load/Edit actions."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. topbar.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * Top Navigation Bar Component
 * ---------------------------------------------------------
 * 
 * This component renders the global top navigation bar for Lupopedia.
 * It includes:
 * - WOLFIE logo
 * - Main navigation links (Home, Q/A, Content, Collections, Users, Agents)
 * - User profile dropdown (when logged in)
 * - Messages icon with badge
 * 
 * Variables expected:
 * - $isUserLoggedIn (bool) - Whether user is logged in
 * - $currentUserId (int) - Current user ID (0 if not logged in)
 * - $userAvatar (string) - User avatar URL (optional)
 * - $userName (string) - User display name (optional)
 * - $userEmail (string) - User email (optional)
 * - $messageCount (int) - Unread message count (optional, defaults to 0)
 * - $currentPage (string) - Current page identifier for active link highlighting (optional)
 */

// Initialize variables with defaults if not set
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}
if (!isset($currentUserId)) {
    $currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $isUserLoggedIn = ($currentUserId > 0);
}
if (!isset($userAvatar)) {
    $userAvatar = $isUserLoggedIn && $currentUserId > 0 
        ? LUPOPEDIA_PUBLIC_PATH . '/uploads/avatars/' . $currentUserId . '_avatar.jpg'
        : LUPOPEDIA_PUBLIC_PATH . '/images/logoface.png';
}
if (!isset($userName)) {
    $userName = $isUserLoggedIn ? 'User' : '';
}
if (!isset($userEmail)) {
    $userEmail = $isUserLoggedIn ? 'user@example.com' : '';
}
if (!isset($messageCount)) {
    $messageCount = 0;
}
if (!isset($currentPage)) {
    $currentPage = '';
}

// Determine active navigation link
$navLinks = [
    'home' => ['url' => '/', 'label' => 'Home'],
    'truth' => ['url' => '/truth', 'label' => 'TRUTH'],
    'qa' => ['url' => '/questions.php', 'label' => 'Q/A'],
    'content' => ['url' => '/search.php', 'label' => 'Content'],
    'users' => ['url' => '/users.php', 'label' => 'Users'],
    'agents' => ['url' => '/agents.php', 'label' => 'Agents'],
];

// Add cache busting timestamp to avatar if it exists
$avatarTimestamp = file_exists(str_replace(LUPOPEDIA_PUBLIC_PATH, LUPOPEDIA_PATH, $userAvatar)) 
    ? '?' . time() 
    : '';

?>
<!-- Navigation Header -->
<header class="main-header">
    <div class="nav-logo-container" style="position: absolute; top: 20px; left: 0; z-index: 2000;">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php" class="nav-logo" onclick="scrollToTop()" title="WOLFIE - Home">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/images/logoface.png" alt="WOLFIE" width="50" height="50" border="0" style="border-radius: 50%;" />
        </a>
    </div>
    <nav class="main-nav">
        <div class="nav-container">
            <!-- Main Navigation Links -->
            <div class="nav-links">
                <?php foreach ($navLinks as $key => $link): ?>
                    <?php 
                    $isActive = ($currentPage === $key || 
                                ($key === 'home' && ($currentPage === '' || $currentPage === 'index')) ||
                                ($key === 'truth' && strpos($currentPage, 'truth') !== false) ||
                                ($key === 'qa' && strpos($currentPage, 'question') !== false) ||
                                ($key === 'content' && strpos($currentPage, 'content') !== false) ||
                                ($key === 'users' && strpos($currentPage, 'user') !== false) ||
                                ($key === 'agents' && strpos($currentPage, 'agent') !== false));
                    ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
                        <?= htmlspecialchars($link['label']) ?>
                    </a>
                <?php endforeach; ?>
                
                <!-- Collections Dropdown (after Agents) -->
                <?php if (file_exists(LUPO_UI_PATH . '/components/collections_dropdown.php')): ?>
                    <?php 
                    // Ensure collection_id is available
                    if (!isset($collection_id)) {
                        $collection_id = null;
                    }
                    $currentCollectionId = $collection_id;
                    include LUPO_UI_PATH . '/components/collections_dropdown.php'; 
                    ?>
                <?php endif; ?>
            </div>
            
            <!-- User Profile Section -->
            <?php if ($isUserLoggedIn): ?>
            <div class="nav-user">
                <!-- Logged in user dropdown -->
                <div class="user-dropdown">
                    <button class="user-profile-btn" onclick="toggleUserDropdown()">
                        <div class="user-avatar">
                            <img src="<?= htmlspecialchars($userAvatar . $avatarTimestamp) ?>" 
                                 alt="Avatar" 
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        </div>
                        <!-- Messages Icon with Badge -->
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/messages.php" class="messages-icon" title="Messages">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <?php if ($messageCount > 0): ?>
                                <span class="message-badge"><?= (int)$messageCount ?></span>
                            <?php endif; ?>
                        </a>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    
                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <div class="dropdown-header">
                            <div class="user-info">
                                <div class="user-avatar-large">
                                    <img src="<?= htmlspecialchars($userAvatar . $avatarTimestamp) ?>" 
                                         alt="Avatar" 
                                         style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div class="user-details">
                                    <div class="user-name-large"><?= htmlspecialchars($userName) ?></div>
                                    <div class="user-email"><?= htmlspecialchars($userEmail) ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/profile.php" class="dropdown-item">
                            <span class="dropdown-icon">👤</span>
                            My Profile
                        </a>
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/my-history.php" class="dropdown-item">
                            <span class="dropdown-icon">📜</span>
                            My History
                        </a>
                        
                        <div class="dropdown-divider"></div>

                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/settings.php" class="dropdown-item">
                            <span class="dropdown-icon">⚙️</span>
                            Settings
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/admin.php" class="dropdown-item" style="color: #16a085; font-weight: 600;">
                            <span class="dropdown-icon">🔧</span>
                            Database Admin
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/logout.php" class="dropdown-item logout-item">
                            <span class="dropdown-icon">🚪</span>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Not logged in - show login link -->
            <div class="nav-user">
                <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/login" class="nav-link">
                    Sign In
                </a>
            </div>
            <?php endif; ?>
        </div>
    </nav>
</header>

<!-- JavaScript for User Dropdown -->
<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdownMenu');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdownMenu');
    const profileBtn = document.querySelector('.user-profile-btn');
    
    if (dropdown && profileBtn && !profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Close dropdown on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdown = document.getElementById('userDropdownMenu');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }
});

// Scroll to top function (used by logo link)
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
