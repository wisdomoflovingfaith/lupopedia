<?php
/**
 * Crafty Syntax Import Controller
 *
 * Handles the complete migration from Crafty Syntax Live Help to Lupopedia.
 * Provides wizard interface for detection, configuration transformation,
 * data migration, validation, and completion.
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author GLOBAL_CURRENT_AUTHORS
 */

namespace App\Http\Controllers;

use App\Services\CraftyDetectionService;
use App\Services\CraftyConfigTransformer;
use App\Services\CraftyMigrationService;
use App\Services\CraftyBackupService;
use App\Services\CraftyValidationService;
use App\Services\CraftyProgressService;
use Exception;
use PDO;

// Load Collection 0 (system documentation) helpers
if (defined('LUPOPEDIA_PATH')) {
    require_once(LUPOPEDIA_PATH . '/lupo-includes/functions/collection-zero-helpers.php');
}

/**
 * CraftyImportController
 *
 * Orchestrates the complete Crafty Syntax to Lupopedia migration process.
 * Follows doctrine-safe patterns with full rollback capability.
 */
class CraftyImportController
{
    /** @var CraftyDetectionService */
    private $detectionService;

    /** @var CraftyConfigTransformer */
    private $configTransformer;

    /** @var CraftyMigrationService */
    private $migrationService;

    /** @var CraftyBackupService */
    private $backupService;

    /** @var CraftyValidationService */
    private $validationService;

    /** @var CraftyProgressService */
    private $progressService;

    /**
     * Constructor
     *
     * Initializes all required services for the migration process.
     */
    public function __construct()
    {
        $this->detectionService = new CraftyDetectionService();
        $this->configTransformer = new CraftyConfigTransformer();
        $this->migrationService = new CraftyMigrationService();
        $this->backupService = new CraftyBackupService();
        $this->validationService = new CraftyValidationService();
        $this->progressService = new CraftyProgressService();
    }

    /**
     * Detect Crafty Syntax installation and show detection screen
     *
     * Checks for config.php existence and legacy table presence.
     * Returns detection results with database summary.
     *
     * @return string HTML detection screen or JSON response
     */
    public function detect(): string
    {
        try {
            // Check if Lupopedia is already configured
            if (file_exists('config/lupopedia.php')) {
                return $this->jsonResponse([
                    'status' => 'already_migrated',
                    'message' => 'Lupopedia is already configured. Migration not needed.',
                    'redirect' => '/'
                ]);
            }

            // Detect Crafty Syntax installation
            $detection = $this->detectionService->detectConfigFile();

            if (!$detection['found']) {
                return $this->jsonResponse([
                    'status' => 'not_found',
                    'message' => 'No Crafty Syntax installation detected. config.php not found.',
                    'redirect' => '/'
                ]);
            }

            // Analyze configuration content
            $configAnalysis = $this->detectionService->analyzeConfigContent();

            if (!$configAnalysis['is_crafty_syntax']) {
                return $this->jsonResponse([
                    'status' => 'invalid_config',
                    'message' => 'config.php found but does not appear to be Crafty Syntax.',
                    'details' => $configAnalysis['missing_indicators']
                ]);
            }

            // Test database connection
            $dbTest = $this->detectionService->testDatabaseConnection();

            if (!$dbTest['success']) {
                return $this->jsonResponse([
                    'status' => 'db_connection_failed',
                    'message' => 'Cannot connect to Crafty Syntax database.',
                    'error' => $dbTest['error']
                ]);
            }

            // Detect legacy tables
            $legacyTables = $this->detectionService->detectLegacyTables();

            // Get data summary
            $dataSummary = $this->detectionService->getDataSummary();

            // Store detection results in session for next step
            $_SESSION['crafty_detection'] = [
                'config_analysis' => $configAnalysis,
                'db_test' => $dbTest,
                'legacy_tables' => $legacyTables,
                'data_summary' => $dataSummary,
                'detected_at' => date('Y-m-d H:i:s')
            ];

            // Return detection view with data
            return $this->renderView('import_wizard/detection', [
                'detection' => $detection,
                'config_analysis' => $configAnalysis,
                'db_test' => $dbTest,
                'legacy_tables' => $legacyTables,
                'data_summary' => $dataSummary
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Detection failed: ' . $e->getMessage(),
                'redirect' => '/import/crafty/error?error=' . urlencode($e->getMessage())
            ]);
        }
    }

