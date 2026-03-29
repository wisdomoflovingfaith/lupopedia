---
lupopedia.headers:
  lupopedia.schema: "constraints"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/constraints/auto_installer_constraints.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "auto_installer_constraints"
  purpose: "Softaculous-first auto-installer acceptance constraints for 4.1.0"
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
  approved_utc: 20260326223300
  next_action:
    - "Track blocker status against installer checklists"
---

# Auto Installer Constraints

## External Acceptance Expectations

Softaculous, Installatron, and Fantastico are expected to require:

1. Clean install script with deterministic outcomes.
2. No manual post-install tasks.
3. Stable schema and predictable upgrade path.
4. No experimental features in release-critical path.
5. Subdirectory-safe URL and path behavior.

Operational reality for 4.1.0: external acceptance is manual after package publication.

- Internal checklists are preflight controls.
- Softaculous is the primary external signal for release readiness.
- Installatron and Fantastico are secondary confirmations after Softaculous signal.
- Any required external-review fix is release-blocking until resolved and revalidated.

## 4.1.0 Governance Rule

If a requirement does not contribute to installer acceptance, installability, or stable parity, it is out of 4.1.0 release scope.
