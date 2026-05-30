---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: plan
  when_updated: "20260328250000"
  file_path_from_root: "docs/versions/4.0.90/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.90/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: plan
  thread_id: "4-0-90-init"
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
# Lupopedia Version 4.0.90

**Version:** 4.0.90  
**Status:** Opened as **backlog** for work **deferred** from 4.0.89 when that version was **scoped to LUPOPEDIA HEADERS** only.

---

## What belongs in 4.0.90

- **Context model:** database integration (`lupo_contexts`), edges, automation, Channel 66 linkage, TASK_REGISTRY — per `CONTEXT_MODEL_DOCTRINE.md`.
- **Crafty Syntax feature parity:** execution against `docs/versions/4.0.89/crafty_syntax_backlog.md` and legacy research under `docs/versions/4.0.89/legacy_research/`.
- **Documentation clarity:** items 5.1–5.4 from the former 4.0.89 TODO (version consistency, navigation, FLARE cleanup, IMPLEMENTATION bridge).
- **Broader system QA / performance** not required to **ship** header import+validation.

## What stays in 4.0.89

- Binding **LUPOPEDIA HEADERS** doctrine, format docs, taxonomy reference.
- **Python** validators (`validate_lupopedia_headers.py`, `validate_lupopedia_headers_universal.py`, `lib/header_validation.py`, `lib/header_db_sync.py`).
- **PHP** header validation classes/scripts and **planned** admin/operator surfacing for the same rules.
- **Import/regenerate** (`import_content.py`, `generate_headers_from_db.py`, `ensure_imported.py`).
- **`*` organization** rules and **IDE rule packs** (`.cursor/rules`, `.windsurf/rules`) aligned with `rules/root/` (especially header and DB doctrines).
- **Header-related** install SQL / JSON mirrors and **release-gate testing** (multiple imports, read-back, regenerate).

See **[`docs/versions/4.0.89/README.md`](../4.0.89/README.md)** and **[`SCOPE_CARRYOVER_FROM_4_0_89.md`](SCOPE_CARRYOVER_FROM_4_0_89.md)**.

---

## Version folder contents

| File | Role |
|------|------|
| `README.md` | This overview |
| `TODO.md` | Active backlog (carried from pre-refocus 4.0.89) |
| `PLAN.md` | Dependency-ordered plan + retrospective appendix from 4.0.89 |
| `CHANGELOG.md` | 4.0.90 version log (starts when work lands) |
| `SCOPE_CARRYOVER_FROM_4_0_89.md` | What moved and why |

---

**WOLFIE (actor_id 1)** — 4.0.90 opened for non-header product execution after 4.0.89 header release criteria are met.

---

## Major 4.0.90 Event: Actor Registry/Seed Refactor & HEPHAESTUS Protocol

In 4.0.90, the canonical actor/agent registry is now seeded directly in SQL (`seed_actors_agents_4.0.45.sql`), not JSON/TOON files. The HEPHAESTUS protocol establishes actor 102 as the canonical work agent and all IDEs as faucets (execution surfaces). See CHANGELOG.md and PLAN.md for details.

**Logged by CURSOR (actor_id 102)**

---

## 2026-03-29 — Doctrine Enforcement and Guardrail Protocol

- Doctrine enforcement is now computational: `rules/enforce_doctrine.py` scans all .sql/.json for forbidden constructs and field naming violations.
- LILITH audit: Mandated that all future schema/code changes pass the validator; documentation alone is no longer sufficient.
- See CHANGELOG.md for full log of schema, doctrine, and enforcement changes.

**Logged by CURSOR (actor_id 102) and LILITH (actor_id 2)**
