# Filesystem to Database Migration Guide

**Version:** 2026.3.9.0
**Date:** 2026-01-31
**Status:** Ready for execution

---

## Overview

This guide documents the migration from filesystem-based agent and channel storage to database-backed storage with a modern hash-based upload structure.

### What's Changing

**Before:**
```
lupopedia/agents/0001/metadata.json
lupopedia/agents/0002/system_prompt.txt
lupopedia/channels/0001/contents.json
lupopedia/channels/0002/threads.json
```

**After:**
```
Database: lupo_agents, lupo_channels, lupo_agent_files, lupo_channel_files
Files: lupopedia/uploads/agents/2026/01/<hash>.json
       lupopedia/uploads/channels/2026/01/<hash>.json
```

---

## Migration Strategy

### Phase 1: Preparation
1. Run migration SQL to create new tables
2. Backup existing directories
3. Verify database connectivity

### Phase 2: Migration
1. Execute PHP migration script
2. Monitor progress and errors
3. Verify data integrity

### Phase 3: Validation
1. Test application functionality
2. Verify all files are accessible
3. Check migration logs for failures

### Phase 4: Cleanup
1. Run cleanup script to remove old directories
2. Keep backups for 30 days
3. Monitor for issues

---

## Step-by-Step Instructions

### Step 1: Run Migration SQL

```bash
cd /path/to/lupopedia
mysql -u root -p lupopedia < database/migrations/2026_01_31_migrate_filesystem_to_database.sql
```

**Expected Output:**
```
Query OK, 0 rows affected
Query OK, 0 rows affected
Query OK, 0 rows affected
```

**Verify Tables Created:**
```sql
SHOW TABLES LIKE 'lupo_%_files';
SHOW TABLES LIKE 'lupo_filesystem_migration_log';
```

### Step 2: Test Migration (Dry Run)

```bash
php scripts/migrate_filesystem_to_db.php --dry-run
```

**Expected Output:**
```
======================================================================
FILESYSTEM TO DATABASE MIGRATION
======================================================================
Mode: DRY RUN (no changes will be made)
Type: ALL (agents + channels)
======================================================================

✓ Database connected

======================================================================
MIGRATING AGENTS
======================================================================
Found 17 agent directories

Processing agent directory: 0001 ... [DRY-RUN] Would create agent: CAPTAIN ✓
Processing agent directory: 0002 ... [EXISTS: agent_id=2] ✓
...
```

**Review Output:**
- Check for any errors
- Verify directory counts match expectations
- Note which entities already exist

### Step 3: Run Live Migration

```bash
php scripts/migrate_filesystem_to_db.php
```

**Monitor Progress:**
- Watch for ✓ success marks
- Note any ✗ failure messages
- Check files_migrated counts

**Expected Output:**
```
======================================================================
MIGRATION STATISTICS
======================================================================
Agents processed:    17
  ✓ Success:         17
  ✗ Failed:          0

Channels processed:  12
  ✓ Success:         12
  ✗ Failed:          0

Files migrated:      245
Bytes processed:     12,458,963 (11.88 MB)
======================================================================
```

### Step 4: Verify Migration

**Check Migration Log:**
```sql
SELECT migration_type, status, COUNT(*) as count
FROM lupo_filesystem_migration_log
GROUP BY migration_type, status;
```

**Expected Result:**
```
+----------------+---------+-------+
| migration_type | status  | count |
+----------------+---------+-------+
| agents         | success |    17 |
| channels       | success |    12 |
+----------------+---------+-------+
```

**Check File Records:**
```sql
-- Agent files
SELECT COUNT(*) as total_files FROM lupo_agent_files WHERE is_deleted = 0;

-- Channel files
SELECT COUNT(*) as total_files FROM lupo_channel_files WHERE is_deleted = 0;
```

**Verify Upload Structure:**
```bash
ls -R lupopedia/uploads/agents/
ls -R lupopedia/uploads/channels/
```

**Expected Structure:**
```
lupopedia/uploads/agents/2026/01/
    ├── a3f5c8d9e2b1f4a6c7d8e9f0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8.json
    ├── b4e6d9f1a2c3b4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7.txt
    └── ...
```

### Step 5: Test Application

**Test Agent Access:**
```php
$agent = lupo_get_agent_by_key('CAPTAIN');
$files = lupo_get_agent_files($agent['agent_id']);
```

**Test Channel Access:**
```php
$channel = lupo_get_channel_by_key('channel_0001');
$files = lupo_get_channel_files($channel['channel_id']);
```

**Test File Upload:**
```php
$handler = lupo_get_upload_handler();
$result = $handler->upload($_FILES['file'], 'agent', 1, 'metadata');
```

### Step 6: Run Cleanup Script

⚠️ **WARNING:** Only run cleanup after verifying migration success!

```bash
# Dry run first (shows what would be removed)
php scripts/cleanup_old_directories.php

# Press Enter to continue
# Press Ctrl+C to cancel

# Cleanup will:
# 1. Verify migration status
# 2. Backup directories to /backups/
# 3. Remove old numeric directories
```

