<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: view
  when_updated: "20260406020607"
  file_path_from_root: "lupo-includes/modules/actors/views/my-profile.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/modules/actors/views/my-profile.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "view"
  artifact_kind: "my_profile"
  purpose: "My Profile view — actor, identity, 2FA, avatar, properties; lupo_t UI strings; $UNTRUSTED session/server snapshot; flash profile_error via controller or session copy."
  tags: ["profile", "ui", "view", "actors", "i18n"]
---
*/

/**
 * My Profile — included by actors_handle_my_profile() only.
 * §17.8: $UNTRUSTED snapshot for session and server; no direct $_SESSION / $_SERVER reads in markup.
 */
$UNTRUSTED = array(
    'session' => (isset($_SESSION) && is_array($_SESSION)) ? $_SESSION : array(),
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
);

$actor = isset($actor) ? $actor : array();
$actor_id = isset($actor_id) ? (int) $actor_id : 0;
$auth_user_id = isset($auth_user_id) ? (int) $auth_user_id : 0;
$current_email = isset($current_email) ? $current_email : '';
$actor_properties = isset($actor_properties) && is_array($actor_properties) ? $actor_properties : array();
$avatar_public_path = isset($avatar_public_path) ? $avatar_public_path : '';
$base = isset($base) ? $base : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '');

$profile_error_display = '';
if (isset($profile_error) && $profile_error !== '') {
    $profile_error_display = $profile_error;
} elseif (isset($UNTRUSTED['session']['profile_error'])) {
    $pe = $UNTRUSTED['session']['profile_error'];
    $profile_error_display = is_string($pe) ? htmlspecialchars($pe, ENT_QUOTES, 'UTF-8') : '';
    if (isset($_SESSION['profile_error'])) {
        unset($_SESSION['profile_error']);
    }
}

$request_uri = '';
if (isset($UNTRUSTED['server']['REQUEST_URI']) && is_string($UNTRUSTED['server']['REQUEST_URI'])) {
    $request_uri = $UNTRUSTED['server']['REQUEST_URI'];
}
$select_actor_redirect = ($request_uri !== '' ? $request_uri : rtrim($base, '/') . '/my-profile');

$profile_current_actor_name = isset($profile_current_actor_name) ? $profile_current_actor_name : (function_exists('lupo_t') ? lupo_t('profile.actor_unknown', 'Unknown') : 'Unknown');
$profile_user_can_switch_actors = !empty($profile_user_can_switch_actors);

$avatar_cache_bust = gmdate('YmdHis');
if (class_exists('timestamp_ymdhis', false)) {
    $avatar_cache_bust = (string) timestamp_ymdhis::now();
}

$actor_name_val = '';
if (isset($actor['name']) && $actor['name'] !== '') {
    $actor_name_val = htmlspecialchars($actor['name'], ENT_QUOTES, 'UTF-8');
} elseif (isset($actor['actor_name'])) {
    $actor_name_val = htmlspecialchars($actor['actor_name'], ENT_QUOTES, 'UTF-8');
}

if ($current_email === null) {
    $current_email = function_exists('lupo_t') ? lupo_t('profile.email_none', 'No email set') : 'No email set';
}

