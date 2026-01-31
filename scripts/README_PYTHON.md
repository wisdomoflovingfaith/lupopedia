# Python Migration Scripts

Python versions of the filesystem-to-database migration and cleanup scripts.

## Prerequisites

### Python Version

- Python 3.7 or higher
- Check your version: `python --version` or `python3 --version`

### Install Dependencies

```bash
cd scripts
pip install -r requirements.txt
```

Or install manually:
```bash
pip install pymysql
```

## Scripts

### 1. migrate_filesystem_to_db.py

Migrates agent and channel metadata from numeric filesystem directories to database tables.

**Usage:**
```bash
# Dry run (no changes)
python scripts/migrate_filesystem_to_db.py --dry-run

# Migrate only agents
python scripts/migrate_filesystem_to_db.py --type=agents

# Migrate only channels
python scripts/migrate_filesystem_to_db.py --type=channels

# Migrate everything
python scripts/migrate_filesystem_to_db.py

# Migrate everything (explicit)
python scripts/migrate_filesystem_to_db.py --type=all
```

**Options:**
- `--dry-run`: Preview changes without modifying database or filesystem
- `--type=agents|channels|all`: Select what to migrate (default: all)

**Example Output:**
```
======================================================================
FILESYSTEM TO DATABASE MIGRATION
======================================================================
Mode: LIVE (changes will be applied)
Type: ALL
======================================================================

✓ Database connected
✓ Created upload directory: agents/
✓ Created upload directory: channels/

======================================================================
MIGRATING AGENTS
======================================================================
Found 17 agent directories

Processing agent directory: 0001 ... [EXISTS: agent_id=1] ✓
Processing agent directory: 0002 ... [DRY-RUN] Would create agent: AGENT_002 ✓
...

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

✓ Migration complete!
```

### 2. cleanup_old_directories.py

Safely removes old numeric directories after verifying migration success.

**Usage:**
```bash
# Interactive cleanup (prompts for confirmation)
python scripts/cleanup_old_directories.py

# Force cleanup (no confirmation)
python scripts/cleanup_old_directories.py --force

# Cleanup only agents
python scripts/cleanup_old_directories.py --type=agents

# Cleanup only channels
python scripts/cleanup_old_directories.py --type=channels
```

**Options:**
- `--force`: Skip verification and confirmation prompts
- `--type=agents|channels|all`: Select what to clean up (default: all)

**Example Output:**
```
======================================================================
OLD DIRECTORY CLEANUP SCRIPT
======================================================================
Force mode: NO (verify migration first)
Type: ALL
======================================================================

This script will:
  1. Verify migration status
  2. Backup directories to /backups/
  3. Remove old numeric directories

Press Ctrl+C to cancel, or press Enter to continue...

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

Space freed:       12,458,963 bytes (11.88 MB)
Backup location:   /path/to/lupopedia/backups/filesystem_migration_20260131_143022
======================================================================

✓ Cleanup complete!
```

## Configuration

Both scripts automatically read database configuration from `lupopedia-config.php`. No additional configuration is needed.

**Default values (if config file not found):**
- Host: localhost
- Port: 3306
- User: root
- Password: ServBay.dev
- Database: lupopedia
- Charset: utf8mb4

## Step-by-Step Migration Guide

### Step 1: Run SQL Migration

First, create the required database tables:

```bash
mysql -u root -p lupopedia < database/migrations/2026_01_31_migrate_filesystem_to_database.sql
```

### Step 2: Test Migration (Dry Run)

Preview what will be migrated without making changes:

```bash
python scripts/migrate_filesystem_to_db.py --dry-run
```

Review the output for any errors or warnings.

### Step 3: Run Actual Migration

Execute the migration:

```bash
python scripts/migrate_filesystem_to_db.py
```

Monitor the output for success/failure counts.

### Step 4: Verify Data

Check the database:

```sql
-- Check migration log
SELECT migration_type, status, COUNT(*) as count
FROM lupo_filesystem_migration_log
GROUP BY migration_type, status;

-- Check files migrated
SELECT COUNT(*) FROM lupo_agent_files;
SELECT COUNT(*) FROM lupo_channel_files;
```

Check the uploads directory:

```bash
ls -R uploads/agents/
ls -R uploads/channels/
```

### Step 5: Test Application

Verify that the application can access agents and channels using the new database-backed system.

### Step 6: Cleanup Old Directories

Once everything is verified, clean up the old directories:

```bash
python scripts/cleanup_old_directories.py
```

## Troubleshooting

### Import Error: No module named 'pymysql'

**Solution:**
```bash
pip install pymysql
```

### Database Connection Failed

**Check:**
1. Database is running
2. Credentials in `lupopedia-config.php` are correct
3. Database exists: `mysql -u root -p -e "SHOW DATABASES;"`

### Permission Denied: uploads/

**Solution:**
```bash
chmod 755 uploads/
chown www-data:www-data uploads/  # Linux/Mac
```

### Migration Failures

Check the migration log in the database:

```sql
SELECT * FROM lupo_filesystem_migration_log WHERE status = 'failed';
```

Review the error messages and fix the underlying issues, then re-run the migration.

### Script Hangs or Takes Too Long

For large datasets, the migration may take several minutes. Monitor progress in the console output.

## Differences from PHP Versions

The Python scripts are functionally identical to the PHP versions, with these differences:

**Advantages:**
- ✓ Cleaner error handling with Python exceptions
- ✓ Type hints for better code clarity
- ✓ argparse for robust command-line argument parsing
- ✓ pathlib for cross-platform path handling
- ✓ No PHP runtime required

**Compatibility:**
- ✓ Reads same PHP config file
- ✓ Uses same database schema
- ✓ Produces identical results
- ✓ Can be run alongside PHP versions

## Testing

### Unit Tests

Run tests (if available):
```bash
python -m pytest tests/
```

### Integration Test

Test the complete migration workflow:

```bash
# 1. Dry run
python scripts/migrate_filesystem_to_db.py --dry-run

# 2. Migrate a single agent
python scripts/migrate_filesystem_to_db.py --type=agents --dry-run

# 3. Verify output looks correct

# 4. Run actual migration
python scripts/migrate_filesystem_to_db.py --type=agents

# 5. Verify in database
mysql -u root -p lupopedia -e "SELECT * FROM lupo_agent_files LIMIT 5;"
```

## Performance

**Expected Performance:**
- ~100 directories per minute
- ~1000 files per minute
- Depends on file sizes and disk I/O

**Optimization Tips:**
- Run on SSD for faster I/O
- Use `--type=agents` or `--type=channels` to migrate in stages
- Ensure adequate disk space in uploads/ directory

## Rollback

If you need to rollback:

1. Restore directories from backup:
   ```bash
   cp -r backups/filesystem_migration_YYYYMMDD_HHMMSS/agents/* agents/
   cp -r backups/filesystem_migration_YYYYMMDD_HHMMSS/channels/* channels/
   ```

2. Clear migration tables:
   ```sql
   TRUNCATE TABLE lupo_agent_files;
   TRUNCATE TABLE lupo_channel_files;
   TRUNCATE TABLE lupo_filesystem_migration_log;
   ```

3. Remove uploads:
   ```bash
   rm -rf uploads/agents/
   rm -rf uploads/channels/
   ```

## Support

**Issues:** https://github.com/lupopedia/lupopedia/issues
**Documentation:** See `docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md`

## License

Same as parent Lupopedia project.
