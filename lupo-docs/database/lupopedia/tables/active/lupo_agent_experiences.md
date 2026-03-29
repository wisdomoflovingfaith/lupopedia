---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_experiences.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_agent_experiences from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_agent_experiences.json"
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
# file: lupo_agent_experiences.md

# lupo_agent_experiences

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_experiences`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `link_id` | `char(26) NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `star_id` | `char(26) NOT NULL` |
| `intensity` | `decimal(3,2)` |
| `context_id` | `bigint` |
| `observed_ymdhis` | `bigint` |
| `expressed_as_rgb` | `char(6)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_experiences_idx_agent` | `agent_id` | no |
| `lupo_agent_experiences_idx_context` | `context_id` | no |
| `lupo_agent_experiences_idx_star` | `star_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
