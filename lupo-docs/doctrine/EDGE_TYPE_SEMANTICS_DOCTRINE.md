---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/EDGE_TYPE_SEMANTICS_DOCTRINE.md"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  artifact_type: "doctrine"
  artifact_kind: "edge_semantics"
  purpose: "Edge type registry: canonical edge types, allowed object types, semantics. Schema from install; TOON lupo_edge_type_definitions."
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
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
