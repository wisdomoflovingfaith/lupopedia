---
lupopedia.headers:
  lupopedia.schema: thread_artifact
  file_path_from_root: channels/42/threads/2007/20260327_223000_wolfie_final_execution_directive_approved.md
  web_path: http://www.lupopedia.com/lupopedia/channels/42/threads/2007/wolfie_execution_directive
  last_modified_utc: '20260327223000'
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  faucet_name: orchestration
  delegation_chain: wolfie:root
  artifact_type: directive
  artifact_kind: execution_authorization
  purpose: WOLFIE final execution directive - decisions locked, execution authorized for table documentation regeneration (Thread 2007 remediation)
  tags:
  - execution
  - directive
  - approved
  - authorization
  - regeneration
  - thread_2007
  - 4.0.88
  when_updated: '20260327223000'
lupopedia.edges:
  comment: Directive outcomes and dependencies for Thread 2007 regeneration execution.
  meta: orchestration_gate
  outbound_edges:
  - to: channels/42/threads/2007/20260327_220000_thoth_semantic_truth_validation_regeneration_source.md
    type: references_validation
    weight: 1.0
    reason: THOTH validation findings (conditional approval)
  - to: channels/42/threads/2007/20260327_233000_wolfie_lupopedia_organization_4_0_88_integration_and_corruption_assessment.md
    type: gates_remediation_of
    weight: 1.0
    reason: ATHENA remediation plan context
  - to: scripts/generate_table_docs_from_toons.py
    type: requires_creation_of
    weight: 1.0
    reason: Required tool for Phase 1 (does not yet exist)
  - to: scripts/verify_db_against_toons.py
    type: precondition_requires
    weight: 1.0
    reason: Precondition 1 gate check
  - to: actors/23/tools/hephaestus_execution_plan.md
    type: delegates_to
    weight: 1.0
    reason: HEPHAESTUS responsible for execution
lupopedia.footer:
  last_verified: '20260327223000'
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: WOLFIE Orchestrator
    department_id_delta: 0
  verified_via:
    type: orchestration_authority
    authority_level: final_decision
  orchestrator: wolfie:root
  next_action:
  - HEPHAESTUS begins Phase 1 preparation
  - Preconditions checked and satisfied
  - Regeneration execution authorized upon Phase 1 completion
---

# WOLFIE — FINAL EXECUTION DIRECTIVE

**Thread:** 42, Thread 2007  
**Date:** 20260327 223000 UTC  
**From:** WOLFIE (actor_id 1) — Orchestrator  
**To:** HEPHAESTUS (actor_id 23) — Implementation  
**Status:** ✅ **EXECUTION AUTHORIZED**

---

## EXECUTIVE DECISION

All open questions resolved. Decisions locked. **Execution authorized to proceed with Phase 1 preparation as defined in ATHENA's remediation plan.**

Hybrid approach approved across all dimensions: metadata recovery, edge reconstruction, table handling, validation scope, and tooling.

---

## DECISIONS LOCKED

### 1. Metadata Recovery Strategy

**SELECTED:** ✅ **Hybrid (Restore + Synthesize)**

**Execution:**
- Use git history to recover LUPOPEDIA_HEADERS from last known-good state where available
- For files without recoverable git history, generate synthetic headers with:
  - `actor_id: 11` (THOTH — Knowledge & Records)
  - `actor_name: "thoth"`
  - `channel_id: 42`
  - `when_updated: <current UTC timestamp>`
  - `tags: ["database", "table", "regenerated", "4.0.88"]`
  - `generated: true` flag in footer for transparency

**Rationale:** Preserves historical attribution where available; ensures complete metadata coverage without gaps.

**Validation:** All headers must pass `validate_lupopedia_headers.php` before closure.

---

### 2. Edge Reconstruction Strategy

**SELECTED:** ✅ **Hybrid (Restore + Scan + Database)**

**Execution:**
- **Primary Source:** Restore edges from git history (preserves semantic relationships and high-confidence data)
- **Secondary Source:** Static code scan of PHP/Python files for table references
  - Tools: `grep`, custom Python static analysis
  - Search patterns: table names in INSERT/UPDATE/SELECT/WHERE clauses
  - Filter for high-confidence matches (immediate context = table operation)
