---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404163615"
  file_path_from_root: "lupo-docs/prd/29_project_structure.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/29_project_structure.md"
  last_modified_utc: "20260404163615"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-project-structure"
  prd_id: 29
  prd_slug: project_structure
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "structure"
  purpose: "Top-level codebase directory map and conventions"
  status: "approved"
  tags:
  - "documentation"
  - "project_structure"
  - "conventions"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 0.95
      reason: "Channel and thread semantics (complements directory map in this PRD)"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "Thread filename patterns (authoritative per-folder decisions/questions/answers/comments)"
    - to: "lupo-channels/0/organization/prd_29_project_organization/decisions/THREAD_INDEX.md"
      type: references
      weight: 0.9
      reason: "Live coordination thread for PRD 29 project organization"
    - to: "lupo-docs/prd/31_implementation_folder_guidelines.md"
      type: references
      weight: 1.0
      reason: "lupo-docs/implementations/{prd_file_stem}/ layout and naming"
    - to: "lupo-docs/implementations/README.md"
      type: references
      weight: 0.95
      reason: "Implementations index"
---

# Project Structure

## Purpose
This document describes the top‑level organization of the Lupopedia codebase. All functional components are placed under directories prefixed with `lupo-` to provide a clear namespace and to satisfy the **Prefix Normalization Doctrine**.

## Channel filesystem strategy (4.0.93+)

Coordination files on disk use two **roles**: legacy archive vs active tree. **Do not** treat the archive as a migration queue for the whole history.

| Old channel system | New channel system |
|--------------------|-------------------|
| `lupo-channels_before_4_0_93/` — **archive**, read-only, historically inconsistent | `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/` — **active** layout |
| **Do not** migrate content wholesale from old → new | **Create fresh** channels and threads for active PRD discussions (PRDs 29, 30, 31, organization, documentation system) |
| **Historical reference only** (cite paths; cherry-pick rare threads if needed) | **Active development and coordination only** |

### Archive cherry-pick policy

Only **`lupo-channels_before_4_0_93/42/`** (channel **42**) is eligible for cherry-picking into new threads.

**Eligibility:** Files with timestamps **newer than `20260325`** (i.e., **March 26, 2026** or later).

All other pre–4.0.93 channel content is **archive reference only** — do not migrate.

**PRD 29 (this document) — coordination thread:** Ongoing discussion of **how the docs system and repo are organized** lives in:

`lupo-channels/0/organization/prd_29_project_organization/`

Use that thread’s `decisions/`, `questions/`, `answers/`, and `comments/` folders per `lupo-docs/prd/02_channels_discussions.md` and `lupo-docs/prd/17_decisions_format.md`. **Per-thread filenames** (`TYPE` / `STATUS` only in `decisions/`, `HHIISS`, optional `YYYYMMDDHHIISS` prefix) are specified in **PRD 17**, section **“Thread filename pattern (authoritative)”** — not duplicated here. **PRD 30** and **PRD 31** working copies should get their own fresh threads under `lupo-channels/` when work is active—do not rely on the pre–4.0.93 tree for new work.

