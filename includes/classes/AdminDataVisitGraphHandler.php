<?php

/**
 * Standalone admin visit graph (Crafty graph.php parity for type=visit).
 * Daily hit counts per path_url from lupo_visits (legacy used livehelp_visits_daily/monthly + recno).
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

class AdminDataVisitGraphHandler
{
    /**
     * @param object $db PDO_DB
     * @param string $prefix Table prefix
     * @param string $base Public base path (e.g. /lupopedia)
     * @param string $pathUrl Exact path_url from lupo_visits
     * @param int $year
     * @param int $month 1-12
     * @param int $actorId 0 = all actors (legacy whichdepartment scope approximated)
     * @return string Full HTML document
     */
    public static function renderDocument($db, $prefix, $base, $pathUrl, $year, $month, $actorId)
    {
        if (!function_exists('lupo_t')) {
            require_once LUPOPEDIA_PATH . '/includes/i18n.php';
        }

        $visits_t = $prefix . 'visits';
        if (function_exists('cal_days_in_month')) {
            $dim = (int) cal_days_in_month(CAL_GREGORIAN, (int) $month, (int) $year);
        } else {
            $dims = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
            $dim = $dims[(int) $month - 1];
            if ((int) $month === 2 && (($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0))) {
                $dim = 29;
            }
        }

        if ($month === 12) {
            $ny = $year + 1;
            $nm = 1;
        } else {
            $ny = $year;
            $nm = $month + 1;
        }
        $monthStart = sprintf('%04d%02d01000000', $year, $month);
        $monthEndEx = sprintf('%04d%02d01000000', $ny, $nm);

        $sql = "SELECT created_ymdhis, transition_metadata, actor_id FROM {$visits_t}
                WHERE is_deleted = 0 AND path_url = :pu
                  AND created_ymdhis >= :ms AND created_ymdhis < :me
                  AND (transition_metadata IS NULL OR transition_metadata = ''
                    OR transition_metadata NOT LIKE :ex_mo)";
        $gRows = $db->fetchAll($sql, array(
            'pu' => $pathUrl,
            'ms' => $monthStart,
            'me' => $monthEndEx,
            'ex_mo' => '%livehelp_visits_monthly%',
        ));

        $byDay = array();
        for ($d = 1; $d <= $dim; $d++) {
            $byDay[$d] = 0;
        }
        foreach ($gRows as $gr) {
            if ($actorId > 0) {
                $rowActor = isset($gr['actor_id']) ? (int) $gr['actor_id'] : 0;
                $mj = isset($gr['transition_metadata']) ? (string) $gr['transition_metadata'] : '';
                $meta = json_decode($mj, true);
                $dept = (is_array($meta) && isset($meta['legacy_department'])) ? (int) $meta['legacy_department'] : 0;
                if ($rowActor !== $actorId && $dept !== $actorId) {
                    continue;
                }
            }
            $cy = isset($gr['created_ymdhis']) ? (string) $gr['created_ymdhis'] : '';
            if (strlen($cy) < 8) {
                continue;
            }
            $gy = (int) substr($cy, 0, 4);
            $gmo = (int) substr($cy, 4, 2);
            $gday = (int) substr($cy, 6, 2);
            if ($gy !== $year || $gmo !== $month || $gday < 1 || $gday > $dim) {
                continue;
            }
            $w = self::graphVisitRowWeight(isset($gr['transition_metadata']) ? $gr['transition_metadata'] : '');
            $byDay[$gday] += $w;
        }

        $series = array();
        $max = 1;
        for ($d = 1; $d <= $dim; $d++) {
            $cnt = isset($byDay[$d]) ? (int) $byDay[$d] : 0;
            if ($cnt > $max) {
                $max = $cnt;
            }
            $series[] = array('day' => $d, 'cnt' => $cnt);
        }

        $title = lupo_t('admin.data.graph_title', 'Visits by day');
        $escUrl = htmlspecialchars(self::shortenText($pathUrl, 120), ENT_QUOTES, 'UTF-8');
        $fullEsc = htmlspecialchars($pathUrl, ENT_QUOTES, 'UTF-8');

        $rowsHtml = '';
        foreach ($series as $pt) {
            $cnt = (int) $pt['cnt'];
            $h = $max > 0 ? (int) floor(($cnt / $max) * 120) : 0;
            $rowsHtml .= '<td class="visit-graph-cell" valign="bottom">'
                . '<div class="visit-graph-num">' . (int) $cnt . '</div>'
                . '<div class="visit-graph-bar" style="height:' . $h . 'px"></div>'
                . '</td>';
        }

        $daysHtml = '';
        foreach ($series as $pt) {
            $daysHtml .= '<td class="visit-graph-day">' . sprintf('%02d', (int) $pt['day']) . '</td>';
        }

        $adminBack = htmlspecialchars(rtrim($base, '/') . '/admin.php?section=database&amp;data_tab=visits', ENT_QUOTES, 'UTF-8');

        return '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8" />'
            . '<title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>'
            . '<style type="text/css">'
            . 'body{font-family:Georgia,serif;background:#f5f0e6;color:#2e1a16;margin:16px;}'
            . 'h1{font-size:1.1rem;margin:0 0 8px;}'
            . '.visit-graph-url{font-size:0.9rem;word-break:break-all;margin-bottom:16px;color:#4e342e;}'
            . 'table.visit-graph{border-collapse:collapse;background:#fff;border:1px solid #bcaaa4;}'
            . 'td.visit-graph-cell{text-align:center;padding:4px 3px 0;vertical-align:bottom;}'
            . '.visit-graph-num{font-size:0.75rem;margin-bottom:2px;}'
            . '.visit-graph-bar{width:12px;margin:0 auto;background:#6d4c41;border-radius:2px 2px 0 0;min-height:0;}'
            . 'td.visit-graph-day{text-align:center;font-size:0.75rem;padding:4px 3px;border-top:1px solid #d7ccc8;}'
            . '.visit-graph-caption{margin-top:12px;font-size:0.85rem;color:#5d4037;}'
            . 'a{color:#4e342e;}</style></head><body>'
            . '<h1>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>'
            . '<div class="visit-graph-url" title="' . $fullEsc . '">' . $escUrl . '</div>'
            . '<p class="visit-graph-caption">'
            . htmlspecialchars(sprintf(lupo_t('admin.data.graph_month_caption', '%04d-%02d UTC — lupo_visits'), $year, $month), ENT_QUOTES, 'UTF-8')
            . '</p>'
            . '<table class="visit-graph" role="presentation"><tr>' . $rowsHtml . '</tr><tr>' . $daysHtml . '</tr></table>'
            . '<p style="margin-top:20px;"><a href="' . $adminBack . '">' . htmlspecialchars(lupo_t('admin.data.graph_back_admin', 'Back to Data hub'), ENT_QUOTES, 'UTF-8') . '</a></p>'
            . '</body></html>';
    }

    /**
     * @param string $s
     * @param int $max
     * @return string
     */
    private static function shortenText($s, $max)
    {
        $u = (string) $s;
        if (strlen($u) <= $max) {
            return $u;
        }
        return substr($u, 0, max(0, $max - 3)) . '...';
    }

    /**
     * Same weight rules as AdminDataHubHandler::analyticsVisitRowWeight (legacy_directvisits when set).
     *
     * @param string $metaJson
     * @return int
     */
    private static function graphVisitRowWeight($metaJson)
    {
        $s = (string) $metaJson;
        if ($s === '') {
            return 1;
        }
        $meta = json_decode($s, true);
        if (!is_array($meta)) {
            return 1;
        }
        if (isset($meta['legacy_directvisits'])) {
            $d = (int) $meta['legacy_directvisits'];
            if ($d > 0) {
                return $d;
            }
            return 0;
        }
        return 1;
    }
}
