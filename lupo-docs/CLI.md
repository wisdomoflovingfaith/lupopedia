---
# FLARE Header
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/CLI.md"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  purpose: "CLI reference for Lupopedia lupo commands"
  traits: ["cli", "reference", "v4.0.62"]
  tags: ["cli", "lupo", "help", "whoami", "context"]
---

# Lupopedia CLI reference

**Version:** 4.0.62

## Overview

The Lupopedia CLI is invoked as:

```bash
php lupo-bin/lupo.php <command> [args]
```

## Commands

### whoami

Show current identity context (dual-identity: human, active agent, session mode).

**Options:**

- `--verbose` — Full context as JSON (same as `context`)

**Example:**

```text
$ php lupo-bin/lupo.php whoami
Human Identity: captain (10000)
Active Agent: cursor (1003)

Session Mode: hybrid
Actor Type: ide_agent

Department: 1
Channel: 42
Thread: 0
Federation Node: 0

Workspace:
/lupo-actors/cursor/

Session:
sess_a82f9c1b

Context Source:
session.md + registry
```

### context

Show full execution context as a single flat JSON object.

**Example:**

```text
$ php lupo-bin/lupo.php context
{"actor_name":"cursor","actor_id":1003,"human_actor_name":"captain","human_actor_id":10000,...}
```

### help

Show the help system.

**Subcommands:**

- `php lupo-bin/lupo.php help` — Main help menu
- `php lupo-bin/lupo.php help --quick` — Quick reference card
- `php lupo-bin/lupo.php help --web` — Open web help in browser
- `php lupo-bin/lupo.php help <topic>` — Topic-specific help (whoami, context, actors, workspace, flare, version)

### docs

Show path to documentation hub or a specific topic.

- `php lupo-bin/lupo.php docs` — Path to lupo-docs/HELP.md
- `php lupo-bin/lupo.php docs <topic>` — Path to lupo-docs/<topic>.md if it exists

### version

Show version information and link to version history.

**Example:**

```text
$ php lupo-bin/lupo.php version
Lupopedia version 4.0.61
Documentation: lupo-docs/version.md
```

### doctor

Run system health checks. When `lupo-agents/1009/doctor.php` exists, the DOCTOR actor (1009) runs; otherwise the built-in **lupo_doctor_health_check()** runs.

**Checks (built-in):**

- Database connectivity
- Registry file: `{LUPO_DATABASE_DIR}/lupopedia/actors/registry.json` (exists, readable)
- Session file: `{LUPO_DATABASE_DIR}/session.md` (optional for CLI fallback)
- Context kernel: identity drift (split-brain, pairing) via ContextKernel::validate()
- Optional: actor workspace/namespace consistency with `--check-actors`

**Options:**

- `--check-actors` — Run DoctorService::checkActors(): ensure each actor in `lupo_actors` has a valid workspace_path and (for agents) php_namespace; report missing dirs or missing namespace.

**Output:** `[OK]`, `[WARN]`, `[FAIL]`, or `[SKIP]` per check; summary line at end. If context kernel reports issues, suggests running `doctor-context [--repair]`.

**Full reference:** [lupo-docs/DOCTOR_HEALTH_CHECK.md](DOCTOR_HEALTH_CHECK.md)

### doctor-context

Validate session and identity state using the ContextKernel. Surfacing identity drift or split-brain session conflicts.

**Options:**

- `--repair` — Synchronize `session.md` metadata with the canonical lupo-database/kernel identity if a conflict or drift is detected. A backup is created automatically as `session.md.bak.YmdHis` (e.g. `session.md.bak.20260307143045`) before overwriting.

### auth / who

Show current authenticated user (from session when in web context; CLI often shows "Not authenticated"). Used by Antigravity for conflict resolution.

### actor-context

Show full actor context with auth status (actor name, id, type, paired_actor_id, auth status). For Antigravity and tooling. See lupo-docs/auth.md.

### actors

List registered actors. Optional: `actors [type]` to filter by type.

### use \<actor_id\> / switch \<actor_id\>

Switch local identity to an existing actor (writes `.lupo_actor`). `switch` is an alias for `use`.

### register \<name\> \<type\>

Register this environment as an actor.

### channels

List available channels.

### threads \<channel_id\>

List threads in a channel.

### join \<channel_id\>

Join a channel.

### messages \<channel_id\> [thread_id]

List last 20 messages in a channel (optional thread).

### send \<channel_id\> \<msg\> [thread_id]

Send a message to a channel/thread.

### nodes

List federation nodes.

### artifacts \<node_id\>

List artifacts by federation node.

### tasks

List your active tasks.

### see \<url\>

Resolve canonical URL to repo .md file.

## Context source

Context is resolved in this order:

1. **session.md** — `lupo-database/session.md` (first-class when present)
2. **lupo_sessions** — Database session table (when DB available)
3. **Registry / defaults** — Actor registry and system defaults

Reported as: `session.md`, `session.md + registry`, `lupo_sessions`, or `default`.

## Identity Sources

- **CLI:** Identity is determined by local files, primarily `session.md` or `.lupo_actor`.
- **Web:** Identity is established through a user login and is managed by a session. The web interface also allows users to switch between different actors.

While the sources are different, both methods ultimately resolve to an actor in the `lupo_actors` table and are recorded in the `lupo_sessions` table, ensuring a consistent identity across the system.

For more details on the web authentication flow, see [WEB_AUTH_AND_ACTOR_SELECTION.md](WEB_AUTH_AND_ACTOR_SELECTION.md).

## Exit codes

| Code | Meaning |
|------|---------|
| 0 | Success |
| 1 | General error (e.g. invalid command, missing config) |
| 2 | Invalid or unknown command |
| 3 | Permission denied |
| 4 | Database error |
| 5 | Configuration error |

Exit codes are advisory; not all code paths set a non-zero exit code.

## More help

- Full help hub: [lupo-docs/HELP.md](HELP.md)
- Whoami and context: [lupopedia_whoami_readme.md](lupopedia_whoami_readme.md)
- Version history: [version.md](version.md)
