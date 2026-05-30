<?php

/**
 * Thin wrapper so templates can call lupo_t() after LupoLocale::bootstrap().
 */

if (!function_exists('lupo_t')) {
    /**
     * @param string $key
     * @param string|null $default
     * @return string
     */
    function lupo_t($key, $default = null)
    {
        return LupoLocale::text($key, $default);
    }
}
