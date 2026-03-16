---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_edge_type_definitions.md"
  web_path: "[lupo_edge_type_definitions](http://www.lupopedia.com/database/lupopedia/tables/active/development/lupo_edge_type_definitions)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Documentation for lupo_edge_type_definitions table - semantic edge type registry"
  traits: ["canonical", "semantic_os", "edges", "registry", "v4.0.78"]
  tags: ["database", "edges", "semantic", "registry"]
  table_primary_key: "edge_type_definition_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_edge_type_definitions table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_edge_type_definitions — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/development/lupo_edge_type_definitions

# Table: lupo_edge_type_definitions

## Table Overview

- **Purpose:** Canonical registry of semantic edge types used in the Lupopedia knowledge graph. Each row defines one edge type: its slug (edge_type), domain, description, allowed source/target object types, directionality, and optional semantic meaning. Governs what relationship types can be stored in lupo_edges and used for cross-document linking and navigation.
- **Category:** Semantic OS / Edge Graph / Registry
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Knowledge graph relationships:** lupo_edges and other graph tables store edges with an edge_type; this table defines the valid types and their semantics so that inserts and traversals use consistent taxonomy.
- **Semantic edges between artifacts:** LUPOPEDIA HEADERS and metadata use edge types (e.g. references, extends, depends_on); this registry is the source of truth for valid type slugs and their descriptions.
- **Cross-document linking:** When linking documents or artifacts, application code can validate and classify links using edge_type and the allowed_left_object_types / allowed_right_object_types constraints defined here.
- **Schema relationship definition:** Edge types define the schema of the relationship layer: domain, directionality (is_bidirectional), and which object types can be source or target.
- **Navigation graph generation:** Tooling that builds navigation or suggestion graphs (e.g. flare_edge_suggester, doc generators) reads this table to know which edge types exist and how to interpret them.

## Edge taxonomy and relationship semantics

- **edge_type:** Unique slug (e.g. references, extends, depends_on, semantic_tag). Used as the value in lupo_edges.edge_type and in LUPOPEDIA HEADERS outbound_edges.
- **domain:** Groups edge types by namespace (e.g. content, actor, doctrine, filesystem) for filtering and governance.
- **allowed_left_object_types / allowed_right_object_types:** Define which entity types can be source and target; application parses these (e.g. JSON array) and validates before inserting edges.
- **is_bidirectional:** When 1, the relationship is symmetric (A→B implies B→A); when 0, directed only.

## Edge classification

- Edge types are classified by domain and by directionality. New types are registered by inserting a row with a unique edge_type and the defining actor in created_by_actor_id. Application code should validate edge_type against this table before creating edges.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| edge_type_definition_id | bigint | No | — | Primary key. Reserved-ID doctrine: application supplies explicit ID; not AUTO_INCREMENT. |
| edge_type | varchar(100) | No | — | Unique edge type slug (e.g. references, extends, depends_on). Used in lupo_edges.edge_type. |
| domain | varchar(100) | No | — | Domain/namespace for this edge type (e.g. content, actor, doctrine). |
| description | text | No | — | Human-readable description of the edge type. |
| allowed_left_object_types | text | No | — | Allowed source object types (JSON array or delimited list). Application parses and validates. |
| allowed_right_object_types | text | No | — | Allowed target object types (JSON array or delimited list). |
| is_bidirectional | tinyint | No | 0 | 1 = bidirectional; 0 = directed. |
| semantic_meaning | text | Yes | — | Optional extended semantic description for tooling or LLM context. |
| created_ymdhis | bigint | No | 0 | Creation timestamp in YYYYMMDDHHIISS UTC format. Set in application code. |
| created_by_actor_id | bigint | No | — | Logical reference to lupo_actors.actor_id; actor who defined this edge type. |

## Indexes

- **PRIMARY KEY:** edge_type_definition_id
- **UNIQUE:** lupo_edge_type_definitions_unique_edge_type (edge_type)
- **INDEX:** lupo_edge_type_definitions_idx_domain (domain)

## Relationships

- **Logical references (no DB FKs):** created_by_actor_id → lupo_actors.actor_id. Application code ensures the actor exists when inserting.
- **Consumed by:** lupo_edges and any table or code that stores edge_type; validation and graph tooling read this table to resolve edge type metadata.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** created_ymdhis is BIGINT UTC YYYYMMDDHHIISS; set in PHP only.
- **Unique edge_type:** No duplicate type slugs; handle conflicts in application logic when registering new types.
- **Reserved ID:** edge_type_definition_id is not AUTO_INCREMENT; application must supply explicit value.
- **allowed_left/right_object_types:** Stored as TEXT; application layer parses (e.g. JSON) and enforces constraints when validating or creating edges.
