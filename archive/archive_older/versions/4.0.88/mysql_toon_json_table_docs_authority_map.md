---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: authority_map
  when_updated: null
  file_path_from_root: "docs/versions/4.0.88/MYSQL_TOON_JSON_TABLE_DOCS_AUTHORITY_MAP.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.88/MYSQL_TOON_JSON_TABLE_DOCS_AUTHORITY_MAP.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: version_documentation
  artifact_kind: schema_and_docs_authority_map
  thread_id: "2007"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# MySQL, TOON, JSON, and Table Docs Authority Map (4.0.88)

## Authority Layers

### 1) Canonical schema authority

Authoritative source:
- `database/lupopedia/mysql/install/install_new_lupopedia.sql`

Rule:
1. This is the schema truth source for 4.0.x install structure.
2. Documentation should align to it, not override it.

### 2) Derived schema exports

Derived exports:
- `database/lupopedia/toon/`
- `database/lupopedia/json/`

Role:
1. These exports are generated from live DB schema state.
2. They support validation and regeneration workflows.

### 3) Human-readable semantic docs

Documentation surface:
- `docs/database/lupopedia/tables/active/`

Role:
1. Provide table-level human-readable context.
2. Link schema references to doctrine and usage surfaces.
3. Must stay aligned with install SQL plus derived exports.

## Why JSON Exports Are Important in Practice

In this workflow, JSON exports were operationally important because:
1. they were the reliable parse/input format for regeneration and verification tooling.
2. they avoided extension/format ambiguity encountered in script assumptions around `.toon` files.
3. they supported deterministic comparisons in execution and validation chain.

4.0.88 evidence:
1. DB reconciliation report identified extension/path mismatch in verifier and corrected processing via JSON path.
2. Phase 2 regeneration used TOON JSON as schema source under scope lock.

## Limitation: TOON/JSON Are Not Full Semantic Metadata

TOON and JSON exports capture schema structure well, but do not fully preserve:
1. historical attribution metadata.
2. rich `lupopedia.edges` relationships.
3. full narrative semantic context from prior docs.

Implication:
1. regeneration alone is not full restoration.
2. metadata and edge reconstruction require additional phases.

This is exactly why post-Phase 2 acceptance was conditional.

## LUPOPEDIA_HEADERS, MySQL, and File-Based Surfaces

LUPOPEDIA_HEADERS and `lupopedia.edges` operate at the documentation/coordination layer.

Relationship model:
1. MySQL install SQL defines schema structure authority.
2. TOON/JSON exports provide machine-readable derived schema snapshots.
3. table-doc files carry LUPOPEDIA_HEADERS and edges for provenance, routing, and semantic relationship context.
4. those header/edge semantics are not fully derivable from schema exports alone.

Practical consequence in 4.0.88:
1. regeneration from JSON rebuilt structural table documentation quickly.
2. additional phases were required for metadata restoration and edge reconstruction.
3. final closure remained conditional until those phases progressed.

## Operational Map

1. install SQL defines structural authority.
2. live DB materializes runtime tables.
3. TOON/JSON are derived snapshots from live DB.
4. table docs are human-readable semantic surfaces that must be continuously validated against authority sources.

## 4.0.88 Validation Chain (Short)

1. THOTH source validation established install SQL and TOON role.
2. THOTH DB reconciliation resolved false alarm and confirmed live alignment.
3. HEPHAESTUS executed controlled subset regeneration from JSON.
4. THOTH accepted subset conditionally and required follow-up for metadata/edge completeness and residual drift.
