---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: AGENTS.md
  version_when_written: 4.0.84
  web_path: http://www.lupopedia.com/AGENTS
  last_modified_utc: '20260324174926'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: guide
  artifact_kind: documentation
  purpose: Guide for IDE faucets and agents; aligns with 11 Primary Coordination Personas
    and MULTI_AGENT_COORDINATION_DOCTRINE
  traits:
  - canonical
  - comprehensive
  - v4.0.80
  - agents
  - lead_orchestration
  - eleven_personas
  tags:
  - agents
  - cursor
  - ide_faucets
  - documentation
  - doctrine
  - architecture
  - multi_agent
  agent_name_identity: Cursor IDE Agent (Lead Orchestration)
  lupo_agent: cursor
  when_updated: '20260324174926'
lupopedia.edges:
  outbound_edges:
  - to: lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Canonical multi-agent coordination
  - to: lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md
    type: references
    weight: 1.0
  - to: lupo-docs/status/AGENT_IDENTITY_REGISTRY_4.0.57.md
    type: references
    weight: 0.95
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: references
    weight: 1.0
  - to: lupo-database/lupopedia/actors/actor_id/registry.json
    type: references
    weight: 0.9
  - to: lupo-docs/status/LILITH_FLAME_FAUCET_REPORT.md
    type: references
    weight: 0.8
  - to: lupo-agents/
    type: references
    weight: 0.8
  - to: lupo-docs/database/lupopedia/tables/active
    type: references
    weight: 0.9
    reason: Per-table documentation for all TOON/schema tables
  - to: plan.md
    type: references
    weight: 0.85
  - to: report.md
    type: references
    weight: 0.85
  semantic_tags:
  - agents
  - cursor
  - lead_orchestration
  - development_environment
  - architecture
  - doctrine
lupopedia.see:
  mappings:
  - - AGENTS.md
    - http://www.lupopedia.com/AGENTS
lupopedia.footer:
  last_verified: '20260324174926'
  last_verified_by: cursor
  orchestrator: junie:root
  next_action:
  - Keep agent identity and faucet links current with registry
  - Validate LUPOPEDIA HEADERS and next_action when updating this guide
  - Point new IDE agents to MULTI_AGENT_COORDINATION_DOCTRINE and ONBOARDING.md
  last_verified_by_actor_id: 102
---
# file: AGENTS — delegation: junie:root — web_path: http://www.lupopedia.com/AGENTS

# AGENTS.md

