<?php
/**
 * Atom Loader Function
 *
 * Loads global atoms from config/global_atoms.yaml. Provides single source of
 * truth for ecosystem-wide metadata.
 *
 * @package Lupopedia
 * @version 4.2.3
 *
 * @note Phase 2 Versioning: version.php and callers load version from the atom
 *       instead of hard-coding. See docs/doctrine/VERSION_DOCTRINE.md.
 * @note Cosmic Microwave Background: read_cosmic_microwave_background() returns
 *       all base atoms from global_atoms.yaml + GLOBAL_IMPORTANT_ATOMS.yaml.
 */

/**
 * Load all atoms from global_atoms.yaml
 * 
 * Uses yaml_parse() if available (PHP YAML extension), otherwise falls back to regex parsing
 * 
 * @param string|null $atom_name Optional: Return specific atom value, or null for all atoms
 * @return mixed|array|null Returns atom value if $atom_name specified, array of all atoms if null, null if not found
 */
function load_atoms($atom_name = null) {
    static $atoms_cache = null;
    
    // Load atoms once and cache
    if ($atoms_cache === null) {
        $atoms_file = __DIR__ . '/../../config/global_atoms.yaml';
        
        if (!file_exists($atoms_file)) {
            trigger_error("Atom file not found: {$atoms_file}", E_USER_WARNING);
            return null;
        }
        
        $yaml_content = file_get_contents($atoms_file);
        $atoms_cache = [];
        
        // Try using yaml_parse if available (PHP YAML extension)
        if (function_exists('yaml_parse')) {
            $parsed = @yaml_parse($yaml_content);
            if ($parsed !== false && is_array($parsed)) {
                // Extract top-level version
                if (isset($parsed['version'])) {
                    $atoms_cache['version'] = $parsed['version'];
                }
                
                // Extract versions section
                if (isset($parsed['versions']) && is_array($parsed['versions'])) {
                    $atoms_cache['versions'] = $parsed['versions'];
                }
                
                // Extract all GLOBAL_* atoms (they're at root level)
                foreach ($parsed as $key => $value) {
                    if (strpos($key, 'GLOBAL_') === 0) {
                        // Handle string values
                        if (is_string($value)) {
                            $atoms_cache[$key] = $value;
                        } elseif (is_array($value)) {
                            $atoms_cache[$key] = $value;
                        }
                    }
                }
            }
        } else {
            // Fallback: Simple regex-based parser for key-value pairs
            // Extract version from top-level
            if (preg_match('/^version:\s*["\']?([^"\'\n]+)["\']?/m', $yaml_content, $matches)) {
                $atoms_cache['version'] = trim($matches[1], '"\'');
            }
            
            // Extract versions section
            if (preg_match('/versions:\s*\n((?:\s+[a-z_]+:\s*["\']?[^"\'\n]+["\']?\n?)+)/m', $yaml_content, $matches)) {
                $atoms_cache['versions'] = [];
                if (preg_match_all('/\s+([a-z_]+):\s*["\']?([^"\'\n]+)["\']?/m', $matches[1], $version_matches, PREG_SET_ORDER)) {
                    foreach ($version_matches as $vm) {
                        $atoms_cache['versions'][$vm[1]] = trim($vm[2], '"\'');
                    }
                }
            }
            
            // Extract GLOBAL_* atoms (simple key-value pairs at root level)
            // Match lines like: GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.35"
            if (preg_match_all('/^(GLOBAL_[A-Z_]+):\s*["\']?([^"\'\n]+)["\']?/m', $yaml_content, $atom_matches, PREG_SET_ORDER)) {
                foreach ($atom_matches as $am) {
                    $atoms_cache[$am[1]] = trim($am[2], '"\'');
                }
            }
        }
    }
    
    // Return specific atom or all atoms
    if ($atom_name !== null) {
        return isset($atoms_cache[$atom_name]) ? $atoms_cache[$atom_name] : null;
    }
    
    return $atoms_cache;
}

/**
 * Get a specific atom value
 * 
 * @param string $atom_name The atom name (e.g., 'GLOBAL_CURRENT_LUPOPEDIA_VERSION')
 * @return string|null The atom value or null if not found
 */
function get_atom($atom_name) {
    return load_atoms($atom_name);
}

/**
 * Get the current Lupopedia version from atom
 * 
 * @return string The current version (e.g., "4.0.35")
 */
function get_lupopedia_version() {
    $version = get_atom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
    if ($version === null) {
        // Fallback to version field if atom not found
        $atoms = load_atoms();
        $version = isset($atoms['version']) ? $atoms['version'] : '4.0.35'; // Last resort fallback
    }
    return $version;
}

