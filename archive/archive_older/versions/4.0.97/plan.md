---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260409205948"
  file_path_from_root: "docs/versions/4.0.97/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupopedia/docs/versions/4.0.97/PLAN.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: version
  thread_id: "version-4.0.97-plan"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# PLAN — Lupopedia 4.0.97 (ARCHIVED)

Active execution plan moved to `docs/versions/4.0.98/PLAN.md` at UTC `20260410063703`.

## Phase Status

| Phase | Description | Status | Completed |
|-------|-------------|--------|-----------|
| Phase A | Session identity (filtered UA) | COMPLETE | 2026-04-09 |
| Phase B | Memory compaction (DB-first + fallback) | COMPLETE | 2026-04-09 |
| Phase C | Emoji stripping | COMPLETE | 2026-04-09 |
| Phase D | LUPOPEDIA HEADERS v3 | COMPLETE | 2026-04-09 |
| Phase E | Channel key migration | COMPLETE | 2026-04-09 |
| Phase F | Top 12 PRD migration | COMPLETE | 2026-04-09 |
| Phase G | Trust Ladder locking | NEXT | Pending |
| Phase H | PostgreSQL migration | PLANNED | 4.0.98 |
| Phase I | Vector search | PLANNED | 4.1.0 |
| Phase J | Multi-agent verification handoff | NEXT | Pending |
| Phase K | Strict header validations v3 | COMPLETE | 2026-04-09 |
| Phase L | Public semantic chrome (shortcut pin → tab map, Eye modes, book/scroll shell) | COMPLETE | 2026-04-09 20:59 UTC |

## Current Focus

**Next up:** Trust Ladder SELECT FOR UPDATE locking (H-01)

**Blocked by:** Nothing

**Waiting for:** Claude Code token reset

## Multi-Agent Follow Through

- Claude Code (actor 116): validate memory load flow + tier path behavior in active tasks; smoke **`add_to_collection.php`** + slug resolution if touching content APIs
- Windsurf IDE agent: verify header/body integrity on migrated PRDs; optional **JS** pass on **`main-layout-collections.js`** / **`monitor.js`** (no desktop template rewrites per doctrine)
- Kiro IDE agent: run focused checks on TODO H-01/H-02 readiness; consider **integration test** for pin API (logged-in actor)
- Antigravity IDE agent: cross-check channel-key registry consistency; confirm **no duplicate** tabs API beyond **`load_collection_tabs.php`** / **`get_actor_tabs.php`** alias

This output complies with Lupopedia Constitutional Root Rules.