    /**
     * Show confirmation screen with migration details
     *
     * Displays comprehensive migration plan with data summary,
     * config transformation details, and migration benefits.
     *
     * @return string HTML confirmation screen
     */
    public function index(): string
    {
        try {
            // Check if detection was run
            if (!isset($_SESSION['crafty_detection'])) {
                header('Location: /import/crafty/detect');
                return '';
            }

            $detection = $_SESSION['crafty_detection'];

            // Generate migration preview
            $migrationPreview = $this->migrationService->generateMigrationPreview();

            // Get config transformation preview
            $configPreview = $this->configTransformer->generateTransformationPreview();

            return $this->renderView('import_wizard/confirmation', [
                'detection' => $detection,
                'migration_preview' => $migrationPreview,
                'config_preview' => $configPreview,
                'benefits' => $this->getMigrationBenefits()
            ]);

        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to load confirmation screen');
        }
    }

    /**
     * Start migration process
     *
     * Initializes migration with backup creation, config transformation,
     * and begins SQL execution. Returns JSON status for AJAX polling.
     *
     * @return string JSON response with migration start status
     */
    public function start(): string
    {
        try {
            // Verify detection data exists
            if (!isset($_SESSION['crafty_detection'])) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'Detection data not found. Please run detection first.',
                    'redirect' => '/import/crafty/detect'
                ]);
            }

            // Initialize progress tracking
            $this->progressService->initializeProgress();

            // Step 1: Create backups
            $this->progressService->updateProgress('Creating backups...', 10);
            $backupResult = $this->backupService->createBackups();

            if (!$backupResult['success']) {
                throw new Exception('Backup creation failed: ' . $backupResult['error']);
            }

            // Step 2: Transform configuration
            $this->progressService->updateProgress('Transforming configuration...', 20);
            $configResult = $this->configTransformer->transformConfig();

            if (!$configResult['success']) {
                throw new Exception('Config transformation failed: ' . $configResult['error']);
            }

            // Step 3: Start migration execution
            $this->progressService->updateProgress('Starting data migration...', 30);
            $migrationResult = $this->migrationService->startMigration();

            if (!$migrationResult['success']) {
                throw new Exception('Migration start failed: ' . $migrationResult['error']);
            }

            // Store migration session data
            $_SESSION['crafty_migration'] = [
                'started_at' => date('Y-m-d H:i:s'),
                'backup_files' => $backupResult['backup_files'],
                'new_config_path' => $configResult['config_path'],
                'migration_id' => $migrationResult['migration_id']
            ];

            return $this->jsonResponse([
                'status' => 'started',
                'message' => 'Migration started successfully',
                'migration_id' => $migrationResult['migration_id'],
                'redirect' => '/import/crafty/progress'
            ]);

        } catch (Exception $e) {
            // Clean up on failure
            $this->cleanup();

            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to start migration: ' . $e->getMessage(),
                'redirect' => '/import/crafty/error?error=' . urlencode($e->getMessage())
            ]);
        }
    }

    /**
     * Get migration progress for AJAX updates
     *
     * Returns real-time progress data including current table,
     * completion percentage, and estimated time remaining.
     *
     * @return string JSON progress data
     */
    public function progress(): string
    {
        try {
            $progressData = $this->progressService->getProgressData();

            return $this->jsonResponse([
                'status' => 'progress',
                'data' => $progressData
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to get progress: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Pause migration execution
     *
     * Safely pauses the current migration process while preserving state.
     *
     * @return string JSON response
     */
    public function pause(): string
    {
        try {
            $result = $this->migrationService->pauseMigration();

            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            return $this->jsonResponse([
                'status' => 'paused',
                'message' => 'Migration paused successfully'
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to pause migration: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Resume paused migration
     *
     * Resumes migration execution from the last completed state.
     *
     * @return string JSON response
     */
    public function resume(): string
    {
        try {
            $result = $this->migrationService->resumeMigration();

            if (!$result['success']) {
                throw new Exception($result['error']);
            }

            return $this->jsonResponse([
                'status' => 'resumed',
                'message' => 'Migration resumed successfully'
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to resume migration: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cancel migration and trigger rollback
     *
     * Cancels the current migration and restores original state.
     *
     * @return string JSON response
     */
    public function cancel(): string
    {
        try {
            // Cancel migration
            $cancelResult = $this->migrationService->cancelMigration();

            if (!$cancelResult['success']) {
                throw new Exception('Cancel failed: ' . $cancelResult['error']);
            }

            // Trigger rollback
            $rollbackResult = $this->rollback();

            return $this->jsonResponse([
                'status' => 'cancelled',
                'message' => 'Migration cancelled and rolled back successfully',
                'redirect' => '/import/crafty/rollback'
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Failed to cancel migration: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Run validation checks on migrated data
     *
     * Validates data integrity, row counts, and workflow functionality.
     *
     * @return string JSON validation results
     */
    public function validate(): string
    {
        try {
            // Run comprehensive validation
            $validation = $this->validationService->validateMigration();

            // Update progress
            $this->progressService->updateProgress('Validation complete', 100);

            return $this->jsonResponse([
                'status' => 'validation_complete',
                'results' => $validation,
                'next_step' => $validation['passed'] ? 'complete' : 'error'
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Validation failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show completion screen with next steps
     *
     * Displays migration success summary and next steps for users.
     *
     * @return string HTML completion screen
     */
    public function complete(): string
    {
        try {
            if (!isset($_SESSION['crafty_migration'])) {
                header('Location: /import/crafty/detect');
                return '';
            }

            // Get final migration summary
            $summary = $this->migrationService->getMigrationSummary();

            // Get validation results
            $validation = $this->validationService->getLastValidationResults();

            // Initialize Collection 0 (system documentation)
            // Ensure Collection 0 exists and is populated with documentation tabs
            if (function_exists('lupo_initialize_collection_zero')) {
                $collection_zero_success = lupo_initialize_collection_zero();
                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log("CRAFTY IMPORT: Collection 0 initialization " . ($collection_zero_success ? 'succeeded' : 'failed'));
                }
            }

            // Clean up temporary files
            $this->cleanup();

            // Check if migration was successful
            $migration_successful = (
                isset($summary['status']) && $summary['status'] === 'complete' &&
                isset($validation['passed']) && $validation['passed'] === true
            );

            // If migration successful, redirect to Collection 0 (documentation landing page)
            if ($migration_successful) {
                // Clear migration session
                unset($_SESSION['crafty_migration']);

                // Redirect to Collection 0
                $collection_zero_url = function_exists('lupo_get_collection_zero_url')
                    ? lupo_get_collection_zero_url()
                    : (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/collection/0/lupopedia';

                if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                    error_log("CRAFTY IMPORT: Redirecting to Collection 0: " . $collection_zero_url);
                }

                header('Location: ' . $collection_zero_url);
                return '';
            }

            // If migration not successful, show completion screen with errors
            return $this->renderView('import_wizard/complete', [
                'summary' => $summary,
                'validation' => $validation,
                'next_steps' => $this->getNextSteps()
            ]);

        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to load completion screen');
        }
    }

    /**
     * Show error screen with troubleshooting
     *
     * Displays error details and troubleshooting options.
     *
     * @return string HTML error screen
     */
    public function error(): string
    {
        $error = $_GET['error'] ?? 'Unknown error occurred';
        $troubleshooting = $this->getTroubleshootingSteps($error);

        return $this->renderView('import_wizard/error', [
            'error' => $error,
            'troubleshooting' => $troubleshooting,
            'support_info' => $this->getSupportInfo()
        ]);
    }

    /**
     * Execute manual rollback procedure
     *
     * Restores original configuration and database state from backups.
     *
     * @return string JSON response or HTML rollback screen
     */
    public function rollback(): string
    {
        try {
            if (!isset($_SESSION['crafty_migration'])) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => 'No migration session found for rollback'
                ]);
            }

            $migrationData = $_SESSION['crafty_migration'];

            // Restore configuration
            if (isset($migrationData['new_config_path'])) {
                $configResult = $this->configTransformer->restoreOriginalConfig();
                if (!$configResult['success']) {
                    throw new Exception('Config restoration failed: ' . $configResult['error']);
                }
            }

            // Restore database from backups
            if (isset($migrationData['backup_files'])) {
                $backupResult = $this->backupService->restoreFromBackups($migrationData['backup_files']);
                if (!$backupResult['success']) {
                    throw new Exception('Database restoration failed: ' . $backupResult['error']);
                }
            }

            // Clean up session data
            unset($_SESSION['crafty_migration']);
            unset($_SESSION['crafty_detection']);

            return $this->renderView('import_wizard/rollback', [
                'status' => 'success',
                'message' => 'System successfully restored to pre-migration state'
            ]);

        } catch (Exception $e) {
            return $this->renderView('import_wizard/rollback', [
                'status' => 'error',
                'message' => 'Rollback failed: ' . $e->getMessage(),
                'support_info' => $this->getSupportInfo()
            ]);
        }
    }

    /**
     * Render view template with data
     *
     * @param string $view View template path
     * @param array $data Data to pass to view
     * @return string Rendered HTML
     */
    private function renderView(string $view, array $data = []): string
    {
        $viewPath = "resources/views/{$view}.blade.php";

        if (!file_exists($viewPath)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => "View template not found: {$view}"
            ]);
        }

        // Extract data for use in template
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view template
        include $viewPath;

        // Get the output and clean the buffer
        $output = ob_get_clean();

        return $output;
    }

    /**
     * Return JSON response
     *
     * @param array $data Response data
     * @return string JSON string
     */
    private function jsonResponse(array $data): string
    {
        header('Content-Type: application/json');
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Handle errors consistently
     *
     * @param Exception $e Exception object
     * @param string $context Error context
     * @return string Error response
     */
    private function handleError(Exception $e, string $context): string
    {
        // Log error for debugging
        error_log("CraftyImport Error [{$context}]: " . $e->getMessage());

        return $this->jsonResponse([
            'status' => 'error',
            'message' => $context . ': ' . $e->getMessage(),
            'redirect' => '/import/crafty/error?error=' . urlencode($e->getMessage())
        ]);
    }

    /**
     * Get migration benefits for display
     *
     * @return array Migration benefits
     */
    private function getMigrationBenefits(): array
    {
        return [
            'temporal_features' => 'BIGINT UTC timestamps with YYYYMMDDHHIISS format',
            'no_foreign_keys' => 'Application-managed relationships for better control',
            'modern_architecture' => '111 doctrine-safe tables with clear separation',
            'ai_integration' => 'Built-in support for 128 AI agents',
            'federation_ready' => 'Multi-node federation capabilities',
            'semantic_navigation' => 'Content meaning emerges from user navigation',
            'actor_system' => 'Unified identity system for humans, AI, and services'
        ];
    }

    /**
     * Get next steps after successful migration
     *
     * @return array Next steps
     */
    private function getNextSteps(): array
    {
        return [
            'explore_admin' => 'Visit the admin panel to configure your installation',
            'create_collections' => 'Create your first knowledge collections',
            'configure_agents' => 'Set up AI agents for your needs',
            'import_content' => 'Begin importing your knowledge content',
            'setup_federation' => 'Connect to other Lupopedia nodes (optional)'
        ];
    }

    /**
     * Get troubleshooting steps for common errors
     *
     * @param string $error Error message
     * @return array Troubleshooting steps
     */
    private function getTroubleshootingSteps(string $error): array
    {
        return [
            'check_permissions' => 'Verify file and database permissions',
            'verify_config' => 'Check config.php database credentials',
            'check_disk_space' => 'Ensure sufficient disk space for backups',
            'contact_support' => 'Contact support with error details if issues persist'
        ];
    }

    /**
     * Get support information
     *
     * @return array Support contact info
     */
    private function getSupportInfo(): array
    {
        return [
            'email' => 'lupopedia@gmail.com',
            'documentation' => '/docs',
            'community' => 'Lupopedia Community Forums'
        ];
    }

    /**
     * Clean up temporary files and session data
     *
     * @return void
     */
    private function cleanup(): void
    {
        try {
            // Clean up temporary backup files if migration completed successfully
            if (isset($_SESSION['crafty_migration']['backup_files'])) {
                $this->backupService->cleanupBackups($_SESSION['crafty_migration']['backup_files']);
            }

            // Clear session data
            unset($_SESSION['crafty_migration']);
            unset($_SESSION['crafty_detection']);

        } catch (Exception $e) {
            // Log cleanup errors but don't throw
            error_log("Cleanup error: " . $e->getMessage());
        }
    }
}
