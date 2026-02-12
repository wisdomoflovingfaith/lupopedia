<?php

namespace App\Support;

/**
 * Version number calculation (M.m.p -> integer).
 */
class VersionUtils
{
    /**
     * Parse "M.m.p" to M*10000 + m*100 + p.
     *
     * @param string $version e.g. "3.0.0"
     * @return int
     */
    public static function calculateVersionNum(string $version): int
    {
        $parts = explode('.', $version);
        $major = isset($parts[0]) ? (int) $parts[0] : 0;
        $minor = isset($parts[1]) ? (int) $parts[1] : 0;
        $patch = isset($parts[2]) ? (int) $parts[2] : 0;
        return ($major * 10000) + ($minor * 100) + $patch;
    }
}
