<?php
/**
 * Generate a sample report to verify output format
 */

define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH . '/');

require_once LUPOPEDIA_PATH . '/app/Services/Initialization/Interfaces/TimestampHelperInterface.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/Interfaces/InitializationLoggerInterface.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/Interfaces/ReportGeneratorInterface.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/InitializationException.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/TimestampHelper.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/ReportGenerationException.php';
require_once LUPOPEDIA_PATH . '/app/Services/Initialization/ReportGenerator.php';

class TestLogger implements InitializationLoggerInterface
{
    private $entries = array();
    public function log($level, $message, $context = array()) { $this->entries[] = array('level' => $level, 'message' => $message, 'context' => $context); }
    public function info($message, $context = array()) { $this->log('INFO', $message, $context); }
    public function warning($message, $context = array()) { $this->log('WARNING', $message, $context); }
    public function error($message, $context = array()) { $this->log('ERROR', $message, $context); }
    public function getEntries() { return $this->entries; }
    public function clear() { $this->entries = array(); }
}

$timestampHelper = new TimestampHelper();
$logger = new TestLogger();
$generator = new ReportGenerator($timestampHelper, $logger);

$auditResults = array(
    array(
        'filename' => 'kiro_status_4_0_43.md',
        'file_path' => 'docs/status/kiro_status_4_0_43.md',
        'version' => '4.0.43',
        'disposition' => 'retain',
        'rationale' => 'Version 4.0.42 or later - relevant for 4.0.44 development'
    ),
    array(
        'filename' => 'development_log_4_0_42.md',
        'file_path' => 'docs/status/development_log_4_0_42.md',
        'version' => '4.0.42',
        'disposition' => 'retain',
        'rationale' => 'Version 4.0.42 or later - relevant for 4.0.44 development'
    ),
    array(
        'filename' => 'status_report_4_0_38.md',
        'file_path' => 'docs/status/status_report_4_0_38.md',
        'version' => '4.0.38',
        'disposition' => 'archive',
        'rationale' => 'Version 4.0.35-4.0.41 - historical reference, move to archive/'
    ),
    array(
        'filename' => 'old_status_4_0_30.log',
        'file_path' => 'docs/status/old_status_4_0_30.log',
        'version' => '4.0.30',
        'disposition' => 'deprecate',
        'rationale' => 'Version 4.0.34 or earlier - obsolete, safe to remove'
    )
);

$dispositionCounts = array(
    'retain' => 2,
    'archive' => 1,
    'deprecate' => 1
);

$outputPath = LUPOPEDIA_PATH . '/tests/unit/test_output/sample_audit_report.md';

// Ensure output directory exists
$outputDir = dirname($outputPath);
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$result = $generator->generateAuditReport($auditResults, $dispositionCounts, $outputPath);

echo "Sample report generated at: {$result}\n";
echo "\nReport content:\n";
echo "================================================================================\n";
echo file_get_contents($result);
echo "================================================================================\n";
