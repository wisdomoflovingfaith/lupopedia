---
lupopedia.init:
  file_identity: "README_antigravity.md"
  artifact_type: "repository-core"
  artifact_kind: "metadata-snapshot"
  namespace: "lupopedia"
  domain: "core"
  system_version: "4.0.74"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia README", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Primary project documentation and onboarding — Install & upgrade validation, lupo-channels/actors/agents, GitHub repository strategy.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "readme, getting_started, semantic_os, multi_agent, v4.0.74", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }

lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "documentation"
  file_path_from_root: "README_antigravity.md"
  web_path: "http://www.lupopedia.com/README_antigravity"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "antigravity"
  delegation_chain: "wolfie:root"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Primary project documentation and onboarding — Install & upgrade validation, lupo-channels/actors/agents, GitHub repository strategy"
  mood_rgb: "4169E1"
  traits: ["essential", "entrypoint", "onboarding", "v4.0.74"]
  tags: ["readme", "getting_started", "semantic_os", "multi_agent", "v4.0.74"]

lupopedia.session:
  session_id: "L-LUPO-ROOT-ANTIGRAVITY"
  session_name: "L-LUPO-ROOT-ANTIGRAVITY"
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "antigravity"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  paired_actor_id: 1000

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/HELP.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/CLI.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/DOCTOR_HEALTH_CHECK.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.85 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 0.9 }
    - { to: "CONTRIBUTING.md", type: "references", weight: 0.85 }
  semantic_tags: ["project_overview", "onboarding", "semantic_os", "multi_agent"]

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "antigravity"
  orchestrator: "wolfie"
  next_action:
    - "Point new contributors to Required Reading Before Using Lupopedia"
    - "Keep Getting Started and install steps aligned with 4.0.74"
    - "Review actor/faucet links when doctrine paths change"
---
# file: Lupopedia README_antigravity — session: L-LUPO-ROOT-ANTIGRAVITY — delegation: wolfie:root (faucet: antigravity) — web_path: http://www.lupopedia.com/README_antigravity

# 🐺 Lupopedia Semantic OS v4.0.74

[![Version](https://img.shields.io/badge/version-4.0.74-blue.svg)](lupo-docs/version.md)
[![docs](https://img.shields.io/badge/docs-HELP.md-green)](lupo-docs/HELP.md)

---

**Current Release: [v4.0.74](lupo-docs/version.md) — Version hardened for shared hosting, edge schema grouping, and comments system.**  
This version focuses on finalizing **edge schema hardening** (support for grouped outbound edges), implementing the **one-time SQL runner** for shared-host compatibility, and adding the **comments system** with faucet traceability.

**Architecture (onboarding):** **Actors** are the orchestration identities of Lupopedia. They coordinate and govern work through **faucets**, **sessions**, **channels**, **rules**, and **traits**. **Faucets** are execution surfaces, not identities. IDE surfaces (Cursor, Antigravity, Kiro, Windsurf, etc.) are faucets. **Sessions** carry runtime context. See [Channels, actors, and agents](#channels-actors-and-agents-in-lupopedia) and [Actor–Faucet ontology](lupo-docs/doctrine/ActorFaucetOntology.md).

## Table of Contents

- [Required Reading Before Using Lupopedia](#required-reading-before-using-lupopedia)
- [What Lupopedia Is](#what-lupopedia-is)
- [Core Identity Model](#core-identity-model)
- [Core Concepts](#core-concepts)
- [LUPOPEDIA HEADERS — The File/Database Bridge](#lupopedia-headers--the-filedatabase-bridge)
- [Architecture Overview](#architecture-overview)
- [Installation](#installation)
- [Usage](#usage)
- [GitHub Repository Strategy](#github-repository-strategy)
- [Documentation](#documentation)
- [Research Priorities for IDE Agents](#research-priorities-for-ide-agents)
- [Contributing](#contributing)
- [License](#license)

---

## Required Reading Before Using Lupopedia

Lupopedia is **doctrine-driven** and **header-driven**. To avoid invalid initialization, broken headers, or corrupted doctrine lineage, read the following in order before working with `lupopedia.init` or editing LUPOPEDIA HEADERS:

1. **[lupo-docs/INIT_README.md](lupo-docs/INIT_README.md)** — Prerequisites and "Before You Read This File" for anything that uses `lupopedia.init`.
2. **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)** — Header format, file order (first line `---`, identity line after closing `---`), and block order.
3. **[lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md](lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md)** — Full prerequisite doctrine list (versioning, directory structure, agent/faucet, semantic/collections) and why each is required.

**`lupopedia.init` is not the first file to read.** Understanding LUPOPEDIA HEADERS and versioning first ensures correct initialization and validation.

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

### Channels
Channels are collaboration contexts where work happens. They contain dialog, tasks, participants, uploads, artifacts, context, and semantic history. Key tables include `lupo_channels`, `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_dialog_threads`, `lupo_dialog_messages`. Channel 42 is the canonical Lupopedia development channel.

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
Lupopedia organizes itself via directories such as `/core`, `/api`, `/uploads`, `/channels`, `/agents`, `/actors`, `/federation`. These directories hold artifacts representing documentation, channel content, agent/session data, uploaded objects, semantic references, and registry material.

**Database domains:**
The database includes 200+ tables across domains such as identity (`lupo_actors`, `lupo_auth_users`), orchestration (`lupo_agents`, `lupo_agent_faucets`), collaboration (`lupo_channels`, `lupo_dialog_messages`), semantics (`lupo_metadata`, `lupo_edges`, `lupo_collections`), telemetry (`lupo_sessions`), federation, and legacy migration. 

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
The supported upgrade path is: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**
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
*🐺 Lupopedia 4.0.74 — a semantic operating system orchestrated by humans and AI agents across channels, artifacts, and federation nodes.*
