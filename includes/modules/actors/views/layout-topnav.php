<?php
/**
 * Standalone layout: top navigation only. No UI/content wrapper, no render_main_layout.
 * Used by My Profile (and can be used by channel cockpit if desired).
 * Expects: $page_title (string), $page_body (string), $head_extra (string, optional).
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. layout-topnav.php cannot be called directly.");
}
if (!isset($page_title)) {
    $page_title = 'Lupopedia';
}
if (!isset($page_body)) {
    $page_body = '';
}
if (!isset($head_extra)) {
    $head_extra = '';
}
require_once __DIR__ . '/../../../theme/theme-loader.php';
$public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars($public_path) ?>/favicon.ico">
    <?= $head_extra ?>
</head>
<body>
<?php
lupo_theme_include_component('topbar.php', []);
?>
<!-- Same full-width wrapper as channel cockpit (main_layout when REQUEST_URI contains /channels/) -->
<div style="width: 100%; height: calc(100vh - 60px); position: fixed; top: 60px; left: 0; overflow: hidden;">
<?= $page_body ?>
</div>
</body>
</html>
