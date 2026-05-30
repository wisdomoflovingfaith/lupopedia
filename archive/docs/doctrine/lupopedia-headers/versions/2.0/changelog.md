---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/changelog.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/changelog.md"
  status: ""
  when_updated: "20260331190000"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: changelog
  artifact_kind: version_specific
  channel_key: null
  federation_node_id: 0
  thread_id: "headers-version-2.0-changelog"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: changelog
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS - Version 2.0 Changelog

## Added

### Core Header Fields
- `when_updated` - replaces `version_when_written` as canonical content update timestamp
- `federation_node_id` - explicit node identification (0=core, 1=current, 2+=external)
- `actor_id` - required actor identification for header attribution
- `actor_name` - human-readable actor name

### Footer Structure
- `verified_by.identity_type` - distinguishes "actor" vs "agent" verification
- `verified_by.actor_id` - canonical verifier identification
- `verified_by.agent_name_identity` - optional human-readable display name
- `verified_by.department_id_delta` - department scope override
- `verified_via.type` - verification surface ("faucet" or "direct")
- `verified_via.faucet_slug` - specific faucet used
- `next_action` - required list of 1-3 suggested next actions

### Documentation
- Versioned directory structure (`versions/2.0/`)
- Migration guide for 1.0 → 2.0
- Field matrix for version 2.0

## Changed

### Timestamp Semantics
- `when_updated`: logical content change time
- `last_modified_utc`: file system write time
- `last_verified`: verification day (YYYYMMDD, not full timestamp)

### Footer Timestamp Format
- Changed from `YYYYMMDDHHIISS` (14 digits) to `YYYYMMDD` (8 digits)
- Verification is now day-based, not timestamp-based

### Verification Attribution
- Replaced flat verifier fields with structured `verified_by` object
- Replaced flat verification surface with `verified_via` object

## Deprecated

### Removed Fields
- `version_when_written` - use `when_updated`
- `system_version` - no replacement
- `lupopedia.version` - no replacement
- `last_verified_system_version` - no replacement
- Flat verifier fields (`last_verified_by`, `last_verified_by_actor_id`) - use structured `verified_by`

## Migration Notes

For migration from version 1.0 to 2.0, see [migration_guide.md](migration_guide.md).

## Version Timeline

| Date | Event |
|------|-------|
| 2026-03-31 | Version 2.0 finalized |
| 2026-04-01 | Validators begin accepting 2.0 |
| 2026-04-15 | Validators begin warning on 1.0 |
| 2026-05-01 | 1.0 headers rejected |
