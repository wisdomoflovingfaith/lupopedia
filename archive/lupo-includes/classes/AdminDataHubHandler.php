<?php

/**
 * Admin Data hub — top-nav "Data" landing (section=database).
 *
 * Tabbed read-only views aligned with craftysyntax-reference/data.php (paths, referers, visits)
 * and PRD 11 / PRD 33 analytics rollup tables (lupo_paths, lupo_referers_daily, lupo_visits).
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

class AdminDataHubHandler
{
    /**
     * @param object $db PDO_DB
     * @param string $prefix Table prefix
     * @param string $base Public base path
     * @return string HTML
     */
    public static function render($db, $prefix, $base)
    {
        if (!function_exists('lupo_t')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/lupo-i18n.php';
        }

        $tab = 'visits';
        if (isset($_GET['data_tab']) && is_string($_GET['data_tab'])) {
            $t = trim($_GET['data_tab']);
            if ($t === 'paths' || $t === 'referrers') {
                $tab = $t;
            } elseif ($t === 'visits') {
                $tab = 'visits';
            }
        }

        $hub = htmlspecialchars(rtrim($base, '/') . '/admin.php?section=database', ENT_QUOTES, 'UTF-8');

        $tabs = array(
            'visits' => lupo_t('admin.data.tab_visits', 'Visits'),
            'paths' => lupo_t('admin.data.tab_paths', 'Paths'),
            'referrers' => lupo_t('admin.data.tab_referrers', 'Referrers'),
        );

        $html = '<div class="admin-data-hub">';
        $html .= '<p class="admin-section-description">' . htmlspecialchars(lupo_t('admin.data.intro', 'Silent-harvest analytics (read-only). Deeper slices use the Data section in the sidebar.'), ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '<nav class="admin-data-tabs" aria-label="' . htmlspecialchars(lupo_t('admin.data.tabs_aria', 'Data views'), ENT_QUOTES, 'UTF-8') . '">';
        foreach ($tabs as $key => $label) {
            $href = $hub . '&amp;data_tab=' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
            $active = ($tab === $key) ? ' is-active' : '';
            $html .= '<a class="admin-data-tab' . $active . '" href="' . $href . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
        }
        $html .= '</nav>';

        $visits_t = $prefix . 'visits';
        $paths_t = $prefix . 'paths';
        $refd_t = $prefix . 'referers_daily';

        if ($tab === 'visits') {
            $html .= self::renderVisits($db, $prefix, $base);
        } elseif ($tab === 'paths') {
            $html .= self::renderPathsExplorer($db, $prefix, $base);
        } else {
            $html .= self::renderReferrersDaily($db, $refd_t);
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Crafty data.php tab=3 style: Top URLs for month/year, View path (tab 4), graph pop-out.
     * Legacy: livehelp_visits_monthly (recno, pageurl, directvisits) -> lupo_visits aggregated by path_url.
     *
     * @param object $db
     * @param string $prefix
     * @param string $base
     * @return string
     */
    private static function renderVisits($db, $prefix, $base)
    {
        $table = $prefix . 'visits';
        $y = (int) gmdate('Y');
        $m = (int) gmdate('n');
        $visitDefaults = !isset($_GET['visit_year']) && !isset($_GET['visit_month']);
        if ($visitDefaults) {
            $lm = self::dataHubLatestVisitMonth($db, $table);
            if ($lm !== null) {
                $y = $lm['y'];
                $m = $lm['m'];
            }
        }
        if (isset($_GET['visit_year']) && is_numeric($_GET['visit_year'])) {
            $yy = (int) $_GET['visit_year'];
            if ($yy >= 2000 && $yy <= 2100) {
                $y = $yy;
            }
        }
        if (isset($_GET['visit_month']) && is_numeric($_GET['visit_month'])) {
            $mm = (int) $_GET['visit_month'];
            if ($mm >= 1 && $mm <= 12) {
                $m = $mm;
            }
        }
        $actorId = 0;
        if (isset($_GET['visit_actor']) && is_numeric($_GET['visit_actor'])) {
            $actorId = (int) $_GET['visit_actor'];
            if ($actorId < 0) {
                $actorId = 0;
            }
        }

        $bounds = self::pathsMonthYmdhisBounds($y, $m);
        $startYmdhis = $bounds['start'];
        $endExclusive = $bounds['end_exclusive'];

        $hub = rtrim($base, '/') . '/admin.php';

        $html = '<div class="admin-data-panel admin-data-visits">';
        $html .= '<h2 class="admin-data-panel-title">' . htmlspecialchars(lupo_t('admin.data.visits_title', 'Top URLs'), ENT_QUOTES, 'UTF-8') . '</h2>';
        $html .= '<p class="admin-muted">' . htmlspecialchars(lupo_t('admin.data.visits_intro', 'Counts from lupo_visits for the selected month (Crafty: livehelp_visits_monthly directvisits).'), ENT_QUOTES, 'UTF-8') . '</p>';

        $html .= '<form class="admin-data-paths-form" method="get" action="' . htmlspecialchars($hub, ENT_QUOTES, 'UTF-8') . '">';
        $html .= '<input type="hidden" name="section" value="database" />';
        $html .= '<input type="hidden" name="data_tab" value="visits" />';
        $html .= '<label class="admin-data-paths-label"><span>' . htmlspecialchars(lupo_t('admin.data.paths_month', 'Month'), ENT_QUOTES, 'UTF-8') . '</span> ';
        $html .= '<select name="visit_month" onchange="this.form.submit()">';
        for ($mi = 1; $mi <= 12; $mi++) {
            $sel = ($mi === $m) ? ' selected="selected"' : '';
            $html .= '<option value="' . $mi . '"' . $sel . '>' . sprintf('%02d', $mi) . '</option>';
        }
        $html .= '</select></label> ';
        $html .= '<label class="admin-data-paths-label"><span>' . htmlspecialchars(lupo_t('admin.data.paths_year', 'Year'), ENT_QUOTES, 'UTF-8') . '</span> ';
        $html .= '<select name="visit_year" onchange="this.form.submit()">';
        for ($yi = $y - 5; $yi <= $y + 1; $yi++) {
            if ($yi < 2000) {
                continue;
            }
            $sel = ($yi === $y) ? ' selected="selected"' : '';
            $html .= '<option value="' . $yi . '"' . $sel . '>' . $yi . '</option>';
        }
        $html .= '</select></label> ';
        $html .= '<label class="admin-data-paths-label"><span>' . htmlspecialchars(lupo_t('admin.data.visits_actor', 'Actor'), ENT_QUOTES, 'UTF-8') . '</span> ';
        $html .= '<select name="visit_actor" onchange="this.form.submit()">';
        $html .= '<option value="0"' . ($actorId === 0 ? ' selected="selected"' : '') . '>' . htmlspecialchars(lupo_t('admin.data.visits_actor_all', 'All'), ENT_QUOTES, 'UTF-8') . '</option>';
        $actorSql = "SELECT DISTINCT actor_id FROM {$table}
                     WHERE is_deleted = 0 AND actor_id IS NOT NULL AND actor_id > 0
                       AND created_ymdhis >= :as1 AND created_ymdhis < :as2
                     ORDER BY actor_id ASC
                     LIMIT 60";
        $actorRows = $db->fetchAll($actorSql, array('as1' => $startYmdhis, 'as2' => $endExclusive));
        foreach ($actorRows as $ar) {
            if (!isset($ar['actor_id'])) {
                continue;
            }
            $aid = (int) $ar['actor_id'];
            if ($aid < 1) {
                continue;
            }
            $sel = ($aid === $actorId) ? ' selected="selected"' : '';
            $html .= '<option value="' . $aid . '"' . $sel . '>' . htmlspecialchars((string) $aid, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        $html .= '</select></label>';
        $html .= '</form>';

        $sql = "SELECT path_url, transition_metadata, actor_id
                FROM {$table}
                WHERE is_deleted = 0
                  AND created_ymdhis >= :v_start AND created_ymdhis < :v_end
                  AND (transition_metadata IS NULL OR transition_metadata = ''
                    OR transition_metadata NOT LIKE :ex_daily)";
        $rawRows = $db->fetchAll($sql, array(
            'v_start' => $startYmdhis,
            'v_end' => $endExclusive,
            'ex_daily' => '%livehelp_visits_daily%',
        ));

        $hasMonthlyInMonth = false;
        foreach ($rawRows as $rr) {
            $mj = isset($rr['transition_metadata']) ? (string) $rr['transition_metadata'] : '';
            if (strpos($mj, 'livehelp_visits_monthly') !== false) {
                $hasMonthlyInMonth = true;
                break;
            }
        }

        $aggMap = array();
        foreach ($rawRows as $rr) {
            $mj = isset($rr['transition_metadata']) ? (string) $rr['transition_metadata'] : '';
            if ($hasMonthlyInMonth && strpos($mj, 'livehelp_visit_track') !== false) {
                continue;
            }
            if ($actorId > 0) {
                $rowActor = isset($rr['actor_id']) ? (int) $rr['actor_id'] : 0;
                $meta = json_decode($mj, true);
                $dept = (is_array($meta) && isset($meta['legacy_department'])) ? (int) $meta['legacy_department'] : 0;
                if ($rowActor !== $actorId && $dept !== $actorId) {
                    continue;
                }
            }
            $fullUrl = isset($rr['path_url']) ? trim((string) $rr['path_url']) : '';
            if ($fullUrl === '') {
                continue;
            }
            $w = self::analyticsVisitRowWeight($mj);
            if ($w < 1) {
                continue;
            }
            if (!isset($aggMap[$fullUrl])) {
                $aggMap[$fullUrl] = 0;
            }
            $aggMap[$fullUrl] += $w;
        }
        arsort($aggMap, SORT_NUMERIC);
        $rows = array();
        $n = 0;
        foreach ($aggMap as $url => $cnt) {
            $rows[] = array('path_url' => $url, 'visit_count' => $cnt);
            $n++;
            if ($n >= 100) {
                break;
            }
        }

        if (empty($rows)) {
            $html .= '<p class="admin-empty">' . htmlspecialchars(lupo_t('admin.data.visits_empty', 'No visits in this month for the selected filter.'), ENT_QUOTES, 'UTF-8') . '</p></div>';
            return $html;
        }

        $graphBase = rtrim($base, '/') . '/lupo-data-graph.php';

        $html .= '<div class="admin-data-table-wrap"><table class="admin-data-table"><thead><tr>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.visits_col_url', 'URL'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.visits_col_count', '# Visits'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.visits_col_paths', 'Paths'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.visits_col_graph', 'Graph'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($rows as $r) {
            $fullUrl = isset($r['path_url']) ? (string) $r['path_url'] : '';
            $cnt = isset($r['visit_count']) ? (int) $r['visit_count'] : 0;
            $disp = self::shortenUrl($fullUrl, 80);
            $urlCell = htmlspecialchars($disp, ENT_QUOTES, 'UTF-8');
            if ($fullUrl !== '' && (preg_match('#^https?://#i', $fullUrl) || (isset($fullUrl[0]) && $fullUrl[0] === '/'))) {
                $urlCell = '<a href="' . htmlspecialchars($fullUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . $urlCell . '</a>';
            }

            $pathParams = array(
                'section' => 'database',
                'data_tab' => 'paths',
                'path_year' => $y,
                'path_month' => $m,
                'path_from' => self::encodePathFromParam($fullUrl),
            );
            $pathHref = $hub . '?' . self::buildQueryRaw($pathParams);

            $graphParams = array(
                'visit_u' => self::encodePathFromParam($fullUrl),
                'visit_year' => $y,
                'visit_month' => $m,
                'visit_actor' => $actorId,
            );
            $graphHref = $graphBase . '?' . self::buildQueryRaw($graphParams);

            $html .= '<tr>';
            $html .= '<td>' . $urlCell . '</td>';
            $html .= '<td>' . (int) $cnt . '</td>';
            $html .= '<td><a class="admin-link" href="' . htmlspecialchars($pathHref, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(lupo_t('admin.data.visits_view_path', 'View path'), ENT_QUOTES, 'UTF-8') . '</a></td>';
            $html .= '<td><a class="admin-link" href="' . htmlspecialchars($graphHref, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars(lupo_t('admin.data.visits_graph', 'Graph'), ENT_QUOTES, 'UTF-8') . '</a></td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';
        $html .= '<p class="admin-muted admin-data-paths-note">' . htmlspecialchars(lupo_t('admin.data.visits_note', 'Graph opens in a new window (Crafty graph.php). It plots hits per calendar day in UTC for that URL.'), ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Crafty-style path explorer (data.php tab 4): month/year, "Paths from", % / clicks, drill-down.
     * Uses lupo_visits for URL-level steps (works when content IDs are unresolved). Optional lupo_paths + path_cid.
     *
     * @param object $db
     * @param string $prefix
     * @param string $base
     * @return string
     */
    private static function renderPathsExplorer($db, $prefix, $base)
    {
        $visits_t = $prefix . 'visits';
        $paths_t = $prefix . 'paths';
        $contents_t = $prefix . 'contents';

        $y = (int) gmdate('Y');
        $m = (int) gmdate('n');
        $pathDefaults = !isset($_GET['path_year']) && !isset($_GET['path_month']);
        if ($pathDefaults) {
            $lm = self::dataHubLatestVisitMonth($db, $visits_t);
            if ($lm !== null) {
                $y = $lm['y'];
                $m = $lm['m'];
            }
        }
        if (isset($_GET['path_year']) && is_numeric($_GET['path_year'])) {
            $yy = (int) $_GET['path_year'];
            if ($yy >= 2000 && $yy <= 2100) {
                $y = $yy;
            }
        }
        if (isset($_GET['path_month']) && is_numeric($_GET['path_month'])) {
            $mm = (int) $_GET['path_month'];
            if ($mm >= 1 && $mm <= 12) {
                $m = $mm;
            }
        }

        $bounds = self::pathsMonthYmdhisBounds($y, $m);
        $startYmdhis = $bounds['start'];
        $endExclusive = $bounds['end_exclusive'];

        $pathCid = 0;
        if (isset($_GET['path_cid']) && is_numeric($_GET['path_cid'])) {
            $pathCid = (int) $_GET['path_cid'];
            if ($pathCid < 0) {
                $pathCid = 0;
            }
        }

        $pathFrom = '';
        if (isset($_GET['path_from']) && is_string($_GET['path_from'])) {
            $pathFrom = self::decodePathFromParam($_GET['path_from']);
        }

        $pathPrev = '';
        if (isset($_GET['path_prev']) && is_string($_GET['path_prev'])) {
            $pathPrev = self::decodePathFromParam($_GET['path_prev']);
        }

        $pathTrailIds = array();
        if (isset($_GET['path_trail']) && is_string($_GET['path_trail'])) {
            $pathTrailIds = self::decodePathTrailParam($_GET['path_trail']);
        }

        $hub = rtrim($base, '/') . '/admin.php';

        $html = '<div class="admin-data-panel admin-data-paths">';
        $html .= '<h2 class="admin-data-panel-title">' . htmlspecialchars(lupo_t('admin.data.paths_title', 'Paths'), ENT_QUOTES, 'UTF-8') . '</h2>';

        $html .= '<form class="admin-data-paths-form" method="get" action="' . htmlspecialchars($hub, ENT_QUOTES, 'UTF-8') . '">';
        $html .= '<input type="hidden" name="section" value="database" />';
        $html .= '<input type="hidden" name="data_tab" value="paths" />';
        if ($pathFrom !== '') {
            $html .= '<input type="hidden" name="path_from" value="' . htmlspecialchars(self::encodePathFromParam($pathFrom), ENT_QUOTES, 'UTF-8') . '" />';
        }
        if ($pathPrev !== '') {
            $html .= '<input type="hidden" name="path_prev" value="' . htmlspecialchars(self::encodePathFromParam($pathPrev), ENT_QUOTES, 'UTF-8') . '" />';
        }
        if ($pathCid > 0) {
            $html .= '<input type="hidden" name="path_cid" value="' . (int) $pathCid . '" />';
        }
        if (!empty($pathTrailIds)) {
            $html .= '<input type="hidden" name="path_trail" value="' . htmlspecialchars(self::encodePathTrailParam($pathTrailIds), ENT_QUOTES, 'UTF-8') . '" />';
        }
        $html .= '<label class="admin-data-paths-label"><span>' . htmlspecialchars(lupo_t('admin.data.paths_month', 'Month'), ENT_QUOTES, 'UTF-8') . '</span> ';
        $html .= '<select name="path_month" onchange="this.form.submit()">';
        for ($mi = 1; $mi <= 12; $mi++) {
            $sel = ($mi === $m) ? ' selected="selected"' : '';
            $html .= '<option value="' . $mi . '"' . $sel . '>' . sprintf('%02d', $mi) . '</option>';
        }
        $html .= '</select></label> ';
        $html .= '<label class="admin-data-paths-label"><span>' . htmlspecialchars(lupo_t('admin.data.paths_year', 'Year'), ENT_QUOTES, 'UTF-8') . '</span> ';
        $html .= '<select name="path_year" onchange="this.form.submit()">';
        for ($yi = $y - 5; $yi <= $y + 1; $yi++) {
            if ($yi < 2000) {
                continue;
            }
            $sel = ($yi === $y) ? ' selected="selected"' : '';
            $html .= '<option value="' . $yi . '"' . $sel . '>' . $yi . '</option>';
        }
        $html .= '</select></label>';
        $html .= '</form>';

        $html .= '<div class="admin-data-paths-from"><strong>' . htmlspecialchars(lupo_t('admin.data.paths_from_label', 'Paths from:'), ENT_QUOTES, 'UTF-8') . '</strong> ';
        if ($pathCid > 0) {
            $html .= htmlspecialchars(self::contentLabelForId($db, $contents_t, $pathCid), ENT_QUOTES, 'UTF-8');
            $html .= ' <span class="admin-muted">(content_id ' . (int) $pathCid . ')</span>';
        } elseif ($pathFrom !== '') {
            $html .= '<a href="' . htmlspecialchars($pathFrom, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars(self::shortenUrl($pathFrom, 96), ENT_QUOTES, 'UTF-8') . '</a>';
        } else {
            $html .= htmlspecialchars(lupo_t('admin.data.paths_from_start', 'START of session — URLs where the first visit in this month landed (per session).'), ENT_QUOTES, 'UTF-8');
        }
        $html .= '</div>';

        $backUrl = '';
        if ($pathCid > 0) {
            if (empty($pathTrailIds)) {
                $backUrl = $hub . '?' . self::buildQueryRaw(array(
                    'section' => 'database',
                    'data_tab' => 'paths',
                    'path_year' => $y,
                    'path_month' => $m,
                ));
            } else {
                $trailCopy = $pathTrailIds;
                $parentCid = (int) array_pop($trailCopy);
                if ($parentCid > 0) {
                    $bp = array(
                        'section' => 'database',
                        'data_tab' => 'paths',
                        'path_year' => $y,
                        'path_month' => $m,
                        'path_cid' => $parentCid,
                    );
                    if (!empty($trailCopy)) {
                        $bp['path_trail'] = self::encodePathTrailParam($trailCopy);
                    }
                    $backUrl = $hub . '?' . self::buildQueryRaw($bp);
                }
            }
        } elseif ($pathFrom !== '') {
            if ($pathPrev !== '') {
                $backUrl = $hub . '?' . self::buildQueryRaw(array(
                    'section' => 'database',
                    'data_tab' => 'paths',
                    'path_year' => $y,
                    'path_month' => $m,
                    'path_from' => self::encodePathFromParam($pathPrev),
                ));
            } else {
                $backUrl = $hub . '?' . self::buildQueryRaw(array(
                    'section' => 'database',
                    'data_tab' => 'paths',
                    'path_year' => $y,
                    'path_month' => $m,
                ));
            }
        }
        if ($backUrl !== '') {
            $html .= '<p class="admin-data-paths-back"><a class="admin-link" href="' . htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(lupo_t('admin.data.paths_back', 'Back track path'), ENT_QUOTES, 'UTF-8') . '</a></p>';
        }

        $rows = array();
        $totalClicks = 0;

        if ($pathCid > 0) {
            $sql = "SELECT exitcontentid, SUM(count_num) AS clicks
                    FROM {$paths_t}
                    WHERE is_deleted = 0
                      AND entercontentid = :cid
                      AND year_num = :yr
                      AND month_num = :mo
                    GROUP BY exitcontentid
                    ORDER BY clicks DESC
                    LIMIT 100";
            $agg = $db->fetchAll($sql, array('cid' => $pathCid, 'yr' => $y, 'mo' => $m));
            foreach ($agg as $a) {
                $cl = isset($a['clicks']) ? (int) $a['clicks'] : 0;
                $totalClicks += $cl;
                $eid = isset($a['exitcontentid']) ? (int) $a['exitcontentid'] : 0;
                $curl = self::contentUrlForId($db, $contents_t, $eid);
                $label = self::contentLabelForId($db, $contents_t, $eid);
                if ($eid === 0 && $label === '') {
                    $label = lupo_t('admin.data.paths_unknown_exit', 'Unknown / unresolved');
                }
                $rows[] = array(
                    'label' => $label,
                    'href' => $curl,
                    'clicks' => $cl,
                    'next_cid' => $eid,
                    'next_url' => '',
                );
            }
        } elseif ($pathFrom === '') {
            $sql = "SELECT v.path_url AS dest_url, COUNT(*) AS cnt
                    FROM {$visits_t} v
                    WHERE v.is_deleted = 0
                      AND v.session_id IS NOT NULL
                      AND v.session_id <> 0
                      AND v.created_ymdhis >= :v_start AND v.created_ymdhis < :v_end
                      AND NOT EXISTS (
                        SELECT 1 FROM {$visits_t} v0
                        WHERE v0.session_id = v.session_id AND v0.is_deleted = 0
                          AND v0.created_ymdhis >= :v0_start AND v0.created_ymdhis < :v0_end
                          AND (v0.created_ymdhis < v.created_ymdhis
                            OR (v0.created_ymdhis = v.created_ymdhis AND v0.visit_id < v.visit_id))
                      )
                    GROUP BY v.path_url
                    ORDER BY cnt DESC
                    LIMIT 100";
            $agg = $db->fetchAll($sql, array(
                'v_start' => $startYmdhis,
                'v_end' => $endExclusive,
                'v0_start' => $startYmdhis,
                'v0_end' => $endExclusive,
            ));
            foreach ($agg as $a) {
                $cl = isset($a['cnt']) ? (int) $a['cnt'] : 0;
                $totalClicks += $cl;
                $url = isset($a['dest_url']) ? (string) $a['dest_url'] : '';
                $rows[] = array(
                    'label' => $url,
                    'href' => $url,
                    'clicks' => $cl,
                    'next_cid' => 0,
                    'next_url' => $url,
                );
            }
        } else {
            $sql = "SELECT v2.path_url AS dest_url, COUNT(*) AS cnt
                    FROM {$visits_t} v1
                    INNER JOIN {$visits_t} v2 ON v1.session_id = v2.session_id
                      AND v2.created_ymdhis > v1.created_ymdhis
                      AND v1.is_deleted = 0 AND v2.is_deleted = 0
                      AND v1.session_id IS NOT NULL
                      AND v1.session_id <> 0
                    WHERE v1.path_url = :from_url
                      AND v1.created_ymdhis >= :s_start AND v1.created_ymdhis < :s_end
                      AND NOT EXISTS (
                        SELECT 1 FROM {$visits_t} v3
                        WHERE v3.session_id = v1.session_id AND v3.is_deleted = 0
                          AND v3.created_ymdhis > v1.created_ymdhis
                          AND v3.created_ymdhis < v2.created_ymdhis
                      )
                    GROUP BY v2.path_url
                    ORDER BY cnt DESC
                    LIMIT 100";
            $agg = $db->fetchAll($sql, array(
                'from_url' => $pathFrom,
                's_start' => $startYmdhis,
                's_end' => $endExclusive,
            ));
            foreach ($agg as $a) {
                $cl = isset($a['cnt']) ? (int) $a['cnt'] : 0;
                $totalClicks += $cl;
                $url = isset($a['dest_url']) ? (string) $a['dest_url'] : '';
                $rows[] = array(
                    'label' => $url,
                    'href' => $url,
                    'clicks' => $cl,
                    'next_cid' => 0,
                    'next_url' => $url,
                );
            }
        }

        if ($totalClicks < 1) {
            $totalClicks = 1;
        }

        if (empty($rows)) {
            $html .= '<p class="admin-empty">' . htmlspecialchars(lupo_t('admin.data.paths_empty', 'No path data for this month (or no matching sessions).'), ENT_QUOTES, 'UTF-8') . '</p></div>';
            return $html;
        }

        $html .= '<div class="admin-data-table-wrap"><table class="admin-data-table admin-data-paths-table"><thead><tr>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.paths_col_to', 'Clicks to'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.paths_col_pct', '%'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.paths_col_clicks', '# Clicks'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.paths_col_graph', 'Graph'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '<th>' . htmlspecialchars(lupo_t('admin.data.paths_col_action', 'Drill down'), ENT_QUOTES, 'UTF-8') . '</th>';
        $html .= '</tr></thead><tbody>';

        foreach ($rows as $r) {
            $cl = (int) $r['clicks'];
            $pct = ($totalClicks > 0) ? (($cl * 100.0) / $totalClicks) : 0.0;
            $disp = isset($r['label']) ? (string) $r['label'] : '';
            if ($disp === '') {
                $disp = lupo_t('admin.data.paths_blank_url', '(empty URL)');
            }
            $linkUrl = isset($r['href']) ? (string) $r['href'] : '';
            $toCell = htmlspecialchars(self::shortenUrl($disp, 72), ENT_QUOTES, 'UTF-8');
            if ($linkUrl !== '' && (preg_match('#^https?://#i', $linkUrl) || (isset($linkUrl[0]) && $linkUrl[0] === '/'))) {
                $toCell = '<a href="' . htmlspecialchars($linkUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . $toCell . '</a>';
            } else {
                $toCell = htmlspecialchars(self::shortenUrl($disp, 72), ENT_QUOTES, 'UTF-8');
            }

            $barW = (int) round(min(100, max(0, $pct)));
            $graphCell = '<span class="admin-data-paths-bar" title="' . htmlspecialchars(sprintf('%.2f%%', $pct), ENT_QUOTES, 'UTF-8') . '"><span class="admin-data-paths-bar-fill" style="width:' . $barW . '%"></span></span>';

            $viewParams = array();
            if ($pathCid > 0 && isset($r['next_cid']) && (int) $r['next_cid'] > 0) {
                $nextTrail = $pathTrailIds;
                $nextTrail[] = $pathCid;
                $viewParams = array(
                    'section' => 'database',
                    'data_tab' => 'paths',
                    'path_year' => $y,
                    'path_month' => $m,
                    'path_cid' => (int) $r['next_cid'],
                    'path_trail' => self::encodePathTrailParam($nextTrail),
                );
            } elseif (isset($r['next_url']) && $r['next_url'] !== '') {
                $viewParams = array(
                    'section' => 'database',
                    'data_tab' => 'paths',
                    'path_year' => $y,
                    'path_month' => $m,
                    'path_from' => self::encodePathFromParam($r['next_url']),
                );
                if ($pathFrom !== '') {
                    $viewParams['path_prev'] = self::encodePathFromParam($pathFrom);
                }
            }

            $viewLink = '';
            if (!empty($viewParams)) {
                $viewUrl = $hub . '?' . self::buildQueryRaw($viewParams);
                $viewLink = '<a class="admin-link" href="' . htmlspecialchars($viewUrl, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars(lupo_t('admin.data.paths_view', 'View path'), ENT_QUOTES, 'UTF-8') . '</a>';
            } else {
                $viewLink = '<span class="admin-muted">—</span>';
            }

            $html .= '<tr>';
            $html .= '<td>' . $toCell . '</td>';
            $html .= '<td><strong>' . htmlspecialchars(sprintf('%.2f', $pct), ENT_QUOTES, 'UTF-8') . '</strong></td>';
            $html .= '<td>' . (int) $cl . '</td>';
            $html .= '<td>' . $graphCell . '</td>';
            $html .= '<td>' . $viewLink . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';
        $html .= '<p class="admin-muted admin-data-paths-note">' . htmlspecialchars(lupo_t('admin.data.paths_note', 'Percentages are shares of the listed next-step total for this month. Graph is a simple bar (no legacy graph.php).'), ENT_QUOTES, 'UTF-8') . '</p>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @param int $year
     * @param int $month 1-12
     * @return array start (bigint string), end_exclusive (bigint string)
     */
    /**
     * Latest calendar month that has any visit row (for default month/year when GET is empty).
     *
     * @param object $db
     * @param string $visitsTable prefixed table name
     * @return array|null array('y'=>int,'m'=>int) or null
     */
    private static function dataHubLatestVisitMonth($db, $visitsTable)
    {
        $sql = "SELECT created_ymdhis AS cy FROM {$visitsTable}
                WHERE is_deleted = 0 AND created_ymdhis IS NOT NULL
                ORDER BY created_ymdhis DESC
                LIMIT 1";
        $row = $db->fetchRow($sql, array());
        if (!$row || !isset($row['cy'])) {
            return null;
        }
        $cy = (string) $row['cy'];
        if (strlen($cy) < 8) {
            return null;
        }
        $yy = (int) substr($cy, 0, 4);
        $mm = (int) substr($cy, 4, 2);
        if ($yy < 2000 || $yy > 2100 || $mm < 1 || $mm > 12) {
            return null;
        }
        return array('y' => $yy, 'm' => $mm);
    }

    /**
     * Weight for one lupo_visits row in analytics (legacy_directvisits when present, else 1).
     *
     * @param string $metaJson transition_metadata column
     * @return int
     */
    private static function analyticsVisitRowWeight($metaJson)
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

    private static function pathsMonthYmdhisBounds($year, $month)
    {
        $start = sprintf('%04d%02d01000000', $year, $month);
        if ($month === 12) {
            $ny = $year + 1;
            $nm = 1;
        } else {
            $ny = $year;
            $nm = $month + 1;
        }
        $endExclusive = sprintf('%04d%02d01000000', $ny, $nm);
        return array('start' => $start, 'end_exclusive' => $endExclusive);
    }

    /**
     * @param array $params
     * @return string query string with & for use inside href after htmlspecialchars
     */
    private static function buildQueryRaw($params)
    {
        $parts = array();
        foreach ($params as $k => $v) {
            if ($v === null || $v === '') {
                continue;
            }
            $parts[] = rawurlencode((string) $k) . '=' . rawurlencode((string) $v);
        }
        return implode('&', $parts);
    }

    /**
     * @param string $raw from $_GET['path_from'] or path_prev
     * @return string decoded URL or empty
     */
    private static function decodePathFromParam($raw)
    {
        $s = trim((string) $raw);
        if ($s === '') {
            return '';
        }
        $bin = base64_decode($s, true);
        if ($bin === false || $bin === '') {
            return '';
        }
        if (strlen($bin) > 2048) {
            return '';
        }
        if (strpos($bin, "\0") !== false) {
            return '';
        }
        return $bin;
    }

    /**
     * @param string $url
     * @return string base64 (no line breaks)
     */
    private static function encodePathFromParam($url)
    {
        return base64_encode((string) $url);
    }

    /**
     * @param string $url
     * @param int $max
     * @return string
     */
    private static function shortenUrl($url, $max)
    {
        $u = (string) $url;
        if (strlen($u) <= $max) {
            return $u;
        }
        return substr($u, 0, max(0, $max - 3)) . '...';
    }

    /**
     * @param object $db
     * @param string $contents_t prefixed table
     * @param int $contentId
     * @return string label
     */
    private static function contentLabelForId($db, $contents_t, $contentId)
    {
        if ($contentId < 1) {
            return '';
        }
        $sql = "SELECT content_url, source_url, title, slug
                FROM {$contents_t}
                WHERE content_id = :id AND is_deleted = 0
                LIMIT 1";
        $row = $db->fetchRow($sql, array('id' => $contentId));
        if (!$row) {
            return '';
        }
        $u = '';
        if (isset($row['content_url']) && trim((string) $row['content_url']) !== '') {
            $u = trim((string) $row['content_url']);
        } elseif (isset($row['source_url']) && trim((string) $row['source_url']) !== '') {
            $u = trim((string) $row['source_url']);
        }
        if ($u !== '') {
            return $u;
        }
        if (isset($row['title']) && trim((string) $row['title']) !== '') {
            return trim((string) $row['title']);
        }
        if (isset($row['slug']) && trim((string) $row['slug']) !== '') {
            return trim((string) $row['slug']);
        }
        return '';
    }

    /**
     * HTTP(S) or site-relative URL for a content row (link column).
     *
     * @param object $db
     * @param string $contents_t
     * @param int $contentId
     * @return string
     */
    private static function contentUrlForId($db, $contents_t, $contentId)
    {
        if ($contentId < 1) {
            return '';
        }
        $sql = "SELECT content_url, source_url
                FROM {$contents_t}
                WHERE content_id = :id AND is_deleted = 0
                LIMIT 1";
        $row = $db->fetchRow($sql, array('id' => $contentId));
        if (!$row) {
            return '';
        }
        foreach (array('content_url', 'source_url') as $col) {
            if (isset($row[$col])) {
                $u = trim((string) $row[$col]);
                if ($u !== '' && (preg_match('#^https?://#i', $u) || (isset($u[0]) && $u[0] === '/'))) {
                    return $u;
                }
            }
        }
        return '';
    }

    /**
     * @param array $ids
     * @return string
     */
    private static function encodePathTrailParam($ids)
    {
        $clean = array();
        foreach ((array) $ids as $id) {
            $i = (int) $id;
            if ($i > 0) {
                $clean[] = $i;
            }
        }
        if (count($clean) > 40) {
            $clean = array_slice($clean, -40);
        }
        return base64_encode(implode(',', $clean));
    }

    /**
     * @param string $raw
     * @return array
     */
    private static function decodePathTrailParam($raw)
    {
        $s = trim((string) $raw);
        if ($s === '') {
            return array();
        }
        $bin = base64_decode($s, true);
        if ($bin === false || $bin === '') {
            return array();
        }
        if (strlen($bin) > 500) {
            return array();
        }
        $out = array();
        foreach (explode(',', $bin) as $p) {
            $i = (int) trim($p);
            if ($i > 0) {
                $out[] = $i;
            }
        }
        if (count($out) > 40) {
            $out = array_slice($out, -40);
        }
        return $out;
    }

    /**
     * @param object $db
     * @param string $table
     * @return string
     */
    private static function renderReferrersDaily($db, $table)
    {
        $sql = "SELECT referers_daily_id, actor_id, referer_domain, visit_ymd, visit_count, updated_ymdhis
                FROM {$table}
                WHERE is_deleted = 0
                ORDER BY visit_ymd DESC, visit_count DESC
                LIMIT 200";
        $rows = $db->fetchAll($sql, array());
        $html = '<div class="admin-data-panel"><h2 class="admin-data-panel-title">' . htmlspecialchars(lupo_t('admin.data.panel_referrers', 'Referrers (daily rollup)'), ENT_QUOTES, 'UTF-8') . '</h2>';
        if (empty($rows)) {
            $html .= '<p class="admin-empty">' . htmlspecialchars(lupo_t('admin.data.empty', 'No rows yet.'), ENT_QUOTES, 'UTF-8') . '</p></div>';
            return $html;
        }
        $html .= '<div class="admin-data-table-wrap"><table class="admin-data-table"><thead><tr>';
        $html .= '<th>referers_daily_id</th><th>actor_id</th><th>referer_domain</th><th>visit_ymd</th><th>visit_count</th><th>updated_ymdhis</th>';
        $html .= '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars((string) $r['referers_daily_id'], ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars(isset($r['actor_id']) ? (string) $r['actor_id'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars(isset($r['referer_domain']) ? (string) $r['referer_domain'] : '', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars((string) $r['visit_ymd'], ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars((string) $r['visit_count'], ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars((string) $r['updated_ymdhis'], ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div></div>';
        return $html;
    }
}
