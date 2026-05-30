<?php
/**
 * Atom Loader Function
 *
 * Loads atoms used by runtime metadata.
 *
 * Version truth source order:
 * 1) memory/atoms/lupopedia_global_constants.atom.toon
 * 2) config/global_atoms.yaml (legacy mirror)
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
function load_atoms($atom_name = null)
{
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->loadAtoms($atom_name) : null;
}

/**
 * Get a specific atom (thin wrapper — AtomLoader).
 *
 * @param string $atom_name e.g. 'GLOBAL_CURRENT_LUPOPEDIA_VERSION'
 * @return mixed|null
 */
function get_atom($atom_name)
{
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->getAtom($atom_name) : load_atoms($atom_name);
}

/**
 * Get current Lupopedia version (thin wrapper — AtomLoader).
 *
 * @return string
 */
function get_lupopedia_version()
{
    $loader = isset($GLOBALS['lupo_atom_loader']) ? $GLOBALS['lupo_atom_loader'] : null;
    if ($loader) {
        return $loader->getLupopediaVersion();
    }

    // Fallback before bootstrap: read global constants atom directly.
    $root = dirname(dirname(__DIR__));
    $atom_path = $root . DIRECTORY_SEPARATOR . 'memory' . DIRECTORY_SEPARATOR . 'atoms' . DIRECTORY_SEPARATOR . 'lupopedia_global_constants.atom.toon';
    if (is_file($atom_path)) {
        $raw = @file_get_contents($atom_path);
        if ($raw !== false) {
            $decoded = @json_decode($raw, true);
            $v = is_array($decoded) ? ($decoded['constants']['versioning']['current_lupopedia_version'] ?? null) : null;
            if (is_string($v) && $v !== '') {
                return $v;
            }
        }
    }

    $atom = get_atom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
    if ($atom !== null) {
        return $atom;
    }
    $a = load_atoms();
    if (is_array($a) && isset($a['version'])) {
        return $a['version'];
    }

    // install.php may call this before bootstrap initializes AtomLoader globals.
    $candidates = array(
        $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
        $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
        $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
    );
    foreach ($candidates as $atoms_file) {
        if (is_file($atoms_file)) {
            $content = @file_get_contents($atoms_file);
            if ($content !== false && preg_match('/^GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*["\']?([0-9.]+)["\']?/m', $content, $matches)) {
                return $matches[1];
            }
            if ($content !== false && preg_match('/^version:\s*["\']?([0-9.]+)["\']?/m', $content, $matches)) {
                return $matches[1];
            }
        }
    }

    return '0.0.0';
}

/**
 * Calculate version number (thin wrapper — App\Support\VersionUtils).
 *
 * @param string $version e.g. "3.0.0"
 * @return int
 */
function calculate_version_num($version)
{
    return class_exists('App\Support\VersionUtils') ? \App\Support\VersionUtils::calculateVersionNum((string) $version) : (function ($v) {
        $p = explode('.', $v);
        return ((int) ($p[0] ?? 0) * 10000) + ((int) ($p[1] ?? 0) * 100) + (int) ($p[2] ?? 0); })($version);
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
function _parse_atoms_yaml($path)
{
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->parseAtomsYaml($path) : [];
}

/**
 * Regex parse for base atoms (thin wrapper — AtomLoader). @internal
 *
 * @param string $content Raw YAML content
 * @return array
 */
function _parse_atoms_yaml_regex($content)
{
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->parseAtomsYamlRegex($content) : [];
}

/**
 * Read cosmic microwave background (thin wrapper — AtomLoader).
 *
 * @return array
 */
function read_cosmic_microwave_background()
{
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->readCosmicMicrowaveBackground() : [];
}

/**
 * Get base atom by key, dot notation (thin wrapper — AtomLoader).
 *
 * @param string $key e.g. 'GLOBAL_CURRENT_LUPOPEDIA_VERSION', 'authors.primary'
 * @return mixed|null
 */
function get_base_atom($key)
{
    $loader = $GLOBALS['lupo_atom_loader'] ?? null;
    return $loader ? $loader->getBaseAtom((string) $key) : null;
}
