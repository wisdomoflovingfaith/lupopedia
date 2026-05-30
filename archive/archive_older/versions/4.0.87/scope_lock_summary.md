---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260325104751"
  file_path_from_root: "docs/versions/4.0.87/SCOPE_LOCK_SUMMARY.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/SCOPE_LOCK_SUMMARY.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: governance
  artifact_kind: scope_lock
  thread_id: "4.0.87-init"
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
# 4.0.87 SCOPE LOCK SUMMARY

## In Scope
- Atoms and version propagation
- Channels behavior and docs
- LUPOPEDIA HEADERS (`lupopedia.init`, `lupopedia.edges`, `lupopedia.footer`)
- Actor/agent/auth_user/department/faucet docs + implementation clarity
- Admin web LLM chatbot calls from `localhost/lupopedia/admin.php`
- Channel stream for `*` folder organization, deprecated artifact cleanup, and `docs` accuracy reconciliation
- Channel 63 stream for table/edge documentation under `docs/database/lupopedia/tables`
- Channel 64 stream for edge lifecycle governance and `lupopedia.edges` generation/population rules
- Actor-centric department mapping corrections in table-optimization channel artifacts
- Slug-first channel directory policy documentation (new channels by slug, legacy numeric compatibility retained)

## Upgrade Doctrine Constraint
- 4.0.87 does not include Lupopedia -> Lupopedia upgrade path compatibility work.
- Supported operational paths remain fresh install and Crafty Syntax import/upgrade.

## Out of Scope (Unless Explicitly Added)
- Non-critical UI redesign outside admin LLM/chat surfaces
- Unrelated schema expansions not required for 4.0.87 objectives

