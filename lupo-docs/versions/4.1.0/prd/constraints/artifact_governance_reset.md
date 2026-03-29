---
lupopedia.headers:
  lupopedia.schema: "constraints"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/constraints/artifact_governance_reset.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "governance_reset"
  purpose: "Define 4.0.x artifact scan results and non-destructive signal/noise governance for 4.1.0"
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
  approved_by_actor_id: 1
  approved_utc: 20260326223700
  next_action:
    - "Use this classification before promoting any 4.0.x artifact"
---

# Artifact Governance Reset (4.1.0)

## Objective

Separate release signal from 4.0.x artifact noise without deleting historical material.

## Baseline Scan Summary

Scan scope: `lupo-docs/versions/4.0.*/**/*.md`

Observed baseline:

- High artifact volume across 4.0.80 to 4.0.88
- Mixed artifact intent: planning, snapshots, handoff notes, contradictions logs, exploratory design
- Multiple documents that are useful for history but not release-binding for installability

## Classification Model

### Legacy Non-Binding (default)

Any 4.0.x artifact is treated as legacy non-binding unless explicitly promoted and approved for 4.1.0.

Typical indicators:

- Session/handoff snapshots (`WHAT_TO_DO_NEXT_SESSION`, status snapshots)
- Contradiction and drift logs (`CONTRADICTIONS.md`)
- Experimental exploration docs and broad speculative architecture notes
- Prior-version release plans that do not map to current installer acceptance gates

### Candidate for Promotion

A 4.0.x artifact can be promoted only if all conditions are true:

1. It directly supports installability or auto-installer acceptance.
2. It does not conflict with 4.1.0 PRD requirements.
3. It is actionable and testable.
4. It can be linked to acceptance evidence.

### Approved Release Signal

Only artifacts with:

- `approved_for_release: "4.1.0"`
- `approval_status: "approved"`

count as release-binding inputs.

## Non-Destructive Cleanup Rule

- Do not delete 4.0.x artifacts.
- Do not rewrite history.
- Keep legacy material untouched and available for forensic reference.
- Isolate release decision-making to approved 4.1.0 artifacts.

## Practical Effect

When a conflict exists between a 4.0.x artifact and a 4.1.0 approved artifact:

- 4.1.0 approved artifact wins.
- 4.0.x artifact remains historical context only.

## Promotion Workflow

1. Identify candidate artifact from 4.0.x.
2. Verify relevance to installer acceptance or stable parity.
3. Reconcile with 4.1.0 PRD constraints.
4. Add or update a 4.1.0 artifact with governance footer fields.
5. Mark approved only after evidence-backed review.
