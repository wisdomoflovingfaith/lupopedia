---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: governance
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
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
