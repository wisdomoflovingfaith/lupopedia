---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_tokens.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "API access tokens for actors and domains"
  mood_rgb: "4169E1"
  traits: ["canonical", "api", "auth", "cursor_domain", "v4.0.70"]
  tags: ["database", "api", "tokens", "auth"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_api_tokens.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_api_token_logs.md", type: "references", weight: 0.7 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_api_rate_limits.md", type: "references", weight: 0.7 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_api_tokens

## Table Overview

- **Purpose:** API tokens keyed by token_key, scoped to actor and domain. Used for API authentication and authorization.
- **Category:** Token / API / Authentication
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| api_token_id | bigint | No | — | Primary key. |
| domain_id | bigint | No | 1 | Domain/federation context. |
| actor_id | bigint | No | 0 | Actor that owns the token. |
| token_key | varchar(255) | No | — | Unique token value (hashed in storage if required). |
| token_label | varchar(150) | Yes | — | Human-readable label. |
| scopes | text | Yes | — | Allowed scopes. |
| is_active | tinyint | No | 1 | Active flag. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| expires_ymdhis | bigint | Yes | — | Expiry timestamp. |
| last_used_ymdhis | bigint | Yes | — | Last use timestamp. |
| created_ip | varchar(45) | Yes | — | IP at creation. |
| last_used_ip | varchar(45) | Yes | — | IP at last use. |
| notes | text | Yes | — | Optional notes. |

## Relationships

- **Logical references:** actor_id → lupo_actors; api_token_id referenced by lupo_api_token_logs, lupo_api_rate_limits.
- **Inbound:** API auth middleware and token refresh logic.
- **Join patterns:** By token_key (unique), actor_id, domain_id, is_active, expires_ymdhis, last_used_ymdhis.

## Usage Notes

- **Indexes:** is_active, actor_id, domain_id, expires_ymdhis, last_used_ymdhis; unique on token_key.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
