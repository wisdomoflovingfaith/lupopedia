---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-docs/versions/4.0.85/PLAN.md"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "documentation"
  artifact_kind: "derived_view"
  purpose: "Derived plan view for 4.0.85 under controlled synchronization v9."
---

# 4.0.85 PLAN

- Derived view only.
- Authoritative task state lives in TASK_REGISTRY.

## Plan Lock
1. Keep one system of record.
2. Keep contradictions diagnostic-only.
3. Keep thread indexes navigation-only.

## Active Gate
- blocking_contradiction: contradiction_c66_1004_semantic_mapping_invalid

## Final 4.0.85 Planning State
1. Install readiness is complete.
2. System compliance is complete.
3. Remaining work is either deferred to 4.0.86 or isolated outside the core 4.0.85 install-ready declaration.
