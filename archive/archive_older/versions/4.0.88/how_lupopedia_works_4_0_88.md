---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: system_guide
  when_updated: null
  file_path_from_root: "docs/versions/4.0.88/HOW_LUPOPEDIA_WORKS_4_0_88.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.88/HOW_LUPOPEDIA_WORKS_4_0_88.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: version_documentation
  artifact_kind: system_orientation
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
# How Lupopedia Works in 4.0.88

## 1) Core Model

Lupopedia is a hybrid system:
1. MySQL provides structured runtime authority.
2. filesystem artifacts provide coordination, documentation, and review-visible traces.

Both are required to understand ongoing work.

## 2) Runtime vs Coordination Surfaces

Runtime-heavy surfaces:
1. `includes/`
2. `database/lupopedia/mysql/`
3. `app/` and app/runtime code surfaces used by loader/bootstrap flow

Coordination-heavy surfaces:
1. `channels/`
2. `docs/`
3. `database/sessions/`
4. `sessions/`

## 3) Schema and Docs Relationship

1. Install SQL is canonical schema authority:
   - `database/lupopedia/mysql/install/install_new_lupopedia.sql`
2. TOON and JSON are derived exports:
   - `database/lupopedia/toon/`
   - `database/lupopedia/json/`
3. Table docs are human-readable semantic/reference surfaces:
   - `docs/database/lupopedia/tables/active/`

Practical note from 4.0.88 thread execution:
1. JSON exports were used as reliable tooling input in the regeneration/verification chain.
2. TOON/JSON do not fully capture historical metadata and rich edge semantics.

## 4) Channels and Threads

Channels are not only conceptual. They are active execution surfaces.

Pattern used in 4.0.88:
1. directives, reports, validations, and stage transitions were posted to Channel 42 Thread 2007.
2. thread index was used as canonical ledger for state.
3. version docs are expected to reflect those artifacts.

## 5) Context Layer (Current State)

Intent:
1. contexts should refine task/question/artifact grouping above channel flow.

Current reality:
1. `context/` exists.
2. implementation is incomplete and not yet mature across workflows.

## 6) Why File-Visible Artifacts Matter

For IDE agents and humans, file-visible artifacts provide:
1. auditable chronology.
2. deterministic cross-linking.
3. shared truth for handoff and checkpointing.
4. grounded claims instead of memory-based summaries.

## 7) Where To Start Reading

1. `README.md`
2. `ORGANIZATION.md`
3. `docs/ORGANIZATION.md`
4. `docs/versions/4.0.88/README.md`
5. `docs/versions/4.0.88/THREAD_2007_WORK_SUMMARY.md`
6. `docs/versions/4.0.88/MYSQL_TOON_JSON_TABLE_DOCS_AUTHORITY_MAP.md`
7. `docs/versions/4.0.88/CORRUPTION_INCIDENT_AND_REMEDIATION_STATUS.md`
