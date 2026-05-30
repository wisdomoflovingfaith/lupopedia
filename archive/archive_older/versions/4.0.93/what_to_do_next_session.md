---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.93/what_to_do_next_session.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.93/what_to_do_next_session.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: session_plan
  artifact_kind: coordination_plan
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# What To Do Next Session

## 🔍 **VERSIONS 4.0.87-4.0.93 AUDIT FINDINGS**

### **❌ CRITICAL ISSUES IDENTIFIED**

**4.0.91 & 4.0.92 - Documentation Only**:
- Zero implementation work completed
- Only PLAN.md and TODO.md files created
- No channel import work performed
- No database synchronization executed

**4.0.92 - Architectural Error**:
- Incorrectly referenced `lupo_context_edges` (redundant table)
- Should use polymorphic `lupo_edges` system
- Fixed: Updated all references to use `lupo_edges`

### **✅ 4.0.93 COMPLETED CORRECTIONS**

**LILITH "Source of Truth" Protocol**:
- Implemented strict Toon file protection with RULE [93.PROTECT_TOONS]
- Updated all 4.0.93 PRD files with actual database schema from Toon JSONs
- Established subdirectory installation doctrine for Semantic Monitoring Widget
- Documented forbidden constructs (AUTO_INCREMENT, TIMESTAMP, FOREIGN KEYS, TRIGGERS, UNSIGNED)

**PRD Schema Alignment**:
- `02_data_model.md`: Updated with actual `lupo_contexts`, `lupo_truth_questions`, `lupo_truth_answers`, `lupo_votes` schema
- `04_lupopedia_js_foundation.md`: Updated with `livehelp_visitors → lupo_visitors` mapping and live typing refraction
- `01_semantic_monitoring_widget.md`: Updated with `lupo_edges` schema and subdirectory installation doctrine

**Critical Architectural Constraints**:
- Subdirectory-only installation for auto-installer compatibility
- Database-first architecture with filesystem archival mirrors
- JavaScript includes must be subdirectory-aware
- The Eye monitors parent site, not Lupopedia directory

## 🎯 **NEXT SESSION PRIORITIES**

### **Priority 1: Complete 4.0.91 Channel Import Work**
- Execute `SyncChannelsToDb.php` with `--commit` flag
- Import lupo_channels from JSON to database
- Import lupo_dialog_threads from JSON to database  
- Import lupo_dialog_messages from JSON to database
- Validate channel data integrity
- Test channel-based coordination system

### **Priority 2: Implement 4.0.92 Semantic Search Features**
- Build Edge-Navigation using `lupo_edges` with `edge_type = 'context_navigation'`
- Implement Fuzzy-Context Search overlay for State Mirror
- Complete Hydration 2.0 optimization
- Test semantic search with 63-bit ID preservation

### **Priority 3: Organizational Context Enhancement**
- Reference `docs/ORGANIZATION.md` for system-wide patterns
- Use `docs/database/DATABASE_TABLE_ORGANIZATION_CONTEXT.md` for table decisions
- Follow `rules/root/required-tables-future-features-doctrine.md` for table classification
- Ensure proper separation of classes vs scripts
- Validate all new tables against established patterns

### **Priority 4: Database-First Migration (Pre-Canonical Cleanup)**
- Execute new install to establish clean database state
- Run `php scripts/SyncChannelsToDb.php --commit` to import existing coordination work
- Verify all filesystem work properly imported to database
- Mark filesystem as "pre-canonical" archival mirrors only
- Test web interface reading only from database tables

## 📋 **IMPLEMENTATION CHECKLIST**

### **Channel Import (4.0.91 completion)**:
- [ ] Run `php scripts/SyncChannelsToDb.php --commit`
- [ ] Verify channels imported to database
- [ ] Verify threads imported to database
- [ ] Verify messages imported to database
- [ ] Test channel coordination in UI

### **Semantic Search (4.0.92 completion)**:
- [ ] Implement Edge-Navigation queries using lupo_edges
- [ ] Build Fuzzy-Context Search interface
- [ ] Optimize State Mirror for keyword search
- [ ] Complete Hydration 2.0 for legacy content

### **Quality Assurance**:
- [ ] Run `enforce_doctrine.py` on all new code
- [ ] Test all features with channel-based coordination
- [ ] Validate database schema consistency
- [ ] Update CHANGELOG.md with completed work

## 🚨 **CRITICAL PATH**

