# AGAPE File Fallback Mode Documentation

## Overview

AGAPE operates in two modes: DB mode (primary) and file fallback mode (degraded). When database connectivity is unavailable, AGAPE automatically switches to file-based JSON persistence to maintain continuous operation.

## Mode Detection Logic

### DB Mode Activation
```php
// Pseudocode for mode detection
if (database_connection_available()) {
    agape_mode = 'DB';
    use_database_factory();
} else {
    agape_mode = 'FILE_FALLBACK';
    use_file_storage();
}
```

### File Fallback Mode Activation
- **Trigger**: Database connection fails or is unavailable
- **Detection**: Test database connectivity on startup
- **Switch**: Automatic, transparent to calling code
- **Return**: DB mode when connection restored

## File Storage Structure

### Base Directory
```
database/agape/
```

### Subdirectories
```
database/agape/
├── events/          # Event records as JSON files
├── why/             # WHY files as JSON files  
├── alerts/          # Alert records as JSON files
└── runtime/         # Runtime state as JSON files
```

### File Naming Convention
- **Events**: `event_{event_id}.json`
- **WHY Files**: `why_{why_id}.json`
- **Alerts**: `alert_{alert_id}.json`
- **Runtime**: `runtime_{component}.json`

## Event Storage (File Mode)

### Event Record Format
```json
{
  "event_id": "evt_20260423183000_001",
  "event_type": "validation_failure|doctrine_violation|system_alert",
  "created_utc": "20260423183000",
  "actor_id": 123,
  "actor_slug": "agent_name",
  "severity": 0,
  "source": "file_or_system",
  "summary": "Brief description of event",
  "status": "active|resolved|escalated",
  "resolution": "Resolution details if available",
  "linked_why_file": "why_20260423183000_001",
  "fallback_mode": true,
  "file_path": "database/agape/events/evt_20260423183000_001.json"
}
```

### Event File Operations
```php
// File mode event storage
function store_event_file($event_data) {
    $filename = "event_{$event_data['event_id']}.json";
    $filepath = "database/agape/events/{$filename}";
    return file_put_contents($filepath, json_encode($event_data, JSON_PRETTY_PRINT));
}
```

## WHY File Storage (File Mode)

### WHY Record Format
```json
{
  "why_id": "why_20260423183000_001",
  "created_utc": "20260423183000",
  "violation_type": "doctrine_violation",
  "severity": 2,
  "source_artifact": "agents/agent_name/config.json",
  "source_instruction": "Invalid header format detected",
  "detected_by": "AGAPE",
  "explanation": "Header missing required fields per PRD 16_C",
  "suggested_fix": "Add missing header fields in correct order",
  "resolved_utc": null,
  "file_path": "database/agape/why/why_20260423183000_001.json"
}
```

### WHY File Operations
```php
// File mode WHY file storage
function store_why_file($why_data) {
    $filename = "why_{$why_data['why_id']}.json";
    $filepath = "database/agape/why/{$filename}";
    return file_put_contents($filepath, json_encode($why_data, JSON_PRETTY_PRINT));
}
```

## Alert Storage (File Mode)

### Alert Record Format
```json
{
  "alert_id": "alert_20260423183000_001",
  "created_utc": "20260423183000",
  "alert_type": "repeated_violation|system_boundary|escalation",
  "severity": 2,
  "source_actor_id": 123,
  "source_actor_slug": "agent_name",
  "message": "Repeated validation failures detected",
  "details": "Actor has 5 violations in last hour",
  "status": "active|acknowledged|resolved",
  "file_path": "database/agape/alerts/alert_20260423183000_001.json"
}
```

## Runtime State Storage (File Mode)

### Runtime Record Format
```json
{
  "component": "agape_core",
  "last_updated_utc": "20260423183000",
  "current_mode": "FILE_FALLBACK",
  "db_last_available": "20260423180000",
  "events_processed": 42,
  "why_files_generated": 3,
  "alerts_active": 1,
  "file_path": "database/agape/runtime/runtime_agape_core.json"
}
```

## Mode Switching Logic

