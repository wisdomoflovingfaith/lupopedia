<?php

namespace App\Support;

/**
 * Atom loader — global_atoms.yaml, CMB, get atom / version.
 * Config/version domain; no DB.
 */
class AtomLoader
{
    /** @var string */
    private $configDir;

    /** @var array|null */
    private static $atomsCache = null;

    /** @var array|null */
    private static $cmbCache = null;

    public function __construct($configDir = null)
    {
        if ($configDir === null) {
            $base = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(dirname(dirname(dirname(__DIR__))));
            if (is_dir($base . '/lupo-config')) {
                $this->configDir = $base . '/lupo-config';
            } else {
                $this->configDir = $base . '/config';
            }
        } else {
            $this->configDir = $configDir;
        }
    }

    /**
     * Load atoms from global_atoms.yaml; optional single key.
     *
     * @param string|null $atomName Null for all
     * @return mixed|array|null
     */
    public function loadAtoms(?string $atomName = null)
    {
        if (self::$atomsCache === null) {
            $file = $this->configDir . '/global_atoms.yaml';
            if (!file_exists($file)) {
                trigger_error("Atom file not found: {$file}", E_USER_WARNING);
                return null;
            }
            $yaml_content = file_get_contents($file);
            self::$atomsCache = [];
            if (function_exists('yaml_parse')) {
                $parsed = @yaml_parse($yaml_content);
                if (is_array($parsed)) {
                    if (isset($parsed['version'])) {
                        self::$atomsCache['version'] = $parsed['version'];
                    }
                    if (isset($parsed['versions']) && is_array($parsed['versions'])) {
                        self::$atomsCache['versions'] = $parsed['versions'];
                    }
                    foreach ($parsed as $key => $value) {
                        if (strpos($key, 'GLOBAL_') === 0) {
                            self::$atomsCache[$key] = $value;
                        }
                    }
                }
            } else {
                if (preg_match('/^version:\s*["\']?([^"\'\n]+)["\']?/m', $yaml_content, $m)) {
                    self::$atomsCache['version'] = trim($m[1], '"\'');
                }
                if (preg_match('/versions:\s*\n((?:\s+[a-z_]+:\s*["\']?[^"\'\n]+["\']?\n?)+)/m', $yaml_content, $m)) {
                    self::$atomsCache['versions'] = [];
                    if (preg_match_all('/\s+([a-z_]+):\s*["\']?([^"\'\n]+)["\']?/m', $m[1], $vm, PREG_SET_ORDER)) {
                        foreach ($vm as $v) {
                            self::$atomsCache['versions'][$v[1]] = trim($v[2], '"\'');
                        }
                    }
                }
                if (preg_match_all('/^(GLOBAL_[A-Z_]+):\s*["\']?([^"\'\n]+)["\']?/m', $yaml_content, $am, PREG_SET_ORDER)) {
                    foreach ($am as $a) {
                        self::$atomsCache[$a[1]] = trim($a[2], '"\'');
                    }
                }
            }
        }
        if ($atomName !== null) {
            return self::$atomsCache[$atomName] ?? null;
        }
        return self::$atomsCache;
    }

    public function getAtom(string $atomName)
    {
        return $this->loadAtoms($atomName);
    }

    public function getLupopediaVersion(): string
    {
        $v = $this->getAtom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
        if ($v !== null) {
            return (string) $v;
        }
        $atoms = $this->loadAtoms();
        return isset($atoms['version']) ? (string) $atoms['version'] : '3.0.0';
    }

    /**
     * Parse YAML file (internal).
     *
     * @param string $path
     * @return array
     */
    public function parseAtomsYaml(string $path): array
    {
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
        return $this->parseAtomsYamlRegex($raw);
    }

    /**
     * Regex fallback for YAML (internal).
     *
     * @param string $content
     * @return array
     */
    public function parseAtomsYamlRegex(string $content): array
    {
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
     * Cosmic Microwave Background: global_atoms.yaml merged with GLOBAL_IMPORTANT_ATOMS.yaml.
     *
     * @return array
     */
    public function readCosmicMicrowaveBackground(): array
    {
        if (self::$cmbCache !== null) {
            return self::$cmbCache;
        }
        $from_global = $this->parseAtomsYaml($this->configDir . '/global_atoms.yaml');
        $from_important = $this->parseAtomsYaml($this->configDir . '/GLOBAL_IMPORTANT_ATOMS.yaml');
        self::$cmbCache = array_replace_recursive($from_global, $from_important);
        return self::$cmbCache;
    }

    /**
     * Get base atom by key (dot notation supported).
     *
     * @param string $key
     * @return mixed|null
     */
    public function getBaseAtom(string $key)
    {
        $cmb = $this->readCosmicMicrowaveBackground();
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
}
