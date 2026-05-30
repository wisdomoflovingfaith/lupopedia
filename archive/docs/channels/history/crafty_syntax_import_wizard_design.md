# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/history/CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md"
  file_hash: "f07931c36ba1cdd12988c6874ca542ae80a67fa98973aee408a3ae99ef6c1556"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\history\CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md"
  file_hash: "2330be520599fe4ed641b52d0a180612f354d26c6c5833a875c6b7e2ae84b374"
  file_path_from_root: "docs\channels\history\CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md"
  file_hash: "9237f424655339bdce68487f5474dab1fd7884f49a024d9c497119d904ccc357"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "history", "crafty_syntax_import_wizard_designmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
GOV-AD-PROHIBIT-001: true
ads_prohibition_statement: "Ads are manipulation. Ads are disrespect. Ads violate user trust."
file.last_modified_system_version: 4.3.3
file.last_modified_utc: 20260120092000
file.utc_day: 20260120
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @FLEET @Monday_Wolfie @Wolf @CAPTAIN_WOLFIE
  mood_RGB: "00FF00"
  message: "First-Run Import Wizard design documented. Non-destructive Crafty Syntax detection and migration workflow. Uses existing craftysyntax_to_lupopedia_mysql.sql logic with progress tracking and validation."
tags:
  categories: ["wizard", "import", "crafty-syntax", "migration", "first-run", "ui-design"]
  collections: ["wizards", "migration", "crafty-syntax"]
  channels: ["dev", "public", "migration"]
file:
  name: "CRAFTY_SYNTAX_IMPORT_WIZARD_DESIGN.md"
  title: "Crafty Syntax Import Wizard Design"
  description: "First-run import wizard design for Crafty Syntax Live Help migration to Lupopedia"
  version: "4.3.3"
  status: "published"
  author: "GLOBAL_CURRENT_AUTHORS"
---

# Crafty Syntax Import Wizard Design

**Version 4.3.3**  
**2026-01-20**  

## 🎯 Wizard Overview

The First-Run Import Wizard automatically detects existing Crafty Syntax installations and guides administrators through a non-destructive migration to Lupopedia using the existing `craftysyntax_to_lupopedia_mysql.sql` migration logic.

---

## 🚀 Detection Logic

### 🔍 **Crafty Syntax Detection**

The wizard triggers when Lupopedia boots and detects:

```php
// Detection Conditions
1. config.php exists in web root
2. config.php contains Crafty Syntax indicators:
   - $dbname, $dbuser, $dbpass variables
   - $livehelp_DB array structure
   - Crafty Syntax version constants
   - Path patterns like 'livehelp_' tables
3. Database connection test succeeds
4. Legacy tables detected (livehelp_*)
```

### 📋 **Detection Results**

| Detection Item | Expected Value | Status |
|----------------|----------------|--------|
| `config.php` | Exists | ✅ Required |
| Database Connection | Valid | ✅ Required |
| Legacy Tables | > 0 | ✅ Required |
| Crafty Version | 3.6.1-3.7.5 | ✅ Supported |
| Data Volume | Any size | ✅ Supported |

---

## 🎨 User Interface Design

### 📱 **Initial Detection Screen**

```
┌-------------------------------------------------------------+
|  🎯 Crafty Syntax Live Help Detected                         |
|                                                             |
|  We found an existing Crafty Syntax installation on this     |
|  server. Would you like to import your data into Lupopedia?  |
|                                                             |
|  📊 Detected Data:                                          |
|  • 1,247 User Accounts                                      |
|  • 47 Departments                                           |
|  • 45,892 Chat Sessions                                     |
|  • 892,341 Chat Messages                                    |
|  • 234,567 Visitor Records                                  |
|                                                             |
|  ⚠️  Migration will:                                        |
|  • Import all existing data                                 |
|  • Preserve all user accounts                               |
|  • Maintain chat history                                    |
|  • Upgrade to temporal-aware system                          |
|                                                             |
|  [🚀 Import Now]  [⏭️ Skip Migration]  [📋 Learn More]     |
+-------------------------------------------------------------+
```

### 📊 **Migration Progress Screen**