### DB to File Fallback
```php
function switch_to_file_fallback() {
    // 1. Log mode switch
    store_event_file([
        'event_type' => 'system_alert',
        'summary' => 'AGAPE switching to file fallback mode',
        'severity' => 1,
        'fallback_mode' => true
    ]);
    
    // 2. Update runtime state
    update_runtime_state(['current_mode' => 'FILE_FALLBACK']);
    
    // 3. Ensure directories exist
    ensure_file_fallback_directories();
}
```

### File Fallback to DB Recovery
```php
function switch_to_db_mode() {
    // 1. Test DB connection
    if (!database_connection_available()) {
        return false;
    }
    
    // 2. Log mode recovery
    store_event_db([
        'event_type' => 'system_alert',
        'summary' => 'AGAPE recovered to DB mode',
        'severity' => 0
    ]);
    
    // 3. Migrate file data to DB
    migrate_file_data_to_db();
    
    // 4. Update runtime state
    update_runtime_state(['current_mode' => 'DB']);
    
    return true;
}
```

## Data Migration

### File to DB Migration
```php
function migrate_file_data_to_db() {
    // Migrate events
    foreach (glob('database/agape/events/*.json') as $file) {
        $event_data = json_decode(file_get_contents($file), true);
        store_event_db($event_data);
        unlink($file); // Remove migrated file
    }
    
    // Migrate WHY files
    foreach (glob('database/agape/why/*.json') as $file) {
        $why_data = json_decode(file_get_contents($file), true);
        store_why_db($why_data);
        unlink($file); // Remove migrated file
    }
    
    // Migrate alerts
    foreach (glob('database/agape/alerts/*.json') as $file) {
        $alert_data = json_decode(file_get_contents($file), true);
        store_alert_db($alert_data);
        unlink($file); // Remove migrated file
    }
}
```

## Directory Management

### Ensure Directories Exist
```php
function ensure_file_fallback_directories() {
    $directories = [
        'database/agape/',
        'database/agape/events/',
        'database/agape/why/',
        'database/agape/alerts/',
        'database/agape/runtime/'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
```

### Cleanup Old Files
```php
function cleanup_old_files($days_old = 30) {
    $cutoff = time() - ($days_old * 24 * 60 * 60);
    
    foreach (['events', 'why', 'alerts'] as $type) {
        foreach (glob("database/agape/{$type}/*.json") as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }
}
```

## Performance Considerations

### File Mode Limitations
- No transaction support
- Slower than DB mode for high volume
- No complex queries or indexing
- Manual cleanup required

### Optimization Strategies
- Batch file operations where possible
- Use file locking for concurrent access
- Implement simple indexing in runtime files
- Regular cleanup of old files

## Error Handling

### File Operation Errors
```php
function safe_file_write($filepath, $data) {
    $result = file_put_contents($filepath, $data, LOCK_EX);
    if ($result === false) {
        // Log error and attempt fallback
        store_event_file([
            'event_type' => 'system_alert',
            'summary' => "Failed to write file: {$filepath}",
            'severity' => 2
        ]);
        return false;
    }
    return true;
}
```

### Disk Space Monitoring
```php
function check_disk_space() {
    $free_space = disk_free_space('database/agape/');
    $min_space = 100 * 1024 * 1024; // 100MB minimum
    
    if ($free_space < $min_space) {
        store_event_file([
            'event_type' => 'system_alert',
            'summary' => 'Low disk space in file fallback mode',
            'severity' => 2
        ]);
        cleanup_old_files(7); // Clean up files older than 7 days
    }
}
```

## Testing File Fallback Mode

### Test Scenarios
1. **DB Unavailable**: Simulate database connection failure
2. **File Operations**: Test all file read/write operations
3. **Mode Switching**: Test DB to file and file to DB transitions
4. **Data Migration**: Verify file to DB data migration
5. **Concurrent Access**: Test multiple processes using file mode

### Validation Checklist
- [ ] Directory creation works correctly
- [ ] File operations complete successfully
- [ ] Data integrity is maintained
- [ ] Mode switching is transparent
- [ ] Error handling is robust
- [ ] Performance is acceptable

---
**Last Updated**: 20260423183000  
**Agent**: AGAPE v2.0.0  
**Status**: Active File Fallback Documentation
