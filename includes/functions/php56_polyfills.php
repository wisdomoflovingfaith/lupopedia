<?php
/**
 * PHP 5.6 Compatibility Polyfills
 * Provides PHP 7+ functions for PHP 5.6 environments
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. php56_polyfills.php cannot be called directly.");
}

/**
 * PHP 7.0+ random_bytes() polyfill for PHP 5.6
 * Generates cryptographically secure random bytes
 */
if (!function_exists('random_bytes')) {
    function random_bytes($length)
    {
        if (!is_int($length)) {
            trigger_error('random_bytes() expects parameter 1 to be integer, ' . gettype($length) . ' given', E_USER_WARNING);
            return false;
        }
        
        if ($length <= 0) {
            trigger_error('random_bytes(): Length must be greater than 0', E_USER_WARNING);
            return false;
        }
        
        // Try OpenSSL first (most secure)
        if (function_exists('openssl_random_pseudo_bytes')) {
            $secure = true;
            $bytes = openssl_random_pseudo_bytes($length, $secure);
            if ($secure !== false && $bytes !== false) {
                return $bytes;
            }
        }
        
        // Try mcrypt_create_iv() if available
        if (function_exists('mcrypt_create_iv')) {
            $bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if ($bytes !== false) {
                return $bytes;
            }
        }
        
        // Fallback to /dev/urandom on Unix systems
        if (DIRECTORY_SEPARATOR !== '/' && @is_readable('/dev/urandom')) {
            $handle = fopen('/dev/urandom', 'rb');
            if ($handle !== false) {
                $bytes = fread($handle, $length);
                fclose($handle);
                if (strlen($bytes) === $length) {
                    return $bytes;
                }
            }
        }
        
        // Last resort - less secure but functional
        // Use mt_rand() with additional entropy
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            // Add some entropy from microtime
            $entropy = microtime(true) * 1000000;
            mt_srand((int)($entropy + $i * 12345));
            $bytes .= chr(mt_rand(0, 255));
        }
        
        return $bytes;
    }
}

/**
 * PHP 7.0+ random_int() polyfill for PHP 5.6
 * Generates cryptographically secure random integers
 */
if (!function_exists('random_int')) {
    function random_int($min, $max)
    {
        if (!is_int($min) || !is_int($max)) {
            trigger_error('random_int() expects parameters to be integer', E_USER_WARNING);
            return false;
        }
        
        if ($min > $max) {
            trigger_error('random_int(): min value must be less than or equal to max value', E_USER_WARNING);
            return false;
        }
        
        $range = $max - $min + 1;
        
        // Use random_bytes if available (most secure)
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(4);
            if ($bytes !== false) {
                $val = unpack('L', $bytes)[1];
                return $min + (abs($val) % $range);
            }
        }
        
        // Fallback to mt_rand() with additional entropy
        $entropy = microtime(true) * 1000000;
        mt_srand((int)$entropy);
        return mt_rand($min, $max);
    }
}

/**
 * PHP 7.0+ intdiv() polyfill for PHP 5.6
 * Performs integer division
 */
if (!function_exists('intdiv')) {
    function intdiv($dividend, $divisor)
    {
        if ($divisor == 0) {
            trigger_error('intdiv(): Division by zero', E_WARNING);
            return false;
        }
        
        if (!is_int($dividend) || !is_int($divisor)) {
            trigger_error('intdiv() expects parameters to be integer', E_WARNING);
            return false;
        }
        
        $quotient = $dividend / $divisor;
        
        if ($quotient < 0 && $quotient != (int)$quotient) {
            // For negative numbers, PHP 7's intdiv rounds toward zero
            return (int)floor($quotient);
        }
        
        return (int)$quotient;
    }
}

/**
 * Helper function to check if we're running on PHP 5.6
 */
function is_php56()
{
    return version_compare(PHP_VERSION, '5.6.0', '>=') && version_compare(PHP_VERSION, '7.0.0', '<');
}

/**
 * Helper function to safely generate session ID for PHP 5.6
 */
function lupo_generate_session_id()
{
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes(16));
    }
    
    // Fallback for older PHP versions
    return bin2hex(openssl_random_pseudo_bytes(16) ?: mcrypt_create_iv(16, MCRYPT_DEV_URANDOM) ?: md5(uniqid(mt_rand(), true)));
}

/**
 * Helper function for secure password hashing in PHP 5.6
 */
function lupo_password_hash($password, $algo = PASSWORD_DEFAULT, $options = array())
{
    // Use password_hash if available (PHP 5.5+)
    if (function_exists('password_hash')) {
        return password_hash($password, $algo, $options);
    }
    
    // Fallback for very old PHP versions (not recommended)
    $salt = lupo_generate_session_id();
    return hash('sha256', $password . $salt) . ':' . $salt;
}

/**
 * Helper function for password verification in PHP 5.6
 */
function lupo_password_verify($password, $hash)
{
    // Use password_verify if available (PHP 5.5+)
    if (function_exists('password_verify')) {
        return password_verify($password, $hash);
    }
    
    // Fallback for very old PHP versions
    if (strpos($hash, ':') !== false) {
        list($hash, $salt) = explode(':', $hash, 2);
        return hash('sha256', $password . $salt) === $hash;
    }
    
    return false;
}

// Log that polyfills are loaded (for debugging)
error_log("Lupopedia PHP 5.6 polyfills loaded on PHP " . PHP_VERSION);
