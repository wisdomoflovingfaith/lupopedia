---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_versions.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Version history per agent (semver, version_label, previous_version_id)"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "versions"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_versions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_versions

## Table Overview

- **Purpose:** Version history for agents: agent_id, version_label, semver_major/minor/patch, version_notes, version_hash, previous_version_id. Soft-delete supported.
- **Category:** Agent
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_version_id | bigint | No | — | Primary key. |
| agent_id | bigint | No | — | Agent (logical → lupo_agents.agent_id). |
| version_label | varchar(64) | No | — | Version label string. |
| semver_major | int | Yes | 0 | Major version. |
| semver_minor | int | Yes | 0 | Minor version. |
| semver_patch | int | Yes | 0 | Patch version. |
| version_notes | text | Yes | — | Release notes. |
| version_hash | varchar(128) | Yes | — | Content hash. |
| previous_version_id | bigint | Yes | — | Previous version (same table). |
| created_ymdhis | bigint | No | 0 | Creation. |
| updated_ymdhis | bigint | No | — | Last update. |
| is_deleted | smallint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** agent_id → lupo_agents.agent_id; previous_version_id → lupo_agent_versions.agent_version_id.
- **Inbound:** Agent versioning and rollback.
- **Join patterns:** By agent_id, (semver_major, semver_minor, semver_patch), version_label.

## Usage Notes

- **Indexes:** agent_id, (semver_major, semver_minor, semver_patch), version_label.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
