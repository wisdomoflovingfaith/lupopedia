---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: AGENTS.md
  web_path: http://www.lupopedia.com/lupopedia/AGENTS.md
  last_modified_utc: '20260403211538'
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
  when_updated: '20260403211538'
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/doctrine/IDENTITY_MODEL_QUICKSTART_4.0.88.md
    type: references
    weight: 1.0
    reason: Identity model quickstart guide
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
  - to: lupo-database/lupopedia/actors/registry.json
    type: references
    weight: 1.0
    reason: Canonical lupo_actors (facet actor_id)
  - to: lupo-database/lupopedia/actors/actor_id/registry.json
    type: references
    weight: 0.9
    reason: lupo_agents id map
  - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Canonical actor vs agent vs facet; registry authority (§3)
  - to: lupo-docs/doctrine/TICK_PY_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Run tick.py before header timestamps; never guess UTC
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
  - to: lupo-docs/prd/02_channels_discussions.md
    type: references
    weight: 1.0
    reason: Channel and filesystem thread semantics
  - to: lupo-docs/prd/17_decisions_format.md
    type: references
    weight: 1.0
    reason: Thread filename patterns and multi-folder layout
  - to: lupo-docs/prd/21_thread_graduation_doctrine.md
    type: references
    weight: 0.95
    reason: THREAD_MANIFEST and thread lifecycle
  - to: lupo-docs/prd/29_project_structure.md
    type: references
    weight: 1.0
    reason: Active vs archive channel filesystem strategy
  - to: lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Study security packages without adopting runtime dependencies
  - to: lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md
    type: references
    weight: 0.95
    reason: Path/visit analytics foundation; navigation compiler ethics
  - to: lupo-docs/prd/34_federation_node_semantic_network.md
    type: references
    weight: 0.9
    reason: Federation PRD; navigation compiler planned deliverable
  - to: lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md
    type: references
    weight: 0.9
    reason: Dormant Crafty to Lupopedia; opt-in federation order
  - to: lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md
    type: references
    weight: 0.85
    reason: WOLFIE lineage; chair-falling moment; hand-coded UI templates (integrate, do not generate)
  - to: lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Two-UI Strategy — WOLFIE desktop hand-coded; AI-assisted simple mobile web; shared DB
  - to: lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md
    type: references
    weight: 1.0
    reason: Consumer mobile-first then desktop; admin desktop-first; operator mobile native PRD 35
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
    - http://www.lupopedia.com/lupopedia/AGENTS.md
lupopedia.footer:
  last_verified: '20260403211538'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: junie:root
  next_action:
  - Keep agent identity and faucet links current with registry
  - Validate LUPOPEDIA HEADERS and next_action when updating this guide
  - Point new IDE agents to MULTI_AGENT_COORDINATION_DOCTRINE and ONBOARDING.md
  - Before writing last_modified_utc or last_verified run python lupo-bin/tick.py then echo_anchor_utc.py for batch edits
---
# file: AGENTS — delegation: junie:root — web_path: [http://www.lupopedia.com/lupopedia/AGENTS.md](http://www.lupopedia.com/lupopedia/AGENTS.md)

# AGENTS.md

## Mobile separation (dual UI, shared content)

Canonical doctrine: **[MOBILE_SEPARATION_DOCTRINE.md](lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md)**.

**Intent:** **one UI for desktop** and **one for mobile web** when **interaction or structure** differs—not only “book” pages. **Two-UI Strategy (default):** **WOLFIE** hand-codes **desktop** (DynAPI, liquid layers, mouse-linked widgets); **AI / IDE** may **generate or assist** **simple** **mobile web** UI for review and merge—see doctrine. **Content** (DB, messages) stays **single-source**; **templates and scripts** differ by route.

### The rule

