<?php
function lupo_crafty_db() {
    if (isset($GLOBALS['mydatabase'])) {
        return $GLOBALS['mydatabase'];
    }
    return null;
}
