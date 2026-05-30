---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/cursor/prompts/20260315_cursor_p1_execution_4_0_74.md
  web_path: https://www.lupopedia.com/lupopedia/actors/cursor/prompts/20260315_cursor_p1_execution_4_0_74.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: implementation-directive
  artifact_kind: execution
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: directive
  prd_cluster: null
  title: null
  summary: null
---
# Cursor Implementation Directive — P1 Execution (v4.0.74)

Cursor, P0 is complete.  
You are now executing **P1 tasks**.

Lilith's review confirms:

- TOON path alignment complete
- seed_projects installer wiring complete
- schema inventory complete
- repo alignment complete

Only one P0 item remains deferred: **folder renames**.

That work begins now.

---

# P1 Task 1 — Folder Rename Dependency Audit

Folder renames must **not happen yet**.

First perform a **full dependency audit**.

Target directories: admin/, admin_sections/, api/, backups/, cache/, images/, install/, legacy/, meta/, prompts/, scripts/, templates/, tests/, tmp/, tools/, uploads/, views/

### Required audit

For each folder: search references in PHP source, .htaccess, Markdown docs, configuration files, scripts, CLI utilities.

### Deliverable

Create: **docs/status/FOLDER_RENAME_AUDIT_4_0_74.md**

Document: Folder | Exists | References | Risk Level | Rename Candidate

No renames yet.

---

# P1 Task 2 — Live DB TOON Generation (When DB Available)

When a database exists: run `python scripts/generate_toon_files.py`. Document results or **blocked until DB available** in CURSOR_IMPLEMENTATION_REPORT_4_0_74.md.

---

# P1 Task 3 — Table Count Doctrine

Create **docs/doctrine/TABLE_COUNT_DOCTRINE.md**: canonical table count (install SQL), install SQL authority, TOON derived status, advisory table ceiling. Update README if it references table counts.

---

# P1 Task 4 — Minor Canonical Cleanup (Controlled)

Opportunistic only when touching files: normalize lupopedia.init misuse, prefer lupopedia.next_actions.

---

# CHANGELOG and Implementation Report

Add subsection under 4.0.74: **P1 execution start — folder rename audit and table count doctrine**.

Append **Pass 4 — P1 execution start** to CURSOR_IMPLEMENTATION_REPORT_4_0_74.md (audit results, files changed, validation, blockers, next actions).

---

*Cursor (actor_id 102) — P1 directive 2026-03-15*
