---
lupopedia.headers:
  header_format_version: 2
  when_updated: '20260330'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/lupopedia/README.md
  last_modified_utc: '20260330'
  channel_id: 42
  thread_id: 4.0.93-init
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: project_documentation
  artifact_kind: readme
  purpose: Root overview for Lupopedia system architecture, version-driven execution, and iterative release planning.
  tags:
    - readme
    - 4.0.93
    - architecture
    - doctrine
    - workflow
lupopedia.init:
  required_reading:
    - path: ONBOARDING.md
      reason: Operational quick-start
    - path: AGENTS.md
      reason: Canonical actor, identity-layer, and coordination rules
    - path: lupo-rules/root/README.md
      reason: Complete root rules and development constraints
    - path: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
      reason: Header/footer validation doctrine (see section on footer validation and staleness policy)
    - path: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      reason: Canonical five-layer identity model
    - path: lupo-docs/versions/4.0.93/README.md
      reason: Current active version overview and thread-consolidated scope
    - path: lupo-docs/versions/4.0.93/PLAN.md
      reason: Current detailed iteration plan
    - path: lupo-docs/versions/4.1.0/prd/README.md
      reason: Post-approval milestone definition and release gate model
    - path: lupo-channels/channel_index.md
      reason: Canonical channel map and path policy
    - path: lupo-channels/channel_creation_doctrine.md
      reason: Channel semantics and creation/routing doctrine
    - path: lupo-channels/42/THREAD_INDEX.md
      reason: Canonical example of thread-scoped channel execution
    - path: ORGANIZATION.md
      reason: Canonical root folder map and repository write guidance
lupopedia.edges:
  comment: Snapshot of root documentation references for version-driven execution and release continuity.
  outbound_edges:
    - to: AGENTS.md
      type: aligns_with
      weight: 1.0
    - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      type: aligns_with
      weight: 1.0
    - to: lupo-rules/root/README.md
      type: references
      weight: 1.0
      reason: Complete root rules and development constraints
    - to: ONBOARDING.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.0.93/README.md
      type: references
      weight: 1.0
      reason: Current active version overview and thread-consolidated scope
    - to: lupo-docs/versions/4.0.93/PLAN.md
      type: references
      weight: 1.0
      reason: Current detailed iteration plan
    - to: lupo-docs/versions/4.0.93/TODO.md
      type: references
      weight: 1.0
      reason: Current task tracking and execution plan
    - to: lupo-docs/versions/4.1.0/plan.md
      type: references
      weight: 0.95
    - to: lupo-docs/versions/4.1.0/prd/README.md
      type: references
      weight: 1.0
    - to: ORGANIZATION.md
      type: references
      weight: 0.95
    - to: lupo-docs/archived/root_stale_20260324/
      type: references
      weight: 0.8
lupopedia.footer:
  last_verified: '20260330'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: cursor:root
  next_action:
    - Keep README aligned with current 4.0.93 version and documentation clarity improvements
    - Ensure footer validation and staleness policy are referenced and enforced
---
# file: Lupopedia README - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/README.md](http://www.lupopedia.com/lupopedia/README.md)

# Lupopedia Semantic OS

## Footer Validation and Staleness Policy

All Lupopedia documentation must include a canonical `lupopedia.footer` block. Footer validation rules require:
- `last_verified` (UTC, 14 digits)
- `verified_by` (object with at minimum: `identity_type`, `actor_id`)
- `verified_via` (object with at minimum: `type`, `faucet_slug`)

Artifacts with `last_verified` earlier than `20260301000000` UTC are considered stale and must be semantically revalidated before updating the footer. See [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) for canonical rules and validator details.

## Temporal Anchor & UTC Timestamp Policy (4.0.93+)

All Lupopedia header timestamps (`last_modified_utc` in `lupopedia.headers`) must be synchronized to real UTC, never local time or a timezone. The IDE and all header writers must reference the canonical anchor file:

- `lupo-bin/temporal_anchor.json`


This file is updated by:

- [`lupo-bin/tick.py`](lupo-bin/tick.py) — see [lupo-docs/lupo-bin/TICK_PY.md](lupo-docs/lupo-bin/TICK_PY.md)

**tick.py** is a required utility script that updates the anchor file with the current UTC time in `YYYYMMDDHHMMSS` format. The IDE must call this script after every session or major write to ensure all header timestamps are synchronized to real UTC. See the [tick.py documentation](lupo-docs/lupo-bin/TICK_PY.md) for usage and policy.

**Policy:**
- All timestamps must be in `YYYYMMDDHHMMSS` (14-digit UTC) format for auditability.
- If the anchor file is missing, the IDE must request a tick before writing headers.
- Never use local time, offsets, or invented dates.

## Subdirectory-Only Installation & Monitoring Widget (Critical)

**Lupopedia must always be installed in a subdirectory of your site (never at the web root).**

