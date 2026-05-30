---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.5/status/install_repair_plan.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.5/status/install_repair_plan.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/install-repair-plan.toon
  atoms_toon: null
  transcript_jsonl: 0/development/install_repair_plan
  artifact_type: documentation
  artifact_kind: plan
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: Install Repair Plan
  summary: Repair plan for identified install system blockers and issues.
---

# Install Repair Plan

## BLOCKERS IDENTIFIED

### 1. CRITICAL: install.php Header Version Violation

**Blocker:**
- install.php header_format_version is "4.1.2"
- System requires "4.1.5" per PRD 16 + PRD 86
- **Severity:** CRITICAL
- **File:** `install.php`
- **Cause:** Header not updated when doctrine changed

**Minimal Fix Path:**
1. Open `install.php`
2. Locate line 4: `header_format_version: "4.1.2"`
3. Change to: `header_format_version: "4.1.5"`
4. Save file

**Classification:** Installer
**Estimated Time:** 2 minutes

### 2. MEDIUM: Seed File Version Confusion

**Blocker:**
- Multiple seed file versions exist
- Installer may use outdated seed data
- **Severity:** MEDIUM
- **Files:** 
  - `install/seed_lupopedia_4_1_0.sql`
  - `database/lupopedia/mysql/seed/seed_4.1.0.sql`
  - `database/lupopedia/mysql/seed/seed_4.1.3.sql`
- **Cause:** Version-specific seed files not consolidated

**Minimal Fix Path:**
1. Archive old seed files to prevent confusion
2. Ensure installer uses consolidated seed file
3. Document canonical seed file usage

**Classification:** SQL
**Estimated Time:** 10 minutes

### 3. LOW: Backup File Proliferation

**Blocker:**
- Multiple timestamped backup SQL files
- Could cause maintenance confusion
- **Severity:** LOW
- **Files:** Various backup files with timestamps
- **Cause:** Development backup process not cleaned up

**Minimal Fix Path:**
1. Move backup files to archive directory
2. Create backup retention policy
3. Document backup procedures

**Classification:** Docs
**Estimated Time:** 15 minutes

## REPAIR PRIORITY

### IMMEDIATE (Before Testing)
1. **Fix install.php header version** (CRITICAL)
   - Must be done before any testing
   - Blocks installation in strict mode

### SHORT-TERM (After Basic Testing)
2. **Clean up seed file confusion** (MEDIUM)
   - Prevents potential data inconsistencies
   - Improves maintainability

### LONG-TERM (Maintenance)
3. **Archive backup files** (LOW)
   - Improves organization
   - Reduces confusion

## DETAILED REPAIR INSTRUCTIONS

### Repair 1: install.php Header Version

**File:** `install.php`
**Line:** 4

**Current:**
```php
*   header_format_version: "4.1.2"
```

**Required:**
```php
*   header_format_version: "4.1.5"
```

**Steps:**
1. Open `install.php` in text editor
2. Navigate to line 4
3. Change "4.1.2" to "4.1.5"
4. Save file
5. Verify change saved correctly

**Validation:**
- File should now pass PRD 16 + PRD 86 validation
- Installer should not be blocked in strict mode

### Repair 2: Seed File Consolidation

**Files to Archive:**
- `database/lupopedia/mysql/seed/seed_4.1.3.sql`

**Primary Seed File:**
- `install/seed_lupopedia_4_1_0.sql` (keep)
- `database/lupopedia/mysql/seed/seed_4.1.0.sql` (fallback)

**Steps:**
1. Create archive directory: `archive/seed-files/`
2. Move `seed_4.1.3.sql` to archive
3. Add README to archive explaining contents
4. Update installer documentation to clarify canonical seed file

**Validation:**
- Installer should use consolidated seed file
- No confusion about which seed file to use

### Repair 3: Backup File Cleanup

**Files to Archive:**
- `database/lupopedia/mysql/install/install_new_lupopedia_backup.sql`
- `database/lupopedia/mysql/install/install_new_lupopedia_backup_20260406.sql`
- `database/lupopedia/mysql/install/install_new_lupopedia_clean.sql`
- `database/lupopedia/mysql/import/import_from_old_crafty_syntax_old_20260406.sql`

**Steps:**
1. Create archive directory: `archive/sql-backups/`
2. Move backup files to archive with date stamps
3. Create index file documenting backup contents
4. Add cleanup procedure to maintenance documentation

**Validation:**
- Cleaner SQL directory structure
- Clear documentation of backup locations

## TESTING AFTER REPAIRS

### Post-Repair Validation
1. **Header Version Test:**
   - Run validator on install.php
   - Confirm no PRD validation errors
   - Verify installer starts without blocking

2. **Seed File Test:**
   - Run fresh install with clean database
   - Verify consolidated seed file used
   - Check table count (should be 162)

3. **Backup Cleanup Test:**
   - Verify SQL directory is clean
   - Confirm archived files accessible
   - Test documentation accuracy

### Regression Testing
1. **Fresh Install Test:**
   - Complete installation process
   - Verify all functionality works
   - Check for any new errors

2. **Upgrade Test:**
   - Test Crafty Syntax upgrade path
   - Verify data migration works
   - Confirm no SQL file confusion

## RISK MITIGATION

### During Repairs
- Create backup of install.php before editing
- Test header change in development environment first
- Document all changes for rollback

### After Repairs
- Monitor installation logs for issues
- Test both fresh install and upgrade paths
- Validate system functionality

## ROLLBACK PLAN

If Repairs Cause Issues:
1. **Header Version Rollback:**
   - Restore install.php from backup
   - Revert to "4.1.2" if needed
   - Document compatibility issues

2. **Seed File Rollback:**
   - Restore archived seed files
   - Update installer fallback logic
   - Test with original configuration

3. **Backup File Rollback:**
   - Restore backup files to original locations
   - Update documentation accordingly
   - No functional impact expected

## SUCCESS CRITERIA

### Repair Success Indicators
- [ ] install.php passes PRD 16 + PRD 86 validation
- [ ] Fresh install completes without errors
- [ ] Upgrade path works correctly
- [ ] No SQL file confusion
- [ ] Documentation is accurate

### Test Success Indicators
- [ ] All 162 tables created
- [ ] System functions in human-only mode
- [ ] API key handling works correctly
- [ ] No critical errors in logs
- [ ] Performance acceptable

## CONCLUSION

**CRITICAL BLOCKER:** install.php header version must be fixed before testing.
**MEDIUM CONCERN:** Seed file confusion should be addressed.
**LOW PRIORITY:** Backup file cleanup for maintenance.

**OVERALL ASSESSMENT:** Repairs are straightforward and low-risk. Critical fix is simple version number change.

**RECOMMENDATION:** Complete critical fix, then proceed with fresh reinstall testing.
