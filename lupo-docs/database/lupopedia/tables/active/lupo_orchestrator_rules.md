---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_orchestrator_rules.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_orchestrator_rules from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_orchestrator_rules.json"
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
# file: lupo_orchestrator_rules.md

# lupo_orchestrator_rules

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_orchestrator_rules`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `rule_id` | `bigint NOT NULL auto_increment` |
| `rule_slug` | `varchar(128) NOT NULL` |
| `orchestrator_actor` | `varchar(64) NOT NULL` |
| `rule_set_version` | `varchar(32) NOT NULL` |
| `applies_to_json` | `text NOT NULL` |
| `enforcement_level` | `varchar(32) NOT NULL DEFAULT 'strict'` |
| `rule_content` | `text NOT NULL` |
| `checksum` | `varchar(64) NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `updated_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_orchestrator_rules_idx_active` | `is_active` | no |
| `lupo_orchestrator_rules_idx_actor_version` | `orchestrator_actor`, `rule_set_version` | no |
| `lupo_orchestrator_rules_idx_updated` | `updated_ymdhis` | no |
| `lupo_orchestrator_rules_uniq_slug` | `rule_slug` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
