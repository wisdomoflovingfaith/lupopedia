<?php
/**
 * Channel governance log view.
 * Chronological list of log entries; role badges; log type labels; optional "New Log Entry" form if actor has role.
 */
$base = isset($channel_public_path) ? $channel_public_path : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
if (!function_exists('lupo_index_slug_url')) {
    $ah = dirname(dirname(dirname(__DIR__))) . '/functions/auth-helpers.php';
    if (is_file($ah)) {
        require_once $ah;
    }
}
$channel_id = isset($channel_id) ? (int) $channel_id : 0;
$channel_name = isset($channel['channel_name']) ? htmlspecialchars($channel['channel_name']) : 'Channel';
$log_entries = isset($log_entries) ? $log_entries : [];
$log_types = isset($log_types) ? $log_types : [];
$log_type_map = isset($log_type_map) ? $log_type_map : [];
$actor_names = isset($actor_names) ? $actor_names : [];
$actor_role = isset($actor_role) ? $actor_role : null;
$can_write = !empty($actor_role);
?>
<link rel="stylesheet" href="<?= $base ?>/includes/modules/channels/channel-interface.css">
<div class="channel-log-page" id="channel-log-page">
    <header class="channel-interface-header channel-rooms-bar">
        <h1 class="channel-interface-title"><?= $channel_name ?> — Channel Log</h1>
        <div class="channel-rooms-actions">
            <a href="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/' . $channel_id) : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id)))) ?>" class="channel-interface-mylink">Back to channel</a>
        </div>
    </header>

    <div class="channel-log-body">
        <?php if ($can_write): ?>
        <section class="channel-log-form-section" aria-label="New log entry">
            <h2 class="channel-log-section-title">New Log Entry</h2>
            <form action="<?= htmlspecialchars(function_exists('lupo_index_slug_url') ? lupo_index_slug_url('channels/' . $channel_id . '/log/create') : ($base . '/index.php?' . http_build_query(array('slug' => 'channels/' . $channel_id . '/log/create')))) ?>" method="post" class="channel-log-form">
                <input type="hidden" name="channel_id" value="<?= $channel_id ?>">
                <div class="channel-log-form-row">
                    <label for="channel-log-type">Type</label>
                    <select id="channel-log-type" name="log_type_id" required>
                        <option value="">— Select —</option>
                        <?php foreach ($log_types as $lt): ?>
                        <option value="<?= (int) $lt['log_type_id'] ?>"><?= htmlspecialchars($lt['type_label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="channel-log-form-row">
                    <label for="channel-log-text">Entry</label>
                    <textarea id="channel-log-text" name="log_text" rows="4" required placeholder="Log entry text…"></textarea>
                </div>
                <div class="channel-log-form-row">
                    <label for="channel-log-metadata">Metadata (optional JSON)</label>
                    <textarea id="channel-log-metadata" name="metadata_json" rows="2" placeholder="{}"></textarea>
                </div>
                <div class="channel-log-form-row">
                    <button type="submit" class="channel-btn">Submit</button>
                </div>
            </form>
        </section>
        <?php endif; ?>

        <section class="channel-log-list-section" aria-label="Log entries">
            <h2 class="channel-log-section-title">Log entries</h2>
            <?php if (empty($log_entries)): ?>
            <p class="channel-log-empty">No log entries yet.</p>
            <?php else: ?>
            <ul class="channel-log-list">
                <?php foreach ($log_entries as $entry):
                    $actor_id = (int) ($entry['actor_id'] ?? 0);
                    $author = isset($actor_names[$actor_id]) ? $actor_names[$actor_id] : ('actor_' . $actor_id);
                    $role_type = isset($entry['role_type']) ? (string) $entry['role_type'] : '';
                    $type_id = (int) ($entry['log_type_id'] ?? 0);
                    $type_label = isset($log_type_map[$type_id]['type_label']) ? $log_type_map[$type_id]['type_label'] : ('type_' . $type_id);
                    $meta = isset($entry['metadata_json']) && $entry['metadata_json'] !== '' && $entry['metadata_json'] !== null
                        ? $entry['metadata_json']
                        : null;
                ?>
                <li class="channel-log-entry" data-log-id="<?= (int) ($entry['channel_log_id'] ?? 0) ?>">
                    <span class="channel-log-entry-meta">
                        <?= htmlspecialchars($entry['created_ymdhis'] ?? '') ?>
                        —
                        <span class="channel-log-entry-author"><?= htmlspecialchars($author) ?></span>
                        <span class="channel-log-role-badge channel-log-role-<?= htmlspecialchars($role_type) ?>"><?= htmlspecialchars($role_type) ?></span>
                        <span class="channel-log-type-label"><?= htmlspecialchars($type_label) ?></span>
                    </span>
                    <div class="channel-log-entry-text"><?= nl2br(htmlspecialchars($entry['log_text'] ?? '')) ?></div>
                    <?php if ($meta !== null): ?>
                    <pre class="channel-log-entry-metadata"><?= htmlspecialchars(is_string($meta) ? $meta : json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </section>
    </div>
</div>
