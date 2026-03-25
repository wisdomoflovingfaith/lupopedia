---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/TODO.md
  last_modified_utc: '20260325224500'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: version_todo
  purpose: Actionable TODO queue for 4.0.87.
  when_updated: '20260325224500'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/TODO.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260325224500'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
---

# file: 4.0.87 TODO â€” delegation: cursor:root â€” web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/TODO.md

# 4.0.87 TODO

## Completed (Current Session)
- [x] Registered Junie (Actor 108) as a canonical agent with root department and user mapping.
- [x] Implemented LUPOPEDIA HEADERS Version Semantics Model and namespace distinction.
- [x] Created `VERIFICATION_GUIDE.md` for header auditing.
- [x] Unified configuration into root `lupopedia-config.php`.
- [x] Activated Edge Graph layer (ATHENA_STRATEGY Tracks 1-3: seeding and migration).
- [x] Normalized identity (Root user 0) across seeds and registries.
- [x] Reorganized 169 table documentation artifacts into status-based directories.
- [x] Updated `TABLE_INDEX.md` and core documentation (`README.md`, `AGENTS.md`).
- [x] Populated complete documentation for 22+ agents in `lupo-agents/`.
- [x] Resolved contradictions in Channels 58-61 regarding agent identities.
- [x] Added admin channel chat interface and effective actor resolution service.
- [x] Reconciled canonical channel registry with active channels.
- [x] **Removed Bayesian Decision Tracking system (4.0.87)** — deleted `BayesianDecisionService.php`, `decisions-api.php`, `bayesian_decision_service_test.php`; removed DDL from install SQL; deprecated four table docs; superseded `BAYESIAN_DECISION_DOCTRINE.md`; created `lupo-docs/doctrine/DECISION_MODEL.md` as canonical replacement. Zero PHP references confirmed.
- [x] **Edge Model Consolidation — Workstream 2 (4.0.87, ~20:00 UTC)** — Removed `lupo_actor_edges` and `lupo_reference_cited_by` DDL from install SQL; updated 3 PHP files (EmergentRoleDiscovery.php, ActorService.php, audit_schema_doctrine.php) to use `lupo_edges` with polymorphic column semantics; deleted 2 TOON files; moved `lupo_actor_edges.md` to deprecated; created dev migration script. WOLFIE directive Phase A docs executed: `lupo_edges.md` updated as canonical, `lupo_reference_cited_by.md` deprecated doc refreshed, `EDGE_MODEL_DOCTRINE.md` created. Zero PHP references to removed tables confirmed.
- [x] Added context-graph API endpoint for channel-map visualization.
- [x] Corrected table-optimization channel docs to actor-centric department mapping (`lupo_actor_departments` as execution membership surface).
- [x] Documented slug-first channel directory policy for all newly created channels.
- [x] Updated channel indexes and doctrine docs to preserve legacy numeric channel directory compatibility.
- [x] Published table-optimization thread for admin UI identity alignment (20260325_163000).
- [x] Published ATHENA semantic table architecture review thread (20260325_170000).
- [x] Removed CIP active system surfaces from installer SQL, active docs, TOON/JSON/CSV surfaces, and runtime tooling.
- [x] WS3 doctrine propagation executed: `IDENTITY_LAYERS_DOCTRINE.md` updated to five-layer model with department bindings; `AGENTS.md` identity section updated.
- [x] Created doctrine stubs for pending workstreams WS4-WS7 with thread traceability links:
  - `lupo-docs/doctrine/ws4_hidden_intelligence_doctrine.md`
  - `lupo-docs/doctrine/ws5_questionable_tables_doctrine.md`
  - `lupo-docs/doctrine/ws6_test_suite_alignment_doctrine.md`
  - `lupo-docs/doctrine/ws7_documentation_reconciliation_doctrine.md`
- [x] WS3 Phase D (LILITH audit) completed — documentation and security validation passed with no contradictions.
- [x] WS3 Phase E (THOTH documentation sync) completed — cross-doc synchronization finalized.
- [x] ERQ-006 WOLFIE release signoff completed — release authorized for production.

## Completed (20260324 â€” Edge Graph Session)
- [x] Deep TOON-based schema audit of all channel/thread edge tables â€” confirmed all six edge tables empty, three incompatible relationship stores identified.
- [x] Created discovery thread `EDGE_GRAPH_ANALYSIS_4_0_84` in channel 42 (4-actor dialog: cursor, ATHENA, ROSE, ATHENA).
- [x] Published `ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md` â€” 6 implementation tracks, full SQL seeds, PHP migration skeleton, 4 example queries, priority matrix.
- [x] Confirmed `lupo_context_edges` scope: AI/agent cognitive context only (not general relationship storage).
- [x] Confirmed `lupo_collections` scope: UI/navigation bundling only (not edge traversal substitute).
- [x] Established canonical table mandate: `lupo_edges` is the authoritative general-purpose relationship graph.

