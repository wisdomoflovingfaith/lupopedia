---
lupopedia.headers:
  version_when_written: 4.0.84
  lupopedia.schema: planning
  file_path_from_root: channels/66/threads/1005/20260320_220000_lilith_headers_improvement_plan_channel_scoped_metadata_sync.md
  web_path: http://www.lupopedia.com/channels/66/threads/1005/20260320_220000_lilith_headers_improvement_plan_channel_scoped_metadata_sync.md
  questions_toon: null
  channel_id: 66
  thread_id: 1005
  actor_id: 2
  actor_name: lilith
  delegation_chain: lilith:review
  artifact_type: plan
  artifact_kind: headers_improvement
  title: LUPOPEDIA Headers Improvement Plan (Channel-Scoped Metadata Sync)
  purpose: Plan improvements to LUPOPEDIA headers generation/import across channel-linked
    entities and TOON table coverage
  tags:
  - lilith
  - headers
  - 4.0.84
  - channel66
  - thread1005
  - toon
  - metadata
  - import
  when_updated: '20260324182605'
lupopedia.footer:
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - Implement deterministic header-to-metadata key registry and import rules
  - Add table-aware projection for channel/thread/actor/collection/task/edge entities
  - Ship dry-run diff and conflict detection before write paths
  last_verified_by_actor_id: 102
---

# file: LUPOPEDIA Headers Improvement Plan (Lilith) - channel 66 thread 1005

## Scope

Improve LUPOPEDIA header reliability so edits to file headers can be safely imported back into canonical DB tables, with deterministic behavior and channel-aware validation.

Primary target domains:
- channels and threads
- actors and agent identity context
- collections and collection tabs
- tasks and task linkage
- graph edges and decision edges
- all TOON tables containing a `channel_id` column

Scope expansion rule:
- Include any table, metadata record, import path, or validation path that contains either `actor_id` or `channel_id`.
- Treat `actor_id` and `channel_id` as first-class identity constraints for generation, import, projection, and validation.
- If a new table gains either field in TOON or install SQL, it is automatically in scope for this workstream.

Governance trigger for automatic scope expansion:
- Add a schema drift watcher step that runs on every TOON or install SQL change.
- Drift watcher computes the current identity-field inventory (`actor_id` OR `channel_id`) and diffs it against the committed mapping registry.
- If new/changed tables are detected, CI opens a required planning update task and blocks merge until registry + task batch updates are committed.

Governance authority and timeout policy:
- Approval authority for identity-field registry updates: WOLFIE (final approval), with LILITH review required for adversarial consistency checks.
- Emergency fallback authority (if WOLFIE unavailable): delegated release authority documented in channel governance thread.
- Stalled drift detection timeout: if a drift task remains unresolved for 24 hours, CI marks status as escalation-required and posts a blocking escalation artifact.
- Hard timeout: unresolved drift over 72 hours requires explicit override artifact signed by approval authority; otherwise merge remains blocked.

## Observations from current scripts

From `scripts/generate_headers_from_db.py`:
- Uses a `MockDBConnection` fallback and does not currently enforce real table-aware hydration/projection per entity type.
- `build_block_tree()` maps plain metadata keys into `lupopedia.headers` by default; this can collapse semantic boundaries for session/edges/footer data.
- Block-specific builders exist (`build_headers_block`, `build_footer_block`) but are not consistently used to define final `front_matter_dict`.
- Canonical order exists but import/export parity with `import_content.py` and `lupo_metadata` remains partial.

From `scripts/import_content.py`:
- Strong deterministic `content_id` generation and explicit-column write behavior are present.
- Import path is content-centric; it does not yet provide full reversible mapping for all header blocks into normalized per-entity metadata records.

## 4.0.84 execution plan

### Phase A - Header schema and key registry hardening
1. Define a canonical key registry per block (`lupopedia.headers`, `lupopedia.session`, `lupopedia.edges`, `lupopedia.footer`, `lupopedia.see`, `lupopedia.next_actions`).
2. Add explicit allow/deny lists for importable keys per entity type (`file`, `channel`, `thread`, `task`, `actor`, `edge`, `collection`).
3. Reject unknown keys in strict mode; emit warnings in compatibility mode.

### Phase B - Table-aware projection engine
1. Add deterministic projection map from header keys to SQL targets.
2. For each entity type, resolve immutable identity first (`content_id`, `channel_id`, `thread_id`, `actor_id`, `task_id`, `edge_id`).
3. Execute projection writes in bounded scope and append audit rows to `lupo_metadata`/log tables.

### Phase C - Channel-centric validation
1. Enforce `channel_id` consistency across all blocks and all affected write targets.
2. Add cross-checks: header channel/thread pairs must exist in canonical channel/thread tables before write.
3. Add actor membership checks for channel-scoped updates where required by doctrine.

