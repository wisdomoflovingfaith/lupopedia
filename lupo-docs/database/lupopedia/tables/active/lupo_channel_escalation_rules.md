---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_escalation_rules.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Normalized table documentation for lupo_channel_escalation_rules from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_channel_escalation_rules.json"
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
# file: lupo_channel_escalation_rules.md

# lupo_channel_escalation_rules

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_escalation_rules`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `rule_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `rule_name` | `varchar(255) NOT NULL` |
| `rule_description` | `text` |
| `rule_type` | `varchar(64) NOT NULL` |
| `rule_config_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_escalation_rules_idx_channel_id` | `channel_id` | no |
| `lupo_channel_escalation_rules_idx_rule_type` | `rule_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
