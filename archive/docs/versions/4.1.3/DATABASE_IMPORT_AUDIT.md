---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/versions/4.1.3/DATABASE_IMPORT_AUDIT.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/DATABASE_IMPORT_AUDIT.md"
  status: "active"
  when_updated: "20260419200000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/database/canonical/1026/04/database-import-audit.toon"
  atoms_toon: null
  transcript_jsonl: "0/database/import-audit"
  artifact_type: documentation
  artifact_kind: audit_report
  channel_key: "database"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "database-import-audit"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Database Import Audit - Crafty Syntax to Lupopedia"
  summary: "Critical audit of import_from_old_crafty_syntax.sql against canonical install schema, identifying blockers and safety issues."
---

# Database Import Audit
## Crafty Syntax 3.7.5 → Lupopedia Migration Script Analysis

**Audit Date**: 2026-04-19  
**Status**: CRITICAL - Import script unsafe to run  
**Files Analyzed**: 
- `database/lupopedia/mysql/install/install_new_lupopedia.sql` (142 tables)
- `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` 
- `database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql` (34 tables)

---

## Critical Blockers

### B1. Missing Tables: `actor_filesystem` and `actor_sync_state`

**Evidence**: Lines 1676, 1848, 1705, 1876 in import script
```sql
INSERT INTO {{prefix}}actor_filesystem (...)
INSERT INTO {{prefix}}actor_sync_state (...)
```

**Impact**: Import fails at runtime with "table does not exist" error. These tables were ejected in 4.1.2 per install schema comments:
```sql
-- [EJECTED 4.1.2] actor_filesystem removed
-- [EJECTED 4.1.2] actor_sync_state removed
```

**Recommended Fix**: Remove all INSERT statements for these tables from import script. Update import documentation to reflect these tables are permanently ejected.

### B2. Destructive TRUNCATE Operations

**Evidence**: 17 TRUNCATE statements targeting core tables:
- `dialog_messages`, `dialog_threads` (lines 1445, 1413)
- `visits`, `paths`, `referers` (lines 1185, 1325, 1069)
- `audit_log`, `actor_departments` (lines 627, 587)
- And 10 others...

**Impact**: On non-empty databases, running import destroys existing data. Only safe on fresh throwaway installations.

**Recommended Fix**: 
1. Add explicit preconditions comment at top of import script
2. Consider replacing TRUNCATE with scoped DELETE operations for Python importer
3. Document as FRESH-INSTALL-ONLY until Python replacement ready

### B3. Broken Actor Linkage - Missing `auth_user_id`

**Evidence**: Import script line ~1600 (actors INSERT):
```sql
INSERT INTO {{prefix}}actors (
    actor_id, actor_name, slug, name, actor_type, agent_key,
    is_kernel, is_required, can_login, is_agent, is_active,
    actor_tier, -- MISSING: auth_user_id
    actor_source_id, actor_source_type, avatar_hash, ...
)
```

**Impact**: Imported operators lack proper linkage to `auth_users` table. Runtime code expecting `auth_user_id` populated will fail or produce inconsistent RBAC.

**Recommended Fix**: Add `auth_user_id` column to INSERT statement, populate from `livehelp_users.user_id` during import.

### B4. Stale Table Count Claims

**Evidence**: Import header lines 25-31:
```sql
-- Total tables present during migration: 233
--   • 34 legacy Crafty Syntax tables (3.7.5)
--   • 199 core Lupopedia tables 
```

**Actual Count**: 142 tables in canonical install (verified by grep CREATE TABLE)

**Impact**: Misleading documentation causes agents to assume missing tables or incorrect scope.

**Recommended Fix**: Update header to reflect actual 142 table count. Mark old numbers as deprecated.

### B5. Incomplete Migration Sections

**Evidence**: Multiple sections with only ALTER statements, no INSERT:
- `livehelp_channels` (lines 108-116): Only ALTER, no INSERT into `{{prefix}}channels`
- `livehelp_modules` (similar): Only ALTER, no INSERT into `{{prefix}}modules`
- `livehelp_operator_channels`: Only ALTER statements

**Impact**: These legacy tables are modified but data never migrated to Lupopedia equivalents.

**Recommended Fix**: Either add proper INSERT statements or document that these depend on seed data/wizard setup.

### B6. Doctrine Violations - MySQL-Specific SQL

**Evidence**: Multiple non-portable constructs:
- `JSON_OBJECT()` calls (line 132+)
- `ON DUPLICATE KEY UPDATE` 
- `REGEXP` in predicates
- `UTC_TIMESTAMP()` calls

**Impact**: Breaks PostgreSQL compatibility and violates database neutrality doctrine.

**Recommended Fix**: Move to Python application layer for these transformations. Mark current script as MySQL-dev-only.

---

## Additional Issues

### Missing INSERT for `livehelp_messages`

**Issue**: Comment indicates mapping to `dialog_messages` but no INSERT statements found
**Status**: NEEDS INVESTIGATION - May be intentional if Crafty tables typically empty

### Module/Channel Seed Dependencies

**Issue**: `livehelp_config` UPDATE expects `modules.id = 1` to exist
**Impact**: Import fails if seed data not present
**Fix**: Document seed requirement or add INSERT for default modules

---

## Safety Recommendations

1. **Immediate**: Add warning header to import script stating FRESH-INSTALL-ONLY
2. **Short-term**: Remove references to ejected tables
3. **Medium-term**: Fix actor linkage and incomplete mappings
4. **Long-term**: Replace with Python importer for cross-platform compatibility

---

## Open Questions

1. Should `actor_filesystem`/`actor_sync_state` be restored to canonical install?
2. Is the current TRUNCATE approach acceptable for dev-only imports?
3. Should incomplete mappings be implemented or documented as seed-dependent?
4. Timeline for Python importer replacement?

---

**Next Action**: Update import script with safety warnings and remove ejected table references.