- **Tertiary Source:** Query `lupo_edges` DB table for existing relationships
  - Extract edges where `left_object_type='code'` or `right_object_type='code'`
  - Preserve weight values and metadata

**Confidence Scoring:**
- `confidence: 1.0` for git-restored edges (historical ground truth)
- `confidence: 0.8` for DB-stored edges (recent history)
- `confidence: 0.6-0.7` for code-scan discovered edges (inferred from context)
- `confidence: 0.4-0.5` for synthesized default edges (minimal semantic content)

**Rationale:** Maximizes edge recovery while accounting for uncertainty in reconstructed relationships.

**Validation:** All edges must have `confidence` field; edges below 0.5 must be manually reviewed before posting.

---

### 3. Aspirational / Non-Schema Tables

**SELECTED:** ✅ **Remove Completely**

**Execution:**
- Tables documented in `docs/database/lupopedia/tables/active/` that are **NOT** in `install_new_lupopedia.sql` shall be:
  - Identified in precondition audit
  - Moved to `docs/archived/non_schema_tables_20260327/` (new directory)
  - Indexed in `DEPRECATION_MANIFEST.md` for reference
  - Removed from `active/` directory
  - Removal documented in 4.0.88 changelog

**Exception:** Tables marked DEPRECATED in install SQL comments (lupo_actor_edges, lupo_decisions, etc.) keep documentation in appropriate `deprecated/` subdirectory with clear status markers.

**Rationale:** Keeps active documentation aligned with canonical schema; aspirational features are tracked separately per doctrine.

**Examples to archive:**
- `lupo_emotional_*.md` (not in install SQL)
- `lupo_persona_*.md` (aspirational, not deployed)
- `lupo_kapu_*.md` (experimental, not in schema)
- ~10-12 others identified in THOTH validation (Section 3.3)

---

### 4. Regeneration Scope

**SELECTED:** ✅ **Corrupted Files Only (~76 files)**

**Execution:**
- Regenerate ONLY the ~76 files identified in initial corruption audit
- Non-corrupted files in `active/` remain untouched (no unnecessary rewrites)
- After regeneration, run full validation scan on entire `active/` directory to catch any other latent corruption
- If additional corruption found, escalate to THOTH for decision on inclusion in scope

**Rationale:** Surgical approach minimizes blast radius; addresses root issue without unnecessary file touches.

**Scope verification:**
- List of 76 corrupted files provided by initial audit
- Confirm each file before regeneration
- Log regenerated files with checksums for traceability

---

### 5. Validation Level

**SELECTED:** ✅ **Full Semantic Validation**

**Execution:** Multi-layer validation required before closure.

| Layer | Validator | Gate | Purpose |
|-------|-----------|------|---------|
| **Syntax** | `validate_lupopedia_headers.php` | Required | YAML format, required fields |
| **Semantic** | THOTH (manual + script) | Required | Edge targets, confidence, edge types |
| **Schema Alignment** | THOTH schema validator | Required | Regenerated docs vs. TOON exports |
| **Graph Consistency** | Semantic indexer | Required | Docs reachable via edges, no orphans |
| **Channel Routing** | Integration test | Required | Docs appear in channel 42 where applicable |

**Post-Regeneration Validation Checklist:**
- ✅ All 76 files have valid YAML headers
- ✅ All edges have `confidence >= 0.4` 
- ✅ All edge targets exist (no broken references)
- ✅ No orphaned docs (all reachable via edges)
- ✅ Schema sections match TOON exports
- ✅ Channel 42 routing works (docs discoverable)
- ✅ Spot-check 5-10 docs for accuracy
- ✅ Semantic graph indexing completes without errors

**Rational:** Regeneration is only as good as its validation. Full validation ensures integrity.

---

### 6. Tooling Approval

**SELECTED:** ✅ **APPROVED**

**Required Tools:**

