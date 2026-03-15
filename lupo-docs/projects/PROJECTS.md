---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/doctrine/AGENT_REGISTRY.md"
      reason: "Actor identity and propagation context"
  required_context:
    - "Projects are a semantic layer above channels; no schema changes in this documentation."

lupopedia.metadata:
  comment: "Canonical doctrine for Projects as first-class semantic concept."
  title: "Projects Doctrine"
  description: "Projects group related channels, collections, and dialogs within a federation node."

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/projects/PROJECTS.md"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  purpose: "Canonical doctrine for Projects as semantic container above Channels."
  artifact_type: "doctrine"
  artifact_kind: "reference"
  tags: ["projects", "governance", "lifecycle", "registry"]

lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  federation_node_id: 1

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/projects/PROJECTS_API.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/channels.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md", type: "references", weight: 0.85 }
    - { to: "EXECUTIVE_SUMMARY.md", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "cursor"
  next_action:
    - "When schema supports projects, align registry and tables with this doctrine"
---

# Projects — First-Class Semantic Concept

Projects are a **semantic container** above Channels. They group related work (channels, collections, dialogs) within a federation node. This document defines the doctrine only; **no database schema or code changes** are implied by this task.

---

## Project Definition

A **Project** is a semantic container that groups related work.

**Properties:**

| Property | Description |
|----------|-------------|
| **project_id** | Allocated via registry (deterministic identity). |
| **project_name** | Unique within a federation node. |
| **federation_node_id** | Node where the project exists. |
| **channels** | Zero or more channels belonging to the project. |
| **collections** | Zero or more collections scoped to the project. |
| **actors** | Actors operate across projects but must declare context. |
| **lifecycle_state** | `active`, `archived`, or `frozen`. |

**Rules:**

- Projects **cannot** span multiple federation nodes.
- Channels **belong to exactly one** project.
- Actors **can** operate in multiple projects (with explicit context).
- Cross-node collaboration uses **federation protocols**, not shared projects.

**Hierarchy:**

```text
Federation Node
      ↓
Project
      ↓
Channel
      ↓
Thread / Dialog
```

---

## Project Lifecycle

| State | Description | Permissions |
|-------|-------------|-------------|
| **Active** | Fully operational. | Read/Write |
| **Archived** | Historical reference. | Read-only |
| **Frozen** | Legal/audit preservation. | None |

**Transition rules:**

- **Active → Archived**  
  Requires lead orchestration approval (e.g. Cursor, actor_id 102, or delegated governance).

- **Archived → Active**  
  Requires federation node administrator.

- **Any state → Frozen**  
  Legal or audit hold; no further transitions without explicit release.

---

## Project Governance

Project governance exists **above** channel governance.

**Responsibilities:**

- Approving project lifecycle transitions (Active / Archived / Frozen).
- Maintaining project registry entries.
- Coordinating cross-channel work within the project.
- Managing project-level collections.
- Approving archival.

Lead orchestration agents (see [AGENTS.md](../../AGENTS.md) and [lupo-docs/doctrine/AGENT_REGISTRY.md](../doctrine/AGENT_REGISTRY.md)) coordinate governance. This role does not grant exclusive authority; it maintains root-level and project-level consistency.

---

## Project Registry

Projects must have **deterministic identity** similar to actors. Registry behavior is defined here conceptually; **no database schema changes** are performed in this documentation task.

**Registry properties (conceptual):**

| Property | Description |
|----------|-------------|
| **project_id** | Unique integer; allocated via registry allocator. |
| **project_slug** | Human-readable identifier; recommended for API and docs. |
| **project_name** | Display name; unique within federation node. |
| **federation_node_id** | Node where the project exists. |
| **created_ymdhis** | BIGINT UTC YYYYMMDDHHIISS. |
| **updated_ymdhis** | BIGINT UTC YYYYMMDDHHIISS. |

**Rules:**

- IDs allocated via registry allocator (no AUTO_INCREMENT for project identity in doctrine-aligned implementation).
- **project_name** unique within a federation node.
- **project_slug** recommended for human-readable identity in APIs and links.

**Note:** This documentation defines registry behavior conceptually. For detailed implementation design, see [PROJECT_REGISTRY_DOCTRINE.md](../doctrine/PROJECT_REGISTRY_DOCTRINE.md), [PROJECT_REGISTRY_SCHEMA_DESIGN.md](../database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md), and [PROJECT_REGISTRY_WORKFLOW.md](../doctrine/PROJECT_REGISTRY_WORKFLOW.md).

---

## Implementation (4.0.76)

Schema and application layer are implemented. Canonical locations:

| Component | Location |
|-----------|----------|
| Install schema | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (lupo_projects, lupo_channels.project_id) |
| Seed | `lupo-database/lupopedia/mysql/seed/seed_projects.sql` |
| ProjectService | `lupo-database/lupopedia/content/lupo-app/Services/ProjectService.php` |
| Registry | `lupo-database/lupopedia/projects/registry.json` |
| API | `lupo-api/v1/projects/` (list.php, get.php, create.php, update.php, archive.php, freeze.php) |
| Upgrade guide | [CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md](../status/CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md) |
