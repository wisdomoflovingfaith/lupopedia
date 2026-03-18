---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_projects.md"
  system_version: "4.0.80"
  namespace: "core"
  channel_id: 42
  actor_id: 14
  actor_name: "hephaestus"
  last_modified_utc: "20260318"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_projects table - core registry for projects"
  artifact_kind: "table"
  traits: ["canonical", "core_system", "registry", "v4.0.80"]
  tags: ["database", "projects", "registry", "orchestrator"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_projects table doc at 4.0.80 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=2 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_projects", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/seed/seed_projects.sql", type: "references", weight: 0.95 }
    - { to: "lupo-includes/classes/ProjectService.php", type: "USED_IN_PHP", weight: 0.9 }

lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "hephaestus"
  next_action:
    - "Regenerate TOON for lupo_projects when DB or generate_toon_from_sql is run"
---
# file: lupo_projects — session: L-LUPO-ROOT-HEPHAESTUS — delegation: hephaestus:root

# lupo_projects

Core registry table for **projects** (scope: channel, orchestrator, federation node). Added 4.0.74 per Captain directive; KIRO proposal.

## Purpose

- Store project identity and metadata keyed by `project_key` and `federation_node_id`.
- Link projects to channel, orchestrator actor, and federation node.
- Support soft delete and BIGINT UTC timestamps per doctrine.

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| project_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| project_key | varchar(64) NOT NULL | Unique key per node (with federation_node_id). |
| project_name | varchar(255) NOT NULL | Display name. |
| project_slug | varchar(255) NOT NULL | URL-friendly slug. |
| description | text | Optional description. |
| github_repository | varchar(512) DEFAULT NULL | Canonical GitHub repository URL (e.g. https://github.com/org/repo). |
| channel_id | bigint NOT NULL | Channel scope. |
| orchestrator_id | bigint NOT NULL | Orchestrator actor_id. |
| federation_node_id | bigint NOT NULL | Federation node. |
| status | varchar(32) NOT NULL DEFAULT 'active' | Status (e.g. active, archived). |
| project_type | varchar(64) NOT NULL DEFAULT 'general' | Type. |
| metadata_json | json DEFAULT NULL | Optional JSON metadata. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp (YYYYMMDDHHIISS UTC). |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | Updated timestamp (YYYYMMDDHHIISS UTC). |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint NOT NULL DEFAULT 0 | Soft delete timestamp. |

**Primary key:** project_id  
**Unique:** (project_key, federation_node_id)  
**Indexes:** channel_id, orchestrator_id, federation_node_id, status, is_deleted

## Doctrine notes

- **No foreign keys** — references to channel_id, orchestrator_id, federation_node_id are logical; application enforces.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in application (e.g. `gmdate('YmdHis')`).
- **Reserved ID / registry:** project_id is explicit; allocate from registry or use reserved IDs (e.g. seed uses 0 for lupopedia-core).
- **JSON:** metadata_json allowed for flexible attributes; no DB logic.

## Seed

- **lupo-database/lupopedia/mysql/seed/seed_projects.sql** — Inserts reserved **project_id 0** as lupopedia-core with `github_repository = 'https://github.com/wisdomoflovingfaith/lupopedia'`. Run after install; timestamps use seed defaults.

---

*HEPHAESTUS (actor_id 14) — table doc 2026-03-18*
