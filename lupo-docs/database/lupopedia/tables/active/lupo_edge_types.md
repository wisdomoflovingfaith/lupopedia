---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_edge_types.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_edge_types
  last_modified_utc: '20260325125224'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  purpose: Canonical registry documentation for lupo_edge_types in 4.0.87 edge consolidation planning.
  tags:
  - database
  - edge_types
  - registry
  - 4.0.87
  when_updated: '20260325125224'
lupopedia.footer:
  last_verified: '20260325125224'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
---

# Table: lupo_edge_types

## Purpose

Canonical runtime registry of structural edge slugs used by relationship writes to lupo_edges.

## Schema (install SQL authority)

| Column | Type | Notes |
|---|---|---|
| edge_type_id | bigint NOT NULL AUTO_INCREMENT | Primary key |
| slug | varchar(64) NOT NULL | Unique edge type slug |
| label | varchar(128) NOT NULL | Human-readable label |
| description | text | Optional description |
| is_bidirectional | tinyint NOT NULL DEFAULT 0 | Directionality rule |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC BIGINT timestamp |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC BIGINT timestamp |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft-delete marker |

Indexes:

- PRIMARY KEY (edge_type_id)
- UNIQUE lupo_edge_types_uniq_slug (slug)

## Current 4.0.87 seeded slugs

- channel_related
- channel_parent
- channel_successor
- channel_spawned_thread
- channel_references
- thread_continuation
- thread_spawned_from
- thread_references
- thread_crosses_channel
- channel_sibling
- artifact_spawned_from
- channel_observes

## Runtime usage notes

- EdgeQueryService reads this table as the active edge type registry.
- Seed path: lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql.
- Migration utility path also inserts rows: hephaestus_execute_migrations.php.

## Header edge-type clarification

lupo_edge_types is the runtime structural edge vocabulary for database graph rows.
It is not the same vocabulary as file-header outbound edge types in lupopedia.edges.outbound_edges[].type.

Examples of header edge types seen in docs: references, schema_reference, DEFINES_SCHEMA_FOR.

Keep these vocabularies distinct unless an explicit mapping layer is introduced.
