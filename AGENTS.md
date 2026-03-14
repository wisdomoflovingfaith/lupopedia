---
lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "AGENTS.md"
  web_path: "http://www.lupopedia.com/AGENTS"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Guide for all IDE faucets and agents; Cursor IDE is lead orchestration actor with Wolfie as supporting actor"
  traits: ["canonical", "comprehensive", "v4.0.75", "agents", "lead_orchestration"]
  tags: ["agents", "cursor", "ide_faucets", "documentation", "doctrine", "architecture"]
  agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 0.9 }
    - { to: "lupo-docs/status/LILITH_FLAME_FAUCET_REPORT.md", type: "references", weight: 0.8 }
    - { to: "lupo-agents/", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active", type: "references", weight: 0.9, reason: "Per-table documentation for all TOON/schema tables" }
    - { to: "plan.md", type: "references", weight: 0.85 }
    - { to: "report.md", type: "references", weight: 0.85 }
  semantic_tags: ["agents", "cursor", "lead_orchestration", "development_environment", "architecture", "doctrine"]

lupopedia.see:
  mappings:
    - ["AGENTS.md", "http://www.lupopedia.com/AGENTS"]

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Keep agent identity and faucet links current with registry"
    - "Validate LUPOPEDIA HEADERS and next_action when updating this guide"
    - "Point new IDE agents to Required Reading and LUPOPEDIA_HEADERS doctrine"
---
# file: AGENTS — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/AGENTS

# AGENTS.md

This file provides guidance for **all IDE agents and faucets** (Cursor, Windsurf, Kiro, Antigravity, Warp, Cascade, Codex) when working with this repository. **Cursor IDE** (actor_id 102) is the **lead orchestration actor**; **Wolfie** (actor_id 1) is the **supporting actor**. Resolve actor and faucet IDs from the canonical registry; see [Lead orchestration and IDE faucets](#lead-orchestration-and-ide-faucets) below.

**New IDE or web terminal agent?** You must register as an actor before participating. Follow the step-by-step **[Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)** (derived from TOON/database and `lupo-database` fallback). The checklist covers registry update, database or fallback persistence, and validation. Do not contribute as an anonymous or unregistered agent.

## What This Project Is

Lupopedia is the continuation of Crafty Syntax Live Help 3.7.5 — a PHP live-chat system rebuilt as a "Semantic OS." **Actors** are the orchestration identities; they coordinate through **faucets**, **sessions**, **channels**, **rules**, and **traits**. **Faucets** are execution surfaces (IDE surfaces such as Cursor, Windsurf, Warp are faucets, not actors). It adds a unified actor model, semantic content graph, and doctrine-driven architecture on top of the original live-chat features. The only supported upgrade path is Crafty Syntax 3.7.5 → Lupopedia 4.0.x. There are zero external installations; the sole instance is the developer's local environment on Windows/ServBay.

## Development Environment

- **Runtime:** PHP 5.6 through 8.3+ (all code must compile on PHP 5.6 minimum — no Composer/outside frameworks not in lupo-includes; no deprecated PHP 8+ syntax; no named arguments, union types, match, enums, typed properties, attributes, arrow functions, strict types, or return type declarations in core paths)
- **Database:** MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL (all SQL must work on all three)
- **Web server:** Apache or Nginx with mod_rewrite, always installed in a subdirectory (never at web root)
- **Local stack:** ServBay on Windows 11, PowerShell

## Build, Test, and Run Commands

There is no build step or package manager. The app is served directly by the web server pointing at the project root.

### Running Tests

```
# All test suites (unit, regression, integration, adversarial)
sh lupo-scripts/run_tests.sh .

# Unit tests only (runs every lupo-tests/unit/*.php via php CLI)
sh lupo-scripts/run_unit_tests.sh .

# Regression tests only
sh lupo-scripts/run_regression_tests.sh .

# Run a single test file
php lupo-tests/unit/admin_csrf.php

# Run a single integration test
sh lupo-tests/integration/test_routing.sh
```

Tests are plain PHP scripts under `lupo-tests/unit/`, `lupo-tests/integration/`, `lupo-tests/regression/`, and `lupo-tests/adversarial/`. There is no PHPUnit or test framework — each file is executed directly with `php`.

### Schema and Migration Commands

```
# Regenerate TOON files from live database (Python)
python lupo-scripts/generate_toon_files.py

# Regenerate directory tree (required before any versioning/cleanup task)
python lupo-scripts/generate_directory_tree.py

# Bump version
php lupo-bin/bump-version.php

# Validate schema against TOONs
python lupo-scripts/verify_db_against_toons.py
```

### Development Workflow (Drop → Upgrade → Test)

The canonical dev cycle is: drop all tables → load 34 legacy Crafty Syntax tables from `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql` → load old Crafty config → run the Lupopedia install wizard (`install.php`) → verify upgrade → test → make changes → repeat.

### Three SQL Entrypoints (installer uses lupo-database/lupopedia/mysql/)

- **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** — Schema only (canonical single source of truth)
- **`lupo-database/lupopedia/mysql/seed/`** — Seed data for fresh install (registry, actors, default sessions)
- **`lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`** — Crafty 3.7.5 upgrade mapping

Fresh install runs (A) then (B). Upgrade from Crafty runs (A), (B), then (C). Never mix them.

## Architecture Overview

### Request Lifecycle

1. `index.php` — Front controller. Defines `LUPOPEDIA_PATH`, `LUPOPEDIA_PUBLIC_PATH`. Searches for `lupopedia-config.php` (above docroot, then inside install dir). Extracts slug from URL.
2. `lupopedia-config.php` — Database credentials, constants, table prefix (`lupo_`). Loads bootstrap.
3. `lupo-includes/bootstrap.php` — Loads `version.php`, `class-pdo_db.php`, `class-DatabaseFactory.php`. Creates DB connection in `$GLOBALS['mydatabase']`. Starts session via `App\Auth\Session`. Loads services into globals (`lupo_actor_service`, `lupo_auth_service`, `lupo_collection_tabs_service`, etc.).
4. `lupo-includes/lupopedia-loader.php` — Central orchestrator. Loads subsystems in order: (1) Core functions, (2) Module system, (3) Semantic engine, (4) Agent subsystem, (5) UI subsystem, (6) REST API.
5. `lupo-includes/modules/module-loader.php` — Defines `lupo_route_slug($slug)` which routes requests. Priority: AUTH → web-path resolution → content/channel/edge/QA/help/list/crafty_syntax → fallback content.

### Key Directories

- `app/` — OOP services and auth: `app/auth/` (Session, AuthService, AuthRoleResolver), `app/Services/` (ActorService, CollectionZeroService, UploadService, CraftySyntax/, Pack/)
- `lupo-includes/` — Core runtime: `class-pdo_db.php` (DB wrapper), `class-DatabaseFactory.php` (singleton connection), `modules/` (auth, content, truth, crafty_syntax, help, list, qa, channels, actors, operator), `classes/` (ColorProtocol, UrlResolver, TOONParser, DialogHistoryManager, etc.), `functions/` (legacy helpers — no new files here), `css/`, `js/`, `themes/`, `ui/`, `semantic/`, `lupo-agents/`, `rest-api/`
- `lupo-bin/` — System binaries and CLI utilities (e.g., `bump-version.php`, `lupo.php`)
- `lupo-agents/` — AI agent configuration files, one numbered folder per agent (`agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt`)
- `lupo-actors/` — Actor-specific resources hub: per-actor dirs (0=system, 1=WOLFIE, …) with `apps/`, `lupo-tools/`, `lupo-docs/`, `db-changes/`, `lupo-api/`, `needs/`. Path from `LUPO_ACTORS_DIR` in config. See `lupo-docs/actors.md`.
- `lupo-database/` — Schema, migrations, seeds, CSV data, TOON schema backups
- `lupo-database/lupopedia/toon/` — `*.toon.json` files: generated from live DB, never hand-edited. These define the canonical column/type reference. (Canonical TOON location per project structure.)
- `lupo-legacy/craftysyntax/` — Original Crafty Syntax 3.7.5 codebase. **Read-only reference.** Never execute, modify, or depend on it.
- `lupo-scripts/` — Python and shell utilities for schema generation, validation, migration. All Python must live here.
- `config/global_atoms.yaml` — System-wide atom definitions including `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
- `lupo-channels/registry.json` — Channel registry

### Database Access Pattern

All DB access must go through `DatabaseFactory::getConnection()` or `lupo_get_db()`, which return the `PDO_DB` wrapper. Direct `new PDO()`, `mysqli_*`, or `new PDO_DB()` are forbidden.

```php
$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$rows = $db->fetchAll(
    "SELECT * FROM {$table_prefix}actors WHERE actor_id = :id",
    array('id' => $actorId)
);
$db->insert($table_prefix . 'sessions', array(
    'session_id' => $sid,
    'created_ymdhis' => gmdate('YmdHis')
));
```

Never hardcode `lupo_` — always use `LUPO_TABLE_PREFIX`. Always use prepared statements with named placeholders.

### Versioning

Version lives in `config/global_atoms.yaml` as `GLOBAL_CURRENT_LUPOPEDIA_VERSION`. Loaded at runtime by `lupo-includes/version.php`. All 4.0.x versions are patch iterations of the same Crafty→Lupopedia upgrade; there are no Lupopedia→Lupopedia upgrades until 4.1.0.

## Critical Doctrines (Non-Negotiable)

### Database Rules
- **No foreign keys, triggers, stored procedures, views, or computed columns.** The database is dumb storage; all logic is in PHP.
- **Integer types only:** `BIGINT`, `INT`, `SMALLINT`, `TINYINT` — no parenthesized display widths (`BIGINT(14)` is forbidden in DDL), no `UNSIGNED`, no `BOOLEAN`.
- **Soft deletes:** Tables use `is_deleted TINYINT DEFAULT 0` and `deleted_ymdhis BIGINT DEFAULT 0`. Queries must filter `WHERE is_deleted = 0` by default.
- **Schema changes:** Update the TOON, then update `install_new_lupopedia.sql`, then create a one-time dev migration in `lupo-database/lupopedia/mysql/migrations/dev_YYYYMMDD_description.sql`. Never modify TOONs directly — they are generated from the live DB.

### Timestamp Rules
- All timestamps are `BIGINT` in `YYYYMMDDHHIISS` UTC format (e.g., `20260214153045`).
- Set with `gmdate('YmdHis')` in PHP — never database-generated.
- **Never** add seconds directly to the integer (`$t + 86400` produces invalid values). Use `timestamp_ymdhis::addSeconds()`.
- Forbidden: `DATETIME`, `TIMESTAMP`, epoch seconds, ISO8601, `time()`.

### Actor Model
- **Actors orchestrate; faucets execute.** `actor_id` is the universal identity key. There is no `user_id` in relationships.
- Actor IDs 0–999 are reserved for non-human (orchestration) actors; human actors start at 1000. IDE surfaces (Cursor, Windsurf, Warp, etc.) are **faucets**, not actors.
- **Actor and agent IDs are defined in the project’s actor registry** (e.g. `lupo-database/lupopedia/actors/` or `lupo-database/lupopedia/actors/actor_id/registry.json`). Tooling and docs must resolve IDs from the registry; do not maintain inline ID lists as canonical. LUPOPEDIA HEADERS may include optional **agent_name_identity** (e.g. “Cursor IDE Agent”) for human-readable identification—see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and [AGENT_IDENTITY_REGISTRY](lupo-docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md).
- Tables: `lupo_actors` (unified), `lupo_auth_users` (human login metadata), `lupo_agents` (AI agent metadata).
- Lilith (actor 2) has a **flame header expert** faucet (slug `lilith-flame`) in `lupo_agent_faucets` for channel 42; see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and [LILITH_FLAME_FAUCET_REPORT](lupo-docs/status/LILITH_FLAME_FAUCET_REPORT.md).

### Agent Identity Registry

Actor and agent IDs are defined in the **canonical registry**:

- `lupo-database/lupopedia/actors/actor_id/registry.json`

LUPOPEDIA HEADERS may include `agent_name_identity` for human-readable display (in the `lupopedia.headers` block):

```yaml
lupopedia.headers:
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
```

See [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and [AGENT_IDENTITY_REGISTRY](lupo-docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md) for complete documentation. Headers are stored in `lupo_metadata` and can also be written to the file as YAML.

### Path Handling
- Lupopedia is always in a subdirectory. All URLs must use `LUPOPEDIA_PUBLIC_PATH` (e.g., `LUPOPEDIA_PUBLIC_PATH . '/login'`). Hardcoded root paths like `/login` are forbidden.
- Filesystem paths use `LUPOPEDIA_PATH` or `LUPOPEDIA_ABSPATH`.

### PHP Constraints
- No frameworks, middleware, Composer, or `vendor/` directory. Pure procedural PHP + PDO only.
- No ORM or query builders. SQL is hand-written.
- `spl_autoload_register()` is the only allowed autoloader.
- No `mbstring` dependency for slug generation — use ASCII fallbacks.
- All new code must be in classes (`app/Services/`, `lupo-includes/classes/`). No new global helper functions. No new files under `lupo-includes/functions/`.
- Existing helpers are migrated incrementally: old helper becomes a thin wrapper calling the new class method.

### LUPOPEDIA HEADERS
- Every file should have a **LUPOPEDIA HEADERS** block (YAML between `---` delimiters) with at minimum `file_path_from_root`, `file.last_modified_system_version`, and `file.last_modified_utc`. These are the file's identity — infer everything from the header, never hallucinate missing fields. Headers are stored in the **`lupo_metadata`** table and can also be **written to the file** as YAML. See [lupo-docs/doctrine/LUPOPEDIA_HEADERS/](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) for format, database behavior, and validators/tooling.

### File Naming
- Lowercase a–z, digits 0–9, underscore only. No uppercase, hyphens, spaces, or Unicode in new filenames.

### Banned Concepts
- No `STONED WOLFIE`, Schrödinger-state metadata, quantum/cosmic metaphors, or experimental AI personas not in the canonical roster.
- No advertising, SEO, marketing, tracking, or monetization hooks.

## Schema Source of Truth Hierarchy

1. `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical DDL
2. `lupo-database/lupopedia/toon/*.toon.json` — generated column/type reference (do not hand-edit)
3. `lupo-docs/doctrine/` — per-table documentation and legacy migration mapping
4. `lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md` — Crafty→Lupopedia table mapping (path under lupo-docs where present)

## Module Routing Priority

In `lupo_route_slug()`: AUTH → web-path resolution (doctrine/qa/docs/flp prefixes) → content by slug → channel/edge/QA routes → HELP → LIST → truth redirects → crafty_syntax → content fallback.

## Lead Orchestration and IDE Faucets

- **Lead orchestration actor:** **Cursor IDE** (actor_id **102**, slug `cursor`). Cursor coordinates consolidation of documentation, root plan, and report; approves merges from faucet-specific artifacts into canonical root files.
- **Supporting actor:** **Wolfie** (actor_id **1**, slug `wolfie`). Wolfie provides domain authority and conflict resolution support.
- **Registry:** [lupo-database/lupopedia/actors/actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json). Cursor (102) is marked `lead_orchestration: true`.

**Seven IDE lupo-agents/faucets** currently working on Lupopedia 4.0.74:

| actor_id | slug        | type        | notes                          |
|----------|-------------|-------------|--------------------------------|
| 1        | wolfie      | agent       | Supporting actor; JetBrains    |
| 100      | kiro        | ide_faucet  | Schema coordinator role        |
| 101      | windsurf    | ide_faucet  | Research, documentation        |
| 102      | cursor      | ide_faucet  | **Lead orchestration**         |
| 103      | antigravity | ide_faucet  | Governance, doctrine           |
| 104      | warp        | ide_faucet  | Warp terminal/IDE              |
| 105      | cascade     | ide_faucet  | Cascade IDE                    |
| —        | codex       | ide_faucet  | JetBrains Codex (Wolfie flow)  |

Commit messages must use the agent prefix (e.g., `cursor:`, `wolfie:`, `windsurf:`, `kiro:`). See `CONTRIBUTING.md` for the full multi-agent workflow. Root consolidation (README, CHANGELOG, plan.md, report.md) is maintained by Cursor as lead orchestration.
