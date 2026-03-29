---
lupopedia.headers:
  lupopedia.schema: "release_authority_index"
  file_path_from_root: "lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "release_authority"
  artifact_kind: "approved_artifacts_index"
  purpose: "Single source of truth for approved 4.1.0 release artifacts"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "governs", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md", type: "related_to", weight: 0.9 }
    - { to: "lupo-docs/versions/4.1.0/REJECTED_ARTIFACTS_INDEX.md", type: "related_to", weight: 0.8 }

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
  approved_utc: 20260326224000
  next_action:
    - "Keep this file as the release authority boundary"
    - "Reject any artifact not listed here as non-binding"
---

# Approved Artifacts (4.1.0)

Rule: If an artifact is not listed here, it is not part of 4.1.0 release definition.

Canonical inclusion rule:

- `approval_status: approved`
- `approval_target_version: 4.1.0`

Legacy compatibility note:

- Some older 4.1.0 entries still rely on `approved_for_release: 4.1.0` in addition to `approval_status: approved`.
- Those rows remain listed while normalization is completed.

Governance anchor exception: `prd/README.md`, `APPROVED_ARTIFACTS_INDEX.md`, and `REJECTED_ARTIFACTS_INDEX.md` are authority control files for this system and are always binding.

| Artifact | Path | Approved By | Date | Notes |
|----------|------|-------------|------|-------|
| Product Overview | lupo-docs/versions/4.1.0/prd/product_overview.md | 1 | 20260326 | Defines what 4.1.0 is and is not |
| Installer Requirements | lupo-docs/versions/4.1.0/prd/requirements/installer_requirements.md | 1 | 20260326 | Primary gate requirements |
| Database Constraints | lupo-docs/versions/4.1.0/prd/requirements/database_constraints.md | 1 | 20260326 | Doctrine scan pass after AUTO_INCREMENT remediation |
| Core System Requirements | lupo-docs/versions/4.1.0/prd/requirements/core_system.md | 1 | 20260326 | Phase 1 verification matrix completed and approved |
| System Architecture | lupo-docs/versions/4.1.0/prd/architecture/system_architecture.md | 1 | 20260326 | Minimal acceptance-focused scope validated |
| Deployment Model | lupo-docs/versions/4.1.0/prd/architecture/deployment_model.md | 1 | 20260326 | Subdirectory-safe deployment baseline validated |
| Hosting Constraints | lupo-docs/versions/4.1.0/prd/constraints/hosting_constraints.md | 1 | 20260326 | Shared-hosting baseline constraints validated |
| PRD Directory README | lupo-docs/versions/4.1.0/prd/README.md | 1 | 20260326 | Governance reset and release-definition rules |
| Release Plan | lupo-docs/versions/4.1.0/plan.md | 1 | 20260326 | Phase model to acceptance submission |
| Release TODO | lupo-docs/versions/4.1.0/todo.md | 1 | 20260326 | Actionable acceptance-critical work |
| Approved Artifacts Index | lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md | 1 | 20260326 | Authority control file |
| Pending Artifacts Index | lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md | 1 | 20260326 | Authority control file |
| Rejected Artifacts Index | lupo-docs/versions/4.1.0/REJECTED_ARTIFACTS_INDEX.md | 1 | 20260326 | Authority control file |
| 4.0.88 Version Doctrine | lupo-docs/versions/4.0.88/DOCTRINE.md | 102 | 20260327 | Approved carryover doctrine for workflow, storage, TOON, and approval-state clarifications |
| 4.0.88 Version README | lupo-docs/versions/4.0.88/README.md | 102 | 20260327 | Approved carryover overview for 4.0.88 to 4.1.0 transition framing |
| 4.0.88 Development Plan | lupo-docs/versions/4.0.88/PLAN.md | 102 | 20260327 | Approved carryover planning baseline for thread-completed governance work |

