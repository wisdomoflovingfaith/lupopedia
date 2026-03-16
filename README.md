---
lupopedia.init:
  # Required reading for new or existing IDE agents (onboarding). Read in order when joining or resuming work.
  # Scripts exist that re-generate header snapshots (e.g. export/import from lupo_metadata, propagate_agent_rules); this init block may be updated by tooling.
  required_reading:
    - path: "ONBOARDING.md"
      reason: "Operational quick-start; first file for new or existing IDE agents"
    - path: "README.md"
      reason: "High-level overview, install, canonical root rules, and required reading"
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and init doctrine before working with lupopedia.init"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format, block order, and file structure"
    - path: "AGENTS.md"
      reason: "Agent/faucet distinction, lead orchestration, and IDE faucet roles"
    - path: "lupo-rules/root/README.md"
      reason: "Root rules index and non-negotiable constraints"
    - path: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md"
      reason: "Register as an actor (new IDE or external agent)"
    - path: "lupo-docs/doctrine/AGENT_REGISTRY.md"
      reason: "Canonical actor identity, propagation targets, and IDE roles"
    - path: "CHANGELOG.md"
      reason: "Current version and latest changes"
    - path: "EXECUTIVE_SUMMARY.md"
      reason: "Philosophy and architecture (why the system is designed this way)"
    - path: "plan.md"
      reason: "Current implementation plan"
    - path: "report.md"
      reason: "Current status and report"
  required_context:
    - "lupo-docs/ is the canonical documentation root."
    - "Cursor (actor_id 102) is lead orchestration; install SQL is schema authority."
    - "Scripts re-generate header snapshots; do not assume init/header content is hand-only."

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia README", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Primary project documentation and onboarding — Install & upgrade validation, lupo-channels/actors/agents, GitHub repository strategy.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "readme, getting_started, semantic_os, multi_agent, v4.0.75", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260314000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }

lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "documentation"
  file_path_from_root: "README.md"
  web_path: "http://www.lupopedia.com/"
  last_modified_utc: "20260315"
  system_version: "4.0.78"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Primary project documentation and onboarding — Install & upgrade validation, canonical root rules, actor registration, lupo-channels/actors/agents"
  mood_rgb: "4169E1"
  traits: ["essential", "entrypoint", "onboarding", "v4.0.76"]
  tags: ["readme", "getting_started", "semantic_os", "multi_agent", "root_rules", "v4.0.76"]

lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  session_name: "L-LUPO-ROOT-CURSOR"
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  paired_actor_id: 1

