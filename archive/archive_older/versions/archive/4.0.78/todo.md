---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.78/TODO.md"
  web_path: "[TODO](http://www.lupopedia.com/versions/4.0.78/TODO)"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: todo
  artifact_kind: version_todo
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
# file: Version 4.0.78 TODO — web_path: http://www.lupopedia.com/versions/4.0.78/TODO

# Version 4.0.78 — TODO List

## Status

- **State:** Open (post–4.0.77 release)
- **Theme:** Top 50 operational table documentation (bounded scope); header/version cleanup; optional automation
- **Source:** Scope narrowed from 161-table inventory to Top 50; see [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md) as priority authority. Pattern: [TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md](../../status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md).

---

## A. Table documentation (Priority 1)

1. **lupo_channels.md**
   - [x] Refreshed to 4.0.78 LUPOPEDIA_HEADERS; "Where This Table Is Used" added; columns aligned with install_new_lupopedia.sql (Cursor).

2. **lupo_actors.md**
   - [x] Refreshed to 4.0.78 LUPOPEDIA_HEADERS; "Where This Table Is Used" added; columns aligned with install SQL including workspace_path, php_namespace (Cursor).

---

## B. Table documentation (Priority 2)

3. **lupo_actor_apps.md**
   - [x] Updated to 4.0.78 LUPOPEDIA_HEADERS; Where This Table Is Used (workspace discovery, IDE agent apps, deployment, tooling, app discovery); columns aligned with install SQL (Cursor).

4. **lupo_channel_departments.md**
   - [x] Updated to 4.0.78 LUPOPEDIA_HEADERS; Where This Table Is Used (channel organization, department content, moderation, domain partitioning, UI grouping); relationship with lupo_channels and content routing documented (Cursor).

5. **lupo_edge_type_definitions.md**
   - [x] Updated to 4.0.78 LUPOPEDIA_HEADERS; Where This Table Is Used (knowledge graph, semantic edges, cross-document linking, schema definition, navigation graph); edge taxonomy and classification documented (Cursor).

---

## C. Table documentation (Priority 3)

6. **lupo_analytics_visits.md**
   - [x] Updated to 4.0.78 LUPOPEDIA_HEADERS; Where This Table Is Used (visit/session analytics, traffic, referrer/campaign, audience behavior, rollups); schema source note (not in install; see lupo_visits); columns from existing doc (Cursor).

7. **lupo_audit_log.md**
   - [x] Updated to 4.0.78 LUPOPEDIA_HEADERS; Where This Table Is Used (admin tracking, moderation, security, accountability, investigation); columns and indexes from install_new_lupopedia.sql; distinction from auth_audit_log and unified_log (Cursor).

8. **lupo_system_logs.md**
   - [x] Updated to 4.0.78 LUPOPEDIA_HEADERS; Where This Table Is Used (runtime diagnostics, troubleshooting, workers, incident review, observability); schema source note (not in install; see lupo_unified_log); columns from existing doc (Cursor).

---

## D. Header cleanup framework *(Phase 2)*

9. **Create header scan script**
   - [x] Added [scan_table_doc_headers.py](../../../scripts/scan_table_doc_headers.py) — scans tables dir, detects LUPOPEDIA_HEADERS, extracts version/file_path_from_root, reports `system_version` != 4.0.78 and anomalies (Cursor).

10. **Generate header report**
    - [x] Script produces [table_doc_header_version_report_4_0_78.md](../../status/table_doc_header_version_report_4_0_78.md) with summary, file list (File | Current Version | Required Version), and header anomalies (duplicate blocks, legacy FLARE, missing LUPOPEDIA_HEADERS) (Cursor).

11. **Top 50 table documentation**
   - [ ] Complete documentation for the **Top 50 operational tables** by domain priority: (1) core, (2) channels, (3) auth, (4) content, (5) analytics. Use [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md) as the priority and scope authority. Lower-value edge-case docs (index, handoff, planning-only) are out of scope for this phase. Broader table-doc inventory remains future backlog.

