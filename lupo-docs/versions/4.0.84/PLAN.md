---
lupopedia.headers:
  version_when_written: "4.0.84"
  file_path_from_root: "lupo-docs/versions/4.0.84/PLAN.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.84/plan"
  last_modified_utc: "20260321"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "execution_plan"
  artifact_kind: "version_plan"
  purpose: "Version-scoped dependency plan for 4.0.84 restart from consolidation stop-state."

lupopedia.footer:
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Execute dependency order exactly as listed"
---

# Version 4.0.84 Plan

This plan is frozen as a release record. Active execution moved to 4.0.85.

## Release Scope

- Thread 1030: included in 4.0.84 release scope.
- Thread 1031/1032: included in 4.0.84 release scope.
- Thread 1034: included in 4.0.84 release scope.
- Thread 1035: included in 4.0.84 release scope.

## Explicit Exclusions

- Thread 1036: design only, not implemented.
- Thread 1037: pending doctrine decision.
- Thread 1038: deferred to 4.0.85.
- Thread 1004: blocker remains open.

## Freeze Guard

- Do not add net-new scope to 4.0.84.
- Treat this file as a release snapshot, not an active queue.
- Route all remaining work to 4.0.85 planning surfaces.

