---
lupopedia.headers:
  lupopedia.schema: "release_authority_index"
  file_path_from_root: "lupo-docs/versions/4.1.0/REJECTED_ARTIFACTS_INDEX.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "release_authority"
  artifact_kind: "rejected_artifacts_index"
  purpose: "Record artifacts rejected from 4.1.0 release scope"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md", type: "complements", weight: 0.9 }
    - { to: "lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md", type: "complements", weight: 0.9 }

lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  approved_by_actor_id: 1
  approved_utc: 20260326224200
  next_action:
    - "Append rejected artifacts with rationale as review proceeds"
---

# Rejected Artifacts (4.1.0)

Use this file to track artifacts reviewed and explicitly rejected from release scope.

Canonical inclusion rule:

- `approval_status: rejected`
- `approval_target_version: 4.1.0`

## Rejection Criteria

- Does not improve installer acceptance
- Conflicts with stable installability requirements
- Introduces experimental or non-essential complexity
- Contradicts approved 4.1.0 PRD constraints

## Current Rejections

| Artifact | Path | Rejected By | Date | Rationale |
|----------|------|-------------|------|-----------|
| 4.0.88 PRD Index | lupo-docs/versions/4.0.88/prd/README.md | 102 | 20260327 | Semantic monitoring feature-planning set is not part of current installer-acceptance release scope |
| Semantic Monitoring Widget PRD | lupo-docs/versions/4.0.88/prd/01_semantic_monitoring_widget.md | 102 | 20260327 | Feature invention work is outside current 4.1.0 release boundary |
| Semantic Monitoring Data Model PRD | lupo-docs/versions/4.0.88/prd/02_data_model.md | 102 | 20260327 | Data-model expansion is not release-critical to current Softaculous-first path |
| Semantic Monitoring Goals PRD | lupo-docs/versions/4.0.88/prd/03_goals_and_success_criteria.md | 102 | 20260327 | Goals target semantic monitoring expansion rather than installer-acceptance readiness |
| lupopedia_js Foundation PRD | lupo-docs/versions/4.0.88/prd/04_lupopedia_js_foundation.md | 102 | 20260327 | Foundation definition is preserved as 4.0.88 planning context but is not release-binding for current 4.1.0 scope |

