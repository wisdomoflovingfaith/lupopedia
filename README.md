---
lupopedia.headers:
  version_when_written: "4.0.86"
  lupopedia.schema: "documentation"
  file_path_from_root: "README.md"
  web_path: "http://www.lupopedia.com/README"
  last_modified_utc: "20260324"
  channel_id: 42
  thread_id: "1001"
  actor_id: 108
  actor_name: "junie"
  faucet_name: "jetbrains"
  delegation_chain: "junie:root"
  artifact_type: "project_documentation"
  artifact_kind: "readme"
  purpose: "Primary project documentation (v4.0.86) with comprehensive reorganization of core concepts."
  tags: ["junie", "readme", "v4.0.86", "project_overview", "identity_model", "semantic_os"]

lupopedia.init:
  required_reading:
    - path: "ONBOARDING.md"
      reason: "Operational quick-start; first file for new or existing IDE agents"
    - path: "README.md"
      reason: "High-level overview, architecture, and canonical root rules"
    - path: "AGENTS.md"
      reason: "Agent/faucet distinction, eleven primary personas, and IDE faucet roles"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format, block order, and file structure"
    - path: "lupo-rules/root/README.md"
      reason: "Root rules index and non-negotiable constraints"
    - path: "lupo-docs/versions/4.0.86/"
      reason: "Version 4.0.86 comprehensive documentation and scope lock"