**`AGENTS.md` vs the thread tree:** `AGENTS.md` lives at the **repository root** (IDE/agent coordination and mandatory channel literacy). It is **not** part of `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/` and no PRD defines an `AGENTS.md` inside each thread. Thread folders may use an optional **`README.md`**, per-folder **`THREAD_INDEX.md`**, and typed files under **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`** as specified in PRD 02 and PRD 17. When older work (e.g. V487-002) lists `AGENTS.md` alongside `lupo-channels/channel_index.md`, read that as **root + channel index**, not as “every thread carries AGENTS.”

## Directory Overview
| Directory | Type | Primary Purpose |
|-----------|------|-----------------|
| `lupo-actors/` | Directory | Resources per actor (apps, docs, API, DB changes). |
| `lupo-admin/` | Directory | Administrative scripts and utilities for system maintenance. |
| `lupo-admin_sections/` | Directory | Sub‑sections of admin functionality (e.g., user management). |
| `lupo-agents/` | Directory | Configuration for AI agents (one folder per agent). |
| `lupo-api/` | Directory | REST API entry points and related helpers. |
| `lupo-archive/` | Directory | Archived legacy code and historical artifacts (read‑only). |
| `lupo-backups/` | Directory | Database and file backups created by maintenance scripts. |
| `lupo-bin/` | Directory | Executable CLI utilities (e.g., version bump, migration helpers). |
| `lupo-cache/` | Directory | Runtime cache files (generated, can be cleared). |
| `lupo-channels/` | Directory | Active channel tree for multi‑agent coordination (layout per channel doctrine; includes `decisions/`, `questions/`, `answers/`, `comments/` under threads where used). |
| `lupo-channels_before_4_0_93/` | Directory | **Read-only archive** of the pre–4.0.93 channel filesystem (legacy numeric and mixed layouts). **Archive cherry-pick policy** (channel 42, timestamps newer than `20260325`) is defined **above** in this PRD. Prefer **new** channels/threads for ongoing work. See also `lupo-docs/prd/02_channels_discussions.md`. |
| `lupo-chats/` | Directory | Chat transcript storage used by the UI. |
| `lupo-collections/` | Directory | Semantic collections and aggregated data sets. |
| `lupo-config/` | Directory | Global configuration files (e.g., `global_atoms.yaml`). |
| `lupo-content/` | Directory | Static content assets (HTML snippets, templates). |
| `lupo-context/` | Directory | Context‑related helpers and runtime context objects. |
| `lupo-contexts/` | Directory | Additional context definitions used by the engine. |
| `lupo-database/` | Directory | Schema definitions, migrations, seed data, and TOON JSON backups. |
| `lupo-docs/` | Directory | Project documentation, PRDs, doctrines, and reference guides. |
| `lupo-emoji/` | Directory | Emoji assets used throughout the UI. |
| `lupo-hooks/` | Directory | Git hooks and validation scripts for development workflow. |
| `lupo-images/` | Directory | Image assets (logos, UI graphics). |
| `lupo-includes/` | Directory | Core runtime files, bootstrap, module loader, legacy helpers, CSS/JS assets, UI components, and the semantic engine. |
| `lupo-install/` | Directory | Installation scripts and wizard resources. |
| `lupo-logs/` | Directory | Log files generated by the application. |
| `lupo-meta/` | Directory | Metadata about the codebase (e.g., manifest files). |
| `lupo-research/` | Directory | Research artifacts and knowledge‑base items. |
| `lupo-routes/` | Directory | Routing definitions for URL handling. |
| `lupo-rules/` | Directory | Doctrine files that define system rules and policies. |
| `lupo-scripts/` | Directory | Helper scripts (Python, shell) for schema generation, validation, and migrations. |
| `lupo-sessions/` | Directory | Session storage files used by the PHP session manager. |
| `lupo-skills/` | Directory | Skill definitions for specialized agent capabilities. |
| `lupo-templates/` | Directory | Template files for code generation and UI rendering. |
| `lupo-tests/` | Directory | Test suites (unit, integration, regression, adversarial). |
| `lupo-tmp/` | Directory | Temporary workspace used by agents; should be cleared regularly. |
| `lupo-tools/` | Directory | Development tooling, VSX extension support, and auxiliary scripts. |
| `lupo-ui/` | Directory | UI components, CSS, JavaScript, and theme assets. |
| `lupo-uploads/` | Directory | User‑uploaded files stored by the application. |
| `lupo-views/` | Directory | View templates (PHP/HTML) for rendering pages. |

## Canonical Decisions Directory Structure (Required)

All architectural and governance decisions for each version **must** be stored in a `decisions/` folder under the version directory. The legacy single-file `decisions.md` is deprecated and must not be used for new work.

**Required structure:**
```
lupo-docs/versions/
└── <version>/
  └── decisions/
    ├── THREAD_INDEX.md     # Required: index of all decision threads
    └── YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md  # Individual thread files
