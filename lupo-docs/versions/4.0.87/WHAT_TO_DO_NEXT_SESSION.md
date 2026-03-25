---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  last_modified_utc: '20260325224500'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: handoff
  artifact_kind: next_session
  purpose: "4.0.87 release-state handoff. Updated 20260325 22:45 UTC with WS3 Phase D/E completion and ERQ-006 authorization."
  when_updated: '20260325224500'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  delegation_chain: cursor:root
  version_when_written: '4.0.87'

lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/versions/4.0.87/CHANGELOG.md
      type: references
      weight: 1.0
      reason: Session changelog for all 4.0.87 work
    - to: lupo-docs/versions/4.0.87/TASK_REGISTRY.md
      type: references
      weight: 1.0
      reason: Full task list with V487 IDs
    - to: lupo-docs/versions/4.0.87/TODO.md
      type: references
      weight: 0.95
      reason: Open/closed TODO items
    - to: lupo-docs/doctrine/DECISION_MODEL.md
      type: references
      weight: 0.9
      reason: New canonical decision doctrine created this session
    - to: lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md
      type: references
      weight: 0.8
      reason: Superseded doctrine, now marked DEPRECATED
    - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
      type: references
      weight: 0.85
      reason: Decision table DDL removed from install SQL; edge table DDL also removed
    - to: lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md
      type: references
      weight: 0.9
      reason: New canonical edge model doctrine created this session (WS2 Phase A)
    - to: lupo-docs/database/lupopedia/tables/active/lupo_edges.md
      type: references
      weight: 0.85
      reason: Canonical edge table doc updated as part of WS2 Phase A (WOLFIE directive)

lupopedia.footer:
  last_verified: '20260325224500'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
  next_action:
    - Publish final release packet artifact for 4.0.87
    - Capture admin channel-chat validation evidence artifact
    - Run final version-marker audit and archive report
    - Execute WS6 as post-release hardening if not already closed
    - Remove dev diagnostic scripts from root after release
---

# file: 4.0.87 next session handoff â€” delegation: cursor:root â€” web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md

# 4.0.87 NEXT SESSION

## Session State (as of 20260325 ~22:45 UTC — RELEASE AUTHORIZED)

- ERQ-006 WOLFIE signoff is complete.
- WS3 Phases D and E are complete (LILITH audit + THOTH synchronization).
- Release blockers are closed for WS1-WS5 and WS7.
- 4.0.87 is authorized for production deployment.
- Remaining tasks are post-release hardening and evidence packaging.

## Session State (as of 20260325 ~21:30 UTC — WS3 Doctrine Propagation)

- WS3 thread content was propagated into canonical doctrine surfaces.
- `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` now reflects the five-layer identity model with department bindings.
- `AGENTS.md` now includes the WS3 identity layers section and binding requirements.
- Doctrine stubs were created for remaining workstreams with direct thread traceability:
  - `lupo-docs/doctrine/ws4_hidden_intelligence_doctrine.md`
  - `lupo-docs/doctrine/ws5_questionable_tables_doctrine.md`
  - `lupo-docs/doctrine/ws6_test_suite_alignment_doctrine.md`
  - `lupo-docs/doctrine/ws7_documentation_reconciliation_doctrine.md`
- Remaining execution focus is now WS4, WS5, WS6, and WS7 reconciliation output.

## Session State (as of 20260325 ~20:00 UTC — Edge Model Consolidation WS2)

- **Edge Model Consolidation (WS2)** — executed by cursor/HEPHAESTUS.
- All redundant edge tables removed from active schema (2 tables: `lupo_actor_edges`, `lupo_reference_cited_by`; 3 others already absent from live DB).
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — DDL blocks for both tables replaced with deprecation comments.
- PHP code updated in 3 files: `EmergentRoleDiscovery.php` (3 queries), `ActorService.php` (1 JOIN), `audit_schema_doctrine.php` (1 array entry).
- TOON files deleted: `lupo_actor_edges.toon`, `lupo_reference_cited_by.toon`.
- Doc moved: `lupo_actor_edges.md` → `tables/deprecated/` with 4.0.87 headers.
- Dev migration script created: `dev_20260325_remove_redundant_edge_tables.sql`.
- PHP scan confirmed: zero remaining references to `lupo_actor_edges` or `lupo_reference_cited_by` in any `.php` file.
- **WOLFIE directive Phase A** (Tasks A/B/C/D) executed:
  - **Task A**: `lupo_edges.md` updated — canonical status, supported object types table, 10-type edge registry, consolidated query examples.
  - **Task B**: `lupo_reference_cited_by.md` deprecated doc refreshed — 4.0.87 headers, column migration mapping table, replacement queries.
  - **Task C**: `lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md` created — 9-section governance doctrine.
  - **Task D**: CHANGELOG.md updated with comprehensive WS2 entry.
