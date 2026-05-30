---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/versions/4.1.4/install_problems_we_fixed.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.4/install_problems_we_fixed.md"
  status: "active"
  when_updated: "20260422000000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/install_problems_we_fixed.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/install-problems-fixes"
  artifact_type: documentation
  artifact_kind: report
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "install-problems-we-fixed"
  default_collection_id: null
  lupopedia.schema: report
---

# Install Problems We Fixed

**Version:** 4.1.4  
**Date:** 2026-04-22  
**Context:** Crafty Syntax Upgrade Installation Issues  
**Status:** RESOLVED

## Overview

During the Crafty Syntax upgrade installation process, multiple critical SQL and PHP errors were encountered that prevented successful installation. This document documents all problems identified, root causes, and fixes applied.

## Problems Fixed

### 1. Crafty Syntax Upgrade Detection Failure

**Problem:** Installer reported "Crafty Syntax installation not found. Cannot perform upgrade." despite user having valid config.php and old tables.

**Root Cause:** The `craftyConfigExists()` method in `install_wizard_classes.php` was looking in wrong file paths due to empty `$_SERVER['DOCUMENT_ROOT']`.

**Fix Applied:**
- Enhanced detection paths in `InstallWizardCredentials::craftyConfigExists()` method
- Added additional search paths:
  - `__DIR__ . '/../config.php'`
  - `dirname(__DIR__) . '/config.php'`  
  - `getcwd() . '/config.php'`

**Files Modified:**
- `install_wizard_classes.php` (lines 233-239)

### 2. SQL Syntax Errors in install_new_lupopedia.sql

**Problem:** Multiple SQL syntax errors causing table creation failures.

#### 2.1 Orphaned Indexes for Ejected Tables

**Root Cause:** Index creation statements remained for tables that were ejected in 4.1.2.

**Fix Applied:** Removed orphaned indexes for:
- `lupo_agent_performance_stats` 
- `lupo_agent_definition_versions`
- `lupo_department_capabilities`

#### 2.2 Missing actor_pairing Table

**Root Cause:** Table was marked as ejected but INSERT statements still referenced it.

**Fix Applied:** 
- Restored complete `lupo_actor_pairing` table definition
- Added proper indexes for performance
- Removed MySQL-specific `ON DUPLICATE KEY UPDATE` clause

#### 2.3 Unquoted DEFAULT Values

**Root Cause:** String literals used as DEFAULT values without quotes.

**Fix Applied:**
- `DEFAULT local` → `DEFAULT 'local'` (line 2287)
- `DEFAULT default` → `DEFAULT 'default'` (line 2306)
- `DEFAULT active` → `DEFAULT 'active'` (line 2310)

#### 2.4 INSERT Statement Syntax Error

**Root Cause:** Missing comma after password hash and inline comment in VALUES clause.

**Fix Applied:**
- Added missing comma after password hash
- Removed inline comment from INSERT statement

**Files Modified:**
- `database/lupopedia/mysql/install/install_new_lupopedia.sql`

### 3. Seed File SQL Errors

**Problem:** Seed file `seed_lupopedia_4_1_0.sql` had column name and table reference errors.

#### 3.1 Wrong Seed File Initially Modified

**Root Cause:** Installer uses `install/seed_lupopedia_4_1_0.sql`, not the version in `database/` directory.

**Fix Applied:** Applied fixes to correct seed file used by installer.

#### 3.2 Incorrect Column Name

**Root Cause:** Seed used `node_base_url` but table schema has `base_url`.

**Fix Applied:** Changed column reference from `node_base_url` to `base_url` (line 18).

#### 3.3 Reference to Ejected Table

**Root Cause:** Seed tried to insert into `lupo_actor_relationships` which was ejected.

**Fix Applied:** Removed entire INSERT statement for actor_relationships and added explanatory comment.

**Files Modified:**
- `install/seed_lupopedia_4_1_0.sql`

## Technical Details

### Database Neutrality Compliance

All fixes ensure compliance with DATABASE_NEUTRAL_SQL_DOCTRINE:
- Removed MySQL-specific `ON DUPLICATE KEY UPDATE`
- Properly quoted string literals
- Cross-platform compatible syntax

### Schema Alignment

All changes maintain alignment between:
- `install_new_lupopedia.sql` (table definitions)
- `seed_lupopedia_4_1_0.sql` (seed data)
- Database doctrine requirements

## Impact Assessment

### Before Fixes
- Crafty Syntax upgrades completely blocked
- New installations failing on SQL errors
- Multiple table creation failures
- Seed data insertion failures

### After Fixes
- Crafty Syntax upgrade detection working
- All SQL syntax errors resolved
- Database neutrality maintained
- Installation process completes successfully

## Lessons Learned

1. **Path Detection Issues**: Environment variables like `$_SERVER['DOCUMENT_ROOT']` cannot be relied upon in all server configurations.

2. **Schema Drift Prevention**: When tables are ejected, all references (indexes, seed data, INSERT statements) must be removed simultaneously.

3. **File Location Awareness**: Multiple copies of files exist in different directories; must identify which version the installer actually uses.

4. **Database Neutrality**: MySQL-specific syntax must be avoided to maintain cross-platform compatibility.

## Verification

All fixes were tested and verified to:
- Allow Crafty Syntax config detection
- Enable successful table creation
- Permit seed data insertion
- Maintain database neutrality compliance

## Files Changed Summary

1. `install_wizard_classes.php` - Enhanced config detection paths
2. `database/lupopedia/mysql/install/install_new_lupopedia.sql` - Fixed SQL syntax errors
3. `install/seed_lupopedia_4_1_0.sql` - Corrected column names and removed ejected table references

## Resolution Status

✅ **COMPLETE** - All installation problems have been resolved.  
✅ **TESTED** - Fixes verified to work with Crafty Syntax upgrades.  
✅ **COMPLIANT** - All changes maintain database neutrality doctrine compliance.  
✅ **INSTALLED** - Crafty Syntax upgrade completed successfully on 2026-04-22.

## Remaining Testing

📋 **PENDING** - Test installation behavior with no API keys configured
- Purpose: Verify graceful handling of missing API keys
- Status: Will be tested later
- Expected: System should provide appropriate user guidance when keys are missing

The installer now successfully handles both new installations and Crafty Syntax upgrades without SQL errors. The system is operational with 2 API keys configured and ready for the no-key testing phase.
