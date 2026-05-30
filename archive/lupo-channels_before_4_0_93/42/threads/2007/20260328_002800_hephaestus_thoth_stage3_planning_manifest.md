---
lupopedia.headers:
  schema: documentation
  file_path_from_root: lupo-channels/42/threads/2007/20260328_002800_hephaestus_thoth_stage3_planning_manifest.md
  last_modified_utc: '20260328002800'
  channel_id: 42
  thread_id: 2007
  actor_id: 23
  actor_name: hephaestus
  delegation_chain: wolfie:hephaestus
  artifact_type: plan
  artifact_kind: stage_plan
  purpose: Stage 3 planning manifest for residual drift resolution and normalization
  tags:
  - phase_3
  - stage_3
  - planning
  - drift_resolution
  - thread_2007
lupopedia.edges:
  outbound_edges:
  - to: lupo-channels/42/threads/2007/20260328_000000_wolfie_phase_3_directive_full_semantic_completeness.md
    type: executes
    weight: 1.0
    reason: Stage 3 planning under approved directive
  - to: lupo-channels/42/threads/2007/20260328_002500_thoth_stage2_metadata_restoration_report.md
    type: follows
    weight: 0.9
    reason: Stage 3 planning starts after Stage 2 completion
  - to: lupo-channels/42/threads/2007/20260328_001500_hephaestus_thoth_stage1_completion_report.md
    type: references
    weight: 0.9
    reason: Stage 1 completion baseline for onward planning
lupopedia.footer:
  last_verified: '20260328002800'
  verified_by:
    identity_type: actor
    actor_id: 23
    actor_name: hephaestus
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
  next_action:
  - Generate full path-level drift manifest for active table docs
  - Classify entries by resolution type
  - Execute normalization pass with validation gates
---

# Phase 3 — Stage 3 Planning Manifest

Thread: 42 / 2007
Date: 20260328 002800 UTC
Actors: HEPHAESTUS (implementation), THOTH (semantic authority)
Status: READY FOR EXECUTION

## Objective

Resolve residual drift in active table documentation with full manifest-driven reconciliation and normalization.

## Execution Tracks

### Track A — Full Path-Level Drift Manifest

Deliverable:
- `lupo-channels/42/threads/2007/20260328_004100_hephaestus_stage3_full_drift_manifest.md`

Method:
1. Enumerate changed/deleted paths in `lupo-docs/database/lupopedia/tables/active/`.
2. Expand to absolute and workspace-relative canonical paths.
3. Capture file state: modified/deleted/untracked.

### Track B — Classification Matrix

Deliverable:
- `lupo-channels/42/threads/2007/20260328_004000_thoth_stage3_drift_classification.md`

Required classes:
- valid (keep)
- corrupted (repair)
- outdated (refresh)
- orphaned (archive/remove)

Per-file fields:
- path
- class
- reason
- action owner
- action priority

### Track C — Normalization Pass Plan

Deliverable:
- `lupo-docs/reports/STAGE3_NORMALIZATION_PLAN_20260328.md`

Normalization standards:
- header doctrine compliance
- schema section consistency with TOON JSON
- edge block structural validity with confidence/source fields
- UTF-8 without BOM

### Track D — Validation Gates

Required gates before Stage 3 closure:
1. Header validation pass on normalized set.
2. Schema alignment pass on affected files.
3. Drift count reconciliation (pre/post counts and explained variance).
4. No unauthorized file modifications outside scoped set.

## Sequencing

1. Generate full drift manifest.
2. Classify all entries.
3. Execute normalization by class priority.
4. Run validation gates.
5. Publish Stage 3 completion report with acceptance recommendation input to LILITH.

## Risks and Controls

- Risk: accidental non-scoped edits
  - Control: manifest-locked path writes only
- Risk: metadata regression
  - Control: validator gate after each batch
- Risk: schema divergence
  - Control: TOON token checks in normalization pipeline

## Stage 3 Ready Signal

Stage 3 planning is complete and executable.

Next commandable action:
- produce full path-level drift manifest and start classification pass.
