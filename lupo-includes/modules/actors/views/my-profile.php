<?php
/**
 * My Profile — standalone interface (same layout pattern as channel cockpit).
 * Does NOT use the content system, content-page.php, slug lookup, or render_main_layout.
 * Rendered via layout-topnav.php: topbar + full-width content. CSS and wrapper same as show.php.
 */
$actor = isset($actor) ? $actor : array();
$actor_id = isset($actor_id) ? (int) $actor_id : 0;
$actor_properties = isset($actor_properties) && is_array($actor_properties) ? $actor_properties : array();
$avatar_public_path = isset($avatar_public_path) ? $avatar_public_path : '';
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');
$actor_name = isset($actor['name']) ? htmlspecialchars($actor['name']) : '';

// Timezone offset dropdown: decimal(4,2) hours from UTC (same concept as legacy timezone_offset). Value stored in actor_properties.timezone.
$timezone_offset_options = array(
    '-12.00' => 'UTC−12 (Baker Island)',
    '-11.00' => 'UTC−11 (American Samoa, Midway)',
    '-10.00' => 'Hawaii — Honolulu (UTC−10)',
    '-9.00'  => 'Alaska — Anchorage (UTC−9)',
    '-8.00'  => 'Pacific — Los Angeles, Seattle (UTC−8)',
    '-7.00'  => 'Mountain — Denver, Phoenix (UTC−7)',
    '-6.00'  => 'Central — Chicago, Sioux Falls, Dallas (UTC−6)',
    '-5.00'  => 'Eastern — New York, Detroit (UTC−5)',
    '-4.00'  => 'Atlantic — Halifax (UTC−4)',
    '-3.50'  => 'Newfoundland — St. John\'s (UTC−3:30)',
    '-3.00'  => 'Buenos Aires, São Paulo (UTC−3)',
    '-2.00'  => 'UTC−2 (Mid-Atlantic)',
    '-1.00'  => 'Azores (UTC−1)',
    '0.00'   => 'UTC — London (winter), Reykjavik',
    '1.00'   => 'Central European — Paris, Berlin (UTC+1)',
    '2.00'   => 'Eastern European — Athens, Cairo (UTC+2)',
    '3.00'   => 'Moscow, Baghdad (UTC+3)',
    '3.50'   => 'Iran — Tehran (UTC+3:30)',
    '4.00'   => 'Dubai, Baku (UTC+4)',
    '4.30'   => 'Afghanistan — Kabul (UTC+4:30)',
    '5.00'   => 'Pakistan (UTC+5)',
    '5.30'   => 'India — Mumbai, Delhi (UTC+5:30)',
    '5.75'   => 'Nepal — Kathmandu (UTC+5:45)',
    '6.00'   => 'Bangladesh (UTC+6)',
    '7.00'   => 'Bangkok, Jakarta (UTC+7)',
    '8.00'   => 'China — Beijing, Singapore, Perth (UTC+8)',
    '9.00'   => 'Japan — Tokyo, Korea (UTC+9)',
    '9.30'   => 'Australia — Adelaide (UTC+9:30)',
    '10.00'  => 'Australia — Sydney (UTC+10)',
    '11.00'  => 'Solomon Islands (UTC+11)',
    '12.00'  => 'New Zealand — Auckland (UTC+12)',
    '13.00'  => 'Samoa (UTC+13)',
    '14.00'  => 'Line Islands (UTC+14)',
);
$current_timezone = isset($actor_properties['timezone']) ? $actor_properties['timezone'] : '0.00';
if (!isset($timezone_offset_options[$current_timezone])) {
    $current_timezone = '0.00';
}
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
            <div class="my-profile-field">
                <label for="prop_timezone">Timezone</label>
                <select id="prop_timezone" name="prop_timezone" class="my-profile-input">
                    <?php foreach ($timezone_offset_options as $offset => $label): ?>
                    <option value="<?= htmlspecialchars($offset) ?>"<?= $current_timezone === $offset ? ' selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="my-profile-hint">UTC offset in hours (decimal). Stored like legacy timezone_offset (e.g. −6.00 for Central).</p>
            </div>
            <div class="my-profile-field">
                <label for="prop_bio">Bio</label>
                <input type="text" id="prop_bio" name="prop_bio" value="<?= isset($actor_properties['bio']) ? htmlspecialchars($actor_properties['bio']) : '' ?>" class="my-profile-input">
            </div>
        </section>

        <div class="my-profile-actions">
            <button type="submit" class="my-profile-submit">Save profile</button>
        </div>
    </form>
</div>
