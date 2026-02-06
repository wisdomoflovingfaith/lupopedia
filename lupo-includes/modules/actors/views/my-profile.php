<?php
/**
 * My Profile — standalone interface (same layout pattern as channel cockpit).
 * Does NOT use the content system, content-page.php, slug lookup, or render_main_layout.
 * Rendered via layout-topnav.php: topbar + full-width content. CSS and wrapper same as show.php.
 */
$actor = isset($actor) ? $actor : [];
$actor_id = isset($actor_id) ? (int) $actor_id : 0;
$actor_properties = isset($actor_properties) && is_array($actor_properties) ? $actor_properties : [];
$avatar_public_path = isset($avatar_public_path) ? $avatar_public_path : '';
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
$actor_name = isset($actor['name']) ? htmlspecialchars($actor['name']) : '';
?>
<link rel="stylesheet" href="<?= $base ?>/lupo-includes/modules/channels/channel-interface.css">
<link rel="stylesheet" href="<?= $base ?>/lupo-includes/modules/actors/my-profile.css">
<div class="my-profile-page" id="my-profile-page" data-base="<?= htmlspecialchars($base) ?>">
    <header class="my-profile-header">
        <h1 class="my-profile-title">My Profile</h1>
        <div class="my-profile-header-actions">
            <a href="<?= htmlspecialchars($base) ?>/channels/my-channels" class="my-profile-back">My Channels</a>
            <a href="<?= htmlspecialchars($base) ?>/my-profile" class="my-profile-back">Refresh</a>
        </div>
    </header>

    <form action="<?= htmlspecialchars($base) ?>/my-profile/save" method="post" enctype="multipart/form-data" class="my-profile-form">
        <input type="hidden" name="actor_id" value="<?= $actor_id ?>">

        <section class="my-profile-section" aria-label="Display name">
            <label for="actor_name">Display name</label>
            <input type="text" id="actor_name" name="actor_name" value="<?= $actor_name ?>"
                   maxlength="255" class="my-profile-input" required>
        </section>

        <section class="my-profile-section" aria-label="Avatar">
            <label>Avatar</label>
            <?php if ($avatar_public_path !== ''): ?>
                <div class="my-profile-avatar-preview">
                    <img src="<?= htmlspecialchars($avatar_public_path) ?>?t=<?= time() ?>" alt="Avatar" class="my-profile-avatar-img">
                </div>
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" class="my-profile-file">
            <p class="my-profile-hint">Optional. JPG, PNG, GIF or WebP. Stored under uploads/actors/YYYY/MM/.</p>
        </section>

        <section class="my-profile-section" aria-label="Additional properties">
            <h2 class="my-profile-section-title">Additional properties</h2>
            <?php
            $editable_keys = ['bio', 'timezone', 'avatar_storage_path'];
            foreach ($editable_keys as $key):
                if ($key === 'avatar_storage_path') continue;
                $val = isset($actor_properties[$key]) ? htmlspecialchars($actor_properties[$key]) : '';
                $name = 'prop_' . $key;
            ?>
            <label for="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($key) ?></label>
            <input type="text" id="<?= htmlspecialchars($name) ?>" name="<?= htmlspecialchars($name) ?>" value="<?= $val ?>" class="my-profile-input">
            <?php endforeach; ?>
        </section>

        <div class="my-profile-actions">
            <button type="submit" class="my-profile-submit">Save profile</button>
        </div>
    </form>
</div>
