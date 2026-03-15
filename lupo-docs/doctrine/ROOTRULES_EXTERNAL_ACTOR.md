---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/projects/PROJECTS.md"
      reason: "Project definition and context"
    - path: "lupo-docs/projects/PROJECTS_API.md"
      reason: "External actor API and capabilities"
  required_context:
    - "External actors are non-IDE agents (e.g. API clients); they must follow this subset of doctrine."

lupopedia.metadata:
  comment: "Subset of root doctrine that external actors must follow."
  title: "Root Rules — External Actor"
  description: "Database, directory, and actor identity rules for external actors."

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  purpose: "Doctrine subset for external actors: database, paths, identity."
  artifact_type: "doctrine"
  artifact_kind: "reference"
  tags: ["external_actor", "root_rules", "database", "identity"]

lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  federation_node_id: 1

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/DATABASE_DOCTRINE.md", type: "references", weight: 0.95 }
    - { to: "lupo-docs/projects/PROJECTS_API.md", type: "references", weight: 1.0 }

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "cursor"
  next_action:
    - "Keep aligned with DATABASE_DOCTRINE and PROJECTS_API when root rules change"
---

# Root Rules — External Actor

This document defines the **subset of doctrine** that **external actors** (API clients, non-IDE agents) must follow. It does not replace full root rules or IDE-specific doctrine; it summarizes the constraints that apply to any external integration.

---

## Database Doctrine

**External actors MUST NOT suggest or generate:**

- Foreign keys
- `DATETIME` or `TIMESTAMP` columns
- Triggers
- Stored procedures
- Vendor-specific SQL (e.g. MySQL-only or PostgreSQL-only syntax that breaks portability)

**External actors MUST use:**

- **BIGINT timestamps** (UTC `YYYYMMDDHHIISS`) for any time values they send or expect.
- **Deterministic, database-agnostic** SQL when any SQL is exposed or suggested (e.g. in tooling or docs).
- Application-set timestamps; no reliance on DB-generated time values.

**Note:** Lupopedia allows AUTO_INCREMENT where doctrine permits it, but **registry allocation is preferred** for actors, channels, and (when implemented) projects. External actors must not assume AUTO_INCREMENT for registry-backed entities.

---

## Directory Doctrine

External actors must use **prefix-aware paths** when referring to Lupopedia directories. Hardcoded paths (e.g. literal `lupo_docs`) are forbidden in integration code or payloads that drive tooling.

**Correct pattern:**

- `{$CONFIG['prefix']}docs`
- `{$CONFIG['prefix']}channels`
- `{$CONFIG['prefix']}database`

(or the equivalent in the deployment’s config: table prefix, path prefix, etc.). The prefix is defined by the environment; external actors must not hardcode `lupo_` or absolute paths.

---

## Actor Identity Rules

**Every external message or request that performs an operation must include:**

- **actor_id** — From the canonical actor registry; no anonymous operations.
- **project_id** — When the operation is project-scoped (see [PROJECTS_API.md](../projects/PROJECTS_API.md)).
- **channel_id** — When the operation is channel- or dialog-scoped.
- **thread_id** — When the operation targets a specific thread/dialog.
- **timestamp** — BIGINT UTC `YYYYMMDDHHIISS`.

**Anonymous operations are prohibited.** External actors must authenticate and supply a valid `actor_id` from the registry (and optional default_project_id / default_channel_id as configured for that actor).

---

## Relation to Full Doctrine

- Full database doctrine: [lupo-docs/doctrine/DATABASE_DOCTRINE.md](DATABASE_DOCTRINE.md).
- Project and API context: [lupo-docs/projects/PROJECTS.md](../projects/PROJECTS.md), [lupo-docs/projects/PROJECTS_API.md](../projects/PROJECTS_API.md).
- Canonical root rules (all agents): [lupo-rules/root/README.md](../../lupo-rules/root/README.md).
