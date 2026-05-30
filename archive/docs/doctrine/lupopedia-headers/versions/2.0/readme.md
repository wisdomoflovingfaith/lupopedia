---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/LUPOPEDIA_HEADERS/versions/2.0/README.md"
  status: ""
  when_updated: "20260331190000"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: version_specification
  channel_key: null
  federation_node_id: 0
  thread_id: "headers-version-2.0"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# LUPOPEDIA HEADERS - Version 2.0

## Overview

Header Format Version 2.0 is the canonical header format for Lupopedia 4.0.93 and later. This version introduced:

- **Single-field versioning**: `when_updated` replaces `version_when_written`
- **Federation awareness**: `federation_node_id` for cross-node artifacts
- **Verification attribution**: Structured `verified_by` and `verified_via` fields
- **Timestamp separation**: `when_updated` (content) vs `last_modified_utc` (file) vs `last_verified` (trust)

## Key Features

### Core Header Fields

| Field | Type | Description |
|-------|------|-------------|
| `header_format_version` | integer | Always 2 for this version |
| `when_updated` | string (quoted) | UTC YYYYMMDDHHIISS - logical content update |
| `last_modified_utc` | string (quoted) | UTC YYYYMMDDHHIISS - file write time |
| `federation_node_id` | integer | 0=core, 1=current install, 2+=external |
| `actor_id` | integer | Actor ID (may be agent) |
| `actor_name` | string | Human-readable actor name |

### Footer Fields

| Field | Type | Description |
|-------|------|-------------|
| `last_verified` | string | YYYYMMDD (8 digits) - verification day |
| `verified_by.identity_type` | string | "actor" or "agent" |
| `verified_by.actor_id` | integer | Verifier actor ID |
| `verified_via.type` | string | "faucet" or "direct" |
| `verified_via.faucet_slug` | string | Faucet identifier or "none" |
| `next_action` | list | 1-3 suggested next actions |

## Version 2.0 vs 1.0

| Aspect | 1.0 (deprecated) | 2.0 (current) |
|--------|------------------|---------------|
| Version field | `version_when_written` | `when_updated` |
| File modification | `last_modified_utc` | `last_modified_utc` (same) |
| Federation | Implied by web_path | Explicit `federation_node_id` |
| Verification | Flat verifier fields | Structured `verified_by`/`verified_via` |
| Actor attribution | Optional | Required (`actor_id`, `actor_name`) |
| Footer timestamp | YYYYMMDDHHIISS | YYYYMMDD (8 digits) |

## Compatibility

- **Forward**: Version 2.0 headers are the current standard
- **Backward**: Validators may read version 1.0 headers but will warn
- **Migration**: See [migration_guide.md](migration_guide.md)

## Version Lifecycle

| Version | Status | Support Ends |
|---------|--------|--------------|
| 1.0 | Deprecated | 4.1.0 |
| 2.0 | Current | TBD |
| 3.0 | Planned | TBD |

---

**Version**: 2.0
**Status**: ACTIVE
**Constitutional Adherence**: FULL