- TASK_REGISTRY updated: V487-070 through V487-083.

## Session State (as of 20260325 13:11 UTC)

- **Bayesian Decision Table Removal (4.0.87)** — executed by Cursor (actor_id 102).
- All four Bayesian decision tables removed from active system:
  - `lupo_decisions`, `lupo_decision_edges`, `lupo_decision_evidence`, `lupo_decision_influences`
- Three PHP files deleted: `BayesianDecisionService.php`, `decisions-api.php`, `bayesian_decision_service_test.php`.
- DDL removed from `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (replaced by deprecation comment block).
- Four active table docs marked `status: DEPRECATED` with `deprecated_in_version: 4.0.87`.
- `lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md` superseded; marked `DEPRECATED`.
- **`lupo-docs/doctrine/DECISION_MODEL.md`** created as canonical replacement — decisions live in channels/threads/artifacts; ROSE is the interpretation layer.
- PHP scan confirmed: zero remaining references to `BayesianDecisionService` or `lupo_decision*` in any `.php` file.
- TASK_REGISTRY updated: V487-066 through V487-069.
- CHANGELOG updated with Bayesian removal section.
- TODO updated: Bayesian removal marked `[x]`.
- Architecture boundary now clean: DB = storage, ROSE = meaning, edges = structure.

## Session State (as of 20260325 12:31 UTC)

- Table-optimization thread outcomes are now synchronized into 4.0.87 docs.
- Session artifacts published:
  - `lupo-channels/table-structure-optimization/threads/20260325_163000_cursor_admin_ui_identity_alignment_4_0_87.md`
  - `lupo-channels/table-structure-optimization/threads/20260325_170000_athena_semantic_table_architecture_review_4_0_87.md`
  - `lupo-channels/table-structure-optimization/threads/20260325_123500_cursor_cip_system_removal_4_0_87.md`
  - `lupo-channels/table-structure-optimization/threads/20260325_123538_wolfie_schema_triage_rose_intelligence_realignment_4_0_87.md`
- CIP active runtime/schema/doc surfaces were removed as executed work, not deferred planning.
- WOLFIE directive now treats CIP as replaced by ROSE and enforces: DB = storage, edges = structure, ROSE = meaning.
- Validation run completed for this handoff against CHANGELOG, TASK_REGISTRY, TODO, and installer SQL state.

## Session State (as of 20260325 10:47 UTC)

- Cursor resumed ownership per WOLFIE takeover directive (thread 1054). All Q4/Q5 HEPHAESTUS-routed tasks executed by Cursor this session.
- Channel 66 thread 1047 Q1â€“Q7: **ALL RESOLVED** â€” see `lupo-channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md`.
- Channel 66 threads 1050/1051/1052: **ALL RESOLVED** with published decision artifacts.
- P0 SQL migrations (ERQ-001/ERQ-002) confirmed executed: 12 rows in `lupo_edge_types`, 12 rows in `lupo_edge_type_definitions`.
- Channel 62/63/64 closure artifacts published this session.
- All code changes syntax-validated and tested.
- Table-optimization channel corrected to actor-centric department model (`lupo_actor_departments` execution surface).
- Channel directory governance updated: all new channels must use `channel_slug` directories; legacy numeric directories remain compatibility paths.
- 4.0.87 docs synchronized (CHANGELOG/README/PLAN/DOCTRINE/OVERVIEW/SCOPE/TASK_REGISTRY/TODO/WHAT_TO_DO_NEXT_SESSION).

## Validation Snapshot (20260325 10:47 UTC)

- This handoff file was refreshed after applying the table-model and channel-path policy corrections.
- Cross-document consistency pass completed against:
  - `lupo-docs/versions/4.0.87/CHANGELOG.md`
  - `lupo-docs/versions/4.0.87/TASK_REGISTRY.md`
  - `lupo-docs/versions/4.0.87/TODO.md`
  - `lupo-docs/versions/4.0.87/README.md`
  - `lupo-channels/channel_creation_doctrine.md`
  - `lupo-channels/channel_index.md`
  - `lupo-channels/INDEX.md`

## Validation Snapshot (20260325 12:31 UTC)

- Confirmed CIP CREATE TABLE blocks removed from:
  - `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - `lupo-database/lupopedia/mysql/install/install_new_lupopedia_backup.sql`
