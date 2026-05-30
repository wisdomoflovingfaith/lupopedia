---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/cli_commands.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/cli_commands.md
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

# CLI Commands (v4.0.61)

## New Commands

| Command | Description |
|---------|-------------|
| `version` | Show Lupopedia version and path to docs/version.md |
| `doctor` | Health check: DB, registry file, session file |
| `docs` [topic] | Print path to docs/HELP.md or docs/<topic>.md |
| `auth` / `who` | Show current authenticated user (or "Not authenticated") |
| `actor-context` | Show actor name, id, type, paired, and auth status |

## Aliases

| Alias | Same as |
|-------|---------|
| `switch <actor_id>` | `use <actor_id>` |

## Help

- `help` — Main menu (categories, exit codes, tips)
- `help --quick` — Quick reference
- `help --web` — Open web help URL
- `help <topic>` — whoami, context, actors, workspace, flare, version, doctor, see, auth

## No-DB Commands

whoami, context, help, docs, version, doctor, auth, who, actor-context (session.md/registry/defaults used when needed).

## Reference

- [docs/CLI.md](../../../../../../../docs/CLI.md) — Full CLI reference
- docs/HELP.md — Help hub
