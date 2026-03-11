---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/FEDERATION_NODE_TYPES_DOCTRINE.md"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  artifact_type: "doctrine"
  artifact_kind: "federation"
  purpose: "Federation node types and trait portability. Schema from install/TOON."
lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "wolfie"
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
