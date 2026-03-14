---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_token_logs.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Per-request audit log for API token usage"
  mood_rgb: "4169E1"
  traits: ["canonical", "api", "audit", "cursor_domain", "v4.0.70"]
  tags: ["database", "api", "token_logs", "audit"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_api_token_logs.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_api_tokens.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_api_token_logs

## Table Overview

- **Purpose:** Audit log of API requests authenticated by token: endpoint, method, IP, user agent, status code, and request time. Used for security and usage analytics.
- **Category:** API / Audit / Security
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| api_token_log_id | bigint | No | — | Primary key. |
| domain_id | bigint | No | 1 | Domain context. |
| api_token_id | bigint | No | — | Token used (logical → lupo_api_tokens). |
| actor_id | bigint | No | 0 | Actor at request time. |
| endpoint | varchar(255) | No | — | Requested endpoint. |
| http_method | varchar(10) | No | — | HTTP method. |
| ip_address | varchar(45) | Yes | — | Client IP. |
| user_agent | varchar(255) | Yes | — | User-Agent. |
| status_code | int | No | — | HTTP status. |
| request_ymdhis | bigint | No | — | Request timestamp. |
| duration_ms | int | Yes | — | Request duration in ms. |

## Relationships

- **Logical references:** api_token_id → lupo_api_tokens.api_token_id; actor_id → lupo_actors.
- **Inbound:** API middleware writes a row per token-authenticated request.
- **Join patterns:** By actor_id, (domain_id, request_ymdhis), endpoint, status_code, api_token_id.

## Usage Notes

- **Indexes:** actor_id, (domain_id, request_ymdhis), endpoint, status_code, api_token_id.
- **Timestamps:** request_ymdhis is BIGINT YYYYMMDDHHIISS UTC.
