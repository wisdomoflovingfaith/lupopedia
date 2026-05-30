---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/lupopedia_headers_plan.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/lupopedia_headers_plan.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/lupopedia-headers-plan-v3.toon
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: plan
  channel_key: development
  federation_node_id: 0
  thread_key: headers-plan-v3
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# LUPOPEDIA HEADERS Plan (v3)

## Objective

Move from v2 rich YAML headers to v3 minimal pointer headers with memory-associated metadata.

## Dependency-based phases

1. Define v3 pointer header schema (`channel_key`, `trust_tier`, `memory_key`, format version 3).
2. Define memory metadata schema (`MEMORY_FILE_SCHEMA.md`).
3. Add migration tooling (`migrate_headers_v2_to_v3.py`) with channel registry mapping.
4. Update validators to enforce v3 and deprecate v2.
5. Execute migration in batches.

## Compatibility window

- v2 accepted with warnings until 4.1.0.
- At 4.1.0 and later, v2 is rejected for new edits; v3 is required.

## channel_id migration

- `channel_id` is replaced by `channel_key`.
- Mapping source: `channels/registry.json`.
- `trust_tier` controls memory path segment and year display transform.