- Confirmed `lupo-scripts/wolfie_orms.py` no longer references `lupo_calibration_impacts`.
- Confirmed active CIP table docs and architecture CIP docs removed from current surfaces.
- Confirmed `lupo-docs/database/lupopedia/tables/TABLE_INDEX.md` no longer includes `lupo_calibration_impacts`.

## Ordered Execution Checklist

### ✅ Completed This Session (20260325 ~20:00 UTC — Edge Model Consolidation WS2)

10. **Edge Table Redundancy Removal**: Read all 7 workstream planning threads before executing. Confirmed all tables empty. Removed `lupo_actor_edges` and `lupo_reference_cited_by` DDL from install SQL. Updated 3 PHP files with polymorphic `lupo_edges` migration. Deleted 2 TOON files. Moved `lupo_actor_edges.md` to deprecated. Created dev migration. PHP scan: zero stale references confirmed.
11. **WOLFIE Phase A Documentation Directive (Tasks A-D)**: Updated `lupo_edges.md` as canonical table doc. Refreshed `lupo_reference_cited_by.md` in deprecated folder. Created `EDGE_MODEL_DOCTRINE.md` (9 sections). Updated CHANGELOG.md with full WS2 entry. Published HEPHAESTUS completion status to channel 42 thread 1005 addressed to WOLFIE, LILITH, ATHENA, ROSE.

### ✅ Completed This Session (20260325 13:11 UTC — Bayesian decision table removal)

9. **Bayesian Decision Table Removal**: Full usage audit — confirmed zero runtime references (service and API were never wired into rest-loader.php or any bootstrap). Deleted `BayesianDecisionService.php`, `decisions-api.php`, `bayesian_decision_service_test.php`. Removed all four `CREATE TABLE` DDL blocks from install SQL (replaced with deprecation comment). Marked four table docs DEPRECATED (`lupo_decisions`, `lupo_decision_edges`, `lupo_decision_evidence`, `lupo_decision_influences`). Superseded `BAYESIAN_DECISION_DOCTRINE.md`. Created `lupo-docs/doctrine/DECISION_MODEL.md` as canonical replacement.

### ✅ Completed This Session (20260324 23:00 UTC)

1. **Q4**: Read-only staleness panel added to `admin.php` Dashboard behind `$isAdmin` â€” queries `lupo_metadata` for `last_verified < 20260301000000` or NULL, read-only.
2. **Q5**: Tier 2 (semantic range) and Tier 3 (role-integrity) validators added to `lupo-scripts/generate_headers_from_db.py`. Unit tests: 9/9 pass (`lupo-tests/unit/test_header_validators.py`).
3. **ERQ-001/ERQ-002 verified**: Both SQL migrations already executed (12 rows each). Backfill SQL is correct no-op (0 channels with parent_channel_id).
4. **EdgeQueryService**: `lupo-includes/classes/EdgeQueryService.php` created â€” 11 read-only query methods covering object/type/channel lookups, aggregate counts, and duplicate guard.
5. **Channel 62 closure**: `lupo-channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md` published.
6. **Channel 63 closure**: `lupo-channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md` published.
7. **Channel 64 closure**: `lupo-channels/edge_generation_governance/threads/6401/20260324_230000_cursor_edge_governance_closure.md` published (ERQ-001 âœ…, ERQ-002 âœ…, ERQ-006 pending WOLFIE).
8. **Channel 66 threads 1050/1051/1052**: Confirmed all three are already resolved (resolution artifacts from 20260324 session).

### ðŸ”„ Remaining (Next Session)

**4.0.87 Critical Findings Workstreams (unblocked after WS1+WS2 completion):**

