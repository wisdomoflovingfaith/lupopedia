---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_rate_limits.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "API rate-limit counters per window, token, actor, IP, endpoint"
  mood_rgb: "4169E1"
  traits: ["canonical", "api", "security", "cursor_domain", "v4.0.70"]
  tags: ["database", "api", "rate_limits"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_api_rate_limits.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_api_tokens.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_api_rate_limits

## Table Overview

- **Purpose:** Tracks API request counts per time window for rate limiting. Can be keyed by domain, api_token_id, actor_id, ip_address, and/or endpoint.
- **Category:** API / Security / Access control
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| api_rate_limit_id | bigint | No | — | Primary key. |
| domain_id | bigint | No | 1 | Domain context. |
| api_token_id | bigint | No | 0 | Token (logical → lupo_api_tokens). |
| actor_id | bigint | No | 0 | Actor (logical → lupo_actors). |
| ip_address | varchar(45) | Yes | — | Client IP. |
| endpoint | varchar(255) | Yes | — | API endpoint pattern. |
| window_ymdhis | bigint | No | — | Window start timestamp. |
| request_count | int | No | 0 | Request count in window. |
| limit_value | int | No | 0 | Limit for window. |
| created_ymdhis | bigint | No | 0 | Row creation. |
| updated_ymdhis | bigint | No | — | Last update. |

## Relationships

- **Logical references:** api_token_id → lupo_api_tokens; actor_id → lupo_actors; domain_id → domain/federation.
- **Inbound:** API rate-limiting middleware reads/updates this table.
- **Join patterns:** By (actor_id, window_ymdhis), (domain_id, window_ymdhis), endpoint, (ip_address, window_ymdhis), (api_token_id, window_ymdhis).

## Usage Notes

- **Indexes:** (actor_id, window_ymdhis), (domain_id, window_ymdhis), endpoint, (ip_address, window_ymdhis), (api_token_id, window_ymdhis).
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
