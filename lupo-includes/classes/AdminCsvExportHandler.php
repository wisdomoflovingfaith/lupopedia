<?php
/**
 * Admin CSV Export Handler - Lupopedia 4.0.22
 * 
 * Exports all TOON-defined tables to CSV format for debugging and schema validation.
 * Only accessible by administrators.
 * 
 * CSV Format:
 * Row 1: Column names (from TOON schema)
 * Row 2: Column format types (type:length, nullable, default, PK)
 * Row 3+: Data rows (from database)
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

class AdminCsvExportHandler
{
    /**
     * Render the CSV export page
     * 
     * @param object $db Database connection (PDO_DB wrapper)
     * @param string $prefix Table prefix
     * @param string $base Base URL
     * @return string HTML content
     */
    public static function render($db, $prefix, $base)
    {
        $output = '';
        
        // Handle export request
        if (isset($_POST['action']) && $_POST['action'] === 'export') {
            $output .= self::handleExport($db, $prefix, $base);
        }
        
        // Show the export form
        $output .= self::renderExportForm($base);
        
        return $output;
    }
    
    /**
     * Handle the CSV export process
     */
    private static function handleExport($db, $prefix, $base)
    {
        $toonDir = LUPOPEDIA_PATH . '/lupo-database/lupopedia/toon';
        $csvDir = LUPOPEDIA_PATH . '/lupo-database/lupopedia/csv';
        
        // Create CSV directory if it doesn't exist
        if (!is_dir($csvDir)) {
            mkdir($csvDir, 0755, true);
        }
        
        // Get all TOON files
        $toonFiles = glob($toonDir . '/*.toon.json');
        $results = array();
        $summary = array();
        
        foreach ($toonFiles as $toonFile) {
            $tableName = basename($toonFile, '.toon.json');
            
            // Skip if not a valid table file
            if (!self::isValidTableFile($toonFile)) {
                continue;
            }
            
            // Extract table name from TOON data (already includes lupo_ prefix)
            $toonData = json_decode(file_get_contents($toonFile), true);
            $actualTableName = isset($toonData['table_name']) ? $toonData['table_name'] : $tableName;
            
            try {
                $result = self::exportTable($db, $prefix, $actualTableName, $toonFile, $csvDir);
                $results[$tableName] = $result;
                $summary[$tableName] = array(
                    'rows' => $result['row_count'],
                    'status' => $result['status'],
                    'file' => $result['file_path'],
                    'classification' => self::getTableClassification($tableName)
                );
            } catch (Exception $e) {
                $results[$tableName] = array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                );
                $summary[$tableName] = array(
                    'rows' => 0,
                    'status' => 'error',
                    'file' => null,
                    'classification' => 'unknown'
                );
            }
        }
        
        return self::renderResults($summary, $base);
    }
    
    /**
     * Export a single table to CSV
     */
    private static function exportTable($db, $prefix, $tableName, $toonFile, $csvDir)
    {
        // Load TOON schema
        $toonData = json_decode(file_get_contents($toonFile), true);
        if (!$toonData || !isset($toonData['fields'])) {
            throw new Exception("Invalid TOON file format for {$tableName}");
        }
        
        // Use the table name directly (already includes prefix from TOON)
        $fullTableName = $tableName;
        $csvFile = $csvDir . '/' . basename($tableName) . '.csv';
        
        // Open CSV file for writing
        $handle = fopen($csvFile, 'w');
        if (!$handle) {
            throw new Exception("Cannot create CSV file: {$csvFile}");
        }
        
        // Write column headers (Row 1)
        $columnNames = array();
        $columnFormats = array();
        
        foreach ($toonData['fields'] as $field) {
            $fieldName = trim($field, '`');
            $columnNames[] = $fieldName;
            
            // Parse field definition for format info
            $format = self::parseFieldFormat($field);
            $columnFormats[] = $fieldName . ':' . $format;
        }
        
        fputcsv($handle, $columnNames, ',', '"', '\\', "\n");
        fputcsv($handle, $columnFormats, ',', '"', '\\', "\n");
        
        // Write data rows (Row 3+)
        $rowCount = 0;
        try {
            $stmt = $db->query("SELECT * FROM " . $db->quoteIdentifier($fullTableName));
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($handle, $row, ',', '"', '\\', "\n");
                $rowCount++;
            }
        } catch (Exception $e) {
            fclose($handle);
            throw new Exception("Error querying table {$fullTableName}: " . $e->getMessage());
        }
        
        fclose($handle);
        
        return array(
            'status' => 'success',
            'file_path' => 'lupo-database/lupopedia/csv/' . $tableName . '.csv',
            'row_count' => $rowCount
        );
    }
    
    /**
     * Parse field format from TOON definition
     */
    private static function parseFieldFormat($field)
    {
        // Extract type, length, nullable, default, and key info
        $format = '';
        
        // Basic type detection
        if (strpos($field, 'bigint') !== false) {
            $format .= 'BIGINT';
        } elseif (strpos($field, 'int') !== false) {
            $format .= 'INT';
        } elseif (strpos($field, 'varchar') !== false) {
            if (preg_match('/varchar\((\d+)\)/', $field, $matches)) {
                $format .= 'VARCHAR(' . $matches[1] . ')';
            } else {
                $format .= 'VARCHAR';
            }
        } elseif (strpos($field, 'text') !== false) {
            $format .= 'TEXT';
        } elseif (strpos($field, 'json') !== false) {
            $format .= 'JSON';
        } elseif (strpos($field, 'datetime') !== false || strpos($field, 'timestamp') !== false) {
            $format .= 'TIMESTAMP';
        } else {
            $format .= 'UNKNOWN';
        }
        
        // Add nullable info
        if (strpos($field, 'NOT NULL') === false) {
            $format .= '|NULL';
        } else {
            $format .= '|NOT_NULL';
        }
        
        // Add default value
        if (preg_match('/DEFAULT\s+([^\s,]+)/', $field, $matches)) {
            $default = $matches[1];
            if ($default !== 'NULL') {
                $format .= '|DEFAULT:' . $default;
            }
        }
        
        // Add primary key info
        if (strpos($field, 'PRIMARY KEY') !== false) {
            $format .= '|PK';
        }
        
        return $format;
    }
    
    /**
     * Check if a TOON file represents a valid table
     */
    private static function isValidTableFile($toonFile)
    {
        $data = json_decode(file_get_contents($toonFile), true);
        return $data && isset($data['table_name']) && isset($data['fields']);
    }
    
    /**
     * Get table classification from REQUIRED_TABLES_4.0.21.md
     */
    private static function getTableClassification($tableName)
    {
        // Read the required tables document
        $requiredFile = LUPOPEDIA_PATH . '/docs/REQUIRED_TABLES_4.0.21.md';
        if (!file_exists($requiredFile)) {
            return 'unknown';
        }
        
        $content = file_get_contents($requiredFile);
        
        // Check if table is in importer list
        if (preg_match('/\|\s*lupo_' . preg_quote($tableName, '/') . '\s*\|\s*required\s*\/\s*importer/i', $content)) {
            return 'required/importer';
        }
        
        // Check if table is in required list
        if (preg_match('/\|\s*lupo_' . preg_quote($tableName, '/') . '\s*\|\s*required/i', $content)) {
            return 'required';
        }
        
        // Check if table is in optional list
        if (preg_match('/\|\s*lupo_' . preg_quote($tableName, '/') . '\s*\|\s*optional/i', $content)) {
            return 'optional';
        }
        
        // Check if table is in future features list
        if (preg_match('/\|\s*lupo_' . preg_quote($tableName, '/') . '\s*\|\s*future/i', $content)) {
            return 'future';
        }
        
        return 'unknown';
    }
    
    /**
     * Render the export form
     */
    private static function renderExportForm($base)
    {
        ob_start();
        ?>
        <div class="admin-section">
            <h2>CSV Data Export</h2>
            <p class="admin-description">
                Export all TOON-defined tables to CSV format for debugging and schema validation during the 4.0.22 development cycle.
                This feature is for internal developer use only.
            </p>
            
            <div class="admin-form">
                <form method="post" action="<?php echo htmlspecialchars($base . '/admin.php?section=csv-export'); ?>">
                    <input type="hidden" name="action" value="export">
                    
                    <div class="form-group">
                        <h3>Export Settings</h3>
                        <p><strong>Output Directory:</strong> lupo-database/lupopedia/csv/</p>
                        <p><strong>Format:</strong></p>
                        <ul>
                            <li>Row 1: Column names (from TOON schema)</li>
                            <li>Row 2: Column format types (type:length, nullable, default, PK)</li>
                            <li>Row 3+: Data rows (from database)</li>
                        </ul>
                        <p><strong>Tables:</strong> All tables with TOON files will be exported.</p>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="admin-button admin-button-primary">
                            Export All Tables to CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render the export results
     */
    private static function renderResults($summary, $base)
    {
        ob_start();
        ?>
        <div class="admin-section">
            <h2>CSV Export Results</h2>
            
            <div class="admin-results">
                <?php if (empty($summary)): ?>
                    <p class="admin-warning">No tables were exported.</p>
                <?php else: ?>
                    <div class="export-summary">
                        <h3>Export Summary</h3>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Table</th>
                                    <th>Rows</th>
                                    <th>Status</th>
                                    <th>Classification</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary as $tableName => $info): ?>
                                    <tr class="<?php echo $info['status'] === 'error' ? 'error' : ($info['rows'] === 0 ? 'warning' : 'success'); ?>">
                                        <td><?php echo htmlspecialchars($tableName); ?></td>
                                        <td><?php echo number_format($info['rows']); ?></td>
                                        <td>
                                            <?php if ($info['status'] === 'error'): ?>
                                                <span class="status-error">Error</span>
                                            <?php else: ?>
                                                <span class="status-success">Success</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="classification classification-<?php echo str_replace('/', '-', $info['classification']); ?>">
                                                <?php echo htmlspecialchars($info['classification']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($info['file']): ?>
                                                <a href="<?php echo htmlspecialchars($base . '/' . $info['file']); ?>" download>
                                                    <?php echo htmlspecialchars(basename($info['file'])); ?>
                                                </a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="export-analysis">
                        <h3>Analysis for 4.0.22 Development</h3>
                        
                        <h4>Tables with Zero Rows (Potential Missing Seed Data)</h4>
                        <?php
                        $zeroRowTables = array_filter($summary, function($info) {
                            return $info['rows'] === 0 && $info['status'] !== 'error';
                        });
                        
                        if (empty($zeroRowTables)): ?>
                            <p class="admin-success">All exported tables contain data.</p>
                        <?php else: ?>
                            <ul class="warning-list">
                                <?php foreach ($zeroRowTables as $tableName => $info): ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars($tableName); ?></strong> 
                                        (<?php echo htmlspecialchars($info['classification']); ?>)
                                        - May need seed data
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        
                        <h4>Tables by Classification</h4>
                        <ul>
                            <?php
                            $classifications = array();
                            foreach ($summary as $tableName => $info) {
                                $class = $info['classification'];
                                if (!isset($classifications[$class])) {
                                    $classifications[$class] = array();
                                }
                                $classifications[$class][] = $tableName;
                            }
                            
                            foreach ($classifications as $class => $tables): ?>
                                <li><strong><?php echo htmlspecialchars($class); ?>:</strong> 
                                    <?php echo count($tables); ?> tables
                                    <?php if ($class === 'required/importer'): ?>
                                        (Importer targets - should have data after upgrade)
                                    <?php elseif ($class === 'required'): ?>
                                        (Core system tables - should have seed data)
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="export-actions">
                        <a href="<?php echo htmlspecialchars($base . '/admin.php?section=csv-export'); ?>" class="admin-button">
                            Export Again
                        </a>
                        <a href="<?php echo htmlspecialchars($base . '/admin.php'); ?>" class="admin-button admin-button-secondary">
                            Back to Admin
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        .admin-section {
            margin: 20px 0;
        }
        .admin-description {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .admin-form {
            background: #fff;
            border: 1px solid #dee2e6;
            padding: 20px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .form-group {
            margin: 15px 0;
        }
        .form-actions {
            margin-top: 20px;
        }
        .admin-button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .admin-button:hover {
            background: #0056b3;
        }
        .admin-button-primary {
            background: #28a745;
        }
        .admin-button-primary:hover {
            background: #1e7e34;
        }
        .admin-button-secondary {
            background: #6c757d;
        }
        .admin-button-secondary:hover {
            background: #545b62;
        }
        .admin-results {
            margin: 20px 0;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .admin-table th,
        .admin-table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        .admin-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .success {
            background: #d4edda;
        }
        .warning {
            background: #fff3cd;
        }
        .error {
            background: #f8d7da;
        }
        .status-success {
            color: #155724;
            font-weight: 600;
        }
        .status-error {
            color: #721c24;
            font-weight: 600;
        }
        .classification {
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 3px;
            background: #e9ecef;
        }
        .classification-required-importer {
            background: #d1ecf1;
            color: #0c5460;
        }
        .classification-required {
            background: #d4edda;
            color: #155724;
        }
        .classification-optional {
            background: #fff3cd;
            color: #856404;
        }
        .classification-future {
            background: #f8d7da;
            color: #721c24;
        }
        .export-summary,
        .export-analysis {
            margin: 30px 0;
        }
        .export-analysis h3,
        .export-analysis h4 {
            margin: 20px 0 10px 0;
            color: #495057;
        }
        .warning-list {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .warning-list li {
            margin: 5px 0;
        }
        .admin-success {
            color: #155724;
            font-weight: 600;
        }
        .export-actions {
            margin: 30px 0;
            text-align: center;
        }
        .export-actions .admin-button {
            margin: 0 10px;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
