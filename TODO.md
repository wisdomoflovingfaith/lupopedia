---
version_when_written: "4.0.84"
file_path_from_root: "TODO.md"
web_path: "http://www.lupopedia.com/TODO.md"
last_modified_utc: "20260320"
project_id: 0
project_slug: "lupopedia-core"
channel_id: 42
thread_id: 1041
task_id: "task_todo_maintenance_001"
actor_id: 1
actor_name: "wolfie"
delegation_chain: "wolfie:root"
artifact_type: "task_list"
artifact_kind: "task_list"
purpose: "Active execution queue for 4.0.84 - rolled forward from 4.0.83"
tags: ["wolfie", "todo", "task-registry", "4.0.84", "protocol-development", "global-task-registry"]
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "task_coordination"
    channel_id: 42
    thread_id: 1041
    session_mode: "development"
    project_id: 0
    project_slug: "lupopedia-core"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"

next_action:
    - "Resolve Thread 1001 test-evidence inconsistency for full production deployment"
    - "Complete LUPOPEDIA_HEADERS doctrine cleanup and single-field versioning model enforcement (4.0.84)"
    - "Remove all lupopedia.version, system_version, last_verified_system_version from documentation"
    - "Update LUPOPEDIA_HEADERS_FORMAT.md with baseline rewrite rules"
    - "Convert VERSIONING_MODEL.md to obsolete stub"
    - "Add LILITH edge case analysis to README.md"
    - "Create WOLFIE script generate_headers_from_db.py for TOON-based header generation"
    - "Implement Thread 1004 semantic validation fixes"
    - "Coordinate HEPHAESTUS implementation across channels 66, 88, and 42"
    - "Monitor Channel 66 production deployment safety"
---

# file: Root TODO — canonical multi-agent coordination (TSK001)

