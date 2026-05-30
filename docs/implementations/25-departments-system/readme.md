---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260405205804"
  file_path_from_root: "docs/implementations/25_departments_system/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/25_departments_system/README.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation
  artifact_kind: workspace
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "in_progress"
  parent_pk_id: "25_departments_system"
  summary: ""
  module: null
  dialog_transcript: null
---
# Implementation: Departments system (PRD 25)

Single workspace for **department-scoped actors**, **root hybrids** (captain/wolfie, lilith, countermeasure), **Crafty import** per-department Wolfie-model actors, and **access/schema** design docs.

**Folder name:** **`25_departments_system`** — exactly the basename of **`docs/prd/25_departments_system.md`** (merged 2026-04-05 from the mistaken duplicate **`25_departments_systems/`**).

- **Canonical PRD:** [docs/prd/25_departments_system.md](../../prd/25_departments_system.md)
- **Auth / act-as:** [docs/prd/05_auth_user_actor_agent_transformation.md](../../prd/05_auth_user_actor_agent_transformation.md)
- **Layout rules:** [PRD 31 — Implementation folder guidelines](../../prd/31_implementation_folder_guidelines.md)

## Status (summary)

| Component | Status | Notes |
|-----------|--------|--------|
| Database schema (design) | Documented | See **`mapping_tables.md`**, **`decisions/database_schema/`** |
| Access control (design) | Documented | **`access_control.md`** |
| IDE protection (headers) | Documented | **`ide_protection_plan.md`** |
| PHP runtime classes | Product backlog | See **`todo.md`** |
| Tests | Stub | **`tests/README.md`** |

## Key files

| File | Purpose |
|------|---------|
| [access_control.md](./access_control.md) | Access control design |
| [mapping_tables.md](./mapping_tables.md) | Table mapping |
| [ide_protection_plan.md](./ide_protection_plan.md) | LUPOPEDIA headers / IDE protection |
| [authors.md](./authors.md) | Provenance |
| [edges.md](./edges.md) | Cross-links |
| [changelog.md](./changelog.md) | Implementation changelog |
| [todo.md](./todo.md) | Remaining tasks |
| [decisions/](./decisions/) | Decision threads (schema, FK policy, permissions, audit) |
| [questions/](./questions/) | PRD 31 questions |
| [versions/v1.0.0/](./versions/v1.0.0/) | Snapshot |

## Version snapshot

- [versions/v1.0.0/](./versions/v1.0.0/) — initial documentation snapshot
