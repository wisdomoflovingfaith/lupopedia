#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412163625"
#   file_path_from_root: "lupo-scripts/check_limit_utilization.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/check_limit_utilization.php"
#   last_modified_utc: "20260412163625"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "lupo-memory/development/canonical/1026/04/check-limit-utilization.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Constitutional limit utilization dashboard"
#   status: "complete"
#   parent_pk_id: "00"
#   summary: "CLI report for PRD/table/actor/channel utilization vs caps; optional markdown report."
#   module: null
#   dialog_transcript: "0/development/check-limit-utilization"
# ---------------------------------------------------------------------
// check_limit_utilization.php — ASCII dashboard for constitutional limits (file docs only; Lupopedia header is the # grid above).
// Usage:
//   php lupo-scripts/check_limit_utilization.php
//   php lupo-scripts/check_limit_utilization.php --write-report

$repoRoot = dirname(__DIR__);
$writeReport = in_array('--write-report', $argv, true);

$prdDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-docs' . DIRECTORY_SEPARATOR . 'prd';
$skip   = array(
    'PRD_INDEX.md' => true,
    'README.md' => true,
    'WHAT_TO_DO_NEXT.md' => true,
    'PRD_AGENT_DEFINITION_MODEL.md' => true,
);
$prdUsed = 0;
if (is_dir($prdDir)) {
    $dh = opendir($prdDir);
    if ($dh) {
        while (($f = readdir($dh)) !== false) {
            if (substr($f, -3) !== '.md') {
                continue;
            }
            if (isset($skip[$f])) {
                continue;
            }
            if (preg_match('/^\\d{2}_.+\\.md$/', $f)) {
                $prdUsed++;
            }
        }
        closedir($dh);
    }
}
$prdPct = $prdUsed / 100.0;

$seedCount = 0;
$regPath   = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
if (is_file($regPath)) {
    $j = json_decode(file_get_contents($regPath), true);
    if (is_array($j) && isset($j['actors']) && is_array($j['actors'])) {
        foreach ($j['actors'] as $a) {
            if (is_array($a) && isset($a['actor_id']) && (int) $a['actor_id'] <= 999) {
                $seedCount++;
            }
        }
    }
}
$actorPct = $seedCount / 999.0;

$tableCount   = null;
$channelWorst = 0;
if (!defined('ABSPATH')) {
    define('ABSPATH', $repoRoot . DIRECTORY_SEPARATOR);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repoRoot . DIRECTORY_SEPARATOR);
}
$cfg = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (is_file($cfg)) {
    require_once $cfg;
    if (file_exists($repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php')) {
        require_once $repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
    }
    if (isset($GLOBALS['mydatabase']) && defined('DB_NAME')) {
        $db     = $GLOBALS['mydatabase'];
        $schema = DB_NAME;
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $sql    = 'SELECT TABLE_NAME AS t FROM information_schema.tables WHERE table_schema = :schema AND table_type = :tt';
        $rows   = $db->fetchAll($sql, array('schema' => $schema, 'tt' => 'BASE TABLE'));
        if (is_array($rows)) {
            $tableCount = 0;
            foreach ($rows as $row) {
                if (!isset($row['t'])) {
                    continue;
                }
                $t = (string) $row['t'];
                if (strpos($t, $prefix) !== 0) {
                    continue;
                }
                if (strpos($t, 'tmp_') === 0) {
                    continue;
                }
                if (strlen($t) >= 4 && substr($t, -4) === '_tmp') {
                    continue;
                }
                $tableCount++;
            }
        }
        $ctable = $prefix . 'channels';
        $sql2   = 'SELECT department_id, COUNT(*) AS c FROM ' . $db->quoteIdentifier($ctable)
            . ' WHERE is_deleted = 0 GROUP BY department_id ORDER BY c DESC LIMIT 1';
        $mx     = $db->fetchRow($sql2, array());
        if (is_array($mx) && isset($mx['c'])) {
            $channelWorst = (int) $mx['c'];
        }
    }
}

$bar = function ($pct) {
    $n = (int) floor(min(1.0, $pct) * 10);
    $s = str_repeat('#', $n) . str_repeat('.', 10 - $n);
    return $s;
};

$ts = gmdate('Y-m-d');
echo "LIMIT UTILIZATION REPORT - {$ts} (UTC)\n";
echo "=====================================\n";
printf("PRD files:        %d/100 (%d%%)  %s\n", $prdUsed, (int) floor($prdPct * 100), $bar($prdPct));
if ($tableCount !== null) {
    $tp = $tableCount / 199.0;
    printf("DB tables:        %d/199 (%d%%)  %s\n", $tableCount, (int) floor($tp * 100), $bar($tp));
} else {
    echo "DB tables:        (skipped - no DB)\n";
}
printf("Seeded actors:    %d/999 (%d%%)  %s\n", $seedCount, (int) floor($actorPct * 100), $bar($actorPct));
$cp = $channelWorst / 99.0;
printf("Channels (max dept): %d/99 (%d%%)  %s\n", $channelWorst, (int) floor($cp * 100), $bar($cp));

if ($prdPct >= 0.8) {
    echo "\nWARNING: PRD utilization >= 80% - initiate audit (see 99_limits_for_everything_and_why.md)\n";
}
if ($prdPct >= 0.95) {
    echo "CRITICAL: PRD utilization >= 95% - consolidation required before new PRDs\n";
}

if ($writeReport) {
    $repDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-docs' . DIRECTORY_SEPARATOR . 'reports';
    if (!is_dir($repDir)) {
        @mkdir($repDir, 0755, true);
    }
    $fn  = $repDir . DIRECTORY_SEPARATOR . 'limit_utilization_' . gmdate('Ymd') . '.md';
    $buf = "# Limit utilization snapshot\n\nGenerated UTC: " . gmdate('YmdHis') . "\n\n";
    $buf .= sprintf("- PRD files: %d/100\n", $prdUsed);
    if ($tableCount !== null) {
        $buf .= sprintf("- DB tables: %d/199\n", $tableCount);
    }
    $buf .= sprintf("- Seed actors: %d/999\n", $seedCount);
    $buf .= sprintf("- Max channels per department: %d/99\n", $channelWorst);
    file_put_contents($fn, $buf);
    echo "\nWrote {$fn}\n";
}

exit(0);
