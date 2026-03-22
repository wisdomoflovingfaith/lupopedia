---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/versions/4.0.86/PLAN.md"
  last_modified_utc: "20260322_191342"
  channel_id: 42
  thread_id: 2018
  actor_id: 3
  actor_name: "hephaestus"
  artifact_type: "documentation"
  artifact_kind: "version_plan"
  purpose: "Define goals and scope for version 4.0.86."
---

# 4.0.86 PLAN

## Goals
1. Preserve 4.0.85 install-ready and compliant baseline without regressions.
2. Complete deferred non-blocking work from 4.0.85 under explicit authority boundaries.
3. Improve runtime safety, observability, and test depth for actor-driven workflows.
4. Keep schema, TOON, and doctrine surfaces synchronized.

## Scope
- In scope:
  - Deferred tasks explicitly marked for 4.0.86.
  - Runtime hardening that does not alter canonical authority model.
  - Documentation and registry synchronization needed for operational clarity.
  - Migration validation loop from Crafty 3.7.5 to Lupopedia 4.0.86 candidate state.
- Out of scope:
  - New authority systems.
  - Runtime filesystem write features for routing/decision loops.
  - Structural rewrites not required by compliance or migration correctness.

## Plan Gates
1. Gate A: Baseline lock and inventory complete.
2. Gate B: Deferred task intake and prioritization complete.
3. Gate C: Runtime and migration corrections validated.
4. Gate D: Final compliance and install-readiness declaration.
