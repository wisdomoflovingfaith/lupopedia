---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/session_format.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/session_format.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# Session File Format

## Overview

Version 4.0.61 treats the session file as a first-class context source. When it exists and contains usable keys, ContextResolver uses it first, then enriches from DB and registry.

**Config:** `LUPO_DATABASE_DIR` in lupopedia-config.php points to database/lupopedia root; session file is typically at project root under `database/session.md` or equivalent.

## File Location

Session file used by ContextResolver: `database/session.md` (relative to project root).

## Core Fields

| Field | Type | Description |
|-------|------|-------------|
| `actor_name` | string | Primary actor identifier |
| `actor_id` | integer | Numeric actor ID |
| `paired_actor_id` | integer | Human actor ID when agent is paired |
| `channel_id` | integer | Current channel context |
| `federation_node_id` | integer | Federation node ID |
| `session_id` | string | Unique session identifier |
| `department_id` | integer | Optional dialog header |
| `thread_id` | integer | Optional dialog header |
| `agent_name` | string | Optional |
| `actor_type` | string | Optional; e.g. ide_agent, human |
| `human_actor_name` | string | Optional; for hybrid mode when no DB |

## Example (YAML frontmatter)

```yaml
---
actor_name: cursor
channel_id: 42
federation_node_id: 0
session_id: sess_cli_fallback
actor_id: 1003
department_id: 1
thread_id: 0
agent_name: cursor
actor_type: ide_agent
human_actor_name: captain
paired_actor_id: 10000
---
```

## Usage

- **CLI whoami/context:** When DB is unavailable or session.md is present with data, ContextResolver reads this file first.
- **context_source** in output: `session.md` or `session.md + registry` when session file was used.

## Documentation

- [docs/lupopedia_whoami_readme.md](../../../../../../../docs/lupopedia_whoami_readme.md) — Resolution order and Section 8
- Session file: [database/session.md](../../../../../../session.md)
