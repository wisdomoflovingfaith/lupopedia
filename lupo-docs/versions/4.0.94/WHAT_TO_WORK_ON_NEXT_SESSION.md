---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403025155"
  file_path_from_root: "lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
  last_modified_utc: "20260403025155"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4-0-94-handoff"
  title: "What to work on next session"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "handoff"
  purpose: "Prioritized backlog for the next working session (orchestrator: admin UI, install, Crafty parity, Eye)"
  status: "active"
  tags:
    - "4.0.94"
    - "handoff"
    - "next_session"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 1.0
      reason: "Master backlog"
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Phase order"
    - to: "lupo-docs/prd/31_implementation_folder_guidelines.md"
      type: references
      weight: 0.9
      reason: "Scaffold/templates follow-up"
lupopedia.footer:
  last_verified: "20260403025155"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# What to work on next session (4.0.94)

**Recorded (UTC):** `20260403025155`

Orchestrator direction: shift toward **admin web interface** work, grounded in a **reproducible install**.

## 1. Baseline: fresh install + Crafty Syntax import

- Run the **canonical** dev cycle: legacy Crafty tables → **install wizard** → **seed** → verify (see root **`AGENTS.md`** / **`README.md`** installer paths).
- **Only** supported data import: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** (constitutional single-upgrade path).
- Capture any **schema / seed drift** as explicit **`TODO.md`** rows with evidence paths (no silent fixes).

## 2. Admin web interface

- After baseline works, prioritize **operator/admin** flows that unlock day-to-day development (auth, actors, channels, content edges as needed).
- Cross-link **`PLAN.md`** Phase D (certification, health, UI, onboarding) and **`TODO.md`** “Product / agents” / “Real-time chat” sections when picking tasks.

## 3. Crafty Syntax feature parity

- Enumerate **missing Crafty-era behaviors** vs current Lupopedia admin and chat surfaces; track as **checklist** items (dependency order, not calendar estimates).
- Avoid mixing “parity” work with unrelated refactors.

## 4. Semantic “The Eye” feature

- Treat **Eye** / semantic monitoring as a **product** slice: UI polish + semantic monitor logic already listed in **`PLAN.md`** Phase D; align with **`MOOD_RGB_DOCTRINE.md`** / semantic channel threads where relevant.
- Define **done** in **`TODO.md`** when a concrete artifact (PRD section, admin route, or channel decision) exists.

## 5. Immediate doc/code follow-ups (small)

- **`lupo-scripts/scaffold_implementation.py`:** align copied templates with **PRD 31** LUPOPEDIA HEADER placeholders (**PRD 31** `next_action`).
- Optional: **`VALID_SCHEMA_VALUES`** in **`validate_lupopedia_headers_universal.py`** — add **`prd`** to silence schema WARN (separate hygiene PR).

---

**Next read:** [`TODO.md`](TODO.md), [`PLAN.md`](PLAN.md), [`CHANGELOG.md`](CHANGELOG.md) (top entry for last session).

This output complies with Lupopedia Constitutional Root Rules.
