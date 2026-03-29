---
lupopedia.headers:
  lupopedia.schema: "requirements"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/requirements/database_constraints.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "database_constraints"
  purpose: "Database doctrine constraints for 4.1.0"
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
    - "Monitor deterministic ID rollout coverage in runtime write paths"
---

# Database Constraints

## Mandatory Doctrine Constraints

1. No foreign keys.
2. No triggers.
3. UTC BIGINT timestamps only (`YYYYMMDDHHIISS`).
4. Deterministic IDs only.
5. No AUTO_INCREMENT in canonical 4.1.0 storage model.

## Release Impact

Any migration or installer operation violating these constraints is release-blocking for 4.1.0.

## Phase 1 Evidence Snapshot (20260326)

Post-remediation installer SQL scan against `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`:

- `FOREIGN KEY`: 0
- `CREATE TRIGGER`: 0
- `AUTO_INCREMENT`: 0
- `DATETIME` type: 0
- `TIMESTAMP` type: 0
- `UNSIGNED` type: 0

Deterministic ID implementation support is now available via:

- `lupo-includes/classes/DeterministicIdService.php`

This service provides doctrine-compatible, deterministic BIGINT ID allocation with in-memory per-table/per-second counter caching for low-overhead performance.
