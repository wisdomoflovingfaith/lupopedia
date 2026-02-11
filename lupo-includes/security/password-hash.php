<?php
/**
 * wolfie.header.identity: password-hash
 * wolfie.header.placement: /lupo-includes/security/password-hash.php
 * wolfie.header.version: 3.0.8
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created password hashing utility for version 3.0.8 authentication. Supports bcrypt (new) and MD5 (legacy) with automatic upgrade on login."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. password-hash.php cannot be called directly.");
}

/**
 * Password Hashing Utilities
 * 
 * Provides secure password hashing with bcrypt and legacy MD5 support.
 * Automatically upgrades MD5 passwords to bcrypt on verification.
 * 
 * @package Lupopedia
 * @subpackage Security
 */

// Default bcrypt cost factor (can be overridden in config)
if (!defined('LUPO_BCRYPT_COST')) {
    define('LUPO_BCRYPT_COST', 10);
}

/**
 * PHP 5.3/5.4 fallback: bcrypt via crypt() when password_hash is not available.
 *
 * @param string $password Plaintext password
 * @return string|false Bcrypt hash on success, false on failure
 */
function lupo_bcrypt_crypt_fallback($password) {
    $cost = defined('LUPO_BCRYPT_COST') ? (int) LUPO_BCRYPT_COST : 10;
    if ($cost < 4 || $cost > 31) {
        $cost = 10;
    }
    $alphabet = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $salt = '';
    $len = strlen($alphabet);
    for ($i = 0; $i < 22; $i++) {
        $salt .= $alphabet[mt_rand(0, $len - 1)];
    }
    $setting = '$2y$' . sprintf('%02d', $cost) . '$' . $salt;
    $hash = crypt($password, $setting);
    return (is_string($hash) && strlen($hash) > 13) ? $hash : false;
}

/**
 * Hash a password using bcrypt
 * 
 * Uses password_hash on PHP 5.5+, crypt() fallback on PHP 5.3/5.4.
 *
 * @param string $password Plaintext password
 * @return string|false Bcrypt hash on success, false on failure
 */
function lupo_hash_password($password) {
    if (empty($password)) {
        return false;
    }
    if (function_exists('password_hash')) {
        $options = array(
            'cost' => defined('LUPO_BCRYPT_COST') ? LUPO_BCRYPT_COST : 10
        );
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    return lupo_bcrypt_crypt_fallback($password);
}

/**
 * Check if a hash is bcrypt format
 * 
 * Bcrypt hashes start with $2y$ (or $2a$, $2b$)
 * 
 * @param string $hash Password hash to check
 * @return bool True if bcrypt format, false otherwise
 */
function lupo_is_bcrypt_hash($hash) {
    if (empty($hash)) {
        return false;
    }
    
    // Bcrypt hashes start with $2y$, $2a$, or $2b$
    return (strpos($hash, '$2y$') === 0 || 
            strpos($hash, '$2a$') === 0 || 
            strpos($hash, '$2b$') === 0);
}

/**
 * Check if a hash is MD5 format
 * 
 * MD5 hashes are exactly 32 hexadecimal characters
 * 
 * @param string $hash Password hash to check
 * @return bool True if MD5 format, false otherwise
 */
function lupo_is_md5_hash($hash) {
    if (empty($hash)) {
        return false;
    }
    
    // MD5 hashes are exactly 32 hexadecimal characters
    return (strlen($hash) === 32 && ctype_xdigit($hash));
}

/**
 * Verify a password against a hash
 * 
 * Supports both bcrypt (new) and MD5 (legacy) formats.
 * Returns true if password matches, false otherwise.
 * 
 * @param string $password Plaintext password
 * @param string $hash Stored password hash
 * @return bool True if password matches, false otherwise
 */
function lupo_verify_password($password, $hash) {
    if (empty($password) || empty($hash)) {
        return false;
    }
    
    // Try bcrypt first (new format)
    if (lupo_is_bcrypt_hash($hash)) {
        if (function_exists('password_verify')) {
            return password_verify($password, $hash);
        }
        return (crypt($password, $hash) === $hash);
    }
    
    // Fallback to MD5 (legacy)
    if (lupo_is_md5_hash($hash)) {
        $md5_hash = md5($password);
        return ($md5_hash === $hash);
    }
    
    // Unknown hash format
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("AUTH WARNING: Unknown password hash format for hash: " . substr($hash, 0, 20) . "...");
    }
    
    return false;
}

/**
 * Check if password hash needs upgrade
 * 
 * Returns true if hash is MD5 (legacy) and should be upgraded to bcrypt.
 * 
 * @param string $hash Password hash to check
 * @return bool True if hash needs upgrade, false otherwise
 */
function lupo_password_needs_upgrade($hash) {
    return lupo_is_md5_hash($hash);
}

?>