**Expected Output:**
```
======================================================================
VERIFYING MIGRATION STATUS
======================================================================
✓ No migration failures detected

Migration summary:
  agents: 17 directories, 85 files
  channels: 12 directories, 160 files

======================================================================
CLEANING UP AGENT DIRECTORIES
======================================================================
Found 17 agent directories

Processing 0001 ... ✓ removed (1.2 MB freed)
Processing 0002 ... ✓ removed (856 KB freed)
...

======================================================================
CLEANUP STATISTICS
======================================================================
Agent directories:
  Removed:         17
  Backed up:       17

Channel directories:
  Removed:         12
  Backed up:       12

Space freed:       12458963 bytes (11.88 MB)
Backup location:   /path/to/lupopedia/backups/filesystem_migration_20260131_143022
======================================================================
```

---

## Database Schema Reference

### lupo_agent_files

| Column | Type | Description |
|--------|------|-------------|
| file_id | BIGINT | Primary key |
| agent_id | BIGINT | References lupo_agents |
| file_type | VARCHAR(50) | metadata, system_prompt, config, etc |
| file_name | VARCHAR(255) | Original filename |
| file_path | VARCHAR(500) | Relative path from uploads root |
| file_hash | VARCHAR(64) | SHA256 hash for deduplication |
| file_size | BIGINT | File size in bytes |
| mime_type | VARCHAR(100) | MIME type |
| upload_ymdhis | CHAR(14) | Upload timestamp |
| created_ymdhis | CHAR(14) | Record creation |
| updated_ymdhis | CHAR(14) | Last update |
| is_deleted | TINYINT | Soft delete flag |
| migrated_from_directory | VARCHAR(255) | Original directory for tracking |

### lupo_channel_files

Same structure as `lupo_agent_files` but with `channel_id` instead of `agent_id`.

### lupo_filesystem_migration_log

| Column | Type | Description |
|--------|------|-------------|
| log_id | BIGINT | Primary key |
| migration_type | VARCHAR(50) | 'agents' or 'channels' |
| directory_path | VARCHAR(500) | Original directory |
| entity_type | VARCHAR(50) | 'agent' or 'channel' |
| entity_id | BIGINT | ID in target table |
| status | VARCHAR(50) | pending, success, failed |
| files_migrated | INT | Number of files migrated |
| error_message | TEXT | Error details if failed |
| started_ymdhis | CHAR(14) | Migration start time |
| completed_ymdhis | CHAR(14) | Migration completion |

---

## Upload Handler Usage

### Basic Upload

```php
require_once LUPOPEDIA_PATH . '/lupo-includes/functions/upload-handler.php';

// Handle file upload
$handler = lupo_get_upload_handler();

// Upload for agent
$result = $handler->upload(
    $_FILES['document'],  // File from form
    'agent',              // Entity type
    $agent_id,            // Agent ID
    'metadata'            // File type
);

if ($result['success']) {
    echo "File uploaded: " . $result['file_path'];
    if ($result['duplicate']) {
        echo " (duplicate detected)";
    }
}
```

### Set Upload Restrictions

```php
$handler = lupo_get_upload_handler();

// Set allowed MIME types
$handler->setAllowedTypes([
    'application/json',
    'text/plain',
    'text/markdown',
    'application/pdf'
]);

// Set max file size (10MB)
$handler->setMaxFileSize(10 * 1024 * 1024);

// Upload with validation
try {
    $result = $handler->upload($_FILES['file'], 'channel', $channel_id, 'document');
} catch (Exception $e) {
    echo "Upload failed: " . $e->getMessage();
}
```

### Retrieve Files

```php
// Get all files for an agent
$files = $handler->getEntityFiles($agent_id, 'agent');

foreach ($files as $file) {
    echo $file['file_name'] . " (" . $file['file_type'] . ")\n";
    echo "  Path: uploads/" . $file['file_path'] . "\n";
    echo "  Size: " . number_format($file['file_size']) . " bytes\n";
}
```

### Delete Files

```php
// Soft delete (keeps file on disk)
$handler->delete($file_id, 'agent', false);

// Hard delete (removes from disk)
$handler->delete($file_id, 'agent', true);
```

### Quick Helper Function

```php
// Simplified upload helper
$result = lupo_upload_file(
    $_FILES['avatar'],
    'operator',
    $operator_id,
    'avatar'
);
```

---

## Upload Structure Best Practices

### Directory Organization

```
uploads/
├── agents/
│   ├── 2026/
│   │   ├── 01/
│   │   │   ├── <hash1>.json
│   │   │   ├── <hash2>.txt
│   │   │   └── <hash3>.md
│   │   └── 02/
│   │       └── ...
│   └── 2027/
│       └── ...
├── channels/
│   └── 2026/01/...
├── operators/
│   └── 2026/01/...
└── content/
    └── 2026/01/...
```

### Hash-Based Filenames

**Benefits:**
- Automatic deduplication (same content = same hash)
- Content-addressable storage
- Prevents filename collisions
- Enables CDN caching by hash

