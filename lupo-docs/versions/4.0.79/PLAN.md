---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "documentation"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/versions/4.0.79/PLAN.md"
  web_path: "[PLAN](http://www.lupopedia.com/versions/4.0.79/PLAN)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "plan"
  artifact_kind: "version_plan"
  purpose: "Dependency-ordered implementation plan for 4.0.79 (remaining Top 50 table documentation, bounded header/namespace cleanup)"
  tags: ["plan", "4.0.79", "table_documentation", "top_50"]

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260316"
  last_verified_by: "cursor"
  next_action:
    - "Use this plan to coordinate 4.0.79 work; scope is remaining Top 50 only"
---
# file: Version 4.0.79 Plan — web_path: http://www.lupopedia.com/versions/4.0.79/PLAN

# Lupopedia 4.0.79 — Implementation Plan

**Opened:** 2026-03-16 (post–4.0.78 release and tag). Unfinished work from 4.0.78 carried forward. **4.0.78 is released;** completed 4.0.78 work is closed and not repeated here.

**Active scope (4.0.79):** Bounded to the **remaining Top 50 operational tables** and related cleanup. Domain priority: (1) core, (2) channels, (3) auth, (4) content, (5) analytics. Authority: [review_of_cursor_cleanup_and_top_50_table_plan.md](lupo-docs/status/review_of_cursor_cleanup_and_top_50_table_plan.md). The full table-doc corpus (351 docs) remains background backlog; success is measured against completing the Top 50 and bounded cleanup only.

---

## Phase 1 — Remaining Top 50 table documentation

- **1.1 Auth (remaining)**  
  - **lupo_auth_providers**, **lupo_auth_audit_log**, **lupo_banned_actors**, **lupo_bans_log** — Update to 4.0.79 LUPOPEDIA_HEADERS with Table Overview, "Where This Table Is Used," column docs from [install_new_lupopedia.sql](lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql) where present, indexes, relationships, doctrine notes. Zencoder pattern; namespace auth.

- **1.2 Content (remaining)**  
  - **lupo_content_versions**, **lupo_content_revisions**, **lupo_content_tags**, **lupo_content_collections** (or equivalent from install SQL) — Same pattern; namespace content.

- **1.3 Analytics (remaining)**  
  - **lupo_unified_log**, **lupo_analytics_campaign_vars**, **lupo_analytics_events** (or equivalent from install SQL) — Same pattern; namespace analytics; schema-source notes where table not in install.

- **1.4 Core / agent (remaining)**  
  - **lupo_agents**, **lupo_actor_channels** — Same pattern; namespace core/channels. Additional tables from install SQL to round out Top 50 as needed.

- **1.5 Pattern and truth**  
  - Schema truth: [install_new_lupopedia.sql](lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql) → TOON → table markdown. Pattern: [TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md](lupo-docs/status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md).

---

## Phase 2 — Bounded header and namespace cleanup

- **2.1 Top 50 header version**  
  - Update remaining Top 50 table docs to 4.0.79 headers where still not at current version. Use [table_doc_header_version_report_4_0_78.md](lupo-docs/status/table_doc_header_version_report_4_0_78.md) (or refreshed 4.0.79 report) for targeting; no blind mass-edit of full corpus.

- **2.1a Header doctrine + table-doc edges exception (docs + active table docs)**  
  - Update LUPOPEDIA HEADERS doctrine/spec/templates/examples so ordinary docs teach only stable, human-authored header blocks, with active table docs as the explicit exception where verbose edges are required. Populate grounded verbose table-doc edges in `lupo-docs/database/lupopedia/tables/active/*.md` when PHP/Python references exist. Track in: [header_doctrine_and_table_edges_update_4_0_79.md](lupo-docs/status/header_doctrine_and_table_edges_update_4_0_79.md).

- **2.2 Missing LUPOPEDIA_HEADERS**  
  - Add LUPOPEDIA_HEADERS to **TABLE_INDEX.md** (only doc missing headers per report). Use minimal valid block; artifact type appropriate for index file.

- **2.3 Namespace for Top 50**  
  - Ensure remaining Top 50 table docs have valid `namespace` per [LUPOPEDIA_HEADERS_FORMAT.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md). Use [namespace_audit_4_0_78.md](lupo-docs/status/namespace_audit_4_0_78.md) (or refreshed audit) for targeting.

- **2.4 Duplicate / FLARE cleanup**  
  - Only where it affects active Top 50 or high-priority table docs; do not expand to full corpus.

---

## Phase 3 — Optional (unchanged from 4.0.78)

- **3.1 Markdown-from-TOON automation (optional)**  
  - If desired: design/implement tool to generate or update table markdown from TOON/install SQL (structure only).

- **3.2 Repo-wide doc/schema validation (optional)**  
  - If desired: run or document validation that table docs align with current schema and list mismatches.

---

## Coordination

- **Lead agent:** Cursor (102). Scope: **remaining Top 50 operational tables** and bounded cleanup only. Completed 4.0.78 table docs (25 at 4.0.78) are historical; do not redo. Broader corpus (325+ docs requiring version update) is background backlog, not the active 4.0.79 target.
