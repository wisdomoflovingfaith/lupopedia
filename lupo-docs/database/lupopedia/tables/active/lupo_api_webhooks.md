---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_webhooks.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Webhook subscriptions (endpoint URL, secret, event types) per actor/module"
  mood_rgb: "4169E1"
  traits: ["canonical", "api", "cursor_domain", "v4.0.70"]
  tags: ["database", "api", "webhooks"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_api_webhooks.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_api_webhooks

## Table Overview

- **Purpose:** Outbound webhook configuration: endpoint URL, secret key, event types, retry policy, and optional expiry. Scoped by actor and module.
- **Category:** API / Webhooks
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| api_webhook_id | bigint | No | — | Primary key. |
| domain_id | bigint | No | 1 | Domain context. |
| actor_id | bigint | No | 0 | Owning actor. |
| module_id | bigint | No | 0 | Module context. |
| endpoint_url | varchar(500) | No | — | Webhook URL. |
| secret_key | varchar(255) | No | — | Secret for signing payloads. |
| event_types | text | No | — | Comma-separated or JSON event types. |
| is_active | tinyint | No | 1 | Active flag. |
| max_retries | int | No | 5 | Max delivery retries. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | No | — | Last update. |
| expires_ymdhis | bigint | Yes | — | Optional expiry. |
| notes | text | Yes | — | Optional notes. |

## Relationships

- **Logical references:** actor_id → lupo_actors; module_id → module registry.
- **Inbound:** Webhook delivery service reads this table.
- **Join patterns:** By is_active, actor_id, domain_id, expires_ymdhis, module_id.

## Usage Notes

- **Indexes:** is_active, actor_id, domain_id, expires_ymdhis, module_id.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
