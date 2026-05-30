---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/federation_node_types_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/federation_node_types_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: federation
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: Federation Node Types Doctrine — web_path: http://www.lupopedia.com/doctrine/FEDERATION_NODE_TYPES_DOCTRINE

# Federation Node Types Doctrine (v4.0.69)

## 1. Schema (install / TOON)

Table: `lupo_federation_nodes`. Columns added for 4.0.69: `node_type` (varchar, default `'local'`), `allows_foreign_traits` (tinyint, default 1). Existing: `federation_node_id`, `node_base_url`, `node_name`, `node_description`, etc.

## 2. Node types

- **kernel** — Core/system node.
- **local** — Local deployment (default).
- **external** — Remote federation node.
- **development** — Dev/test.

Use `node_type` for routing and policy; no ENUM in schema (varchar).

## 3. Trait portability

When `allows_foreign_traits = 1`, traits from other federation nodes may be accepted (e.g. for joined actors). When 0, only local traits apply. Enforcement in application code (TraitEnforcer considers `federation_node_id`).

## 4. References

- Federation scoping: FEDERATION_SCOPING_DOCTRINE.md
- Traits: TRAITS_DOCTRINE.md
- TOON: `lupo_federation_nodes.toon`
