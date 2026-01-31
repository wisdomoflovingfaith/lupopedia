<div class="operator-signon">
    <h1>Operator Sign-On</h1>

    <?php if (empty($operators)): ?>
        <p>No operator records found for your account.</p>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="operator_select">Select Operator:</label>
                <select id="operator_select" name="operator_id" required>
                    <option value="">-- Select Operator --</option>
                    <?php foreach ($operators as $operator): ?>
                        <option value="<?= htmlspecialchars($operator['operator_id']) ?>">
                            Operator ID <?= htmlspecialchars($operator['operator_id']) ?> –
                            Department <?= htmlspecialchars($operator['department_id'] ?? 'N/A') ?> –
                            Channel ID <?= htmlspecialchars($operator['channel_id']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Sign On as Operator</button>
        </form>
    <?php endif; ?>
</div>
