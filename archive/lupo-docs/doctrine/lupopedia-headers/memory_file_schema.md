---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/MEMORY_FILE_SCHEMA.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/MEMORY_FILE_SCHEMA.md"
  status: ""
  when_updated: "20260409132813"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/lupopedia-headers-memory-schema.toon"
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: "headers-memory-schema-v3"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# Memory File Schema for Header Metadata (v3)

Memory file path comes from `lupopedia.headers.memory_key` and points to a `.toon` JSON file using trust-tier pathing.

## Required shape

```json
{
  "id": "file-slug",
  "type": "header_metadata",
  "schema_version": "toon_v1",
  "header_format_version": 3,
  "file_path_from_root": "path/from/repo/root.md",
  "channel_key": "development",
  "trust_tier": "canonical",
  "display_year": 1026,
  "actual_year": 2026,
  "month": 4,
  "edges": { "outbound": [] },
  "tags": [],
  "purpose": "One line purpose",
  "status": "draft",
  "author": { "type": "actor", "id": 102, "name": "cursor" },
  "delegation_chain": "cursor:root",
  "footer": {
    "last_verified": "20260409132813",
    "verified_by": {
      "identity_type": "actor",
      "actor_id": 102,
      "agent_name_identity": "Cursor IDE Agent"
    },
    "verified_via": { "type": "faucet", "faucet_slug": "cursor" },
    "orchestrator": "cursor:root",
    "next_action": []
  }
}
```

## Field notes

- `channel_key` mirrors v3 header routing identity.
- `trust_tier` must be one of `seed`, `canonical`, `staging`, `archive` (**PRD 16** §4.2 field 9).
- `display_year` carries tier-aware path year (`actual_year - 1000` for canonical).
- `edges`, `footer`, tags, purpose, author, and delegation fields live here in v3.
- Validators should treat this file as authoritative for rich metadata checks.