1. **WS6 — Test Suite Update (LILITH, thread 1001)** — **HIGHEST PRIORITY: NOW UNBLOCKED**. Remove/update tests referencing `lupo_actor_edges`, `lupo_reference_cited_by`, or Bayesian decision tables. Check `EmergentRoleDiscovery.php`, `ActorService.php`, `audit_schema_doctrine.php` for test coverage gaps. Thread: `lupo-channels/42/threads/1001/`.
2. **WS3 — Identity Model Clarification (ATHENA, thread 1006)** — Design artifact written in thread 1006, no implementation. Document `lupo_auth_users` / `lupo_actors` / `lupo_agents` / `lupo_agent_faucets` separation; actor_id ranges; faucet agent entries (100-106). Thread: `lupo-channels/42/threads/1006/`.
3. **WS4 — Hidden Intelligence Tables Audit (THOTH, thread 1007)** — Investigate `lupo_human_request_context`, `lupo_human_request_responses`, `lupo_actor_moods`, `lupo_emotional_constellations`. Classify CIP-style vs. legitimate. Thread: `lupo-channels/42/threads/1007/`.
4. **WS5 — Questionable Tables Audit (THOTH, channel 66 thread 1008)** — 12 tables to classify (including `lupo_meta_log_events`, `lupo_memory_events`, `lupo_pack_role_registry`). Thread: `lupo-channels/66/threads/1008/`.
5. **WS7 — Documentation Reconciliation Phase B-D (THOTH, thread 1034)** — Phase A complete. Phase B: decision system docs. Phase C: identity/intelligence/questionable tables. Phase D: final CHANGELOG/TODO/plan.md. Thread: `lupo-channels/42/threads/1034/`.

**Ongoing release gates:**

6. **ERQ-006**: Route WOLFIE release signoff via channel 66 — blocks final 4.0.87 release gate.
7. **admin.php validation**: Validate `section=channel-chat` path against `/api/channels/{id}/messages` behavior; capture evidence artifact.
8. **Atom/version audit**: Close remaining `4.0.86` references; publish audit output.
9. **Release packet**: Finalize CHANGELOG.md with complete UTC execution log.
10. **Validation refresh**: Regenerate `lupo-tools/flare_validate_issues.json` after CIP + edge deletions.
11. **Cleanup**: Remove dev diagnostic scripts (`check_edge_state.php`, `check_metadata_state.php`, etc.) from root after release.

## Resolved This Session (20260324 22:00 UTC â€” WOLFIE takeover per thread 1054 directive)

| Q | Decision | Status |
|---|----------|--------|
| Q1 | Header reimport deprecated; one-way DBâ†’files; upsert for new records only | âœ… Closed |
| Q2 | Creating channel owns single `lupo_metadata` record; cross-channel = edges | âœ… Closed |
| Q3 | Headers in files are immutable snapshots; edit DB â†’ regenerate | âœ… Closed |
| Q4 | Read-only staleness panel in `admin.php` behind `$isAdmin` | âœ… Implemented |
| Q5 | Tier 2 + Tier 3 timestamp checks in `generate_headers_from_db.py` | âœ… Implemented |
| Q6 | `when_updated` is file-global; never per-channel | âœ… Closed |
| Q7 | Global admin, CLI/local only, `--dry-run` default, audit log mandatory | âœ… Closed |

## Artifacts Published This Session (20260324 23:00 UTC)

- Channel 62 closure: `lupo-channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md` âœ…
- Channel 63 closure: `lupo-channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md` âœ…
- Channel 64 closure: `lupo-channels/edge_generation_governance/threads/6401/20260324_230000_cursor_edge_governance_closure.md` âœ…
- `lupo-docs/versions/4.0.87/CHANGELOG.md` updated with session execution log âœ…
- `lupo-docs/versions/4.0.87/TASK_REGISTRY.md` updated with V487-050 through V487-057 âœ…
- `lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md` updated (ERQ-001/002 closed) ✅

## Artifacts Published This Session (20260325 13:11 UTC — Bayesian removal)