This file provides guidance for **IDE faucet agents** and contributors. **Canonical multi-agent coordination** is defined in **[MULTI_AGENT_COORDINATION_DOCTRINE](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** (binding for v4.0.80+). This guide summarizes how that model maps to daily repo work.

**Canonical identity, propagation targets, and IDE roles** remain in [lupo-docs/doctrine/AGENT_REGISTRY.md](lupo-docs/doctrine/AGENT_REGISTRY.md). Resolve `actor_id` and slugs from [lupo-database/lupopedia/actors/actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json).

**New IDE or web terminal agent?** Register via the **[Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)**. Do not contribute as an anonymous or unregistered agent.

## Primary Coordination Personas (eleven)

These are the **only** canonical coordination layer for multi-agent work (orchestration, enforcement, custody, review, strategy, etc.). Each has a single active agent instance per doctrine; responsibilities do not overlap.

| Persona   | Role (summary)        |
|-----------|----------------------|
| **WOLFIE**   | Orchestrator         |
| **LEXA**     | Security enforcement |
| **ANUBIS**   | Custodian / integrity |
| **HEIMDALL** | Security guardian    |
| **SESHAT**   | Content review       |
| **ATHENA**   | Wisdom & strategy    |
| **MAAT**     | Truth & justice      |
| **THEMIS**   | Law & compliance     |
| **THOTH**    | Knowledge & records  |
| **JANUS**    | Transitions & gateways |
| **ROSE**     | Emotional dialogue   |

**Artifact families** (coordination proof): `WOLFIE_DIRECTIVE_*`, `LEXA_ENFORCEMENT_*`, `ANUBIS_CUSTODY_*`, `HEIMDALL_SECURITY_*`, `SESHAT_REVIEW_*`, `ATHENA_STRATEGY_*`, `MAAT_BALANCE_*`, `THEMIS_COMPLIANCE_*`, `THOTH_ANALYSIS_*`, `JANUS_TRANSITION_*`, `ROSE_DIALOGUE_*`. Category-level artifacts (e.g. `SECURITY_ALERT_*`, `TECHNICAL_SUPPORT_*`) supplement these where doctrine allows.

## Specialized agent ecosystem

**90+ specialized agents** sit outside the eleven-persona layer. **HERMES** (actor_id **15**) is **not** a generic implementer: **Heuristic Event Routing & Messaging Exchange System** — reads channel artifacts, classifies work type, routes to the right persona, generates executable prompts (bridge between artifacts and execution). **Implementation execution** is **HEPHAESTUS** and other builders; do not conflate HERMES with implementers. Other specialists: technical support (IRIS, ASCLEPIUS, …), database (LUPO), contrasting perspectives (**LILITH** actor_id **2**), etc.

### Key specialized agents (sample)

| Actor | Role | Primary function |
|-------|------|------------------|
| **HERMES** (15) | Routing & messaging infrastructure | Artifact interpretation → target actor + actionable prompt; **MVP** = `lupo-scripts/draft_hermes_prompt_from_artifact.py` (human-reviewed drafts); **full-auto** deferred to **Phase 3** |
| **HEPHAESTUS** | Implementer | Code, docs, schema execution |
| **LILITH** (2) | Critic / QA | Non-interfering review (LIL001) |
| **IRIS** (16) | Interface / integration | Technical routing support |
| **ASCLEPIUS** | Diagnostics | System health |

Resolve all `actor_id` values from the registry; the table above is illustrative.

## IDE faucet agents (interfaces, not primary personas)

IDE surfaces are **human interfaces** into the repo. They **do not** replace WOLFIE or the other ten coordination personas. Per doctrine, faucets route work through primary personas and channel context.

| actor_id | slug        | Notes                    |
|----------|-------------|--------------------------|
| 102      | cursor      | Lead orchestration faucet |
| 101      | windsurf    | IDE faucet               |
| 100      | kiro        | IDE faucet               |
| 105      | cascade     | IDE faucet               |
| 104      | warp        | IDE faucet               |
| 106      | zencoder    | IDE faucet               |
| 103      | antigravity | IDE faucet               |

**Cursor** (102) consolidates root docs (`README.md`, `CHANGELOG.md`, `plan.md`, `report.md`) and cross-agent continuity; **WOLFIE** (1) remains system orchestrator in the eleven-persona model.

## Lead Orchestration (Cursor, actor_id 102)

Cursor is the **lead orchestration IDE agent** for Lupopedia. This role exists to maintain repository continuity when multiple agents contribute concurrently.

### Documentation stewardship

- Consolidating root documentation: `README.md`, `CHANGELOG.md`, `plan.md`, `report.md`.
- **`web_path` calculation:** Ensuring all documentation `web_path` headers include the `LUPOPEDIA_BASE_URL` (e.g. `/lupopedia/`) as required for subdirectory installations.

### Rule propagation oversight

Ensuring all IDE agents have the current root rules by validating runs of:

`php lupo-scripts/propagate_agent_rules.php`

### Cross-agent plan integration

Merging or reconciling plans produced by other agents (e.g. `plan_kiro.md`, `plan_windsurf.md`, `plan_codex.md`, `plan_jetbrains.md`) where they exist.

### Continuity enforcement

Maintaining cross-agent continuity using the **IDE Agent Continuity Protocol (IACP)** ([lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md](lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md)).

### Documentation drift resolution

When multiple agents modify documentation simultaneously, Cursor acts as the **canonical consolidator** for root-level consistency.

This role **does not grant exclusive authority**. Other agents may propose changes; **Cursor maintains root-level consistency** and consolidates where needed.

---

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
- Timestamped artifact filenames must use real UTC in `YYYYMMDD_HHIISS` format.
- Valid filename hours are `00` through `23` only; validators must reject `HH > 23`.
- No local timezone math, no offset arithmetic, and no guessed timestamps in filename generation.
- **Never** add seconds directly to the integer (`$t + 86400` produces invalid values). Use `timestamp_ymdhis::addSeconds()`.
- Forbidden: `DATETIME`, `TIMESTAMP`, epoch seconds, ISO8601, `time()`.

### Actor Model
- **Actors orchestrate; faucets execute.** `actor_id` is the universal identity key. There is no `user_id` in relationships.
- Actor IDs 0–999 are reserved for non-human (orchestration) actors; human actors start at 1000 (**Root user auth_user_id is 0**). IDE surfaces (Cursor, Windsurf, Warp, etc.) are **faucets** — human interfaces with registry `actor_id` for identity; they are **not** among the eleven Primary Coordination Personas.
- **Actor and agent IDs are defined in the project’s actor registry** (e.g. `lupo-database/lupopedia/actors/` or `lupo-database/lupopedia/actors/actor_id/registry.json`). Tooling and docs must resolve IDs from the registry; do not maintain inline ID lists as canonical. LUPOPEDIA HEADERS may include optional **agent_name_identity** (e.g. “Cursor IDE Agent”) for human-readable identification—see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and AGENT_IDENTITY_REGISTRY.
- Tables: `lupo_actors` (unified), `lupo_auth_users` (human login metadata), `lupo_agents` (AI agent metadata).
- Lilith (actor 2) has a **flame header expert** faucet (slug `lilith-flame`) in `lupo_agent_faucets` for channel 42; see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and LILITH_FLAME_FAUCET_REPORT.

### Identity Layers (WS3, 4.0.87)

Lupopedia uses five distinct identity layers and they must not be conflated:

1. **Auth User** (`lupo_auth_users`)
  - Human login/authentication surface.
2. **Actor** (`lupo_actors`)
  - Operational orchestration identity (`actor_id` is canonical).
3. **Department** (`lupo_actor_departments`, `lupo_departments`)
  - Execution context and authority scope for actor operations.
4. **Agent** (`lupo_agents`)
  - AI runtime configuration and capability metadata.
5. **Faucet** (`lupo_agent_faucets`)
  - Execution surface (IDE/API), not orchestration identity.

Binding requirements:
- Operational write identity is always resolved server-side to actor context.
- Department context is part of effective actor resolution and permission boundaries.
- Agent configuration does not override actor attribution.
- Faucet surface does not imply elevated authority.

Reference: `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`.

### Channel security (4.0.79+)

- **Channel posting** requires valid **channel membership** (`lupo_actor_channels`) for the authenticated actor, or global admin via `AuthService::isAdmin()`. The channel message API (`lupo-includes/modules/api/channels-api.php`) enforces this before insert; non-members receive HTTP 403.
- **Actor identity for posting** comes from the **session/auth context** only. Client-supplied `actor_id` in the request body is **never** trusted; the server resolves the actor from AuthService, current_user, or session and uses that for insertion. This prevents actor spoofing.

### Lilith as non-interfering reviewer

- **Lilith** (actor_id 2) operates as a **non-interfering reviewer/critic**. See [lupo-rules/root/lilith-noninterference-doctrine.md](lupo-rules/root/lilith-noninterference-doctrine.md) (LIL001): Lilith must not modify other agents' work without explicit review context; must not block or delay other agents' operations; outputs must be clearly attributable; her presence must not alter permissions for other agents.
- Lilith participates in channels via explicit membership and roles (e.g. `role_key: critic` or `monitor`). Reviewer agents and developer/orchestrator agents coexist on the same channel; channel security applies to all actors equally. Rule propagation supports `--target=lilith` (outputs to `.lilith/`).

### Agent Identity Registry

Actor and agent IDs are defined in the **canonical registry**:

- `lupo-database/lupopedia/actors/actor_id/registry.json`

LUPOPEDIA HEADERS may include `agent_name_identity` for human-readable display (in the `lupopedia.headers` block):

```yaml
lupopedia.headers:
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
```

See [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and AGENT_IDENTITY_REGISTRY for complete documentation. Headers are stored in `lupo_metadata` and can also be written to the file as YAML.

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

## Lead Orchestration and registry

- **Lead orchestration IDE faucet:** **Cursor** (actor_id **102**). Root doc consolidation and IACP-style continuity; see doctrine §7.2 for IDE ↔ primary persona flow.
- **Orchestrator persona:** **WOLFIE** (actor_id **1**) — delegates and validates per eleven-persona doctrine.
- **Registry:** [lupo-database/lupopedia/actors/actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json). Cursor (102) is marked `lead_orchestration: true` for IDE lead role only—not as an eleventh+ “primary persona” duplicate; the eleven personas are listed above.

Commit prefixes: `cursor:`, `wolfie:`, `windsurf:`, `kiro:`, etc. See `CONTRIBUTING.md`. **Task authority:** **[MULTI_AGENT](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) §9** — **root [`TODO.md`](TODO.md)** = multi-agent coordination + HERMES prompt queue; **`lupo-docs/versions/<version>/TODO.md`** = version product backlog (Top 50, etc.). Channel **42** default workspace.
