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
    - path: "lupo-docs/versions/4.0.86/"
      reason: "Version 4.0.86 comprehensive documentation and status"
    - path: "EXECUTIVE_SUMMARY.md"
      reason: "Philosophy and architecture (why system is designed this way)"
    - path: "plan.md"
      reason: "Current implementation plan"
    - path: "report.md"
      reason: "Current status and report"
  required_context:
    - "lupo-docs/ is the canonical documentation root."
    - "Cursor (actor_id 102) is lead orchestration; install SQL is schema authority."
    - "Scripts re-generate header snapshots; do not assume init/header content is hand-only."
    - "Version 4.0.86 is SCOPE LOCKED to Channels 58, 59, 60, and 61."

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia README", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260323000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Primary project documentation — deterministic multi-agent semantic OS; database-backed knowledge graph + doctrine-driven filesystem + MySQL/Postgres backend. Version 4.0.86 with comprehensive actor system, context graph architecture, and canonical role layer enforcement.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260323000000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "readme, getting_started, semantic_os, multi_agent, deterministic_os, knowledge_graph, doctrine_filesystem, database_backend, v4.0.86, canonical_role_layer, context_graph, actor_system", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260323000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }

lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "README.md"
  last_modified_utc: "20260323_230000"
  channel_id: 42
  thread_id: "1001"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "project_documentation"
  artifact_kind: "readme"
  purpose: "Primary project documentation with version 4.0.86 comprehensive updates."
  tags: ["wolfie", "readme", "v4.0.86", "project_overview"]
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
  last_verified: "20260321"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Point new contributors to Required Reading (INIT_README, LUPOPEDIA_HEADERS), lupo-rules/root/, and actor registration checklist (lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md)"
    - "Single active version: 4.0.85 — see CHANGELOG.md, TODO.md, plan.md, notes_for_next_session.md, and WOLFIE master consolidation (lupo-channels/1/threads/1035/20260319_190000_wolfie_master_shutdown_consolidation.md)"
    - "README deterministic OS + knowledge-graph + DB backend section stays aligned with install SQL / TOON regeneration"
    - "Actor–facet separation + channel migration execution are 4.0.82 scope; deferred DB-primary/UI/dedupe items are in root TODO.md"
    - "Operational reality sections (§3-7) now canonical; sync all architecture docs to dual-mode model (Thread 1033, §5)"
---
# file: Lupopedia README — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/

# Lupopedia Semantic OS v4.0.86

