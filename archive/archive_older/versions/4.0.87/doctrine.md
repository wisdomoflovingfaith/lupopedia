---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260325104751"
  file_path_from_root: "docs/versions/4.0.87/DOCTRINE.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/DOCTRINE.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: version_doctrine
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
# 4.0.87 DOCTRINE

## Non-Negotiables
- Database remains dumb storage: no foreign keys, no triggers, no stored procedures.
- CIP is deprecated in active architecture for 4.0.87 and must not be reintroduced in runtime, schema, or projection paths.
- ROSE is the canonical intelligence layer for synthesis, interpretation, and decision-support context.
- Intelligence boundary is mandatory:
  - DB = storage
  - EDGES = structure
  - ROSE = meaning
- Runtime remains compatible with minimum PHP baseline for project core.
- LUPOPEDIA HEADERS remain required for governed documentation surfaces.
- Identity model boundaries remain strict: actor != agent != faucet; auth_user mapping is explicit.
- Department execution scope is actor-centric: operational membership mapping is through `lupo_actor_departments`.
- All timestamps stay BIGINT UTC `YYYYMMDDHHIISS` format.
- Version scope must not introduce Lupopedia -> Lupopedia upgrade compatibility assumptions in 4.0.x.
- New channel directories are slug-first (`channels/<channel_slug>/`); numeric channel directories are legacy compatibility paths.

## Focus Constraints
- Changes in 4.0.87 must advance atoms/channels/headers/identity/admin-LLM objectives.
- If scope expands, additions must be recorded in `SCOPE_LOCK_SUMMARY.md` before implementation.
- Upgrade path doctrine remains fixed: new install and Crafty Syntax 3.7.5 import only.