### Phase C2 - Actor/channel universal coverage
1. Build discovery step to enumerate all TOON tables containing `actor_id` or `channel_id`.
2. Generate a per-table mapping matrix: header key -> entity type -> table/column -> validation rule.
3. Require projection tests for every discovered table before declaring implementation complete.
4. Add CI gate: fail if any table containing `actor_id` or `channel_id` is missing from the mapping registry.

### Phase C3 - Registry authority and anti-drift controls
1. Establish a single version-controlled registry file for identity-field mappings (canonical source for review and audit).
2. Generate an introspected inventory snapshot from TOON during CI and compare against the committed registry.
3. CI rule: fail when committed registry and introspected inventory diverge.
4. Require explicit migration note when table inventory changes (added/removed/renamed identity-field table).

Branch divergence update mechanism (schema + registry edited together):
1. Run introspection on branch head and produce `registry_expected` snapshot.
2. Run registry validator comparing committed registry with `registry_expected`.
3. If diverged, require same-branch atomic update: schema change, registry update, and migration note in one mergeable change-set.
4. Rebase/revalidate rule: any rebase that changes TOON or install SQL must rerun introspection and refresh snapshot before merge.
5. Final merge gate requires validator success on latest target branch head.

### Phase D - Dry-run and conflict safety
1. Add `--dry-run --diff` mode for both generate and import to show exact key-level mutations.
2. Add optimistic concurrency checks using a deterministic precedence chain:
  - Primary: `last_modified_utc` when present and valid.
  - Secondary: DB `updated_ymdhis` or equivalent canonical row timestamp.
  - Fallback: deterministic content/header fingerprint hash.
3. Add deterministic conflict reports and no-partial-write guarantees.

Deterministic fingerprint specification:
- Hash algorithm: SHA-256.
- Canonical input payload (UTF-8, newline `\n` separators, fixed key order):
  - file_path_from_root
  - content_id (or `none`)
  - actor_id (or `none`)
  - channel_id (or `none`)
  - thread_id (or `none`)
  - normalized header block payload (canonical YAML serialization)
  - normalized body content
- Fingerprint output format: lowercase hex string.

### Phase E - Test matrix
1. Fixture tests for reversible round-trip: DB -> header -> edited header -> DB.
2. Mismatch tests: cross-channel spoofing, invalid actor/channel linkage, stale timestamp conflicts.
3. Coverage targets for channel/thread/actor/collection/task/edge entities.

## Task planning framework for unknown task volume

This thread may generate an unknown number of implementation tasks. Planning must remain deterministic.

Planning method:
1. Create inventory snapshot from TOON: all tables containing `actor_id` or `channel_id`.
2. For each table, derive one mapping task and one validation task minimum.
3. Group tasks into execution batches to keep parallel work bounded.
4. Recompute task inventory whenever TOON changes.

Deterministic complexity rubric (used to split high vs low complexity):
- Score each table from 0 to 10 using fixed criteria:
  - +2 if table is write-critical (core runtime path).
  - +2 if table has both `actor_id` and `channel_id`.
  - +2 if projection touches more than 8 mapped header keys.
  - +2 if table requires cross-table validation (membership/existence checks).
  - +2 if table participates in conflict-prone workflows (threads/messages/tasks/edges).
- Complexity class:
  - High complexity: score >= 7 (one task per table per bucket).
  - Medium complexity: score 4 to 6 (group by close domain, max 3 tables per task).
  - Low complexity: score <= 3 (group by domain, max 6 tables per task).

Objective method for write-critical classification:
- A table is write-critical when any of the following are true:
  - Referenced by request-path modules under `includes/modules/` for create/update operations.
  - Written by installer/upgrade runtime paths (`install.php`, import scripts, or channel posting APIs).
  - Participates in channel message/task/thread mutation paths.
- Classification evidence is generated by static code scan plus explicit path registry, and committed as a traceability artifact.

Task bucket model:
- Bucket A: Registry and schema tasks (key registry, strict parser, projection map).
- Bucket B: Table mapping tasks (per-table projection and import rules).
- Bucket C: Validation tasks (integrity checks, membership checks, conflict detection).
- Bucket D: Test tasks (round-trip fixtures, adversarial mismatch tests, regression suite).
- Bucket E: Rollout tasks (dry-run staging, production-safe flags, audit log verification).

Current scope snapshot from TOON (2026-03-20):
- Tables containing `actor_id`: 76
- Tables containing `channel_id`: 36
- Unique union (`actor_id` OR `channel_id`): 86 tables

Coverage clarification:
- The planning framework covers both sets: `channel_id` tables and `actor_id`-only tables.
- Channel list in this document is informational; enforcement and CI gating use the union inventory (`actor_id` OR `channel_id`).
- If an external inventory reports a different `channel_id` count (for example 41 vs 36), treat as reconciliation-required and block closure until both inventories are compared and resolved.

