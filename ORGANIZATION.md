---
lupopedia.headers:
  when_updated: "20260329200000"
  lupopedia.schema: "documentation"
  file_path_from_root: "ORGANIZATION.md"
  web_path: "http://www.lupopedia.com/lupopedia/ORGANIZATION.md"
  last_modified_utc: "20260329200000"
  channel_id: 42
  thread_id: "4.0.89-organization"
  actor_id: 23
  actor_name: "thoth"
  delegation_chain: "wolfie:thoth"
  artifact_type: "documentation"
  artifact_kind: "organization_guide"
  purpose: "Root orientation with navigation edges and clarity improvements"
  tags: ["organization", "4.0.89", "navigation", "clarity"]
  namespace: "documentation"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.89/README.md", type: "references", weight: 1.0, reason: "Current active version overview" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 1.0, reason: "Metadata and validation system" }
    - { to: "lupo-docs/database/README.md", type: "references", weight: 0.9, reason: "Database authority and documentation" }
    - { to: "lupo-docs/database/lupopedia/tables/active/", type: "references", weight: 0.8, reason: "Canonical table documentation" }
    - { to: "lupo-channels/channel_index.md", type: "references", weight: 0.8, reason: "Channel coordination system" }
    - { to: "lupo-docs/versions/4.0.89/TODO.md", type: "references", weight: 0.9, reason: "Current task tracking" }

lupopedia.footer:
  last_verified: "20260329200000"
  verified_by:
    identity_type: "actor"
    actor_id: 23
    agent_name_identity: "THOTH"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "windsurf"
  orchestrator: "wolfie:thoth"
  next_action:
    - "Maintain organization guidance as documentation clarity improves"
    - "Update navigation edges as new documentation is added"
    - "Keep edge weights and reasons current and meaningful"
---

# Lupopedia Repository Organization (4.0.88)

## 1. Purpose

This file is the root orientation map for humans and IDE agents.

It documents:

- what each major root `lupo-*` directory is for
- how MySQL schema artifacts are organized
- how file-based coordination artifacts are organized
- what is canonical, generated, operational, legacy, or unclear

This file is based on observed repository contents and code/script references, not naming assumptions.

## 2. Quick Start Reading Order

Read these first:

1. `README.md`
2. `AGENTS.md`
3. `lupo-docs/ORGANIZATION.md`
4. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
5. `lupo-docs/versions/4.0.88/README.md`
6. `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_PASS_REPORT.md`
7. `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md`

## 3. High-Level System Model

Lupopedia is a hybrid system:

- MySQL-backed runtime state and identity tables
- filesystem-visible documentation and coordination artifacts

Observed from installer and scripts:

- `install.php` resolves installer SQL from `lupo-database/lupopedia/mysql/`
- runtime uses PHP loaders under `lupo-includes/`
- channel artifacts are read from `lupo-channels/` and can be imported/synced to DB by scripts in `lupo-scripts/`

## 4. Root `lupo-*` Directory Map

Classification values used:

- `canonical` = declared authority surface
- `active_operational` = used by runtime, installer, scripts, or tests
- `generated_exported` = derived snapshots/artifacts
- `coordination_surface` = file-based state/communication for agents
- `legacy_or_archive` = historical storage, not primary execution path
- `unclear_or_transitional` = present but weakly defined or inconsistently used

