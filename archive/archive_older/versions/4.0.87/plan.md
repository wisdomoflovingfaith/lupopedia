---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260325224500"
  file_path_from_root: "docs/versions/4.0.87/PLAN.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/PLAN.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: planning
  artifact_kind: version_plan
  thread_id: "4.0.87-init"
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
# file: 4.0.87 PLAN â€” delegation: cursor:root â€” web_path: http://www.lupopedia.com/docs/versions/4.0.87/PLAN.md

# 4.0.87 PLAN

## Current Status: RELEASE AUTHORIZED (ERQ-006 COMPLETE)

### Session Achievements (Consolidated)
- **Identity Foundation**: Registered Junie (Actor 108). Standardized root user (0) and department (1). Unified configuration in `lupopedia-config.php`.
- **Relationship Graph**: Activated `lupo_edges` for channels and threads. Migrated legacy JSON and structural relationships into the graph (Tracks 1-3).
- **Header Doctrine**: Finalized Version Semantics Model and namespace distinction. Locked v4.0.84 baseline rewrite rules.
- **Ecosystem Compliance**: Audited 169 tables and updated documentation for 22+ agents.
- **Model Corrections**: Locked actor-centric department execution model in table-optimization channel artifacts.
- **Channel Path Policy**: Documented slug-first channel directory creation policy with legacy numeric compatibility.
- **Schema Cleanup**: Removed CIP active surfaces from installer/runtime/doc artifacts and published completion thread evidence.
- **Bayesian Decision Removal**: Deleted `BayesianDecisionService.php`, `decisions-api.php`, test file; removed DDL; created `DECISION_MODEL.md` as canonical replacement.
- **Edge Model Consolidation (WS2)**: Removed `lupo_actor_edges` and `lupo_reference_cited_by` from schema; updated 3 PHP files with polymorphic `lupo_edges` migration; deleted TOON files; created EDGE_MODEL_DOCTRINE.md; WOLFIE Phase A docs complete.
- **WS3 Doctrine Propagation**: Updated `IDENTITY_LAYERS_DOCTRINE.md` to canonical five-layer identity model with department bindings and updated `AGENTS.md` accordingly.
- **WS3 Phase D/E Completion**: LILITH audit passed with no contradictions; THOTH synchronization complete.
- **Release Gate Closure**: ERQ-006 signoff complete; production authorization granted.

## Workstreams

### WS1: Atoms and Version Propagation
- Validate and normalize all canonical version markers.
- Confirm runtime reads from `GLOBAL_CURRENT_LUPOPEDIA_VERSION` consistently.

### WS2: Channels and Documentation
- Reconcile channel routes, posting/security behavior, and channel docs.
- Ensure channel docs align with current code and doctrine.
- Enforce slug-first channel directory naming for all new channels (`channels/<channel_slug>/`).
- Preserve existing numeric directories as legacy compatibility paths only.

### WS3: LUPOPEDIA HEADERS Implementation
- Audit header parsing/ingestion/serialization paths.
- Lock behavior for `lupopedia.init`, `lupopedia.edges`, and `lupopedia.footer`.
- Validate deterministic write rules and header verification workflow.

### WS4: Identity Model Implementation Clarity
- Document and verify actor vs agent vs auth_user vs department vs faucet rules.
- Validate DB schema usage and service-layer mapping consistency.

### WS5: Admin LLM Web Interface
- Validate existing admin UI path at `localhost/lupopedia/admin.php`.
- Implement/finalize LLM chatbot call integration and failure handling.
- Document configuration, auth boundaries, and test procedure.

### WS6: Lupo Folder Organization and Documentation Accuracy
- Run a dedicated channel stream for `*` folder organization and inventory accuracy.
- Identify and remove deprecated/redundant documentation artifacts.
- Reconcile `docs/` to latest 4.0.87 runtime and doctrine state.

