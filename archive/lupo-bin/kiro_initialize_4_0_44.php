#!/usr/bin/env php
<?php
/**
 * CLI Entry Point for Lupopedia 4.0.44 Initialization Workflow
 * 
 * This script orchestrates the complete initialization workflow for the 4.0.44
 * development cycle. It performs the following tasks:
 * 
 * 1. Doctrine ingestion from Channel 0 broadcasts
 * 2. Development thread creation in Channel 42
 * 3. Status directory audit
 * 4. Audit report generation
 * 5. Channel 42 summary posting
 * 6. System log writing
 * 7. Validation of all outputs
 * 8. Completion notification
 * 
 * Usage:
 *   php bin/kiro_initialize_4_0_44.php
 * 
 * Exit Codes:
 *   0 - Success (all validation checks passed)
 *   1 - Failure (one or more validation checks failed)
 * 
 * Requirements:
 *   - PHP 5.3 or higher
 *   - lupopedia-config.php must be accessible
 *   - Database connection must be configured
 *   - channels/0/broadcasts/ directory must exist
 *   - docs/status/ directory must exist
 * 
 * @package Lupopedia\Initialization
 * @since 4.0.44
 */

// Ensure this is running in CLI mode
if (php_sapi_name() !== 'cli') {
    die("This script must be run from the command line.\n");
}

// Set up CLI environment
set_time_limit(0); // No time limit for CLI execution
ini_set('memory_limit', '256M'); // Increase memory limit for large operations

// Enable error reporting for CLI
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Register shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        fwrite(STDERR, "\n\nFATAL ERROR DETECTED:\n");
        fwrite(STDERR, "Type: " . $error['type'] . "\n");
        fwrite(STDERR, "Message: " . $error['message'] . "\n");
        fwrite(STDERR, "File: " . $error['file'] . "\n");
        fwrite(STDERR, "Line: " . $error['line'] . "\n");
        fflush(STDERR);
    }
});

// Determine base path (script is in bin/, so go up one level)
$basePath = dirname(dirname(__FILE__));

// Define LUPOPEDIA_PATH and LUPOPEDIA_ABSPATH constants
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $basePath);
}
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', $basePath . DIRECTORY_SEPARATOR);
}

// Output startup message
fwrite(STDOUT, "=======================================================\n");
fwrite(STDOUT, "Lupopedia 4.0.44 Initialization Workflow\n");
fwrite(STDOUT, "=======================================================\n");
fwrite(STDOUT, "Base Path: " . $basePath . "\n");
fwrite(STDOUT, "Start Time: " . gmdate('Y-m-d H:i:s') . " UTC\n");
fwrite(STDOUT, "=======================================================\n\n");

// Locate and load lupopedia-config.php
$configPath = null;
$configLocations = array(
    // Check parent directory (above docroot)
    dirname($basePath) . DIRECTORY_SEPARATOR . 'lupopedia-config.php',
    // Check install directory
    $basePath . DIRECTORY_SEPARATOR . 'lupopedia-config.php',
);

foreach ($configLocations as $location) {
    if (file_exists($location)) {
        $configPath = $location;
        break;
    }
}

if ($configPath === null) {
    fwrite(STDERR, "ERROR: Could not locate lupopedia-config.php\n");
    fwrite(STDERR, "Searched locations:\n");
    foreach ($configLocations as $location) {
        fwrite(STDERR, "  - " . $location . "\n");
    }
    fwrite(STDERR, "\nPlease ensure lupopedia-config.php exists in one of these locations.\n");
    exit(1);
}

fwrite(STDOUT, "Loading configuration from: " . $configPath . "\n");
fflush(STDOUT);

// Load configuration with output buffering to capture any die() output
ob_start();
try {
    require_once($configPath);
    $output = ob_get_clean();
    if (!empty($output)) {
        fwrite(STDOUT, "Output from config/bootstrap:\n");
        fwrite(STDOUT, $output . "\n");
        fflush(STDOUT);
    }
    fwrite(STDOUT, "Config require completed\n");
    fflush(STDOUT);
} catch (Exception $e) {
    $output = ob_get_clean();
    if (!empty($output)) {
        fwrite(STDERR, "Output before exception:\n");
        fwrite(STDERR, $output . "\n");
    }
    fwrite(STDERR, "ERROR: Failed to load configuration\n");
    fwrite(STDERR, "Exception: " . $e->getMessage() . "\n");
    fflush(STDERR);
    exit(1);
}