| Directory | Classification | Observed role in 4.0.88 |
|---|---|---|
| `lupo-database/` | canonical, active_operational | Database artifacts. Includes canonical install SQL path `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, seed/import SQL, TOON/JSON exports, and session markdown files. |
| `lupo-includes/` | canonical, active_operational | Core runtime loader/modules/classes. Loaded by bootstrap and front controller. |
| `lupo-docs/` | canonical, active_operational | Primary doctrine, version docs, table docs, and planning/status/reference documentation. |
| `lupo-rules/` | canonical, active_operational | Root rule/doctrine constraints and multi-agent governance rules. |
| `lupo-scripts/` | active_operational | Maintenance/validation/import/sync tooling (schema generation, channel import/sync, validators). |
| `lupo-tests/` | active_operational | Unit/integration/regression/adversarial/manual test files. |
| `lupo-channels/` | coordination_surface, active_operational | File-based channel and thread artifacts used for multi-agent coordination and imported/synced into DB. |
| `lupo-database/sessions/` (subdir) | coordination_surface, active_operational | Canonical multi-session markdown context files for IDE/CLI runtime context. |
| `lupo-sessions/` | coordination_surface, active_operational | JSON default-session artifacts used in tests/runtime fallbacks. |
| `lupo-actors/` | coordination_surface, active_operational | Actor-specific resources and per-actor workspaces/tools/docs/prompts. |
| `lupo-agents/` | coordination_surface, active_operational | Agent configuration/capability folders (numbered slots and mixed legacy naming). |
| `lupo-prompts/` | coordination_surface, active_operational | Prompt artifacts outside per-thread `lupo-channels/.../prompts` surfaces. |
| `lupo-bin/` | active_operational | CLI/utility entrypoints (`lupo.php`, audits, startup/worker scripts). |
| `lupo-api/` | active_operational | API endpoints and API-related handlers. |
| `lupo-routes/` | active_operational | Route-related PHP surfaces used by runtime modules. |
| `lupo-views/` | active_operational | View-layer files used by runtime UI routes. |
| `lupo-admin/` | active_operational | Admin-specific runtime files (small surface). |
| `lupo-admin_sections/` | active_operational | Admin section fragments (small surface). |
| `lupo-install/` | active_operational | Installer support class(es), including markdown import logic. |
| `lupo-tools/` | active_operational, mixed | Tooling workspace including VSX extension and backup utility surfaces. |
| `lupo-config/` | active_operational | Global atoms and runtime config files consumed by installer/runtime docs. |
| `lupo-uploads/` | active_operational | Uploaded/generated file storage (actors/agents/channels/operators paths). |
| `lupo-cache/` | active_operational, legacy_or_archive | Cache and resolved artifacts; README content is stale historical material. |
| `lupo-logs/` | active_operational | Runtime/admin logging outputs. |
| `lupo-images/` | active_operational | Static image assets. |
| `lupo-templates/` | active_operational, small | Template assets including canonical header template file. |
| `lupo-skills/` | active_operational, small | Skills package area with focused subfolders/readme. |
| `lupo-collections/` | unclear_or_transitional | Very small set of collection snapshot files; limited direct runtime evidence. |
| `lupo-content/` | unclear_or_transitional | Sparse actor/federation path tree; declared by config constants but not a full WordPress-style content tree in current repo state. |
| `lupo-context/` | unclear_or_transitional | Present but currently empty child directories in this workspace snapshot. |
| `lupo-chats/` | unclear_or_transitional | Minimal surface (`rose/json`). |
| `lupo-meta/` | legacy_or_archive, unclear_or_transitional | Contains legacy FLARE tracking JSON surface. |
| `lupo-app/` | active_operational, mixed | Thin root app surface (`Services/`, `help.php`) while fuller app stack currently sits under `lupo-database/lupopedia/content/lupo-app/`. |
| `lupo-archive/` | legacy_or_archive | Archived historical docs/scripts; explicitly non-primary and excluded from active flow. |
| `lupo-backups/` | legacy_or_archive | Backup snapshots and historical recovery artifacts. |
| `lupo-research/` | legacy_or_archive, reference | Research corpus; not core runtime path. |
| `lupo-tmp/` | active_operational, ephemeral | Temporary workspace/scripts. |

### 4.1 Agent filesystem access (4.0.89+)

#### IDE agents (Cursor, Windsurf, Kiro)

**Full repository access** for development — code, scripts, config, and content paths as needed for building and maintaining Lupopedia.

#### PHP agents (DeepSeek, OpenAI, Grok)

**Content-only** writes. May write only under:

- **`lupo-rules/`** (policy / rules markdown)
- **`lupo-docs/`** (documentation)
- **`lupo-channels/`** (channel artifacts)
- **`lupo-content/`** (content tree per project use)

**May not** write to code directories (**`lupo-includes/`**, **`app/`**, **`lupo-scripts/`**, **`lupo-bin/`**, **`lupo-api/`**, …), root PHP entry points, or executable / HTML-JS-capable file types — see **`lupo-docs/ORGANIZATION.md` §2.2** and **`lupo-docs/versions/4.0.89/TODO.md` H9**.

**Code guard:** **`lupo-includes/classes/AgentFileWriter.php`**. **Deployment hardening:** **`lupo-docs/versions/4.0.89/PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`**.

## 5. MySQL and Schema Artifacts

## 5.1 Canonical authority

For 4.0.88, schema authority is:

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`

Observed evidence:

- installer comments in `install.php` explicitly point installer-critical SQL to `lupo-database/lupopedia/mysql/`
- multiple scripts call out `install_new_lupopedia.sql` as canonical schema source

## 5.2 Supporting SQL surfaces

- `lupo-database/lupopedia/mysql/seed/` = seed data layers
- `lupo-database/lupopedia/mysql/import/` = import mapping from old Crafty Syntax
- `lupo-database/lupopedia/mysql/migrations/` = dev migration surfaces
- `lupo-database/lupopedia/mysql/manifest/` = plain manifest text files for install/migrations/seed entries

## 5.3 TOON and JSON exports

- `lupo-database/lupopedia/toon/` currently stores `.toon` YAML-style table snapshots
- `lupo-database/lupopedia/json/` stores JSON table snapshots

These are generated/derived schema representations used by validators/tooling and documentation support, not the install-authority DDL file itself.

## 5.4 Table documentation

- table docs are under `lupo-docs/database/lupopedia/tables/active/`
- these are documentation/reference surfaces that should align with install SQL plus TOON/JSON exports

## 6. File-Based and NoSQL-Like Surfaces

