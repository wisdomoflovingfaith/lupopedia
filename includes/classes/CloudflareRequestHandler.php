<?php
/**
 * Cloudflare request header handling for Lupopedia.
 * Extracts real client IP (CF-Connecting-IP), country (CF-IPCountry), Ray ID (CF-Ray),
 * and optional threat score. Optionally validates that the request is from Cloudflare
 * IP ranges to prevent header spoofing. Sets REMOTE_ADDR and defines LUPO_CLIENT_* constants.
 *
 * PHP 5.3+ compatible. No Composer. Use from bootstrap after config load.
 *
 * @package Lupopedia
 */

class CloudflareRequestHandler
{
    /** @var string */
    private static $client_ip = '';

    /** @var string */
    private static $client_country = '';

    /** @var string */
    private static $cf_ray = '';

    /** @var int */
    private static $threat_score = -1;

    /** @var bool */
    private static $from_cloudflare = false;

    /**
     * Run once per request. Call from bootstrap after config and before session.
     * Reads Cloudflare headers, optionally validates proxy IP, sets REMOTE_ADDR and constants.
     */
    public static function process()
    {
        $cf_ip     = isset($_SERVER['HTTP_CF_CONNECTING_IP'])     ? trim($_SERVER['HTTP_CF_CONNECTING_IP'])     : '';
        $cf_country = isset($_SERVER['HTTP_CF_IPCOUNTRY'])       ? trim($_SERVER['HTTP_CF_IPCOUNTRY'])         : '';
        $cf_ray   = isset($_SERVER['HTTP_CF_RAY'])               ? trim($_SERVER['HTTP_CF_RAY'])               : '';
        $cf_threat = isset($_SERVER['HTTP_CF_THREAT_SCORE'])     ? trim($_SERVER['HTTP_CF_THREAT_SCORE'])       : '';
        $remote   = isset($_SERVER['REMOTE_ADDR'])               ? trim($_SERVER['REMOTE_ADDR'])               : '';

        self::$client_ip      = $remote;
        self::$client_country = $cf_country !== '' ? $cf_country : 'XX';
        self::$cf_ray         = $cf_ray;
        self::$threat_score   = $cf_threat !== '' && is_numeric($cf_threat) ? (int) $cf_threat : -1;
        self::$from_cloudflare = ($cf_ip !== '' || $cf_ray !== '');

        $enabled = defined('LUPO_CLOUDFLARE_ENABLED') && LUPO_CLOUDFLARE_ENABLED;
        if (!$enabled) {
            self::defineConstants();
            return;
        }

        $trust_proxy = defined('LUPO_CLOUDFLARE_TRUST_PROXY') && LUPO_CLOUDFLARE_TRUST_PROXY;
        $valid       = false;

        if ($cf_ip !== '') {
            if (defined('LUPO_CLOUDFLARE_VALIDATE_IP') && LUPO_CLOUDFLARE_VALIDATE_IP) {
                $valid = self::isCloudflareIp($remote);
            } else {
                $valid = $trust_proxy;
            }
            if ($valid && $cf_ip !== '') {
                $_SERVER['REMOTE_ADDR'] = $cf_ip;
                self::$client_ip = $cf_ip;
            }
        }

        if (defined('LUPO_CLOUDFLARE_THREAT_MAX') && LUPO_CLOUDFLARE_THREAT_MAX >= 0 && self::$threat_score >= 0) {
            if (self::$threat_score > LUPO_CLOUDFLARE_THREAT_MAX) {
                self::deny('threat_score');
                return;
            }
        }

        if (defined('LUPO_CLOUDFLARE_BLOCKED_COUNTRIES') && is_string(LUPO_CLOUDFLARE_BLOCKED_COUNTRIES)) {
            $blocked = array_map('trim', explode(',', LUPO_CLOUDFLARE_BLOCKED_COUNTRIES));
            $country = strtoupper(self::$client_country);
            if (in_array($country, $blocked)) {
                self::deny('country');
                return;
            }
        }

        self::defineConstants();
    }

    private static function defineConstants()
    {
        if (!defined('LUPO_CLIENT_IP')) {
            define('LUPO_CLIENT_IP', self::$client_ip);
        }
        if (!defined('LUPO_CLIENT_COUNTRY')) {
            define('LUPO_CLIENT_COUNTRY', self::$client_country);
        }
        if (!defined('LUPO_CF_RAY')) {
            define('LUPO_CF_RAY', self::$cf_ray);
        }
        if (!defined('LUPO_CF_THREAT_SCORE')) {
            define('LUPO_CF_THREAT_SCORE', self::$threat_score);
        }
        if (!defined('LUPO_REQUEST_VIA_CLOUDFLARE')) {
            define('LUPO_REQUEST_VIA_CLOUDFLARE', self::$from_cloudflare);
        }
    }

    /**
     * Check if the given IP is in Cloudflare's published ranges.
     * Uses LUPO_CLOUDFLARE_IPS_FILE (path to a file with one CIDR per line) if defined.
     *
     * @param string $ip IPv4 or IPv6
     * @return bool
     */
    public static function isCloudflareIp($ip)
    {
        if (!defined('LUPO_CLOUDFLARE_IPS_FILE') || !is_file(LUPO_CLOUDFLARE_IPS_FILE)) {
            return false;
        }
        $lines = @file(LUPO_CLOUDFLARE_IPS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!is_array($lines)) {
            return false;
        }
        foreach ($lines as $cidr) {
            $cidr = trim($cidr);
            if ($cidr === '') {
                continue;
            }
            if (self::ipInCidr($ip, $cidr)) {
                return true;
            }
        }
        return false;
    }

    /**
     * IPv4 CIDR check (PHP 5.3 safe).
     *
     * @param string $ip
     * @param string $cidr e.g. 173.245.48.0/20
     * @return bool
     */
    public static function ipInCidr($ip, $cidr)
    {
        if (strpos($cidr, '/') === false) {
            return ($ip === $cidr);
        }
        $parts = explode('/', $cidr);
        $range = $parts[0];
        $bits  = isset($parts[1]) ? (int) $parts[1] : 32;
        if (strpos($ip, ':') !== false) {
            return false;
        }
        $ip_long   = @ip2long($ip);
        $range_long = @ip2long($range);
        if ($ip_long === false || $range_long === false) {
            return false;
        }
        $mask = $bits >= 32 ? 0xFFFFFFFF : (0xFFFFFFFF << (32 - $bits)) & 0xFFFFFFFF;
        return ($ip_long & $mask) === ($range_long & $mask);
    }

    private static function deny($reason)
    {
        if (!headers_sent()) {
            header('HTTP/1.1 403 Forbidden');
            header('X-Lupo-Deny-Reason: ' . $reason);
        }
        exit('Access denied.');
    }

    public static function getClientIp()      { return self::$client_ip; }
    public static function getClientCountry() { return self::$client_country; }
    public static function getCfRay()        { return self::$cf_ray; }
    public static function getThreatScore()   { return self::$threat_score; }
    public static function isFromCloudflare() { return self::$from_cloudflare; }
}