**Session Goal**: Complete the "auto-pilot" work from 4.0.91-4.0.92 that was never implemented.

**Success Criteria**:
1. ✅ Channel import fully functional
2. ✅ Semantic search operational  
3. ✅ All edge operations use `lupo_edges`
4. ✅ No architectural debt remaining

## 📊 **VERSION STATUS MATRIX**

| Version | Planned | Actual | Next Session |
|---------|----------|---------|--------------|
| 4.0.87 | ✅ Complete | N/A |
| 4.0.88 | ✅ Complete | N/A |
| 4.0.89 | ✅ Complete | N/A |
| 4.0.90 | ✅ Complete | N/A |
| 4.0.91 | Channel Import | ❌ **IMPLEMENT** |
| 4.0.92 | Semantic Search | ❌ **IMPLEMENT** |
| 4.0.93 | LILITH Audit & PRD Updates | ✅ **COMPLETED** |

## 🚨 **4.0.93 ACHIEVEMENTS**

### **Critical Architectural Corrections**
- ✅ **LILITH "Source of Truth" Protocol**: Strict hierarchy for schema evolution
- ✅ **Toon File Protection**: RULE [93.PROTECT_TOONS] prevents IDE corruption
- ✅ **PRD Schema Alignment**: All PRDs reference actual database structures
- ✅ **Subdirectory Doctrine**: Critical constraint for auto-installer compatibility

### **Documentation Alignment**
- ✅ **PLAN.md**: Updated with LILITH protocol and subdirectory requirements
- ✅ **TODO.md**: Marked completed LILITH protocol tasks
- ✅ **PRD Files**: Updated with real database schema and constraints
- ✅ **Session Planning**: Documented completed corrections and next priorities
- [x] **ID Generation Directive Compliance**: IdGenerator.php updated with YYYYMMDDHHIISS + random suffix format; 63-bit signed-safe BIGINTs; test suite created
- [x] **Full Database Audit**: Comprehensive audit of all 166 tables completed; 5 doctrine violations; 48 missing documentation; all PRDs updated with lupopedia.edges
- [x] **PRD Edge Integration**: All PRD files now include lupopedia.edges sections linking to table definitions and related documentation
- [x] **Grouped PRD Structure**: Complete 14-namespace PRD architecture created in `docs/prd/`; 100% PRD coverage achieved (14/14 files); maintenance burden reduced by 92%

### **Installer / consolidated seed (2026-03-30)**
- [x] **Single runtime seed:** `install/seed_lupopedia_4_1_0.sql` after `install_new_lupopedia.sql` (sources under `database/lupopedia/mysql/seed/`; rebuild via `scripts/build_consolidated_seed_4_1_0.py`).
- [x] **`{{prefix}}` in installer:** `InstallWizardSqlRunner::applyTablePrefixToSql()` used by `runSqlFile()` for DDL, consolidated seed, and import SQL.
- [x] **Duplicate seed runs removed** from wizard tail (Anubis helper SQL still optional post-seed).
- **Next:** When touching any file in `mysql/seed/`, regenerate consolidated SQL and note it in CHANGELOG/session; re-run deferred `enforce_doctrine` on consolidated output when tooling unblocks.

### **Installer / actor runtime follow-up (2026-03-31)**
- [x] Removed remaining per-file seed execution from installer runtime path (legacy Anubis helper seed SQL no longer executed by `install.php`).
- [x] Deleted obsolete seed files that were no longer used in runtime installer flow.
- [x] Updated runtime actor creation paths to deterministic IDs (`YmdHis + 4`) and sharded workspace resolution/provisioning under `actors/YYYY/MM/actor_id`.
- [x] Kept backward-compatible fallback resolution for legacy flat actor directories.

**Verification (read-only, 2026-03-30)** — see `/docs/versions/4.0.93/WHAT_TO_DO_NEXT.md` §14: confirmed root `install.php` / `install_wizard_classes.php`, import path, `{{prefix}}` on consolidated + import, 23 sections in consolidated seed, Anubis post-seed; noted cosmetic `BEGIN FILE` comment quirk for metadata seed filename.

---

**Session Focus**: Complete the missing implementation work from 4.0.91-4.0.92 while maintaining architectural integrity using the proper `lupo_edges` polymorphic system and LILITH's "Source of Truth" protocol. Fresh installs and Crafty upgrades should verify `install.php` + consolidated seed path documented in `docs/versions/4.0.93/CHANGELOG.md` and `prd/01_installer_requirements.md`.