**Why:**
- Auto-installers (Softaculous, Installatron, etc.) do not allow replacing the web root.
- Lupopedia is designed to coexist with an existing site and monitor the parent site above its own directory.
- The Semantic Monitoring Widget ("The Eye") is responsible for tracking and analytics of the host site, not Lupopedia itself.

**How Monitoring Works:**
- Lupopedia provides a dynamic JavaScript endpoint (`lupopedia_js.php`) that must be embedded in your host site’s pages (outside the Lupopedia directory).
- Example usage:
  ```html
  <script src="/your-subdirectory/lupopedia_js.php"></script>
  ```
- The system must NOT assume the folder is named `lupopedia`—the installer will detect and store the correct subdirectory.
- All monitoring, visitor tracking, and content interaction features depend on this script being present on the host site.

**Never install Lupopedia at the web root.** All paths, cookies, and monitoring logic assume a subdirectory context.

See also: [Semantic Monitoring Widget PRD](lupo-docs/versions/4.0.93/prd/semantic_monitoring_widget.md)

Lupopedia is a semantic AI operating system built on Crafty Syntax 3.7.5 foundations. It combines a hybrid MySQL plus filesystem architecture, multi-agent coordination, actor-based identity, and doctrine-driven documentation to evolve the original live-help system into a broader semantic runtime.

## What Lupopedia Is

At a high level, Lupopedia is:

- a semantic AI operating system
- a hybrid MySQL plus filesystem system
- a multi-agent coordination environment
- a Crafty Syntax 3.7.5 continuation and upgrade path

Core system characteristics:

- MySQL is the runtime authority for structured data, identities, edges, and operational state.
- The filesystem is used for documentation, coordination artifacts, channels, threads, prompts, and reports.
- Actors orchestrate work; faucets are execution surfaces.
- Channels and threads are the primary coordination model.

## How the Project Is Organized

The repository is organized around domain folders such as `lupo-docs/`, `lupo-database/`, `lupo-includes/`, `lupo-tests/`, `lupo-channels/`, and other `lupo-*` surfaces.

For a full evidence-based structure map of all root `lupo-*` directories and authority boundaries, use:

- `ORGANIZATION.md`
- `lupo-docs/ORGANIZATION.md`
- `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md`
- `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md`

For planning and progress, the most important rule is:

- `lupo-docs/versions/` is the source of truth for versioned execution, planning, and release state.

Root files are intentionally high level:

- `README.md` explains the project and version model.
- `plan.md` explains the high-level strategy.
- `TODO.md` lists high-level work categories.

Detailed execution belongs in version folders such as:

- `lupo-docs/versions/4.0.88/`
- `lupo-docs/versions/4.1.0/`

Organization references:

- `ORGANIZATION.md`
- `lupo-docs/ORGANIZATION.md`

Database/file-based authority references:

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical DDL
- `lupo-database/lupopedia/json/` — generated per-table column mirrors (e.g. `lupo_contents.json`) used by import tooling; do not hand-edit
- `lupo-database/lupopedia/toon/` — optional TOON export in some workflows; same “generated mirror” role as `json/` when present
- `lupo-docs/database/lupopedia/tables/active/` — human-readable table documentation
- `lupo-channels/`
- `lupo-database/sessions/`

## Configuration File Location

Lupopedia follows the traditional open-source auto-installer pattern used by WordPress, phpBB, and similar applications.

The configuration file `lupopedia-config.php` is searched for in this order:

1. **One level above web document root** (most secure)
   - Example: `/home/user/lupopedia-config.php` when web root is `/home/user/public_html/` 

2. **One level above the Lupopedia installation directory**
   - Example: `/home/user/lupopedia-config.php` when Lupopedia is at `/home/user/public_html/lupopedia/` 

3. **In the Lupopedia installation directory itself** (fallback)
   - Example: `/home/user/public_html/lupopedia/lupopedia-config.php` 

### For Auto-Installers (Softaculous, etc.)

When creating a package for auto-installers:
- Place `lupopedia-config.php` **one level above installation directory**
- Use the constant `LUPOPEDIA_CONFIG_PATH` if you need to override the search order
- Ensure the configuration file is NOT web-accessible

For complete details, see **[Configuration Doctrine](lupo-docs/doctrine/CONFIGURATION_DOCTRINE.md)**.

## Development Rules & Constraints

All development in Lupopedia must follow the root rules and doctrines:

### 📋 Essential Rules (Must Read)

**[Complete Root Rules](lupo-rules/root/README.md)** - All development constraints and requirements

**[LUPOPEDIA Headers Doctrine — single source of truth](lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md)** — the **only** binding file for YAML header taxonomy, validation rules, and **database-first mapping** (`lupo_contents`, `lupo_metadata`, `lupo_edges`, `revision_history`). Format, block order, and tooling index: [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md). Stable alias path (pointer only): [`lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md`](lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md).