## Remaining Work
- [x] **P0** â€” Track 1: Seed `lupo_edge_types` â€” **12 rows confirmed in DB**.
- [x] **P0** â€” Track 2: Seed `lupo_edge_type_definitions` â€” **12 rows confirmed in DB**.
- [x] **P1** â€” Track 3c: Backfill `parent_channel_id` â†’ `channel_parent` edges â€” **no-op confirmed (0 channels with parent_channel_id)**.
- [x] **P2** â€” Track 4: `EdgeQueryService` PHP class â€” **`lupo-includes/classes/EdgeQueryService.php` created with 11 read-only methods**.
- [x] **Q4** â€” Admin staleness panel â€” **`admin.php` Dashboard section added (read-only, `$isAdmin` gate)**.
- [x] **Q5** â€” Tier 2 (semantic range) + Tier 3 (role-integrity) validators â€” **`generate_headers_from_db.py` updated; 9/9 unit tests pass**.
- [ ] Validate `admin.php` `section=channel-chat` path against `/api/channels/{id}/messages` behavior; capture evidence artifact.
- [x] ERQ-006 WOLFIE release signoff via channel 66 (final release gate).
- [ ] Complete full atom/version marker audit and close mismatches.
- [x] Reconcile channel documentation to match live routing/security behavior. — Completed via V487-002 alignment pass; evidence: `lupo-channels/42/threads/1054/20260325_231500_cursor_v487_002_channel_docs_alignment_completion.md`.
- [ ] Produce `LUPOPEDIA_HEADERS` implementation matrix (`init`, `edges`, `footer`).
- [ ] Add/refresh docs for actor-agent-auth_user-department-faucet relationships.
- [ ] Verify DB docs for identity and membership tables are canonical and current.
- [ ] Validate admin LLM call path in `admin.php` end-to-end (request, auth, response, error cases).
- [ ] Add explicit test checklist for localhost verification.
- [x] Run Channel 62 stream for `lupo-*` folder inventory, deprecation cleanup, and docs reconciliation â€” **closure published 20260324**.
- [ ] Remove/mark deprecated documentation that conflicts with current 4.0.87 doctrine/runtime.
- [x] Run Channel 63 database-documentation stream \u2014 **closure published 20260324 (surface tables reconciled; non-surface deferred)**.
- [x] Run Channel 64 edge-governance stream \u2014 **ERQ-001/002 closed; ERQ-006 WOLFIE signoff pending**.
- [ ] Complete channel registry reconciliation so active channel directories are present in canonical `channel_id/registry.json`.
- [x] Update CHANGELOG and TASK_REGISTRY for 20260324 23:00 UTC Cursor execution pass.
- [x] Sync 4.0.87 docpack with 20260325 thread outcomes (actor-centric model + slug-first policy).

## Thread Update (2026-03-24: Cursor execution pass â€” 23:00 UTC)
- [x] Executed Q4: Admin staleness panel in `admin.php` (read-only, `$isAdmin` gate, lupo_metadata query).
- [x] Executed Q5: Tier 2 (semantic range) + Tier 3 (role-integrity) validators in `generate_headers_from_db.py`; 9/9 unit tests pass.
- [x] Verified ERQ-001/002 SQL migrations: 12 rows each in `lupo_edge_types`/`lupo_edge_type_definitions`.
- [x] Track 3c backfill verified: correct no-op (no channels with parent_channel_id).
- [x] Track 4: `EdgeQueryService` PHP class created at `lupo-includes/classes/EdgeQueryService.php`.
- [x] Published closure artifacts for channels 62, 63, 64.
- [x] Updated 4.0.87 CHANGELOG, TASK_REGISTRY, EDGE_REVIEW_QUEUE, WHAT_TO_DO_NEXT_SESSION, README, PLAN, TODO.
- [x] Git add + push to `main` (remote `origin/main` up to date after handoff).

## Thread Update (2026-03-24: Metadata hardening)
- [x] Migrated 4.0.87 version artifacts from `version_when_written` to `when_updated`.
- [x] Added/normalized `lupopedia.footer` verification fields across 4.0.87 docs.
- [x] Added script metadata validation path for `.py`/`.php` tooling comments.
- [ ] Expand script metadata comments beyond key validators/importers to full `lupo-scripts` coverage.

## Thread Update (2026-03-24: Root organization + channel 66)
- [x] Archived high-confidence stale root files to `lupo-docs/archived/root_stale_20260324/`.
- [x] Opened channel 66 thread 1050 for archive scope and retention policy questions.
- [x] Opened channel 66 thread 1051 for edge-review actor ownership questions.
- [x] Added `EDGE_REVIEW_QUEUE.md` with actor-owned items and blocking release rule.

