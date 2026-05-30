---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/taxonomy_reference.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/taxonomy_reference.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: memory/2026/04/M-lupopedia-headers-taxonomy-20260409.toon
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: headers-taxonomy-v3
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# LUPOPEDIA HEADERS — Taxonomy Reference (v3)

## Authority

If this file disagrees with `rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`, the root doctrine wins.

## Cross-field taxonomy

| `lupopedia.schema` | `artifact_type` | `artifact_kind` |
|---|---|---|
| doctrine | doctrine | constitutional \| reference \| decisions |
| rule | rule | rule |
| plan | plan | plan |
| changelog | changelog | version_specific |
| implementation | implementation | code \| documentation |
| thread | discussion | thread \| message |
| alias | documentation | documentation |

## v3 header implications

- `channel_id` is replaced by `channel_key` (string slug).
- `memory_key` is required.
- Detailed metadata previously held in header blocks is now located in the memory file.

## Conditional fields in v3

| `artifact_type` | Additional required fields |
|---|---|
| prd | `prd_id`, `prd_slug`, `title`, `status` |
| implementation | `parent_prd`, `status` (+ `version` for doc kind) |
| discussion | `channel_key`, `thread_id` |

## Deprecated / moved in v3

- Header blocks `lupopedia.edges` and `lupopedia.footer` are moved to memory metadata (`memory_key` target).
- Author attribution fields and tags are moved to memory metadata.

## Related docs

- `LUPOPEDIA_HEADERS_FORMAT.md`
- `OPTIONAL_BLOCKS.md`
- `MEMORY_FILE_SCHEMA.md`
