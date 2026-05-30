---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md"
  status: ""
  when_updated: "20260327121457"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: "VERSIONING_MODEL compatibility notice"
  summary: ""
---
# file: VERSIONING_MODEL (compatibility notice) — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERSIONING_MODEL.md)

# VERSIONING_MODEL

This document is retained only so historical links resolve to the current rule.

## Current rule

The canonical freshness fields in `lupopedia.headers` are:

- `when_updated`
- `file_path_from_root`
- `last_modified_utc`

Trust and revalidation state belongs in `lupopedia.footer.last_verified` and the verifier identity fields.

## Deprecated compatibility field

`version_when_written` is not a canonical write field.

- In 4.0.88 it may be read for compatibility and should trigger a warning.
- In 4.0.89 it should be rejected inside `lupopedia.headers`.

Also deprecated: `system_version`, `lupopedia.version`, `last_verified_system_version`, and standalone `version` keys inside `lupopedia.headers`.

See [LUPOPEDIA_HEADERS_FORMAT.md](./LUPOPEDIA_HEADERS_FORMAT.md) and [README.md](./README.md) for the active doctrine.
