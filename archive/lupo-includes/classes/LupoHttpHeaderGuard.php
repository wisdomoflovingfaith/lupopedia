<?php

/**
 * Mitigate HTTP response splitting / header injection (CRLF in header values).
 * PHPMailer covers mail headers; any raw PHP header() from partly user-controlled strings should use this.
 *
 * @package Lupopedia
 */
class LupoHttpHeaderGuard
{

    /**
     * Emit a single header after stripping CR/LF from name and value.
     *
     * @param string $name  Header field name (e.g. Location).
     * @param string $value Field value (e.g. URL path).
     * @param bool   $replace Whether to replace prior same-named header (PHP header() third arg).
     * @param int    $code    Optional HTTP response code (PHP 5.3+ header fourth arg); use null to omit.
     * @return void
     */
    public static function header($name, $value, $replace = true, $code = null)
    {
        if (!is_string($name)) {
            $name = '';
        }
        if (!is_string($value)) {
            $value = '';
        }
        $name = str_replace(array("\r", "\n"), '', $name);
        $value = str_replace(array("\r", "\n"), '', $value);
        if ($name === '') {
            return;
        }
        $line = $name . ': ' . $value;
        if ($code !== null && is_int($code)) {
            header($line, $replace, $code);
        } else {
            header($line, $replace);
        }
    }
}
