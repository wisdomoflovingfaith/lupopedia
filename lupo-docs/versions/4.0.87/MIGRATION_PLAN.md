---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/MIGRATION_PLAN.md
  last_modified_utc: '20260324200640'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: migration_plan
  purpose: Staged migration and rollout plan for 4.0.87.
  when_updated: '20260324200640'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/MIGRATION_PLAN.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
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

## Upgrade Compatibility Statement
- No Lupopedia -> Lupopedia upgrade migration path is in scope for 4.0.87.
- Migration/installation focus remains fresh install and Crafty Syntax import path.

