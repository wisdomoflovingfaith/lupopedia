<?php
/**
 * Current UTC time — PHP only (shared hosting, no Python, no Docker).
 *
 * Lupopedia doctrine: store/compare times as 14-digit BIGINT UTC, YmdHis.
 * Use: gmdate('YmdHis') — this file is a thin wrapper for HTTP, CLI, or includes.
 *
 * tick.py / temporal_anchor.json are for batch repo work (markdown headers, agents);
 * do not shell to Python from the web app for runtime timestamps.
 *
 * Usage:
 *   CLI:  php lupo-bin/current_utc.php
 *   HTTP: .../lupo-bin/current_utc.php           → JSON
 *         .../lupo-bin/current_utc.php?format=text → plain 14 digits only
 */
$utc = gmdate('YmdHis');

if (php_sapi_name() === 'cli') {
    echo $utc . PHP_EOL;
    exit(0);
}

$fmt = isset($_GET['format']) ? $_GET['format'] : 'json';

if ($fmt === 'text' || $fmt === 'plain') {
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    echo $utc;
    exit;
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
echo json_encode(array(
    'current_utc' => $utc,
    'format' => 'YmdHis',
    'generator' => 'php_gmdate',
));
