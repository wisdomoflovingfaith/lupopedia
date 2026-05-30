---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/versions/4.1.3/DATABASE_MAPPING_CLASSIFICATION.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/DATABASE_MAPPING_CLASSIFICATION.md"
  status: "active"
  when_updated: "20260419210000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/database/canonical/1026/04/database-mapping-classification.toon"
  atoms_toon: null
  transcript_jsonl: "0/database/mapping-classification"
  artifact_type: documentation
  artifact_kind: classification_report
  channel_key: "database"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "database-mapping-classification"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Database Mapping Classification"
  summary: "Classification of remaining incomplete legacy table mappings into execution buckets for Crafty Syntax import."
---

# Database Mapping Classification
## Legacy Crafty Tables Classified by Execution Strategy

**Created**: 2026-04-19  
**Status**: CLASSIFIED - Ready for execution planning  
**Scope**: Classification of unresolved legacy table mappings

---

## Classification Buckets

1. **PATCHABLE NOW IN SQL** - Clear mapping exists, can be implemented directly
2. **SEED-DEPENDENT / NEEDS PRECONDITION** - Requires pre-existing data or wizard setup
3. **DEFER TO PYTHON OR LATER DECISION** - Complex or requires future architecture
4. **INTENTIONALLY DROPPED** - Explicitly not migrated per design decision

---

## Legacy Table Classifications

### 1. livehelp_channels

**Current Status**: PREP ONLY (ALTER statements only)  
**Classification**: NEEDS PRODUCT DECISION  
**Why**: Legacy channels represent transient chat sessions, not persistent channels. Requires decision on whether to import historical chat data.  
**Canonical Target**: `channels` (exists but may not be appropriate)  
**Preconditions Required**: 
- Decision on historical chat session preservation strategy
- Mapping of transient sessions to persistent channel model  
**Decision**: NEEDS PRODUCT / DOCTRINE DECISION - Transient chat sessions vs persistent channels
**Status**: NOT APPROVED for SQL patching until product decision strategy

### 2. livehelp_config

**Current Status**: PARTIALLY IMPORTED (UPDATE only)  
**Classification**: SEED-DEPENDENT → APPROVED WITH PRECONDITION  
**Why**: Only updates existing row (modules.id=1), no INSERT for missing modules. Assumes seed data exists.  
**Canonical Target**: `modules` (id=1)  
**Preconditions Required**: 
- Default modules row must exist (id=1)
- Config JSON structure compatibility verified  
**Decision**: APPROVED WITH PRECONDITION - SQL logic correct, needs seed row guarantee
**Status**: APPROVED for SQL patching with precondition documentation

### 3. livehelp_modules ✅

**Current Status**: PATCHED IN SQL  
**Classification**: PATCHABLE NOW IN SQL → PATCHED IN SQL  
**Why**: Clear mapping to `modules` table exists, legacy module definitions can be imported. Schema compatible.  
**Canonical Target**: `modules`  
**Preconditions Required**: None (modules table exists with proper structure)  
**Implementation**: Full import implemented mapping id→module_id, name→module_key/module_name, path→user_path, adminpath→admin_path  
**Status**: Complete - imports module definitions with proper field mapping

### 4. livehelp_operator_channels

**Current Status**: PREP ONLY (ALTER statements only)  
**Classification**: DEFER TO PYTHON OR LATER DECISION  
**Why**: Complex many-to-many relationship between operators and channels. Depends on channel import strategy.  
**Canonical Target**: `actor_channel_roles` (inferred from schema)  
**Preconditions Required**: 
- Channel import strategy decided
- Actor linkage completed (✅ done)
- Role mapping defined  
**Recommended Next Action**: DEFER - Wait for channel strategy, then implement in Python for complexity

### 5. livehelp_messages

**Current Status**: NOT IMPORTED  
**Classification**: DEFER TO PYTHON OR LATER DECISION  
**Why**: Comment indicates table is typically empty unless active chats. Complex message threading logic.  
**Canonical Target**: `dialog_messages`, `dialog_threads`  
**Preconditions Required**: 
- Active chat recovery priority assessment
- Message threading strategy  
**Recommended Next Action**: DEFER - Low priority, implement in Python if needed for active chat recovery

### 6. livehelp_modules_dep

**Current Status**: INTENTIONALLY DROPPED  
**Classification**: INTENTIONALLY DROPPED  
**Why**: Explicit documentation states "DO NOT MAP THIS TABLE...THIS TABLE IS DROPPED WITH NO IMPORT." Lupopedia uses UI-driven module visibility.  
**Canonical Target**: NONE  
**Preconditions Required**: None  
**Recommended Next Action**: DOCUMENT - Mark as resolved per design decision

---

## Classification Summary

| Bucket | Count | Tables |
|--------|-------|--------|
| PATCHED IN SQL | 1 | livehelp_modules |
| APPROVED WITH PRECONDITION | 1 | livehelp_config |
| NEEDS PRODUCT DECISION | 1 | livehelp_channels |
| DEFER TO PYTHON OR LATER DECISION | 2 | livehelp_operator_channels, livehelp_messages |
| INTENTIONALLY DROPPED | 1 | livehelp_modules_dep |

**Total Classified**: 6 tables  
**Unresolved**: 4 tables (excluding intentionally dropped)

---

## Execution Priority

1. **Complete**: livehelp_modules ✅ (patched in SQL)
2. **Approved with Precondition**: livehelp_config (document seed dependency)
3. **Product Decision Required**: livehelp_channels (transient vs persistent channels)
4. **Deferred**: livehelp_operator_channels, livehelp_messages (Python/later)

---

## Notes

- `livehelp_modules` is the only table clearly patchable in current SQL
- Channel strategy decision impacts multiple dependent mappings
- Python importer recommended for complex relationships and active chat recovery
- Seed dependencies should be documented for future import runs