- `lupo-database/lupopedia/content/lupo-app/Services/BayesianDecisionService.php` deleted ✅
- `lupo-includes/modules/api/decisions-api.php` deleted ✅
- `lupo-tests/unit/bayesian_decision_service_test.php` deleted ✅
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` DDL blocks removed ✅
- `lupo-docs/database/lupopedia/tables/active/lupo_decisions.md` marked DEPRECATED ✅
- `lupo-docs/database/lupopedia/tables/active/lupo_decision_edges.md` marked DEPRECATED ✅
- `lupo-docs/database/lupopedia/tables/active/lupo_decision_evidence.md` marked DEPRECATED ✅
- `lupo-docs/database/lupopedia/tables/active/lupo_decision_influences.md` marked DEPRECATED ✅
- `lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md` superseded + marked DEPRECATED ✅
- `lupo-docs/doctrine/DECISION_MODEL.md` created (canonical channel/thread decision model) ✅
- `lupo-docs/versions/4.0.87/CHANGELOG.md` updated with Bayesian removal entry ✅
- `lupo-docs/versions/4.0.87/TASK_REGISTRY.md` updated (V487-066 through V487-069) ✅
- `lupo-docs/versions/4.0.87/TODO.md` updated (Bayesian removal marked [x]) ✅
- `lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md` this file updated ✅

## Artifacts Published This Session (20260325 ~20:00 UTC — WS2 Edge Model Consolidation)

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — `lupo_actor_edges` DDL removed ✅
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — `lupo_reference_cited_by` DDL removed ✅
- `lupo-includes/classes/EmergentRoleDiscovery.php` — 3 queries migrated to `lupo_edges` ✅
- `lupo-database/lupopedia/content/lupo-app/Services/ActorService.php` — JOIN migrated to `lupo_edges` ✅
- `lupo-scripts/audit_schema_doctrine.php` — `lupo_actor_edges` removed from soft-delete check ✅
- `lupo-database/lupopedia/toon/lupo_actor_edges.toon` — deleted ✅
- `lupo-database/lupopedia/toon/lupo_reference_cited_by.toon` — deleted ✅
- `lupo-docs/database/lupopedia/tables/deprecated/lupo_actor_edges.md` — moved from active + 4.0.87 headers ✅
- `lupo-database/lupopedia/mysql/migrations/dev_20260325_remove_redundant_edge_tables.sql` — created ✅
- `lupo-channels/42/threads/1005/20260325_200000_hephaestus_status_edge_consolidation_execution_complete.md` — HEPHAESTUS status ✅
- `lupo-docs/database/lupopedia/tables/active/lupo_edges.md` — canonical doc updated (4.0.87) ✅
- `lupo-docs/database/lupopedia/tables/deprecated/lupo_reference_cited_by.md` — deprecated doc refreshed (4.0.87) ✅
- `lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md` — created (canonical 9-section doctrine) ✅
- `lupo-docs/versions/4.0.87/CHANGELOG.md` — Workstream 2 entry appended ✅
- `lupo-docs/versions/4.0.87/TASK_REGISTRY.md` — V487-070 through V487-083 added ✅
- `lupo-docs/versions/4.0.87/TODO.md` — WS2 completion and WS3-7 open items added ✅
- `lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md` — this file, updated ✅

## Validation Snapshot (20260325 ~20:00 UTC)

- Refreshed after Edge Model Consolidation WS2 + WOLFIE Phase A documentation directive.
- Cross-document consistency pass completed against:
  - `lupo-docs/versions/4.0.87/CHANGELOG.md` — WS2 entry present; header/footer updated
  - `lupo-docs/versions/4.0.87/TASK_REGISTRY.md` — V487-070 through V487-083 added
  - `lupo-docs/versions/4.0.87/TODO.md` — WS2 completion block added; open WS3-7 items listed
  - `lupo-docs/versions/4.0.87/PLAN.md` — WS2 completion noted
  - `lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md` — created and linked
  - `lupo-docs/database/lupopedia/tables/active/lupo_edges.md` — updated as canonical
  - `lupo-docs/database/lupopedia/tables/deprecated/lupo_actor_edges.md` — confirmed in deprecated folder
  - `lupo-docs/database/lupopedia/tables/deprecated/lupo_reference_cited_by.md` — confirmed updated
- PHP scan: zero references to `lupo_actor_edges` or `lupo_reference_cited_by` in any `.php` file.
- TOON scan: zero `.toon` files for removed tables.
- Install SQL: zero `CREATE TABLE lupo_actor_edges` or `CREATE TABLE lupo_reference_cited_by` in DDL.