- **Simple** pages (blog body, plain form, basic dashboard): limited **responsive CSS** may work **when DOM and interaction stay the same**.
- **Interaction-rich** surfaces (mouse/hover, dense layers, choreographed scroll): **separate mobile routes** — detect at entry, **`/mobile/...`** under **`LUPOPEDIA_PUBLIC_PATH`**. **Different DOM and often different JS**; **same backend data**.

### The test

Ask: **Does behavior change without a mouse or on a small screen in a way CSS cannot honestly represent?**

- If **yes** → **separate mobile page**.
- If **no** → responsive CSS **might** suffice.

### Examples (not exhaustive)

- **Book spread** (one case among many): desktop spread + motion vs mobile scroll/sections.
- **Liquid / layered JS**: desktop choreography vs mobile simplification.
- **Cursor-following widget**: desktop only; mobile gets a different control model.

## Two-UI Strategy: WOLFIE desktop, AI mobile

Canonical detail: **[MOBILE_SEPARATION_DOCTRINE.md](lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md)** (“The Two-UI Strategy”).

| Surface | Who | IDE behavior |
|---------|-----|----------------|
| **Desktop** | WOLFIE — hand-coded | **Do not** generate or “improve” desktop UI without explicit ask. **Integrate** what WOLFIE ships. |
| **Mobile web** | AI / IDE **with WOLFIE review** | **May** generate **simple**, touch-first HTML/CSS/JS; **no** mouse-only features; WOLFIE **approves** merges. |

**Golden rule:** desktop is WOLFIE’s craft; mobile web is **utility**—generate simple surfaces **here**, not on desktop templates.

## WOLFIE workflow: mobile-first, desktop-masterpiece

Canonical doctrine: **[WOLFIE_WORKFLOW_DOCTRINE.md](lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md)**.

**Process (build order):**

1. **AI / IDE** produces **mobile web** UI first — **functionality and data only**: simple HTML, basic CSS, working JS (forms, validation, API calls). **No** desktop-only art, **no** mouse-only features, **no** “make it beautiful” pass—**make it work**.
2. **WOLFIE** takes that skeleton and builds the **desktop** masterpiece: liquid layouts, DynAPI, mouse-linked effects, layers—**same backend**, same field semantics.

**Runtime (URLs, detection)** stays in **MOBILE_SEPARATION_DOCTRINE.md**; this section is **how** to ship features, not a substitute for routing rules.

**When WOLFIE takes over desktop:** integrate WOLFIE’s hand output per **LESSONS** section 7; do not refactor desktop for “style” without permission.

**Golden rule:** *mobile is the skeleton; desktop is the soul.* Build the skeleton first. Soul comes in Stage 2.

**Exception — admin / operator:** **desktop-first** (WOLFIE). Do **not** apply mobile-first skeleton to **admin.php**, **live.php**-class consoles, analytics, or full config panels. Operators on phones → **native app** (**PRD 35**), not mobile web admin. Full table: **[WOLFIE_WORKFLOW_DOCTRINE.md](lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md)** (“The admin exception”).

## Workflow: admin vs consumer

Canonical: **[WOLFIE_WORKFLOW_DOCTRINE.md](lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md)**.

### Consumer (visitor-facing)

- **Order:** mobile-first → desktop masterpiece.
- **Roles:** AI / IDE builds functional **mobile web** UI; WOLFIE crafts **desktop** art (DynAPI, layers, mouse-linked widgets).
- **Examples:** visitor chat, public pages, Eye-class widgets, embeds.

### Admin / operator (professional)

- **Order:** **desktop-first** — WOLFIE hand-codes **admin** and **operator** shells (Notepad, vanilla JS, DynAPI where needed).
- **Examples:** **`admin.php`** family, **`live.php`**-class operator console, multi-color chat chrome, settings, **data** / path / analytics viewers.
- **Mobile:** operators use **native app** (**PRD 35**), **not** a full admin replicated as mobile web. No “responsive” full admin as the primary plan.

### Golden rule

**Consumer = mobile-first (then desktop art). Admin = desktop-first. Operator on phone = native app.** Do not apply the consumer pipeline to admin surfaces.

