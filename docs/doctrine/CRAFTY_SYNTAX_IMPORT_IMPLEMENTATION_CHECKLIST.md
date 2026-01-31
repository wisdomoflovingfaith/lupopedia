# Crafty Syntax Import Wizard - Concrete Implementation Checklist

**Version 4.3.3**  
**2026-01-20**  

## 1. File Creation Checklist

### Controllers
```
app/Controllers/CraftyImportController.php
```

### Services
```
app/Services/CraftyImportService.php
app/Services/CraftyDetectionService.php
app/Services/CraftyConfigTransformer.php
app/Services/CraftyMigrationService.php
app/Services/CraftyValidationService.php
app/Services/CraftyProgressService.php
```

### Views/Templates
```
resources/views/import_wizard/detection.blade.php
resources/views/import_wizard/confirmation.blade.php
resources/views/import_wizard/progress.blade.php
resources/views/import_wizard/complete.blade.php
resources/views/import_wizard/error.blade.php
resources/views/import_wizard/rollback.blade.php
```

### Routes
```
routes/web.php (import wizard routes)
```

### Migration Runners
```
app/Services/CraftyMigrationRunner.php
app/Services/CraftyBackupService.php
```

### Validation Utilities
```
app/Utilities/CraftyValidationUtils.php
app/Utilities/CraftyDataIntegrityChecker.php
```

### Logging Utilities
```
app/Logging/CraftyImportLogger.php
```

### Configuration
```
config/crafty_import.php
```

## 2. Route Checklist

```
GET  /import/crafty/detect     - Detect Crafty Syntax installation
GET  /import/crafty            - Show detection/confirmation screen
POST /import/crafty/start      - Start migration process
GET  /import/crafty/progress   - Get migration progress (AJAX/WebSocket)
POST /import/crafty/pause      - Pause migration
POST /import/crafty/resume     - Resume migration
POST /import/crafty/cancel     - Cancel migration with rollback
GET  /import/crafty/validate   - Validate migration results
GET  /import/crafty/complete   - Show completion screen
GET  /import/crafty/error      - Show error screen
POST /import/crafty/rollback   - Manual rollback
```

## 3. Controller Responsibilities

### CraftyImportController
```php
detect()     - Check for config.php and legacy tables, show detection screen
index()     - Show confirmation screen with data summary
start()     - Initialize migration, create backups, transform config, start execution
progress()  - Return JSON progress data for AJAX updates
pause()     - Pause migration execution
resume()    - Resume paused migration
cancel()    - Cancel migration and trigger rollback
validate()  - Run validation checks, return results
complete()  - Show completion screen with next steps
error()     - Show error screen with troubleshooting
rollback()  - Execute manual rollback procedure
```

## 4. Service Layer Responsibilities

### CraftyDetectionService
```php
detectConfigFile()     - Check for config.php existence
analyzeConfigContent()  - Parse config.php for Crafty Syntax indicators:
                          * "Sales Syntax Live Help" copyright
                          * $dbtype = 'mysql'
                          * $application_root variable
                          * Database variables ($server, $database, $datausername, $password)
                          * livehelp/CSLH references
                          * setup.php references
testDatabaseConnection() - Validate database connectivity using extracted credentials
extractDatabaseCredentials() - Parse $server, $database, $datausername, $password from config
detectLegacyTables()   - Count livehelp_* tables in database
getDataSummary()       - Get database name, server, path info for display
```

### CraftyConfigTransformer (NEW)
```php
transformConfig()         - Convert config.php to config/lupopedia.php
extractDatabaseCredentials() - Parse database credentials from Crafty config
generateLupopediaConfig() - Create new Lupopedia config array format
updateApplicationPaths()   - Convert /lh/ paths to /lupopedia/ paths
validateNewConfig()       - Test new config file functionality
```

### CraftyMigrationService
```php
readMigrationSQL()     - Load craftysyntax_to_lupopedia_mysql.sql
splitSQLStatements()   - Split SQL into executable statements
executeStatement()     - Execute single statement with error handling
executeInTransaction()  - Run migration in database transaction
trackProgress()        - Update progress tracking
```

### CraftyBackupService
```php
createBackups()         - Backup critical legacy tables
restoreFromBackups()   - Restore from backup on rollback
cleanupBackups()        - Remove backup files after successful migration
```

