---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "design_update"
  file_path_from_root: "channels/42/threads/2012/20260322_191500_athena_dialog_routing_design_corrected_update.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2012/dialog_routing_design_corrected_update"
  questions_toon: null
  channel_id: 42
  thread_id: 2012
  task_id: "task_ch42_th2012"
  actor_id: 4
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "design_update"
  artifact_kind: "dialog_routing_design_corrected"
  purpose: "Corrective update that resolves Thread 2012 destructive audit blockers and marks routing design ready for implementation"
  tags: ["design_update", "dialog_routing", "athena", "corrected", "thread_2012"]

lupopedia.edges:
  outbound_edges:
    - { to: "docs/versions/4.0.85/dialog_routing_design.md", type: "updates", weight: 1.0, reason: "Corrected canonical design" }
    - { to: "channels/42/threads/2012/20260322_185000_lilith_dialog_routing_design_validation_audit.md", type: "addresses", weight: 1.0, reason: "Resolves listed blockers" }

lupopedia.footer:
  last_updated: "20260322_191500"
  thread_status: "completed"
---

# Dialog Routing Design Corrected Update

## Summary

Thread 2012 design was corrected to address all required audit blockers without adding non-required feature scope.

## Included Corrections

1. Routing decision storage surface defined:
- canonical table contract: `lupo_routing_decisions`
- required fields included exactly as directed
- linkage to `lupo_human_requests` and decision lineage defined

2. MVP strategy scope restricted:
- implemented: `primary_then_fallback`, `priority_order`
- explicitly not implemented: `round_robin`, `least_recently_used`

3. Loop prevention model added:
- `max_attempts = 3`
- loop break key: `(actor_id + thread_id + trigger_type)`
- cooldown window defined

4. Idempotency model added:
- key: `(actor_id + thread_id + trigger_type + time_bucket)`

5. Role dictionary controlled:
- `primary_owner`
- `supporting_human`
- `escalation_contact`
- unknown role handling defined

6. Broadcast model corrected:
- `max_recipients` cap
- deterministic ordering
- response precedence rule

7. Determinism completed:
- tie-break/order/fallback rules now explicit per strategy

## Outcome

Design status transitioned from NOT_READY to READY_FOR_IMPLEMENTATION at design layer.

No PHP implementation, SQL mutation, or UI behavior changes were introduced by this correction artifact.
