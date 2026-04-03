---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md"
  when_updated: "20260403222041"
  last_modified_utc: "20260403222041"
  channel_id: 42
  federation_node_id: 0
  thread_id: "version-4-0-94-decisions"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: decision
  artifact_kind: approval
  purpose: "APPROVED department-first actor model documentation, ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE, and aligned PRDs (LILITH audit)"
  status: approved
  tags:
    - "4.0.94"
    - "decision"
    - "department_model"
    - "lilith_audit"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical join model auth_user, department, actor"
    - to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Edge to actor/department doctrine from five-layer doc"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Visitor chain; act-as"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Actors in departments"
    - to: "lupo-docs/implementations/13_crafty_integration/questions/THREAD_INDEX.md"
      type: references
      weight: 0.95
      reason: "Visitor chat Q1–Q3 threads"
lupopedia.footer:
  last_verified: "20260403222041"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
---

# file: DECISION — APPROVED department-first actor model + PRD alignment — web_path: /lupo-docs/versions/4.0.94/decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md

# DECISION (APPROVED): Department-first actor model — canonical doctrine + PRD alignment

## 5W1H (thread-verified)

| Field | Value |
|-------|--------|
| **WHO** | Cursor (`actor_id` **102**); LILITH audit (`actor_id` **2**); orchestrator WOLFIE (`actor_id` **1**) |
| **WHAT** | New **`lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`** (join tables, eligibility, visitor chat vs Crafty, illustrative SQL with PHP disclaimer). **`IDENTITY_LAYERS_DOCTRINE.md`** outbound edge to that file. PRD prose/edges updated: **02**, **05**, **07**, **13**, **15**, **18**, **25**, **32** — department intersection, **`from_actor_id`** / **`actor_id`** attribution, governance vs operational scope, Crafty migration narrative; **`lupo-docs/implementations/13_crafty_integration/questions/`** Q3 marked answered. |
| **WHERE** | `lupo-docs/doctrine/`, `lupo-docs/prd/`, `lupo-docs/implementations/13_crafty_integration/` |
| **WHEN** | Documentation batch UTC **`20260403222041`** (`python lupo-bin/tick.py`) |
| **WHY** | Single canonical mental model; PRDs must not contradict department-first act-as or actor-first chat |
| **HOW** | Edits + LILITH audit notes in PRDs; no new install SQL in this thread |

## Core principles (approved)

- Actors **belong to departments** via **`lupo_actor_departments`**; humans via **`lupo_auth_user_departments`**; web act-as eligibility is **department intersection** first; **`lupo_actor_auth_users`** is supplementary (import/audit), not the sole gate.
- Visitor-facing chat: **`actor_id`** primary; **`auth_user`** fallback/accountability — **PRD 05**, **PRD 18**, doctrine §4.

## Deprecates / do not use as sole authority

- Inferring web act-as **only** from **`lupo_edges`** “supports” lists for department hybrids (implementation defers to **`AuthSessionManager::getActorsUserCanActAs`** per **PRD 05** / **15**).
- Modeling chat as **only** visitor ↔ **`auth_user`** without **`actor_id`** resolution (Crafty operator mental model — see **PRD 13** migration narrative).

## WHAT NOT claimed here

- **No** assertion that **`SILENT_HARVEST_DOCTRINE.md`**, **`MOBILE_SEPARATION_DOCTRINE.md`**, or **`WOLFIE_WORKFLOW_DOCTRINE.md`** were **created** in this thread (they pre-exist; federation / Two-UI narrative cites them elsewhere in **`4.0.94/CHANGELOG.md`**).
- **No** “navigation compiler” product shipped — see open **QUESTION** `20260403_222042_QUESTION_federation_navigation_compiler.md`.

This output complies with Lupopedia Constitutional Root Rules.
