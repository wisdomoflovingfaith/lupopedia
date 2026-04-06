<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: component
  when_updated: "20260406010727"
  file_path_from_root: "lupo-includes/themes/default/components/topbar.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/themes/default/components/topbar.php"
  last_modified_utc: "20260406010727"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "component"
  artifact_kind: "navigation"
  purpose: "Global top navigation; lupo_t UI strings; DatabaseFactory; Model A actor via lupo_session and App\\Auth\\Session::loadById; ActorService for act-as list."
  tags: ["ui", "navigation", "topbar", "locale", "pdo_db"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. topbar.php cannot be called directly.");
}

$UNTRUSTED = array(
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
);

$root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '';
if ($root !== '' && !class_exists('LupoLocale', false)) {
    $lp = $root . '/lupo-includes/classes/LupoLocale.php';
    if (is_file($lp)) {
        require_once $lp;
    }
}
if ($root !== '' && class_exists('LupoLocale', false) && method_exists('LupoLocale', 'bootstrap')) {
    LupoLocale::bootstrap($root);
}
if (!function_exists('lupo_t')) {
    $i18n = $root . '/lupo-includes/lupo-i18n.php';
    if (is_file($i18n)) {
        require_once $i18n;
    }
}

/**
 * Variables expected (optional overrides from includer):
 * - $isUserLoggedIn, $currentUserId, $userAvatar, $userName, $userEmail, $messageCount, $currentPage
 */

$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
$current_auth_user = $authService ? $authService->getCurrentUser() : (function_exists('current_user') ? current_user() : false);
$isUserLoggedIn = ($current_auth_user !== false && $current_auth_user !== null);

$db = null;
if (!class_exists('DatabaseFactory', false) && $root !== '') {
    $df = $root . '/lupo-includes/classes/DatabaseFactory.php';
    if (is_file($df)) {
        require_once $df;
    }
}
if (class_exists('DatabaseFactory', false)) {
    try {
        $db = DatabaseFactory::getConnection();
    } catch (Exception $e) {
        $db = null;
    }
}

$actor_id = 0;
$lupoSession = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
if ($lupoSession && method_exists($lupoSession, 'getActorId')) {
    $aid = $lupoSession->getActorId();
    if ($aid !== null && (int) $aid > 0) {
        $actor_id = (int) $aid;
    }
}
if ($actor_id <= 0 && $db && $isUserLoggedIn && class_exists('App\\Auth\\Session', false)) {
    if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE) {
        $sid = session_id();
        if ($sid !== '') {
            $loaded = \App\Auth\Session::loadById($db, $sid);
            if ($loaded) {
                $actor_id = (int) $loaded->actor_id;
            }
        }
    }
}

$current_actor = null;
$current_actor_name = function_exists('lupo_t') ? lupo_t('admin.layout.unknown_actor', 'Unknown') : 'Unknown';
if ($isUserLoggedIn && $actor_id > 0 && $db) {
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $actorsTable = $prefix . 'actors';
    $actor = $db->fetchRow(
        'SELECT actor_name, name FROM ' . $db->quoteIdentifier($actorsTable)
        . ' WHERE actor_id = :actor_id AND is_active = 1 AND is_deleted = 0',
        array('actor_id' => $actor_id)
    );
    if ($actor) {
        $current_actor = $actor;
        $nm = isset($actor['name']) ? $actor['name'] : '';
        $an = isset($actor['actor_name']) ? $actor['actor_name'] : '';
        $current_actor_name = ($nm !== '') ? $nm : $an;
        if ($current_actor_name === '') {
            $current_actor_name = function_exists('lupo_t') ? lupo_t('admin.layout.unknown_actor', 'Unknown') : 'Unknown';
        }
    }
}

