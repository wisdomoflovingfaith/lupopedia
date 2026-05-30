---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: changelog
  when_updated: "20260328250000"
  file_path_from_root: "docs/versions/4.0.90/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.90/CHANGELOG.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: changelog
  artifact_kind: version_specific
  thread_id: "4-0-90-changelog"
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
# Changelog — Version 4.0.90

**Version:** 4.0.90  
**Status:** Opened — backlog only; no release tag yet.

---

## 2026-03-28 — Version folder opened

- Created `docs/versions/4.0.90/` with `README.md`, `TODO.md`, `PLAN.md`, `CHANGELOG.md`, `SCOPE_CARRYOVER_FROM_4_0_89.md`.
- **Reason:** **4.0.89** release scope narrowed to **LUPOPEDIA HEADERS** (validation, import, `*` organization, IDE rules, header-related DB). Context model, Crafty Syntax execution, and doc-clarity tasks **5.1–5.4** move here.

---

**WOLFIE (actor_id 1)**

---

## 2026-03-29 — Schema, Doctrine, and Actor Registry Refactor (HEPHAESTUS Protocol)

- Refactored canonical actor/agent registry to be seeded directly in SQL (`seed_actors_agents_4.0.45.sql`), not JSON/TOON files.
- Added explicit, doctrine-compliant INSERTs for HEPHAESTUS (actor_id 102) and LILITH (actor_id 2) with canonical_identity JSON.
- Updated install/seed logic to ensure all critical actors are persistent and doctrine-aligned.
- Codified the "Faucet Proxy Pattern" and made HEPHAESTUS the canonical work agent; all IDEs are now faucets (execution surfaces).
- Updated MULTI_AGENT_COORDINATION_DOCTRINE.md and created HEPHAESTUS_DOCTRINE.md to reflect new identity protocol.
- Registry and JSON outputs updated for reference, but SQL is now the only authoritative source for actor identity.
- All changes logged without overwriting or duplicating prior actor entries.

## 2026-03-29 — Context/Edge Schema, Doctrine Enforcement, and Guardrail Protocol (CURSOR, LILITH)

- Refactored `lupo_contexts` and `lupo_edges` schema in install SQL to match doctrine and PRD (no foreign keys, no auto-increment, all IDs/timestamps application-driven, field names/types per PRD).
- Added doctrine comments and PRD references to canonical install SQL for context/edge tables.
- Created `rules/enforce_doctrine.py` — a hard-stop validator script that scans all .sql/.json for forbidden constructs (FOREIGN KEY, AUTO_INCREMENT, TIMESTAMP, etc.) and enforces field naming rules.
- LILITH audit: Mandated computational enforcement of doctrine, not just documentation; validator is now required for all future codegen and sync.
- Updated backlog and plan to include doctrine enforcement and validator integration as critical path.
- No prior actor entries overwritten or duplicated; all changes logged as CURSOR (actor_id 102) and LILITH (actor_id 2) per audit.

**CURSOR (actor_id 102)**
