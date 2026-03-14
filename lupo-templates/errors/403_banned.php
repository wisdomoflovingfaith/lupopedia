<?php
/**
 * 403 Banned template (4.0.18 T7). Shown when Ban at Gate blocks a banned actor.
 * No suggestions, no Smart 404 behavior.
 */
$requested_path = isset($requested_path) ? $requested_path : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
?>
<div class="lupopedia-403 lupopedia-403-banned">
    <h1>Access Denied</h1>
    <p>Your account is restricted from accessing this content.</p>
    <?php if ($requested_path !== '') : ?>
        <p>Requested path: <code><?php echo htmlspecialchars($requested_path); ?></code></p>
    <?php endif; ?>
</div>