fwrite(STDOUT, "Configuration loaded successfully\n");
fflush(STDOUT);

// Bootstrap is automatically loaded by lupopedia-config.php
fwrite(STDOUT, "Bootstrap loaded successfully\n\n");

// Load initialization service classes
fwrite(STDOUT, "Loading initialization services...\n");

$initServicesPath = $basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Initialization';

// Load interfaces first
$interfacesPath = $initServicesPath . DIRECTORY_SEPARATOR . 'Interfaces';
if (is_dir($interfacesPath)) {
    $interfaceFiles = glob($interfacesPath . DIRECTORY_SEPARATOR . '*.php');
    if ($interfaceFiles !== false) {
        foreach ($interfaceFiles as $file) {
            require_once($file);
        }
    }
}

// Load exception classes
$exceptionFiles = array(
    'InitializationException.php',
    'DoctrineIngestionException.php',
    'ThreadCreationException.php',
    'StatusAuditException.php',
    'ReportGenerationException.php',
    'LogWriterException.php',
    'ValidationException.php',
);

foreach ($exceptionFiles as $file) {
    $filePath = $initServicesPath . DIRECTORY_SEPARATOR . $file;
    if (file_exists($filePath)) {
        require_once($filePath);
    }
}

// Load service classes
$serviceFiles = array(
    'ErrorMessages.php',
    'TimestampHelper.php',
    'FLIPHeaderParser.php',
    'VersionClassifier.php',
    'InitializationLogger.php',
    'DoctrineIngester.php',
    'ThreadCreator.php',
    'StatusAuditor.php',
    'ReportGenerator.php',
    'SummaryPoster.php',
    'LogWriter.php',
    'Validator.php',
    'CompletionNotifier.php',
    'FileSafetyChecker.php',
    'InitializationOrchestrator.php',
);

foreach ($serviceFiles as $file) {
    $filePath = $initServicesPath . DIRECTORY_SEPARATOR . $file;
    if (file_exists($filePath)) {
        require_once($filePath);
        fwrite(STDOUT, "  Loaded: " . $file . "\n");
    } else {
        fwrite(STDERR, "WARNING: Could not find service file: " . $file . "\n");
    }
}

fwrite(STDOUT, "All services loaded successfully\n\n");

// Instantiate dependencies
fwrite(STDOUT, "Initializing components...\n");

try {
    // Create helper instances
    $flipParser = new FLIPHeaderParser();
    $timestampHelper = new TimestampHelper();
    $classifier = new VersionClassifier($flipParser);
    $logger = new InitializationLogger();
    
    fwrite(STDOUT, "  Created helper instances\n");
    
    // Create orchestrator
    $orchestrator = new InitializationOrchestrator(
        $flipParser,
        $timestampHelper,
        $classifier,
        $logger,
        $basePath
    );
    
    fwrite(STDOUT, "  Created InitializationOrchestrator\n");
    fwrite(STDOUT, "Components initialized successfully\n\n");
    
} catch (Exception $e) {
    fwrite(STDERR, "ERROR: Failed to initialize components\n");
    fwrite(STDERR, "Exception: " . $e->getMessage() . "\n");
    fwrite(STDERR, "File: " . $e->getFile() . "\n");
    fwrite(STDERR, "Line: " . $e->getLine() . "\n");
    exit(1);
}

// Execute workflow
fwrite(STDOUT, "=======================================================\n");
fwrite(STDOUT, "Starting Initialization Workflow\n");
fwrite(STDOUT, "=======================================================\n\n");

