<?php
/**
 * Admin Trust Ladder section handler.
 *
 * Renders four panels:
 *   A — Registry vs Install SQL validation (PHP port of validate_trust_ladder_registry.py)
 *   B — Live tier counts from lupo_memory_nodes / lupo_memory_edges
 *   C — Recent lupo_unified_log entries (log_type = 'trust_ladder' or 'gc')
 *   D — Seed actor registry with validateTrustLadderPk badges
 *
 * POST action: Run Staging GC (invokes StagingGcService::purge()).
 *
 * Normative references:
 *   CHRONOLOGICAL_TRUST_LADDER.md §9.1, FOR_CLAUDE_CODE_ON_PK_IDS.md §Future Work
 *   PDO: named params only; LUPO_TABLE_PREFIX; no triggers/FKs; attribution via $user.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminTrustLadderHandler
{
    // -----------------------------------------------------------------------
    // Entry point
    // -----------------------------------------------------------------------

    /**
     * @param object $db     PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base   Base URL path
     * @return string HTML
     */
    public static function render($db, $prefix, $base)
    {
        if (!class_exists('IdGenerator', false)) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }

        $html = '';

        // -----------------------------------------------------------------------
        // POST: Run Staging GC
        // -----------------------------------------------------------------------
        $gcMsg = '';
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['action']) &&
            $_POST['action'] === 'run_staging_gc'
        ) {
            if (function_exists('lupo_require_valid_csrf_token')) {
                lupo_require_valid_csrf_token();
            }

            $gcResult = self::runStagingGc($db, $prefix);
            if ($gcResult !== null) {
                $nodesP = (int) $gcResult['memory_nodes_purged'];
                $edgesP = (int) $gcResult['memory_edges_purged'];
                if (empty($gcResult['errors'])) {
                    $gcMsg = '<div class="admin-notice admin-notice-ok">Staging GC complete: '
                        . htmlspecialchars("$nodesP memory_nodes, $edgesP memory_edges purged.") . '</div>';
                    self::logAdminAction($db, $prefix, 'run_staging_gc', array(
                        'memory_nodes_purged' => $nodesP,
                        'memory_edges_purged' => $edgesP,
                    ));
                } else {
                    $errList = implode('; ', array_slice($gcResult['errors'], 0, 3));
                    $gcMsg = '<div class="admin-notice admin-notice-warn">Staging GC completed with errors: '
                        . htmlspecialchars($errList) . '</div>';
                }
            } else {
                $gcMsg = '<div class="admin-notice admin-notice-warn">StagingGcService not available.</div>';
            }
        }

        $html .= $gcMsg;

        // -----------------------------------------------------------------------
        // Panel A: Registry vs Install SQL validation
        // -----------------------------------------------------------------------
        $html .= self::panelRegistryValidation();

        // -----------------------------------------------------------------------
        // Panel B: Live tier counts
        // -----------------------------------------------------------------------
        $html .= self::panelTierStats($db, $prefix);

        // -----------------------------------------------------------------------
        // Panel C: Recent trust ladder / GC log entries
        // -----------------------------------------------------------------------
        $html .= self::panelRecentLog($db, $prefix);

        // -----------------------------------------------------------------------
        // Panel D: Seed actor registry
        // -----------------------------------------------------------------------
        $html .= self::panelSeedActors($db, $prefix, $base);

        // -----------------------------------------------------------------------
        // GC action form
        // -----------------------------------------------------------------------
        $html .= self::panelGcAction($base);

        return $html;
    }

    // -----------------------------------------------------------------------
    // Panel A: Registry vs Install SQL
    // -----------------------------------------------------------------------

    private static function panelRegistryValidation()
    {
        $registryPath = LUPOPEDIA_PATH . '/docs/doctrine/TRUST_LADDER_REGISTRY.md';
        $installSqlPath = LUPOPEDIA_PATH . '/database/lupopedia/mysql/install/install_new_lupopedia.sql';

        $html = '<div class="admin-panel"><h3>A — Registry vs Install SQL Validation</h3>';

        if (!is_file($registryPath)) {
            return $html . '<p class="admin-warn">TRUST_LADDER_REGISTRY.md not found.</p></div>';
        }
        if (!is_file($installSqlPath)) {
            return $html . '<p class="admin-warn">install_new_lupopedia.sql not found at canonical path.</p></div>';
        }

        $registryText = @file_get_contents($registryPath);
        $sqlText      = @file_get_contents($installSqlPath);

        if ($registryText === false || $sqlText === false) {
            return $html . '<p class="admin-warn">Could not read doctrine files.</p></div>';
        }

        // Extract table names from registry (backtick-wrapped lupo_* names)
        preg_match_all('/`(lupo_[a-z0-9_]+)`/i', $registryText, $regMatches);
        $regTables = array_unique(array_map('strtolower', $regMatches[1]));
        sort($regTables);

        // Extract table names from install SQL ({{prefix}}name or lupo_name patterns)
        $sqlTables = array();
        if (preg_match_all(
            '/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?\{\{prefix\}\}(\w+)\s*\(/i',
            $sqlText, $m1
        )) {
            foreach ($m1[1] as $short) {
                $sqlTables[] = strtolower('lupo_' . $short);
            }
        }
        if (preg_match_all(
            '/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?lupo_(\w+)`?\s*\(/i',
            $sqlText, $m2
        )) {
            foreach ($m2[1] as $short) {
                $sqlTables[] = strtolower('lupo_' . $short);
            }
        }
        $sqlTables = array_unique($sqlTables);

        // Required seed-range doctrine markers
        $requiredMarkers = array(
            '## Seed ID Ranges by Category',
            '0-999,999',
        );
        $missingMarkers = array();
        foreach ($requiredMarkers as $marker) {
            if (strpos($registryText, $marker) === false) {
                $missingMarkers[] = $marker;
            }
        }

        // Build result table
        $html .= '<p><small>Registry table refs: ' . count($regTables)
            . ' &nbsp;|&nbsp; Install SQL tables (lupo_*): ' . count($sqlTables) . '</small></p>';

        if ($missingMarkers) {
            $html .= '<p class="admin-staleness-bad">&#9888; Registry missing required seed-range doctrine markers:</p><ul>';
            foreach ($missingMarkers as $m) {
                $html .= '<li><code>' . htmlspecialchars($m) . '</code></li>';
            }
            $html .= '</ul>';
        }

        if (empty($regTables)) {
            $html .= '<p class="admin-warn">No backtick `lupo_*` names found in registry — nothing to validate.</p>';
        } else {
            $allOk = true;
            $html .= '<table class="admin-staleness-table"><thead><tr>'
                . '<th>Registry Table</th><th>In Install SQL</th></tr></thead><tbody>';
            foreach ($regTables as $tbl) {
                $inSql = in_array($tbl, $sqlTables, true);
                if (!$inSql) {
                    $allOk = false;
                }
                $badge = $inSql
                    ? '<span style="color:#2a7a2a">&#10003; PASS</span>'
                    : '<span style="color:#a00">&#10007; MISSING</span>';
                $html .= '<tr><td><code>' . htmlspecialchars($tbl) . '</code></td>'
                    . '<td>' . $badge . '</td></tr>';
            }
            $html .= '</tbody></table>';

            if ($allOk && empty($missingMarkers)) {
                $html .= '<p style="color:#2a7a2a">&#10003; All registry tables present in install SQL.</p>';
            }
        }

        return $html . '</div>';
    }

    // -----------------------------------------------------------------------
    // Panel B: Tier stats
    // -----------------------------------------------------------------------

    private static function panelTierStats($db, $prefix)
    {
        $html = '<div class="admin-panel"><h3>B — Trust Ladder Live Tier Stats</h3>';

        $tables = array(
            'memory_nodes' => 'memory_node_id',
            'memory_edges' => 'memory_edge_id',
        );

        // Cutoff for "eligible for GC" (90 days ago packed UTC)
        $cutoff90 = '';
        if (class_exists('timestamp_ymdhis', false) || file_exists(LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php')) {
            if (!class_exists('timestamp_ymdhis', false)) {
                require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
            }
            $cutoff90 = (string) timestamp_ymdhis::subtractSeconds((int) timestamp_ymdhis::now(), 90 * 86400);
        }

        foreach ($tables as $shortTable => $pkCol) {
            $fullTable = $prefix . $shortTable;
            $qt = $db->quoteIdentifier($fullTable);

            try {
                // Count by tier using PHP-layer classification after fetch.
                // For display/reporting: use a simple aggregate query; PHP layer classifies by string year band.
                $counts = array('seed' => 0, 'canonical_live' => 0, 'staging_live' => 0, 'soft_deleted' => 0, 'gc_eligible' => 0);

                $allRows = $db->fetchAll(
                    "SELECT $pkCol AS pk, is_deleted, deleted_ymdhis FROM $qt LIMIT 50000"
                );
                foreach ($allRows as $r) {
                    $pkStr    = (string) $r['pk'];
                    $deleted  = (int) ($r['is_deleted'] ?? 0);
                    $delYmdhis = (string) ($r['deleted_ymdhis'] ?? '0');

                    $band = self::pkBand($pkStr);

                    if ($deleted) {
                        $counts['soft_deleted']++;
                        if ($band === 'staging' && $cutoff90 !== '' && $delYmdhis > '0' && $delYmdhis <= $cutoff90) {
                            $counts['gc_eligible']++;
                        }
                    } elseif ($band === 'seed') {
                        $counts['seed']++;
                    } elseif ($band === 'canonical') {
                        $counts['canonical_live']++;
                    } elseif ($band === 'staging') {
                        $counts['staging_live']++;
                    }
                }

                $html .= '<h4><code>' . htmlspecialchars($fullTable) . '</code></h4>';
                $html .= '<table class="admin-staleness-table"><thead><tr>'
                    . '<th>Band</th><th>Count</th></tr></thead><tbody>';
                $html .= '<tr><td>Seed / reserved</td><td>' . $counts['seed'] . '</td></tr>';
                $html .= '<tr><td>Living canonical (year 1000–1999, live)</td><td>' . $counts['canonical_live'] . '</td></tr>';
                $html .= '<tr><td>Staging (year 2000–2099, live)</td><td>' . $counts['staging_live'] . '</td></tr>';
                $html .= '<tr><td>Soft-deleted (all bands)</td><td>' . $counts['soft_deleted'] . '</td></tr>';
                $gcStyle = $counts['gc_eligible'] > 0 ? ' style="color:#a00"' : '';
                $html .= '<tr><td' . $gcStyle . '>Staging eligible for GC (&ge;90 days)</td><td' . $gcStyle . '>'
                    . $counts['gc_eligible'] . '</td></tr>';
                $html .= '</tbody></table>';
            } catch (Exception $e) {
                $html .= '<p class="admin-warn">Could not query ' . htmlspecialchars($fullTable) . ': '
                    . htmlspecialchars($e->getMessage()) . '</p>';
            }
        }

        return $html . '</div>';
    }

    // -----------------------------------------------------------------------
    // Panel C: Recent log entries
    // -----------------------------------------------------------------------

    private static function panelRecentLog($db, $prefix)
    {
        $html = '<div class="admin-panel"><h3>C — Recent Trust Ladder / GC Log</h3>';

        $logTable = $prefix . 'unified_log';
        $qt = $db->quoteIdentifier($logTable);

        try {
            $rows = $db->fetchAll(
                "SELECT log_id, log_type, log_level, log_message, log_context, created_ymdhis
                 FROM $qt
                 WHERE log_type IN ('trust_ladder', 'gc')
                 ORDER BY created_ymdhis DESC
                 LIMIT 25"
            );

            if (empty($rows)) {
                $html .= '<p><em>No trust_ladder or gc log entries found.</em></p>';
            } else {
                $html .= '<table class="admin-staleness-table"><thead><tr>'
                    . '<th>created_ymdhis</th><th>level</th><th>type</th>'
                    . '<th>message</th><th>context</th></tr></thead><tbody>';
                foreach ($rows as $r) {
                    $ctx = isset($r['log_context']) ? (string) $r['log_context'] : '';
                    if (strlen($ctx) > 120) {
                        $ctx = substr($ctx, 0, 117) . '...';
                    }
                    $levelStyle = '';
                    if ($r['log_level'] === 'error' || $r['log_level'] === 'critical') {
                        $levelStyle = ' style="color:#a00"';
                    }
                    $html .= '<tr>'
                        . '<td><code>' . htmlspecialchars((string) $r['created_ymdhis']) . '</code></td>'
                        . '<td' . $levelStyle . '>' . htmlspecialchars((string) $r['log_level']) . '</td>'
                        . '<td>' . htmlspecialchars((string) $r['log_type']) . '</td>'
                        . '<td>' . htmlspecialchars((string) $r['log_message']) . '</td>'
                        . '<td><small><code>' . htmlspecialchars($ctx) . '</code></small></td>'
                        . '</tr>';
                }
                $html .= '</tbody></table>';
            }
        } catch (Exception $e) {
            $html .= '<p><em>Log table not available: ' . htmlspecialchars($e->getMessage()) . '</em></p>';
        }

        return $html . '</div>';
    }

    // -----------------------------------------------------------------------
    // Panel D: Seed actor registry
    // -----------------------------------------------------------------------

    private static function panelSeedActors($db, $prefix, $base)
    {
        $html = '<div class="admin-panel"><h3>D — Seed Actor Registry</h3>';

        $registryPath = LUPOPEDIA_PATH . '/database/lupopedia/actors/registry.json';
        if (!is_file($registryPath)) {
            return $html . '<p class="admin-warn">actors/registry.json not found.</p></div>';
        }

        $json = @file_get_contents($registryPath);
        if ($json === false) {
            return $html . '<p class="admin-warn">Could not read registry.json.</p></div>';
        }

        $data   = json_decode($json, true);
        $actors = (is_array($data) && isset($data['actors'])) ? $data['actors'] : array();

        if (empty($actors)) {
            return $html . '<p class="admin-warn">No actors found in registry.</p></div>';
        }

        $html .= '<table class="admin-staleness-table"><thead><tr>'
            . '<th>actor_id</th><th>actor_code</th><th>actor_name</th>'
            . '<th>type</th><th>status</th><th>PK valid</th><th>Canonical 18-digit</th>'
            . '</tr></thead><tbody>';

        foreach ($actors as $actor) {
            if (!is_array($actor)) {
                continue;
            }
            $aid   = isset($actor['actor_id'])   ? (string) $actor['actor_id']   : '';
            $code  = isset($actor['actor_code'])  ? (string) $actor['actor_code']  : '';
            $name  = isset($actor['actor_name'])  ? (string) $actor['actor_name']  : '';
            $type  = isset($actor['type'])        ? (string) $actor['type']        : '';
            $status = isset($actor['status'])     ? (string) $actor['status']      : '';

            $valid = IdGenerator::validateTrustLadderPk($aid, 'actors.actor_id');
            $badge = $valid
                ? '<span style="color:#2a7a2a">&#10003; valid seed</span>'
                : '<span style="color:#a00">&#10007; not validated</span>';

            $canonical18 = '';
            try {
                $canonical18 = '<code>' . htmlspecialchars(IdGenerator::seedActorToCanonicalId((int) $aid)) . '</code>';
            } catch (Exception $e) {
                $canonical18 = '<em>N/A</em>';
            }

            $html .= '<tr>'
                . '<td><code>' . htmlspecialchars($aid) . '</code></td>'
                . '<td><code>' . htmlspecialchars($code) . '</code></td>'
                . '<td>' . htmlspecialchars($name) . '</td>'
                . '<td>' . htmlspecialchars($type) . '</td>'
                . '<td>' . htmlspecialchars($status) . '</td>'
                . '<td>' . $badge . '</td>'
                . '<td>' . $canonical18 . '</td>'
                . '</tr>';
        }

        $html .= '</tbody></table>';

        return $html . '</div>';
    }

    // -----------------------------------------------------------------------
    // Panel GC action form
    // -----------------------------------------------------------------------

    private static function panelGcAction($base)
    {
        $csrfField = '';
        if (function_exists('lupo_get_csrf_token')) {
            $csrfField = '<input type="hidden" name="csrf_token" value="'
                . htmlspecialchars(lupo_get_csrf_token()) . '">';
        }

        $html = '<div class="admin-panel"><h3>Run Staging GC</h3>';
        $html .= '<p>Physically purges soft-deleted staging-tier rows (&ge;90 days old) '
            . 'from <code>lupo_memory_nodes</code> and <code>lupo_memory_edges</code>.</p>';
        $html .= '<form method="post" action="" onsubmit="return confirm(\'Run Staging GC purge now?\')">';
        $html .= $csrfField;
        $html .= '<input type="hidden" name="action" value="run_staging_gc">';
        $html .= '<button type="submit" class="admin-btn admin-btn-danger">'
            . 'Run Staging GC (90-day retention)</button>';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    // -----------------------------------------------------------------------
    // Private helpers
    // -----------------------------------------------------------------------

    /**
     * Classify PK string into trust band.
     * Mirrors CHRONOLOGICAL_TRUST_LADDER.md §2.2.1 in PHP.
     *
     * @param string $pkStr
     * @return string 'seed'|'canonical'|'staging'|'unknown'
     */
    private static function pkBand($pkStr)
    {
        if (!ctype_digit($pkStr) || $pkStr === '') {
            return 'unknown';
        }
        // Reserved / seed space: 0-999,999
        if (class_exists('IdGenerator', false) && IdGenerator::isReservedSpace($pkStr)) {
            return 'seed';
        }
        if (strlen($pkStr) !== 18) {
            return 'unknown';
        }
        $year = (int) substr($pkStr, 0, 4);
        if ($year >= 1000 && $year <= 1999) {
            return 'canonical';
        }
        if ($year >= 2000 && $year <= 2099) {
            return 'staging';
        }
        return 'unknown';
    }

    /**
     * Invoke StagingGcService::purge() and return the summary array or null.
     *
     * @param object $db
     * @param string $prefix
     * @return array|null
     */
    private static function runStagingGc($db, $prefix)
    {
        $svcPath = LUPOPEDIA_PATH . '/app/Services/Kairos/StagingGcService.php';
        if (!file_exists($svcPath)) {
            return null;
        }
        if (!class_exists('StagingGcService', false)) {
            require_once $svcPath;
        }

        $user    = isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service'])
                    && method_exists($GLOBALS['lupo_auth_service'], 'getCurrentUser')
                    ? $GLOBALS['lupo_auth_service']->getCurrentUser()
                    : (function_exists('current_user') ? current_user() : array());
        $actorId = (is_array($user) && isset($user['actor_id'])) ? (int) $user['actor_id'] : 0;

        try {
            $svc = new StagingGcService($db, $prefix, $actorId);
            return $svc->purge(90, 1000);
        } catch (Exception $e) {
            return array(
                'memory_nodes_purged' => 0,
                'memory_edges_purged' => 0,
                'errors'              => array($e->getMessage()),
            );
        }
    }

    /**
     * Log an admin action to lupo_unified_log (best-effort).
     *
     * @param object $db
     * @param string $prefix
     * @param string $action
     * @param array  $context
     */
    private static function logAdminAction($db, $prefix, $action, array $context = array())
    {
        if (!class_exists('IdGenerator', false)) {
            return;
        }
        if (!class_exists('timestamp_ymdhis', false) && file_exists(LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php')) {
            require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
        }

        $user = isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service'])
                && method_exists($GLOBALS['lupo_auth_service'], 'getCurrentUser')
                ? $GLOBALS['lupo_auth_service']->getCurrentUser()
                : (function_exists('current_user') ? current_user() : array());
        $actorId = (is_array($user) && isset($user['actor_id'])) ? (int) $user['actor_id'] : 0;

        $logTable = $prefix . 'unified_log';
        $qt       = $db->quoteIdentifier($logTable);
        $logId    = IdGenerator::generate();
        $now      = class_exists('timestamp_ymdhis', false) ? (string) timestamp_ymdhis::now() : gmdate('YmdHis');
        $ctx      = json_encode(array_merge(array('action' => $action, 'actor_id' => $actorId), $context));

        $sql = "INSERT INTO $qt (log_id, log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
                VALUES (:lid, 'trust_ladder', 'info', :msg, :ctx, :actor, :now)";
        try {
            $db->query($sql, array(
                ':lid'   => $logId,
                ':msg'   => 'Admin action: ' . $action,
                ':ctx'   => $ctx,
                ':actor' => $actorId,
                ':now'   => $now,
            ));
        } catch (Exception $e) {
            // Best-effort
        }
    }
}
