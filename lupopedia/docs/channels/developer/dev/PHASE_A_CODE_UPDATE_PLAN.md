---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.20
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-15
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: @everyone
  message: "Created detailed code update plan for Phase A schema federation - comprehensive, step-by-step, testable approach."
  mood: "00FF00"
tags:
  categories: ["documentation", "migration", "development"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Phase A Code Update Plan"
  description: "Detailed, step-by-step plan for updating PHP code to use schema-qualified table names after Phase A migration"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# Phase A Code Update Plan (Path D)

## Overview

This plan provides a comprehensive, step-by-step approach to updating all PHP code to use schema-qualified table names after Phase A schema federation migration.

**Strategy**: Update code first (before migration), test incrementally, verify schema resolution.

---

## Prerequisites

1. ✅ `lupo-includes/schema-config.php` created
2. ✅ Helper functions (`lupo_table()`, `lupo_table_schema()`) available
3. ✅ Table classification maps defined
4. ✅ Phase A migration SQL files ready

---

## Phase 1: Bootstrap Schema Config

### Step 1.1: Include Schema Config in Bootstrap

**File**: `lupo-includes/bootstrap.php`

**Action**: Add schema config include after database connection

```php
// After database connection setup (around line 100)
require_once(LUPO_INCLUDES_DIR . '/schema-config.php');
```

**Test**: 
```php
// Add temporary test
var_dump(lupo_table('lupo_audit_log')); // Should output: lupopedia_orchestration.lupo_audit_log
var_dump(lupo_table('lupo_sessions'));  // Should output: lupopedia_ephemeral.lupo_sessions
var_dump(lupo_table('lupo_actors'));     // Should output: lupopedia.lupo_actors
```

**Verification**: 
- Schema config loads without errors
- Helper functions resolve correctly
- All three schemas resolve as expected

**Rollback**: Remove include line

---

## Phase 2: Core Database Classes

### Step 2.1: Update PDO_DB Class

**File**: `lupo-includes/class-pdo_db.php`

**Status**: ✅ Already supports schema-qualified names via `quoteIdentifier()`

**Action**: No changes needed - class already handles `schema.table` format

**Test**: Verify existing functionality works:
```php
$db = new PDO_DB($host, $user, $pass, $dbname);
$db->query("SELECT * FROM lupopedia_orchestration.lupo_audit_log LIMIT 1");
```

**Verification**: Cross-schema queries work correctly

---

## Phase 3: Authentication Module

### Step 3.1: Update Auth Controller

**File**: `lupo-includes/modules/auth/auth-controller.php`

**Action**: Find all table references and update:

**Pattern to Find**:
```php
// Find patterns like:
FROM lupo_sessions
INTO lupo_sessions
UPDATE lupo_sessions
JOIN lupo_sessions
```

**Update Pattern**:
```php
// Before
"SELECT * FROM lupo_sessions WHERE session_id = :id"

// After
"SELECT * FROM " . lupo_table('lupo_sessions') . " WHERE session_id = :id"
```

**Tables to Update**:
- `lupo_sessions` → `lupopedia_ephemeral.lupo_sessions`
- `lupo_auth_users` → `lupopedia.lupo_auth_users` (core)
- `lupo_auth_providers` → `lupopedia.lupo_auth_providers` (core)

**Test**: 
- Login flow works
- Session creation works
- Session validation works

**Verification**: Check database queries use correct schema

---

### Step 3.2: Update Auth Helpers

**File**: `lupo-includes/functions/auth-helpers.php`

**Action**: Update all table references

**Tables**: Same as Step 3.1

**Test**: All auth helper functions work correctly

---

### Step 3.3: Update Auth UI Helpers

**File**: `lupo-includes/functions/auth-ui-helpers.php`

**Action**: Update all table references

**Test**: UI components render correctly

---

## Phase 4: Truth Module

### Step 4.1: Update Truth Model

**File**: `lupo-includes/modules/truth/truth-model.php`

**Action**: Update all table references

**Tables**: All `lupo_truth_*` tables → `lupopedia.lupo_truth_*` (core)

**Test**: Truth queries work correctly

---

## Phase 5: Collections and Content

### Step 5.1: Update Saved Collections Renderer

**File**: `lupo-includes/functions/render-saved-collections.php`

**Action**: Update table references

**Tables**: 
- `lupo_collections` → `lupopedia.lupo_collections` (core)
- `lupo_collection_tabs` → `lupopedia.lupo_collection_tabs` (core)

**Test**: Collections render correctly

---

## Phase 6: Search and Indexing

### Step 6.1: Update Search Indexer

**File**: `lupo-includes/class-SearchIndexer.php`

**Action**: Update table references

**Tables**:
- `lupo_search_index` → `lupopedia.lupo_search_index` (core)
- `lupo_search_rebuild_log` → `lupopedia_orchestration.lupo_search_rebuild_log` ⚠️

**Test**: Search indexing works correctly

---

## Phase 7: Agent and Orchestration

### Step 7.1: Find All Agent Table References

**Action**: Search for agent-related tables

**Tables to Update**:
- `lupo_agent_context_snapshots` → `lupopedia_orchestration.lupo_agent_context_snapshots`
- `lupo_agent_dependencies` → `lupopedia_orchestration.lupo_agent_dependencies`
- `lupo_agent_external_events` → `lupopedia_orchestration.lupo_agent_external_events`
- `lupo_agent_tool_calls` → `lupopedia_orchestration.lupo_agent_tool_calls`
- `lupo_agent_versions` → `lupopedia_orchestration.lupo_agent_versions`
- `lupo_agents` → `lupopedia.lupo_agents` (core)
- `lupo_agent_registry` → `lupopedia.lupo_agent_registry` (core)

**Files to Check**:
- All files in `lupo-includes/modules/agents/`
- All files in `lupo-includes/functions/` that reference agents

---

## Phase 8: Analytics and Ephemeral Data

### Step 8.1: Update Analytics References

**Action**: Find all analytics table references

**Tables to Update**:
- `lupo_analytics_campaign_vars_daily` → `lupopedia_ephemeral.lupo_analytics_campaign_vars_daily`
- `lupo_analytics_paths_daily` → `lupopedia_ephemeral.lupo_analytics_paths_daily`
- `lupo_analytics_referers_daily` → `lupopedia_ephemeral.lupo_analytics_referers_daily`
- `lupo_analytics_visits_daily` → `lupopedia_ephemeral.lupo_analytics_visits_daily`
- `lupo_analytics_visits` → `lupopedia_ephemeral.lupo_analytics_visits`
- `lupo_analytics_*_monthly` → `lupopedia.lupo_analytics_*_monthly` (core - monthly stays in core)

**Files to Check**:
- All analytics-related files
- Tracking scripts
- Reporting modules

---

## Phase 9: API and Rate Limiting

### Step 9.1: Update API References

**Action**: Find all API table references

**Tables to Update**:
- `lupo_api_tokens` → `lupopedia_ephemeral.lupo_api_tokens`
- `lupo_api_clients` → `lupopedia_ephemeral.lupo_api_clients`
- `lupo_api_webhooks` → `lupopedia_ephemeral.lupo_api_webhooks`
- `lupo_api_rate_limits` → `lupopedia_orchestration.lupo_api_rate_limits`
- `lupo_api_token_logs` → `lupopedia_orchestration.lupo_api_token_logs`

**Files to Check**:
- All API controller files
- Rate limiting middleware
- Webhook handlers

---

## Phase 10: System and Orchestration

### Step 10.1: Update System Tables

**Action**: Find all system table references

**Tables to Update**:
- `lupo_system_events` → `lupopedia_orchestration.lupo_system_events`
- `lupo_system_config` → `lupopedia_orchestration.lupo_system_config`
- `lupo_audit_log` → `lupopedia_orchestration.lupo_audit_log`
- `lupo_notifications` → `lupopedia_orchestration.lupo_notifications`
- `lupo_governance_overrides` → `lupopedia_orchestration.lupo_governance_overrides`

**Files to Check**:
- System event handlers
- Configuration management
- Audit logging
- Notification system

---

## Phase 11: Memory and Debugging

### Step 11.1: Update Memory Tables

**Action**: Find all memory table references

**Tables to Update**:
- `lupo_memory_debug_log` → `lupopedia_orchestration.lupo_memory_debug_log`
- `lupo_memory_events` → `lupopedia_orchestration.lupo_memory_events`
- `lupo_memory_rollups` → `lupopedia_orchestration.lupo_memory_rollups`
- `lupo_interpretation_log` → `lupopedia_orchestration.lupo_interpretation_log`

**Files to Check**:
- Memory management modules
- Debug logging
- Interpretation handlers

---

## Phase 12: anubis and Event Management

### Step 12.1: Update anubis Tables

**Action**: Find all anubis table references

**Tables to Update**:
- `lupo_anubis_events` → `lupopedia_orchestration.lupo_anubis_events`
- `lupo_anubis_orphaned` → `lupopedia_orchestration.lupo_anubis_orphaned`
- `lupo_anubis_redirects` → `lupopedia_orchestration.lupo_anubis_redirects`

**Files to Check**:
- anubis event handlers
- Redirect management
- Orphan detection

---

## Phase 13: Content Processing

### Step 13.1: Update Content Processing Tables

**Action**: Find all content processing table references

**Tables to Update**:
- `lupo_narrative_fragments` → `lupopedia_ephemeral.lupo_narrative_fragments`
- `lupo_document_chunks` → `lupopedia_ephemeral.lupo_document_chunks`

**Files to Check**:
- Content processing scripts
- Document chunking
- Narrative generation

---

## Testing Strategy

### Unit Testing Per Phase

After each phase:

1. **Test Basic Functionality**
   ```php
   // Test schema resolution
   require_once(LUPO_INCLUDES_DIR . '/schema-config.php');
   echo lupo_table('lupo_sessions'); // Should output correct schema
   ```

2. **Test Database Queries**
   ```php
   // Test actual query
   $result = $db->fetchRow("SELECT * FROM " . lupo_table('lupo_sessions') . " LIMIT 1");
   ```

3. **Test Application Flow**
   - Login/logout
   - Content access
   - Agent operations
   - Analytics tracking

### Integration Testing

After all phases complete:

1. **Full Application Test**
   - All major user flows
   - All admin functions
   - All API endpoints

2. **Schema Verification**
   ```sql
   -- Verify tables are in correct schemas
   SELECT table_schema, table_name 
   FROM information_schema.tables 
   WHERE table_name LIKE 'lupo_%'
   ORDER BY table_schema, table_name;
   ```

3. **Query Logging**
   - Enable query logging
   - Verify all queries use schema-qualified names
   - Check for any unqualified table references

---

## Rollback Safety

### Before Starting

1. **Backup Database**
   ```bash
   mysqldump -u root -p lupopedia > backup_before_phase_a_code_update.sql
   ```

2. **Version Control**
   ```bash
   # If using git (after 4.1.0)
   git commit -m "Pre-Phase A code update checkpoint"
   ```

3. **Document Current State**
   - List all files to be modified
   - Document current table references
   - Create rollback checklist

### During Updates

1. **Incremental Commits**
   - Commit after each phase
   - Test before moving to next phase
   - Keep rollback points

2. **Test After Each File**
   - Run basic functionality test
   - Verify no errors
   - Check query logs

### Rollback Procedure

If issues occur:

1. **Revert Code Changes**
   ```bash
   # Restore from version control or backup
   git checkout <previous-commit>
   # OR restore files from backup
   ```

2. **Verify System Works**
   - Test application
   - Verify database connectivity
   - Check logs

3. **Investigate Issues**
   - Review error logs
   - Check query logs
   - Identify root cause

---

## Verification Checklist

### Pre-Migration Verification

- [ ] Schema config loads without errors
- [ ] Helper functions resolve correctly
- [ ] All table classifications correct
- [ ] Test queries work with schema-qualified names

### Post-Update Verification

- [ ] All files updated
- [ ] No unqualified table references remain
- [ ] All application flows work
- [ ] Database queries use correct schemas
- [ ] No errors in logs
- [ ] Performance acceptable

### Post-Migration Verification

- [ ] Tables moved to correct schemas
- [ ] Application works correctly
- [ ] Cross-schema queries work
- [ ] Rollback tested and works

---

## Execution Order Summary

1. **Phase 1**: Bootstrap schema config
2. **Phase 2**: Verify PDO_DB class (no changes needed)
3. **Phase 3**: Authentication module
4. **Phase 4**: Truth module
5. **Phase 5**: Collections and content
6. **Phase 6**: Search and indexing
7. **Phase 7**: Agent and orchestration
8. **Phase 8**: Analytics and ephemeral
9. **Phase 9**: API and rate limiting
10. **Phase 10**: System and orchestration
11. **Phase 11**: Memory and debugging
12. **Phase 12**: anubis and events
13. **Phase 13**: Content processing

---

## Estimated Time

- **Phase 1**: 15 minutes
- **Phase 2**: 5 minutes (verification only)
- **Phases 3-13**: 2-4 hours per phase (depending on file count)
- **Testing**: 2-4 hours total
- **Total**: 1-2 days for complete update

---

## Next Steps After Code Update

1. **Run Phase A Migrations**
   - Execute `phase_a_orchestration_schema.sql`
   - Execute `phase_a_move_orchestration_tables.sql`
   - Execute `phase_a_move_ephemeral_tables.sql`

2. **Verify Migration**
   - Check table counts
   - Verify table locations
   - Test application

3. **Monitor**
   - Watch error logs
   - Monitor query performance
   - Check application behavior

---

*Last Updated: January 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Author: Captain Wolfie*

