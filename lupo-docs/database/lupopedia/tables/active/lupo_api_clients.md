---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_api_clients.md
  channel_id: 1
  actor_id: 102
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: API client credentials (client_key, client_secret) per actor
  mood_rgb: 4169E1
  traits:
  - canonical
  - api
  - auth
  - cursor_domain
  - v4.0.70
  tags:
  - database
  - api
  - clients
  - credentials
  lupo_agent: cursor
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_api_clients.toon.json
    type: schema_reference
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
    type: references
    weight: 0.8
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table: lupo_api_clients

## Table Overview

- **Purpose:** OAuth-style API client credentials: client_key, client_secret, scopes, and expiry. One row per client application per actor.
- **Category:** API / Authentication / Credentials
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| api_client_id | bigint | No | — | Primary key. |
| actor_id | bigint | No | 0 | Owning actor. |
| client_key | varchar(255) | No | — | Unique client identifier. |
| client_secret | varchar(255) | No | — | Client secret. |
| client_name | varchar(150) | No | — | Display name. |
| client_description | text | Yes | — | Description. |
| scopes | text | Yes | — | Allowed scopes. |
| is_active | tinyint | No | 1 | Active flag. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | No | — | Last update. |
| expires_ymdhis | bigint | Yes | — | Expiry timestamp. |

## Relationships

- **Logical references:** actor_id → lupo_actors.actor_id.
- **Inbound:** API client auth and token issuance.
- **Join patterns:** By client_key (unique), actor_id, is_active, expires_ymdhis.

## Usage Notes

- **Indexes:** is_active, actor_id, expires_ymdhis; unique on client_key.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