lupopedia.edges:
  outbound_edges:
    - { to: "AGENTS.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.98 }
    - { to: "plan.md", type: "references", weight: 0.95 }
    - { to: "report.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/HELP.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/CLI.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/DOCTOR_HEALTH_CHECK.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 0.9 }
    - { to: "CONTRIBUTING.md", type: "references", weight: 0.85 }
  semantic_tags: ["project_overview", "onboarding", "semantic_os", "multi_agent", "root_rules"]

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260315"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Point new contributors to Required Reading (INIT_README, LUPOPEDIA_HEADERS), lupo-rules/root/, and actor registration checklist (lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)"
    - "Keep Getting Started and install steps aligned with 4.0.78"
    - "Review actor/faucet and registration guidance when doctrine paths change"
---
# file: Lupopedia README — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/

# Lupopedia Semantic OS v4.0.78

[![Version](https://img.shields.io/badge/version-4.0.78-blue.svg)](lupo-docs/version.md)
[![docs](https://img.shields.io/badge/docs-HELP.md-green)](lupo-docs/HELP.md)

---

**Current version: [v4.0.78](lupo-docs/version.md)** (active development). **Released: [v4.0.77](lupo-docs/version.md), [v4.0.76](lupo-docs/version.md).** Rules and governance updates: **canonical root rules** in `lupo-rules/root/`, IDE rule propagation (Cursor, Kiro, Windsurf, JetBrains), TOON path unified to `lupo-database/lupopedia/toon/`, `lupo-database/` security hardening, and **Antigravity VSX Extension** integration (including full LUPOPEDIA HEADERS terminology adoption and feature UI scaffolding). The only supported upgrade path is **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. See [plan.md](plan.md) and [report.md](report.md).

**Canonical root rules:** All agents and actors must follow the doctrine in **`lupo-rules/root/`**. Agent-specific rule files (e.g. `.cursor/rules/`, `.kiro/rules/`, `.windsurf/rules/`) are **derived** from those root rules; the root is the single source of truth. See [Canonical root rules](#canonical-root-rules) and [New agent onboarding](#new-agent--web-terminal-agent-onboarding).

**Architecture (onboarding):** **Actors** are the orchestration identities of Lupopedia. They coordinate through **faucets**, **sessions**, **channels**, and **rules**. **Faucets** are execution surfaces (IDE or web terminal), not identities. **Cursor IDE** (actor_id 102) is the **lead orchestration actor**, with **Wolfie** (actor_id 1) as supporting actor. A **new IDE agent or new web terminal agent** must **create and register an actor** before participating; anonymous or unregistered participation is not acceptable. See [AGENTS.md](AGENTS.md), [New agent onboarding](#new-agent--web-terminal-agent-onboarding), and [Actor–Faucet ontology](lupo-docs/doctrine/ActorFaucetOntology.md).

## Table of Contents

- [Required Reading Before Using Lupopedia](#required-reading-before-using-lupopedia)
- [Canonical root rules](#canonical-root-rules)
- [New agent / web terminal agent onboarding](#new-agent--web-terminal-agent-onboarding) — **Start here: [ONBOARDING.md](ONBOARDING.md)**
- [What Lupopedia Is](#what-lupopedia-is)
- [Core Identity Model](#core-identity-model)
- [Core Concepts](#core-concepts)
- [Projects](#projects)
- [LUPOPEDIA HEADERS — The File/Database Bridge](#lupopedia-headers--the-filedatabase-bridge)
- [Architecture Overview](#architecture-overview)
- [Installation](#installation)
- [Usage](#usage)
- [GitHub Repository Strategy](#github-repository-strategy)
- [Documentation](#documentation)
- [Plan and Report](#plan-and-report)
- [Research Priorities for IDE Agents](#research-priorities-for-ide-agents)
- [Contributing](#contributing)
- [License](#license)

---

## Required Reading Before Using Lupopedia

Lupopedia is **doctrine-driven** and **header-driven**. To avoid invalid initialization, broken headers, or corrupted doctrine lineage, read the following in order before working with `lupopedia.init` or editing LUPOPEDIA HEADERS:

The **canonical, full onboarding reading list** is maintained in this file’s header at `lupopedia.init.required_reading` (top of file).

Minimum required set (read in order):

1. **[ONBOARDING.md](ONBOARDING.md)** — Operational quick-start; first file for new or existing IDE agents.
2. **[lupo-docs/INIT_README.md](lupo-docs/INIT_README.md)** — Prerequisites and "Before You Read This File" for anything that uses `lupopedia.init`.
3. **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)** — Header format, file order (first line `---`, identity line after closing `---`), and block order.
4. **[lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md](lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md)** — Full prerequisite doctrine list (versioning, directory structure, agent/faucet, semantic/collections) and why each is required.

**`lupopedia.init` is not the first file to read.** Understanding LUPOPEDIA HEADERS and versioning first ensures correct initialization and validation.

---

## Canonical root rules

**`lupo-rules/root/`** is the **canonical source of truth** for all behavioral and technical doctrine that governs Lupopedia. Every IDE agent, web terminal agent, and code-writing participant must read and follow these rules before contributing.

- **What they are:** Root rules are Markdown files (e.g. `database-logic-prohibition-doctrine.md`, `pdo-db-database-access-doctrine.md`) that define mandatory constraints: no database-side logic, PDO_DB-only access, PHP 5.6+ compatibility, install SQL as schema authority, TOON files as derived artifacts, actor/identity and context boundaries, and more. Each rule has a unique ID (e.g. DB001, ARC002, ACT001) and is tracked in a `lupopedia.rules` block.
- **Governance:** All agents and actors must follow the root rules. Lupopedia is non-standard (shared-hosting, fallback-first, no foreign keys, explicit timestamps, etc.); the root rules exist so that contributors and agents do not apply conventional framework assumptions that violate doctrine.
- **Derived outputs:** Agent-specific rule files (e.g. `.cursor/rules/*.mdc`, `.kiro/rules/*.md`, `.windsurf/rules/*.md`) are **generated** from the root rules by `lupo-scripts/propagate_agent_rules.php`. Do not treat those outputs as the source of doctrine; edit only `lupo-rules/root/` and re-run propagation for your target (e.g. `--target=cursor`).
- **Where to read:** Full rule text and index: [lupo-rules/root/README.md](lupo-rules/root/README.md). Individual rule files live in `lupo-rules/root/*.md`.

---

## New agent / web terminal agent onboarding

**First stop for new agents:** Read **[ONBOARDING.md](ONBOARDING.md)** for the operational quick-start (what to read first, non-negotiable rules, how to begin or continue work). It applies to IDE agents, external LLM agents, and human contributors.

If you are a **new IDE agent** (e.g. a new IDE or AI coding assistant joining the repo) or a **new web terminal agent**, you **must not** start contributing as an unnamed or anonymous participant. Lupopedia uses **explicit actor identity** and **registration** for safe multi-agent operation.

1. **Establish actor identity.** You must have an **actor** that represents you in the system. Actors are defined in `lupo_actors` and referenced in the project’s **actor registry**: `lupo-database/lupopedia/actors/actor_id/registry.json`. Resolve actor IDs and slugs from this registry; do not hardcode or invent IDs.
2. **Complete the actor registration checklist.** Follow the step-by-step process in **[lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)**. It covers registry update, database (or fallback) persistence, validation, and doctrine prerequisites. The checklist is derived from the TOON/database model and documents `lupo-database` fallback when the live DB is unavailable.
3. **Adopt the canonical root rules.** Before doing work, read and follow the rules in **`lupo-rules/root/`**. Use them as the source of truth for database access, schema changes, timestamps, PHP compatibility, and all other doctrinal constraints. If your environment uses derived rule files (e.g. `.cursor/rules/`), those are generated from the root rules—canonical doctrine lives in `lupo-rules/root/`.
4. **Avoid unregistered participation.** Operating as an untracked or anonymous worker undermines the actor model and makes it impossible to enforce doctrine and attribute work. Register, then contribute.

**Summary:** Do not “just start coding.” Complete the [actor registration checklist](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md), have an actor identity in the registry (and DB or fallback), review the root rules first, and use the root rules as source of truth. See also [AGENTS.md](AGENTS.md) for lead orchestration and registry usage.

---

## What Lupopedia Is

Lupopedia solves fragmented human–AI workflows with a **unified Semantic OS** on top of Crafty Syntax live chat:

- **Actors orchestrate** — Actors are the orchestration identities in `lupo_actors` (actor_name is PRIMARY KEY); they coordinate and govern through faucets, sessions, channels, rules, and traits. **Faucets execute** — IDE surfaces (Cursor, Antigravity, Kiro, Windsurf, etc.) are faucets, not actors; the actor operates *through* the faucet.
- **Channel-based communication** — Threads, tasks, and rich metadata for coordination on **channels** (Channel 42 is the canonical development channel).
- **LUPOPEDIA HEADERS** — Self-describing artifacts (YAML headers) for file identity, doctrine, and routing; stored in `lupo_metadata` and optionally written to the file.
- **Comments system** — New in 4.0.73: threaded comments on artifacts with full faucet traceability via `lupo_comments` table and `lupopedia.comments` header block.

**Target audience:** Developers building agents, admins managing systems, contributors to open-source AI-collab tooling.

[Core doctrine](lupo-docs/doctrine/) | [LUPOPEDIA HEADERS](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) | [Comments System](lupo-docs/database/lupopedia/tables/active/lupo_comments.md)

---

## Core Identity Model

One thing that must be understood clearly is that Lupopedia separates human account identity from operational orchestration identity.

### Auth Users
Humans live in the `lupo_auth_users` tables.  
These are the real authenticated people in the system. They have login credentials and account-level identity. Auth users represent:
- real human users
- authentication and login
- account ownership
- permissions at the account level

### Actors
Actors live in `lupo_actors` and are the operational identity layer used inside the semantic system. Actors are what channels, tasks, artifacts, headers, sessions, and orchestration use. Every participant in Lupopedia is represented operationally by an actor, including:
- AI-led participants
- human-led participants
- hybrid human+AI working identities

### Agents
Agents live in `lupo_agents` and are AI/runtime metadata, not the identity itself. They define model/provider/prompt/runtime characteristics for AI behavior.

### Faucets
Faucets live in `lupo_agent_faucets` and are the execution surfaces through which actors operate. Examples include Cursor, Windsurf, Antigravity, Kiro, Warp, JetBrains, Codex.  
A faucet is not the actor. It is the surface/environment the actor uses. **Actors orchestrate, faucets execute.**

**Why this distinction matters:** A human may exist as an auth user, but collaboration in channels and artifacts happens through an actor. This separation enables AI-led workflows, human-led workflows, paired workflows, multi-agent orchestration, and channel-based role scoping. 

*Actor ID rules:*
- `actor_id < 1000` → system or AI-oriented identity
- `actor_id >= 1000` → primarily human-oriented identity

## Core Concepts

### Projects
**Projects** are a first-class semantic layer above channels. A project groups related channels, collections, and dialogs within a federation node. **IDE agents** infer project context from the workspace. **External agents** must declare project (and channel/thread) explicitly in every request. 

**Design Package:** The complete Project Registry design includes:
- Doctrine: [lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md](lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md)
- Schema Design: [lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md](lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md)
- Workflow: [lupo-docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md](lupo-docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md)

**API and External Actors:** [lupo-docs/projects/PROJECTS.md](lupo-docs/projects/PROJECTS.md) and [lupo-docs/projects/PROJECTS_API.md](lupo-docs/projects/PROJECTS_API.md). **Upgrade and migration (4.0.76):** [CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md](lupo-docs/status/CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md).

### Channels
Channels are collaboration contexts where work happens. Each channel belongs to exactly one project. They contain dialog, tasks, participants, uploads, artifacts, context, and semantic history. Key tables include `lupo_channels`, `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_dialog_threads`, `lupo_dialog_messages`. Channel 42 is the canonical Lupopedia development channel.

### Sessions
Sessions carry runtime context. A session identifies who is acting, through what faucet, in which channel, with which paired context. Session state may exist in `lupo_sessions` (DB) and `lupo-database/sessions/*.md` (IDE session files).

### Traits & Roles
- **Traits** are intrinsic properties or constraints on actors. They are actor-scoped and not channel-local.
- **Roles** are channel-local permissions assigned per actor per channel.

### Tasks
Tasks are transient work items that live within the orchestration system.

### Collections
Collections are semantic groupings, not folders. They can group files, pages, artifacts, channels, objects, and external resources. A collection organizes meaning, not storage location.

### Federation Nodes
Every Lupopedia installation is a federation node (`Node 1` is usually the current installed domain). Federation allows cross-domain semantic linkage while keeping each node autonomous.

### Monitoring Widgets
Because Lupopedia runs in a subfolder, it cannot automatically see all root-domain activity. To bridge that gap, it uses monitoring widgets such as JS snippets generated by endpoints like `livehelp_js.php`. These widgets can report current page, referrer, click events, and navigation source, and open live chat.

## LUPOPEDIA HEADERS — The File/Database Bridge

This is one of the most important parts of Lupopedia. **LUPOPEDIA HEADERS** are structured YAML blocks at the top of `.md` files and other artifact-like objects. They are the bridge between database state and filesystem artifacts.

**Why headers exist:** The database holds live relational state. The filesystem holds persistent artifacts. Neither alone is enough. The database can be unavailable. A file can be moved, copied, federated, or read offline. Headers solve this by embedding structured snapshots of semantic context (identity, routing, authorship, session context, semantic relationships) directly into the artifact.

**Important principle:** Headers are not merely decorative frontmatter; they are part of the semantic operating system.

### Main header sections
- `lupopedia.init` — Initialization identity and artifact classification (e.g. file identity, artifact type).
- `lupopedia.metadata` — Snapshot of metadata rows derived from `lupo_metadata`.
- `lupopedia.headers` — Core operational tracking fields (version, schema, file path, actor id, channel id).
- `lupopedia.session` — Runtime context snapshot (actor, faucet, paired actor).
- `lupopedia.edges` — Snapshot of `lupo_edges` outbound semantic relationships (groups links to other docs, code, schema). 
- `lupopedia.engagement` — Snapshot of engagement metrics derived from analytics.
- `lupopedia.footer` — Verification and next-action context.

## Architecture Overview

**Filesystem layout:**
Lupopedia organizes itself via directories with the `lupo-` prefix: `/lupo-api`, `/lupo-uploads`, `/lupo-channels`, `/lupo-agents`, `/lupo-actors`, `/lupo-docs`, `/lupo-prompts`, and related `lupo-*` folders. These hold artifacts for documentation, channel content, agent/session data, uploaded objects, semantic references, and registry material. The **`legacy/`** folder is the intentional exception: it holds legacy read-only code (e.g. Crafty Syntax reference) and is not renamed to `lupo-legacy`.

**Database domains:**
The database schema is defined in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — **install SQL is the authoritative schema source**. TOON files are **derived artifacts** (do not edit by hand); they are generated into `lupo-database/lupopedia/toon/` by `python lupo-scripts/generate_toon_from_sql.py` or `python lupo-scripts/generate_toon_files.py`. The former path is canonical (no longer `lupo-docs/toons/`). Canonical table count: **161** as of 4.0.77 (see [TABLE_COUNT_DOCTRINE](lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md)); table ceiling is advisory only. Domains include identity (`lupo_actors`, `lupo_auth_users`), orchestration (`lupo_agents`, `lupo_agent_faucets`), projects (`lupo_projects`), collaboration (`lupo_channels`, `lupo_dialog_messages`), semantics (`lupo_metadata`, `lupo_edges`, `lupo_collections`), telemetry (`lupo_sessions`), federation, and legacy migration. The `lupo-database/` directory is protected from direct web access (e.g. `.htaccess` hardening) so that schema and seed assets are not served over HTTP.

**Doctrine reminder (non-standard architecture):** Lupopedia is not a conventional framework application. Critical constraints: **no foreign keys, triggers, stored procedures, or stored functions**; all logic in application code; **explicit column naming** on inserts where doctrine requires it; timestamps as **BIGINT UTC** in `YYYYMMDDHHIISS` format, set in PHP (e.g. `gmdate('YmdHis')`), never database-generated; **install SQL authoritative**, TOON derived; shared-hosting and unknown-environment assumptions; fallback-first behavior. These and other rules are codified in [lupo-rules/root/](lupo-rules/root/).

**Filesystem/database relationship:**
The database is the live relational layer. The filesystem is the artifact memory layer. Headers are the synchronization and portability layer between them.

## Installation

### New installation
1. Clone the repository into a web-accessible subdirectory.
2. Point your web server to the project root.
3. Run the installer through the browser.

```bash
git clone https://github.com/lupopedia/lupopedia.git
cd lupopedia
```

Then visit: `https://your-host/lupopedia/install.php`

### Upgrade from Crafty Syntax 3.7.5
The supported upgrade path is: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. There is **no Lupopedia→Lupopedia upgrade** in 4.0.x; version-to-version upgrades start in 4.1.0. See [lupo-docs/doctrine/UPGRADE_POLICY_DOCTRINE.md](lupo-docs/doctrine/UPGRADE_POLICY_DOCTRINE.md) for the full policy.

1. Back up the old database and files.
2. Load/install Lupopedia schema + data seeds.
3. Follow the migration mapping documentation. 
4. Validate the upgrade output carefully.

## Usage

**CLI commands:**
- `whoami` — show current identity and context
- `context` — show full execution context
- `doctor` — run system health checks (`lupo-bin/lupo.php doctor`)
- `doctor-context --repair` — repair/sync context state
- `help` — show CLI help

## GitHub Repository Strategy

Current active development through version 4.1.0 is in: `https://github.com/wisdomoflovingfaith/lupopedia`
Planned future canonical organization: `https://github.com/lupopedia`

Architectural rule: all real logic should live in `core`, with other repos serving as surfaces/adapters (`web`, `cli`, `docs`, `vercel`). 

## Documentation

Primary references:
- `lupo-docs/HELP.md`
- `lupo-docs/CLI.md`
- `lupo-docs/DOCTOR_HEALTH_CHECK.md`
- `lupo-docs/TOON_REFERENCE.md`
- `lupo-docs/version.md`
- `CHANGELOG.md`
- `lupo-docs/doctrine/`

## Plan and Report

Root consolidated [plan.md](plan.md) and [report.md](report.md) are maintained by **Cursor IDE** (lead orchestration). They synthesize inputs from Kiro, Windsurf, Codex, and Antigravity faucet-specific plans and reports. See [AGENTS.md](AGENTS.md) for the seven IDE agents and lead/supporting actor roles.

## Research Priorities for IDE Agents

IDE agents are actively researching and documenting these critical areas to fully align the system:

1. **Header–Database Bridge**: Explicitly defined in `lupo-docs/doctrine/LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE.md`.
2. **Auth User ↔ Actor Relationship**: Defined in `lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md`.
3. **Actor / Agent / Faucet Ontology**: Defined canonically in Identity Layers Doctrine.
4. **Channel and Dialog System**: Managing `lupo_dialog_messages` vs threads vs filesystem tasks.
5. **Collections and Edge Semantics**: Graph semantic relationships and tab collections.
6. **Filesystem Object Doctrine**: Defined in `lupo-docs/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS.md`.

## Contributing

See `CONTRIBUTING.md`. All contributions should follow doctrine: UTC YmdHis timestamps, actor-centered orchestration, header-driven artifacts, deterministic IDs, and NO FOREIGN KEYS where forbidden by doctrine.

## License

See `license.txt` in the repository. Free to use, modify, and distribute under the terms specified there.

---
*Lupopedia 4.0.78 — a semantic operating system orchestrated by humans and AI agents across channels, artifacts, and federation nodes. Canonical doctrine: lupo-rules/root/.*
