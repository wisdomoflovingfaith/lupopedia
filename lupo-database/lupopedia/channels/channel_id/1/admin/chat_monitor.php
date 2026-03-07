<?php
require_once(dirname(__FILE__) . '/admin_layout.php');

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 1;
$actor_id = channel_admin_require_actor();
channel_admin_require_access($actor_id, $channel_id);

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sql = "SELECT dialog_thread_id, title, last_message_ymdhis, created_by_actor_id, status FROM {$table_prefix}dialog_threads WHERE is_deleted = 0 ORDER BY last_message_ymdhis DESC LIMIT 15";
$rows = $db->fetchAll($sql, array());

channel_admin_page_start('Chat Monitor', 'Livehelp Thread Overview');
?>
<div class="channel-admin-card">
    <h3>Recent Dialog Threads</h3>
    <p class="channel-admin-note">Snapshot of latest conversations for monitoring and triage.</p>
    <table class="channel-admin-table" style="margin-top: 12px;">
        <thead>
            <tr>
                <th>Thread ID</th>
                <th>Title</th>
                <th>Last Message</th>
                <th>Created By</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows) { foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo (int) $row['dialog_thread_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['last_message_ymdhis'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['created_by_actor_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="5">No dialog threads found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
channel_admin_page_end();
?>
