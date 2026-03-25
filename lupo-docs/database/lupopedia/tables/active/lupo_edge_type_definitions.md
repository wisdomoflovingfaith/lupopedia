---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_edge_type_definitions.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_edge_type_definitions
  last_modified_utc: '20260325125224'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  purpose: Constraint/semantics registry documentation for lupo_edge_type_definitions during 4.0.87 edge consolidation planning.
  tags:
  - database
  - edge_types
  - definitions
  - 4.0.87
  when_updated: '20260325125224'
lupopedia.footer:
  last_verified: '20260325125224'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
---

# Table: lupo_edge_type_definitions

## Purpose

Stores semantic and object-type constraints for edge_type values.
In 4.0.87 planning, this table is treated as a parallel registry that overlaps with lupo_edge_types.

## Schema (install SQL authority)

| Column | Type | Notes |
|---|---|---|
| edge_type_definition_id | bigint NOT NULL | Primary key |
| edge_type | varchar(100) NOT NULL | Unique edge type key |
| domain | varchar(100) NOT NULL | Domain label |
| description | text NOT NULL | Description |
| allowed_left_object_types | text NOT NULL | Allowed source object types |
| allowed_right_object_types | text NOT NULL | Allowed target object types |
| is_bidirectional | tinyint NOT NULL DEFAULT 0 | Directionality flag |
| semantic_meaning | text DEFAULT NULL | Semantic explanation |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC BIGINT timestamp |
| created_by_actor_id | bigint NOT NULL | Author actor |

Indexes:

- PRIMARY KEY (edge_type_definition_id)
- UNIQUE lupo_edge_type_definitions_unique_edge_type (edge_type)
- INDEX lupo_edge_type_definitions_idx_domain (domain)

## Runtime usage notes

- Seed/write paths are present in seed SQL and migration utility scripts.
- Active runtime read paths are limited compared to lupo_edge_types.
- 4.0.87 consolidation direction is to keep one canonical type registry (target: lupo_edge_types).

## Relationship to LUPOPEDIA headers

Header outbound edge type values (lupopedia.edges.outbound_edges[].type) are documentation graph semantics and should not be treated as a direct substitute for this table without explicit mapping.

Examples from docs include references and schema_reference; these belong to file metadata graph semantics, not necessarily to runtime entity relationship semantics.
