---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/versions/4.0.86/TODO.md"
  last_modified_utc: "20260322_191342"
  channel_id: 42
  thread_id: 2018
  actor_id: 3
  actor_name: "hephaestus"
  artifact_type: "documentation"
  artifact_kind: "version_todo"
  purpose: "Initialize 4.0.86 task queue with priority and completion criteria."
---

# 4.0.86 TODO

## Priority Queue
1. Build deferred-work intake list from 4.0.85 and classify by risk.
2. Validate runtime actor loop and escalation boundaries against doctrine.
3. Execute Crafty 3.7.5 to 4.0.86 migration dry-run and capture deltas.
4. Reconcile schema, TOON artifacts, and database docs after each migration pass.
5. Expand regression and adversarial tests for authz and authority boundaries.
6. Prepare 4.0.86 changelog draft with evidence-linked entries.

## Completion Criteria
- All P0 and P1 deferred items have explicit resolved state.
- No open security or authority-model FAIL in final audit pass.
- Install wizard path and upgrade path validate end to end.
