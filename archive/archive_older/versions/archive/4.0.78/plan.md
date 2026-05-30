---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.78/PLAN.md"
  web_path: "[PLAN](http://www.lupopedia.com/versions/4.0.78/PLAN)"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: version_plan
  thread_id: ""
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
# file: Version 4.0.78 Plan — web_path: http://www.lupopedia.com/versions/4.0.78/PLAN

# Lupopedia 4.0.78 — Implementation Plan

**Opened:** 2026-03-16 (post–4.0.77 release). All work below was deferred from 4.0.77; see [TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md](../../status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md).

**Active scope (4.0.78):** The table documentation initiative is now bounded to the **Top 50 operational tables**. Domain priority order: (1) core, (2) channels, (3) auth, (4) content, (5) analytics. Lower-value edge-case docs (index files, handoff docs, planning-only, legacy reference) are deferred. The full table-doc inventory remains future backlog; success is measured against the Top 50. Authority: [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md).

---

## Phase 1 — Table documentation initiative (Top 50 operational tables)

- **1.1 Priority 1 core tables** *(done 4.0.78)*
  - **lupo_channels.md** and **lupo_actors.md** refreshed to 4.0.78 LUPOPEDIA_HEADERS with Table Overview, "Where This Table Is Used," column docs aligned to install SQL, relationships, and doctrine notes (Cursor).

- **1.2 Priority 2 tables** *(done 4.0.78)*
  - **lupo_actor_apps.md**, **lupo_channel_departments.md**, **lupo_edge_type_definitions.md** — Updated to 4.0.78 LUPOPEDIA_HEADERS with Table Overview, "Where This Table Is Used," column docs aligned to install SQL, relationships, and doctrine notes (Cursor).

- **1.3 Priority 3 tables** *(done 4.0.78)*
  - **lupo_analytics_visits.md**, **lupo_audit_log.md**, **lupo_system_logs.md** — Updated to 4.0.78 LUPOPEDIA_HEADERS with Table Overview, "Where This Table Is Used," column docs (lupo_audit_log from install SQL; lupo_analytics_visits and lupo_system_logs from existing docs with schema-source notes), relationships, and doctrine notes. lupo_audit_log aligned to install_new_lupopedia.sql; analytics and system_logs documented with note that they are not in current install (lupo_visits and lupo_unified_log referenced).

- **1.4 Pattern and truth**
  - Use Zencoder’s four development table docs and Cursor-updated lupo_sessions / lupo_contents as the model. Schema truth: [install_new_lupopedia.sql](../../../database/lupopedia/mysql/install/install_new_lupopedia.sql) → TOON → table markdown. Top 50 scope: [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md).

- **1.5 Top 50 reframing** *(done 4.0.78)*
  - Active scope narrowed from 161-table inventory to **Top 50 operational tables**. Domain priority: core, channels, auth, content, analytics. Authority: [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md). Edge-case docs (index, handoff, planning-only) deferred.

- **1.6 Next Top 50 batch (core)** *(done 4.0.78)*
  - **lupo_metadata.md**, **lupo_atoms.md**, **lupo_collections.md**, **lupo_departments.md** updated to 4.0.78 LUPOPEDIA_HEADERS with Table Overview, "Where This Table Is Used," column docs from install SQL, indexes, relationships, doctrine notes. Zencoder pattern; namespace core/content/governance as appropriate.

- **1.7 Next Top 50 batch (core, federation, auth)** *(done 4.0.78)*
  - **lupo_registry.md**, **lupo_modules.md**, **lupo_federation_nodes.md** (core/federation), **lupo_auth_users.md** (auth) updated to 4.0.78 LUPOPEDIA_HEADERS with Table Overview, Where This Table Is Used, column docs from install SQL, indexes, relationships, doctrine notes. Namespace core/federation/auth; reserved-ID doctrine noted for lupo_auth_users. Reports refreshed: 25 at 4.0.78, 325 requiring update.

---

## Phase 2 — Header cleanup preparation *(done 4.0.78)*

