<?php
require_once(__DIR__ . '/admin/admin_bootstrap.php');

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 1;
$actor_id = channel_admin_require_actor();
channel_admin_require_access($actor_id, $channel_id);
channel_admin_security_headers();

$channel = channel_admin_get_channel_info($channel_id);
if (!$channel) {
    header('HTTP/1.0 404 Not Found');
    echo '<h1>Channel not found</h1>';
    exit;
}

$public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$csrf_token = channel_admin_get_csrf_token();
$base_admin = $public_path . '/channels/1/admin/';
$nav_items = array(
    array('label' => 'Dashboard', 'path' => $base_admin . 'dashboard.php?channel_id=' . $channel_id, 'meta' => 'Overview + stats'),
    array('label' => 'Operators', 'path' => $base_admin . 'operators.php?channel_id=' . $channel_id, 'meta' => 'Accounts + roles'),
    array('label' => 'Departments', 'path' => $base_admin . 'departments.php?channel_id=' . $channel_id, 'meta' => 'Routing + teams'),
    array('label' => 'Chat Monitor', 'path' => $base_admin . 'chat_monitor.php?channel_id=' . $channel_id, 'meta' => 'Live threads'),
    array('label' => 'Settings', 'path' => $base_admin . 'settings.php?channel_id=' . $channel_id, 'meta' => 'System config')
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Channels Admin — <?php echo htmlspecialchars($channel['channel_name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($public_path . '/channels/1/assets/css/channels_admin.css', ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body>
<div class="channel-admin-shell">
    <header class="channel-admin-header">
        <div class="channel-admin-brand">
            <div class="channel-admin-title">Channels Admin</div>
            <div class="channel-admin-subtitle">
                <?php echo htmlspecialchars($channel['channel_name'], ENT_QUOTES, 'UTF-8'); ?> — channel <?php echo (int) $channel_id; ?>
            </div>
        </div>
        <div class="channel-admin-meta">
            <span class="channel-admin-pill">Livehelp Modernization</span>
            <span>Actor <?php echo (int) $actor_id; ?></span>
        </div>
    </header>

    <main class="channel-admin-layout">
        <nav class="channel-admin-nav">
            <h2>Admin Panels</h2>
            <?php foreach ($nav_items as $index => $item) { ?>
                <a href="<?php echo htmlspecialchars($item['path'], ENT_QUOTES, 'UTF-8'); ?>" data-admin-target="channels-admin-frame" class="channel-admin-link<?php echo $index === 0 ? ' active' : ''; ?>">
                    <div><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></div>
                    <span><?php echo htmlspecialchars($item['meta'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            <?php } ?>
            <div class="channel-admin-rail">
                Use the panels to manage livehelp operations while maintaining Lupopedia doctrine alignment.
            </div>
        </nav>

        <section class="channel-admin-panel">
            <iframe id="channels-admin-frame" name="channels-admin-frame" src="<?php echo htmlspecialchars($nav_items[0]['path'], ENT_QUOTES, 'UTF-8'); ?>"></iframe>
        </section>
    </main>
</div>

<script src="<?php echo htmlspecialchars($public_path . '/channels/1/assets/js/channels_comm.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="<?php echo htmlspecialchars($public_path . '/channels/1/assets/js/admin_interface.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.CHANNELS_ADMIN_API_BASE = "<?php echo htmlspecialchars($public_path . '/api/channels/admin/', ENT_QUOTES, 'UTF-8'); ?>";
        window.CHANNELS_ADMIN_CSRF = "<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>";
        if (window.ChannelsCommunication) {
            window.CHANNELS_ADMIN_COMM = new ChannelsCommunication(window.CHANNELS_ADMIN_API_BASE, window.CHANNELS_ADMIN_CSRF);
        }
        new AdminInterface();
    });
</script>
</body>
</html>


