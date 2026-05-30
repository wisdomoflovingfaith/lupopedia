<?php
/**
 * Generate a LUPO_SESSION_SALT value for lupopedia-config.php.
 *
 * Usage:
 *   php scripts/generate_session_salt.php
 *   php scripts/generate_session_salt.php --define
 */

$emit_define = false;
if (isset($argv) && is_array($argv)) {
    foreach ($argv as $arg) {
        if ($arg === '--define') {
            $emit_define = true;
            break;
        }
    }
}

$salt = bin2hex(random_bytes(32));
if ($emit_define) {
    echo "define('LUPO_SESSION_SALT', '" . $salt . "');" . PHP_EOL;
} else {
    echo $salt . PHP_EOL;
}