**Blessed by WOLFIE** per [todo-authority-alignment](lupo-channels/42/threads/1001/20260318_050000_wolfie_todo-authority-alignment.md) (closes HERMES prompt **004501**). Doctrine: [MULTI_AGENT §9](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md#9-todo-authority-two-tier).

**Authority:** [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [4.0.80 release readiness](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md) · [LILITH remaining-work P0–P2](lupo-channels/42/threads/1001/20260318_001200_lilith_remaining-work-4.0.80.md). **Master sync (4.0.83):** [WOLFIE master shutdown consolidation](lupo-channels/1/threads/1035/20260319_190000_wolfie_master_shutdown_consolidation.md). **Archived version backlogs:** e.g. [lupo-docs/versions/4.0.80/TODO.md](lupo-docs/versions/4.0.80/TODO.md) — not the live execution queue.

**Pick up work from `lupo-channels/42/prompts/`** — files below are **pending execution** until to target actor posts a result artifact or marks done in a follow-up commit.

---

## Global Task Registry (Option A)

| task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes |
|---|---|---|---|---|---|---|---|---|---|---|
| task_impl_001 | Implement Option A TODO.md + plan.md restructuring | 14:hephaestus | resolved | complete | 1005 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1005/20260318_142300_hephaestus_directive_task_impl_001_kickoff.md | Structural migration complete (see thread 1005 status artifact). |
| task_prompt_010100 | A12 QA checklist | 2:lilith | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1001/20260318_093000_lilith_review_formal-a12-pass-fail-checklist.md | Formal A12 checklist artifact exists, but Final Verdict is FAIL (constitutional compliance signoff missing). |
| task_prompt_022050 | External AI batch completion | 15:hermes | resolved | complete | 1001 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1001/20260318_133928_hermes_report_externalai-batch-coverage-1001-1002.md | Coverage report deliverable fulfilled for threads 1001 + 1002 (what remains is A12 compliance + watcher acceptance). |
| task_prompt_041000 | Table-doc authorship fix | 1:wolfie | resolved | complete | 1001 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1001/20260318_090000_wolfie_directive_table-doc-authorship-closure.md | Prompt 041000 closed (authorship confirmed VALID; HERMES routing corrected). |
| task_prompt_234200 | Prompt routing automation policy (watcher/auto-draft) | 14:hephaestus | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md | Spec exists in thread 1002; watcher auto-draft status is still `draft` in thread 1001, pending WOLFIE acceptance. |
| task_plan_001 | Planning system spec (Option A) | 12:athena | resolved | complete | 1004 | P1 | 20260318_141109 | 20260318_150000 | lupo-channels/42/threads/1004/20260318_150000_wolfie_directive_task_plan_001_review.md | Spec accepted by WOLFIE; implementation allocated as task_impl_001. |
| task_doc_001 | Documentation alignment + thread model explanation | 26:thoth | resolved | complete | 1003 | P1 | 20260318_170000 | 20260318_175000 | lupo-channels/42/threads/1003/20260318_175000_thoth_status_task_doc_001_complete.md | README updated for 4.0.81 thread/task model; awaiting archival directive. |
| task_doc_1004_mapping_revision_001 | Thread 1004 revised structural mapping (post-LILITH) | 4:athena | resolved | complete | 1004 | P1 | 20260319_300000 | 20260319_300000 | lupo-channels/88/threads/1004/20260319_300000_athena_revised_structural_mapping_model_crafty_lupo_after_lilith_attack.md | Corrected Thread 1004 structural mapping model: verified 34 legacy `livehelp_*` tables, fixed transcript/config/Q&A transformation scope vs canonical import SQL, and defined explicit SQL-first documentation authority hierarchy. Next step: WOLFIE narrowing; HEPHAESTUS blocked on remaining P0 semantic validation. |
| task_thread1001_audit_repost_001 | Thread 1001: repost Channel 66 Phase 1 audit into thread-local working artifact | 1:wolfie | resolved | complete | 1001 | P0 | 20260319_200000 | 20260319_200000 | lupo-channels/66/threads/1001/20260319_200000_wolfie_audit_channel66_system_phase1_thread_repost.md | Routing fix applied: supersedes misrouted `lupo-docs/status/...` audit for active Channel 66 work. |
| task_thread1001_reframe_arch_001 | Thread 1001: respond to LILITH attack and reframe Channel 66 architecture decision boundaries | 1:wolfie | resolved | complete | 1001 | P0 | 20260319_220000 | 20260319_220000 | lupo-channels/66/threads/1001/20260319_220000_wolfie_response_lilith_attack_reframed_architecture.md | P0 header ingestion prerequisite; reject `lupo_dialog_threads` for question semantics; Bayesian explicitly excluded from Phase 1; precedence frame updated. |
| task_thread1001_bounded_authority_ingestion_p0_impl_001 | Channel 66 Thread 1001: build and execute P0 bounded-authority header ingestion scaffold | 3:hephaestus | resolved | complete | 1001 | P0 | 20260319_390000 | 20260319_390000 | lupo-channels/66/threads/1001/20260319_390000_hephaestus_implementation_results_p0_bounded_authority_ingestion.md | Implemented runner + validators + deterministic `lupo_metadata` projection + JSONL logging, and added fixture-based P0 unit test suite (evidence: `38 passed, 0 failed`) (including concurrent-edit detection and toon-schema cache invalidation coverage). |
| task_thread1003_narrowing_decision_ready_001 | Thread 1003: decision-ready narrowing for collections vs namespaces | 1:wolfie | resolved | complete | 1003 | P1 | 20260319_290000 | 20260319_290000 | lupo-channels/66/threads/1003/20260319_290000_wolfie_narrowing_collections_namespaces_decision_ready.md | Narrows ATHENA’s revised model into operational working position; doctrine impacts identified; implementation blocked until doctrine update. |
| task_thread88_1004_create_question_001 | Channel 88 Thread 1004: create Crafty→Lupo table mapping question thread | 1:wolfie | resolved | complete | 1004 | P1 | 20260319_230000 | 20260319_230000 | lupo-channels/88/threads/1004/20260319_230000_wolfie_question_crafty_lupo_table_mapping.md | Central question node created; links Crafty schema/install SQL + existing mapping docs to support deterministic upgrade mapping investigation. |
| task_thread1005_single_field_versioning_closure_001 | Thread 1005: single-field versioning model closure and doctrine lock | 1:wolfie | resolved | complete | 1005 | P0 | 20260320_610000 | 20260320_610000 | lupo-channels/66/threads/1005/20260320_610000_wolfie_final_closure_doctrine_lock_single_field_versioning.md | WOLFIE final closure: single-field versioning model doctrine-locked; only version_when_written stored in new artifacts; lupopedia.version and system_version forbidden; all components operational and verified. |
| task_deferred_0001 | Table-doc path deduplication | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0002 | UI channel visualization | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0003 | DB-primary channel ingestion | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0004 | Artifact auto-healing | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0005 | External read capabilities | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_schema_implementation_database_backed_visibility_001 | Canonical schema implementation for database-backed visibility | 1:wolfie | active | in_progress | 1031 | P0 | 20260320_182000 | 20260320_182000 | lupo-channels/42/threads/1031/20260320_182000_wolfie_directive_canonical_schema_implementation_database_backed_visibility.md | Phase 1 complete - schema changes implemented in install SQL. |
| task_channel42_db_visibility_reconciliation_001 | Database visibility reconciliation and table doc corrections | 26:thoth | resolved | complete | 1030 | P0 | 20260320_174500 | 20260320_181000 | lupo-channels/42/threads/1030/20260320_181000_thoth_table_doc_correction_set_phase2_execution.md | All table docs corrected, schema authority restored. |

## 4.0.84 Active Tasks (Rolled Forward from 4.0.83)

### Channel 88 Active Tasks
- **task_channel88_1004_semantic_validation_001** — Thread 1004 semantic validation and P0 blocker resolution
  - **Status**: 🔄 ACTIVE - P0 semantic blocker identified
  - **Critical Issue**: `lupo_visits.actor_id` semantic mapping invalid
  - **Details**: `livehelp_visits_daily.livehelp_id` incorrectly mapped to `lupo_visits.actor_id`
  - **Next Steps**: HEPHAESTUS implementation plan pending
  - **Thread**: lupo-channels/88/threads/1004/

### Channel 42 Active Tasks
- **task_schema_implementation_database_backed_visibility_001** — Thread 1031 canonical schema implementation for database-backed visibility
  - **Status**: 🔄 ACTIVE - Phase 1 complete, Phase 2 pending
  - **Progress**: Schema changes implemented in install SQL, TOON files updated
  - **Next**: Phase 2 - TOON regeneration by THOTH
  - **Thread**: lupo-channels/42/threads/1031/

- **task_channel42_coordination_repair_001** — Thread 1028 file-visible coordination recovery strategy
  - **Status**: 🔄 ACTIVE - ATHENA strategy development
  - **Progress**: Coordination templates and delegation framework established
  - **Thread**: lupo-channels/42/threads/1028/

- **task_channel42_thread_tree_normalization_001** — Thread 1029 parent-child thread hierarchy normalization
  - **Status**: 🔄 ACTIVE - ATHENA strategy for thread tree normalization
  - **Progress**: Thread hierarchy model designed, migration strategy prepared
  - **Thread**: lupo-channels/42/threads/1029/

### Channel 66 Active Tasks
- **task_channel66_1001_routing_violations_001** — Thread 1001 routing violations and doctrine compliance [COMPLETED — 20260320 — verified by LILITH]
  - **Status**: Completed
  - **Issue**: Routing violations identified in audit artifact placement - RESOLVED
  - **Resolution**: Documentation reconciliation workstream complete - all P0 violations resolved
  - **Thread**: lupo-channels/66/threads/1001/

- **task_channel66_1004_semantic_validation_001** — Thread 1004 documentation consistency and semantic validation
  - **Status**: 🔄 ACTIVE - P0 SEMANTIC ATTACK IN PROGRESS
  - **Critical Issue**: Same P0 semantic blocker as Channel 88 Thread 1004
  - **Details**: `lupo_visits.actor_id` mapping violates data meaning integrity
  - **Next Steps**: Awaiting HEPHAESTUS implementation plan
  - **Thread**: lupo-channels/66/threads/1004/

### Channel 42 Active Tasks

- **task_channel42_thread_hierarchy_phase1_closure_001** — Thread 1029 phase-1 hierarchy normalization and governance closure
  - **Status**: ✅ COMPLETED (closure-ready; final audit and strategy validation complete)
  - **Artifacts**:
    - lupo-channels/42/threads/1029/20260320_170000_wolfie_omnibus_adjudication_execution_directive_provisional_thread_set_phase_1.md
    - lupo-channels/42/threads/1029/20260320_172000_lilith_final_governance_audit_phase_1_closure_readiness_thread_hierarchy_normalization.md
    - lupo-channels/42/threads/1029/20260320_173000_athena_final_validation_strategy_alignment_phase_1_closure_thread_hierarchy_normalization.md
  - **Outcome**: phase-1 governance closure criteria satisfied without phase-2 scope expansion.

- **task_channel42_db_visibility_reconciliation_001** — Database-backed channel/thread/task visibility planning and 4.0.84 documentation reconciliation
  - **Status**: 🔄 ACTIVE
  - **Thread**: lupo-channels/42/threads/1030/
  - **Scope**:
    - reconcile root docs with artifact-backed 4.0.84 state
    - define operational DB visibility model for channels, threads, tasks, ownership, and status
    - define deterministic web review interface path and phase-ordered execution plan
  - **Next Steps**:
    - produce strategy/reconciliation artifact with TOON and table-doc grounding
    - define DB projection/review workflow with no hidden sync
    - sequence implementation phases for docs, DB projection logic, UI review, and validation

### Completed Work (Channel 66)
- ✅ **Thread 1005** - Single-Field Versioning Model Implementation (CLOSED AND DOCTRINE-LOCKED)
- ✅ **Thread 1005** - System-wide documentation normalization
- ✅ **Thread 1005** - Enforcement doctrine implementation
- ✅ **Thread 1005** - Adversarial validation completion

### Channel Migration Execution (Priority 0)
- **task_channel_migration_execution_001** — Channel 42 thread-to-channel migration (copy-not-move)
  - **Status**: HERMES table lists **24** mapped threads; HEPHAESTUS copy + redirects executed (see [enforcement_and_migration audit](lupo-channels/7/threads/1035/20260318_212358_hephaestus_enforcement_and_migration.md)); **THOTH** (THREAD_INDEX / docs) and **LILITH** (audit) and **HERMES** (checklists) may still need closure artifacts.
  - **Artifacts**: [HERMES mapping](lupo-channels/42/threads/1027/20260318_155033_hermes_report_thread_channel_mapping.md) · [Ratification 1033](lupo-channels/51/threads/1033/20260319_170500_wolfie_directive_channel-migration-ratification.md)

### Actor-Facet Separation Doctrine Implementation (Priority 0)
- **task_actor_facet_separation_doctrine_001** - Complete implementation of actor-facet separation doctrine
  - **Status**: Doctrine created, rule system complete
  - **Artifacts**: 
    - [ACTOR_FACET_SEPARATION_DOCTRINE.md](lupo-docs/doctrine/ACTOR_FACET_SEPARATION_DOCTRINE.md)
    - [lupo-rules/root/](lupo-rules/root/) - 20 canonical YAML rules
  - **Next Steps**: Facet bootstrapping validation, actor compliance verification
  - **Target**: All facets load rules from lupo-rules/root/

### Release Blockers (Carried into 4.0.83)

| # | Prompt | Target | Owner | Status | Notes |
|---|--------|--------|-------|--------|-------|
| 1 | [041000_wolfie_table-doc-authorship-003000.md](lupo-channels/42/prompts/20260318_041000_wolfie_table-doc-authorship-003000.md) | **WOLFIE** | done | Fix authorship on `184500` repair artifact |
| 2 | [022050_hermes_externalai-batch.md](lupo-channels/42/prompts/20260318_022050_hermes_externalai-batch.md) | **HERMES** | complete | Complete documentation and full coverage report |
| 3 | [010100_lilith_formal-a12-pass-fail-checklist.md](lupo-channels/42/threads/1001/20260318_093000_lilith_review_formal-a12-pass-fail-checklist.md) | **LILITH** | pending | Formal A12 pass/fail QA checklist + result (Final Verdict is FAIL; needs constitutional compliance signoff artifact) |
| 4 | [234200_athena_prompt-routing-watcher-policy.md](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md) §3–§4 | **HEPHAESTUS** | pending | Implement filesystem watcher / auto-draft per policy |

### 4.0.83 Deferred Work (Non-blocking)

| # | Task | Owner | Status | Notes |
|---|------|-------|--------|-------|
| 1 | Table-doc path deduplication | TBD | **4.0.83** | Resolve duplicate `tables/active/projects/` vs `active/lupo_*.md` paths |
| 2 | UI channel visualization | TBD | **4.0.83** | Build UI for browsing channel artifacts |
| 3 | DB-primary channel ingestion | TBD | **4.0.83** | Database-first channel operations |
| 4 | Artifact auto-healing | TBD | **4.0.83** | Self-repairing channel artifacts |
| 5 | External read capabilities | TBD | **4.0.83** | Public read-only channel access |

---

### 📋 Current Development Priorities

#### Thread 1005 Versioning Model Implementation - COMPLETED ✅
- ✅ ATHENA doctrine compliance confirmation completed - model is doctrinally compliant
- ✅ All components verified: resolver, template generator, validator, projection, tests
- ✅ Single-field versioning model ready for WOLFIE closure
- ✅ WOLFIE final closure and doctrine lock completed
- ✅ System-wide documentation normalization completed
- ✅ Thread 1005 CLOSED AND DOCTRINE-LOCKED
- ⚠️ Non-blocking cleanup: stale fallback in Channel66HeaderProjection.php line 405
- ⚠️ Cosmetic: class/file names still reference 'three_field' model

#### System-Wide Documentation Normalization - COMPLETED ✅
- ✅ CHANGELOG.md: Consolidated Thread 1005 entries, removed duplicates
- ✅ TODO.md: Removed completed Thread 1005 tasks, updated priorities
- ✅ PLAN.md: Marked Thread 1005 complete, added dedicated section
- ✅ README.md: Added clear "Versioning Model (Locked)" section
- ✅ LUPOPEDIA HEADERS doctrine cleanup (remove obsolete three-field model references; enforce `version_when_written`-only; add baseline rewrite-on-write rule; fix identity-line forms; canonicalize optional blocks docs) completed
- ✅ LUPOPEDIA HEADERS doctrine glue-layer rules completed (routing precedence, metadata identity/dedup, placeholder constraints, next_actions vs footer precedence, close deprecation behavior, objective edge update criteria) and deterministic `import_content.py` implemented
- ✅ Root-level header baseline pass completed for repo root and `lupo-docs/` root Markdown files: `version_when_written` aligned to `4.0.84` and missing LUPOPEDIA front matter added where absent
- ✅ `import_content.py` doctrine hardening completed: vendor-specific upsert removed, application-layer SELECT->UPDATE/INSERT applied, strict `last_modified_utc` validation enforced, deterministic expanded update columns on re-import, success output delayed until commit + file write
- ✅ THREAD_INDEX.md (Channel 66): Validated Thread 1005 closure status
- ✅ THREAD_INDEX.md (Channel 88): Updated monitoring to reflect completion
- ✅ System consistency achieved: Documentation matches reality across all files
- ✅ Contradiction detection: No forbidden version fields or doctrine conflicts found

#### Header Generation From DB - IN PROGRESS 🔄
- 🔄 `lupo-scripts/generate_headers_from_db.py` implementation started
- 🔄 TOON-grounded schema validation completed for `lupo_contents` and `lupo_metadata` (table/column names verified before coding)
- 🔄 DB->YAML deterministic mapping layer design in progress (canonical block order + legacy normalization rules)

#### Channel 66 Production Migration Fixes
- Resolve all 15 critical violations identified by LILITH final production gate review
- Implement atomic rollback and deployment safety mechanisms
- Fix bounded authority violations in retry logic and batch processing
- Address determinism issues in entity ID calculation and batch ordering
- Correct versioning compliance violations per LUPOPEDIA HEADERS doctrine

#### Thread 1004 Semantic Validation
- Implement semantic fix for `lupo_visits.actor_id` mapping issue
- Address P1 dropped table dependencies and edge case handling
- Complete Crafty to Lupopedia structural mapping with semantic validity

#### Multi-Agent Coordination
- Continue adversarial review process across channels 66, 88, and 42
- Maintain bounded authority compliance per Thread 1002 constraints
- Coordinate implementation fixes between HEPHAESTUS and validation teams

#### Documentation Quality
- Update all thread indexes with current status
- Maintain comprehensive changelog with reverse chronological order
- Ensure all artifacts have proper LUPOPEDIA HEADERS metadata

### Shutdown checkpoint (Global state sync)
- **task_global_state_sync_001** — confirm `CHANGELOG.md`, `TODO.md`, `plan.md` are aligned with: web_path deterministic rule, interpretation header model, and next limiter phases (login/posting/uploads; then global request guard; then pagination/search limits).

### web_path canonicalization (repo-wide)
- Run repo-wide deterministic normalization: `python lupo-scripts/generate_web_path.py --repo-root . --apply --path .`
- Re-run validation (Channel 42/other channels): `python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --actor-identity-validation`

### Identity & filename violations (cleanup)
- Fix actor registry mismatches in channel artifacts (e.g. artifacts authored as `hermes` but carrying `actor_id: 102`, and `actor_id: 1000` using non-canonical `actor_name`).
- Normalize non-canonical filenames flagged by `validate_channel_artifacts.py` (BAD_FILENAME).

### Request / rate limits (not yet implemented)
- Implement **RequestLimiter Phase 1**: login + channel posting + uploads (429/413/400 with stable error codes; audit logging).
- Implement **global request guard** (front controller): request-size limits, JSON depth limits, header size/count limits.
- Add pagination/search/export caps for expensive endpoints.

## Version truth (4.0.84 only for active work)

- **4.0.84** — **current** — root `TODO.md` / `plan.md` / `CHANGELOG.md` / `GLOBAL_CURRENT_LUPOPEDIA_VERSION` in `lupo-config/config/global_atoms.yaml`.
- **4.0.82 / 4.0.81 / 4.0.80** — historical; unfinished items live only as rows above.

---

*Last updated: 2026-03-20 (4.0.84 roll-forward aligned)*