/**
 * Calculate version number from version string
 * Format: MAJOR * 10000 + MINOR * 100 + PATCH
 * Example: 4.0.35 = 40035
 * 
 * @param string $version Version string (e.g., "4.0.35")
 * @return int Version number for numeric comparisons
 */
function calculate_version_num($version) {
    $parts = explode('.', $version);
    $major = isset($parts[0]) ? (int)$parts[0] : 0;
    $minor = isset($parts[1]) ? (int)$parts[1] : 0;
    $patch = isset($parts[2]) ? (int)$parts[2] : 0;
    
    return ($major * 10000) + ($minor * 100) + $patch;
}

// ---------------------------------------------------------------------------
// Cosmic Microwave Background (CMB) — Base Atoms
// ---------------------------------------------------------------------------
// The CMB is the foundational radiation: config/global_atoms.yaml merged with
// config/GLOBAL_IMPORTANT_ATOMS.yaml. All base atoms that the ecosystem is
// built on. GLOBAL_IMPORTANT_ATOMS overrides overlapping keys (foundation wins).

/**
 * Parse a YAML file into an array. Uses yaml_parse when available.
 *
 * @param string $path Absolute path to YAML file
 * @return array<string, mixed>
 * @internal
 */
function _parse_atoms_yaml($path) {
    if (!file_exists($path)) {
        return [];
    }
    $raw = @file_get_contents($path);
    if ($raw === false) {
        return [];
    }
    if (function_exists('yaml_parse')) {
        $p = @yaml_parse($raw);
        return is_array($p) ? $p : [];
    }
    return _parse_atoms_yaml_regex($raw);
}

/**
 * Fallback regex parse for base atoms (scalars only). Used when yaml_parse is unavailable.
 *
 * @param string $content Raw YAML content
 * @return array<string, mixed>
 * @internal
 */
function _parse_atoms_yaml_regex($content) {
    $out = [];
    if (preg_match('/^version:\s*["\']?([^"\'\s\n]+)["\']?/m', $content, $m)) {
        $out['version'] = trim($m[1], '"\'');
    }
    if (preg_match('/^authors:\s*\n\s+primary:\s*["\']?([^"\'\n]+)["\']?/m', $content, $m)) {
        $out['authors'] = ['primary' => trim($m[1], '"\'')];
    }
    if (preg_match('/^project:\s*\n\s+name:\s*["\']?([^"\'\n]+)["\']?/m', $content, $m)) {
        $out['project'] = ['name' => trim($m[1], '"\'')];
    }
    $pat = '/^(GLOBAL_[A-Z0-9_]+|BRIDGE_[A-Z0-9_]+|MASTER_BRIDGE[A-Z0-9_]*|UTC_TIMEKEEPER__[A-Z0-9_]+|WOLFIE_[A-Z0-9_]+):\s*["\']?([^"\'\n]*)["\']?\s*$/m';
    if (preg_match_all($pat, $content, $ms, PREG_SET_ORDER)) {
        foreach ($ms as $m) {
            $out[$m[1]] = trim($m[2], '"\'');
        }
    }
    return $out;
}

/**
 * Read the cosmic microwave background: all base atoms from the foundational config.
 *
 * Merges config/global_atoms.yaml and config/GLOBAL_IMPORTANT_ATOMS.yaml.
 * GLOBAL_IMPORTANT_ATOMS overrides overlapping keys (foundation wins).
 * Full nested structures (authors, project, GLOBAL_LUPOPEDIA_*, BRIDGE_*, etc.)
 * are available when the PHP YAML extension is present.
 *
 * @return array<string, mixed> Merged base atoms (nested). Empty on parse failure.
 */
function read_cosmic_microwave_background() {
    static $cmb_cache = null;
    if ($cmb_cache !== null) {
        return $cmb_cache;
    }
    $base = __DIR__ . '/../../config';
    $from_global   = _parse_atoms_yaml($base . '/global_atoms.yaml');
    $from_important = _parse_atoms_yaml($base . '/GLOBAL_IMPORTANT_ATOMS.yaml');
    $cmb_cache = array_replace_recursive($from_global, $from_important);
    return $cmb_cache;
}

/**
 * Get a single base atom by key. Supports dot notation for nested keys.
 *
 * Examples: get_base_atom('GLOBAL_CURRENT_LUPOPEDIA_VERSION'),
 *           get_base_atom('authors.primary'), get_base_atom('MASTER_BRIDGE_FILE')
 *
 * @param string $key Atom name or dotted path (e.g. 'authors.primary')
 * @return mixed The value or null if not found
 */
function get_base_atom($key) {
    $cmb = read_cosmic_microwave_background();
    $parts = explode('.', $key);
    $cur = $cmb;
    foreach ($parts as $p) {
        if (!is_array($cur) || !array_key_exists($p, $cur)) {
            return null;
        }
        $cur = $cur[$p];
    }
    return $cur;
}
