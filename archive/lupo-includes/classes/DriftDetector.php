<?php
/**
 * Drift detection: compare filesystem (canonical for body) vs DB (metadata). If FS last_modified_utc > DB, last write wins for body. If both changed, flag conflict to queue/log for Anubis. No automatic three-way merge.
 *
 * @package Lupopedia
 */

class DriftDetector
{
    /**
     * Detect drift between parsed FLARE (from file) and DB metadata. Returns array with 'conflict' (bool), 'fs_wins' (bool), 'details' (string).
     *
     * @param array  $parsed_headers Headers from FlareParser
     * @param string $body_hash     Optional hash of body for comparison
     * @param array  $db_meta       Optional DB row with last_modified_umdhis, body_hash, etc.
     * @return array
     */
    public static function detect($parsed_headers, $body_hash = '', $db_meta = array())
    {
        $fs_utc = 0;
        if (isset($parsed_headers['flare']) && is_array($parsed_headers['flare']) && isset($parsed_headers['flare']['headers']) && is_array($parsed_headers['flare']['headers'])) {
            $h = $parsed_headers['flare']['headers'];
            $fs_utc = isset($h['last_modified_utc']) ? (int) $h['last_modified_utc'] : 0;
        } elseif (isset($parsed_headers['flare.headers']) && is_array($parsed_headers['flare.headers'])) {
            $fs_utc = isset($parsed_headers['flare.headers']['last_modified_utc']) ? (int) $parsed_headers['flare.headers']['last_modified_utc'] : 0;
        } elseif (isset($parsed_headers['last_modified_utc'])) {
            $fs_utc = (int) $parsed_headers['last_modified_utc'];
        }
        $db_utc = isset($db_meta['last_modified_ymdhis']) ? (int) $db_meta['last_modified_ymdhis'] : (isset($db_meta['last_modified_utc']) ? (int) $db_meta['last_modified_utc'] : 0);
        $fs_wins = ($fs_utc >= $db_utc);
        $conflict = ($db_utc > 0 && $fs_utc > 0 && $fs_utc !== $db_utc && !$fs_wins);
        return array(
            'conflict' => $conflict,
            'fs_wins'  => $fs_wins,
            'details'  => 'fs_utc=' . $fs_utc . ' db_utc=' . $db_utc,
        );
    }

    /**
     * Append conflict to queue file (e.g. lupo-actors/42/logs/conflicts.log or anubis queue).
     *
     * @param string $log_path Full path to log file
     * @param string $path     Content path
     * @param string $details  Optional details
     * @param string $base_path
     */
    public static function flagConflict($log_path, $path, $details = '', $base_path = '')
    {
        $dir = dirname($log_path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $line = gmdate('Y-m-d\TH:i:s\Z') . ' conflict path=' . $path . ($details !== '' ? ' ' . $details : '') . "\n";
        @file_put_contents($log_path, $line, FILE_APPEND | LOCK_EX);
    }
}
