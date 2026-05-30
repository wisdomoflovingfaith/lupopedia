---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/HELP.md"
  web_path: "http://www.lupopedia.com/help"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: help
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: help
  title: ""
  summary: ""
---
# LUPOPEDIA HELP HUB

Welcome to the Lupopedia help system. This is your central resource for understanding and using Lupopedia.

## Documentation sections

### Getting started

| Resource | Description | Link |
|----------|-------------|------|
| README | Project overview and setup | [README.md](../../README.md) |
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
| **DOCTOR actor (1009)** | System health, diagnostics, repair | lupo-agents/1009/ |
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

All timestamps in Lupopedia are UTC in `YmdHis` format (e.g. `20260307143045`). See [lupo-prompts/lilith/20260306_doctor_sql_queries.md](../lupo-prompts/lilith/20260306_doctor_sql_queries.md) for timestamp convention in health queries.

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
| `php lupo-bin/lupo.php rules --check [target_table] [target_id]` | List rules for a target (e.g. channels 42) | [lupo-docs/doctrine/RULES_DOCTRINE.md](../lupo-docs/doctrine/RULES_DOCTRINE.md) |
| `php lupo-bin/lupo.php rules --evaluate [target_table] [target_id] [context_json]` | Evaluate rules for a target | [lupo-docs/doctrine/RULES_DOCTRINE.md](../lupo-docs/doctrine/RULES_DOCTRINE.md) |
| `php lupo-bin/lupo.php skills --actor [actor_id]` | List skills for an actor (default actor 1) | [lupo-docs/doctrine/SKILLS_DOCTRINE.md](../lupo-docs/doctrine/SKILLS_DOCTRINE.md) |
| `php lupo-bin/lupo.php skills --check [actor_id] <skill_name> [min_proficiency]` | Check if actor has a skill at or above proficiency | [lupo-docs/doctrine/SKILLS_DOCTRINE.md](../lupo-docs/doctrine/SKILLS_DOCTRINE.md) |

### Rules system

Lupopedia includes a doctrine-aligned rules system for governance, permissions, and constraints. Rules are stored in `lupo_rules` and attached to nodes (channels, actors, departments) via `lupo_rule_targets`; evaluation is logged in `lupo_rule_logs`.

- **Documentation:** [lupo-docs/doctrine/RULES_DOCTRINE.md](../lupo-docs/doctrine/RULES_DOCTRINE.md)
- **Channel 42 rules:** [lupo-channels/42/content/federation_node_id/0/RULES.md](../lupo-channels/42/content/federation_node_id/0/RULES.md)

### Skills system

Actors can possess skills documented in `lupo-skills/` and attached via the `lupopedia.skills` header (in profile or `skills/*.md`). SkillService resolves skills from the actor directory given by registry `dir` (e.g. `lupo-actors/1/` for WOLFIE).

- **Documentation:** [lupo-docs/doctrine/SKILLS_DOCTRINE.md](../lupo-docs/doctrine/SKILLS_DOCTRINE.md)
- **Skills directory:** [lupo-skills/](../lupo-skills/)
- **Example:** WOLFIE (actor 1) has the "lupopedia-headers" skill at master level; see [lupo-actors/1/skills/lupopedia-headers.md](../lupo-actors/1/skills/lupopedia-headers.md).

### Communication system

All communication in Lupopedia — live chat, channel discussions, version threads — uses the `lupo_dialog_*` tables (with table prefix):

- **`lupo_dialog_channels`** — Channel metadata
- **`lupo_dialog_threads`** — Conversation threads
- **`lupo_dialog_messages`** — Individual messages

**Channel 42 discussions** are stored in these tables with `channel_id=42`. File-based threads under `lupo-channels/42/threads/` can be migrated to the database via `lupo-scripts/migrate_channel42_threads_to_db.php`.

- **Documentation:** [lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md](../lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md)

**CLI commands:**

```bash
# List threads in channel 42
php lupo-bin/lupo.php threads 42

# View messages from channel 42
php lupo-bin/lupo.php messages 42

# Send a message to channel 42
php lupo-bin/lupo.php send 42 "Hello world"
```

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
| Doctrine | DB rules | [lupo-docs/doctrine/](doctrine/) |
| **TOONs** | Database structure representation (tables, columns, indexes); where they are and how they are generated | [TOON_REFERENCE.md](TOON_REFERENCE.md) |
| TOON files (JSON) | One JSON per table | [lupo-database/lupopedia/json/](../lupo-database/lupopedia/json/) (`.json`) |
| TOON files (TOON) | Same content, TOON format | [lupo-database/lupopedia/toon/](../lupo-database/lupopedia/toon/) (`.toon`) |
| Migrations | Schema changes | lupo-database/migrations/ |

### Web Interface

| Resource | Description | Link |
|----------|-------------|------|
| Web Auth & Actor Selection | How the web UI handles logins and actor switching | WEB_AUTH_AND_ACTOR_SELECTION.md |

### LUPOPEDIA HEADERS protocol

| Resource | Description | Link |
|----------|-------------|------|
| LUPOPEDIA HEADERS (overview) | Canonical metadata system; storage in `lupo_metadata`; headers can be written to the file | [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](../lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) |
| Format and file structure | Markdown structure, required fields, database and channel resolution | [LUPOPEDIA_HEADERS_FORMAT.md](../lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) |
| Validators and tooling | How headers work with the database (read/write) and writing headers to files | [VALIDATORS_AND_TOOLING.md](../lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) |

### Reports and status

| Resource | Description | Link |
|----------|-------------|------|
| Version history | All releases | [version.md](version.md) |
| Status reports | Current status | [status/](status/) |
| v4.0.61 thread review | Assessment and file-specific review of the version thread | [VERSION_4.0.61_THREAD_REVIEW.md](VERSION_4.0.61_THREAD_REVIEW.md) |
| v4.0.61 strategy & v4.0.62 roadmap | Strategic assessment, risks, and next steps (Context Doctor, Context Kernel) | [VERSION_4.0.61_STRATEGY.md](VERSION_4.0.61_STRATEGY.md) |
| Task status reference | Statuses (pending, active, completed, etc.), paths, and how to list/query tasks | [TASK_STATUS_REFERENCE.md](TASK_STATUS_REFERENCE.md) |
| Task docs verification (LILITH) | Final verification of task documentation system (v4.0.62) | [lupo-prompts/lilith/20260306_task_docs_verification.md](../lupo-prompts/lilith/20260306_task_docs_verification.md) |
| DOCTOR SQL queries (LILITH) | SQL to query session/actor health (same data DOCTOR uses) | [lupo-prompts/lilith/20260306_doctor_sql_queries.md](../lupo-prompts/lilith/20260306_doctor_sql_queries.md) |
| DOCTOR SQL final verification (LILITH) | Final verification of DOCTOR SQL queries doc (10/10 canonical) | [lupo-prompts/lilith/20260306_doctor_sql_final.md](../lupo-prompts/lilith/20260306_doctor_sql_final.md) |
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
- Tests: `lupo-tests/`

### Agents

- Workspace: `/lupo-actors/{actor_name}/`
- Use `use <actor_id>` to switch active actor.
- Session file: `lupo-database/session.md` when DB is unavailable.

## Getting help

- **CLI:** `php lupo-bin/lupo.php help` or `php lupo-bin/lupo.php help --web`
- **Web:** http://www.lupopedia.com/help
- **Version:** [lupo-docs/version.md](version.md)

---

**Last updated:** 2026-03-06  
**Version:** 4.0.62