```
┌-------------------------------------------------------------+
|  🔄 Crafty Syntax Migration in Progress                      |
|                                                             |
|  ████████████████████████████████████░░░░ 75%              |
|                                                             |
|  📋 Current Task: Migrating Chat Transcripts                |
|                                                             |
|  📊 Migration Progress:                                     |
|  • User Accounts: ✅ 1,247/1,247                           |
|  • Departments: ✅ 47/47                                  |
|  • Chat Sessions: 🔄 35,419/45,892                        |
|  • Chat Messages: ⏳ 0/892,341                            |
|  • Visitor Data: ⏳ 0/234,567                            |
|                                                             |
|  ⏱️  Estimated Time Remaining: 3 minutes                     |
|                                                             |
|  📝 Migration Log:                                          |
|  [14:32:15] ✅ User accounts migrated successfully         |
|  [14:32:18] ✅ Department settings imported                |
|  [14:32:22] 🔄 Processing chat transcripts...             |
|                                                             |
|  [⏸️ Pause]  [❌ Cancel Migration]                        |
+-------------------------------------------------------------+
```

### ✅ **Migration Complete Screen**

```
┌-------------------------------------------------------------+
|  🎉 Migration Complete!                                     |
|                                                             |
|  Your Crafty Syntax data has been successfully imported     |
|  into Lupopedia with enhanced temporal awareness.           |
|                                                             |
|  📊 Migration Summary:                                      |
|  • ✅ 1,247 User Accounts Imported                         |
|  • ✅ 47 Departments Migrated                              |
|  • ✅ 45,892 Chat Sessions Preserved                        |
|  • ✅ 892,341 Chat Messages Imported                        |
|  • ✅ 234,567 Visitor Records Migrated                      |
|  • ✅ All Settings and Configuration Imported              |
|                                                             |
|  🚀 New Features Available:                                 |
|  • Temporal-aware chat routing                              |
|  • Enhanced visitor tracking                               |
|  • Frame-aware operator coordination                        |
|  • Improved analytics and reporting                         |
|                                                             |
|  🎯 Next Steps:                                            |
|  • [📱 Test Live Help System]                               |
|  • [👥 Verify User Accounts]                               |
|  • [📊 Review Analytics Dashboard]                          |
|  • [⚙️ Configure Settings]                                 |
|                                                             |
|  [🎯 Continue to Lupopedia]                                |
+-------------------------------------------------------------+
```

---

## 🔧 Technical Implementation

### 🏗️ **Wizard Architecture**

```php
class CraftySyntaxImportWizard {
    
    private $detection;
    private $migration;
    private $progress;
    private $validation;
    
    public function __construct() {
        $this->detection = new CraftySyntaxDetection();
        $this->migration = new CraftySyntaxMigration();
        $this->progress = new MigrationProgress();
        $this->validation = new MigrationValidation();
    }
    
    /**
     * Main wizard execution flow
     */
    public function execute() {
        // 1. Detection Phase
        if (!$this->detection->detect()) {
            return; // No Crafty Syntax found
        }
        
        // 2. User Confirmation
        if (!$this->showConfirmationScreen()) {
            return; // User declined migration
        }
        
        // 3. Migration Execution
        $this->executeMigration();
        
        // 4. Validation
        $this->validateMigration();
        
        // 5. Completion
        $this->showCompletionScreen();
    }
}
```

### 🔍 **Detection Implementation**

```php
class CraftySyntaxDetection {
    
    public function detect() {
        // Check for config.php
        if (!file_exists('config.php')) {
            return false;
        }
        
        // Analyze config.php content
        $config_content = file_get_contents('config.php');
        if (!$this->hasCraftySyntaxIndicators($config_content)) {
            return false;
        }
        
        // Test database connection
        if (!$this->testDatabaseConnection($config_content)) {
            return false;
        }
        
        // Check for legacy tables
        if (!$this->detectLegacyTables()) {
            return false;
        }
        
        return true;
    }
    
    private function hasCraftySyntaxIndicators($content) {
        $indicators = [
            '$livehelp_DB',
            'livehelp_',
            'crafty syntax',
            'CSLH'
        ];
        
        foreach ($indicators as $indicator) {
            if (stripos($content, $indicator) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private function detectLegacyTables() {
        $query = "SELECT COUNT(*) as count 
                  FROM information_schema.tables 
                  WHERE table_schema = DATABASE() 
                  AND table_name LIKE 'livehelp_%'";
        
        $result = $this->db->query($query);
        $count = $result->fetch_assoc()['count'];
        
        return $count > 0;
    }
}
```

### 🔄 **Migration Implementation**