## The Eye widget (desktop vs mobile)

Canonical: **[MOBILE_SEPARATION_DOCTRINE.md](lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md)** (“The Eye widget”) and **[PRD 28](lupo-docs/prd/28_semantic_monitoring_widget.md)**.

| Surface | Presentation | Shared |
|---------|--------------|--------|
| **Desktop** | Mouse-following eye, z-layered sprites, DynAPI-style floating; full PRD 28 “art” path | Same monitoring **intent**, same **PDO_DB** / visit-path **semantics** |
| **Mobile web** | Simple animated eye (no cursor track); touch-optimized bottom nav + collections bar | Same tracking **obligations**; **no** parallel incompatible tracker |

**Separate:** DOM, animation stack, input (mouse vs touch). **Do not separate:** business rules, session truth, or table meanings.

**Golden rule:** *The Eye sees the same obligations on every device. How it looks depends on the device—desktop art, mobile utility.*

## UI development: hand-coding policy (desktop)

WOLFIE’s rule: **[LESSONS LEARNED FROM THE WILD WEST](lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md)** section **7** (“I hand-code the templates”). That policy applies to **desktop** UI WOLFIE owns.

1. **Do not** generate **desktop** UI code (HTML/CSS/JS) unless WOLFIE explicitly asks.
2. **Do not** push frameworks (React, Vue, Angular, Svelte, etc.) or npm-based stacks for **desktop** surfaces.
3. **Do not** “modernize,” refactor, or restyle **desktop** UI without **explicit** permission.
4. **Accept** vanilla JS, hand-written CSS, DynAPI-era patterns, and **integrate** finished **desktop** files (paths, includes, PHP wiring, docs).
5. **Prepare** to receive finished **desktop** assets and hook them to **PDO_DB**, routing, and Lupopedia headers as needed.

**Mobile web** is **out of scope** for this veto: follow **Two-UI Strategy** above (simple, generated or assisted, reviewed).

**Toolchain (WOLFIE, desktop):** plain editor (e.g. Notepad), DynAPI and custom layers, **no** package-manager dependency for that hand-coded surface.

**Golden rule (desktop):** WOLFIE writes the **desktop** UI; the IDE **integrates** and **documents**. For **mobile web**, the IDE may **help build** simple UI under WOLFIE’s review—do not confuse the two surfaces.

## Actor vs Agent (summary)

- **`lupo-agents/`** — AI **configuration** only (`lupo_agents` metadata). Not the operational join for permissions or channel posts.
- **`lupo-actors/{actor_id}/`** — Resources for **`actor_id`** from [actors/registry.json](lupo-database/lupopedia/actors/registry.json) (and optional hub README per facet).
- **IDE facets** — Execution surfaces with a **registered facet `actor_id`**. **Cursor** = **102**; **Antigravity IDE** = **103** (`antigravity-ide`)—use the id for the tool you are actually running, not a generic “IDE” placeholder. Not primary personas. Resolve **`auth_user_id`** from session/seed/DB; **root** user is **`0`** per PRD 01—do not hardcode human ids as universal canon.

