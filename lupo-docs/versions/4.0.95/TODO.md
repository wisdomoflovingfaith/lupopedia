---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.95"
  file_path_from_root: "lupo-docs/versions/4.0.95/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.95/TODO.md"
  when_updated: "20260407172944"
  last_modified_utc: "20260407172944"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.95-todo"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Closed registry for Lupopedia 4.0.95 — no open tasks; successor backlog is 4.0.96"
  tags: ["todo", "version", "4.0.95", "cursor", "finalized"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 1.0
      reason: "Closed 4.0.94 checklist"
    - to: "lupo-docs/versions/4.0.95/PLAN.md"
      type: references
      weight: 1.0
      reason: "Plan for this line (finalized)"
    - to: "lupo-docs/versions/4.0.96/TODO.md"
      type: references
      weight: 1.0
      reason: "Active backlog — tasks migrated from 4.0.95"
lupopedia.footer:
  last_verified: "20260407172944"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.95/TODO.md — delegation: cursor:root

# TODO - Lupopedia 4.0.95 (FINALIZED)

**Version:** 4.0.95 — **closed** (UTC `20260407172944`)  
**Successor backlog:** **[../4.0.96/TODO.md](../4.0.96/TODO.md)**

This file intentionally contains **no open tasks**. Items that were open at closeout were migrated to **`lupo-docs/versions/4.0.96/TODO.md`** under **Carried Over from 4.0.95**.

---

## Historical note

**Source:** Deferred work had been tracked from **`lupo-docs/versions/4.0.94/TODO.md`**. Items **P3-005, P3-006, P3-007, P4-002, P4-006** were completed in **4.0.94** (packaging gate). Schema/runtime alignment **T-SCHEMA-RUNTIME-001** and **T-SCHEMA-TOOLCALLS-001** were completed in **4.0.95** (see **`CHANGELOG.md`**).

---

## Completed in 4.0.95 (archive)

### Schema / runtime alignment (carry from 4.0.94 install merge — COMPLETED 2026-04-07)

- [x] **T-SCHEMA-RUNTIME-001:** Align PHP installer + seed SQL with renamed/removed install tables (see **`CHANGELOG.md`**).
- [x] **T-SCHEMA-TOOLCALLS-001:** `lupo_agent_tool_calls` restored to `install_new_lupopedia.sql` (see **`CHANGELOG.md`**).

---

This output complies with Lupopedia Constitutional Root Rules.
