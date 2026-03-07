<?php
require_once(dirname(__FILE__) . '/admin_layout.php');

$channel_id = isset($_GET['channel_id']) ? (int) $_GET['channel_id'] : 1;
$actor_id = channel_admin_require_actor();
channel_admin_require_access($actor_id, $channel_id);

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sql = "SELECT department_id, name, email, show_dept, created_ymdhis FROM {$table_prefix}departments WHERE is_deleted = 0 ORDER BY department_id ASC";
$rows = $db->fetchAll($sql, array());

channel_admin_page_start('Departments', 'Channel Department Management');
?>
<div class="channel-admin-card">
    <h3>Departments</h3>
    <p class="channel-admin-note">Department configuration for routing and operator assignment.</p>
    <table class="channel-admin-table" style="margin-top: 12px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Visible</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($rows) { foreach ($rows as $row) { ?>
                <tr>
                    <td><?php echo (int) $row['department_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo ((int) $row['show_dept'] === 1) ? 'Yes' : 'No'; ?></td>
                    <td><?php echo htmlspecialchars($row['created_ymdhis'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="5">No departments found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
channel_admin_page_end();
?>
