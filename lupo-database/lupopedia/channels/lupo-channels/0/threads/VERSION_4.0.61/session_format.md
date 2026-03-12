---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/session_format.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  actor_name: "cursor"
  purpose: "Session file format for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "session"]
  tags: ["session", "format", "documentation"]
  lupo_agent: "cursor"
---

# Session File Format

## Overview

Version 4.0.61 treats the session file as a first-class context source. When it exists and contains usable keys, ContextResolver uses it first, then enriches from DB and registry.

**Config:** `LUPO_DATABASE_DIR` in lupopedia-config.php points to database/lupopedia root; session file is typically at project root under `lupo-database/session.md` or equivalent.

## File Location

Session file used by ContextResolver: `lupo-database/session.md` (relative to project root).

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
- Session file: [lupo-database/session.md](../../../../../../session.md)
