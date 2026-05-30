---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-channels/0/threads/VERSION_4.0.61/cli_commands.md"
  questions_toon: null
  system_version: "4.0.61"
  channel_id: 0
  purpose: "New and updated CLI commands for v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "cli", "config_path"]
  tags: ["cli", "commands", "implementation"]
---

# CLI Commands (v4.0.61)

## New Commands

| Command | Description |
|---------|-------------|
| `version` | Show Lupopedia version and path to lupo-docs/version.md |
| `doctor` | Health check: DB, registry file, session file |
| `docs` [topic] | Print path to lupo-docs/HELP.md or lupo-docs/<topic>.md |
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

These run without a database connection (session.md/registry/defaults used where needed):

whoami, context, help, docs, version, doctor, auth, who, actor-context

## Reference

- [lupo-docs/CLI.md](../../../../lupo-docs/CLI.md) — Full CLI reference
- lupo-docs/HELP.md — Help hub
