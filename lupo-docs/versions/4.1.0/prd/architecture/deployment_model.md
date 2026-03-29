---
lupopedia.headers:
  lupopedia.schema: "architecture"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/architecture/deployment_model.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "deployment_model"
  purpose: "Deployment model requirements for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md", type: "implements", weight: 0.9 }
    - { to: "lupo-docs/versions/4.1.0/prd/constraints/hosting_constraints.md", type: "implements", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/constraints/auto_installer_constraints.md", type: "implements", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_metadata.md", type: "affects", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "affects", weight: 0.8 }
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
    - "Carry this approved baseline into Softaculous preflight"
---

# Deployment Model

## Target Topology

- Web root contains subdirectory deployment at `/lupopedia/`
- Shared hosting compatible runtime
- Managed database instance with deterministic schema initialization

## Deployment Rules

1. No assumption of root-level installation.
2. No dependency on host-specific absolute paths.
3. No dependency on unavailable package managers.
4. Configuration must remain portable across providers.

## Acceptance Evidence Needed

To move this artifact to approved:

- Evidence of clean deployment under realistic shared-hosting topology with subdirectory installation.
- Evidence that URL/path resolution remains stable under `/lupopedia/` without root-only assumptions.
- Evidence that deployment does not require host-specific package managers or runtime customizations.
- Evidence that metadata and content persistence paths remain deterministic after deployment.

## Phase 1 Evidence Snapshot (20260326)

- Entrypoint bootstrap confirms dynamic subdirectory handling via `LUPOPEDIA_PUBLIC_PATH` in `index.php` and `module-loader.php`.
- Install redirect doctrine in `index.php` provides deterministic install flow when config is missing.
- Existing install-readiness execution evidence remains indexed in Channel 42 thread 2013 and is consistent with this deployment model.
