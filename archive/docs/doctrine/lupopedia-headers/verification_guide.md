---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md"
  status: ""
  when_updated: "20260409132813"
  trust_tier: null
  questions_toon: null
  memory_toon: "memory/2026/04/M-lupopedia-headers-verification-20260409.toon"
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: "headers-verification-v3"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# LUPOPEDIA Verification Guide (v3)

## Verification source moved

In v3, verification truth is read from the memory file (`memory_key`), not from `lupopedia.footer` in YAML.

## Verification checklist

1. Confirm required v3 header pointer fields exist.
2. Confirm `channel_key` is valid from channel registry.
3. Confirm `memory_key` file exists.
4. Confirm memory file has:
   - `footer.last_verified`
   - `footer.verified_by`
   - `footer.verified_via`
   - any required review metadata for artifact type
5. Confirm stale threshold checks use memory `footer.last_verified`.

## Stale rule

An artifact is stale when the memory file lacks required verification fields or `footer.last_verified` is below configured freshness threshold.

## Legacy behavior

v2 files may still carry `lupopedia.footer` in YAML and are accepted with warning during migration.