### CraftyValidationService
```php
validateRowCounts()     - Compare legacy vs new table row counts
validateDataIntegrity() - Check data corruption
validateWorkflows()     - Test key user workflows
generateValidationReport() - Create validation summary
```

### CraftyProgressService
```php
initializeProgress()    - Set up progress tracking
updateProgress()        - Update current progress state
getProgressData()       - Return progress for UI
estimateTimeRemaining() - Calculate completion time
```

## 5. Wizard UI Checklist

### Detection Screen
Shows Crafty Syntax detection with database name, server, path info, and migration benefits.

### Confirmation Screen
Displays detailed data summary including database credentials, path migration (/lh/ → /lupopedia/), and asks user to confirm migration.

### Progress Screen
Real-time progress bar with current table, completion percentage, config transformation status, and migration log.

### Completion Screen
Success summary with new config file location, path updates, temporal features overview, and next step links.

### Error Screen
Error details, config transformation issues, troubleshooting steps, and rollback options.

### Rollback Screen
Rollback progress, config restoration, and confirmation when manual rollback is triggered.

## 6. Bootstrap Logic

```php
// app/Bootstrap/CraftyImportBootstrap.php
public function checkFirstRun()
{
    // Check for existing Lupopedia config first
    if (file_exists('config/lupopedia.php')) {
        return null; // Already migrated
    }
    
    // Check for Crafty Syntax config
    if (file_exists('config.php')) {
        $detector = new CraftyDetectionService();
        if ($detector->detect()) {
            // Store migration flag in session
            $_SESSION['crafty_syntax_detected'] = true;
            return redirect('/import/crafty');
        }
    }
    return null; // No migration needed
}
```

## 7. Safety Requirements

### Non-Destructive Until Validation
- Create backups before any table modifications
- Run migration in transaction
- Only drop legacy tables after successful validation
- Preserve backup files until user confirms success

### Full Rollback Capability
- Backup all critical tables before migration
- Store rollback SQL statements
- Implement rollback() method in service layer
- Test rollback procedure in development

### Transaction-Based Execution
- Wrap entire migration in database transaction
- Commit only after all validation passes
- Rollback automatically on any error
- Log all transaction states

### SQL-Only Doctrine Compliance
- Use only existing craftysyntax_to_lupopedia_mysql.sql
- No invented transformations or mappings
- No new tables or columns
- Strict adherence to documented migrations

## 8. Implementation Priority

### Phase 1 - Core Infrastructure
1. CraftyDetectionService
2. CraftyConfigTransformer (NEW)
3. CraftyImportController (detect, index methods)
4. Basic detection and confirmation views
5. Route definitions

### Phase 2 - Migration Engine
1. CraftyMigrationService
2. CraftyBackupService
3. CraftyProgressService
4. Progress tracking and AJAX endpoints
5. Config transformation integration

### Phase 3 - Validation & Safety
1. CraftyValidationService
2. Rollback functionality
3. Error handling and logging
4. Completion and error views
5. Config restoration procedures

### Phase 4 - Integration & Testing
1. Bootstrap integration with config/lupopedia.php check
2. End-to-end testing with real config.php
3. Path migration testing (/lh/ → /lupopedia/)
4. Performance optimization
5. Documentation updates

## 9. Key Technical Requirements

### Memory Management
- Process SQL in chunks of 1000 rows
- Monitor memory usage during migration
- Implement garbage collection in long-running processes

### Error Handling
- Catch and log all database errors
- Provide user-friendly error messages
- Implement automatic retry for transient errors

### Performance
- Use AJAX/WebSocket for real-time progress
- Optimize large table imports with batching
- Implement pause/resume functionality

### Security
- Validate all user inputs
- Sanitize config file paths and database credentials
- Protect config.php during transformation
- Secure temporary backup files

---

**Implementation Status: Ready for Development**
**All components mapped to existing SQL migration logic**
**No architecture expansion or new features**
**Strict adherence to migration doctrine**

---

## SECTION 10 — Crafty Syntax → Lupopedia Integration Requirements (Feb 7–9)

### A. Legacy Login Completion
- [ ] MD5 → bcrypt upgrade flow
- [ ] Redirect-back logic
- [ ] Avatar dropdown
- [ ] Operator detection
- [ ] Session upgrade logic

