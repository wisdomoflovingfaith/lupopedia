<?php

/**
 * UI locale: session-backed, PHP array catalogs under lupo-includes/lang/lupo-{locale}.php
 * (Crafty-style lang files; semantic keys instead of txtNN).
 */
class LupoLocale
{
    const SESSION_KEY = 'lupo_locale';

    private static $strings = array();

    /**
     * @return array
     */
    public static function allowedLocales()
    {
        return array('en');
    }

    /**
     * @param string|null $code
     * @return string
     */
    public static function normalize($code)
    {
        if ($code === null || $code === '') {
            return 'en';
        }
        $code = strtolower(trim((string) $code));
        $code = preg_replace('/[^a-z0-9_-]+/', '', $code);
        if (in_array($code, self::allowedLocales(), true)) {
            return $code;
        }
        return 'en';
    }

    /**
     * Apply GET/POST lupo_locale, then load strings for the session locale.
     *
     * @param string $appRoot Lupopedia install root (directory containing lupo-includes)
     */
    public static function bootstrap($appRoot)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (isset($_GET['lupo_locale'])) {
                $_SESSION[self::SESSION_KEY] = self::normalize($_GET['lupo_locale']);
            }
            if (isset($_POST['lupo_locale'])) {
                $_SESSION[self::SESSION_KEY] = self::normalize($_POST['lupo_locale']);
            }
        }
        $loc = self::getLocale();
        self::load($appRoot, $loc);
    }

    /**
     * @return string
     */
    public static function getLocale()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return 'en';
        }
        if (isset($_SESSION[self::SESSION_KEY])) {
            return self::normalize($_SESSION[self::SESSION_KEY]);
        }
        return 'en';
    }

    /**
     * @param string $appRoot
     * @param string $locale
     */
    public static function load($appRoot, $locale)
    {
        $locale = self::normalize($locale);
        $root = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $appRoot), DIRECTORY_SEPARATOR);
        $path = $root . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'lupo-' . $locale . '.php';
        self::$strings = array();
        if (is_file($path)) {
            $loaded = include $path;
            if (is_array($loaded)) {
                self::$strings = $loaded;
            }
        }
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return string
     */
    public static function text($key, $default = null)
    {
        if (isset(self::$strings[$key])) {
            return self::$strings[$key];
        }
        if ($default !== null) {
            return $default;
        }
        return $key;
    }

    /**
     * Stable slug for i18n keys derived from English menu labels / titles.
     *
     * @param string $label
     * @return string
     */
    public static function slug($label)
    {
        $s = strtolower((string) $label);
        $s = preg_replace('/[^a-z0-9]+/', '_', $s);
        $s = trim($s, '_');
        if ($s === '') {
            return 'item';
        }
        return $s;
    }

    /**
     * @return string safe for html lang=""
     */
    public static function htmlLang()
    {
        $l = self::getLocale();
        return htmlspecialchars($l, ENT_QUOTES, 'UTF-8');
    }
}