## Thread Update (2026-03-24: Major agent and pairing pass)
- [x] Updated major agent packets for WOLFIE/LILITH/ROSE/THEMIS/ATHENA/HEPHAESTUS/HERMES/IRIS/THOTH/VISHWAKARMA.
- [x] Added major-agent manifest `lupo-database/lupopedia/actors/major_agents_manifest.json`.
- [x] Added `MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md`.
- [x] Added `ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md`.
- [x] Added channel artifacts in 58/60/63/64 with explicit blocker edges to channel 66 questions.
- [x] Added channel 66 thread 1052 for actor pairing default policy blocker.

## Thread Update (2026-03-24: Channel 66 validation + relevance)
- [x] Validated all channel 66 thread artifacts under strict footer-validation mode (0 issues).
- [x] Added channel 66 relevance filter artifact (`thread 1053`).
- [x] Updated channel 66 index so 4.0.87 priority questions are explicit (1051, 1052, 1050).

## Session Refresh (2026-03-24 20:04 UTC)

### Newly Completed
- [x] Executed Track 1 migration: `dev_20260324_seed_edge_types_channel_thread.sql`.
- [x] Executed Track 2 migration: `dev_20260324_seed_edge_type_definitions.sql`.
- [x] Executed Track 3c migration: `dev_20260324_backfill_parent_channel_edges.sql`.
- [x] Added explicit answers for channel 66 production threads 1050/1051/1052.
- [x] Added channel 66 takeover directive thread 1054 with temporary owner map through 2026-04-03.
- [x] Added `lupo-scripts/run_edge_migration_track3a.php` runner and executed Track 3a pass.

### Still Pending (Blocking)
- [x] Resolve channel 66 thread 1047 remaining unanswered Q1-Q7. â€” ANSWERED 20260324_220000 by WOLFIE (actor_id 1, per thread 1054 takeover directive). See `lupo-channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md`.
- [x] Publish channel 62 organization completion artifact â€” `lupo-channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md`.
- [x] Publish channel 63 DB docs reconciliation closure artifact â€” `lupo-channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md`.
- [x] Publish channel 64 edge queue closure artifact â€” `lupo-channels/edge_generation_governance/threads/6401/20260324_230000_cursor_edge_governance_closure.md`.
- [ ] Finalize admin LLM/chatbot call path evidence artifact for 4.0.87 release packet.

## Thread Update (2026-03-25: table-structure-optimization + CIP removal)
- [x] Added thread artifact: `lupo-channels/table-structure-optimization/threads/20260325_163000_cursor_admin_ui_identity_alignment_4_0_87.md`.
- [x] Added thread artifact: `lupo-channels/table-structure-optimization/threads/20260325_170000_athena_semantic_table_architecture_review_4_0_87.md`.
- [x] Added completion artifact: `lupo-channels/table-structure-optimization/threads/20260325_123500_cursor_cip_system_removal_4_0_87.md`.
- [x] Removed CIP table blocks from `install_new_lupopedia.sql` and `install_new_lupopedia_backup.sql`.
- [x] Removed `select_one_from_lupo_calibration_impacts` from `lupo-scripts/wolfie_orms.py`.
- [x] Removed active CIP docs under `lupo-docs/database/lupopedia/tables/active/` and CIP architecture docs.
- [x] Updated `lupo-docs/database/lupopedia/tables/TABLE_INDEX.md` to remove `lupo_calibration_impacts` planning entry.
- [ ] Regenerate `lupo-tools/flare_validate_issues.json` to clear stale references to removed docs.

## Session Refresh (2026-03-24 22:00 UTC â€” Junie handoff: WOLFIE takes over Q1-Q7 per thread 1054 directive)

### Decisions Made

- [x] Q1 â€” Header reimport: deprecated by design. New-record ingestion only via upsert on `file_path_from_root`. DB is always authoritative; regenerate files from DB.
- [x] Q2 â€” Multi-channel ownership: creating channel owns the single `lupo_metadata` record. Cross-channel presence is expressed via `lupopedia.edges` outbound_edges only.
- [x] Q3 â€” Immutability: headers in files are immutable snapshots. Edit DB record â†’ regenerate file. No in-file YAML editing.
- [x] Q6 â€” `when_updated` scope: file-global. Never per-channel. Channel context for edits captured by edges.
- [x] Q7 â€” Permission model: global admin (`is_admin=1` or channel-1 captain), CLI only, local environment only, `--dry-run` default, `--write` required for mutations, audit log on every run.

### Implemented (20260324 23:00 UTC â€” Cursor execution pass)