```php
class CraftySyntaxMigration {
    
    private $migration_file;
    private $progress_tracker;
    
    public function __construct() {
        $this->migration_file = 'database/migrations/craftysyntax_to_lupopedia_mysql.sql';
        $this->progress_tracker = new MigrationProgress();
    }
    
    /**
     * Execute migration using existing SQL file
     */
    public function execute() {
        // Read migration SQL
        $sql_content = file_get_contents($this->migration_file);
        
        // Split into individual statements
        $statements = $this->splitSqlStatements($sql_content);
        
        // Execute statements with progress tracking
        foreach ($statements as $statement) {
            $this->executeStatement($statement);
            $this->progress_tracker->updateProgress($statement);
        }
    }
    
    private function executeStatement($statement) {
        try {
            $result = $this->db->query($statement);
            
            if ($result === false) {
                throw new MigrationException("SQL Error: " . $this->db->error);
            }
            
            // Log successful execution
            $this->logMigration($statement, true);
            
        } catch (Exception $e) {
            // Log error and continue (non-destructive)
            $this->logMigration($statement, false, $e->getMessage());
            throw $e;
        }
    }
    
    private function splitSqlStatements($sql) {
        // Split SQL into individual statements
        $statements = [];
        $current_statement = '';
        $lines = explode("\n", $sql);
        
        foreach ($lines as $line) {
            // Skip comments and empty lines
            if (trim($line) === '' || strpos(trim($line), '--') === 0) {
                continue;
            }
            
            $current_statement .= $line . "\n";
            
            // Check for statement terminator
            if (substr(trim($line), -1) === ';') {
                $statements[] = trim($current_statement);
                $current_statement = '';
            }
        }
        
        return $statements;
    }
}
```

### 📊 **Progress Tracking**

```php
class MigrationProgress {
    
    private $total_statements;
    private $completed_statements;
    private $current_table;
    
    public function updateProgress($statement) {
        $this->completed_statements++;
        
        // Extract table name from statement
        $this->current_table = $this->extractTableName($statement);
        
        // Calculate progress percentage
        $progress = ($this->completed_statements / $this->total_statements) * 100;
        
        // Update UI via WebSocket or AJAX
        $this->sendProgressUpdate([
            'percentage' => round($progress, 2),
            'current_table' => $this->current_table,
            'completed' => $this->completed_statements,
            'total' => $this->total_statements
        ]);
    }
    
    private function extractTableName($statement) {
        // Extract table name from INSERT INTO or ALTER TABLE statements
        if (preg_match('/INSERT INTO\s+(\w+)/i', $statement, $matches)) {
            return $matches[1];
        }
        
        if (preg_match('/ALTER TABLE\s+(\w+)/i', $statement, $matches)) {
            return $matches[1];
        }
        
        return 'Unknown';
    }
}
```

### ✅ **Validation Implementation**

```php
class MigrationValidation {
    
    public function validateMigration() {
        $results = [];
        
        // Row count validation
        $results['row_counts'] = $this->validateRowCounts();
        
        // Data integrity validation
        $results['data_integrity'] = $this->validateDataIntegrity();
        
        // Workflow validation
        $results['workflows'] = $this->validateWorkflows();
        
        return $results;
    }
    
    private function validateRowCounts() {
        $validations = [];
        
        // Compare legacy vs new table row counts
        $comparisons = [
            'livehelp_users' => 'lupo_auth_users',
            'livehelp_departments' => 'lupo_departments',
            'livehelp_transcripts' => 'lupo_dialog_threads',
            // Add more comparisons as needed
        ];
        
        foreach ($comparisons as $legacy => $new) {
            $legacy_count = $this->getTableCount($legacy);
            $new_count = $this->getTableCount($new);
            
            $validations[$legacy] = [
                'legacy_count' => $legacy_count,
                'new_count' => $new_count,
                'valid' => $new_count >= $legacy_count
            ];
        }
        
        return $validations;
    }
    
    private function validateWorkflows() {
        $workflows = [];
        
        // Test user login
        $workflows['user_login'] = $this->testUserLogin();
        
        // Test department access
        $workflows['department_access'] = $this->testDepartmentAccess();
        
        // Test chat functionality
        $workflows['chat_functionality'] = $this->testChatFunctionality();
        
        return $workflows;
    }
}
```

---

## 🛡️ Safety Features

### 🔒 **Non-Destructive Approach**

```php
class SafetyManager {
    
    public function executeSafely() {
        // 1. Create backup of critical tables
        $this->createBackups();
        
        // 2. Run migration in transaction
        $this->runInTransaction(function() {
            $this->executeMigration();
        });
        
        // 3. Validate results
        $this->validateResults();
        
        // 4. Only drop legacy tables if successful
        if ($this->validation_passed) {
            $this->dropLegacyTables();
        } else {
            $this->rollbackMigration();
        }
    }
    
    private function createBackups() {
        $critical_tables = [
            'livehelp_users',
            'livehelp_departments',
            'livehelp_transcripts'
        ];
        
        foreach ($critical_tables as $table) {
            $backup_sql = "CREATE TABLE {$table}_backup AS SELECT * FROM {$table}";
            $this->db->query($backup_sql);
        }
    }
}
```

