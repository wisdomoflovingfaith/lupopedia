---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/header_versioning.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/header_versioning.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/2026/04/header-versioning.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/16_lupopedia_headers
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: Header Format Versioning Policy
  summary: ''
---
# Header Versioning

## Policy

- Header format version is a string in the `4.0.x` family.
- Major/minor (`4.0`) stays aligned with the current platform family.
- Patch can drift independently (`4.0.96`, `4.0.97`, `4.0.98`, ...).

## Validator Contract

- Accept: `^4\.0\.\d+$`
- Legacy accepted during migration: `3`
- Reject: `4.0`, `4.1.0`, `5.0.0`

## Write Default

- New headers should use `header_format_version: "4.0.98"` until the next header-schema patch.
- Existing headers at `3` can remain unchanged while migration proceeds.

