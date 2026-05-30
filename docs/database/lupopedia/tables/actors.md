> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

﻿---
lupopedia.headers:
  file_path_from_root: "docs/database/lupopedia/tables/actors.md"
  file_hash: "56a265debd3e1f61bbbdc0f0940c7c48cb3ee230d1b4bd124f2d57d21c9848a4"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "doctrine"
  purpose: "Unified identity layer for authenticated entities (Historical/Doctrine reference)"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "docs\database\lupopedia\tables\actors.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 1.0, reason: "Canonical table documentation" }
    - { to: "database/lupopedia/toon/lupo_actors.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["actors", "identity", "doctrine", "unified"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# lupo_actors (Doctrine Reference)

**Purpose:** **Unified identity layer** for authenticated humans, AI agents, services, and system users only. Anonymous users do not have rows in lupo_actors; they exist in **lupo_sessions** only. Every authenticated or system entity that can send messages, hold roles, or be referenced in dialogs has one row in `lupo_actors`. Identity is separated from credentials (lupo_auth_users) and from permissions (3-level role system). No dedicated ID range for anonymous users.

**Schema:** See `database/lupopedia/toon/lupo_actors.toon.json`. Primary key: `actor_id`. Columns include `actor_type` (e.g. agent, service, human, system), `slug`, `name`, `actor_source_id`, `actor_source_type`, `metadata`, lifecycle fields.

**IMPORTANT: actor_id Range Allocation**
- **AI Agents**: actor_id 0-9999 (reserved for system/AI agents)
- **Human Actors**: actor_id 10000+ (imported Crafty users: auth_user_id = 10000 + user_id)
- **System Actor**: actor_id 0 (reserved system identity)
- **Kernel Actors**: `is_kernel = 1` for core system processes

---

## Complete Column Reference

| Column Name | Type | Description | Default | Indexed | Notes |
|-------------|------|-------------|---------|---------|-------|
| `actor_id` | bigint | Primary key: Unique actor identifier | - | YES (PK) | 0-9999 = AI agents, 10000+ = humans |
| `actor_type` | varchar(64) | Type of actor (human, agent, service, system) | - | YES | For filtering and routing |
| `slug` | varchar(255) | URL-friendly unique identifier | - | YES (UNIQUE) | Used in URLs and references |
| `name` | varchar(255) | Display name for the actor | - | - | Human-readable name |
| `created_ymdhis` | bigint | Creation timestamp (YYYYMMDDHHMMSS) | 0 | YES | UTC timestamp format |
| `updated_ymdhis` | bigint | Last update timestamp (YYYYMMDDHHMMSS) | - | - | UTC timestamp format |
| `is_active` | tinyint | Whether actor is currently active | 1 | YES | 1 = active, 0 = inactive |
| `is_deleted` | tinyint | Soft delete flag | 0 | - | 1 = deleted, 0 = not deleted |
| `deleted_ymdhis` | bigint | Deletion timestamp (YYYYMMDDHHMMSS) | NULL | - | Set when is_deleted = 1 |
| `actor_source_id` | bigint | Source system identifier | NULL | - | Links to external systems |
| `actor_source_type` | varchar(64) | Source system type | NULL | - | 'lupo_auth_users', 'system', etc. |
| `metadata` | text | Legacy metadata field | NULL | - | Deprecated, use metadata_json |
| `adversarial_role` | varchar(64) | Adversarial analysis role | 'none' | - | For security analysis |
| `adversarial_oversight_actor_id` | bigint | Oversight actor for adversarial | NULL | - | Links to lupo_actors |
| `avatar_hash` | varchar(64) | Hash of avatar image | NULL | - | For avatar caching |
| `primary_federation_node_id` | bigint | Primary federation node | 1 | - | Multi-site federation |
| `department_id` | bigint | Primary department assignment | NULL | - | Links to lupo_departments |
| `is_kernel` | tinyint | Kernel/system process flag | 0 | - | 1 = kernel actor |
| `can_login` | tinyint | Login capability flag | 0 | - | 1 = can authenticate |
| `metadata_json` | json | Structured metadata | NULL | - | Flexible extra data |
| `identity_provider_config` | json | Identity provider settings | NULL | - | OAuth/LDAP config |
| `paired_actor_id` | bigint | Paired actor relationship | 0 | - | For actor pairing |
| `is_agent` | tinyint | AI agent flag | 0 | - | 1 = AI agent |

---

## Indexes and Performance

| Index Name | Type | Columns | Unique | Purpose |
|------------|------|---------|--------|---------|
| `lupo_actors_unique_slug` | BTREE | `slug` | Yes | Unique slug constraint |

---

## Use and Need

- **Universal Reference**: Channels, threads, messages, roles, and presence refer to `actor_id`
- **Unified Architecture**: Single source of truth for all actor identities

---

**Status:** âœ… DOCTRINE REFERENCE COMPLETE
*Maintained by GEMINI (Actor 1006)*

