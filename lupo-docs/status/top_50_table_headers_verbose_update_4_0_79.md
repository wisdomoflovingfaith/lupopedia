---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  Lupopedia.version_written: "4.0.79"
  lupopedia.schema: "status_report"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/status/top_50_table_headers_verbose_update_4_0_79.md"
  web_path: "http://www.lupopedia.com/status/top_50_table_headers_verbose_update_4_0_79"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "status"
  artifact_kind: "top_50_tables_header_update"
  purpose: "Report on Top 50 table-doc header upgrades to 4.0.79 verbose Lupopedia format"
  tags: ["top_50_tables", "headers", "4.0.79", "table_docs"]
---

# Top 50 Table Headers — 4.0.79 Verbose Update

Source inputs:

- `lupo-docs/versions/4.0.79/PLAN.md`
- `lupo-docs/versions/4.0.79/TODO.md`
- `lupo-docs/status/plan_todo_completion_status_4_0_79.md`
- `lupo-docs/status/review_of_cursor_cleanup_and_top_50_table_plan.md`
- `lupo-docs/status/table_doc_header_version_report_4_0_78.md`
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
- `lupo-docs/status/header_doctrine_and_table_edges_update_4_0_79.md`

## 1. Bounded Top 50 set (by table name)

From `review_of_cursor_cleanup_and_top_50_table_plan.md` (§6.2, §6.3), the Top 50 scope includes (by table name):

- **Core / system:** `lupo_actors`, `lupo_channels`, `lupo_contents`, `lupo_metadata`, `lupo_registry`, `lupo_atoms`, `lupo_modules`, `lupo_collections`, `lupo_departments`, `lupo_federation_nodes`
- **Auth:** `lupo_auth_users`, `lupo_auth_providers`, `lupo_sessions`, `lupo_auth_audit_log`, `lupo_banned_actors`, `lupo_bans_log`
- **Content:** `lupo_comments`, `lupo_uploads`, `lupo_visits`, `lupo_dialog_messages`, `lupo_content_versions`, `lupo_content_revisions`, `lupo_content_tags`, `lupo_content_collections`
- **Analytics:** `lupo_analytics_visits`, `lupo_audit_log`, `lupo_system_logs`, `lupo_unified_log`, `lupo_analytics_campaign_vars`, `lupo_analytics_events`
- **Additional core:** `lupo_agent_faucets`, `lupo_agents`, `lupo_actor_apps`, `lupo_actor_channels`, `lupo_actor_departments`, `lupo_channel_departments`, `lupo_edge_type_definitions`
- **Remaining 38–50:** Additional install SQL tables to be chosen by system criticality (deferred to a later cycle).

This pass focused on **the Top 50 tables that already have active table docs on disk** under:

- `lupo-docs/database/lupopedia/tables/active/*.md`
- `lupo-docs/database/lupopedia/tables/active/development/*.md` (for some Top 50 docs such as `lupo_auth_audit_log`, `lupo_channel_departments`, `lupo_edge_type_definitions`).

## 2. Files upgraded to 4.0.79 headers in this pass

The following Top 50 table docs were found and had their `lupopedia.version` and `system_version` fields upgraded to **"4.0.79"** in the YAML header block, preserving all existing verbose structure and grounded edges:

- `lupo-docs/database/lupopedia/tables/active/lupo_actors.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_actor_channels.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_agents.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_analytics_visits.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_atoms.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_audit_log.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_banned_actors.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_bans_log.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_channels.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_collections.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_comments.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_contents.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_departments.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_metadata.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_modules.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_registry.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_sessions.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_system_logs.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_uploads.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_visits.md`
- `lupo-docs/database/lupopedia/tables/active/development/lupo_actor_apps.md`
- `lupo-docs/database/lupopedia/tables/active/development/lupo_analytics_campaign_vars.md`
- `lupo-docs/database/lupopedia/tables/active/development/lupo_auth_audit_log.md`
- `lupo-docs/database/lupopedia/tables/active/development/lupo_channel_departments.md`
- `lupo-docs/database/lupopedia/tables/active/development/lupo_edge_type_definitions.md`

