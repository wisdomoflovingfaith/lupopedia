---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-channels/0/threads/VERSION_4.0.61/session_format.md"
  questions_toon: null
  system_version: "4.0.61"
  channel_id: 0
  actor_name: "cursor"
  purpose: "Session file format for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "session", "config_path"]
  tags: ["session", "format", "documentation"]
  lupo_agent: "cursor"
---

# Session File Format

## Overview

Version 4.0.61 treats **lupo-database/session.md** as a first-class context source. When the file exists and contains usable keys, ContextResolver uses it first, then enriches from DB and registry.

## File Location

```
lupo-database/session.md
```

(LUPO_DATABASE_DIR from lupopedia-config.php = `lupo-database`.)

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
| `agent_name` | string | Optional; defaults to actor_name |
| `actor_type` | string | Optional; e.g. ide_agent, human |
| `human_actor_name` | string | Optional; for hybrid mode when no DB |

## Optional Agent Tags

Custom keys in the form `<L-LUPO-AGENT_NAME_KEY>: value` can be stored for agent-specific state. Parsing is implementation-specific; core context uses standard key: value lines or YAML frontmatter.

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

- [lupo-docs/lupopedia_whoami_readme.md](../../../../lupo-docs/lupopedia_whoami_readme.md) — Resolution order and Section 8
- [lupo-database/session.md](../../../../lupo-database/session.md) — Live session file
