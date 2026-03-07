# file: Lupopedia HELP HUB — session: L-LUPO-ANTIGRAVITY — delegation: antigravity:cursor:captain  — web_path: http://www.lupopedia.com/help
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "help"
  file_path_from_root: "docs/HELP.md"
  web_path: "http://www.lupopedia.com/help"
  last_modified_utc: "20260306"
  system_version: "4.0.63"
  channel_id: 42
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  artifact_type: "help"
  artifact_kind: "documentation"
  purpose: "User-friendly help hub for Lupopedia"
  mood_rgb: "4169E1"
  traits: ["help", "documentation", "hub", "v4.0.63"]
  tags: ["help", "documentation", "user_friendly", "cli", "web"]
  lupo_agent: "cursor"
---

# LUPOPEDIA HELP HUB

Welcome to the Lupopedia help system. This is your central resource for understanding and using Lupopedia.

## Documentation sections

### Getting started

| Resource | Description | Link |
|----------|-------------|------|
| README | Project overview and setup | [README.md](../README.md) |
| Version history | Releases and upgrade notes | [version.md](version.md) |
| Whoami & context | Identity and execution context | [lupopedia_whoami_readme.md](lupopedia_whoami_readme.md) |

### Identity and actors

| Resource | Description | Link |
|----------|-------------|------|
| Whoami | Identity and context (dual-identity) | [lupopedia_whoami_readme.md](lupopedia_whoami_readme.md) |
| Actor registry | Actors and IDs | [lupo-database/lupopedia/actors/actor_id/registry.json](../lupo-database/lupopedia/actors/actor_id/registry.json) |
| Dual identity | Human + agent model | [lupopedia_whoami_readme.md#4](lupopedia_whoami_readme.md#4) |
| Actors doc | Actor directories and config | [actors.md](actors.md) |
| Auth and actor context | Auth user + actor for Antigravity | [auth.md](auth.md) |
| **DOCTOR actor (1009)** | System health, diagnostics, repair | [lupo-agents/1009/](../lupo-agents/1009/) |
| **UTC_TIMEKEEPER (1212)** | Authoritative system time (kernel agent) | See "Querying UTC_TIMEKEEPER" below; aliases: [lupo-database/lupopedia/actors/actor_id/aliases.csv](../lupo-database/lupopedia/actors/actor_id/aliases.csv) |
| **Channel 0 / Actor 0 tasks** | Index of all tasks on channel_id 0 and actor_id 0 | [CHANNEL_0_ACTOR_0_TASKS.md](CHANNEL_0_ACTOR_0_TASKS.md) |

#### Querying UTC_TIMEKEEPER

To obtain the current UTC date and time (authoritative for Lupopedia timestamps):

**Via CLI (if ask command is implemented):**

```bash
php lupo-bin/lupo.php ask utc_timekeeper "What time is it?"
```

**Via PHP:**

```php
<?php
echo gmdate('Y-m-d H:i:s') . " UTC\n";
// Lupopedia stored format: gmdate('YmdHis')
?>
```

**Via SQL (MySQL):**

```sql
SELECT UTC_TIMESTAMP();
-- For YmdHis format: SELECT DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s');
```

**One-liner:**

```bash
php -r "echo gmdate('Y-m-d H:i:s') . \" UTC\n\";"
```

All timestamps in Lupopedia are UTC in `YmdHis` format (e.g. `20260307143045`). See [prompts/lilith/20260306_doctor_sql_queries.md](../prompts/lilith/20260306_doctor_sql_queries.md) for timestamp convention in health queries.

### CLI commands

| Command | Description | Documentation |
|---------|-------------|---------------|
| `php lupo-bin/lupo.php whoami` | Show current identity | [CLI.md#whoami](CLI.md#whoami) |
| `php lupo-bin/lupo.php context` | Full context (JSON) | [CLI.md#context](CLI.md#context) |
| `php lupo-bin/lupo.php help` | Help system | [CLI.md#help](CLI.md#help) |
| `php lupo-bin/lupo.php docs` | Show documentation path | [CLI.md#docs](CLI.md#docs) |
| `php lupo-bin/lupo.php version` | Show version | [CLI.md#version](CLI.md#version) |
| `php lupo-bin/lupo.php doctor` | System health check (via DOCTOR actor when present); see [DOCTOR_HEALTH_CHECK.md](DOCTOR_HEALTH_CHECK.md) | [CLI.md#doctor](CLI.md#doctor) |
| `php lupo-bin/lupo.php doctor-context [--repair]` | Identity stack check; `--repair` syncs session.md to kernel | [CLI.md#doctor](CLI.md#doctor) |
| `php lupo-bin/lupo.php auth` / `who` | Current authenticated user | [CLI.md#auth](CLI.md#auth) |
| `php lupo-bin/lupo.php actor-context` | Actor context with auth | [CLI.md#actor-context](CLI.md#actor-context) |

### Workspace and files

| Resource | Description | Link |
|----------|-------------|------|
| Workspace layout | Actor workspace path | [lupopedia_whoami_readme.md](lupopedia_whoami_readme.md) Section 6 |
| Actor directories | Per-actor workspaces | lupo-actors/ |

### Tasks

- Tasks are stored under channel directories: `lupo-database/lupopedia/channels/channel_id/{channel_id}/tasks/` or `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/tasks/`.
- Statuses: `pending`, `active`, `completed`, `blocked`, `failed`, `archived`. Each status has its own subdirectory (e.g. `tasks/active/`, `tasks/pending/`).
- To list tasks that are **not** pending: list files in `active/`, `completed/`, `blocked/`, `failed/`, and `archived/` (or run `find .../tasks/ -name "*.md" | grep -v "/pending/"`).
- Full reference: [TASK_STATUS_REFERENCE.md](TASK_STATUS_REFERENCE.md).

### Database and schema

| Resource | Description | Link |
|----------|-------------|------|
| Doctrine | DB rules | [docs/doctrine/](doctrine/) |
| TOON files | Table definitions | [docs/toons/](toons/) (if present) |
| Migrations | Schema changes | database/migrations/ |

### Web Interface

| Resource | Description | Link |
|----------|-------------|------|
| Web Auth & Actor Selection | How the web UI handles logins and actor switching | [WEB_AUTH_AND_ACTOR_SELECTION.md](WEB_AUTH_AND_ACTOR_SELECTION.md) |

### FLARE protocol

| Resource | Description | Link |
|----------|-------------|------|
| Required headers | Minimum FLARE headers | [doctrine/required_flare_headers.md](doctrine/required_flare_headers.md) |
| FLARE doctrine | Core protocol | [doctrine/FLARE/FLARE_DOCTRINE.md](doctrine/FLARE/FLARE_DOCTRINE.md) (if present) |

### Reports and status

| Resource | Description | Link |
|----------|-------------|------|
| Version history | All releases | [version.md](version.md) |
| Status reports | Current status | [status/](status/) |
| v4.0.61 thread review | Assessment and file-specific review of the version thread | [VERSION_4.0.61_THREAD_REVIEW.md](VERSION_4.0.61_THREAD_REVIEW.md) |
| v4.0.61 strategy & v4.0.62 roadmap | Strategic assessment, risks, and next steps (Context Doctor, Context Kernel) | [VERSION_4.0.61_STRATEGY.md](VERSION_4.0.61_STRATEGY.md) |
| Task status reference | Statuses (pending, active, completed, etc.), paths, and how to list/query tasks | [TASK_STATUS_REFERENCE.md](TASK_STATUS_REFERENCE.md) |
| Task docs verification (LILITH) | Final verification of task documentation system (v4.0.62) | [prompts/lilith/20260306_task_docs_verification.md](../prompts/lilith/20260306_task_docs_verification.md) |
| DOCTOR SQL queries (LILITH) | SQL to query session/actor health (same data DOCTOR uses) | [prompts/lilith/20260306_doctor_sql_queries.md](../prompts/lilith/20260306_doctor_sql_queries.md) |
| DOCTOR SQL final verification (LILITH) | Final verification of DOCTOR SQL queries doc (10/10 canonical) | [prompts/lilith/20260306_doctor_sql_final.md](../prompts/lilith/20260306_doctor_sql_final.md) |
| **DOCTOR health check (full reference)** | lupo_doctor_health_check: checks, options (--check-actors), paths, troubleshooting | [DOCTOR_HEALTH_CHECK.md](DOCTOR_HEALTH_CHECK.md) |

## Quick tips

### First-time users

1. Run `php lupo-bin/lupo.php doctor` to check your system.
2. Run `php lupo-bin/lupo.php whoami` to see your identity.
3. Run `php lupo-bin/lupo.php help` for the full menu.
4. Read [version.md](version.md) for upgrade notes.

### Developers

- Core code: `lupo-includes/`
- Services: `app/Services/` (or project equivalent)
- Tests: `tests/`

### Agents

- Workspace: `/lupo-actors/{actor_name}/`
- Use `use <actor_id>` to switch active actor.
- Session file: `lupo-database/session.md` when DB is unavailable.

## Getting help

- **CLI:** `php lupo-bin/lupo.php help` or `php lupo-bin/lupo.php help --web`
- **Web:** http://www.lupopedia.com/help
- **Version:** [docs/version.md](version.md)

---

**Last updated:** 2026-03-06  
**Version:** 4.0.62