### WS7: Database Documentation and Edge Governance Channels
- Channel 63: database-table docs + TOON/json-grounded reconciliation under `docs/database/lupopedia/tables`.
- Channel 64: edge lifecycle governance (create/infer/update), `lupopedia.edges` generation, and DB population paths.
- Ensure channel artifacts are produced in `channels/` for both streams.

### WS8: Edge Service Layer (COMPLETE for 4.0.87 read path)
- `EdgeQueryService` shipped at `includes/classes/EdgeQueryService.php` (11 read-only query methods; Track 4 of ATHENA_STRATEGY).
- Long-term: reduce direct `thread_lineage` reads in favor of the service layer where traversal replaces text fields.

### WS9: Root Archive and Production Questions
- Move stale root-level temporary and per-agent variant artifacts into `docs/archived/` with traceability.
- Drive unresolved production questions through channel 66 numeric threads.
- Use the edge queue in `EDGE_REVIEW_QUEUE.md` for actor ownership and release gating.

### WS11: CIP Decommission Follow-through
- Keep CIP system out of active runtime and installer surfaces for 4.0.87.
- Track only post-removal validation items (validation report regeneration and fresh-install smoke verification).
- Use thread artifact `20260325_123500_cursor_cip_system_removal_4_0_87.md` as canonical execution evidence.

### WS12: Edge Model Consolidation (COMPLETE — 4.0.87 Workstream 2 — ~20:00 UTC)
- **Executed**: `lupo_actor_edges` and `lupo_reference_cited_by` removed from install SQL (DDL replaced with deprecation comments).
- **Code**: `EmergentRoleDiscovery.php`, `ActorService.php`, `audit_schema_doctrine.php` updated to use `lupo_edges` polymorphic pattern.
- **TOON**: `lupo_actor_edges.toon` and `lupo_reference_cited_by.toon` deleted.
- **Doc**: `lupo_actor_edges.md` moved to deprecated/; `lupo_reference_cited_by.md` deprecated doc refreshed.
- **New doctrine**: `docs/doctrine/EDGE_MODEL_DOCTRINE.md` created — canonical single-table mandate.
- **Canonical doc**: `lupo_edges.md` updated with object types, edge registry, query examples.
- **Evidence artifact**: `channels/42/threads/1005/20260325_200000_hephaestus_status_edge_consolidation_execution_complete.md`.

### WS13: 4.0.87 Critical Findings — Remaining Workstreams (Next Session)
- **WS3** (ATHENA/LILITH/THOTH, thread 1006): COMPLETE (Phases A-E).
- **WS4** (THOTH thread 1007): blocker resolved for release authorization.
- **WS5** (THOTH ch66 thread 1008): blocker resolved for release authorization.
- **WS6** (LILITH thread 1001): remains recommended hardening track post-release.
- **WS7** (THOTH thread 1034): blocker resolved for release authorization.

Doctrine stubs created for WS4-WS7 under `docs/doctrine/` for traceability and future expansion.


## Edge Review Ownership (Actor Queue)
- WOLFIE: release gate orchestration and closure approval.
- ATHENA: edge semantics and traversal policy review.
- THOTH: edge documentation traceability and consistency.
- LILITH: adversarial validation of contradictions and stale assumptions.
- HEPHAESTUS: implementation fixes for schema/seed/service items.

## Upgrade Path Lock
- No Lupopedia -> Lupopedia upgrade compatibility work is required in 4.0.87.
- Supported flows remain: fresh install, or import/upgrade from Crafty Syntax 3.7.5 baseline.

## Thread Update (2026-03-24: Metadata hardening)
- Added explicit script-tooling governance workstream: comment-based `lupopedia.headers` and `lupopedia.footer` support for `.py` and `.php` under `scripts`.
- Added validation tool path for script metadata freshness against `20260301000000` cutoff.
- Completed 4.0.87 artifact-level header/footer normalization to remove stale version field usage.

