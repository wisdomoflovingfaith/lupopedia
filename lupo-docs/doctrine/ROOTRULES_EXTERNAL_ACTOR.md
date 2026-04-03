---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/ROOTRULES_EXTERNAL_ACTOR.md"
  last_modified_utc: "20260403113047"
  when_updated: "20260403113047"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "ROOTRULES EXTERNAL ACTOR"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
---

# file: ROOTRULES_EXTERNAL_ACTOR — delegation: cursor:root

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
