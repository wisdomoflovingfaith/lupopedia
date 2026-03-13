---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_files.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "File metadata per agent (path, hash, size, mime, upload time)"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "files"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_files.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_files

## Table Overview

- **Purpose:** File registry per agent: file_type, file_name, file_path, file_hash, file_size, mime_type, upload_ymdhis, and optional migrated_from_directory. Soft-delete supported.
- **Category:** Agent / Files
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| file_id | bigint | No | — | Primary key. |
| agent_id | bigint | No | — | Agent (logical → lupo_agents.agent_id). |
| file_type | varchar(50) | No | — | File type code. |
| file_name | varchar(255) | No | — | File name. |
| file_path | varchar(500) | No | — | Path. |
| file_hash | varchar(64) | No | — | Content hash. |
| file_size | bigint | No | — | Size in bytes. |
| mime_type | varchar(100) | Yes | — | MIME type. |
| upload_ymdhis | bigint | No | — | Upload timestamp. |
| created_ymdhis | bigint | No | 0 | Row creation. |
| updated_ymdhis | bigint | No | — | Last update. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |
| migrated_from_directory | varchar(255) | Yes | — | Migration source. |

## Relationships

- **Logical references:** agent_id → lupo_agents.agent_id.
- **Inbound:** Agent file upload and migration.
- **Join patterns:** By agent_id, file_hash, file_type, is_deleted, upload_ymdhis.

## Usage Notes

- **Indexes:** agent_id, file_hash, file_type, is_deleted, upload_ymdhis.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
