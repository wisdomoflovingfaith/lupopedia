---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/versions/4.0.86/MIGRATION_PLAN.md"
  last_modified_utc: "20260322_191342"
  channel_id: 42
  thread_id: 2018
  actor_id: 3
  actor_name: "hephaestus"
  artifact_type: "documentation"
  artifact_kind: "migration_plan"
  purpose: "Define migration workflow and validation checkpoints for version 4.0.86."
---

# 4.0.86 Migration Plan

## Phase 1: Baseline Reset
1. Drop tables and restore Crafty 3.7.5 baseline source.
2. Load canonical install schema and seed paths in documented order.
3. Confirm actor/auth/channel baseline integrity.

## Phase 2: Upgrade Execution
1. Run install wizard and import mapping flow.
2. Execute required development migrations for 4.0.86.
3. Regenerate TOON files and verify schema alignment.

## Phase 3: Validation
1. Run unit, regression, integration, and adversarial suites.
2. Execute targeted authz, authority, and routing probes.
3. Confirm no runtime filesystem write regressions.

## Phase 4: Release Readiness
1. Resolve residual migration mismatches.
2. Record final system snapshot and compliance evidence.
3. Prepare release tag candidate checklist for 4.0.86.