- **2.0 Header cleanup framework**
  - **Scanner:** [scan_table_doc_headers.py](../../../scripts/scan_table_doc_headers.py) recursively scans `docs/database/lupopedia/tables/`, detects LUPOPEDIA_HEADERS, extracts `lupopedia.version` / `system_version` / `file_path_from_root`, reports files where `system_version` != 4.0.78.
  - **Report:** [table_doc_header_version_report_4_0_78.md](../../status/table_doc_header_version_report_4_0_78.md) — summary (total, at 4.0.78, requiring update), file list table, and header anomalies (duplicate blocks, legacy FLARE, missing headers). Enables safe, controlled mass updates later; no mass edits in this phase.

- **2.1 Mass header version update** *(deferred; use report to drive)*
  - 80+ table docs still have 4.0.73 (or earlier) in headers. Use the header version report to plan batch update to 4.0.78 where appropriate. Avoid low-value bulk rewrites that don’t add "Where Used" or content.

- **2.2 Remaining LUPOPEDIA HEADERS doctrine**
  - Any unfinished header-doctrine items from 4.0.77 TODO (e.g. lupopedia.init correctness, snapshot comments for edges/engagement) can be scheduled here.

---

## Phase 2.5 — Namespace doctrine, validator, audit, cleanup *(done 4.0.78)*

- **Doctrine:** Namespace formalized in [LUPOPEDIA_HEADERS_FORMAT.md](../../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §2.2 (required for table docs; approved taxonomy; node-local). [synthesized-framework.md](../../synthesized-framework.md) and [VALIDATORS_AND_TOOLING.md](../../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) aligned.
- **Validator:** [validate_lupopedia_headers.php](../../../scripts/validate_lupopedia_headers.php) enhanced for namespace (required on table docs; value validation). Fixtures added under tests/fixtures/headers/ and tables/_validator_fixtures/.
- **Audit:** [audit_namespace_headers.py](../../../scripts/audit_namespace_headers.py) and [namespace_audit_4_0_78.md](../../status/namespace_audit_4_0_78.md) generated; artifact-type policy documented (table = required; API/rule/skill/planning/status = optional TBD).
- **Cleanup:** Systematic namespace added/normalized across table docs ([apply_namespace_to_table_docs.py](../../../scripts/apply_namespace_to_table_docs.py) + manual fixes); auth/channels/core/content/analytics/governance/integration/legacy applied; Priority 1–3 docs included.

- **Documentation debt — synthesized framework:** [synthesized-framework.md](../../synthesized-framework.md) documentation debt resolved; file migrated to canonical 4.0.78 LUPOPEDIA_HEADERS with historical quadrant values preserved in `lupopedia.metadata`.

---

## Phase 2.6 — Compliance cleanup *(done 4.0.78)*

- **Linking:** File references in PLAN, TODO, CHANGELOG use Markdown links (Windsurf Option A) where appropriate.
- **Namespace/header pass:** Namespace and 4.0.78 headers applied to lupo_sessions, lupo_contents, lupo_agent_faucets, lupo_comments, lupo_uploads, lupo_visits, lupo_dialog_messages; [apply_namespace_to_table_docs.py](../../../scripts/apply_namespace_to_table_docs.py) updated for Windows line endings. Reports refreshed; backlog reduced (136 missing namespace, 333 needing version update).

---

## Phase 3 — Optional automation and validation

- **3.1 Markdown-from-TOON automation (optional)**
  - If useful, design a script or tool that generates or updates table markdown from TOON/install SQL (structure only; "Where Used" remains manual).

- **3.2 Repo-wide completeness validation (optional)**
  - Run or document a check that table docs align with current schema (install SQL / TOON) and list mismatches.

---

## Coordination

- **Lead agent:** Cursor (102). Table-doc work is bounded to **Top 50 operational tables**; domain priority: core, channels, auth, content, analytics. See [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md) for list and priorities; pattern in [TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md](../../status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md).
- **Do not redo:** Zencoder’s four development table docs and Cursor-updated lupo_sessions and lupo_contents are at 4.0.77 standard; do not overwrite with generic template text. **Out of scope this phase:** Index docs, handoff docs, planning-only docs, legacy reference docs; full table-doc corpus is future backlog.
