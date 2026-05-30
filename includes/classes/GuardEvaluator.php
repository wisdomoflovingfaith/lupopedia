<?php
/**
 * Evaluates flare.conditional.guards_allow from parsed FLARE headers. If guards allow,
 * content can be written or hooks may perform writes; otherwise read-only.
 *
 * @package Lupopedia
 */

class GuardEvaluator
{
    /**
     * Evaluate whether guards allow write/execution. Default deny if missing.
     *
     * @param array $headers Parsed FLARE headers (nested)
     * @return bool
     */
    public static function guardsAllow($headers)
    {
        if (!is_array($headers)) {
            return false;
        }
        $conditional = isset($headers['flare']) && is_array($headers['flare'])
            ? $headers['flare']
            : array();
        if (isset($conditional['conditional']) && is_array($conditional['conditional'])) {
            $c = $conditional['conditional'];
            if (isset($c['guards_allow'])) {
                $v = $c['guards_allow'];
                if ($v === true || $v === 'true' || $v === 1 || $v === '1' || $v === 'yes') {
                    return true;
                }
            }
        }
        return false;
    }
}
