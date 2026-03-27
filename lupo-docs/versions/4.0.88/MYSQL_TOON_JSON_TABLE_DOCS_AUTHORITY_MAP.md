---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "authority_map"
  file_path_from_root: "lupo-docs/versions/4.0.88/MYSQL_TOON_JSON_TABLE_DOCS_AUTHORITY_MAP.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/MYSQL_TOON_JSON_TABLE_DOCS_AUTHORITY_MAP.md"
  last_modified_utc: "20260327"
  channel_id: 42
  thread_id: "2007"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "version_documentation"
  artifact_kind: "schema_and_docs_authority_map"
  purpose: "Clarify install SQL, TOON, JSON, and table-doc authority relations for 4.0.88"
  tags: ["4.0.88", "mysql", "toon", "json", "table_docs", "authority"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_authority", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/", type: "derived_export", weight: 1.0 }
    - { to: "lupo-database/lupopedia/json/", type: "derived_export", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/", type: "documentation_surface", weight: 1.0 }
    - { to: "lupo-channels/42/threads/2007/20260327_220000_thoth_semantic_truth_validation_regeneration_source.md", type: "validation_reference", weight: 1.0 }
    - { to: "lupo-channels/42/threads/2007/20260327_230000_thoth_database_instance_reconciliation_report.md", type: "validation_reference", weight: 1.0 }
    - { to: "lupo-channels/42/threads/2007/20260327_235700_hephaestus_phase2_regeneration_manifest.md", type: "execution_reference", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260327"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
---

# MySQL, TOON, JSON, and Table Docs Authority Map (4.0.88)

## Authority Layers

### 1) Canonical schema authority

Authoritative source:
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`

Rule:
1. This is the schema truth source for 4.0.x install structure.
2. Documentation should align to it, not override it.

### 2) Derived schema exports

Derived exports:
- `lupo-database/lupopedia/toon/`
- `lupo-database/lupopedia/json/`

Role:
1. These exports are generated from live DB schema state.
2. They support validation and regeneration workflows.

### 3) Human-readable semantic docs

Documentation surface:
- `lupo-docs/database/lupopedia/tables/active/`

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
