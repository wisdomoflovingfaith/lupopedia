---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/EDGE_TYPE_SEMANTICS_DOCTRINE.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: edge_semantics
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Edge Type Semantics Doctrine — web_path: http://www.lupopedia.com/doctrine/EDGE_TYPE_SEMANTICS_DOCTRINE

# Edge Type Semantics Doctrine (v4.0.69)

## 1. Purpose

`lupo_edge_type_definitions` is the **canonical registry** of edge types and their semantics. It enables validation of `lupo_edges` and `lupo_actor_edges` (allowed left/right object types, domain, bidirectional).

## 2. Schema (install / TOON)

Table: `lupo_edge_type_definitions`. Columns: `edge_type_definition_id`, `edge_type` (UNIQUE), `domain`, `description`, `allowed_left_object_types` (text/JSON), `allowed_right_object_types` (text/JSON), `is_bidirectional` (tinyint), `semantic_meaning`, `created_ymdhis`, `created_by_actor_id`. No FK. IDs from application.

## 3. Core edge types (seed)

Seed file `seed_traits_edge_types_action_auth_4.0.69.sql` inserts: REFERENCES, HAS_CONTENT, HAS_MEMBER, DELEGATES_TO, MENTIONS. See EDGE_VOCABULARY_DOCTRINE.md for allowed object_type pairs.

## 4. References

- Edge vocabulary: EDGE_VOCABULARY_DOCTRINE.md
- Install: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOON: `lupo-database/lupopedia/toon/lupo_edge_type_definitions.toon`
