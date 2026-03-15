---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/doctrine/DATABASE_DOCTRINE.md"
      reason: "Schema and migration doctrine"
    - path: "lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md"
      reason: "Safe migration wrapper usage"
  required_context:
    - "4.0.x has no Lupopedia-to-Lupopedia upgrade; only Crafty 3.7.5 → Lupopedia. 4.1.0 introduces Lupopedia→Lupopedia."

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md"
  last_modified_utc: "20260316"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  artifact_type: "documentation"
  artifact_kind: "migration_guide"
  purpose: "Production migration and upgrade guidance for 4.0.76 Project System."

lupopedia.session:
  session_id: "L-LUPO-CURSOR-4.0.76-FINAL"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  federation_node_id: 1

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/status/CURSOR_PROJECT_SYSTEM_4_0_76_FINAL_COMPLETION.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/mysql/seed/seed_projects.sql", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# Project System 4.0.76 — Upgrade and Migration Guide

## What changed in 4.0.76

- **lupo_projects table** added in install SQL. No AUTO_INCREMENT; `project_id` is application-assigned (reserved-ID doctrine).
- **lupo_channels** gained optional **project_id** column: `bigint DEFAULT NULL` with index `lupo_channels_idx_project_id`. Existing channels remain valid with `project_id = NULL`.
- **Seed:** `seed_projects.sql` inserts the default project (project_id 1, lupopedia-core). Run after install.
- **Application:** ProjectService, project registry (`lupo-database/lupopedia/projects/registry.json`), and REST endpoints under `lupo-api/v1/projects/` (list, get, create, update, archive, freeze).
- **Doctrine:** No foreign keys, triggers, or stored procedures; BIGINT UTC timestamps; PHP 5.6+ compatible.

---

## Fresh install (no existing Lupopedia)

1. Run **install_new_lupopedia.sql** (creates all tables including `lupo_projects` and `lupo_channels.project_id`).
2. Run **seed_projects.sql** (and any other seed scripts your process uses).
3. Confirm one row in `lupo_projects` with `project_id = 1`, `project_key = 'lupopedia-core'`.
4. Channels may have `project_id = NULL` until assigned; default project 1 uses `default_channel_id = 42` if desired.

---

## Upgrade from 4.0.75 (or earlier 4.0.x)

**Doctrine (4.0.x):** There is **no** formal Lupopedia→Lupopedia upgrade path until **4.1.0**. For 4.0.76:

- **Recommended:** Treat as “reinstall from Crafty 3.7.5” if you need a clean 4.0.76 state: run install SQL (which now includes `lupo_projects` and `lupo_channels.project_id`), then run seed scripts including `seed_projects.sql`.
- **If you must preserve data:** Manually add the 4.0.76 schema changes to your database:
  - Create `lupo_projects` using the table definition from `install_new_lupopedia.sql` (same columns and indexes).
  - Add `project_id bigint DEFAULT NULL` to `lupo_channels` if missing, and add index `lupo_channels_idx_project_id` on `lupo_channels(project_id)`.
  - Run `seed_projects.sql` to insert the default project (project_id 1) if not already present.
- **Backward compatibility:** Existing channels with no `project_id` (NULL) continue to work. Application code must tolerate NULL `project_id` when reading channels.

---

## Crafty Syntax 3.7.5 → Lupopedia 4.0.76

- The only **supported** upgrade path in 4.0.x is **Crafty Syntax 3.7.5 → Lupopedia** (import + install + seed).
- Use the **current** install SQL (which includes `lupo_projects` and `lupo_channels.project_id`).
- After install, run **seed_projects.sql** so the default project exists.
- No schema conflicts: Crafty import does not define `lupo_projects` or `project_id` on channels; they are added by the Lupopedia install.

---

## lupo_channels.project_id and legacy installs

- **Nullable:** `project_id` is `DEFAULT NULL`. Existing channels and any code that does not set `project_id` remain valid.
- **Index:** `lupo_channels_idx_project_id` supports filtering and joins by `project_id`; NULLs are allowed in the index.
- **No FK:** No foreign key to `lupo_projects`; application logic is responsible for consistency.

---

## Rollback considerations

- **No automatic rollback script** is provided for 4.0.76. If you must revert:
  - **Schema:** Dropping `project_id` from `lupo_channels` and dropping `lupo_projects` is a manual, environment-specific operation. Prefer using the safe migration wrapper and idempotent patterns if you introduce a formal rollback script later.
  - **Application:** Deploy a previous version of the code that does not depend on projects; ensure it tolerates `project_id` column presence (e.g. ignore or pass through).
- **Data:** If you inserted rows into `lupo_projects`, back them up before any rollback; there is no built-in “undo” for project data.

---

## What is and is not supported before 4.1.0

- **Supported:** Fresh install (install + seed including `seed_projects.sql`); Crafty 3.7.5 → Lupopedia upgrade using current install/seed; channels with `project_id = NULL`; ProjectService and project API for 4.0.76.
- **Not supported:** Lupopedia→Lupopedia automated upgrade (e.g. 4.0.75 → 4.0.76) is out of scope until 4.1.0. Manual schema/data steps are the only option if you must move an existing Lupopedia DB to 4.0.76.

---

## References

- Install: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Seed: `lupo-database/lupopedia/mysql/seed/seed_projects.sql`
- Design: `lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md`
- Doctrine: `lupo-docs/doctrine/DATABASE_DOCTRINE.md`, `lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md`
- Single-install doctrine: `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`
