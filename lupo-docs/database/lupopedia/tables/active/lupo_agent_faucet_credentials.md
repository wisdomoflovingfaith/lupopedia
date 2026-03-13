---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucet_credentials.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "API keys and provider credentials per faucet"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "credentials", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "faucets", "credentials"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_faucet_credentials.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_faucet_credentials

## Table Overview

- **Purpose:** Stores provider-specific credentials (e.g. API keys) per faucet: faucet_id, provider, api_key (varbinary). Used for LLM/API calls under that faucet.
- **Category:** Agent / Credentials
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_faucet_credential_id | int | No | — | Primary key. |
| faucet_id | bigint | No | — | Faucet (logical → lupo_agent_faucets.agent_faucet_id). |
| provider | varchar(64) | No | — | Provider name. |
| api_key | varbinary(512) | No | — | Encrypted or hashed API key. |
| created_ymdhis | bigint | No | 0 | Creation. |
| updated_ymdhis | bigint | No | — | Last update. |

## Relationships

- **Logical references:** faucet_id → lupo_agent_faucets.agent_faucet_id.
- **Inbound:** Agent runtime reads credentials when invoking provider APIs.
- **Join patterns:** By faucet_id.

## Usage Notes

- **Index:** faucet_id. Store api_key securely (encrypt at rest if required).
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