try {
    // Run the workflow
    $results = $orchestrator->run();
    
    fwrite(STDOUT, "\n=======================================================\n");
    fwrite(STDOUT, "Workflow Execution Complete\n");
    fwrite(STDOUT, "=======================================================\n\n");
    
    // Output results summary
    fwrite(STDOUT, "Execution Summary:\n");
    fwrite(STDOUT, "  Start Time: " . $results['start_time'] . "\n");
    fwrite(STDOUT, "  End Time: " . $results['end_time'] . "\n");
    fwrite(STDOUT, "  Overall Status: " . $results['overall_status'] . "\n");
    fwrite(STDOUT, "  Successful Steps: " . count($results['successes']) . "\n");
    fwrite(STDOUT, "  Failed Steps: " . count($results['failures']) . "\n");
    fwrite(STDOUT, "\n");
    
    // Output step details
    if (!empty($results['steps'])) {
        fwrite(STDOUT, "Step Details:\n");
        foreach ($results['steps'] as $stepName => $stepData) {
            $status = isset($stepData['status']) ? $stepData['status'] : 'unknown';
            $statusSymbol = ($status === 'success') ? '[✓]' : '[✗]';
            fwrite(STDOUT, "  " . $statusSymbol . " " . $stepName . ": " . $status . "\n");
            
            // Output step-specific details
            if ($status === 'success') {
                if ($stepName === 'doctrine_ingestion' && isset($stepData['doctrine_count'])) {
                    fwrite(STDOUT, "      Doctrines loaded: " . $stepData['doctrine_count'] . "\n");
                }
                if ($stepName === 'thread_creation' && isset($stepData['thread_id'])) {
                    fwrite(STDOUT, "      Thread ID: " . $stepData['thread_id'] . "\n");
                }
                if ($stepName === 'status_audit' && isset($stepData['disposition_counts'])) {
                    fwrite(STDOUT, "      Disposition counts:\n");
                    foreach ($stepData['disposition_counts'] as $disposition => $count) {
                        fwrite(STDOUT, "        " . $disposition . ": " . $count . "\n");
                    }
                }
                if ($stepName === 'report_generation' && isset($stepData['report_path'])) {
                    fwrite(STDOUT, "      Report: " . $stepData['report_path'] . "\n");
                }
                if ($stepName === 'log_writing' && isset($stepData['log_path'])) {
                    fwrite(STDOUT, "      Log: " . $stepData['log_path'] . "\n");
                }
            } else {
                if (isset($stepData['error'])) {
                    // Format error message for CLI output
                    $errorMsg = ErrorMessages::formatForCLI($stepData['error'], true);
                    fwrite(STDERR, "      Error:\n");
                    fwrite(STDERR, $errorMsg . "\n");
                }
            }
        }
        fwrite(STDOUT, "\n");
    }
    
    // Output failures if any
    if (!empty($results['failures'])) {
        fwrite(STDERR, "\nFailures Encountered:\n");
        foreach ($results['failures'] as $failure) {
            if (isset($failure['step'])) {
                fwrite(STDERR, "  Step: " . $failure['step'] . "\n");
            }
            if (isset($failure['error'])) {
                // Format error message for CLI output
                $errorMsg = ErrorMessages::formatForCLI($failure['error'], true);
                fwrite(STDERR, "  " . $errorMsg . "\n");
            }
            fwrite(STDERR, "\n");
        }
    }
    
    // Determine exit code based on success
    $isSuccessful = $orchestrator->isSuccessful();
    
    if ($isSuccessful) {
        fwrite(STDOUT, "=======================================================\n");
        fwrite(STDOUT, "✓ Initialization completed successfully\n");
        fwrite(STDOUT, "=======================================================\n");
        fwrite(STDOUT, "\nNext Steps:\n");
        fwrite(STDOUT, "  1. Review the audit report in docs/status/\n");
        fwrite(STDOUT, "  2. Check the system log for detailed information\n");
        fwrite(STDOUT, "  3. Review Channel 42 messages for summary\n");
        fwrite(STDOUT, "  4. Begin 4.0.44 development work\n\n");
        exit(0);
    } else {
        fwrite(STDERR, "\n=======================================================\n");
        fwrite(STDERR, "✗ Initialization completed with failures\n");
        fwrite(STDERR, "=======================================================\n");
        fwrite(STDERR, "\nPlease review the errors above and:\n");
        fwrite(STDERR, "  1. Check the system log for detailed error information\n");
        fwrite(STDERR, "  2. Verify all required directories exist\n");
        fwrite(STDERR, "  3. Ensure file permissions are correct\n");
        fwrite(STDERR, "  4. Re-run the script after addressing issues\n\n");
        exit(1);
    }
    
} catch (Exception $e) {
    fwrite(STDERR, "\n=======================================================\n");
    fwrite(STDERR, "FATAL ERROR: Workflow execution failed\n");
    fwrite(STDERR, "=======================================================\n");
    
    // Format exception message for CLI output
    $errorMsg = ErrorMessages::formatForCLI($e->getMessage(), true);
    fwrite(STDERR, $errorMsg . "\n\n");
    
    fwrite(STDERR, "File: " . $e->getFile() . "\n");
    fwrite(STDERR, "Line: " . $e->getLine() . "\n");
    fwrite(STDERR, "\nStack Trace:\n");
    fwrite(STDERR, $e->getTraceAsString() . "\n\n");
    exit(1);
}