In this repository, “NoSQL-like” means file-based/document-based coordination and state surfaces visible in git and IDE.

Primary file-based coordination/state surfaces:

- `lupo-channels/<channel>/threads/<thread>/...` markdown artifacts
- `lupo-channels/<channel>/broadcasts/...` artifacts
- `lupo-database/sessions/*.md` runtime session context files
- `lupo-sessions/*.json` default/fallback session snapshots
- doctrine and planning markdown under `lupo-docs/`

How they relate to DB:

- channel artifacts can be imported/synchronized into DB (`import_filesystem_channels_to_db.py`, `sync_channel_artifacts.py`)
- DB edge tables/content tables remain runtime authority for structured queries and application behavior
- file artifacts remain essential for multi-agent visibility, git history, reviewability, and offline coordination

## 7. Runtime and Coordination Connections

Observed connection model:

1. `index.php` resolves config and bootstraps runtime.
2. `lupopedia-config.php` defines directory constants and loads bootstrap.
3. `lupo-includes/bootstrap.php` initializes DB/session/auth/services.
4. `lupo-includes/lupopedia-loader.php` loads modules/semantic/agent/ui/rest layers.
5. channel and actor coordination artifacts are consumed by scripts and certain module flows from filesystem paths under `lupo-channels/` and session files.

## 8. Canonical Authority Map

| Area | Canonical authority | Supporting/derived surfaces |
|---|---|---|
| Install schema (4.0.x) | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` | TOON/JSON exports, table docs, migration scripts |
| Runtime DB state | MySQL tables (`lupo_*`) | Channel/session file imports and sync scripts |
| Documentation doctrine | `lupo-docs/doctrine/` + root rule docs | Archived docs and historical notes |
| Version planning state | `lupo-docs/versions/<version>/` | Root summaries (`README.md`, `plan.md`, `TODO.md`) |
| File-based coordination | `lupo-channels/` and `lupo-database/sessions/` | `lupo-sessions/` defaults and script-generated reports |

## 9. Known Confusion Points

Current repository has overlapping or stale documentation in several places.

Most common confusion points:

- older docs still claim deprecated FLARE-era header/version fields as current
- TOON format/location descriptions vary across older docs
- channel index/doctrine docs contain outdated metadata/path expectations
- `lupo-app/` vs `lupo-database/lupopedia/content/lupo-app/` dual location is not consistently explained
- several directories exist with sparse content and weak explicit ownership docs (`lupo-context/`, `lupo-content/`, `lupo-chats/`)

See `lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md` for path-by-path contradictions and suggested fixes.

## 10. 4.0.88 Documentation Pass Status

This 4.0.88 pass adds an evidence-based orientation layer:

- root organization map (this file)
- detailed documentation-structure map in `lupo-docs/ORGANIZATION.md`
- a contradiction/gap report for stale/duplicated/misaligned docs
- version-report artifact describing scope, findings, and follow-up

Follow-up work remains for stale docs outside core orientation entrypoints.

## 11. Navigation and Edge Guidance

### 11.1 Recommended Reading Path

For understanding Lupopedia organization and navigation, follow this path:

1. `README.md` - Project overview and current version
2. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md` - Metadata system
3. `lupo-docs/versions/4.0.89/README.md` - Current version overview
4. `lupo-docs/versions/4.0.89/PLAN.md` - Implementation roadmap
5. `lupo-docs/versions/4.0.89/TODO.md` - Task tracking
6. `lupo-docs/database/README.md` - Database authority
7. `lupo-docs/database/lupopedia/tables/active/` - Table documentation
8. `lupo-docs/IMPLEMENTATION_GETTING_STARTED.md` - Implementation guide (when created)

### 11.2 Edge Navigation System

This file provides navigation edges to guide readers:

```yaml
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.89/README.md"
      type: "references"
      weight: 1.0
      reason: "Current active version overview"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      type: "references"
      weight: 1.0
      reason: "Metadata and validation system"
    - to: "lupo-docs/database/README.md"
      type: "references"
      weight: 0.9
      reason: "Database authority and documentation"
    - to: "lupo-docs/database/lupopedia/tables/active/"
      type: "references"
      weight: 0.8
      reason: "Canonical table documentation"
    - to: "lupo-channels/channel_index.md"
      type: "references"
      weight: 0.8
      reason: "Channel coordination system"
    - to: "lupo-docs/versions/4.0.89/TODO.md"
      type: "references"
      weight: 0.9
      reason: "Current task tracking"
```

### 11.3 Edge Weighting Guide

- **1.0**: Critical/authoritative navigation
- **0.9**: Important supporting documentation
- **0.8**: Useful reference documentation
- **0.6**: Optional/supplementary material
- **0.4**: Historical or reference only

### 11.4 Navigation Success Criteria

Navigation is successful when readers can:
- Understand system organization from this file
- Follow edges to find relevant documentation
- Locate current version information easily
- Bridge from documentation to implementation
- Avoid confusion about authoritative sources