if (!isset($currentUserId)) {
    $currentUserId = $isUserLoggedIn && is_array($current_auth_user) && isset($current_auth_user['auth_user_id'])
        ? (int) $current_auth_user['auth_user_id']
        : 0;
}
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
if (!isset($userAvatar)) {
    if ($isUserLoggedIn && $currentUserId > 0) {
        $avatar_path = LUPOPEDIA_PATH . '/lupo-uploads/avatars/' . $currentUserId . '_avatar.jpg';
        if (file_exists($avatar_path)) {
            $userAvatar = $base . '/lupo-uploads/avatars/' . $currentUserId . '_avatar.jpg';
        } else {
            $userAvatar = $base . '/lupo-images/logoface.png';
        }
    } else {
        $userAvatar = $base . '/lupo-images/logoface.png';
    }
}
if (!isset($userName)) {
    if ($isUserLoggedIn && is_array($current_auth_user)) {
        if (isset($current_auth_user['display_name']) && $current_auth_user['display_name'] !== '') {
            $userName = $current_auth_user['display_name'];
        } elseif (isset($current_auth_user['username'])) {
            $userName = $current_auth_user['username'];
        } else {
            $userName = 'User';
        }
    } else {
        $userName = '';
    }
}
if (!isset($userEmail)) {
    $userEmail = ($isUserLoggedIn && is_array($current_auth_user) && isset($current_auth_user['email']))
        ? $current_auth_user['email']
        : '';
}
if (!isset($messageCount)) {
    $messageCount = 0;
}
if (!isset($currentPage)) {
    $currentPage = '';
}

$navLinks = array(
    'home' => array('url' => $base . '/', 'label' => function_exists('lupo_t') ? lupo_t('nav.home', 'Home') : 'Home'),
    'qa' => array('url' => $base . '/qa/', 'label' => function_exists('lupo_t') ? lupo_t('nav.qa', 'Q/A') : 'Q/A'),
    'content' => array('url' => $base . '/search.php', 'label' => function_exists('lupo_t') ? lupo_t('nav.content', 'Content') : 'Content'),
    'users' => array('url' => $base . '/users.php', 'label' => function_exists('lupo_t') ? lupo_t('nav.users', 'Users') : 'Users'),
    'agents' => array('url' => $base . '/agents.php', 'label' => function_exists('lupo_t') ? lupo_t('nav.agents', 'Agents') : 'Agents'),
);

$avatarTimestamp = '';
if ($userAvatar !== '' && strpos($userAvatar, $base . '/lupo-uploads/avatars/') === 0) {
    $diskPath = str_replace($base, LUPOPEDIA_PATH, $userAvatar);
    if (is_file($diskPath)) {
        $avatarTimestamp = '?' . gmdate('YmdHis');
    }
}

$request_uri = '';
if (isset($UNTRUSTED['server']['REQUEST_URI']) && is_string($UNTRUSTED['server']['REQUEST_URI'])) {
    $request_uri = $UNTRUSTED['server']['REQUEST_URI'];
}