| Tool | Status | Notes |
|------|--------|-------|
| `generate_table_docs_from_toons.py` | **NOT YET CREATED** | **MUST CREATE** before regeneration. Converts TOON → Markdown + headers. Single responsibility: read TOON, generate `.md` with schema, headers (synthetic), minimal edges (template). |
| `verify_db_against_toons.py` | ✅ Exists (scripts/) | Precondition 1 gate check. Run before TOON regeneration. |
| Git history / manual edge restore | ✅ Manual process | Requires git CLI + human review. Executable immediately. |
| Code-scan edge discovery (tooling) | **OPTIONAL** | Can be created during Phase 2 if needed; not blocking Phase 1. Simple grep + Python filter. |
| `validate_schema_alignment.py` | **OPTIONAL** | Can be created during Phase 3 for automated schema comparison; not blocking. |

**Tooling Constraints:**
- All new scripts MUST include LUPOPEDIA_HEADERS with:
  - `file_path_from_root` (accurate path)
  - `artifact_type: "tooling"`
  - `artifact_kind: "script"`
  - `last_modified_utc` (creation time)
  - `actor_id: 1` (WOLFIE) and `actor_name: "wolfie"` for direction scripts
- All scripts MUST output include `created_at` and `completed_at` timestamps
- All scripts MUST log file operations (create count, update count, skip count, error count)

---

### 7. Execution Authorization

**✅ FINAL CALL: AUTHORIZED TO PROCEED**

**Declaration:**

> WOLFIE hereby authorizes HEPHAESTUS to commence Phase 1 (Preparation) of Table Documentation Regeneration immediately. All preconditions from THOTH validation must be satisfied before Phase 2 (Regeneration) execution. THOTH and LILITH required for post-execution validation sign-off.

**Conditions:**
1. Preconditions checked and satisfied (Section 8 below)
2. `generate_table_docs_from_toons.py` created before Phase 2
3. All decision criteria applied throughout execution
4. Phase 1 completion report delivered to WOLFIE before Phase 2 start
5. Full semantic validation completed before thread closure

**Authority:** WOLFIE (actor_id 1), Orchestrator. No further escalation required.

---

## EXECUTION SUMMARY TABLE

| Dimension | Decision | Owner | Timeline |
|-----------|----------|-------|----------|
| Metadata | Hybrid (restore + synthesize) | HEPHAESTUS | Phase 2 |
| Edges | Hybrid (restore + scan + DB) | HEPHAESTUS | Phase 2 |
| Aspirational Tables | Remove completely (archive) | HEPHAESTUS | Phase 1 audit |
| Scope | Only corrupted files (~76) | HEPHAESTUS | All phases |
| Validation | Full semantic | THOTH/LILITH | Phase 3 |
| Tooling | Approved (create generator) | HEPHAESTUS | Phase 1 |
| **Authorization** | **✅ PROCEED** | **WOLFIE** | **Immediate** |

---

## PHASE 1 PREPARATION CHECKLIST

**Owner:** HEPHAESTUS  
**Timeline:** Before Phase 2 regeneration  
**Deliverable:** Phase 1 Completion Report

### Pre-Checks

- [ ] Run `verify_db_against_toons.py` → confirm zero schema mismatches between live DB and install SQL
  - **Gate:** If mismatches found, halt and report to WOLFIE. Resolve drift before proceeding.
- [ ] Audit git history for corrupted files
  - **Command:** `git log --oneline docs/database/lupopedia/tables/active/ | head -50`
  - **Goal:** Identify commits before corruption; determine if clean state recoverable
- [ ] Identify ~10-12 aspirational tables for archival
  - **Source:** THOTH validation Section 3.3 list
  - **Action:** Create `docs/archived/non_schema_tables_20260327/` directory
  - **Log:** Create `DEPRECATION_MANIFEST.md` listing moved files + rationale

### Tooling Creation

- [ ] Create `scripts/generate_table_docs_from_toons.py`
  - **Input:** `database/lupopedia/toon/` directory + TOON file list
  - **Output:** Markdown files in `docs/database/lupopedia/tables/active/` with:
    - LUPOPEDIA_HEADERS (actor_id: 11, channel_id: 42, tags, version)
    - Schema section (fields, indexes, primary key from TOON)
    - Doctrine section (from TOON `doctrine_metadata`)
    - Minimal edges block (template or empty, to be filled in Phase 2)
    - LUPOPEDIA_FOOTER (last_verified timestamp, generated: true flag)
  - **Requirements:**
    - Script must have LUPOPEDIA_HEADERS in file header
    - Must log progress (files read, docs generated, errors)
    - Must validate output against header format before writing
    - Must skip non-corrupted files (input: corruption manifest)
  - **Verification:** Script must produce valid YAML headers (run quick `yamllint` test on 3-5 outputs)

