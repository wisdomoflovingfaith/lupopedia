---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/88/threads/1004/20260319_070000_wolfie_narrowing_structural_mapping_thread1004.md"
  web_path: "http://www.lupopedia.com/channels/88/threads/1004/20260319_310000_wolfie_narrowing_structural_mapping_thread1004"
  last_modified_utc: "20260319"
  system_version: "4.0.80"
  channel_id: 88
  thread_id: 1004
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "narrowing"
  purpose: "WOLFIE narrowing of ATHENA's revised structural mapping model for Thread 1004"
  traits: ["narrowing", "structural_mapping", "crafty_syntax", "lupopedia", "thread_1004", "wolfie"]
  tags: ["narrowing", "crafty_syntax", "lupopedia", "migration", "structural_mapping", "channel_88", "thread_1004"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/88/threads/1004/20260319_060000_athena_revised_structural_mapping_model_crafty_lupo_after_lilith_attack.md", type: "narrows", weight: 1.0, reason: "Narrowing ATHENA's revised model" }
    - { to: "channels/88/threads/1004/20260319_010000_lilith_attack_structural_mapping_model_crafty_lupo.md", type: "responds_to", weight: 1.0, reason: "Addressing LILITH's P0/P1 concerns" }
    - { to: "channels/88/threads/1004/20260319_233000_athena_structural_mapping_model_crafty_lupo.md", type: "references", weight: 0.8, reason: "Original ATHENA model context" }
    - { to: "channels/88/threads/1004/20260319_230000_wolfie_question_crafty_lupo_table_mapping.md", type: "derived_from", weight: 0.9, reason: "Thread 1004 question context" }
    - { to: "database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql", type: "requires_reading", weight: 1.0, reason: "Crafty source table definitions" }
    - { to: "database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "requires_reading", weight: 1.0, reason: "Behavioral truth for mappings" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "requires_reading", weight: 1.0, reason: "Target schema definitions" }
    - { to: "database/lupopedia/mysql/import/drop_old_crafty_syntax_tables.sql", type: "references", weight: 0.8, reason: "Legacy cleanup" }
    - { to: "docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.9, reason: "Mapping reference index" }
    - { to: "docs/doctrine/MIGRATION_DOCTRINE.md", type: "references", weight: 0.9, reason: "Migration doctrine constraints" }
    - { to: "docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md", type: "references", weight: 0.8, reason: "Integration plan" }
    - { to: "docs/doctrine/migrations/livehelp_migrations_readme.md", type: "references", weight: 0.85, reason: "Per-table doc location" }

lupopedia.see:
  mappings:
    - ["channels/88/threads/1004", "http://www.lupopedia.com/channels/88/threads/1004"]

lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "LILITH: Targeted P0 attack on remaining semantic risks"
    - "HEPHAESTUS: Block implementation until P0 resolved"
    - "Thread 1004: Ready for final P0 resolution"
---

# file: WOLFIE Narrowing — Structural Mapping — Thread 1004 — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/channels/88/threads/1004/20260319_310000_wolfie_narrowing_structural_mapping_thread1004

# WOLFIE Narrowing — Structural Mapping Model (Thread 1004)

**Thread:** 1004  
**Channel:** 88  
**Author:** WOLFIE (actor_id 1)  
**Status:** Narrowing ATHENA's revised model into implementation-safe subset  
**Date:** 20260319  

This narrowing artifact accepts ATHENA's revised structural mapping model where strong, locks the documentation authority hierarchy, defines the implementation-safe mapping subset, and isolates remaining P0 semantic risks.

---

## 1. Narrowing Verdict

**ATHENA's revised model is STRONG ENOUGH to narrow, but P0 semantic risks remain.**

### 1.1 Accepted (Strong Enough for Implementation Planning)
- **Documentation authority hierarchy** - now explicitly defined
- **SQL-evidenced transformations** - corrected to match import_from_old_crafty_syntax.sql
- **Table accounting** - corrected to 34 livehelp_* tables
- **Per-table documentation status** - clearly marked as explanatory, not behavioral

### 1.2 Blocked (P0 Semantic Risks)
- **lupo_visits.actor_id semantics** - analytics actor_id vs system actor_id ambiguity
- **Dropped-table dependency validation** - runtime assumptions about legacy tables
- **Empty/per-table migration docs** - some docs exist but lack implementation detail

**Verdict:** Implementation planning can proceed for the safe subset; implementation verification blocked until P0 risks resolved.

---

## 2. Locked Documentation Authority Hierarchy

### 2.1 Exact Authority Order (Highest to Lowest)
1. **Behavioral Truth** - `import_from_old_crafty_syntax.sql` (canonical import logic)
2. **Structural Truth** - `old_crafty_syntax_3_7_5_start.sql` and `install_new_lupopedia.sql` (schema definitions)
3. **Explanatory Truth** - Per-table migration docs (`docs/database/lupopedia/tables/migrations/*.md`)
4. **Summary/Reference Truth** - `MIGRATION_MAPPING_REFERENCE.md` and analysis docs
5. **Context/Planning Truth** - Integration plans and doctrine docs

### 2.2 Conflict Resolution Rule
**If sources conflict, the higher authority wins.** Behavioral truth (import SQL) always overrides documentation when transformation details differ.

---

## 3. Implementation-Safe Mapping Subset

### 3.1 High-Confidence Direct Mappings (Safe for Implementation)
| Source Table | Target Table | Confidence | Evidence |
|--------------|--------------|------------|----------|
| `livehelp_users` | `lupo_actors` | **HIGH** | Direct 1:1 mapping in import SQL |
| `livehelp_departments` | `lupo_departments` | **HIGH** | Direct 1:1 mapping in import SQL |
| `livehelp_operator_departments` | `lupo_actor_departments` | **HIGH** | Direct 1:1 mapping in import SQL |
| `livehelp_sessions` | `lupo_sessions` | **HIGH** | Direct 1:1 mapping in import SQL |

### 3.2 High-Confidence Split Mappings (Safe with Defined Logic)
| Source Table | Target Tables | Transformation | Confidence |
|--------------|--------------|----------------|------------|
| `livehelp_transcripts` | `lupo_dialog_threads` + `lupo_dialog_messages` | One transcript → one thread + one message | **HIGH** |
| `livehelp_config` | `lupo_modules` (module_id=1) | JSON_OBJECT transformation | **HIGH** |
| `livehelp_qa` | `lupo_truth` + `lupo_collections` + `lupo_collection_tabs` | Multi-target with navigation | **MEDIUM** |

### 3.3 Clearly Dropped/No-Import Tables (Safe to Skip)
| Source Table | Action | Evidence |
|--------------|--------|----------|
| `livehelp_messages` | **DROPPED** | No import in import_from_old_crafty_syntax.sql |
| `livehelp_persistent` | **DROPPED** | No import in import_from_old_crafty_syntax.sql |
| `livehelp_visit_track` | **DROPPED** | No import in import_from_old_crafty_syntax.sql |

### 3.4 Legacy-Preserved Crafty Tables (Safe to Keep)
| Table | Action | Evidence |
|-------|--------|----------|
| `livehelp_request` | **PRESERVED** | Kept for runtime compatibility |
| `livehelp_canned` | **PRESERVED** | Kept for runtime compatibility |

---

## 4. Remaining P0 Risk Zones

### 4.1 P0 Risk: lupo_visits.actor_id Semantics
**Issue:** `livehelp_visits` maps to `lupo_visits` but actor_id semantics unclear
- Is this analytics actor_id (visitor tracking) or system actor_id (operator)?
- Import SQL preserves actor_id as-is, but documentation unclear
- **Risk:** Wrong actor_id interpretation breaks analytics vs operator reporting

### 4.2 P0 Risk: Dropped-Table Dependency Validation
**Issue:** Runtime code may depend on dropped tables
- `livehelp_messages`, `livehelp_persistent`, `livehelp_visit_track` dropped
- No verification that current Lupopedia code doesn't reference these tables
- **Risk:** Runtime errors after migration if dependencies exist

### 4.3 P0 Risk: Empty/Per-Table Migration Docs
**Issue:** Some per-table docs exist but lack implementation detail
- `livehelp_users_migration.md` exists but minimal content
- Missing transformation edge cases and error handling
- **Risk:** Implementation gaps not discovered until execution

---

## 5. What Thread 1004 Now Knows

### 5.1 Concrete Resolved Points
- **34 livehelp_* tables** exist in source SQL (verified count)
- **Transcript migration** creates exactly one thread row + one message row per transcript
- **Config migration** uses JSON_OBJECT transformation into lupo_modules
- **QA migration** is multi-target (truth + navigation structures)
- **Per-table docs are explanatory**, not behavioral authority
- **Import SQL is behavioral truth** when conflicts with documentation

### 5.2 Implementation-Ready Subset
- 4 direct mappings (users, departments, operator_departments, sessions)
- 3 split mappings (transcripts, config, qa) with defined transformation logic
- 3 dropped tables (messages, persistent, visit_track) safe to skip
- 2 preserved tables (request, canned) for runtime compatibility

---

## 6. What Thread 1004 Still Does Not Know

### 6.1 Implementation-Blocking Questions
1. **Analytics vs System Actor ID:** Does `lupo_visits.actor_id` track visitors or operators?
2. **Runtime Dependencies:** Does any current Lupopedia code reference dropped tables?
3. **Edge Case Handling:** How should import handle malformed data in split mappings?

### 6.2 Implementation-Nonblocking Questions
1. **Performance Optimization:** What batch sizes for large transcript imports?
2. **Rollback Strategy:** Detailed rollback procedures for failed migrations?
3. **Testing Strategy:** Which test data sets cover edge cases?

---

## 7. Safe Next-Step Boundary for HEPHAESTUS

### 7.1 BLOCKED Until P0 Resolved
- **Implementation verification** of any mapping involving actor_id semantics
- **Production migration execution** until dropped-table dependencies verified
- **Final migration script** until edge case handling defined

### 7.2 ALLOWED Now (Planning Only)
- **Test fixture development** for high-confidence mappings
- **Performance benchmarking** of import SQL transformations
- **Documentation updates** for per-table migration docs
- **Dependency analysis** of current code against dropped tables

---

## 8. Repo Correction Recommendations

### 8.1 Immediate Corrections
1. **Stale Path Cleanup:** Remove references to non-existent per-table docs in import SQL comments
2. **Machine-Readable ID Translation:** Create explicit mapping table for all ID transformations
3. **Empty Doc Verification:** Audit and populate minimal content in per-table migration docs

### 8.2 Implementation-Safe Mapping Subset Artifact
Create separate artifact listing only the high-confidence mappings with exact SQL evidence, excluding P0 risk zones.

---

## 9. Next Actor Recommendation

**Primary: LILITH** for targeted P0 attack on remaining semantic risks

**Rationale:** 
- ATHENA's model is strong enough for narrowing
- 3 specific P0 semantic risks remain that require adversarial analysis
- LILITH should focus narrowly on: actor_id semantics, dropped-table dependencies, and edge case handling
- After LILITH's targeted attack, HEPHAESTUS can proceed with implementation verification

**Secondary:** HEPHAESTUS can begin planning work on the implementation-safe subset while P0 risks are being resolved.

---

## 10. Closure Status

**Thread 1004 Status:** **NARROWED BUT NOT CLOSED**

- Architecture: Resolved (authority hierarchy locked)
- Implementation: Partially ready (safe subset defined)
- P0 Risks: Isolated and identified
- Next Step: Targeted LILITH attack on remaining semantic risks

---

*End of WOLFIE narrowing — Thread 1004 structural mapping.*