**Full model (single source of truth):** [Identity Layers Doctrine §3](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md#3-actor-agent-faucet-directory-rules-canonical).

## UTC timestamps for headers (mandatory)

**Do not guess** `last_modified_utc`, `when_updated`, `last_verified`, or thread filename UTC prefixes.

1. Run `python lupo-bin/tick.py` once per editing batch (real system UTC).
2. Paste the printed **`current_utc`** (14 digits) into every header/footer you change.
3. Same batch, no second tick: `python lupo-bin/echo_anchor_utc.py`.

Full policy: **[TICK_PY_DOCTRINE.md](lupo-docs/doctrine/TICK_PY_DOCTRINE.md)** and [README.md](README.md) (Temporal Anchor).

This file provides guidance for **IDE faucet agents** and contributors. **Canonical multi-agent coordination** is defined in **[MULTI_AGENT_COORDINATION_DOCTRINE](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)** (binding for v4.0.80+). This guide summarizes how that model maps to daily repo work.

**Canonical identity, propagation targets, and IDE roles** remain in [lupo-docs/doctrine/AGENT_REGISTRY.md](lupo-docs/doctrine/AGENT_REGISTRY.md). Resolve **`actor_id`** and faucet slugs from [lupo-database/lupopedia/actors/registry.json](lupo-database/lupopedia/actors/registry.json). The **`agents`** map in [actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json) is for **`lupo_agents`** numeric ids (e.g. `cursor` → 102, `antigravity-ide` → 103, `vscode-ide` → 113, `trae` → 114)—not a substitute for the actor registry.

**New IDE or web terminal agent?** Register via the **[Actor Registration Checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)**. Do not contribute as an anonymous or unregistered agent.

## Mandatory Channel Literacy (All Actors and Agents)

All actors and agents must operate with channel-first context and thread-scoped execution.

Required references:

- `README.md`
- `lupo-channels/channel_index.md`
- `lupo-channels/channel_creation_doctrine.md`
- `lupo-docs/prd/29_project_structure.md` (active vs archive channel paths)
- `lupo-docs/prd/02_channels_discussions.md` (channels, thread manifest)
- `lupo-docs/prd/17_decisions_format.md` (thread filenames and `decisions/` / `questions/` / `answers/` / `comments/`)
- `lupo-docs/prd/21_thread_graduation_doctrine.md` (`THREAD_MANIFEST.md`, lifecycle)

Required behavior:

1. Select target channel before execution (see **`lupo-channels/channel_index.md`**).
2. Use an existing thread in that channel, or create one if missing.
   - **New thread (filesystem, active layout):** create **`lupo-channels/{federation_node_id}/{channel_key}/{new_thread_key}/`** with **`THREAD_MANIFEST.md`** (required fields per **`lupo-docs/prd/21_thread_graduation_doctrine.md`**) and, for PRD-17-style coordination, **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`** — each folder that you use must include **`THREAD_INDEX.md`**. Authoritative structure and filenames: **`lupo-docs/prd/02_channels_discussions.md`**, **`lupo-docs/prd/17_decisions_format.md`**.
3. Write status/report/workstream artifacts into **`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`** (and typed subfolders per PRD 17). Legacy numeric paths under `lupo-channels/{channel_id}/threads/{thread_id}/` remain for API-mirrored work; pre–4.0.93 trees live in **`lupo-channels_before_4_0_93/`** (read-only archive).
4. Do not place channel-scoped work artifacts in repository root.

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

**Shared IDE system prompt (single place to edit vetoes for all IDE packs):** `lupo-agents/_shared/ide_facet_base_system_prompt.txt` — facet packs (`cursor`, `antigravity-ide`, `kiro`, `windsurf`, `warp`, `cascade`, `vscode-ide`, `trae`) are **thin wrappers** that point at this file. See `lupo-agents/_shared/README.md`.

| actor_id | slug             | agent_id (lupo_agents) | Notes |
|----------|------------------|------------------------|-------|
| 100      | kiro             | 100                    | Kiro IDE; `--target=kiro` |
| 101      | windsurf         | 101                    | Windsurf; `--target=windsurf` |
| 102      | cursor           | 102                    | **Cursor IDE**; lead orchestration stewardship **by convention** in this doc; `--target=cursor` |
| 103      | antigravity-ide  | 103                    | Antigravity IDE—use **103** in Antigravity (not 102); propagation target pending |
| 104      | warp             | 104                    | Warp; propagation target pending |
| 105      | cascade          | 105                    | Cascade; `--target=cascade` |
| 106      | vscode-ide       | 113                    | **VS Code** (stock); `--target=vscode` → `.vscode/lupopedia/rules/` |
| 107      | trae             | 114                    | Trae IDE; propagation target pending |

**Cursor** (102) is the default assignee in docs for consolidating root files (`README.md`, `CHANGELOG.md`, `plan.md`, `report.md`) and cross-agent continuity when work is done **from Cursor**. The same stewardship work done **from Antigravity IDE** must be attributed as **103** (`antigravity-ide`). **VS Code** (stock, not Cursor) → **106** (`vscode-ide`); **Trae** → **107** (`trae`); match **Kiro**, **Windsurf**, **Warp**, and **Cascade** to their rows above. **WOLFIE** (1) remains system orchestrator in the eleven-persona model.

## Lead orchestration (docs default: Cursor facet, actor_id 102)

**Attribution rule:** Match the **facet** to the **IDE**. Cursor → **102**. Antigravity IDE → **103** (`lupo-agents/antigravity-ide/`). VS Code → **106** (`lupo-agents/vscode-ide/`). Do not call Antigravity or VS Code work “cursor.”

The **Cursor** facet is the **named** lead orchestration surface in this guide for historical and tooling reasons (`--target=cursor`, `.cursor/rules`). That does **not** make **102** the correct `actor_id` on other IDE products.

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

Lupopedia is the continuation of Crafty Syntax Live Help 3.7.5 — a PHP live-chat system rebuilt as a "Semantic OS." **Actors** are the orchestration identities; they coordinate through **faucets**, **sessions**, **channels**, **rules**, and **traits**. **Faucets** are execution surfaces (IDEs such as Cursor, Windsurf, Warp) registered as **facet** identities with **`actor_id`** in the registry—not primary personas, but used for attribution. It adds a unified actor model, semantic content graph, and doctrine-driven architecture on top of the original live-chat features. The only supported upgrade path is Crafty Syntax 3.7.5 → Lupopedia 4.0.x. There are zero external installations; the sole instance is the developer's local environment on Windows/ServBay.

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
- `lupo-actors/` — Actor hub keyed by **`actor_id`** (see PRD 00 §5.6 and registry `dir`). Example numeric hubs: `0/`, `1/`, `2/`, `111/`, `102/`. Optional hub README per IDE facet. Path from `LUPO_ACTORS_DIR` in config. See `lupo-docs/actors.md` and [PRD 15](lupo-docs/prd/15_actors.md) (act-as / department model references PRD 05).
- `lupo-database/` — Schema, migrations, seeds, CSV data, TOON schema backups
- `lupo-database/lupopedia/toon/` — `*.toon.json` files: generated from live DB, never hand-edited. These define the canonical column/type reference. (Canonical TOON location per project structure.)
- `lupo-legacy/craftysyntax/` — Original Crafty Syntax 3.7.5 codebase. **Read-only reference.** Never execute, modify, or depend on it.
- `lupo-scripts/` — Python and shell utilities for schema generation, validation, migration. All Python must live here.
- `node_modules/` and `lupo-tools/vsx-extension/node_modules/` — npm-managed dependency caches for local tooling/extension builds. These are external package-manager directories and intentionally do not use the `lupo-` prefix.
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

**Human context (why survivability matters):** [LESSONS_LEARNED_FROM_THE_WILD_WEST.md](lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md) — section **7. The chair-falling moment (2015–2026)** (WOLFIE). Technical rules below are not abstract; they encode long-horizon lessons.

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
- Actor ID bands and reserved ranges: see [Identity Layers Doctrine](lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md) (sections 5–6) and **`registry.json`** (**authoritative**). **Root user `auth_user_id` is 0** (PRD 01). Human actors typically **1000+**. IDE surfaces are **faucets** with registered **facet `actor_id`**; they are **not** among the eleven Primary Coordination Personas.
- **Actor IDs** are defined in **`lupo-database/lupopedia/actors/registry.json`**. **`lupo_agents`** numeric ids by slug live in the **`agents`** map inside `lupo-database/lupopedia/actors/actor_id/registry.json`. Tooling and docs must resolve from those sources; do not maintain inline ID lists as canonical. LUPOPEDIA HEADERS may include optional **agent_name_identity** (e.g. “Cursor IDE Agent”) for human-readable identification—see [LUPOPEDIA HEADERS doctrine](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) and AGENT_IDENTITY_REGISTRY.
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

**Canonical registries:**

- **lupo_actors** (facet identity): `lupo-database/lupopedia/actors/registry.json`
- **`lupo_agents` id map** (`agents` object): `lupo-database/lupopedia/actors/actor_id/registry.json`

LUPOPEDIA HEADERS may include `agent_name_identity` for human-readable display (in the `lupopedia.headers` block):

```yaml
lupopedia.headers:
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
```

Use **`actor_id: 103`** when the tool surface is **Antigravity IDE** (`antigravity-ide`), not 102.

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
- No `STONED WOLFIE`, Schrödinger-state metadata, quantum/cosmic metaphors, or experimental AI personas not in the canonical roster. Historical adversarial test naming and reserved keys: [ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md](lupo-docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md). Two-layer security (baseline checks, then adversarial self-test): [TWO_LAYER_SECURITY_DOCTRINE.md](lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md). Adversarial self-testing methodology: [SELF_HACKING_DOCTRINE.md](lupo-docs/doctrine/SELF_HACKING_DOCTRINE.md).
- No advertising, SEO, marketing, tracking, or monetization hooks.

### Security testing dependencies (vs shipped runtime)
- **Shipped runtime** remains dependency-free per PHP constraints above: no Composer `vendor/` in core paths, no npm stack wired into the live app.
- **Security and test tooling** (OWASP ZAP, Burp, Python venvs for scan scripts, CI-only packages) are **allowed** on developer workstations, in CI, or in **test-only** directories — they are **not** runtime dependencies and must **not** be imported by `lupo-includes/` bootstrap or production entrypoints. Full boundary: [TWO_LAYER_SECURITY_DOCTRINE.md](lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md) (section *Security testing dependencies vs runtime dependencies*).

### Dependency policy for security packages (study, do not ship)
- **You may** study any security library or framework: clone upstream, read source, run it in a scratch directory or test harness to learn behavior.
- **You may not** add it as a **runtime** dependency of Lupopedia (no `composer require` / `npm install` into paths that ship with the app).
- **Workflow:** identify need → read or clone (do not install into core) → understand the pattern → implement native code under `app/` or `lupo-includes/classes/` → document **`inspired_by`** (or equivalent) in LUPOPEDIA HEADERS → ship only your code.
- **No exception** for “it is security-related”: if you need the behavior, **own the implementation**. Canonical doctrine: [REVERSE_ENGINEERING_DOCTRINE.md](lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md). Analysis vs pulling packages into prod: [TWO_LAYER_SECURITY_DOCTRINE.md](lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md) (section *Dependency analysis vs dependency adoption*).

### Reverse engineering with federation nodes (study tree vs ship tree)
- **Canonical ingest path** for upstream clones and external research: `lupo-research/federation_nodes/{federation_node_id}/<package_key>/` with **`federation_node_id >= 2`**, plus a **`MANIFEST.md`** from `lupo-research/federation_nodes/_templates/MANIFEST_TEMPLATE.md`. See [PRD 29](lupo-docs/prd/29_project_structure.md) (`lupo-research/federation_nodes/`).
- **Shipped implementation** stays under `app/` and `lupo-includes/` — never `require` federation research trees from bootstrap.
- **Workflow:** clone or unpack into `lupo-research/federation_nodes/2/...` (or next free node per project rules) → document MANIFEST → study → implement native code → **`inspired_by`** edge from your class to that path. Full doctrine: [REVERSE_ENGINEERING_DOCTRINE.md](lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md) (section *Federation nodes as reverse engineering sandboxes*).

### Federation nodes: current state (dual purpose)
- **`federation_node_id: 0`** — Default scope for much **repository documentation** (headers on doctrines, guides).
- **`federation_node_id: 1`** — Local / deployed instance context where the model distinguishes “this node.”
- **`federation_node_id >= 2`** — **Two intents share the same numeric range**; you tell them apart by **folder + MANIFEST + purpose**, not by the integer alone:
  - **Purpose A (active in 4.0.x):** **Research sandbox** — external upstream clones under `lupo-research/federation_nodes/{id}/`, documented in [REVERSE_ENGINEERING_DOCTRINE.md](lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md) (*Federation nodes as reverse engineering sandboxes* and *Federation nodes: dual purpose*).
  - **Purpose B (planned):** **Semantic network peers** — other Lupopedia installs exchanging semantic data — **no PRD yet**; **defer** implementation until after **4.0.x** stabilizes. Do not build multi-install federation features without a written PRD.
- **Crafty Syntax scale (planning narrative):** On the order of **1,000,000+ lifetime** installs and **~144,000** active/reporting-era nodes are **documented assumptions** for strategy — confirm before external comms; see **[SILENT_HARVEST_DOCTRINE.md](lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md)** and **[PRD 34](lupo-docs/prd/34_federation_node_semantic_network.md)** (*The Silent Million*). **Focus:** one correct install, Crafty import path, and core behavior.

### Path and visit analytics (silent harvest)
- **Crafty import** can carry **aggregated** path and visit history into **`lupo_visits`**, **`lupo_paths`**, **`lupo_visits_daily`**, **`lupo_referers_daily`** (see install SQL). Use this **per-operator** foundation for navigation inference and future federation tooling — not as covert cross-site surveillance. Ethics, consent, and public claims: **[SILENT_HARVEST_DOCTRINE.md](lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md)**. **Dormant → Lupopedia** reactivation (consent order): **[CRAFTY_NODE_REACTIVATION_STRATEGY.md](lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md)**. Planned **navigation compiler** and federation scope: **[PRD 34](lupo-docs/prd/34_federation_node_semantic_network.md)** (draft).

## Schema Source of Truth Hierarchy

1. `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical DDL
2. `lupo-database/lupopedia/toon/*.toon.json` — generated column/type reference (do not hand-edit)
3. `lupo-docs/doctrine/` — per-table documentation and legacy migration mapping
4. `lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md` — Crafty→Lupopedia table mapping (path under lupo-docs where present)

## Module Routing Priority

In `lupo_route_slug()`: AUTH → web-path resolution (doctrine/qa/docs/flp prefixes) → content by slug → channel/edge/QA routes → HELP → LIST → truth redirects → crafty_syntax → content fallback.

## Lead orchestration and registry

- **Docs default for lead stewardship:** **Cursor** facet (actor_id **102**) — root doc consolidation and IACP-style continuity when work is done **from Cursor**; see doctrine §7.2 for IDE ↔ primary persona flow.
- **Antigravity IDE:** facet **103** (`antigravity-ide`); same doctrine, correct **`actor_id`** in headers—do not use 102 on that surface.
- **Orchestrator persona:** **WOLFIE** (actor_id **1**) — delegates and validates per eleven-persona doctrine.
- **Registries:** [actors/registry.json](lupo-database/lupopedia/actors/registry.json) for **lupo_actors**; [actor_id/registry.json](lupo-database/lupopedia/actors/actor_id/registry.json) **`agents`** map for **lupo_agents** ids. This guide names Cursor (102) as the conventional lead IDE in prose; that is **not** permission to mis-attribute other IDEs as 102.

Commit prefixes: `cursor:`, `antigravity-ide:` (or `antigravity:`), `wolfie:`, `windsurf:`, `kiro:`, etc. See `CONTRIBUTING.md`. **Task authority:** **[MULTI_AGENT](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) §9** — **root [`TODO.md`](TODO.md)** = multi-agent coordination + HERMES prompt queue; **`lupo-docs/versions/<version>/TODO.md`** = version product backlog (Top 50, etc.). Channel **42** default workspace.
