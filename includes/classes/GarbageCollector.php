<?php
// includes/classes/GarbageCollector.php

class GarbageCollector {
    private $db;
    private $deleted = 0;
    private $config;
    private $max_per_run;
    private $prefix;

    const DEFAULT_MAX_PER_RUN = 10000;

    /**
     * Packed UTC instant $days calendar-days before now (for created_ymdhis cutoffs).
     */
    private function cutoffPackedDaysAgo($days) {
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
        }
        return (string) timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), (int) $days * 86400);
    }

    /**
     * Packed UTC instant $hours before now.
     */
    private function cutoffPackedHoursAgo($hours) {
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
        }
        return (string) timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), (int) $hours * 3600);
    }

    /**
     * YYYYMMDD for date_ymd columns (integer/string eight digits).
     */
    private function cutoffYmdDaysAgo($days) {
        $packed = (int) $this->cutoffPackedDaysAgo($days);
        $s = str_pad((string) $packed, 14, '0', STR_PAD_LEFT);
        return substr($s, 0, 8);
    }

    /**
     * YYYY-MM-DD for visit_date style columns.
     */
    private function cutoffSqlDateDaysAgo($days) {
        $packed = (int) $this->cutoffPackedDaysAgo($days);
        $s = str_pad((string) $packed, 14, '0', STR_PAD_LEFT);
        return substr($s, 0, 4) . '-' . substr($s, 4, 2) . '-' . substr($s, 6, 2);
    }

    public function __construct() {
        $this->db = DatabaseFactory::getConnection();
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->loadConfig();
        $this->max_per_run = isset($this->config['gc_max_per_run'])
            ? $this->config['gc_max_per_run']
            : self::DEFAULT_MAX_PER_RUN;
    }

    /**
     * Execute parameterized SQL; return affected row count (UPDATE/INSERT/DELETE).
     */
    private function sqlExecute($sql, $params = array()) {
        $stmt = $this->db->query($sql, $params);
        try {
            return $stmt->rowCount();
        } finally {
            $stmt->closeCursor();
        }
    }

    /**
     * Main GC entry point — runs all functions
     */
    public function run() {
        $this->aggregatePathsRawToRollups();
        $this->aggregatePaths();

        $this->aggregateReferersRawToRollups();

        $this->aggregateCampaigns();
        $this->aggregateDailyVisitsFromVisits();

        $this->pruneRawVisits();
        $this->prunePathsRawAggregated();
        $this->pruneReferersRawAggregated();
        $this->prunePaths();
        $this->prunePathsRollups();
        $this->pruneReferrers();
        $this->pruneReferrersDaily();
        $this->pruneDailyVisits();

        $this->pruneCampaigns();

        $this->cleanupSessions();
        $this->pruneExpiredMemoryNodes();

        $this->optimizeTables();
        $this->logRun();
    }

    /**
     * Roll lupo_paths_raw into lupo_paths_daily and lupo_paths_monthly; mark raw rows aggregated.
     */
    private function aggregatePathsRawToRollups() {
        $tRaw = $this->prefix . 'paths_raw';
        $tDaily = $this->prefix . 'paths_daily';
        $tMonthly = $this->prefix . 'paths_monthly';

        $sql = "SELECT 
                    from_url,
                    to_url,
                    FLOOR(created_ymdhis / 1000000) AS visit_ymd,
                    COUNT(*) AS cnt
                FROM {$tRaw}
                WHERE is_aggregated = 0 AND is_deleted = 0
                GROUP BY from_url, to_url, FLOOR(created_ymdhis / 1000000)
                LIMIT 500";

        $rows = $this->db->fetchAll($sql);
        $now = gmdate('YmdHis');

        foreach ($rows as $row) {
            $visitYmd = (int) $row['visit_ymd'];
            if ($visitYmd <= 0) {
                continue;
            }
            $dateYm = (int) floor($visitYmd / 100);
            $fromUrl = $row['from_url'];
            $toUrl = $row['to_url'];
            $cnt = (int) $row['cnt'];

            $fromContentId = $this->getContentIdFromUrl($fromUrl);
            $toContentId = $this->getContentIdFromUrl($toUrl);

            $this->upsertPathsDailyOrMonthly(
                $tDaily,
                $visitYmd,
                $fromUrl,
                $toUrl,
                $fromContentId,
                $toContentId,
                $cnt,
                $now
            );
            $this->upsertPathsDailyOrMonthly(
                $tMonthly,
                $dateYm,
                $fromUrl,
                $toUrl,
                $fromContentId,
                $toContentId,
                $cnt,
                $now,
                true
            );

            $fromKey = ($fromUrl === null || $fromUrl === '') ? '' : $fromUrl;
            $toKey = ($toUrl === null || $toUrl === '') ? '' : $toUrl;
            $markSql = "UPDATE {$tRaw} SET is_aggregated = 1
                WHERE is_aggregated = 0 AND is_deleted = 0
                  AND FLOOR(created_ymdhis / 1000000) = ?
                  AND COALESCE(from_url, '') = ?
                  AND COALESCE(to_url, '') = ?";
            $this->sqlExecute($markSql, array($visitYmd, $fromKey, $toKey));
        }
    }

    /**
     * @param string $table paths_daily or paths_monthly
     * @param int    $dateKey date_ymd (YYYYMMDD) or date_ym (YYYYMM)
     * @param bool   $monthly When true, $dateKey is date_ym and column is date_ym
     */
    private function upsertPathsDailyOrMonthly($table, $dateKey, $fromUrl, $toUrl, $fromCid, $toCid, $cnt, $now, $monthly = false) {
        $dateCol = $monthly ? 'date_ym' : 'date_ymd';
        $pkCol = $monthly ? 'path_monthly_id' : 'path_daily_id';

        $fromKey = ($fromUrl === null || $fromUrl === '') ? '' : $fromUrl;
        $toKey = ($toUrl === null || $toUrl === '') ? '' : $toUrl;
        $findSql = "SELECT {$pkCol} FROM {$table}
            WHERE {$dateCol} = ? AND is_deleted = 0
              AND COALESCE(from_url, '') = ?
              AND COALESCE(to_url, '') = ?
            LIMIT 1";
        $existing = $this->db->fetchRow($findSql, array($dateKey, $fromKey, $toKey));

        if ($existing !== null && isset($existing[$pkCol])) {
            $upd = "UPDATE {$table} SET count_num = count_num + ?, updated_ymdhis = ?
                WHERE {$pkCol} = ? AND is_deleted = 0";
            $this->sqlExecute($upd, array($cnt, $now, $existing[$pkCol]));
            return;
        }

        if (!class_exists('IdGenerator', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }
        $newId = IdGenerator::generate();
        $ins = "INSERT INTO {$table}
            ({$pkCol}, from_url, to_url, from_content_id, to_content_id, count_num, {$dateCol}, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NULL)";
        $this->sqlExecute($ins, array(
            $newId,
            $fromUrl,
            $toUrl,
            $fromCid,
            $toCid,
            $cnt,
            $dateKey,
            $now,
            $now
        ));
    }

    /**
     * Roll lupo_referers_raw into lupo_referers_daily and lupo_referers; mark raw rows aggregated.
     */
    private function aggregateReferersRawToRollups() {
        $tRaw = $this->prefix . 'referers_raw';
        $tDaily = $this->prefix . 'referers_daily';
        $tRef = $this->prefix . 'referers';

        $batch = $this->db->fetchAll(
            "SELECT referer_raw_id, content_id, referer_url, referer_domain, actor_id, created_ymdhis
             FROM {$tRaw} WHERE is_aggregated = 0 AND is_deleted = 0 ORDER BY created_ymdhis ASC LIMIT 1000"
        );
        if (empty($batch)) {
            return;
        }

        if (!class_exists('IdGenerator', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }
        $now = gmdate('YmdHis');

        $daily = array();
        $detail = array();
        $ids = array();

        foreach ($batch as $r) {
            $ymd = (int) floor((int) $r['created_ymdhis'] / 1000000);
            if ($ymd <= 0) {
                $this->sqlExecute(
                    "UPDATE {$tRaw} SET is_aggregated = 1 WHERE referer_raw_id = ?",
                    array($r['referer_raw_id'])
                );
                continue;
            }

            $ids[] = $r['referer_raw_id'];

            $aid = (isset($r['actor_id']) && $r['actor_id'] !== null && $r['actor_id'] !== '') ? (int) $r['actor_id'] : 0;
            $dom = isset($r['referer_domain']) ? (string) $r['referer_domain'] : '';
            $dk = json_encode(array('a' => $aid, 'd' => $dom, 'y' => $ymd));
            if (!isset($daily[$dk])) {
                $daily[$dk] = 0;
            }
            $daily[$dk]++;

            $cid = (isset($r['content_id']) && $r['content_id'] !== null && $r['content_id'] !== '') ? (int) $r['content_id'] : 0;
            $rurl = isset($r['referer_url']) ? (string) $r['referer_url'] : '';
            $ek = json_encode(array('c' => $cid, 'a' => $aid, 'u' => $rurl, 'd' => $dom, 'y' => $ymd));
            if (!isset($detail[$ek])) {
                $detail[$ek] = 0;
            }
            $detail[$ek]++;
        }

        foreach ($daily as $dk => $cnt) {
            $o = json_decode($dk, true);
            if (!is_array($o) || !isset($o['y'])) {
                continue;
            }
            $aid = (int) $o['a'];
            $dom = isset($o['d']) ? (string) $o['d'] : '';
            $ymd = (int) $o['y'];
            $actorParam = $aid === 0 ? null : $aid;
            $domParam = $dom === '' ? null : $dom;

            $dupDaily = "INSERT INTO {$tDaily}
                (referers_daily_id, actor_id, referer_domain, visit_ymd, visit_count, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                VALUES (?, ?, ?, ?, ?, ?, ?, 0, NULL)
                ON DUPLICATE KEY UPDATE
                visit_count = visit_count + VALUES(visit_count),
                updated_ymdhis = VALUES(updated_ymdhis)";
            $this->sqlExecute($dupDaily, array(
                IdGenerator::generate(),
                $actorParam,
                $domParam,
                $ymd,
                (int) $cnt,
                $now,
                $now
            ));
        }

        foreach ($detail as $ek => $cnt) {
            $o = json_decode($ek, true);
            if (!is_array($o) || !isset($o['y'])) {
                continue;
            }
            $cid = (int) $o['c'];
            $aid = (int) $o['a'];
            $ymd = (int) $o['y'];
            $rurl = isset($o['u']) ? (string) $o['u'] : '';
            $dom = isset($o['d']) ? (string) $o['d'] : '';

            $find = "SELECT referer_id, visits FROM {$tRef}
                WHERE date_ymd = ? AND content_id = ? AND actor_id = ?
                  AND COALESCE(referer_url, '') = ?
                  AND COALESCE(referer_domain, '') = ?
                LIMIT 1";
            $ex = $this->db->fetchRow($find, array($ymd, $cid, $aid, $rurl, $dom));

            if ($ex !== null) {
                $this->sqlExecute(
                    "UPDATE {$tRef} SET visits = visits + ? WHERE referer_id = ?",
                    array((int) $cnt, $ex['referer_id'])
                );
            } else {
                $this->sqlExecute(
                    "INSERT INTO {$tRef}
                    (referer_id, content_id, actor_id, referer_url, referer_domain, referer_path, referer_content_id, date_ymd, visits, depth, metadata_json)
                    VALUES (?, ?, ?, ?, ?, NULL, NULL, ?, ?, 0, NULL)",
                    array(
                        IdGenerator::generate(),
                        $cid,
                        $aid,
                        $rurl === '' ? null : $rurl,
                        $dom === '' ? null : $dom,
                        $ymd,
                        (int) $cnt
                    )
                );
            }
        }

        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $this->sqlExecute(
                "UPDATE {$tRaw} SET is_aggregated = 1 WHERE referer_raw_id IN ({$placeholders})",
                $ids
            );
        }
    }

    /**
     * Aggregate raw visits into lupo_paths (content-id transitions from enter/exit when present).
     */
    private function aggregatePaths() {
        $tVisits = $this->prefix . 'visits';
        $tPaths = $this->prefix . 'paths';

        $sql = "SELECT v1.visit_id, v1.path_url as enter, v2.path_url as exit, 
                       v1.created_ymdhis, v1.session_id
                FROM {$tVisits} v1
                JOIN {$tVisits} v2 ON v1.session_id = v2.session_id 
                  AND v1.created_ymdhis < v2.created_ymdhis
                  AND v1.is_processed = 0
                WHERE NOT EXISTS (
                    SELECT 1 FROM {$tVisits} v3 
                    WHERE v1.session_id = v3.session_id 
                      AND v1.created_ymdhis < v3.created_ymdhis 
                      AND v3.created_ymdhis < v2.created_ymdhis
                )
                LIMIT 5000";

        $stmt = $this->db->query($sql);
        try {
            foreach ($stmt as $visit) {
                $year = (int) substr($visit['created_ymdhis'], 0, 4);
                $month = (int) substr($visit['created_ymdhis'], 4, 2);
                $day = (int) substr($visit['created_ymdhis'], 6, 2);

                $enter_id = $this->getContentIdFromUrl($visit['enter']);
                $exit_id = $this->getContentIdFromUrl($visit['exit']);
                $ts = $visit['created_ymdhis'];

                $findP = "SELECT path_id FROM {$tPaths}
                    WHERE (entercontentid <=> ?) AND (exitcontentid <=> ?)
                      AND year_num = ? AND month_num = ? AND day_num = ? AND is_deleted = 0
                    LIMIT 1";
                $existingP = $this->db->fetchRow($findP, array($enter_id, $exit_id, $year, $month, $day));

                if ($existingP !== null && isset($existingP['path_id'])) {
                    $this->sqlExecute(
                        "UPDATE {$tPaths} SET count_num = count_num + 1, updated_ymdhis = ? WHERE path_id = ?",
                        array($ts, $existingP['path_id'])
                    );
                } else {
                    if (!class_exists('IdGenerator', false)) {
                        require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
                    }
                    $this->sqlExecute(
                        "INSERT INTO {$tPaths}
                        (path_id, entercontentid, exitcontentid, year_num, month_num, day_num,
                         count_num, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                        VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, 0, NULL)",
                        array(
                            IdGenerator::generate(),
                            $enter_id,
                            $exit_id,
                            $year,
                            $month,
                            $day,
                            $ts,
                            $ts
                        )
                    );
                }

                $this->sqlExecute(
                    "UPDATE {$tVisits} SET is_processed = 1 WHERE visit_id = ?",
                    array($visit['visit_id'])
                );
            }
        } finally {
            $stmt->closeCursor();
        }
    }

    /**
     * Per-hit visits -> lupo_visits_daily (actor_id + visit_ymd). Uses rows not yet rolled (is_processed = 0).
     */
    private function aggregateDailyVisitsFromVisits() {
        $tVisits = $this->prefix . 'visits';
        $tDaily = $this->prefix . 'visits_daily';

        $sql = "SELECT 
                    COALESCE(actor_id, 0) AS aid,
                    FLOOR(created_ymdhis / 1000000) AS visit_ymd,
                    COUNT(*) AS total_visits,
                    COUNT(DISTINCT session_id) AS unique_sessions
                FROM {$tVisits}
                WHERE is_processed = 0 AND is_deleted = 0
                GROUP BY COALESCE(actor_id, 0), FLOOR(created_ymdhis / 1000000)
                LIMIT 200";

        $rows = $this->db->fetchAll($sql);
        $now = gmdate('YmdHis');

        foreach ($rows as $day) {
            $visitYmd = (int) $day['visit_ymd'];
            if ($visitYmd <= 0) {
                continue;
            }
            $aid = (int) $day['aid'];
            $actorParam = ($aid === 0) ? null : $aid;

            $dup = "INSERT INTO {$tDaily}
                (visits_daily_id, actor_id, visit_ymd, total_visits, unique_sessions, avg_duration_seconds, bounce_count, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                VALUES (?, ?, ?, ?, ?, 0, 0, ?, ?, 0, NULL)
                ON DUPLICATE KEY UPDATE
                total_visits = total_visits + VALUES(total_visits),
                unique_sessions = unique_sessions + VALUES(unique_sessions),
                updated_ymdhis = VALUES(updated_ymdhis)";

            if (!class_exists('IdGenerator', false)) {
                require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
            }
            $this->sqlExecute($dup, array(
                IdGenerator::generate(),
                $actorParam,
                $visitYmd,
                (int) $day['total_visits'],
                (int) $day['unique_sessions'],
                $now,
                $now
            ));

            $mark = "UPDATE {$tVisits} SET is_processed = 1
                WHERE is_processed = 0 AND is_deleted = 0
                  AND COALESCE(actor_id, 0) = ?
                  AND FLOOR(created_ymdhis / 1000000) = ?";
            $this->sqlExecute($mark, array($aid, $visitYmd));
        }
    }

    /**
     * Prune old paths (content-id aggregate)
     */
    private function prunePaths() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_path_retention_days'])
            ? $this->config['gc_path_retention_days'] : 90;
        $cutoff = $this->cutoffPackedDaysAgo($retention);

        $this->prune($this->prefix . 'paths', 'created_ymdhis < ?', array($cutoff));
    }

    /**
     * Prune old referrers (detail table)
     */
    private function pruneReferrers() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_referrer_retention_days'])
            ? $this->config['gc_referrer_retention_days'] : 365;
        $cutoffYmd = (int) $this->cutoffYmdDaysAgo($retention);

        $this->prune($this->prefix . 'referers', 'date_ymd < ?', array($cutoffYmd));
    }

    /**
     * Soft-delete aggregated path raw rows past retention (high-volume stream).
     */
    private function prunePathsRawAggregated() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_paths_raw_retention_days'])
            ? $this->config['gc_paths_raw_retention_days']
            : (isset($this->config['gc_visits_retention_days']) ? $this->config['gc_visits_retention_days'] : 30);
        $cutoff = $this->cutoffPackedDaysAgo($retention);

        $this->prune(
            $this->prefix . 'paths_raw',
            'is_aggregated = 1 AND created_ymdhis < ?',
            array($cutoff)
        );
    }

    /**
     * Soft-delete old URL-pair rollups (lupo_paths_daily / lupo_paths_monthly).
     */
    private function prunePathsRollups() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_path_rollup_retention_days'])
            ? $this->config['gc_path_rollup_retention_days']
            : (isset($this->config['gc_path_retention_days']) ? $this->config['gc_path_retention_days'] : 90);
        $cutoffYmd = (int) $this->cutoffYmdDaysAgo($retention);
        $cutoffYm = (int) floor($cutoffYmd / 100);

        $this->prune($this->prefix . 'paths_daily', 'date_ymd < ?', array($cutoffYmd));
        $this->prune($this->prefix . 'paths_monthly', 'date_ym < ?', array($cutoffYm));
    }

    /**
     * Soft-delete old lupo_referers_daily rows.
     */
    private function pruneReferrersDaily() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_referrers_daily_retention_days'])
            ? $this->config['gc_referrers_daily_retention_days']
            : (isset($this->config['gc_referrer_retention_days']) ? $this->config['gc_referrer_retention_days'] : 365);
        $cutoffYmd = (int) $this->cutoffYmdDaysAgo($retention);

        $this->prune($this->prefix . 'referers_daily', 'visit_ymd < ?', array($cutoffYmd));
    }

    /**
     * Soft-delete processed referer raw events past retention.
     */
    private function pruneReferersRawAggregated() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_referrers_raw_retention_days'])
            ? $this->config['gc_referrers_raw_retention_days']
            : (isset($this->config['gc_visits_retention_days']) ? $this->config['gc_visits_retention_days'] : 30);
        $cutoff = $this->cutoffPackedDaysAgo($retention);

        $this->prune(
            $this->prefix . 'referers_raw',
            'is_aggregated = 1 AND created_ymdhis < ?',
            array($cutoff)
        );
    }

    /**
     * Prune raw visits
     */
    private function pruneRawVisits() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_visits_retention_days'])
            ? $this->config['gc_visits_retention_days'] : 30;
        $cutoff = $this->cutoffPackedDaysAgo($retention);

        $this->prune($this->prefix . 'visits', 'created_ymdhis < ? AND is_processed = 1', array($cutoff));
    }

    /**
     * Prune daily visit aggregates (lupo_visits_daily.visit_ymd is YYYYMMDD int).
     */
    private function pruneDailyVisits() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_daily_visits_retention_days'])
            ? $this->config['gc_daily_visits_retention_days'] : 365;
        $cutoffYmd = (int) $this->cutoffYmdDaysAgo($retention);

        $this->prune($this->prefix . 'visits_daily', 'visit_ymd < ?', array($cutoffYmd));
    }

    /**
     * Prune campaigns
     */
    private function pruneCampaigns() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_campaign_retention_days'])
            ? $this->config['gc_campaign_retention_days'] : 365;
        $cutoff = $this->cutoffPackedDaysAgo($retention);

        $remaining = $this->max_per_run - $this->deleted;
        if ($remaining <= 0) {
            return;
        }
        $tCamp = $this->prefix . 'analytics_campaign_vars';
        $sql = "DELETE FROM {$tCamp} WHERE created_ymdhis < ? ORDER BY created_ymdhis ASC LIMIT " . (int) $remaining;
        $this->deleted += $this->sqlExecute($sql, array($cutoff));
    }

    /**
     * Clean up expired sessions
     */
    private function cleanupSessions() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_session_retention_hours'])
            ? $this->config['gc_session_retention_hours'] : 24;
        $cutoff = $this->cutoffPackedHoursAgo($retention);

        $this->prune(
            $this->prefix . 'sessions',
            'expires_ymdhis IS NOT NULL AND expires_ymdhis <> 0 AND expires_ymdhis < ?',
            array($cutoff)
        );
    }

    /**
     * Prune expired rows in lupo_memory_nodes (unified memory; replaces lupo_actor_memory).
     */
    private function pruneExpiredMemoryNodes() {
        if ($this->deleted >= $this->max_per_run) {
            return;
        }

        $retention = isset($this->config['gc_memory_retention_days'])
            ? $this->config['gc_memory_retention_days'] : 30;
        $cutoff = $this->cutoffPackedDaysAgo($retention);

        $this->prune(
            $this->prefix . 'memory_nodes',
            'expires_ymdhis > 0 AND expires_ymdhis < ?',
            array($cutoff)
        );
    }

    /**
     * Generic prune function with self-limiting
     */
    private function prune($table, $condition, $params = array()) {
        $remaining = $this->max_per_run - $this->deleted;
        if ($remaining <= 0) {
            return;
        }

        $sql = "UPDATE {$table} 
                SET is_deleted = 1, deleted_ymdhis = ? 
                WHERE {$condition} AND is_deleted = 0 
                LIMIT {$remaining}";

        $all_params = array_merge(array(gmdate('YmdHis')), $params);
        $this->deleted += $this->sqlExecute($sql, $all_params);
    }

    /**
     * Aggregate campaign data from URLs (lupo_visits.path_url).
     */
    private function aggregateCampaigns() {
        $tVisits = $this->prefix . 'visits';
        $tCamp = $this->prefix . 'analytics_campaign_vars';

        $sql = "SELECT 
                    FLOOR(created_ymdhis / 1000000) AS date_ymd_int,
                    'utm_source' AS campaign_key,
                    SUBSTRING_INDEX(SUBSTRING_INDEX(path_url, 'utm_source=', -1), '&', 1) AS campaign_value,
                    COUNT(*) AS cnt
                FROM {$tVisits}
                WHERE path_url LIKE '%utm_source=%'
                  AND is_deleted = 0
                GROUP BY FLOOR(created_ymdhis / 1000000), campaign_value
                LIMIT 5000";

        $campaigns = $this->db->fetchAll($sql);

        if (!class_exists('IdGenerator', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }

        foreach ($campaigns as $camp) {
            $ymdInt = (int) $camp['date_ymd_int'];
            if ($ymdInt <= 0) {
                continue;
            }
            $d = (string) $ymdInt;
            $year = (int) substr($d, 0, 4);
            $yearmonth = (int) substr($d, 0, 6);
            $ckey = $camp['campaign_key'];
            $cval = $camp['campaign_value'];
            $add = (int) $camp['cnt'];
            $now = gmdate('YmdHis');

            $findC = "SELECT campaign_var_id, metadata_json FROM {$tCamp}
                WHERE period = 'day' AND date_ymd = ? AND campaign_key = ?
                  AND (campaign_value <=> ?)
                LIMIT 1";
            $ex = $this->db->fetchRow($findC, array($ymdInt, $ckey, $cval));

            if ($ex !== null) {
                $meta = array();
                if (!empty($ex['metadata_json'])) {
                    $decoded = json_decode($ex['metadata_json'], true);
                    if (is_array($decoded)) {
                        $meta = $decoded;
                    }
                }
                $prev = isset($meta['count']) ? (int) $meta['count'] : 0;
                $meta['count'] = $prev + $add;
                $this->sqlExecute(
                    "UPDATE {$tCamp} SET metadata_json = ? WHERE campaign_var_id = ?",
                    array(json_encode($meta), $ex['campaign_var_id'])
                );
            } else {
                $this->sqlExecute(
                    "INSERT INTO {$tCamp}
                    (campaign_var_id, period, date_ymd, yearmonth, year, campaign_key, campaign_value, metadata_json, created_ymdhis)
                    VALUES (?, 'day', ?, ?, ?, ?, ?, ?, ?)",
                    array(
                        IdGenerator::generate(),
                        $ymdInt,
                        $yearmonth,
                        $year,
                        $ckey,
                        $cval,
                        json_encode(array('count' => $add)),
                        $now
                    )
                );
            }
        }
    }

    /**
     * Optimize tables after deletions
     */
    private function optimizeTables() {
        if ($this->deleted == 0) {
            return;
        }

        $tables = array(
            $this->prefix . 'paths',
            $this->prefix . 'paths_raw',
            $this->prefix . 'paths_daily',
            $this->prefix . 'paths_monthly',
            $this->prefix . 'referers',
            $this->prefix . 'referers_raw',
            $this->prefix . 'referers_daily',
            $this->prefix . 'visits',
            $this->prefix . 'visits_daily',
            $this->prefix . 'sessions',
            $this->prefix . 'memory_nodes'
        );

        foreach ($tables as $table) {
            $this->db->query('OPTIMIZE TABLE ' . $table);
        }
    }

    /**
     * Get content ID from URL path
     */
    private function getContentIdFromUrl($url) {
        return 0;
    }

    /**
     * Load configuration from system_config
     */
    private function loadConfig() {
        $tCfg = $this->prefix . 'system_config';
        $sql = "SELECT config_key, config_value FROM {$tCfg} 
                WHERE config_key LIKE 'gc_%'";
        $rows = $this->db->fetchAll($sql);

        foreach ($rows as $row) {
            $this->config[$row['config_key']] = $row['config_value'];
        }
    }

    /**
     * Log GC run
     */
    private function logRun() {
        $tLog = $this->prefix . 'unified_log';
        $sql = "INSERT INTO {$tLog} 
                (log_id, log_type, log_level, log_message, created_ymdhis)
                VALUES (?, 'gc', 'info', ?, ?)";

        if (!class_exists('IdGenerator', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }
        $message = sprintf('GC run completed: %d rows soft-deleted', $this->deleted);
        $this->sqlExecute($sql, array(IdGenerator::generate(), $message, gmdate('YmdHis')));
    }

    /**
     * Static method to trigger GC (called from requests)
     */
    public static function maybeRun() {
        $config = self::getGCConfig();
        $chance = isset($config['gc_execution_chance']) ? $config['gc_execution_chance'] : 1;

        if (rand(1, 100) <= $chance) {
            $gc = new self();
            $gc->run();
        }
    }

    private static function getGCConfig() {
        return array(
            'gc_execution_chance' => 1,
            'gc_path_retention_days' => 90,
            'gc_referrer_retention_days' => 365,
            'gc_visits_retention_days' => 30,
            'gc_daily_visits_retention_days' => 365,
            'gc_session_retention_hours' => 24,
            'gc_memory_retention_days' => 30,
            'gc_campaign_retention_days' => 365,
        );
    }
}
