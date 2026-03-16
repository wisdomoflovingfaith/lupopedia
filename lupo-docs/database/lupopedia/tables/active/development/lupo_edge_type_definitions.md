---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_edge_type_definitions.md"
  system_version: "4.0.77"
  namespace: "semantic"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_edge_type_definitions table - canonical registry of semantic edge type definitions for the Lupopedia content graph"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "semantic_os", "edges", "registry", "v4.0.77"]
  tags: ["database", "edges", "semantic", "registry", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "edge_type_definition_id"
  lupo_edge_type_definitions.edge_type_definition_id: "BIGINT NOT NULL primary key"
  lupo_edge_type_definitions.edge_type: "VARCHAR(100) NOT NULL UNIQUE semantic edge type identifier"
  lupo_edge_type_definitions.domain: "VARCHAR(100) NOT NULL domain/namespace for this edge type"
  lupo_edge_type_definitions.description: "TEXT NOT NULL human-readable description of what this edge type means"
  lupo_edge_type_definitions.allowed_left_object_types: "TEXT NOT NULL JSON or delimited list of allowed source object types"
  lupo_edge_type_definitions.allowed_right_object_types: "TEXT NOT NULL JSON or delimited list of allowed target object types"
  lupo_edge_type_definitions.is_bidirectional: "TINYINT NOT NULL DEFAULT 0 whether this edge type is bidirectional"
  lupo_edge_type_definitions.semantic_meaning: "TEXT optional extended semantic description"
  lupo_edge_type_definitions.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC creation timestamp"
  lupo_edge_type_definitions.created_by_actor_id: "BIGINT NOT NULL actor who defined this edge type"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: ["PRIMARY", "lupo_edge_type_definitions_unique_edge_type", "lupo_edge_type_definitions_idx_domain"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_edge_type_definitions

## Table Overview

- **Purpose:** Canonical registry of all semantic edge types used in the Lupopedia content graph. Each row defines a valid edge type with its domain, description, directionality, and allowed object type constraints. This table governs what types of relationships can be created in `lupo_edges` and similar graph tables.
- **Category:** Semantic OS / Edge Graph / Registry
- **Status:** Development (not yet in canonical install SQL; defined in `development/` TOON only)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **lupo_edges validation:** Before inserting an edge into `lupo_edges`, application code can look up the `edge_type` here to validate that the source and target object types are permitted.
- **Semantic graph tooling:** Scripts like `flare_edge_suggester.py` and LUPOPEDIA HEADERS edge blocks use edge types to classify relationships. This table provides the authoritative list of valid types.
- **Content graph queries:** When traversing the knowledge graph, edge type definitions inform the query logic about directionality (`is_bidirectional`) and domain context.
- **Documentation generation:** Edge type definitions are referenced when auto-generating `lupopedia.edges` blocks in LUPOPEDIA HEADERS files.
- **UI and API:** API endpoints that expose graph relationships return edge type metadata from this table to help consumers interpret semantic meaning.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| edge_type_definition_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| edge_type | varchar(100) | No | — | Unique slug for this edge type (e.g. `references`, `extends`, `depends_on`, `semantic_tag`). Used as the foreign key value in `lupo_edges.edge_type`. |
| domain | varchar(100) | No | — | Domain or namespace (e.g. `content`, `actor`, `doctrine`, `filesystem`). Groups related edge types for querying and governance. |
| description | text | No | — | Human-readable explanation of what this edge type represents. |
| allowed_left_object_types | text | No | — | JSON array or delimiter-separated list of valid source object types (e.g. `["file","artifact","channel"]`). |
| allowed_right_object_types | text | No | — | JSON array or delimiter-separated list of valid target object types. |
| is_bidirectional | tinyint | No | `0` | `1` if the edge is symmetric (A→B implies B→A); `0` for directed edges. |
| semantic_meaning | text | Yes | — | Optional extended semantic description for tooling or LLM context. |
| created_ymdhis | bigint | No | `0` | Creation timestamp in YYYYMMDDHHIISS UTC format. Set in application code. |
| created_by_actor_id | bigint | No | — | `actor_id` of the actor who defined this edge type. Logically references `lupo_actors.actor_id`. |

## Indexes

| Index Name | Columns | Unique | Notes |
|-----------|---------|--------|-------|
| PRIMARY | edge_type_definition_id | Yes | Primary key |
| lupo_edge_type_definitions_unique_edge_type | edge_type | Yes | Prevents duplicate edge type slugs |
| lupo_edge_type_definitions_idx_domain | domain | No | Fast lookup of all edge types in a domain |

## Usage Patterns

### Validate edge type before insert
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$def = $db->fetchRow(
    "SELECT * FROM {$prefix}edge_type_definitions WHERE edge_type = :type",
    ['type' => $edgeType]
);
if (!$def) {
    throw new \Exception("Unknown edge type: {$edgeType}");
}
```

### List all edge types in a domain
```php
$rows = $db->fetchAll(
    "SELECT edge_type, description, is_bidirectional
     FROM {$prefix}edge_type_definitions
     WHERE domain = :domain
     ORDER BY edge_type ASC",
    ['domain' => 'content']
);
```

### Register a new edge type
```php
$now = gmdate('YmdHis');
$db->query(
    "INSERT INTO {$prefix}edge_type_definitions
     (edge_type_definition_id, edge_type, domain, description,
      allowed_left_object_types, allowed_right_object_types,
      is_bidirectional, created_ymdhis, created_by_actor_id)
     VALUES (:id, :type, :domain, :desc, :left, :right, :bidir, :ts, :actor)",
    [
        'id'     => $newId,
        'type'   => 'depends_on',
        'domain' => 'content',
        'desc'   => 'Source artifact depends on target artifact.',
        'left'   => '["artifact","file"]',
        'right'  => '["artifact","file"]',
        'bidir'  => 0,
        'ts'     => $now,
        'actor'  => $actorId,
    ]
);
```

## Doctrine Notes

- **No foreign keys.** `created_by_actor_id` logically references `lupo_actors.actor_id`, but no DB-level FK is created (see database-logic-prohibition-doctrine).
- **Timestamps:** `created_ymdhis` is BIGINT UTC YYYYMMDDHHIISS; never use `CURRENT_TIMESTAMP` or `ON UPDATE`.
- **Unique constraint on `edge_type`** ensures no duplicate type slugs; application code should handle conflicts explicitly.
- **`allowed_left_object_types` / `allowed_right_object_types`** are free-text fields (TEXT); application layer must parse and validate them (JSON array format recommended).
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_edge_type_definitions.toon`.
