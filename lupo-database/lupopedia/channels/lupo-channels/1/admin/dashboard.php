<?php
require_once(dirname(__FILE__) . '/admin_layout.php');

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 1;
$actor_id = channel_admin_require_actor();
channel_admin_require_access($actor_id, $channel_id);
$channel = channel_admin_get_channel_info($channel_id);

function channel_admin_count($table, $has_soft_delete) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $sql = "SELECT COUNT(*) AS total FROM {$table_prefix}{$table}";
    if ($has_soft_delete) {
        $sql .= " WHERE is_deleted = 0";
    }
    $row = $db->fetchRow($sql, array());
    return $row ? (int) $row['total'] : 0;
}

$stats = array(
    array('label' => 'Active Actors', 'value' => channel_admin_count('actors', true)),
    array('label' => 'Channels', 'value' => channel_admin_count('channels', true)),
    array('label' => 'Dialog Threads', 'value' => channel_admin_count('dialog_threads', true)),
    array('label' => 'Dialog Messages', 'value' => channel_admin_count('dialog_messages', true))
);

channel_admin_page_start('Channels Admin Dashboard', 'Channel ' . $channel_id);
?>
<div class="channel-admin-grid">
    <?php foreach ($stats as $stat) { ?>
        <div class="channel-admin-card">
            <div class="channel-admin-kicker"><?php echo htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div style="font-size: 28px; font-weight: 700; margin-top: 10px;">
                <?php echo (int) $stat['value']; ?>
            </div>
            <div class="channel-admin-note" style="margin-top: 8px;">Live counts from Lupopedia core tables.</div>
        </div>
    <?php } ?>
</div>

<div class="channel-admin-card" style="margin-top: 18px;">
    <h3>Channel Snapshot</h3>
    <p class="channel-admin-note">Current channel context and status from <code>lupo_channels</code>.</p>
    <table class="channel-admin-table" style="margin-top: 12px;">
        <thead>
            <tr>
                <th>Channel</th>
                <th>Key</th>
                <th>Slug</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($channel ? $channel['channel_name'] : 'Unknown', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($channel ? $channel['channel_key'] : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($channel ? $channel['channel_slug'] : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($channel ? $channel['status_flag'] : '-', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
channel_admin_page_end();
?>
