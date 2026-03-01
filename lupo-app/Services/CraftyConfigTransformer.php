<?php
/**
 * Crafty Syntax Configuration Transformer
 *
 * Transforms legacy Crafty Syntax config.php to Lupopedia configuration format.
 * Handles path migration, database credential extraction, and secure config replacement.
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author GLOBAL_CURRENT_AUTHORS
 */

namespace App\Services;

use Exception;

/**
 * CraftyConfigTransformer
 *
 * Transforms Crafty Syntax config.php to ../lupopedia-config.php
 * with path migration and security measures.
 */
class CraftyConfigTransformer
{
    /** @var string Legacy config path */
    private const LEGACY_CONFIG_PATH = 'config.php';

    /** @var string New config path (outside webroot) */
    private const NEW_CONFIG_PATH = '../lupopedia-config.php';

    /** @var string Backup config path */
    private const BACKUP_CONFIG_PATH = 'config.php.bak';

    /**
     * Transform legacy config to new Lupopedia format
     *
     * Main entry point for config transformation process.
     *
     * @return array Result array with success status and details
     */
    public function transformConfig(): array
    {
        try {
            // Check if new config already exists
            if (file_exists(self::NEW_CONFIG_PATH)) {
                return [
                    'success' => true,
                    'message' => 'Lupopedia config already exists, skipping transformation',
                    'config_path' => self::NEW_CONFIG_PATH,
                    'already_migrated' => true
                ];
            }

            // Check if legacy config exists
            if (!file_exists(self::LEGACY_CONFIG_PATH)) {
                throw new Exception('Legacy config.php not found');
            }

            // Parse legacy configuration
            $legacyConfig = $this->parseLegacyConfig();

            // Validate extracted configuration
            $this->validateLegacyConfig($legacyConfig);

            // Transform paths
            $transformedConfig = $this->transformPaths($legacyConfig);

            // Generate new config structure
            $newConfig = $this->generateLupopediaConfig($transformedConfig);

            // Write new configuration file
            $this->writeNewConfig($newConfig);

            // Secure legacy configuration
            $this->secureLegacyConfig();

            return [
                'success' => true,
                'message' => 'Configuration transformed successfully',
                'config_path' => self::NEW_CONFIG_PATH,
                'legacy_backup' => self::BACKUP_CONFIG_PATH,
                'paths_migrated' => $this->getPathMigrations($legacyConfig, $transformedConfig)
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate transformation preview without writing files
     *
     * @return array Preview data for UI display
     */
    public function generateTransformationPreview(): array
    {
        try {
            if (!file_exists(self::LEGACY_CONFIG_PATH)) {
                throw new Exception('Legacy config.php not found');
            }

            $legacyConfig = $this->parseLegacyConfig();
            $this->validateLegacyConfig($legacyConfig);
            $transformedConfig = $this->transformPaths($legacyConfig);

            return [
                'success' => true,
                'legacy_config' => [
                    'database' => $legacyConfig['database'],
                    'server' => $legacyConfig['server'],
                    'application_root' => $legacyConfig['application_root']
                ],
                'new_config_path' => self::NEW_CONFIG_PATH,
                'path_migrations' => $this->getPathMigrations($legacyConfig, $transformedConfig),
                'backup_location' => self::BACKUP_CONFIG_PATH
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Restore original configuration from backup
     *
     * @return array Result array with success status
     */
    public function restoreOriginalConfig(): array
    {
        try {
            // Check if backup exists
            if (!file_exists(self::BACKUP_CONFIG_PATH)) {
                throw new Exception('Backup config.php.bak not found');
            }

            // Restore from backup
            if (!copy(self::BACKUP_CONFIG_PATH, self::LEGACY_CONFIG_PATH)) {
                throw new Exception('Failed to restore config.php from backup');
            }

            // Remove new config if it exists
            if (file_exists(self::NEW_CONFIG_PATH)) {
                unlink(self::NEW_CONFIG_PATH);
            }

            // Remove backup file
            unlink(self::BACKUP_CONFIG_PATH);

            return [
                'success' => true,
                'message' => 'Original configuration restored successfully'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Parse legacy Crafty Syntax config.php
     *
     * Extracts required variables using regex patterns.
     *
     * @return array Extracted configuration variables
     * @throws Exception If parsing fails or required variables missing
     */
    private function parseLegacyConfig(): array
    {
        $content = file_get_contents(self::LEGACY_CONFIG_PATH);

        if ($content === false) {
            throw new Exception('Unable to read legacy config.php');
        }

        $config = [];

        // Extract $server
        if (preg_match('/\$server\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
            $config['server'] = $matches[1];
        }

        // Extract $database
        if (preg_match('/\$database\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
            $config['database'] = $matches[1];
        }

        // Extract $datausername
        if (preg_match('/\$datausername\s*=\s*["\']([^"\']*)["\']/', $content, $matches)) {
            $config['datausername'] = $matches[1];
        }

        // Extract $password
        if (preg_match('/\$password\s*=\s*["\']([^"\']*)["\']/', $content, $matches)) {
            $config['password'] = $matches[1];
        }

        // Extract $application_root
        if (preg_match('/\$application_root\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
            $config['application_root'] = $matches[1];
        }

        // Extract $dbtype
        if (preg_match('/\$dbtype\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
            $config['dbtype'] = $matches[1];
        }

        return $config;
    }

    /**
     * Validate extracted legacy configuration
     *
     * @param array $config Extracted configuration
     * @throws Exception If validation fails
     */
    private function validateLegacyConfig(array $config): void
    {
        $required = ['server', 'database', 'datausername', 'password', 'application_root', 'dbtype'];
        $missing = [];

        foreach ($required as $key) {
            if (!isset($config[$key]) || $config[$key] === '') {
                $missing[] = '$' . $key;
            }
        }

        if (!empty($missing)) {
            throw new Exception('Missing required variables in config.php: ' . implode(', ', $missing));
        }

        // Validate database type
        if ($config['dbtype'] !== 'mysql') {
            throw new Exception('Unsupported database type: ' . $config['dbtype'] . '. Only mysql is supported.');
        }
    }

    /**
     * Transform paths from legacy to new format
     *
     * @param array $config Legacy configuration
     * @return array Transformed configuration
     */
    private function transformPaths(array $config): array
    {
        $transformed = $config;

        // Transform application_root: /lh/ → /lupopedia/
        $transformed['application_root'] = str_replace('/lh/', '/lupopedia/', $config['application_root']);

        // If no /lh/ found, append /lupopedia/ if needed
        if ($transformed['application_root'] === $config['application_root'] &&
            !str_contains($transformed['application_root'], '/lupopedia/')) {
            $transformed['application_root'] = rtrim($config['application_root'], '/') . '/lupopedia/';
        }

        return $transformed;
    }

    /**
     * Generate new Lupopedia configuration structure
     *
     * @param array $config Transformed configuration
     * @return array New config structure
     */
    private function generateLupopediaConfig(array $config): array
    {
        return [
            'database' => [
                'driver' => 'mysql',
                'host' => $config['server'],
                'database' => $config['database'],
                'username' => $config['datausername'],
                'password' => $config['password'],
            ],
            'paths' => [
                'root' => $config['application_root'],
            ],
            'legacy' => [
                'migrated_from' => 'crafty_syntax',
                'original_root' => $config['application_root'] !== $config['application_root'] ?
                    str_replace('/lupopedia/', '/lh/', $config['application_root']) :
                    $config['application_root'],
            ],
        ];
    }

    /**
     * Write new configuration file
     *
     * @param array $config Configuration array
     * @throws Exception If write operation fails
     */
    private function writeNewConfig(array $config): void
    {
        $phpConfig = "<?php\n\n";
        $phpConfig .= "/**\n";
        $phpConfig .= " * Lupopedia Configuration\n";
        $phpConfig .= " *\n";
        $phpConfig .= " * Migrated from Crafty Syntax configuration\n";
        $phpConfig .= " * Generated on: " . date('Y-m-d H:i:s') . "\n";
        $phpConfig .= " */\n\n";
        $phpConfig .= "return " . var_export($config, true) . ";\n";

        if (file_put_contents(self::NEW_CONFIG_PATH, $phpConfig) === false) {
            throw new Exception('Failed to write new configuration file');
        }

        // Set appropriate permissions
        chmod(self::NEW_CONFIG_PATH, 0600);
    }

    /**
     * Secure legacy configuration file
     *
     * Creates backup and replaces original with safe stub.
     *
     * @throws Exception If backup or replacement fails
     */
    private function secureLegacyConfig(): void
    {
        // Create backup
        if (!copy(self::LEGACY_CONFIG_PATH, self::BACKUP_CONFIG_PATH)) {
            throw new Exception('Failed to create config.php backup');
        }

        // Create safe stub content
        $stubContent = "<?php\n";
        $stubContent .= "/**\n";
        $stubContent .= " * Legacy Crafty Syntax Configuration (MIGRATED)\n";
        $stubContent .= " *\n";
        $stubContent .= " * This file has been migrated to Lupopedia.\n";
        $stubContent .= " * New configuration is located at: ../lupopedia-config.php\n";
        $stubContent .= " * Original backup saved as: config.php.bak\n";
        $stubContent .= " * Migration completed on: " . date('Y-m-d H:i:s') . "\n";
        $stubContent .= " *\n";
        $stubContent .= " * DO NOT DELETE THIS FILE - It may be needed for rollback\n";
        $stubContent .= " */\n\n";
        $stubContent .= "// Configuration migrated to Lupopedia\n";
        $stubContent .= "// See ../lupopedia-config.php for current configuration\n";

        // Replace original with stub
        if (file_put_contents(self::LEGACY_CONFIG_PATH, $stubContent) === false) {
            throw new Exception('Failed to replace legacy config with stub');
        }
    }

    /**
     * Get path migration details for display
     *
     * @param array $legacy Legacy configuration
     * @param array $transformed Transformed configuration
     * @return array Path migration details
     */
    private function getPathMigrations(array $legacy, array $transformed): array
    {
        return [
            'original_root' => $legacy['application_root'],
            'new_root' => $transformed['application_root'],
            'migration_pattern' => '/lh/ → /lupopedia/',
            'changed' => $legacy['application_root'] !== $transformed['application_root']
        ];
    }
}
