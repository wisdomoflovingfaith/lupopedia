---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/PLAN_DATABASE_CORRECTION.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.3/PLAN_DATABASE_CORRECTION.md"
  status: "active"
  when_updated: "20260419200000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/database/canonical/1026/04/database-correction-plan.toon"
  atoms_toon: null
  transcript_jsonl: "0/database/correction-plan"
  artifact_type: documentation
  artifact_kind: plan
  channel_key: "database"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "database-correction-plan"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Database Import Correction Plan"
  summary: "Ordered execution plan to safely correct import_from_old_crafty_syntax.sql blockers and align with canonical install schema."
---

# Database Import Correction Plan
## Safe Resolution of Crafty Syntax → Lupopedia Migration Issues

**Created**: 2026-04-19  
**Priority**: CRITICAL - Import currently unsafe  
**Scope**: Align import script with canonical install schema (142 tables)

---

## Phase 1: Immediate Safety Fixes (Can be applied now)

### 1.1 Add Safety Warning Header
**File**: `import_from_old_crafty_syntax.sql`  
**Action**: Add warning at top of file
```sql
/* 
======================================================================
   CRITICAL SAFETY WARNING
   This script is for FRESH INSTALL ONLY - destroys existing data
   Do NOT run on production databases with data
   Issues: See lupo-docs/versions/4.1.3/DATABASE_IMPORT_AUDIT.md
====================================================================== */
```

### 1.2 Remove Ejected Table References
**File**: `import_from_old_crafty_syntax.sql`  
**Lines**: ~1676, 1848, 1705, 1876  
**Action**: Comment out or remove all INSERT into `actor_filesystem` and `actor_sync_state`

### 1.3 Update Stale Table Count
**File**: `import_from_old_crafty_syntax.sql`  
**Lines**: 25-31  
**Action**: Change "199 core tables" to "142 core tables" (match actual install)

---

## Phase 2: Data Integrity Fixes (Requires testing)

### 2.1 Fix Actor Linkage
**File**: `import_from_old_crafty_syntax.sql`  
**Location**: Actors INSERT section (~line 1600)  
**Action**: 
- Add `auth_user_id` to INSERT column list
- Populate from `livehelp_users.user_id` during import
- Set `can_login`, `is_agent`, `actor_tier` explicitly

### 2.2 Resolve Incomplete Mappings
**Tables needing decision**:
- `livehelp_channels` → `channels`
- `livehelp_modules` → `modules`  
- `livehelp_operator_channels` → `channels`
- `livehelp_messages` → `dialog_messages`

**Options**:
A. Add proper INSERT statements from legacy data
B. Document as seed-dependent and require wizard setup
C. Mark as intentionally not migrated

### 2.3 Address TRUNCATE Hazards
**Current**: 17 TRUNCATE statements destroy data  
**Options**:
A. Keep as-is but document FRESH-INSTALL-ONLY clearly
B. Replace with scoped DELETE operations
C. Move to Python importer with transactional batches

---

## Phase 3: Doctrine Compliance (Medium-term)

### 3.1 Remove MySQL-Specific SQL
**Issues to fix**:
- `JSON_OBJECT()` calls → Build in Python
- `ON DUPLICATE KEY UPDATE` → Use SELECT then INSERT/UPDATE
- `REGEXP` predicates → Application-side filtering
- `UTC_TIMESTAMP()` → Pass explicit BIGINT from host

### 3.2 Cross-Platform Preparation
**Goal**: Make import runnable on PostgreSQL  
**Approach**: Move complex transformations to Python application layer

---

## Phase 4: Documentation Updates

### 4.1 Update PRD 13
**Sections to update**:
- Import is dev-only, not production migration
- Canonical table count is 142, not 199
- Ejected tables documentation
- Fresh-install-only requirements

### 4.2 Update Migration Documentation
**Files**:
- `lupo-docs/versions/4.1.3/todo.md` (already done)
- `lupo-docs/versions/4.1.3/plan.md`
- Any referenced migration guides

---

## Phase 5: Long-term Architecture

### 5.1 Python Importer Replacement
**Benefits**:
- Cross-platform compatibility
- Transactional safety
- Better error handling
- No TRUNCATE hazards

### 5.2 Seed Data Strategy
**Define**: What data must exist before import runs
- Default modules row (id=1)
- Default channels
- Default departments

---

## Execution Order

1. **Phase 1** - Apply safety fixes immediately (low risk)
2. **Decision Point** - Choose approach for incomplete mappings (Phase 2.2)
3. **Phase 2** - Implement data integrity fixes (medium risk)
4. **Phase 3** - Doctrine compliance (can be done incrementally)
5. **Phase 4** - Update documentation (parallel with code changes)
6. **Phase 5** - Python importer (future project)

---

## Risk Assessment

| Phase | Risk | Impact | Mitigation |
|-------|------|--------|------------|
| 1 | LOW | Documentation only | Review before commit |
| 2 | MEDIUM | Data structure changes | Test on fresh install |
| 3 | MEDIUM | SQL compatibility changes | Test both MySQL/PostgreSQL |
| 4 | LOW | Documentation only | Peer review |
| 5 | HIGH | New application code | Separate project |

---

## Success Criteria

1. Import script runs without errors on fresh install
2. All imported operators properly linked to auth_users
3. No references to ejected tables
4. Clear documentation of limitations
5. Path defined for Python replacement

---

## Open Questions for Wolfie

1. Should `actor_filesystem`/`actor_sync_state` remain permanently ejected?
2. Is FRESH-INSTALL-ONLY acceptable for current import script?
3. Which approach for incomplete mappings (A, B, or C)?
4. Timeline for Python importer replacement?
5. Should we attempt PostgreSQL compatibility now or mark as MySQL-dev-only?

---

**Next Action**: Execute Phase 1 safety fixes immediately, then await decisions on Phase 2.2 approach.