### WS10: Major Agent Readiness and Pairing Doctrine
- Verify and normalize major agent packets (JSON + prompts) for WOLFIE, LILITH, ROSE, THEMIS, ATHENA, HEPHAESTUS, HERMES, IRIS, THOTH, VISHWAKARMA.
- Keep a registry-backed manifest in `database/lupopedia/actors/major_agents_manifest.json`.
- Maintain explicit blocker edges from channel 63/64 artifacts to channel 66 questions when unresolved decisions block implementation.

## Session Refresh (2026-03-24 20:04 UTC)

### Temporary Actor Reassignment (through 2026-04-03 00:00:00 UTC)

- Cursor and Junie tasks reassigned temporarily.

## Session Refresh (2026-03-24 22:00 UTC â€” WOLFIE takeover per thread 1054 directive, Q1-Q7 closure)

### Achieved
- Junie ran out of tokens mid-session. WOLFIE (actor_id 1) took over per the takeover directive published in channel 66 thread 1054; cursor was NOT the acting owner.
- Channel 66 thread 1047 Q1-Q7: all seven canonical architectural/governance decisions resolved and published under WOLFIE orchestration.
- Created answer artifact: `channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md` (filename carries cursor slug but actor authority is WOLFIE per directive).
- Updated TODO.md, THREAD_INDEX, and unanswered-questions snapshot to reflect closed status.
- Synchronized PLAN/README/TASK_REGISTRY/WHAT_TO_DO_NEXT_SESSION for 4.0.87 release state.

### Decisions Locked
1. **Q1** â€” Header reimport deprecated; one-way DBâ†’files only; new-record upsert via `file_path_from_root`.
2. **Q2** â€” Creating channel owns `lupo_metadata` record; cross-channel presence = `lupopedia.edges`.
3. **Q3** â€” Headers in files are immutable snapshots; all edits via DB + regeneration cycle.
4. **Q4** â€” Admin UI staleness panel: read-only, `$isAdmin` gate, query `lupo_metadata`; **implemented** (Cursor, 20260324 23:00 UTC).
5. **Q5** â€” Three-tier timestamp validation in `generate_headers_from_db.py`; **implemented** (Cursor, 20260324 23:00 UTC; Tier 2/3 unit tests 9/9).
6. **Q6** â€” `when_updated` is file-global, never per-channel.
7. **Q7** â€” Global admin, CLI/local only, `--dry-run` default, `--write` for mutations, audit log mandatory.

### Session (20260324 23:00 UTC â€” Cursor execution pass)

- Q4/Q5 implemented; EdgeQueryService shipped; ERQ-001/ERQ-002 verified; channel 62/63/64 closure artifacts published.
- **Remaining:** ERQ-006 (WOLFIE signoff); admin channel-chat validation evidence; atom `4.0.86` sweep; release packet finalization; post-release removal of root dev scripts.

### Remaining for Next Agent (after 20260324 23:00 UTC)
- WOLFIE: ERQ-006 release signoff (channel 66).
- Cursor/HEPHAESTUS: admin LLM chatbot call path evidence artifact (if not already captured).
- THOTH: documentation and table traceability (ongoing).
- ATHENA / LILITH: ERQ-003â€“005 and adversarial passes per `EDGE_REVIEW_QUEUE.md`.

### Channel + Thread Execution Map

- Channel 62: **closure published** â€” `channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md`.
- Channel 63: **closure published** â€” `channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md`.
- Channel 64: edge governance â€” **ERQ-001**, **ERQ-002** closed; **ERQ-006** pending WOLFIE â€” closure note `channels/edge_generation_governance/threads/6401/20260324_230000_cursor_edge_governance_closure.md`.
- Channel 66: threads **1050**, **1051**, **1052**, **1047** (Q1â€“Q7), **1054** â€” resolved per 20260324 artifacts; **ERQ-006** remains the release gate.