### B. Crafty Syntax Operator Admin Panel
- [ ] Operator dashboard
- [ ] Operator presence (online/offline/away)
- [ ] Operator expertise (domain + department)
- [ ] Operator routing rules
- [ ] Operator status updates
- [ ] Operator activity feed

### C. Livehelp_js Integration
- [ ] JS icon on external pages
- [ ] Visitor session initialization
- [ ] Visitor tracking
- [ ] Incoming chat request generation
- [ ] Operator assignment
- [ ] Escalation to human operator

### D. Multi-Channel Operator Screen
- [ ] Multi-color channel UI
- [ ] Operator can handle multiple chats
- [ ] Channel switching
- [ ] Notifications
- [ ] Message history
- [ ] Typing indicators

### E. REST API for Cross-Install Operator Routing
- [ ] API endpoint for requesting an operator
- [ ] Expertise-based routing
- [ ] Department-based routing
- [ ] Authentication
- [ ] Rate limiting
- [ ] JSON response format
- [ ] Error codes

### F. Documentation Landing Collection (Collection 0)
- [ ] Create or verify Collection 0 exists
- [ ] Ensure Collection 0 is system-owned and always available
- [ ] Populate Collection 0 with all Lupopedia documentation tabs
- [ ] Ensure navigation displays all documentation tabs
- [ ] Ensure /collection/0/lupopedia loads the documentation viewer by default
- [ ] Redirect Crafty Syntax users to Collection 0 after upgrade
- [ ] Ensure documentation reflects current Lupopedia system
- [ ] Complete documentation by Feb 7 (progress report)
- [ ] Polish documentation UI for Feb 9 demo

---

## SECTION 11 — Sprint Plan (Feb 1–9, 2026)

### Milestone 1 — Feb 1–3
- [ ] Finalize login system
- [ ] Finalize session upgrade logic
- [ ] Implement operator detection
- [ ] Implement avatar dropdown
- [ ] Begin operator admin panel skeleton

### Milestone 2 — Feb 3–5
- [ ] Implement Livehelp_js traffic ingestion
- [ ] Implement operator presence + status
- [ ] Implement operator dashboard basics
- [ ] Begin multi-channel UI prototype

### Milestone 3 — Feb 5–7 (Progress Report Due)
- [ ] Multi-channel UI functional prototype
- [ ] Livehelp traffic visible
- [ ] Operator panel usable
- [ ] REST API endpoints stubbed
- [ ] Documentation updated
- [ ] Collection 0 created and populated
- [ ] Documentation tabs visible in navigation
- [ ] Auto-redirect after upgrade working
- [ ] Documentation content updated to current system
- [ ] Submit progress report

### Milestone 4 — Feb 7–9 (Demo Prep)
- [ ] End-to-end operator workflow
- [ ] Multi-channel UI polished
- [ ] REST API functional
- [ ] Cross-install operator routing working
- [ ] Documentation viewer polished
- [ ] Navigation cleaned and finalized
- [ ] Demo flow: upgrade → Collection 0 → operator panel
- [ ] Demo script prepared

---

## SECTION 12 — Notes for AI Agents

All AI coding agents (JetBrains, Cursor, Claude, Windsurf, etc.) must:

- Respect the actor model (`actor_id`, not `user_id`)
- Respect database doctrine (no FK, no triggers, no stored procedures)
- Use `YYYYMMDDHHIISS` UTC timestamps
- Treat TOON files in `/docs/toons/` as schema source of truth
- Respect Python vs PHP boundaries
- Respect upload structure (hash-based, date-based)
- Respect LEXA's enforcement role
- Never invent schema or modify schema without TOON source
- Never modernize away from doctrine

### Side Note — Fantastico Re-Added Crafty Syntax 3.7.5

Fantastico (netenberg.com) has reintroduced Crafty Syntax 3.7.5 into their installer packages.

This means:

- New Crafty Syntax installs will continue to appear across shared hosting environments.
- The import wizard must remain compatible with fresh 3.7.5 installs.
- Migration testing should include Fantastico-provisioned environments.
- No changes to the current sprint timeline are required.
- Expect increased upgrade volume once Lupopedia documentation and Collection 0 are live.

This is an informational note only and does not modify doctrine or requirements.
