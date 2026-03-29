---
lupopedia.headers:
  lupopedia.schema: "release_todo"
  file_path_from_root: "lupo-docs/versions/4.1.0/todo.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "todo"
  artifact_kind: "release_tasks"
  purpose: "Actionable tasks for 4.1.0 acceptance"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  approved_by_actor_id: 1
  approved_utc: 20260326223500
  next_action:
    - "Keep this todo blocked on approved 4.0.x baseline"
    - "Use it as the post-approval execution checklist"
---

# 4.1.0 TODO

## Entry Preconditions

- [ ] A Softaculous-approved 4.0.x baseline exists.
- [ ] Structure and migration boundaries are validated.
- [ ] Required 4.0.x carryover artifacts are intentionally promoted.

## Foundation from 4.0.x

- [ ] Confirm workflow model carried from 4.0.x: channel -> questions -> discussion -> prompts -> execution.
- [ ] Confirm architecture carryover for hybrid storage, federation model, and approval system.
- [ ] Confirm `lupopedia_js.php` PRD definition is stable enough for implementation.

## 4.1.0 Execution Scope Priorities

- [ ] Priority 1: Implement `lupopedia_js.php`.
- [ ] Priority 2: Complete channel refactor.
- [ ] Priority 3: Build validator system.
- [ ] Priority 4: Implement CLI workflow.
- [ ] Priority 5: Complete context system.

## Priority Validation Targets (Top)

### 1) Database Documentation (Critical)

- [x] Review canonical table definitions in `lupo-docs/database/lupopedia/tables/`.
- [ ] Resolve ambiguity across active/legacy table documentation views.
- [x] Verify alignment between table docs and install SQL.
- [ ] Verify doctrine compliance in release-critical table paths. (AUTO_INCREMENT blocker in install SQL)

### 2) Migration Documentation (Critical)

- [ ] Review `lupo-docs/doctrine/migrations/` for Crafty Syntax 3.7.5 mapping clarity.
- [ ] Produce explicit Crafty Syntax 3.7.5 to Lupopedia 4.1.0 mapping sign-off notes.
- [ ] Ensure migration narrative matches installer acceptance constraints.

### 3) Core System Concepts (Critical)

- [ ] Validate and standardize definitions for channels, threads, actors, agents, auth_users, faucets, and collections.
- [ ] Cross-link concept definitions to canonical doctrine files.
- [ ] Remove contradictory concept language from release-binding artifacts.
- [ ] Validate actor resolution implementation against auth_user, department, agent, and faucet boundaries.
- [ ] Validate channels and threads as primary context containers with collection integration rules.
- [ ] Validate DB canonical storage with generated `lupo-channels/` projections and round-trip reconciliation by `content_id`.
- [ ] Validate federation node semantics (node 0 core, node 1 install default) in ingestion and retrieval paths.
- [ ] Validate ingestion precedence: Crafty import baseline and runtime telemetry from `livehelp_js.php` pipeline.

## Installability and Acceptance (Primary)

- [ ] Validate clean subdirectory install using the dynamic project-root basename on shared-hosting-like environment.
- [ ] Eliminate remaining absolute-path assumptions in release-critical flows.
- [ ] Run internal Softaculous preflight checklist and capture evidence.
- [ ] Run internal Installatron preflight checklist and capture evidence.
- [ ] Run internal Fantastico preflight checklist and capture evidence.
- [ ] Publish required 4.0.x package for external installer reviewer access.
- [ ] Submit to Softaculous reviewer first and capture manual feedback.
- [ ] Submit to Installatron/Fantastico reviewers as secondary confirmations.
- [ ] Apply required fixes from external reviewer feedback and re-run internal preflight checks.

## Database and Doctrine

- [ ] Verify deterministic ID paths for release-critical tables.
- [ ] Verify no FK/trigger usage in release-critical schema.
- [ ] Validate BIGINT UTC timestamp compliance in critical data paths.

## Parity and Stability

- [ ] Validate core Crafty Syntax parity surface (chat, admin, user/session, channel/thread).
- [ ] Execute migration run from Crafty Syntax 3.7.5 baseline.
- [ ] Build minimal parity regression test suite for release-critical behaviors.

## Governance

- [ ] Keep 4.0.x artifacts untouched and unapproved for 4.1.0 unless intentionally promoted.
- [ ] Promote pending 4.1.0 artifacts to approved only after evidence exists.
- [ ] Keep `APPROVED_ARTIFACTS_INDEX.md` as strict release boundary.
- [ ] Keep PRD, plan, todo, and changelog synchronized.
- [ ] Track 4.0.88 channel refactor governance as pending carryover until edge-safe migration batches are validated.

## Approval-State and Index Consistency

- [ ] Audit current 4.1.0 artifacts for explicit `approval_target_version` fields.
- [ ] Normalize legacy `approved_for_release` / `approved_for_version` usage where artifacts are directly in scope.
- [ ] Verify approved index entries match approved footer state.
- [ ] Verify pending index entries match pending footer state.
- [ ] Verify rejected index entries match rejected footer state.
- [ ] Define validator or reporting path for footer-to-index reconciliation.
- [ ] Identify stale artifacts with verification metadata but no explicit approval classification.

## Implementation Readiness Tracking

Ready to build:

- [ ] `lupopedia_js.php`
- [ ] migration scripts
- [ ] validator scripts

Needs clarification:

- [ ] context system
- [ ] federation syncing

Needs validation:

- [ ] edge integrity
- [ ] approval indexing
- [ ] schema alignment
