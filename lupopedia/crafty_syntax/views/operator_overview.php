<?php
$is_admin = lupo_crafty_is_admin();
$nav_sections = lupo_crafty_nav_sections($operator, $is_admin);
$base_url = lupo_crafty_base_url();
$logout_url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/logout' : '/logout';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crafty Syntax Operator Overview</title>
    <link rel="stylesheet" href="<?php echo lupo_crafty_h($base_url); ?>assets/css/admin.css">
    <script src="<?php echo lupo_crafty_h($base_url); ?>assets/js/live_mood.js" defer></script>
</head>
<body>
    <div class="cs-layout">
        <header class="cs-topbar">
            <div class="cs-topbar-left">
                <span class="cs-brand">Crafty Syntax Live Help</span>
                <span class="cs-subtitle">Operator Console</span>
            </div>
            <div class="cs-topbar-right">
                <span class="cs-user">Logged in as: <?php echo lupo_crafty_h($user['display_name'] ?: $user['username']); ?></span>
                <a class="cs-logout" href="<?php echo lupo_crafty_h($logout_url); ?>">Log Out</a>
            </div>
        </header>

        <div class="cs-body">
            <aside class="cs-sidebar">
                <?php foreach ($nav_sections as $section => $links): ?>
                    <div class="cs-nav-section">
                        <div class="cs-nav-title"><?php echo lupo_crafty_h($section); ?></div>
                        <ul>
                            <?php foreach ($links as $link): ?>
                                <?php if (!$link) { continue; } ?>
                                <li>
                                    <a href="<?php echo lupo_crafty_h($link['url']); ?>"<?php echo !empty($link['external']) ? ' target="_blank" rel="noopener"' : ''; ?>>
                                        <?php echo lupo_crafty_h($link['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </aside>

            <main class="cs-content">
                <section class="cs-card">
                    <h2>Operator Snapshot</h2>
                    <div class="cs-grid">
                        <div>
                            <div class="cs-label">Operator</div>
                            <div class="cs-value"><?php echo lupo_crafty_h($operator['department_name'] ?: 'Unassigned'); ?> / ID <?php echo lupo_crafty_h($operator['operator_id']); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Availability</div>
                            <div class="cs-value"><?php echo lupo_crafty_h($status['status']); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Active Chats</div>
                            <div class="cs-value"><?php echo lupo_crafty_h($status['active_chat_count']); ?> of <?php echo lupo_crafty_h($status['max_chat_capacity']); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Last Seen</div>
                            <div class="cs-value"><?php echo lupo_crafty_format_ymdhis($status['last_seen_ymdhis']); ?></div>
                        </div>
                    </div>

                    <?php if ($status['status'] !== 'kapu'): ?>
                    <div style="margin-top: 16px;">
                        <form method="post" action="<?php echo lupo_crafty_base_url(); ?>kapu_invoke.php" style="display: inline;">
                            <button type="submit" class="cs-button cs-button-kapu">🛑 Invoke Kapu (Self-Care Mode)</button>
                        </form>
                        <p class="cs-muted" style="margin-top: 8px;">Activate protected state: reduces capacity, escalates active chats</p>
                    </div>
                    <?php else: ?>
                    <div style="margin-top: 16px;">
                        <form method="post" action="<?php echo lupo_crafty_base_url(); ?>kapu_release.php" style="display: inline;">
                            <button type="submit" class="cs-button cs-button-release">✅ Release Kapu</button>
                        </form>
                        <p class="cs-muted" style="margin-top: 8px;">You are in protected state. Click to restore normal capacity.</p>
                    </div>
                    <?php endif; ?>
                </section>

                <section class="cs-card">
                    <h2>System Activity</h2>
                    <div class="cs-grid">
                        <div>
                            <div class="cs-label">Active Threads</div>
                            <div class="cs-value"><?php echo lupo_crafty_h($active_threads); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Departments</div>
                            <div class="cs-value"><?php echo lupo_crafty_h(count($departments)); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Pono</div>
                            <div class="cs-value cs-pono"><?php echo lupo_crafty_h($emotional['pono']); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Pilau</div>
                            <div class="cs-value cs-pilau"><?php echo lupo_crafty_h($emotional['pilau']); ?></div>
                        </div>
                        <div>
                            <div class="cs-label">Kapakai</div>
                            <div class="cs-value cs-kapakai"><?php echo lupo_crafty_h($emotional['kapakai']); ?></div>
                        </div>
                    </div>
                </section>

                <section class="cs-card">
                    <h2>Operator Expertise Snapshot</h2>
                    <?php if (empty($expertise_snapshot)): ?>
                        <p class="cs-muted">No operators available for scoring.</p>
                    <?php else: ?>
                        <table class="cs-table">
                            <thead>
                                <tr>
                                    <th>Operator</th>
                                    <th>Departments</th>
                                    <th>Channels</th>
                                    <th>Heartmind</th>
                                    <th>Availability</th>
                                    <th>Load</th>
                                    <th>Active Chats</th>
                                    <th>Mood_RGB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expertise_snapshot as $snapshot): ?>
                                    <?php
                                    $dept_label = $snapshot['department_names'] ? implode(', ', $snapshot['department_names']) : 'Unassigned';
                                    $channel_label = $snapshot['channel_names'] ? implode(', ', $snapshot['channel_names']) : 'None';
                                    $emotional_label = $snapshot['emotional_stability'] > 0 ? 'stable' : ($snapshot['emotional_stability'] < 0 ? 'overwhelmed' : 'stressed');
                                    $availability_label = $snapshot['availability_label'] ?: 'offline';
                                    $load_label = $snapshot['load_score'] > 0 ? 'light' : ($snapshot['load_score'] < 0 ? 'overloaded' : 'balanced');
                                    $performance_label = $snapshot['performance_score'] > 0 ? 'high' : ($snapshot['performance_score'] < 0 ? 'low' : 'average');
                                    $mood_rgb = $snapshot['mood_rgb'] ?: '666666';
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo lupo_crafty_h($snapshot['operator_name']); ?>
                                            <span class="cs-muted">#<?php echo lupo_crafty_h($snapshot['operator_id']); ?></span>
                                        </td>
                                        <td><?php echo lupo_crafty_h($dept_label); ?></td>
                                        <td><?php echo lupo_crafty_h($channel_label); ?></td>
                                        <td>
                                            <strong><?php echo lupo_crafty_h($snapshot['heartmind']); ?></strong>
                                            <span class="cs-muted" style="display: block; font-size: 10px;">
                                                E: <?php echo lupo_crafty_h($snapshot['expertise_score']); ?> |
                                                S: <?php echo lupo_crafty_h($snapshot['emotional_stability']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo lupo_crafty_h($availability_label); ?> (<?php echo lupo_crafty_h($snapshot['availability_score']); ?>)</td>
                                        <td><?php echo lupo_crafty_h($load_label); ?> (<?php echo lupo_crafty_h($snapshot['load_score']); ?>)</td>
                                        <td><?php echo lupo_crafty_h($snapshot['active_chat_count']); ?></td>
                                        <td style="background-color: #<?php echo lupo_crafty_h($mood_rgb); ?>; color: #fff; text-shadow: 1px 1px 1px #000;">#<?php echo lupo_crafty_h($mood_rgb); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>

                <section class="cs-card">
                    <h2>Recent Threads</h2>
                    <?php if (!$recent_threads): ?>
                        <p class="cs-muted">No dialog threads yet.</p>
                    <?php else: ?>
                        <table class="cs-table">
                            <thead>
                                <tr>
                                    <th>Thread</th>
                                    <th>Status</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_threads as $thread): ?>
                                    <tr>
                                        <td>
                                            #<?php echo lupo_crafty_h($thread['dialog_thread_id']); ?>
                                            <span class="cs-muted"><?php echo lupo_crafty_h($thread['summary_text']); ?></span>
                                        </td>
                                        <td><?php echo lupo_crafty_h($thread['status']); ?></td>
                                        <td><?php echo lupo_crafty_format_ymdhis($thread['updated_ymdhis']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>

                <section class="cs-card">
                    <h2>Recent Transcripts</h2>
                    <?php if (!$recent_transcripts): ?>
                        <p class="cs-muted">No transcripts imported yet.</p>
                    <?php else: ?>
                        <ul class="cs-list">
                            <?php foreach ($recent_transcripts as $transcript): ?>
                                <li>
                                    <strong>#<?php echo lupo_crafty_h($transcript['dialog_thread_id']); ?></strong>
                                    <?php echo lupo_crafty_h($transcript['summary_text']); ?>
                                    <span class="cs-muted"><?php echo lupo_crafty_format_ymdhis($transcript['updated_ymdhis']); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
