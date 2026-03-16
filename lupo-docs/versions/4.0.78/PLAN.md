---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "documentation"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/versions/4.0.78/PLAN.md"
  web_path: "[PLAN](http://www.lupopedia.com/versions/4.0.78/PLAN)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "plan"
  artifact_kind: "version_plan"
  purpose: "Dependency-ordered implementation plan for 4.0.78 (table documentation continuation, header cleanup, optional automation)"
  tags: ["plan", "4.0.78", "table_documentation", "headers"]

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
  next_action:
    - "Use this plan to coordinate 4.0.78 work; follow TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md for table-doc pattern and priorities"
---
# file: Version 4.0.78 Plan — web_path: http://www.lupopedia.com/versions/4.0.78/PLAN

# Lupopedia 4.0.78 — Implementation Plan

**Opened:** 2026-03-16 (post–4.0.77 release). All work below was deferred from 4.0.77; see `lupo-docs/status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md`.

---

## Phase 1 — Table documentation initiative (carried forward from 4.0.77)

- **1.1 Priority 1 core tables**
  - Refresh **lupo_channels.md** and **lupo_actors.md** to 4.0.78 LUPOPEDIA_HEADERS and add/align "Where This Table Is Used" (Zencoder pattern). Do not overwrite existing good content; upgrade headers and fill gaps.

- **1.2 Priority 2 tables**
  - **lupo_actor_apps.md**, **lupo_channel_departments.md**, **lupo_edge_type_definitions.md** — Apply Zencoder pattern (4.0.78 headers, Where Used, schema-aligned descriptions, doctrine notes).

- **1.3 Priority 3 tables**
  - **lupo_analytics_visits.md**, **lupo_audit_log.md**, **lupo_system_logs.md** — Same pattern as above.

- **1.4 Pattern and truth**
  - Use Zencoder’s four development table docs and Cursor-updated lupo_sessions / lupo_contents as the model. Schema truth: install SQL → TOON → table markdown. See TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md for next-step guidance.

---

## Phase 2 — Header and version cleanup (deferred from 4.0.77)

- **2.1 Mass header version update**
  - 80+ table docs still have 4.0.73 (or earlier) in headers. Plan a batch update to 4.0.78 where appropriate (e.g. when touching a file for other edits, or a dedicated sweep). Avoid low-value bulk rewrites that don’t add "Where Used" or content.

- **2.2 Remaining LUPOPEDIA HEADERS doctrine**
  - Any unfinished header-doctrine items from 4.0.77 TODO (e.g. lupopedia.init correctness, snapshot comments for edges/engagement) can be scheduled here.

---

## Phase 3 — Optional automation and validation

- **3.1 Markdown-from-TOON automation (optional)**
  - If useful, design a script or tool that generates or updates table markdown from TOON/install SQL (structure only; "Where Used" remains manual).

- **3.2 Repo-wide completeness validation (optional)**
  - Run or document a check that table docs align with current schema (install SQL / TOON) and list mismatches.

---

## Coordination

- **Lead agent:** Cursor (102). Table-doc work can be parallelized by table; follow the priority order in TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md.
- **Do not redo:** Zencoder’s four development table docs and Cursor-updated lupo_sessions and lupo_contents are at 4.0.77 standard; do not overwrite with generic template text.
