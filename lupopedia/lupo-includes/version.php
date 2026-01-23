<?php
/**
 * Lupopedia Version Information
 * 
 * This file defines the current version of Lupopedia and provides
 * version-related constants and helper functions.
 * 
 * @package Lupopedia
 * @version 2026.1.0.1
 * 
 * @note VERSION DOCTRINE: This file now loads version from GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *       atom in config/global_atoms.yaml (Phase 2 implementation). Constants are defined
 *       from the atom at parse time, ensuring single source of truth. See
 *       docs/doctrine/VERSION_DOCTRINE.md for complete versioning doctrine.
 */

// Load atom loader function if not already loaded
if (!function_exists('load_atoms')) {
    $atom_loader = __DIR__ . '/functions/load_atoms.php';
    if (file_exists($atom_loader)) {
        require_once $atom_loader;
    }
}

// Load version from atom (single source of truth)
$version_from_atom = null;
if (function_exists('get_lupopedia_version')) {
    $version_from_atom = get_lupopedia_version();
} elseif (function_exists('load_atoms')) {
    $version_from_atom = load_atoms('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
    if ($version_from_atom === null) {
        // Fallback: try loading from version field
        $atoms = load_atoms();
        $version_from_atom = isset($atoms['version']) ? $atoms['version'] : null;
    }
}

// Fallback to hard-coded version if atom loader fails (backward compatibility)
$current_version = $version_from_atom !== null ? $version_from_atom : '4.4.1';

// LIMITS enforcement (dry-run mode in 4.0.103)
// Check version bump before applying (non-blocking, logs warnings only)
if (file_exists(__DIR__ . '/functions/limits_logger.php')) {
    require_once __DIR__ . '/functions/limits_logger.php';
    // Note: Version bump check would be called by version bump script, not here
    // This is a placeholder for future integration
}

/**
 * The Lupopedia version string
 * 
 * Follows Semantic Versioning (https://semver.org/)
 * Format: MAJOR.MINOR.PATCH[-PRERELEASE][+BUILD]
 * 
 * Loaded from GLOBAL_CURRENT_LUPOPEDIA_VERSION atom in config/global_atoms.yaml
 * 
 * @var string
 */
if (!defined('LUPOPEDIA_VERSION')) {
    define('LUPOPEDIA_VERSION', $current_version);
}

/**
 * The Lupopedia database version
 * 
 * This should be incremented whenever database schema changes are made.
 * Used for migration tracking and compatibility checks.
 * 
 * Loaded from GLOBAL_CURRENT_LUPOPEDIA_VERSION atom (same as LUPOPEDIA_VERSION)
 * 
 * @var string
 */
if (!defined('LUPOPEDIA_DB_VERSION')) {
    define('LUPOPEDIA_DB_VERSION', $current_version);
}

/**
 * The Lupopedia version number (for numeric comparisons)
 * 
 * Format: MAJOR * 10000 + MINOR * 100 + PATCH
 * Example: 1.0.1 = 10001, 1.2.3 = 10203, 4.0.0 = 40000
 * 
 * Calculated from LUPOPEDIA_VERSION
 * 
 * @var int
 */
if (!defined('LUPOPEDIA_VERSION_NUM')) {
    if (function_exists('calculate_version_num')) {
        define('LUPOPEDIA_VERSION_NUM', calculate_version_num($current_version));
    } else {
        // Fallback calculation
        $parts = explode('.', $current_version);
        $major = isset($parts[0]) ? (int)$parts[0] : 0;
        $minor = isset($parts[1]) ? (int)$parts[1] : 0;
        $patch = isset($parts[2]) ? (int)$parts[2] : 0;
        define('LUPOPEDIA_VERSION_NUM', ($major * 10000) + ($minor * 100) + $patch);
    }
}

/**
 * The release date of this version
 * 
 * Format: YYYYMMDDHHMMSS (BIGINT UTC timestamp format used throughout Lupopedia)
 * 
 * @note This is still manually updated. Future enhancement: load from atom or calculate from file modification time
 * 
 * @var int
 */
if (!defined('LUPOPEDIA_VERSION_DATE')) {
    define('LUPOPEDIA_VERSION_DATE', 20260120113800);
}

/**
 * Get the Lupopedia version string
 * 
 * Loads from atom if available, otherwise returns constant
 * 
 * @return string The version string (e.g., "4.0.35")
 */
function lupopedia_get_version() {
    // Try to load from atom first (most up-to-date)
    if (function_exists('get_lupopedia_version')) {
        $atom_version = get_lupopedia_version();
        if ($atom_version !== null) {
            return $atom_version;
        }
    }
    // Fallback to constant
    return defined('LUPOPEDIA_VERSION') ? LUPOPEDIA_VERSION : '2026.1.0.1';
}

/**
 * Get the Lupopedia database version
 * 
 * @return string The database version string
 */
function lupopedia_get_db_version() {
    return LUPOPEDIA_DB_VERSION;
}

/**
 * Get the Lupopedia version number for numeric comparisons
 * 
 * @return int The version number
 */
function lupopedia_get_version_num() {
    return LUPOPEDIA_VERSION_NUM;
}

/**
 * Get the release date of the current version
 * 
 * @return int The version date as BIGINT UTC timestamp
 */
function lupopedia_get_version_date() {
    return LUPOPEDIA_VERSION_DATE;
}

/**
 * Check if Lupopedia is a development/pre-release version
 * 
 * @return bool True if this is a dev/pre-release version
 */
function lupopedia_is_dev_version() {
    return (strpos(LUPOPEDIA_VERSION, '-dev') !== false || 
            strpos(LUPOPEDIA_VERSION, '-alpha') !== false ||
            strpos(LUPOPEDIA_VERSION, '-beta') !== false ||
            strpos(LUPOPEDIA_VERSION, '-rc') !== false);
}

/**
 * Get version information as an array
 * 
 * @return array Associative array with version information
 */
function lupopedia_get_version_info() {
    return [
        'version' => LUPOPEDIA_VERSION,
        'db_version' => LUPOPEDIA_DB_VERSION,
        'version_num' => LUPOPEDIA_VERSION_NUM,
        'version_date' => LUPOPEDIA_VERSION_DATE,
        'is_dev' => lupopedia_is_dev_version(),
        'release_date' => date('Y-m-d H:i:s', strtotime(substr((string)LUPOPEDIA_VERSION_DATE, 0, 8)))
    ];
}

/**
 * Compare two version numbers
 * 
 * @param string $version1 First version to compare
 * @param string $version2 Second version to compare
 * @return int Returns -1 if version1 < version2, 0 if equal, 1 if version1 > version2
 */
function lupopedia_compare_versions($version1, $version2) {
    $v1_parts = explode('.', preg_replace('/[^0-9.]/', '', $version1));
    $v2_parts = explode('.', preg_replace('/[^0-9.]/', '', $version2));
    
    // Pad arrays to same length
    $max_length = max(count($v1_parts), count($v2_parts));
    $v1_parts = array_pad($v1_parts, $max_length, 0);
    $v2_parts = array_pad($v2_parts, $max_length, 0);
    
    for ($i = 0; $i < $max_length; $i++) {
        if ($v1_parts[$i] < $v2_parts[$i]) {
            return -1;
        } elseif ($v1_parts[$i] > $v2_parts[$i]) {
            return 1;
        }
    }
    
    return 0;
}

/**
 * Check if a version meets minimum requirement
 * 
 * @param string $required_version Minimum required version
 * @param string $current_version Version to check (defaults to current Lupopedia version)
 * @return bool True if current version meets or exceeds required version
 */
function lupopedia_version_meets_requirement($required_version, $current_version = null) {
    if ($current_version === null) {
        $current_version = LUPOPEDIA_VERSION;
    }
    return lupopedia_compare_versions($current_version, $required_version) >= 0;
}

?>