lupopedia.edges:
  outbound_edges:
    - { to: "AGENTS.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.98 }
    - { to: "ONBOARDING.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/versions/4.0.86/OVERVIEW.md", type: "references", weight: 0.95 }

lupopedia.footer:
  last_verified: "20260324"
  last_verified_by: "junie"
  orchestrator: "junie:root"
  next_action:
    - "Maintain README alignment with version-specific scope locks and doctrine changes."
    - "Update core concept diagrams when the web dialog interface is finalized."
---
# file: Lupopedia Semantic OS — delegation: junie:root — web_path: http://www.lupopedia.com/README

# Lupopedia Semantic OS v4.0.86

Lupopedia is a **deterministic semantic operating system** built on the durable foundation of Crafty Syntax (human coordination) and Doom Emacs research (compositional structure). It organizes humans, AI agents, rules, and documents into an inspectable, replayed, and validated knowledge graph.

## 🚀 Version 4.0.86 (Active)
**Status**: 4.0.86 is the **stabilization and synchronization** version. It focuses on clarifying the **Unified Identity Model**, enforcing **Channel Security**, and resolving documentation contradictions across the actor system.
- **Scope Lock**: Channels 58 (Actor Model), 59 (ROSE/DIALOG), 60 (Agent System), and 61 (Enforcement).
- **Authority**: WOLFIE (actor_id 1) is the final orchestrator; install SQL is the schema authority.
- **Upgrade Path**: Crafty Syntax 3.7.5 → Lupopedia 4.0.x (the only supported path).

---

## 1. The Unified Identity Model

Lupopedia separates human account identity from operational orchestration identity and AI behavioral metadata.

### Auth Users (`lupo_auth_users`)
- **Who you are**: The physical human logging in (credentials, 2FA, ownership).
- **Rule**: Never use `user_id` for operations; operations use the paired `actor_id`.

### Departments (`lupo_departments`)
- **Where you belong**: Organizational units for grouping and routing.
- **System Departments**: `department_id: 0` (System), `department_id: 1` (General Default).
- **Membership**: Managed via `lupo_actor_departments`.

### Actors (`lupo_actors`)
- **How you act**: The universal operational identity layer.
- **Identity Tiers**:
  - `0–999`: System and Primary AI Actors (e.g., WOLFIE, LILITH, HERMES).
  - `0`: Root / Master User (special exception in `auth_users` for human orchestration).
  - `1000+`: Other human-led actors (linked to `auth_users`).
- **Traits & Roles**: Intrinsic constraints (`traits`) vs. channel-local permissions (`roles`).

### Agents (`lupo_agents`)
- **AI Behavior**: Metadata for AI reasoning (model, prompt, temperature, provider).
- **Distinction**: The *actor* is the identity; the *agent* is the configuration.

### Faucets (`lupo_agent_faucets`)
- **Execution Surface**: The software/API used (e.g., Cursor, Windsurf, Warp, JetBrains).
- **Core Doctrine**: **Actors orchestrate, Faucets execute.**

---

## 2. Orchestration & Coordination

Work happens in a strict containment hierarchy to ensure clear lineage and traceability.

### The Hierarchy: Project → Channel → Thread → Task
- **Project** (Namespace): The repository boundary (e.g., `project_id: 0` for core).
- **Channel** (Workspace): Scoped environments for specific coordination (e.g., Channel 42 for Dev).
- **Thread** (Conversation): A focused dialog for a single task (numeric `thread_id`).
- **Task** (Work Item): Stable identity of the work itself (human-readable `task_id`).

### Coordination Flow
1. **WOLFIE** issues a directive or allocates a thread for a task.
2. **Actors** write artifacts (Markdown + Headers) into the channel/thread tree.
3. **HERMES (15)** reads artifacts, classifies intent, and writes executable prompts for target actors.
4. **Target Actors** execute work and publish results under their own `actor_id`.

---

## 3. The Semantic OS Layer

Lupopedia treats relationships, properties, and decisions as first-class data.

### LUPOPEDIA HEADERS
Structured YAML blocks at the top of files that act as the **bridge between database and filesystem**.
- **Self-Describing**: Artifacts carry their identity, intent, and relationships (edges).
- **Subdirectory-Aware**: All `web_path` headers MUST include the `LUPOPEDIA_BASE_URL` (e.g., `/lupopedia/`) as the project is always installed in a subfolder.
- **Deterministic**: Headers allow the system to reconstruct state even if the DB is unavailable.

### Edges and Metadata
- **Edges (`lupo_edges`)**: Typed, weighted relationships between entities (e.g., `references`, `resolves`, `depends_on`).
- **Metadata (`lupo_metadata`)**: Property key-value pairs stored as first-class records.

### Dialog & Messages
- **Threads (`lupo_dialog_threads`)**: Containers for ongoing dialog.
- **Messages (`lupo_dialog_messages`)**: Individual communication entries, audited by actor and faucet.

---

## 4. Dual-Mode Architecture

Lupopedia is split across two authoritative modes that must remain in sync:

1. **Filesystem Mode**: The surface for External AI (Claude, GPT). It reads artifacts and headers; it has **Zero Database Access**.
2. **Database Mode**: The surface for Internal Runtime (PHP, HERMES). It manages schema authority, identity registry, and temporal state.

**Round-trip Equivalence**: Changes in one mode must be projectable to the other without data loss.

---

## 5. Development Environment

- **Runtime**: PHP 5.6 through 8.3+ (Core code must remain compatible with PHP 5.6).
- **Database**: MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL (Logic-free DB; all logic in PHP).
- **Local Stack**: ServBay on Windows 11 / PowerShell.
- **No Build Step**: Pure procedural PHP + PDO + Semantic Engine. No Composer or frameworks.
- **Timestamps**: All timestamps are `BIGINT` in `YYYYMMDDHHIISS` UTC format (never DB-generated).

---

## 6. Required Reading & Onboarding

Lupopedia is **doctrine-driven**. Read in order:
1. **[ONBOARDING.md](ONBOARDING.md)** (Quick Start).
2. **[AGENTS.md](AGENTS.md)** (Roles & Registry).
3. **[lupo-rules/root/README.md](lupo-rules/root/README.md)** (Non-negotiable rules).
4. **[lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)** (Header spec).

---
*Lupopedia 4.0.86 — A semantic operating system orchestrating humans and AI across channels, artifacts, and nodes.*
