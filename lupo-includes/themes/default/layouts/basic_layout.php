<?php
/**
 * wolfie.header.identity: basic-layout
 * wolfie.header.placement: /lupo-includes/themes/default/layouts/basic_layout.php
 *
 * Basic template: top graphic area, simple dropdown menu, content.
 * Same context as main_layout (page_body, content, page_title, etc.).
 * Use by defining LUPO_LAYOUT to 'basic_layout.php' before render (e.g. in config).
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. basic_layout.php cannot be called directly.");
}

$page_title = isset($content['title']) ? $content['title'] : 'Lupopedia';
$page_description = isset($content['description']) ? $content['description'] : '';
$hide_heading = !empty($content['hide_heading']);
if (!isset($isUserLoggedIn)) {
    $isUserLoggedIn = false;
}
if (!isset($currentUserId)) {
    $currentUserId = 0;
if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] && method_exists($GLOBALS['lupo_session'], 'getActorId')) {
    $aid = $GLOBALS['lupo_session']->getActorId();
    $currentUserId = $aid !== null ? (int) $aid : 0;
}
    $isUserLoggedIn = ($currentUserId > 0);
}

if (!defined('LUPO_UI_PATH')) {
    define('LUPO_UI_PATH', LUPOPEDIA_PATH . '/lupo-includes/themes/default');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - LUPOPEDIA</title>
    <link rel="icon" type="image/x-icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="shortcut icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <?php if (!empty($page_description)): ?>
        <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main.css">
    <style>
        /* Basic template: top graphic, drop menu, content */
        .basic-wrap { min-height: 100vh; display: flex; flex-direction: column; }
        .basic-header-graphic {
            width: 100%;
            min-height: 120px;
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 50%, #2b6cb0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .basic-header-graphic a { text-decoration: none; color: #fff; display: flex; align-items: center; gap: 12px; }
        .basic-header-graphic img { border-radius: 50%; }
        .basic-header-graphic .site-name { font-size: 1.75rem; font-weight: 700; letter-spacing: 0.02em; }
        .basic-nav {
            background: #2d3748;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .basic-nav .nav-inner { max-width: 1200px; margin: 0 auto; width: 100%; display: flex; align-items: center; flex-wrap: wrap; }
        .basic-nav .nav-item { position: relative; }
        .basic-nav .nav-link {
            display: block;
            padding: 12px 16px;
            color: #e2e8f0;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .basic-nav .nav-link:hover { background: #4a5568; color: #fff; }
        .basic-nav .nav-item.has-dropdown .nav-link::after { content: " ▾"; font-size: 0.75em; }
        .basic-nav .nav-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 200px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            padding: 4px 0;
        }
        .basic-nav .nav-item.has-dropdown.active .nav-dropdown { display: block; }
        .basic-nav .nav-dropdown a {
            display: block;
            padding: 8px 16px;
            color: #2d3748;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .basic-nav .nav-dropdown a:hover { background: #edf2f7; }
        .basic-content {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding: 24px 1rem;
        }
        .basic-content h1 { margin-top: 0; font-size: 1.75rem; }
        .basic-footer {
            background: #2d3748;
            color: #a0aec0;
            padding: 12px 1rem;
            text-align: center;
            font-size: 0.875rem;
        }
        .basic-footer a { color: #e2e8f0; text-decoration: none; }
    </style>
</head>
<body class="basic-wrap">
    <!-- Top graphic area -->
    <header class="basic-header-graphic" role="banner">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php" title="Lupopedia Home">
            <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/logoface.png" alt="" width="64" height="64">
            <span class="site-name">LUPOPEDIA</span>
        </a>
    </header>

    <!-- Basic drop menu -->
    <nav class="basic-nav" role="navigation" aria-label="Main">
        <div class="nav-inner">
            <div class="nav-item">
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php">Home</a>
            </div>
            <div class="nav-item has-dropdown" id="nav-qa">
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/qa/" aria-haspopup="true" aria-expanded="false">Q/A</a>
                <div class="nav-dropdown" role="menu">
                    <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/qa/" role="menuitem">Ask</a>
                    <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/qa/" role="menuitem">Browse</a>
                </div>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/search.php">Content</a>
            </div>
            <div class="nav-item has-dropdown" id="nav-more">
                <a class="nav-link" href="#" aria-haspopup="true" aria-expanded="false">More</a>
                <div class="nav-dropdown" role="menu">
                    <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/users.php" role="menuitem">Users</a>
                    <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/agents.php" role="menuitem">Agents</a>
                    <?php if ($isUserLoggedIn): ?>
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/my-profile" role="menuitem">My Profile</a>
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/logout.php" role="menuitem">Sign Out</a>
                    <?php else: ?>
                        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/login" role="menuitem">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="basic-content" id="content">
        <?php if (!$hide_heading): ?><h1><?= htmlspecialchars($page_title) ?></h1><?php endif; ?>
        <?= $page_body ?>
    </main>

    <footer class="basic-footer">
        <a href="<?= LUPOPEDIA_PUBLIC_PATH ?>/index.php">Lupopedia</a>
        &middot; Content &amp; Q/A
    </footer>

    <script>
        (function() {
            document.querySelectorAll('.basic-nav .nav-item.has-dropdown').forEach(function(item) {
                var link = item.querySelector('.nav-link');
                var menu = item.querySelector('.nav-dropdown');
                if (!link || !menu) return;
                link.addEventListener('click', function(e) {
                    if (link.getAttribute('href') === '#') e.preventDefault();
                    var open = item.classList.contains('active');
                    document.querySelectorAll('.basic-nav .nav-item.has-dropdown').forEach(function(i) {
                        i.classList.remove('active');
                        i.querySelector('.nav-link').setAttribute('aria-expanded', 'false');
                    });
                    if (!open) {
                        item.classList.add('active');
                        link.setAttribute('aria-expanded', 'true');
                    }
                });
            });
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.basic-nav .nav-item.has-dropdown')) {
                    document.querySelectorAll('.basic-nav .nav-item.has-dropdown').forEach(function(i) {
                        i.classList.remove('active');
                        i.querySelector('.nav-link').setAttribute('aria-expanded', 'false');
                    });
                }
            });
        })();
    </script>
    <script src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/js/lupopedia.js"></script>
</body>
</html>