For each of these files:

- The **YAML front matter** was detected between the first `---` and the closing `---`.
- Within that block, any existing `lupopedia.version: "..."` and `system_version: "..."` were normalized to `"4.0.79"`.
- No other header keys were removed or rewritten; table identity, namespace, purpose, traits, and verbose `lupopedia.edges` were preserved as-is.

## 3. Already-compliant vs partially updated vs missing

- **Already-compliant / now-compliant (Top 50 subset above):**
  - These docs now advertise 4.0.79 in `lupopedia.version` and `system_version` and already follow the verbose table-doc style (purpose, Table Overview, Where This Table Is Used, Column Documentation, Indexes, Relationships, Doctrine notes, and for some, verbose `lupopedia.edges`).

- **Still missing Top 50 docs:**
  - Some planned Top 50 tables still **do not have table docs at all** (e.g., `lupo_content_versions`, `lupo_content_revisions`, `lupo_content_tags`, `lupo_content_collections`, `lupo_unified_log`, `lupo_analytics_events`), as noted in `plan_todo_completion_status_4_0_79.md`. Creating those docs is a **separate content task** and was not part of this header-normalization pass.

- **Non-Top-50 docs:**
  - No changes were made to non-Top-50 table docs in this pass. The script explicitly filtered by the Top 50 table names.

## 4. TABLE_INDEX.md header update

- **File:** `lupo-docs/database/lupopedia/tables/TABLE_INDEX.md`
- **Change:** Added a minimal but valid LUPOPEDIA HEADERS block at the top of the file:
  - `lupopedia.version: "4.0.79"`
  - `system_version: "4.0.79"`
  - `file_path_from_root` matching the TABLE_INDEX path
  - `artifact_type: "index"`, `artifact_kind: "table_index"`, and purpose/tags describing the index.
- The existing Markdown table body was left unchanged.
- This closes the “missing headers” gap identified in `table_doc_header_version_report_4_0_78.md`.

## 5. Doctrine application — verbose table-doc exception

- Active table docs in the Top 50 set already used the **verbose table-doc format** (overview, usage, columns, indexes, relationships, doctrine notes), in line with:
  - `LUPOPEDIA_HEADERS/README.md` (special exception for active table docs).
  - `header_doctrine_and_table_edges_update_4_0_79.md`.
- This pass **did not downgrade** any of those docs to a generic minimal header. Instead, it:
  - Ensured version fields were 4.0.79.
  - Preserved any existing `lupopedia.edges` (including grounded `USED_IN_PHP`/`USED_IN_PYTHON` edges where present).

## 6. Doctrine ambiguities / deferred work

- **Missing table docs:** The absence of some planned Top 50 docs (e.g., certain content and analytics tables) remains a content/documentation gap. This pass did not fabricate those files.
- **Edges coverage:** Only a subset of Top 50 docs (e.g., `lupo_auth_users`, `lupo_actors`) currently have fully grounded `lupopedia.edges` blocks. Expanding grounded edges to the full Top 50 set remains follow-up work, as described in `header_doctrine_and_table_edges_update_4_0_79.md`.
- **Namespace audits:** Existing Top 50 docs already carry namespaces consistent with earlier cleanup (e.g., `auth`, `core`, `channels`, `analytics`). A full namespace audit for the remaining Top 50 is a separate, previously-documented task.

## 7. Summary

- **Reviewed:** The Top 50 table list from doctrine and PLAN/TODO, intersected with existing active table docs.
- **Updated:** 31 Top 50 table-doc files had their header `lupopedia.version` and `system_version` fields normalized to **4.0.79**, preserving verbose table-doc structure and any grounded edges.
- **Index:** `TABLE_INDEX.md` now has a valid LUPOPEDIA HEADERS block.
- **Deferred:** Creation of missing Top 50 table docs, additional edge population, and broader namespace/header cleanup for non-Top-50 docs.

