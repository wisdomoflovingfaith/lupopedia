<?php
/**
 * Atom Loader Function
 *
 * Loads global atoms from config/global_atoms.yaml. Provides single source of
 * truth for ecosystem-wide metadata.
 *
 * @package Lupopedia
 * @version 3.0.0
 *
 * @note Phase 2 Versioning: version.php and callers load version from the atom
 *       instead of hard-coding. See docs/doctrine/VERSION_DOCTRINE.md.
 * @note Cosmic Microwave Background: read_cosmic_microwave_background() returns
 *       all base atoms from global_atoms.yaml + GLOBAL_IMPORTANT_ATOMS.yaml.
 */

/**
 * Load atoms (thin wrapper — App\Support\AtomLoader).
 *
 * @param string|null $atom_name Optional: return specific atom, or null for all
 * @return mixed|array|null
 */
function load_atoms($atom_name = null) {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->loadAtoms($atom_name) : null;
}

/**
 * Get a specific atom (thin wrapper — AtomLoader).
 *
 * @param string $atom_name e.g. 'GLOBAL_CURRENT_LUPOPEDIA_VERSION'
 * @return mixed|null
 */
function get_atom($atom_name) {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->getAtom($atom_name) : load_atoms($atom_name);
}

/**
 * Get current Lupopedia version (thin wrapper — AtomLoader).
 *
 * @return string
 */
function get_lupopedia_version() {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->getLupopediaVersion() : (get_atom('GLOBAL_CURRENT_LUPOPEDIA_VERSION') ?? ((($a = load_atoms()) && is_array($a) && isset($a['version'])) ? $a['version'] : '4.0.13'));
}

/**
 * Calculate version number (thin wrapper — App\Support\VersionUtils).
 *
 * @param string $version e.g. "3.0.0"
 * @return int
 */
function calculate_version_num($version) {
    return class_exists('App\Support\VersionUtils') ? \App\Support\VersionUtils::calculateVersionNum((string) $version) : (function($v) { $p = explode('.', $v); return ((int)($p[0]??0)*10000) + ((int)($p[1]??0)*100) + (int)($p[2]??0); })($version);
}

// ---------------------------------------------------------------------------
// Cosmic Microwave Background (CMB) — Base Atoms
// ---------------------------------------------------------------------------
// The CMB is the foundational radiation: config/global_atoms.yaml merged with
// config/GLOBAL_IMPORTANT_ATOMS.yaml. All base atoms that the ecosystem is
// built on. GLOBAL_IMPORTANT_ATOMS overrides overlapping keys (foundation wins).

/**
 * Parse YAML file (thin wrapper — AtomLoader). @internal
 *
 * @param string $path Absolute path to YAML file
 * @return array
 */
function _parse_atoms_yaml($path) {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->parseAtomsYaml($path) : [];
}

/**
 * Regex parse for base atoms (thin wrapper — AtomLoader). @internal
 *
 * @param string $content Raw YAML content
 * @return array
 */
function _parse_atoms_yaml_regex($content) {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->parseAtomsYamlRegex($content) : [];
}

/**
 * Read cosmic microwave background (thin wrapper — AtomLoader).
 *
 * @return array
 */
function read_cosmic_microwave_background() {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->readCosmicMicrowaveBackground() : [];
}

/**
 * Get base atom by key, dot notation (thin wrapper — AtomLoader).
 *
 * @param string $key e.g. 'GLOBAL_CURRENT_LUPOPEDIA_VERSION', 'authors.primary'
 * @return mixed|null
 */
function get_base_atom($key) {
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->getBaseAtom((string) $key) : null;
}
