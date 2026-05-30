<?php
require_once(dirname(__FILE__) . '/admin_layout.php');

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 1;
$actor_id = channel_admin_require_actor();
channel_admin_require_access($actor_id, $channel_id);

$public_path = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$pp = rtrim($public_path, '/');
$channel_index_url = $pp . '/index.php?' . http_build_query(array('slug' => 'channels/' . (int) $channel_id));
$login_page_url = $pp . '/login.php';
$docs_entry_url = $pp . '/index.php?' . http_build_query(array('resolved_uri' => 'docs'));

channel_admin_page_start('Settings', 'System Configuration Snapshot');
?>
<div class="channel-admin-card">
    <h3>System Links</h3>
    <p class="channel-admin-note">Administrative references for channel operations.</p>
    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px;">
        <a class="channel-admin-button" href="<?php echo htmlspecialchars($channel_index_url, ENT_QUOTES, 'UTF-8'); ?>" target="_parent">Open Channel View</a>
        <a class="channel-admin-button" href="<?php echo htmlspecialchars($login_page_url, ENT_QUOTES, 'UTF-8'); ?>" target="_parent">Session Login</a>
        <a class="channel-admin-button" href="<?php echo htmlspecialchars($docs_entry_url, ENT_QUOTES, 'UTF-8'); ?>" target="_parent">Documentation</a>
    </div>
</div>

<div class="channel-admin-card" style="margin-top: 18px;">
    <h3>Configuration Notes</h3>
    <p class="channel-admin-note">Use `config/global_atoms.yaml` and channel doctrine files to adjust core settings. Any database changes must follow TOON doctrine.</p>
</div>
<?php
channel_admin_page_end();
?>