**Format:**
```
<sha256_hash>.<extension>

Examples:
a3f5c8d9e2b1f4a6c7d8e9f0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8.json
b4e6d9f1a2c3b4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b1c2d3e4f5a6b7.txt
```

### Date-Based Paths

**Format:** `YYYY/MM/`

**Benefits:**
- Prevents directory bloat (max ~1000 files per month)
- Easy backup/archival by time period
- Natural chronological organization
- Filesystem performance optimization

**Example:**
```
2026/01/  ← January 2026 uploads
2026/02/  ← February 2026 uploads
2027/01/  ← January 2027 uploads
```

---

## Troubleshooting

### Migration Failures

**Problem:** Some directories fail to migrate

**Solution:**
```sql
-- Check migration log for errors
SELECT * FROM lupo_filesystem_migration_log WHERE status = 'failed';

-- View error details
SELECT directory_path, error_message
FROM lupo_filesystem_migration_log
WHERE status = 'failed';
```

**Common Causes:**
- Missing metadata.json file
- Invalid JSON format
- Insufficient disk space
- Database connection issues
- File permission problems

**Fix:**
1. Review error message
2. Fix underlying issue
3. Re-run migration for failed items:
   ```bash
   php scripts/migrate_filesystem_to_db.php --type=agents
   ```

### Duplicate Detection

**Problem:** Files are detected as duplicates

**This is normal!** The hash-based system automatically deduplicates identical files.

**Verify:**
```sql
SELECT file_hash, COUNT(*) as count
FROM lupo_agent_files
WHERE is_deleted = 0
GROUP BY file_hash
HAVING count > 1;
```

### Upload Permission Issues

**Problem:** Cannot write to uploads directory

**Solution:**
```bash
# Set correct permissions
chmod 755 lupopedia/uploads
chown www-data:www-data lupopedia/uploads

# Ensure subdirectories are writable
find lupopedia/uploads -type d -exec chmod 755 {} \;
```

### Large File Uploads

**Problem:** Uploads fail for large files

**Solution:**

Edit `php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

Edit `.htaccess` (if using Apache):
```apache
php_value upload_max_filesize 50M
php_value post_max_size 50M
php_value max_execution_time 300
```

---

## Rollback Procedure

If issues arise after migration, you can rollback:

### Step 1: Restore Directory Structure

```bash
# Copy backed-up directories back
cp -r backups/filesystem_migration_YYYYMMDD_HHMMSS/agents/* agents/
cp -r backups/filesystem_migration_YYYYMMDD_HHMMSS/channels/* channels/
```

### Step 2: Clear Migration Tables

```sql
TRUNCATE TABLE lupo_agent_files;
TRUNCATE TABLE lupo_channel_files;
TRUNCATE TABLE lupo_filesystem_migration_log;
```

### Step 3: Remove Uploads

```bash
rm -rf uploads/agents/
rm -rf uploads/channels/
```

### Step 4: Test Application

Verify that old filesystem-based access still works.

---

## Maintenance

### Regular Cleanup

**Remove old deleted files (after 90 days):**
```sql
-- Find old soft-deleted files
SELECT file_path, deleted_ymdhis
FROM lupo_agent_files
WHERE is_deleted = 1
AND deleted_ymdhis < DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 90 DAY), '%Y%m%d%H%i%s');

-- Hard delete from filesystem (run script)
```

### Monitor Disk Usage

```bash
# Check upload directory size
du -sh lupopedia/uploads/

# Check by entity type
du -sh lupopedia/uploads/agents/
du -sh lupopedia/uploads/channels/
du -sh lupopedia/uploads/operators/
```

### Backup Strategy

**Daily:** Backup database (includes file metadata)
```bash
mysqldump lupopedia lupo_agent_files lupo_channel_files > backup.sql
```

**Weekly:** Backup uploads directory
```bash
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz uploads/
```

---

## Performance Optimization

### Database Indexes

Indexes are automatically created by migration SQL:
- `idx_agent_id` - Fast file lookups by agent
- `idx_file_hash` - Fast duplicate detection
- `idx_file_type` - Fast filtering by file type
- `idx_upload_ymdhis` - Fast chronological queries

### File Serving

**Recommended:** Use web server to serve files directly

**Apache `.htaccess`:**
```apache
# Direct file serving for uploads
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^uploads/ - [L]
</IfModule>
```

**Nginx:**
```nginx
location /uploads/ {
    alias /path/to/lupopedia/uploads/;
    access_log off;
    expires 30d;
}
```

---

## Success Criteria

✓ All numeric directories migrated
✓ Zero migration failures in log
✓ All files accessible in new structure
✓ Application functionality verified
✓ Old directories backed up
✓ Old directories removed
✓ Space savings confirmed

---

## Support

**Issues:** https://github.com/lupopedia/lupopedia/issues
**Doctrine:** See `/docs/doctrine/` for schema rules
**TOON Files:** See `/docs/toons/` for table definitions

---

**End of Migration Guide**
