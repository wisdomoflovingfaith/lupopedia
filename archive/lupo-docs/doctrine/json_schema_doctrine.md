---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/JSON_SCHEMA_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/JSON_SCHEMA_DOCTRINE.md"
  status: "draft"
  when_updated: "20260409113315"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: schema
  channel_key: null
  federation_node_id: 0
  thread_id: "json-schema-doctrine"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# JSON Schema Doctrine — DRAFT

**Status:** DRAFT — Do NOT mark FINAL

---

## Version Registry

### `header_json_v1` — Header Companion JSON

**Location:** `lupo-docs/headers/{file_stem}.json`

**Purpose:** Machine-readable companion to each `.md` file's YAML frontmatter. Provides edge graph data for traversal without parsing YAML.

**Schema:**

```json
{
  "file_id": "string — filename with extension",
  "file_path": "string — path from project root",
  "last_updated": "string — 14-digit UTC (YYYYMMDDHHMMSS)",
  "memory_ref": "string — path to associated .toon file",
  "edges": {
    "outbound": [
      {"to": "string", "type": "string", "weight": "float 0.0–1.0"}
    ]
  },
  "tags": ["array of strings"],
  "schema_version": "header_json_v1",
  "footer": {
    "last_verified": "string — 14-digit UTC",
    "verified_by": {
      "actor_id": "integer",
      "agent_name_identity": "string"
    }
  }
}
```

**Optional fields** (carried forward from YAML when present):
- `prd_id` — integer PRD number
- `prd_slug` — string slug
- `status` — string (draft, approved, active, deprecated)
- `purpose` — string
- `thread_id` — string

**Generator:** `python lupo-scripts/generate_json_headers.py`

---

### `toon_v1` — Memory Node File (`.toon`)

**Location:** `lupo-memory/{YYYY}/{MM}/M-{slug}-{date}.toon`

**Purpose:** Graph node for memory traversal. Each node represents an event, document state, or transcript entry. Agents load context by traversing edges, not by reading raw transcripts.

**Schema:**

```json
{
  "id": "string — unique node ID, format: M-{slug}-{YYYYMMDD}",
  "type": "string — e.g. prd_memory, transcript_memory, constitutional_memory",
  "ts": "string — 17-digit UTC with milliseconds (YYYYMMDDHHMMSS.mmm)",
  "actor_id": "integer — who created this memory node",
  "summary": "string — one-line description (≤200 chars)",
  "edges": [
    {"to": "string", "type": "string", "weight": "float 0.0–1.0"}
  ],
  "content": {},
  "schema_version": "toon_v1",
  "status": "draft | active | archived"
}
```

**Edge `to` formats:**
- `FILE:{filename}` — references a file
- `ACTOR:{id}` — references an actor
- `CHANNEL:{path}` — belongs to a channel
- `TASK:{ref}` — references a task
- `{file_path}` — direct path reference

**Generator:** `python lupo-scripts/generate_json_headers.py` or `python lupo-scripts/migrate_transcript_to_memory.py`

---

## Versioning Rules

1. Schema version increments (`v1` → `v2`) when **structure changes** (fields added/removed/renamed)
2. Old schema versions remain readable — no breaking changes without migration path
3. Migration scripts live in `lupo-scripts/` and handle version upgrades
4. `schema_version` field is **required** in all JSON files covered by this doctrine

---

## Slug Naming Convention (Transcript Memory)

Transcript-derived toon IDs use deterministic slugs to prevent collisions:

```
M-{channel_key}-{slug_sanitized}-{ts_digits}
```

Where:
- `channel_key` — e.g. `development`
- `slug_sanitized` — channel slug with `/` → `_`, max 60 chars
- `ts_digits` — up to 17 digits from transcript entry `ts` field

**Example:** `M-development-prd_files_44_prd_discussion-20260409001808000`

---

## Validator

```bash
python lupo-scripts/validate_json_schema.py --schema header_json_v1 --dir lupo-docs/headers/
python lupo-scripts/validate_json_schema.py --schema toon_v1 --dir lupo-memory/
```

*(Validator script: TODO — create `lupo-scripts/validate_json_schema.py`)*

---

*DRAFT — Do NOT mark FINAL or COMPLETE*
