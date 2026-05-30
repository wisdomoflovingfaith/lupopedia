<?php
/**
 * Admin Departments section view. Expects: $departments (array), $base.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}
$departments = isset($departments) && is_array($departments) ? $departments : array();
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
?>
<div class="admin-departments-section">
    <p class="admin-section-description">Departments scope actor context, routing, and fallback selection. They do not attach to agents directly. Department ID 0 is reserved (system).</p>
    <p class="admin-hint">Default actor is the actor-level fallback for the department. Agent behavior remains a separate layer.</p>
    <?php if (empty($departments)): ?>
    <p class="admin-empty">No departments found.</p>
    <?php else: ?>
    <table class="admin-users-table admin-list-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Default actor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $row): ?>
            <tr>
                <td><?= (int) $row['department_id'] ?></td>
                <td><?= htmlspecialchars(isset($row['name']) ? $row['name'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['department_type']) ? $row['department_type'] : '') ?></td>
                <td><?= htmlspecialchars(isset($row['description']) ? substr($row['description'], 0, 80) : '') ?><?= (isset($row['description']) && strlen($row['description']) > 80) ? '…' : '' ?></td>
                <td><?= (int) (isset($row['default_actor_id']) ? $row['default_actor_id'] : 0) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
