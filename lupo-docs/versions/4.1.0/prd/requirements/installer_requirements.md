---
lupopedia.headers:
  lupopedia.schema: "requirements"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/requirements/installer_requirements.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "installer_requirements"
  purpose: "Installer requirements and release gate criteria for 4.1.0"
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
  approved_utc: 20260326223200
  next_action:
    - "Use this as primary acceptance gate for 4.1.0"
---

# Installer Requirements

## Primary Gate

4.1.0 release is blocked by auto-installer acceptance, not by additional feature invention.

## Mandatory Installer Behaviors

1. Clean install from a fresh environment with no manual edits.
2. Subdirectory install support (`/lupopedia/`) without root-path assumptions.
3. No absolute filesystem dependencies.
4. Stable schema creation path.
5. Predictable Crafty Syntax 3.7.5 upgrade path.
6. Idempotent installer step behavior where applicable.

## Forbidden Release Behaviors

- Manual SQL surgery as a required step.
- Hidden environment assumptions.
- Non-deterministic install outcomes.
