---
lupopedia.headers:
  lupopedia.schema: plan
  file_path_from_root: "lupo-docs/versions/4.0.90/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.90/README.md"
  federation_node_id: 0
  last_modified_utc: "20260328250000"
  when_updated: "20260328250000"
  channel_id: 42
  thread_id: "4-0-90-init"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: plan
  artifact_kind: plan
  purpose: Version 4.0.90 backlog — context model, Crafty Syntax parity, doc clarity, and non-header product work deferred from 4.0.89
  tags:
    - "4.0.90"
    - version
    - backlog
    - context_model
    - crafty_syntax
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.89/README.md"
      type: references
      weight: 1.0
      reason: Prior version — LUPOPEDIA HEADERS release scope
    - to: "lupo-docs/versions/4.0.90/TODO.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.90/PLAN.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/doctrine/CONTEXT_MODEL_DOCTRINE.md"
      type: references
      weight: 0.95
    - to: "lupo-docs/versions/4.0.89/crafty_syntax_backlog.md"
      type: references
      weight: 0.9
lupopedia.footer:
  last_verified: "20260328250000"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
    department_id_delta: 0
  verified_via:
    type: direct
    faucet_slug: none
  orchestrator: "wolfie:root"
  next_action:
    - "Execute TODO.md in dependency order (TASK_PLANNING_DOCTRINE.md)"
    - "Update PLAN.md when major deliverables land"
---

# Lupopedia Version 4.0.90

**Version:** 4.0.90  
**Status:** Opened as **backlog** for work **deferred** from 4.0.89 when that version was **scoped to LUPOPEDIA HEADERS** only.

---

## What belongs in 4.0.90

- **Context model:** database integration (`lupo_contexts`), edges, automation, Channel 66 linkage, TASK_REGISTRY — per `CONTEXT_MODEL_DOCTRINE.md`.
- **Crafty Syntax feature parity:** execution against `lupo-docs/versions/4.0.89/crafty_syntax_backlog.md` and legacy research under `lupo-docs/versions/4.0.89/legacy_research/`.
- **Documentation clarity:** items 5.1–5.4 from the former 4.0.89 TODO (version consistency, navigation, FLARE cleanup, IMPLEMENTATION bridge).
- **Broader system QA / performance** not required to **ship** header import+validation.

## What stays in 4.0.89

- Binding **LUPOPEDIA HEADERS** doctrine, format docs, taxonomy reference.
- **Python** validators (`validate_lupopedia_headers.py`, `validate_lupopedia_headers_universal.py`, `lib/header_validation.py`, `lib/header_db_sync.py`).
- **PHP** header validation classes/scripts and **planned** admin/operator surfacing for the same rules.
- **Import/regenerate** (`import_content.py`, `generate_headers_from_db.py`, `ensure_imported.py`).
- **`lupo-*` organization** rules and **IDE rule packs** (`.cursor/rules`, `.windsurf/rules`) aligned with `lupo-rules/root/` (especially header and DB doctrines).
- **Header-related** install SQL / JSON mirrors and **release-gate testing** (multiple imports, read-back, regenerate).

See **[`lupo-docs/versions/4.0.89/README.md`](../4.0.89/README.md)** and **[`SCOPE_CARRYOVER_FROM_4_0_89.md`](SCOPE_CARRYOVER_FROM_4_0_89.md)**.

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
