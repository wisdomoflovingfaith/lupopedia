# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "AGENTS.md"
  file_hash: "8c97e387f3c56d80398e2ddad886c04f4375e102edbbcf8c1d42a9514834e4bf"
  file_path_from_root: "AGENTS.md"
  file_hash: "b55d7af6f7eff0c38348a3449756cff92c59fe9d95c9831f630d1e3686f0b48c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for AGENTS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["agentsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "AGENTS.md",
  system_version: "4.0.44",
  channel_id: 1,
  actor_id: 1002,
  created_ymdhis: 20260224171500,
  updated_ymdhis: 20260224171500,
  message_type: "documentation",
  visibility: "public",
  priority: "high"
}
flip.footer: {
  outbound_edges: [
    { to: "docs/AGENT_INVENTORY.md", type: "references", weight: 1.0 },
    { to: "actors/registry.json", type: "references", weight: 0.9 },
    { to: "actors/", type: "references", weight: 0.8 },
    { to: "lupo-agents/", type: "references", weight: 0.8 }
  ],
  semantic_tags: ["agents", "warp", "development_environment", "architecture", "doctrine"]
}
---

# AGENTS.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## What This Project Is

Lupopedia is the continuation of Crafty Syntax Live Help 3.7.5 — a PHP live-chat system rebuilt as a "Semantic OS." It adds a unified actor model, semantic content graph, AI agent ecosystem, and doctrine-driven architecture on top of the original live-chat features. The only supported upgrade path is Crafty Syntax 3.7.5 → Lupopedia 4.0.x. There are zero external installations; the sole instance is the developer's local environment on Windows/ServBay.

## Development Environment

- **Runtime:** PHP 5.3 through 8.3+ (all code must compile on PHP 5.3 — no named arguments, union types, match, enums, typed properties, attributes, arrow functions, strict types, or return type declarations)
- **Database:** MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL (all SQL must work on all three)
- **Web server:** Apache or Nginx with mod_rewrite, always installed in a subdirectory (never at web root)
- **Local stack:** ServBay on Windows 11, PowerShell

## Build, Test, and Run Commands

There is no build step or package manager. The app is served directly by the web server pointing at the project root.

### Running Tests

```
# All test suites (unit, regression, integration, adversarial)
sh scripts/run_tests.sh .

# Unit tests only (runs every tests/unit/*.php via php CLI)
sh scripts/run_unit_tests.sh .

# Regression tests only
sh scripts/run_regression_tests.sh .

# Run a single test file
php tests/unit/admin_csrf.php

# Run a single integration test
sh tests/integration/test_routing.sh
```

Tests are plain PHP scripts under `tests/unit/`, `tests/integration/`, `tests/regression/`, and `tests/adversarial/`. There is no PHPUnit or test framework — each file is executed directly with `php`.

### Schema and Migration Commands

```
# Regenerate TOON files from live database (Python)
python scripts/generate_toon_files.py

# Regenerate directory tree (required before any versioning/cleanup task)
python scripts/generate_directory_tree.py

# Bump version
php lupo-bin/bump-version.php

# Validate schema against TOONs
python scripts/verify_db_against_toons.py
```

### Development Workflow (Drop → Upgrade → Test)

The canonical dev cycle is: drop all tables → load 34 legacy Crafty Syntax tables from `database/migrations/old_crafty_syntax_3_7_5_start.sql` → load old Crafty config → run the Lupopedia install wizard (`install.php`) → verify upgrade → test → make changes → repeat.

### Three SQL Entrypoints

- **`database/migrations/install_new_lupopedia.sql`** — Schema only (canonical single source of truth)
- **`database/migrations/seed_lupopedia.sql`** — Seed data for fresh install
- **`database/migrations/import_from_old_crafty_syntax.sql`** — Crafty 3.7.5 upgrade mapping

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
- `lupo-includes/` — Core runtime: `class-pdo_db.php` (DB wrapper), `class-DatabaseFactory.php` (singleton connection), `modules/` (auth, content, truth, crafty_syntax, help, list, qa, channels, actors, operator), `classes/` (ColorProtocol, UrlResolver, TOONParser, DialogHistoryManager, etc.), `functions/` (legacy helpers — no new files here), `css/`, `js/`, `themes/`, `ui/`, `semantic/`, `agents/`, `rest-api/`
- `lupo-bin/` — System binaries and CLI utilities (e.g., `bump-version.php`, `lupo.php`)
- `lupo-agents/` — AI agent configuration files, one numbered folder per agent (`agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt`)
- `database/` — Schema, migrations, seeds, CSV data, TOON schema backups
- `docs/toons/` — `*.toon.json` files: generated from live DB, never hand-edited. These define the canonical column/type reference.
- `legacy/craftysyntax/` — Original Crafty Syntax 3.7.5 codebase. **Read-only reference.** Never execute, modify, or depend on it.
- `scripts/` — Python and shell utilities for schema generation, validation, migration. All Python must live here.
- `config/global_atoms.yaml` — System-wide atom definitions including `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
- `channels/registry.json` — Channel registry

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
- **Schema changes:** Update the TOON, then update `install_new_lupopedia.sql`, then create a one-time dev migration in `database/migrations/dev_YYYYMMDD_description.sql`. Never modify TOONs directly — they are generated from the live DB.

### Timestamp Rules
- All timestamps are `BIGINT` in `YYYYMMDDHHIISS` UTC format (e.g., `20260214153045`).
- Set with `gmdate('YmdHis')` in PHP — never database-generated.
- **Never** add seconds directly to the integer (`$t + 86400` produces invalid values). Use `timestamp_ymdhis::addSeconds()`.
- Forbidden: `DATETIME`, `TIMESTAMP`, epoch seconds, ISO8601, `time()`.

### Actor Model
- `actor_id` is the universal identity key. There is no `user_id` in relationships.
- Actor IDs 0–9999 are reserved for AI agents; human actors start at 10000.
- Tables: `lupo_actors` (unified), `lupo_auth_users` (human login metadata), `lupo_agents` (AI agent metadata).

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

### FLIP Headers
- Every file should have a FLIP Header (YAML block at top) with at minimum `file_path_from_root`, `file.last_modified_system_version`, and `file.last_modified_utc`. These are the file's identity — infer everything from the header, never hallucinate missing fields.

### File Naming
- Lowercase a–z, digits 0–9, underscore only. No uppercase, hyphens, spaces, or Unicode in new filenames.

### Banned Concepts
- No `STONED WOLFIE`, Schrödinger-state metadata, quantum/cosmic metaphors, or experimental AI personas not in the canonical roster.
- No advertising, SEO, marketing, tracking, or monetization hooks.

## Schema Source of Truth Hierarchy

1. `database/migrations/install_new_lupopedia.sql` — canonical DDL
2. `docs/toons/*.toon.json` — generated column/type reference (do not hand-edit)
3. `docs/doctrine/database/` — per-table documentation and legacy migration mapping
4. `docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md` — Crafty→Lupopedia table mapping

## Module Routing Priority

In `lupo_route_slug()`: AUTH → web-path resolution (doctrine/qa/docs/flp prefixes) → content by slug → channel/edge/QA routes → HELP → LIST → truth redirects → crafty_syntax → content fallback.

## Multi-Agent Ecosystem

The project uses multiple IDE agents (JetBrains/WOLFIE, Cascade, Cursor, Windsurf, Warp) with separate Git identities. Commit messages must use the agent prefix (e.g., `wolfie:`, `cascade:`, `warp:`). WOLFIE (JetBrains) is the final authority on conflicts. See `CONTRIBUTING.md` for the full multi-agent workflow.