### 📝 **Comprehensive Logging**

```php
class MigrationLogger {
    
    public function logMigration($statement, $success, $error = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'statement' => substr($statement, 0, 200) . '...',
            'success' => $success,
            'error' => $error,
            'memory_usage' => memory_get_usage(true),
            'execution_time' => $this->getExecutionTime()
        ];
        
        // Write to migration log
        $this->writeToLog($log_entry);
        
        // Update progress display
        $this->updateProgressDisplay($log_entry);
    }
}
```

---

## 🎨 User Experience Design

### 📱 **Responsive Design**

- **Mobile-friendly**: Works on all device sizes
- **Progressive enhancement**: Functions without JavaScript
- **Accessibility**: WCAG 2.1 compliant
- **Dark/Light mode**: System preference detection

### ⚡ **Performance Optimization**

- **Chunked processing**: Large datasets processed in batches
- **Memory management**: Controlled memory usage during migration
- **Progress updates**: Real-time feedback via WebSocket
- **Pause/Resume**: User control over migration process

### 🔔 **Notification System**

```php
class NotificationManager {
    
    public function sendProgressNotification($progress) {
        $notification = [
            'type' => 'progress',
            'percentage' => $progress['percentage'],
            'current_task' => $progress['current_table'],
            'estimated_remaining' => $progress['estimated_time']
        ];
        
        // Send via WebSocket
        $this->websocket->send($notification);
        
        // Email notification for long migrations
        if ($progress['estimated_time'] > 300) { // 5 minutes
            $this->sendEmailNotification($notification);
        }
    }
}
```

---

## 📋 Error Handling

### ⚠️ **Common Error Scenarios**

| Error Type | Detection | Resolution |
|------------|-----------|-------------|
| Database Connection | Connection test | Show configuration help |
| Insufficient Permissions | File system check | Provide permission guide |
| Memory Limit | Memory monitoring | Increase memory limit |
| Timeout | Execution time | Extend timeout or chunk |
| Data Corruption | Validation | Rollback and retry |

### 🔄 **Rollback Procedure**

```php
class RollbackManager {
    
    public function rollbackMigration() {
        // 1. Drop new tables
        $this->dropNewTables();
        
        // 2. Restore from backups
        $this->restoreFromBackups();
        
        // 3. Notify user
        $this->notifyRollback();
        
        // 4. Log rollback
        $this->logRollback();
    }
}
```

---

## 🎯 Success Criteria

### ✅ **Migration Success Indicators**

1. **Data Integrity**: All data migrated without loss
2. **Functionality**: All key workflows operational
3. **Performance**: System responsive and fast
4. **User Satisfaction**: Users can successfully use Live Help
5. **Validation**: All validation checks pass

### 📊 **Post-Migration Checklist**

- [ ] All user accounts accessible
- [ ] Departments and permissions correct
- [ ] Chat history viewable
- [ ] New features working
- [ ] Analytics data accurate
- [ ] Configuration settings applied
- [ ] No data corruption detected
- [ ] Performance acceptable

---

## 🚀 Deployment

### 📦 **Installation Requirements**

- **PHP Version**: 8.0+
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **Memory**: 256MB+ recommended
- **Disk Space**: 2x current database size
- **Permissions**: Read/Write to database and backup directory

### 🔧 **Configuration**

```php
// config/wizard.php
return [
    'migration' => [
        'batch_size' => 1000,
        'memory_limit' => '256M',
        'timeout' => 300,
        'backup_path' => '/tmp/crafty_syntax_backup/',
        'log_path' => '/logs/migration.log'
    ],
    'ui' => [
        'refresh_interval' => 1000, // milliseconds
        'show_detailed_progress' => true,
        'allow_pause' => true,
        'email_notifications' => false
    ]
];
```

---

## 📚 Documentation

### 📖 **User Guide**

1. **Detection**: Automatic detection of Crafty Syntax
2. **Confirmation**: Review migration summary
3. **Execution**: Monitor migration progress
4. **Validation**: Review migration results
5. **Completion**: Access enhanced Live Help system

### 🔧 **Developer Guide**

1. **Integration**: How to integrate with existing systems
2. **Customization**: How to customize migration logic
3. **Troubleshooting**: Common issues and solutions
4. **API Reference**: Complete API documentation

---

**Wizard Status: ✅ DESIGN COMPLETE**  
**Implementation**: Ready for development  
**Safety**: Non-destructive approach with rollback  
**User Experience**: Intuitive and informative interface  

---

*Generated by CURSOR on 2026-01-20*  
*Design based on craftysyntax_to_lupopedia_mysql.sql*  
*Non-destructive migration with comprehensive validation*
