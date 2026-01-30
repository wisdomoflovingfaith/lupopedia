<?php
function lupo_crafty_is_admin() {
    $user = lupo_crafty_current_user();
    if (!$user) {
        return false;
    }
    return !empty($user['is_admin']);
}
