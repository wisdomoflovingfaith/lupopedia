---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/lupopedia-headers/verification_guide.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/lupopedia-headers/verification_guide.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: memory/2026/04/M-lupopedia-headers-verification-20260409.toon
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: headers-verification-v3
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
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
