<?php
/**
 * Lupopedia — Branch Budget (Version-Gated Branch Freeze Protocol)
 *
 * GET /api/v1/governance/branch-budget
 *
 * Returns machine-readable branch budget and thaw version.
 * Align with config/global_atoms: GLOBAL_BRANCH_BUDGET, GLOBAL_BRANCH_THAW_VERSION.
 * Doctrine: VERSION_GATED_BRANCH_FREEZE_PROTOCOL.
 *
 * @package Lupopedia\API
 * @version 3.1.5
 */

// Define LUPOPEDIA_PATH before loading config (required for bootstrap)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(dirname(__DIR__))));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(dirname(dirname(__DIR__)))));
}

require_once __DIR__ . '/../../../lupopedia-config.php';

header('Content-Type: application/json');

$branch_budget = 0;
$thaw_version = '4.2.0';

// Optional: resolve from config/global_atoms.yaml if a YAML loader is available
if (function_exists('yaml_parse_file')) {
    $atoms = @yaml_parse_file(__DIR__ . '/../../../config/global_atoms.yaml');
    if (isset($atoms['GLOBAL_BRANCH_BUDGET'])) {
        $branch_budget = (int) $atoms['GLOBAL_BRANCH_BUDGET'];
    }
    if (isset($atoms['GLOBAL_BRANCH_THAW_VERSION'])) {
        $thaw_version = (string) $atoms['GLOBAL_BRANCH_THAW_VERSION'];
    }
}

echo json_encode([
    'branch_budget'   => $branch_budget,
    'thaw_version'    => $thaw_version,
    'utc_timestamp'   => gmdate('YmdHis'),
], JSON_PRETTY_PRINT);