[**GitHub repo**](https://github.com/wisdomoflovingfaith/lupopedia)

[![Version](https://img.shields.io/badge/version-4.0.86-blue.svg)](lupo-docs/version.md)
[![docs](https://img.shields.io/badge/docs-HELP.md-green)](lupo-docs/HELP.md)

---

## Controlled Synchronization v9

- Authoritative task and question state lives only in `lupo-docs/versions/4.0.85/TASK_REGISTRY.md`.
- `lupo-channels/*/THREAD_INDEX.md` files are derived navigation surfaces only.
- LILITH output is validation-only; violations route through `lupo-docs/versions/4.0.85/CONTRADICTIONS.md` and linked task entries.

---

## Lupopedia as a deterministic, multi-agent semantic OS

**Lupopedia** is a **deterministic semantic operating system** built from two durable lineages and adapted for current multi-actor work.

- From **Crafty Syntax** it keeps the idea that real work happens through **channels, threads, and human dialog**.
- From **Doom Emacs research** it takes conceptual lessons about **relationships, composition, layered structure, and collections of relationships**.

It is not a generic chatbot wrapper, not just a ticket queue, and not only a graph database. It is a system for coordinating humans, actors, agents, rules, documents, and decisions in a way that can be inspected, replayed, and validated.

### What is first-class in Lupopedia

| Layer | What it means in practice |
|---|---|
| **Edges and metadata** | `lupo_edges` and `lupo_metadata` store typed relationships and properties as first-class system data rather than treating links as informal prose only. |
| **Channels and threads** | `lupo_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`, and filesystem thread artifacts provide the coordination structure for ongoing work. |
| **Actors and humans** | `lupo_actors`, `lupo_auth_users`, and `lupo_actor_auth_users` separate orchestration identity from login identity and support many-to-many human support pools. |
| **Decisions and contradictions** | `lupo_decisions`, decision-evidence tables, `TASK_REGISTRY.md`, and `CONTRADICTIONS.md` support traceability instead of hidden judgment. |
| **Doctrine** | Root rules and doctrine files replace implicit framework behavior with explicit constraints that agents and humans can follow repeatably. |

### Why it is structured this way

**Crafty Syntax was chosen for durability of the human interaction model.** It has more than 20 years of real-world live-help usage behind its channel, thread, and dialog patterns. Lupopedia keeps that proven shape because human coordination is not theoretical here; it needs a model that already survived real operational use.

**Doom Emacs research was chosen for durability of compositional structure.** The relevant lesson is not “Emacs as an editor.” The useful lesson is that a long-lived system can remain extensible when relationships, ordering, gating, and layered composition are explicit. Lupopedia applies that concept to edges, collections, task dependencies, contradictions, and decision lineage. In 4.0.85 this remains **research-informed and partial**, not a claim of completed Doom-derived implementation.

### Database + filesystem duality

Lupopedia is intentionally split across two authoritative modes that describe the same system from different angles:

- The **database** holds runtime state: actors, auth users, channels, threads, messages, metadata, edges, tasks, routing decisions, and request records.
- The **filesystem** holds doctrine, thread artifacts, version documentation, LUPOPEDIA HEADERS, and agent-readable continuity records.

This is not duplication for its own sake. The database is the live operational substrate. The filesystem is the inspectable, git-native, external-AI-readable coordination surface. Together they let Lupopedia work as both a running application and a doctrine-driven repository.

### Current 4.0.85 reality

In 4.0.85, Lupopedia already has the structural foundation for channel/thread/dialog work, but the system is still uneven across surfaces:

- dialog already exists in **channels, threads, artifacts, and schema**
- actor-to-human routing has an implemented **MVP** with deterministic selection and audited corrections
- actor to auth-user support pools use the corrected **many-to-many** relationship model from Thread 2011
- version-folder governance replaced flat root status tracking for 4.0.85+
- Thread 2013 explicitly cleared the current system for the canonical drop -> Crafty import -> install.php cycle
- the **web dialog interface is not complete yet**
- current real usage still depends heavily on **filesystem artifacts, CLI workflows, IDE faucets, and external AI participation**

So the right description is: **foundational and operational in parts, but not yet a finished end-user dialog UI.**

---

## 🚧 Current Development — v4.0.85 (active development)  
**Status**: 4.0.85 is a stabilization and synchronization version. It exists to bring schema authority, TOON parity, thread/task authority, actor/auth routing semantics, and documentation surfaces into a coherent state before larger feature work continues. The only supported upgrade path remains **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**.

**What 4.0.85 established:**
- **Thread 1047** — TASK_REGISTRY as the only authoritative task surface; THREAD_INDEX demoted to navigation-only
- **Thread 2004** — install SQL and TOON parity restored; stale `lupo_visibility_state` projection removed
- **Thread 2011** — many-to-many `actor` ↔ `auth_user` support model corrected and validated for routing use
- **Thread 2012** — deterministic dialog routing MVP implemented, corrected, and validated as COMPLIANT
- **Thread 2013** — dual PASS install-readiness verdict issued for install schema and runtime system
- **Thread 2015** — `mood_rgb` resolved into authoritative canonical tokens plus non-authoritative routing vector semantics
- **4.0.85 version directory** — version-specific docs now carry the detailed record that no flat root changelog can safely represent

**Final 4.0.85 declaration:** install ready + system compliant. Structured changes are documented in `lupo-docs/versions/4.0.85/`.

Final documentation consolidation (Channel 42, Thread 2016) confirms version 4.0.85 as INSTALL READY + SYSTEM COMPLIANT with authoritative details under `lupo-docs/versions/4.0.85/`.

**Version-folder orientation:**
- `lupo-docs/versions/4.0.85/README.md` explains what 4.0.85 is and why the version directory exists
- `lupo-docs/versions/4.0.85/OVERVIEW.md` explains system-level outcomes of the version
- `lupo-docs/versions/4.0.85/OVERVIEW_ORGANIZATION.md` explains how work, authority, and documentation surfaces are organized

**Canonical root rules:** All agents and actors must follow the doctrine in **`lupo-rules/root/`**. Agent-specific rule files (e.g. `.cursor/rules/`, `.kiro/rules/`, `.windsurf/rules/`) are **derived** from those root rules; the root is the single source of truth. See [Canonical root rules](#canonical-root-rules) and [New agent onboarding](#new-agent--web-terminal-agent-onboarding).

**Architecture (onboarding):** **Actors** are the orchestration identities of Lupopedia. They coordinate through **faucets**, **sessions**, **channels**, and **rules**. **Faucets** are execution surfaces (IDE or web terminal), not identities. **Cursor IDE** (actor_id 102) is the **lead orchestration actor**, with **Wolfie** (actor_id 1) as supporting actor. A **new IDE agent or new web terminal agent** must **create and register an actor** before participating; anonymous or unregistered participation is not acceptable. See [AGENTS.md](AGENTS.md), [New agent onboarding](#new-agent--web-terminal-agent-onboarding), and [Actor–Faucet ontology](lupo-docs/doctrine/ActorFaucetOntology.md).

## Table of Contents

- [Required Reading Before Using Lupopedia](#required-reading-before-using-lupopedia)
- [Canonical root rules](#canonical-root-rules)
- [New agent / web terminal agent onboarding](#new-agent--web-terminal-agent-onboarding) — **Start here: [ONBOARDING.md](ONBOARDING.md)**
- [Lupopedia as a deterministic, multi-agent semantic OS](#lupopedia-as-a-deterministic-multi-agent-semantic-os)
- [What Lupopedia Is](#what-lupopedia-is)
- [Core Identity Model](#core-identity-model)
- [Channel filesystem and HERMES routing (4.0.80+)](#channel-filesystem-and-hermes-routing-4080)
- [Thread Model and Task Management (4.0.81+)](#thread-model-and-task-management-4081)
- [Project → Channel → Thread → Task Hierarchy (4.0.81+)](#project--channel--thread--task-hierarchy-4081)
- [Core Concepts](#core-concepts)
- [External AI Model (4.0.81+)](#external-ai-model-4081)
- [§3 Current Operational Reality (4.0.85)](#3-current-operational-reality-4085)
- [§4 Dual-Mode Architecture](#4-dual-mode-architecture)
- [§5 Channel 66 Question Graph System (4.0.85+)](#5-channel-66-question-graph-system-4085)
- [§6 External AI Zero-Database Access Constraint](#6-external-ai-zero-database-access-constraint-binding)
- [§7 Project_id Implementation Requirements (4.0.85)](#7-project_id-implementation-requirements-4085)
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
- **Derived outputs:** Agent-specific rule files (e.g. `.cursor/rules/*.mdc`, `.kiro/rules/*.md`, `.windsurf/rules/*.md`) are **generated** from the root rules by `lupo-scripts/propagate_agent_rules.php`. **Validation Rule:** Derived rules must be regenerated via `propagate_agent_rules.php` whenever a root rule changes. Agents executing with stale derived rules are out-of-compliance and risk operating on deprecated constraints. A repository validation hook `verify_agent_rules_sync.php` is intended to execute on formal PRs to automatically fail the commit if root rules drift from the derived IDE files. Do not treat those outputs as the source of doctrine; edit only `lupo-rules/root/` and re-run propagation.
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

## Versioning Model (Locked)

Lupopedia uses single-field versioning for all new artifacts:

**version_when_written only**

- New artifacts store only `version_when_written` (immutable creation version)
- Minimum baseline for handwritten/hand-edited LUPOPEDIA HEADERS is `version_when_written: "4.0.85"`; if a file is below that baseline (or contains deprecated version keys), tooling MUST rewrite `lupopedia.headers` on save to the current system version (baseline rewrite-on-write).
- Runtime version is resolved dynamically from the canonical resolver
- No stored runtime version in artifact headers

**runtime version resolved dynamically**

- Current system version obtained from `get_lupopedia_system_version()`
- Primary source: `LUPEDIA_VERSION` file
- Secondary sources: version.php runtime helper, config fallback

**no stored runtime version**

- Forbidden fields: `lupopedia.version` and `system_version`
- Validators reject these fields in new artifacts
- Prevents version drift and duplicated state

**Doctrine Status:** 🔒 LOCKED - Thread 1005 closed and doctrine-locked

---

## What Lupopedia Is

Lupopedia solves fragmented human–AI workflows with a **unified Semantic OS** on top of Crafty Syntax live chat. It combines a **database-backed knowledge graph** (`lupo_metadata`, `lupo_edges`, actors, channels, decisions—table shapes appear as **TOON** `.toon.json` files under `lupo-database/lupopedia/toon/` when regenerated from install SQL via `python lupo-scripts/generate_toon_from_sql.py`) with a **doctrine-driven filesystem** (rules, docs, channel artifacts, headers). The [section above](#lupopedia-as-a-deterministic-multi-agent-semantic-os) states that model in one place.

- **Actors orchestrate** — Actors are the orchestration identities in `lupo_actors` (actor_name is PRIMARY KEY); they coordinate and govern through faucets, sessions, channels, rules, and traits. **Faucets execute** — IDE surfaces (Cursor, Antigravity, Kiro, Windsurf, etc.) are faucets, not actors; the actor operates *through* the faucet.
- **Channel-based communication** — Threads, tasks, and rich metadata for coordination on **channels** (Channel 42 is the canonical development channel).
- **LUPOPEDIA HEADERS** — Self-describing artifacts (YAML headers) for file identity, doctrine, and routing; stored in `lupo_metadata` and optionally written to the file.
- **Comments system** — New in 4.0.73: threaded comments on artifacts with full faucet traceability via `lupo_comments` table and `lupopedia.comments` header block.

**Target audience:** Developers building agents, admins managing systems, contributors to open-source AI-collab tooling.

[Core doctrine](lupo-docs/doctrine) | [LUPOPEDIA HEADERS](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md) | [Comments System](lupo-docs/database/lupopedia/tables/active/lupo_comments.md)

---

## Channel filesystem and HERMES routing (4.0.80+)

**Active coordination** for multi-agent work lives under **`lupo-channels/{channel_id}/`** (channel **42** = default dev workspace). **`lupo-docs/status/`** is **not** the primary coordination sink — archival / redirect only; see **`lupo-docs/status/README.md`**.

### Core concepts

| Concept | Meaning |
|---------|---------|
| **Actors** | Orchestration identities (`lupo_actors`); WOLFIE (1), LILITH (2), **HERMES (15)** routing, IDE faucets execute as paired actors. |
| **Channels** | Scoped workspaces (`lupo_channels`); artifacts and membership are channel-bound. |
| **Threads** | Numeric **`dialog_thread_id`** only under `threads/{id}/`; row must exist in DB before thread posts (Option A). |
| **Artifacts** | Markdown + **LUPOPEDIA HEADERS** — directives, reviews, repair reports, **HERMES prompts**. |
| **Routing** | API + **`Lupo_Channel_Message_Router`**; filenames **`YYYYMMDD_HHIISS_actor_purpose.md`**; see **CHANNEL_ARTIFACT_ROUTING_DOCTRINE**. |
| **HERMES** | **Heuristic Event Routing & Messaging Exchange System** — reads artifacts, classifies by **`artifact_kind`** / **`message_type`** / intent (not filename alone), writes **`prompts/`** handoffs for target actors. **Not** WOLFIE; **actor_id 15** on HERMES files only. |

### Directory layout (`lupo-channels/42/` example)

```
lupo-channels/42/
├── broadcasts/      # channel-wide
├── content/       # durable docs
├── direct/{actor_id}/
├── tasks/
├── threads/{thread_id}/   # numeric thread id only
├── rules/
└── prompts/         # HERMES → target-actor execution prompts
```

### Deterministic flow

1. Actors write artifacts into the channel tree (thread / broadcast / direct / content).
2. Artifacts record communication and state (headers + body).
3. **HERMES** reads non-prompt artifacts and classifies intent.
4. **HERMES** writes **`prompts/YYYYMMDD_HHIISS_hermes_prompt_{target}_{purpose}.md`** with task, expected output, source reference. **MVP:** `python lupo-scripts/draft_hermes_prompt_from_artifact.py --artifact <thread.md> --target <slug> --purpose <slug> --write` drafts that file from a thread artifact (HERMES reviews before treating as final). **Full-auto HERMES** (unattended classification + prompt emission) is **Phase 3** — not required for 4.0.x MVP.
5. Target actors execute work; they publish **their own** artifacts under **their** `actor_id` (no impersonation).
6. Cycle repeats.

**Doctrine:** [MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md) · [CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md](lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md) · [prompts/README](lupo-channels/42/prompts/README.md)

---

## Thread Model and Task Management (4.0.81+)

Lupopedia uses a **one-thread-per-task** model for organized, traceable multi-agent coordination. This ensures clear work boundaries, complete audit trails, and deterministic task execution.

### One-Thread-Per-Task Doctrine

**Core Principle**: Each distinct task or work item executes in exactly one dedicated thread.

This principle provides:
- **Clear Lineage**: Every artifact has an unambiguous task context
- **Traceable Execution**: Complete history of work per task
- **Isolated Scope**: No mixing of different tasks in the same thread
- **Deterministic Routing**: Clear paths for coordination and handoffs

**Enforcement**: Thread allocation is controlled by WOLFIE (actor_id 1) through explicit directives. New tasks must not be created in existing threads without explicit allocation.

### Task ID vs Thread ID Separation

**Critical Distinction**: `task_id` and `thread_id` are separate entities with different purposes.

#### Task ID (`task_id`)
- **Purpose**: Stable identity of the work item itself
- **Format**: Human-readable identifiers (e.g., `task_doc_001`, `impl_042`, `review_007`)
- **Stability**: Remains constant even if thread allocation changes
- **Usage**: Appears in filenames, metadata, and cross-references

#### Thread ID (`thread_id`)
- **Purpose**: Container identifier for task execution
- **Format**: Numeric database ID (e.g., 1001, 1002, 1003)
- **Stability**: May change through reassignment directives
- **Usage**: Directory structure and database references

#### Canonical Filename Convention
```
YYYYMMDD_HHIISS_{actor}_{type}_{task_id}_{purpose}.md
```

Examples:
- `20260318_170000_thoth_directive_task_doc_001_kickoff.md`
- `20260318_120000_hephaestus_status_impl_042_schema-complete.md`
- `20260318_140000_lilith_review_review_007_security-audit.md`

### Thread Lifecycle Management

Threads progress through five canonical states with explicit transitions:

| State | Meaning | Who Can Set | Required Evidence |
|-------|---------|-------------|-------------------|
| **open** | Thread exists, ready to begin work | WOLFIE, Task Owner | Creation/assignment directive |
| **active** | Work is in progress | Task Owner, WOLFIE | Status artifact declaring transition |
| **blocked** | Awaiting dependency | Task Owner, WOLFIE | Blocking status with dependency reference |
| **resolved** | Work complete, reviewed if required | Task Owner (proposes), WOLFIE (confirms) | Completion artifact + review if needed |
| **archived** | Historical preservation only | WOLFIE only | WOLFIE directive declaring archival |

**Key Rules**:
- No hidden transitions - all state changes must be explicitly declared
- `archived` is terminal - no transitions out of archived state
- Single owner per thread - clear responsibility for state management

### Legacy Thread Handling

#### Historical Threads (1001, 1002)
- **Thread 1001**: Temporary triage thread for doctrine correction
- **Thread 1002**: Historical migration thread
- **Status**: Will transition to `archived` after 4.0.81 doctrine alignment
- **Access**: Read-only reference with explicit cross-references

#### Legacy Reference Protocol
When referencing historical artifacts:
1. Use full explicit path references
2. Add "Legacy Reference" section explaining relevance
3. Clearly distinguish from new doctrine practices
4. Do not modify historical artifacts

#### New Work Allocation
All new tasks must:
- Use thread allocation from dynamic thread pools
- Follow canonical filename convention with `task_id`
- Create dedicated threads via WOLFIE directive
- Maintain clear separation from legacy threads

### Contributor Guidance for Thread Usage

#### Getting a Thread for New Work
1. **Define Task Scope**: Clearly articulate the work item and its boundaries
2. **Request Allocation**: Submit work proposal to WOLFIE for thread assignment
3. **Receive Directive**: Wait for explicit WOLFIE allocation directive
4. **Create Kickoff**: Post initial artifact in allocated thread with proper metadata

#### Best Practices
- **Single Scope**: Keep one task per thread - no mixing
- **Clear Transitions**: Explicitly declare all state changes
- **Proper Filenames**: Use canonical convention with `task_id`
- **Complete Metadata**: Include all required LUPOPEDIA HEADERS
- **Cross-References**: Link to related work with explicit paths

#### Common Pitfalls to Avoid
- **Mixed Scope**: Don't add different tasks to existing threads
- **Implicit Transitions**: Never assume state changes without declaring them
- **Legacy Modification**: Don't modify historical artifacts
- **Missing task_id**: Always include task identifier in filenames
- **Ambiguous Ownership**: Ensure single clear owner per thread

#### Thread Reassignment (When Needed)
If work must move to a new thread:
1. WOLFIE issues explicit reassignment directive
2. Old thread posts closing status referencing directive
3. New thread posts kickoff referencing directive
4. All cross-references updated to new locations

### Integration with Channel Coordination

The thread model integrates with channel-based coordination:
- **Channel 42**: Primary workspace for thread allocation
- **Thread Directories**: `lupo-channels/42/threads/{thread_id}/`
- **Database Integration**: Threads map to `lupo_dialog_threads` table
- **HERMES Routing**: Classifies thread artifacts for actor handoffs

For complete coordination doctrine, see [CHANNEL_BASED_COORDINATION_DOCTRINE.md](lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md) and [MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md).

---

## Project → Channel → Thread → Task Hierarchy (4.0.81+)

Lupopedia organizes work in a strict containment hierarchy. Understanding this hierarchy is essential for proper coordination and artifact placement.

```
PROJECT (repository boundary)
  └── CHANNEL (workspace within project)
        └── THREAD (focused conversation)
              └── TASK (stable work identity)
```

### Layer Definitions

#### PROJECT
- **Purpose**: Repository boundary and namespace
- **Identity**: `project_id` (numeric) and `project_slug` (human-readable)
- **Scope**: All code, documentation, and coordination artifacts
- **Example**: This repository = project_id 0 (lupopedia-core)

#### CHANNEL
- **Purpose**: Workspace for specific type of coordination
- **Identity**: `channel_id` (numeric)
- **Scope**: Threads, broadcasts, direct messages
- **Example**: Channel 42 = primary development workspace

#### THREAD
- **Purpose**: Focused conversation for one task
- **Identity**: `thread_id` (numeric)
- **Scope**: Artifacts for a single task execution
- **Example**: Thread 1003 = documentation task execution

#### TASK
- **Purpose**: Stable work item identity
- **Identity**: `task_id` (human-readable)
- **Scope**: The work itself, across potential thread changes
- **Example**: task_doc_001 = documentation alignment task

### Key Rules

1. **Project Contains Everything**: All coordination artifacts exist within one project
2. **Channels Are Project-Scoped**: Channel 42 in project A ≠ Channel 42 in project B
3. **Threads Are Channel-Scoped**: Thread IDs are unique only within their channel
4. **Tasks Map to Threads**: One active task maps to one thread at a time

### Repository Context

**This repository is project_id 0 (lupopedia-core)**

- All paths in this repo are within project 0
- Channel 42 is the default development workspace for project 0
- External AI sees this entire repo as one project boundary

---

## External AI Consumption

Lupopedia provides deterministic navigation for external AI systems without prior knowledge.

### Navigation Entry Points
- **Global Index**: `lupo-channels/INDEX.md` - All channels overview
- **Development Channel**: `lupo-channels/42/THREAD_INDEX.md` - Thread directory
- **External AI Guide**: `lupo-docs/EXTERNAL_AI_README.md` - Complete participation instructions

### How External AI Navigation Works
1. **Discover Channels**: Start at global INDEX.md
2. **Select Channel**: Channel 42 for development work
3. **Find Threads**: Use THREAD_INDEX.md to locate relevant work
4. **Access Artifacts**: Navigate to thread directories using thread_id
5. **Understand Context**: Read structured metadata and follow edges

### Repository Structure for External AI
- **Source of Truth**: GitHub repository reflects current state
- **Channel Organization**: Work organized by channel and thread
- **Machine-Readable**: Structured metadata and comprehensive indexing
- **Zero Prior Knowledge**: External AI can navigate without system familiarity

### Thread Discovery
- **Thread 1006**: ✅ EXISTS - Validator implementation (resolved)
- **Complete Index**: All 16+ threads indexed with status and metadata
- **Predictable Paths**: `lupo-channels/42/threads/{thread_id}/` structure

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

### Project Context for All Work

**Fundamental Principle**: All work occurs within a project context.

#### Default Assumption
- **Current repository** = project_id 0 (lupopedia-core)
- **No explicit project_id needed** for single-project work
- **Agents must not assume global channels** - channels belong to projects

#### Project Scoping Rules

1. **When you work in this repo**: You're working in project_id 0
2. **When you reference Channel 42**: You mean project 0's Channel 42
3. **When you create threads**: They belong to the project's channel
4. **When you update TODO.md**: You're updating project 0's task registry

#### Multi-Project Awareness

- **Future state**: Multiple repositories = multiple projects
- **Current state**: Single repository = single project (project_id 0)
- **Agent behavior**: Assume project context unless explicitly told otherwise

### Projects
**Projects** are a first-class semantic layer above channels. A project groups related channels, collections, and dialogs within a federation node. Per the **Thread 1032 Directive**, `project_id = 0` is the canonical system/default project. The `lupo_projects` (and `lupo_actor_projects`) tables define the actual models. Additional projects require formal DB creation prior to artifact bindings. WOLFIE operates as the sole directive authority capable of approving new project creation; subsequent provisioning is executed formally via HEPHAESTUS schema insertion scripts. **IDE agents** infer project context from the workspace. **External agents** must declare project (and channel/thread) explicitly in every request. 

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

## External AI Model (4.0.81+)

### How External AI Sees Lupopedia

External AI models (ChatGPT, Grok, DeepSeek) interact with Lupopedia differently than internal agents:

#### External AI Constraints
- **Reads filesystem (GitHub)**, not database
- **Project = their universe**: One repository = one project
- **No database queries**: Cannot access `lupo_projects` table
- **Path-based inference**: Must understand structure from file paths

#### External AI Mental Model

```
Repository Root (GitHub clone)
├── lupo-channels/
│   └── 42/
│       └── threads/
│           └── 1003/
├── TODO.md
├── plan.md
└── README.md
```

#### External AI Interpretation Rules

1. **Project Boundary**: Repository root = project boundary
2. **Channel Inference**: `lupo-channels/{channel_id}/` = channel exists
3. **Thread Inference**: `threads/{thread_id}/` = thread exists
4. **Task Registry**: `TODO.md` and `plan.md` = project-scoped planning

#### Critical for External AI

- **DO NOT assume global channels**: Channel 42 is project-scoped
- **DO NOT assume database access**: Work with filesystem only
- **DO respect project boundaries**: Each repo is separate project
- **DO read headers**: LUPOPEDIA HEADERS provide explicit context
- **Database-only Threads Resolution**: If a thread exists in the database but lacks filesystem artifacts, external AI cannot infer its identity directly from missing folders. In these cases, external AI lacking database query interfaces must write an explicit request artifact to `lupo-channels/42/broadcasts/` directing executing agents (`HEPHAESTUS` or `THOTH`) to formally flush the target DB thread metadata back to a generated `THREAD_INDEX.md` list.

#### Example: External AI Reading Thread 1003

Path: `lupo-channels/42/threads/1003/20260318_170000_thoth_directive_task_doc_001_kickoff.md`

External AI inference:
- Project: Repository root (project_id 0, implicit)
- Channel: 42 (from path)
- Thread: 1003 (from path)
- Task: task_doc_001 (from headers)

---

## §3 Current Operational Reality (4.0.85)

**Lupopedia operates as a dual-mode system in actual practice:**

1. **Filesystem-first coordination** — Artifacts live on disk; GitHub is the primary coordination surface. External AI agents (Claude, GPT, etc.) read filesystem only.
2. **LUPOPEDIA HEADERS as active execution layer** — Headers are not metadata; they drive routing, workflow, and semantic meaning. Treat headers as code.
3. **Database as structural authority** — Database holds schema (install SQL), identity (actors, projects), and temporal state (timestamps, soft deletes), but **not** coordination flow.
4. **External AI zero-database-access constraint** — External AI models cannot query the database. They work exclusively with LUPOPEDIA HEADERS and file artifacts.
5. **Deterministic round-trip requirement** — System must guarantee bidirectional equivalence: Database State ↔ HEADERS produces identical outcomes both directions.

**This is not the intended architecture described in conceptual docs. It is the actual operational reality.** See Thread 1033 §3-4 for full analysis.

---

## §4 Dual-Mode Architecture (Filesystem + Database)

### Mode 1: Filesystem Mode (External AI Surface)
- **Who works here**: External AI agents (Claude, GPT, etc.) via IDE faucets
- **What they see**: LUPOPEDIA HEADERS + file artifacts + indexes
- **Database access**: **Zero** — no query capability at all
- **Coordination layer**: Thread/task/channel structure inferred from file paths
- **Authority level**: Read-only on filesystem; write-only to filesystem artifacts

### Mode 2: Database Mode (Internal Runtime Surface)
- **Who works here**: Internal runtime (PHP services, HERMES, scheduled tasks)
- **What it manages**: Schema authority, identity registry, temporal state, referential integrity
- **Filesystem access**: **Via LUPOPEDIA HEADERS only**
- **Coordination layer**: Queries `lupo_metadata`, `lupo_edges`, `lupo_channels` for live state
- **Authority level**: Source of truth for structure

### Critical Consequence
**Round-trip equivalence is mandatory.** If database state changes, filesystem must be projectable to identical state. If headers change, database ingestion must produce identical state. This is a hard constraint, not an aspiration.

See [HEADER_DB_REVERSIBILITY_DOCTRINE.md](lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md) for binding rules on deterministic projection, ingestion, collision detection, and confidence levels.

---

## §5 Channel 66 Question Graph System (4.0.85+)

**Channel 66 is the canonical question-driven semantic resolution system for all 4.0.x releases.** It is not experimental; it is proven (Threads 1001-1005 completed).

### Channel 66 Model
- **Purpose**: Resolve architectural questions through evidence-based investigation → doctrine binding
- **Thread identity**: Each thread = one semantic question in natural language
- **Lifecycle**: Question → Investigation → Doctrine Draft → LILITH Review → WOLFIE Closure → Binding
- **Role assignments**: WOLFIE (orchestrator), THOTH (investigator), ATHENA (strategist), LILITH (reviewer), HEPHAESTUS (awaits doctrine)
- **Edge model**: required_reading (0.9-1.0), resolves, contradicts, refines, depends_on, next_action with weighted relationships

### Non-Blocking Workflow
- Channel 66 work happens **in parallel** to implementation
- Binding doctrine from Channel 66 **immediately affects** implementation
- No grandfather clause — binding doctrine is effective upon WOLFIE closure

### Quality Standard
- Evidence-based (references source code, schema, thread artifacts)
- Deterministic (testable propositions, reproducible findings)
- Testable (validators provided for each claim)
- Bounded scope (one question per thread, no scope creep)

**Doctrine**: [CHANNEL_66_QUESTION_GRAPH_DOCTRINE.md](lupo-docs/doctrine/CHANNEL_66_QUESTION_GRAPH_DOCTRINE.md)

---

## §6 External AI Zero-Database Access Constraint (Binding)

This is a hard architectural constraint, not a limitation we're working around.

### Why Zero Access?
- **Offline operation**: Filesystem must work when database is unreachable
- **IDE faucet operation**: IDE agents lack database connection infrastructure
- **Determinism**: External AI cannot create implicit state dependencies
- **Reproducibility**: Work must be expressible in checked-in artifacts

### What This Means
- **External AI CANNOT**: Query database, execute SQL, call database APIs, access database credentials, see live database tables
- **External AI CAN**: Read git-tracked files, read LUPOPEDIA HEADERS, write artifacts to channel structure, reference thread/task/channel via paths

### Implementation Consequence
**All external AI work must be FSY expressible in committed Markdown artifacts with LUPOPEDIA HEADERS.** If work requires querying the database, it must be done by:
1. Internal runtime (PHP services)
2. HERMES routing to THOTH/HEPHAESTUS
3. Broadcast request to executing agents

**Never ask external AI to do database work directly.**

---

## §7 Project_id Implementation Requirements (4.0.85)

Per Thread 1032 Canonical Project Model Directive:

### Required Changes
Schema changes to 6 tables (bindings executed via migration `dev_20260321_project_model_and_schema_authority.sql`):

| Table | Change | Default | Rationale |
|-------|--------|---------|-----------|
| `lupo_channels` | Modify `project_id` from nullable to `NOT NULL DEFAULT 0` | 0 (system project) | Every channel belongs to exactly one project |
| `lupo_dialog_threads` | Add `project_id BIGINT NOT NULL DEFAULT 0` | 0 | Thread identity includes project scope |
| `lupo_tasks` | Add `project_id BIGINT NOT NULL DEFAULT 0` | 0 | Task allocation is project-scoped |
| `lupo_edges` | Add `project_id BIGINT NOT NULL DEFAULT 0` | 0 | Edge context is project-scoped |
| `lupo_metadata` | Add `project_id BIGINT NOT NULL DEFAULT 0` | 0 | Metadata clustering by project |
| `lupo_atoms` | Add `project_id BIGINT NOT NULL DEFAULT 0` | 0 | Atom namespace scoping |

### New Table
**`lupo_actor_projects`** — Maps actors to projects with roles:
```
actor_id BIGINT NOT NULL
project_id BIGINT NOT NULL DEFAULT 0
role_key VARCHAR(64) — 'admin', 'contributor', 'viewer', 'reviewer'
created_ymdhis BIGINT NOT NULL
updated_ymdhis BIGINT NOT NULL
is_deleted TINYINT DEFAULT 0
deleted_ymdhis BIGINT DEFAULT 0
```

### Header Declaration
In LUPOPEDIA HEADERS:
```yaml
lupopedia.headers:
  project_id: 0  # system/default project
  channel_id: 42
  ...
```

### Project_id=0 Semantics
- `project_id=0` is the **system/default project** — all current work (`lupo_channels`, `lupo_dialog_threads`, etc.) defaults to it
- Multi-project work requires explicit `project_id > 0` 
- Single-repository (current state) implicitly operates in `project_id=0` context
- Project isolation is enforced at query time (WHERE `project_id = ?`), not by schema

### Migration Path
1. **Immediate** (4.0.85): Schema modified to add `project_id` with `DEFAULT 0`
2. **Transparent** (4.0.85-4.0.86): All existing work continues to work (defaults to `project_id=0`)
3. **Future** (4.1.0): Multi-project support becomes explicit requirement

---

## LUPOPEDIA HEADERS — The File/Database Bridge

This is one of the most important parts of Lupopedia. **LUPOPEDIA HEADERS** are structured YAML blocks at the top of `.md` files and other artifact-like objects. They are the bridge between database state and filesystem artifacts.

**Why headers exist:** The database holds live relational state. The filesystem holds persistent artifacts. Neither alone is enough. The database can be unavailable. A file can be moved, copied, federated, or read offline. Headers solve this by embedding **stable, human-authored artifact identity and intent** directly into the artifact so it can be resolved deterministically across environments.

**Important principle:** Headers are not merely decorative frontmatter; they are part of the semantic operating system.

### Main header sections
For **ordinary documentation artifacts** (doctrine/spec/foundation/status), handwritten headers should contain only stable, human-authored blocks (identity + intent). Use the principle:

**Headers declare the artifact. The database declares the world around it.**

Canonical handwritten blocks for general docs:

- `Lupopedia.init`
- `Lupopedia.routing`
- `Lupopedia.metadata`
- `Lupopedia.environment`
- `Lupopedia.next_actions`
- `Lupopedia.comments`

Dynamic, DB-derived, or synthetic-view concerns (usage, relationships, engagement, lineage/graph context) should **not** be taught as default handwritten header content for general docs.

**Special exception (table docs):** Active table documentation is a special semantic mapping surface. For active table docs, a **verbose `Lupopedia.edges`** block is explicitly declared and populated from grounded repository evidence (PHP/Python/schema/seed/install SQL usage).

### Project ID in Headers

#### When to Include project_id

**REQUIRED**:
- All `lupo_channels`, `lupo_dialog_threads`, `lupo_tasks`, `lupo_edges`, and `lupo_metadata` DB inserts per the Thread 1032 canonical project model. 
- In LUPOPEDIA HEADERS, `project_id` must be explicitly declared (defaulting to `0` for the system/default project) to maintain deterministic alignment with the implemented schema.
- Multi-project environments
- Federation scenarios
- Cross-project references

**OPTIONAL**:
- Previously considered optional in early versions, **project_id is now natively required** per Thread 1032 constraints. Legacy artifacts missing it will implicitly resolve to `project_id: 0`. These legacy artifacts transition automatically to the explicit compliance requirement the next time their headers are rewritten during an ordinary save containing `version_when_written` (a standard baseline rewrite-on-write event).

#### Header Examples

**Single Project (current state)**:
```yaml
lupopedia.headers:
  channel_id: 42
  thread_id: 1003
  task_id: "task_doc_001"
  # project_id omitted - inferred from repo
```

**Multi-Project (future state)**:
```yaml
lupopedia.headers:
  project_id: 0
  channel_id: 42
  thread_id: 1003
  task_id: "task_doc_001"
```

**Cross-Project Reference**:
```yaml
lupopedia.edges:
  outbound_edges:
    - { 
        to: "project-1:lupo-channels/42/threads/1003/artifact.md",
        type: "references",
        project_id: 1
      }
```

#### Implementation Guidance

- **Internal agents**: Can infer project from context
- **External AI**: Relies on filesystem + headers
- **Tooling**: Should default project_id to 0 for single-repo
- **Federation**: Must include project_id explicitly

---

## Canonical Project Model (4.0.85+)

### Thread 1032 Consolidation 

**Current State (v4.0.85)**:
- Single repository = single project (`project_id: 0` is the system/default project).
- Per Thread 1032, `project_id` bindings are strictly required across `lupo_channels`, `lupo_dialog_threads`, `lupo_edges`, `lupo_tasks`, and `lupo_metadata`.
- Cross-project references require explicit explicit `project_id` values.
- New configurations are orchestrated using `lupo_actor_projects`.
- WOLFIE Thread 1032 acts as the foundational authority concerning active database modeling. `plan.md` must be considered strictly aligned and authoritative alongside this standard constraint; legacy claims to the contrary found in previous versions or orphaned files are non-canonical.

### Multi-Project Rules

1. **Project Isolation**: Each project has its own channels and threads
2. **ID Namespacing**: thread_id is NOT globally unique
3. **Identity Composition**: Full identity = (project_id + channel_id + thread_id)
4. **Cross-Project References**: Must be explicit with project_id

### Example: Thread ID Collision

**Project A (lupopedia-core)**:
- Channel 42, Thread 1003
- Full identity: (project_id: 0, channel_id: 42, thread_id: 1003)

**Project B (lupopedia-plugins)**:
- Channel 42, Thread 1003
- Full identity: (project_id: 1, channel_id: 42, thread_id: 1003)

**Result**: No conflict - different projects, same local IDs

### Federation Considerations

- **federation_node_id**: Scopes project_id uniqueness
- **project_slug**: Human-readable identifier per node
- **Cross-node references**: Require full qualified identifiers

---

## Architecture Overview

**Filesystem layout:**
Lupopedia organizes itself via directories with the `lupo-` prefix: `/lupo-api`, `/lupo-uploads`, `/lupo-channels`, `/lupo-agents`, `/lupo-actors`, `/lupo-docs`, `/lupo-prompts`, and related `lupo-*` folders. These hold artifacts for documentation, channel content, agent/session data, uploaded objects, semantic references, and registry material. The **`legacy/`** folder is the intentional exception: it holds legacy read-only code (e.g. Crafty Syntax reference) and is not renamed to `lupo-legacy`.

**Database domains:**
The database schema is defined in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — **install SQL is the authoritative schema source**. TOON files are **derived artifacts** (do not edit by hand); they are generated into `lupo-database/lupopedia/toon/` by `python lupo-scripts/generate_toon_from_sql.py` or `python lupo-scripts/generate_toon_files.py`. The former path is canonical (no longer `lupo-docs/toons/`). Canonical table count: **161** as of 4.0.77 (see [TABLE_COUNT_DOCTRINE](lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md)); table ceiling is advisory only. Domains include identity (`lupo_actors`, `lupo_auth_users`), orchestration (`lupo_agents`, `lupo_agent_faucets`), projects (`lupo_projects`), collaboration (`lupo_channels`, `lupo_dialog_messages`), semantics (`lupo_metadata`, `lupo_edges`, `lupo_collections`), telemetry (`lupo_sessions`), federation, and legacy migration. The `lupo-database/` directory is protected from direct web access (e.g. `.htaccess` hardening) so that schema and seed assets are not served over HTTP.

**Doctrine reminder (non-standard architecture):** Lupopedia is not a conventional framework application. Critical constraints: **no foreign keys, triggers, stored procedures, or stored functions**; all logic in application code; **explicit column naming** on inserts where doctrine requires it; timestamps as **BIGINT UTC** in `YYYYMMDDHHIISS` format, set in PHP (e.g. `gmdate('YmdHis')`), never database-generated; **install SQL authoritative**, TOON derived; shared-hosting and unknown-environment assumptions; fallback-first behavior. These and other rules are codified in [lupo-rules/root/](lupo-rules/root).

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
*Lupopedia 4.0.85 — a semantic operating system orchestrated by humans and AI agents across channels, artifacts, and federation nodes. Canonical doctrine: lupo-rules/root/.*
