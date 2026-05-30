<?php
/**
 * Temporal anchor pulse — refresh bin/temporal_anchor.json from PHP (real UTC).
 *
 * Keeps the same single source of truth as python bin/tick.py so IDE, chat, and
 * web admin share one clock for headers and handoff (constitutional PRD 00 §3.5a).
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. time.php cannot be called directly.');
}

/**
 * Write temporal_anchor.json if missing or older than $minSeconds (reduces disk I/O).
 * Semantics align with tick.py: last_session_end is the previous current_utc from file.
 *
 * @param int $minSeconds Minimum seconds since anchor mtime before rewriting (default 60).
 * @return bool True if skipped (fresh enough) or write succeeded; false on hard failure.
 */
function lupo_pulse_temporal_anchor($minSeconds = 60)
{
    $root = '';
    if (defined('LUPOPEDIA_PATH')) {
        $root = LUPOPEDIA_PATH;
    } elseif (defined('LUPOPEDIA_ABSPATH')) {
        $root = rtrim(LUPOPEDIA_ABSPATH, '/\\');
    }
    if ($root === '') {
        return false;
    }

    $anchorPath = $root . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'temporal_anchor.json';
    $currentUtcPath = $root . DIRECTORY_SEPARATOR . 'CURRENT_UTC';
    $now = gmdate('YmdHis');
    $systemYear = gmdate('Y');

    $anchor = array();
    if (is_file($anchorPath)) {
        $raw = @file_get_contents($anchorPath);
        if ($raw !== false && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $anchor = $decoded;
            }
        }
    }

    $lastSessionEnd = isset($anchor['current_utc']) ? $anchor['current_utc'] : $now;

    $data = array(
        'current_utc' => $now,
        'last_session_end' => $lastSessionEnd,
        'system_year' => $systemYear,
        'format_standard' => 'YYYYMMDDHHMMSS',
        'anchor_source' => 'PHP_INTERFACE',
    );

    if (is_file($anchorPath)) {
        $mtime = @filemtime($anchorPath);
        if ($mtime !== false && (time() - $mtime) < (int) $minSeconds) {
            return true;
        }
    }

    $jsonFlags = defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0;
    $payload = json_encode($data, $jsonFlags);
    if ($payload === false) {
        return false;
    }

    $ok = @file_put_contents($anchorPath, $payload) !== false;
    if ($ok) {
        @file_put_contents($currentUtcPath, $now);
    }

    return $ok;
}

/**
 * Alias for documentation / human-readable name ("Lupo Pulse").
 *
 * @param int $minSeconds
 * @return bool
 */
function LupoPulse($minSeconds = 60)
{
    return lupo_pulse_temporal_anchor($minSeconds);
}
