<div class="channel-signon">
    <h1>Channel Sign-On</h1>

    <?php if (empty($channels)): ?>
        <p>No channels found where you have a role.</p>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="channel_select">Select Channel:</label>
                <select id="channel_select" name="channel_id" required>
                    <option value="">-- Select Channel --</option>
                    <?php foreach ($channels as $ch): ?>
                        <option value="<?= htmlspecialchars($ch['channel_id']) ?>">
                            <?= htmlspecialchars($ch['channel_name'] ?? 'Channel ' . $ch['channel_id']) ?>
                            – <?= htmlspecialchars($ch['role_type'] ?? '') ?>
                            <?php if (!empty($ch['department_id'])): ?> (Dept <?= (int)$ch['department_id'] ?>)<?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Sign On to Channel</button>
        </form>
    <?php endif; ?>
</div>
