---
lupopedia.headers:
  lupopedia.schema: "acceptance_checklist"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/acceptance/installatron_checklist.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "acceptance_checklist"
  purpose: "Installatron acceptance checklist for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "pending"
  approved_by_actor_id: 0
  approved_utc: 0
  next_action:
    - "Execute checklist against clean shared-hosting simulation"
---

# Installatron Acceptance Checklist

This checklist is split into two stages:

1. Internal preflight validation (execution controlled by the Lupopedia team).
2. External manual review feedback from Installatron after package publication.

## Install Flow

- [ ] Clean install succeeds without manual intervention.
- [ ] Subdirectory install (`/lupopedia/`) works.
- [ ] No absolute path dependency.
- [ ] Installer script does not require interactive patching.

## Schema and Upgrade

- [ ] Schema initializes deterministically.
- [ ] Crafty Syntax 3.7.5 upgrade path is predictable.
- [ ] No experimental migration branches required.

## Runtime Sanity

- [ ] Web chat baseline works after install.
- [ ] Admin panel baseline works after install.
- [ ] Session and auth flows are functional.

## Release Decision

- [ ] Checklist evidence recorded in changelog.
- [ ] Softaculous review outcome recorded as primary external signal.
- [ ] 4.0.x package published and submitted for Installatron review.
- [ ] External reviewer feedback received and documented.
- [ ] Required reviewer fixes applied and revalidated.
- [ ] Approval status set to `approved` only after all items pass and external feedback is resolved.
