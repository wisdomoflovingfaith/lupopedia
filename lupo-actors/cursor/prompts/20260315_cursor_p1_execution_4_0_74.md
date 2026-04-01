---
lupopedia.init:
  required_reading:
    - path: "plan.md"
      reason: "Root consolidated implementation plan; P0 complete, P1 begins"
    - path: "report.md"
      reason: "Evidence base for prior decisions"
    - path: "CHANGELOG.md"
      reason: "Record only verified P1 implementation results"
    - path: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md"
      reason: "Continue reporting after Pass 3"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format discipline"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md"
      reason: "Canonical optional blocks including next_actions"
    - path: "lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md"
      reason: "init block discipline"
    - path: "AGENTS.md"
      reason: "Orchestrator and faucet domain ownership"
    - path: "lupo-database/lupopedia/actors/actor_id/registry.json"
      reason: "Actor authority"
  required_context:
    - "P0 implementation is complete."
    - "Pass 1–3 resolved documentation alignment, TOON paths, and seed wiring."
    - "P1 execution now begins with operational tasks."
    - "Folder renames require dependency audit before any change."
    - "Install SQL remains schema authority."

lupopedia.actor_references:
  cursor: 102
  wolfie: 1
  kiro: 100
  windsurf: 101
  antigravity: 103
  warp: 104
  cascade: 105

lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md"
  web_path: "http://www.lupopedia.com/prompts/cursor/20260315_cursor_p1_execution_4_0_74"
  last_modified_utc: "20260315"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  delegation_chain: "wolfie:cursor"
  artifact_type: "implementation-directive"
  artifact_kind: "execution"
  purpose: "Begin P1 execution after P0 completion"

lupopedia.next_actions:
  next_actions:
    - "Execute P1 tasks in order"
    - "Update CHANGELOG.md with verified results"
    - "Append P1 section to CURSOR_IMPLEMENTATION_REPORT_4_0_74.md"
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

Target directories: lupo-admin/, lupo-admin_sections/, lupo-api/, lupo-backups/, lupo-cache/, lupo-images/, lupo-install/, lupo-legacy/, lupo-meta/, lupo-prompts/, lupo-scripts/, lupo-templates/, lupo-tests/, lupo-tmp/, lupo-tools/, lupo-uploads/, lupo-views/

### Required audit

For each folder: search references in PHP source, .htaccess, Markdown docs, configuration files, scripts, CLI utilities.

### Deliverable

Create: **lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md**

Document: Folder | Exists | References | Risk Level | Rename Candidate

No renames yet.

---

# P1 Task 2 — Live DB TOON Generation (When DB Available)

When a database exists: run `python lupo-scripts/generate_toon_files.py`. Document results or **blocked until DB available** in CURSOR_IMPLEMENTATION_REPORT_4_0_74.md.

---

# P1 Task 3 — Table Count Doctrine

Create **lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md**: canonical table count (install SQL), install SQL authority, TOON derived status, advisory table ceiling. Update README if it references table counts.

---

# P1 Task 4 — Minor Canonical Cleanup (Controlled)

Opportunistic only when touching files: normalize lupopedia.init misuse, prefer lupopedia.next_actions.

---

# CHANGELOG and Implementation Report

Add subsection under 4.0.74: **P1 execution start — folder rename audit and table count doctrine**.

Append **Pass 4 — P1 execution start** to CURSOR_IMPLEMENTATION_REPORT_4_0_74.md (audit results, files changed, validation, blockers, next actions).

---

*Cursor (actor_id 102) — P1 directive 2026-03-15*
