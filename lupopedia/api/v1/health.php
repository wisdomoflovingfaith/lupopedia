<?php
/**
 * Lupopedia Minimal REST API — Health / Readiness
 *
 * GET /api/v1/health
 *
 * Returns system readiness. Stateless, UTC-driven.
 * Doctrine: no triggers, no FK, minimal.
 *
 * @package Lupopedia\API
 * @version 4.1.4
 */

require_once __DIR__ . '/../../lupopedia-config.php';

header('Content-Type: application/json');

echo json_encode([
    'status'       => 'ok',
    'utc_timestamp' => gmdate('YmdHis'),
], JSON_PRETTY_PRINT);