```

- `decisions/` is always a folder, never a file.
- `THREAD_INDEX.md` is required in every `decisions/` folder and must list all threads.
- Each thread file must use the naming convention: `YYYYMMDD_HHIISS_TYPE_STATUS_TITLE.md` (UTC timestamp, type, status, lowercase/underscored title).
- All thread files must include a LUPOPEDIA HEADERS block.
- No new `decisions.md` files may be created; all new decisions must be threads in the folder.

**Rationale:**
- Enables parallel, atomic, and auditable decision records
- Prevents merge conflicts and monolithic file bottlenecks
- Satisfies constitutional requirements for provenance and channel/thread linkage

**References:** See [lupo-docs/prd/17_decisions_format.md](17_decisions_format.md), [README.md](../README.md), and [LUPOPEDIA_HEADERS/README.md](../doctrine/LUPOPEDIA_HEADERS/README.md).

## Non‑Prefixed Items
| Item | Type | Note |
|------|------|------|
| `.git/`, `.gitignore`, `.htaccess` | Hidden files/directories | Version control and server configuration. |
| `config.php`, `install.php`, `index.php`, etc. | Files | Core entry points that live at the repository root. |
| `node_modules/` | Directory | NPM dependency cache; not part of the core application. |
| `vendor/` (if present) | Directory | Composer packages – currently unused per project doctrine. |
| `.venv/` | Directory | Python virtual environment for executing administrative scripts. |

## Legacy / Irrelevant Folders
The repository may contain leftover directories that are no longer required:
- `.cascade/`, `.cursor/`, `.idea/`, `.kiro/`, `.lexa/`, `.lilith/`, `.qodo/`, `.vscode/`, `.windsurf/` – hidden IDE‑agent workspaces; keep only if they contain active artifacts, otherwise delete.
- `lupo-archive/` – holds historic code; can be archived off‑site if no longer needed.
- Any empty `lupo-` prefixed directories without code or documentation should be reviewed before removal.

## Maintenance Guidelines
1. **When adding new top‑level components**, always use the `lupo-` prefix.
2. **Update this PRD** whenever a new `lupo-` directory is introduced or an existing one is removed.
3. **Run the Prefix Normalization Audit** periodically to ensure documentation references match actual directory names.
4. **Clean up hidden IDE workspaces** (`.cascade`, `.cursor`, etc.) after a sprint to avoid clutter.

### STRICT EXCEPTIONS: `node_modules/` and `app/`
> [!WARNING]
> The `node_modules/` directory must **NEVER** be renamed or prefixed (e.g., `lupo-node_modules/`). External tooling, NPM workflows, and agents explicitly rely on the exact literal `node_modules` string. Changing this will immediately shatter dependencies and break semantic and TOON JSON compilation relying on standard JavaScript toolchains. Do not modify.
> 
> The `app/` directory must **NEVER** be prefixed as `lupo-app/`. It prevents standard composer autoloader mapping and breaks PSR-4 namespace resolution used by system foundational services.

---
*Generated on 2026‑04‑01 by Antigravity (cursor IDE agent).*
## Important Sub‑folders

The following sub‑folders are critical for understanding project organization and should be referenced in documentation:

| Directory Path | Description |
|------------|-------------|
| `lupo-docs/versions/` | Contains version‑specific documentation, release notes, and work summaries (e.g., `4.0.93/KIRO_WORK_SUMMARY.md`). |
| `lupo-docs/database/lupopedia/tables/` | Holds documentation of database table schemas, TOON JSON files, and migration guidance. |
| `lupo-docs/prd/` | Core product‑requirement documents defining architecture, policies, and system requirements. |
| `lupo-docs/doctrine/` | Formal doctrines, rules, and policies governing system behavior. |
| `lupo-docs/knowledge/` | Knowledge‑base items and research artifacts referenced by agents. |
| `lupo-docs/channels/` | Detailed documentation on specific channel rules, histories, and conventions. |
| `lupo-docs/implementations/` | PRD-scoped implementation workspaces. **Folder name = `prd_file_stem`:** same basename as `lupo-docs/prd/{prd_file_stem}.md` (e.g. `36_rose_multi_persona_synthetic_dialog/`). **Constitution §5.8**; full layout **PRD 31**. |
| `lupo-docs/implementations/README.md` | Index; naming rules and table of known folders. |
| `lupo-docs/implementations/{prd_file_stem}/` | Per-PRD tree: typically **`status/`**, **`decisions/`**, **`questions/`** (often **`critical/`** / **`optimization/`** / **`clarification/`**), **`answers/`**, **`comments/`**, **`templates/`**, **`authors.md`**, **`edges.md`**, **`todo.md`**, **`changelog.md`** — see **PRD 31**. |
| `lupo-docs/implementations/{prd_file_stem}/README.md` | Implementation overview for that PRD. |
| `lupo-docs/implementations/_template/` | Canonical scaffold source copied by **`lupo-scripts/scaffold_implementation.py`**. |
| `lupo-docs/api/` | Authoritative API specs and endpoint documentation for integrations. |
| `lupo-hooks/` | Git hooks and validation scripts for development workflow. Contains pre-commit hooks, installation scripts, and validation runners that ensure code quality and compliance before commits. |
| `lupo-rules/root/` | Contains the highest-authority, non-negotiable constitutional rules and doctrines governing the codebase. |
| `lupo-research/federation_nodes/` | Formal ingestion structure for external federation research, guarded by strict RAG (read-only) boundaries. |
| `lupo-skills/lupopedia-headers/` | Contains the agent skills logic required to parse, update, and write LUPOPEDIA headers. |
| `lupo-includes/classes/` | Active PHP OOP class implementations. Consolidated structural storage. |
| `lupo-includes/modules/` | Procedural execution logic spanning all core functionalities. |
| `lupo-includes/functions/` | Legacy functional handlers retaining core Crafty stability. |
| `lupo-includes/security/` | Cryptographic processing, password hashing, system security logic. |
| `lupo-includes/semantic/` | Core graph parsing and semantic mapping layer. |
| `lupo-includes/templates/` | Standard template scaffolding structures. |
| `lupo-includes/themes/` | UI Themes definitions. |
| `lupo-includes/ui/` | Active frontend HTML component renderers. |

## Documentation Sub‑folders

The `lupo-docs/prd/` directory contains the core product‑requirement documents (PRDs) that define Lupopedia’s architecture, policies, and operational guidelines.

| File | Type | Brief Description |
|------|------|-------------------|
| `00_root_constitutional_system_requirements.md` | Doctrine | Defines non‑negotiable constitutional rules for the entire system. |
| `01_core_identity.md` | Doctrine | Describes the core identity model, actors, agents, and faucets. |
| `27_installer_requirements.md` | Doctrine | Lists requirements and constraints for the installation process. |
| `28_semantic_monitoring_widget.md` | Doctrine | Specifies the semantic monitoring widget architecture. |
| `02_channels_discussions.md` | Doctrine | Details channel and discussion mechanisms for multi‑agent coordination. |
| `02_data_model.md` | Doctrine | Outlines the data model, tables, and relationships (conceptual). |
| `03_goals_and_success_criteria.md` | PRD | States project goals and measurable success criteria. |
| `03_truth_knowledge.md` | Doctrine | Explains the truth‑knowledge layer and knowledge‑graph concepts. |
| `04_lupopedia_js_foundation.md` | PRD | Provides the foundation for Lupopedia’s JavaScript components. |
| `04_tags_metadata.md` | PRD | Describes tag and metadata handling across the platform. |
| `05_auth_user_actor_agent_transformation.md` | Doctrine | Covers transformation between auth users, actors, and agents. |
| `05_collections_navigation.md` | PRD | Defines navigation structures for semantic collections. |
| `06_content_management.md` | PRD | Specifies content‑management workflows and UI. |
| `07_agents_faucets.md` | Doctrine | Documents the relationship between agents and IDE faucets. |
| `08_actors.md` | Doctrine | Details actor definitions and responsibilities. |
| `08_governance_rules.md` | Doctrine | Lists governance rules governing system behavior. |
| `09_federation_sync.md` | PRD | Describes federation synchronization processes. |
| `10_tasks_workflow.md` | PRD | Outlines task and workflow management. |
| `11_analytics_tracking.md` | PRD | Defines analytics and tracking mechanisms. |
| `12_api_integration.md` | PRD | Provides guidelines for REST API integration. |
| `13_crafty_integration.md` | PRD | Explains integration with legacy Crafty Syntax components. |
| `14_system_operations.md` | PRD | Covers operational procedures and maintenance tasks. |
| `15_actors.md` | Doctrine | Additional actor‑related specifications. |
| `15_temporal_system.md` | Doctrine | Describes temporal aspects of the system (scheduling, timestamps). |
| `16_lupopedia_headers.md` | Doctrine | Specifies LUPOPEDIA HEADERS format and usage. |
| `17_decisions_format.md` | Doctrine | Defines the format for decision documentation. |
| `18_channel_chat_display.md` | PRD | Details UI for channel and chat display. |
| `19_garbage_collection_system.md` | PRD | Describes garbage collection and cleanup processes. |
| `PRD_AGENT_DEFINITION_MODEL.md` | PRD | Provides the PRD for the agent definition model. |
| `README.md` | Documentation | Overview and usage guide for the PRD folder. |
| `WHAT_TO_DO_NEXT.md` | Guidance | Suggested next steps for contributors. |
| `project_structure.md` | PRD | This file – project structure documentation (self‑reference). |

---