$tz_defs = array(
    '-12.00' => array('profile.tz.m12', 'UTC−12 (Baker Island)'),
    '-11.00' => array('profile.tz.m11', 'UTC−11 (American Samoa, Midway)'),
    '-10.00' => array('profile.tz.m10', 'Hawaii — Honolulu (UTC−10)'),
    '-9.00' => array('profile.tz.m9', 'Alaska — Anchorage (UTC−9)'),
    '-8.00' => array('profile.tz.m8', 'Pacific — Los Angeles, Seattle (UTC−8)'),
    '-7.00' => array('profile.tz.m7', 'Mountain — Denver, Phoenix (UTC−7)'),
    '-6.00' => array('profile.tz.m6', 'Central — Chicago, Sioux Falls, Dallas (UTC−6)'),
    '-5.00' => array('profile.tz.m5', 'Eastern — New York, Detroit (UTC−5)'),
    '-4.00' => array('profile.tz.m4', 'Atlantic — Halifax (UTC−4)'),
    '-3.50' => array('profile.tz.m3_5', 'Newfoundland — St. John\'s (UTC−3:30)'),
    '-3.00' => array('profile.tz.m3', 'Buenos Aires, São Paulo (UTC−3)'),
    '-2.00' => array('profile.tz.m2', 'UTC−2 (Mid-Atlantic)'),
    '-1.00' => array('profile.tz.m1', 'Azores (UTC−1)'),
    '0.00' => array('profile.tz.z0', 'UTC — London (winter), Reykjavik'),
    '1.00' => array('profile.tz.p1', 'Central European — Paris, Berlin (UTC+1)'),
    '2.00' => array('profile.tz.p2', 'Eastern European — Athens, Cairo (UTC+2)'),
    '3.00' => array('profile.tz.p3', 'Moscow, Baghdad (UTC+3)'),
    '3.50' => array('profile.tz.p3_5', 'Iran — Tehran (UTC+3:30)'),
    '4.00' => array('profile.tz.p4', 'Dubai, Baku (UTC+4)'),
    '4.30' => array('profile.tz.p4_5', 'Afghanistan — Kabul (UTC+4:30)'),
    '5.00' => array('profile.tz.p5', 'Pakistan (UTC+5)'),
    '5.30' => array('profile.tz.p5_5', 'India — Mumbai, Delhi (UTC+5:30)'),
    '5.75' => array('profile.tz.p5_75', 'Nepal — Kathmandu (UTC+5:45)'),
    '6.00' => array('profile.tz.p6', 'Bangladesh (UTC+6)'),
    '7.00' => array('profile.tz.p7', 'Bangkok, Jakarta (UTC+7)'),
    '8.00' => array('profile.tz.p8', 'China — Beijing, Singapore, Perth (UTC+8)'),
    '9.00' => array('profile.tz.p9', 'Japan — Tokyo, Korea (UTC+9)'),
    '9.30' => array('profile.tz.p9_5', 'Australia — Adelaide (UTC+9:30)'),
    '10.00' => array('profile.tz.p10', 'Australia — Sydney (UTC+10)'),
    '11.00' => array('profile.tz.p11', 'Solomon Islands (UTC+11)'),
    '12.00' => array('profile.tz.p12', 'New Zealand — Auckland (UTC+12)'),
    '13.00' => array('profile.tz.p13', 'Samoa (UTC+13)'),
    '14.00' => array('profile.tz.p14', 'Line Islands (UTC+14)'),
);
$timezone_offset_options = array();
foreach ($tz_defs as $off => $pair) {
    $timezone_offset_options[$off] = function_exists('lupo_t') ? lupo_t($pair[0], $pair[1]) : $pair[1];
}
$current_timezone = isset($actor_properties['timezone']) ? $actor_properties['timezone'] : '0.00';
if (!isset($timezone_offset_options[$current_timezone])) {
    $current_timezone = '0.00';
}
?>
<link rel="stylesheet" href="<?= $base ?>/lupo-includes/modules/channels/channel-interface.css">
<link rel="stylesheet" href="<?= $base ?>/lupo-includes/modules/actors/my-profile.css">
<div class="my-profile-page" id="my-profile-page" data-base="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>">
    <header class="my-profile-header">
        <h1 class="my-profile-title"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.title', 'My Profile'), ENT_QUOTES, 'UTF-8') : 'My Profile' ?></h1>
        <div class="my-profile-header-actions">
            <a href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/channels/my-channels" class="my-profile-back"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.my_channels', 'My Channels'), ENT_QUOTES, 'UTF-8') : 'My Channels' ?></a>
            <a href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/my-profile" class="my-profile-back"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.refresh', 'Refresh'), ENT_QUOTES, 'UTF-8') : 'Refresh' ?></a>
        </div>
    </header>

    <?php if ($profile_error_display !== ''): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 0.75rem; margin: 1rem 0; border-radius: 4px;">
            <strong><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.error_label', 'Error'), ENT_QUOTES, 'UTF-8') : 'Error' ?>:</strong> <?= $profile_error_display ?>
        </div>
    <?php endif; ?>

    <div class="my-profile-section" aria-label="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.current_actor', 'Current Actor'), ENT_QUOTES, 'UTF-8') : 'Current Actor' ?>">
        <h2 class="my-profile-section-title"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.current_actor', 'Current Actor'), ENT_QUOTES, 'UTF-8') : 'Current Actor' ?></h2>
        <div class="my-profile-field">
            <label><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.acting_as', 'Acting As'), ENT_QUOTES, 'UTF-8') : 'Acting As' ?></label>
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                <strong style="font-size: 1.1em; color: #2c5282;"><?= htmlspecialchars($profile_current_actor_name, ENT_QUOTES, 'UTF-8') ?></strong>
                <?php if ($profile_user_can_switch_actors): ?>
                    <a href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/select-actor.php?redirect=<?= urlencode($select_actor_redirect) ?>"
                       style="color: #4299e1; text-decoration: none; font-size: 0.9em; padding: 4px 8px; border: 1px solid #4299e1; border-radius: 4px; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='#e6f3ff'; this.style.textDecoration='none'"
                       onmouseout="this.style.backgroundColor='transparent'; this.style.textDecoration='none'">
                        <?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.change', 'change'), ENT_QUOTES, 'UTF-8') : 'change' ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="my-profile-section" aria-label="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.identity', 'Identity Information'), ENT_QUOTES, 'UTF-8') : 'Identity Information' ?>">
        <h2 class="my-profile-section-title"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.identity', 'Identity Information'), ENT_QUOTES, 'UTF-8') : 'Identity Information' ?></h2>
        <div class="my-profile-field">
            <label><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.actor_id', 'Actor ID'), ENT_QUOTES, 'UTF-8') : 'Actor ID' ?></label>
            <input type="text" value="<?= (int) $actor_id ?>" readonly class="my-profile-input" style="background: #f8f9fa; color: #666;">
        </div>
        <div class="my-profile-field">
            <label><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.auth_user_id', 'Auth User ID'), ENT_QUOTES, 'UTF-8') : 'Auth User ID' ?></label>
            <input type="text" value="<?= (int) $auth_user_id ?>" readonly class="my-profile-input" style="background: #f8f9fa; color: #666;">
        </div>
        <div class="my-profile-field">
            <label for="email"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.email', 'Email'), ENT_QUOTES, 'UTF-8') : 'Email' ?></label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars((string) $current_email, ENT_QUOTES, 'UTF-8') ?>"
                   maxlength="255" class="my-profile-input" required>
        </div>
    </div>

    <form action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/my-profile/save" method="post" enctype="multipart/form-data" class="my-profile-form">
        <input type="hidden" name="actor_id" value="<?= (int) $actor_id ?>">

        <section class="my-profile-section" aria-label="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.display_name', 'Display name'), ENT_QUOTES, 'UTF-8') : 'Display name' ?>">
            <label for="actor_name"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.display_name', 'Display name'), ENT_QUOTES, 'UTF-8') : 'Display name' ?></label>
            <input type="text" id="actor_name" name="actor_name" value="<?= $actor_name_val ?>"
                   maxlength="255" class="my-profile-input" required>
        </section>

        <section class="my-profile-section" aria-label="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_title', 'Two-Factor Authentication (2FA)'), ENT_QUOTES, 'UTF-8') : 'Two-Factor Authentication (2FA)' ?>">
            <h2 class="my-profile-section-title"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_title', 'Two-Factor Authentication (2FA)'), ENT_QUOTES, 'UTF-8') : 'Two-Factor Authentication (2FA)' ?></h2>
            <?php
            if (!isset($two_factor_enabled)) {
                $two_factor_enabled = 0;
            }
            $two_factor_status = $two_factor_enabled
                ? (function_exists('lupo_t') ? lupo_t('profile.2fa_enabled', 'Enabled') : 'Enabled')
                : (function_exists('lupo_t') ? lupo_t('profile.2fa_disabled', 'Disabled') : 'Disabled');
            if (!isset($profile_2fa_pending)) {
                $profile_2fa_pending = false;
            }
            ?>
            <div class="my-profile-field">
                <label><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_status', '2FA Status'), ENT_QUOTES, 'UTF-8') : '2FA Status' ?></label>
                <span style="font-weight:bold; color:<?= $two_factor_enabled ? '#38a169' : '#e53e3e' ?>;">
                    <?= htmlspecialchars($two_factor_status, ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>
            <?php if (!$two_factor_enabled): ?>
                <?php if (empty($profile_2fa_pending)): ?>
                    <form action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/my-profile/save" method="post" style="margin-top:1em;">
                        <input type="hidden" name="2fa_action" value="start">
                        <button type="submit" class="my-profile-submit"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_enable', 'Enable 2FA'), ENT_QUOTES, 'UTF-8') : 'Enable 2FA' ?></button>
                    </form>
                <?php else: ?>
                    <form action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/my-profile/save" method="post" style="margin-top:1em;">
                        <input type="hidden" name="2fa_action" value="verify">
                        <label for="2fa_code"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_enter_code', 'Enter the code sent to your email:'), ENT_QUOTES, 'UTF-8') : 'Enter the code sent to your email:' ?></label>
                        <input type="text" id="2fa_code" name="2fa_code" maxlength="8" class="my-profile-input" required>
                        <button type="submit" class="my-profile-submit"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_verify', 'Verify & Enable'), ENT_QUOTES, 'UTF-8') : 'Verify & Enable' ?></button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <form action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>/my-profile/save" method="post" style="margin-top:1em;">
                    <input type="hidden" name="2fa_action" value="disable">
                    <button type="submit" class="my-profile-submit" style="background:#e53e3e; color:#fff;"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.2fa_disable', 'Disable 2FA'), ENT_QUOTES, 'UTF-8') : 'Disable 2FA' ?></button>
                </form>
            <?php endif; ?>
        </section>

        <section class="my-profile-section" aria-label="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.avatar', 'Avatar'), ENT_QUOTES, 'UTF-8') : 'Avatar' ?>">
            <label><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.avatar', 'Avatar'), ENT_QUOTES, 'UTF-8') : 'Avatar' ?></label>
            <?php if ($avatar_public_path !== ''): ?>
                <div class="my-profile-avatar-preview">
                    <img src="<?= htmlspecialchars($avatar_public_path, ENT_QUOTES, 'UTF-8') ?>?t=<?= htmlspecialchars($avatar_cache_bust, ENT_QUOTES, 'UTF-8') ?>" alt="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.avatar', 'Avatar'), ENT_QUOTES, 'UTF-8') : 'Avatar' ?>" class="my-profile-avatar-img">
                </div>
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" class="my-profile-file">
            <p class="my-profile-hint"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.avatar_hint', 'Optional. JPG, PNG, GIF or WebP. Stored under uploads/actors/YYYY/MM/.'), ENT_QUOTES, 'UTF-8') : 'Optional. JPG, PNG, GIF or WebP. Stored under uploads/actors/YYYY/MM/.' ?></p>
        </section>

        <section class="my-profile-section" aria-label="<?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.additional_properties', 'Additional properties'), ENT_QUOTES, 'UTF-8') : 'Additional properties' ?>">
            <h2 class="my-profile-section-title"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.additional_properties', 'Additional properties'), ENT_QUOTES, 'UTF-8') : 'Additional properties' ?></h2>
            <div class="my-profile-field">
                <label for="prop_timezone"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.timezone', 'Timezone'), ENT_QUOTES, 'UTF-8') : 'Timezone' ?></label>
                <select id="prop_timezone" name="prop_timezone" class="my-profile-input">
                    <?php foreach ($timezone_offset_options as $offset => $label) : ?>
                    <option value="<?= htmlspecialchars($offset, ENT_QUOTES, 'UTF-8') ?>"<?= $current_timezone === $offset ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="my-profile-hint"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.utc_hint', 'UTC offset in hours (decimal). Stored like legacy timezone_offset (e.g. −6.00 for Central).'), ENT_QUOTES, 'UTF-8') : 'UTC offset in hours (decimal). Stored like legacy timezone_offset (e.g. −6.00 for Central).' ?></p>
            </div>
            <div class="my-profile-field">
                <label for="prop_bio"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.bio', 'Bio'), ENT_QUOTES, 'UTF-8') : 'Bio' ?></label>
                <input type="text" id="prop_bio" name="prop_bio" value="<?= isset($actor_properties['bio']) ? htmlspecialchars($actor_properties['bio'], ENT_QUOTES, 'UTF-8') : '' ?>" class="my-profile-input">
            </div>
        </section>

        <div class="my-profile-actions">
            <button type="submit" class="my-profile-submit"><?= function_exists('lupo_t') ? htmlspecialchars(lupo_t('profile.save', 'Save profile'), ENT_QUOTES, 'UTF-8') : 'Save profile' ?></button>
        </div>
    </form>
</div>
