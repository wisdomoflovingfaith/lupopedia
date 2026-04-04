<?php
/**
 * Canonical UTC for KAIROS persistence (memory rows, edges).
 *
 * Reads lupo-bin/temporal_anchor.json when present; falls back to gmdate('YmdHis').
 * Rate limiting may use wall clock separately — never stored as doctrine timestamps.
 */
class KairosTemporalAnchor
{
    /**
     * @return string 14-digit YYYYMMDDHHIISS UTC
     */
    public static function getUtcYmdHis()
    {
        if (!defined('LUPOPEDIA_PATH')) {
            return gmdate('YmdHis');
        }

        $path = rtrim(LUPOPEDIA_PATH, '/\\') . DIRECTORY_SEPARATOR . 'lupo-bin' . DIRECTORY_SEPARATOR . 'temporal_anchor.json';
        if (!is_file($path) || !is_readable($path)) {
            return gmdate('YmdHis');
        }

        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return gmdate('YmdHis');
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return gmdate('YmdHis');
        }

        $utc = isset($data['current_utc']) ? (string) $data['current_utc'] : '';
        if ($utc === '' || !preg_match('/^\d{14}$/', $utc)) {
            return gmdate('YmdHis');
        }

        $hh = (int) substr($utc, 8, 2);
        $mi = (int) substr($utc, 10, 2);
        $ss = (int) substr($utc, 12, 2);
        if ($hh > 23 || $mi > 59 || $ss > 59) {
            return gmdate('YmdHis');
        }

        return $utc;
    }
}
