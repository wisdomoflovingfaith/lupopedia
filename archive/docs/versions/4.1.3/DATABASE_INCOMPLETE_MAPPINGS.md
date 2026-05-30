---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/versions/4.1.3/DATABASE_INCOMPLETE_MAPPINGS.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/DATABASE_INCOMPLETE_MAPPINGS.md"
  status: "active"
  when_updated: "20260419204500"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/database/canonical/1026/04/database-incomplete-mappings.toon"
  atoms_toon: null
  transcript_jsonl: "0/database/incomplete-mappings"
  artifact_type: documentation
  artifact_kind: audit_report
  channel_key: "database"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "database-incomplete-mappings"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Database Import Incomplete Mappings"
  summary: "Documentation of legacy Crafty tables with incomplete or missing data migration in import script."
---

# Database Import Incomplete Mappings
## Legacy Crafty Tables Missing Data Migration

**Created**: 2026-04-19  
**Status**: IDENTIFIED - Requires decisions/implementation  
**Scope**: Tables with ALTER/COMMENT only, no actual data import

---

## Incomplete Mappings Identified

### 1. livehelp_channels

**Current Import Status**: PREP ONLY  
**Evidence**: Lines 125-133 in import script
```sql
-- MIGRATION: livehelp_channels - Upgrade to new schema
-- DROPPED: livehelp_channels 
-- See: docs/doctrine/migrations/livehelp_channels_migration.md 
ALTER TABLE livehelp_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE livehelp_channels
  COMMENT = 'DEPRECATED: Only retained for migration...';
```
**Issue**: Only ALTER statements, no INSERT into `{{prefix}}channels`  
**Intended Canonical Target**: `channels` (per comment)  
**Recommended Next Action**: NEEDS DECISION - Determine if channels should be imported from legacy or rely on seed/wizard setup

### 2. livehelp_config

**Current Import Status**: PARTIALLY IMPORTED  
**Evidence**: Lines 136-160 in import script
```sql
-- livehelp_config               → JSON inserted into modules.id = 1
UPDATE {{prefix}}modules m
SET m.config_json = (
    SELECT JSON_OBJECT(...)
```
**Issue**: Only updates existing row (id=1), no INSERT for missing modules  
**Intended Canonical Target**: `modules` (id=1)  
**Recommended Next Action**: DOCUMENT - Clarify dependency on pre-existing seed row

### 3. livehelp_modules ✅ PATCHED

**Current Import Status**: PATCHED IN SQL  
**Evidence**: Lines 564-636 in import script
```sql
-- PATCHED: livehelp_modules -> modules
-- Import legacy modules into canonical modules table
-- Mapping: id -> module_id, name -> module_key/module_name, path -> user_path, adminpath -> admin_path
-- Note: query_string not mapped (no equivalent in modules schema)
TRUNCATE {{prefix}}modules;
INSERT INTO {{prefix}}modules (...) SELECT ... FROM livehelp_modules lm;
```
**Resolution**: Full import implemented mapping legacy modules to canonical modules table  
**Canonical Target**: `modules`  
**Status**: Complete - imports module definitions with proper field mapping

### 4. livehelp_operator_channels

**Current Import Status**: PREP ONLY  
**Evidence**: Lines 587-593 in import script
```sql
-- livehelp_operator_channels -> channels
-- See: /docs/doctrine/migrations/livehelp_operator_channels_migration.md 
ALTER TABLE livehelp_operator_channels
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
**Issue**: Only ALTER statements, no INSERT into `{{prefix}}channels`  
**Intended Canonical Target**: `channels` (per comment)  
**Recommended Next Action**: NEEDS DECISION - Determine operator-channel relationships import strategy

### 6. livehelp_messages

**Current Import Status**: NOT IMPORTED  
**Evidence**: Lines 551-557 in import script
```sql
-- livehelp_messages -> dialog_messages but crafty did not store any of the messages after the chat ended so this table is empty unless there was active chats
ALTER TABLE livehelp_messages
    ENGINE=InnoDB,
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
**Issue**: Comment suggests table is typically empty, no import logic provided  
**Intended Canonical Target**: `dialog_messages` (per comment)  
**Recommended Next Action**: DOCUMENT - Mark as low priority, only for active chat recovery

---

## Summary by Status

| Status | Count | Tables |
|--------|-------|--------|
| PREP ONLY | 2 | livehelp_channels, livehelp_operator_channels |
| PARTIALLY IMPORTED | 1 | livehelp_config |
| NOT IMPORTED | 1 | livehelp_messages |
| PATCHED IN SQL | 1 | livehelp_modules |

**Total Incomplete**: 4 tables (requiring decisions/implementation)

**Intentionally Dropped**: 1 table (livehelp_modules_dep) - resolved per design

---

## Decision Points

1. **Channel Strategy**: Should legacy channels be imported or created via wizard?
2. **Operator-Channel Links**: Should these relationships be preserved?
3. **Active Chat Recovery**: Is importing livehelp_messages worth the complexity?

Note: `livehelp_modules_dep` is intentionally dropped - no decision needed.  
Note: `livehelp_modules` has been patched - no decision needed.

---

## Recommended Implementation Order

1. **High Priority**: livehelp_config (document seed dependency)
2. **Medium Priority**: livehelp_channels (determine import vs wizard)
3. **Low Priority**: livehelp_operator_channels (depends on channel strategy)
4. **Optional**: livehelp_messages (active chat recovery only)

Note: livehelp_modules has been implemented and removed from the queue.

---

## Notes

- All identified tables have proper ALTER/COMMENT staging
- No schema violations, just missing data migration logic
- Some may be intentionally deferred to Python importer
- Documentation suggests some may be wizard/seed-dependent