### Precondition Reports

- [ ] **DB Validation Report**
  - Output: `channels/42/threads/2007/20260327_221000_hephaestus_phase1_db_validation.md`
  - Include: Table count, column count, index count vs. install SQL
  - Include: List of any mismatches found (hopefully zero)

- [ ] **Git History Audit Report**
  - Output: `channels/42/threads/2007/20260327_221500_hephaestus_phase1_git_history_audit.md`
  - Include: Last clean commit (if found) and timestamp
  - Include: Commits between clean state and corruption (if determinable)
  - Include: List of files with recoverable history vs. no history

- [ ] **Aspirational Table Inventory**
  - Output: `docs/archived/non_schema_tables_20260327/DEPRECATION_MANIFEST.md`
  - Include: Files moved, rationale (not in install SQL vs. deprecated in schema)
  - Include: Archive path for each file
  - Include: Decision for each (remove vs. deprecated subdirectory)

- [ ] **Tooling Approval Report**
  - Output: `channels/42/threads/2007/20260327_222500_hephaestus_phase1_tooling_status.md`
  - Include: `generate_table_docs_from_toons.py` creation status
  - Include: Test run results (e.g., "Generated 5 sample docs, all headers valid")
  - Include: Any blockers or concerns

### Phase 1 Completion Report

- [ ] **Deliver:** `channels/42/threads/2007/20260327_225000_hephaestus_phase1_completion_report.md`
  - Executive summary (all preconditions passed or blocked)
  - Details from each sub-report above
  - Approval to proceed to Phase 2 (yes/no/conditional)
  - Signed by HEPHAESTUS with timestamp

**Gate:** WOLFIE reviews Phase 1 report. If all conditions met, authorizes Phase 2. If blockers, escalates or defers.

---

## NEXT ACTIONS (Immediate)

| Action | Owner | Timeline |
|--------|-------|----------|
| **1. Run `verify_db_against_toons.py`** | HEPHAESTUS | Next 30 min |
| **2. Audit git history** | HEPHAESTUS | Next 30 min |
| **3. Create `generate_table_docs_from_toons.py`** | HEPHAESTUS | Next 2 hours |
| **4. Complete Phase 1 checklist** | HEPHAESTUS | Next 4-6 hours |
| **5. Submit Phase 1 Completion Report** | HEPHAESTUS | End of shift |
| **6. WOLFIE reviews + approves Phase 2** | WOLFIE | Within 2 hours of report |
| **7. Phase 2 regeneration execution** | HEPHAESTUS | Day 2 morning |

---

## AUTHORIZATION STATEMENT

**By WOLFIE (Orchestrator, actor_id 1):**

> This directive locks all remediation decisions for Thread 2007 table documentation regeneration. Execution is **authorized and encouraged**. HEPHAESTUS has full authority to execute Phase 1 and Phase 2 per this directive. Decisions are final; no further escalation required unless blockers are encountered. Post-execution validation by THOTH and LILITH is mandatory before thread closure.
>
> **Signed:** WOLFIE  
> **Date:** 20260327 223000 UTC  
> **Authority:** Orchestrator  
> **Status:** ✅ APPROVED TO PROCEED

---

## RELATED ARTIFACTS

- [THOTH Semantic Truth Validation](channels/42/threads/2007/20260327_220000_thoth_semantic_truth_validation_regeneration_source.md) — Source authority assessment
- [ATHENA Remediation Plan](channels/42/threads/2007/20260327_233000_wolfie_lupopedia_organization_4_0_88_integration_and_corruption_assessment.md) — Remediation context
- [Thread Index](channels/42/threads/2007/THREAD_INDEX.md) — Navigation

---

**END DIRECTIVE**
