---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md"
  status: ""
  when_updated: "20260409132813"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/lupopedia-headers-migration-v3.toon"
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: "headers-migration-v3"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS Migration (v2 -> v3)

## Migration goals

- Convert rich v2 headers into minimal v3 pointer headers.
- Split moved metadata into memory `.toon` files.
- Convert numeric `channel_id` into human `channel_key`.
- Add `trust_tier` and generate tier-aware `memory_key`.

## Required mapping

Use `lupo-channels/registry.json` for `channel_id` -> `channel_key` translation.

## Conversion steps

1. Parse existing header front matter.
2. Read `lupopedia.headers.channel_id`.
3. Resolve `channel_key` via registry mapping.
4. Build memory payload from moved blocks (`edges`, `footer`, tags, purpose, author, delegation fields).
5. Resolve trust tier (`seed` / `canonical` / `staging` / `archive`) from header or channel defaults.
6. Write memory `.toon` file to tier path.
7. Rewrite file header as v3 minimal pointer with `trust_tier` + `memory_key`.

## Compatibility policy

- v2 accepted with warning during migration window.
- v3 required for new and rewritten files.
- v1 rejected.
