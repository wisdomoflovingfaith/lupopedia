---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260407015813"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md"
  last_modified_utc: "20260407015813"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-decisions"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "decision"
  artifact_kind: "version"
  purpose: "Receipt — merge schema_review corrected SQL into canonical install; table diff vs backup; follow-ups"
  tags: ["decision", "4.0.94", "schema", "install", "cursor"]
lupopedia.footer:
  last_verified: "20260407015813"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/decisions/20260407_015813_DECISION_cursor_install_schema_merge_receipt.md — delegation: cursor:root

# DECISION — Receipt: corrected schema merged into canonical install (Cursor thread)

**Status:** RECORDED (implementation completed in tree; runtime/seed alignment is follow-up)

## 5W1H

| Element | Answer |
|--------|--------|
| **WHO** | Cursor IDE agent (facet **actor_id 102**), documenting work performed in the Cursor thread that merged `schema_review` outputs into the installer. |
| **WHAT** | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` was updated from **`schema_corrected_core.sql`** and **`schema_corrected_missing.sql`** (DDL applied as authored there). A table-name set diff was run against **`install_new_lupopedia_backup_20260406.sql`**. Thread Q&A: redundant **`questions` / `answers` / `question_map`** removed in favor of existing **`truth_*`** tables. |
| **WHERE** | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (canonical); sources under `lupo-database/lupopedia/mysql/schema_review/`; optional local backup `install_new_lupopedia_backup_20260406.sql`. |
| **WHEN** | Epoch **2026-04-07 ~02:00 UTC** (temporal anchor **`20260407015813`** from `python lupo-bin/tick.py` for this documentation batch). |
| **WHY** | Close the gap left in **4.0.94** PLAN Phase 8 (“apply corrected schema back into install”); keep version docs aligned with actual tree state. |
| **HOW** | Merge replaced/added `CREATE TABLE` blocks per corrected files; removed duplicate `edges` indexes and deprecated tables called out in SECTION 20–21 of `schema_corrected_core.sql`; validated no duplicate `CREATE TABLE` names in the install script (170 tables after merge). |

## Verified facts (thread-scoped)

- **Table count (install):** **170** `CREATE TABLE {{prefix}}…` definitions.
- **Vs backup `install_new_lupopedia_backup_20260406.sql`:** **163** tables → **24** table names only in new install, **17** only in backup (net **+7**).
- **All 10** `CREATE TABLE` blocks from **`schema_corrected_missing.sql`** (through SECTION 8) are present in the current install.
- **Deprecated/removed** from install per corrected core (non-exhaustive): `edge_type_definitions`, `event_metadata`, `edge_map`, `questions`, `answers`, `question_map`; old `agents` / `agent_faucets` / `agent_versions` block replaced by the split model (`agent_definitions`, `actor_faucets`, `versions`, etc.).

## Follow-up (not done in this thread)

- **PHP / seed / import:** Code and seeds may still reference **`lupo_agents`**, **`lupo_agent_faucets`**, or removed tables; align in a separate pass.
- **`lupo_agent_tool_calls`:** `schema_corrected_missing.sql` SECTION 9 describes an **ALTER** (add `actor_id`), not removal; the merged install **does not** currently define **`agent_tool_calls`** (nor several other former `agent_*` satellite tables). Restore or replace if logging is still required.

## Edges

- **Sources:** `lupo-database/lupopedia/mysql/schema_review/schema_corrected_core.sql`, `schema_corrected_missing.sql`
- **Prior approval batch:** `decisions/20260406_200000_DECISION_APPROVED_schema_review_chronos_activation_migration_docs.md`
- **Q/A:** `questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md` → `answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md`

This decision documents thread-verified outcomes only; it does not re-assert unrelated PRD or validator work.