?>
<!-- Navigation Header -->
<header class="main-header">
    <div class="nav-logo-container" style="position: absolute; top: 20px; left: 0; z-index: 2000;">
        <a href="<?= htmlspecialchars($base . '/index.php') ?>" class="nav-logo" onclick="scrollToTop()" title="<?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.logo_title', 'Lupopedia home') : 'Lupopedia home') ?>">
            <img src="<?= htmlspecialchars($base . '/lupo-images/logoface.png') ?>" alt="<?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.logo_alt', 'Lupopedia') : 'Lupopedia') ?>" width="50" height="50" border="0" style="border-radius: 50%;" />
        </a>
    </div>
    <nav class="main-nav">
        <div class="nav-container">
            <div class="nav-links">
                <?php foreach ($navLinks as $key => $link): ?>
                    <?php
                    $isActive = ($currentPage === $key ||
                        ($key === 'home' && ($currentPage === '' || $currentPage === 'index')) ||
                        ($key === 'qa' && (strpos($currentPage, 'question') !== false || strpos($currentPage, 'qa') !== false)) ||
                        ($key === 'content' && strpos($currentPage, 'content') !== false) ||
                        ($key === 'users' && strpos($currentPage, 'user') !== false) ||
                        ($key === 'agents' && strpos($currentPage, 'agent') !== false));
                    ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
                        <?= htmlspecialchars($link['label']) ?>
                    </a>
                <?php endforeach; ?>

                <?php if (defined('LUPO_UI_PATH') && file_exists(LUPO_UI_PATH . '/components/collections_dropdown.php')): ?>
                    <?php
                    if (!isset($collection_id)) {
                        $collection_id = null;
                    }
                    $currentCollectionId = $collection_id;
                    include LUPO_UI_PATH . '/components/collections_dropdown.php';
                    ?>
                <?php endif; ?>
            </div>

            <?php if ($isUserLoggedIn): ?>
            <div class="nav-user">
                <div class="user-dropdown">
                    <button class="user-profile-btn" onclick="toggleUserDropdown()">
                        <div class="user-avatar">
                            <img src="<?= htmlspecialchars($userAvatar . $avatarTimestamp) ?>"
                                 alt="<?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.avatar_alt', 'Avatar') : 'Avatar') ?>"
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        </div>
                        <a href="<?= htmlspecialchars($base . '/messages.php') ?>" class="messages-icon" title="<?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.messages_title', 'Messages') : 'Messages') ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <?php if ($messageCount > 0): ?>
                                <span class="message-badge"><?= (int) $messageCount ?></span>
                            <?php endif; ?>
                        </a>
                        <span class="dropdown-arrow">▼</span>
                    </button>

                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <div class="dropdown-header">
                            <div class="user-info">
                                <div class="user-avatar-large">
                                    <img src="<?= htmlspecialchars($userAvatar . $avatarTimestamp) ?>"
                                         alt="<?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.avatar_alt', 'Avatar') : 'Avatar') ?>"
                                         style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                </div>
                                <div class="user-details">
                                    <div class="user-name-large"><?= htmlspecialchars($userName) ?></div>
                                    <div class="user-email"><?= htmlspecialchars($userEmail) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <a href="<?= htmlspecialchars($base . '/my-profile') ?>" class="dropdown-item">
                            <span class="dropdown-icon">👤</span>
                            <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.my_profile', 'My Profile') : 'My Profile') ?>
                            <?php if ($current_actor): ?>
                                <span style="color: #666; font-size: 0.85em; margin-left: 8px;"><?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.layout.acting_as', 'Acting as:') : 'Acting as:') ?> <strong><?= htmlspecialchars($current_actor_name) ?></strong></span>
                                <?php
                                $available_actors = array();
                                $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
                                if ($actorService && method_exists($actorService, 'getActorsUserCanActAs')) {
                                    $auth_user_id = (int) $currentUserId;
                                    $available_actors = $actorService->getActorsUserCanActAs($auth_user_id, false);
                                }
                                if (!is_array($available_actors)) {
                                    $available_actors = array();
                                }
                                $user_can_switch_actors = count($available_actors) > 1;
                                if ($user_can_switch_actors):
                                ?>
                                    <a href="<?= htmlspecialchars($base . '/select-actor.php?redirect=' . rawurlencode($request_uri)) ?>" style="color: #4299e1; text-decoration: none; font-size: 0.8em; margin-left: 4px;"><?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('admin.layout.change_lower', 'change') : 'change') ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </a>
                        <a href="<?= htmlspecialchars($base . '/my-history.php') ?>" class="dropdown-item">
                            <span class="dropdown-icon">📜</span>
                            <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.my_history', 'My History') : 'My History') ?>
                        </a>
                        <a href="<?= htmlspecialchars($base . '/my-channel.php') ?>" class="dropdown-item">
                            <span class="dropdown-icon">📡</span>
                            <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.my_channel', 'My Channel') : 'My Channel') ?>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="<?= htmlspecialchars($base . '/settings.php') ?>" class="dropdown-item">
                            <span class="dropdown-icon">⚙️</span>
                            <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.settings', 'Settings') : 'Settings') ?>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="<?= htmlspecialchars($base . '/admin.php') ?>" class="dropdown-item" style="color: #16a085; font-weight: 600;">
                            <span class="dropdown-icon">🔧</span>
                            <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.admin', 'Database Admin') : 'Database Admin') ?>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a href="<?= htmlspecialchars($base . '/logout.php') ?>" class="dropdown-item logout-item">
                            <span class="dropdown-icon">🚪</span>
                            <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.sign_out', 'Sign Out') : 'Sign Out') ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="nav-user">
                <?php
                $redir = $request_uri !== '' ? $request_uri : '/';
                $login_url = function_exists('lupo_login_url') ? lupo_login_url($redir) : ($base . '/login.php?redirect=' . rawurlencode($redir));
                ?>
                <a href="<?= htmlspecialchars($login_url) ?>" class="nav-link">
                    <?= htmlspecialchars(function_exists('lupo_t') ? lupo_t('nav.sign_in', 'Sign In') : 'Sign In') ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdownMenu');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdownMenu');
    const profileBtn = document.querySelector('.user-profile-btn');

    if (dropdown && profileBtn && !profileBtn.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdown = document.getElementById('userDropdownMenu');
        if (dropdown) {
            dropdown.classList.remove('show');
        }
    }
});

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
