---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
  status: ""
  when_updated: "20260409132813"
  trust_tier: null
  questions_toon: null
  memory_toon: "memory/2026/04/M-lupopedia-headers-optional-blocks-20260409.toon"
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: "headers-optional-v3"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS — Optional Blocks (v3)

## Core rule

`lupopedia.edges` is never part of the header block. It must only appear as a trailing block at the bottom of the file, after the main content, if present. The header is for metadata only.

v3 headers are minimal pointer headers. Most optional metadata blocks previously in YAML are now stored in the memory `.toon` file pointed to by `memory_key`.

## Moved to memory

These blocks are marked **MOVED TO MEMORY** in v3:

- `lupopedia.edges`
- `lupopedia.footer`
- `lupopedia.next_actions`
- `lupopedia.actor_references`
- `lupopedia.engagement`

## Optional blocks still allowed in file YAML

- `lupopedia.routing`
- `lupopedia.lists`
- `lupopedia.metadata`

## Routing note

For v3 routing payloads, prefer `channel_key` over numeric `channel_id`. Legacy files may still carry `channel_id` during migration.

## Validator compatibility

- v1 headers: reject.
- v2 headers: accept with warning.
- v3 headers: required for new/rewritten files.
