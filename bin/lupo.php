#!/usr/bin/env php
<?php
/**
 * Lupopedia CLI Tool
 * 
 * Usage:
 *   php bin/lupo.php doctor   - Run system health check
 */

// Determine base path (physical, explicit)
$base_path = dirname(__DIR__);
$config_path = $base_path . '/lupopedia-config.php';

if (!file_exists($config_path)) {
    fwrite(STDERR, "[ERROR] Configuration not found: $config_path\n");
    exit(1);
}
require_once $config_path;

$command = $argv[1] ?? 'help';

switch ($command) {
    case 'doctor':
    case 'health':
    case 'check':
        $doctor_path = LUPOPEDIA_PATH . '/agents/asclepius/doctor.php';
        if (!file_exists($doctor_path)) {
            fwrite(STDERR, "[ERROR] ASCLEPIUS doctor not found: $doctor_path\n");
            exit(1);
        }
        require_once $doctor_path;
        break;
        
    case 'staging-gc':
        $gc_path = LUPOPEDIA_PATH . '/bin/cli/staging_gc.php';
        if (!file_exists($gc_path)) {
            fwrite(STDERR, "[ERROR] staging-gc CLI not found: $gc_path\n");
            exit(1);
        }
        require_once $gc_path;
        break;

    case 'help':
    default:
        echo "Lupopedia CLI Tool\n";
        echo "Usage: php bin/lupo.php <command>\n\n";
        echo "Commands:\n";
        echo "  doctor      - Run system health check (ASCLEPIUS)\n";
        echo "  health      - Alias for doctor\n";
        echo "  check       - Alias for doctor\n";
        echo "  staging-gc  - Purge soft-deleted staging-tier rows (90-day retention)\n";
        echo "                Options: --days=N  --batch=N  --dry-run  --actor=N\n";
        break;
}
