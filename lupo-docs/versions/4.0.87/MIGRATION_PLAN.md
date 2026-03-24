---
lupopedia.headers:
  version_when_written: "4.0.87"
  file_path_from_root: "lupo-docs/versions/4.0.87/MIGRATION_PLAN.md"
  last_modified_utc: "20260324_143710"
  channel_id: 42
  thread_id: "4.0.87-init"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "planning"
  artifact_kind: "migration_plan"
  purpose: "Staged migration and rollout plan for 4.0.87."
---

# 4.0.87 MIGRATION PLAN

## Phase 1: Version and Atom Alignment
- Complete canonical version marker updates.
- Verify runtime atom reads and CLI outputs.

## Phase 2: Headers + Identity Foundations
- Lock header class behavior and verification tooling.
- Reconcile identity docs against tables/services.

## Phase 3: Channels + Admin LLM Path
- Validate channel behavior and docs.
- Implement and test admin LLM call flow in `admin.php`.

## Phase 4: Validation + Release Readiness
- Execute version checklist.
- Update changelog/task registry/next-session handoff.