12. **Header version cleanup (Top 50 scope)**
    - [ ] Use [table_doc_header_version_report_4_0_78.md](../../status/table_doc_header_version_report_4_0_78.md) to update Top 50 table docs to 4.0.78 where still needed. Prefer when materially improving a doc; do not blind mass-edit. *(Bounded pass done: 17 at 4.0.78; Top 50 is the active target.)*

---

## D2. Namespace doctrine and cleanup *(done 4.0.78)*

15. **Namespace in doctrine**
    - [x] Formalized namespace in [LUPOPEDIA_HEADERS_FORMAT.md](../../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md) §2.2; [VALIDATORS_AND_TOOLING.md](../../doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md) §1.1; [LUPOPEDIA_HEADERS_PLAN.md](../../doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_PLAN.md); aligned [synthesized-framework.md](../../synthesized-framework.md) (Cursor).

16. **Namespace validator and fixtures**
    - [x] [validate_lupopedia_headers.php](../../../scripts/validate_lupopedia_headers.php) requires `namespace` on table docs, validates taxonomy; fixtures valid-namespace, invalid-namespace-value, missing-required-namespace, namespace-on-wrong-artifact; _validator_fixtures under tables/ (Cursor).

17. **Namespace audit**
    - [x] [audit_namespace_headers.py](../../../scripts/audit_namespace_headers.py) and [namespace_audit_4_0_78.md](../../status/namespace_audit_4_0_78.md); artifact-type policy (table required; others optional TBD) (Cursor).

18. **Systematic namespace cleanup**
    - [x] [apply_namespace_to_table_docs.py](../../../scripts/apply_namespace_to_table_docs.py) + manual normalization; Priority 1–3 and 178+ table docs updated; auth/channels/core/content/analytics/governance/integration/legacy applied (Cursor).

19. **Synthesized framework canonical migration**
    - [x] [synthesized-framework.md](../../synthesized-framework.md) migrated to canonical 4.0.78 LUPOPEDIA_HEADERS; historical quadrant fields preserved in `lupopedia.metadata`; obsolete header syntax removed; validator pass (Cursor).

20. **Compliance cleanup pass (linking, namespace, header version)**
    - [x] File-reference style: CHANGELOG, PLAN, TODO use Markdown links for file paths (Windsurf Option A). Namespace + 4.0.78 headers applied to lupo_sessions, lupo_contents, lupo_agent_faucets, lupo_comments, lupo_uploads, lupo_visits, lupo_dialog_messages; apply script fixed for Windows line endings. Reports refreshed; 136 table docs still missing namespace, 333 still requiring version update (Cursor).

21. **Top 50 reframing and first core batch**
    - [x] PLAN and TODO reframed to Top 50 operational tables; [review_of_cursor_cleanup_and_top_50_table_plan.md](../../status/review_of_cursor_cleanup_and_top_50_table_plan.md) as scope authority. Next batch completed: **lupo_metadata**, **lupo_atoms**, **lupo_collections**, **lupo_departments** — 4.0.78 headers, Table Overview, Where This Table Is Used, columns from install SQL, indexes, relationships, doctrine notes (Cursor). Reports refreshed: 21 at 4.0.78, 329 requiring update.

22. **Top 50 next batch (core, federation, auth)**
    - [x] **lupo_registry**, **lupo_modules**, **lupo_federation_nodes**, **lupo_auth_users** updated to 4.0.78 LUPOPEDIA_HEADERS; Zencoder pattern; schema from install SQL; namespace core/federation/auth; reserved-ID noted for lupo_auth_users (Cursor). Reports refreshed: 25 at 4.0.78, 325 requiring update.

---

## E. Optional

13. **Markdown-from-TOON automation**
    - [ ] If desired: design/implement tool to generate or update table markdown from TOON/install SQL (structure only).

14. **Repo-wide doc/schema validation**
    - [ ] If desired: run or document validation that table docs align with current schema and list mismatches.

---

*Update CHANGELOG.md and docs/version.md as 4.0.78 work progresses.*