Canonical count authority and reconciliation procedure:
- Canonical source for counts: TOON introspection output generated on target branch head at validation time.
- Secondary reference: committed registry snapshot for historical comparison.
- Reconciliation steps when counts differ:
  1. Regenerate TOON introspection in clean workspace.
  2. Compare against committed registry and prior validated snapshot.
  3. Classify differences as add/remove/rename/parser mismatch.
  4. Update registry + migration note + task batches accordingly.
  5. Require approval artifact from governance authority before unblocking closure.

Initial task sizing baseline:
- Minimum baseline = `2 x union_count` (mapping + validation) = 172 table-scoped tasks.
- Recommended starting rollout = 5 foundational tasks (Bucket A) + 3 batch waves for Buckets B/C/D.
- Recompute counts and backlog automatically after any TOON/schema change.

Baseline counting clarification:
- Union membership is counted once per table regardless of whether a table contains one or both identity fields.
- Baseline `2 x union_count` means exactly two table-scoped tasks per table: one mapping task and one validation task.
- Tables containing both `actor_id` and `channel_id` still contribute one mapping + one validation baseline pair, with increased complexity score driving decomposition when needed.

Sizing and sequencing rules:
- Use one task per table per bucket when complexity is high.
- Merge low-complexity tables into grouped tasks by domain (channels, tasks, edges, collections).
- Keep active batch size capped (recommended 5 to 9 tasks concurrently).
- Every batch must close with evidence artifact and pass/fail summary.

Definition of done for planning:
- 100% of `actor_id`/`channel_id` tables are represented in the mapping registry.
- 100% have projection coverage and validation coverage.
- Round-trip tests pass for critical entities: channels, threads, actors, tasks, collections, edges.
- Drift detector reports zero unmapped identity fields.

Cross-thread coordination protocol:
- Use this thread (66/1005) as the planning authority thread.
- Every implementation thread must declare dependency links back to 66/1005 and forward links to impacted threads.
- Required checkpoint fields in each dependent thread artifact:
  - source_thread_id
  - depends_on_thread_ids
  - blocking_items
  - handoff_target_thread_id
- Any dependency spanning channels (for example Channel 66 and Channel 88) requires a mirrored checkpoint artifact in both channels before task state can move to done.

Cross-thread validation and blocker resolution detection:
- Add a dependency validator job that parses checkpoint fields from all dependent thread artifacts.
- Validator cross-checks declared `depends_on_thread_ids` against existing thread artifacts and current status markers.
- A blocking item is considered resolved only when the referenced thread publishes a closure or unblock artifact containing matching blocker ID.
- CI/task board sync marks dependency state transitions (`blocked` -> `ready`) only after validator confirmation.

## TOON tables with channel_id (must be included in planning scope)

- lupo_actor_channels
- lupo_actor_channel_roles
- lupo_actor_history
- lupo_anubis_operations
- lupo_artifacts
- lupo_audit_log
- lupo_channels
- lupo_channel_boot_detail
- lupo_channel_boot_detail_lifecycle
- lupo_channel_boot_lifecycle
- lupo_channel_content
- lupo_channel_departments
- lupo_channel_escalations
- lupo_channel_escalation_rules
- lupo_channel_files
- lupo_channel_state
- lupo_collections
- lupo_comments
- lupo_contents
- lupo_decisions
- lupo_decision_edges
- lupo_decision_influences
- lupo_dialog_channels
- lupo_dialog_messages
- lupo_dialog_threads
- lupo_documentation_frameworks
- lupo_edges
- lupo_folders
- lupo_metadata
- lupo_notifications
- lupo_projects
- lupo_rolls
- lupo_tasks
- lupo_tickets
- lupo_unified_log
- lupo_uploads

## Design decisions proposed for WOLFIE/HEPHAESTUS review

1. Keep `import_content.py` as content ingestion core, but delegate metadata projection to a new header projection module.
2. Refactor `generate_headers_from_db.py` to remove mock-only assumptions and require real DB mode for production paths.
3. Introduce one canonical `header_key_map` source shared by generation, import, and validation.
4. Treat channel/thread identifiers as first-class constraints, not optional metadata.

## Requested follow-up threads

- Channel 66 / Thread 1005: implementation approvals and phase lock.
- Channel 66 / new thread: round-trip test design and fixtures.
- Channel 88 / Thread 1004: semantic validation alignment for edge/task/channel fields where shared mapping risks exist.
- Channel 66 / new thread: actor_id + channel_id table inventory tracking and task batch registry.
- Channel 66 / new thread: registry drift watcher and CI reconciliation for identity-field inventories.
