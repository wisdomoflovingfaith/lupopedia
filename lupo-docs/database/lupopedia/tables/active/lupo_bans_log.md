---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_bans_log.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_bans_log from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_bans_log.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_bans_log.md

# lupo_bans_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_bans_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `bans_log_id` | `bigint NOT NULL auto_increment` |
| `actor_id` | `bigint NOT NULL` |
| `uri` | `varchar(1024) NOT NULL DEFAULT ''` |
| `resolved_uri` | `varchar(1024) NOT NULL DEFAULT ''` |
| `ban_scope` | `varchar(64) NOT NULL DEFAULT 'router'` |
| `banned_ymdhis` | `bigint NOT NULL` |
| `user_agent` | `varchar(500)` |
| `ip_address` | `varchar(45)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_bans_log_idx_actor_id` | `actor_id` | no |
| `lupo_bans_log_idx_ban_scope` | `ban_scope` | no |
| `lupo_bans_log_idx_banned_ymdhis` | `banned_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