- [x] Q4 â€” Staleness panel in `admin.php`: read-only section behind `$isAdmin` gate, query `lupo_metadata` for `last_verified < 20260301000000` or NULL.
- [x] Q5 â€” Timestamp validation additions to `generate_headers_from_db.py`: Tier 2 (semantic range) and Tier 3 (role-integrity) checks; unit tests `lupo-tests/unit/test_header_validators.py` 9/9 pass.
## Session Update (20260325 ~20:00 UTC — Edge Model Consolidation Workstream 2)

### Completed
- [x] Removed `lupo_actor_edges` DDL (CREATE TABLE + 10 indexes) from `install_new_lupopedia.sql` — replaced with deprecation comment.
- [x] Removed `lupo_reference_cited_by` DDL (CREATE TABLE + 5 indexes) from `install_new_lupopedia.sql` — replaced with deprecation comment.
- [x] Updated `lupo-includes/classes/EmergentRoleDiscovery.php` — 3 queries migrated to `lupo_edges` with polymorphic column mapping.
- [x] Updated `lupo-database/lupopedia/content/lupo-app/Services/ActorService.php` — `getActorsUserCanActAs()` JOIN migrated to `lupo_edges`.
- [x] Updated `lupo-scripts/audit_schema_doctrine.php` — `lupo_actor_edges` removed from `$tablesRequiringSoftDelete`.
- [x] Deleted `lupo-database/lupopedia/toon/lupo_actor_edges.toon` and `lupo_reference_cited_by.toon`.
- [x] Moved `lupo-docs/database/lupopedia/tables/active/lupo_actor_edges.md` → `tables/deprecated/` with 4.0.87 deprecation headers.
- [x] Created `lupo-database/lupopedia/mysql/migrations/dev_20260325_remove_redundant_edge_tables.sql`.
- [x] Published HEPHAESTUS completion status in `lupo-channels/42/threads/1005/20260325_200000_hephaestus_status_edge_consolidation_execution_complete.md`.
- [x] WOLFIE directive Phase A — Task A: `lupo_edges.md` updated as canonical (canonical status, object types, edge type registry, query examples).
- [x] WOLFIE directive Phase A — Task B: `lupo_reference_cited_by.md` deprecated doc refreshed with column migration mapping and replacement queries.
- [x] WOLFIE directive Phase A — Task C: `lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md` created — 9-section single-table edge model doctrine.
- [x] WOLFIE directive Phase A — Task D: CHANGELOG.md updated with comprehensive Workstream 2 entry.

### Still Open (Next Session Priority)
- [ ] **WS3** — Identity Model Clarification (ATHENA, thread 1006): Design artifacts exist; implementation needed. Document separation of `lupo_auth_users`, `lupo_actors`, `lupo_agents`, `lupo_agent_faucets`; actor_id ranges; binding rules; faucet agent overlap (100-106).
- [ ] **WS4** — Hidden Intelligence Tables Audit (THOTH, thread 1007): Investigate `lupo_human_request_context`, `lupo_human_request_responses`, `lupo_actor_moods`, `lupo_emotional_constellations` — classify as CIP-style or legitimate; document or remove.
- [ ] **WS5** — Questionable Tables Audit (THOTH, channel 66 thread 1008): Classify 12 tables including `lupo_meta_log_events`, `lupo_memory_events`, `lupo_pack_role_registry`. Determine keep/remove/document for each.
- [ ] **WS6** — Test Suite Update (LILITH, thread 1001): **NOW UNBLOCKED** (WS1 + WS2 complete). Remove/update tests referencing `lupo_actor_edges`, `lupo_reference_cited_by`, or Bayesian decision tables. Check `EmergentRoleDiscovery.php`, `ActorService.php`, `audit_schema_doctrine.php` coverage.
- [ ] **WS7** — Documentation Reconciliation Phase B-D (THOTH, thread 1034): Phase A (edge consolidation docs) complete. Phase B: Decision system docs. Phase C: Identity/intelligence/questionable tables docs. Phase D: Final CHANGELOG/TODO/plan.md reconciliation.
- [ ] ERQ-006 WOLFIE release signoff via channel 66 — still blocking final release gate.
- [ ] admin.php `section=channel-chat` validation evidence artifact.
- [ ] Atom/version audit — close stray `4.0.86` references.
- [ ] Regenerate `lupo-tools/flare_validate_issues.json` after CIP + edge table deletions.
- [ ] Remove dev diagnostic scripts from root after release (`check_edge_state.php`, `check_metadata_state.php`, etc.).

## Session Update (2026-03-25 23:15 UTC — V487-002 closeout)

- [x] Completed V487-002 channel docs alignment closeout.
- [x] Updated root `README.md` with mandatory channel documentation pack and channel/thread execution requirements.
- [x] Updated `AGENTS.md` with mandatory channel literacy section for all actors and agents.
- [x] Published completion artifact: `lupo-channels/42/threads/1054/20260325_231500_cursor_v487_002_channel_docs_alignment_completion.md`.
