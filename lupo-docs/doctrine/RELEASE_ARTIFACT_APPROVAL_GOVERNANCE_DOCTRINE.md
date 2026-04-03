---
lupopedia.headers:
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "governance"
  purpose: "Define release artifact approval model and promotion workflow"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md", type: "governs", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md", type: "governs", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "extends", weight: 0.9 }

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: 20260326224300
  next_action:
    - "Reference this doctrine from versioned release PRD READMEs"
---

# Release Artifact Approval Governance Doctrine

## Purpose

Define how artifacts become release-binding and how PRD systems enforce release scope.

## Approval Fields (Mandatory in release artifacts)

All release-scoped artifacts must include these `lupopedia.footer` fields:

- `approved_for_release`
- `approval_status` (`approved` | `pending` | `rejected`)
- `approved_by_actor_id`
- `approved_utc`

## Meaning of approval_status

- `approved`: Artifact is release-binding for the specified release.
- `pending`: Artifact is under evaluation and non-binding.
- `rejected`: Artifact is explicitly excluded from release scope.

## Granting Approval

Approval is granted only when:

1. Artifact content aligns with release PRD constraints.
2. Artifact is backed by execution or validation evidence.
3. Artifact does not conflict with approved release artifacts.
4. Artifact is listed in `APPROVED_ARTIFACTS_INDEX.md`.

## Promotion Workflow

1. Create or update artifact with `approval_status: pending`.
2. Run required validations and collect evidence.
3. Resolve contradictions with approved release scope.
4. Update footer to `approval_status: approved` and set approver fields.
5. Add artifact entry to `APPROVED_ARTIFACTS_INDEX.md`.

## Relationship to PRD System

- PRD directory defines what the system must be.
- Approval governance defines which artifacts are authoritative.
- Approved index defines final release boundary.
- Pending/rejected indexes define review pipeline and exclusions.

## Hard Rule

If an artifact is not approved and listed in the approved index, it is not part of the release definition.
