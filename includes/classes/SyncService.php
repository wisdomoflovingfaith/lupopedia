<?php
/**
 * Sync policy: when no conflict, update non-canonical side. FS is canonical for body; DB for metadata. Last write wins for body when fs_utc >= db_utc. No three-way merge.
 *
 * @package Lupopedia
 */

class SyncService
{
    /**
     * Apply sync based on drift result: if fs_wins and DB given, caller can update DB from file; if conflict, only flag (no auto-merge).
     *
     * @param array  $drift_result From DriftDetector::detect
     * @param string $content_path Path to file (for logging)
     * @param string $conflicts_log Path to conflicts log (e.g. actors/42/logs/conflicts.log)
     * @param string $base_path
     * @return bool True if sync applied (fs_wins, no conflict), false if conflict or nothing to do
     */
    public static function applyPolicy($drift_result, $content_path, $conflicts_log = '', $base_path = '')
    {
        if (!is_array($drift_result)) {
            return false;
        }
        $conflict = isset($drift_result['conflict']) ? $drift_result['conflict'] : false;
        $fs_wins  = isset($drift_result['fs_wins']) ? $drift_result['fs_wins'] : false;
        if ($conflict && $conflicts_log !== '') {
            DriftDetector::flagConflict($conflicts_log, $content_path, isset($drift_result['details']) ? $drift_result['details'] : '', $base_path);
            return false;
        }
        if ($fs_wins) {
            return true;
        }
        return false;
    }
}