Key constraints:
- **PHP 5.6+ Compatibility** - No PHP 7+ features, shared hosting ready
- **No Composer** - Self-contained libraries in `lupo-includes/` allowed per EXTERNAL_LIBRARIES_DOCTRINE
- **No Frameworks** - No Laravel, Symfony, or Blade templates
- **Database Doctrine** - No foreign keys, triggers, or auto-increment
- **Multi-Agent Coordination** - Channel-based communication in `lupo-channels/42/`

### Quick Checklist

Before writing code:
- [ ] PHP 5.6+ compatible (no `??`, `<=>`, type hints)
- [ ] No Composer dependencies
- [ ] No framework code (Laravel, Symfony)
- [ ] Use `lupo-includes/` for external libraries
- [ ] Follow database doctrine (no FK, no triggers)
- [ ] Use UTC timestamps in YYYYMMDDHHIISS format


## Version Model and Softaculous Approval Policy

Lupopedia is released exclusively as a sequence of 4.0.x versions (e.g., 4.0.93, 4.0.94, ..., 4.0.222) until Softaculous formally approves a 4.0.x release for their auto-installer. The 4.1.0 milestone **cannot** be released or considered active until Softaculous has reviewed, provided feedback, and accepted a 4.0.x version into their system. This process requires sending the code to Softaculous, receiving their feedback, and making any required corrections. Only after Softaculous approval will the project transition to a 4.1.0 release cycle.

**There is no direct jump from any 4.0.x release to 4.1.0.** All development, bugfixes, and improvements will continue as 4.0.x releases until Softaculous acceptance is achieved. This ensures compatibility, review, and a stable upgrade path for all users.

**Summary:**
- 4.0.x releases are iterative and ongoing until Softaculous approval.
- 4.1.0 will only begin after a 4.0.x version is accepted by Softaculous.
- All planning, documentation, and PRD must reflect this policy.

## Current Focus

The project is currently focused on:

- working in 4.0.88 as the active iteration
- building toward an approved 4.0.x baseline
- preparing the foundation required for 4.1.0

Current detailed execution surfaces:

- `lupo-docs/versions/4.0.88/README.md`
- `lupo-docs/versions/4.0.88/PLAN.md`
- `lupo-docs/versions/4.0.88/TODO.md`

Post-approval milestone surfaces:

- `lupo-docs/versions/4.1.0/plan.md`
- `lupo-docs/versions/4.1.0/todo.md`
- `lupo-docs/versions/4.1.0/prd/README.md`

## Key Systems Being Built

The main systems currently being defined, stabilized, or prepared are:

- channel system
- questions and prompts workflow
- approval footer and index system
- federation model
- `lupopedia_js.php` navigation and tracking system (**must be embedded in host site pages above the Lupopedia directory; see above**)
- channel refactor and migration model

## Channel-First Workflow

The current workflow model is:

1. Channel.
2. Questions.
3. Discussion.
4. Prompts.
5. Execution.

This model is documented in more detail in the version-scoped files and doctrine surfaces. Root docs only summarize it.

## Architecture Summary

Lupopedia uses a hybrid architecture:

- database-first runtime authority for content, identities, edges, tracking, and operational tables
- filesystem-first coordination and documentation surfaces for channels, threads, prompts, and reports
- edge integrity rules to keep document references and graph references coherent during structural changes
- federation-aware design that tolerates partial node 0 completeness during current deployment stages

## Where to Read Next

For developers and IDE agents, read in this order:

1. `AGENTS.md`
2. `ONBOARDING.md`
3. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
4. `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`
5. `ORGANIZATION.md`
6. `lupo-docs/versions/4.0.93/README.md`
7. `lupo-docs/versions/4.0.93/PLAN.md`
8. `lupo-docs/versions/4.0.93/TODO.md`
9. `lupo-docs/IMPLEMENTATION_GETTING_STARTED.md` (when created)
10. `lupo-docs/versions/4.1.0/prd/README.md`
11. `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md`
12. `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md`

## Root File Policy

Root files must stay aligned with version folders but must not duplicate version detail.

- Root files explain direction.
- Version files define execution.
- If a contradiction appears, the version-scoped files under `lupo-docs/versions/` are the authoritative planning surfaces.

## PRD Policy: Canonical Location, Referencing, and Anti-Duplication

- **All canonical Product Requirements Documents (PRDs) must be created and maintained ONLY in `lupo-docs/prd/`.**
- Do not create new PRDs in versioned or legacy folders.
- Always reference the constitutional PRD at `lupo-docs/prd/00_root_constitutional_system_requirements.md` for system-level requirements.
- Archive any PRDs found in versioned folders and update their headers with `status: legacy` and `superseded_by: lupo-docs/prd/00_root_constitutional_system_requirements.md`.
- Before creating a new PRD, check for existing documents in `lupo-docs/prd/` to avoid duplication.
- This policy is mandatory for all contributors to ensure requirements are canonical and non-fragmented.
