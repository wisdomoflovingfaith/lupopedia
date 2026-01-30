<?php
$base_url = lupo_crafty_base_url();
$logout_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/logout' : '/logout';
$user = lupo_crafty_current_user();
$operator = lupo_crafty_operator();
$is_admin = lupo_crafty_is_admin();
$nav_sections = lupo_crafty_nav_sections($operator, $is_admin);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo lupo_crafty_h($title); ?> - Crafty Syntax</title>
    <link rel="stylesheet" href="<?php echo lupo_crafty_h($base_url); ?>assets/css/admin.css">
</head>
<body>
    <div class="cs-layout">
        <header class="cs-topbar">
            <div class="cs-topbar-left">
                <span class="cs-brand">Crafty Syntax Live Help</span>
                <span class="cs-subtitle"><?php echo lupo_crafty_h($title); ?></span>
            </div>
            <div class="cs-topbar-right">
                <span class="cs-user">Logged in as: <?php echo lupo_crafty_h($user['display_name'] ?: $user['username']); ?></span>
                <a class="cs-logout" href="<?php echo lupo_crafty_h($logout_url); ?>">Log Out</a>
            </div>
        </header>

        <div class="cs-body">
            <aside class="cs-sidebar">
                <?php foreach ($nav_sections as $section => $links): ?>
                    <div class="cs-nav-section">
                        <div class="cs-nav-title"><?php echo lupo_crafty_h($section); ?></div>
                        <ul>
                            <?php foreach ($links as $link): ?>
                                <?php if (!$link) { continue; } ?>
                                <li>
                                    <a href="<?php echo lupo_crafty_h($link['url']); ?>"<?php echo !empty($link['external']) ? ' target="_blank" rel="noopener"' : ''; ?>>
                                        <?php echo lupo_crafty_h($link['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </aside>

            <main class="cs-content">
                <section class="cs-card">
                    <h2><?php echo lupo_crafty_h($title); ?></h2>
                    <p><?php echo lupo_crafty_h($message); ?></p>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
