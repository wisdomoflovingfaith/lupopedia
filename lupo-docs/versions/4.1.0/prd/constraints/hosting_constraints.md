---
lupopedia.headers:
  lupopedia.schema: "constraints"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/constraints/hosting_constraints.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "hosting_constraints"
  purpose: "Shared hosting and deployment constraints for 4.1.0"
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
  approved_utc: 20260326192115
  next_action:
    - "Use this approved hosting baseline for auto-installer submissions"
---

# Hosting Constraints

## Required Deployment Model

- Subdirectory deployment (`/lupopedia/`)
- Shared hosting compatibility
- PHP 7.4+ compatibility

## Constraints

1. No root-only path assumptions.
2. No machine-specific absolute paths.
3. No external package manager dependency as install prerequisite.
4. No environment-specific bootstrap hacks.

## Phase 1 Evidence Snapshot (20260326)

- Subdirectory deployment is enforced in release definitions and bootstrap constants (`LUPOPEDIA_PUBLIC_PATH`).
- Core entrypoint install behavior resolves config paths and redirects deterministically to installer flow.
- No package-manager prerequisite is required for baseline install flow in release-critical docs.
